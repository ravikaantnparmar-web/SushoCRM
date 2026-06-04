<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Salary Slips';
$month = sanitize($_GET['month'] ?? date('Y-m'));

// Handle generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $emp_id = (int)$_POST['employee_id'];
    $sl_month = sanitize($_POST['month']);
    $basic = (float)$_POST['basic_salary'];
    $allowances = (float)$_POST['allowances'];
    $deductions = (float)$_POST['deductions'];
    $net = ($basic + $allowances) - $deductions;
    
    // Check if exists
    $stmt = db()->prepare("SELECT id FROM salary_slips WHERE employee_id=? AND salary_month=?");
    $stmt->execute([$emp_id, $sl_month]);
    if ($stmt->fetch()) {
        setFlash('danger','Salary slip already exists for this month.');
    } else {
        $stmt = db()->prepare("INSERT INTO salary_slips (employee_id, salary_month, basic_salary, allowances, deductions, net_salary) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$emp_id, $sl_month, $basic, $allowances, $deductions, $net]);
        setFlash('success','Salary slip generated successfully.');
    }
    header("Location: ".BASE_URL."/modules/salary/index.php?month=$sl_month");
    exit;
}

// Mark as paid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_paid') {
    $id = (int)$_POST['slip_id'];
    db()->prepare("UPDATE salary_slips SET status='paid', payment_date=CURRENT_DATE WHERE id=?")->execute([$id]);
    setFlash('success','Salary marked as paid.');
    header("Location: ".BASE_URL."/modules/salary/index.php?month=$month");
    exit;
}

$stmt = db()->prepare("SELECT s.*, e.name, e.emp_code 
                       FROM salary_slips s JOIN employees e ON s.employee_id=e.id 
                       WHERE s.salary_month = ? ORDER BY e.name ASC");
$stmt->execute([$month]);
$slips = $stmt->fetchAll();

$employees = db()->query("SELECT id, name, emp_code, salary FROM employees WHERE status='active' ORDER BY name")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Salary Slips</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Salary Slips</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Salary</li></ol></nav>
  </div>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal"><i class="bi bi-plus-lg me-1"></i>Generate Slip</button>
</div>

<div class="crm-card mb-4">
    <div class="crm-card-body p-3">
        <form method="GET" class="d-flex gap-3 align-items-center">
            <label class="fw-semibold">Filter by Month:</label>
            <input type="month" name="month" class="form-control form-control-sm" style="width:200px" value="<?= e($month) ?>" onchange="this.form.submit()">
        </form>
    </div>
</div>

<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table crm-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Month</th>
                    <th>Basic</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($slips)): ?>
                    <tr><td colspan="8"><div class="empty-state"><i class="bi bi-cash-coin"></i><p>No salary slips found for <?= date('F Y', strtotime($month.'-01')) ?></p></div></td></tr>
                <?php else: foreach($slips as $s): ?>
                <tr>
                    <td>
                        <div class="fw-semibold text-dark"><?= e($s['name']) ?></div>
                        <div class="small text-muted"><?= e($s['emp_code']) ?></div>
                    </td>
                    <td><?= date('M Y', strtotime($s['salary_month'].'-01')) ?></td>
                    <td><?= formatCurrency($s['basic_salary']) ?></td>
                    <td class="text-success">+ <?= formatCurrency($s['allowances']) ?></td>
                    <td class="text-danger">- <?= formatCurrency($s['deductions']) ?></td>
                    <td class="fw-bold text-primary"><?= formatCurrency($s['net_salary']) ?></td>
                    <td><?= statusBadge($s['status']) ?></td>
                    <td>
                        <?php if($s['status']==='generated'): ?>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Mark this salary as paid?');">
                            <input type="hidden" name="action" value="mark_paid">
                            <input type="hidden" name="slip_id" value="<?= $s['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check-circle me-1"></i>Mark Paid</button>
                        </form>
                        <?php else: ?>
                        <span class="text-muted small"><i class="bi bi-check-all"></i> Paid on <?= formatDate($s['payment_date']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div></div>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold">Generate Salary Slip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST">
            <input type="hidden" name="action" value="generate">
            <div class="mb-3">
                <label class="form-label">Month</label>
                <input type="month" name="month" class="form-control" value="<?= date('Y-m') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-select" id="empSelect" required onchange="updateBasic()">
                    <option value="">— Select Employee —</option>
                    <?php foreach($employees as $e): ?>
                        <option value="<?= $e['id'] ?>" data-salary="<?= $e['salary'] ?>"><?= e($e['name'].' ('.$e['emp_code'].')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Basic Salary</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="basic_salary" id="basicSalary" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Allowances</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="allowances" class="form-control" value="0" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Deductions</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="deductions" class="form-control" value="0" step="0.01" min="0" required>
                    </div>
                </div>
            </div>
            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-primary">Generate Slip</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function updateBasic() {
    const sel = document.getElementById('empSelect');
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('basicSalary').value = opt.dataset.salary || 0;
}
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
