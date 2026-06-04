<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$db = db();
$errors = [];

// Fetch record
$stmt = $db->prepare("SELECT * FROM travels WHERE id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    setFlash('danger', 'Record not found.');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_date = $_POST['from_date'] ?: null;
    $to_date = $_POST['to_date'] ?: null;
    $number_of_days = (int)$_POST['number_of_days'] ?: 0;
    $travel_type = $_POST['travel_type'] ?: null;
    $travel_priority = $_POST['travel_priority'] ?: null;
    $purpose_category = $_POST['purpose_category'] ?: null;
    $location_city = sanitize($_POST['location_city'] ?? '');
    $location_state = sanitize($_POST['location_state'] ?? '');
    $location_country = sanitize($_POST['location_country'] ?? '');
    $multiple_locations = sanitize($_POST['multiple_locations'] ?? '');
    $mode_of_travel = $_POST['mode_of_travel'] ?: null;
    $travel_status = $_POST['travel_status'] ?: 'Planned';

    $meeting_agenda = sanitize($_POST['meeting_agenda'] ?? '');
    $meeting_with_type = $_POST['meeting_with_type'] ?: null;
    $meeting_with_id = (int)($_POST['meeting_with_id'] ?? 0) ?: null;
    $meeting_datetime = $_POST['meeting_datetime'] ?: null;
    $meeting_venue = sanitize($_POST['meeting_venue'] ?? '');
    $meeting_purpose = sanitize($_POST['meeting_purpose'] ?? '');
    $followup_datetime = $_POST['followup_datetime'] ?: null;

    $expense_booking_required = $_POST['expense_booking_required'] ?? 'No';
    $estimated_budget = (float)($_POST['estimated_budget'] ?? 0);
    $advance_taken = (float)($_POST['advance_taken'] ?? 0);
    
    if (!$from_date || !$to_date) $errors['period'] = 'Travel period is required.';
    if (!$location_city) $errors['location'] = 'Location city is required.';

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("
                UPDATE travels SET 
                    from_date = ?, to_date = ?, number_of_days = ?, 
                    travel_type = ?, travel_priority = ?, purpose_category = ?, 
                    location_city = ?, location_state = ?, location_country = ?, multiple_locations = ?, 
                    mode_of_travel = ?, travel_status = ?, 
                    meeting_agenda = ?, meeting_with_type = ?, meeting_with_id = ?, meeting_datetime = ?, meeting_venue = ?,
                    meeting_purpose = ?, followup_datetime = ?,
                    expense_booking_required = ?, estimated_budget = ?, advance_taken = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $from_date, $to_date, $number_of_days,
                $travel_type, $travel_priority, $purpose_category,
                $location_city, $location_state, $location_country, $multiple_locations,
                $mode_of_travel, $travel_status,
                $meeting_agenda, $meeting_with_type, $meeting_with_id, $meeting_datetime, $meeting_venue,
                $meeting_purpose, $followup_datetime,
                $expense_booking_required, $estimated_budget, $advance_taken, $id
            ]);
            
            logActivity('travels', 'update', "Updated travel record: " . $t['travel_number'], $id);
            setFlash('success', "Travel record updated successfully.");
            header('Location: view.php?id=' . $id);
            exit;
        } catch (PDOException $e) {
            $errors['db'] = "Error updating record: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Edit Travel Request: <?= e($t['travel_number']) ?></div>
</div>
<div class="page-content">

<div class="page-header">
  <div class="page-header-left">
    <h1>Edit Request</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Travels</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>
  <a href="view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
</div>

<form method="POST">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">1. Travel Information</h5></div>
                <div class="crm-card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $t['from_date'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $t['to_date'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Number of Days</label>
                            <input type="number" name="number_of_days" id="number_of_days" class="form-control" value="<?= $t['number_of_days'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Travel Type</label>
                            <select name="travel_type" class="form-select">
                                <option value="Local" <?= $t['travel_type']=='Local'?'selected':'' ?>>Local</option>
                                <option value="Domestic" <?= $t['travel_type']=='Domestic'?'selected':'' ?>>Domestic</option>
                                <option value="International" <?= $t['travel_type']=='International'?'selected':'' ?>>International</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Travel Priority</label>
                            <select name="travel_priority" class="form-select">
                                <option value="Low" <?= $t['travel_priority']=='Low'?'selected':'' ?>>Low</option>
                                <option value="Medium" <?= $t['travel_priority']=='Medium'?'selected':'' ?>>Medium</option>
                                <option value="High" <?= $t['travel_priority']=='High'?'selected':'' ?>>High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Purpose Category</label>
                            <select name="purpose_category" class="form-select">
                                <option value="Client Meeting" <?= $t['purpose_category']=='Client Meeting'?'selected':'' ?>>Client Meeting</option>
                                <option value="Site Visit" <?= $t['purpose_category']=='Site Visit'?'selected':'' ?>>Site Visit</option>
                                <option value="Vendor" <?= $t['purpose_category']=='Vendor'?'selected':'' ?>>Vendor</option>
                                <option value="Exhibition" <?= $t['purpose_category']=='Exhibition'?'selected':'' ?>>Exhibition</option>
                                <option value="Other" <?= $t['purpose_category']=='Other'?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="location_city" class="form-control" value="<?= e($t['location_city']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="location_state" class="form-control" value="<?= e($t['location_state']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="location_country" class="form-control" value="<?= e($t['location_country']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Multiple Locations</label>
                            <input type="text" name="multiple_locations" class="form-control" value="<?= e($t['multiple_locations']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mode of Travel</label>
                            <select name="mode_of_travel" class="form-select">
                                <option value="Flight" <?= $t['mode_of_travel']=='Flight'?'selected':'' ?>>Flight</option>
                                <option value="Train" <?= $t['mode_of_travel']=='Train'?'selected':'' ?>>Train</option>
                                <option value="Car" <?= $t['mode_of_travel']=='Car'?'selected':'' ?>>Car</option>
                                <option value="Bus" <?= $t['mode_of_travel']=='Bus'?'selected':'' ?>>Bus</option>
                                <option value="Other" <?= $t['mode_of_travel']=='Other'?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Travel Status</label>
                            <select name="travel_status" class="form-select">
                                <option value="Planned" <?= $t['travel_status']=='Planned'?'selected':'' ?>>Planned</option>
                                <option value="Ongoing" <?= $t['travel_status']=='Ongoing'?'selected':'' ?>>Ongoing</option>
                                <option value="Completed" <?= $t['travel_status']=='Completed'?'selected':'' ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">2. Meeting Details</h5></div>
                <div class="crm-card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meeting Agenda</label>
                            <textarea name="meeting_agenda" class="form-control" rows="2"><?= e($t['meeting_agenda']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $selectedName = '';
                            if ($t['meeting_with_id'] && $t['meeting_with_type'] === 'Customer') {
                                $stmt = $db->prepare("SELECT name FROM customers WHERE id = ?");
                                $stmt->execute([$t['meeting_with_id']]);
                                $selectedName = $stmt->fetchColumn();
                            }
                            ?>
                            <label class="form-label">Client / Customer <small class="text-muted">(type to search • <span class="badge bg-dark">F2</span> to add new)</small></label>
                            <div class="input-group position-relative">
                                <input type="text" id="meeting_with_search" class="form-control <?= $selectedName?'d-none':'' ?>" placeholder="Search Client..." autocomplete="off">
                                <button class="btn btn-outline-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#quickAddCustomerModal" id="btnQuickAddTrigger"><i class="bi bi-person-plus-fill"></i></button>
                                <div id="search_results" class="list-group position-absolute shadow w-100 d-none" style="z-index: 1000; top: 100%; max-height: 300px; overflow-y: auto;"></div>
                            </div>
                            <input type="hidden" name="meeting_with_id" id="meeting_with_id" value="<?= $t['meeting_with_id'] ?>">
                            <input type="hidden" name="meeting_with_type" id="meeting_with_type" value="<?= $t['meeting_with_type'] ?>">
                            <div id="selection_badge" class="mt-2 <?= $selectedName?'':'d-none' ?>">
                                <span class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                                    <i class="bi bi-person-check-fill me-2 text-success"></i>
                                    <span id="selected_name"><?= e($selectedName) ?></span>
                                    <button type="button" class="btn-close ms-2" style="font-size: 0.6rem;" onclick="clearSelection()"></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meeting Date & Time</label>
                            <input type="datetime-local" name="meeting_datetime" class="form-control" value="<?= $t['meeting_datetime']?date('Y-m-d\TH:i', strtotime($t['meeting_datetime'])):'' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Expected Purpose of the Meeting</label>
                            <input type="text" name="meeting_purpose" class="form-control" value="<?= e($t['meeting_purpose']) ?>" placeholder="Briefly describe the purpose...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meeting Venue</label>
                            <input type="text" name="meeting_venue" class="form-control" value="<?= e($t['meeting_venue']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Follow-up Date & Time</label>
                            <input type="datetime-local" name="followup_datetime" class="form-control" value="<?= $t['followup_datetime']?date('Y-m-d\TH:i', strtotime($t['followup_datetime'])):'' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Expense Booking</h5></div>
                <div class="crm-card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Expense Booking Required?</label>
                        <select name="expense_booking_required" id="expense_toggle" class="form-select">
                            <option value="No" <?= $t['expense_booking_required']=='No'?'selected':'' ?>>No</option>
                            <option value="Yes" <?= $t['expense_booking_required']=='Yes'?'selected':'' ?>>Yes</option>
                        </select>
                    </div>
                    <div id="expense_section" class="<?= $t['expense_booking_required']=='Yes'?'':'d-none' ?>">
                        <div class="mb-3">
                            <label class="form-label">Estimated Budget</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="estimated_budget" class="form-control" value="<?= $t['estimated_budget'] ?>" step="0.01">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Advance Taken</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="advance_taken" class="form-control" value="<?= $t['advance_taken'] ?>" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crm-card">
                <div class="crm-card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Update Travel Request</button>
                    <a href="view.php?id=<?= $id ?>" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

</div></div>

<!-- Quick Add Customer Modal -->
<div class="modal fade" id="quickAddCustomerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="quickCustomerForm">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Quick Add Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Customer Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Company Name</label>
              <input type="text" name="company" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="btnSaveCustomer">Save & Select</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function clearSelection() {
    document.getElementById('meeting_with_id').value = '';
    document.getElementById('meeting_with_type').value = '';
    document.getElementById('selected_name').textContent = '';
    document.getElementById('selection_badge').classList.add('d-none');
    document.getElementById('meeting_with_search').value = '';
    document.getElementById('meeting_with_search').classList.remove('d-none');
}

document.addEventListener('DOMContentLoaded', function() {
    const fromDate = document.getElementById('from_date');
    const toDate = document.getElementById('to_date');
    const numDays = document.getElementById('number_of_days');
    const expToggle = document.getElementById('expense_toggle');
    const expSection = document.getElementById('expense_section');

    function calculateDays() {
        if (fromDate.value && toDate.value) {
            const start = new Date(fromDate.value);
            const end = new Date(toDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 
            numDays.value = diffDays > 0 ? diffDays : 0;
        }
    }

    fromDate.addEventListener('change', calculateDays);
    toDate.addEventListener('change', calculateDays);

    expToggle.addEventListener('change', function() {
        if (this.value === 'Yes') {
            expSection.classList.remove('d-none');
        } else {
            expSection.classList.add('d-none');
        }
    });

    // Autocomplete Logic
    const searchInput = document.getElementById('meeting_with_search');
    const searchResults = document.getElementById('search_results');
    const withIdInput = document.getElementById('meeting_with_id');
    const withTypeInput = document.getElementById('meeting_with_type');
    const selectionBadge = document.getElementById('selection_badge');
    const selectedName = document.getElementById('selected_name');

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        if (query.length < 2) {
            searchResults.classList.add('d-none');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/modules/customers/ajax_search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'list-group-item list-group-item-action cursor-pointer p-3';
                            div.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold fs-6">${item.company || item.name}</span>
                                        ${item.company ? `<span class="text-muted ms-2">(${item.name})</span>` : ''}
                                    </div>
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">${item.customer_type || 'RETAIL CUSTOMER'}</small>
                                </div>
                            `;
                            div.onclick = function() {
                                selectEntity(item.id, item.name, 'Customer');
                            };
                            searchResults.appendChild(div);
                        });
                        searchResults.classList.remove('d-none');
                    } else {
                        searchResults.innerHTML = '<div class="list-group-item text-muted">No results found. Press <b>F2</b> to add new.</div>';
                        searchResults.classList.remove('d-none');
                    }
                });
        }, 300);
    });

    // F2 Shortcut Logic
    window.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('quickAddCustomerModal'));
            modal.show();
        }
    });

    function selectEntity(id, name, type) {
        withIdInput.value = id;
        withTypeInput.value = type;
        selectedName.textContent = name;
        selectionBadge.classList.remove('d-none');
        searchInput.classList.add('d-none');
        searchResults.classList.add('d-none');
    }

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });

    // Quick Add Customer Logic
    const quickForm = document.getElementById('quickCustomerForm');
    quickForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveCustomer');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const formData = new FormData(this);
        fetch('<?= BASE_URL ?>/modules/customers/ajax_create.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectEntity(data.customer.id, data.customer.name, 'Customer');
                bootstrap.Modal.getInstance(document.getElementById('quickAddCustomerModal')).hide();
                quickForm.reset();
            } else {
                alert(data.error || 'Failed to save customer');
            }
        })
        .catch(err => alert('An error occurred'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Save & Select';
        });
    });
});
</script>

<style>
.cursor-pointer { cursor: pointer; }
</style>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
