<?php
require_once 'config/db.php';
$stmt = db()->query("DESCRIBE lead_meetings");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($cols);
