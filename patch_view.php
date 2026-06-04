<?php
$view = file_get_contents('modules/quotations/view.php');

// 1. SQL Query
$view = str_replace(
    "SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company, c.address_line1 AS address, c.city, c.state, c.pincode, c.company_email AS email, (SELECT mobile FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS phone, u.name AS created_by_name",
    "SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company, c.address_line1 AS address, c.city, c.state, c.pincode, c.company_email AS email, c.gst_number, (SELECT mobile FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS phone, u.name AS created_by_name",
    $view
);

// 2. Print Button
$printBtnHtml = <<<HTML
  <div class="d-flex align-items-center gap-2">
    <?= statusBadge(\$q['status']) ?>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
  </div>
HTML;
$view = preg_replace(
    '/<div><\?= statusBadge\(\$q\[\'status\'\]\) \?><\/div>/',
    $printBtnHtml,
    $view
);

// 3. Header Logo
$headerOriginal = <<<HTML
      <div class="col-sm-6">
        <h2 class="fw-bold text-primary mb-1"><?= APP_NAME ?></h2>
        <div class="text-muted small">
          <?= COMPANY_ADDRESS ?><br>
          Email: <?= COMPANY_EMAIL ?> | Phone: <?= COMPANY_PHONE ?><br>
          GST: <?= COMPANY_GST ?>
        </div>
      </div>
HTML;
$headerNew = <<<HTML
      <div class="col-sm-6 d-flex align-items-center gap-3">
        <?php if(file_exists(__DIR__.'/../../assets/images/company_logo.png')): ?>
          <img src="<?= BASE_URL ?>/assets/images/company_logo.png" alt="Logo" style="max-height: 80px; object-fit: contain;">
        <?php endif; ?>
        <div>
          <h2 class="fw-bold text-primary mb-1"><?= APP_NAME ?></h2>
          <div class="text-muted small">
            <?= COMPANY_ADDRESS ?><br>
            Email: <?= COMPANY_EMAIL ?> | Phone: <?= COMPANY_PHONE ?><br>
            GST: <?= COMPANY_GST ?>
          </div>
        </div>
      </div>
HTML;
$view = str_replace($headerOriginal, $headerNew, $view);

// 4. Customer Details
$customerOriginal = <<<HTML
    <!-- Customer Details -->
    <div class="row mb-5">
      <div class="col-sm-6">
        <strong class="small text-muted text-uppercase mb-2 d-block">Quotation For:</strong>
        <div class="fs-6 fw-semibold text-dark"><?= e(\$q['customer_name']) ?></div>
        <?php if(\$q['customer_company']): ?><div><?= e(\$q['customer_company']) ?></div><?php endif; ?>
        <div class="text-muted small mt-1">
          <?= e(\$q['address']) ?><br>
          <?= e(\$q['city']) ?><?= \$q['state'] ? ', ' . e(\$q['state']) : '' ?> <?= e(\$q['pincode']) ?><br>
          Phone: <?= e(\$q['phone'] ?: '—') ?><br>
          Email: <?= e(\$q['email'] ?: '—') ?>
        </div>
      </div>
    </div>
HTML;
$customerNew = <<<HTML
    <!-- Customer Details -->
    <div class="row mb-5">
      <div class="col-sm-6">
        <strong class="small text-muted text-uppercase mb-2 d-block">Quotation For:</strong>
        <div class="fs-6 fw-semibold text-dark"><?= e(\$q['customer_name']) ?></div>
        <?php if(\$q['customer_company']): ?><div><?= e(\$q['customer_company']) ?></div><?php endif; ?>
        <div class="text-muted small mt-1">
          <?= e(\$q['address']) ?><br>
          <?= e(\$q['city']) ?><?= \$q['state'] ? ', ' . e(\$q['state']) : '' ?> <?= e(\$q['pincode']) ?><br>
          Phone: <?= e(\$q['phone'] ?: '—') ?><br>
          Email: <?= e(\$q['email'] ?: '—') ?><br>
          <?php if(!empty(\$q['gst_number'])): ?>GST Number: <?= e(\$q['gst_number']) ?><br><?php endif; ?>
        </div>
      </div>
      <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
        <?php if(!empty(\$q['contact_person'])): ?>
        <strong class="small text-muted text-uppercase mb-1 d-block">Contact Person</strong>
        <div class="fs-6 text-dark mb-3"><?= e(\$q['contact_person']) ?></div>
        <?php endif; ?>
        
        <?php if(!empty(\$q['project_name'])): ?>
        <strong class="small text-muted text-uppercase mb-1 d-block">Project Name</strong>
        <div class="fs-6 text-dark"><?= e(\$q['project_name']) ?></div>
        <?php endif; ?>
      </div>
    </div>
HTML;
$view = str_replace($customerOriginal, $customerNew, $view);

// 5. Amount in Words
$totalsOriginal = <<<HTML
          <tr class="border-top border-2"><td class="fw-bold text-dark pt-2 fs-5">Total</td><td class="text-end fw-bold text-primary pt-2 fs-5"><?= formatCurrency(\$q['total']) ?></td></tr>
        </table>
      </div>
    </div>
HTML;
$totalsNew = <<<HTML
          <tr class="border-top border-2"><td class="fw-bold text-dark pt-2 fs-5">Total</td><td class="text-end fw-bold text-primary pt-2 fs-5"><?= formatCurrency(\$q['total']) ?></td></tr>
        </table>
        <div class="text-end text-muted small mt-2 fst-italic">
            <?= amountInWords(\$q['total']) ?>
        </div>
      </div>
    </div>
HTML;
$view = str_replace($totalsOriginal, $totalsNew, $view);

file_put_contents('modules/quotations/view.php', $view);
echo "Successfully patched view.php\n";
