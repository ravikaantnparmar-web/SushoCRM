<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();

$pageTitle = 'Leads Kanban';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$priority = sanitize($_GET['priority'] ?? '');

$leadsByStatus = [];
foreach($leadStatuses as $st) $leadsByStatus[$st] = [];

$where = ['l.deleted_at IS NULL']; $params = [];
if (!isAdmin()) {
    $where[] = '(l.assigned_to = ? OR l.created_by = ?)';
    $params[] = $_SESSION['user_id'];
    $params[] = $_SESSION['user_id'];
}
if ($search) { 
    $where[] = '(l.company_name LIKE ? OR l.lead_code LIKE ?)'; 
    $params = array_merge($params, ["%$search%","%$search%"]); 
}
if ($status) { $where[] = 'l.lead_status = ?'; $params[] = $status; }
if ($priority) { $where[] = 'l.lead_priority = ?'; $params[] = $priority; }

$whereStr = implode(' AND ', $where);

$sql = "SELECT l.*, 
        (SELECT name FROM lead_contacts WHERE lead_id = l.id AND is_primary = 1 LIMIT 1) as contact_name,
        (SELECT name FROM users WHERE id = l.assigned_to) as assigned_name
        FROM leads l 
        WHERE $whereStr 
        ORDER BY l.created_at DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$allLeads = $stmt->fetchAll();

foreach($allLeads as $l) {
    if (isset($leadsByStatus[$l['lead_status']])) {
        $leadsByStatus[$l['lead_status']][] = $l;
    } else {
        $leadsByStatus['Open'][] = $l; // Fallback
    }
}

include __DIR__ . '/../../includes/header.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Leads Kanban</div>
</div>

<div class="page-content">
    <div class="page-header">
        <div class="page-header-left">
            <h1>Leads Kanban</h1>
        </div>
        <div class="page-header-right">
            <div class="btn-group shadow-sm">
                <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-list-ul me-1"></i>Table</a>
                <button class="btn btn-outline-secondary active"><i class="bi bi-kanban me-1"></i>Kanban</button>
            </div>
            <a href="create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i>Add Lead</a>
        </div>
    </div>

    <!-- Kanban Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <div class="table-search" style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 10px; top: 8px; color: #6c757d;"></i>
                    <input type="text" name="search" class="form-control form-control-sm" style="padding-left: 30px; width: 250px;" placeholder="Search company, code..." value="<?= e($search) ?>">
                </div>
                <select name="status" class="form-select form-select-sm" style="width:140px">
                    <option value="">All Status</option>
                    <?php foreach($leadStatuses as $st): ?>
                        <option value="<?= $st ?>" <?= $status===$st?'selected':'' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="priority" class="form-select form-select-sm" style="width:140px">
                    <option value="">All Priority</option>
                    <?php foreach($leadPriorities as $p): ?>
                        <option value="<?= $p ?>" <?= $priority===$p?'selected':'' ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                <?php if ($search||$status||$priority): ?>
                    <a href="kanban.php" class="btn btn-sm btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="kanban-board d-flex gap-3 overflow-auto pb-4" style="min-height: calc(100vh - 250px);">
        <?php foreach($leadStatuses as $st): ?>
        <div class="kanban-column" style="min-width: 300px; max-width: 300px;">
            <div class="kanban-column-header d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold"><?= $st ?> <span class="badge bg-light text-dark ms-2"><?= count($leadsByStatus[$st]) ?></span></h6>
                <button class="btn btn-icon btn-sm"><i class="bi bi-three-dots-vertical"></i></button>
            </div>
            <div class="kanban-cards d-flex flex-column gap-2">
                <?php foreach($leadsByStatus[$st] as $l): ?>
                <div class="kanban-card crm-card p-3 shadow-sm border-left-<?= $l['lead_priority']=='Hot Lead'?'danger':'primary' ?>" style="cursor: grab;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="x-small text-muted"><?= e($l['lead_code']) ?></span>
                        <?= statusBadge($l['lead_priority']) ?>
                    </div>
                    <div class="fw-bold mb-1"><a href="view.php?id=<?= $l['id'] ?>" class="text-dark"><?= e($l['company_name'] ?: 'No Company') ?></a></div>
                    <div class="small text-muted mb-3"><i class="bi bi-person me-1"></i><?= e($l['contact_name'] ?: '—') ?></div>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="small text-muted"><i class="bi bi-calendar-event me-1"></i><?= formatDate($l['next_followup_date']) ?></div>
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; font-size:10px;">
                            <?= strtoupper(substr($l['assigned_name'] ?? 'U', 0, 1)) ?>
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

<style>
.kanban-board::-webkit-scrollbar { height: 8px; }
.kanban-board::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.kanban-card:hover { border-color: var(--primary); transform: translateY(-2px); transition: all 0.2s; }
.x-small { font-size: 10px; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const board = document.querySelector('.kanban-board');
    // Find the first column that has at least one kanban card
    const cols = document.querySelectorAll('.kanban-column');
    for (let i = 0; i < cols.length; i++) {
        if (cols[i].querySelector('.kanban-card')) {
            if (board) {
                // Scroll smoothly to this column
                board.scrollTo({
                    left: cols[i].offsetLeft - 20,
                    behavior: 'smooth'
                });
            }
            break;
        }
    }
});
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
