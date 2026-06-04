<?php
require_once 'config/db.php';

// Check what's in lead_meetings table
$stmt = db()->query("SELECT * FROM lead_meetings ORDER BY created_at DESC LIMIT 5");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "=== lead_meetings records ===\n";
print_r($rows);

// Check lead_contacts for lead_id = 1 (or first lead)
$stmt2 = db()->query("SELECT id, lead_id, name, contact_type FROM lead_contacts LIMIT 10");
$contacts = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== lead_contacts records ===\n";
print_r($contacts);
