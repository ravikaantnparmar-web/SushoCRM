<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (!isAdmin()) {
            throw new Exception('Unauthorized access');
        }

        // Now id is contact_id, and we need lead_id to update the relation
        $contactId = $_POST['id'];
        $leadId = $_POST['lead_id']; 
        
        $name = $_POST['name'];

        $contactType = $_POST['contact_type'] ?: 'Owner';
        $mobile = $_POST['mobile'];
        $whatsapp = $_POST['whatsapp'] ?: null;
        $email = $_POST['email'] ?: null;
        $organization_name = $_POST['organization_name'] ?? null;
        $address = $_POST['address'] ?? null;
        $city = $_POST['city'] ?? null;
        $state = $_POST['state'] ?? null;
        $pincode = $_POST['pincode'] ?? null;
        $website = $_POST['website'] ?? null;
        
        $cardPaths = [];
        $existingCards = $_POST['existing_cards'] ?? null;
        if ($existingCards) {
            $cardPaths = json_decode($existingCards, true) ?: [$existingCards];
        }

        if (!empty($_FILES['visiting_card']['name'][0])) {
            $files = $_FILES['visiting_card'];
            foreach ($files['name'] as $key => $fName) {
                if ($files['error'][$key] == UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];
                    $path = uploadFile($file, 'leads/cards/');
                    if ($path) $cardPaths[] = $path;
                }
            }
        }
        $cardPathJson = !empty($cardPaths) ? json_encode($cardPaths) : null;

        db()->beginTransaction();

        $sql = "UPDATE contacts SET contact_type = ?, name = ?, mobile = ?, whatsapp = ?, email = ?, visiting_card = ?, organization_name = ?, address = ?, city = ?, state = ?, pincode = ?, website = ? 
                WHERE id = ?";
        $stmt = db()->prepare($sql);
        $stmt->execute([$contactType, $name, $mobile, $whatsapp, $email, $cardPathJson, $organization_name, $address, $city, $state, $pincode, $website, $contactId]);

        // Update relation role if necessary
        $relSql = "UPDATE contact_relations SET role = ? WHERE contact_id = ? AND entity_type = 'lead' AND entity_id = ?";
        $relStmt = db()->prepare($relSql);
        $relStmt->execute([$contactType, $contactId, $leadId]);

        db()->commit();

        echo json_encode(['status' => 'success', 'message' => 'Contact updated successfully']);
    } catch (Exception $e) {
        if(db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
