<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\db.php';
$stmt = db()->query("SELECT * FROM settings");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
