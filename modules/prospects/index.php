<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();
requirePermission('prospects', 'view');
$pageTitle = 'Lead Management';
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$priority = sanitize($_GET['priority'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$per = RECORDS_PER_PAGE;

$where = ['l.deleted_at IS NULL'];
$params = [];
if (!isAdmin()) {
    $where[] = '(l.assigned_to = ? OR l.created_by = ?)';
    $params[] = $_SESSION['user_id'];
    $params[] = $_SESSION['user_id'];
}
if ($search) {
    $where[] = '(l.company_name LIKE ? OR l.lead_code LIKE ? OR c.name LIKE ? OR c.mobile LIKE ?)';
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
}
if ($status) {
    $where[] = 'l.lead_status = ?';
    $params[] = $status;
}
if ($priority) {
    $where[] = 'l.lead_priority = ?';
    $params[] = $priority;
}

$whereStr = implode(' AND ', $where);

// Count Total
$totalStmt = db()->prepare("SELECT COUNT(DISTINCT l.id) FROM leads l LEFT JOIN lead_contacts c ON l.id = c.lead_id WHERE $whereStr");
$totalStmt->execute($params);
$totalCount = $totalStmt->fetchColumn();

$pag = paginate($totalCount, $per, $page, BASE_URL . '/modules/prospects/index.php?search=' . urlencode($search) . '&status=' . urlencode($status) . '&priority=' . urlencode($priority));

// Fetch Leads
$sql = "SELECT l.*, u.name AS assigned_name, 
        (SELECT name FROM lead_contacts WHERE lead_id = l.id AND is_primary = 1 LIMIT 1) as primary_contact,
        (SELECT mobile FROM lead_contacts WHERE lead_id = l.id AND is_primary = 1 LIMIT 1) as primary_mobile
        FROM leads l 
        LEFT JOIN users u ON l.assigned_to = u.id 
        WHERE $whereStr 
        ORDER BY l.created_at DESC 
        LIMIT $per OFFSET {$pag['offset']}";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$leads = $stmt->fetchAll();



// Base condition for stats (permissions only, no search filters)
$statWhere = ['deleted_at IS NULL'];
$statParams = [];
if (!isAdmin()) {
    $statWhere[] = '(assigned_to = ? OR created_by = ?)';
    $statParams[] = $_SESSION['user_id'];
    $statParams[] = $_SESSION['user_id'];
}
$statWhereStr = implode(' AND ', $statWhere);

// Dashboard Stats
$statSql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN lead_priority = 'Hot Lead' OR lead_status = 'Hot Lead' THEN 1 ELSE 0 END) as hot,
        SUM(CASE WHEN lead_status = 'Won' THEN 1 ELSE 0 END) as won,
        SUM(CASE WHEN lead_status = 'Lost' THEN 1 ELSE 0 END) as lost,
        SUM(CASE WHEN DATE(next_followup_date) = CURDATE() THEN 1 ELSE 0 END) as today_followup
    FROM leads 
    WHERE $statWhereStr
";
$statStmt = db()->prepare($statSql);
$statStmt->execute($statParams);
$stats = $statStmt->fetch();

include __DIR__ . '/../../includes/header.php'; ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="topbar">
        <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
        <div class="topbar-title">Lead Management</div>
    </div>

    <div class="page-content">
        <?= flashHtml() ?>

        <div class="page-header">
            <div class="page-header-left">
                <h1>Leads & Prospects</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
                        <li class="breadcrumb-item active">Leads</li>
                    </ol>
                </nav>
            </div>
            <div class="page-header-right d-flex gap-2">
                <div class="btn-group shadow-sm">
                    <a href="index.php" class="btn btn-outline-secondary active"><i
                            class="bi bi-list-ul me-1"></i>Table</a>
                    <a href="kanban.php" class="btn btn-outline-secondary"><i class="bi bi-kanban me-1"></i>Kanban</a>
                </div>



                <a href="create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i>Add New
                    Lead</a>
            </div>
        </div>



        <!-- Dashboard Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="stat-card primary">
                    <div class="stat-icon primary"><i class="bi bi-funnel"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float) ($stats['total'] ?? 0)) ?></div>
                        <div class="stat-label">Total Leads</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card danger">
                    <div class="stat-icon danger"><i class="bi bi-fire"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float) ($stats['hot'] ?? 0)) ?></div>
                        <div class="stat-label">Hot Leads</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card success">
                    <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float) ($stats['won'] ?? 0)) ?></div>
                        <div class="stat-label">Won / Converted</div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="stat-card warning">
                    <div class="stat-icon warning"><i class="bi bi-calendar2-check"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format((float) ($stats['today_followup'] ?? 0)) ?></div>
                        <div class="stat-label">Due Today</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-wrapper">
            <div class="table-toolbar">
                <form method="GET" class="d-flex gap-2 flex-wrap flex-grow-1">
                    <div class="table-search">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Search company, code, contact..."
                            value="<?= e($search) ?>">
                    </div>
                    <select name="status" class="form-select form-select-sm" style="width:140px">
                        <option value="">All Status</option>
                        <?php foreach ($leadStatuses as $st): ?>
                            <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= $st ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="priority" class="form-select form-select-sm" style="width:140px">
                        <option value="">All Priority</option>
                        <?php foreach ($leadPriorities as $p): ?>
                            <option value="<?= $p ?>" <?= $priority === $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                    <?php if ($search || $status || $priority): ?>
                        <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </form>
                <span class="text-muted small"><?= number_format($totalCount) ?> Leads Found</span>
            </div>
            <div class="table-responsive">
                <table class="table crm-table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Lead Info</th>
                            <th>Contact Person</th>
                            <th>Source</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Follow Up</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state"><i class="bi bi-funnel"></i>
                                        <p>No leads matched your search</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else:
                            foreach ($leads as $l): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><a href="view.php?id=<?= $l['id'] ?>"
                                                class="text-dark"><?= e($l['company_name'] ?: 'No Company') ?></a></div>
                                        <div class="text-muted x-small"><?= e($l['lead_code']) ?> • <?= e($l['project_type']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-medium"><?= e($l['primary_contact'] ?: '—') ?></div>
                                        <div class="text-muted small"><?= e($l['primary_mobile'] ?: '—') ?></div>
                                    </td>
                                    <td>
                                        <div class="small"><?= e($l['lead_source']) ?></div>
                                    </td>
                                    <td><?= statusBadge($l['lead_priority']) ?></td>
                                    <td><?= statusBadge($l['lead_status']) ?></td>
                                    <td>
                                        <div class="small"><?= e($l['assigned_name'] ?: 'Unassigned') ?></div>
                                    </td>
                                    <td>
                                        <div class="small text-nowrap">
                                            <?= $l['next_followup_date'] ? formatDateTime($l['next_followup_date']) : '<span class="text-muted">—</span>' ?>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="view.php?id=<?= $l['id'] ?>"
                                                class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                            <a href="edit.php?id=<?= $l['id'] ?>"
                                                class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                            <a href="delete.php?id=<?= $l['id'] ?>"
                                                class="btn btn-icon btn-sm btn-outline-danger"
                                                onclick="return confirm('Archive this lead?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($pag['total_pages'] > 1): ?>
                <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">Page <?= $pag['current'] ?> of <?= $pag['total_pages'] ?></small>
                    <?= paginationHtml($pag) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<style>
    .x-small {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
</style>