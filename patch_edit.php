<?php
$file = 'modules/quotations/edit.php';
$content = file_get_contents($file);

// Find where to insert the fetch logic.
// We can insert it right after the permissions check
$insertPoint = <<<PHP
// Ensure the user has permission to edit this quotation
if (!isAdmin() && \$q['created_by'] != \$_SESSION['user_id']) {
    setFlash('danger', 'You do not have permission to edit this quotation.');
    header('Location: '.BASE_URL.'/modules/quotations/index.php');
    exit;
}
PHP;

$newLogic = <<<PHP
// Ensure the user has permission to edit this quotation
if (!isAdmin() && \$q['created_by'] != \$_SESSION['user_id']) {
    setFlash('danger', 'You do not have permission to edit this quotation.');
    header('Location: '.BASE_URL.'/modules/quotations/index.php');
    exit;
}

// Fetch existing items for the frontend JS
\$stmtItems = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
\$stmtItems->execute([\$id]);
\$existingItems = \$stmtItems->fetchAll(PDO::FETCH_ASSOC) ?: [];
PHP;

$content = str_replace($insertPoint, $newLogic, $content);

// Also fix the JS if it's null
$oldJS = "if (existingItems.length > 0)";
$newJS = "if (existingItems && existingItems.length > 0)";
$content = str_replace($oldJS, $newJS, $content);

file_put_contents($file, $content);
echo "Successfully patched edit.php\n";
