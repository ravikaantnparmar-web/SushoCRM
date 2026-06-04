<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

try {
    if ($action === 'get_entities') {
        $type = $_POST['entity_type'] ?? '';
        $data = [];
        
        switch ($type) {
            case 'lead':
                $stmt = db()->query("SELECT id, company_name as name FROM leads ORDER BY company_name");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'customer':
                $stmt = db()->query("SELECT id, name FROM customers ORDER BY name");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'contact':
                $stmt = db()->query("SELECT id, name FROM contacts ORDER BY name");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'quotation':
                $stmt = db()->query("SELECT id, quote_number as name FROM quotations ORDER BY id DESC");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'order':
                $stmt = db()->query("SELECT id, order_number as name FROM orders ORDER BY id DESC");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'invoice':
                $stmt = db()->query("SELECT id, invoice_number as name FROM invoices ORDER BY id DESC");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        
        echo json_encode($data);
        exit;
    }
    
    if ($action === 'update_task_status') {
        $task_id = (int)$_POST['task_id'];
        $status_id = (int)$_POST['status_id'];
        
        // Fetch new status
        $status = db()->prepare("SELECT name, is_terminal FROM task_statuses WHERE id = ?");
        $status->execute([$status_id]);
        $statusInfo = $status->fetch();
        
        if ($statusInfo) {
            // Update status
            $updateQ = "UPDATE tasks SET status_id = ?";
            $params = [$status_id];
            
            if ($statusInfo['is_terminal'] == 1) {
                $updateQ .= ", completion_date = NOW(), progress_percentage = 100";
            }
            
            $updateQ .= " WHERE id = ?";
            $params[] = $task_id;
            
            $stmt = db()->prepare($updateQ);
            $stmt->execute($params);
            
            logActivity('tasks', 'update', "Changed task status to {$statusInfo['name']}", $task_id);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid status']);
        }
        exit;
    }
    
    if ($action === 'add_comment') {
        $task_id = (int)$_POST['task_id'];
        $comment = trim($_POST['comment'] ?? '');
        $user_id = $_SESSION['user_id'];
        
        if (!empty($comment)) {
            $stmt = db()->prepare("INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$task_id, $user_id, $comment]);
            logActivity('tasks', 'comment', "Added a comment", $task_id);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Empty comment']);
        }
        exit;
    }
    
    if ($action === 'add_checklist_item') {
        $task_id = (int)$_POST['task_id'];
        $title = trim($_POST['title'] ?? '');
        
        if (!empty($title)) {
            $stmt = db()->prepare("INSERT INTO task_checklists (task_id, title) VALUES (?, ?)");
            $stmt->execute([$task_id, $title]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Empty title']);
        }
        exit;
    }
    
    if ($action === 'toggle_checklist_item') {
        $item_id = (int)$_POST['item_id'];
        $is_completed = (int)$_POST['is_completed'];
        $user_id = $_SESSION['user_id'];
        
        $completed_by = $is_completed ? $user_id : null;
        $completed_at = $is_completed ? date('Y-m-d H:i:s') : null;
        
        $stmt = db()->prepare("UPDATE task_checklists SET is_completed = ?, completed_by = ?, completed_at = ? WHERE id = ?");
        $stmt->execute([$is_completed, $completed_by, $completed_at, $item_id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'update_progress') {
        $task_id = (int)$_POST['task_id'];
        $progress = (int)$_POST['progress'];
        
        if ($progress >= 0 && $progress <= 100) {
            $stmt = db()->prepare("UPDATE tasks SET progress_percentage = ? WHERE id = ?");
            $stmt->execute([$progress, $task_id]);
            logActivity('tasks', 'update', "Updated progress to $progress%", $task_id);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid progress']);
        }
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
