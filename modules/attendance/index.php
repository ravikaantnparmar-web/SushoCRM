<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Attendance';
$date = sanitize($_GET['date'] ?? date('Y-m-d'));
$search = sanitize($_GET['search'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $att_date = sanitize($_POST['attendance_date']);
    $attendance = $_POST['attendance'] ?? [];
    
    db()->beginTransaction();
    try {
        $stmtCheck = db()->prepare("SELECT id FROM attendance WHERE employee_id=? AND date=?");
        $stmtUpdate = db()->prepare("UPDATE attendance SET status=?, check_in=?, check_out=?, notes=? WHERE id=?");
        $stmtInsert = db()->prepare("INSERT INTO attendance (employee_id, date, status, check_in, check_out, notes) VALUES (?,?,?,?,?,?)");
        
        foreach ($attendance as $emp_id => $data) {
            $status = $data['status'] ?? 'present';
            $in = !empty($data['check_in']) ? $data['check_in'] : null;
            $out = !empty($data['check_out']) ? $data['check_out'] : null;
            $notes = sanitize($data['notes'] ?? '');
            
            $stmtCheck->execute([$emp_id, $att_date]);
            $existing = $stmtCheck->fetchColumn();
            
            if ($existing) {
                $stmtUpdate->execute([$status, $in, $out, $notes, $existing]);
            } else {
                $stmtInsert->execute([$emp_id, $att_date, $status, $in, $out, $notes]);
            }
        }
        db()->commit();
        setFlash('success', "Attendance saved for $att_date");
        header('Location: '.BASE_URL.'/modules/attendance/index.php?date='.$att_date);
        exit;
    } catch(Exception $e) {
        db()->rollBack();
        setFlash('danger', 'Error saving attendance: '.$e->getMessage());
    }
}

$where = ['e.status="active"']; $params = [];
if ($search) { $where[] = '(e.name LIKE ? OR e.emp_code LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%"]); }
$whereStr = implode(' AND ', $where);

// Get all active employees and their attendance for the selected date
$sql = "SELECT e.id as emp_id, e.emp_code, e.name, e.department,
        a.id as att_id, a.status, a.check_in, a.check_out, a.notes 
        FROM employees e 
        LEFT JOIN attendance a ON e.id = a.employee_id AND a.date = ? 
        WHERE $whereStr 
        ORDER BY e.name ASC";
$stmt = db()->prepare($sql);
$stmt->execute(array_merge([$date], $params));
$employees = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Attendance</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Mark Attendance</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Attendance</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/attendance/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Attendance</a>
</div>

<div class="crm-card mb-4">
    <div class="crm-card-body p-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Select Date</label>
                <input type="date" name="date" class="form-control" value="<?= e($date) ?>" onchange="this.form.submit()">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small mb-1">Search Employee</label>
                <input type="text" name="search" class="form-control" placeholder="Name or code..." value="<?= e($search) ?>">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                <?php if($search): ?><a href="?date=<?= e($date) ?>" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
            </div>
        </form>
    </div>
</div>

<form method="POST">
    <input type="hidden" name="attendance_date" value="<?= e($date) ?>">
    <div class="table-wrapper mb-4">
        <div class="table-responsive">
            <table class="table crm-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:25%">Employee</th>
                        <th style="width:20%">Status</th>
                        <th style="width:15%">Check In</th>
                        <th style="width:15%">Check Out</th>
                        <th style="width:25%">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($employees)): ?>
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-calendar-check"></i><p>No active employees found.</p></div></td></tr>
                    <?php else: foreach($employees as $e): 
                        $status = $e['status'] ?? 'present';
                    ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark"><?= e($e['name']) ?></div>
                            <div class="small text-muted"><?= e($e['emp_code']) ?> <?= $e['department'] ? ' • '.e($e['department']) : '' ?></div>
                        </td>
                        <td>
                            <select name="attendance[<?= $e['emp_id'] ?>][status]" class="form-select form-select-sm">
                                <option value="present" <?= $status==='present'?'selected':'' ?>>Present</option>
                                <option value="absent" <?= $status==='absent'?'selected':'' ?>>Absent</option>
                                <option value="half_day" <?= $status==='half_day'?'selected':'' ?>>Half Day</option>
                                <option value="leave" <?= $status==='leave'?'selected':'' ?>>On Leave</option>
                            </select>
                        </td>
                        <td><input type="time" name="attendance[<?= $e['emp_id'] ?>][check_in]" class="form-control form-control-sm" value="<?= e($e['check_in'] ? date('H:i', strtotime($e['check_in'])) : '') ?>"></td>
                        <td><input type="time" name="attendance[<?= $e['emp_id'] ?>][check_out]" class="form-control form-control-sm" value="<?= e($e['check_out'] ? date('H:i', strtotime($e['check_out'])) : '') ?>"></td>
                        <td><input type="text" name="attendance[<?= $e['emp_id'] ?>][notes]" class="form-control form-control-sm" placeholder="Notes..." value="<?= e($e['notes']) ?>"></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if(!empty($employees)): ?>
    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Save Attendance for <?= date('d M Y', strtotime($date)) ?></button>
    </div>
    <?php endif; ?>
</form>

</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
