<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT p.*, pc.name AS category_name FROM products p LEFT JOIN product_categories pc ON p.category_id=pc.id WHERE p.id=?");
$stmt->execute([$id]);
$pr = $stmt->fetch();
if (!$pr) { setFlash('danger','Product not found.'); header('Location: '.BASE_URL.'/modules/products/index.php'); exit; }

$pageTitle = $pr['name'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Product Details</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1><?= e($pr['name']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/products/index.php">Products</a></li><li class="breadcrumb-item active"><?= e($pr['name']) ?></li></ol></nav>
  </div>
  <div>
    <?= statusBadge($pr['is_active'] ? 'active' : 'inactive') ?>
    <span class="badge <?= $pr['type']==='service'?'bg-info':'bg-secondary' ?> ms-1"><?= ucfirst($pr['type']) ?></span>
  </div>
  <a href="<?= BASE_URL ?>/modules/products/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="crm-card mb-3">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-info-circle text-primary me-2"></i>Product Information</h2></div>
      <div class="crm-card-body">
        <div class="row g-3">
          <div class="col-md-6"><div class="text-muted small mb-1">Product Name</div><div class="fw-semibold"><?= e($pr['name']) ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Brand</div><div class="fw-semibold"><?= e($pr['brand'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">SKU</div><div><?= e($pr['sku'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Category</div><div><?= e($pr['category_name'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Unit</div><div><?= e($pr['unit']) ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Quantity per Unit</div><div><?= rtrim(rtrim(number_format($pr['package_qty'] ?? 1, 2), '0'), '.') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Tax Rate</div><div><?= $pr['tax_rate'] ?>%</div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Type</div><div><?= ucfirst($pr['type']) ?></div></div>
          <?php if($pr['description']): ?>
          <div class="col-12"><div class="text-muted small mb-1">Description</div><div><?= e($pr['description']) ?></div></div>
          <?php endif; ?>
          <?php if($pr['image']): ?>
          <div class="col-12"><div class="text-muted small mb-1 mt-2">Product Image</div><div><img src="<?= BASE_URL . '/' . e($pr['image']) ?>" alt="Product Image" style="max-width: 100%; max-height: 200px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="crm-card mb-3">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-currency-rupee text-success me-2"></i>Pricing</h2></div>
      <div class="crm-card-body">
        <div class="mb-3">
          <div class="text-muted small mb-1">Purchase Price</div>
          <div class="h5 fw-bold"><?= formatCurrency($pr['purchase_price']) ?></div>
        </div>
        <div class="mb-3">
          <div class="text-muted small mb-1">Selling Price</div>
          <div class="h4 fw-bold text-success"><?= formatCurrency($pr['selling_price']) ?></div>
        </div>
        <div>
          <div class="text-muted small mb-1">Margin</div>
          <?php $margin = $pr['selling_price'] > 0 ? (($pr['selling_price'] - $pr['purchase_price']) / $pr['selling_price'] * 100) : 0; ?>
          <div class="fw-bold <?= $margin >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($margin, 1) ?>%</div>
        </div>
      </div>
    </div>
    <?php if($pr['type'] === 'product'): ?>
    <div class="crm-card">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-boxes text-warning me-2"></i>Stock</h2></div>
      <div class="crm-card-body">
        <div class="mb-3">
          <div class="text-muted small mb-1">Current Stock</div>
          <div class="h4 fw-bold <?= $pr['stock_qty'] <= $pr['min_stock'] ? 'text-danger' : 'text-success' ?>"><?= number_format($pr['stock_qty'], 2) ?> <?= e($pr['unit']) ?></div>
          <?php if($pr['stock_qty'] <= $pr['min_stock']): ?><div class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>Below minimum stock!</div><?php endif; ?>
        </div>
        <div>
          <div class="text-muted small mb-1">Min Stock Level</div>
          <div><?= number_format($pr['min_stock'], 2) ?> <?= e($pr['unit']) ?></div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <div class="d-grid gap-2 mt-3">
      <a href="<?= BASE_URL ?>/modules/products/edit.php?id=<?= $id ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Product</a>
      <a href="<?= BASE_URL ?>/modules/products/delete.php?id=<?= $id ?>" class="btn btn-outline-danger btn-sm" data-confirm="Delete this product?"><i class="bi bi-trash me-1"></i>Delete</a>
    </div>
  </div>
</div>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
