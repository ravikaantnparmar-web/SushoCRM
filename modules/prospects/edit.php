<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();
requirePermission('prospects', 'edit');
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
  header('Location: index.php');
  exit;
}

$stmt = db()->prepare("SELECT l.*, u.name as assigned_name, c.name as creator_name FROM leads l LEFT JOIN users u ON l.assigned_to = u.id LEFT JOIN users c ON l.created_by = c.id WHERE l.id = ?");
$stmt->execute([$id]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
  setFlash('danger', 'Lead not found.');
  header('Location: index.php');
  exit;
}

// Fetch related data
$stmtCont = db()->prepare("
  SELECT c.*, cr.role AS relation_role, cr.is_primary
  FROM contacts c
  JOIN contact_relations cr ON c.id = cr.contact_id
  WHERE cr.entity_type = 'lead' AND cr.entity_id = ?
  ORDER BY cr.is_primary DESC, cr.id ASC
");
$stmtCont->execute([$id]);
$contacts = $stmtCont->fetchAll();
$stmtAddr = db()->prepare("SELECT * FROM lead_addresses WHERE lead_id = ? ORDER BY is_primary DESC, id ASC"); $stmtAddr->execute([$id]); $addresses = $stmtAddr->fetchAll();
$stmtProd = db()->prepare("SELECT product_name FROM lead_interested_products WHERE lead_id = ?"); $stmtProd->execute([$id]); $products = $stmtProd->fetchAll(PDO::FETCH_COLUMN);
$stmtMeet = db()->prepare("SELECT * FROM lead_meetings WHERE lead_id = ? ORDER BY created_at DESC"); $stmtMeet->execute([$id]); $meetings = $stmtMeet->fetchAll();
$stmtDoc = db()->prepare("SELECT * FROM lead_documents WHERE lead_id = ? ORDER BY created_at DESC"); $stmtDoc->execute([$id]); $documents = $stmtDoc->fetchAll();
$stmtTime = db()->prepare("SELECT t.*, u.name as user_name FROM lead_timeline t LEFT JOIN users u ON t.user_id = u.id WHERE t.lead_id = ? ORDER BY t.created_at DESC"); $stmtTime->execute([$id]); $timeline = $stmtTime->fetchAll();

$users = getAllUsers();
$pageTitle = 'Edit Lead: ' . $lead['lead_code'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
  <div class="topbar">
    <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
    <div class="topbar-title">Edit Lead: <?= e($lead['lead_code']) ?></div>
  </div>

  <style>
    /* ============================================================
   LEAD EDIT FORM — Two-Column Picture-Match Layout
   ============================================================ */
    :root {
      --lp: #5b6ef5;
      /* primary accent */
      --lp-light: #eef0ff;
      --ls-border: #e4e7ec;
      --ls-bg: #f9fafb;
      --ls-header-txt: #374151;
      --ls-label: #6b7280;
      --radius-card: 10px;
    }

    /* ---- Page header strip ---- */
    .lead-edit-topstrip {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 22px 10px;
      background: #fff;
      border-bottom: 1px solid var(--ls-border);
      position: sticky;
      top: 0;
      z-index: 200;
    }

    .lead-edit-topstrip h1 {
      font-size: 17px;
      font-weight: 700;
      color: #1f2937;
      margin: 0;
    }

    .lead-edit-topstrip .strip-actions {
      display: flex;
      gap: 10px;
    }

    /* ---- Two-column wrapper ---- */
    .lead-edit-outer {
      display: flex;
      gap: 18px;
      padding: 18px 22px 40px;
      align-items: flex-start;
    }

    .lead-left-col {
      flex: 1 1 0;
      min-width: 0;
    }

    .lead-right-col {
      width: 340px;
      flex-shrink: 0;
    }

    /* ---- Section card ---- */
    .ls-card {
      background: #fff;
      border: 1px solid var(--ls-border);
      border-radius: var(--radius-card);
      margin-bottom: 16px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    }

    .ls-card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      background: var(--ls-bg);
      border-bottom: 1px solid var(--ls-border);
      cursor: pointer;
      user-select: none;
    }

    .ls-card-header .sec-ico {
      width: 32px;
      height: 32px;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      flex-shrink: 0;
    }

    .ls-card-header h6 {
      margin: 0;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .6px;
      text-transform: uppercase;
      color: var(--ls-header-txt);
    }

    .ls-card-header .ls-toggle {
      margin-left: auto;
      font-size: 13px;
      color: #9ca3af;
    }

    .ls-card-body {
      padding: 18px;
    }

    .ls-card-body.collapsed {
      display: none;
    }

    /* Section icon colours */
    .ico-indigo {
      background: #eef0ff;
      color: #5b6ef5;
    }

    .ico-green {
      background: #ecfdf5;
      color: #059669;
    }

    .ico-orange {
      background: #fff7ed;
      color: #ea580c;
    }

    .ico-purple {
      background: #fdf4ff;
      color: #9333ea;
    }

    .ico-teal {
      background: #f0fdfa;
      color: #0d9488;
    }

    .ico-amber {
      background: #fffbeb;
      color: #d97706;
    }

    .ico-red {
      background: #fef2f2;
      color: #dc2626;
    }

    .ico-blue {
      background: #eff6ff;
      color: #2563eb;
    }

    /* ---- F2 Master badge ---- */
    .f2-badge {
      display: inline-flex;
      align-items: center;
      gap: 3px;
      font-size: 9px;
      font-weight: 700;
      color: #6b7280;
      background: #f3f4f6;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      padding: 1px 5px;
      vertical-align: middle;
      margin-left: 4px;
      letter-spacing: .3px;
    }

    .f2-badge i {
      font-size: 8px;
    }

    /* ---- Form controls ---- */
    .form-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--ls-label);
      letter-spacing: .4px;
      text-transform: uppercase;
      margin-bottom: 5px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .form-control,
    .form-select {
      font-size: 13px;
      border-color: #d1d5db;
      border-radius: 7px;
      background: #fff;
      color: #1f2937;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--lp);
      box-shadow: 0 0 0 3px rgba(91, 110, 245, .12);
    }

    .form-control[readonly],
    .form-control:disabled {
      background: #f3f4f6;
      color: #6b7280;
      cursor: not-allowed;
    }

    .input-group-text {
      font-size: 13px;
      background: #f9fafb;
      border-color: #d1d5db;
    }

    /* ---- Section sub-divider ---- */
    .ls-sub-label {
      font-size: 10px;
      font-weight: 700;
      color: #9ca3af;
      letter-spacing: .8px;
      text-transform: uppercase;
      padding: 6px 0 4px;
      border-bottom: 1px solid #f3f4f6;
      /* margin-bottom: 12px; */
    }

    /* ---- Contact person table ---- */
    .contact-table-wrap {
      overflow-x: auto;
    }

    .contact-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .contact-table th {
      font-size: 10px;
      font-weight: 700;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: .5px;
      padding: 9px 10px;
      background: #f9fafb;
      border-bottom: 2px solid var(--ls-border);
      white-space: nowrap;
    }

    .contact-table td {
      padding: 7px 6px;
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
      background: #fff;
    }

    .contact-table tr:last-child td {
      border-bottom: none;
    }

    /* Primary row: subtle left accent, no background flood */
    .contact-table tr.contact-primary-row td {
      background: #fff;
      border-top: 1px solid #e0e7ff;
      border-bottom: 1px solid #e0e7ff;
    }

    .contact-table tr.contact-primary-row td:first-child {
      border-left: 3px solid var(--lp);
    }

    /* Always white inputs regardless of row */
    .contact-table .form-control,
    .contact-table .form-select {
      font-size: 12px;
      padding: 5px 8px;
      border-radius: 6px;
      background: #fff !important;
      color: #1f2937 !important;
      height: 34px;
      min-height: 34px;
    }

    .primary-badge-tag {
      display: inline-block;
      font-size: 9px;
      font-weight: 700;
      color: var(--lp);
      background: var(--lp-light);
      border: 1px solid #c7d2fe;
      border-radius: 10px;
      padding: 0px 6px;
      letter-spacing: .3px;
      white-space: nowrap;
      vertical-align: middle;
      margin-left: 4px;
    }

    .ct-add-btn {
      display: flex;
      gap: 8px;
      align-items: center;
      margin-bottom: 12px;
    }

    .ct-add-btn .btn {
      font-size: 12px;
    }

    .primary-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--lp);
      display: inline-block;
    }

    /* ---- Right sidebar panels ---- */
    .ls-right-panel {
      background: #fff;
      border: 1px solid var(--ls-border);
      border-radius: var(--radius-card);
      margin-bottom: 16px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    }

    .ls-right-panel-header {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 11px 16px;
      background: var(--ls-bg);
      border-bottom: 1px solid var(--ls-border);
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: var(--ls-header-txt);
    }

    .ls-right-panel-header i {
      color: var(--lp);
    }

    .ls-right-panel-body {
      padding: 14px 16px;
    }

    /* Product checkboxes */
    .prod-check-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .prod-check-list li {
      padding: 5px 0;
      border-bottom: 1px solid #f3f4f6;
    }

    .prod-check-list li:last-child {
      border-bottom: none;
    }

    .prod-check-list .form-check-label {
      font-size: 13px;
      color: #374151;
      cursor: pointer;
    }

    .prod-check-list .form-check-input:checked {
      background-color: var(--lp);
      border-color: var(--lp);
    }

    /* Doc upload zone */
    .doc-upload-zone {
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      padding: 20px 12px;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
      background: #fafbff;
    }

    .doc-upload-zone:hover {
      border-color: var(--lp);
      background: var(--lp-light);
    }

    .doc-upload-zone .du-icon {
      font-size: 28px;
      color: #9ca3af;
      margin-bottom: 6px;
    }

    .doc-upload-zone .du-text {
      font-size: 12px;
      font-weight: 600;
      color: #374151;
    }

    .doc-upload-zone .du-sub {
      font-size: 11px;
      color: #9ca3af;
    }

    .doc-action-btns {
      display: flex;
      gap: 8px;
      justify-content: center;
      margin-top: 10px;
    }

    .doc-action-btns .btn {
      font-size: 12px;
      padding: 5px 12px;
    }

    .saved-docs-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 10px;
    }

    .saved-doc-thumb {
      width: 64px;
      height: 56px;
      border: 1px solid #e4e7ec;
      border-radius: 6px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f9fafb;
      font-size: 10px;
      color: #6b7280;
      flex-direction: column;
    }

    .saved-doc-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Meeting log items */
    .meeting-log {
      background: #f9fafb;
      border: 1px solid var(--ls-border);
      border-radius: 7px;
      padding: 10px 12px;
      margin-bottom: 8px;
    }

    .meeting-log .ml-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .meeting-log .ml-type {
      font-size: 12px;
      font-weight: 700;
      color: #374151;
    }

    .meeting-log .ml-badge {
      font-size: 10px;
    }

    .meeting-log .ml-meta {
      font-size: 11px;
      color: #6b7280;
      margin-top: 3px;
    }

    /* ---- Sticky bottom bar ---- */
    .ls-sticky-bar {
      position: sticky;
      bottom: 0;
      background: #fff;
      border-top: 1px solid var(--ls-border);
      padding: 12px 22px;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      z-index: 150;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, .07);
    }

    /* ---- Contact extra fields row ---- */
    .contact-extra-row {
      background: #f9fafb;
      border-top: 1px solid #f0f0f0;
      padding: 10px 8px 8px;
      border-radius: 0 0 6px 6px;
    }

    /* ---- Primary contact badge ---- */
    .primary-badge {
      font-size: 10px;
      background: var(--lp-light);
      color: var(--lp);
      border: 1px solid var(--lp);
      border-radius: 20px;
      padding: 1px 7px;
      font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .lead-edit-outer {
        flex-direction: column;
      }

      .lead-right-col {
        width: 100%;
      }
    }
  </style>

  <div class="page-content pb-0">
    <?= flashHtml() ?>

    <form id="lead-edit-form" action="update.php" method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">

      <!-- Top strip with title + action buttons -->
      <div class="lead-edit-topstrip">
        <h1><i class="bi bi-pencil-square me-2 text-primary" style="font-size:15px;"></i>Edit Lead:
          <?= e($lead['lead_code']) ?>
        </h1>
        <div class="strip-actions">
          <a href="view.php?id=<?= $id ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x me-1"></i>Cancel
          </a>
          <button type="submit" class="btn btn-sm btn-primary fw-semibold px-4" id="submit-btn">
            <i class="bi bi-check2-circle me-1"></i>Update Lead
          </button>
        </div>
      </div>

      <div class="lead-edit-outer">

        <!-- ====================================================
         LEFT COLUMN
         ==================================================== -->
        <div class="lead-left-col">

          <!-- ─── SECTION 1: LEAD MASTER INFO ─── -->
          <div class="ls-card">
            <div class="ls-card-header" onclick="toggleSection(this)">
              <div class="sec-ico ico-indigo"><i class="bi bi-star-fill"></i></div>
              <h6>Lead Master Information</h6>
              <span class="badge bg-primary ms-2" style="font-size:10px;">Required</span>
              <i class="bi bi-chevron-up ls-toggle"></i>
            </div>
            <div class="ls-card-body">
              <div class="row g-3">
                <!-- Lead ID (read-only) -->
                <div class="col-md-3">
                  <label class="form-label">Lead ID</label>
                  <input type="text" class="form-control" value="<?= e($lead['lead_code']) ?>" readonly>
                </div>
                <!-- Lead Date -->
                <div class="col-md-3">
                  <label class="form-label">Lead Date <span class="text-danger">*</span></label>
                  <input type="date" name="lead_date" class="form-control" value="<?= e($lead['lead_date']) ?>"
                    required>
                </div>
                <!-- Lead Status -->
                <div class="col-md-6">
                  <label class="form-label">
                    Lead Status
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="lead_status" class="form-select">
                    <?php foreach ($leadStatuses as $s): ?>
                      <option value="<?= e($s) ?>" <?= $lead['lead_status'] == $s ? 'selected' : '' ?>><?= e($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Status Description -->
                <div class="col-12">
                  <label class="form-label">Status Description</label>
                  <input type="text" name="status_description" class="form-control"
                    placeholder="Short description of current status"
                    value="<?= e($lead['status_description'] ?? '') ?>">
                </div>
                <!-- Site Stage -->
                <div class="col-md-4">
                  <label class="form-label">
                    Site Stage
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="site_stage" class="form-select">
                    <option value="">Select Stage</option>
                    <?php foreach ($siteStages as $ss): ?>
                      <option value="<?= e($ss) ?>" <?= $lead['site_stage'] == $ss ? 'selected' : '' ?>><?= e($ss) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Project Type -->
                <div class="col-md-4">
                  <label class="form-label">Project Type</label>
                  <select name="project_type" class="form-select">
                    <option value="">Select Project Type</option>
                    <?php foreach ($projectTypes as $pt): ?>
                      <option value="<?= e($pt) ?>" <?= $lead['project_type'] == $pt ? 'selected' : '' ?>><?= e($pt) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Lead Source -->
                <div class="col-md-4">
                  <label class="form-label">
                    Lead Source
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="lead_source" class="form-select">
                    <option value="">Select Source</option>
                    <?php foreach ($leadSources as $s): ?>
                      <option value="<?= e($s) ?>" <?= $lead['lead_source'] == $s ? 'selected' : '' ?>><?= e($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Lead Priority -->
                <div class="col-md-4">
                  <label class="form-label">
                    Lead Priority
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="lead_priority" class="form-select">
                    <?php foreach ($leadPriorities as $p): ?>
                      <option value="<?= e($p) ?>" <?= $lead['lead_priority'] == $p ? 'selected' : '' ?>><?= e($p) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Assign To -->
                <div class="col-md-4">
                  <label class="form-label">Assign To</label>
                  <select name="assigned_to" class="form-select">
                    <option value="">Select Employee</option>
                    <?php foreach ($users as $u): ?>
                      <option value="<?= $u['id'] ?>" <?= $lead['assigned_to'] == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Expected Closing Date -->
                <div class="col-md-4">
                  <label class="form-label">Expected Project Closing Date</label>
                  <input type="datetime-local" name="next_followup_date" class="form-control"
                    value="<?= $lead['next_followup_date'] ? date('Y-m-d\TH:i', strtotime($lead['next_followup_date'])) : '' ?>">
                </div>
              </div>
            </div>
          </div><!-- /ls-card lead master -->

          <!-- ─── SECTION 2: COMPANY INFORMATION ─── -->
          <div class="ls-card">
            <div class="ls-card-header" onclick="toggleSection(this)">
              <div class="sec-ico ico-green"><i class="bi bi-building"></i></div>
              <h6>Company Information</h6>
              <i class="bi bi-chevron-up ls-toggle"></i>
            </div>
            <div class="ls-card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Company Name <span class="text-danger">*</span></label>
                  <input type="text" name="company_name" class="form-control" placeholder="Enter company or client name"
                    value="<?= e($lead['company_name']) ?>" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">
                    Company Type
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="company_type" class="form-select">
                    <option value="">Select Type</option>
                    <?php foreach ($companyTypes as $ct): ?>
                      <option value="<?= e($ct) ?>" <?= $lead['company_type'] == $ct ? 'selected' : '' ?>><?= e($ct) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Company Status</label>
                  <select name="company_status" class="form-select">
                    <?php foreach ($companyStatuses as $cs): ?>
                      <option value="<?= e($cs) ?>" <?= $lead['company_status'] == $cs ? 'selected' : '' ?>><?= e($cs) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">
                    Industry Type
                    <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
                  </label>
                  <select name="industry_type" class="form-select">
                    <option value="">Select Industry</option>
                    <?php foreach ($industryTypes as $it): ?>
                      <option value="<?= e($it) ?>" <?= $lead['industry_type'] == $it ? 'selected' : '' ?>><?= e($it) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">GST Number</label>
                  <input type="text" name="gst_number" class="form-control" placeholder="22AAAAA0000A1Z5" maxlength="20"
                    value="<?= e($lead['gst_number']) ?>">
                </div>
                <div class="col-md-4">
                  <label class="form-label">TIN Number</label>
                  <input type="text" name="tin_number" class="form-control" placeholder="Enter TIN Number"
                    maxlength="50" value="<?= e($lead['tin_number'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email Address</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="company_email" class="form-control" placeholder="company@email.com"
                      value="<?= e($lead['company_email']) ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Website</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-globe text-muted"></i></span>
                    <input type="url" name="company_website" class="form-control" placeholder="https://example.com"
                      value="<?= e($lead['company_website']) ?>">
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /ls-card company -->

          <!-- ─── SECTION 3: ADDRESS INFORMATION ─── -->
          <div class="ls-card">
            <div class="ls-card-header" onclick="toggleSection(this)">
              <div class="sec-ico ico-orange"><i class="bi bi-geo-alt-fill"></i></div>
              <h6>Address Information</h6>
              <span class="badge ms-2" style="background:#ea580c;font-size:10px;" id="address-count-badge">
                <?= count($addresses) ?> Address(es)
              </span>
              <div class="ms-auto d-flex gap-2" onclick="event.stopPropagation()">
                <button type="button" class="btn btn-sm btn-outline-warning text-dark" onclick="addAddress()"
                  style="font-size:12px; border-color:#ea580c;">
                  <i class="bi bi-plus-lg me-1"></i>Add Address
                </button>
              </div>
              <i class="bi bi-chevron-up ls-toggle ms-2"></i>
            </div>
            <div class="ls-card-body" style="padding: 10px; background: #f9fafb;" id="addresses-container">

              <?php foreach ($addresses as $idx => $addr): ?>
                <div class="address-card p-3 bg-white border rounded mb-3 position-relative shadow-sm"
                  data-address-index="<?= $idx ?>">
                  <?php if (!$addr['is_primary']): ?>
                    <button type="button" class="btn btn-sm btn-outline-danger position-absolute remove-address-btn"
                      onclick="removeAddress(this)" style="top: 10px; right: 10px; font-size: 11px;">
                      <i class="bi bi-trash"></i> Remove
                    </button>
                  <?php endif; ?>

                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label text-orange">
                        <i class="bi bi-tag-fill me-1"></i>Address Type
                      </label>
                      <select class="form-select address-type-input" name="addresses[<?= $idx ?>][address_type]">
                        <option value="">Select Type</option>
                        <?php foreach ($addressTypes as $at): ?>
                          <option value="<?= e($at) ?>" <?= $addr['address_type'] == $at ? 'selected' : '' ?>><?= e($at) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex align-items-center h-100 pt-4">
                        <div class="form-check form-switch">
                          <input class="form-check-input primary-address-check" type="checkbox" role="switch"
                            name="addresses[<?= $idx ?>][is_primary]" value="1" onchange="handlePrimaryAddress(this)"
                            <?= $addr['is_primary'] ? 'checked' : '' ?>>
                          <label class="form-check-label text-muted" style="font-size:12px;">Set as Primary
                            Address</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Address Line 1</label>
                      <input type="text" class="form-control addr-line1" name="addresses[<?= $idx ?>][address_line1]"
                        placeholder="House / Flat / Building No." value="<?= e($addr['address_line1']) ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Address Line 2</label>
                      <input type="text" class="form-control addr-line2" name="addresses[<?= $idx ?>][address_line2]"
                        placeholder="Street, Road, Colony" value="<?= e($addr['address_line2']) ?>">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Area</label>
                      <input type="text" class="form-control addr-area" name="addresses[<?= $idx ?>][area]"
                        placeholder="Area / Locality" value="<?= e($addr['area']) ?>">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">City</label>
                      <input type="text" class="form-control addr-city" name="addresses[<?= $idx ?>][city]"
                        placeholder="City" value="<?= e($addr['city']) ?>">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">State</label>
                      <select class="form-select addr-state" name="addresses[<?= $idx ?>][state]">
                        <option value="">Select State</option>
                        <?php foreach (['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi', 'Ladakh', 'Puducherry', 'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Lakshadweep', 'Andaman and Nicobar Islands'] as $st): ?>
                          <option value="<?= e($st) ?>" <?= $addr['state'] == $st ? 'selected' : '' ?>><?= e($st) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Pincode</label>
                      <input type="text" class="form-control addr-pincode" name="addresses[<?= $idx ?>][pincode]"
                        placeholder="000000" maxlength="6" pattern="[0-9]{6}" value="<?= e($addr['pincode']) ?>">
                    </div>

                    <div class="col-12">
                      <div class="ls-sub-label">Google Map Location (Optional)</div>
                    </div>
                    <div class="col-12">
                      <div class="d-flex align-items-center gap-2">
                        <div class="input-group shadow-sm" style="flex:1 1 0;min-width:0;">
                          <span class="input-group-text bg-white border-end-0 pe-1"><i
                              class="bi bi-geo-alt-fill text-danger" style="font-size:13px;"></i></span>
                          <input type="text" class="form-control addr-gsearch border-start-0"
                            placeholder="Search address or click Locate Me to use GPS..."
                            value="<?= e($addr['google_address']) ?>">
                          <button type="button"
                            class="btn btn-outline-primary btn-fetch-location d-flex align-items-center gap-1 px-3"
                            onclick="fetchLocationForCard(this)" title="Fetch Current Location">
                            <i class="bi bi-geo-fill"></i> <span class="small fw-semibold">Locate Me</span>
                          </button>
                        </div>
                        <div
                          class="addr-preview-container flex-shrink-0 <?= empty($addr['google_address']) ? 'd-none' : '' ?>"
                          style="max-width:42%;">
                          <div class="p-2 bg-light rounded border small d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                            <span class="addr-preview-text text-success fw-medium text-truncate"
                              style="max-width:130px;"><?= e($addr['google_address']) ?></span>
                            <a href="<?= e($addr['google_maps_link']) ?: '#' ?>" target="_blank"
                              class="btn btn-sm btn-outline-success addr-preview-map-link flex-shrink-0 <?= empty($addr['google_maps_link']) ? 'd-none' : '' ?>"
                              style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i
                                class="bi bi-map-fill me-1"></i>Map</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= !empty($addr['lat']) && !empty($addr['lng']) ? $addr['lat'] . ',' . $addr['lng'] : urlencode($addr['google_address']) ?>"
                              target="_blank" class="btn btn-sm btn-outline-primary addr-preview-dir-link flex-shrink-0"
                              style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i
                                class="bi bi-cursor-fill me-1"></i>Directions</a>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" class="addr-gaddress" name="addresses[<?= $idx ?>][google_address]"
                        value="<?= e($addr['google_address']) ?>">
                      <input type="hidden" class="addr-glink" name="addresses[<?= $idx ?>][google_maps_link]"
                        value="<?= e($addr['google_maps_link']) ?>">
                      <input type="hidden" class="addr-lat" name="addresses[<?= $idx ?>][lat]"
                        value="<?= e($addr['lat']) ?>">
                      <input type="hidden" class="addr-lng" name="addresses[<?= $idx ?>][lng]"
                        value="<?= e($addr['lng']) ?>">
                      <input type="hidden" class="addr-gcode" name="addresses[<?= $idx ?>][google_location]"
                        value="<?= e($addr['google_location']) ?>">
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>

            </div>
          </div><!-- /address -->

          <!-- ─── SECTION 4: CONTACT PERSON MANAGEMENT ─── -->
          <div class="ls-card" style="padding:20px">
            <div class="ls-card-header" onclick="toggleSection(this)">
              <div class="sec-ico ico-purple"><i class="bi bi-people-fill"></i></div>
              <h6>Contact Person Management</h6>
              <span class="badge ms-2" style="background:#9333ea;font-size:10px;" id="contact-count-badge">
                <?= count($contacts) ?> Contact(s)
              </span>
              <!-- Action buttons in header -->
              <div class="ms-auto d-flex gap-2" onclick="event.stopPropagation()">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showAddContactModal()"
                  style="font-size:12px;">
                  <i class="bi bi-plus-lg me-1"></i>Add Contact
                </button>
                <button type="button" class="btn btn-sm btn-success"
                  onclick="document.getElementById('submit-btn').click()" style="font-size:12px;">
                  <i class="bi bi-floppy me-1"></i>Save Lead
                </button>
              </div>
              <i class="bi bi-chevron-up ls-toggle ms-2"></i>
            </div>
            <div class="ls-card-body" style="padding:0;">
              <div class="table-responsive">
                <table class="table table-hover table-contacts mb-0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Contact Details</th>
                      <th>Visiting Card</th>
                      <th style="width: 100px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($contacts as $c): ?>
                    <tr>
                      <td>
                        <div class="fw-bold"><?= e($c['name']) ?> <?= $c['is_primary']?'<span class="badge bg-primary ms-1" style="font-size:9px;">PRIMARY</span>':'' ?></div>
                      </td>
                      <td><span class="badge bg-light text-dark border"><?= e($c['contact_type'] ?? $c['relation_role'] ?? 'Owner') ?></span></td>
                      <td>
                        <?php if($c['mobile']): ?>
                          <div class="small"><i class="bi bi-telephone text-muted me-1"></i><a href="tel:<?= e($c['mobile']) ?>"><?= e($c['mobile']) ?></a></div>
                        <?php endif; ?>
                        <?php if($c['whatsapp']): ?>
                          <div class="small"><i class="bi bi-whatsapp text-success me-1"></i><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$c['whatsapp']) ?>" target="_blank" class="text-success"><?= e($c['whatsapp']) ?></a></div>
                        <?php endif; ?>
                        <?php if($c['email']): ?>
                          <div class="small"><i class="bi bi-envelope text-muted me-1"></i><a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a></div>
                        <?php endif; ?>

                        <?php if(!empty($c['organization_name']) || !empty($c['city']) || !empty($c['website'])): ?>
                          <div class="mt-2 pt-2 border-top border-light">
                            <?php if($c['organization_name']): ?>
                              <div class="small text-muted"><i class="bi bi-building me-1"></i><strong><?= e($c['organization_name']) ?></strong></div>
                            <?php endif; ?>
                            <?php if($c['address'] || $c['city'] || $c['state'] || $c['pincode']): ?>
                              <div class="small text-muted" style="font-size:11px;">
                                <i class="bi bi-geo-alt me-1"></i>
                                <?= implode(', ', array_filter([e($c['address']), e($c['city']), e($c['state']), e($c['pincode'])])) ?>
                              </div>
                            <?php endif; ?>
                            <?php if($c['website']): ?>
                              <div class="small"><i class="bi bi-globe text-muted me-1"></i><a href="<?= e($c['website']) ?>" target="_blank" class="text-decoration-none" style="font-size:11px;">Website</a></div>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php 
                        if($c['visiting_card']): 
                          $cards = json_decode($c['visiting_card'], true) ?: [];
                          if (!empty($cards)):
                        ?>
                        <div class="d-flex gap-1 flex-wrap">
                          <?php foreach($cards as $card): ?>
                            <a href="<?= BASE_URL ?>/<?= e($card) ?>" target="_blank" class="visiting-card-thumb-link">
                              <img src="<?= BASE_URL ?>/<?= e($card) ?>" class="rounded border" style="width: 50px; height: 35px; object-fit: cover;" alt="Visiting Card">
                            </a>
                          <?php endforeach; ?>
                        </div>
                        <?php else: echo '-'; endif; else: echo '-'; endif; ?>
                      </td>
                      <td>
                        <div class="d-flex flex-column gap-1">
                          <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>/modules/contacts/view.php?id=<?= $c['id'] ?>" class="btn btn-outline-info btn-sm px-2 py-1" title="View Profile"><i class="bi bi-eye"></i></a>
                            <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1" onclick="editContactById(<?= $c['id'] ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1" onclick="deleteContact(<?= $c['id'] ?>)" title="Delete"><i class="bi bi-trash"></i></button>
                          </div>
                          <?php if(!$c['is_primary']): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="setPrimaryContact(<?= $c['id'] ?>)" style="font-size: 10px;">Set as Primary</button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($contacts)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">No contacts found. Please add a contact person.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div><!-- /ls-card contacts -->

          <!-- ─── SECTION 5: LEAD MEET (full width on left for meetings log) ─── -->
          <div class="ls-card">
            <div class="ls-card-header" onclick="toggleSection(this)">
              <div class="sec-ico ico-amber"><i class="bi bi-calendar-check-fill"></i></div>
              <h6>Lead Meet &amp; Visits</h6>
              <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">
                <?= count($meetings) ?> Meeting(s)
              </span>
              <i class="bi bi-chevron-up ls-toggle"></i>
            </div>
            <div class="ls-card-body">
              <?php if (!empty($meetings)): ?>
                <label class="form-label text-muted mb-2">Previous Meetings</label>
                <?php foreach ($meetings as $m): ?>
                  <div class="meeting-log">
                    <div class="ml-head">
                      <span class="ml-type"><?= e($m['type']) ?></span>
                      <span class="badge bg-secondary ml-badge"><?= e($m['status']) ?></span>
                    </div>
                    <div class="ml-meta">With: <?= e($m['meeting_with']) ?></div>
                    <div class="ml-meta"><?= e($m['purpose']) ?></div>
                    <div class="ml-meta" style="font-size:10px;"><i
                        class="bi bi-clock me-1"></i><?= formatDateTime($m['created_at']) ?></div>
                  </div>
                <?php endforeach; ?>
                <hr class="my-3">
              <?php endif; ?>

              <label class="form-label text-primary fw-semibold mb-3">Record New Meeting (Optional)</label>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Meeting With (Contact Person)</label>
                  <select name="meeting_with_name" id="meeting-with-contact" class="form-select">
                    <option value="">Select Contact Person</option>
                    <?php foreach ($contacts as $c):
                      if (!empty($c['name'])): ?>
                        <option value="<?= e($c['name']) ?>"><?= e($c['name']) ?></option>
                      <?php endif; endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Meeting Type</label>
                  <select name="meeting_type" class="form-select">
                    <?php foreach ($meetingTypes as $mt): ?>
                      <option value="<?= e($mt) ?>"><?= e($mt) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Meeting Priority</label>
                  <select name="meeting_status" class="form-select">
                    <?php foreach ($meetingStatuses as $ms): ?>
                      <option value="<?= e($ms) ?>" <?= $ms == 'Scheduled' ? 'selected' : '' ?>><?= e($ms) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Sales Stage</label>
                  <select name="sales_stage" class="form-select">
                    <option value="">Select Stage</option>
                    <?php foreach ($salesStages as $ss): ?>
                      <option value="<?= e($ss) ?>"><?= e($ss) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Next Follow-up After Meeting</label>
                  <input type="datetime-local" name="meeting_followup_date" class="form-control">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Update Lead Status</label>
                  <select name="meeting_lead_status" class="form-select">
                    <option value="">No Change</option>
                    <?php foreach ($leadStatuses as $ls): ?>
                      <option value="<?= e($ls) ?>"><?= e($ls) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Meeting Purpose / Summary</label>
                  <textarea name="meeting_purpose" class="form-control" rows="2"
                    placeholder="What was discussed?"></textarea>
                </div>
              </div>
            </div>
          </div><!-- /ls-card lead meet -->

        </div><!-- /.lead-left-col -->

        <!-- ====================================================
         RIGHT COLUMN / SIDEBAR
         ==================================================== -->
        <div class="lead-right-col">

          <!-- ── PANEL: PRODUCT / REQUIREMENT ── -->
          <div class="ls-right-panel">
            <div class="ls-right-panel-header">
              <i class="bi bi-box-seam-fill"></i>
              Product / Requirement
            </div>
            <div class="ls-right-panel-body">
              <label class="form-label mb-2">Interested Products</label>
              <ul class="prod-check-list">
                <?php foreach ($interestedProducts as $ip):
                  $isChecked = in_array($ip, $products); ?>
                  <li>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="interested_products[]" value="<?= e($ip) ?>"
                        id="prod_<?= md5($ip) ?>" <?= $isChecked ? 'checked' : '' ?>>
                      <label class="form-check-label" for="prod_<?= md5($ip) ?>">
                        <?= e($ip) ?>
                      </label>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>

              <hr class="my-3">

              <div class="mb-3">
                <label class="form-label">Product Type</label>
                <select name="product_type" class="form-select form-select-sm">
                  <option value="">Select Product Type</option>
                  <?php foreach ($productTypes as $pt): ?>
                    <option value="<?= e($pt) ?>" <?= $lead['product_type'] == $pt ? 'selected' : '' ?>><?= e($pt) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Estimated Budget (₹)</label>
                <div class="input-group input-group-sm">
                  <span class="input-group-text text-muted">₹</span>
                  <input type="number" name="estimated_budget" class="form-control" placeholder="0" min="0" step="1000"
                    value="<?= e($lead['estimated_budget']) ?>">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Purchase Timeline</label>
                <input type="text" name="purchase_timeline" class="form-control form-control-sm"
                  placeholder="e.g. Within 3 months" value="<?= e($lead['purchase_timeline']) ?>">
              </div>

              <div class="mb-1">
                <label class="form-label">Requirement Description</label>
                <textarea name="requirement_description" class="form-control form-control-sm" rows="4"
                  placeholder="Describe client's requirements..."><?= e($lead['requirement_description']) ?></textarea>
              </div>

              <div class="mt-3">
                <label class="form-label">Competitor Info</label>
                <input type="text" name="competitor_info" class="form-control form-control-sm"
                  placeholder="Competing brands / vendors" value="<?= e($lead['competitor_info']) ?>">
              </div>
            </div>
          </div><!-- /product panel -->


          <!-- ── PANEL: SITE MEDIA & DOCUMENTS ── -->
          <div class="ls-right-panel">
            <div class="ls-right-panel-header">
              <i class="bi bi-folder2-open"></i>
              Site Media &amp; Documents
            </div>
            <div class="ls-right-panel-body">

              <!-- Drop zone -->
              <div class="doc-upload-zone" id="doc-drop-zone"
                onclick="document.getElementById('doc-file-input').click()">
                <div class="du-icon"><i class="bi bi-cloud-upload"></i></div>
                <div class="du-text">Drop files here or click to upload</div>
                <div class="du-sub">Support for JPG, PNG, PDF (Max 5MB)</div>
              </div>
              <input type="file" id="doc-file-input" name="documents[]" multiple
                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="d-none" onchange="previewDocs(this)">

              <div class="doc-action-btns">
                <label class="btn btn-sm btn-outline-primary mb-0">
                  <i class="bi bi-folder2 me-1"></i>Browse
                  <input type="file" name="camera_photos[]" multiple accept="image/*" class="d-none"
                    onchange="previewDocs(this,'cam-previews')">
                </label>
                <label class="btn btn-sm btn-outline-secondary mb-0">
                  <i class="bi bi-camera me-1"></i>Take Photo
                  <input type="file" name="camera_photos_2[]" multiple accept="image/*" capture="environment"
                    class="d-none" onchange="previewDocs(this,'cam-previews')">
                </label>
              </div>

              <div class="doc-previews-small d-flex flex-wrap gap-1 mt-2" id="doc-previews"></div>
              <div class="doc-previews-small d-flex flex-wrap gap-1 mt-1" id="cam-previews"></div>

              <div class="mt-3">
                <button type="button" class="btn btn-success btn-sm w-100 fw-semibold"
                  onclick="document.getElementById('submit-btn').click()">
                  <i class="bi bi-floppy me-1"></i>Save Documents Now
                </button>
              </div>

              <?php if (!empty($documents)): ?>
                <hr class="my-3">
                <div class="form-label mb-2">Previously Saved Documents</div>
                <div class="saved-docs-grid">
                  <?php foreach ($documents as $doc):
                    $ext = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                    $imgExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    $isImg = in_array($ext, $imgExts);
                    ?>
                    <div class="saved-doc-thumb" title="<?= e($doc['file_name']) ?>">
                      <?php if ($isImg): ?>
                        <img src="<?= BASE_URL ?>/<?= e($doc['file_path']) ?>">
                      <?php else:
                        $icon = 'file-earmark';
                        if ($ext == 'pdf')
                          $icon = 'file-pdf text-danger';
                        elseif (in_array($ext, ['doc', 'docx']))
                          $icon = 'file-word text-primary';
                        elseif (in_array($ext, ['xls', 'xlsx']))
                          $icon = 'file-excel text-success';
                        ?>
                        <i class="bi bi-<?= $icon ?> fs-3"></i>
                        <span style="font-size:9px;"><?= substr($doc['file_name'], 0, 8) ?></span>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div class="mt-3">
                <label class="form-label">Upload Remark</label>
                <input type="text" name="upload_remark" class="form-control form-control-sm"
                  placeholder="e.g. Site entrance view">
              </div>

            </div>
          </div><!-- /site media panel -->

        </div><!-- /.lead-right-col -->

      </div><!-- /.lead-edit-outer -->

      <!-- Sticky bottom submit bar -->
      <div class="ls-sticky-bar">
        <a href="view.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-x me-1"></i>Cancel
        </a>
        <button type="submit" class="btn btn-primary btn-sm px-5 fw-semibold" id="submit-btn">
          <i class="bi bi-check2-circle me-2"></i>Update Lead
        </button>
      </div>

    </form>
  </div><!-- /.page-content -->
</div><!-- /.main-content -->

<?php include __DIR__ . '/../../includes/contact_modal_ui.php'; ?>

<!-- ADDRESS CARD TEMPLATE -->
<template id="address-tpl">
  <div class="address-card p-3 bg-white border rounded mb-3 position-relative shadow-sm">
    <button type="button" class="btn btn-sm btn-outline-danger position-absolute remove-address-btn"
      onclick="removeAddress(this)" style="top: 10px; right: 10px; font-size: 11px; display: none;">
      <i class="bi bi-trash"></i> Remove
    </button>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label text-orange">
          <i class="bi bi-tag-fill me-1"></i>Address Type
        </label>
        <select class="form-select address-type-input" name="">
          <option value="">Select Type</option>
          <?php foreach ($addressTypes as $at): ?>
            <option value="<?= e($at) ?>"><?= e($at) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-8">
        <div class="d-flex align-items-center h-100 pt-4">
          <div class="form-check form-switch">
            <input class="form-check-input primary-address-check" type="checkbox" role="switch" name="" value="1"
              onchange="handlePrimaryAddress(this)">
            <label class="form-check-label text-muted" style="font-size:12px;">Set as Primary Address</label>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Address Line 1</label>
        <input type="text" class="form-control addr-line1" name="" placeholder="House / Flat / Building No.">
      </div>
      <div class="col-md-6">
        <label class="form-label">Address Line 2</label>
        <input type="text" class="form-control addr-line2" name="" placeholder="Street, Road, Colony">
      </div>
      <div class="col-md-3">
        <label class="form-label">Area</label>
        <input type="text" class="form-control addr-area" name="" placeholder="Area / Locality">
      </div>
      <div class="col-md-3">
        <label class="form-label">City</label>
        <input type="text" class="form-control addr-city" name="" placeholder="City">
      </div>
      <div class="col-md-4">
        <label class="form-label">State</label>
        <select class="form-select addr-state" name="">
          <option value="">Select State</option>
          <?php foreach (['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi', 'Ladakh', 'Puducherry', 'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Lakshadweep', 'Andaman and Nicobar Islands'] as $st): ?>
            <option value="<?= e($st) ?>"><?= e($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Pincode</label>
        <input type="text" class="form-control addr-pincode" name="" placeholder="000000" maxlength="6"
          pattern="[0-9]{6}">
      </div>

      <div class="col-12">
        <div class="ls-sub-label">Google Map Location (Optional)</div>
      </div>
      <div class="col-12">
        <div class="d-flex align-items-center gap-2">
          <div class="input-group shadow-sm" style="flex:1 1 0;min-width:0;">
            <span class="input-group-text bg-white border-end-0 pe-1"><i class="bi bi-geo-alt-fill text-danger"
                style="font-size:13px;"></i></span>
            <input type="text" class="form-control addr-gsearch border-start-0"
              placeholder="Search address or click Locate Me to use GPS...">
            <button type="button"
              class="btn btn-outline-primary btn-fetch-location d-flex align-items-center gap-1 px-3"
              onclick="fetchLocationForCard(this)" title="Fetch Current Location">
              <i class="bi bi-geo-fill"></i> <span class="small fw-semibold">Locate Me</span>
            </button>
          </div>
          <div class="addr-preview-container d-none flex-shrink-0" style="max-width:42%;">
            <div class="p-2 bg-light rounded border small d-flex align-items-center gap-1">
              <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
              <span class="addr-preview-text text-success fw-medium text-truncate" style="max-width:130px;"></span>
              <a href="#" target="_blank"
                class="btn btn-sm btn-outline-success addr-preview-map-link flex-shrink-0 d-none"
                style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i class="bi bi-map-fill me-1"></i>Map</a>
              <a href="#" target="_blank" class="btn btn-sm btn-outline-primary addr-preview-dir-link flex-shrink-0"
                style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i
                  class="bi bi-cursor-fill me-1"></i>Directions</a>
            </div>
          </div>
        </div>
        <input type="hidden" class="addr-gaddress" name="">
        <input type="hidden" class="addr-glink" name="">
        <input type="hidden" class="addr-lat" name="">
        <input type="hidden" class="addr-lng" name="">
        <input type="hidden" class="addr-gcode" name="">
      </div>
    </div>
  </div>
</template>

<style>
  /* Contact num badge */
  .contact-num-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: var(--lp);
    color: #fff;
    border-radius: 50%;
    font-size: 10px;
    font-weight: 700;
  }
</style>

<script>
// --- Global Data for Contacts ---
const leadContacts = <?= json_encode($contacts) ?>;

function editContactById(id) {
    const c = leadContacts.find(item => parseInt(item.id) === parseInt(id));
    if (c) {
        document.getElementById('contact_form_mode').value = 'update';
        showEditContactModal(c);
    }
}

function showAddContactModal() {
    document.getElementById('contactForm').reset();
    document.getElementById('contact_id').value = '';
    document.getElementById('contact_form_mode').value = 'create';
    document.getElementById('contactModalLabel').textContent = 'Add Contact';
    document.getElementById('existing_cards_preview').innerHTML = '';
    document.getElementById('visiting_cards_new_preview').innerHTML = '';
    
    Array.from(document.getElementById('contactForm').elements).forEach(el => el.disabled = false);
    
    new bootstrap.Modal(document.getElementById('contactModal')).show();
}

function showEditContactModal(c) {
    document.getElementById('contactForm').reset();
    document.getElementById('contact_id').value = c.id;
    document.getElementById('visiting_cards_new_preview').innerHTML = '';
    document.getElementById('contactModalLabel').textContent = 'Edit Contact';
    
    document.getElementById('contact_name').value = c.name || '';
    if(c.contact_type) document.getElementById('contact_contact_type').value = c.contact_type;
    document.getElementById('contact_mobile').value = c.mobile || '';
    document.getElementById('contact_whatsapp').value = c.whatsapp || '';
    document.getElementById('contact_email').value = c.email || '';
    
    document.getElementById('contact_organization_name').value = c.organization_name || '';
    document.getElementById('contact_website').value = c.website || '';
    document.getElementById('contact_address').value = c.address || '';
    document.getElementById('contact_city').value = c.city || '';
    document.getElementById('contact_state').value = c.state || '';
    document.getElementById('contact_pincode').value = c.pincode || '';
    
    document.getElementById('contact_existing_cards').value = c.visiting_card || '';
    
    const previewDiv = document.getElementById('existing_cards_preview');
    previewDiv.innerHTML = '';
    if (c.visiting_card) {
        const cards = JSON.parse(c.visiting_card) || [];
        cards.forEach((card, index) => {
            const wrap = document.createElement('div');
            wrap.className = 'position-relative d-inline-block border rounded p-1';
            wrap.style.width = '70px';
            wrap.innerHTML = `
                <img src="<?= BASE_URL ?>/${card}" style="width: 100%; height: 45px; object-fit: cover;" class="rounded">
                <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center" 
                        style="width: 16px; height: 16px; font-size: 10px; line-height: 1; border-radius: 50%;" 
                        onclick="removeExistingCard(${index})">&times;</button>
            `;
            previewDiv.appendChild(wrap);
        });
    }

    new bootstrap.Modal(document.getElementById('contactModal')).show();
}

function removeExistingCard(index) {
    const input = document.getElementById('contact_existing_cards');
    if (input.value) {
        const cards = JSON.parse(input.value) || [];
        cards.splice(index, 1);
        input.value = JSON.stringify(cards);
        
        const previewDiv = document.getElementById('existing_cards_preview');
        previewDiv.innerHTML = '';
        cards.forEach((card, idx) => {
            const wrap = document.createElement('div');
            wrap.className = 'position-relative d-inline-block border rounded p-1';
            wrap.style.width = '70px';
            wrap.innerHTML = `
                <img src="<?= BASE_URL ?>/${card}" style="width: 100%; height: 45px; object-fit: cover;" class="rounded">
                <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center" 
                        style="width: 16px; height: 16px; font-size: 10px; line-height: 1; border-radius: 50%;" 
                        onclick="removeExistingCard(${idx})">&times;</button>
            `;
            previewDiv.appendChild(wrap);
        });
    }
}

document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('contactSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    const id = document.getElementById('contact_id').value;
    const mode = document.getElementById('contact_form_mode').value;
    const url = (id && mode !== 'link_existing') ? 'update_contact_ajax.php' : 'add_contact_ajax.php';
    
    Array.from(this.elements).forEach(el => { el.disabled = false; });
    const formData = new FormData(this);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else if (data.status === 'duplicate') {
            if (confirm(data.message + "\n\nWould you like to link the existing contact (" + data.contact.name + ") to this lead instead?")) {
                let linkFormData = new FormData();
                linkFormData.append('id', data.contact.id);
                linkFormData.append('lead_id', document.querySelector('input[name="id"]').value);
                let ctEl = document.querySelector('[name="contact_type"]');
                linkFormData.append('contact_type', ctEl ? ctEl.value : 'Owner');
                linkFormData.append('mode', 'link_existing');
                
                fetch('add_contact_ajax.php', {
                    method: 'POST',
                    body: linkFormData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        window.location.reload();
                    } else {
                        alert(d.message);
                        btn.disabled = false;
                        btn.textContent = 'Save Contact';
                    }
                })
                .catch(e => {
                    alert('Failed to link contact.');
                    btn.disabled = false;
                    btn.textContent = 'Save Contact';
                });
            } else {
                btn.disabled = false;
                btn.textContent = 'Save Contact';
            }
        } else {
            alert(data.message || 'An error occurred.');
            btn.disabled = false;
            btn.textContent = 'Save Contact';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to connect to the server.');
        btn.disabled = false;
        btn.textContent = 'Save Contact';
    });
});

