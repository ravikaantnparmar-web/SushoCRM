<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

// Set JSON header
header('Content-Type: application/json');

// Check login
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (!$name) {
        echo json_encode(['status' => 'error', 'message' => 'Category name is required']);
        exit;
    }

    try {
        // Check if category already exists
        $stmt = db()->prepare("SELECT id FROM product_categories WHERE name = ?");
        $stmt->execute([$name]);
        $existing = $stmt->fetch();

        if ($existing) {
            echo json_encode(['status' => 'error', 'message' => 'Category already exists', 'id' => $existing['id']]);
            exit;
        }

        // Insert new category
        $stmt = db()->prepare("INSERT INTO product_categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $id = db()->lastInsertId();

        logActivity('products', 'category_create', "Created category via AJAX: $name", $id);

        echo json_encode([
            'status' => 'success',
            'message' => 'Category added successfully',
            'id' => $id,
            'name' => $name
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
