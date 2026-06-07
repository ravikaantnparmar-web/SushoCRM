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
$timeout = isset($_GET['timeout']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF check
  if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    $error = 'Invalid request. Please refresh and try again.';
    logActivity('auth', 'login_failed', 'CSRF token mismatch on login attempt.');
  } else {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Verify Captcha if needed
    $requiresCaptcha = ($_SESSION['login_failures'] ?? 0) >= 3;
    if ($requiresCaptcha) {
        $captchaInput = sanitize($_POST['captcha'] ?? '');
        if ($captchaInput !== (string)($_SESSION['captcha_answer'] ?? '')) {
            $error = 'Invalid CAPTCHA answer.';
            logActivity('auth', 'login_failed', 'CAPTCHA failed for email: ' . $email);
        }
    }

    if (empty($email) || empty($password)) {
      $error = 'Please enter your email and password.';
    } elseif (empty($error)) {
      try {
        $stmt = db()->prepare("SELECT u.*, r.slug AS role_slug FROM users u JOIN roles r ON u.role_id=r.id WHERE u.email = ? AND u.is_active = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
          // Check lockout
          if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $error = "Account is temporarily locked. Please try again later.";
            logActivity('auth', 'login_failed', 'Attempted login on locked account: ' . $email);
          } elseif (!password_verify($password, $user['password'])) {
            $attempts = $user['login_attempts'] + 1;
            $_SESSION['login_failures'] = ($_SESSION['login_failures'] ?? 0) + 1;
            if ($attempts >= MAX_LOGIN_ATTEMPTS) {
              $lockedUntil = date('Y-m-d H:i:s', time() + LOCKOUT_DURATION);
              db()->prepare("UPDATE users SET login_attempts=?, locked_until=? WHERE id=?")->execute([$attempts, $lockedUntil, $user['id']]);
              $error = 'Invalid email or password.';
              logActivity('auth', 'account_locked', 'Account locked due to too many failed attempts: ' . $email);
            } else {
              db()->prepare("UPDATE users SET login_attempts=? WHERE id=?")->execute([$attempts, $user['id']]);
              $error = "Invalid email or password.";
              logActivity('auth', 'login_failed', 'Invalid password attempt for: ' . $email);
            }
          } else {
            // Success
            require_once __DIR__ . '/../../includes/auth.php';
            $_SESSION['login_failures'] = 0; // reset failures
            unset($_SESSION['captcha_answer']);
            loginUser($user);
            logActivity('auth', 'login_success', 'User logged in successfully: ' . $user['email']);
            header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
            exit;
          }
        } else {
          $_SESSION['login_failures'] = ($_SESSION['login_failures'] ?? 0) + 1;
          $error = 'Invalid email or password.';
          logActivity('auth', 'login_failed', 'Login attempt with non-existent email: ' . $email);
        }
      } catch (Exception $e) {
          $error = 'An internal system error occurred. Please try again later.';
          // Do not expose database errors or stack trace to the user
      }
    }
  }
}

