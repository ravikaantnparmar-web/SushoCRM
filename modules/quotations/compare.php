<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('quotations', 'view');
$v1_id = (int)($_GET['v1'] ?? 0);
$v2_id = (int)($_GET['v2'] ?? 0);

if (!$v1_id || !$v2_id) {
    setFlash('danger', 'Please select two versions to compare.');
    header('Location: ' . BASE_URL . '/modules/quotations/index.php');
    exit;
}

// Fetch Quotation 1 (Base Version)
$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company FROM quotations q JOIN customers c ON q.customer_id = c.id WHERE q.id=?");
$stmt->execute([$v1_id]);
$q1 = $stmt->fetch();

// Fetch Quotation 2 (Compare Version)
$stmt2 = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company FROM quotations q JOIN customers c ON q.customer_id = c.id WHERE q.id=?");
$stmt2->execute([$v2_id]);
$q2 = $stmt2->fetch();

if (!$q1 || !$q2) {
    setFlash('danger', 'Quotation version not found.');
    header('Location: ' . BASE_URL . '/modules/quotations/index.php');
    exit;
}

// Check if they belong to same quote number
if ($q1['quote_number'] !== $q2['quote_number']) {
    setFlash('danger', 'Cannot compare versions of different quotations.');
    header('Location: ' . BASE_URL . '/modules/quotations/index.php');
    exit;
}

// Ensure V1 is the older one
if ($q1['version'] > $q2['version']) {
    $temp = $q1; $q1 = $q2; $q2 = $temp;
    $temp_id = $v1_id; $v1_id = $v2_id; $v2_id = $temp_id;
}

// Fetch Items
$stmt1 = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
$stmt1->execute([$v1_id]);
$items1 = $stmt1->fetchAll();
$stmt2 = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
$stmt2->execute([$v2_id]);
$items2 = $stmt2->fetchAll();

// Map items by description (or product_id) to compare
$map1 = [];
foreach ($items1 as $item) {
    $key = trim(strtolower($item['description']));
    $map1[$key] = $item;
}
$map2 = [];
foreach ($items2 as $item) {
    $key = trim(strtolower($item['description']));
    $map2[$key] = $item;
}

$allKeys = array_unique(array_merge(array_keys($map1), array_keys($map2)));

