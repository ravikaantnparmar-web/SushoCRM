<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM employees WHERE id=?");
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) { setFlash('danger','Employee not found.'); header('Location: '.BASE_URL.'/modules/employees/index.php'); exit; }

db()->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
logActivity('employees','delete',"Deleted employee: {$emp['name']}",$id);
setFlash('success',"Employee '{$emp['name']}' deleted.");
header('Location: '.BASE_URL.'/modules/employees/index.php');
exit;
