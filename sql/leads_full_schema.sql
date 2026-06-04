-- ============================================================
-- SushobhaCRM - Leads Table Full Schema (Spec-Aligned)
-- Run after masters_migration.sql
-- Safe: uses ALTER to add missing columns only
-- ============================================================
USE `sushobha_crm`;

-- Drop old leads table if doing a fresh redesign (COMMENT OUT to preserve data)
-- DROP TABLE IF EXISTS `lead_timeline`, `lead_meetings`, `lead_documents`, `lead_interested_products`, `lead_contacts`, `leads`;

-- ── Recreate leads table with ALL spec fields ─────────────────
CREATE TABLE IF NOT EXISTS `leads` (
  `id`                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_code`             VARCHAR(20)  NOT NULL UNIQUE,

  -- Section 1: Lead Master Info
  `lead_date`             DATE         DEFAULT NULL,
  `lead_status`           VARCHAR(100) DEFAULT 'New',
  `lead_priority`         VARCHAR(100) DEFAULT 'Cold Lead',
  `lead_source`           VARCHAR(150) DEFAULT NULL,
  `lead_type`             VARCHAR(150) DEFAULT NULL,
  `assigned_to`           INT UNSIGNED DEFAULT NULL,
  `actual_followup_date`  DATE         DEFAULT NULL,
  `next_followup_date`    DATE         DEFAULT NULL,
  `expected_closing_date` DATE         DEFAULT NULL,

  -- Section 2: Company / Site Info
  `company_name`          VARCHAR(200) DEFAULT NULL,
  `company_type`          VARCHAR(100) DEFAULT NULL,
  `industry_type`         VARCHAR(150) DEFAULT NULL,
  `business_category`     VARCHAR(100) DEFAULT NULL,
  `company_email`         VARCHAR(150) DEFAULT NULL,
  `company_website`       VARCHAR(255) DEFAULT NULL,
  `gst_number`            VARCHAR(20)  DEFAULT NULL,
  `company_status`        VARCHAR(50)  DEFAULT 'Active',
  `site_stage`            VARCHAR(150) DEFAULT NULL,
  `project_type`          VARCHAR(150) DEFAULT NULL,
  `approx_area_sqft`      DECIMAL(10,2) DEFAULT 0.00,

  -- Google Location
  `google_location`       VARCHAR(255) DEFAULT NULL,
  `google_address`        TEXT         DEFAULT NULL,
  `google_maps_link`      TEXT         DEFAULT NULL,
  `lat`                   DECIMAL(11,8) DEFAULT NULL,
  `lng`                   DECIMAL(11,8) DEFAULT NULL,

  -- Section 3: Address
  `address_line1`         VARCHAR(255) DEFAULT NULL,
  `address_line2`         VARCHAR(255) DEFAULT NULL,
  `area`                  VARCHAR(150) DEFAULT NULL,
  `city`                  VARCHAR(100) DEFAULT NULL,
  `state`                 VARCHAR(100) DEFAULT NULL,
  `pincode`               VARCHAR(10)  DEFAULT NULL,

  -- Section 5: Requirement
  `product_type`          VARCHAR(100) DEFAULT NULL,
  `requirement_description` TEXT       DEFAULT NULL,
  `estimated_budget`      DECIMAL(15,2) DEFAULT 0.00,
  `purchase_timeline`     VARCHAR(100) DEFAULT NULL,
  `competitor_info`       VARCHAR(255) DEFAULT NULL,

  -- Metadata
  `created_by`            INT UNSIGNED DEFAULT NULL,
  `updated_by`            INT UNSIGNED DEFAULT NULL,
  `deleted_at`            DATETIME     DEFAULT NULL,
  `created_at`            TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`updated_by`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_lead_status`   (`lead_status`),
  INDEX `idx_lead_priority` (`lead_priority`),
  INDEX `idx_lead_source`   (`lead_source`),
  INDEX `idx_assigned_to`   (`assigned_to`),
  INDEX `idx_deleted_at`    (`deleted_at`)
) ENGINE=InnoDB;

-- ── Lead Contacts (Multiple per lead) ─────────────────────────
CREATE TABLE IF NOT EXISTS `lead_contacts` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id`       INT UNSIGNED NOT NULL,
  `contact_type`  VARCHAR(100) DEFAULT 'Owner',
  `name`          VARCHAR(150) NOT NULL,
  `designation`   VARCHAR(150) DEFAULT NULL,
  `mobile`        VARCHAR(20)  DEFAULT NULL,
  `alt_mobile`    VARCHAR(20)  DEFAULT NULL,
  `whatsapp`      VARCHAR(20)  DEFAULT NULL,
  `email`         VARCHAR(150) DEFAULT NULL,
  `visiting_card` TEXT         DEFAULT NULL,   -- JSON array of file paths
  `is_primary`    TINYINT(1)   DEFAULT 0,
  `created_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE,
  INDEX `idx_lc_lead` (`lead_id`)
) ENGINE=InnoDB;

-- ── Lead Interested Products ───────────────────────────────────
CREATE TABLE IF NOT EXISTS `lead_interested_products` (
  `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id`      INT UNSIGNED NOT NULL,
  `product_name` VARCHAR(200) NOT NULL,
  FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE,
  INDEX `idx_lip_lead` (`lead_id`)
) ENGINE=InnoDB;

-- ── Lead Meetings ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `lead_meetings` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id`       INT UNSIGNED NOT NULL,
  `meeting_with`  VARCHAR(150) DEFAULT NULL,
  `type`          VARCHAR(100) DEFAULT 'Site Visit',
  `purpose`       TEXT         DEFAULT NULL,
  `status`        VARCHAR(50)  DEFAULT 'Scheduled',
  `sales_stage`   VARCHAR(100) DEFAULT NULL,
  `followup_date` DATE         DEFAULT NULL,
  `created_by`    INT UNSIGNED DEFAULT NULL,
  `created_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`)    REFERENCES `leads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_lm_lead` (`lead_id`)
) ENGINE=InnoDB;

-- ── Lead Documents ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `lead_documents` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id`       INT UNSIGNED NOT NULL,
  `file_path`     VARCHAR(500) NOT NULL,
  `file_name`     VARCHAR(255) DEFAULT NULL,
  `file_type`     VARCHAR(100) DEFAULT NULL,
  `category`      VARCHAR(100) DEFAULT 'General',
  `remark`        VARCHAR(255) DEFAULT NULL,
  `uploaded_from` VARCHAR(50)  DEFAULT 'Device',
  `uploaded_by`   INT UNSIGNED DEFAULT NULL,
  `created_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`)      REFERENCES `leads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_ld_lead` (`lead_id`)
) ENGINE=InnoDB;

-- ── Lead Timeline / Activity Log ───────────────────────────────
CREATE TABLE IF NOT EXISTS `lead_timeline` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id`     INT UNSIGNED NOT NULL,
  `user_id`     INT UNSIGNED DEFAULT NULL,
  `action_type` VARCHAR(50)  DEFAULT 'Updated',
  `description` TEXT         DEFAULT NULL,
  `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`)  REFERENCES `leads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_lt_lead` (`lead_id`)
) ENGINE=InnoDB;
