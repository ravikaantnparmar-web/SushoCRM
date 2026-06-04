<?php
$dir = 'modules/contacts';
if (!is_dir($dir)) mkdir($dir, 0777, true);

// 1. index.php
$indexCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

\$pageTitle = 'Master Contacts';
\$search = sanitize(\$_GET['search'] ?? '');
\$type = sanitize(\$_GET['type'] ?? '');
\$page   = max(1, (int)(\$_GET['page'] ?? 1));
\$per    = RECORDS_PER_PAGE;

\$where = ['1=1'];
\$params = [];
if (\$search) { \$where[] = '(name LIKE ? OR organization_name LIKE ? OR mobile LIKE ? OR email LIKE ?)'; \$params = array_merge(\$params, ["%\$search%","%\$search%","%\$search%","%\$search%"]); }
if (\$type) { \$where[] = 'contact_type = ?'; \$params[] = \$type; }
\$whereStr = implode(' AND ', \$where);

\$total = db()->prepare("SELECT COUNT(*) FROM contacts WHERE \$whereStr");
\$total->execute(\$params);
\$totalCount = \$total->fetchColumn();

\$pag = paginate(\$totalCount, \$per, \$page, BASE_URL . '/modules/contacts/index.php?search=' . urlencode(\$search) . '&type=' . urlencode(\$type));

\$stmt = db()->prepare("SELECT * FROM contacts WHERE \$whereStr ORDER BY created_at DESC LIMIT \$per OFFSET {\$pag['offset']}");
\$stmt->execute(\$params);
\$contacts = \$stmt->fetchAll();

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

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search">
        <i class="bi bi-search"></i>
        <input type="text" name="search" placeholder="Search contacts..." value="<?= e(\$search) ?>">
      </div>
      <select name="type" class="form-select form-select-sm" style="width:150px">
        <option value="">All Types</option>
        <?php foreach(['Architect', 'Builder', 'Interior Designer', 'Contractor', 'Consultant', 'Developer', 'PMC', 'Fabricator', 'Vendor', 'Channel Partner', 'Owner', 'Other'] as \$opt): ?>
          <option value="<?= \$opt ?>" <?= \$type===\$opt?'selected':'' ?>><?= \$opt ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if (\$search || \$type): ?>
        <a href="<?= BASE_URL ?>/modules/contacts/index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
      <?php endif; ?>
    </form>
    <div class="d-flex gap-2">
      <span class="text-muted small align-self-center"><?= number_format(\$totalCount) ?> records</span>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0">
      <thead><tr>
        <th>Name</th><th>Organization</th><th>Type</th><th>Mobile</th><th>Email</th><th>City</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php if (empty(\$contacts)): ?>
          <tr><td colspan="7"><div class="empty-state"><i class="bi bi-person-lines-fill"></i><p>No contacts found</p></div></td></tr>
        <?php else: ?>
          <?php foreach (\$contacts as \$c): ?>
          <tr>
            <td>
              <a href="<?= BASE_URL ?>/modules/contacts/view.php?id=<?= \$c['id'] ?>" class="fw-semibold text-dark"><?= e(\$c['name']) ?></a>
              <?php if(\$c['designation']): ?><div class="small text-muted"><?= e(\$c['designation']) ?></div><?php endif; ?>
            </td>
            <td><?= e(\$c['organization_name'] ?: '—') ?></td>
            <td><span class="badge bg-light text-dark border"><?= e(\$c['contact_type']) ?></span></td>
            <td><?= e(\$c['mobile'] ?: '—') ?></td>
            <td><?= e(\$c['email'] ?: '—') ?></td>
            <td><?= e(\$c['city'] ?: '—') ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?= BASE_URL ?>/modules/contacts/view.php?id=<?= \$c['id'] ?>" class="btn btn-icon btn-sm btn-outline-info" title="View Profile"><i class="bi bi-eye"></i></a>
                <a href="<?= BASE_URL ?>/modules/contacts/edit.php?id=<?= \$c['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/modules/contacts/delete.php?id=<?= \$c['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete contact <?= e(\$c['name']) ?>?" title="Delete"><i class="bi bi-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if (\$pag['total_pages'] > 1): ?>
  <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
    <small class="text-muted">Showing <?= (\$pag['offset']+1) ?>–<?= min(\$pag['offset']+\$per,\$totalCount) ?> of <?= \$totalCount ?></small>
    <?= paginationHtml(\$pag) ?>
  </div>
  <?php endif; ?>
