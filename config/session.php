<?php
require_once __DIR__ . '/config.php';

function startSecureSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_TIMEOUT,
            'path'     => '/',
            'secure'   => false,  // true in production (HTTPS)
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

function checkSessionTimeout(): void {
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            session_unset();
            session_destroy();
            header('Location: ' . BASE_URL . '/modules/auth/login.php?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }
}

startSecureSession();
checkSessionTimeout();
