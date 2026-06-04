<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$db = db();

// Handle Actions (Update Outcome, Update Expense, Approval)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_outcome') {
        $stmt = $db->prepare("
            UPDATE travels SET 
                meeting_outcome = ?, customer_interest_level = ?, discussion_summary = ?, 
                client_requirement = ?, quotation_required = ?, expected_business_value = ?, 
                expected_closure_date = ?, follow_up_needed = ?, follow_up_date = ?, 
                follow_up_assigned_to = ?, next_action_plan = ?, customer_feedback = ?, 
                deal_status = ?, travel_status = 'Completed'
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST['meeting_outcome'], $_POST['customer_interest_level'], sanitize($_POST['discussion_summary']),
            sanitize($_POST['client_requirement']), $_POST['quotation_required'], (float)$_POST['expected_business_value'],
            $_POST['expected_closure_date'] ?: null, $_POST['follow_up_needed'], $_POST['follow_up_date'] ?: null,
            (int)$_POST['follow_up_assigned_to'] ?: null, sanitize($_POST['next_action_plan']), sanitize($_POST['customer_feedback']),
            $_POST['deal_status'], $id
        ]);
        setFlash('success', 'Visit outcome updated successfully.');
    } elseif ($action === 'update_expense') {
        $stmt = $db->prepare("
            UPDATE travels SET 
                actual_expense_amount = ?, payment_done_by = ?, reimbursement_required = ?, 
                expense_date = ?, expense_vendor_name = ?, payment_method = ?, 
                gst_applicable = ?, gst_number = ?, expense_notes = ?
            WHERE id = ?
        ");
        $stmt->execute([
            (float)$_POST['actual_expense_amount'], $_POST['payment_done_by'], $_POST['reimbursement_required'],
            $_POST['expense_date'] ?: null, sanitize($_POST['expense_vendor_name']), $_POST['payment_method'],
            $_POST['gst_applicable'], sanitize($_POST['gst_number']), sanitize($_POST['expense_notes']), $id
        ]);
        setFlash('success', 'Expense details updated.');
    } elseif ($action === 'approve_travel' && isAdmin()) {
        $status = $_POST['status'];
        $db->prepare("UPDATE travels SET overall_approval_status = ?, approved_by = ? WHERE id = ?")
           ->execute([$status, $_SESSION['user_id'], $id]);
        setFlash('success', 'Travel request ' . $status . '.');
    } elseif ($action === 'approve_expense' && isAdmin()) {
        $status = $_POST['status'];
        $db->prepare("UPDATE travels SET expense_approval_status = ? WHERE id = ?")
           ->execute([$status, $id]);
        setFlash('success', 'Expense ' . $status . '.');
    }
    
    header('Location: view.php?id=' . $id);
    exit;
}

// Fetch Travel Details
$stmt = $db->prepare("
    SELECT t.*, u.name as employee_name, f.name as follow_up_name, a.name as approver_name
    FROM travels t 
    LEFT JOIN users u ON t.employee_id = u.id 
    LEFT JOIN users f ON t.follow_up_assigned_to = f.id
    LEFT JOIN users a ON t.approved_by = a.id
    WHERE t.id = ?
");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    setFlash('danger', 'Travel record not found.');
    header('Location: index.php');
    exit;
}

// Fetch Documents
$doc_stmt = $db->prepare("SELECT * FROM travel_documents WHERE travel_id = ?");
$doc_stmt->execute([$id]);
$documents = $doc_stmt->fetchAll();

$users = $db->query("SELECT id, name FROM users WHERE is_active=1 ORDER BY name ASC")->fetchAll();

