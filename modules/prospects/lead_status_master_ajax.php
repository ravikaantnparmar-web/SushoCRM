<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$action = $_GET['action'] ?? '';
$pdo = db();

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM lead_statuses ORDER BY sort_order ASC");
            $statuses = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $statuses]);
            break;

        case 'save':
            $id = $_POST['id'] ?? null;
            $name = sanitize($_POST['status_name'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $color = sanitize($_POST['color_code'] ?? '#6c757d');
            $order = (int) ($_POST['sort_order'] ?? 0);

            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Status name is required.']);
                exit;
            }

            if ($id) {
                $stmt = $pdo->prepare("UPDATE lead_statuses SET status_name = ?, description = ?, color_code = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$name, $description, $color, $order, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO lead_statuses (status_name, description, color_code, sort_order) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $description, $color, $order]);
            }
            echo json_encode(['success' => true, 'message' => 'Status saved successfully.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM lead_statuses WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Status deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
