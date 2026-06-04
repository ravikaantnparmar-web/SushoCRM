<?php
$file = 'modules/quotations/index.php';
$content = file_get_contents($file);

// Find <div class="table-responsive">
$pos = strpos($content, '<div class="table-responsive">');

if ($pos !== false) {
    // Cut at this position
    $goodContent = substr($content, 0, $pos);
    
    // Check if the toolbar is missing
    if (strpos($goodContent, '<div class="table-toolbar">') === false) {
        $toolbar = <<<'EOD'
<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search quotes..." value="<?= e($search) ?>"></div>
      <select name="status" class="form-select form-select-sm" style="width:130px">
        <option value="">All Status</option>
        <?php foreach(['draft','sent','negotiation','accepted','rejected','converted','expired'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$status): ?><a href="<?= BASE_URL ?>/modules/quotations/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
  </div>
EOD;
        
        $full = substr($content, 0, strpos($content, '<a href="<?= BASE_URL ?>/modules/quotations/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Quote</a>') + 120);
        
        // Append toolbar then rest
        $rest = substr($content, $pos);
        file_put_contents($file, $full . "\n</div>\n\n" . $toolbar . "\n" . $rest);
        echo "Fixed index.php";
    }
} else {
    echo "Pattern not found";
}