</div>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
PHP;
file_put_contents("$dir/index.php", $indexCode);

// 2. create.php
$createCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

\$pageTitle = 'Add Master Contact';
include __DIR__ . '/../../includes/header.php';
\$contactTypes = ['Architect', 'Builder', 'Interior Designer', 'Contractor', 'Consultant', 'Developer', 'PMC', 'Fabricator', 'Vendor', 'Channel Partner', 'Owner', 'Other'];
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Add Contact</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Create New Master Contact</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/contacts/index.php">Contacts</a></li><li class="breadcrumb-item active">Add</li></ol></nav>
  </div>
</div>

<form action="save.php" method="POST" enctype="multipart/form-data">
  <div class="crm-card mb-4">
    <div class="crm-card-header"><h2 class="crm-card-title">Contact Information</h2></div>
    <div class="crm-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Contact Type <span class="text-danger">*</span></label>
          <select name="contact_type" class="form-select" required>
            <?php foreach(\$contactTypes as \$t): ?>
              <option value="<?= \$t ?>"><?= \$t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Organization / Company Name</label>
          <input type="text" name="organization_name" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Designation</label>
          <input type="text" name="designation" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Mobile</label>
          <input type="text" name="mobile" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        
        <div class="col-12"><hr class="my-2"></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Address</label>
          <textarea name="address" class="form-control" rows="2"></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">City</label>
          <input type="text" name="city" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">State</label>
          <input type="text" name="state" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Pincode</label>
          <input type="text" name="pincode" class="form-control">
        </div>

        <div class="col-12"><hr class="my-2"></div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Website</label>
          <input type="url" name="website" class="form-control" placeholder="https://">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">GST Number</label>
          <input type="text" name="gst_number" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <div class="text-end pb-4">
    <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary px-4">Save Contact</button>
  </div>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
PHP;
file_put_contents("$dir/create.php", $createCode);

