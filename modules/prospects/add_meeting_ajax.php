<?php
error_reporting(0);
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        db()->beginTransaction();

        $leadId       = (int)($_POST['lead_id'] ?? 0);
        $meetingWith  = sanitize($_POST['meeting_with_name'] ?? '') ?: null;
        $type         = sanitize($_POST['meeting_type'] ?? 'Site Visit');
        $purpose      = sanitize($_POST['meeting_purpose'] ?? '');
        $meetingStatus= sanitize($_POST['meeting_status'] ?? 'Scheduled');
        $leadStatus   = sanitize($_POST['meeting_lead_status'] ?? '');
        $salesStage   = sanitize($_POST['sales_stage'] ?? '') ?: null;
        $followupDate = sanitize($_POST['actual_followup_date'] ?? '') ?: null;
        $createdBy    = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

        // 1. Insert Meeting Record
        $sqlMeeting = "INSERT INTO lead_meetings 
            (lead_id, meeting_with, type, purpose, status, sales_stage, followup_date, created_by) 
            VALUES (?,?,?,?,?,?,?,?)";
        db()->prepare($sqlMeeting)->execute([
            $leadId, $meetingWith, $type, $purpose,
            $meetingStatus, $salesStage, $followupDate, $createdBy
        ]);

        // 2. Update Master Lead Record (lead status + followup date)
        $updateFields = [];
        $updateParams = [];
        if ($leadStatus) {
            $updateFields[] = 'lead_status = ?';
            $updateParams[] = $leadStatus;
        }
        if ($followupDate) {
            $updateFields[] = 'actual_followup_date = ?';
            $updateParams[] = $followupDate;
        }
        if ($salesStage) {
            $updateFields[] = 'sales_stage = ?';
            $updateParams[] = $salesStage;
        }
        if (!empty($updateFields)) {
            $sqlLead = "UPDATE leads SET " . implode(', ', $updateFields) . " WHERE id = ?";
            try {
                db()->prepare($sqlLead)->execute([...$updateParams, $leadId]);
            } catch (Exception $e) {
                // Retry without sales_stage if column doesn't exist
                $idx = array_search('sales_stage = ?', $updateFields);
                if ($idx !== false) {
                    unset($updateFields[$idx]);
                    unset($updateParams[$idx]);
                    if (!empty($updateFields)) {
                        $sqlLead2 = "UPDATE leads SET " . implode(', ', $updateFields) . " WHERE id = ?";
                        db()->prepare($sqlLead2)->execute([...array_values($updateParams), $leadId]);
                    }
                } else {
                    throw $e;
                }
            }
        }

        // 3. Add Timeline Entry
        $desc = "Meeting recorded: $type" . ($meetingWith ? " with $meetingWith" : '') . 
                ($leadStatus ? ". Status → $leadStatus" : '') .
                ($followupDate ? ". Next follow-up: $followupDate" : '');
        db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, 'Meeting', ?, ?)")
           ->execute([$leadId, $desc, $createdBy]);

        db()->commit();
        echo json_encode(['status' => 'success', 'message' => 'Meeting recorded successfully']);
    } catch (Exception $e) {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
