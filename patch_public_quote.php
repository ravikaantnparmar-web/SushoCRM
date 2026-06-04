<?php
// 1. Create public_quote.php
$content = <<<HTML
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

\$id = (int)(\$_GET['id'] ?? 0);
\$hash = \$_GET['hash'] ?? '';

\$stmt = db()->prepare("SELECT q.*, c.company_name AS customer_name, c.company_name AS customer_company, c.address_line1 AS address, c.city, c.state, c.pincode, c.company_email AS email, c.gst_number, (SELECT mobile FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS phone, u.name AS created_by_name FROM quotations q JOIN customers c ON q.customer_id = c.id LEFT JOIN users u ON q.created_by = u.id WHERE q.id=?");
\$stmt->execute([\$id]);
\$q = \$stmt->fetch();

if (!\$q || md5(\$q['id'] . 'crm_secret' . \$q['created_at']) !== \$hash) { 
    die("Invalid or expired quotation link.");
}

\$stmtItems = db()->prepare("SELECT * FROM quotation_items WHERE quotation_id=? ORDER BY sort_order ASC");
\$stmtItems->execute([\$id]);
\$items = \$stmtItems->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation: <?= e(\$q['quote_number']) ?></title>
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

<div class="crm-card print-container">
  <div class="crm-card-body">
    <!-- Header -->
    <div class="row mb-5 border-bottom pb-4">
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
      <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
        <h3 class="text-uppercase text-muted fw-bold mb-3">Quotation</h3>
        <div class="row text-start text-sm-end">
          <div class="col-6 col-sm-12"><strong class="small text-muted text-uppercase">Quote Number</strong><br><span class="fs-6"><?= e(\$q['quote_number']) ?></span></div>
          <div class="col-6 col-sm-12 mt-2"><strong class="small text-muted text-uppercase">Date</strong><br><span><?= formatDate(\$q['created_at']) ?></span></div>
          <?php if(\$q['valid_until']): ?>
            <div class="col-6 col-sm-12 mt-2"><strong class="small text-muted text-uppercase">Valid Until</strong><br><span><?= formatDate(\$q['valid_until']) ?></span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
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

    <!-- Items Table -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered border-primary mb-0" style="--bs-border-opacity: .2;">
        <thead class="table-light">
          <tr>
            <th class="py-3">#</th>
            <th class="py-3">Description</th>
            <th class="text-end py-3">Qty</th>
            <th class="text-end py-3">Price</th>
            <th class="text-end py-3">Tax</th>
            <th class="text-end py-3">Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach(\$items as \$i => \$item): ?>
          <tr>
            <td class="text-muted"><?= \$i+1 ?></td>
            <td><?= e(\$item['description']) ?></td>
            <td class="text-end"><?= (float)\$item['qty'] ?> <?= e(\$item['unit']) ?></td>
            <td class="text-end"><?= formatCurrency(\$item['unit_price']) ?></td>
            <td class="text-end"><?= \$item['tax_rate'] ?>%</td>
            <td class="text-end fw-semibold text-dark"><?= formatCurrency(\$item['line_total']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="row justify-content-end mb-5">
      <div class="col-sm-6 col-md-5 col-lg-4">
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted">Subtotal</td><td class="text-end"><?= formatCurrency(\$q['subtotal']) ?></td></tr>
          <?php if(\$q['discount_amount'] > 0): ?>
          <tr><td class="text-muted">Discount <?= \$q['discount_type']==='percent'?"({\$q['discount_value']}%)":'' ?></td><td class="text-end text-danger">- <?= formatCurrency(\$q['discount_amount']) ?></td></tr>
          <?php endif; ?>
          <tr><td class="text-muted">Tax Amount</td><td class="text-end"><?= formatCurrency(\$q['tax_amount']) ?></td></tr>
          <tr class="border-top border-2"><td class="fw-bold text-dark pt-2 fs-5">Total</td><td class="text-end fw-bold text-primary pt-2 fs-5"><?= formatCurrency(\$q['total']) ?></td></tr>
        </table>
        <div class="text-end text-muted small mt-2 fst-italic">
            <?= amountInWords(\$q['total']) ?>
        </div>
      </div>
    </div>

    <!-- Terms & Notes -->
    <div class="row">
      <div class="col-md-6 mb-4 mb-md-0">
        <?php if(\$q['terms']): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Terms & Conditions</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e(\$q['terms']) ?></div>
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <?php if(\$q['notes']): ?>
        <strong class="small text-muted text-uppercase mb-2 d-block">Notes</strong>
        <div class="small text-muted" style="white-space:pre-line"><?= e(\$q['notes']) ?></div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</body>
</html>
HTML;
file_put_contents('modules/quotations/public_quote.php', $content);

// 2. Update view.php to use the WhatsApp button and SMS button
$view = file_get_contents('modules/quotations/view.php');

$oldBtns = <<<HTML
  <div class="d-flex align-items-center gap-2">
    <?= statusBadge(\$q['status']) ?>
    <?php if (!empty(\$q['phone'])): 
        \$wa_number = preg_replace('/[^0-9]/', '', \$q['phone']);
        if (strlen(\$wa_number) == 10) \$wa_number = '91' . \$wa_number;
        \$wa_text = urlencode("Hello! Here is the quotation {\$q['quote_number']} from " . APP_NAME . " for a total of " . formatCurrency(\$q['total']) . ". Please let us know if you have any questions.");
    ?>
    <a href="https://wa.me/<?= \$wa_number ?>?text=<?= \$wa_text ?>" target="_blank" class="btn btn-success btn-sm d-print-none">
      <i class="bi bi-whatsapp me-1"></i> WhatsApp
    </a>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
  </div>
HTML;

$newBtns = <<<HTML
  <div class="d-flex align-items-center gap-2">
    <?= statusBadge(\$q['status']) ?>
    <?php 
        \$public_hash = md5(\$q['id'] . 'crm_secret' . \$q['created_at']);
        \$public_link = BASE_URL . "/modules/quotations/public_quote.php?id={\$q['id']}&hash={\$public_hash}";
        if (!empty(\$q['phone'])): 
            \$clean_phone = preg_replace('/[^0-9]/', '', \$q['phone']);
            \$wa_number = (strlen(\$clean_phone) == 10) ? '91' . \$clean_phone : \$clean_phone;
            
            \$msg_text = "Hello! Here is the quotation {\$q['quote_number']} from " . APP_NAME . " for a total of " . formatCurrency(\$q['total']) . ".\n\nYou can view and download your quotation securely here:\n{\$public_link}";
            \$wa_text = urlencode(\$msg_text);
    ?>
    <a href="https://wa.me/<?= \$wa_number ?>?text=<?= \$wa_text ?>" target="_blank" class="btn btn-success btn-sm d-print-none">
      <i class="bi bi-whatsapp me-1"></i> WhatsApp
    </a>
    <a href="sms:<?= \$clean_phone ?>?body=<?= \$wa_text ?>" class="btn btn-info btn-sm text-white d-print-none">
      <i class="bi bi-chat-text me-1"></i> SMS
    </a>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-secondary btn-sm d-print-none" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print / PDF
    </button>
  </div>
HTML;

if (strpos($view, "wa.me") !== false) {
    $view = str_replace($oldBtns, $newBtns, $view);
} else {
    // If oldBtns string doesn't perfectly match, use regex fallback
    $view = preg_replace(
        '/<div class="d-flex align-items-center gap-2">.*?<\/button>\s*<\/div>/s',
        $newBtns,
        $view
    );
}

file_put_contents('modules/quotations/view.php', $view);
echo "Successfully created public_quote.php and updated view.php\n";
