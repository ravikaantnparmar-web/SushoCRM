<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM payments WHERE id = ?");
$stmt->execute([$id]);
$pay = $stmt->fetch();

if (!$pay) {
    setFlash('danger', 'Payment record not found.');
    header('Location: ' . BASE_URL . '/modules/payments/index.php');
    exit;
}

// Reverse the paid_amount on the invoice
$inv = db()->prepare("SELECT paid_amount, total FROM invoices WHERE id = ?");
$inv->execute([$pay['invoice_id']]);
$invoice = $inv->fetch();

if ($invoice) {
    $newPaid    = max(0, $invoice['paid_amount'] - $pay['amount']);
    $newBalance = $invoice['total'] - $newPaid;
    $newStatus  = ($newPaid <= 0) ? 'sent' : 'partial';
    db()->prepare("UPDATE invoices SET paid_amount=?, balance_due=?, status=? WHERE id=?")
        ->execute([$newPaid, $newBalance, $newStatus, $pay['invoice_id']]);
}

db()->prepare("DELETE FROM payments WHERE id = ?")->execute([$id]);
logActivity('payments', 'delete', "Deleted payment of " . formatCurrency($pay['amount']), $id);
setFlash('success', 'Payment record deleted and invoice balance restored.');
header('Location: ' . BASE_URL . '/modules/payments/index.php');
exit;
