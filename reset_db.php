<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

try {
    $db = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Dropping database sushobha_crm...\n";
    $db->exec("DROP DATABASE IF EXISTS `sushobha_crm`");

    echo "Creating database sushobha_crm...\n";
    $db->exec("CREATE DATABASE `sushobha_crm` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $db->exec("USE `sushobha_crm`");

    $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
    if ($schema) {
        echo "Importing schema.sql...\n";
        $db->exec($schema);
    } else {
        echo "Error: Could not read schema.sql\n";
    }

    $seed = file_get_contents(__DIR__ . '/sql/seed.sql');
    if ($seed) {
        echo "Importing seed.sql...\n";
        $db->exec($seed);
    } else {
        echo "Error: Could not read seed.sql\n";
    }

    echo "Database reset successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
