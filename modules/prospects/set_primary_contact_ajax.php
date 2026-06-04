<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $contactId = (int)$_POST['contact_id'];
        $leadId = (int)$_POST['lead_id'];

        if (!$contactId || !$leadId) {
            throw new Exception("Missing required parameters.");
        }

        db()->beginTransaction();

        // Remove primary from all contacts for this lead
        $stmtClear = db()->prepare("UPDATE contact_relations SET is_primary = 0 WHERE entity_type = 'lead' AND entity_id = ?");
        $stmtClear->execute([$leadId]);

        // Set the new primary contact
        $stmtSet = db()->prepare("UPDATE contact_relations SET is_primary = 1 WHERE contact_id = ? AND entity_type = 'lead' AND entity_id = ?");
        $stmtSet->execute([$contactId, $leadId]);

        if ($stmtSet->rowCount() === 0) {
            throw new Exception("Contact is not linked to this lead.");
        }

        db()->commit();
        echo json_encode(['status' => 'success', 'message' => 'Primary contact updated successfully.']);

    } catch (Exception $e) {
        if(db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
