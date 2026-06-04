<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Master Contacts';
$search = sanitize($_GET['search'] ?? '');
$type = sanitize($_GET['type'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1'];
$params = [];
if ($search) { $where[] = '(name LIKE ? OR organization_name LIKE ? OR mobile LIKE ? OR email LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
if ($type) { $where[] = 'contact_type = ?'; $params[] = $type; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM contacts WHERE $whereStr");
$total->execute($params);
$totalCount = $total->fetchColumn();

$pag = paginate($totalCount, $per, $page, BASE_URL . '/modules/contacts/index.php?search=' . urlencode($search) . '&type=' . urlencode($type));

$stmt = db()->prepare("SELECT * FROM contacts WHERE $whereStr ORDER BY created_at DESC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$contacts = $stmt->fetchAll();

// Dashboard Stats
$statSql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN contact_type IN ('Architect', 'Interior Designer') THEN 1 ELSE 0 END) as designers,
        SUM(CASE WHEN contact_type IN ('Builder', 'Contractor', 'Developer') THEN 1 ELSE 0 END) as contractors,
        SUM(CASE WHEN contact_type IN ('Vendor', 'Channel Partner', 'Fabricator') THEN 1 ELSE 0 END) as vendors
    FROM contacts
";
$statStmt = db()->query($statSql);
$stats = $statStmt->fetch();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Master Contacts</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Contact Management</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Contacts</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/contacts/create.php" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add Contact</a>
</div>

<!-- Dashboard Widgets -->
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="bi bi-person-lines-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['total'] ?? 0)) ?></div>
                <div class="stat-label">Total Contacts</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="bi bi-vector-pen"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['designers'] ?? 0)) ?></div>
                <div class="stat-label">Architects & Designers</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="bi bi-building"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['contractors'] ?? 0)) ?></div>
                <div class="stat-label">Builders & Contractors</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="bi bi-truck"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= number_format((float)($stats['vendors'] ?? 0)) ?></div>
                <div class="stat-label">Vendors & Partners</div>
            </div>
        </div>
    </div>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search">
        <i class="bi bi-search"></i>
        <input type="text" name="search" placeholder="Search contacts..." value="<?= e($search) ?>">
      </div>
      <select name="type" class="form-select form-select-sm" style="width:150px">
        <option value="">All Types</option>
        <?php foreach(['Architect', 'Builder', 'Interior Designer', 'Contractor', 'Consultant', 'Developer', 'PMC', 'Fabricator', 'Vendor', 'Channel Partner', 'Owner', 'Other'] as $opt): ?>
          <option value="<?= $opt ?>" <?= $type===$opt?'selected':'' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if ($search || $type): ?>
        <a href="<?= BASE_URL ?>/modules/contacts/index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
      <?php endif; ?>
    </form>
    <div class="d-flex gap-2">
      <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr>
        <th>Name</th><th>Organization</th><th>Type</th><th>Mobile</th><th>Email</th><th>City</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php if (empty($contacts)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-person-lines-fill"></i><p>No contacts found</p></div></td></tr>
        <?php else: ?>
          <?php foreach ($contacts as $c): ?>
          <tr>
            <td>
              <a href="<?= BASE_URL ?>/modules/contacts/view.php?id=<?= $c['id'] ?>" class="fw-semibold text-dark"><?= e($c['name']) ?></a>
              <!-- Removed designation -->
            </td>
            <td><?= e($c['organization_name'] ?: '—') ?></td>
            <td><span class="badge bg-light text-dark border"><?= e($c['contact_type']) ?></span></td>
            <td><?= e($c['mobile'] ?: '—') ?></td>
            <td><?= e($c['email'] ?: '—') ?></td>
            <td><?= e($c['city'] ?: '—') ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?= BASE_URL ?>/modules/contacts/view.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-info" title="View Profile"><i class="bi bi-eye"></i></a>
                <a href="<?= BASE_URL ?>/modules/contacts/edit.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/modules/contacts/delete.php?id=<?= $c['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete contact <?= e($c['name']) ?>?" title="Delete"><i class="bi bi-trash"></i></a>
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