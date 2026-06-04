<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_SESSION['user_id'])) throw new Exception("Unauthorized");
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) throw new Exception("Invalid meeting ID");

        $status = $_POST['status'] ?? ($_POST['meeting_status'] ?? 'Scheduled');
        $type = $_POST['type'] ?? ($_POST['meeting_type'] ?? '');
        $meeting_with = $_POST['meeting_with'] ?? ($_POST['meeting_with_name'] ?? '');
        $purpose = $_POST['purpose'] ?? ($_POST['meeting_purpose'] ?? '');
        $followup_date = !empty($_POST['followup_date']) ? $_POST['followup_date'] : (!empty($_POST['actual_followup_date']) ? $_POST['actual_followup_date'] : null);
        $salesStage = $_POST['sales_stage'] ?? '';

        $sql = "UPDATE lead_meetings SET 
                status = ?, type = ?, meeting_with = ?, purpose = ?, followup_date = ?, sales_stage = ? 
                WHERE id = ?";
        
        db()->prepare($sql)->execute([$status, $type, $meeting_with, $purpose, $followup_date, $salesStage, $id]);

        $lead_id = (int)($_POST['lead_id'] ?? 0);
        $leadStatus = $_POST['meeting_lead_status'] ?? '';
        
        if ($lead_id > 0) {
            $updateFields = [];
            $updateParams = [];
            
            if ($leadStatus) {
                $updateFields[] = 'lead_status = ?';
                $updateParams[] = $leadStatus;
            }
            if ($followup_date) {
                $updateFields[] = 'actual_followup_date = ?';
                $updateParams[] = $followup_date;
            }
            if ($salesStage) {
                $updateFields[] = 'sales_stage = ?';
                $updateParams[] = $salesStage;
            }
            
            if (!empty($updateFields)) {
                $sqlLead = "UPDATE leads SET " . implode(', ', $updateFields) . " WHERE id = ?";
                try {
                    db()->prepare($sqlLead)->execute([...$updateParams, $lead_id]);
                } catch (Exception $e) {
                    $idx = array_search('sales_stage = ?', $updateFields);
                    if ($idx !== false) {
                        unset($updateFields[$idx]);
                        unset($updateParams[$idx]);
                        if (!empty($updateFields)) {
                            $sqlLead2 = "UPDATE leads SET " . implode(', ', $updateFields) . " WHERE id = ?";
                            db()->prepare($sqlLead2)->execute([...array_values($updateParams), $lead_id]);
                        }
                    } else {
                        throw $e;
                    }
                }
            }
            
            // Add Timeline Entry if lead status changed via edit
            if ($leadStatus) {
                $desc = "Meeting updated ($type). Status updated to: $leadStatus";
                $createdBy = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;
                db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, 'Update', ?, ?)")
                  ->execute([$lead_id, $desc, $createdBy]);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Meeting updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