$pageTitle = 'Compare Versions: ' . $q1['quote_number'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Compare Versions</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Compare Revisions: <?= e($q1['quote_number']) ?></h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/index.php">Quotations</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q2['id'] ?>"><?= e($q1['quote_number']) ?></a></li>
        <li class="breadcrumb-item active">Compare V<?= $q1['version'] ?> & V<?= $q2['version'] ?></li>
      </ol>
    </nav>
  </div>
  <div class="d-flex align-items-center gap-2">
    <a href="view.php?id=<?= $q2['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Quote</a>
  </div>
</div>

<!-- Header Comparison -->
<div class="row g-3 mb-4">
    <!-- V1 Card -->
    <div class="col-md-6">
        <div class="crm-card h-100">
            <div class="crm-card-header bg-light">
                <h3 class="crm-card-title m-0">Version <?= $q1['version'] ?></h3>
                <span class="badge bg-secondary"><?= formatDate($q1['created_at']) ?></span>
            </div>
            <div class="crm-card-body p-3">
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Status</div>
                    <div class="col-8 fw-semibold"><?= statusBadge($q1['status']) ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Subtotal</div>
                    <div class="col-8"><?= formatCurrency($q1['subtotal']) ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Discount</div>
                    <div class="col-8"><?= formatCurrency($q1['discount_amount']) ?> <?= $q1['discount_type']==='percent'?"({$q1['discount_value']}%)":'' ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Tax</div>
                    <div class="col-8"><?= formatCurrency($q1['tax_amount']) ?></div>
                </div>
                <div class="row mb-0">
                    <div class="col-4 text-muted small fw-bold text-dark">Total Amount</div>
                    <div class="col-8 fw-bold fs-5 text-primary"><?= formatCurrency($q1['total']) ?></div>
                </div>
                <?php if($q1['revision_notes']): ?>
                <hr class="my-2">
                <div class="small text-muted fst-italic">"<?= e($q1['revision_notes']) ?>"</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- V2 Card -->
    <div class="col-md-6">
        <div class="crm-card h-100 border-primary">
            <div class="crm-card-header bg-primary-subtle border-bottom border-primary-subtle">
                <h3 class="crm-card-title m-0 text-primary">Version <?= $q2['version'] ?></h3>
                <span class="badge bg-primary"><?= formatDate($q2['created_at']) ?></span>
            </div>
            <div class="crm-card-body p-3">
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Status</div>
                    <div class="col-8 fw-semibold"><?= statusBadge($q2['status']) ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Subtotal</div>
                    <div class="col-8">
                        <?= formatCurrency($q2['subtotal']) ?>
                        <?php 
                        $diff = $q2['subtotal'] - $q1['subtotal'];
                        if ($diff > 0) echo '<span class="text-danger small ms-1">(+'.formatCurrency($diff).')</span>';
                        elseif ($diff < 0) echo '<span class="text-success small ms-1">('.formatCurrency($diff).')</span>';
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Discount</div>
                    <div class="col-8">
                        <?= formatCurrency($q2['discount_amount']) ?> <?= $q2['discount_type']==='percent'?"({$q2['discount_value']}%)":'' ?>
                        <?php 
                        $diff = $q2['discount_amount'] - $q1['discount_amount'];
                        if ($diff > 0) echo '<span class="text-success small ms-1">(+'.formatCurrency($diff).')</span>';
                        elseif ($diff < 0) echo '<span class="text-danger small ms-1">('.formatCurrency($diff).')</span>';
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Tax</div>
                    <div class="col-8">
                        <?= formatCurrency($q2['tax_amount']) ?>
                        <?php 
                        $diff = $q2['tax_amount'] - $q1['tax_amount'];
                        if ($diff > 0) echo '<span class="text-danger small ms-1">(+'.formatCurrency($diff).')</span>';
                        elseif ($diff < 0) echo '<span class="text-success small ms-1">('.formatCurrency($diff).')</span>';
                        ?>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-4 text-muted small fw-bold text-dark">Total Amount</div>
                    <div class="col-8 fw-bold fs-5 text-primary">
                        <?= formatCurrency($q2['total']) ?>
                        <?php 
                        $diff = $q2['total'] - $q1['total'];
                        if ($diff > 0) echo '<span class="badge bg-danger-subtle text-danger ms-2"><i class="bi bi-arrow-up-short"></i> '.formatCurrency($diff).'</span>';
                        elseif ($diff < 0) echo '<span class="badge bg-success-subtle text-success ms-2"><i class="bi bi-arrow-down-short"></i> '.formatCurrency(abs($diff)).'</span>';
                        ?>
                    </div>
                </div>
                <?php if($q2['revision_notes']): ?>
                <hr class="my-2">
                <div class="small text-muted fst-italic">"<?= e($q2['revision_notes']) ?>"</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Item Level Comparison -->
<div class="crm-card">
    <div class="crm-card-header">
        <h5 class="crm-card-title"><i class="bi bi-list-check me-2"></i>Line Items Comparison</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle">Item Description</th>
                    <th colspan="3" class="text-center">V<?= $q1['version'] ?></th>
                    <th colspan="3" class="text-center border-start border-primary border-opacity-25 bg-primary-subtle">V<?= $q2['version'] ?></th>
                    <th rowspan="2" class="align-middle text-center" style="width: 120px;">Difference</th>
                </tr>
                <tr>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Rate</th>
                    <th class="text-end">Total</th>
                    <th class="text-end border-start border-primary border-opacity-25 bg-primary-subtle">Qty</th>
                    <th class="text-end bg-primary-subtle">Rate</th>
                    <th class="text-end bg-primary-subtle">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($allKeys)):
                ?>
                <tr><td colspan="8" class="text-center text-muted">No items found in either quotation.</td></tr>
                <?php else:
                foreach ($allKeys as $key):
                    $i1 = $map1[$key] ?? null;
                    $i2 = $map2[$key] ?? null;
                    $desc = $i1['description'] ?? $i2['description'];
                    
                    $v1_total = $i1 ? $i1['line_total'] : 0;
                    $v2_total = $i2 ? $i2['line_total'] : 0;
                    
                    $diff = $v2_total - $v1_total;
                    
                    $diffClass = ''; $diffIcon = '';
                    if ($diff > 0) { $diffClass = 'text-danger fw-bold'; $diffIcon = '+'; }
                    elseif ($diff < 0) { $diffClass = 'text-success fw-bold'; $diffIcon = ''; }
                ?>
                <tr>
                    <td class="fw-medium">
                        <?= e($desc) ?>
                        <?php if(!$i1) echo '<span class="badge bg-success ms-2">New</span>'; ?>
                        <?php if(!$i2) echo '<span class="badge bg-danger ms-2">Removed</span>'; ?>
                    </td>
                    
                    <!-- V1 Data -->
                    <td class="text-end <?= !$i1 ? 'bg-light text-muted' : '' ?>">
                        <?= $i1 ? (float)$i1['qty'] . ' <small>' . e($i1['unit']) . '</small>' : '-' ?>
                    </td>
                    <td class="text-end <?= !$i1 ? 'bg-light text-muted' : '' ?>">
                        <?= $i1 ? formatCurrency($i1['unit_price']) : '-' ?>
                    </td>
                    <td class="text-end fw-semibold <?= !$i1 ? 'bg-light text-muted' : '' ?>">
                        <?= $i1 ? formatCurrency($i1['line_total']) : '-' ?>
                    </td>
                    
                    <!-- V2 Data -->
                    <td class="text-end border-start border-primary border-opacity-25 <?= !$i2 ? 'bg-light text-muted' : '' ?>">
                        <?= $i2 ? (float)$i2['qty'] . ' <small>' . e($i2['unit']) . '</small>' : '-' ?>
                        <?php 
                        if ($i1 && $i2 && $i1['qty'] != $i2['qty']) {
                            $qdiff = $i2['qty'] - $i1['qty'];
                            $qcolor = $qdiff > 0 ? 'text-success' : 'text-danger';
                            $qsign = $qdiff > 0 ? '+' : '';
                            echo "<div class='small {$qcolor}'>({$qsign}{$qdiff})</div>";
                        }
                        ?>
                    </td>
                    <td class="text-end <?= !$i2 ? 'bg-light text-muted' : '' ?>">
                        <?= $i2 ? formatCurrency($i2['unit_price']) : '-' ?>
                        <?php 
                        if ($i1 && $i2 && $i1['unit_price'] != $i2['unit_price']) {
                            $rdiff = $i2['unit_price'] - $i1['unit_price'];
                            $rcolor = $rdiff > 0 ? 'text-danger' : 'text-success'; // Higher price = danger for customer
                            $rsign = $rdiff > 0 ? '+' : '';
                            echo "<div class='small {$rcolor}'>({$rsign}".formatCurrency($rdiff).")</div>";
                        }
                        ?>
                    </td>
                    <td class="text-end fw-semibold <?= !$i2 ? 'bg-light text-muted' : '' ?>">
                        <?= $i2 ? formatCurrency($i2['line_total']) : '-' ?>
                    </td>
                    
                    <!-- Difference -->
                    <td class="text-center bg-light <?= $diffClass ?>">
                        <?php 
                        if ($diff != 0) {
                            echo $diffIcon . formatCurrency($diff);
                        } else {
                            echo '<span class="text-muted small">No change</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
