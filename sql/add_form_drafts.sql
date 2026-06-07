-- Navigation Protection: Form Drafts Table
-- Run once on both local XAMPP and Hostinger phpMyAdmin

CREATE TABLE IF NOT EXISTS `form_drafts` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    INT UNSIGNED NOT NULL,
  `draft_key`  VARCHAR(255) NOT NULL COMMENT 'URL path used as unique key per form per user',
  `draft_data` LONGTEXT     NOT NULL COMMENT 'JSON-serialized form field data',
  `form_title` VARCHAR(255) DEFAULT NULL COMMENT 'Human-readable label for the draft',
  `saved_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `user_draft` (`user_id`, `draft_key`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_saved_at` (`saved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
