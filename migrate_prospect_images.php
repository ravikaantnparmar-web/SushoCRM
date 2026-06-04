<?php
require 'config/db.php';
$db = db();

try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS prospect_images (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            prospect_id INT(10) UNSIGNED NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            description TEXT NULL,
            uploaded_by INT(10) UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (prospect_id) REFERENCES prospects(id) ON DELETE CASCADE,
            FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "prospect_images table created successfully.\n";

    // Create directory for uploads
    $uploadDir = __DIR__ . '/uploads/prospects';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo "Created directory: $uploadDir\n";
    }

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
