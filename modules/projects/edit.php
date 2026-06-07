<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('danger','Project not found.'); header('Location: '.BASE_URL.'/modules/projects/index.php'); exit; }

$pageTitle = 'Edit Project: ' . $p['name'];
$errors = [];
$customers = db()->query("SELECT id, name, company FROM customers ORDER BY company ASC, name ASC")->fetchAll();
$users = db()->query("SELECT id, name FROM users ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_number = sanitize($_POST['project_number'] ?? '');
    $name = sanitize($_POST['name'] ?? '');
    $project_type = sanitize($_POST['project_type'] ?? '');
    $project_category = sanitize($_POST['project_category'] ?? '');
    $priority = sanitize($_POST['priority'] ?? 'medium');
    $stage = sanitize($_POST['stage'] ?? 'planning');
    $description = sanitize($_POST['description'] ?? '');
    $customer_id = (int)($_POST['customer_id'] ?? 0) ?: null;
    $status = sanitize($_POST['status'] ?? 'planning');
    $start_date = sanitize($_POST['start_date'] ?? '');
    $target_end_date = sanitize($_POST['target_end_date'] ?? '');
    $actual_end_date = sanitize($_POST['actual_end_date'] ?? '');
    $expected_duration = sanitize($_POST['expected_duration'] ?? '');
    $completion_percentage = (int)($_POST['completion_percentage'] ?? 0);
    $budget = (float)($_POST['budget'] ?? 0);
    $project_cost = (float)($_POST['project_cost'] ?? 0);
    $manager_id = (int)($_POST['manager_id'] ?? 0) ?: null;
    
    $site_address = sanitize($_POST['site_address'] ?? '');
    $site_city = sanitize($_POST['site_city'] ?? '');
    $site_state = sanitize($_POST['site_state'] ?? '');
    $site_pincode = sanitize($_POST['site_pincode'] ?? '');
    $google_maps_location = sanitize($_POST['google_maps_location'] ?? '');
    $site_contact_person = sanitize($_POST['site_contact_person'] ?? '');
    $site_contact_number = sanitize($_POST['site_contact_number'] ?? '');
    $site_engineer_name_number = sanitize($_POST['site_engineer_name_number'] ?? '');

    if (!$name) $errors['name'] = 'Project name is required.';

    if (!$errors) {
        $stmt = db()->prepare("
            UPDATE projects SET 
                project_number=?, name=?, project_type=?, project_category=?, priority=?, stage=?, description=?, customer_id=?, status=?, 
                start_date=?, target_end_date=?, actual_end_date=?, expected_duration=?, completion_percentage=?, 
                budget=?, project_cost=?, manager_id=?,
                site_address=?, site_city=?, site_state=?, site_pincode=?, google_maps_location=?, site_contact_person=?, site_contact_number=?, site_engineer_name_number=?
            WHERE id=?
        ");
        $stmt->execute([
            $project_number, $name, $project_type?:null, $project_category?:null, $priority, $stage, $description, $customer_id, $status,
            $start_date?:null, $target_end_date?:null, $actual_end_date?:null, $expected_duration?:null, $completion_percentage,
            $budget, $project_cost, $manager_id,
            $site_address?:null, $site_city?:null, $site_state?:null, $site_pincode?:null, $google_maps_location?:null, $site_contact_person?:null, $site_contact_number?:null, $site_engineer_name_number?:null,
            $id
        ]);
        
        logActivity('projects','update',"Updated project: $project_number", $id);
        setFlash('success',"Project '$name' updated successfully.");
        header('Location: '.BASE_URL.'/modules/projects/view.php?id='.$id);
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
  <div class="topbar-title">Edit Project</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left"><h1>Edit Project</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/projects/index.php">Projects</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/projects/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form method="POST">
<div class="row">
  <div class="col-lg-8">
    <div class="crm-card mb-4">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">General Information</h5></div>
      <div class="crm-card-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Project Code / Short Name</label>
              <input type="text" name="project_number" class="form-control" value="<?= e($p['project_number']) ?>">
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Project Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= e($p['name']) ?>" required>
              <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?= $errors['name'] ?></div><?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label">Project Type</label>
              <select name="project_type" class="form-select">
                <option value="">— Select Type —</option>
                <?php foreach(['Residential','Commercial','Hospitality','Retail','Villa','Apartment','Office'] as $opt): ?>
                  <option value="<?= $opt ?>" <?= $p['project_type']===$opt?'selected':'' ?>><?= $opt ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Project Category</label>
              <select name="project_category" class="form-select">
                <option value="">— Select Category —</option>
                <?php foreach(['Interior','Construction','Renovation','Turnkey','Architecture'] as $opt): ?>
                  <option value="<?= $opt ?>" <?= $p['project_category']===$opt?'selected':'' ?>><?= $opt ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Project Priority</label>
              <select name="priority" class="form-select">
                <option value="low" <?= $p['priority']==='low'?'selected':'' ?>>Low</option>
                <option value="medium" <?= $p['priority']==='medium'?'selected':'' ?>>Medium</option>
                <option value="high" <?= $p['priority']==='high'?'selected':'' ?>>High</option>
                <option value="urgent" <?= $p['priority']==='urgent'?'selected':'' ?>>Urgent</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Client / Customer <small class="text-muted">(type to search &bull; <kbd>F2</kbd> to add new)</small></label>
              <div class="customer-search-wrap">
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-search"></i></span>
                  <input type="text" id="customerSearch" class="form-control" placeholder="Search by name or company..." autocomplete="off">
                  <input type="hidden" name="customer_id" id="customer_id" value="<?= e($p['customer_id']??'') ?>">
                </div>
                <div id="customerResults" class="list-group position-absolute w-100 d-none" style="z-index:1050; top:100%; left:0;"></div>
              </div>
              <div id="selectedCustomer" class="mt-1 small text-primary d-none">
                <i class="bi bi-check-circle-fill text-success me-1"></i>Selected: <strong><span id="selectedCustomerName"></span></strong> &mdash; <span id="selectedCustomerType" class="text-muted"></span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="clearCustomer()" title="Remove selection"><i class="bi bi-x-circle"></i></button>
              </div>
            </div>
            
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"><?= e($p['description']) ?></textarea>
            </div>
          </div>
      </div>
    </div>

    <div class="crm-card mb-4">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Location & Site Details</h5></div>
      <div class="crm-card-body p-4">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Site Address</label>
              <textarea name="site_address" class="form-control" rows="2"><?= e($p['site_address']) ?></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">City</label>
              <input type="text" name="site_city" class="form-control" value="<?= e($p['site_city']) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">State</label>
              <input type="text" name="site_state" class="form-control" value="<?= e($p['site_state']) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Pincode</label>
              <input type="text" name="site_pincode" class="form-control" value="<?= e($p['site_pincode']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Google Maps Location Link</label>
              <input type="url" name="google_maps_location" id="google_maps_location_input" class="form-control" value="<?= e($p['google_maps_location']) ?>" placeholder="https://goo.gl/maps/...">
              
              <div id="googleMapsPreview" class="mt-2 small d-none p-2 bg-light rounded border border-light-subtle">
                <span class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Location:</span>
                <a href="#" id="googleMapsLink" target="_blank" class="fw-bold ms-1 text-decoration-none text-primary"></a>
              </div>
              <div id="googleMapsLoading" class="mt-2 small text-primary d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span> Extracting exact address from link...
              </div>
              <div id="googleMapsError" class="mt-2 small text-danger d-none">
                <i class="bi bi-exclamation-triangle me-1"></i> <span id="googleMapsErrorText"></span>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Site Contact Person</label>
              <input type="text" name="site_contact_person" class="form-control" value="<?= e($p['site_contact_person']) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Site Contact Number</label>
              <input type="text" name="site_contact_number" class="form-control" value="<?= e($p['site_contact_number']) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Site Engineer (Name/Number)</label>
              <input type="text" name="site_engineer_name_number" class="form-control" value="<?= e($p['site_engineer_name_number']) ?>">
            </div>
          </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="crm-card mb-4">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Timeline & Status</h5></div>
      <div class="crm-card-body p-4">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Overall Status</label>
              <select name="status" class="form-select">
                <option value="planning" <?= $p['status']==='planning'?'selected':'' ?>>Planning</option>
                <option value="in_progress" <?= $p['status']==='in_progress'?'selected':'' ?>>In Progress</option>
                <option value="on_hold" <?= $p['status']==='on_hold'?'selected':'' ?>>On Hold</option>
                <option value="completed" <?= $p['status']==='completed'?'selected':'' ?>>Completed</option>
                <option value="cancelled" <?= $p['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Project Stage</label>
              <select name="stage" class="form-select">
                <option value="planning" <?= $p['stage']==='planning'?'selected':'' ?>>Planning</option>
                <option value="design" <?= $p['stage']==='design'?'selected':'' ?>>Design</option>
                <option value="execution" <?= $p['stage']==='execution'?'selected':'' ?>>Execution</option>
                <option value="finishing" <?= $p['stage']==='finishing'?'selected':'' ?>>Finishing</option>
                <option value="handover" <?= $p['stage']==='handover'?'selected':'' ?>>Handover</option>
                <option value="completed" <?= $p['stage']==='completed'?'selected':'' ?>>Completed</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Project Manager</label>
              <select name="manager_id" class="form-select">
                <option value="">— Unassigned —</option>
                <?php foreach($users as $u): ?>
                  <option value="<?= $u['id'] ?>" <?= $p['manager_id']==$u['id']?'selected':'' ?>><?= e($u['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-control" value="<?= e($p['start_date']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Target End Date</label>
              <input type="date" name="target_end_date" class="form-control" value="<?= e($p['target_end_date']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Actual End Date</label>
              <input type="date" name="actual_end_date" class="form-control" value="<?= e($p['actual_end_date']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Expected Duration</label>
              <input type="text" name="expected_duration" class="form-control" value="<?= e($p['expected_duration']) ?>" placeholder="e.g. 6 Months">
            </div>
            <div class="col-12">
              <label class="form-label">Completion Percentage</label>
              <div class="input-group">
                <input type="number" name="completion_percentage" class="form-control" value="<?= e($p['completion_percentage']) ?>" min="0" max="100">
                <span class="input-group-text">%</span>
              </div>
            </div>
          </div>
      </div>
    </div>

    <div class="crm-card mb-4">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Financial Details</h5></div>
      <div class="crm-card-body p-4">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Approved Budget</label>
              <div class="input-group">
                <span class="input-group-text">&#8377;</span>
                <input type="number" name="budget" class="form-control" value="<?= e($p['budget']) ?>" step="0.01" min="0">
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Project Cost (Estimated)</label>
              <div class="input-group">
                <span class="input-group-text">&#8377;</span>
                <input type="number" name="project_cost" class="form-control" value="<?= e($p['project_cost']) ?>" step="0.01" min="0">
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="crm-card">
        <div class="crm-card-body p-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Project</button>
            <a href="<?= BASE_URL ?>/modules/projects/view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
  </div>
</div>
</form>

</div></div>

<style>
#customerResults {
    max-height: 260px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0 0 6px 6px;
    box-shadow: 0 6px 18px rgba(0,0,0,.12);
}
#customerResults .list-group-item { cursor: pointer; border-left: none; border-right: none; border-radius: 0 !important; }
#customerResults .list-group-item:first-child { border-top: none; }
#customerResults .list-group-item:hover { background-color: #f0f4ff; }
#customerResults .cust-type { font-size: 11px; text-transform: uppercase; color: #6c757d; }
.customer-search-wrap { position: relative; }
</style>

<!-- Quick Customer Add Modal (F2) -->
<div class="modal fade" id="quickCustomerModal" tabindex="-1" aria-labelledby="quickCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="quickCustomerModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Add New Customer</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="quickCustAlert" class="alert alert-danger d-none mb-3"></div>
        <form id="quickCustomerForm" novalidate>
          <input type="hidden" name="csrf_token" id="quickCsrfToken" value="<?= generateCsrfToken() ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="quickCustName" class="form-control" placeholder="Full name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Company Name</label>
              <input type="text" name="company" class="form-control" placeholder="Company / Firm name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer Type</label>
              <select name="customer_type" class="form-select">
                <option value="Retail Customer">Retail Customer</option>
                <option value="Corporate Client">Corporate Client</option>
                <option value="Architect">Architect</option>
                <option value="Interior Designer">Interior Designer</option>
                <option value="Builder">Builder</option>
                <option value="Contractor">Contractor</option>
                <option value="Dealer">Dealer</option>
                <option value="Channel Partner">Channel Partner</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone Number</label>
              <input type="text" name="phone" class="form-control" placeholder="Mobile / landline">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">WhatsApp Number</label>
              <input type="text" name="whatsapp_number" class="form-control" placeholder="WhatsApp number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="email@example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">City</label>
              <input type="text" name="city" class="form-control" placeholder="City">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">State</label>
              <input type="text" name="state" class="form-control" placeholder="State">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= BASE_URL ?>/modules/customers/create.php" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-box-arrow-up-right me-1"></i>Open Full Form
        </a>
        <button type="button" class="btn btn-primary" id="saveQuickCustomer">
          <i class="bi bi-check-lg me-1"></i>Save &amp; Select
        </button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
    // ---- Elements ----
    const customerSearch    = document.getElementById('customerSearch');
    const customerResults   = document.getElementById('customerResults');
    const customerIdInput   = document.getElementById('customer_id');
    const selectedCustomerDiv  = document.getElementById('selectedCustomer');
    const selectedCustomerName = document.getElementById('selectedCustomerName');
    const selectedCustomerType = document.getElementById('selectedCustomerType');
    const modalEl           = document.getElementById('quickCustomerModal');
    const quickCustAlert    = document.getElementById('quickCustAlert');
    const saveBtn           = document.getElementById('saveQuickCustomer');
    const quickCsrfInput    = document.getElementById('quickCsrfToken');
    const quickForm         = document.getElementById('quickCustomerForm');

    // Init Bootstrap modal safely (lazy initialization)
    let quickModal = null;
    function getModal() {
        if (!quickModal && typeof bootstrap !== 'undefined') {
            try { quickModal = new bootstrap.Modal(modalEl); } 
            catch(e) { console.error('Bootstrap modal init failed:', e); }
        }
        return quickModal;
    }

    // ---- Search with debounce ----
    let searchTimer = null;
    customerSearch.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        if (q.length < 2) {
            customerResults.classList.add('d-none');
            customerResults.innerHTML = '';
            return;
        }
        customerResults.innerHTML = '<div class="list-group-item text-center text-muted small py-2"><span class="spinner-border spinner-border-sm me-1"></span>Searching...</div>';
        customerResults.classList.remove('d-none');

        searchTimer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/modules/customers/ajax_search.php?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    customerResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(cust => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action';
                            const label = cust.company ? `<strong>${escHtml(cust.company)}</strong><span class="small text-muted ms-1">(${escHtml(cust.name)})</span>` : `<strong>${escHtml(cust.name)}</strong>`;
                            btn.innerHTML = `<div class="d-flex justify-content-between align-items-center">${label}<span class="cust-type">${escHtml(cust.customer_type)}</span></div>`;
                            btn.addEventListener('click', () => selectCustomer(cust));
                            customerResults.appendChild(btn);
                        });
                        customerResults.classList.remove('d-none');
                    } else {
                        customerResults.innerHTML = '<div class="list-group-item text-center small text-muted py-2">No customers found &mdash; press <kbd>F2</kbd> to add new</div>';
                        customerResults.classList.remove('d-none');
                    }
                })
                .catch(() => {
                    customerResults.innerHTML = '<div class="list-group-item text-danger text-center small py-2">Search failed. Try again.</div>';
                });
        }, 280);
    });

    // ---- Escape HTML helper ----
    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str || '';
        return d.innerHTML;
    }

    // ---- Select a customer ----
    function selectCustomer(cust) {
        customerIdInput.value = cust.id;
        customerSearch.value  = (cust.company || cust.name);
        customerResults.classList.add('d-none');
        customerResults.innerHTML = '';
        selectedCustomerName.textContent = cust.company || cust.name;
        selectedCustomerType.textContent = cust.customer_type || '';
        selectedCustomerDiv.classList.remove('d-none');
    }

    window.clearCustomer = function() {
        customerIdInput.value = '';
        customerSearch.value  = '';
        selectedCustomerDiv.classList.add('d-none');
        customerSearch.focus();
    };

    // ---- F2 key opens modal ----
    customerSearch.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            const modal = getModal();
            if (modal) { modal.show(); }
        }
        if (e.key === 'Escape') {
            customerResults.classList.add('d-none');
        }
    });

    // Focus first name field when modal opens
    modalEl.addEventListener('shown.bs.modal', function() {
        document.getElementById('quickCustName').focus();
        quickCustAlert.classList.add('d-none');
        quickCustAlert.textContent = '';
    });

    // Reset form when modal is hidden
    modalEl.addEventListener('hidden.bs.modal', function() {
        quickForm.reset();
        quickCustAlert.classList.add('d-none');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save &amp; Select';
    });

    // ---- Save quick customer via AJAX ----
    saveBtn.addEventListener('click', function() {
        const nameVal = document.getElementById('quickCustName').value.trim();
        if (!nameVal) {
            quickCustAlert.textContent = 'Customer name is required.';
            quickCustAlert.classList.remove('d-none');
            document.getElementById('quickCustName').focus();
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
        quickCustAlert.classList.add('d-none');

        const formData = new FormData(quickForm);

        fetch('<?= BASE_URL ?>/modules/customers/ajax_create.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.csrf_token) quickCsrfInput.value = data.csrf_token;
                selectCustomer(data.customer);
                const modal = getModal();
                if (modal) modal.hide();
            } else {
                quickCustAlert.textContent = 'Error: ' + (data.error || 'Unknown error');
                quickCustAlert.classList.remove('d-none');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save &amp; Select';
            }
        })
        .catch(() => {
            quickCustAlert.textContent = 'Network error. Please try again.';
            quickCustAlert.classList.remove('d-none');
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save &amp; Select';
        });
    });

    // ---- Close results when clicking outside ----
    document.addEventListener('click', function(e) {
        const wrap = customerSearch.closest('.customer-search-wrap');
        if (wrap && !wrap.contains(e.target)) {
            customerResults.classList.add('d-none');
        }
    });

    // ---- Pre-fill existing customer on edit ----
