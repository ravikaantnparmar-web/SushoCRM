<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\db.php';
$tables = db()->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $cols = db()->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_COLUMN);
    $where = [];
    foreach ($cols as $col) {
        $where[] = "`$col` LIKE '%262145%'";
    }
    $query = "SELECT * FROM `$table` WHERE " . implode(' OR ', $where);
    $stmt = db()->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Found in $table: ";
        print_r($row);
    }
}
