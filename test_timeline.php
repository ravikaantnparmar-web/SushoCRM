<?php
require 'config/db.php';
$id = 4; // Assuming lead 4 exists
$oldLeadStmt = db()->prepare("SELECT * FROM leads WHERE id = ?");
$oldLeadStmt->execute([$id]);
$oldLead = $oldLeadStmt->fetch(PDO::FETCH_ASSOC);

$newLeadData = $oldLead;
$newLeadData['lead_status'] = 'Negotiation';
$newLeadData['estimated_budget'] = 50000;

$trackFields = [
    'lead_status' => 'Status',
    'lead_priority' => 'Priority',
    'assigned_to' => 'Assigned To',
    'expected_closing_date' => 'Expected Closing Date',
    'estimated_budget' => 'Estimated Budget',
    'company_name' => 'Company Name',
    'company_status' => 'Company Status',
    'site_stage' => 'Site Stage',
    'project_type' => 'Project Type',
];

$changes = [];
if (isset($oldLead) && $oldLead) {
    foreach ($trackFields as $key => $label) {
        $oldVal = (string)($oldLead[$key] ?? '');
        $newVal = (string)($newLeadData[$key] ?? '');
        
        if ($key === 'estimated_budget' && $oldVal != $newVal) {
            $oldVal = round((float)$oldVal, 2);
            $newVal = round((float)$newVal, 2);
        }

        if ($oldVal !== $newVal) {
            if ($key === 'assigned_to') {
                $newName = $newVal ? db()->query("SELECT name FROM users WHERE id = " . (int)$newVal)->fetchColumn() : 'None';
                $changes[] = "$label to $newName";
            } else {
                $displayNew = $newVal ?: 'None';
                $changes[] = "$label to $displayNew";
            }
        }
    }
}

$description = !empty($changes) ? 'Updated: ' . implode(', ', $changes) . '.' : 'Lead details updated.';
echo $description;