$requiresCaptcha = ($_SESSION['login_failures'] ?? 0) >= 3;
if ($requiresCaptcha) {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_answer'] = $num1 + $num2;
    $captchaQuestion = "What is $num1 + $num2?";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | <?= APP_NAME ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --brand-color: #4f46e5;
      --brand-hover: #4338ca;
      --text-dark: #0f172a;
      --text-muted: #64748b;
      --border-color: #cbd5e1;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #ffffff;
      height: 100vh;
      overflow: hidden;
    }

    .split-layout {
      display: flex;
      height: 100vh;
      width: 100vw;
    }

    /* Left visual column */
    .visual-column {
      flex: 0 0 65%;
      background: linear-gradient(135deg, #1f1b63 0%, #2f2a96 45%, #4f46e5 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 6% 8%;
      color: #ffffff;
      position: relative;
      overflow: hidden;
    }

    /* Glowing ambient circles in background */
    .visual-column::before {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
      top: -10%;
      right: -10%;
      z-index: 1;
      pointer-events: none;
    }

    .visual-column::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0) 70%);
      bottom: -10%;
      left: -10%;
      z-index: 1;
      pointer-events: none;
    }

    .visual-content {
      position: relative;
      z-index: 2;
      max-width: 520px;
    }

    .brand-logo-wrap {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 45px;
    }

    .brand-logo-icon {
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .brand-logo-text {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: -0.5px;
      color: #ffffff;
    }

    .headline {
      font-size: 44px;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 20px;
      letter-spacing: -1px;
    }

    .description {
      font-size: 16px;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 45px;
    }

    /* Feature Pills / Cards */
    .feature-card {
      display: flex;
      align-items: center;
      gap: 16px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 12px;
      padding: 14px 20px;
      margin-bottom: 16px;
      max-width: 340px;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      transition: all 0.25s ease;
    }

    .feature-card:hover {
      background: rgba(255, 255, 255, 0.12);
      transform: translateY(-2px);
    }

    .feature-icon-box {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #ffffff;
      flex-shrink: 0;
    }

    .feature-card-text {
      font-size: 14px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.95);
      letter-spacing: 0.2px;
    }

    /* Right form column */
    .form-column {
      flex: 0 0 35%;
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 40px 60px;
      overflow-y: auto;
    }

    .form-content {
      width: 100%;
      max-width: 360px;
      margin: 0 auto;
    }

    .welcome-header {
      margin-bottom: 32px;
    }

    .welcome-header h2 {
      font-size: 26px;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .welcome-header p {
      font-size: 14px;
      color: var(--text-muted);
      margin: 0;
    }

    /* Styled Input Custom Group */
    .custom-input-group {
      margin-bottom: 20px;
    }

    .custom-input-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 8px;
    }

    .custom-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .custom-input-wrapper .prefix-icon {
      position: absolute;
      left: 14px;
      color: #94a3b8;
      font-size: 16px;
      pointer-events: none;
    }

    .custom-input-wrapper .suffix-icon {
      position: absolute;
      right: 14px;
      color: #94a3b8;
      font-size: 16px;
      cursor: pointer;
      transition: color 0.15s;
    }

    .custom-input-wrapper .suffix-icon:hover {
      color: var(--brand-color);
    }

    .custom-input-wrapper .custom-form-control {
      width: 100%;
      padding: 10px 14px 10px 42px;
      font-size: 14px;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      outline: none;
      transition: all 0.2s ease;
      color: var(--text-dark);
      background-color: #ffffff;
      height: 42px;
    }

    .custom-input-wrapper .custom-form-control::placeholder {
      color: #94a3b8;
    }

    .custom-input-wrapper .custom-form-control:focus {
      border-color: var(--brand-color);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    /* Checkbox & Forgot link */
    .form-extras {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
      font-size: 13px;
    }

    .form-extras .remember-me-wrap {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .form-extras .remember-me-wrap input {
      width: 15px;
      height: 15px;
      border-radius: 4px;
      border: 1px solid var(--border-color);
      cursor: pointer;
    }

    .form-extras .remember-me-wrap label {
      color: var(--text-muted);
      cursor: pointer;
      user-select: none;
    }

    .form-extras .forgot-pass-link {
      color: var(--brand-color);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.15s;
    }

    .form-extras .forgot-pass-link:hover {
      color: var(--brand-hover);
      text-decoration: underline;
    }

    /* Submit Button */
    .btn-brand-signin {
      width: 100%;
      background-color: var(--brand-color);
      border: none;
      border-radius: 8px;
      padding: 11px;
      color: #ffffff;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 42px;
    }

    .btn-brand-signin:hover {
      background-color: var(--brand-hover);
      transform: translateY(-1px);
    }

    .btn-brand-signin:active {
      transform: translateY(0);
    }

    .btn-brand-signin:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.4);
    }

    /* Responsive details */
    @media (max-width: 991px) {
      .split-layout {
        flex-direction: column;
      }

      .visual-column {
        flex: 0 0 35%;
        padding: 40px;
        justify-content: center;
      }

      .brand-logo-wrap {
        margin-bottom: 20px;
      }

      .headline {
        font-size: 28px;
        margin-bottom: 10px;
      }

      .description {
        font-size: 14px;
        margin-bottom: 20px;
      }

      .feature-card {
        display: none;
        /* Hide feature cards on small screens to save space */
      }

      .form-column {
        flex: 1 1 auto;
        padding: 40px;
      }
    }
  </style>
</head>

