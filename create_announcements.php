<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

try {
    $db = new PDO("mysql:host=localhost;dbname=sushobha_crm;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    CREATE TABLE IF NOT EXISTS `announcements` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `content` TEXT NOT NULL,
      `category` VARCHAR(100) DEFAULT 'General',
      `priority` VARCHAR(50) DEFAULT 'Normal',
      `is_active` TINYINT(1) DEFAULT 1,
      `created_by` INT UNSIGNED DEFAULT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS `announcement_comments` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `announcement_id` INT UNSIGNED NOT NULL,
      `user_id` INT UNSIGNED NOT NULL,
      `comment` TEXT NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`announcement_id`) REFERENCES `announcements`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    -- Insert a dummy announcement so the board isn't completely empty
    INSERT INTO `announcements` (`title`, `content`, `category`, `priority`, `created_by`) 
    VALUES ('Welcome to SUSHOBHA CRM', 'Welcome to the newly updated dashboard! Please explore the new features.', 'System', 'High', 1);
    ";

    $db->exec($sql);
    echo "Successfully created announcements tables.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
