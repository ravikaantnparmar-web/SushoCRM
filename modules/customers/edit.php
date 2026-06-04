<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../prospects/constants.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT c.*,
    (SELECT co.name FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_name,
    (SELECT co.mobile FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_phone,
    (SELECT co.email FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_email,
    (SELECT co.whatsapp FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_whatsapp
    FROM customers c WHERE c.id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) { setFlash('danger','Customer not found.'); header('Location: ' . BASE_URL . '/modules/customers/index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    $name    = sanitize($_POST['name'] ?? '');
    $company = sanitize($_POST['company'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $website = filter_var($_POST['website'] ?? '', FILTER_SANITIZE_URL);
    $phone   = sanitize($_POST['phone'] ?? '');
    $whatsapp = sanitize($_POST['whatsapp_number'] ?? '');
    $gst     = sanitize($_POST['gst_number'] ?? '');
    $pan     = sanitize($_POST['pan_number'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city    = sanitize($_POST['city'] ?? '');
    $state   = sanitize($_POST['state'] ?? '');
    $pincode = sanitize($_POST['pincode'] ?? '');
    $notes   = sanitize($_POST['notes'] ?? '');
    $status  = in_array($_POST['status'] ?? '', ['Prospect','Active','Inactive','Lost','Blacklisted']) ? $_POST['status'] : 'Active';
    $customer_type = sanitize($_POST['customer_type'] ?? 'Retail Customer');
    $business_category = sanitize($_POST['business_category'] ?? 'B2C');
    $industry_type = sanitize($_POST['industry_type'] ?? 'Real Estate');
    
    if (empty($company) && empty($name)) $errors[] = 'Company or Customer name is required.';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';

    if (empty($errors)) {
        db()->prepare("UPDATE customers SET company_name=?,company_email=?,company_website=?,gst_number=?,tin_number=?,address_line1=?,city=?,state=?,pincode=?,requirement_description=?,company_status=?,company_type=?,business_category=?,industry_type=? WHERE id=?")
           ->execute([$company,$email,$website,$gst,$pan,$address,$city,$state,$pincode,$notes,$status,$customer_type,$business_category,$industry_type,$id]);
        
        // Also update primary contact
        $contactCheck = db()->prepare("SELECT contact_id FROM contact_relations WHERE entity_type='customer' AND entity_id=? AND is_primary=1 LIMIT 1");
        $contactCheck->execute([$id]);
        $primaryContactId = $contactCheck->fetchColumn();
        
        if ($primaryContactId) {
            db()->prepare("UPDATE contacts SET name=?, mobile=?, whatsapp=?, email=? WHERE id=?")
               ->execute([$name, $phone, $whatsapp, $email, $primaryContactId]);
        } else if ($name || $phone) {
            // Check if contact already exists
            $existing = false;
            if (!empty($phone)) {
                $check = db()->prepare("SELECT id FROM contacts WHERE mobile = ? LIMIT 1");
                $check->execute([$phone]);
                $existing = $check->fetchColumn();
            }
            if (!$existing && !empty($email)) {
                $check = db()->prepare("SELECT id FROM contacts WHERE email = ? LIMIT 1");
                $check->execute([$email]);
                $existing = $check->fetchColumn();
            }
            if (!$existing) {
                $check = db()->prepare("SELECT id FROM contacts WHERE name = ? LIMIT 1");
                $check->execute([$name]);
                $existing = $check->fetchColumn();
            }

            if ($existing) {
                $primaryContactId = $existing;
            } else {
                db()->prepare("INSERT INTO contacts (contact_type, name, mobile, whatsapp, email) VALUES ('Primary', ?, ?, ?, ?)")
                   ->execute([$name, $phone, $whatsapp, $email]);
                $primaryContactId = db()->lastInsertId();
            }
            
            db()->prepare("INSERT INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'customer', ?, 'Primary', 1)")
               ->execute([$primaryContactId, $id]);
        }

        logActivity('customers','edit',"Updated customer: " . ($company ?: $name),$id);
        setFlash('success', "Customer updated successfully.");
        header('Location: ' . BASE_URL . '/modules/customers/view.php?id=' . $id);
        exit;
    }
    $customer = array_merge($customer, $_POST);
}
$pageTitle = 'Edit Customer';
$states = ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi'];
$displayName = $customer['primary_contact_name'] ?: $customer['company_name'] ?: 'Customer';
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Customer</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left">
    <h1>Edit Customer</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/customers/index.php">Customers</a></li>
      <li class="breadcrumb-item active"><?= e($displayName) ?></li>
    </ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $er) echo '<li>'.e($er).'</li>'; ?></ul></div><?php endif; ?>
<form method="POST" novalidate>
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-person me-2"></i>Basic Information</div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="<?= e($customer['primary_contact_name']??'') ?>" required></div>
          <div class="col-md-6"><label class="form-label">Company</label><input type="text" name="company" class="form-control" value="<?= e($customer['company_name']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($customer['company_email']??$customer['primary_contact_email']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">Website</label><input type="text" name="website" class="form-control" value="<?= e($customer['company_website']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= e($customer['primary_contact_phone']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control" value="<?= e($customer['primary_contact_whatsapp']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">GST Number</label><input type="text" name="gst_number" class="form-control" value="<?= e($customer['gst_number']??'') ?>"></div>
          <div class="col-md-6"><label class="form-label">TIN Number</label><input type="text" name="pan_number" class="form-control" value="<?= e($customer['tin_number']??'') ?>"></div>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-person-lines-fill me-2"></i>Profiling Information</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Customer Type</label>
            <select name="customer_type" class="form-select">
              <?php foreach($customerTypes as $opt): ?>
                <option value="<?= $opt ?>" <?= (($customer['company_type']??'Retail Customer')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Business Category</label>
            <select name="business_category" class="form-select">
              <?php foreach($businessCategories as $opt): ?>
                <option value="<?= $opt ?>" <?= (($customer['business_category']??'B2C')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Industry Type</label>
            <select name="industry_type" class="form-select">
              <?php foreach($industryTypes as $opt): ?>
                <option value="<?= $opt ?>" <?= (($customer['industry_type']??'Real Estate')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt me-2"></i>Address</div>
        <div class="row g-3">
          <div class="col-12"><label class="form-label">Street Address</label><textarea name="address" class="form-control" rows="2"><?= e($customer['address_line1']??'') ?></textarea></div>
          <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="<?= e($customer['city']??'') ?>"></div>
          <div class="col-md-4"><label class="form-label">State</label>
            <select name="state" class="form-select"><option value="">Select State</option>
            <?php foreach($states as $s): ?><option value="<?= e($s) ?>" <?= (($customer['state']??'')===$s)?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4"><label class="form-label">Pincode</label><input type="text" name="pincode" class="form-control" value="<?= e($customer['pincode']??'') ?>"></div>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-sticky me-2"></i>Requirements</div>
        <textarea name="notes" class="form-control" rows="3"><?= e($customer['requirement_description']??'') ?></textarea>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-gear me-2"></i>Settings</div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach(['Prospect', 'Active', 'Inactive', 'Lost', 'Blacklisted'] as $opt): ?>
              <option value="<?= $opt ?>" <?= (($customer['company_status']??'Active')===$opt)?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="crm-form-section">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Update Customer</button>
          <a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
