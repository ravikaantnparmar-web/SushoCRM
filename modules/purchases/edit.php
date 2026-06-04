<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM purchases WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('danger','Purchase not found.'); header('Location: '.BASE_URL.'/modules/purchases/index.php'); exit; }

$stmtItems = db()->prepare("SELECT * FROM purchase_items WHERE purchase_id=? ORDER BY id ASC");
$stmtItems->execute([$id]);
$existingItems = $stmtItems->fetchAll();

$pageTitle = 'Edit Purchase: ' . $p['purchase_number'];
$errors = [];
$vendors = db()->query("SELECT * FROM vendors ORDER BY name ASC")->fetchAll();
$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purchase_number = sanitize($_POST['purchase_number'] ?? '');
    $vendor_id = (int)($_POST['vendor_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? 'pending');
    $purchase_date = sanitize($_POST['purchase_date'] ?? date('Y-m-d'));
    $notes = sanitize($_POST['notes'] ?? '');
    $terms = sanitize($_POST['terms'] ?? '');
    $amount_paid = (float)($_POST['amount_paid'] ?? 0);

    $items = $_POST['items'] ?? [];

    if (!$vendor_id) $errors['vendor_id'] = 'Please select a vendor.';
    if (empty($items)) $errors['items'] = 'At least one item is required.';

    if (!$errors) {
        try {
            db()->beginTransaction();
            
            $subtotal = 0;
            $tax_amount = 0;
            
            foreach ($items as $item) {
                $qty = (float)($item['qty'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                $line_total = $qty * $price;
                $line_tax = $line_total * ($tax_rate / 100);
                $subtotal += $line_total;
                $tax_amount += $line_tax;
            }
            
            $total = $subtotal + $tax_amount;

            $old_balance = $p['total'] - $p['paid_amount'];
            $new_balance = $total - $amount_paid;
            $balance_diff = $new_balance - $old_balance;

            $stmt = db()->prepare("UPDATE purchases SET purchase_number=?, vendor_id=?, status=?, purchase_date=?, subtotal=?, tax_amount=?, total=?, paid_amount=?, notes=?, terms=? WHERE id=?");
            $stmt->execute([$purchase_number, $vendor_id, $status, $purchase_date?:null, $subtotal, $tax_amount, $total, $amount_paid, $notes, $terms, $id]);

            db()->prepare("DELETE FROM purchase_items WHERE purchase_id=?")->execute([$id]);

            $stmtItem = db()->prepare("INSERT INTO purchase_items (purchase_id, product_id, description, qty, unit, unit_price, tax_rate, tax_amount, line_total)
                VALUES (?,?,?,?,?,?,?,?,?)");
            
            foreach ($items as $i => $item) {
                $pid = (int)($item['product_id'] ?? 0) ?: null;
                $desc = sanitize($item['description'] ?? '');
                $qty = (float)($item['qty'] ?? 1);
                $unit = sanitize($item['unit'] ?? 'Nos');
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                $line_total = $qty * $price;
                $line_tax = $line_total * ($tax_rate / 100);
                
                $stmtItem->execute([$id, $pid, $desc, $qty, $unit, $price, $tax_rate, $line_tax, $line_total]);
            }
            
            if ($balance_diff != 0) {
                db()->prepare("UPDATE vendors SET outstanding_balance = outstanding_balance + ? WHERE id=?")->execute([$balance_diff, $vendor_id]);
            }

            db()->commit();
            logActivity('purchases','update',"Updated purchase order: $purchase_number",$id);
            setFlash('success',"Purchase '$purchase_number' updated.");
            header('Location: '.BASE_URL.'/modules/purchases/view.php?id='.$id);
            exit;
        } catch (Exception $e) {
            db()->rollBack();
            $errors['general'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Purchase</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Edit Purchase</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/purchases/index.php">Purchases</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/purchases/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" id="purchaseForm">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-bag me-2"></i>Purchase Details</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">PO Number</label>
            <input type="text" name="purchase_number" class="form-control" value="<?= e($p['purchase_number']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Vendor <span class="text-danger">*</span></label>
            <select name="vendor_id" class="form-select <?= isset($errors['vendor_id'])?'is-invalid':'' ?>">
              <option value="">— Select Vendor —</option>
              <?php foreach($vendors as $v): ?>
                <option value="<?= $v['id'] ?>" <?= $p['vendor_id']==$v['id']?'selected':'' ?>><?= e($v['name']) ?> <?= $v['company']?"({$v['company']})":'' ?></option>
              <?php endforeach; ?>
            </select>
            <?php if(isset($errors['vendor_id'])): ?><div class="invalid-feedback"><?= $errors['vendor_id'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="<?= e($p['purchase_date']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="pending" <?= $p['status']==='pending'?'selected':'' ?>>Pending</option>
              <option value="received" <?= $p['status']==='received'?'selected':'' ?>>Received</option>
              <option value="cancelled" <?= $p['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="crm-form-section">
        <div class="form-section-title d-flex justify-content-between align-items-center">
          <span><i class="bi bi-list-check me-2"></i>Items</span>
          <button type="button" class="btn btn-sm btn-primary" onclick="addItem()"><i class="bi bi-plus me-1"></i>Add Item</button>
        </div>
        <?php if(isset($errors['items'])): ?><div class="alert alert-danger small py-2"><?= $errors['items'] ?></div><?php endif; ?>
        <div class="line-items-wrapper">
          <table class="table table-bordered line-items-table" id="itemsTable">
            <thead>
              <tr class="bg-light">
                <th style="width:30%">Item / Description</th>
                <th style="width:12%">Qty</th>
                <th style="width:12%">Unit</th>
                <th style="width:15%">Purchase Price</th>
                <th style="width:12%">Tax %</th>
                <th style="width:15%">Total</th>
                <th style="width:5%"></th>
              </tr>
            </thead>
            <tbody>
              <!-- Initializing existing items using JS below -->
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="crm-form-section">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Notes (Internal)</label>
            <textarea name="notes" class="form-control" rows="3"><?= e($p['notes']) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Terms & Conditions</label>
            <textarea name="terms" class="form-control" rows="3"><?= e($p['terms']) ?></textarea>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="line-total-section mb-3">
        <h5 class="mb-3 text-dark fw-bold">Summary</h5>
        <div class="line-total-row-item">
          <span>Subtotal</span>
          <span id="calcSubtotal">₹ 0.00</span>
        </div>
        <div class="line-total-row-item">
          <span>Tax</span>
          <span id="calcTax">₹ 0.00</span>
        </div>
        <div class="line-total-row-item grand-total">
          <span>Total Amount</span>
          <span id="calcTotal">₹ 0.00</span>
        </div>
        <div class="line-total-row-item align-items-center mt-3 pt-3 border-top">
          <span class="text-success fw-bold">Amount Paid</span>
          <input type="number" name="amount_paid" id="amountPaid" class="form-control form-control-sm text-end text-success fw-bold" value="<?= e($p['paid_amount']) ?>" step="0.01" min="0" oninput="calculateTotal()" style="width:120px">
        </div>
        <div class="line-total-row-item mt-2">
          <span class="text-danger fw-bold">Balance Due</span>
          <span id="calcBalance" class="text-danger fw-bold">₹ 0.00</span>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Purchase</button>
        <a href="<?= BASE_URL ?>/modules/purchases/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
</div></div></div>

<script>
const products = <?= json_encode($products) ?>;
const existingItems = <?= json_encode($existingItems) ?>;
let itemIndex = 0;

function renderProductSelect(idx, selectedId = null) {
    let options = `<option value="">— Custom Item —</option>`;
    products.forEach(p => {
        const selected = selectedId == p.id ? 'selected' : '';
        options += `<option value="${p.id}" data-price="${p.purchase_price}" data-unit="${p.unit}" data-tax="${p.tax_rate}" ${selected}>${p.name}</option>`;
    });
    return options;
}

function addItem(data = {}) {
    const desc = data.description || '';
    const qty = data.qty || 1;
    const unit = data.unit || 'Nos';
    const price = data.unit_price || 0;
    const tax = data.tax_rate || 18;
    const pid = data.product_id || null;
    
    const tbody = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${itemIndex}][product_id]" class="form-select form-select-sm mb-1 product-select" onchange="productSelected(this, ${itemIndex})">
                ${renderProductSelect(itemIndex, pid)}
            </select>
            <input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm item-desc" placeholder="Description" value="${desc.replace(/"/g, '&quot;')}" required>
        </td>
        <td><input type="number" name="items[${itemIndex}][qty]" class="form-control form-control-sm item-qty text-end" value="${qty}" step="0.01" min="0.01" oninput="calculateTotal()" required></td>
        <td><input type="text" name="items[${itemIndex}][unit]" class="form-control form-control-sm item-unit" value="${unit}"></td>
        <td><input type="number" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm item-price text-end" value="${price}" step="0.01" min="0" oninput="calculateTotal()" required></td>
        <td><input type="number" name="items[${itemIndex}][tax_rate]" class="form-control form-control-sm item-tax text-end" value="${tax}" step="0.01" min="0" oninput="calculateTotal()"></td>
        <td><input type="text" class="form-control form-control-sm item-total text-end bg-light" readonly value="0.00"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-icon" onclick="removeItem(this)"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    itemIndex++;
    calculateTotal();
}

function removeItem(btn) {
    btn.closest('tr').remove();
    calculateTotal();
}

function productSelected(select, idx) {
    const tr = select.closest('tr');
    const option = select.options[select.selectedIndex];
    if (option.value) {
        tr.querySelector('.item-desc').value = option.text;
        tr.querySelector('.item-price').value = option.dataset.price;
        tr.querySelector('.item-unit').value = option.dataset.unit;
        tr.querySelector('.item-tax').value = option.dataset.tax;
    }
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        const price = parseFloat(tr.querySelector('.item-price').value) || 0;
        const lineTotal = qty * price;
        
        tr.querySelector('.item-total').value = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    
    const discVal = parseFloat(document.getElementById('discountValue').value) || 0;
    const discAmount = subtotal * (discVal / 100);
    const discountedSubtotal = subtotal - discAmount;
    
    let totalTax = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        const price = parseFloat(tr.querySelector('.item-price').value) || 0;
        const taxRate = parseFloat(tr.querySelector('.item-tax').value) || 0;
        
        const lineTotal = qty * price;
        const discountedLineTotal = lineTotal * (1 - (discVal / 100));
        const lineTax = discountedLineTotal * (taxRate / 100);
        
        totalTax += lineTax;
    });
    
    const grandTotal = discountedSubtotal + totalTax;
    
    document.getElementById('calcSubtotal').textContent = '₹ ' + subtotal.toFixed(2);
    if(document.getElementById('calcDiscount')) {
        document.getElementById('calcDiscount').textContent = '-₹ ' + discAmount.toFixed(2);
    }
    document.getElementById('calcTax').textContent = '₹ ' + totalTax.toFixed(2);
    document.getElementById('calcTotal').textContent = '₹ ' + grandTotal.toFixed(2);
    
    if(document.getElementById('calcBalance')) {
        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        const balance = grandTotal - amountPaid;
        document.getElementById('calcBalance').textContent = '₹ ' + balance.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (existingItems.length > 0) {
        existingItems.forEach(item => addItem(item));
    } else {
        addItem();
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js?v=<?= time() ?>"></script>
</body></html>
