<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

$statuses = db()->query("SELECT * FROM task_statuses ORDER BY sort_order")->fetchAll();

$query = "SELECT t.*, p.color as priority_color, u.name as assignee_name 
          FROM tasks t 
          LEFT JOIN task_priorities p ON t.priority_id = p.id
          LEFT JOIN task_assignments a ON t.id = a.task_id AND a.is_watcher = 0
          LEFT JOIN users u ON a.user_id = u.id
          ORDER BY t.priority_id DESC, t.due_date ASC";
$stmt = db()->prepare($query);
$stmt->execute();
$allTasks = $stmt->fetchAll();

// Group tasks by status
$kanban = [];
foreach ($statuses as $s) {
    $kanban[$s['id']] = [
        'status' => $s,
        'tasks' => []
    ];
}
foreach ($allTasks as $t) {
    if (isset($kanban[$t['status_id']])) {
        // Simple deduplication since LEFT JOIN might return multiple rows per task if multiple assignees
        // For Kanban, we can just show the first assignee for simplicity or group them.
        $found = false;
        foreach ($kanban[$t['status_id']]['tasks'] as &$ext) {
            if ($ext['id'] == $t['id']) {
                if ($t['assignee_name']) $ext['assignees'][] = $t['assignee_name'];
                $found = true; break;
            }
        }
        if (!$found) {
            $t['assignees'] = $t['assignee_name'] ? [$t['assignee_name']] : [];
            $kanban[$t['status_id']]['tasks'][] = $t;
        }
    }
}

$pageTitle = "Task Kanban Board";
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Kanban Board</div>
    </div>
    <div class="page-content" style="height: calc(100vh - 60px); display: flex; flex-direction: column;">
        
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Kanban Board</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Tasks</a></li>
                        <li class="breadcrumb-item active">Kanban</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="bi bi-list-ul me-1"></i> List View</a>
                <a href="calendar.php" class="btn btn-outline-primary shadow-sm"><i class="bi bi-calendar-event me-1"></i> Calendar</a>
                <a href="create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i> New Task</a>
            </div>
        </div>

        <div class="kanban-board flex-grow-1" style="overflow-x: auto; display: flex; gap: 1rem; padding-bottom: 1rem;">
            <?php foreach($kanban as $statusId => $col): ?>
            <div class="kanban-col bg-light rounded" style="min-width: 320px; max-width: 320px; display: flex; flex-direction: column; max-height: 100%;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-top: 4px solid <?= $col['status']['color'] ?>; background: #fff; border-radius: 4px 4px 0 0;">
                    <h6 class="mb-0 fw-bold"><?= e($col['status']['name']) ?></h6>
                    <span class="badge bg-secondary rounded-pill"><?= count($col['tasks']) ?></span>
                </div>
                <div class="p-2 flex-grow-1 kanban-tasks-container" data-status-id="<?= $statusId ?>" style="overflow-y: auto;">
                    <?php foreach($col['tasks'] as $t): ?>
                    <div class="card shadow-sm mb-2 border-0 task-card" data-task-id="<?= $t['id'] ?>" style="cursor: grab;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <a href="view.php?id=<?= $t['id'] ?>" class="text-decoration-none fw-bold text-dark fs-6"><?= e($t['title']) ?></a>
                                <?php if($t['priority_color']): ?>
                                    <i class="bi bi-flag-fill" style="color: <?= $t['priority_color'] ?>" title="Priority"></i>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted small mb-3">
                                <?= e($t['task_number']) ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="small">
                                    <?php if($t['due_date']): ?>
                                        <?php $due = strtotime($t['due_date']); ?>
                                        <span class="<?= ($due < time() && $t['progress_percentage'] < 100) ? 'text-danger' : 'text-muted' ?>">
                                            <i class="bi bi-calendar-event"></i> <?= date('M d', $due) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-1">
                                    <?php foreach($t['assignees'] as $idx => $aname): ?>
                                        <?php if($idx < 3): ?>
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:10px;" title="<?= e($aname) ?>">
                                                <?= strtoupper(substr($aname,0,1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if(count($t['assignees']) > 3): ?>
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:10px;">
                                            +<?= count($t['assignees']) - 3 ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    const containers = document.querySelectorAll('.kanban-tasks-container');
    containers.forEach(container => {
        new Sortable(container, {
            group: 'shared',
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function(evt) {
                const itemEl = evt.item;
                const toContainer = evt.to;
                const taskId = itemEl.dataset.taskId;
                const newStatusId = toContainer.dataset.statusId;
                
                if (evt.from !== evt.to) {
                    $.post('ajax_handler.php', { action: 'update_task_status', task_id: taskId, status_id: newStatusId }, function(res) {
                        if(!res.success) {
                            alert('Failed to update status: ' + res.error);
                            location.reload();
                        } else {
                            // Update counts
                            const fromCount = evt.from.querySelectorAll('.task-card').length;
                            const toCount = evt.to.querySelectorAll('.task-card').length;
                            evt.from.previousElementSibling.querySelector('.badge').textContent = fromCount;
                            evt.to.previousElementSibling.querySelector('.badge').textContent = toCount;
                        }
                    }, 'json');
                }
            }
        });
    });
});
</script>
<style>
.kanban-tasks-container { min-height: 100px; }
</style>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
