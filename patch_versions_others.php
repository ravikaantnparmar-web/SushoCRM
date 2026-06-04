<?php
// 1. Patch index.php
$index = file_get_contents('modules/quotations/index.php');

$oldWhere = <<<PHP
\$where = ['1=1']; \$params = [];
PHP;
$newWhere = <<<PHP
\$where = ['q.is_latest = 1']; \$params = [];
PHP;
$index = str_replace($oldWhere, $newWhere, $index);

$oldSelect = <<<PHP
\$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company FROM quotations q JOIN customers c ON q.customer_id=c.id WHERE \$whereStr ORDER BY q.created_at DESC LIMIT \$per OFFSET {\$pag['offset']}");
PHP;
$newSelect = <<<PHP
\$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company, (SELECT COUNT(*) FROM quotations q2 WHERE q2.quote_number = q.quote_number) as total_versions FROM quotations q JOIN customers c ON q.customer_id=c.id WHERE \$whereStr ORDER BY q.created_at DESC LIMIT \$per OFFSET {\$pag['offset']}");
PHP;
$index = str_replace($oldSelect, $newSelect, $index);

$oldTableQuoteNo = <<<HTML
<td><a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= \$q['id'] ?>" class="fw-semibold text-primary"><?= e(\$q['quote_number']) ?></a></td>
HTML;
$newTableQuoteNo = <<<HTML
<td>
  <a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= \$q['id'] ?>" class="fw-semibold text-primary"><?= e(\$q['quote_number']) ?></a>
  <span class="badge bg-light text-secondary border ms-1" title="<?= \$q['total_versions'] ?> total revisions">V<?= \$q['version'] ?></span>
</td>
HTML;
$index = str_replace($oldTableQuoteNo, $newTableQuoteNo, $index);

file_put_contents('modules/quotations/index.php', $index);

// 2. Patch edit.php
$edit = file_get_contents('modules/quotations/edit.php');
$oldEditCheck = <<<PHP
if (!\$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }
PHP;
$newEditCheck = <<<PHP
if (!\$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }
if (!\$q['is_latest']) { setFlash('danger','Cannot edit a historical version. Only the latest version is editable.'); header('Location: '.BASE_URL.'/modules/quotations/view.php?id='.\$id); exit; }
PHP;
$edit = str_replace($oldEditCheck, $newEditCheck, $edit);
file_put_contents('modules/quotations/edit.php', $edit);

// 3. Patch delete.php
$delete = file_get_contents('modules/quotations/delete.php');
$oldDeleteCheck = <<<PHP
if (!\$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }
PHP;
$newDeleteCheck = <<<PHP
if (!\$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }
if (!\$q['is_latest']) { setFlash('danger','Cannot delete a historical version. Only the latest version can be deleted.'); header('Location: '.BASE_URL.'/modules/quotations/view.php?id='.\$id); exit; }
PHP;
$delete = str_replace($oldDeleteCheck, $newDeleteCheck, $delete);

// Also when deleting the latest version, should it delete ALL versions, or just the latest one?
// If we delete the latest version, we should probably delete the whole quote group or revert the previous one.
// Let's just delete the whole group.
$oldDeleteRun = <<<PHP
    db()->prepare("DELETE FROM quotations WHERE id=?")->execute([\$id]);
PHP;
$newDeleteRun = <<<PHP
    // Delete all revisions of this quote
    db()->prepare("DELETE FROM quotations WHERE quote_number=?")->execute([\$q['quote_number']]);
PHP;
$delete = str_replace($oldDeleteRun, $newDeleteRun, $delete);
file_put_contents('modules/quotations/delete.php', $delete);

echo "Successfully patched index.php, edit.php, and delete.php\n";
