<?php
$content = file_get_contents('modules/quotations/create.php');
$pos = strpos($content, '<form method="POST" id="quoteForm">');
$bottom = substr($content, $pos);

$top = <<<'PHP'
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Create Quotation';
$errors = [];
$customers = getAllCustomers();
$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote_number = sanitize($_POST['quote_number'] ?? '');
    if (!$quote_number) $quote_number = generateQuoteNumber();
    $customer_id = (int)($_POST['customer_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? 'draft');
    $valid_until = sanitize($_POST['valid_until'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $terms = sanitize($_POST['terms'] ?? '');
    $discount_type = sanitize($_POST['discount_type'] ?? 'fixed');
    $discount_value = (float)($_POST['discount_value'] ?? 0);

    // Line items array
    $items = $_POST['items'] ?? [];

    if (!$customer_id) $errors['customer_id'] = 'Please select a customer.';
    if (empty($items)) $errors['items'] = 'At least one item is required.';

    if (!$errors) {
        try {
            db()->beginTransaction();
            
            $subtotal = 0;
            
            foreach ($items as $item) {
                $qty = (float)($item['qty'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $subtotal += ($qty * $price);
            }
            
            $discount_amount = $discount_type === 'percent' ? ($subtotal * ($discount_value / 100)) : $discount_value;
            $discount_ratio = $subtotal > 0 ? ($discount_amount / $subtotal) : 0;
            
            $tax_amount = 0;
            foreach ($items as $item) {
                $qty = (float)($item['qty'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                
                $line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);
                
                $tax_amount += $line_tax;
            }
            
            $total = ($subtotal - $discount_amount) + $tax_amount;

            $stmt = db()->prepare("INSERT INTO quotations (quote_number, customer_id, status, valid_until, subtotal, discount_type, discount_value, discount_amount, tax_amount, total, notes, terms, created_by)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$quote_number, $customer_id, $status, $valid_until?:null, $subtotal, $discount_type, $discount_value, $discount_amount, $tax_amount, $total, $notes, $terms, $_SESSION['user_id']]);
            $quote_id = db()->lastInsertId();

            $stmtItem = db()->prepare("INSERT INTO quotation_items (quotation_id, product_id, description, qty, unit, unit_price, tax_rate, tax_amount, discount, line_total, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            foreach ($items as $i => $item) {
                $pid = (int)($item['product_id'] ?? 0) ?: null;
                $desc = sanitize($item['description'] ?? '');
                $qty = (float)($item['qty'] ?? 1);
                $unit = sanitize($item['unit'] ?? 'Nos');
                $price = (float)($item['unit_price'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                $line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);
                $stmtItem->execute([$quote_id, $pid, $desc, $qty, $unit, $price, $tax_rate, $line_tax, 0, $line_total, $i]);
            }

            db()->commit();
            logActivity('quotations','create',"Created quotation: $quote_number",$quote_id);
            setFlash('success',"Quotation '$quote_number' created.");
            header('Location: '.BASE_URL.'/modules/quotations/view.php?id='.$quote_id);
            exit;
        } catch (Exception $e) {
            db()->rollBack();
            $errors['general'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Create Quotation</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Create Quotation</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/index.php">Quotations</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/quotations/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
PHP;

file_put_contents('modules/quotations/create.php', $top . $bottom);
echo "Fixed quotations/create.php";
