<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

$id = (int)($_GET['id'] ?? 0);

$stmt = db()->prepare("
    SELECT t.*, c.name as category_name, c.color as category_color, 
           p.name as priority_name, p.color as priority_color, 
           s.name as status_name, s.color as status_color, s.is_terminal,
           u.name as created_by_name
    FROM tasks t 
    LEFT JOIN task_categories c ON t.category_id = c.id
    LEFT JOIN task_priorities p ON t.priority_id = p.id
    LEFT JOIN task_statuses s ON t.status_id = s.id
    LEFT JOIN users u ON t.created_by = u.id
    WHERE t.id = ?
");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    setFlash('danger', 'Task not found.');
    header('Location: index.php');
    exit;
}

// Fetch Assignees & Watchers
$stmtAssign = db()->prepare("SELECT a.*, u.name as user_name FROM task_assignments a JOIN users u ON a.user_id = u.id WHERE a.task_id = ? ORDER BY a.is_watcher ASC, u.name ASC");
$stmtAssign->execute([$id]);
$assignments = $stmtAssign->fetchAll();

$assignees = [];
$watchers = [];
foreach ($assignments as $a) {
    if ($a['is_watcher'] == 1) $watchers[] = $a;
    else $assignees[] = $a;
}

// Fetch Checklists
$stmtCheck = db()->prepare("SELECT c.*, u.name as completed_by_name FROM task_checklists c LEFT JOIN users u ON c.completed_by = u.id WHERE c.task_id = ? ORDER BY c.sort_order ASC, c.id ASC");
$stmtCheck->execute([$id]);
$checklists = $stmtCheck->fetchAll();

// Fetch Comments
$stmtComm = db()->prepare("SELECT c.*, u.name as user_name, u.avatar FROM task_comments c JOIN users u ON c.user_id = u.id WHERE c.task_id = ? ORDER BY c.created_at DESC");
$stmtComm->execute([$id]);
$comments = $stmtComm->fetchAll();

// Fetch Activity Logs
$stmtLog = db()->prepare("SELECT l.*, u.name as user_name FROM activity_logs l LEFT JOIN users u ON l.user_id = u.id WHERE l.module = 'tasks' AND l.record_id = ? ORDER BY l.created_at DESC");
$stmtLog->execute([$id]);
$logs = $stmtLog->fetchAll();

