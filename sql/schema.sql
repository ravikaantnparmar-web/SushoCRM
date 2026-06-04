-- ============================================================
-- SushobhaCRM - Full Database Schema
-- MySQL 8.0+ | utf8mb4 | InnoDB
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:30";

-- Database
CREATE DATABASE IF NOT EXISTS `sushobha_crm` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sushobha_crm`;

-- ============================================================
-- ROLES
-- ============================================================
CREATE TABLE `roles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `permissions` JSON DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- USERS
-- ============================================================
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT UNSIGNED NOT NULL DEFAULT 5,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `reset_token` VARCHAR(100) DEFAULT NULL,
  `reset_token_expires` DATETIME DEFAULT NULL,
  `last_login` DATETIME DEFAULT NULL,
  `login_attempts` INT DEFAULT 0,
  `locked_until` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- COMPANY SETTINGS
-- ============================================================
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- CUSTOMERS
-- ============================================================
CREATE TABLE `customers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customer_code` VARCHAR(20) UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `company` VARCHAR(150) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `alt_phone` VARCHAR(20) DEFAULT NULL,
  `gst_number` VARCHAR(20) DEFAULT NULL,
  `pan_number` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `pincode` VARCHAR(10) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT 'India',
  `customer_type` ENUM('Dealer', 'Distributor', 'Architect', 'Interior Designer', 'Builder', 'Contractor', 'Retail Customer', 'Corporate Client', 'Vendor/Supplier', 'Channel Partner') DEFAULT 'Retail Customer',
  `business_category` ENUM('Residential', 'Commercial', 'Hospitality', 'Retail', 'Industrial') DEFAULT 'Residential',
  `industry_type` ENUM('Construction', 'Interior Design', 'Real Estate', 'Furniture', 'Luxury Products', 'Architecture Firm') DEFAULT 'Real Estate',
  `preferred_communication` ENUM('Call', 'WhatsApp', 'Email', 'Meeting') DEFAULT 'Call',
  `credit_limit` DECIMAL(12,2) DEFAULT 0.00,
  `outstanding_balance` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('Prospect', 'Active', 'Inactive', 'Lost', 'Blacklisted') DEFAULT 'Active',
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_customer_name` (`name`),
  INDEX `idx_customer_email` (`email`)
) ENGINE=InnoDB;

-- ============================================================
-- PROSPECTS / LEADS
-- ============================================================
CREATE TABLE `prospects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `company` VARCHAR(150) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `source` ENUM('website','referral','cold_call','social_media','exhibition','other') DEFAULT 'other',
  `status` ENUM('new','contacted','qualified','proposal','negotiation','won','lost') DEFAULT 'new',
  `priority` ENUM('low','medium','high') DEFAULT 'medium',
  `expected_value` DECIMAL(12,2) DEFAULT 0.00,
  `follow_up_date` DATE DEFAULT NULL,
  `assigned_to` INT UNSIGNED DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `lost_reason` TEXT DEFAULT NULL,
  `converted_customer_id` INT UNSIGNED DEFAULT NULL,
  `converted_at` DATETIME DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`converted_customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_prospect_status` (`status`)
) ENGINE=InnoDB;

-- ============================================================
-- PROSPECT FOLLOW-UPS
-- ============================================================
CREATE TABLE `prospect_followups` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `prospect_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `type` ENUM('call','email','meeting','demo','other') DEFAULT 'call',
  `notes` TEXT DEFAULT NULL,
  `next_follow_up` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`prospect_id`) REFERENCES `prospects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- PRODUCT CATEGORIES
-- ============================================================
CREATE TABLE `product_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- PRODUCTS & SERVICES
-- ============================================================
CREATE TABLE `products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `sku` VARCHAR(50) UNIQUE,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `type` ENUM('product','service') DEFAULT 'product',
  `unit` VARCHAR(30) DEFAULT 'Nos',
  `purchase_price` DECIMAL(12,2) DEFAULT 0.00,
  `selling_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) DEFAULT 18.00,
  `stock_qty` DECIMAL(10,2) DEFAULT 0.00,
  `min_stock` DECIMAL(10,2) DEFAULT 0.00,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_product_name` (`name`),
  INDEX `idx_product_sku` (`sku`)
) ENGINE=InnoDB;

