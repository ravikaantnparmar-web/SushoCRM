<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT q.*, 
    (SELECT cnt.name FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS customer_name, 
    c.company_name AS customer_company, 
    c.address_line1 AS address, c.city, c.state, c.pincode, c.company_email AS email, c.gst_number, 
    (SELECT cnt.mobile FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS phone, 
    u.name AS created_by_name 
    FROM quotations q JOIN customers c ON q.customer_id = c.id LEFT JOIN users u ON q.created_by = u.id WHERE q.id=?");
$stmt->execute([$id]);
$q = $stmt->fetch();
if (!$q) { setFlash('danger','Quotation not found.'); header('Location: '.BASE_URL.'/modules/quotations/index.php'); exit; }

$stmtItems = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

// Fetch version history
$stmtVersions = db()->prepare("SELECT id, version, is_latest, created_at, total, status, revision_notes, (SELECT name FROM users WHERE id=created_by) AS author FROM quotations WHERE quote_number=? ORDER BY version DESC");
$stmtVersions->execute([$q['quote_number']]);
$versions = $stmtVersions->fetchAll();

// Check if an order has already been generated
$stmtOrderCount = db()->prepare("SELECT COUNT(*) FROM orders WHERE quotation_id = ?");
$stmtOrderCount->execute([$id]);
$hasOrder = $stmtOrderCount->fetchColumn() > 0;

$pageTitle = 'Quotation: ' . $q['quote_number'] . ' - V' . $q['version'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">View Quotation</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(!$q['is_latest']): ?>
<div class="alert alert-warning d-print-none d-flex align-items-center gap-3">
  <i class="bi bi-exclamation-triangle-fill fs-4"></i>
  <div>
    <strong>Historical Revision:</strong> You are viewing an older, read-only revision (V<?= $q['version'] ?>). 
    <a href="index.php" class="alert-link">Return to Quotations List</a>
  </div>
</div>
<?php endif; ?>
<div class="page-header d-print-none">
  <div class="page-header-left"><h1><?= e($q['quote_number']) ?> - V<?= $q['version'] ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/quotations/index.php">Quotations</a></li><li class="breadcrumb-item active"><?= e($q['quote_number']) ?> - V<?= $q['version'] ?></li></ol></nav>
  </div>
  <div class="d-flex align-items-center gap-2">
    <?= statusBadge($q['status']) ?>
    <?php
      $approval_class = match($q['approval_status'] ?? 'pending') {
          'approved' => 'bg-success',
          'rejected' => 'bg-danger',
          'under_review' => 'bg-info',
          'on_hold' => 'bg-warning',
          default => 'bg-secondary'
      };
      $approval_label = ucwords(str_replace('_', ' ', $q['approval_status'] ?? 'pending'));
    ?>
    <span class="badge <?= $approval_class ?> ms-1" title="Internal Approval Status"><i class="bi bi-shield-check me-1"></i><?= $approval_label ?></span>
    <?php 
        $public_hash = md5($q['id'] . 'crm_secret' . $q['created_at']);
        $public_link = BASE_URL . "/modules/quotations/public_quote.php?id={$q['id']}&hash={$public_hash}";
        if (!empty($q['phone'])): 
            $clean_phone = preg_replace('/[^0-9]/', '', $q['phone']);
            $wa_number = (strlen($clean_phone) == 10) ? '91' . $clean_phone : $clean_phone;
            
            $msg_text = "Hello! Here is the quotation {$q['quote_number']} from " . APP_NAME . " for a total of " . formatCurrency($q['total']) . ".

You can view and download your quotation securely here:
{$public_link}";
            $wa_text = urlencode($msg_text);
    ?>
    <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_text ?>" target="_blank" class="btn btn-success btn-sm d-print-none">
      <i class="bi bi-whatsapp me-1"></i> WhatsApp
    </a>
    <a href="sms:<?= $clean_phone ?>?body=<?= $wa_text ?>" class="btn btn-info btn-sm text-white d-print-none">
      <i class="bi bi-chat-text me-1"></i> SMS
    </a>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-secondary btn-sm d-print-none" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
    <?php if(count($versions) > 1): ?>
    <button type="button" class="btn btn-outline-info btn-sm d-print-none" onclick="window.location.href='compare.php?v1=<?= $versions[1]['id'] ?>&v2=<?= $q['id'] ?>'">
      <i class="bi bi-arrow-left-right me-1"></i> Compare Versions
    </button>
    <?php endif; ?>
    <?php if($q['is_latest']): ?>
    <?php if(($q['approval_status'] ?? 'pending') !== 'approved'): ?>
    <a href="edit.php?id=<?= $q['id'] ?>" class="btn btn-primary btn-sm d-print-none">
      <i class="bi bi-pencil me-1"></i> Edit
    </a>
    <button type="button" class="btn btn-warning btn-sm d-print-none" data-bs-toggle="modal" data-bs-target="#revisionModal">
      <i class="bi bi-files me-1"></i> Create Revision
    </button>
    <?php endif; ?>
    <?php if(($q['approval_status'] ?? 'pending') === 'approved' && !$hasOrder): ?>
    <button type="button" class="btn btn-success btn-sm d-print-none" onclick="document.getElementById('convertForm').submit();">
      <i class="bi bi-cart-check me-1"></i> Convert to Order
    </button>
    <?php elseif($hasOrder): ?>
    <span class="btn btn-secondary btn-sm d-print-none pe-none">
      <i class="bi bi-check-circle me-1"></i> Order Generated
    </span>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<div class="crm-card print-container border-0 shadow-none">
  <div class="crm-card-body p-0">
    
    <!-- Top Accent Bar -->
    <div style="height: 6px; background: #1e3a8a; width: 100%; border-radius: 4px 4px 0 0; margin-bottom: 25px;"></div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-start px-4 mb-4 pb-3" style="border-bottom: 2px solid #f1f5f9;">
      <div class="d-flex gap-3 align-items-center">
        <?php if(file_exists(__DIR__.'/../../assets/images/company_logo.png')): ?>
          <img src="<?= BASE_URL ?>/assets/images/company_logo.png" alt="Logo" style="max-height: 70px; object-fit: contain;">
        <?php endif; ?>
        <div>
          <h2 class="fw-bolder mb-1" style="color: #1e3a8a; font-size: 1.6rem; letter-spacing: -0.02em;"><?= APP_NAME ?></h2>
          <div style="color: #475569; font-size: 0.85rem; line-height: 1.4;">
            <?= COMPANY_ADDRESS ?><br>
            <?= COMPANY_EMAIL ?> | <?= COMPANY_PHONE ?><br>
            <strong>GST:</strong> <?= COMPANY_GST ?>
          </div>
        </div>
      </div>
      <div class="text-end">
        <h1 class="text-uppercase fw-black mb-2" style="color: #1e293b; letter-spacing: 2px; font-size: 2rem; font-weight: 900;">Quotation</h1>
        <div class="d-inline-block text-start p-2 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0; min-width: 200px;">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Quote No.</span>
            <span class="fw-bolder text-dark" style="font-size: 0.85rem;"><?= e($q['quote_number']) ?> - V<?= $q['version'] ?></span>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Date</span>
            <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= formatDate($q['created_at']) ?></span>
          </div>
          <?php if($q['valid_until']): ?>
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Valid Until</span>
            <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= formatDate($q['valid_until']) ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Customer Details -->
    <div class="row mb-3 px-4">
      <div class="col-7">
        <strong class="small text-uppercase mb-1 d-block" style="color: #1e3a8a; letter-spacing: 1px; font-weight: 700;">Quotation For</strong>
        <div class="fs-5 fw-bolder" style="color: #0f172a;"><?= e($q['customer_name']) ?></div>
        <?php if($q['customer_company']): ?><div class="fw-medium mb-1" style="color: #475569; font-size: 0.9rem;"><?= e($q['customer_company']) ?></div><?php endif; ?>
        <div class="mt-1" style="color: #64748b; font-size: 0.85rem; line-height: 1.4;">
          <?= e($q['address']) ?><br>
          <?= e($q['city']) ?><?= $q['state'] ? ', ' . e($q['state']) : '' ?> <?= e($q['pincode']) ?><br>
          <?php if($q['phone']): ?><span class="me-3">Ph: <?= e($q['phone']) ?></span><?php endif; ?>
          <?php if($q['email']): ?><span>Email: <?= e($q['email']) ?></span><br><?php endif; ?>
          <?php if(!empty($q['gst_number'])): ?><div class="mt-1"><strong class="text-dark">GST:</strong> <?= e($q['gst_number']) ?></div><?php endif; ?>
        </div>
      </div>
      <div class="col-5">
        <?php if(!empty($q['contact_person']) || !empty($q['project_name'])): ?>
        <div class="p-2 h-100 d-flex flex-column justify-content-center" style="border-left: 2px solid #f1f5f9;">
          <?php if(!empty($q['contact_person'])): ?>
          <div class="mb-2">
            <strong class="small text-uppercase mb-1 d-block" style="color: #94a3b8; font-size: 0.65rem; letter-spacing: 1px;">Contact Person</strong>
            <div class="fw-bold" style="color: #1e293b; font-size: 0.9rem;"><?= e($q['contact_person']) ?></div>
          </div>
          <?php endif; ?>
          <?php if(!empty($q['project_name'])): ?>
          <div>
            <strong class="small text-uppercase mb-1 d-block" style="color: #94a3b8; font-size: 0.65rem; letter-spacing: 1px;">Project Name</strong>
            <div class="fw-bold" style="color: #1e293b; font-size: 0.9rem;"><?= e($q['project_name']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Items Table -->
    <div class="mb-4 px-4">
      <table class="table mb-0 w-100" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th class="py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 5%; border-bottom: 2px solid #1e3a8a;">#</th>
            <th class="py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 45%; border-bottom: 2px solid #1e3a8a;">Item Description</th>
            <th class="text-end py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 10%; border-bottom: 2px solid #1e3a8a;">Qty</th>
            <th class="text-end py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 15%; border-bottom: 2px solid #1e3a8a;">Price</th>
            <th class="text-end py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 10%; border-bottom: 2px solid #1e3a8a;">Tax</th>
            <th class="text-end py-2 px-2 text-uppercase font-monospace" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px; width: 15%; border-bottom: 2px solid #1e3a8a;">Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $i => $item): ?>
          <tr>
            <td class="text-muted px-2 py-2 border-bottom" style="font-size: 0.85rem; border-color: #e2e8f0 !important; vertical-align: middle;"><?= $i+1 ?></td>
            <td class="px-2 py-2 fw-medium border-bottom" style="color: #1e293b; font-size: 0.85rem; border-color: #e2e8f0 !important; vertical-align: middle;"><?= e($item['description']) ?></td>
            <td class="text-end px-2 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #e2e8f0 !important; vertical-align: middle; white-space: nowrap;"><?= (float)$item['qty'] ?> <span style="font-size: 0.75rem;"><?= e($item['unit']) ?></span></td>
            <td class="text-end px-2 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #e2e8f0 !important; vertical-align: middle; white-space: nowrap;"><?= formatCurrency($item['unit_price']) ?></td>
            <td class="text-end px-2 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #e2e8f0 !important; vertical-align: middle; white-space: nowrap;"><?= $item['tax_rate'] ?>%</td>
            <td class="text-end px-2 py-2 fw-bold border-bottom" style="color: #0f172a; font-size: 0.9rem; border-color: #e2e8f0 !important; vertical-align: middle; white-space: nowrap;"><?= formatCurrency($item['line_total']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Totals & Notes Row -->
    <div class="row mb-3 px-4">
      <div class="col-7">
        
        <!-- Amount in Words -->
        <div class="mb-4">
          <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #1e3a8a; font-size: 0.7rem; letter-spacing: 1px;">Amount in Words</strong>
          <div class="fw-semibold fst-italic" style="color: #1e293b; font-size: 0.85rem; line-height: 1.4;"><?= amountInWords($q['total']) ?></div>
        </div>

        <?php if($q['terms'] || $q['notes']): ?>
        <div class="pe-4 d-flex flex-column">
          <?php if($q['notes']): ?>
          <div class="mb-3">
            <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #64748b; font-size: 0.7rem; letter-spacing: 1px;">Notes</strong>
            <div style="color: #475569; font-size: 0.8rem; line-height: 1.5; white-space:pre-line"><?= e($q['notes']) ?></div>
          </div>
          <?php endif; ?>

          <?php if($q['approval_remarks']): ?>
          <div class="mb-3 d-print-none border-start border-info border-3 ps-3">
            <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #0dcaf0; font-size: 0.7rem; letter-spacing: 1px;">Internal Approval Remarks</strong>
            <div style="color: #475569; font-size: 0.8rem; line-height: 1.5; white-space:pre-line"><?= e($q['approval_remarks']) ?></div>
          </div>
          <?php endif; ?>
          <?php if($q['terms']): ?>
          <div>
            <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #64748b; font-size: 0.7rem; letter-spacing: 1px;">Terms & Conditions</strong>
            <div style="color: #475569; font-size: 0.8rem; line-height: 1.5; white-space:pre-line"><?= e($q['terms']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      
      <div class="col-5">
        <div class="ms-auto" style="max-width: 350px;">
          <div class="p-3 rounded-2" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Subtotal</span>
              <span class="fw-bold text-dark" style="font-size: 0.85rem;"><?= formatCurrency($q['subtotal']) ?></span>
            </div>
            
            <?php if($q['discount_amount'] > 0): ?>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Discount <?= $q['discount_type']==='percent'?"({$q['discount_value']}%)":'' ?></span>
              <span class="fw-bold text-danger" style="font-size: 0.85rem;">- <?= formatCurrency($q['discount_amount']) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom" style="border-color: #cbd5e1 !important;">
              <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Tax Amount</span>
              <span class="fw-bold text-dark" style="font-size: 0.85rem;"><?= formatCurrency($q['tax_amount']) ?></span>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-2">
              <span class="fw-black text-uppercase" style="color: #1e3a8a; font-size: 0.85rem; letter-spacing: 1px; font-weight: 900;">Grand Total</span>
              <span class="fw-black" style="color: #1e3a8a; font-size: 1.2rem; font-weight: 900;"><?= formatCurrency($q['total']) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<?php if(($q['approval_status'] ?? 'pending') === 'approved'): ?>
<form id="convertForm" action="<?= BASE_URL ?>/modules/orders/create.php" method="POST" style="display:none;">
    <input type="hidden" name="customer_id" value="<?= $q['customer_id'] ?>">
    <input type="hidden" name="quotation_id" value="<?= $q['id'] ?>">
    <input type="hidden" name="status" value="pending">
    <input type="hidden" name="discount_type" value="<?= e($q['discount_type']) ?>">
    <input type="hidden" name="discount_value" value="<?= e($q['discount_value']) ?>">
    <textarea name="notes"><?= e($q['notes']) ?></textarea>
    <textarea name="terms"><?= e($q['terms']) ?></textarea>
    <?php foreach($items as $i => $item): ?>
    <input type="hidden" name="items[<?= $i ?>][product_id]" value="<?= $item['product_id'] ?>">
    <input type="hidden" name="items[<?= $i ?>][description]" value="<?= e($item['description']) ?>">
    <input type="hidden" name="items[<?= $i ?>][qty]" value="<?= $item['qty'] ?>">
    <input type="hidden" name="items[<?= $i ?>][unit]" value="<?= $item['unit'] ?>">
    <input type="hidden" name="items[<?= $i ?>][unit_price]" value="<?= $item['unit_price'] ?>">
    <input type="hidden" name="items[<?= $i ?>][tax_rate]" value="<?= $item['tax_rate'] ?>">
    <?php endforeach; ?>
</form>
<?php endif; ?>

<?php if(isAdmin() && $q['is_latest']): ?>
<div class="crm-card mt-4 d-print-none border-info border">
  <div class="crm-card-body p-4">
    <h5 class="fw-bold text-info mb-3"><i class="bi bi-shield-lock me-2"></i>Internal Approval Actions</h5>
    <form action="update_approval.php" method="POST" class="d-flex flex-column gap-3 w-100">
      <input type="hidden" name="id" value="<?= $q['id'] ?>">
      
      <div class="d-flex align-items-center gap-2">
        <label class="fw-semibold text-muted">Update Status:</label>
        <select name="approval_status" class="form-select" style="max-width: 250px;">
          <option value="pending" <?= ($q['approval_status'] ?? 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="under_review" <?= ($q['approval_status'] ?? 'pending') === 'under_review' ? 'selected' : '' ?>>Under Review</option>
          <option value="approved" <?= ($q['approval_status'] ?? 'pending') === 'approved' ? 'selected' : '' ?>>Approved</option>
          <option value="rejected" <?= ($q['approval_status'] ?? 'pending') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
          <option value="on_hold" <?= ($q['approval_status'] ?? 'pending') === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
        </select>
      </div>

      <div>
        <label class="fw-semibold text-muted mb-1">Remarks (Optional):</label>
        <textarea name="approval_remarks" class="form-control" rows="2" placeholder="Add a comment..."><?= e($q['approval_remarks'] ?? '') ?></textarea>
      </div>

      <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-info text-white">Save Approval</button>
        <?php if(($q['approval_status'] ?? 'pending') !== 'approved'): ?>
        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Quotation must be approved before conversion to sales order.</span>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<style>
@media print {
  @page { margin: 1cm; size: auto; }
  body { background: white !important; font-size: 12px; }
  body * { visibility: hidden; }
  .print-container, .print-container * { visibility: visible; }
  .print-container { 
      position: absolute; left: 0; top: 0; width: 100%; height: auto; min-height: 100%;
      border: none !important; box-shadow: none !important; margin: 0 !important; padding: 0 !important; 
  }
  .main-content, .page-content { padding: 0 !important; margin: 0 !important; }
  .table { page-break-inside: auto; }
  tr { page-break-inside: avoid; page-break-after: auto; }
}
</style>
<?php if(count($versions) > 1): ?>
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
          <?php foreach($versions as $index => $v): ?>
          <?php 
          $diffHtml = '';
          if (isset($versions[$index + 1])) {
              $prevV = $versions[$index + 1];
              $diff = $v['total'] - $prevV['total'];
              if ($diff > 0) {
                  $diffHtml = '<div class="small text-danger fw-semibold mt-1" style="font-size: 0.75rem;"><i class="bi bi-arrow-up-short"></i>' . formatCurrency(abs($diff)) . '</div>';
              } elseif ($diff < 0) {
                  $diffHtml = '<div class="small text-success fw-semibold mt-1" style="font-size: 0.75rem;"><i class="bi bi-arrow-down-short"></i>' . formatCurrency(abs($diff)) . '</div>';
              } else {
                  $diffHtml = '<div class="small text-muted fw-semibold mt-1" style="font-size: 0.75rem;">No change</div>';
              }
          }
          ?>
          <tr <?= $v['id'] == $q['id'] ? 'class="table-primary"' : '' ?>>
            <td>
              <span class="badge <?= $v['is_latest'] ? 'bg-success' : 'bg-secondary' ?>">V<?= $v['version'] ?></span>
              <?php if($v['is_latest']) echo ' <small class="text-success fw-bold">(Latest)</small>'; ?>
            </td>
            <td><?= formatDate($v['created_at']) ?></td>
            <td><?= e($v['author']) ?></td>
            <td><?= e($v['revision_notes'] ?: '—') ?></td>
            <td class="fw-bold">
              <?= formatCurrency($v['total']) ?>
              <?= $diffHtml ?>
            </td>
            <td><?= statusBadge($v['status']) ?></td>
            <td>
              <?php if($v['id'] != $q['id']): ?>
                <div class="d-flex gap-1">
                  <a href="view.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                  <a href="compare.php?v1=<?= $v['id'] ?>&v2=<?= $q['id'] ?>" class="btn btn-sm btn-outline-info">Compare</a>
                </div>
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

</div> <!-- closes page-content -->

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="create_revision.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Create Revision (V<?= $q['version'] + 1 ?>)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>This will clone the current quotation and create <strong>Version <?= $q['version'] + 1 ?></strong>. This version (V<?= $q['version'] ?>) will become read-only history.</p>
          <input type="hidden" name="id" value="<?= $q['id'] ?>">
          <div class="mb-3">
            <label class="form-label fw-semibold">Reason for Revision <span class="text-danger">*</span></label>
            <select name="revision_reason" class="form-select" required>
                <option value="">Select Reason...</option>
                <option value="Customer Negotiated Price">Customer Negotiated Price</option>
                <option value="Scope Change">Scope Change</option>
                <option value="Product Specification Change">Product Specification Change</option>
                <option value="Discount Offered">Discount Offered</option>
                <option value="Other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Revision Notes (Optional)</label>
            <textarea name="revision_notes" class="form-control" rows="3" placeholder="Additional details about this revision..."></textarea>
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
<?php include __DIR__ . '/../../includes/footer.php'; ?>
