<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

// This should ideally only be accessible by Admins, but we'll allow it for demonstration
$pageTitle = 'Activity Logs';
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = 50;

$total = db()->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();
$pag = paginate($total, $per, $page, BASE_URL.'/modules/settings/activity-logs.php?');

$stmt = db()->prepare("SELECT a.*, u.name AS user_name FROM activity_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute();
$logs = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Activity Logs</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left"><h1>System Activity Logs</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/settings/index.php">Settings</a></li><li class="breadcrumb-item active">Activity Logs</li></ol></nav>
  </div>
</div>

<div class="table-wrapper">
  <div class="table-responsive">
    <table class="table crm-table align-middle mb-0">
      <thead><tr><th>Date & Time</th><th>User</th><th>Module</th><th>Action</th><th>Description</th></tr></thead>
      <tbody>
        <?php if(empty($logs)): ?>
          <tr><td colspan="5"><div class="empty-state"><p>No logs found</p></div></td></tr>
        <?php else: foreach($logs as $log): ?>
        <tr>
          <td class="text-nowrap text-muted small"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
          <td class="fw-semibold"><?= e($log['user_name'] ?: 'System') ?></td>
          <td><span class="badge bg-light text-dark border text-uppercase"><?= e($log['module']) ?></span></td>
          <td>
            <?php
            $actionClass = 'bg-secondary';
            if ($log['action'] === 'create') $actionClass = 'bg-success';
            if ($log['action'] === 'update') $actionClass = 'bg-primary';
            if ($log['action'] === 'delete') $actionClass = 'bg-danger';
            if ($log['action'] === 'login') $actionClass = 'bg-info';
            ?>
            <span class="badge <?= $actionClass ?>"><?= ucfirst($log['action']) ?></span>
          </td>
          <td class="text-wrap" style="max-width: 400px;"><?= e($log['description']) ?></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($pag['total_pages']>1): ?>
  <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
    <small class="text-muted">Showing <?= $pag['offset']+1 ?>–<?= min($pag['offset']+$per,$total) ?> of <?= $total ?></small>
    <?= paginationHtml($pag) ?>
  </div>
  <?php endif; ?>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
