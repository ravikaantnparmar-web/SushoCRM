<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

try {
    $db = db();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Drop old tasks table if exists
    $db->exec("DROP TABLE IF EXISTS `tasks`;");

    // Drop new tables if exists to allow re-running
    $tables = [
        'task_categories', 'task_statuses', 'task_priorities', 
        'task_assignments', 'task_checklists', 'task_comments', 
        'task_attachments', 'task_reminders', 'task_activity_logs', 
        'task_links', 'tasks'
    ];
    foreach ($tables as $t) {
        $db->exec("DROP TABLE IF EXISTS `$t`;");
    }

    echo "Creating task_categories...\n";
    $db->exec("
        CREATE TABLE `task_categories` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL UNIQUE,
            `color` VARCHAR(20) DEFAULT '#6c757d',
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_statuses...\n";
    $db->exec("
        CREATE TABLE `task_statuses` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL UNIQUE,
            `color` VARCHAR(20) DEFAULT '#6c757d',
            `sort_order` INT DEFAULT 0,
            `is_default` TINYINT(1) DEFAULT 0,
            `is_terminal` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_priorities...\n";
    $db->exec("
        CREATE TABLE `task_priorities` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL UNIQUE,
            `color` VARCHAR(20) DEFAULT '#6c757d',
            `level` INT DEFAULT 0
        ) ENGINE=InnoDB;
    ");

    echo "Creating tasks...\n";
    $db->exec("
        CREATE TABLE `tasks` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_number` VARCHAR(30) NOT NULL UNIQUE,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT DEFAULT NULL,
            `category_id` INT UNSIGNED DEFAULT NULL,
            `priority_id` INT UNSIGNED DEFAULT NULL,
            `status_id` INT UNSIGNED DEFAULT NULL,
            `start_date` DATE DEFAULT NULL,
            `due_date` DATETIME DEFAULT NULL,
            `completion_date` DATETIME DEFAULT NULL,
            `estimated_hours` DECIMAL(8,2) DEFAULT 0.00,
            `actual_hours` DECIMAL(8,2) DEFAULT 0.00,
            `progress_percentage` INT DEFAULT 0,
            `is_recurring` TINYINT(1) DEFAULT 0,
            `recurring_type` ENUM('daily','weekly','monthly','quarterly','yearly') DEFAULT NULL,
            `created_by` INT UNSIGNED DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`category_id`) REFERENCES `task_categories`(`id`) ON DELETE SET NULL,
            FOREIGN KEY (`priority_id`) REFERENCES `task_priorities`(`id`) ON DELETE SET NULL,
            FOREIGN KEY (`status_id`) REFERENCES `task_statuses`(`id`) ON DELETE SET NULL,
            FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
            INDEX `idx_task_dates` (`start_date`, `due_date`)
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_assignments...\n";
    $db->exec("
        CREATE TABLE `task_assignments` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `is_watcher` TINYINT(1) DEFAULT 0,
            `assigned_by` INT UNSIGNED DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`assigned_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
            UNIQUE KEY `unique_task_user` (`task_id`, `user_id`, `is_watcher`)
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_links...\n";
    $db->exec("
        CREATE TABLE `task_links` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `entity_type` ENUM('lead','customer','contact','quotation','order','project','invoice','ticket') NOT NULL,
            `entity_id` INT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            UNIQUE KEY `unique_task_link` (`task_id`, `entity_type`, `entity_id`)
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_checklists...\n";
    $db->exec("
        CREATE TABLE `task_checklists` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `is_completed` TINYINT(1) DEFAULT 0,
            `sort_order` INT DEFAULT 0,
            `completed_by` INT UNSIGNED DEFAULT NULL,
            `completed_at` DATETIME DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`completed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_comments...\n";
    $db->exec("
        CREATE TABLE `task_comments` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `comment` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_attachments...\n";
    $db->exec("
        CREATE TABLE `task_attachments` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `file_name` VARCHAR(255) NOT NULL,
            `file_path` VARCHAR(255) NOT NULL,
            `file_type` VARCHAR(50) DEFAULT NULL,
            `file_size` INT UNSIGNED DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_reminders...\n";
    $db->exec("
        CREATE TABLE `task_reminders` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `reminder_date` DATETIME NOT NULL,
            `is_sent` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    echo "Creating task_activity_logs...\n";
    $db->exec("
        CREATE TABLE `task_activity_logs` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED DEFAULT NULL,
            `action` VARCHAR(50) NOT NULL,
            `description` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB;
    ");

    echo "Inserting default data...\n";
    $categories = [
        ['Sales', '#0d6efd', 1],
        ['Follow-up', '#198754', 2],
        ['Site Visit', '#0dcaf0', 3],
        ['Proposal', '#ffc107', 4],
        ['Quotation', '#fd7e14', 5],
        ['Payment Collection', '#dc3545', 6],
        ['Design', '#6f42c1', 7],
        ['Procurement', '#20c997', 8],
        ['Execution', '#055160', 9],
        ['Installation', '#842029', 10],
        ['Handover', '#0f5132', 11],
        ['Support', '#6610f2', 12],
        ['Maintenance', '#d63384', 13],
        ['Administration', '#6c757d', 14]
    ];
    $stmtCat = $db->prepare("INSERT INTO task_categories (name, color, sort_order) VALUES (?, ?, ?)");
    foreach ($categories as $cat) { $stmtCat->execute($cat); }

    $statuses = [
        ['Pending', '#6c757d', 1, 1, 0],
        ['Assigned', '#17a2b8', 2, 0, 0],
        ['In Progress', '#007bff', 3, 0, 0],
        ['Under Review', '#ffc107', 4, 0, 0],
        ['Completed', '#28a745', 5, 0, 1],
        ['On Hold', '#fd7e14', 6, 0, 0],
        ['Cancelled', '#dc3545', 7, 0, 1],
        ['Reopened', '#e83e8c', 8, 0, 0]
    ];
    $stmtStat = $db->prepare("INSERT INTO task_statuses (name, color, sort_order, is_default, is_terminal) VALUES (?, ?, ?, ?, ?)");
    foreach ($statuses as $stat) { $stmtStat->execute($stat); }

    $priorities = [
        ['Low', '#198754', 1],
        ['Medium', '#0dcaf0', 2],
        ['High', '#fd7e14', 3],
        ['Critical', '#dc3545', 4]
    ];
    $stmtPri = $db->prepare("INSERT INTO task_priorities (name, color, level) VALUES (?, ?, ?)");
    foreach ($priorities as $pri) { $stmtPri->execute($pri); }

    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Tasks module database installation completed successfully.\n";

} catch (Exception $e) {
    die("Error installing tasks module: " . $e->getMessage() . "\n");
}
