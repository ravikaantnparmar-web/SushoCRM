<?php
require 'config/db.php';
$pdo = db();

$tables = ['customers', 'vendors', 'prospects'];
foreach ($tables as $t) {
    echo "--- $t ---\n";
    $q = $pdo->query("DESCRIBE $t");
    $cols = $q->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $cols) . "\n";
}
