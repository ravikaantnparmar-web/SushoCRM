<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

// Only Admins can delete tasks
if (!isAdmin()) {
    setFlash('danger', 'You do not have permission to delete tasks.');
    header('Location: index.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $stmt = db()->prepare("SELECT task_number, title FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch();
        
        if ($task) {
            // Delete the task. Foreign keys with ON DELETE CASCADE will handle assignments, links, comments, checklists, etc.
            $delStmt = db()->prepare("DELETE FROM tasks WHERE id = ?");
            $delStmt->execute([$id]);
            
            logActivity('tasks', 'delete', "Deleted task {$task['task_number']}: {$task['title']}", null);
            setFlash('success', "Task {$task['task_number']} deleted successfully.");
        } else {
            setFlash('danger', 'Task not found.');
        }
    } catch (Exception $e) {
        setFlash('danger', 'Error deleting task: ' . $e->getMessage());
    }
}

header('Location: index.php');
exit;
