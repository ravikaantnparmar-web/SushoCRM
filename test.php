<?php
$files = [
    'modules/orders/create.php', 'modules/orders/edit.php',
    'modules/invoices/create.php', 'modules/invoices/edit.php'
];
foreach ($files as $f) {
    if (file_exists($f)) {
        $content = file_get_contents($f);
        preg_match_all('/\$subtotal.*?\$total.*?;/s', $content, $matches);
        echo "--- $f ---\n";
        print_r($matches[0][0]);
        echo "\n";
    }
}
