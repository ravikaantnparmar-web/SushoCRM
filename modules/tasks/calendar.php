<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

// AJAX endpoint for calendar events
if (isset($_GET['action']) && $_GET['action'] == 'get_events') {
    header('Content-Type: application/json');
    $start = $_GET['start'] ?? date('Y-m-d', strtotime('-1 month'));
    $end = $_GET['end'] ?? date('Y-m-d', strtotime('+1 month'));
    
    $stmt = db()->prepare("SELECT t.*, s.color as status_color FROM tasks t LEFT JOIN task_statuses s ON t.status_id = s.id WHERE (t.start_date BETWEEN ? AND ?) OR (t.due_date BETWEEN ? AND ?) OR (t.start_date IS NULL AND t.due_date BETWEEN ? AND ?)");
    $stmt->execute([$start, $end, $start, $end, $start, $end]);
    $tasks = $stmt->fetchAll();
    
    $events = [];
    foreach ($tasks as $t) {
        $events[] = [
            'id' => $t['id'],
            'title' => $t['task_number'] . ' - ' . $t['title'],
            'start' => $t['start_date'] ? $t['start_date'] : date('Y-m-d', strtotime($t['due_date'])),
            'end' => $t['due_date'] ? $t['due_date'] : null,
            'url' => 'view.php?id=' . $t['id'],
            'color' => $t['status_color'] ?? '#3788d8'
        ];
    }
    echo json_encode($events);
    exit;
}

$pageTitle = "Task Calendar";
include __DIR__ . '/../../includes/header.php';
?>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Calendar View</div>
    </div>
    <div class="page-content">
        
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Calendar</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Tasks</a></li>
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="bi bi-list-ul me-1"></i> List View</a>
                <a href="kanban.php" class="btn btn-outline-primary shadow-sm"><i class="bi bi-kanban me-1"></i> Kanban</a>
                <a href="create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i> New Task</a>
            </div>
        </div>

        <div class="crm-card">
            <div class="crm-card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: 'calendar.php?action=get_events',
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            if (info.event.url) {
                window.open(info.event.url, '_self');
            }
        },
        height: 'auto'
    });
    calendar.render();
});
</script>
<style>
.fc-event { cursor: pointer; border: none; padding: 2px 4px; font-size: 0.85rem; }
.fc-daygrid-event-dot { display: none; }
</style>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
