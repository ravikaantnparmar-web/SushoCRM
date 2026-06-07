<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Already logged in → redirect
if (isset($_SESSION['user_id'])) {
  header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
  exit;
}

$error = '';
$success = '';
$isValidToken = false;
$userId = null;
$tokenRaw = $_GET['token'] ?? '';
$emailRaw = $_GET['email'] ?? '';

if (empty($tokenRaw) || empty($emailRaw)) {
    $error = 'Invalid or missing password reset token.';
} else {
    try {
        $hashedToken = hash('sha256', $tokenRaw);
        // Find user by email and verify token hasn't expired
        $stmt = db()->prepare("SELECT id FROM users WHERE email = ? AND reset_token = ? AND reset_token_expires > NOW() AND is_active = 1 LIMIT 1");
        $stmt->execute([$emailRaw, $hashedToken]);
        $user = $stmt->fetch();

        if ($user) {
            $isValidToken = true;
            $userId = $user['id'];
        } else {
            $error = 'This password reset link is invalid or has expired. Please request a new one.';
        }
    } catch (Exception $e) {
        $error = 'An internal system error occurred.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isValidToken) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request (CSRF). Please refresh and try again.';
    } else {
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        // Strict Password Policy
        if (strlen($newPass) < 12) {
            $error = 'Password must be at least 12 characters.';
        } elseif (!preg_match('/[A-Z]/', $newPass)) {
            $error = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $newPass)) {
            $error = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $newPass)) {
            $error = 'Password must contain at least one number.';
        } elseif (!preg_match('/[^A-Za-z0-9]/', $newPass)) {
            $error = 'Password must contain at least one special character.';
        } elseif ($newPass !== $confirmPass) {
            $error = 'Passwords do not match.';
        } else {
            try {
                $options = ['cost' => 12];
                $hashedPwd = password_hash($newPass, PASSWORD_BCRYPT, $options);
                
                // Update password and invalidate the token (one-time use)
                $stmt = db()->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
                if ($stmt->execute([$hashedPwd, $userId])) {
                    logActivity('auth', 'password_reset_success', "Password successfully reset for email: {$emailRaw}");
                    $success = 'Password has been successfully reset. You can now sign in.';
                    $isValidToken = false; // Hide form
                } else {
                    $error = 'Failed to reset password. Please try again.';
                }
            } catch (Exception $e) {
                $error = 'An internal system error occurred.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password | <?= APP_NAME ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root { --brand-color: #4f46e5; --brand-hover: #4338ca; --text-dark: #0f172a; --text-muted: #64748b; --border-color: #cbd5e1; }
    body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #ffffff; height: 100vh; overflow: hidden; }
    .split-layout { display: flex; height: 100vh; width: 100vw; }
    .visual-column { flex: 0 0 65%; background: linear-gradient(135deg, #1f1b63 0%, #2f2a96 45%, #4f46e5 100%); display: flex; flex-direction: column; justify-content: center; padding: 6% 8%; color: #ffffff; position: relative; overflow: hidden; }
    .visual-column::before { content: ''; position: absolute; width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%); top: -10%; right: -10%; z-index: 1; pointer-events: none; }
    .visual-column::after { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0) 70%); bottom: -10%; left: -10%; z-index: 1; pointer-events: none; }
    .visual-content { position: relative; z-index: 2; max-width: 520px; }
    .brand-logo-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 45px; }
    .brand-logo-icon { color: #ffffff; display: flex; align-items: center; justify-content: center; }
    .brand-logo-text { font-size: 24px; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; }
    .headline { font-size: 44px; font-weight: 800; line-height: 1.2; margin-bottom: 20px; letter-spacing: -1px; }
    .description { font-size: 16px; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 45px; }
    .form-column { flex: 0 0 35%; background-color: #ffffff; display: flex; flex-direction: column; justify-content: center; padding: 40px 60px; overflow-y: auto; }
    .form-content { width: 100%; max-width: 360px; margin: 0 auto; }
    .welcome-header { margin-bottom: 32px; }
    .welcome-header h2 { font-size: 26px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; letter-spacing: -0.5px; }
    .welcome-header p { font-size: 14px; color: var(--text-muted); margin: 0; }
    .custom-input-group { margin-bottom: 20px; }
    .custom-input-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
    .custom-input-wrapper { position: relative; display: flex; align-items: center; }
    .custom-input-wrapper .prefix-icon { position: absolute; left: 14px; color: #94a3b8; font-size: 16px; pointer-events: none; }
    .custom-input-wrapper .custom-form-control { width: 100%; padding: 10px 14px 10px 42px; font-size: 14px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; transition: all 0.2s ease; color: var(--text-dark); background-color: #ffffff; height: 42px; }
    .custom-input-wrapper .custom-form-control:focus { border-color: var(--brand-color); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12); }
    .btn-brand-signin { width: 100%; background-color: var(--brand-color); border: none; border-radius: 8px; padding: 11px; color: #ffffff; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 8px; height: 42px; margin-bottom: 20px; }
    .btn-brand-signin:hover { background-color: var(--brand-hover); transform: translateY(-1px); }
    .back-to-login { display: block; text-align: center; color: var(--text-muted); font-size: 14px; text-decoration: none; font-weight: 500; transition: color 0.15s; }
    .back-to-login:hover { color: var(--brand-color); }
    @media (max-width: 991px) { .split-layout { flex-direction: column; } .visual-column { flex: 0 0 35%; padding: 40px; } .form-column { flex: 1 1 auto; padding: 40px; } }
  </style>
</head>

<body>
  <div class="split-layout">
    <div class="visual-column">
      <div class="visual-content">
        <div class="brand-logo-wrap">
          <div class="brand-logo-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C8.686 2 6 4.686 6 8V13.5C6 14.328 5.328 15 4.5 15H19.5C18.672 15 18 14.328 18 13.5V8C18 4.686 15.314 2 12 2Z" fill="currentColor" />
              <path d="M7 17C7 16.448 7.448 16 8 16H16C16.552 16 17 16.448 17 17C17 17.552 16.552 18 16 18H8C7.448 18 7 17.552 7 17Z" fill="currentColor" opacity="0.8" />
              <path d="M10 20C10 18.895 10.895 18 12 18C13.105 18 14 18.895 14 20C14 21.105 13.105 22 12 22C10.895 22 10 21.105 10 20Z" fill="currentColor" opacity="0.6" />
            </svg>
          </div>
          <div class="brand-logo-text">Sushobha CRM</div>
        </div>

        <h1 class="headline">Secure Password Reset</h1>
        <p class="description">We employ industry-standard cryptography to ensure your data remains protected. Regain access to your account securely.</p>
      </div>
    </div>

    <div class="form-column">
      <div class="form-content">
        <div class="welcome-header">
          <h2>Set New Password</h2>
          <p>Please enter your new strong password.</p>
        </div>

        <?php if ($success): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 py-2 rounded-3 mb-4">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span class="small"><?= e($success) ?></span>
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2 rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span class="small"><?= e($error) ?></span>
          </div>
        <?php endif; ?>

        <?php if ($isValidToken): ?>
          <form method="POST" novalidate>
            <?= csrfField() ?>

            <div class="custom-input-group">
              <label class="custom-input-label">New Password</label>
              <div class="custom-input-wrapper">
                <i class="bi bi-shield-lock prefix-icon"></i>
                <input type="password" name="new_password" class="custom-form-control" placeholder="12+ chars, upper, lower, num, special" required>
              </div>
            </div>

            <div class="custom-input-group">
              <label class="custom-input-label">Confirm Password</label>
              <div class="custom-input-wrapper">
                <i class="bi bi-shield-check prefix-icon"></i>
                <input type="password" name="confirm_password" class="custom-form-control" placeholder="Confirm your new password" required>
              </div>
            </div>

            <button type="submit" class="btn-brand-signin">
              Reset Password
            </button>
          </form>
        <?php endif; ?>
        
        <a href="<?= BASE_URL ?>/modules/auth/login.php" class="back-to-login"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
      </div>
    </div>
  </div>
</body>
</html>
