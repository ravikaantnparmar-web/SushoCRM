<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Receipts';
$search = sanitize($_GET['search'] ?? '');
$category_id = (int)($_GET['category_id'] ?? 0);
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    $where[] = 'r.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) {
    $where[] = '(r.title LIKE ? OR r.reference LIKE ? OR c.company_name LIKE ?)';
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
if ($category_id) {
    $where[] = 'r.category_id = ?';
    $params[] = $category_id;
}
$whereStr = implode(' AND ', $where);

$sql = "SELECT r.*, c.company_name AS customer_name, cat.name AS category_name, u.name AS creator_name
        FROM receipts r
        LEFT JOIN customers c ON r.customer_id = c.id
        LEFT JOIN receipt_categories cat ON r.category_id = cat.id
        LEFT JOIN users u ON r.created_by = u.id
        WHERE $whereStr";

$total = db()->prepare("SELECT COUNT(*) FROM ($sql) AS count_table");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/receipts/index.php?search='.urlencode($search).'&category_id='.$category_id);

$stmt = db()->prepare("$sql ORDER BY r.receipt_date DESC, r.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$receipts = $stmt->fetchAll();

$categories = db()->query("SELECT * FROM receipt_categories WHERE is_active=1 ORDER BY name ASC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Finance: Receipts</div>
</div>
<div class="page-content">
<?= flashHtml() ?>

<div class="page-header">
  <div class="page-header-left">
    <h1>Money Receipts</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item active">Receipts</li>
      </ol>
    </nav>
  </div>
  <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Record Receipt</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search title, ref or customer..." value="<?= e($search) ?>"></div>
      <select name="category_id" class="form-select form-select-sm w-auto">
        <option value="">All Categories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $category_id==$cat['id']?'selected':'' ?>><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel"></i></button>
      <?php if($search || $category_id): ?><a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead>
        <tr>
          <th>Date</th>
          <th>Title / Description</th>
          <th>Customer</th>
          <th>Category</th>
          <th>Method</th>
          <th>Reference</th>
          <th>Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($receipts)): ?>
          <tr><td colspan="8"><div class="empty-state"><i class="bi bi-receipt"></i><p>No receipts recorded yet.</p></div></td></tr>
        <?php else: foreach($receipts as $rec): ?>
        <tr>
          <td class="text-nowrap"><?= formatDate($rec['receipt_date']) ?></td>
          <td>
            <div class="d-flex align-items-center">
                <div>
                    <div class="fw-semibold"><?= e($rec['title']) ?></div>
                    <?php if($rec['description']): ?><div class="small text-muted text-truncate" style="max-width:200px;"><?= e($rec['description']) ?></div><?php endif; ?>
                </div>
                <?php if($rec['attachment']): ?>
                    <a href="<?= BASE_URL ?>/uploads/receipts/<?= $rec['attachment'] ?>" target="_blank" class="ms-2 text-primary" title="View Attachment">
                        <i class="bi bi-paperclip fs-5"></i>
                    </a>
                <?php endif; ?>
            </div>
          </td>
          <td>
            <?php if($rec['customer_name']): ?>
              <span class="fw-semibold"><i class="bi bi-person me-1 text-muted"></i><?= e($rec['customer_name']) ?></span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td><span class="badge bg-light text-dark border"><?= e($rec['category_name']) ?></span></td>
          <td><?= e($rec['payment_method']) ?></td>
          <td><code class="small"><?= e($rec['reference'] ?: '—') ?></code></td>
          <td class="fw-bold text-success">+ <?= formatCurrency($rec['amount']) ?></td>
          <td>
            <div class="btn-group">
              <a href="delete.php?id=<?= $rec['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete this receipt record?" title="Delete"><i class="bi bi-trash"></i></a>
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
