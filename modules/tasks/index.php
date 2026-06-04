<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$isAdmin = isAdmin();
$isManager = currentRole() === 'manager' || $isAdmin;

// Filtering
$statusFilter = $_GET['status'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';
$assigneeFilter = $_GET['assignee'] ?? ($isManager ? '' : $userId);
$page = (int)($_GET['page'] ?? 1);
$perPage = 20;

// Base query for tasks
$query = "SELECT t.*, c.name as category_name, c.color as category_color, 
                 p.name as priority_name, p.color as priority_color, 
                 s.name as status_name, s.color as status_color 
          FROM tasks t 
          LEFT JOIN task_categories c ON t.category_id = c.id
          LEFT JOIN task_priorities p ON t.priority_id = p.id
          LEFT JOIN task_statuses s ON t.status_id = s.id
          WHERE 1=1";
$params = [];

// Apply filters
if ($statusFilter !== '') {
    $query .= " AND t.status_id = ?";
    $params[] = $statusFilter;
}
if ($priorityFilter !== '') {
    $query .= " AND t.priority_id = ?";
    $params[] = $priorityFilter;
}
if ($assigneeFilter !== '') {
    $query .= " AND t.id IN (SELECT task_id FROM task_assignments WHERE user_id = ?)";
    $params[] = $assigneeFilter;
}

// Order & Pagination
$query .= " ORDER BY t.due_date ASC, t.priority_id DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = ($page - 1) * $perPage;

$stmt = db()->prepare($query);
foreach ($params as $i => $p) {
    $stmt->bindValue($i + 1, $p, is_int($p) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$tasks = $stmt->fetchAll();

// Get total count for pagination
$countQuery = "SELECT COUNT(DISTINCT t.id) FROM tasks t WHERE 1=1";
$cParams = [];
if ($statusFilter !== '') { $countQuery .= " AND t.status_id = ?"; $cParams[] = $statusFilter; }
if ($priorityFilter !== '') { $countQuery .= " AND t.priority_id = ?"; $cParams[] = $priorityFilter; }
if ($assigneeFilter !== '') { $countQuery .= " AND t.id IN (SELECT task_id FROM task_assignments WHERE user_id = ?)"; $cParams[] = $assigneeFilter; }
$countStmt = db()->prepare($countQuery);
$countStmt->execute($cParams);
$totalTasks = $countStmt->fetchColumn();

// Fetch auxiliary data for filters
$statuses = db()->query("SELECT * FROM task_statuses ORDER BY sort_order")->fetchAll();
$priorities = db()->query("SELECT * FROM task_priorities ORDER BY level DESC")->fetchAll();
$users = db()->query("SELECT id, name FROM users WHERE is_active = 1 ORDER BY name")->fetchAll();

// Widgets Data
$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime('+7 days'));
$myTasksCount = db()->prepare("SELECT COUNT(*) FROM task_assignments ta JOIN tasks t ON ta.task_id = t.id JOIN task_statuses s ON t.status_id = s.id WHERE ta.user_id = ? AND s.is_terminal = 0")->execute([$userId]) ? db()->query("SELECT FOUND_ROWS()")->fetchColumn() : 0; // Using simpler approach
$myTasksStmt = db()->prepare("SELECT COUNT(*) FROM task_assignments ta JOIN tasks t ON ta.task_id = t.id JOIN task_statuses s ON t.status_id = s.id WHERE ta.user_id = ? AND s.is_terminal = 0");
$myTasksStmt->execute([$userId]);
$myTasksCount = $myTasksStmt->fetchColumn();

$overdueStmt = db()->prepare("SELECT COUNT(*) FROM task_assignments ta JOIN tasks t ON ta.task_id = t.id JOIN task_statuses s ON t.status_id = s.id WHERE ta.user_id = ? AND s.is_terminal = 0 AND DATE(t.due_date) < ?");
$overdueStmt->execute([$userId, $today]);
$overdueCount = $overdueStmt->fetchColumn();

$upcomingStmt = db()->prepare("SELECT COUNT(*) FROM task_assignments ta JOIN tasks t ON ta.task_id = t.id JOIN task_statuses s ON t.status_id = s.id WHERE ta.user_id = ? AND s.is_terminal = 0 AND DATE(t.due_date) BETWEEN ? AND ?");
$upcomingStmt->execute([$userId, $today, $nextWeek]);
$upcomingCount = $upcomingStmt->fetchColumn();

$completedStmt = db()->prepare("SELECT COUNT(*) FROM task_assignments ta JOIN tasks t ON ta.task_id = t.id JOIN task_statuses s ON t.status_id = s.id WHERE ta.user_id = ? AND s.is_terminal = 1 AND MONTH(t.completion_date) = MONTH(CURRENT_DATE())");
$completedStmt->execute([$userId]);
$completedCount = $completedStmt->fetchColumn();

$pageTitle = "Task Management";
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Tasks</div>
    </div>
    <div class="page-content">
        <?= flashHtml() ?>

        <div class="page-header">
            <div class="page-header-left">
                <h1>Task Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ol>
                </nav>
            </div>
            <div class="page-header-right d-flex gap-2">
                <a href="kanban.php" class="btn btn-outline-secondary"><i class="bi bi-kanban me-1"></i> Kanban</a>
                <a href="calendar.php" class="btn btn-outline-secondary"><i class="bi bi-calendar-event me-1"></i> Calendar</a>
                <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Task</a>
            </div>
        </div>

        <!-- Dashboard Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="stat-card primary">
                    <div class="stat-icon primary"><i class="bi bi-list-task"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float)$myTasksCount) ?></div>
                        <div class="stat-label">My Active Tasks</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card danger">
                    <div class="stat-icon danger"><i class="bi bi-exclamation-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float)$overdueCount) ?></div>
                        <div class="stat-label">Overdue</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card warning">
                    <div class="stat-icon warning"><i class="bi bi-calendar-week"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float)$upcomingCount) ?></div>
                        <div class="stat-label">Upcoming (7 Days)</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card success">
                    <div class="stat-icon success"><i class="bi bi-check2-all"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float)$completedCount) ?></div>
                        <div class="stat-label">Completed This Month</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-toolbar">
                <form method="GET" class="d-flex gap-2 flex-wrap flex-grow-1">
                    <div class="table-search">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Search tasks..." value="">
                    </div>
                    <select name="status" class="form-select form-select-sm" style="width:140px">
                        <option value="">All Statuses</option>
                        <?php foreach($statuses as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $statusFilter == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="priority" class="form-select form-select-sm" style="width:140px">
                        <option value="">All Priorities</option>
                        <?php foreach($priorities as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $priorityFilter == $p['id'] ? 'selected' : '' ?>><?= e($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if($isManager): ?>
                    <select name="assignee" class="form-select form-select-sm" style="width:150px">
                        <option value="">All Assignees</option>
                        <?php foreach($users as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= $assigneeFilter == $u['id'] ? 'selected' : '' ?>><?= e($u['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    <?php if($statusFilter || $priorityFilter || ($isManager && $assigneeFilter)): ?>
                        <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </form>
                <div class="d-flex gap-2">
                    <span class="text-muted small align-self-center"><?= number_format($totalTasks) ?> records</span>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="table-responsive">
                <table class="table crm-table mb-0" id="tasksTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Progress</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($tasks)): ?>
                        <tr><td colspan="8"><div class="empty-state"><i class="bi bi-list-task"></i><p>No tasks found</p><a href="create.php" class="btn btn-primary btn-sm">Create Task</a></div></td></tr>
                        <?php else: ?>
                            <?php foreach($tasks as $i => $t): ?>
                            <tr>
                                <td class="text-muted small"><?= (($page-1)*$perPage)+$i+1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-icon primary" style="width:32px;height:32px;border-radius:10px;font-size:13px;flex-shrink:0"><?= strtoupper(substr($t['title'],0,1)) ?></div>
                                        <div>
                                            <a href="view.php?id=<?= $t['id'] ?>" class="fw-semibold text-dark"><?= e($t['title']) ?></a>
                                            <div class="text-muted" style="font-size:11px"><?= e($t['task_number']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if($t['category_name']): ?>
                                        <span class="badge bg-light text-dark border"><?= e($t['category_name']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge rounded-pill" style="background-color: <?= $t['priority_color'] ?>"><?= e($t['priority_name']) ?></span></td>
                                <td><span class="badge" style="background-color: <?= $t['status_color'] ?>"><?= e($t['status_name']) ?></span></td>
                                <td>
                                    <?php 
                                    $due = $t['due_date'] ? strtotime($t['due_date']) : null;
                                    if ($due) {
                                        $class = ($due < time() && $t['progress_percentage'] < 100) ? 'text-danger fw-bold' : 'text-muted';
                                        echo "<span class='$class'>" . formatDateTime($t['due_date']) . "</span>";
                                    } else {
                                        echo '<span class="text-muted">—</span>';
                                    }
                                    ?>
                                </td>
                                <td style="width: 150px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar <?= $t['progress_percentage'] == 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= $t['progress_percentage'] ?>%;"></div>
                                        </div>
                                        <small class="text-muted" style="min-width: 35px;"><?= $t['progress_percentage'] ?>%</small>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="view.php?id=<?= $t['id'] ?>" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="edit.php?id=<?= $t['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <?php if(isAdmin()): ?>
                                            <a href="delete.php?id=<?= $t['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this task?');"><i class="bi bi-trash"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (ceil($totalTasks / $perPage) > 1): ?>
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <small class="text-muted">Showing <?= (($page-1)*$perPage)+1 ?>–<?= min($page*$perPage, $totalTasks) ?> of <?= $totalTasks ?></small>
                <?= paginationHtml(paginate($totalTasks, $perPage, $page, 'index.php?status='.$statusFilter.'&priority='.$priorityFilter.'&assignee='.$assigneeFilter)) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