// Fetch Links
$stmtLink = db()->prepare("SELECT * FROM task_links WHERE task_id = ?");
$stmtLink->execute([$id]);
$raw_links = $stmtLink->fetchAll();
$links = [];
foreach($raw_links as $l) {
    $entity_name = "Record #" . $l['entity_id'];
    switch($l['entity_type']) {
        case 'lead':
            $n = db()->query("SELECT company_name FROM leads WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = $n;
            break;
        case 'customer':
            $n = db()->query("SELECT name FROM customers WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = $n;
            break;
        case 'contact':
            $n = db()->query("SELECT name FROM contacts WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = $n;
            break;
        case 'quotation':
            $n = db()->query("SELECT quote_number FROM quotations WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = "Quote: " . $n;
            break;
        case 'order':
            $n = db()->query("SELECT order_number FROM orders WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = "Order: " . $n;
            break;
        case 'invoice':
            $n = db()->query("SELECT invoice_number FROM invoices WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = "Invoice: " . $n;
            break;
        case 'project':
            $n = db()->query("SELECT project_number FROM projects WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = "Project: " . $n;
            break;
        case 'ticket':
            $n = db()->query("SELECT ticket_number FROM support_tickets WHERE id = " . (int)$l['entity_id'])->fetchColumn();
            if ($n) $entity_name = "Ticket: " . $n;
            break;
    }
    $l['entity_name'] = $entity_name;
    $links[] = $l;
}

// Fetch Statuses for dropdown
$statuses = db()->query("SELECT * FROM task_statuses ORDER BY sort_order")->fetchAll();

$pageTitle = "Task: " . $task['task_number'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">View Task</div>
    </div>
    <div class="page-content">
        <?= flashHtml() ?>
        
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><?= e($task['task_number']) ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Tasks</a></li>
                        <li class="breadcrumb-item active"><?= e($task['task_number']) ?></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-outline-primary shadow-sm"><i class="bi bi-pencil me-1"></i> Edit Task</a>
                <?php if(isAdmin()): ?>
                    <a href="delete.php?id=<?= $task['id'] ?>" class="btn btn-outline-danger shadow-sm" onclick="return confirm('Are you sure you want to delete this task?');"><i class="bi bi-trash me-1"></i> Delete</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="crm-card mb-4">
                    <div class="crm-card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h2 class="fw-bold mb-0 text-dark"><?= e($task['title']) ?></h2>
                            <span class="badge rounded-pill fs-6" style="background-color: <?= $task['status_color'] ?>"><?= e($task['status_name']) ?></span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-3 mb-4 text-muted small">
                            <div><i class="bi bi-tag-fill text-secondary me-1"></i> <?= $task['category_name'] ? e($task['category_name']) : 'No Category' ?></div>
                            <div><i class="bi bi-flag-fill me-1" style="color: <?= $task['priority_color'] ?>"></i> <?= $task['priority_name'] ? e($task['priority_name']) : 'No Priority' ?></div>
                            <div><i class="bi bi-calendar-event me-1"></i> Created: <?= formatDateTime($task['created_at']) ?></div>
                        </div>

                        <?php if($task['description']): ?>
                        <div class="mb-4 bg-light p-3 rounded">
                            <?= nl2br(e($task['description'])) ?>
                        </div>
                        <?php endif; ?>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted text-uppercase small mb-2">Update Progress</h6>
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="range" class="form-range flex-grow-1" id="progressRange" min="0" max="100" step="5" value="<?= $task['progress_percentage'] ?>">
                                        <span class="fw-bold fs-5" id="progressVal"><?= $task['progress_percentage'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted text-uppercase small mb-2">Change Status</h6>
                                    <select class="form-select" id="statusSelect">
                                        <?php foreach($statuses as $s): ?>
                                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $task['status_id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom mb-3" id="taskTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#checklist">Checklist</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#comments">Comments</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#activity">Activity</button></li>
                </ul>

                <div class="tab-content">
                    <!-- Checklist Tab -->
                    <div class="tab-pane fade show active crm-card p-4" id="checklist">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Sub-Tasks / Checklist</h5>
                        </div>
                        
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" id="newChecklistTitle" placeholder="Add a new checklist item...">
                            <button class="btn btn-primary" type="button" id="btnAddChecklist">Add Item</button>
                        </div>
                        
                        <ul class="list-group" id="checklistGroup">
                            <?php foreach($checklists as $chk): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input chk-toggle" type="checkbox" data-id="<?= $chk['id'] ?>" <?= $chk['is_completed'] ? 'checked' : '' ?>>
                                        <label class="form-check-label <?= $chk['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                            <?= e($chk['title']) ?>
                                        </label>
                                    </div>
                                    <?php if($chk['is_completed']): ?>
                                        <small class="text-muted" style="font-size: 0.75rem;">Done by <?= e($chk['completed_by_name']) ?></small>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php if(empty($checklists)): ?>
                                <li class="list-group-item text-muted text-center" id="noChecklistMsg">No checklist items yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Comments Tab -->
                    <div class="tab-pane fade crm-card p-4" id="comments">
                        <h5 class="mb-3">Comments</h5>
                        <div class="mb-4">
                            <textarea class="form-control mb-2" id="newCommentText" rows="3" placeholder="Write a comment..."></textarea>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btnPostComment">Post Comment</button>
                            </div>
                        </div>
                        
                        <div class="comments-list">
                            <?php foreach($comments as $com): ?>
                            <div class="d-flex mb-3 border-bottom pb-3">
                                <div class="me-3">
                                    <?php if(!empty($com['avatar'])): ?>
                                        <img src="<?= BASE_URL ?>/<?= e($com['avatar']) ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                            <?= strtoupper(substr($com['user_name'],0,1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-bold"><?= e($com['user_name']) ?> <small class="text-muted ms-2 fw-normal"><?= formatDateTime($com['created_at']) ?></small></div>
                                    <div class="mt-1"><?= nl2br(e($com['comment'])) ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade crm-card p-4" id="activity">
                        <h5 class="mb-3">Activity Timeline</h5>
                        <div class="timeline">
                            <?php if(empty($logs)): ?>
                                <div class="text-muted small text-center py-3">No activity logged yet.</div>
                            <?php else: ?>
                                <?php foreach($logs as $log): ?>
                                    <div class="d-flex mb-3">
                                        <div class="me-3 text-primary"><i class="bi bi-circle-fill" style="font-size: 10px;"></i></div>
                                        <div>
                                            <div class="fw-semibold text-dark text-capitalize"><?= e($log['action']) ?></div>
                                            <div class="text-muted small"><?= e($log['description']) ?></div>
                                            <div class="text-muted small" style="font-size: 0.75rem;"><?= e($log['user_name'] ?? 'System') ?> • <?= formatDateTime($log['created_at']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Details -->
            <div class="col-lg-4">
                <!-- Dates & Info -->
                <div class="crm-card mb-4">
                    <div class="crm-card-body p-3">
                        <h6 class="border-bottom pb-2 mb-3">Task Details</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted">Start Date</td><td class="text-end fw-semibold"><?= formatDate($task['start_date']) ?></td></tr>
                            <tr><td class="text-muted">Due Date</td><td class="text-end fw-semibold text-danger"><?= $task['due_date'] ? formatDateTime($task['due_date']) : '-' ?></td></tr>
                            <tr><td class="text-muted">Completed</td><td class="text-end fw-semibold text-success"><?= $task['completion_date'] ? formatDateTime($task['completion_date']) : '-' ?></td></tr>
                            <tr><td class="text-muted">Est. Hours</td><td class="text-end fw-semibold"><?= (float)$task['estimated_hours'] ?> h</td></tr>
                            <tr><td class="text-muted">Created By</td><td class="text-end fw-semibold"><?= e($task['created_by_name']) ?></td></tr>
                        </table>
                    </div>
                </div>

                <!-- Assignees -->
                <div class="crm-card mb-4">
                    <div class="crm-card-body p-3">
                        <h6 class="border-bottom pb-2 mb-3">Assignees</h6>
                        <?php if(empty($assignees)): ?>
                            <div class="text-muted small">Unassigned</div>
                        <?php else: ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach($assignees as $a): ?>
                                    <li class="mb-2"><i class="bi bi-person-check-fill text-primary me-2"></i> <?= e($a['user_name']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Watchers -->
                <div class="crm-card mb-4">
                    <div class="crm-card-body p-3">
                        <h6 class="border-bottom pb-2 mb-3">Watchers</h6>
                        <?php if(empty($watchers)): ?>
                            <div class="text-muted small">No watchers</div>
                        <?php else: ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach($watchers as $w): ?>
                                    <li class="mb-2"><i class="bi bi-eye-fill text-secondary me-2"></i> <?= e($w['user_name']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Linked Entities -->
                <?php if(!empty($links)): ?>
                <div class="crm-card mb-4">
                    <div class="crm-card-body p-3">
                        <h6 class="border-bottom pb-2 mb-3">Linked Records</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach($links as $link): 
                                $modulePath = $link['entity_type'] . 's';
                                if ($link['entity_type'] === 'lead') $modulePath = 'prospects';
                            ?>
                                <li class="mb-2">
                                    <a href="<?= BASE_URL ?>/modules/<?= $modulePath ?>/view.php?id=<?= $link['entity_id'] ?>" class="text-decoration-none">
                                        <span class="badge bg-light text-dark border text-uppercase me-2"><?= e($link['entity_type']) ?></span>
                                        <i class="bi bi-box-arrow-up-right small me-1"></i> <span class="fw-semibold"><?= e($link['entity_name']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    const taskId = <?= $task['id'] ?>;

    // Progress Range
    $('#progressRange').on('change', function() {
        const val = $(this).val();
        $('#progressVal').text(val + '%');
        $.post('ajax_handler.php', { action: 'update_progress', task_id: taskId, progress: val }, function(res) {
            if(res.success) location.reload();
        });
    });
    $('#progressRange').on('input', function() {
        $('#progressVal').text($(this).val() + '%');
    });

    // Status Select
    $('#statusSelect').on('change', function() {
        const val = $(this).val();
        $.post('ajax_handler.php', { action: 'update_task_status', task_id: taskId, status_id: val }, function(res) {
            if(res.success) location.reload();
            else alert(res.error);
        });
    });

    // Checklist
    $('#btnAddChecklist').click(function() {
        const title = $('#newChecklistTitle').val().trim();
        if(!title) return;
        $.post('ajax_handler.php', { action: 'add_checklist_item', task_id: taskId, title: title }, function(res) {
            if(res.success) location.reload();
        });
    });
    $('.chk-toggle').change(function() {
        const itemId = $(this).data('id');
        const isCompleted = $(this).is(':checked') ? 1 : 0;
        $.post('ajax_handler.php', { action: 'toggle_checklist_item', item_id: itemId, is_completed: isCompleted }, function(res) {
            if(res.success) location.reload();
        });
    });

    // Comment
    $('#btnPostComment').click(function() {
        const comment = $('#newCommentText').val().trim();
        if(!comment) return;
        $.post('ajax_handler.php', { action: 'add_comment', task_id: taskId, comment: comment }, function(res) {
            if(res.success) location.reload();
        });
    });
});
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
