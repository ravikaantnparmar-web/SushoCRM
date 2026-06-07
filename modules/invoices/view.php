<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('invoices', 'view');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT i.*, 
    (SELECT cnt.name FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS customer_name, 
    c.company_name AS customer_company, 
    c.address_line1 AS address, 
    c.city, c.state, c.pincode, 
    c.company_email AS email, 
    (SELECT cnt.mobile FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS phone, 
    u.name AS created_by_name 
    FROM invoices i 
    JOIN customers c ON i.customer_id = c.id 
    LEFT JOIN users u ON i.created_by = u.id 
    WHERE i.id=?");
$stmt->execute([$id]);
$inv = $stmt->fetch();
if (!$inv) { setFlash('danger','Invoice not found.'); header('Location: '.BASE_URL.'/modules/invoices/index.php'); exit; }

$stmtItems = db()->prepare("SELECT * FROM invoice_items WHERE invoice_id=? ORDER BY sort_order ASC");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

$pageTitle = 'Invoice: ' . $inv['invoice_number'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">View Invoice</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header d-print-none">
  <div class="page-header-left"><h1><?= e($inv['invoice_number']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/invoices/index.php">Invoices</a></li><li class="breadcrumb-item active"><?= e($inv['invoice_number']) ?></li></ol></nav>
  </div>
  <div><?= statusBadge($inv['status']) ?></div>
</div>

<div class="crm-card print-container">
  <div class="crm-card-body p-4 p-md-5">
    <!-- Header -->
    <div class="row mb-5 border-bottom pb-4">
      <div class="col-sm-6">
        <h2 class="fw-bold text-primary mb-1"><?= APP_NAME ?></h2>
        <div class="text-muted small">
          <?= COMPANY_ADDRESS ?><br>
          Email: <?= COMPANY_EMAIL ?> | Phone: <?= COMPANY_PHONE ?><br>
          GST: <?= COMPANY_GST ?>
        </div>
      </div>
      <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
        <h3 class="text-uppercase text-muted fw-bold mb-3">Tax Invoice</h3>
        <div class="row text-start text-sm-end">
          <div class="col-6 col-sm-12"><strong class="small text-muted text-uppercase">Invoice Number</strong><br><span class="fs-6 fw-bold"><?= e($inv['invoice_number']) ?></span></div>
          <div class="col-6 col-sm-12 mt-2"><strong class="small text-muted text-uppercase">Date</strong><br><span><?= formatDate($inv['created_at']) ?></span></div>
          <?php if($inv['due_date']): ?>
            <div class="col-6 col-sm-12 mt-2"><strong class="small text-muted text-uppercase">Due Date</strong><br><span class="<?= strtotime($inv['due_date'])<time() && !in_array($inv['status'],['paid','cancelled']) ? 'text-danger fw-semibold' : '' ?>"><?= formatDate($inv['due_date']) ?></span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Customer Details -->
    <div class="row mb-5">
      <div class="col-sm-6">
        <strong class="small text-muted text-uppercase mb-2 d-block">Bill To:</strong>
        <div class="fs-6 fw-semibold text-dark"><?= e($inv['customer_name']) ?></div>
        <?php if($inv['customer_company']): ?><div><?= e($inv['customer_company']) ?></div><?php endif; ?>
        <div class="text-muted small mt-1">
          <?= e($inv['address']) ?><br>
          <?= e($inv['city']) ?><?= $inv['state'] ? ', ' . e($inv['state']) : '' ?> <?= e($inv['pincode']) ?><br>
          Phone: <?= e($inv['phone'] ?: '—') ?><br>
          Email: <?= e($inv['email'] ?: '—') ?>
        </div>
      </div>
      <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
        <div class="p-3 bg-light rounded" style="display: inline-block; min-width: 200px;">
          <div class="text-muted small text-uppercase mb-1">Balance Due</div>
          <div class="fs-3 fw-bold <?= ($inv['total']-$inv['paid_amount'])>0?'text-danger':'text-success' ?>">
            <?= formatCurrency(max(0, $inv['total'] - $inv['paid_amount'])) ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered border-primary mb-0" style="--bs-border-opacity: .2;">
        <thead class="table-light">
          <tr>
            <th class="py-3">#</th>
            <th class="py-3">Description</th>
            <th class="text-end py-3">Qty</th>
            <th class="text-end py-3">Price</th>
            <th class="text-end py-3">Tax</th>
            <th class="text-end py-3">Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $i => $item): ?>
          <tr>
            <td class="text-muted"><?= $i+1 ?></td>
            <td><?= e($item['description']) ?></td>
            <td class="text-end"><?= (float)$item['qty'] ?> <?= e($item['unit']) ?></td>
            <td class="text-end"><?= formatCurrency($item['unit_price']) ?></td>
            <td class="text-end"><?= $item['tax_rate'] ?>%</td>
            <td class="text-end fw-semibold text-dark"><?= formatCurrency($item['line_total']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="row justify-content-end mb-5">
      <div class="col-sm-6 col-md-5 col-lg-4">
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted">Subtotal</td><td class="text-end"><?= formatCurrency($inv['subtotal']) ?></td></tr>
          <?php if($inv['discount_amount'] > 0): ?>
          <tr><td class="text-muted">Discount <?= (isset($inv['discount_type']) && $inv['discount_type']==='percent')?"({$inv['discount_value']}%)":'' ?></td><td class="text-end text-danger">- <?= formatCurrency($inv['discount_amount']) ?></td></tr>
          <?php endif; ?>
          <tr><td class="text-muted">Tax Amount</td><td class="text-end"><?= formatCurrency($inv['tax_amount']) ?></td></tr>
          <tr class="border-top border-2"><td class="fw-bold text-dark pt-2 fs-5">Total</td><td class="text-end fw-bold text-primary pt-2 fs-5"><?= formatCurrency($inv['total']) ?></td></tr>
          <tr><td class="text-success pt-2">Amount Paid</td><td class="text-end text-success pt-2"><?= formatCurrency($inv['paid_amount']) ?></td></tr>
          <tr class="border-top"><td class="fw-bold text-danger pt-2">Balance Due</td><td class="text-end fw-bold text-danger pt-2"><?= formatCurrency(max(0, $inv['total'] - $inv['paid_amount'])) ?></td></tr>
        </table>
      </div>
    </div>

    <!-- Terms & Notes -->
    <div class="row">
      <div class="col-md-6 mb-4 mb-md-0">
        <?php if(!empty($inv['terms'])): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Terms & Conditions</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e($inv['terms']) ?></div>
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <?php if(!empty($inv['notes'])): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Notes</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e($inv['notes']) ?></div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</div>
<style>
@media print {
  body * { visibility: hidden; }
  .print-container, .print-container * { visibility: visible; }
  .print-container { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
  .main-content, .page-content { padding: 0 !important; margin: 0 !important; }
}
</style>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
