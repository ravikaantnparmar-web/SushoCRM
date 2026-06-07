<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('orders', 'delete');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$id]);
$o = $stmt->fetch();
if (!$o) { setFlash('danger','Order not found.'); header('Location: '.BASE_URL.'/modules/orders/index.php'); exit; }

db()->prepare("DELETE FROM order_items WHERE order_id=?")->execute([$id]);
db()->prepare("DELETE FROM orders WHERE id=?")->execute([$id]);
logActivity('orders','delete',"Deleted order: {$o['order_number']}",$id);
setFlash('success',"Order '{$o['order_number']}' deleted.");
header('Location: '.BASE_URL.'/modules/orders/index.php');
exit;
