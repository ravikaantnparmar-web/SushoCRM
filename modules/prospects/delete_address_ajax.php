<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_SESSION['user_id'])) throw new Exception("Unauthorized");

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) throw new Exception("Invalid Address ID");

        db()->beginTransaction();

        // Get lead_id and address details for this address
        $stmt = db()->prepare("SELECT lead_id, address_type, city FROM lead_addresses WHERE id = ?");
        $stmt->execute([$id]);
        $addr = $stmt->fetch();
        if ($addr) {
            $leadId = $addr['lead_id'];
            $address_type = $addr['address_type'];
            $city = $addr['city'];

            db()->prepare("DELETE FROM lead_addresses WHERE id = ?")->execute([$id]);

            // Add timeline log
            $desc = "Deleted address: " . ($address_type ? $address_type : 'Address') . ($city ? " ($city)" : '');
            db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, 'Updated', ?, ?)")
               ->execute([$leadId, $desc, $_SESSION['user_id']]);
        }

        db()->commit();

        echo json_encode(['status' => 'success', 'message' => 'Address deleted successfully']);
    } catch (Exception $e) {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
