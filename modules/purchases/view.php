<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT p.*, v.name AS vendor_name, v.company AS vendor_company, v.address, v.city, v.state, v.pincode, v.email, v.phone, u.name AS created_by_name FROM purchases p JOIN vendors v ON p.vendor_id = v.id LEFT JOIN users u ON p.created_by = u.id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('danger','Purchase not found.'); header('Location: '.BASE_URL.'/modules/purchases/index.php'); exit; }

$stmtItems = db()->prepare("SELECT * FROM purchase_items WHERE purchase_id=? ORDER BY id ASC");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

$pageTitle = 'Purchase: ' . $p['purchase_number'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">View Purchase</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header d-print-none">
  <div class="page-header-left"><h1><?= e($p['purchase_number']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/purchases/index.php">Purchases</a></li><li class="breadcrumb-item active"><?= e($p['purchase_number']) ?></li></ol></nav>
  </div>
  <div><?= statusBadge($p['status']) ?></div>
</div>

<div class="crm-card print-container">
  <div class="crm-card-body p-4 p-md-5">
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
        <h3 class="text-uppercase text-muted fw-bold mb-3">Purchase Order</h3>
        <div class="row text-start text-sm-end">
          <div class="col-6 col-sm-12"><strong class="small text-muted text-uppercase">PO Number</strong><br><span class="fs-6 fw-bold"><?= e($p['purchase_number']) ?></span></div>
          <div class="col-6 col-sm-12 mt-2"><strong class="small text-muted text-uppercase">Date</strong><br><span><?= formatDate($p['purchase_date'] ?: $p['created_at']) ?></span></div>
        </div>
      </div>
    </div>
    
    <div class="row mb-5">
      <div class="col-sm-6">
        <strong class="small text-muted text-uppercase mb-2 d-block">Vendor Details:</strong>
        <div class="fs-6 fw-semibold text-dark"><?= e($p['vendor_name']) ?></div>
        <?php if($p['vendor_company']): ?><div><?= e($p['vendor_company']) ?></div><?php endif; ?>
        <div class="text-muted small mt-1">
          <?= e($p['address']) ?><br>
          <?= e($p['city']) ?><?= $p['state'] ? ', ' . e($p['state']) : '' ?> <?= e($p['pincode']) ?><br>
          Phone: <?= e($p['phone'] ?: '—') ?><br>
          Email: <?= e($p['email'] ?: '—') ?>
        </div>
      </div>
      <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
        <div class="p-3 bg-light rounded" style="display: inline-block; min-width: 200px;">
          <div class="text-muted small text-uppercase mb-1">Balance Due</div>
          <div class="fs-3 fw-bold <?= ($p['total']-$p['paid_amount'])>0?'text-danger':'text-success' ?>">
            <?= formatCurrency(max(0, $p['total'] - $p['paid_amount'])) ?>
          </div>
        </div>
      </div>
    </div>

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

    <div class="row justify-content-end mb-5">
      <div class="col-sm-6 col-md-5 col-lg-4">
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted">Subtotal</td><td class="text-end"><?= formatCurrency($p['subtotal']) ?></td></tr>
          <tr><td class="text-muted">Tax Amount</td><td class="text-end"><?= formatCurrency($p['tax_amount']) ?></td></tr>
          <tr class="border-top border-2"><td class="fw-bold text-dark pt-2 fs-5">Total</td><td class="text-end fw-bold text-primary pt-2 fs-5"><?= formatCurrency($p['total']) ?></td></tr>
          <tr><td class="text-success pt-2">Amount Paid</td><td class="text-end text-success pt-2"><?= formatCurrency($p['paid_amount']) ?></td></tr>
          <tr class="border-top"><td class="fw-bold text-danger pt-2">Balance Due</td><td class="text-end fw-bold text-danger pt-2"><?= formatCurrency(max(0, $p['total'] - $p['paid_amount'])) ?></td></tr>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-4 mb-md-0">
        <?php if(!empty($p['terms'])): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Terms & Conditions</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e($p['terms']) ?></div>
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <?php if($p['notes']): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Notes</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e($p['notes']) ?></div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</div></div></div>
<style>
@media print {
  body * { visibility: hidden; }
  .print-container, .print-container * { visibility: visible; }
  .print-container { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
  .main-content, .page-content { padding: 0 !important; margin: 0 !important; }
}
</style>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
