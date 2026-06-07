<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('invoices', 'edit');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM invoices WHERE id=?");
$stmt->execute([$id]);
$inv = $stmt->fetch();
if (!$inv) { setFlash('danger','Invoice not found.'); header('Location: '.BASE_URL.'/modules/invoices/index.php'); exit; }

$stmtItems = db()->prepare("SELECT * FROM invoice_items WHERE invoice_id=? ORDER BY sort_order ASC");
$stmtItems->execute([$id]);
$existingItems = $stmtItems->fetchAll();

$pageTitle = 'Edit Invoice: ' . $inv['invoice_number'];
$errors = [];
$customers = getAllCustomers();
$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_number = sanitize($_POST['invoice_number'] ?? '');
    $customer_id = (int)($_POST['customer_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? 'draft');
    $due_date = sanitize($_POST['due_date'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $terms = sanitize($_POST['terms'] ?? '');
    $discount_type = sanitize($_POST['discount_type'] ?? 'fixed');
    $discount_value = (float)($_POST['discount_value'] ?? 0);
    $amount_paid = (float)($_POST['amount_paid'] ?? 0);

    $items = $_POST['items'] ?? [];

    if (!$customer_id) $errors['customer_id'] = 'Please select a customer.';
    if (empty($items)) $errors['items'] = 'At least one item is required.';

    if (!$errors) {
        try {
            db()->beginTransaction();
            
            $subtotal = 0;
            
            foreach ($items as $item) {
                $qty = (float)($item['qty'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $subtotal += ($qty * $price);
            }
            $discount_ratio = $subtotal > 0 ? ($discount_amount / $subtotal) : 0;
            
            $tax_amount = 0;
            foreach ($items as $item) {
                $qty = (float)($item['qty'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                
                $line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);
                
                $tax_amount += $line_tax;
            }
            $total = ($subtotal - $discount_amount) + $tax_amount;

            // Auto update status based on paid amount if not draft/cancelled
            if ($status !== 'draft' && $status !== 'cancelled') {
                if ($amount_paid >= $total) $status = 'paid';
                elseif ($amount_paid > 0)   $status = 'partial';
                elseif ($due_date && strtotime($due_date) < time()) $status = 'overdue';
                else $status = 'sent';
            }
            
            $balance_due = max(0, $total - $amount_paid);

            $stmt = db()->prepare("UPDATE invoices SET invoice_number=?, customer_id=?, status=?, due_date=?, subtotal=?, discount_type=?, discount_value=?, discount_amount=?, tax_amount=?, total=?, paid_amount=?, balance_due=?, notes=?, terms=? WHERE id=?");
            $stmt->execute([$invoice_number, $customer_id, $status, $due_date?:null, $subtotal, $discount_type, $discount_value, $discount_amount, $tax_amount, $total, $amount_paid, $balance_due, $notes, $terms, $id]);

            db()->prepare("DELETE FROM invoice_items WHERE invoice_id=?")->execute([$id]);

            $stmtItem = db()->prepare("INSERT INTO invoice_items (invoice_id, product_id, description, qty, unit, unit_price, tax_rate, tax_amount, discount, line_total, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            foreach ($items as $i => $item) {
                $pid = (int)($item['product_id'] ?? 0) ?: null;
                $desc = sanitize($item['description'] ?? '');
                $qty = (float)($item['qty'] ?? 1);
                $unit = sanitize($item['unit'] ?? 'Nos');
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                $line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);
                $stmtItem->execute([$id, $pid, $desc, $qty, $unit, $price, $tax_rate, $line_tax, 0, $line_total, $i]);
            }
            
            db()->commit();
            logActivity('invoices','update',"Updated invoice: $invoice_number",$id);
            setFlash('success',"Invoice '$invoice_number' updated.");
            header('Location: '.BASE_URL.'/modules/invoices/view.php?id='.$id);
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
  <div class="topbar-title">Edit Invoice</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Edit Invoice</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/invoices/index.php">Invoices</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/invoices/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" id="invoiceForm">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-receipt me-2"></i>Invoice Details</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Invoice Number</label>
            <input type="text" name="invoice_number" class="form-control" value="<?= e($inv['invoice_number']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Customer <span class="text-danger">*</span></label>
            <select name="customer_id" class="form-select <?= isset($errors['customer_id'])?'is-invalid':'' ?>">
              <option value="">— Select Customer —</option>
              <?php foreach($customers as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $inv['customer_id']==$c['id']?'selected':'' ?>><?= e($c['name']) ?> <?= $c['company']?"({$c['company']})":'' ?></option>
              <?php endforeach; ?>
            </select>
            <?php if(isset($errors['customer_id'])): ?><div class="invalid-feedback"><?= $errors['customer_id'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?= e($inv['due_date']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <?php foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s): ?>
                <option value="<?= $s ?>" <?= $inv['status']===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="form-text text-muted small">Status will auto-update if amount paid covers total.</div>
          </div>
        </div>
      </div>
      
      <div class="crm-form-section">
        <div class="form-section-title d-flex justify-content-between align-items-center">
          <span><i class="bi bi-list-check me-2"></i>Line Items</span>
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
                <th style="width:15%">Unit Price</th>
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
            <label class="form-label">Notes (Internal/Customer)</label>
            <textarea name="notes" class="form-control" rows="4"><?= e($inv['notes'] ?? '') ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Terms & Conditions</label>
            <textarea name="terms" class="form-control" rows="4"><?= e($inv['terms']) ?></textarea>
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
        <div class="line-total-row-item align-items-center mb-2">
          <span>Discount (%)</span>
          <div class="input-group input-group-sm" style="width:140px">
              <input type="number" name="discount_value" id="discountValue" class="form-control text-end" value="<?= e($inv['discount_value']) ?>" step="0.01" min="0" oninput="calculateTotal()">
              <span class="input-group-text">%</span>
              <input type="hidden" name="discount_type" value="percent">
            </div>
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
          <input type="number" name="amount_paid" id="amountPaid" class="form-control form-control-sm text-end text-success fw-bold" value="<?= e($inv['paid_amount']) ?>" step="0.01" min="0" oninput="calculateTotal()" style="width:120px">
        </div>
        <div class="line-total-row-item mt-2">
          <span class="text-danger fw-bold">Balance Due</span>
          <span id="calcBalance" class="text-danger fw-bold">₹ 0.00</span>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Invoice</button>
        <a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
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
        options += `<option value="${p.id}" data-price="${p.selling_price}" data-unit="${p.unit}" data-tax="${p.tax_rate}" ${selected}>${p.name}</option>`;
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
