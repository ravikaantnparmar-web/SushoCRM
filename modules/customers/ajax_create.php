<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
        exit;
    }

    $name          = sanitize($_POST['name'] ?? '');
    $company       = sanitize($_POST['company'] ?? '');
    $customer_type = sanitize($_POST['customer_type'] ?? 'Retail Customer');
    $phone         = sanitize($_POST['phone'] ?? '');
    $email         = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $whatsapp      = sanitize($_POST['whatsapp_number'] ?? '');
    $city          = sanitize($_POST['city'] ?? '');
    $state         = sanitize($_POST['state'] ?? '');

    // Validate customer_type
    $validTypes = ['Dealer','Distributor','Architect','Interior Designer','Builder','Contractor','Retail Customer','Corporate Client','Vendor/Supplier','Channel Partner'];
    if (!in_array($customer_type, $validTypes)) $customer_type = 'Retail Customer';

    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Customer name is required.']);
        exit;
    }
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
        exit;
    }

    try {
        $code = generateCustomerCode();
        $stmt = db()->prepare("
            INSERT INTO customers 
                (customer_code, name, company, customer_type, phone, whatsapp_number, email, city, state, status, created_by) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $code, $name, $company, $customer_type,
            $phone, $whatsapp ?: null, $email ?: null,
            $city ?: null, $state ?: null,
            'Active', $_SESSION['user_id']
        ]);
        $id = db()->lastInsertId();

        logActivity('customers', 'create', "Created customer via quick-add: $name", $id);

        // Return fresh CSRF token so modal can be reused
        $newCsrf = generateCsrfToken();

        echo json_encode([
            'success' => true,
            'csrf_token' => $newCsrf,
            'customer' => [
                'id'            => (int)$id,
                'name'          => $name,
                'company'       => $company,
                'customer_type' => $customer_type
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
