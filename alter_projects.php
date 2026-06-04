<?php
require 'config/db.php';
$db = db();
try {
    $db->exec("
        ALTER TABLE projects
        ADD COLUMN project_type VARCHAR(50) NULL AFTER name,
        ADD COLUMN project_category VARCHAR(50) NULL AFTER project_type,
        ADD COLUMN priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium' AFTER project_category,
        ADD COLUMN stage ENUM('planning', 'design', 'execution', 'finishing', 'handover', 'completed') DEFAULT 'planning' AFTER priority,
        
        ADD COLUMN site_address TEXT NULL AFTER manager_id,
        ADD COLUMN site_city VARCHAR(100) NULL AFTER site_address,
        ADD COLUMN site_state VARCHAR(100) NULL AFTER site_city,
        ADD COLUMN site_pincode VARCHAR(20) NULL AFTER site_state,
        ADD COLUMN google_maps_location TEXT NULL AFTER site_pincode,
        ADD COLUMN site_contact_person VARCHAR(100) NULL AFTER google_maps_location,
        ADD COLUMN site_contact_number VARCHAR(50) NULL AFTER site_contact_person,
        ADD COLUMN site_engineer_name_number VARCHAR(150) NULL AFTER site_contact_number,
        
        ADD COLUMN project_cost DECIMAL(15,2) DEFAULT 0.00 AFTER budget,
        
        ADD COLUMN expected_duration VARCHAR(100) NULL AFTER actual_end_date,
        ADD COLUMN completion_percentage INT(3) DEFAULT 0 AFTER expected_duration
    ");
    echo "Projects table altered successfully.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
