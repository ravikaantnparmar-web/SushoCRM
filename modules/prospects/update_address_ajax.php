<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_SESSION['user_id'])) throw new Exception("Unauthorized");

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) throw new Exception("Invalid Address ID");

        $address_type = $_POST['address_type'] ?: 'Office Address';
        $address_line1 = $_POST['address_line1'] ?: null;
        $address_line2 = $_POST['address_line2'] ?: null;
        $area = $_POST['area'] ?: null;
        $city = $_POST['city'] ?: null;
        $state = $_POST['state'] ?: null;
        $pincode = $_POST['pincode'] ?: null;
        $lat = $_POST['lat'] ?: null;
        $lng = $_POST['lng'] ?: null;
        $google_location = $_POST['google_location'] ?: null;
        $google_address = $_POST['google_address'] ?: null;
        $google_maps_link = $_POST['google_maps_link'] ?: null;
        $is_primary = !empty($_POST['is_primary']) ? 1 : 0;

        db()->beginTransaction();

        // Get lead_id for this address
        $stmt = db()->prepare("SELECT lead_id FROM lead_addresses WHERE id = ?");
        $stmt->execute([$id]);
        $addr = $stmt->fetch();
        if (!$addr) throw new Exception("Address not found");
        $leadId = $addr['lead_id'];

        if ($is_primary) {
            // Set other addresses of this lead to not primary
            db()->prepare("UPDATE lead_addresses SET is_primary = 0 WHERE lead_id = ?")->execute([$leadId]);
        }

        $sql = "UPDATE lead_addresses SET 
                address_type = ?, address_line1 = ?, address_line2 = ?, area = ?, city = ?, state = ?, pincode = ?, 
                lat = ?, lng = ?, google_location = ?, google_address = ?, google_maps_link = ?, is_primary = ?
                WHERE id = ?";
        db()->prepare($sql)->execute([
            $address_type, $address_line1, $address_line2, $area, $city, $state, $pincode, $lat, $lng, $google_location, $google_address, $google_maps_link, $is_primary, $id
        ]);

        // Add timeline log
        $desc = "Updated address: " . ($address_type ? $address_type : 'Address') . ($city ? " ($city)" : '');
        db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, 'Updated', ?, ?)")
           ->execute([$leadId, $desc, $_SESSION['user_id']]);

        db()->commit();

        echo json_encode(['status' => 'success', 'message' => 'Address updated successfully']);
    } catch (Exception $e) {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
