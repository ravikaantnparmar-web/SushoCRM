<?php
// Shared topbar/page-header partial for all module pages
// Usage: set $pageTitle, $breadcrumbs[], $pageActions (HTML) before including
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title"><?= e($pageTitle ?? 'Page') ?></div>
  <div class="topbar-right">
    <div class="dropdown">
      <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown"
              style="background:var(--bg-main);border:1px solid var(--border-color);border-radius:8px;padding:5px 10px">
        <div class="stat-icon primary" style="width:28px;height:28px;border-radius:8px;font-size:12px">
          <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
        </div>
        <span class="small fw-semibold d-none d-md-inline"><?= e($currentUser['name'] ?? '') ?></span>
        <i class="bi bi-chevron-down small"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px">
        <li><a class="dropdown-item small" href="<?= BASE_URL ?>/modules/settings/index.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item small text-danger" href="#" data-bs-toggle="modal" data-bs-target="#globalLogoutModal"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="page-content">
<?php echo flashHtml(); ?>
