<?php
require_once 'config/db.php';

// Get the lead ID from query string or just check all
$leadId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

echo "=== lead_contacts for lead_id=$leadId ===\n";
$stmt = db()->prepare("SELECT * FROM lead_contacts WHERE lead_id = ?");
$stmt->execute([$leadId]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($contacts);

echo "\n=== lead_meetings for lead_id=$leadId ===\n";
$stmt2 = db()->prepare("SELECT m.*, u.name as personnel_name FROM lead_meetings m LEFT JOIN users u ON m.created_by = u.id WHERE m.lead_id = ?");
$stmt2->execute([$leadId]);
$meetings = $stmt2->fetchAll(PDO::FETCH_ASSOC);
print_r($meetings);

echo "\n=== All leads (id, lead_code) ===\n";
$leads = db()->query("SELECT id, lead_code, company_name FROM leads ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
print_r($leads);
