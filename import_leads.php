<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

try {
    $db = new PDO("mysql:host=localhost;dbname=sushobha_crm;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlFile = __DIR__ . '/sql/leads_revamp.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);
        echo "Imported leads_revamp.sql successfully.\n";
    } else {
        echo "Could not find leads_revamp.sql\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
