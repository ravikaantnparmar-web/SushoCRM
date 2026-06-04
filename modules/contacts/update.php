<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

try {
    $stmt = db()->prepare("SELECT visiting_card FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $existingContact = $stmt->fetch();
    $cardPaths = !empty($existingContact['visiting_card']) && $existingContact['visiting_card'] !== 'null' ? json_decode($existingContact['visiting_card'], true) : [];
    if (!is_array($cardPaths)) $cardPaths = [];

    // Handle removals
    if (!empty($_POST['remove_cards']) && is_array($_POST['remove_cards'])) {
        foreach ($_POST['remove_cards'] as $rem) {
            if (($key = array_search($rem, $cardPaths)) !== false) {
                unset($cardPaths[$key]);
            }
        }
        $cardPaths = array_values($cardPaths); // reindex
    }

    // Handle new uploads
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
                $path = uploadFile($file, 'contacts/cards/');
                if ($path) $cardPaths[] = $path;
            }
        }
    }
    
    $cardPathJson = !empty($cardPaths) ? json_encode($cardPaths) : null;

    $sql = "UPDATE contacts SET contact_type=?, name=?, organization_name=?, mobile=?, whatsapp=?, email=?, address=?, city=?, state=?, pincode=?, website=?, gst_number=?, visiting_card=? WHERE id=?";
    $stmt = db()->prepare($sql);
    $stmt->execute([
        $_POST['contact_type'] ?? 'Other',
        $_POST['name'],
        $_POST['organization_name'] ?: null,
        $_POST['mobile'] ?: null,
        $_POST['whatsapp'] ?: null,
        $_POST['email'] ?: null,
        $_POST['address'] ?: null,
        $_POST['city'] ?: null,
        $_POST['state'] ?: null,
        $_POST['pincode'] ?: null,
        $_POST['website'] ?: null,
        $_POST['gst_number'] ?: null,
        $cardPathJson,
        $id
    ]);
    
    setFlash('success', 'Master Contact updated successfully.');
    header("Location: view.php?id=$id");
} catch (Exception $e) {
    setFlash('danger', 'Failed to update contact: ' . $e->getMessage());
    header("Location: edit.php?id=$id");
}
exit;