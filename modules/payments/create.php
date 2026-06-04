<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Record Payment';
$errors    = [];

// Pre-select invoice if passed via GET (e.g. from invoice view page)
$preInvoiceId = (int)($_GET['invoice_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id   = (int)($_POST['invoice_id'] ?? 0);
    $amount       = (float)($_POST['amount'] ?? 0);
    $payment_date = sanitize($_POST['payment_date'] ?? date('Y-m-d'));
    $method       = sanitize($_POST['method'] ?? 'cash');
    $reference    = sanitize($_POST['reference'] ?? '');
    $notes        = sanitize($_POST['notes'] ?? '');

    if (!$invoice_id) $errors['invoice_id'] = 'Please select an invoice.';
    if ($amount <= 0) $errors['amount']      = 'Valid amount is required.';
    if (!$payment_date) $errors['payment_date'] = 'Payment date is required.';

    if (!$errors) {
        // Fetch invoice to get customer_id & validate balance
        $inv = db()->prepare("SELECT id, customer_id, total, paid_amount, balance_due, status FROM invoices WHERE id = ?");
        $inv->execute([$invoice_id]);
        $invoice = $inv->fetch();

        if (!$invoice) {
            $errors['invoice_id'] = 'Selected invoice not found.';
        } elseif (in_array($invoice['status'], ['paid', 'cancelled'])) {
            $errors['invoice_id'] = 'This invoice is already ' . $invoice['status'] . '.';
        } else {
            // Insert payment
            $stmt = db()->prepare("INSERT INTO payments (invoice_id, customer_id, amount, payment_date, method, reference, notes, created_by) VALUES (?,?,?,?,?,?,?,?)");
            if ($stmt->execute([$invoice_id, $invoice['customer_id'], $amount, $payment_date, $method, $reference, $notes, $_SESSION['user_id']])) {
                $paymentId = db()->lastInsertId();

                // Update invoice paid_amount, balance_due & status
                $newPaid    = $invoice['paid_amount'] + $amount;
                $newBalance = max(0, $invoice['total'] - $newPaid);
                $newStatus  = ($newBalance <= 0) ? 'paid' : (($newPaid > 0) ? 'partial' : $invoice['status']);

                db()->prepare("UPDATE invoices SET paid_amount=?, balance_due=?, status=? WHERE id=?")
                    ->execute([$newPaid, $newBalance, $newStatus, $invoice_id]);

                logActivity('payments', 'create', "Recorded payment of " . formatCurrency($amount) . " for invoice #{$invoice['id']}", $paymentId);
                setFlash('success', "Payment of " . formatCurrency($amount) . " recorded successfully!");
                header('Location: ' . BASE_URL . '/modules/payments/index.php');
                exit;
            } else {
                $errors['general'] = 'Failed to record payment. Please try again.';
            }
        }
    }
}

// Load invoices that are not fully paid (for dropdown)
$invoices = db()->query("
    SELECT i.id, i.invoice_number, i.total, i.paid_amount, i.balance_due, i.status, c.company_name AS customer_name
    FROM invoices i
    JOIN customers c ON i.customer_id = c.id
    WHERE i.status NOT IN ('paid','cancelled')
    ORDER BY i.invoice_number DESC
")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Record Payment</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Record Payment</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/payments/index.php">Payments</a></li>
      <li class="breadcrumb-item active">Record</li>
    </ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/payments/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
  <!-- Form -->
  <div class="col-lg-7">
    <div class="crm-card">
      <div class="crm-card-body p-4">
        <form method="POST" id="paymentForm">

          <!-- Invoice Selector -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Invoice <span class="text-danger">*</span></label>
            <select name="invoice_id" id="invoiceSelect" class="form-select <?= isset($errors['invoice_id'])?'is-invalid':'' ?>" onchange="loadInvoiceDetails(this)" required>
              <option value="">— Select Invoice —</option>
              <?php foreach($invoices as $inv): ?>
                <option value="<?= $inv['id'] ?>"
                  data-customer="<?= e($inv['customer_name']) ?>"
                  data-total="<?= $inv['total'] ?>"
                  data-paid="<?= $inv['paid_amount'] ?>"
                  data-balance="<?= $inv['balance_due'] ?>"
                  data-status="<?= $inv['status'] ?>"
                  <?= (($_POST['invoice_id'] ?? $preInvoiceId) == $inv['id']) ? 'selected' : '' ?>>
                  <?= e($inv['invoice_number']) ?> — <?= e($inv['customer_name']) ?> — Balance: <?= formatCurrency($inv['balance_due']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if(isset($errors['invoice_id'])): ?><div class="invalid-feedback"><?= $errors['invoice_id'] ?></div><?php endif; ?>
          </div>

          <!-- Invoice Summary Card (shown after selection) -->
          <div id="invoiceSummary" class="mb-3 p-3 rounded-2 border d-none" style="background:#f8fbff;">
            <div class="row g-2 small">
              <div class="col-6"><span class="text-muted">Customer</span><div class="fw-semibold" id="sumCustomer">—</div></div>
              <div class="col-6"><span class="text-muted">Invoice Total</span><div class="fw-semibold" id="sumTotal">—</div></div>
              <div class="col-6"><span class="text-muted">Already Paid</span><div class="fw-semibold text-success" id="sumPaid">—</div></div>
              <div class="col-6"><span class="text-muted">Balance Due</span><div class="fw-bold text-danger fs-6" id="sumBalance">—</div></div>
            </div>
          </div>

          <div class="row g-3">
            <!-- Amount -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Amount Received <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="amount" id="amountInput" class="form-control <?= isset($errors['amount'])?'is-invalid':'' ?>"
                       value="<?= e($_POST['amount'] ?? '') ?>" step="0.01" min="0.01" placeholder="0.00" required>
                <?php if(isset($errors['amount'])): ?><div class="invalid-feedback"><?= $errors['amount'] ?></div><?php endif; ?>
              </div>
              <div class="mt-1">
                <a href="#" class="small text-primary" onclick="fillBalance(); return false;"><i class="bi bi-lightning-fill me-1"></i>Fill full balance</a>
              </div>
            </div>

            <!-- Date -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
              <input type="date" name="payment_date" class="form-control <?= isset($errors['payment_date'])?'is-invalid':'' ?>"
                     value="<?= e($_POST['payment_date'] ?? date('Y-m-d')) ?>" required>
              <?php if(isset($errors['payment_date'])): ?><div class="invalid-feedback"><?= $errors['payment_date'] ?></div><?php endif; ?>
            </div>

            <!-- Method -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
              <select name="method" class="form-select" required>
                <?php
                $methods = ['cash'=>'💵 Cash','bank_transfer'=>'🏦 Bank Transfer','cheque'=>'📄 Cheque','upi'=>'📱 UPI','card'=>'💳 Card','other'=>'Other'];
                foreach ($methods as $val => $label):
                  $sel = (($_POST['method'] ?? 'cash') === $val) ? 'selected' : '';
                ?>
                <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Reference -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Transaction / Ref. No.</label>
              <input type="text" name="reference" class="form-control" value="<?= e($_POST['reference'] ?? '') ?>"
                     placeholder="Cheque no., UTR, TXN ID...">
            </div>

            <!-- Notes -->
            <div class="col-12">
              <label class="form-label fw-semibold">Notes</label>
              <textarea name="notes" class="form-control" rows="3" placeholder="Optional remarks..."><?= e($_POST['notes'] ?? '') ?></textarea>
            </div>
          </div>

          <hr class="my-4">
          <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/modules/payments/index.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Record Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Help Panel -->
  <div class="col-lg-5">
    <div class="crm-card mb-3">
      <div class="crm-card-body p-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>How It Works</h6>
        <ul class="list-unstyled small text-muted mb-0" style="line-height:1.9;">
          <li><i class="bi bi-1-circle-fill text-primary me-2"></i>Select the invoice you're receiving payment for</li>
          <li><i class="bi bi-2-circle-fill text-primary me-2"></i>Enter the amount received (can be partial)</li>
          <li><i class="bi bi-3-circle-fill text-primary me-2"></i>Choose the payment method</li>
          <li><i class="bi bi-4-circle-fill text-primary me-2"></i>Add a reference number for traceability</li>
          <li><i class="bi bi-check-circle-fill text-success me-2"></i>Invoice status auto-updates to <strong>Partial</strong> or <strong>Paid</strong></li>
        </ul>
      </div>
    </div>

    <div class="crm-card">
      <div class="crm-card-body p-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-credit-card text-primary me-2"></i>Payment Methods</h6>
        <div class="row g-2">
          <?php foreach(['💵 Cash','🏦 Bank Transfer','📄 Cheque','📱 UPI / NEFT / RTGS','💳 Card'] as $m): ?>
            <div class="col-6">
              <div class="p-2 rounded border text-center small"><?= $m ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div><!-- row -->
</div></div>

<script>
let currentBalance = 0;

function formatCurrency(val) {
  return '₹' + parseFloat(val).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
}

function loadInvoiceDetails(sel) {
  const opt = sel.options[sel.selectedIndex];
  const summary = document.getElementById('invoiceSummary');
  if (!sel.value || sel.value === '') { summary.classList.add('d-none'); return; }

  document.getElementById('sumCustomer').textContent = opt.dataset.customer || '—';
  document.getElementById('sumTotal').textContent    = formatCurrency(opt.dataset.total || 0);
  document.getElementById('sumPaid').textContent     = formatCurrency(opt.dataset.paid || 0);
  document.getElementById('sumBalance').textContent  = formatCurrency(opt.dataset.balance || 0);
  currentBalance = parseFloat(opt.dataset.balance || 0);
  summary.classList.remove('d-none');
}

function fillBalance() {
  if (currentBalance > 0) {
    document.getElementById('amountInput').value = currentBalance.toFixed(2);
  }
}

// Auto-load on page load if invoice was pre-selected
window.addEventListener('DOMContentLoaded', () => {
  const sel = document.getElementById('invoiceSelect');
  if (sel.value) loadInvoiceDetails(sel);
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
