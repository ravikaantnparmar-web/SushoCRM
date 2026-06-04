-- ============================================================
-- SushobhaCRM - Lead Management System Revamp
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Drop old tables if they interfere (optional, but requested revamp)
-- DROP TABLE IF EXISTS `prospect_followups`;
-- DROP TABLE IF EXISTS `prospects`;

-- Main Leads Table
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_code` VARCHAR(50) UNIQUE NOT NULL,
  `lead_date` DATE NOT NULL,
  `site_stage` VARCHAR(100) DEFAULT NULL,
  `project_type` VARCHAR(100) DEFAULT NULL,
  `lead_type` VARCHAR(100) DEFAULT NULL,
  `lead_source` VARCHAR(100) DEFAULT NULL,
  `lead_priority` VARCHAR(100) DEFAULT NULL,
  `lead_status` VARCHAR(100) DEFAULT NULL,
  `expected_closing_date` DATE DEFAULT NULL,
  `next_followup_date` DATE DEFAULT NULL,
  `assigned_to` INT UNSIGNED DEFAULT NULL,
  
  -- Company Information
  `company_name` VARCHAR(255) DEFAULT NULL,
  `company_type` VARCHAR(100) DEFAULT NULL,
  `industry_type` VARCHAR(100) DEFAULT NULL,
  `business_category` VARCHAR(100) DEFAULT NULL,
  `company_email` VARCHAR(255) DEFAULT NULL,
  `company_website` VARCHAR(255) DEFAULT NULL,
  `gst_number` VARCHAR(50) DEFAULT NULL,
  `company_status` VARCHAR(50) DEFAULT 'Active',
  
  -- Address Information
  `address_line1` TEXT DEFAULT NULL,
  `address_line2` TEXT DEFAULT NULL,
  `area` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT 'India',
  `pincode` VARCHAR(20) DEFAULT NULL,
  `approx_area_sqft` DECIMAL(15,2) DEFAULT NULL,
  `lat` DECIMAL(10,8) DEFAULT NULL,
  `lng` DECIMAL(11,8) DEFAULT NULL,
  `google_location` TEXT DEFAULT NULL,
  
  -- Product / Requirement Section
  `product_type` VARCHAR(100) DEFAULT NULL,
  `requirement_description` TEXT DEFAULT NULL,
  `estimated_budget` DECIMAL(15,2) DEFAULT 0.00,
  `purchase_timeline` VARCHAR(100) DEFAULT NULL,
  `competitor_info` TEXT DEFAULT NULL,
  
  -- Audit & Metadata
  `created_by` INT UNSIGNED DEFAULT NULL,
  `updated_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_lead_status` (`lead_status`),
  INDEX `idx_lead_priority` (`lead_priority`),
  INDEX `idx_lead_date` (`lead_date`)
) ENGINE=InnoDB;

-- Dynamic Multi-Contact Support
CREATE TABLE IF NOT EXISTS `lead_contacts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `contact_type` VARCHAR(100) DEFAULT 'Primary', -- Owner, Entrepreneur, Manager, Architect, etc.
  `name` VARCHAR(255) NOT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `mobile` VARCHAR(20) DEFAULT NULL,
  `alt_mobile` VARCHAR(20) DEFAULT NULL,
  `whatsapp` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Multi-select Products
CREATE TABLE IF NOT EXISTS `lead_interested_products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Meeting Management
CREATE TABLE IF NOT EXISTS `lead_meetings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Scheduled',
  `priority` VARCHAR(20) DEFAULT 'Medium',
  `meeting_with` VARCHAR(255) DEFAULT NULL,
  `type` VARCHAR(100) DEFAULT NULL,
  `purpose` TEXT DEFAULT NULL,
  `followup_date` DATE DEFAULT NULL,
  `project_start_date` DATE DEFAULT NULL,
  `expected_completion_date` DATE DEFAULT NULL,
  `sales_stage` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Document Management
CREATE TABLE IF NOT EXISTS `lead_documents` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_type` VARCHAR(100) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `uploaded_from` ENUM('Mobile', 'Device') DEFAULT 'Device',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Activity Feed / Timeline
CREATE TABLE IF NOT EXISTS `lead_timeline` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `action_type` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` JSON DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
