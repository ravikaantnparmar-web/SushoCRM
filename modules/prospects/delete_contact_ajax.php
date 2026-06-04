<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (!isAdmin()) {
            throw new Exception('Unauthorized access');
        }

        $id = $_POST['id'] ?? 0;
        if (!$id) throw new Exception('Invalid ID');

        $stmt = db()->prepare("DELETE FROM contact_relations WHERE contact_id = ? AND entity_type = 'lead'");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Contact removed successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
