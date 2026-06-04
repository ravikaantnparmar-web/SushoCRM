<?php
require_once 'config/db.php';
$stmt = db()->query("DESCRIBE leads");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
