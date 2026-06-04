<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if (!isAdmin()) {
    setFlash('danger', 'Access denied.');
    header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
    exit;
}

$pageTitle = 'Pending Approvals';
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

// Currently we only have quotations for approval, but we can structure this to query multiple things if needed, or just list quotations.
$whereStr = "q.approval_status IN ('pending', 'under_review')";

$total = db()->prepare("SELECT COUNT(*) FROM quotations q JOIN customers c ON q.customer_id = c.id WHERE $whereStr AND q.is_latest = 1");
$total->execute(); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/approvals/index.php?');

$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company FROM quotations q JOIN customers c ON q.customer_id=c.id WHERE $whereStr AND q.is_latest = 1 ORDER BY q.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute();
$quotations = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Pending Approvals</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Pending Approvals</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Approvals</li></ol></nav>
  </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records requiring approval</span>
  </div>
<div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Type</th><th>Reference #</th><th>Customer</th><th>Date</th><th>Amount</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($quotations)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-shield-check"></i><p>No pending approvals at the moment.</p></div></td></tr>
        <?php else: foreach($quotations as $q): ?>
        <tr>
          <td><span class="badge bg-secondary">Quotation</span></td>
          <td>
            <a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q['id'] ?>" class="fw-semibold text-primary"><?= e($q['quote_number']) ?></a>
            <span class="badge bg-light text-secondary border ms-1">V<?= $q['version'] ?></span>
          </td>
          <td>
            <div class="fw-semibold text-dark"><?= e($q['customer_name']) ?></div>
            <?php if($q['customer_company']): ?><div class="text-muted small"><?= e($q['customer_company']) ?></div><?php endif; ?>
          </td>
          <td><?= formatDate($q['created_at']) ?></td>
          <td class="fw-bold"><?= formatCurrency($q['total']) ?></td>
          <td>
            <?php
              $approval_class = match($q['approval_status']) {
                  'approved' => 'bg-success',
                  'rejected' => 'bg-danger',
                  'under_review' => 'bg-info',
                  'on_hold' => 'bg-warning',
                  default => 'bg-secondary'
              };
              $approval_label = ucwords(str_replace('_', ' ', $q['approval_status']));
            ?>
            <span class="badge <?= $approval_class ?> ms-1"><?= $approval_label ?></span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-primary">Review & Approve</a>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($pag['total_pages']>1): ?>
  <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
    <small class="text-muted">Showing <?= $pag['offset']+1 ?>–<?= min($pag['offset']+$per,$totalCount) ?> of <?= $totalCount ?></small>
    <?= paginationHtml($pag) ?>
  </div>
  <?php endif; ?>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
