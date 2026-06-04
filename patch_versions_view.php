<?php
$view = file_get_contents('modules/quotations/view.php');

// 1. Update Title and Header left
$oldTitle = <<<HTML
$pageTitle = 'Quotation: ' . \$q['quote_number'];
HTML;
$newTitle = <<<HTML
$pageTitle = 'Quotation: ' . \$q['quote_number'] . ' - V' . \$q['version'];
HTML;
$view = str_replace($oldTitle, $newTitle, $view);

$oldBread = <<<HTML
<div class="page-header-left"><h1><?= e(\$q['quote_number']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/index.php">Quotations</a></li><li class="breadcrumb-item active"><?= e(\$q['quote_number']) ?></li></ol></nav>
  </div>
HTML;
$newBread = <<<HTML
<div class="page-header-left"><h1><?= e(\$q['quote_number']) ?> - V<?= \$q['version'] ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/index.php">Quotations</a></li><li class="breadcrumb-item active"><?= e(\$q['quote_number']) ?> - V<?= \$q['version'] ?></li></ol></nav>
  </div>
HTML;
$view = str_replace($oldBread, $newBread, $view);


// 2. Add Read-only Alert
$alertBlock = <<<HTML
<?php if(!\$q['is_latest']): ?>
<div class="alert alert-warning d-print-none d-flex align-items-center gap-3">
  <i class="bi bi-exclamation-triangle-fill fs-4"></i>
  <div>
    <strong>Historical Revision:</strong> You are viewing an older, read-only revision (V<?= \$q['version'] ?>). 
    <a href="index.php" class="alert-link">Return to Quotations List</a>
  </div>
</div>
<?php endif; ?>
HTML;
$view = str_replace('<div class="page-header d-print-none">', $alertBlock . "\n" . '<div class="page-header d-print-none">', $view);


// 3. Add "Create Revision" and "Edit" button if it's latest
$oldBtns = <<<HTML
    <button type="button" class="btn btn-outline-secondary btn-sm d-print-none" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
  </div>
HTML;
$newBtns = <<<HTML
    <button type="button" class="btn btn-outline-secondary btn-sm d-print-none" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
    <?php if(\$q['is_latest']): ?>
    <a href="edit.php?id=<?= \$q['id'] ?>" class="btn btn-primary btn-sm d-print-none">
      <i class="bi bi-pencil me-1"></i> Edit
    </a>
    <button type="button" class="btn btn-warning btn-sm d-print-none" data-bs-toggle="modal" data-bs-target="#revisionModal">
      <i class="bi bi-files me-1"></i> Create Revision
    </button>
    <?php endif; ?>
  </div>
HTML;
$view = str_replace($oldBtns, $newBtns, $view);

// 4. Update the visual Quote No on the PDF itself
$oldQuoteNo = <<<HTML
<span class="fw-bolder text-dark" style="font-size: 0.85rem;"><?= e(\$q['quote_number']) ?></span>
HTML;
$newQuoteNo = <<<HTML
<span class="fw-bolder text-dark" style="font-size: 0.85rem;"><?= e(\$q['quote_number']) ?> - V<?= \$q['version'] ?></span>
HTML;
$view = str_replace($oldQuoteNo, $newQuoteNo, $view);


// 5. Add Version History Section and Revision Modal at the bottom
$versionHistory = <<<HTML
<?php
// Fetch version history
\$stmtVersions = db()->prepare("SELECT id, version, is_latest, created_at, total, status, revision_notes, (SELECT name FROM users WHERE id=created_by) AS author FROM quotations WHERE quote_number=? ORDER BY version DESC");
\$stmtVersions->execute([\$q['quote_number']]);
\$versions = \$stmtVersions->fetchAll();
?>
<?php if(count(\$versions) > 1): ?>
<div class="crm-card mt-4 d-print-none">
  <div class="crm-card-body p-4">
    <h5 class="fw-bold mb-4">Version History</h5>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Version</th>
            <th>Date</th>
            <th>Author</th>
            <th>Notes</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach(\$versions as \$v): ?>
          <tr <?= \$v['id'] == \$q['id'] ? 'class="table-primary"' : '' ?>>
            <td>
              <span class="badge <?= \$v['is_latest'] ? 'bg-success' : 'bg-secondary' ?>">V<?= \$v['version'] ?></span>
              <?php if(\$v['is_latest']) echo ' <small class="text-success fw-bold">(Latest)</small>'; ?>
            </td>
            <td><?= formatDate(\$v['created_at']) ?></td>
            <td><?= e(\$v['author']) ?></td>
            <td><?= e(\$v['revision_notes'] ?: '—') ?></td>
            <td class="fw-bold"><?= formatCurrency(\$v['total']) ?></td>
            <td><?= statusBadge(\$v['status']) ?></td>
            <td>
              <?php if(\$v['id'] != \$q['id']): ?>
                <a href="view.php?id=<?= \$v['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <?php else: ?>
                <span class="text-muted small">Currently Viewing</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="create_revision.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Create Revision (V<?= \$q['version'] + 1 ?>)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>This will clone the current quotation and create <strong>Version <?= \$q['version'] + 1 ?></strong>. This version (V<?= \$q['version'] ?>) will become read-only history.</p>
          <input type="hidden" name="id" value="<?= \$q['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Revision Notes (Optional)</label>
            <textarea name="revision_notes" class="form-control" rows="3" placeholder="e.g. Client requested 10% discount..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Create Revision</button>
        </div>
      </form>
    </div>
  </div>
</div>
HTML;

$view = str_replace("<?php include __DIR__ . '/../../includes/footer.php'; ?>", $versionHistory . "\n<?php include __DIR__ . '/../../includes/footer.php'; ?>", $view);
file_put_contents('modules/quotations/view.php', $view);


// Update public_quote.php
$public = file_get_contents('modules/quotations/public_quote.php');
$public = str_replace($oldQuoteNo, $newQuoteNo, $public);
$publicTitle = <<<HTML
<title>Quotation: <?= e(\$q['quote_number']) ?></title>
HTML;
$newPublicTitle = <<<HTML
<title>Quotation: <?= e(\$q['quote_number']) ?> - V<?= \$q['version'] ?></title>
HTML;
$public = str_replace($publicTitle, $newPublicTitle, $public);
file_put_contents('modules/quotations/public_quote.php', $public);

echo "Successfully patched view.php and public_quote.php\n";
