<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM employees WHERE id=?");
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) { setFlash('danger','Employee not found.'); header('Location: '.BASE_URL.'/modules/employees/index.php'); exit; }

$pageTitle = 'Edit Employee';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_code = sanitize($_POST['emp_code'] ?? '');
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $designation = sanitize($_POST['designation'] ?? '');
    $department = sanitize($_POST['department'] ?? '');
    $join_date = sanitize($_POST['join_date'] ?? '');
    $salary = (float)($_POST['salary'] ?? 0);
    $status = sanitize($_POST['status'] ?? 'active');
    $address = sanitize($_POST['address'] ?? '');
    $role_id = (int)($_POST['role_id'] ?? 5);
    
    // Set default rights based on role if not provided
    if ($role_id == 1) $rights = json_encode(['Read','Write','Modify','Delete','View','Approve']);
    elseif ($role_id == 2) $rights = json_encode(['Read','Write','Modify','View','Approve']);
    elseif ($role_id == 3) $rights = json_encode(['Read','Write','Modify','View']);
    else $rights = json_encode(['Read','View']);

    if (!$name) $errors['name'] = 'Name is required.';
    if (!$emp_code) $errors['emp_code'] = 'Employee Code is required.';

    $stmt = db()->prepare("SELECT id FROM employees WHERE emp_code = ? AND id != ?");
    $stmt->execute([$emp_code, $id]);
    if ($stmt->fetch()) $errors['emp_code'] = 'Employee Code already exists.';

    if (!$errors) {
        $stmt = db()->prepare("UPDATE employees SET emp_code=?, name=?, email=?, phone=?, designation=?, department=?, join_date=?, salary=?, status=?, address=?, access_rights=?, role_id=? WHERE id=?");
        if ($stmt->execute([$emp_code, $name, $email, $phone, $designation, $department, $join_date?:null, $salary, $status, $address, $rights, $role_id, $id])) {
            logActivity('employees','update',"Updated employee: $name",$id);
            setFlash('success',"Employee '$name' updated successfully.");
            header('Location: '.BASE_URL.'/modules/employees/view.php?id='.$id);
            exit;
        } else {
            $errors['general'] = 'Failed to update employee.';
        }
    }
}
$roles = db()->query("SELECT id, name FROM roles ORDER BY id ASC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Employee</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Edit Employee</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/employees/index.php">Employees</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/employees/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form method="POST">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="crm-card"><div class="crm-card-body p-4">
        <h5 class="fw-bold mb-4">Personal & Employment Details</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Employee Code</label>
            <input type="text" name="emp_code" class="form-control <?= isset($errors['emp_code'])?'is-invalid':'' ?>" value="<?= e($_POST['emp_code']??$emp['emp_code']) ?>">
            <?php if(isset($errors['emp_code'])): ?><div class="invalid-feedback"><?= $errors['emp_code'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">User Type (Role)</label>
            <select name="role_id" id="role_id" class="form-select">
              <?php foreach($roles as $r): ?>
                <option value="<?= $r['id'] ?>" <?= ($emp['role_id']??5)==$r['id']?'selected':'' ?>><?= e($r['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <?php foreach(['active','on_leave','terminated'] as $s): ?>
                <option value="<?= $s ?>" <?= ($_POST['status']??$emp['status'])===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-12">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= e($_POST['name']??$emp['name']) ?>" required>
            <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?= $errors['name'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($_POST['email']??$emp['email']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= e($_POST['phone']??$emp['phone']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" value="<?= e($_POST['designation']??$emp['designation']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="<?= e($_POST['department']??$emp['department']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Joining Date</label>
            <input type="date" name="join_date" class="form-control" value="<?= e($_POST['join_date']??$emp['join_date']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Basic Salary</label>
            <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="salary" class="form-control" value="<?= e($_POST['salary']??$emp['salary']) ?>" step="0.01" min="0">
            </div>
          </div>
        </div>
      </div></div>
    </div>
    <div class="col-lg-4">
      <div class="crm-card">
        <div class="crm-card-body p-4">
            <h5 class="fw-bold mb-4">Address Details</h5>
            <div class="col-12">
                <label class="form-label">Full Address</label>
                <textarea name="address" class="form-control" rows="5"><?= e($_POST['address']??$emp['address']) ?></textarea>
            </div>
        </div>
      </div>

      <!-- Access rights removed from UI as requested -->

      <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Employee</button>
        <a href="<?= BASE_URL ?>/modules/employees/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
