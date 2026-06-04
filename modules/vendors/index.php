<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Vendors';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if ($search) { $where[] = '(v.name LIKE ? OR v.company LIKE ? OR v.email LIKE ? OR v.phone LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
if ($status) { $where[] = 'v.status = ?'; $params[] = $status; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM vendors v WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/vendors/index.php?search='.urlencode($search).'&status='.urlencode($status));

$stmt = db()->prepare("SELECT v.* FROM vendors v WHERE $whereStr ORDER BY v.name ASC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$vendors = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Vendors</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Vendor Management</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Vendors</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/vendors/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Vendor</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search vendors..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <option value="active" <?= $status==='active'?'selected':'' ?>>Active</option>
        <option value="inactive" <?= $status==='inactive'?'selected':'' ?>>Inactive</option>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/vendors/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr><th>#</th><th>Vendor</th><th>Company</th><th>Phone</th><th>City</th><th>GST</th><th>Balance</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($vendors)): ?>
          <tr><td colspan="9"><div class="empty-state"><i class="bi bi-truck"></i><p>No vendors found</p><a href="<?= BASE_URL ?>/modules/vendors/create.php" class="btn btn-primary btn-sm">Add First Vendor</a></div></td></tr>
        <?php else: foreach($vendors as $i => $v): ?>
        <tr>
          <td class="text-muted small"><?= ($pag['offset']+$i+1) ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="stat-icon warning" style="width:32px;height:32px;border-radius:10px;font-size:13px;flex-shrink:0"><?= strtoupper(substr($v['name'],0,1)) ?></div>
              <div>
                <a href="<?= BASE_URL ?>/modules/vendors/view.php?id=<?= $v['id'] ?>" class="fw-semibold text-dark"><?= e($v['name']) ?></a>
                <?php if($v['vendor_code']): ?><div class="text-muted" style="font-size:11px"><?= e($v['vendor_code']) ?></div><?php endif; ?>
              </div>
            </div>
          </td>
          <td><?= e($v['company'] ?: '—') ?></td>
          <td><?= e($v['phone'] ?: '—') ?></td>
          <td><?= e($v['city'] ?: '—') ?></td>
          <td><?= e($v['gst_number'] ?: '—') ?></td>
          <td class="<?= $v['outstanding_balance'] > 0 ? 'text-danger fw-semibold' : '' ?>"><?= formatCurrency($v['outstanding_balance']) ?></td>
          <td><?= statusBadge($v['status']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/modules/vendors/view.php?id=<?= $v['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/modules/vendors/edit.php?id=<?= $v['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="<?= BASE_URL ?>/modules/vendors/delete.php?id=<?= $v['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete vendor <?= e($v['name']) ?>?"><i class="bi bi-trash"></i></a>
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
