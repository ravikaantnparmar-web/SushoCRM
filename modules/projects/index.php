<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Projects';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    $where[] = '(p.manager_id = ? OR p.created_by = ?)';
    $params[] = $_SESSION['user_id'];
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(p.project_number LIKE ? OR p.name LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%"]); }
if ($status) { $where[] = 'p.status = ?'; $params[] = $status; }

$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM projects p WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/projects/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT p.*, c.company_name AS customer_company, c.company_name AS customer_name, m.name AS manager_name FROM projects p LEFT JOIN customers c ON p.customer_id = c.id LEFT JOIN users m ON p.manager_id = m.id WHERE $whereStr ORDER BY p.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$projects = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Projects</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Projects</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Projects</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/projects/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Project</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search projects..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:140px">
        <option value="">All Status</option>
        <?php foreach(['planning','in_progress','on_hold','completed','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucwords(str_replace('_',' ',$s)) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/projects/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Project</th><th>Client</th><th>Manager</th><th>Budget</th><th>Target End</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($projects)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-briefcase"></i><p>No projects found</p><a href="<?= BASE_URL ?>/modules/projects/create.php" class="btn btn-primary btn-sm">Create Project</a></div></td></tr>
        <?php else: foreach($projects as $p): ?>
        <tr>
          <td>
            <a href="<?= BASE_URL ?>/modules/projects/view.php?id=<?= $p['id'] ?>" class="fw-semibold text-primary d-block"><?= e($p['name']) ?></a>
            <span class="text-muted small"><?= e($p['project_number']) ?></span>
          </td>
          <td>
            <?php if($p['customer_company']): ?>
              <div class="fw-semibold text-dark"><?= e($p['customer_company']) ?></div>
              <div class="text-muted small"><?= e($p['customer_name']) ?></div>
            <?php else: ?>
              <span class="text-muted fst-italic">Internal</span>
            <?php endif; ?>
          </td>
          <td><?= e($p['manager_name'] ?: '—') ?></td>
          <td class="fw-bold"><?= formatCurrency($p['budget']) ?></td>
          <td>
            <?php 
              $isOverdue = ($p['target_end_date'] && $p['target_end_date'] < date('Y-m-d') && $p['status'] === 'in_progress');
            ?>
            <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
              <?= $p['target_end_date'] ? formatDate($p['target_end_date']) : '—' ?>
            </span>
          </td>
          <td><?= statusBadge($p['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/projects/view.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/projects/edit.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/projects/delete.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete project <?= e($p['project_number']) ?>?"><i class="bi bi-trash"></i></a>
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
