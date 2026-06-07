<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('expenses', 'view');
$pageTitle = 'Expenses';
$search = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    $where[] = 'e.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(ec.name LIKE ? OR e.reference LIKE ? OR e.description LIKE ? OR e.title LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
if ($category) { $where[] = 'e.category_id = ?'; $params[] = $category; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM expenses e LEFT JOIN expense_categories ec ON e.category_id = ec.id WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/expenses/index.php?search='.urlencode($search).'&category='.urlencode($category));

$stmt = db()->prepare("SELECT e.*, ec.name as category_name FROM expenses e LEFT JOIN expense_categories ec ON e.category_id = ec.id WHERE $whereStr ORDER BY e.expense_date DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$expenses = $stmt->fetchAll();

$categories = db()->query("SELECT id, name FROM expense_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Expenses</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Expenses</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Expenses</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/expenses/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Record Expense</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search expenses..." value="<?= e($search) ?>"></div>
      <select name="category" class="form-select form-select-sm" style="width:150px">
        <option value="">All Categories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= e($cat['id']) ?>" <?= $category==$cat['id']?'selected':'' ?>><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$category): ?><a href="<?= BASE_URL ?>/modules/expenses/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Date</th><th>Category</th><th>Title</th><th>Reference</th><th>Notes</th><th>Attachment</th><th>Amount</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($expenses)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cash-stack"></i><p>No expenses recorded</p><a href="<?= BASE_URL ?>/modules/expenses/create.php" class="btn btn-primary btn-sm">Record First Expense</a></div></td></tr>
        <?php else: foreach($expenses as $ex): ?>
        <tr>
          <td class="text-nowrap"><?= formatDate($ex['expense_date']) ?></td>
          <td><span class="badge bg-light text-dark border"><?= e($ex['category_name'] ?: 'Uncategorized') ?></span></td>
          <td><span class="fw-semibold text-dark"><?= e($ex['title']) ?></span></td>
          <td><?= e($ex['reference'] ?: '—') ?></td>
          <td><div class="text-truncate" style="max-width:180px" title="<?= e($ex['description']) ?>"><?= e($ex['description'] ?: '—') ?></div></td>
          <td>
            <?php if($ex['attachment']): 
              $ext = strtolower(pathinfo($ex['attachment'], PATHINFO_EXTENSION));
              $isPdf = ($ext === 'pdf');
              $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);
              $icon  = $isPdf ? 'bi-file-earmark-pdf text-danger' : ($isImg ? 'bi-file-earmark-image text-info' : 'bi-file-earmark text-secondary');
            ?>
              <a href="<?= BASE_URL ?>/uploads/expenses/<?= urlencode($ex['attachment']) ?>" target="_blank"
                 class="btn btn-icon btn-sm btn-outline-secondary" title="View / Download Attachment">
                <i class="bi <?= $icon ?>"></i>
              </a>
            <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
          </td>
          <td class="fw-bold text-danger"><?= formatCurrency($ex['amount']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/modules/expenses/delete.php?id=<?= $ex['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete this expense?"><i class="bi bi-trash"></i></a>
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