<?php if (!empty($p['customer_id'])):
    $cId   = (int)$p['customer_id'];
    $stmtC = db()->prepare("SELECT id, name, company, customer_type FROM customers WHERE id = ?"); $stmtC->execute([$cId]); $cData = $stmtC->fetch();
    if ($cData): ?>
    selectCustomer(<?= json_encode(['id'=>$cData['id'],'name'=>$cData['name'],'company'=>$cData['company'],'customer_type'=>$cData['customer_type']]) ?>);
<?php endif; endif; ?>

    // ---- Google Maps Auto-Extract Logic ----
    const gmapsInput = document.getElementById('google_maps_location_input');
    const gmapsPreview = document.getElementById('googleMapsPreview');
    const gmapsLink = document.getElementById('googleMapsLink');
    const gmapsLoading = document.getElementById('googleMapsLoading');
    const gmapsError = document.getElementById('googleMapsError');
    const gmapsErrorText = document.getElementById('googleMapsErrorText');
    const siteAddressInput = document.querySelector('textarea[name="site_address"]');

    function processGmapsUrl() {
        const url = gmapsInput.value.trim();
        gmapsPreview.classList.add('d-none');
        gmapsError.classList.add('d-none');
        
        if (!url) return;
        
        // Basic check for google maps url
        if (!/(google\.com\/maps|goo\.gl\/maps|maps\.app\.goo\.gl|maps\.google\.com)/i.test(url)) {
            return; // Not a maps url, silently ignore or we could show error
        }

        gmapsLoading.classList.remove('d-none');

        fetch(`<?= BASE_URL ?>/modules/projects/ajax_parse_google_map.php?url=${encodeURIComponent(url)}`)
            .then(res => res.json())
            .then(data => {
                gmapsLoading.classList.add('d-none');
                if (data.success && data.address) {
                    gmapsLink.textContent = data.address;
                    gmapsLink.href = data.final_url || url;
                    gmapsPreview.classList.remove('d-none');
                    
                    // Auto-fill site address if empty
                    if (siteAddressInput && siteAddressInput.value.trim() === '') {
                        siteAddressInput.value = data.address;
                        // Flash green briefly to indicate auto-fill
                        siteAddressInput.style.transition = 'background-color 0.5s';
                        siteAddressInput.style.backgroundColor = '#d1e7dd';
                        setTimeout(() => { siteAddressInput.style.backgroundColor = ''; }, 800);
                    }
                } else if (!data.success) {
                    gmapsErrorText.textContent = data.error || 'Failed to extract address.';
                    gmapsError.classList.remove('d-none');
                }
            })
            .catch(err => {
                gmapsLoading.classList.add('d-none');
                gmapsErrorText.textContent = 'Network error during address extraction.';
                gmapsError.classList.remove('d-none');
            });
    }

    if (gmapsInput) {
        gmapsInput.addEventListener('blur', processGmapsUrl);
        gmapsInput.addEventListener('paste', function() {
            // Need a slight delay to allow paste to complete before reading value
            setTimeout(processGmapsUrl, 100);
        });
        
        // Trigger on load if there's already a value
        if (gmapsInput.value.trim()) {
            processGmapsUrl();
        }
    }

})();
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
