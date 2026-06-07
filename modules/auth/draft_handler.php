<?php
/**
 * draft_handler.php
 * AJAX endpoint for auto-save draft CRUD.
 * Actions: save | load | delete
 * Called by nav-guard.js
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthenticated']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ── LOAD ─────────────────────────────────────────────────────────────────────
if ($action === 'load') {
    $draftKey = trim($_GET['key'] ?? '');
    if (!$draftKey) { echo json_encode(['draft' => null]); exit; }

    $stmt = db()->prepare(
        "SELECT draft_data, form_title, saved_at FROM form_drafts WHERE user_id = ? AND draft_key = ?"
    );
    $stmt->execute([$userId, $draftKey]);
    $row = $stmt->fetch();

    if (!$row) {
        echo json_encode(['draft' => null]);
        exit;
    }

    echo json_encode([
        'draft'      => json_decode($row['draft_data'], true),
        'form_title' => $row['form_title'],
        'saved_at'   => $row['saved_at'],
    ]);
    exit;
}

// ── SAVE ──────────────────────────────────────────────────────────────────────
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $draftKey  = trim($_POST['key'] ?? '');
    $draftData = $_POST['data'] ?? '';
    $formTitle = trim($_POST['form_title'] ?? '');

    if (!$draftKey || !$draftData) {
        echo json_encode(['error' => 'Missing key or data']);
        exit;
    }

    // Validate JSON
    $decoded = json_decode($draftData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }

    // Limit draft size to 500KB
    if (strlen($draftData) > 512000) {
        echo json_encode(['error' => 'Draft data too large']);
        exit;
    }

    $stmt = db()->prepare(
        "INSERT INTO form_drafts (user_id, draft_key, draft_data, form_title)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE draft_data = VALUES(draft_data),
                                 form_title = VALUES(form_title),
                                 saved_at   = CURRENT_TIMESTAMP"
    );
    $stmt->execute([$userId, $draftKey, $draftData, $formTitle]);

    echo json_encode(['saved' => true, 'saved_at' => date('Y-m-d H:i:s')]);
    exit;
}

// ── DELETE ────────────────────────────────────────────────────────────────────
if ($action === 'delete') {
    $draftKey = trim($_GET['key'] ?? $_POST['key'] ?? '');
    if (!$draftKey) { echo json_encode(['deleted' => false]); exit; }

    $stmt = db()->prepare(
        "DELETE FROM form_drafts WHERE user_id = ? AND draft_key = ?"
    );
    $stmt->execute([$userId, $draftKey]);

    echo json_encode(['deleted' => true]);
    exit;
}

// ── LIST (for admin/debug) ───────────────────────────────────────────────────
if ($action === 'list') {
    $stmt = db()->prepare(
        "SELECT draft_key, form_title, saved_at FROM form_drafts WHERE user_id = ? ORDER BY saved_at DESC"
    );
    $stmt->execute([$userId]);
    echo json_encode(['drafts' => $stmt->fetchAll()]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
