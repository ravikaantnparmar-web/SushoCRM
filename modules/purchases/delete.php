<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM purchases WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('danger','Purchase not found.'); header('Location: '.BASE_URL.'/modules/purchases/index.php'); exit; }

// Balance is calculated dynamically now
db()->prepare("DELETE FROM purchase_items WHERE purchase_id=?")->execute([$id]);
db()->prepare("DELETE FROM purchases WHERE id=?")->execute([$id]);
logActivity('purchases','delete',"Deleted purchase: {$p['purchase_number']}",$id);
setFlash('success',"Purchase '{$p['purchase_number']}' deleted.");
header('Location: '.BASE_URL.'/modules/purchases/index.php');
exit;
