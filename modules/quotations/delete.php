<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('quotations', 'delete');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM quotations WHERE id=?");
$stmt->execute([$id]);
$q = $stmt->fetch();
if (!$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }
if (!$q['is_latest']) { setFlash('danger','Cannot delete a historical version. Only the latest version can be deleted.'); header('Location: '.BASE_URL.'/modules/quotations/view.php?id='.$id); exit; }

db()->prepare("DELETE FROM quotation_items WHERE quotation_id=?")->execute([$id]);
db()->prepare("DELETE FROM quotations WHERE id=?")->execute([$id]);
logActivity('quotations','delete',"Deleted quotation: {$q['quote_number']}",$id);
setFlash('success',"Quotation '{$q['quote_number']}' deleted.");
header('Location: '.BASE_URL.'/modules/quotations/index.php');
exit;
