<?php
error_reporting(0);
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

header('Content-Type: application/json');

if (empty($_SESSION['user_id']) && empty($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

try {
    // Get meetings scheduled for the logged-in user within the next 5 hours and 15 minutes
    $sql = "SELECT m.id, m.meeting_with, m.type, m.purpose, m.followup_date, l.company_name, l.lead_code, l.id as lead_id
            FROM lead_meetings m
            LEFT JOIN leads l ON m.lead_id = l.id
            WHERE m.created_by = ? 
              AND m.status = 'Scheduled'
              AND m.followup_date > NOW()
              AND m.followup_date <= DATE_ADD(NOW(), INTERVAL 315 MINUTE)
            ORDER BY m.followup_date ASC";
    
    $stmt = db()->prepare($sql);
    $stmt->execute([$user_id]);
    $meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'server_time' => date('Y-m-d H:i:s'),
        'meetings' => $meetings
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
