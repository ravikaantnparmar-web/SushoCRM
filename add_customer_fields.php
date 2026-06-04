<?php
require_once 'config/config.php';
require_once 'config/db.php';

try {
    $db = db();
    $db->exec("ALTER TABLE customers ADD COLUMN contact_person VARCHAR(255) NULL AFTER company");
    $db->exec("ALTER TABLE customers ADD COLUMN alternate_contact VARCHAR(255) NULL AFTER phone");
    $db->exec("ALTER TABLE customers ADD COLUMN google_maps_location TEXT NULL AFTER notes");
    $db->exec("ALTER TABLE customers ADD COLUMN latitude DECIMAL(10,8) NULL AFTER google_maps_location");
    $db->exec("ALTER TABLE customers ADD COLUMN longitude DECIMAL(11,8) NULL AFTER latitude");
    $db->exec("ALTER TABLE customers ADD COLUMN gps_accuracy DECIMAL(8,2) NULL AFTER longitude");
    $db->exec("ALTER TABLE customers ADD COLUMN gps_address TEXT NULL AFTER gps_accuracy");
    $db->exec("ALTER TABLE customers ADD COLUMN gps_captured_at DATETIME NULL AFTER gps_address");
    echo "Columns added to customers table successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
