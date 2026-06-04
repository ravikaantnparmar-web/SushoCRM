<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$pr = $stmt->fetch();
if (!$pr) { setFlash('danger','Product not found.'); header('Location: '.BASE_URL.'/modules/products/index.php'); exit; }

db()->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
logActivity('products','delete',"Deleted product: {$pr['name']}",$id);
setFlash('success',"Product '{$pr['name']}' deleted.");
header('Location: '.BASE_URL.'/modules/products/index.php');
exit;
