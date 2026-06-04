<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();

header('Content-Type: application/json');

$term = $_GET['q'] ?? '';

$sql = "SELECT id, name, contact_type, mobile, email, organization_name FROM contacts WHERE name LIKE ? OR mobile LIKE ? OR email LIKE ? OR organization_name LIKE ? ORDER BY name ASC LIMIT 20";
$stmt = db()->prepare($sql);
$search = '%' . $term . '%';
$stmt->execute([$search, $search, $search, $search]);

$results = [];
foreach ($stmt->fetchAll() as $row) {
    $text = $row['name'];
    if ($row['mobile']) $text .= ' (' . $row['mobile'] . ')';
    if ($row['organization_name']) $text .= ' - ' . $row['organization_name'];
    
    $results[] = [
        'id' => $row['id'],
        'text' => $text,
        'contact' => $row // Pass full data for auto-filling
    ];
}

echo json_encode(['results' => $results]);
