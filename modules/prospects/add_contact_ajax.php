<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
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

        $mode = $_POST['mode'] ?? 'create';

        if ($mode === 'link_existing') {
            error_log("LINK_EXISTING POST: " . print_r($_POST, true));
            $contactId = (int)($_POST['id'] ?? 0);
            if (!$contactId) {
                throw new Exception("Contact ID is missing.");
            }
            $link = db()->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'lead', ?, ?, 0)");
            $link->execute([$contactId, $leadId, $contactType]);
            echo json_encode(['status' => 'success', 'message' => 'Contact linked successfully']);
            exit;
        }

        // Try to find existing contact for duplicate check during create
        $existing = false;
        $existingRow = null;
        if (!empty($mobile)) {
            $check = db()->prepare("SELECT * FROM contacts WHERE mobile = ? LIMIT 1");
            $check->execute([$mobile]);
            $existingRow = $check->fetch();
        }
        if (!$existingRow && !empty($email)) {
            $check = db()->prepare("SELECT * FROM contacts WHERE email = ? LIMIT 1");
            $check->execute([$email]);
            $existingRow = $check->fetch();
        }

        if ($existingRow) {
            echo json_encode([
                'status' => 'duplicate', 
                'message' => 'A contact with this mobile/email already exists.',
                'contact' => $existingRow
            ]);
            exit;
        }

        db()->beginTransaction();

        $ins = db()->prepare("INSERT INTO contacts (contact_type, name, mobile, whatsapp, email, visiting_card, organization_name, address, city, state, pincode, website) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $ins->execute([$contactType, $name, $mobile, $whatsapp, $email, $cardPathJson, $organization_name, $address, $city, $state, $pincode, $website]);
        $contactId = db()->lastInsertId();

        // Link to lead
        $link = db()->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'lead', ?, ?, 0)");
        $link->execute([$contactId, $leadId, $contactType]);

        db()->commit();
        echo json_encode(['status' => 'success', 'message' => 'Contact added successfully']);
    } catch (Exception $e) {
        if(db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
