<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM invoices WHERE id=?");
$stmt->execute([$id]);
$inv = $stmt->fetch();
if (!$inv) { setFlash('danger','Invoice not found.'); header('Location: '.BASE_URL.'/modules/invoices/index.php'); exit; }

// Balance is calculated dynamically now
db()->prepare("DELETE FROM invoice_items WHERE invoice_id=?")->execute([$id]);
db()->prepare("DELETE FROM payments WHERE invoice_id=?")->execute([$id]);
db()->prepare("DELETE FROM invoices WHERE id=?")->execute([$id]);
logActivity('invoices','delete',"Deleted invoice: {$inv['invoice_number']}",$id);
setFlash('success',"Invoice '{$inv['invoice_number']}' deleted.");
header('Location: '.BASE_URL.'/modules/invoices/index.php');
exit;
