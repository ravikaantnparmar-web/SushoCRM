<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Add Vendor';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_code = sanitize($_POST['vendor_code'] ?? '');
    if (!$vendor_code) $vendor_code = generateVendorCode();
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
        db()->prepare("INSERT INTO vendors (vendor_code, name, company, email, phone, gst_number, pan_number, address, city, state, pincode, bank_name, bank_account, bank_ifsc, notes, status, created_by)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$vendor_code,$name,$company,$email,$phone,$gst_number,$pan_number,$address,$city,$state,$pincode,$bank_name,$bank_account,$bank_ifsc,$notes,$status,$_SESSION['user_id']]);
        $id = db()->lastInsertId();
        logActivity('vendors','create',"Created vendor: $name",$id);
        setFlash('success',"Vendor '$name' created.");
        header('Location: '.BASE_URL.'/modules/vendors/index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Add Vendor</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Add Vendor</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/vendors/index.php">Vendors</a></li><li class="breadcrumb-item active">Add</li></ol></nav>
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
            <input type="text" name="vendor_code" class="form-control" value="<?= e($_POST['vendor_code']??'') ?>" placeholder="Auto-generated if left blank">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= e($_POST['name']??'') ?>">
            <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?= $errors['name'] ?></div><?php endif; ?>
          </div>
          <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company" class="form-control" value="<?= e($_POST['company']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($_POST['email']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= e($_POST['phone']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= ($_POST['status']??'active')==='active'?'selected':'' ?>>Active</option>
              <option value="inactive" <?= ($_POST['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt me-2"></i>Address & Tax</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"><?= e($_POST['address']??'') ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= e($_POST['city']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= e($_POST['state']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="<?= e($_POST['pincode']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">GST Number</label>
            <input type="text" name="gst_number" class="form-control" value="<?= e($_POST['gst_number']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_number" class="form-control" value="<?= e($_POST['pan_number']??'') ?>">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-bank me-2"></i>Bank Details</div>
        <div class="mb-3">
          <label class="form-label">Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="<?= e($_POST['bank_name']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Account Number</label>
          <input type="text" name="bank_account" class="form-control" value="<?= e($_POST['bank_account']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">IFSC Code</label>
          <input type="text" name="bank_ifsc" class="form-control" value="<?= e($_POST['bank_ifsc']??'') ?>">
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-card-text me-2"></i>Notes</div>
        <textarea name="notes" class="form-control" rows="4"><?= e($_POST['notes']??'') ?></textarea>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Vendor</button>
        <a href="<?= BASE_URL ?>/modules/vendors/index.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
