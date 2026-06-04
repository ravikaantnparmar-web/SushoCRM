<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Payments';
$search = sanitize($_GET['search'] ?? '');
$type = sanitize($_GET['type'] ?? ''); // incoming or outgoing
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if ($search) { $where[] = '(p.reference LIKE ? OR c.company_name LIKE ? OR i.invoice_number LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
$whereStr = implode(' AND ', $where);

$sql = "SELECT p.*, c.company_name AS customer_name, i.invoice_number
        FROM payments p 
        LEFT JOIN customers c ON p.customer_id = c.id 
        LEFT JOIN invoices i ON p.invoice_id = i.id 
        WHERE $whereStr";

$total = db()->prepare("SELECT COUNT(*) FROM ($sql) AS count_table");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/payments/index.php?search='.urlencode($search).'&type='.urlencode($type));

$stmt = db()->prepare("$sql ORDER BY p.payment_date DESC, p.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$payments = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Payments Log</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Payments Log</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Payments</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/payments/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Record Payment</a>
</div>

<?php
$totalReceived = db()->query("SELECT COALESCE(SUM(amount),0) FROM payments")->fetchColumn();
$thisMonth     = db()->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE MONTH(payment_date)=MONTH(CURDATE()) AND YEAR(payment_date)=YEAR(CURDATE())")->fetchColumn();
$txnCount      = db()->query("SELECT COUNT(*) FROM payments")->fetchColumn();
?>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="crm-card p-4 d-flex align-items-center gap-3">
      <div class="stat-icon" style="background:rgba(34,197,94,.12);color:#16a34a;"><i class="bi bi-cash-stack fs-4"></i></div>
      <div><div class="text-muted small">Total Received</div><div class="fw-bold fs-5"><?= formatCurrency($totalReceived) ?></div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="crm-card p-4 d-flex align-items-center gap-3">
      <div class="stat-icon" style="background:rgba(99,102,241,.12);color:#4f46e5;"><i class="bi bi-calendar-check fs-4"></i></div>
      <div><div class="text-muted small">This Month</div><div class="fw-bold fs-5"><?= formatCurrency($thisMonth) ?></div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="crm-card p-4 d-flex align-items-center gap-3">
      <div class="stat-icon" style="background:rgba(249,115,22,.12);color:#ea580c;"><i class="bi bi-receipt fs-4"></i></div>
      <div><div class="text-muted small">Transactions</div><div class="fw-bold fs-5"><?= number_format($txnCount) ?></div></div>
    </div>
  </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search payments..." value="<?= e($search) ?>"></div>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search): ?><a href="<?= BASE_URL ?>/modules/payments/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>Date</th><th>Customer</th><th>Invoice</th><th>Method</th><th>Reference</th><th>Amount</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($payments)): ?>
          <tr><td colspan="6"><div class="empty-state"><i class="bi bi-credit-card"></i><p>No payments found</p></div></td></tr>
        <?php else: foreach($payments as $pay): ?>
        <tr>
          <td><?= formatDate($pay['payment_date']) ?></td>
          <td class="fw-semibold"><i class="bi bi-person me-1 text-muted"></i><?= e($pay['customer_name']) ?></td>
          <td>
            <?php if($pay['invoice_number']): ?>
                <a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $pay['invoice_id'] ?>"><?= e($pay['invoice_number']) ?></a>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td><?= e($pay['method']) ?></td>
          <td><?= e($pay['reference'] ?: '—') ?></td>
          <td class="fw-bold text-success">+ <?= formatCurrency($pay['amount']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $pay['invoice_id'] ?>" class="btn btn-icon btn-sm btn-outline-info" title="View Invoice"><i class="bi bi-file-text"></i></a>
            <a href="<?= BASE_URL ?>/modules/payments/delete.php?id=<?= $pay['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete this payment record?" title="Delete"><i class="bi bi-trash"></i></a>
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
