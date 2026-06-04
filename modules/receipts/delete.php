<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $db = db();
    $stmt = $db->prepare("SELECT title FROM receipts WHERE id = ?");
    $stmt->execute([$id]);
    $receipt = $stmt->fetch();
    
    if ($receipt) {
        $db->prepare("DELETE FROM receipts WHERE id = ?")->execute([$id]);
        logActivity('receipts', 'delete', "Deleted money receipt: " . $receipt['title'], $id);
        setFlash('success', 'Receipt record deleted.');
    }
}

header('Location: index.php');
exit;
