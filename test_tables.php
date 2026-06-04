<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\db.php';
$stmt = db()->query("SHOW TABLES");
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
