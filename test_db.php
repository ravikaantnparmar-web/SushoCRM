<?php
require_once __DIR__ . '/config/db.php';
$stmt = db()->query("DESCRIBE users");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