$pageTitle = 'Travel Record: ' . $t['travel_number'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Travel Details: <?= e($t['travel_number']) ?></div>
</div>
<div class="page-content">

<?= flashHtml() ?>

<div class="page-header">
  <div class="page-header-left">
    <h1><?= e($t['travel_number']) ?> <span class="badge bg-primary fs-6 ms-2 text-capitalize"><?= e($t['travel_status']) ?></span></h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Travels</a></li>
        <li class="breadcrumb-item active"><?= e($t['travel_number']) ?></li>
      </ol>
    </nav>
  </div>
  <div class="page-header-right d-flex gap-2">
    <?php if(isAdmin()): ?>
        <?php if($t['overall_approval_status'] === 'Pending'): ?>
            <form method="POST" class="d-inline">
                <input type="hidden" name="action" value="approve_travel">
                <input type="hidden" name="status" value="Approved">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Approve Travel</button>
            </form>
            <form method="POST" class="d-inline">
                <input type="hidden" name="action" value="approve_travel">
                <input type="hidden" name="status" value="Rejected">
                <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i>Reject</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
    <a href="index.php" class="btn btn-outline-secondary">Back</a>
  </div>
</div>

<div class="row g-4">
    <!-- Main Info -->
    <div class="col-lg-8">
        <div class="crm-card mb-4">
            <div class="crm-card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">General Information</h5>
                <span class="small text-muted">Created: <?= formatDate($t['date_of_request'], true) ?></span>
            </div>
            <div class="crm-card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="text-muted small d-block">Employee</label><span class="fw-bold"><?= e($t['employee_name']) ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Travel Type</label><span><?= e($t['travel_type']) ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Period</label><span class="fw-bold"><?= formatDate($t['from_date']) ?> to <?= formatDate($t['to_date']) ?> (<?= $t['number_of_days'] ?> Days)</span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Priority</label><?= statusBadge($t['travel_priority']) ?></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Location</label><span><?= e($t['location_city']) ?>, <?= e($t['location_state']) ?>, <?= e($t['location_country']) ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Purpose</label><span><?= e($t['purpose_category']) ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Mode</label><span><?= e($t['mode_of_travel']) ?></span></div>
                    <?php if($t['multiple_locations']): ?>
                    <div class="col-12"><label class="text-muted small d-block">Multiple Locations</label><span><?= e($t['multiple_locations']) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Meeting Section -->
        <div class="crm-card mb-4">
            <div class="crm-card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Meeting Details & Outcome</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#outcomeModal">Update Outcome</button>
            </div>
            <div class="crm-card-body p-4">
                <div class="row g-3">
                    <?php
                    $meetingWith = $t['meeting_with_type'];
                    if ($t['meeting_with_id']) {
                        if ($t['meeting_with_type'] === 'Customer') {
                            $stmt = $db->prepare("SELECT name FROM customers WHERE id = ?");
                            $stmt->execute([$t['meeting_with_id']]);
                            $meetingWith = $stmt->fetchColumn() . ' (Customer)';
                        }
                    }
                    ?>
                    <div class="col-md-6"><label class="text-muted small d-block">Meeting With</label><span class="fw-bold"><?= e($meetingWith) ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Datetime</label><span><?= $t['meeting_datetime'] ? formatDate($t['meeting_datetime'], true) : '—' ?></span></div>
                    <div class="col-12"><label class="text-muted small d-block">Expected Purpose</label><p class="mb-0 fw-bold text-primary"><?= e($t['meeting_purpose']) ?: '—' ?></p></div>
                    <div class="col-12"><label class="text-muted small d-block">Agenda</label><p class="mb-0"><?= nl2br(e($t['meeting_agenda'])) ?: '—' ?></p></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Venue</label><span><?= e($t['meeting_venue']) ?: '—' ?></span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Initial Follow-up</label><span><?= $t['followup_datetime'] ? formatDate($t['followup_datetime'], true) : '—' ?></span></div>
                    
                    <div class="col-12 border-top pt-3 mt-3">
                        <label class="text-muted small d-block">Outcome</label>
                        <span class="fw-bold fs-6 text-primary"><?= e($t['meeting_outcome']) ?: 'Pending' ?></span>
                    </div>
                    
                    <?php if($t['meeting_outcome']): ?>
                    <div class="col-md-4"><label class="text-muted small d-block">Interest Level</label><span><?= e($t['customer_interest_level']) ?></span></div>
                    <div class="col-md-4"><label class="text-muted small d-block">Deal Status</label><span><?= e($t['deal_status']) ?></span></div>
                    <div class="col-md-4"><label class="text-muted small d-block">Quotation Req.</label><span><?= e($t['quotation_required']) ?></span></div>
                    <div class="col-12"><label class="text-muted small d-block">Discussion Summary</label><p class="mb-0"><?= nl2br(e($t['discussion_summary'])) ?></p></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Follow-up Needed</label><span><?= e($t['follow_up_needed']) ?> (<?= $t['follow_up_date'] ? formatDate($t['follow_up_date']) : 'N/A' ?>)</span></div>
                    <div class="col-md-6"><label class="text-muted small d-block">Follow-up By</label><span><?= e($t['follow_up_name']) ?: '—' ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="crm-card mb-4">
            <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Supporting Documents</h5></div>
            <div class="crm-card-body p-4">
                <?php if(empty($documents)): ?>
                    <p class="text-muted mb-0 italic">No documents uploaded.</p>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach($documents as $doc): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded p-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-richtext fs-4 text-primary"></i>
                                    <div class="overflow-hidden">
                                        <div class="small fw-bold text-truncate"><?= e($doc['document_type']) ?></div>
                                        <a href="<?= BASE_URL ?>/<?= e($doc['file_path']) ?>" target="_blank" class="x-small text-decoration-none">View File</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Info (Expenses & Status) -->
    <div class="col-lg-4">
        <div class="crm-card mb-4">
            <div class="crm-card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Expense Details</h5>
                <?php if($t['expense_booking_required'] === 'Yes'): ?>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">Update</button>
                <?php endif; ?>
            </div>
            <div class="crm-card-body p-4">
                <div class="mb-3"><label class="text-muted small d-block">Required?</label><span><?= e($t['expense_booking_required']) ?></span></div>
                <?php if($t['expense_booking_required'] === 'Yes'): ?>
                    <div class="mb-3"><label class="text-muted small d-block">Est. Budget</label><span class="fw-bold"><?= formatCurrency($t['estimated_budget']) ?></span></div>
                    <div class="mb-3"><label class="text-muted small d-block">Actual Amount</label><span class="fw-bold text-danger"><?= formatCurrency($t['actual_expense_amount']) ?></span></div>
                    <div class="mb-3"><label class="text-muted small d-block">Advance Taken</label><span class="fw-bold text-success"><?= formatCurrency($t['advance_taken']) ?></span></div>
                    <div class="mb-3 border-top pt-2">
                        <label class="text-muted small d-block">Balance Amount</label>
                        <span class="fs-5 fw-bold"><?= formatCurrency($t['actual_expense_amount'] - $t['advance_taken']) ?></span>
                    </div>
                    <div class="mb-3"><label class="text-muted small d-block">Expense Status</label><span><?= statusBadge($t['expense_approval_status']) ?></span></div>
                    
                    <?php if(isAdmin() && $t['expense_approval_status'] === 'Pending'): ?>
                        <div class="d-flex gap-2">
                            <form method="POST" class="flex-fill">
                                <input type="hidden" name="action" value="approve_expense">
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="btn btn-sm btn-success w-100">Approve Exp</button>
                            </form>
                            <form method="POST" class="flex-fill">
                                <input type="hidden" name="action" value="approve_expense">
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Reject</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="crm-card">
            <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Approval Workflow</h5></div>
            <div class="crm-card-body p-4 text-center">
                <div class="mb-2">
                    <span class="text-muted small d-block">Travel Approval</span>
                    <span class="fs-5 fw-bold"><?= e($t['overall_approval_status']) ?></span>
                </div>
                <?php if($t['approved_by']): ?>
                    <div class="small text-muted">Approved by <?= e($t['approver_name']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Update Outcome -->
<div class="modal fade" id="outcomeModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="action" value="update_outcome">
        <div class="modal-header">
          <h5 class="modal-title">Meeting Outcome & Visit Report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Meeting Outcome</label>
                <select name="meeting_outcome" class="form-select">
                    <option value="Successful" <?= $t['meeting_outcome']=='Successful'?'selected':'' ?>>Successful</option>
                    <option value="Pending" <?= $t['meeting_outcome']=='Pending'?'selected':'' ?>>Pending</option>
                    <option value="Rejected" <?= $t['meeting_outcome']=='Rejected'?'selected':'' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Customer Interest Level</label>
                <select name="customer_interest_level" class="form-select">
                    <option value="Hot">Hot</option>
                    <option value="Warm">Warm</option>
                    <option value="Cold">Cold</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Discussion Summary</label>
                <textarea name="discussion_summary" class="form-control" rows="3"><?= e($t['discussion_summary']) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Client Requirement</label>
                <textarea name="client_requirement" class="form-control" rows="2"><?= e($t['client_requirement']) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Quotation Required?</label>
                <select name="quotation_required" class="form-select">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Expected Business Value</label>
                <input type="number" name="expected_business_value" class="form-control" value="<?= $t['expected_business_value'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Follow-up Needed?</label>
                <select name="follow_up_needed" class="form-select">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Follow-up Date</label>
                <input type="date" name="follow_up_date" class="form-control" value="<?= $t['follow_up_date'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Follow-up Assigned To</label>
                <select name="follow_up_assigned_to" class="form-select">
                    <option value="">- Select Employee -</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $t['follow_up_assigned_to']==$u['id']?'selected':'' ?>><?= e($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Deal Status</label>
                <select name="deal_status" class="form-select">
                    <option value="Open">Open</option>
                    <option value="Negotiation">Negotiation</option>
                    <option value="Won">Won</option>
                    <option value="Lost">Lost</option>
                </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Outcome</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Update Expense -->
<div class="modal fade" id="expenseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="action" value="update_expense">
        <div class="modal-header">
          <h5 class="modal-title">Actual Expense Reporting</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Actual Expense Amount</label>
                <input type="number" name="actual_expense_amount" class="form-control" value="<?= $t['actual_expense_amount'] ?>" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label">Payment Done By</label>
                <select name="payment_done_by" class="form-select">
                    <option value="Employee">Employee</option>
                    <option value="Company">Company</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Reimbursement Req.</label>
                <select name="reimbursement_required" class="form-select">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Expense Date</label>
                <input type="date" name="expense_date" class="form-control" value="<?= $t['expense_date'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Card">Card</option>
                    <option value="Bank">Bank</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Vendor Name</label>
                <input type="text" name="expense_vendor_name" class="form-control" value="<?= e($t['expense_vendor_name']) ?>">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Expenses</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
