<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

$pageTitle = 'Task Analytics';
include __DIR__ . '/../../includes/header.php';

// Fetch overall task completion stats
$totalTasks = db()->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$completedTasks = db()->query("SELECT COUNT(*) FROM tasks t JOIN task_statuses s ON t.status_id = s.id WHERE s.is_terminal = 1")->fetchColumn();
$overdueTasks = db()->query("SELECT COUNT(*) FROM tasks t JOIN task_statuses s ON t.status_id = s.id WHERE s.is_terminal = 0 AND DATE(t.due_date) < CURRENT_DATE()")->fetchColumn();

// Fetch tasks by category
$catStats = db()->query("SELECT c.name, COUNT(t.id) as count FROM tasks t JOIN task_categories c ON t.category_id = c.id GROUP BY c.id")->fetchAll();
$catLabels = []; $catData = [];
foreach($catStats as $c) { $catLabels[] = $c['name']; $catData[] = $c['count']; }

// Fetch user productivity (Completed tasks per user)
$userStats = db()->query("
    SELECT u.name, COUNT(t.id) as completed_count 
    FROM task_assignments a 
    JOIN tasks t ON a.task_id = t.id 
    JOIN task_statuses s ON t.status_id = s.id 
    JOIN users u ON a.user_id = u.id 
    WHERE a.is_watcher = 0 AND s.is_terminal = 1 
    GROUP BY u.id 
    ORDER BY completed_count DESC
")->fetchAll();
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Task Analytics</div>
    </div>
    <div class="page-content">
        <div class="page-header mb-4">
            <div>
                <h1>Task Analytics</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/reports/index.php">Reports</a></li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print Report</button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="crm-card bg-primary text-white h-100 p-4">
                    <div class="text-white-50 text-uppercase small fw-bold mb-2">Total Tasks</div>
                    <h3 class="fw-bold mb-0"><?= $totalTasks ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card bg-success text-white h-100 p-4">
                    <div class="text-white-50 text-uppercase small fw-bold mb-2">Completed Tasks</div>
                    <h3 class="fw-bold mb-0"><?= $completedTasks ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card bg-danger text-white h-100 p-4">
                    <div class="text-white-50 text-uppercase small fw-bold mb-2">Overdue Tasks</div>
                    <h3 class="fw-bold mb-0"><?= $overdueTasks ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="crm-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Tasks by Category</h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="crm-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Team Productivity (Completed Tasks)</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr><th>Employee</th><th class="text-end">Completed Tasks</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($userStats as $u): ?>
                                <tr>
                                    <td><?= e($u['name']) ?></td>
                                    <td class="text-end fw-bold text-success"><?= $u['completed_count'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($userStats)): ?>
                                    <tr><td colspan="2" class="text-center text-muted">No completed tasks yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($catLabels) ?>,
            datasets: [{
                data: <?= json_encode($catData) ?>,
                backgroundColor: ['#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#20c997']
            }]
        },
        options: { responsive: true }
    });
});
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
