<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $revision_notes = sanitize($_POST['revision_notes'] ?? '');
    $revision_reason = sanitize($_POST['revision_reason'] ?? '');

    $stmt = db()->prepare("SELECT * FROM quotations WHERE id = ?");
    $stmt->execute([$id]);
    $oldQ = $stmt->fetch();

    if (!$oldQ) {
        setFlash('danger', 'Quotation not found.');
        header('Location: ' . BASE_URL . '/modules/quotations/index.php');
        exit;
    }

    if (!$oldQ['is_latest']) {
        setFlash('danger', 'You can only create a revision from the latest version.');
        header('Location: ' . BASE_URL . '/modules/quotations/view.php?id=' . $id);
        exit;
    }

    try {
        db()->beginTransaction();

        // 1. Mark existing versions as not latest
        $stmtUpdate = db()->prepare("UPDATE quotations SET is_latest = 0 WHERE quote_number = ?");
        $stmtUpdate->execute([$oldQ['quote_number']]);

        // 2. Insert new version
        $newVersion = $oldQ['version'] + 1;
        $stmtInsert = db()->prepare("INSERT INTO quotations (quote_number, version, is_latest, revision_notes, revision_reason, customer_id, project_name, contact_person, status, valid_until, subtotal, discount_type, discount_value, discount_amount, tax_amount, total, notes, terms, created_by) VALUES (?, ?, 1, ?, ?, ?, ?, ?, 'draft', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmtInsert->execute([
            $oldQ['quote_number'],
            $newVersion,
            $revision_notes,
            $revision_reason,
            $oldQ['customer_id'],
            $oldQ['project_name'],
            $oldQ['contact_person'],
            $oldQ['valid_until'],
            $oldQ['subtotal'],
            $oldQ['discount_type'],
            $oldQ['discount_value'],
            $oldQ['discount_amount'],
            $oldQ['tax_amount'],
            $oldQ['total'],
            $oldQ['notes'],
            $oldQ['terms'],
            $_SESSION['user_id']
        ]);
        
        $newId = db()->lastInsertId();

        // 3. Clone line items
        $stmtItems = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY sort_order ASC");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll();

        if ($items) {
            $stmtInsertItem = db()->prepare("INSERT INTO quotation_items (quotation_id, product_id, description, qty, unit, unit_price, tax_rate, line_total, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmtInsertItem->execute([
                    $newId,
                    $item['product_id'],
                    $item['description'],
                    $item['qty'],
                    $item['unit'],
                    $item['unit_price'],
                    $item['tax_rate'],
                    $item['line_total'],
                    $item['sort_order']
                ]);
            }
        }

        db()->commit();
        setFlash('success', 'Revision ' . $newVersion . ' created successfully.');
        header('Location: ' . BASE_URL . '/modules/quotations/edit.php?id=' . $newId);
        exit;

    } catch (Exception $e) {
        db()->rollBack();
        error_log("Revision creation error: " . $e->getMessage());
        setFlash('danger', 'Error creating revision. Please try again.');
        header('Location: ' . BASE_URL . '/modules/quotations/view.php?id=' . $id);
        exit;
    }
} else {
    header('Location: ' . BASE_URL . '/modules/quotations/index.php');
    exit;
}
