-- ============================================================
-- SushobhaCRM - Master Tables Migration
-- Run this AFTER schema.sql and leads_revamp.sql
-- Converts all hardcoded PHP dropdowns to DB-driven masters
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
USE `sushobha_crm`;

-- ============================================================
-- LEAD STATUSES
-- ============================================================
CREATE TABLE IF NOT EXISTS `lead_statuses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `status_name` VARCHAR(100) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `lead_statuses` (`status_name`, `color_code`, `sort_order`) VALUES
('New',             '#3b82f6', 1),
('Open',            '#6366f1', 2),
('Contact Attempt', '#8b5cf6', 3),
('Contacted',       '#0ea5e9', 4),
('Followup',        '#f59e0b', 5),
('Qualified',       '#10b981', 6),
('Proposal Sent',   '#06b6d4', 7),
('Catalogue Sent',  '#14b8a6', 8),
('Negotiation',     '#f97316', 9),
('Won',             '#22c55e', 10),
('Closed',          '#16a34a', 11),
('Lost',            '#ef4444', 12),
('Duplicate',       '#94a3b8', 13),
('Junk',            '#64748b', 14)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

-- ============================================================
-- LEAD PRIORITIES
-- ============================================================
CREATE TABLE IF NOT EXISTS `lead_priorities` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `priority_name` VARCHAR(100) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `lead_priorities` (`priority_name`, `color_code`, `sort_order`) VALUES
('Cold Lead',       '#93c5fd', 1),
('Warm Lead',       '#fdba74', 2),
('Hot Lead',        '#f87171', 3),
('Qualified Lead',  '#34d399', 4),
('Converted Lead',  '#a78bfa', 5),
('Hold Lead',       '#fbbf24', 6),
('Lost Lead',       '#94a3b8', 7)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

-- ============================================================
-- LEAD SOURCES
-- ============================================================
CREATE TABLE IF NOT EXISTS `lead_sources` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `source_name` VARCHAR(150) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `lead_sources` (`source_name`, `color_code`, `sort_order`) VALUES
('Digital Marketing',   '#3b82f6', 1),
('Architect',           '#8b5cf6', 2),
('Builder',             '#f59e0b', 3),
('Contractor',          '#f97316', 4),
('Google Ads',          '#4285F4', 5),
('Instagram',           '#E1306C', 6),
('Facebook',            '#1877F2', 7),
('Offline Marketing',   '#64748b', 8),
('Exhibition',          '#06b6d4', 9),
('Newspaper',           '#78716c', 10),
('Referral',            '#22c55e', 11),
('Dealer',              '#a78bfa', 12),
('Employee',            '#10b981', 13),
('Existing Customer',   '#ec4899', 14),
('WhatsApp',            '#25D366', 15),
('Cold Calling',        '#94a3b8', 16),
('Export Inquiry',      '#0ea5e9', 17)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

-- ============================================================
-- LEAD TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `lead_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `lead_types` (`type_name`, `color_code`, `sort_order`) VALUES
('Website',         '#3b82f6', 1),
('Instagram',       '#E1306C', 2),
('Facebook',        '#1877F2', 3),
('Google Ads',      '#4285F4', 4),
('WhatsApp',        '#25D366', 5),
('Referral',        '#22c55e', 6),
('Dealer Network',  '#a78bfa', 7),
('Exhibition',      '#06b6d4', 8),
('Cold Calling',    '#94a3b8', 9),
('Export Inquiry',  '#0ea5e9', 10)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

