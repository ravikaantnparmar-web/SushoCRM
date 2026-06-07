<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../prospects/constants.php';
requireLogin();
requirePermission('customers', 'create');
$errors = [];
$sourceLeadId = null;

// Handle Pre-filling from Lead Conversion
if (isset($_GET['lead_id']) && empty($_POST)) {
    $leadId = (int)$_GET['lead_id'];
    $stmt = db()->prepare("SELECT * FROM leads WHERE id = ? AND is_converted = 0");
    $stmt->execute([$leadId]);
    $lead = $stmt->fetch();
    
    if ($lead) {
        $sourceLeadId = $lead['id'];
        $cStmt = db()->prepare("SELECT * FROM lead_contacts WHERE lead_id = ? AND is_primary = 1 LIMIT 1");
        $cStmt->execute([$leadId]);
        $contact = $cStmt->fetch();
        
        $_POST['name'] = $contact['name'] ?? '';
        $_POST['company'] = $lead['company_name'] ?? '';
        $_POST['email'] = $lead['company_email'] ?? ($contact['email'] ?? '');
        $_POST['website'] = $lead['company_website'] ?? '';
        $_POST['phone'] = $contact['mobile'] ?? '';
        $_POST['whatsapp_number'] = $contact['whatsapp'] ?? '';
        $_POST['gst_number'] = $lead['gst_number'] ?? '';
        $_POST['pan_number'] = $lead['tin_number'] ?? ''; // Using tin for pan loosely as placeholder
        $_POST['address'] = trim(($lead['address_line1'] ?? '') . "\n" . ($lead['address_line2'] ?? ''));
        $_POST['city'] = $lead['city'] ?? '';
        $_POST['state'] = $lead['state'] ?? '';
        $_POST['pincode'] = $lead['pincode'] ?? '';
        $_POST['notes'] = "Converted from Lead: " . $lead['lead_code'] . "\n" . ($lead['requirement_description'] ?? '');
        $_POST['industry_type'] = $lead['industry_type'] ?? 'Real Estate';
        $_POST['business_category'] = $lead['business_category'] ?? 'B2C';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sourceLeadId = isset($_POST['source_lead_id']) && $_POST['source_lead_id'] !== '' ? (int)$_POST['source_lead_id'] : null;
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
    $preferred_communication = in_array($_POST['preferred_communication'] ?? '', ['Call', 'WhatsApp', 'Email', 'Meeting']) ? $_POST['preferred_communication'] : 'Call';
    $credit  = (float)($_POST['credit_limit'] ?? 0);

    if (empty($name)) $errors[] = 'Customer name is required.';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';

    if (empty($errors)) {
        $db = db();
        $db->beginTransaction();
        
        try {
            $code = generateCustomerCode();
            $converted_from_lead = $sourceLeadId ? 1 : 0;
            
            $stmt = $db->prepare("INSERT INTO customers (customer_code, company_name, company_email, company_website, gst_number, tin_number, address_line1, city, state, pincode, company_status, company_type, business_category, industry_type, requirement_description, created_by, source_lead_id, converted_from_lead, customer_date)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())");
            $stmt->execute([$code, $company, $email, $website, $gst, $pan, $address, $city, $state, $pincode, $status, $customer_type, $business_category, $industry_type, $notes, $_SESSION['user_id'], $sourceLeadId, $converted_from_lead]);
            $customerId = $db->lastInsertId();
            
            // Handle Contacts
            if ($sourceLeadId) {
                // If converted from lead, update the lead status
                $db->prepare("UPDATE leads SET is_converted = 1, converted_date = NOW(), converted_by = ? WHERE id = ?")
                   ->execute([$_SESSION['user_id'], $sourceLeadId]);
                
                // Copy relations from lead to customer
                $getContactsStmt = $db->prepare("SELECT * FROM contact_relations WHERE entity_type = 'lead' AND entity_id = ?");
                $getContactsStmt->execute([$sourceLeadId]);
                $relations = $getContactsStmt->fetchAll();
                
                $relStmt = $db->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'customer', ?, ?, ?)");
                foreach ($relations as $r) {
                    $relStmt->execute([$r['contact_id'], $customerId, $r['role'], $r['is_primary']]);
                }
            } else {
                // If creating manually, insert the single primary contact from the form
                if (!empty($name) || !empty($phone)) {
                    $existing = false;
                    if (!empty($phone)) {
                        $check = $db->prepare("SELECT id FROM contacts WHERE mobile = ? LIMIT 1");
                        $check->execute([$phone]);
                        $existing = $check->fetchColumn();
                    }
                    if (!$existing && !empty($email)) {
                        $check = $db->prepare("SELECT id FROM contacts WHERE email = ? LIMIT 1");
                        $check->execute([$email]);
                        $existing = $check->fetchColumn();
                    }
                    if (!$existing) {
                        $check = $db->prepare("SELECT id FROM contacts WHERE name = ? LIMIT 1");
                        $check->execute([$name]);
                        $existing = $check->fetchColumn();
                    }

                    if ($existing) {
                        $primaryContactId = $existing;
                    } else {
                        $contactStmt = $db->prepare("INSERT INTO contacts (contact_type, name, mobile, whatsapp, email) VALUES ('Primary', ?, ?, ?, ?)");
                        $contactStmt->execute([$name, $phone, $whatsapp, $email]);
                        $primaryContactId = $db->lastInsertId();
                    }
                    
                    $db->prepare("INSERT INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'customer', ?, 'Primary', 1)")
                       ->execute([$primaryContactId, $customerId]);
                }
            }

            
            logActivity('customers','create',"Created customer: $name",$customerId);
            $db->commit();
            
            setFlash('success', "Customer '$name' created successfully.");
            header('Location: ' . BASE_URL . '/modules/customers/index.php');
            exit;
        } catch (\Exception $e) {
            $db->rollBack();
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
$pageTitle = 'Add Customer';
$states = ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Add Customer</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left">
    <h1>Add Customer</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/customers/index.php">Customers</a></li>
      <li class="breadcrumb-item active">Add</li>
    </ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/customers/index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
<?php endif; ?>

<form method="POST" novalidate>
  <?= csrfField() ?>
  <input type="hidden" name="source_lead_id" value="<?= e($sourceLeadId ?? '') ?>">
  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-person me-2"></i>Basic Information</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= e($_POST['name']??'') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company" class="form-control" value="<?= e($_POST['company']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="<?= e($_POST['email']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form-control" placeholder="www.example.com" value="<?= e($_POST['website']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?= e($_POST['phone']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">WhatsApp Number</label>
            <input type="text" name="whatsapp_number" class="form-control" value="<?= e($_POST['whatsapp_number']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">GST Number</label>
            <input type="text" name="gst_number" class="form-control" maxlength="20" value="<?= e($_POST['gst_number']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_number" class="form-control" maxlength="15" value="<?= e($_POST['pan_number']??'') ?>">
          </div>
        </div>
      </div>

      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-person-lines-fill me-2"></i>Profiling Information</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Customer Type</label>
            <select name="customer_type" class="form-select">
              <?php foreach($customerTypes as $opt): ?>
                <option value="<?= $opt ?>" <?= (($_POST['customer_type']??'Retail Customer')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Business Category</label>
            <select name="business_category" class="form-select">
              <?php foreach($businessCategories as $opt): ?>
                <option value="<?= $opt ?>" <?= (($_POST['business_category']??'B2C')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Industry Type</label>
            <select name="industry_type" class="form-select">
              <?php foreach($industryTypes as $opt): ?>
                <option value="<?= $opt ?>" <?= (($_POST['industry_type']??'Real Estate')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Preferred Communication</label>
            <select name="preferred_communication" class="form-select">
              <?php foreach(['Call', 'WhatsApp', 'Email', 'Meeting'] as $opt): ?>
                <option value="<?= $opt ?>" <?= (($_POST['preferred_communication']??'Call')===$opt)?'selected':'' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt me-2"></i>Address</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Street Address</label>
            <textarea name="address" class="form-control" rows="2"><?= e($_POST['address']??'') ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= e($_POST['city']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">State</label>
            <select name="state" class="form-select">
              <option value="">Select State</option>
              <?php foreach($states as $s): ?>
                <option value="<?= e($s) ?>" <?= (($_POST['state']??'')===$s)?'selected':'' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" maxlength="10" value="<?= e($_POST['pincode']??'') ?>">
          </div>
        </div>
      </div>

      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-sticky me-2"></i>Notes</div>
        <textarea name="notes" class="form-control" rows="3" placeholder="Internal notes about this customer..."><?= e($_POST['notes']??'') ?></textarea>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="crm-form-section">
        <div class="form-section-title"><i class="bi bi-gear me-2"></i>Settings</div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach(['Prospect', 'Active', 'Inactive', 'Lost', 'Blacklisted'] as $opt): ?>
              <option value="<?= $opt ?>" <?= (($_POST['status']??'Active')===$opt)?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Credit Limit (<?= CURRENCY_SYMBOL ?>)</label>
          <input type="number" name="credit_limit" class="form-control" min="0" step="0.01" value="<?= e($_POST['credit_limit']??'0') ?>">
        </div>
      </div>

      <div class="crm-form-section">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Save Customer</button>
          <a href="<?= BASE_URL ?>/modules/customers/index.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>
</div>
</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
