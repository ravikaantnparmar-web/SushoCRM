<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\db.php';
$stmt = db()->query('DESCRIBE users');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
