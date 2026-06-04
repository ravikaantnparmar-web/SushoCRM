<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['order']) || !is_array($data['order'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$userId = $_SESSION['user_id'];
$orderJson = json_encode($data['order']);

try {
    $db = db();
    // Check if preference exists
    $stmt = $db->prepare("SELECT id FROM user_preferences WHERE user_id = ? AND preference_key = 'sidebar_order'");
    $stmt->execute([$userId]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $update = $db->prepare("UPDATE user_preferences SET preference_value = ? WHERE id = ?");
        $update->execute([$orderJson, $exists]);
    } else {
        $insert = $db->prepare("INSERT INTO user_preferences (user_id, preference_key, preference_value) VALUES (?, 'sidebar_order', ?)");
        $insert->execute([$userId, $orderJson]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