-- ============================================================
-- VENDORS
-- ============================================================
CREATE TABLE `vendors` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `vendor_code` VARCHAR(20) UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `company` VARCHAR(150) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `gst_number` VARCHAR(20) DEFAULT NULL,
  `pan_number` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `pincode` VARCHAR(10) DEFAULT NULL,
  `bank_name` VARCHAR(100) DEFAULT NULL,
  `bank_account` VARCHAR(30) DEFAULT NULL,
  `bank_ifsc` VARCHAR(15) DEFAULT NULL,
  `outstanding_balance` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- QUOTATIONS
-- ============================================================
CREATE TABLE `quotations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quote_number` VARCHAR(30) NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NOT NULL,
  `status` ENUM('draft','sent','accepted','rejected','converted','expired') DEFAULT 'draft',
  `valid_until` DATE DEFAULT NULL,
  `subtotal` DECIMAL(12,2) DEFAULT 0.00,
  `discount_type` ENUM('fixed','percent') DEFAULT 'fixed',
  `discount_value` DECIMAL(10,2) DEFAULT 0.00,
  `discount_amount` DECIMAL(12,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `total` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `terms` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_quote_status` (`status`)
) ENGINE=InnoDB;

-- ============================================================
-- QUOTATION ITEMS
-- ============================================================
CREATE TABLE `quotation_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quotation_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(250) NOT NULL,
  `qty` DECIMAL(10,2) NOT NULL DEFAULT 1,
  `unit` VARCHAR(30) DEFAULT 'Nos',
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `discount` DECIMAL(10,2) DEFAULT 0.00,
  `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- ORDERS
-- ============================================================
CREATE TABLE `orders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(30) NOT NULL UNIQUE,
  `quotation_id` INT UNSIGNED DEFAULT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `status` ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_date` DATE DEFAULT NULL,
  `payment_status` ENUM('unpaid','partial','paid') DEFAULT 'unpaid',
  `subtotal` DECIMAL(12,2) DEFAULT 0.00,
  `discount_type` ENUM('fixed', 'percent') DEFAULT 'fixed',
  `discount_value` DECIMAL(10,2) DEFAULT 0.00,
  `discount_amount` DECIMAL(12,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `shipping_charges` DECIMAL(10,2) DEFAULT 0.00,
  `total` DECIMAL(12,2) DEFAULT 0.00,
  `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `shipping_address` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_order_status` (`status`),
  INDEX `idx_order_payment` (`payment_status`)
) ENGINE=InnoDB;

-- ============================================================
-- ORDER ITEMS
-- ============================================================
CREATE TABLE `order_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(250) NOT NULL,
  `qty` DECIMAL(10,2) NOT NULL DEFAULT 1,
  `unit` VARCHAR(30) DEFAULT 'Nos',
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- INVOICES
-- ============================================================
CREATE TABLE `invoices` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_number` VARCHAR(30) NOT NULL UNIQUE,
  `order_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `status` ENUM('draft','sent','paid','partial','overdue','cancelled') DEFAULT 'draft',
  `issued_date` DATE NOT NULL,
  `due_date` DATE DEFAULT NULL,
  `subtotal` DECIMAL(12,2) DEFAULT 0.00,
  `discount_amount` DECIMAL(12,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `total` DECIMAL(12,2) DEFAULT 0.00,
  `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
  `balance_due` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_invoice_status` (`status`)
) ENGINE=InnoDB;

-- ============================================================
-- PAYMENTS
-- ============================================================
CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_date` DATE NOT NULL,
  `method` ENUM('cash','bank_transfer','cheque','upi','card','other') DEFAULT 'cash',
  `reference` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- PURCHASES
-- ============================================================
CREATE TABLE `purchases` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_number` VARCHAR(30) NOT NULL UNIQUE,
  `vendor_id` INT UNSIGNED NOT NULL,
  `status` ENUM('pending','received','partial','cancelled') DEFAULT 'pending',
  `payment_status` ENUM('unpaid','partial','paid') DEFAULT 'unpaid',
  `purchase_date` DATE NOT NULL,
  `subtotal` DECIMAL(12,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `total` DECIMAL(12,2) DEFAULT 0.00,
  `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
  `notes` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendor_id`) REFERENCES `vendors`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- PURCHASE ITEMS
-- ============================================================
CREATE TABLE `purchase_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(250) NOT NULL,
  `qty` DECIMAL(10,2) NOT NULL DEFAULT 1,
  `unit` VARCHAR(30) DEFAULT 'Nos',
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) DEFAULT 0.00,
  `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (`purchase_id`) REFERENCES `purchases`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- EXPENSE CATEGORIES
-- ============================================================
CREATE TABLE `expense_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- EXPENSES
-- ============================================================
CREATE TABLE `expenses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `expense_date` DATE NOT NULL,
  `payment_method` ENUM('cash','bank_transfer','card','cheque','other') DEFAULT 'cash',
  `reference` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `attachment` VARCHAR(255) DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `expense_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_expense_date` (`expense_date`)
) ENGINE=InnoDB;

