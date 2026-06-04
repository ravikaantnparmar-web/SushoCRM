<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$query = sanitize($_GET['q'] ?? '');
if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = db()->prepare("
    SELECT id, name, company, customer_type 
    FROM customers 
    WHERE (name LIKE ? OR company LIKE ? OR customer_code LIKE ?) 
    AND LOWER(status) IN ('active', 'prospect')
    ORDER BY company ASC, name ASC
    LIMIT 15
");
$stmt->execute(["%$query%", "%$query%", "%$query%"]);
$results = $stmt->fetchAll();

echo json_encode($results);
