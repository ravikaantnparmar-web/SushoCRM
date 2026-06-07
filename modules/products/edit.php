<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('products', 'edit');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT p.*, pc.name AS category_name FROM products p LEFT JOIN product_categories pc ON p.category_id=pc.id WHERE p.id=?");
$stmt->execute([$id]);
$pr = $stmt->fetch();
if (!$pr) { setFlash('danger','Product not found.'); header('Location: '.BASE_URL.'/modules/products/index.php'); exit; }

$pageTitle = 'Edit: ' . $pr['name'];
$errors = [];
$categories = db()->query("SELECT * FROM product_categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = sanitize($_POST['name'] ?? '');
    $brand          = sanitize($_POST['brand'] ?? '');
    $sku            = sanitize($_POST['sku'] ?? '');
    $type           = sanitize($_POST['type'] ?? 'product');
    $category_id    = (int)($_POST['category_id'] ?? 0) ?: null;
    $unit           = sanitize($_POST['unit'] ?? 'Nos');
    $package_qty    = (float)($_POST['package_qty'] ?? 1);
    $description    = sanitize($_POST['description'] ?? '');
    $purchase_price = (float)($_POST['purchase_price'] ?? 0);
    $selling_price  = (float)($_POST['selling_price'] ?? 0);
    $tax_rate       = (float)($_POST['tax_rate'] ?? 18);
    $stock_qty      = (float)($_POST['stock_qty'] ?? 0);
    $min_stock      = (float)($_POST['min_stock'] ?? 0);
    $is_active      = isset($_POST['is_active']) ? 1 : 0;

    $imagePath = $pr['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $newImage = uploadFile($_FILES['image'], 'products', ALLOWED_IMAGES);
        if ($newImage) {
            $imagePath = $newImage;
        } else {
            $errors['image'] = 'Failed to upload new image.';
        }
    }

    if (!$name) $errors['name'] = 'Product name is required.';
    if ($sku && $sku !== $pr['sku']) {
        $exists = db()->prepare("SELECT COUNT(*) FROM products WHERE sku=? AND id!=?");
        $exists->execute([$sku,$id]);
        if ($exists->fetchColumn()) $errors['sku'] = 'SKU already exists.';
    }

    if (!$errors) {
        db()->prepare("UPDATE products SET name=?,brand=?,sku=?,type=?,category_id=?,unit=?,package_qty=?,description=?,purchase_price=?,selling_price=?,tax_rate=?,stock_qty=?,min_stock=?,image=?,is_active=? WHERE id=?")
            ->execute([$name,$brand,$sku?:null,$type,$category_id,$unit,$package_qty,$description,$purchase_price,$selling_price,$tax_rate,$stock_qty,$min_stock,$imagePath,$is_active,$id]);
        logActivity('products','update',"Updated product: $name",$id);
        setFlash('success',"Product '$name' updated.");
        header('Location: '.BASE_URL.'/modules/products/view.php?id='.$id);
        exit;
    }
    $pr = array_merge($pr, $_POST);
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Product</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Edit Product</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/products/index.php">Products</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/products/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" enctype="multipart/form-data">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-box-seam me-2"></i>Product Details</div>
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= e($pr['name']) ?>">
            <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?= $errors['name'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label">Type</label>
            <select name="type" id="product_type" class="form-select">
              <option value="product" <?= $pr['type']==='product'?'selected':'' ?>>Product</option>
              <option value="service" <?= $pr['type']==='service'?'selected':'' ?>>Service</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">SKU / Code</label>
            <input type="text" name="sku" class="form-control <?= isset($errors['sku'])?'is-invalid':'' ?>" value="<?= e($pr['sku']??'') ?>">
            <?php if(isset($errors['sku'])): ?><div class="invalid-feedback"><?= $errors['sku'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="<?= e($pr['brand']??'') ?>" placeholder="e.g. Samsung">
          </div>
          <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0">Category</label>
              <small class="text-muted" style="font-size: 10px;">Press <b>F2</b> to Add Record</small>
            </div>
            <select name="category_id" class="form-select">
              <option value="">— Uncategorized —</option>
              <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($pr['category_id']??'')==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Unit</label>
            <select name="unit" class="form-select">
              <?php foreach(['Nos','Pcs','Kg','Litre','Meter','Box','Set','Hour','Day','Month'] as $u): ?>
                <option value="<?= $u ?>" <?= ($pr['unit']??'Nos')===$u?'selected':'' ?>><?= $u ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Quantity per Unit</label>
            <input type="number" name="package_qty" class="form-control" value="<?= e($pr['package_qty']??'1') ?>" step="0.01" min="0.01">
          </div>
          <div class="col-md-4">
            <label class="form-label">Tax Rate (%)</label>
            <select name="tax_rate" class="form-select">
              <?php foreach([0,5,12,18,28] as $t): ?>
                <option value="<?= $t ?>" <?= ($pr['tax_rate']??18)==$t?'selected':'' ?>><?= $t ?>% <?= $t>0?'GST':'' ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= e($pr['description']??'') ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Product Image</label>
            <?php if($pr['image']): ?>
              <div class="mb-2">
                <img src="<?= BASE_URL . '/' . e($pr['image']) ?>" alt="Product Image" style="max-height: 100px; border-radius: 4px;">
              </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="form-text">Leave blank to keep current image.</div>
            <?php if(isset($errors['image'])): ?><div class="invalid-feedback d-block"><?= $errors['image'] ?></div><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-currency-rupee me-2"></i>Pricing</div>
        <div class="mb-3">
          <label class="form-label">Purchase Price (₹)</label>
          <input type="number" id="purchase_price" name="purchase_price" class="form-control" value="<?= e($pr['purchase_price']??'0') ?>" step="0.01" min="0">
        </div>
        <div class="mb-3">
          <label class="form-label text-primary"><i class="bi bi-percent me-1"></i>Markup / Margin (%)</label>
          <input type="number" id="markup_percentage" class="form-control" placeholder="e.g. 15 for 15%" step="0.01" min="0">
          <div class="form-text" style="font-size: 11px;">Enter a % to automatically calculate the Selling Price</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Selling Price (₹) <span class="text-danger">*</span></label>
          <input type="number" id="selling_price" name="selling_price" class="form-control" value="<?= e($pr['selling_price']??'0') ?>" step="0.01" min="0">
        </div>
      </div>
      <div class="crm-form-section" id="inventorySection">
        <div class="form-section-title"><i class="bi bi-boxes me-2"></i>Inventory</div>
        <div class="mb-3">
          <label class="form-label">Product Quantity (Stock)</label>
          <input type="number" name="stock_qty" class="form-control" value="<?= e($pr['stock_qty']??'0') ?>" step="0.01" min="0">
        </div>
        <div class="mb-3">
          <label class="form-label">Minimum Stock Level</label>
          <input type="number" name="min_stock" class="form-control" value="<?= e($pr['min_stock']??'0') ?>" step="0.01" min="0">
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" <?= $pr['is_active']?'checked':'' ?>>
          <label class="form-check-label" for="isActive">Active</label>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Product</button>
        <a href="<?= BASE_URL ?>/modules/products/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
</div></div></div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #212529 0%, #343a40 100%);">
        <h6 class="modal-title d-flex align-items-center text-white mb-0" style="font-size: 14px;">
          <i class="bi bi-plus-circle-fill me-2 text-primary"></i> Add New Category
        </h6>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <div class="mb-2">
          <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px; letter-spacing: 0.5px;">CATEGORY NAME <span class="text-danger">*</span></label>
          <input type="text" id="new_category_name" class="form-control form-control-sm" placeholder="e.g. Computer Accessories" style="font-size: 13px;">
        </div>
        <div class="mb-0">
          <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px; letter-spacing: 0.5px;">DESCRIPTION (OPTIONAL)</label>
          <textarea id="new_category_description" class="form-control form-control-sm" rows="2" placeholder="Brief description..." style="font-size: 13px; resize: none;"></textarea>
        </div>
      </div>
      <div class="modal-footer border-0 px-4 pb-3 pt-0 gap-2">
        <button type="button" class="btn btn-sm btn-light text-muted px-3" data-bs-dismiss="modal" style="font-size: 12px;">Cancel</button>
        <button type="button" id="saveCategoryBtn" class="btn btn-sm btn-dark px-4 fw-semibold flex-grow-1" style="font-size: 12px;">
          <i class="bi bi-check-lg me-1"></i> Save Category
        </button>
      </div>
    </div>
  </div>
</div>

<?php 
$extraScripts = '
<script>
document.addEventListener("keydown", function(e) {
  if (e.key === "F2") {
    e.preventDefault();
    const addCategoryModal = new bootstrap.Modal(document.getElementById("addCategoryModal"));
    addCategoryModal.show();
    setTimeout(() => document.getElementById("new_category_name").focus(), 500);
  }
});

const purchasePrice = document.getElementById("purchase_price");
const markupPercent = document.getElementById("markup_percentage");
const sellingPrice = document.getElementById("selling_price");

// Auto-fill markup if prices exist
if (purchasePrice && sellingPrice && markupPercent) {
  const pp = parseFloat(purchasePrice.value) || 0;
  const sp = parseFloat(sellingPrice.value) || 0;
  if (pp > 0 && sp >= pp) {
    const m = ((sp - pp) / pp) * 100;
    markupPercent.value = m.toFixed(2);
  }
}

function calculateSellingPrice() {
  const ppVal = parseFloat(purchasePrice.value) || 0;
  const mpVal = parseFloat(markupPercent.value);
  if (!isNaN(mpVal) && ppVal > 0) {
    const spVal = ppVal + (ppVal * mpVal / 100);
    sellingPrice.value = spVal.toFixed(2);
  }
}

if(purchasePrice) purchasePrice.addEventListener("input", calculateSellingPrice);
if(markupPercent) markupPercent.addEventListener("input", calculateSellingPrice);

const productType = document.getElementById("product_type");
const inventorySection = document.getElementById("inventorySection");

function toggleInventory() {
  if (productType && inventorySection) {
    if (productType.value === "service") {
      inventorySection.style.display = "none";
    } else {
      inventorySection.style.display = "block";
    }
  }
}
if(productType) {
  productType.addEventListener("change", toggleInventory);
  toggleInventory();
}

document.getElementById("saveCategoryBtn").addEventListener("click", function() {
  const name = document.getElementById("new_category_name").value;
  const desc = document.getElementById("new_category_description").value;
  const btn = this;

  if (!name) {
    alert("Category name is required.");
    return;
  }

  btn.disabled = true;
  btn.innerHTML = \'<span class="spinner-border spinner-border-sm me-1"></span>Saving...\';

  const formData = new FormData();
  formData.append("name", name);
  formData.append("description", desc);

  fetch("ajax_add_category.php", {
    method: "POST",
    body: formData,
    headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      const select = document.querySelector(\'select[name="category_id"]\');
      const option = new Option(data.name, data.id, true, true);
      select.add(option);
      bootstrap.Modal.getInstance(document.getElementById("addCategoryModal")).hide();
      document.getElementById("new_category_name").value = "";
      document.getElementById("new_category_description").value = "";
    } else {
      alert(data.message || "Error adding category.");
    }
  })
  .catch(err => {
    console.error(err);
    alert("An error occurred.");
  })
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = \'<i class="bi bi-check-lg me-1"></i> Save Category\';
  });
});
</script>';
include __DIR__ . '/../../includes/footer.php'; 
?>

