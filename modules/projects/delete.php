<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if ($p) {
    db()->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    logActivity('projects','delete',"Deleted project: {$p['project_number']}");
    setFlash('success', 'Project deleted successfully.');
} else {
    setFlash('danger', 'Project not found.');
}

header('Location: '.BASE_URL.'/modules/projects/index.php');
exit;
