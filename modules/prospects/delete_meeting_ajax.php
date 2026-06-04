<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_SESSION['user_id'])) throw new Exception("Unauthorized");

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) throw new Exception("Invalid meeting ID");

        db()->beginTransaction();

        // Get details of the meeting to construct the description
        $stmt = db()->prepare("SELECT lead_id, type FROM lead_meetings WHERE id = ?");
        $stmt->execute([$id]);
        $meeting = $stmt->fetch();
        if ($meeting) {
            $leadId = $meeting['lead_id'];
            $type = $meeting['type'];

            // Delete meeting
            db()->prepare("DELETE FROM lead_meetings WHERE id = ?")->execute([$id]);

            // Add Timeline Entry
            $desc = "Meeting deleted: $type";
            db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, 'Meeting', ?, ?)")
               ->execute([$leadId, $desc, $_SESSION['user_id']]);
        }

        db()->commit();

        echo json_encode(['status' => 'success', 'message' => 'Meeting deleted successfully']);
    } catch (Exception $e) {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
