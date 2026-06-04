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
            $stmt = $pdo->query("SELECT * FROM site_stages ORDER BY sort_order ASC");
            $stages = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $stages]);
            break;

        case 'save':
            $id = $_POST['id'] ?? null;
            $name = sanitize($_POST['stage_name'] ?? '');
            $color = sanitize($_POST['color_code'] ?? '#64748b');
            $order = (int)($_POST['sort_order'] ?? 0);

            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Stage name is required.']);
                exit;
            }

            if ($id) {
                $stmt = $pdo->prepare("UPDATE site_stages SET stage_name = ?, color_code = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$name, $color, $order, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO site_stages (stage_name, color_code, sort_order) VALUES (?, ?, ?)");
                $stmt->execute([$name, $color, $order]);
            }
            echo json_encode(['success' => true, 'message' => 'Stage saved successfully.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM site_stages WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Stage deleted successfully.']);
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
