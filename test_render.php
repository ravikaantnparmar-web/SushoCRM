<?php
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

ob_start();
$_GET['id'] = 3;
include 'c:\xampp\htdocs\SushobhaCRM\modules\prospects\view.php';
$html = ob_get_clean();

$lines = explode("\n", $html);
foreach ($lines as $line) {
    if (strpos($line, 'text-success') !== false && strpos($line, '500,000') !== false) {
        echo "FOUND: " . trim($line) . "\n";
        echo "HEX: " . bin2hex(trim($line)) . "\n";
    }
}
