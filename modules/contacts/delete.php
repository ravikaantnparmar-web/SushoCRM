<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }
if (!isAdmin()) { setFlash('danger', 'Unauthorized access.'); header('Location: index.php'); exit; }

try {
    db()->prepare("DELETE FROM contacts WHERE id = ?")->execute([$id]);
    setFlash('success', 'Master Contact deleted successfully.');
} catch (Exception $e) {
    setFlash('danger', 'Error: ' . $e->getMessage());
}
header('Location: index.php');
exit;