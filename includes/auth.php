<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/modules/auth/login.php');
        exit;
    }
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    static $user = null;
    if ($user === null) {
        $stmt = db()->prepare("SELECT u.*, r.name AS role_name, r.slug AS role_slug, r.permissions FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
    return $user;
}

function currentRole(): string {
    $u = currentUser();
    return $u['role_slug'] ?? 'user';
}

function isSuperAdmin(): bool { return currentRole() === 'super_admin'; }
function isAdmin(): bool      { return in_array(currentRole(), ['super_admin', 'admin']); }

function requireRole(array $roles): void {
    requireLogin();
    if (!in_array(currentRole(), $roles)) {
        setFlash('danger', 'Access restricted to authorized personnel only.');
        header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
        exit;
    }
}

function hasPermission(string $module, string $action = 'view'): bool {
    $user = currentUser();
    if (!$user) return false;
    if (isAdmin()) return true; // Admins and Super Admins have full access
    $permissions = json_decode($user['permissions'] ?? '{}', true);
    return isset($permissions[$module]) && in_array($action, (array)$permissions[$module]);
}

function requirePermission(string $module, string $action = 'view'): void {
    requireLogin();
    if (!hasPermission($module, $action)) {
        setFlash('danger', 'You do not have permission to ' . $action . ' ' . $module . '.');
        header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
        exit;
    }
}

function loginUser(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['role_id']    = $user['role_id'];
    $_SESSION['last_activity'] = time();
    // Update last login
    db()->prepare("UPDATE users SET last_login = NOW(), login_attempts = 0 WHERE id = ?")->execute([$user['id']]);
}

function logoutUser(): void {
    session_unset();
    session_destroy();
}