<body>
  <div class="split-layout">
    <!-- LEFT VISUAL COLUMN -->
    <div class="visual-column">
      <div class="visual-content">
        <!-- Logo -->
        <div class="brand-logo-wrap">
          <div class="brand-logo-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M12 2C8.686 2 6 4.686 6 8V13.5C6 14.328 5.328 15 4.5 15H19.5C18.672 15 18 14.328 18 13.5V8C18 4.686 15.314 2 12 2Z"
                fill="currentColor" />
              <path
                d="M7 17C7 16.448 7.448 16 8 16H16C16.552 16 17 16.448 17 17C17 17.552 16.552 18 16 18H8C7.448 18 7 17.552 7 17Z"
                fill="currentColor" opacity="0.8" />
              <path
                d="M10 20C10 18.895 10.895 18 12 18C13.105 18 14 18.895 14 20C14 21.105 13.105 22 12 22C10.895 22 10 21.105 10 20Z"
                fill="currentColor" opacity="0.6" />
            </svg>
          </div>
          <div class="brand-logo-text">Sushobha CRM</div>
        </div>

        <!-- Typography -->
        <h1 class="headline">Welcome to<br>Sushobha CRM</h1>
        <p class="description">Manage enquiries, projects, architects, dealers, quotations, and customer relationships
          from one powerful platform.</p>

        <!-- Features Glass Widgets -->
        <div class="feature-card">
          <div class="feature-icon-box">
            <i class="bi bi-people-fill"></i>
          </div>
          <div class="feature-card-text">Lead & Customer Management</div>
        </div>

        <div class="feature-card">
          <div class="feature-icon-box">
            <i class="bi bi-file-earmark-text-fill"></i>
          </div>
          <div class="feature-card-text">Quotation & Project Tracking</div>
        </div>

        <div class="feature-card">
          <div class="feature-icon-box">
            <i class="bi bi-bar-chart-line-fill"></i>
          </div>
          <div class="feature-card-text">Real-Time Sales Insights</div>
        </div>
      </div>
    </div>

    <!-- RIGHT FORM COLUMN -->
    <div class="form-column">
      <div class="form-content">
        <div class="welcome-header">
          <h2>Welcome Back</h2>
          <p>Please enter your credentials to login.</p>
        </div>

        <!-- Alerts block -->
        <?php if ($timeout): ?>
          <div class="alert alert-warning text-center py-2 small rounded-3">Session expired. Please log in again.</div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2 rounded-3">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span class="small"><?= e($error) ?></span>
          </div>
        <?php endif; ?>

        <!-- Credentials Form -->
        <form method="POST" novalidate>
          <?= csrfField() ?>

          <div class="custom-input-group">
            <label class="custom-input-label">Email Address</label>
            <div class="custom-input-wrapper">
              <i class="bi bi-envelope prefix-icon"></i>
              <input type="email" name="email" class="custom-form-control" placeholder="ravikaant@sushobha.com"
                value="<?= e($_POST['email'] ?? '') ?>" required autocomplete="email">
            </div>
          </div>

          <div class="custom-input-group">
            <label class="custom-input-label">Password</label>
            <div class="custom-input-wrapper">
              <i class="bi bi-shield-lock prefix-icon"></i>
              <input type="password" name="password" id="passwordInput" class="custom-form-control"
                placeholder="Enter your password" required autocomplete="current-password">
              <i class="bi bi-eye suffix-icon" id="eyeIcon" onclick="togglePwd()"></i>
            </div>
          </div>

          <?php if ($requiresCaptcha): ?>
          <div class="custom-input-group">
            <label class="custom-input-label">Security Verification: <?= $captchaQuestion ?></label>
            <div class="custom-input-wrapper">
              <i class="bi bi-robot prefix-icon"></i>
              <input type="text" name="captcha" class="custom-form-control" placeholder="Enter the sum" required autocomplete="off">
            </div>
          </div>
          <?php endif; ?>

          <div class="form-extras">
            <div class="remember-me-wrap">
              <input type="checkbox" id="remember_me">
              <label for="remember_me">Remember me</label>
            </div>
            <a href="<?= BASE_URL ?>/modules/auth/forgot-password.php" class="forgot-pass-link">Forgot Password?</a>
          </div>

          <button type="submit" class="btn-brand-signin">
            Sign In
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePwd() {
      const inp = document.getElementById('passwordInput');
      const ico = document.getElementById('eyeIcon');
      if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash suffix-icon';
      } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye suffix-icon';
      }
    }
  </script>
</body>

</html>