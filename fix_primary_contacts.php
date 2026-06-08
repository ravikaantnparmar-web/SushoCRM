<?php
require 'config/config.php';
require 'config/db.php';

// Find all leads that don't have a primary contact in contact_relations
$stmt = db()->query("
    SELECT l.id 
    FROM leads l
    WHERE NOT EXISTS (
        SELECT 1 FROM contact_relations cr 
        WHERE cr.entity_type = 'lead' AND cr.entity_id = l.id AND cr.is_primary = 1
    )
");

$leadsWithoutPrimary = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($leadsWithoutPrimary as $leadId) {
    // Find the first contact linked to this lead
    $stmtFirst = db()->prepare("
        SELECT contact_id FROM contact_relations 
        WHERE entity_type = 'lead' AND entity_id = ? 
        ORDER BY id ASC LIMIT 1
    ");
    $stmtFirst->execute([$leadId]);
    $firstContactId = $stmtFirst->fetchColumn();

    if ($firstContactId) {
        $stmtUpdate = db()->prepare("
            UPDATE contact_relations 
            SET is_primary = 1 
            WHERE entity_type = 'lead' AND entity_id = ? AND contact_id = ?
        ");
        $stmtUpdate->execute([$leadId, $firstContactId]);
        echo "Set primary contact $firstContactId for lead $leadId\n";
    }
}
echo "Done fixing missing primary contacts.\n";
