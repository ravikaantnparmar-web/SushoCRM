<?php
require_once __DIR__ . '/config/db.php';

try {
    $db = db();
    $sql = "
    CREATE TABLE IF NOT EXISTS `user_preferences` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(10) unsigned NOT NULL,
        `preference_key` varchar(50) NOT NULL,
        `preference_value` text NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_user_pref` (`user_id`, `preference_key`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->exec($sql);
    echo "Table user_preferences created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
