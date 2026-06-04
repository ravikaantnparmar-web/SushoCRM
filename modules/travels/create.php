<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Create Travel Request';
$errors = [];
$db = db();

// Fetch auxiliary data
$customers = $db->query("SELECT id, company_name as name FROM customers ORDER BY company_name ASC")->fetchAll();
$prospects = $db->query("SELECT id, name FROM prospects ORDER BY name ASC")->fetchAll();
$vendors = $db->query("SELECT id, name FROM vendors ORDER BY name ASC")->fetchAll();
$users = $db->query("SELECT id, name FROM users WHERE is_active=1 ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate Travel ID
    $travel_number = 'TRV-' . date('Ym') . '-' . rand(1000, 9999);
    
    $employee_id = $_SESSION['user_id'];
    $from_date = $_POST['from_date'] ?: null;
    $to_date = $_POST['to_date'] ?: null;
    $number_of_days = (int)$_POST['number_of_days'] ?: 0;
    $travel_type = $_POST['travel_type'] ?: null;
    $travel_priority = $_POST['travel_priority'] ?: null;
    $purpose_category = $_POST['purpose_category'] ?: null;
    $location_city = sanitize($_POST['location_city'] ?? '');
    $location_state = sanitize($_POST['location_state'] ?? '');
    $location_country = sanitize($_POST['location_country'] ?? '');
    $multiple_locations = sanitize($_POST['multiple_locations'] ?? ''); // Simple comma separated or similar
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
    
    // Basic validation
    if (!$from_date || !$to_date) $errors['period'] = 'Travel period is required.';
    if (!$location_city) $errors['location'] = 'Location city is required.';

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("
                INSERT INTO travels (
                    travel_number, employee_id, from_date, to_date, number_of_days, 
                    travel_type, travel_priority, purpose_category, 
                    location_city, location_state, location_country, multiple_locations, 
                    mode_of_travel, travel_status, 
                    meeting_agenda, meeting_with_type, meeting_with_id, meeting_datetime, meeting_venue,
                    meeting_purpose, followup_datetime,
                    expense_booking_required, estimated_budget, advance_taken
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $travel_number, $employee_id, $from_date, $to_date, $number_of_days,
                $travel_type, $travel_priority, $purpose_category,
                $location_city, $location_state, $location_country, $multiple_locations,
                $mode_of_travel, $travel_status,
                $meeting_agenda, $meeting_with_type, $meeting_with_id, $meeting_datetime, $meeting_venue,
                $meeting_purpose, $followup_datetime,
                $expense_booking_required, $estimated_budget, $advance_taken
            ]);
            
            $travel_id = $db->lastInsertId();
            
            // Handle File Uploads (Supporting Documents)
            $upload_dir = __DIR__ . '/../../uploads/travels/' . $travel_id . '/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $document_fields = [
                'travel_tickets' => 'Travel Tickets',
                'hotel_bills' => 'Hotel Bills',
                'food_bills' => 'Food Bills',
                'fuel_receipts' => 'Fuel Receipts',
                'client_documents' => 'Client Documents',
                'mom' => 'MOM',
                'photos' => 'Photos',
                'visiting_card' => 'Visiting Card',
                'signed_documents' => 'Signed Documents'
            ];
            
            foreach ($document_fields as $field => $type) {
                if (!empty($_FILES[$field]['name'])) {
                    $file = $_FILES[$field];
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_name = $field . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
                        $db->prepare("INSERT INTO travel_documents (travel_id, document_type, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?)")
                           ->execute([$travel_id, $type, $file['name'], 'uploads/travels/' . $travel_id . '/' . $new_name, $_SESSION['user_id']]);
                    }
                }
            }
            
            // Multiple Expense Bills
            if (!empty($_FILES['expense_bills']['name'][0])) {
                foreach ($_FILES['expense_bills']['name'] as $key => $name) {
                    if ($_FILES['expense_bills']['error'][$key] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($name, PATHINFO_EXTENSION);
                        $new_name = 'expense_bill_' . $key . '_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['expense_bills']['tmp_name'][$key], $upload_dir . $new_name)) {
                            $db->prepare("INSERT INTO travel_documents (travel_id, document_type, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?)")
                               ->execute([$travel_id, 'Expense Bills', $name, 'uploads/travels/' . $travel_id . '/' . $new_name, $_SESSION['user_id']]);
                        }
                    }
                }
            }

            logActivity('travels', 'create', "Created travel request: $travel_number", $travel_id);
            setFlash('success', "Travel request created successfully.");
            header('Location: view.php?id=' . $travel_id);
            exit;
        } catch (PDOException $e) {
            $errors['db'] = "Error saving to database: " . $e->getMessage();
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
  <div class="topbar-title">Create Travel Request</div>
</div>
<div class="page-content">

<div class="page-header">
  <div class="page-header-left">
    <h1>New Travel Request</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Travels</a></li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </nav>
  </div>
  <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form method="POST" enctype="multipart/form-data" id="travelForm">
    <div class="row g-4">
        <!-- Travel Information -->
        <div class="col-lg-8">
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">1. Travel Information</h5></div>
                <div class="crm-card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Employee Name</label>
                            <input type="text" class="form-control" value="<?= e($_SESSION['user_name']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employee ID</label>
                            <input type="text" class="form-control" value="<?= e($_SESSION['user_id']) ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">From Date <span class="text-danger">*</span></label>
                            <input type="date" name="from_date" id="from_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="to_date" id="to_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Number of Days</label>
                            <input type="number" name="number_of_days" id="number_of_days" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Travel Type</label>
                            <select name="travel_type" class="form-select">
                                <option value="Local">Local</option>
                                <option value="Domestic" selected>Domestic</option>
                                <option value="International">International</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Travel Priority</label>
                            <select name="travel_priority" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Purpose Category</label>
                            <select name="purpose_category" class="form-select">
                                <option value="Client Meeting">Client Meeting</option>
                                <option value="Site Visit">Site Visit</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Exhibition">Exhibition</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="location_city" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="location_state" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="location_country" class="form-control" value="India">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Multiple Locations (Optional)</label>
                            <input type="text" name="multiple_locations" class="form-control" placeholder="e.g. Mumbai, Pune, Nasik">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mode of Travel</label>
                            <select name="mode_of_travel" class="form-select">
                                <option value="Flight">Flight</option>
                                <option value="Train">Train</option>
                                <option value="Car">Car</option>
                                <option value="Bus">Bus</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Travel Status</label>
                            <select name="travel_status" class="form-select">
                                <option value="Planned">Planned</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meeting Details -->
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">2. Meeting Details</h5></div>
                <div class="crm-card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meeting Agenda</label>
                            <textarea name="meeting_agenda" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client / Customer <small class="text-muted">(type to search • <span class="badge bg-dark">F2</span> to add new)</small></label>
                            <div class="input-group position-relative">
                                <input type="text" id="meeting_with_search" class="form-control" placeholder="Search Client..." autocomplete="off">
                                <button class="btn btn-outline-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#quickAddCustomerModal" id="btnQuickAddTrigger"><i class="bi bi-person-plus-fill"></i></button>
                                <div id="search_results" class="list-group position-absolute shadow w-100 d-none" style="z-index: 1000; top: 100%; max-height: 300px; overflow-y: auto;"></div>
                            </div>
                            <input type="hidden" name="meeting_with_id" id="meeting_with_id">
                            <input type="hidden" name="meeting_with_type" id="meeting_with_type">
                            <div id="selection_badge" class="mt-2 d-none">
                                <span class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                                    <i class="bi bi-person-check-fill me-2 text-success"></i>
                                    <span id="selected_name"></span>
                                    <button type="button" class="btn-close ms-2" style="font-size: 0.6rem;" onclick="clearSelection()"></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meeting Date & Time</label>
                            <input type="datetime-local" name="meeting_datetime" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Expected Purpose of the Meeting</label>
                            <input type="text" name="meeting_purpose" class="form-control" placeholder="Briefly describe the purpose...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meeting Venue</label>
                            <input type="text" name="meeting_venue" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Follow-up Date & Time</label>
                            <input type="datetime-local" name="followup_datetime" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supporting Documents -->
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">3. Supporting Documents Upload</h5></div>
                <div class="crm-card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Travel Tickets</label>
                            <input type="file" name="travel_tickets" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hotel Bills</label>
                            <input type="file" name="hotel_bills" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Food Bills</label>
                            <input type="file" name="food_bills" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fuel Receipts</label>
                            <input type="file" name="fuel_receipts" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client Documents</label>
                            <input type="file" name="client_documents" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MOM (Minutes of Meeting)</label>
                            <input type="file" name="mom" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Photos</label>
                            <input type="file" name="photos" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Visiting Card</label>
                            <input type="file" name="visiting_card" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expense Bills (Multiple)</label>
                            <input type="file" name="expense_bills[]" class="form-control" multiple>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Signed Documents</label>
                            <input type="file" name="signed_documents" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options (Expenses & Submit) -->
        <div class="col-lg-4">
            <div class="crm-card mb-4">
                <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Expense Booking</h5></div>
                <div class="crm-card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Expense Booking Required?</label>
                        <select name="expense_booking_required" id="expense_toggle" class="form-select">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div id="expense_section" class="d-none">
                        <div class="mb-3">
                            <label class="form-label">Estimated Budget</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="estimated_budget" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Advance Taken</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="advance_taken" class="form-control" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="crm-card">
                <div class="crm-card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Create Travel Request</button>
                    <a href="index.php" class="btn btn-outline-secondary w-100">Cancel</a>
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
