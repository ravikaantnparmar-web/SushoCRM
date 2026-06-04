<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin(); requireRole(['super_admin','admin']);
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT name FROM customers WHERE id=?"); $stmt->execute([$id]); $c = $stmt->fetch();
if (!$c) { setFlash('danger','Customer not found.'); header('Location: ' . BASE_URL . '/modules/customers/index.php'); exit; }
$has = db()->prepare("SELECT COUNT(*) FROM (SELECT id FROM quotations WHERE customer_id=? UNION SELECT id FROM orders WHERE customer_id=?) t");
$has->execute([$id,$id]);
if ($has->fetchColumn() > 0) { setFlash('danger','Cannot delete: customer has associated records.'); header('Location: ' . BASE_URL . '/modules/customers/index.php'); exit; }
db()->prepare("DELETE FROM customers WHERE id=?")->execute([$id]);
logActivity('customers','delete',"Deleted customer: {$c['name']}",$id);
setFlash('success',"Customer '{$c['name']}' deleted.");
header('Location: ' . BASE_URL . '/modules/customers/index.php'); exit;
