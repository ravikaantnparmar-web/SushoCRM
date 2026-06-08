<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Detect post_max_size overflow (PHP clears $_POST and $_FILES if size > post_max_size)
        if (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            throw new Exception("File too large. The uploaded files exceed the server's maximum allowed size.");
        }

        $leadId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($leadId <= 0) throw new Exception("Invalid Lead ID");

        $allFiles = [];
        if (!empty($_FILES['documents']['name'][0])) {
            foreach($_FILES['documents']['name'] as $k => $name) {
                $allFiles[] = [
                    'name' => $_FILES['documents']['name'][$k],
                    'type' => $_FILES['documents']['type'][$k],
                    'tmp_name' => $_FILES['documents']['tmp_name'][$k],
                    'error' => $_FILES['documents']['error'][$k],
                    'size' => $_FILES['documents']['size'][$k],
                    'source' => 'Device'
                ];
            }
        }
        if (!empty($_FILES['camera_photos']['name'][0])) {
            foreach($_FILES['camera_photos']['name'] as $k => $name) {
                $allFiles[] = [
                    'name' => $_FILES['camera_photos']['name'][$k],
                    'type' => $_FILES['camera_photos']['type'][$k],
                    'tmp_name' => $_FILES['camera_photos']['tmp_name'][$k],
                    'error' => $_FILES['camera_photos']['error'][$k],
                    'size' => $_FILES['camera_photos']['size'][$k],
                    'source' => 'Mobile'
                ];
            }
        }

        $uploadedCount = 0;
        $newDocs = [];
        foreach ($allFiles as $fileData) {
            $path = uploadFile($fileData, 'leads');
            if ($path) {
                $sqlDoc = "INSERT INTO lead_documents (lead_id, file_path, file_name, file_type, category, remark, uploaded_from) VALUES (?,?,?,?,?,?,?)";
                db()->prepare($sqlDoc)->execute([
                    $leadId, $path, $fileData['name'], $fileData['type'], 'Site Media', $_POST['upload_remark'] ?: null, $fileData['source']
                ]);
                $newDocs[] = [
                    'path' => BASE_URL . '/' . $path,
                    'is_image' => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp'])
                ];
                $uploadedCount++;
            }
        }

        if ($uploadedCount > 0) {
            // Log to timeline
            db()->prepare("INSERT INTO lead_timeline (lead_id, user_id, action_type, description) VALUES (?,?,?,?)")
              ->execute([$leadId, $_SESSION['user_id'], 'Document', "Uploaded $uploadedCount new document(s)."]);
        }

        echo json_encode([
            'status' => 'success', 
            'message' => "$uploadedCount documents uploaded successfully",
            'new_docs' => $newDocs
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
