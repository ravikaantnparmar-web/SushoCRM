<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('quotations', 'view');

$pageTitle = 'Quotations';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['q.is_latest = 1']; $params = [];
if (!isAdmin()) {
    $where[] = 'q.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(q.quote_number LIKE ? OR c.company_name LIKE ? OR c.company_email LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'q.status = ?'; $params[] = $status; }

$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM quotations q LEFT JOIN customers c ON q.customer_id = c.id WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/quotations/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company, (SELECT COUNT(*) FROM quotations q2 WHERE q2.quote_number = q.quote_number) as total_versions FROM quotations q LEFT JOIN customers c ON q.customer_id=c.id WHERE $whereStr ORDER BY q.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$quotations = $stmt->fetchAll();

// Dashboard Stats
$statWhere = ['q.is_latest = 1']; 
$statParams = [];
if (!isAdmin()) {
    $statWhere[] = 'q.created_by = ?';
    $statParams[] = $_SESSION['user_id'];
}
$statWhereStr = implode(' AND ', $statWhere);

$statSql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN approval_status IN ('pending', 'under_review') THEN 1 ELSE 0 END) as pending_approval,
        SUM(CASE WHEN status IN ('accepted', 'converted') THEN 1 ELSE 0 END) as converted,
        SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft
    FROM quotations q
    WHERE $statWhereStr
";
$statStmt = db()->prepare($statSql);
$statStmt->execute($statParams);
$stats = $statStmt->fetch();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Quotations</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Quotations</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Quotations</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/quotations/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Quote</a>
</div>

<!-- Dashboard Widgets -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="bi bi-file-text"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['total'] ?? 0)) ?></div>
                <div class="stat-label">Total Quotations</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="bi bi-shield-exclamation"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['pending_approval'] ?? 0)) ?></div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['converted'] ?? 0)) ?></div>
                <div class="stat-label">Won / Converted</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="bi bi-pencil-square"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['draft'] ?? 0)) ?></div>
                <div class="stat-label">Drafts</div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search quotes..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['draft','sent','negotiation','accepted','rejected','converted','expired'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/quotations/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
<div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Quote #</th><th>Customer</th><th>Date</th><th>Valid Until</th><th>Total Amount</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($quotations)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-file-text"></i><p>No quotations found</p><a href="<?= BASE_URL ?>/modules/quotations/create.php" class="btn btn-primary btn-sm">Create First Quote</a></div></td></tr>
        <?php else: foreach($quotations as $q): ?>
        <tr>
          <td>
  <a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q['id'] ?>" class="fw-semibold text-primary"><?= e($q['quote_number']) ?></a>
  <span class="badge bg-light text-secondary border ms-1" title="<?= $q['total_versions'] ?> total revisions">V<?= $q['version'] ?></span>
</td>
          <td>
            <div class="fw-semibold text-dark"><?= e($q['customer_name']) ?></div>
            <?php if($q['customer_company']): ?><div class="text-muted small"><?= e($q['customer_company']) ?></div><?php endif; ?>
          </td>
          <td><?= formatDate($q['created_at']) ?></td>
          <td>
            <?php if($q['valid_until']): ?>
              <span class="<?= strtotime($q['valid_until'])<time() && $q['status']!=='accepted' && $q['status']!=='converted' ? 'text-danger fw-semibold' : '' ?>"><?= formatDate($q['valid_until']) ?></span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="fw-bold"><?= formatCurrency($q['total']) ?></td>
          <td><?= statusBadge($q['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/quotations/edit.php?id=<?= $q['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/quotations/delete.php?id=<?= $q['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete quote <?= e($q['quote_number']) ?>?"><i class="bi bi-trash"></i></a>
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
