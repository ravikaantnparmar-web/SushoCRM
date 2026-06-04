-- ============================================================
-- SushobhaCRM - Alter Leads Table
-- Adds missing columns referenced in save.php / update.php
-- Safe: uses IF NOT EXISTS pattern via ALTER IGNORE
-- ============================================================

USE `sushobha_crm`;

-- Add google_address if not exists
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'leads'
    AND COLUMN_NAME  = 'google_address'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `leads` ADD COLUMN `google_address` TEXT DEFAULT NULL AFTER `google_location`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add google_maps_link if not exists
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'leads'
    AND COLUMN_NAME  = 'google_maps_link'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `leads` ADD COLUMN `google_maps_link` TEXT DEFAULT NULL AFTER `google_address`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add actual_followup_date if not exists
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'leads'
    AND COLUMN_NAME  = 'actual_followup_date'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `leads` ADD COLUMN `actual_followup_date` DATE DEFAULT NULL AFTER `next_followup_date`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add company_status if not exists
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'leads'
    AND COLUMN_NAME  = 'company_status'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `leads` ADD COLUMN `company_status` VARCHAR(50) DEFAULT ''Active'' AFTER `gst_number`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ============================================================
-- Fix customers table: convert ENUM columns to VARCHAR
-- Preserves all existing data
-- ============================================================

-- customer_type: ENUM → VARCHAR
ALTER TABLE `customers`
  MODIFY COLUMN `customer_type` VARCHAR(150) DEFAULT 'Retail Customer';

-- business_category: ENUM → VARCHAR
ALTER TABLE `customers`
  MODIFY COLUMN `business_category` VARCHAR(150) DEFAULT 'Residential';

-- industry_type: ENUM → VARCHAR
ALTER TABLE `customers`
  MODIFY COLUMN `industry_type` VARCHAR(150) DEFAULT 'Real Estate';

-- preferred_communication: ENUM → VARCHAR (if it exists)
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'customers'
    AND COLUMN_NAME  = 'preferred_communication'
);
SET @sql = IF(@col_exists > 0,
  'ALTER TABLE `customers` MODIFY COLUMN `preferred_communication` VARCHAR(50) DEFAULT ''Call''',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add website and whatsapp_number to customers if not exists
SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'customers'
    AND COLUMN_NAME  = 'website'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `customers` ADD COLUMN `website` VARCHAR(255) DEFAULT NULL AFTER `email`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'sushobha_crm'
    AND TABLE_NAME   = 'customers'
    AND COLUMN_NAME  = 'whatsapp_number'
);
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `customers` ADD COLUMN `whatsapp_number` VARCHAR(20) DEFAULT NULL AFTER `phone`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
