<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Purchases';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if ($search) { $where[] = '(p.purchase_number LIKE ? OR v.name LIKE ? OR v.company LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'p.status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM purchases p JOIN vendors v ON p.vendor_id = v.id WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/purchases/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT p.*, v.name AS vendor_name, v.company AS vendor_company FROM purchases p JOIN vendors v ON p.vendor_id=v.id WHERE $whereStr ORDER BY p.created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$purchases = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Purchases</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Purchases</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Purchases</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/purchases/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Purchase</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search purchases..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['pending','received','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/purchases/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>PO Number</th><th>Vendor</th><th>Date</th><th>Total Amount</th><th>Amount Paid</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($purchases)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-bag"></i><p>No purchases found</p><a href="<?= BASE_URL ?>/modules/purchases/create.php" class="btn btn-primary btn-sm">Create First Purchase</a></div></td></tr>
        <?php else: foreach($purchases as $p): ?>
        <tr>
          <td><a href="<?= BASE_URL ?>/modules/purchases/view.php?id=<?= $p['id'] ?>" class="fw-semibold text-primary"><?= e($p['purchase_number']) ?></a></td>
          <td>
            <div class="fw-semibold text-dark"><?= e($p['vendor_name']) ?></div>
            <?php if($p['vendor_company']): ?><div class="text-muted small"><?= e($p['vendor_company']) ?></div><?php endif; ?>
          </td>
          <td><?= $p['purchase_date'] ? formatDate($p['purchase_date']) : formatDate($p['created_at']) ?></td>
          <td class="fw-bold"><?= formatCurrency($p['total']) ?></td>
          <td class="text-success"><?= formatCurrency($p['paid_amount']) ?></td>
          <td><?= statusBadge($p['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/purchases/view.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/purchases/edit.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/purchases/delete.php?id=<?= $p['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete purchase <?= e($p['purchase_number']) ?>?"><i class="bi bi-trash"></i></a>
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