function deleteContact(id) {
    if (confirm('Are you sure you want to delete this contact?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('delete_contact_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message || 'An error occurred.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to delete contact.');
        });
    }
}

function setPrimaryContact(contactId) {
    const formData = new FormData();
    formData.append('contact_id', contactId);
    formData.append('lead_id', <?= $id ?>);

    fetch('set_primary_contact_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'Error setting primary contact');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to connect to the server.');
    });
}

  function toggleSection(header) {
    const body = header.nextElementSibling;
    const icon = header.querySelector('.ls-toggle');
    body.classList.toggle('collapsed');
    icon.classList.toggle('bi-chevron-up');
    icon.classList.toggle('bi-chevron-down');
  }

  function updateMeetingWithDropdown() {
    const sel = document.getElementById('meeting-with-contact');
    if (!sel) return;
    const names = leadContacts.map(c => c.name).filter(Boolean);
    sel.innerHTML = '<option value="">Select Contact Person</option>' +
      names.map(n => `<option value="${n}">${n}</option>`).join('');
  }

  function previewDocs(input, containerId) {
    containerId = containerId || 'doc-previews';
    const container = document.getElementById(containerId);
    const imgExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    Array.from(input.files).forEach(f => {
      const ext = f.name.split('.').pop().toLowerCase();
      const div = document.createElement('div');
      div.className = 'saved-doc-thumb';
      if (imgExts.includes(ext)) {
        const r = new FileReader();
        r.onload = e => { div.innerHTML = `<img src="${e.target.result}">`; };
        r.readAsDataURL(f);
      } else {
        const icons = {
          pdf: 'file-pdf text-danger', doc: 'file-word text-primary',
          docx: 'file-word text-primary', xls: 'file-excel text-success', xlsx: 'file-excel text-success'
        };
        div.innerHTML = `<i class="bi bi-${icons[ext] || 'file-earmark'} fs-3"></i>
                       <span style="font-size:9px;">${f.name.substring(0, 8)}</span>`;
      }
      container.appendChild(div);
    });
  }

  function previewCardFiles(input) {
    const container = document.getElementById('visiting_cards_new_preview');
    Array.from(input.files).forEach(f => {
      const div = document.createElement('div');
      div.className = 'position-relative d-inline-block border rounded p-1';
      div.style.width = '70px';
      const r = new FileReader();
      r.onload = e => {
        div.innerHTML = `
          <img src="${e.target.result}" style="width: 100%; height: 45px; object-fit: cover;" class="rounded">
          <span class="badge bg-success position-absolute top-0 start-0 p-1" style="font-size: 8px; border-radius: 50%;"><i class="bi bi-check"></i></span>
        `;
      };
      r.readAsDataURL(f);
      container.appendChild(div);
    });
  }

  // Contact Autocomplete Logic
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('contact_search');
    const resultsContainer = document.getElementById('contact_search_results');
    
    if (searchInput && resultsContainer) {
      let debounceTimer;
      searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const val = this.value.trim();
        if (val.length < 2) {
          resultsContainer.style.display = 'none';
          return;
        }
        
        debounceTimer = setTimeout(() => {
          fetch('<?= BASE_URL ?>/modules/contacts/search_ajax.php?q=' + encodeURIComponent(val))
          .then(r => r.json())
          .then(data => {
            resultsContainer.innerHTML = '';
            if (data.results && data.results.length > 0) {
              data.results.forEach(item => {
                const a = document.createElement('a');
                a.href = '#';
                a.className = 'list-group-item list-group-item-action py-2';
                
                let subtitle = '';
                if(item.contact.organization_name) subtitle += item.contact.organization_name;
                if(item.contact.mobile) subtitle += (subtitle ? ' | ' : '') + item.contact.mobile;
                
                a.innerHTML = `<div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1">${item.contact.name}</h6>
                  <small>${item.contact.contact_type || ''}</small>
                </div>
                <small class="text-muted">${subtitle}</small>`;
                
                a.addEventListener('click', function(e) {
                  e.preventDefault();
                  document.getElementById('contact_id').value = item.id;
                  document.getElementById('contact_form_mode').value = 'link_existing';
                  
                  document.getElementById('contact_name').value = item.contact.name || '';
                  if(item.contact.contact_type) document.getElementById('contact_contact_type').value = item.contact.contact_type;
                  document.getElementById('contact_mobile').value = item.contact.mobile || '';
                  document.getElementById('contact_whatsapp').value = item.contact.whatsapp || '';
                  document.getElementById('contact_email').value = item.contact.email || '';
                  document.getElementById('contact_organization_name').value = item.contact.organization_name || '';
                  document.getElementById('contact_website').value = item.contact.website || '';
                  document.getElementById('contact_address').value = item.contact.address || '';
                  document.getElementById('contact_city').value = item.contact.city || '';
                  document.getElementById('contact_state').value = item.contact.state || '';
                  document.getElementById('contact_pincode').value = item.contact.pincode || '';
                  
                  // Disable fields for read-only view
                  const fieldsToDisable = ['contact_name', 'contact_contact_type', 'contact_mobile', 'contact_whatsapp', 'contact_email', 'contact_organization_name', 'contact_website', 'contact_address', 'contact_city', 'contact_state', 'contact_pincode'];
                  fieldsToDisable.forEach(fid => {
                    if(document.getElementById(fid)) document.getElementById(fid).disabled = true;
                  });
                  
                  resultsContainer.style.display = 'none';
                  searchInput.value = '';
                });
                resultsContainer.appendChild(a);
              });
              resultsContainer.style.display = 'block';
            } else {
              resultsContainer.innerHTML = '<div class="list-group-item text-muted">No contacts found. Type below to create a new one.</div>';
              resultsContainer.style.display = 'block';
            }
          });
        }, 300);
      });
      
      document.addEventListener('click', function(e) {
        if (e.target !== searchInput && e.target !== resultsContainer && !resultsContainer.contains(e.target)) {
          resultsContainer.style.display = 'none';
        }
      });
    }
  });

  // Drag & drop
  const dropZone = document.getElementById('doc-drop-zone');
  if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor = 'var(--lp)'; });
    dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = ''; });
    dropZone.addEventListener('drop', e => {
      e.preventDefault(); dropZone.style.borderColor = '';
      const input = document.getElementById('doc-file-input');
      if (!input) return;
      const dt = new DataTransfer();
      Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
      input.files = dt.files;
      previewDocs(input);
    });
  }

  // Submit
  document.getElementById('lead-edit-form').addEventListener('submit', function () {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
  });

  let addressCount = <?= count($addresses) ?>;

  function addAddress() {
    const tpl = document.getElementById('address-tpl');
    const clone = tpl.content.cloneNode(true);
    const card = clone.querySelector('.address-card');
    const idx = addressCount;

    card.dataset.addressIndex = idx;

    card.querySelector('.address-type-input').name = `addresses[${idx}][address_type]`;
    card.querySelector('.primary-address-check').name = `addresses[${idx}][is_primary]`;
    card.querySelector('.addr-line1').name = `addresses[${idx}][address_line1]`;
    card.querySelector('.addr-line2').name = `addresses[${idx}][address_line2]`;
    card.querySelector('.addr-area').name = `addresses[${idx}][area]`;
    card.querySelector('.addr-city').name = `addresses[${idx}][city]`;
    card.querySelector('.addr-state').name = `addresses[${idx}][state]`;
    card.querySelector('.addr-pincode').name = `addresses[${idx}][pincode]`;

    card.querySelector('.addr-gaddress').name = `addresses[${idx}][google_address]`;
    card.querySelector('.addr-glink').name = `addresses[${idx}][google_maps_link]`;
    card.querySelector('.addr-lat').name = `addresses[${idx}][lat]`;
    card.querySelector('.addr-lng').name = `addresses[${idx}][lng]`;
    card.querySelector('.addr-gcode').name = `addresses[${idx}][google_location]`;

    card.querySelector('.remove-address-btn').style.display = 'inline-block';

    document.getElementById('addresses-container').appendChild(clone);
    addressCount++;
    updateAddressBadge();
  }

  function removeAddress(btn) {
    btn.closest('.address-card').remove();
    updateAddressBadge();
  }

  function updateAddressBadge() {
    const n = document.querySelectorAll('.address-card').length;
    document.getElementById('address-count-badge').textContent = n + ' Address' + (n !== 1 ? 'es' : '');
  }

  function handlePrimaryAddress(checkbox) {
    if (checkbox.checked) {
      document.querySelectorAll('.primary-address-check').forEach(cb => {
        if (cb !== checkbox) cb.checked = false;
      });
    }
  }

  // Add event listener for manual typing in gsearch
  document.addEventListener('input', function (e) {
    if (e.target.classList.contains('addr-gsearch')) {
      const card = e.target.closest('.address-card');
      if (!card) return;
      const val = e.target.value;
      card.querySelector('.addr-gaddress').value = val;
      card.querySelector('.addr-glink').value = 'https://www.google.com/maps?q=' + encodeURIComponent(val);

      const preview = card.querySelector('.addr-preview-container');
      if (val.trim()) {
        preview.classList.remove('d-none');
        preview.querySelector('.addr-preview-text').textContent = val;
        const mapLink = preview.querySelector('.addr-preview-map-link');
        if (mapLink) {
          mapLink.href = 'https://www.google.com/maps?q=' + encodeURIComponent(val);
          mapLink.classList.remove('d-none');
        }
        preview.querySelector('.addr-preview-dir-link').href = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(val);
      } else {
        preview.classList.add('d-none');
      }
    }
  });

  function fetchLocationForCard(btn) {
    const card = btn.closest('.address-card');
    if (!card) return;

    const gSearch = card.querySelector('.addr-gsearch');
    const latInput = card.querySelector('.addr-lat');
    const lngInput = card.querySelector('.addr-lng');
    const gAddress = card.querySelector('.addr-gaddress');
    const gLink = card.querySelector('.addr-glink');
    const gCode = card.querySelector('.addr-gcode');
    const preview = card.querySelector('.addr-preview-container');
    const previewText = card.querySelector('.addr-preview-text');
    const mapLink = card.querySelector('.addr-preview-map-link');
    const dirLink = card.querySelector('.addr-preview-dir-link');

    const line1Input = card.querySelector('.addr-line1');
    const areaInput = card.querySelector('.addr-area');
    const cityInput = card.querySelector('.addr-city');
    const stateSelect = card.querySelector('.addr-state');
    const pincodeInput = card.querySelector('.addr-pincode');

    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span>';

    if (!navigator.geolocation) {
      alert("Geolocation is not supported by your browser.");
      btn.disabled = false;
      btn.innerHTML = originalHtml;
      return;
    }

    navigator.geolocation.getCurrentPosition(
      function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const latLng = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        latInput.value = lat;
        lngInput.value = lng;
        gLink.value = `https://www.google.com/maps?q=${lat},${lng}`;
        gCode.value = latLng;

        // Show lat,long in the search field
        gSearch.value = latLng;

        // Attempt reverse geocoding via Nominatim (OpenStreetMap)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
          .then(response => response.json())
          .then(data => {
            const address = data.display_name || `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;
            // Store full address in hidden field (used for green preview)
            gAddress.value = address;

            // Auto-fill standard inputs if empty
            const details = data.address || {};
            if (details.road && !line1Input.value) {
              line1Input.value = (details.house_number ? details.house_number + ", " : "") + details.road;
            }
            if (details.suburb && !areaInput.value) {
              areaInput.value = details.suburb;
            } else if (details.neighbourhood && !areaInput.value) {
              areaInput.value = details.neighbourhood;
            }
            if ((details.city || details.town || details.village) && !cityInput.value) {
              cityInput.value = details.city || details.town || details.village;
            }
            if (details.postcode && !pincodeInput.value) {
              pincodeInput.value = details.postcode;
            }
            if (details.state && stateSelect) {
              const stateVal = details.state;
              Array.from(stateSelect.options).forEach(opt => {
                if (opt.value.toLowerCase() === stateVal.toLowerCase() || stateVal.toLowerCase().includes(opt.value.toLowerCase())) {
                  stateSelect.value = opt.value;
                }
              });
            }

            // Update Preview with full address
            preview.classList.remove('d-none');
            previewText.textContent = address;
            if (mapLink) {
              mapLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
              mapLink.classList.remove('d-none');
            }
            dirLink.href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

            btn.disabled = false;
            btn.innerHTML = originalHtml;
          })
          .catch(err => {
            gAddress.value = latLng;

            preview.classList.remove('d-none');
            previewText.textContent = latLng;
            if (mapLink) {
              mapLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
              mapLink.classList.remove('d-none');
            }
            dirLink.href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

            btn.disabled = false;
            btn.innerHTML = originalHtml;
          });
      },
      function (error) {
        alert("Error fetching location: " + error.message);
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      },
      { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
    );
  }
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
