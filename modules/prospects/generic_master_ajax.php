<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$table  = $_GET['table']  ?? $_POST['table']  ?? '';
$pdo    = db();

// ── Whitelist of all allowed master tables ─────────────────────────────────
// Format: table_name => ['col' => name_column, 'label' => display_label, 'has_color' => bool]
$allowedMasters = [
    // Lead Masters
    'lead_statuses'     => ['col' => 'status_name',   'label' => 'Lead Status',         'has_color' => true],
    'lead_priorities'   => ['col' => 'priority_name', 'label' => 'Lead Priority',        'has_color' => true],
    'lead_sources'      => ['col' => 'source_name',   'label' => 'Lead Source',          'has_color' => true],
    'lead_types'        => ['col' => 'type_name',     'label' => 'Lead Type',            'has_color' => true],
    'site_stages'       => ['col' => 'stage_name',    'label' => 'Site Stage',           'has_color' => false],
    'project_types'     => ['col' => 'type_name',     'label' => 'Project Type',         'has_color' => false],
    'lead_product_types'=> ['col' => 'type_name',     'label' => 'Product Type',         'has_color' => false],
    'interested_products'=> ['col' => 'product_name', 'label' => 'Interested Products',  'has_color' => false],
    'sales_stages'      => ['col' => 'stage_name',    'label' => 'Sales Stage',          'has_color' => false],
    // Contact & Company Masters
    'contact_types'     => ['col' => 'type_name',     'label' => 'Contact Type / Designation', 'has_color' => false],
    'company_types'     => ['col' => 'type_name',     'label' => 'Company Type',         'has_color' => false],
    'industry_types'    => ['col' => 'type_name',     'label' => 'Industry Type',        'has_color' => false],
    'business_categories'=> ['col' => 'category_name','label' => 'Business Category',   'has_color' => false],
    'company_statuses'  => ['col' => 'status_name',   'label' => 'Company Status',       'has_color' => true],
    // Meeting Masters
    'meeting_types'     => ['col' => 'type_name',     'label' => 'Meeting Type',         'has_color' => false],
    'meeting_statuses'  => ['col' => 'status_name',   'label' => 'Meeting Status',       'has_color' => true],
    // Customer Masters
    'customer_types'    => ['col' => 'type_name',     'label' => 'Customer Type',        'has_color' => false],
    'address_types'     => ['col' => 'type_name',     'label' => 'Address Type',         'has_color' => false],
];

if (!array_key_exists($table, $allowedMasters)) {
    echo json_encode(['success' => false, 'message' => 'Invalid master table: ' . $table]);
    exit;
}

$meta = $allowedMasters[$table];
$col  = $meta['col'];

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM `$table` ORDER BY sort_order ASC, id ASC");
            $data = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $data, 'meta' => $meta]);
            break;

        case 'save':
            $id    = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $name  = sanitize($_POST['name'] ?? '');
            $color = sanitize($_POST['color_code'] ?? '#64748b');
            $order = (int)($_POST['sort_order'] ?? 0);
            $active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Name is required.']);
                exit;
            }

            if ($id) {
                if ($meta['has_color']) {
                    $stmt = $pdo->prepare("UPDATE `$table` SET `$col` = ?, color_code = ?, sort_order = ?, is_active = ? WHERE id = ?");
                    $stmt->execute([$name, $color, $order, $active, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE `$table` SET `$col` = ?, sort_order = ?, is_active = ? WHERE id = ?");
                    $stmt->execute([$name, $order, $active, $id]);
                }
                echo json_encode(['success' => true, 'message' => $meta['label'] . ' updated successfully.']);
            } else {
                if ($meta['has_color']) {
                    $stmt = $pdo->prepare("INSERT INTO `$table` (`$col`, color_code, sort_order, is_active) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $color, $order, $active]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO `$table` (`$col`, sort_order, is_active) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $order, $active]);
                }
                echo json_encode(['success' => true, 'message' => $meta['label'] . ' added successfully.', 'id' => $pdo->lastInsertId()]);
            }
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                // Soft delete: set is_active = 0
                $stmt = $pdo->prepare("UPDATE `$table` SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Record deactivated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            }
            break;

        case 'delete_hard':
            if (!isSuperAdmin()) {
                echo json_encode(['success' => false, 'message' => 'Super admin only.']);
                exit;
            }
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Record permanently deleted.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            }
            break;

        case 'reorder':
            $ids = $_POST['ids'] ?? [];
            if (is_array($ids)) {
                $stmt = $pdo->prepare("UPDATE `$table` SET sort_order = ? WHERE id = ?");
                foreach ($ids as $order => $id) {
                    $stmt->execute([$order + 1, (int)$id]);
                }
                echo json_encode(['success' => true, 'message' => 'Order saved.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
