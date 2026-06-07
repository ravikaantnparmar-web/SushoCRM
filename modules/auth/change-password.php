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
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        setFlash('danger', 'Invalid security token (CSRF). Please try again.');
        logActivity('auth', 'CSRF Failure', 'Failed CSRF validation on password change.');
        header('Location: change-password.php');
        exit;
    }

    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    // Validate
    $user = currentUser();
    if (!password_verify($current_pass, $user['password'])) {
        $errors['current_password'] = 'Current password is incorrect.';
        logActivity('auth', 'password_change_failed', 'Incorrect current password provided.');
    }
    
    // Strict Password Policy
    if (strlen($new_pass) < 12) {
        $errors['new_password'] = 'Password must be at least 12 characters.';
    } elseif (!preg_match('/[A-Z]/', $new_pass)) {
        $errors['new_password'] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $new_pass)) {
        $errors['new_password'] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $new_pass)) {
        $errors['new_password'] = 'Password must contain at least one number.';
    } elseif (!preg_match('/[^A-Za-z0-9]/', $new_pass)) {
        $errors['new_password'] = 'Password must contain at least one special character.';
    }

    if ($new_pass !== $confirm_pass) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        try {
            $options = ['cost' => 12];
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT, $options);
            $stmt = db()->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed, $_SESSION['user_id']])) {
                logActivity('auth', 'update', 'User changed their password securely.');
                setFlash('success', 'Password updated successfully. Please sign in again.');
                // Invalidate session to enforce re-authentication
                session_unset();
                session_destroy();
                header('Location: ' . BASE_URL . '/modules/auth/login.php');
                exit;
            } else {
                setFlash('danger', 'Failed to update password. Please try again.');
            }
        } catch (Exception $e) {
            setFlash('danger', 'An internal server error occurred.');
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
                    <?= csrfField() ?>
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
