<?php
require 'config/config.php';
require 'config/db.php';
$q = db()->query('DESCRIBE quotations')->fetchAll(PDO::FETCH_ASSOC);
echo "--- Quotations ---\n";
foreach($q as $row) echo $row['Field'] . ' : ' . $row['Type'] . "\n";
$c = db()->query('DESCRIBE customers')->fetchAll(PDO::FETCH_ASSOC);
echo "--- Customers ---\n";
foreach($c as $row) echo $row['Field'] . ' : ' . $row['Type'] . "\n";
