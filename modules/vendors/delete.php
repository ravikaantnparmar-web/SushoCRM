<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM vendors WHERE id=?");
$stmt->execute([$id]);
$v = $stmt->fetch();
if (!$v) { setFlash('danger','Vendor not found.'); header('Location: '.BASE_URL.'/modules/vendors/index.php'); exit; }

db()->prepare("DELETE FROM vendors WHERE id=?")->execute([$id]);
logActivity('vendors','delete',"Deleted vendor: {$v['name']}",$id);
setFlash('success',"Vendor '{$v['name']}' deleted.");
header('Location: '.BASE_URL.'/modules/vendors/index.php');
exit;
