<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

if (isLoggedIn()) {
    logActivity('auth', 'logout', 'User logged out: ' . ($_SESSION['user_email'] ?? ''));
}
logoutUser();
header('Location: ' . BASE_URL . '/modules/auth/login.php');
exit;
