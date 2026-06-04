<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

try {
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
                $path = uploadFile($file, 'contacts/cards/');
                if ($path) $cardPaths[] = $path;
            }
        }
    }
    $cardPathJson = !empty($cardPaths) ? json_encode($cardPaths) : null;

    $sql = "INSERT INTO contacts (contact_type, name, organization_name, mobile, whatsapp, email, address, city, state, pincode, website, gst_number, visiting_card) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
        $cardPathJson
    ]);
    
    $id = db()->lastInsertId();
    setFlash('success', 'Master Contact created successfully.');
    header("Location: view.php?id=$id");
} catch (Exception $e) {
    setFlash('danger', 'Failed to save contact: ' . $e->getMessage());
    header('Location: create.php');
}
exit;