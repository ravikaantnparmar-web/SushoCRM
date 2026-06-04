<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Settings';

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Settings</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left"><h1>Company Settings</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Settings</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/settings/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Setting</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="crm-card">
            <div class="crm-card-body p-4">
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                    <div>
                        Company settings are currently managed via the application configuration file <code>config/config.php</code>. 
                        Please contact your system administrator to modify these values.
                    </div>
                </div>
                
                <h5 class="fw-bold mb-4 border-bottom pb-2">Company Information</h5>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Company Name</div>
                    <div class="col-sm-8 fw-semibold"><?= e(APP_NAME) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Email Address</div>
                    <div class="col-sm-8 fw-semibold"><?= e(COMPANY_EMAIL) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Phone Number</div>
                    <div class="col-sm-8 fw-semibold"><?= e(COMPANY_PHONE) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Address</div>
                    <div class="col-sm-8 fw-semibold" style="white-space:pre-line"><?= e(COMPANY_ADDRESS) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">GST Number</div>
                    <div class="col-sm-8 fw-semibold"><?= e(COMPANY_GST) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="crm-card h-100">
            <div class="crm-card-body p-4">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <div class="list-group list-group-flush border-top border-bottom">
                    <a href="<?= BASE_URL ?>/modules/settings/users.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-people me-2 text-primary"></i>Manage Users</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/modules/settings/activity-logs.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-clock-history me-2 text-info"></i>Activity Logs</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