-- ============================================================
-- EMPLOYEES
-- ============================================================
CREATE TABLE `employees` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `emp_code` VARCHAR(20) UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `department` VARCHAR(100) DEFAULT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `join_date` DATE DEFAULT NULL,
  `salary` DECIMAL(12,2) DEFAULT 0.00,
  `salary_type` ENUM('monthly','weekly','daily','hourly') DEFAULT 'monthly',
  `address` TEXT DEFAULT NULL,
  `emergency_contact` VARCHAR(100) DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `bank_name` VARCHAR(100) DEFAULT NULL,
  `bank_account` VARCHAR(30) DEFAULT NULL,
  `bank_ifsc` VARCHAR(15) DEFAULT NULL,
  `status` ENUM('active','inactive','terminated') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- ATTENDANCE
-- ============================================================
CREATE TABLE `attendance` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id` INT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `status` ENUM('present','absent','half_day','leave','holiday') DEFAULT 'present',
  `check_in` TIME DEFAULT NULL,
  `check_out` TIME DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_attendance` (`employee_id`, `date`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SALARY RECORDS
-- ============================================================
CREATE TABLE `salary_records` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id` INT UNSIGNED NOT NULL,
  `month` INT NOT NULL,
  `year` INT NOT NULL,
  `basic_salary` DECIMAL(12,2) DEFAULT 0.00,
  `allowances` DECIMAL(12,2) DEFAULT 0.00,
  `deductions` DECIMAL(12,2) DEFAULT 0.00,
  `net_salary` DECIMAL(12,2) DEFAULT 0.00,
  `payment_date` DATE DEFAULT NULL,
  `payment_method` ENUM('cash','bank_transfer','cheque') DEFAULT 'bank_transfer',
  `status` ENUM('pending','paid') DEFAULT 'pending',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_salary` (`employee_id`, `month`, `year`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- NOTIFICATIONS
-- ============================================================
CREATE TABLE `notifications` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT DEFAULT NULL,
  `type` ENUM('info','success','warning','danger') DEFAULT 'info',
  `link` VARCHAR(255) DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- ACTIVITY LOGS
-- ============================================================
CREATE TABLE `activity_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `module` VARCHAR(50) DEFAULT NULL,
  `action` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `record_id` INT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_log_module` (`module`),
  INDEX `idx_log_created` (`created_at`)
) ENGINE=InnoDB;

-- ============================================================
-- TASKS
-- ============================================================
CREATE TABLE `tasks` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `assigned_to` INT UNSIGNED DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `related_module` VARCHAR(50) DEFAULT NULL,
  `related_id` INT UNSIGNED DEFAULT NULL,
  `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
  `status` ENUM('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `due_date` DATE DEFAULT NULL,
  `completed_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- NOTES
-- ============================================================
CREATE TABLE `notes` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `module` VARCHAR(50) NOT NULL,
  `record_id` INT UNSIGNED NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_notes_module` (`module`, `record_id`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