// 3. save.php
$saveCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if (\$_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

try {
    \$sql = "INSERT INTO contacts (contact_type, name, organization_name, designation, mobile, whatsapp, email, address, city, state, pincode, website, gst_number) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    \$stmt = db()->prepare(\$sql);
    \$stmt->execute([
        \$_POST['contact_type'] ?? 'Other',
        \$_POST['name'],
        \$_POST['organization_name'] ?: null,
        \$_POST['designation'] ?: null,
        \$_POST['mobile'] ?: null,
        \$_POST['whatsapp'] ?: null,
        \$_POST['email'] ?: null,
        \$_POST['address'] ?: null,
        \$_POST['city'] ?: null,
        \$_POST['state'] ?: null,
        \$_POST['pincode'] ?: null,
        \$_POST['website'] ?: null,
        \$_POST['gst_number'] ?: null
    ]);
    
    \$id = db()->lastInsertId();
    setFlash('success', 'Master Contact created successfully.');
    header("Location: view.php?id=\$id");
} catch (Exception \$e) {
    setFlash('danger', 'Failed to save contact: ' . \$e->getMessage());
    header('Location: create.php');
}
exit;
PHP;
file_put_contents("$dir/save.php", $saveCode);

// 4. edit.php
$editCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

\$id = (int)(\$_GET['id'] ?? 0);
\$stmt = db()->prepare("SELECT * FROM contacts WHERE id = ?");
\$stmt->execute([\$id]);
\$c = \$stmt->fetch();
if (!\$c) { setFlash('danger', 'Contact not found.'); header('Location: index.php'); exit; }

\$pageTitle = 'Edit Master Contact';
include __DIR__ . '/../../includes/header.php';
\$contactTypes = ['Architect', 'Builder', 'Interior Designer', 'Contractor', 'Consultant', 'Developer', 'PMC', 'Fabricator', 'Vendor', 'Channel Partner', 'Owner', 'Other'];
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Contact</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Edit Master Contact</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/contacts/index.php">Contacts</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
</div>

<form action="update.php" method="POST">
  <input type="hidden" name="id" value="<?= \$id ?>">
  <div class="crm-card mb-4">
    <div class="crm-card-header"><h2 class="crm-card-title">Contact Information</h2></div>
    <div class="crm-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="<?= e(\$c['name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Contact Type <span class="text-danger">*</span></label>
          <select name="contact_type" class="form-select" required>
            <?php foreach(\$contactTypes as \$t): ?>
              <option value="<?= \$t ?>" <?= \$c['contact_type'] === \$t ? 'selected' : '' ?>><?= \$t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Organization / Company Name</label>
          <input type="text" name="organization_name" class="form-control" value="<?= e(\$c['organization_name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Designation</label>
          <input type="text" name="designation" class="form-control" value="<?= e(\$c['designation']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Mobile</label>
          <input type="text" name="mobile" class="form-control" value="<?= e(\$c['mobile']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control" value="<?= e(\$c['whatsapp']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="<?= e(\$c['email']) ?>">
        </div>
        
        <div class="col-12"><hr class="my-2"></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Address</label>
          <textarea name="address" class="form-control" rows="2"><?= e(\$c['address']) ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">City</label>
          <input type="text" name="city" class="form-control" value="<?= e(\$c['city']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">State</label>
          <input type="text" name="state" class="form-control" value="<?= e(\$c['state']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Pincode</label>
          <input type="text" name="pincode" class="form-control" value="<?= e(\$c['pincode']) ?>">
        </div>

        <div class="col-12"><hr class="my-2"></div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Website</label>
          <input type="url" name="website" class="form-control" value="<?= e(\$c['website']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">GST Number</label>
          <input type="text" name="gst_number" class="form-control" value="<?= e(\$c['gst_number']) ?>">
        </div>
      </div>
    </div>
  </div>
  <div class="text-end pb-4">
    <a href="view.php?id=<?= \$id ?>" class="btn btn-outline-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
  </div>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
PHP;
file_put_contents("$dir/edit.php", $editCode);

// 5. update.php
$updateCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

\$id = (int)(\$_POST['id'] ?? 0);
if (\$id <= 0 || \$_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

try {
    \$sql = "UPDATE contacts SET contact_type=?, name=?, organization_name=?, designation=?, mobile=?, whatsapp=?, email=?, address=?, city=?, state=?, pincode=?, website=?, gst_number=? WHERE id=?";
    \$stmt = db()->prepare(\$sql);
    \$stmt->execute([
        \$_POST['contact_type'] ?? 'Other',
        \$_POST['name'],
        \$_POST['organization_name'] ?: null,
        \$_POST['designation'] ?: null,
        \$_POST['mobile'] ?: null,
        \$_POST['whatsapp'] ?: null,
        \$_POST['email'] ?: null,
        \$_POST['address'] ?: null,
        \$_POST['city'] ?: null,
        \$_POST['state'] ?: null,
        \$_POST['pincode'] ?: null,
        \$_POST['website'] ?: null,
        \$_POST['gst_number'] ?: null,
        \$id
    ]);
    
    setFlash('success', 'Master Contact updated successfully.');
    header("Location: view.php?id=\$id");
} catch (Exception \$e) {
    setFlash('danger', 'Failed to update contact: ' . \$e->getMessage());
    header("Location: edit.php?id=\$id");
}
exit;
PHP;
file_put_contents("$dir/update.php", $updateCode);

// 6. delete.php
$deleteCode = <<<PHP
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

\$id = (int)(\$_GET['id'] ?? 0);
if (\$id <= 0) { header('Location: index.php'); exit; }
if (!isAdmin()) { setFlash('danger', 'Unauthorized access.'); header('Location: index.php'); exit; }

try {
    db()->prepare("DELETE FROM contacts WHERE id = ?")->execute([\$id]);
    setFlash('success', 'Master Contact deleted successfully.');
} catch (Exception \$e) {
    setFlash('danger', 'Error: ' . \$e->getMessage());
}
header('Location: index.php');
exit;
PHP;
file_put_contents("$dir/delete.php", $deleteCode);

echo "Contacts module scaffold generated.\n";
