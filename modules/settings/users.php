<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireRole(['super_admin', 'admin']);

$pageTitle = 'Manage Users';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $pass = password_hash('password123', PASSWORD_DEFAULT);
        $role = (int)sanitize($_POST['role']);
        $status = (int)sanitize($_POST['status']);
        $rights = json_encode($_POST['access_rights'] ?? ['Read']);
        
        $stmt = db()->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            setFlash('danger', 'Email already exists.');
        } else {
            db()->prepare("INSERT INTO users (name, email, password, role_id, is_active, access_rights) VALUES (?,?,?,?,?,?)")->execute([$name, $email, $pass, $role, $status, $rights]);
            logActivity('settings', 'create', "Added user: $email", db()->lastInsertId());
            setFlash('success', 'User added successfully.');
        }
    } elseif ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $role = (int)sanitize($_POST['role']);
        $status = (int)sanitize($_POST['status']);
        $rights = json_encode($_POST['access_rights'] ?? ['Read']);
        
        $stmt = db()->prepare("SELECT id FROM users WHERE email=? AND id!=?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            setFlash('danger', 'Email already exists.');
        } else {
            db()->prepare("UPDATE users SET name=?, email=?, role_id=?, is_active=?, access_rights=? WHERE id=?")->execute([$name, $email, $role, $status, $rights, $id]);
            logActivity('settings', 'update', "Updated user info: $email", $id);
            setFlash('success', 'User updated successfully.');
        }
    } elseif ($_POST['action'] === 'reset_password') {
        $id = (int)$_POST['id'];
        $newPass = password_hash('password123', PASSWORD_DEFAULT);
        db()->prepare("UPDATE users SET password=? WHERE id=?")->execute([$newPass, $id]);
        setFlash('success', 'Password reset to default: password123');
        logActivity('settings', 'update', "Reset password for user ID: $id", $id);
    } elseif ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        if ($id === $_SESSION['user_id']) {
            setFlash('danger', 'You cannot delete yourself.');
        } else {
            db()->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
            logActivity('settings', 'delete', "Deleted user ID: $id", $id);
            setFlash('success', 'User deleted successfully.');
        }
    }
    header('Location: '.BASE_URL.'/modules/settings/users.php');
    exit;
}

$users = db()->query("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.name ASC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Manage Users</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>System Users</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/settings/index.php">Settings</a></li><li class="breadcrumb-item active">Users</li></ol></nav>
  </div>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-plus-lg me-1"></i>Add User</button>
</div>

<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table crm-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Rights</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td class="fw-semibold text-dark"><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><span class="badge <?= $u['role_name']==='Admin'?'bg-danger':'bg-secondary' ?>"><?= e($u['role_name']) ?></span></td>
                    <td>
                        <?php 
                        $rightsArr = json_decode($u['access_rights'] ?? '["Read"]', true);
                        if(is_array($rightsArr)):
                            foreach($rightsArr as $r): ?>
                                <span class="badge bg-light text-dark border me-1" style="font-size: 0.65rem;"><?= e($r) ?></span>
                            <?php endforeach; 
                        endif; ?>
                    </td>
                    <td><?= statusBadge($u['is_active'] ? 'active' : 'inactive') ?></td>
                    <td><?= formatDate($u['created_at']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $u['id'] ?>" title="Edit Settings"><i class="bi bi-pencil"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Reset password to password123?');">
                            <input type="hidden" name="action" value="reset_password">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm btn-icon btn-outline-warning" title="Reset Password"><i class="bi bi-key"></i></button>
                        </form>
                        <?php if($u['id'] !== $_SESSION['user_id']): ?>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm btn-icon btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                      <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= e($u['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= e($u['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-shield-lock me-1"></i>User Access *</label>
                                <div class="p-3 border rounded bg-light-subtle">
                                    <div class="row g-2">
                                        <?php 
                                        $userRights = json_decode($u['access_rights'] ?? '["Read"]', true) ?: [];
                                        foreach(['Read', 'Write', 'Modify', 'Delete', 'View', 'Approve'] as $r): ?>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="access_rights[]" value="<?= $r ?>" id="edit_right_<?= $u['id'] ?>_<?= $r ?>" <?= in_array($r, $userRights)?'checked':'' ?>>
                                                <label class="form-check-label" for="edit_right_<?= $u['id'] ?>_<?= $r ?>" style="font-size: 0.8rem;">
                                                    <?= $r ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-select">
                                        <option value="5" <?= $u['role_id']==5?'selected':'' ?>>User</option>
                                        <option value="4" <?= $u['role_id']==4?'selected':'' ?>>Accountant</option>
                                        <option value="3" <?= $u['role_id']==3?'selected':'' ?>>Manager</option>
                                        <option value="2" <?= $u['role_id']==2?'selected':'' ?>>Admin</option>
                                        <option value="1" <?= $u['role_id']==1?'selected':'' ?>>Super Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" <?= $u['is_active']==1?'selected':'' ?>>Active</option>
                                        <option value="0" <?= $u['is_active']==0?'selected':'' ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid mt-2"><button type="submit" class="btn btn-primary">Save Changes</button></div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div></div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold"><i class="bi bi-shield-lock me-1"></i>User Access *</label>
                <div class="p-3 border rounded bg-light-subtle">
                    <div class="row g-2">
                        <?php foreach(['Read', 'Write', 'Modify', 'Delete', 'View', 'Approve'] as $r): ?>
                        <div class="col-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="access_rights[]" value="<?= $r ?>" id="add_right_<?= $r ?>" <?= $r==='Read'||$r==='View'?'checked':'' ?>>
                                <label class="form-check-label" for="add_right_<?= $r ?>" style="font-size: 0.8rem;">
                                    <?= $r ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Temporary Password</label>
                <input type="password" name="password" class="form-control" required minlength="6" value="password123">
                <div class="form-text small">Default: password123</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="5">User</option>
                        <option value="4">Accountant</option>
                        <option value="3">Manager</option>
                        <option value="2">Admin</option>
                        <option value="1">Super Admin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="d-grid mt-2"><button type="submit" class="btn btn-primary">Create User</button></div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
