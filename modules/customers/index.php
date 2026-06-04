<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Customers';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1'];
$params = [];
if (!isAdmin()) {
    $where[] = 'c.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(c.company_name LIKE ? OR c.company_email LIKE ? OR c.city LIKE ? OR c.gst_number LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'c.company_status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM customers c WHERE $whereStr");
$total->execute($params);
$totalCount = $total->fetchColumn();

$pag = paginate($totalCount, $per, $page, BASE_URL . '/modules/customers/index.php?search=' . urlencode($search) . '&status=' . urlencode($status));

$stmt = db()->prepare("SELECT c.*, u.name AS created_by_name,
    (SELECT COUNT(*) FROM quotations q WHERE q.customer_id=c.id) AS quote_count,
    (SELECT COALESCE(SUM(p.amount),0) FROM payments p WHERE p.customer_id=c.id) AS total_paid,
    (SELECT co.name FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_name,
    (SELECT co.mobile FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_phone
    FROM customers c LEFT JOIN users u ON c.created_by=u.id
    WHERE $whereStr ORDER BY c.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$customers = $stmt->fetchAll();

// Dashboard Stats
$statWhere = ['1=1'];
$statParams = [];
if (!isAdmin()) {
    $statWhere[] = 'created_by = ?';
    $statParams[] = $_SESSION['user_id'];
}
$statWhereStr = implode(' AND ', $statWhere);

$statSql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN company_status = 'Active' THEN 1 ELSE 0 END) as active_count,
        SUM(CASE WHEN company_status IN ('Inactive', 'Lost', 'Blacklisted') THEN 1 ELSE 0 END) as inactive_count,
        SUM(CASE WHEN company_status = 'Prospect' THEN 1 ELSE 0 END) as prospect_count
    FROM customers 
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
  <div class="topbar-title">Customers</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Customer Management</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Customers</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/customers/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Customer</a>
</div>

<!-- Dashboard Widgets -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="bi bi-people"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['total'] ?? 0)) ?></div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['active_count'] ?? 0)) ?></div>
                <div class="stat-label">Active Customers</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="bi bi-slash-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['inactive_count'] ?? 0)) ?></div>
                <div class="stat-label">Inactive / Lost</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="bi bi-star"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['prospect_count'] ?? 0)) ?></div>
                <div class="stat-label">Prospects</div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search">
        <i class="bi bi-search"></i>
        <input type="text" name="search" placeholder="Search customers..." value="<?= e($search) ?>">
      </div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['Prospect', 'Active', 'Inactive', 'Lost', 'Blacklisted'] as $opt): ?>
          <option value="<?= $opt ?>" <?= $status===$opt?'selected':'' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if ($search || $status): ?>
        <a href="<?= BASE_URL ?>/modules/customers/index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
      <?php endif; ?>
    </form>
    <div class="d-flex gap-2">
      <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
      <button class="btn btn-sm btn-outline-secondary" onclick="exportTableToCSV('customersTable','customers.csv')"><i class="bi bi-download me-1"></i>CSV</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0" id="customersTable">
      <thead><tr>
        <th>#</th><th>Customer</th><th>Company</th><th>Type</th><th>Phone</th><th>City</th>
        <th>Quotes</th><th>Total Paid</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr><td colspan="9"><div class="empty-state"><i class="bi bi-people"></i><p>No customers found</p><a href="<?= BASE_URL ?>/modules/customers/create.php" class="btn btn-primary btn-sm">Add First Customer</a></div></td></tr>
        <?php else: ?>
          <?php foreach ($customers as $i => $c): 
            $displayName = $c['primary_contact_name'] ?: $c['company_name'] ?: 'Customer';
          ?>
          <tr>
            <td class="text-muted small"><?= ($pag['offset']+$i+1) ?></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="stat-icon primary" style="width:32px;height:32px;border-radius:10px;font-size:13px;flex-shrink:0"><?= strtoupper(substr($displayName,0,1)) ?></div>
                <div>
                  <a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $c['id'] ?>" class="fw-semibold text-dark"><?= e($displayName) ?></a>
                  <?php if ($c['customer_code']): ?><div class="text-muted" style="font-size:11px"><?= e($c['customer_code']) ?></div><?php endif; ?>
                </div>
              </div>
            </td>
            <td><?= e($c['company_name'] ?: '—') ?></td>
            <td><span class="badge bg-light text-dark border"><?= e($c['company_type'] ?: '—') ?></span></td>
            <td><?= e($c['primary_contact_phone'] ?: '—') ?></td>
            <td><?= e($c['city'] ?: '—') ?></td>
            <td><span class="badge bg-primary"><?= $c['quote_count'] ?></span></td>
            <td class="fw-semibold text-success"><?= formatCurrency($c['total_paid']) ?></td>
            <td><?= statusBadge($c['company_status'] ?? '') ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></a>
                <a href="<?= BASE_URL ?>/modules/customers/edit.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/modules/customers/delete.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete customer <?= e($displayName) ?>?" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($pag['total_pages'] > 1): ?>
  <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
    <small class="text-muted">Showing <?= ($pag['offset']+1) ?>–<?= min($pag['offset']+$per,$totalCount) ?> of <?= $totalCount ?></small>
    <?= paginationHtml($pag) ?>
  </div>
  <?php endif; ?>
</div>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
