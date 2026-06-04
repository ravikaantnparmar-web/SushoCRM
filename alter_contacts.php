<?php
require_once __DIR__ . '/config/db.php';
$db = db();

try {
    $db->exec("ALTER TABLE lead_contacts 
        ADD COLUMN organization_name VARCHAR(255) NULL,
        ADD COLUMN address TEXT NULL,
        ADD COLUMN city VARCHAR(100) NULL,
        ADD COLUMN state VARCHAR(100) NULL,
        ADD COLUMN pincode VARCHAR(20) NULL,
        ADD COLUMN website VARCHAR(255) NULL
    ");
    echo "lead_contacts altered. ";
} catch(Exception $e) { echo $e->getMessage(); }

try {
    $db->exec("ALTER TABLE customer_contacts 
        ADD COLUMN organization_name VARCHAR(255) NULL,
        ADD COLUMN address TEXT NULL,
        ADD COLUMN city VARCHAR(100) NULL,
        ADD COLUMN state VARCHAR(100) NULL,
        ADD COLUMN pincode VARCHAR(20) NULL,
        ADD COLUMN website VARCHAR(255) NULL
    ");
    echo "customer_contacts altered.";
} catch(Exception $e) { echo $e->getMessage(); }
