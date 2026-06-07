<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('expenses', 'delete');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM expenses WHERE id=?");
$stmt->execute([$id]);
$ex = $stmt->fetch();
if (!$ex) { setFlash('danger','Expense not found.'); header('Location: '.BASE_URL.'/modules/expenses/index.php'); exit; }

db()->prepare("DELETE FROM expenses WHERE id=?")->execute([$id]);
logActivity('expenses','delete',"Deleted expense of ".formatCurrency($ex['amount']),$id);
setFlash('success',"Expense deleted successfully.");
header('Location: '.BASE_URL.'/modules/expenses/index.php');
exit;
