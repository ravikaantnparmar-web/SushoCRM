<?php
$view = file_get_contents('modules/quotations/view.php');

// Extract the quotation block from view.php
// It starts with <div class="crm-card print-container border-0 shadow-none">
// and ends right before <?php // Fetch version history 
$startStr = '<div class="crm-card print-container border-0 shadow-none">';
$endStr = '<?php';
$posStart = strpos($view, $startStr);
$posEnd = strpos($view, $endStr, $posStart);

$quoteBlock = substr($view, $posStart, $posEnd - $posStart);

// Now construct the public_quote.php
$public = <<<HTML
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
    <title>Quotation: <?= e(\$q['quote_number']) ?> - V<?= \$q['version'] ?></title>
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

{$quoteBlock}

</body>
</html>
HTML;

file_put_contents('modules/quotations/public_quote.php', $public);
echo "Successfully rebuilt public_quote.php\n";
