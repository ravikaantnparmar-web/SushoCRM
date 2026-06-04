<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        db()->beginTransaction();
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $priority_id = !empty($_POST['priority_id']) ? (int)$_POST['priority_id'] : null;
        $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $estimated_hours = (float)($_POST['estimated_hours'] ?? 0);
        $is_recurring = isset($_POST['is_recurring']) ? 1 : 0;
        $recurring_type = $is_recurring && !empty($_POST['recurring_type']) ? $_POST['recurring_type'] : null;
        
        // Find default "Pending" status
        $statusStmt = db()->query("SELECT id FROM task_statuses WHERE sort_order = 1 LIMIT 1");
        $status_id = $statusStmt->fetchColumn();
        
        if (empty($title)) {
            throw new Exception("Title is required.");
        }
        
        $task_number = generateTaskNumber();
        $created_by = $_SESSION['user_id'];
        
        $stmt = db()->prepare("
            INSERT INTO tasks (
                task_number, title, description, category_id, priority_id, status_id, 
                start_date, due_date, estimated_hours, is_recurring, recurring_type, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $task_number, $title, $description, $category_id, $priority_id, $status_id,
            $start_date, $due_date, $estimated_hours, $is_recurring, $recurring_type, $created_by
        ]);
        
        $task_id = db()->lastInsertId();
        
        // Handle Assignees
        if (!empty($_POST['assignees']) && is_array($_POST['assignees'])) {
            $stmtAssign = db()->prepare("INSERT INTO task_assignments (task_id, user_id, is_watcher, assigned_by) VALUES (?, ?, 0, ?)");
            foreach ($_POST['assignees'] as $uid) {
                $stmtAssign->execute([$task_id, (int)$uid, $created_by]);
            }
        }
        
        // Handle Watchers
        if (!empty($_POST['watchers']) && is_array($_POST['watchers'])) {
            $stmtWatch = db()->prepare("INSERT INTO task_assignments (task_id, user_id, is_watcher, assigned_by) VALUES (?, ?, 1, ?) ON DUPLICATE KEY UPDATE is_watcher=is_watcher");
            foreach ($_POST['watchers'] as $uid) {
                $stmtWatch->execute([$task_id, (int)$uid, $created_by]);
            }
        }
        
        // Handle Links
        $entity_type = $_POST['entity_type'] ?? '';
        $entity_id = !empty($_POST['entity_id']) ? (int)$_POST['entity_id'] : null;
        if (!empty($entity_type) && $entity_id) {
            $stmtLink = db()->prepare("INSERT INTO task_links (task_id, entity_type, entity_id) VALUES (?, ?, ?)");
            $stmtLink->execute([$task_id, $entity_type, $entity_id]);
        }
        
        logActivity('tasks', 'create', "Created task $task_number: $title", $task_id);
        
        db()->commit();
        setFlash('success', "Task $task_number created successfully.");
        header('Location: index.php');
        exit;
        
    } catch (Exception $e) {
        db()->rollBack();
        setFlash('danger', $e->getMessage());
    }
}

$categories = db()->query("SELECT * FROM task_categories ORDER BY sort_order")->fetchAll();
$priorities = db()->query("SELECT * FROM task_priorities ORDER BY level DESC")->fetchAll();
$users = db()->query("SELECT id, name FROM users WHERE is_active = 1 ORDER BY name")->fetchAll();

$pageTitle = "Create Task";
include __DIR__ . '/../../includes/header.php';
?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Tasks</div>
    </div>
    <div class="page-content">
        <?= flashHtml() ?>
        
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Create New Task</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Tasks</a></li>
                        <li class="breadcrumb-item active">Create Task</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form method="POST" action="" class="crm-card p-4">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" required autofocus placeholder="What needs to be done?">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Add detailed instructions, context, or notes..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Date & Time</label>
                            <input type="datetime-local" name="due_date" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="bg-light rounded p-4 border h-100">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                <?php foreach($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority_id" class="form-select">
                                <?php foreach($priorities as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estimated Hours</label>
                            <input type="number" step="0.5" min="0" name="estimated_hours" class="form-control" placeholder="0.00">
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assignees</label>
                            <select name="assignees[]" class="form-select select2" multiple data-placeholder="Select team members">
                                <?php foreach($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= e($u['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Followers / Watchers</label>
                            <select name="watchers[]" class="form-select select2" multiple data-placeholder="Select watchers">
                                <?php foreach($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= e($u['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link to Entity</label>
                            <select name="entity_type" id="entity_type" class="form-select mb-2">
                                <option value="">-- No Link --</option>
                                <option value="lead">Lead</option>
                                <option value="customer">Customer</option>
                                <option value="contact">Contact</option>
                                <option value="quotation">Quotation</option>
                                <option value="order">Sales Order</option>
                                <option value="invoice">Invoice</option>
                            </select>
                            
                            <select name="entity_id" id="entity_id" class="form-select" style="display:none;">
                                <!-- Loaded via AJAX -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-top mt-4 pt-4 text-end">
                <a href="index.php" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Create Task</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    $('#entity_type').change(function() {
        var type = $(this).val();
        var $entityId = $('#entity_id');
        
        if (!type) {
            $entityId.hide().empty();
            return;
        }
        
        $entityId.show().html('<option value="">Loading...</option>');
        
        $.ajax({
            url: 'ajax_handler.php',
            type: 'POST',
            data: { action: 'get_entities', entity_type: type },
            dataType: 'json',
            success: function(data) {
                $entityId.empty();
                if (data.length === 0) {
                    $entityId.html('<option value="">No records found</option>');
                } else {
                    $entityId.append('<option value="">Select ' + type + '...</option>');
                    $.each(data, function(index, item) {
                        $entityId.append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                }
            }
        });
    });
});
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
