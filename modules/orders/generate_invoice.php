<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/modules/orders/index.php');
    exit;
}

$order_id = (int)($_POST['order_id'] ?? 0);
if (!$order_id) {
    setFlash('danger', 'Invalid Order ID.');
    header('Location: ' . BASE_URL . '/modules/orders/index.php');
    exit;
}

// Fetch Order
$stmt = db()->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('danger', 'Order not found.');
    header('Location: ' . BASE_URL . '/modules/orders/index.php');
    exit;
}

// Check if invoice exists
$invStmt = db()->prepare("SELECT id FROM invoices WHERE order_id = ? LIMIT 1");
$invStmt->execute([$order_id]);
if ($existingInvoice = $invStmt->fetch()) {
    setFlash('warning', 'An invoice has already been generated for this order.');
    header('Location: ' . BASE_URL . '/modules/invoices/view.php?id=' . $existingInvoice['id']);
    exit;
}

// Fetch Order Items
$itemsStmt = db()->prepare("SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC");
$itemsStmt->execute([$order_id]);
$orderItems = $itemsStmt->fetchAll();

if (empty($orderItems)) {
    setFlash('danger', 'Cannot generate an invoice for an order with no items.');
    header('Location: ' . BASE_URL . '/modules/orders/view.php?id=' . $order_id);
    exit;
}

try {
    db()->beginTransaction();

    $invoice_number = generateInvoiceNumber();
    $issued_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+7 days'));
    $status = 'draft';
    $notes = "Auto-generated from Order #" . $order['order_number'];
    
    // Check dynamic columns that might exist
    $columnsStmt = db()->query("SHOW COLUMNS FROM invoices LIKE 'discount_type'");
    $hasDiscountType = $columnsStmt->rowCount() > 0;
    
    $columnsTermsStmt = db()->query("SHOW COLUMNS FROM invoices LIKE 'terms'");
    $hasTerms = $columnsTermsStmt->rowCount() > 0;

    if ($hasDiscountType && $hasTerms) {
        $stmtInv = db()->prepare("INSERT INTO invoices (invoice_number, order_id, customer_id, status, issued_date, due_date, subtotal, discount_type, discount_value, discount_amount, tax_amount, total, paid_amount, balance_due, notes, terms, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInv->execute([
            $invoice_number, $order['id'], $order['customer_id'], $status, $issued_date, $due_date,
            $order['subtotal'], $order['discount_type'], $order['discount_value'], $order['discount_amount'],
            $order['tax_amount'], $order['total'], 0, $order['total'], $notes, "1. Payment is due within 7 days.", $_SESSION['user_id']
        ]);
    } else {
        $stmtInv = db()->prepare("INSERT INTO invoices (invoice_number, order_id, customer_id, status, issued_date, due_date, subtotal, discount_amount, tax_amount, total, paid_amount, balance_due, notes, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInv->execute([
            $invoice_number, $order['id'], $order['customer_id'], $status, $issued_date, $due_date,
            $order['subtotal'], $order['discount_amount'], $order['tax_amount'], $order['total'],
            0, $order['total'], $notes, $_SESSION['user_id']
        ]);
    }
    
    $invoice_id = db()->lastInsertId();

    $columnsDiscountItemStmt = db()->query("SHOW COLUMNS FROM invoice_items LIKE 'discount'");
    $hasItemDiscount = $columnsDiscountItemStmt->rowCount() > 0;

    if ($hasItemDiscount) {
        $stmtItem = db()->prepare("INSERT INTO invoice_items (invoice_id, product_id, description, qty, unit, unit_price, tax_rate, tax_amount, discount, line_total, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($orderItems as $idx => $item) {
            $stmtItem->execute([
                $invoice_id, $item['product_id'], $item['description'], $item['qty'], $item['unit'],
                $item['unit_price'], $item['tax_rate'], $item['tax_amount'], 0, $item['line_total'], $idx
            ]);
        }
    } else {
        $stmtItem = db()->prepare("INSERT INTO invoice_items (invoice_id, product_id, description, qty, unit, unit_price, tax_rate, tax_amount, line_total, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($orderItems as $idx => $item) {
            $stmtItem->execute([
                $invoice_id, $item['product_id'], $item['description'], $item['qty'], $item['unit'],
                $item['unit_price'], $item['tax_rate'], $item['tax_amount'], $item['line_total'], $idx
            ]);
        }
    }

    db()->commit();
    logActivity('invoices', 'create', "Auto-generated invoice '$invoice_number' from Order #{$order['order_number']}", $invoice_id);
    
    setFlash('success', "Invoice '$invoice_number' generated successfully.");
    header('Location: ' . BASE_URL . '/modules/invoices/view.php?id=' . $invoice_id);
    exit;

} catch (Exception $e) {
    db()->rollBack();
    setFlash('danger', 'An error occurred: ' . $e->getMessage());
    header('Location: ' . BASE_URL . '/modules/orders/view.php?id=' . $order_id);
    exit;
}