-- ============================================================
-- SITE STAGES
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_stages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `stage_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `site_stages` (`stage_name`, `sort_order`) VALUES
('Blueprint',                   1),
('Estimation Stage',            2),
('3D Discussion',               3),
('Interior Work',               4),
('False Ceiling Work',          5),
('Modular Kitchen',             6),
('Bedroom Work',                7),
('Living Hall',                 8),
('Bath & Toilet Installation',  9),
('Glass Work',                  10),
('Flooring Work',               11),
('Painting Work',               12),
('Electrical Work',             13),
('Plumbing Work',               14),
('Final Finishing',             15),
('Renovation Work',             16),
('Possession Stage',            17)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- PROJECT TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `project_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `project_types` (`type_name`, `sort_order`) VALUES
('Residential',         1),
('Commercial',          2),
('Industrial',          3),
('Institutional',       4),
('Real Estate',         5),
('Turnkey',             6),
('Hospitality',         7),
('Renovation',          8),
('Interior',            9),
('Landscape',           10),
('PMC',                 11),
('Design Consultancy',  12)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- PRODUCT TYPES (Bespoke/Turnkey/etc.)
-- ============================================================
CREATE TABLE IF NOT EXISTS `lead_product_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `lead_product_types` (`type_name`, `sort_order`) VALUES
('Bespoke',     1),
('Turnkey',     2),
('Supply Only', 3),
('Labour Only', 4)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- INTERESTED PRODUCTS (Lead Requirements)
-- ============================================================
CREATE TABLE IF NOT EXISTS `interested_products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_name` VARCHAR(200) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `interested_products` (`product_name`, `sort_order`) VALUES
('Partition Systems',           1),
('Shower Enclosures',           2),
('Flush Doors',                 3),
('Cabinets & Storage Solutions',4),
('Illuminated Walls',           5),
('Glass Surface',               6),
('LED Mirrors',                 7),
('Modular Kitchen',             8),
('Wardrobe',                    9),
('Vanity Units',                10),
('Decorative Panels',           11),
('Office Partitions',           12),
('Sliding Doors',               13),
('Cladding Panels',             14),
('Handrails & Balustrades',     15)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- MEETING TYPES (Unified from all sources)
-- ============================================================
CREATE TABLE IF NOT EXISTS `meeting_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `meeting_types` (`type_name`, `sort_order`) VALUES
('Site Visit',          1),
('Office Meeting',      2),
('Telephonic',          3),
('Virtual Meeting',     4),
('Quotation Review',    5),
('Negotiation',         6),
('Project Review',      7),
('Follow-up',           8),
('Dealer Network',      9),
('Client Discussion',   10),
('Material Discussion', 11),
('Demo',                12)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- MEETING STATUSES
-- ============================================================
CREATE TABLE IF NOT EXISTS `meeting_statuses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `status_name` VARCHAR(100) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `meeting_statuses` (`status_name`, `color_code`, `sort_order`) VALUES
('Scheduled',   '#3b82f6', 1),
('Completed',   '#22c55e', 2),
('Postponed',   '#f59e0b', 3),
('Cancelled',   '#ef4444', 4),
('Rescheduled', '#8b5cf6', 5)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

-- ============================================================
-- CONTACT TYPES / DESIGNATIONS (Unified)
-- ============================================================
CREATE TABLE IF NOT EXISTS `contact_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `contact_types` (`type_name`, `sort_order`) VALUES
('Owner',           1),
('Entrepreneur',    2),
('Manager',         3),
('Architect',       4),
('Engineer',        5),
('Contractor',      6),
('Purchase Head',   7),
('Site Supervisor', 8),
('Interior Designer',9),
('Builder',         10),
('Consultant',      11),
('Other',           12)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- COMPANY TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `company_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(100) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `company_types` (`type_name`, `sort_order`) VALUES
('Individual',      1),
('Partnership',     2),
('Pvt Ltd',         3),
('Ltd',             4),
('LLP',             5),
('Proprietorship',  6),
('Trust',           7),
('NGO',             8)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- INDUSTRY TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `industry_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `industry_types` (`type_name`, `sort_order`) VALUES
('Manufacturing',       1),
('Trading',             2),
('Service',             3),
('Retail',              4),
('Wholesale',           5),
('Construction',        6),
('Interior Design',     7),
('Real Estate',         8),
('Furniture',           9),
('Luxury Products',     10),
('Architecture Firm',   11),
('Hospitality',         12),
('IT & Technology',     13),
('Healthcare',          14),
('Education',           15)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- BUSINESS CATEGORIES
-- ============================================================
CREATE TABLE IF NOT EXISTS `business_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `business_categories` (`category_name`, `sort_order`) VALUES
('B2B',     1),
('B2C',     2),
('D2C',     3),
('Export',  4),
('Import',  5),
('B2G',     6)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- SALES STAGES
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_stages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `stage_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `sales_stages` (`stage_name`, `sort_order`) VALUES
('Inquiry',                 1),
('Qualification',           2),
('Requirement Gathering',   3),
('Proposal',                4),
('Negotiation',             5),
('Conversion',              6),
('Execution',               7),
('Completed',               8)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- CUSTOMER TYPES (for customer module)
-- ============================================================
CREATE TABLE IF NOT EXISTS `customer_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(150) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `customer_types` (`type_name`, `sort_order`) VALUES
('Dealer',              1),
('Distributor',         2),
('Architect',           3),
('Interior Designer',   4),
('Builder',             5),
('Contractor',          6),
('Retail Customer',     7),
('Corporate Client',    8),
('Vendor/Supplier',     9),
('Channel Partner',     10),
('End User',            11)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- ADDRESS TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `address_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type_name` VARCHAR(100) NOT NULL UNIQUE,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `address_types` (`type_name`, `sort_order`) VALUES
('Site Address',        1),
('Office Address',      2),
('Home Address',        3),
('Billing Address',     4),
('Shipping Address',    5),
('Registered Address',  6),
('Warehouse Address',   7)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`);

-- ============================================================
-- COMPANY STATUSES
-- ============================================================
CREATE TABLE IF NOT EXISTS `company_statuses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `status_name` VARCHAR(100) NOT NULL UNIQUE,
  `color_code` VARCHAR(20) DEFAULT '#64748b',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `company_statuses` (`status_name`, `color_code`, `sort_order`) VALUES
('Active',      '#22c55e', 1),
('Inactive',    '#94a3b8', 2),
('Prospect',    '#3b82f6', 3),
('Blacklisted', '#ef4444', 4)
ON DUPLICATE KEY UPDATE `sort_order` = VALUES(`sort_order`), `color_code` = VALUES(`color_code`);

SET FOREIGN_KEY_CHECKS = 1;
