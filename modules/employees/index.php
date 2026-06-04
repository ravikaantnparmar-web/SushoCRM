<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Employees';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    // Non-admins only see their own employee record matching their email
    $where[] = 'email = ?';
    $params[] = $_SESSION['user_email'];
}
if ($search) { $where[] = '(name LIKE ? OR emp_code LIKE ? OR email LIKE ? OR phone LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM employees WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/employees/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT * FROM employees WHERE $whereStr ORDER BY created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$employees = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Employees</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Employees</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Employees</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/employees/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Employee</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search employees..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['active','on_leave','terminated'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/employees/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>EMP Code</th><th>Name</th><th>Designation</th><th>Department</th><th>Contact</th><th>Joining Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($employees)): ?>
          <tr><td colspan="8"><div class="empty-state"><i class="bi bi-people"></i><p>No employees found</p><a href="<?= BASE_URL ?>/modules/employees/create.php" class="btn btn-primary btn-sm">Add First Employee</a></div></td></tr>
        <?php else: foreach($employees as $emp): ?>
        <tr>
          <td><span class="fw-semibold text-primary"><?= e($emp['emp_code']) ?></span></td>
          <td><a href="<?= BASE_URL ?>/modules/employees/view.php?id=<?= $emp['id'] ?>" class="fw-semibold text-dark text-decoration-none"><?= e($emp['name']) ?></a></td>
          <td><?= e($emp['designation'] ?: '—') ?></td>
          <td><?= e($emp['department'] ?: '—') ?></td>
          <td>
            <div class="small"><i class="bi bi-envelope me-1 text-muted"></i><?= e($emp['email']?:'—') ?></div>
            <div class="small"><i class="bi bi-telephone me-1 text-muted"></i><?= e($emp['phone']?:'—') ?></div>
          </td>
          <td><?= $emp['join_date'] ? formatDate($emp['join_date']) : '—' ?></td>
          <td><?= statusBadge($emp['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/employees/view.php?id=<?= $emp['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/employees/edit.php?id=<?= $emp['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/employees/delete.php?id=<?= $emp['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete employee <?= e($emp['name']) ?>?"><i class="bi bi-trash"></i></a>
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
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
