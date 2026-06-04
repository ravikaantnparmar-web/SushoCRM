<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM vendors WHERE id=?");
$stmt->execute([$id]);
$v = $stmt->fetch();
if (!$v) { setFlash('danger','Vendor not found.'); header('Location: '.BASE_URL.'/modules/vendors/index.php'); exit; }

$pageTitle = $v['name'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Vendor Details</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1><?= e($v['name']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/vendors/index.php">Vendors</a></li><li class="breadcrumb-item active"><?= e($v['name']) ?></li></ol></nav>
  </div>
  <div><?= statusBadge($v['status']) ?></div>
  <a href="<?= BASE_URL ?>/modules/vendors/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="crm-card mb-3">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-truck text-primary me-2"></i>Vendor Information</h2></div>
      <div class="crm-card-body">
        <div class="row g-3">
          <div class="col-md-6"><div class="text-muted small mb-1">Vendor Code</div><div class="fw-semibold"><?= e($v['vendor_code'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Contact Name</div><div><?= e($v['name']) ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Company Name</div><div><?= e($v['company'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Email</div><div><?= $v['email'] ? '<a href="mailto:'.e($v['email']).'">'.e($v['email']).'</a>' : '—' ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">Phone</div><div><?= $v['phone'] ? '<a href="tel:'.e($v['phone']).'">'.e($v['phone']).'</a>' : '—' ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">GST Number</div><div><?= e($v['gst_number'] ?: '—') ?></div></div>
          <div class="col-md-6"><div class="text-muted small mb-1">PAN Number</div><div><?= e($v['pan_number'] ?: '—') ?></div></div>
          <div class="col-12"><div class="text-muted small mb-1">Address</div><div><?= e($v['address'] ?: '—') ?></div></div>
          <div class="col-md-4"><div class="text-muted small mb-1">City</div><div><?= e($v['city'] ?: '—') ?></div></div>
          <div class="col-md-4"><div class="text-muted small mb-1">State</div><div><?= e($v['state'] ?: '—') ?></div></div>
          <div class="col-md-4"><div class="text-muted small mb-1">Pincode</div><div><?= e($v['pincode'] ?: '—') ?></div></div>
        </div>
      </div>
    </div>
    <div class="crm-card">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-bank text-info me-2"></i>Bank Details</h2></div>
      <div class="crm-card-body">
        <div class="row g-3">
          <div class="col-md-4"><div class="text-muted small mb-1">Bank Name</div><div><?= e($v['bank_name'] ?: '—') ?></div></div>
          <div class="col-md-4"><div class="text-muted small mb-1">Account Number</div><div><?= e($v['bank_account'] ?: '—') ?></div></div>
          <div class="col-md-4"><div class="text-muted small mb-1">IFSC Code</div><div><?= e($v['bank_ifsc'] ?: '—') ?></div></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="crm-card mb-3">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-cash-stack text-success me-2"></i>Financials</h2></div>
      <div class="crm-card-body">
        <div class="mb-3">
          <div class="text-muted small mb-1">Outstanding Balance</div>
          <div class="h4 fw-bold <?= $v['outstanding_balance'] > 0 ? 'text-danger' : 'text-success' ?>"><?= formatCurrency($v['outstanding_balance']) ?></div>
        </div>
      </div>
    </div>
    <?php if($v['notes']): ?>
    <div class="crm-card mb-3">
      <div class="crm-card-header"><h2 class="crm-card-title"><i class="bi bi-card-text text-warning me-2"></i>Notes</h2></div>
      <div class="crm-card-body">
        <div style="white-space:pre-line" class="small"><?= e($v['notes']) ?></div>
      </div>
    </div>
    <?php endif; ?>
    <div class="crm-card">
      <div class="crm-card-body">
        <div class="d-grid gap-2">
          <a href="<?= BASE_URL ?>/modules/vendors/edit.php?id=<?= $id ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Vendor</a>
          <a href="<?= BASE_URL ?>/modules/vendors/delete.php?id=<?= $id ?>" class="btn btn-outline-danger btn-sm" data-confirm="Delete this vendor?"><i class="bi bi-trash me-1"></i>Delete</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
