<?php
require 'config/db.php';
$db = db();
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_number VARCHAR(50) NOT NULL UNIQUE,
            name VARCHAR(200) NOT NULL,
            description TEXT NULL,
            customer_id INT(10) UNSIGNED NULL,
            status ENUM('planning', 'in_progress', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning',
            start_date DATE NULL,
            target_end_date DATE NULL,
            actual_end_date DATE NULL,
            budget DECIMAL(15,2) DEFAULT 0.00,
            manager_id INT(10) UNSIGNED NULL,
            notes TEXT NULL,
            created_by INT(10) UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
            FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "projects created\n";

    $db->exec("
        CREATE TABLE IF NOT EXISTS project_boq (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_id INT(10) UNSIGNED NOT NULL,
            item_description TEXT NOT NULL,
            unit VARCHAR(30) NULL,
            estimated_qty DECIMAL(10,2) DEFAULT 0.00,
            estimated_rate DECIMAL(12,2) DEFAULT 0.00,
            estimated_amount DECIMAL(15,2) DEFAULT 0.00,
            actual_qty DECIMAL(10,2) DEFAULT 0.00,
            actual_rate DECIMAL(12,2) DEFAULT 0.00,
            actual_amount DECIMAL(15,2) DEFAULT 0.00,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "project_boq created\n";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
