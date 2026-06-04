<?php
require 'c:/xampp/htdocs/SushobhaCRM/config/db.php';

$pdo = db();

echo "--- Fixing Invoices Module Schema ---\n\n";

// 1. Make order_id nullable (so invoices can be created without an order)
$pdo->exec("ALTER TABLE `invoices` MODIFY `order_id` INT UNSIGNED DEFAULT NULL");
echo "1. Made order_id nullable ✓\n";

// 2. Add missing columns to invoices
$cols = $pdo->query("SHOW COLUMNS FROM invoices")->fetchAll(PDO::FETCH_COLUMN);
$add = [];

if (!in_array('discount_type', $cols))
    $add[] = "ADD COLUMN `discount_type` ENUM('fixed','percent') DEFAULT 'fixed' AFTER `discount_amount`";
if (!in_array('discount_value', $cols))
    $add[] = "ADD COLUMN `discount_value` DECIMAL(12,2) DEFAULT 0.00 AFTER `discount_type`";
if (!in_array('terms', $cols))
    $add[] = "ADD COLUMN `terms` TEXT DEFAULT NULL AFTER `notes`";
if (!in_array('issued_date', $cols))
    $add[] = "ADD COLUMN `issued_date` DATE DEFAULT NULL";

if ($add) {
    $pdo->exec("ALTER TABLE `invoices` " . implode(', ', $add));
    echo "2. Added missing columns to invoices: " . implode(', ', array_map(fn($a) => explode('`', $a)[1], $add)) . " ✓\n";
} else {
    echo "2. Invoice columns already exist ✓\n";
}

// 3. Create invoice_items table
$pdo->exec("CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(250) NOT NULL,
  `qty` DECIMAL(10,2) NOT NULL DEFAULT 1,
  `unit` VARCHAR(30) DEFAULT 'Nos',
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `discount` DECIMAL(12,2) DEFAULT 0.00,
  `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB");
echo "3. Created invoice_items table ✓\n";

echo "\n--- All done! The Invoices module should now work. ---\n";
