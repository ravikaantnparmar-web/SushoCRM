<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $db = db();
    
    // Check if exists
    $stmt = $db->prepare("SELECT travel_number FROM travels WHERE id = ?");
    $stmt->execute([$id]);
    $t = $stmt->fetch();
    
    if ($t) {
        // Delete documents first (optional but good practice to clean up files)
        $doc_stmt = $db->prepare("SELECT file_path FROM travel_documents WHERE travel_id = ?");
        $doc_stmt->execute([$id]);
        $docs = $doc_stmt->fetchAll();
        foreach ($docs as $doc) {
            $path = __DIR__ . '/../../' . $doc['file_path'];
            if (file_exists($path)) unlink($path);
        }
        
        $db->prepare("DELETE FROM travels WHERE id = ?")->execute([$id]);
        logActivity('travels', 'delete', "Deleted travel record: " . $t['travel_number'], $id);
        setFlash('success', 'Travel record deleted successfully.');
    } else {
        setFlash('danger', 'Record not found.');
    }
}

header('Location: index.php');
exit;
