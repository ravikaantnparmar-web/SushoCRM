<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$hash = $_GET['hash'] ?? '';

$stmt = db()->prepare("SELECT q.*, 
    (SELECT cnt.name FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS customer_name, 
    c.company_name AS customer_company, 
    c.address_line1 AS address, c.city, c.state, c.pincode, c.company_email AS email, c.gst_number, 
    (SELECT cnt.mobile FROM contacts cnt JOIN contact_relations cr ON cnt.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS phone, 
    u.name AS created_by_name 
    FROM quotations q JOIN customers c ON q.customer_id = c.id LEFT JOIN users u ON q.created_by = u.id WHERE q.id=?");
$stmt->execute([$id]);
$q = $stmt->fetch();

if (!$q || md5($q['id'] . 'crm_secret' . $q['created_at']) !== $hash) { 
    die("Invalid or expired quotation link.");
}

$stmtItems = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation: <?= e($q['quote_number']) ?> - V<?= $q['version'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; padding: 20px; }
        .crm-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 900px; margin: 0 auto; overflow: hidden; }
        .crm-card-body { padding: 40px; }
        @media print {
            body { padding: 0; background: #fff; }
            .crm-card { box-shadow: none; max-width: 100%; border: none; }
            .d-print-none { display: none !important; }
        }
    </style>
</head>
<body>

<div class="text-center mb-4 d-print-none">
    <button type="button" class="btn btn-primary" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / Download PDF
    </button>
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


</body>
</html>