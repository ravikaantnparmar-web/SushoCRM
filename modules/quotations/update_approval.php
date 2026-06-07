<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if (!isAdmin()) {
    setFlash('danger', 'You do not have permission to approve quotations.');
    header('Location: ' . BASE_URL . '/modules/quotations/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        setFlash('danger', 'Invalid security token (CSRF). Please try again.');
        logActivity('System', 'CSRF Failure', 'Failed CSRF validation in update_approval.php');
        header('Location: ' . BASE_URL . '/modules/quotations/index.php');
        exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    $status = sanitize($_POST['approval_status'] ?? '');
    $remarks = sanitize($_POST['approval_remarks'] ?? '');
    
    $valid_statuses = ['pending', 'under_review', 'approved', 'rejected', 'on_hold'];
    
    if (!$id || !in_array($status, $valid_statuses)) {
        setFlash('danger', 'Invalid input.');
        header('Location: ' . BASE_URL . '/modules/quotations/index.php');
        exit;
    }
    
    try {
        $stmt = db()->prepare("UPDATE quotations SET approval_status = ?, approval_remarks = ? WHERE id = ?");
        $stmt->execute([$status, $remarks, $id]);
        
        $stmtNum = db()->prepare("SELECT quote_number FROM quotations WHERE id = ?");
        $stmtNum->execute([$id]);
        $quoteNumber = $stmtNum->fetchColumn();
        logActivity('quotations', 'approve', "Updated approval status to '$status' for quotation: $quoteNumber", $id);
        
        setFlash('success', "Quotation approval status updated to " . ucfirst(str_replace('_', ' ', $status)) . ".");
    } catch (Exception $e) {
        logActivity('System', 'Database Error', 'Error updating quotation approval: ' . $e->getMessage());
        setFlash('danger', 'An internal error occurred. Please try again later.');
    }
    
    header('Location: ' . BASE_URL . '/modules/quotations/view.php?id=' . $id);
    exit;
}

header('Location: ' . BASE_URL . '/modules/quotations/index.php');
exit;
