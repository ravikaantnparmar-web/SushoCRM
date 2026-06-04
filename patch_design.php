<?php

$modernDesign = <<<HTML
<div class="crm-card print-container border-0 shadow-none">
  <div class="crm-card-body p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom border-2" style="border-color: #f1f5f9 !important;">
      <div class="d-flex gap-3 align-items-center">
        <?php if(file_exists(__DIR__.'/../../assets/images/company_logo.png')): ?>
          <img src="<?= BASE_URL ?>/assets/images/company_logo.png" alt="Logo" style="max-height: 65px; object-fit: contain;">
        <?php endif; ?>
        <div>
          <h2 class="fw-bolder mb-1" style="color: #0f172a; font-size: 1.5rem; letter-spacing: -0.02em;"><?= APP_NAME ?></h2>
          <div style="color: #475569; font-size: 0.85rem; line-height: 1.4;">
            <?= COMPANY_ADDRESS ?><br>
            <?= COMPANY_EMAIL ?> | <?= COMPANY_PHONE ?><br>
            <strong>GST:</strong> <?= COMPANY_GST ?>
          </div>
        </div>
      </div>
      <div class="text-end">
        <h1 class="text-uppercase fw-bold mb-2" style="color: #cbd5e1; letter-spacing: 2px; font-size: 1.8rem;">Quotation</h1>
        <div class="d-inline-block text-start" style="font-size: 0.85rem;">
          <div class="d-flex justify-content-between gap-4 mb-1">
            <span class="text-muted fw-medium text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Quote No.</span>
            <span class="fw-bold text-dark"><?= e(\$q['quote_number']) ?></span>
          </div>
          <div class="d-flex justify-content-between gap-4 mb-1">
            <span class="text-muted fw-medium text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Date</span>
            <span class="fw-semibold text-dark"><?= formatDate(\$q['created_at']) ?></span>
          </div>
          <?php if(\$q['valid_until']): ?>
          <div class="d-flex justify-content-between gap-4">
            <span class="text-muted fw-medium text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Valid Until</span>
            <span class="fw-semibold text-dark"><?= formatDate(\$q['valid_until']) ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Customer Details -->
    <div class="row mb-4">
      <div class="col-7">
        <div class="p-3 rounded" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
          <div class="text-uppercase fw-bold mb-2" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px;">Billed To</div>
          <div class="fs-6 fw-bold" style="color: #1e293b;"><?= e(\$q['customer_name']) ?></div>
          <?php if(\$q['customer_company']): ?><div class="fw-medium text-secondary mb-1" style="font-size: 0.85rem;"><?= e(\$q['customer_company']) ?></div><?php endif; ?>
          <div style="color: #475569; font-size: 0.85rem; line-height: 1.4; mt-2">
            <?= e(\$q['address']) ?><br>
            <?= e(\$q['city']) ?><?= \$q['state'] ? ', ' . e(\$q['state']) : '' ?> <?= e(\$q['pincode']) ?><br>
            <?php if(\$q['phone']): ?><span class="me-3"><i class="bi bi-telephone text-muted"></i> <?= e(\$q['phone']) ?></span><?php endif; ?>
            <?php if(\$q['email']): ?><span><i class="bi bi-envelope text-muted"></i> <?= e(\$q['email']) ?></span><br><?php endif; ?>
            <?php if(!empty(\$q['gst_number'])): ?><div class="mt-1"><strong class="text-dark">GST:</strong> <?= e(\$q['gst_number']) ?></div><?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-5">
        <?php if(!empty(\$q['contact_person']) || !empty(\$q['project_name'])): ?>
        <div class="p-3 rounded h-100" style="border: 1px solid #f1f5f9;">
          <?php if(!empty(\$q['contact_person'])): ?>
          <div class="mb-3">
            <div class="text-uppercase fw-bold mb-1" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px;">Contact Person</div>
            <div class="fw-semibold" style="color: #1e293b; font-size: 0.9rem;"><?= e(\$q['contact_person']) ?></div>
          </div>
          <?php endif; ?>
          <?php if(!empty(\$q['project_name'])): ?>
          <div>
            <div class="text-uppercase fw-bold mb-1" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px;">Project Name</div>
            <div class="fw-semibold" style="color: #1e293b; font-size: 0.9rem;"><?= e(\$q['project_name']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Items Table -->
    <div class="mb-4 rounded" style="border: 1px solid #e2e8f0; overflow: hidden;">
      <table class="table table-sm mb-0">
        <thead style="background-color: #f8fafc;">
          <tr>
            <th class="py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 5%;">#</th>
            <th class="py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 45%;">Description</th>
            <th class="text-end py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 10%;">Qty</th>
            <th class="text-end py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 15%;">Price</th>
            <th class="text-end py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 10%;">Tax</th>
            <th class="text-end py-2 px-3 text-uppercase border-bottom" style="color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; border-color: #e2e8f0 !important; width: 15%;">Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach(\$items as \$i => \$item): ?>
          <tr>
            <td class="text-muted px-3 py-2 border-bottom" style="font-size: 0.85rem; border-color: #f1f5f9 !important;"><?= \$i+1 ?></td>
            <td class="px-3 py-2 fw-medium border-bottom" style="color: #334155; font-size: 0.85rem; border-color: #f1f5f9 !important;"><?= e(\$item['description']) ?></td>
            <td class="text-end px-3 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #f1f5f9 !important;"><?= (float)\$item['qty'] ?> <span style="font-size: 0.75rem;"><?= e(\$item['unit']) ?></span></td>
            <td class="text-end px-3 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #f1f5f9 !important;"><?= formatCurrency(\$item['unit_price']) ?></td>
            <td class="text-end px-3 py-2 text-muted border-bottom" style="font-size: 0.85rem; border-color: #f1f5f9 !important;"><?= \$item['tax_rate'] ?>%</td>
            <td class="text-end px-3 py-2 fw-bold border-bottom" style="color: #0f172a; font-size: 0.9rem; border-color: #f1f5f9 !important;"><?= formatCurrency(\$item['line_total']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Totals & Notes Row -->
    <div class="row mb-3">
      <div class="col-7">
        <?php if(\$q['terms'] || \$q['notes']): ?>
        <div class="pe-4">
          <?php if(\$q['notes']): ?>
          <div class="mb-3">
            <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #64748b; font-size: 0.7rem; letter-spacing: 0.5px;">Notes</strong>
            <div style="color: #475569; font-size: 0.8rem; line-height: 1.5; white-space:pre-line"><?= e(\$q['notes']) ?></div>
          </div>
          <?php endif; ?>
          <?php if(\$q['terms']): ?>
          <div>
            <strong class="text-uppercase fw-bold mb-1 d-block" style="color: #64748b; font-size: 0.7rem; letter-spacing: 0.5px;">Terms & Conditions</strong>
            <div style="color: #475569; font-size: 0.8rem; line-height: 1.5; white-space:pre-line"><?= e(\$q['terms']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      
      <div class="col-5">
        <div class="p-3 rounded" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
            <span class="text-muted fw-medium" style="font-size: 0.85rem;">Subtotal</span>
            <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= formatCurrency(\$q['subtotal']) ?></span>
          </div>
          
          <?php if(\$q['discount_amount'] > 0): ?>
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
            <span class="text-muted fw-medium" style="font-size: 0.85rem;">Discount <?= \$q['discount_type']==='percent'?"({\$q['discount_value']}%)":'' ?></span>
            <span class="fw-semibold text-danger" style="font-size: 0.85rem;">- <?= formatCurrency(\$q['discount_amount']) ?></span>
          </div>
          <?php endif; ?>
          
          <div class="d-flex justify-content-between mb-3 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
            <span class="text-muted fw-medium" style="font-size: 0.85rem;">Tax Amount</span>
            <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= formatCurrency(\$q['tax_amount']) ?></span>
          </div>
          
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-bolder text-uppercase" style="color: #0f172a; font-size: 0.9rem; letter-spacing: 0.5px;">Total</span>
            <span class="fw-bolder text-primary" style="font-size: 1.25rem;"><?= formatCurrency(\$q['total']) ?></span>
          </div>
        </div>
        
        <div class="text-end mt-2 px-1">
          <span class="fst-italic" style="color: #64748b; font-size: 0.75rem;"><?= amountInWords(\$q['total']) ?></span>
        </div>
      </div>
    </div>
    
  </div>
</div>
<style>
@media print {
  @page { margin: 0.5cm; size: A4 portrait; }
  body { background: white; font-size: 12px; }
  body * { visibility: hidden; }
  .print-container, .print-container * { visibility: visible; }
  .print-container { 
      position: absolute; left: 0; top: 0; width: 100%; height: 100%; 
      border: none !important; box-shadow: none !important; margin: 0 !important; padding: 15px !important; 
  }
  .main-content, .page-content { padding: 0 !important; margin: 0 !important; }
  .table { page-break-inside: auto; }
  tr { page-break-inside: avoid; page-break-after: auto; }
}
</style>
HTML;

function updateFile($filename, $modernDesign) {
    $content = file_get_contents($filename);
    $startPos = strpos($content, '<div class="crm-card print-container');
    $endPos = strpos($content, '</style>');
    if ($startPos !== false && $endPos !== false) {
        $endPos += 8; // length of </style>
        $newContent = substr($content, 0, $startPos) . $modernDesign . substr($content, $endPos);
        file_put_contents($filename, $newContent);
        echo "Updated $filename\n";
    } else {
        echo "Failed to find boundaries in $filename\n";
    }
}

updateFile('modules/quotations/view.php', $modernDesign);
updateFile('modules/quotations/public_quote.php', $modernDesign);
