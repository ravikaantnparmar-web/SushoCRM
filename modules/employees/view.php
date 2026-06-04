<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM employees WHERE id=?");
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) { setFlash('danger','Employee not found.'); header('Location: '.BASE_URL.'/modules/employees/index.php'); exit; }

$pageTitle = 'View Employee: ' . $emp['name'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Employee Profile</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1><?= e($emp['name']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/employees/index.php">Employees</a></li><li class="breadcrumb-item active">Profile</li></ol></nav>
  </div>
  <div><?= statusBadge($emp['status']) ?></div>
  <a href="<?= BASE_URL ?>/modules/employees/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="crm-card text-center py-5">
      <div class="mb-3">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary fs-1 fw-bold" style="width:100px;height:100px;">
          <?= strtoupper(substr($emp['name'],0,2)) ?>
        </div>
      </div>
      <h4 class="fw-bold mb-1"><?= e($emp['name']) ?></h4>
      <div class="text-muted mb-3"><?= e($emp['designation']?:'—') ?></div>
      <div class="d-flex justify-content-center gap-2 mb-4">
        <?php if($emp['email']): ?><a href="mailto:<?= e($emp['email']) ?>" class="btn btn-outline-primary btn-sm btn-icon rounded-circle"><i class="bi bi-envelope"></i></a><?php endif; ?>
        <?php if($emp['phone']): ?><a href="tel:<?= e($emp['phone']) ?>" class="btn btn-outline-primary btn-sm btn-icon rounded-circle"><i class="bi bi-telephone"></i></a><?php endif; ?>
      </div>
      <div class="px-4 text-start">
        <div class="mb-2"><small class="text-muted text-uppercase fw-semibold d-block">Employee Code</small><span class="fw-semibold"><?= e($emp['emp_code']) ?></span></div>
        <div class="mb-2"><small class="text-muted text-uppercase fw-semibold d-block">Department</small><span><?= e($emp['department']?:'—') ?></span></div>
        <div class="mb-2"><small class="text-muted text-uppercase fw-semibold d-block">Joining Date</small><span><?= $emp['join_date']?formatDate($emp['join_date']):'—' ?></span></div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="crm-card mb-4"><div class="crm-card-body p-4">
      <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Contact Information</h5>
      <div class="row mb-4">
        <div class="col-sm-6 mb-3"><div class="text-muted small">Email Address</div><div class="fw-semibold"><?= e($emp['email']?:'—') ?></div></div>
        <div class="col-sm-6 mb-3"><div class="text-muted small">Phone Number</div><div class="fw-semibold"><?= e($emp['phone']?:'—') ?></div></div>
      </div>
      <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Address</h5>
      <div class="row">
        <div class="col-12 mb-3"><div class="text-muted small">Full Address</div><div class="fw-semibold"><?= nl2br(e($emp['address']?:'—')) ?></div></div>
      </div>
    </div></div>
    
    <div class="crm-card"><div class="crm-card-body p-4">
      <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-cash-stack me-2 text-primary"></i>Financial Details</h5>
      <div class="row">
        <div class="col-sm-6 mb-3"><div class="text-muted small">Basic Salary</div><div class="fw-bold text-dark fs-5"><?= formatCurrency($emp['salary']) ?></div></div>
      </div>
    </div></div>
  </div>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
