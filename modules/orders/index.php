<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('orders', 'view');
$pageTitle = 'Orders';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    $where[] = 'o.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(o.order_number LIKE ? OR c.company_name LIKE ? OR c.company_email LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'o.status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM orders o LEFT JOIN customers c ON o.customer_id = c.id WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/orders/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT o.*, c.company_name AS customer_name, c.company_name AS customer_company FROM orders o LEFT JOIN customers c ON o.customer_id=c.id WHERE $whereStr ORDER BY o.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$orders = $stmt->fetchAll();

// Dashboard Stats
$statWhere = ['1=1']; 
$statParams = [];
if (!isAdmin()) {
    $statWhere[] = 'o.created_by = ?';
    $statParams[] = $_SESSION['user_id'];
}
$statWhereStr = implode(' AND ', $statWhere);

$statSql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
        SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_count,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
    FROM orders o
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
  <div class="topbar-title">Orders</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Sales Orders</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Orders</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/orders/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Order</a>
</div>

<!-- Dashboard Widgets -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="bi bi-cart"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['total'] ?? 0)) ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['pending_count'] ?? 0)) ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="bi bi-gear"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['processing_count'] ?? 0)) ?></div>
                <div class="stat-label">Processing</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="bi bi-check2-all"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['completed_count'] ?? 0)) ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search orders..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['pending','processing','completed','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/orders/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Order #</th><th>Customer</th><th>Date</th><th>Delivery Date</th><th>Total Amount</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($orders)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cart"></i><p>No orders found</p><a href="<?= BASE_URL ?>/modules/orders/create.php" class="btn btn-primary btn-sm">Create First Order</a></div></td></tr>
        <?php else: foreach($orders as $o): ?>
        <tr>
          <td><a href="<?= BASE_URL ?>/modules/orders/view.php?id=<?= $o['id'] ?>" class="fw-semibold text-primary"><?= e($o['order_number']) ?></a></td>
          <td>
            <div class="fw-semibold text-dark"><?= e($o['customer_name']) ?></div>
            <?php if($o['customer_company']): ?><div class="text-muted small"><?= e($o['customer_company']) ?></div><?php endif; ?>
          </td>
          <td><?= formatDate($o['created_at']) ?></td>
          <td><?= $o['delivery_date'] ? formatDate($o['delivery_date']) : '—' ?></td>
          <td class="fw-bold"><?= formatCurrency($o['total']) ?></td>
          <td><?= statusBadge($o['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/orders/view.php?id=<?= $o['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/orders/edit.php?id=<?= $o['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/orders/delete.php?id=<?= $o['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete order <?= e($o['order_number']) ?>?"><i class="bi bi-trash"></i></a>
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
