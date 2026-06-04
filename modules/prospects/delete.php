<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare("UPDATE leads SET deleted_at = NOW(), updated_by = ? WHERE id = ?");
    if ($stmt->execute([$_SESSION['user_id'], $id])) {
        // Log activity
        db()->prepare("INSERT INTO lead_timeline (lead_id, user_id, action_type, description) VALUES (?,?,?,?)")
          ->execute([$id, $_SESSION['user_id'], 'Deleted', 'Lead moved to archive (soft deleted).']);
          
        setFlash('success', 'Lead archived successfully.');
    } else {
        setFlash('danger', 'Failed to archive lead.');
    }
}

header("Location: index.php");
exit;
