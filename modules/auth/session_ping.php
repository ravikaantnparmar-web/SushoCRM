<?php
/**
 * session_ping.php
 * Heartbeat endpoint: returns session status, optionally extends the session.
 * Called by nav-guard.js every 60 seconds.
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

if (!isLoggedIn()) {
    echo json_encode(['valid' => false, 'remaining_seconds' => 0]);
    exit;
}

// Extend session if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['extend'])) {
    $_SESSION['last_activity'] = time();
    echo json_encode(['valid' => true, 'extended' => true, 'remaining_seconds' => SESSION_TIMEOUT]);
    exit;
}

// Return remaining session time
$lastActivity = $_SESSION['last_activity'] ?? time();
$elapsed      = time() - $lastActivity;
$remaining    = max(0, SESSION_TIMEOUT - $elapsed);

echo json_encode([
    'valid'             => $remaining > 0,
    'remaining_seconds' => $remaining,
    'session_timeout'   => SESSION_TIMEOUT,
]);
