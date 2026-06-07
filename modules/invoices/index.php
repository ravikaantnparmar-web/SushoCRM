<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('invoices', 'view');
$pageTitle = 'Invoices';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if (!isAdmin()) {
    $where[] = 'i.created_by = ?';
    $params[] = $_SESSION['user_id'];
}
if ($search) { $where[] = '(i.invoice_number LIKE ? OR c.company_name LIKE ? OR c.company_email LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'i.status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/invoices/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT i.*, c.company_name AS customer_name, c.company_name AS customer_company FROM invoices i JOIN customers c ON i.customer_id=c.id WHERE $whereStr ORDER BY i.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$invoices = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Invoices</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Invoices</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Invoices</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/invoices/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Invoice</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search invoices..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/invoices/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Invoice #</th><th>Customer</th><th>Date</th><th>Due Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($invoices)): ?>
          <tr><td colspan="9"><div class="empty-state"><i class="bi bi-receipt"></i><p>No invoices found</p><a href="<?= BASE_URL ?>/modules/invoices/create.php" class="btn btn-primary btn-sm">Create First Invoice</a></div></td></tr>
        <?php else: foreach($invoices as $inv): ?>
        <tr>
          <td><a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $inv['id'] ?>" class="fw-semibold text-primary"><?= e($inv['invoice_number']) ?></a></td>
          <td>
            <div class="fw-semibold text-dark"><?= e($inv['customer_name']) ?></div>
            <?php if($inv['customer_company']): ?><div class="text-muted small"><?= e($inv['customer_company']) ?></div><?php endif; ?>
          </td>
          <td><?= formatDate($inv['created_at']) ?></td>
          <td>
            <?php if($inv['due_date']): ?>
              <span class="<?= strtotime($inv['due_date'])<time() && !in_array($inv['status'],['paid','cancelled']) ? 'text-danger fw-semibold' : '' ?>"><?= formatDate($inv['due_date']) ?></span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="fw-bold"><?= formatCurrency($inv['total']) ?></td>
          <td class="text-success"><?= formatCurrency($inv['paid_amount']) ?></td>
          <td class="<?= ($inv['total']-$inv['paid_amount'])>0?'text-danger fw-semibold':'' ?>"><?= formatCurrency($inv['total'] - $inv['paid_amount']) ?></td>
          <td><?= statusBadge($inv['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $inv['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/invoices/edit.php?id=<?= $inv['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/invoices/delete.php?id=<?= $inv['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete invoice <?= e($inv['invoice_number']) ?>?"><i class="bi bi-trash"></i></a>
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
