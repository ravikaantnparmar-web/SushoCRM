<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('vendors', 'edit');
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM vendors WHERE id=?");
$stmt->execute([$id]);
$v = $stmt->fetch();
if (!$v) { setFlash('danger','Vendor not found.'); header('Location: '.BASE_URL.'/modules/vendors/index.php'); exit; }

$pageTitle = 'Edit Vendor';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_code = sanitize($_POST['vendor_code'] ?? '');
    $name = sanitize($_POST['name'] ?? '');
    $company = sanitize($_POST['company'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $gst_number = sanitize($_POST['gst_number'] ?? '');
    $pan_number = sanitize($_POST['pan_number'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $pincode = sanitize($_POST['pincode'] ?? '');
    $bank_name = sanitize($_POST['bank_name'] ?? '');
    $bank_account = sanitize($_POST['bank_account'] ?? '');
    $bank_ifsc = sanitize($_POST['bank_ifsc'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');

    if (!$name) $errors['name'] = 'Name is required.';

    if (!$errors) {
        db()->prepare("UPDATE vendors SET vendor_code=?,name=?,company=?,email=?,phone=?,gst_number=?,pan_number=?,address=?,city=?,state=?,pincode=?,bank_name=?,bank_account=?,bank_ifsc=?,notes=?,status=? WHERE id=?")
            ->execute([$vendor_code,$name,$company,$email,$phone,$gst_number,$pan_number,$address,$city,$state,$pincode,$bank_name,$bank_account,$bank_ifsc,$notes,$status,$id]);
        logActivity('vendors','update',"Updated vendor: $name",$id);
        setFlash('success',"Vendor '$name' updated.");
        header('Location: '.BASE_URL.'/modules/vendors/view.php?id='.$id);
        exit;
    }
    $v = array_merge($v, $_POST);
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Vendor</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Edit Vendor</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/vendors/index.php">Vendors</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/vendors/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-truck me-2"></i>Vendor Details</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Vendor Code</label>
            <input type="text" name="vendor_code" class="form-control" value="<?= e($v['vendor_code']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= e($v['name']) ?>">
            <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?= $errors['name'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company" class="form-control" value="<?= e($v['company']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($v['email']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= e($v['phone']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($v['status']??'active')==='active'?'selected':'' ?>>Active</option>
              <option value="inactive" <?= ($v['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt me-2"></i>Address & Tax</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"><?= e($v['address']??'') ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= e($v['city']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= e($v['state']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="<?= e($v['pincode']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">GST Number</label>
            <input type="text" name="gst_number" class="form-control" value="<?= e($v['gst_number']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_number" class="form-control" value="<?= e($v['pan_number']??'') ?>">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-bank me-2"></i>Bank Details</div>
        <div class="mb-3">
          <label class="form-label">Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="<?= e($v['bank_name']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Account Number</label>
          <input type="text" name="bank_account" class="form-control" value="<?= e($v['bank_account']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">IFSC Code</label>
          <input type="text" name="bank_ifsc" class="form-control" value="<?= e($v['bank_ifsc']??'') ?>">
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-card-text me-2"></i>Notes</div>
        <textarea name="notes" class="form-control" rows="4"><?= e($v['notes']??'') ?></textarea>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Vendor</button>
        <a href="<?= BASE_URL ?>/modules/vendors/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
