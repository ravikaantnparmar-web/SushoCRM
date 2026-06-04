<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Change Password';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    // Validate
    $user = currentUser();
    if (!password_verify($current_pass, $user['password'])) {
        $errors['current_password'] = 'Current password is incorrect.';
    }
    if (strlen($new_pass) < 6) {
        $errors['new_password'] = 'New password must be at least 6 characters.';
    }
    if ($new_pass !== $confirm_pass) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = db()->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed, $_SESSION['user_id']])) {
            logActivity('auth', 'update', 'User changed their password');
            setFlash('success', 'Password updated successfully.');
            header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
            exit;
        } else {
            setFlash('danger', 'Failed to update password. Please try again.');
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Security Settings</div>
</div>
<div class="page-content">
<?= flashHtml() ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="crm-card">
            <div class="crm-card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-shield-lock text-primary me-2"></i>Change Your Password</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control <?= isset($errors['current_password'])?'is-invalid':'' ?>" required>
                        <?php if(isset($errors['current_password'])): ?><div class="invalid-feedback"><?= $errors['current_password'] ?></div><?php endif; ?>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control <?= isset($errors['new_password'])?'is-invalid':'' ?>" required>
                        <?php if(isset($errors['new_password'])): ?><div class="invalid-feedback"><?= $errors['new_password'] ?></div><?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control <?= isset($errors['confirm_password'])?'is-invalid':'' ?>" required>
                        <?php if(isset($errors['confirm_password'])): ?><div class="invalid-feedback"><?= $errors['confirm_password'] ?></div><?php endif; ?>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        <a href="<?= BASE_URL ?>/modules/dashboard/index.php" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
