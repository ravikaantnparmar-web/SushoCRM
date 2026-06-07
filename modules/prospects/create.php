<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();
requirePermission('prospects', 'create');
$users  = getAllUsers();
$errors = [];

$pageTitle = 'New Lead';
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">New Lead</div>
</div>

<style>
/* ============================================================
   NEW LEAD FORM Ã¢â‚¬â€ Two-Column Layout (matches edit.php)
   ============================================================ */
:root {
  --lp: #5b6ef5;
  --lp-light: #eef0ff;
  --ls-border: #e4e7ec;
  --ls-bg: #f9fafb;
  --ls-header-txt: #374151;
  --ls-label: #6b7280;
  --radius-card: 10px;
}

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
  font-size: 17px; font-weight: 700; color: #1f2937; margin: 0;
}
.lead-edit-topstrip .strip-actions { display: flex; gap: 10px; }

.lead-edit-outer {
  display: flex;
  gap: 18px;
  padding: 18px 22px 40px;
  align-items: flex-start;
}
.lead-left-col  { flex: 1 1 0; min-width: 0; }
.lead-right-col { width: 340px; flex-shrink: 0; }

.ls-card {
  background: #fff;
  border: 1px solid var(--ls-border);
  border-radius: var(--radius-card);
  margin-bottom: 16px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.ls-card-header {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 18px;
  background: var(--ls-bg);
  border-bottom: 1px solid var(--ls-border);
  cursor: pointer; user-select: none;
}
.ls-card-header .sec-ico {
  width: 32px; height: 32px; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; flex-shrink: 0;
}
.ls-card-header h6 {
  margin: 0; font-size: 12px; font-weight: 700;
  letter-spacing: .6px; text-transform: uppercase; color: var(--ls-header-txt);
}
.ls-card-header .ls-toggle { margin-left: auto; font-size: 13px; color: #9ca3af; }
.ls-card-body { padding: 18px; }
.ls-card-body.collapsed { display: none; }

.ico-indigo { background:#eef0ff; color:#5b6ef5; }
.ico-green  { background:#ecfdf5; color:#059669; }
.ico-orange { background:#fff7ed; color:#ea580c; }
.ico-purple { background:#fdf4ff; color:#9333ea; }
.ico-teal   { background:#f0fdfa; color:#0d9488; }
.ico-amber  { background:#fffbeb; color:#d97706; }
.ico-red    { background:#fef2f2; color:#dc2626; }

.f2-badge {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: 9px; font-weight: 700; color: #6b7280;
  background: #f3f4f6; border: 1px solid #d1d5db;
  border-radius: 4px; padding: 1px 5px;
  vertical-align: middle; margin-left: 4px; letter-spacing: .3px;
}
.f2-badge i { font-size: 8px; }

.form-label {
  font-size: 11px; font-weight: 600; color: var(--ls-label);
  letter-spacing: .4px; text-transform: uppercase;
  margin-bottom: 5px; display: flex; align-items: center; gap: 4px;
}
.form-control, .form-select {
  font-size: 13px; border-color: #d1d5db; border-radius: 7px;
  background: #fff; color: #1f2937;
}
.form-control:focus, .form-select:focus {
  border-color: var(--lp); box-shadow: 0 0 0 3px rgba(91,110,245,.12);
}
.input-group-text { font-size: 13px; background: #f9fafb; border-color: #d1d5db; }

.ls-sub-label {
  font-size: 10px; font-weight: 700; color: #9ca3af;
  letter-spacing: .8px; text-transform: uppercase;
  padding: 6px 0 4px; border-bottom: 1px solid #f3f4f6; margin-bottom: 12px;
}

/* Contact table */
.contact-table-wrap { overflow-x: auto; }
.contact-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.contact-table th {
  font-size: 10px; font-weight: 700; color: #6b7280;
  text-transform: uppercase; letter-spacing: .5px;
  padding: 9px 10px; background: #f9fafb;
  border-bottom: 2px solid var(--ls-border); white-space: nowrap;
}
.contact-table td {
  padding: 7px 6px; border-bottom: 1px solid #f3f4f6; vertical-align: middle;
  background: #fff;
}
.contact-table tr:last-child td { border-bottom: none; }
.contact-table tr.contact-primary-row td {
  background: #fff; border-top: 1px solid #e0e7ff; border-bottom: 1px solid #e0e7ff;
}
.contact-table tr.contact-primary-row td:first-child {
  border-left: 3px solid var(--lp);
}
.contact-table .form-control,
.contact-table .form-select {
  font-size: 12px; padding: 5px 8px; border-radius: 6px;
  background: #fff !important; color: #1f2937 !important;
  height: 34px; min-height: 34px;
}
.primary-badge-tag {
  display: inline-block; font-size: 9px; font-weight: 700;
  color: var(--lp); background: var(--lp-light); border: 1px solid #c7d2fe;
  border-radius: 10px; padding: 0px 6px; letter-spacing: .3px;
  white-space: nowrap; vertical-align: middle; margin-left: 4px;
}
.contact-num-badge {
  display: inline-flex; align-items: center; justify-content: center;
  width: 22px; height: 22px; background: var(--lp); color: #fff;
  border-radius: 50%; font-size: 10px; font-weight: 700;
}

/* Right sidebar */
.ls-right-panel {
  background: #fff; border: 1px solid var(--ls-border);
  border-radius: var(--radius-card); margin-bottom: 16px;
  overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.ls-right-panel-header {
  display: flex; align-items: center; gap: 8px;
  padding: 11px 16px; background: var(--ls-bg);
  border-bottom: 1px solid var(--ls-border);
  font-size: 12px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .5px; color: var(--ls-header-txt);
}
.ls-right-panel-header i { color: var(--lp); }
.ls-right-panel-body { padding: 14px 16px; }

.prod-check-list { list-style: none; padding: 0; margin: 0; }
.prod-check-list li { padding: 5px 0; border-bottom: 1px solid #f3f4f6; }
.prod-check-list li:last-child { border-bottom: none; }
.prod-check-list .form-check-label { font-size: 13px; color: #374151; cursor: pointer; }
.prod-check-list .form-check-input:checked { background-color: var(--lp); border-color: var(--lp); }

.doc-upload-zone {
  border: 2px dashed #d1d5db; border-radius: 8px;
  padding: 20px 12px; text-align: center; cursor: pointer;
  transition: all .2s; background: #fafbff;
}
.doc-upload-zone:hover { border-color: var(--lp); background: var(--lp-light); }
.doc-upload-zone .du-icon { font-size: 28px; color: #9ca3af; margin-bottom: 6px; }
.doc-upload-zone .du-text { font-size: 12px; font-weight: 600; color: #374151; }
.doc-upload-zone .du-sub  { font-size: 11px; color: #9ca3af; }

.doc-action-btns { display: flex; gap: 8px; justify-content: center; margin-top: 10px; }
.doc-action-btns .btn { font-size: 12px; padding: 5px 12px; }

.saved-doc-thumb {
  width: 64px; height: 56px; border: 1px solid #e4e7ec; border-radius: 6px;
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  background: #f9fafb; font-size: 10px; color: #6b7280; flex-direction: column;
}
.saved-doc-thumb img { width: 100%; height: 100%; object-fit: cover; }

.meeting-log {
  background: #f9fafb; border: 1px solid var(--ls-border);
  border-radius: 7px; padding: 10px 12px; margin-bottom: 8px;
}

.ls-sticky-bar {
  position: sticky; bottom: 0; background: #fff;
  border-top: 1px solid var(--ls-border);
  padding: 12px 22px; display: flex; gap: 10px; justify-content: flex-end;
  z-index: 150; box-shadow: 0 -4px 20px rgba(0,0,0,.07);
}

@media (max-width: 900px) {
  .lead-edit-outer { flex-direction: column; }
  .lead-right-col { width: 100%; }
}
</style>

<div class="page-content pb-0">
  <?= flashHtml() ?>

  <form id="lead-create-form" action="save.php" method="POST" enctype="multipart/form-data" novalidate>

  <!-- Top strip -->
  <div class="lead-edit-topstrip">
    <h1>
      <i class="bi bi-plus-circle-fill me-2 text-primary" style="font-size:15px;"></i>
      New Lead
    </h1>
    <div class="strip-actions">
      <a href="index.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-x me-1"></i>Cancel
      </a>
      <button type="submit" class="btn btn-sm btn-primary fw-semibold px-4" id="submit-btn">
        <i class="bi bi-check2-circle me-1"></i>Create Lead
      </button>
    </div>
  </div>

  <div class="lead-edit-outer">

    <!-- ====================================================
         LEFT COLUMN
         ==================================================== -->
    <div class="lead-left-col">

      <!-- Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ SECTION 1: LEAD MASTER INFO Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-card">
        <div class="ls-card-header" onclick="toggleSection(this)">
          <div class="sec-ico ico-indigo"><i class="bi bi-star-fill"></i></div>
          <h6>Lead Master Information</h6>
          <span class="badge bg-primary ms-2" style="font-size:10px;">Required</span>
          <i class="bi bi-chevron-up ls-toggle"></i>
        </div>
        <div class="ls-card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Lead Date <span class="text-danger">*</span></label>
              <input type="date" name="lead_date" class="form-control"
                     value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Lead Status</label>
              <select name="lead_status" class="form-select">
                <?php foreach($leadStatuses as $s): ?>
                <option value="<?= e($s) ?>" <?= $s=='New'?'selected':'' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">
                Lead Priority
                <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
              </label>
              <select name="lead_priority" class="form-select">
                <?php foreach($leadPriorities as $p): ?>
                <option value="<?= e($p) ?>"><?= e($p) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">
                Lead Source
                <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
              </label>
              <select name="lead_source" class="form-select">
                <option value="">Select Source</option>
                <?php foreach($leadSources as $s): ?>
                <option value="<?= e($s) ?>"><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Assigned To</label>
              <select name="assigned_to" class="form-select">
                <option value="">Select Employee</option>
                <?php foreach($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= e($u['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Expected Closing Date</label>
              <input type="datetime-local" name="next_followup_date" class="form-control">
            </div>

            <!-- Site Stage / Project Type -->
            <div class="col-12"><div class="ls-sub-label">Site Details</div></div>
            <div class="col-md-4">
              <label class="form-label">
                Site Stage
                <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
              </label>
              <select name="site_stage" class="form-select">
                <option value="">Select Stage</option>
                <?php foreach($siteStages as $ss): ?>
                <option value="<?= e($ss) ?>"><?= e($ss) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Project Type</label>
              <select name="project_type" class="form-select">
                <option value="">Select Project Type</option>
                <?php foreach($projectTypes as $pt): ?>
                <option value="<?= e($pt) ?>"><?= e($pt) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Status Description</label>
              <input type="text" name="status_description" class="form-control"
                     placeholder="Short status note">
            </div>
          </div>
        </div>
      </div><!-- /lead master -->

      <!-- Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ SECTION 2: COMPANY INFORMATION Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ -->
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
              <input type="text" name="company_name" class="form-control"
                     placeholder="Enter company or client name" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">
                Company Type
                <span class="f2-badge"><i class="bi bi-keyboard"></i> F2 Master</span>
              </label>
              <select name="company_type" class="form-select">
                <option value="">Select Type</option>
                <?php foreach($companyTypes as $ct): ?>
                <option value="<?= e($ct) ?>"><?= e($ct) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Company Status</label>
              <select name="company_status" class="form-select">
                <?php foreach($companyStatuses as $cs): ?>
                <option value="<?= e($cs) ?>"><?= e($cs) ?></option>
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
                <?php foreach($industryTypes as $it): ?>
                <option value="<?= e($it) ?>"><?= e($it) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">GST Number</label>
              <input type="text" name="gst_number" class="form-control"
                     placeholder="22AAAAA0000A1Z5" maxlength="20">
            </div>
            <div class="col-md-4">
              <label class="form-label">TIN Number</label>
              <input type="text" name="tin_number" class="form-control"
                     placeholder="Enter TIN Number" maxlength="50">
            </div>
            <div class="col-md-6">
              <label class="form-label">Company Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="company_email" class="form-control"
                       placeholder="company@email.com">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Company Website</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-globe text-muted"></i></span>
                <input type="url" name="company_website" class="form-control"
                       placeholder="https://example.com">
              </div>
            </div>
          </div>
        </div>
      </div><!-- /company -->

      <!-- Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ SECTION 3: ADDRESS INFORMATION Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-card">
        <div class="ls-card-header" onclick="toggleSection(this)">
          <div class="sec-ico ico-orange"><i class="bi bi-geo-alt-fill"></i></div>
          <h6>Address Information</h6>
          <span class="badge ms-2" style="background:#ea580c;font-size:10px;" id="address-count-badge">
            1 Address
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
          <!-- Addresses inserted by JS -->
        </div>
      </div><!-- /address -->      <!-- Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ SECTION 4: CONTACT PERSONS Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-card">
        <div class="ls-card-header" onclick="toggleSection(this)">
          <div class="sec-ico ico-purple"><i class="bi bi-people-fill"></i></div>
          <h6>Contact Person Management</h6>
          <span class="badge ms-2" style="background:#9333ea;font-size:10px;" id="contact-count-badge">
            1 Contact
          </span>
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
          <div class="contact-table-wrap table-responsive">
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
              <tbody id="contacts-tbody">
                <!-- Rows inserted by JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /contacts -->

      <!-- Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ SECTION 5: LEAD MEET Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-card">
        <div class="ls-card-header" onclick="toggleSection(this)">
          <div class="sec-ico ico-amber"><i class="bi bi-calendar-check-fill"></i></div>
          <h6>Lead Meet (Initial Meeting)</h6>
          <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">Optional</span>
          <i class="bi bi-chevron-up ls-toggle"></i>
        </div>
        <div class="ls-card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Meeting With (Contact Person)</label>
              <select name="meeting_with_name" id="meeting-with-contact" class="form-select">
                <option value="">Select Contact Person</option>
              </select>
              <div class="form-text" style="font-size:10px;">Add contacts above first</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Meeting Type</label>
              <select name="meeting_type" class="form-select">
                <?php foreach($meetingTypes as $mt): ?>
                <option value="<?= e($mt) ?>"><?= e($mt) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Meeting Priority</label>
              <select name="meeting_status" class="form-select">
                <?php foreach($meetingStatuses as $ms): ?>
                <option value="<?= e($ms) ?>" <?= $ms=='Scheduled'?'selected':'' ?>><?= e($ms) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Sales Stage</label>
              <select name="sales_stage" class="form-select">
                <option value="">Select Stage</option>
                <?php foreach($salesStages as $ss): ?>
                <option value="<?= e($ss) ?>"><?= e($ss) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Next Follow-up After Meeting</label>
              <input type="datetime-local" name="meeting_followup_date" class="form-control"
                     value="<?= date('Y-m-d\TH:i', strtotime('+7 days')) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Update Lead Status After Meet</label>
              <select name="meeting_lead_status" class="form-select">
                <?php foreach($leadStatuses as $ls): ?>
                <option value="<?= e($ls) ?>" <?= $ls=='New'?'selected':'' ?>><?= e($ls) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Meeting Purpose / Summary</label>
              <textarea name="meeting_purpose" class="form-control" rows="2"
                        placeholder="What was discussed? Key takeaways..."></textarea>
            </div>
          </div>
        </div>
      </div><!-- /lead meet -->

    </div><!-- /.lead-left-col -->

    <!-- ====================================================
         RIGHT COLUMN / SIDEBAR
         ==================================================== -->
    <div class="lead-right-col">

      <!-- Ã¢â€â‚¬Ã¢â€â‚¬ PANEL: PRODUCT / REQUIREMENT Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-right-panel">
        <div class="ls-right-panel-header">
          <i class="bi bi-box-seam-fill"></i>
          Product / Requirement
        </div>
        <div class="ls-right-panel-body">
          <label class="form-label mb-2">Interested Products</label>
          <ul class="prod-check-list">
            <?php foreach($interestedProducts as $ip): ?>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="interested_products[]"
                       value="<?= e($ip) ?>"
                       id="prod_<?= md5($ip) ?>">
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
              <?php foreach($productTypes as $pt): ?>
              <option value="<?= e($pt) ?>"><?= e($pt) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Estimated Budget (Ã¢â€šÂ¹)</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text text-muted">Ã¢â€šÂ¹</span>
              <input type="number" name="estimated_budget" class="form-control"
                     placeholder="0" min="0" step="1000">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Purchase Timeline</label>
            <input type="text" name="purchase_timeline" class="form-control form-control-sm"
                   placeholder="e.g. Within 3 months">
          </div>

          <div class="mb-1">
            <label class="form-label">Requirement Description</label>
            <textarea name="requirement_description" class="form-control form-control-sm" rows="4"
                      placeholder="Describe client's requirements..."></textarea>
          </div>

          <div class="mt-3">
            <label class="form-label">Competitor Info</label>
            <input type="text" name="competitor_info" class="form-control form-control-sm"
                   placeholder="Competing brands / vendors">
          </div>
        </div>
      </div><!-- /product panel -->



      <!-- Ã¢â€â‚¬Ã¢â€â‚¬ PANEL: SITE MEDIA & DOCUMENTS Ã¢â€â‚¬Ã¢â€â‚¬ -->
      <div class="ls-right-panel">
        <div class="ls-right-panel-header">
          <i class="bi bi-folder2-open"></i>
          Site Media &amp; Documents
        </div>
        <div class="ls-right-panel-body">
          <div class="doc-upload-zone" id="doc-drop-zone"
               onclick="document.getElementById('doc-file-input').click()">
            <div class="du-icon"><i class="bi bi-cloud-upload"></i></div>
            <div class="du-text">Drop files here or click to upload</div>
            <div class="du-sub">Support for JPG, PNG, PDF (Max 5MB)</div>
          </div>
          <input type="file" id="doc-file-input" name="documents[]" multiple
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp"
                 class="d-none" onchange="previewDocs(this)">

          <div class="doc-action-btns">
            <label class="btn btn-sm btn-outline-primary mb-0">
              <i class="bi bi-folder2 me-1"></i>Browse
              <input type="file" name="camera_photos[]" multiple accept="image/*"
                     class="d-none" onchange="previewDocs(this,'cam-previews')">
            </label>
            <label class="btn btn-sm btn-outline-secondary mb-0">
              <i class="bi bi-camera me-1"></i>Take Photo
              <input type="file" name="camera_photos_2[]" multiple accept="image/*"
                     capture="environment" class="d-none"
                     onchange="previewDocs(this,'cam-previews')">
            </label>
          </div>

          <div class="d-flex flex-wrap gap-1 mt-2" id="doc-previews"></div>
          <div class="d-flex flex-wrap gap-1 mt-1" id="cam-previews"></div>

          <div class="mt-3">
            <button type="button" class="btn btn-success btn-sm w-100 fw-semibold"
                    onclick="document.getElementById('submit-btn').click()">
              <i class="bi bi-floppy me-1"></i>Save Documents Now
            </button>
          </div>

          <div class="mt-3">
            <label class="form-label">Upload Remark</label>
            <input type="text" name="upload_remark" class="form-control form-control-sm"
                   placeholder="e.g. Site photos from initial visit">
          </div>
        </div>
      </div><!-- /site media panel -->

    </div><!-- /.lead-right-col -->

  </div><!-- /.lead-edit-outer -->

  <!-- Sticky bottom bar -->
  <div class="ls-sticky-bar">
    <a href="index.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-x me-1"></i>Cancel
    </a>
    <button type="submit" class="btn btn-primary btn-sm px-5 fw-semibold" id="submit-btn">
      <i class="bi bi-check2-circle me-2"></i>Create Lead
    </button>
  </div>

  </form>
</div><!-- /.page-content -->
</div><!-- /.main-content -->

<?php include __DIR__ . '/../../includes/contact_modal_ui.php'; ?>

<!-- CONTACT ROW TEMPLATE -->
<template id="contact-tpl">
  <tr class="contact-row" data-contact-index="">
    <td>
      <div class="fw-bold contact-name-display"></div>
      <span class="badge bg-primary contact-primary-badge mt-1" style="font-size:9px; display:none;">PRIMARY</span>
      <input type="hidden" name="" class="contact-id-input">
      <input type="hidden" name="" class="contact-organization-name-input">
      <input type="hidden" name="" class="contact-website-input">
      <input type="hidden" name="" class="contact-address-input">
      <input type="hidden" name="" class="contact-city-input">
      <input type="hidden" name="" class="contact-state-input">
      <input type="hidden" name="" class="contact-pincode-input">
      <input type="hidden" name="" class="contact-existing-cards-input">
      <input type="hidden" name="" class="contact-name-input">
      <input type="hidden" name="" class="contact-contact-type-input">
      <input type="hidden" name="" class="contact-email-input">
      <input type="hidden" name="" class="contact-mobile-input">
      <input type="hidden" name="" class="contact-whatsapp-input">
      <input type="checkbox" name="" class="primary-check d-none">
      <div class="contact-file-inputs-container d-none"></div>
    </td>
    <td>
      <span class="badge bg-light text-dark border contact-contact-type-display"></span>
    </td>
    <td>
      <div class="small contact-mobile-container d-none"><i class="bi bi-telephone text-muted me-1"></i><a href="#" class="contact-mobile-display"></a></div>
      <div class="small contact-whatsapp-container d-none"><i class="bi bi-whatsapp text-success me-1"></i><a href="#" target="_blank" class="text-success contact-whatsapp-display"></a></div>
      <div class="small contact-email-container d-none"><i class="bi bi-envelope text-muted me-1"></i><a href="#" class="contact-email-display"></a></div>

      <div class="mt-2 pt-2 border-top border-light contact-org-section d-none">
        <div class="small text-muted contact-org-name-container d-none"><i class="bi bi-building me-1"></i><strong class="contact-organization-name-display"></strong></div>
        <div class="small text-muted contact-address-display-container d-none" style="font-size:11px;">
          <i class="bi bi-geo-alt me-1"></i>
          <span class="contact-address-display"></span>
        </div>
        <div class="small contact-website-container d-none"><i class="bi bi-globe text-muted me-1"></i><a href="#" target="_blank" class="text-decoration-none contact-website-display" style="font-size:11px;">Website</a></div>
      </div>
    </td>
    <td>
      <div class="d-flex gap-1 flex-wrap contact-card-previews"></div>
      <div class="contact-cards-count small text-muted mt-1"></div>
    </td>
    <td>
      <div class="d-flex flex-column gap-1">
        <div class="d-flex gap-1">
          <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1 edit-btn" onclick="editLocalContact(this)" title="Edit"><i class="bi bi-pencil"></i></button>
          <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1 remove-btn" onclick="removeContact(this)" title="Delete"><i class="bi bi-trash"></i></button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1 set-primary-btn" onclick="setPrimaryContactNew(this)" style="font-size: 10px;">Set as Primary</button>
      </div>
    </td>
  </tr>
</template>

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
          <?php foreach($addressTypes as $at): ?>
          <option value="<?= e($at) ?>"><?= e($at) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-8">
        <div class="d-flex align-items-center h-100 pt-4">
          <div class="form-check form-switch">
            <input class="form-check-input primary-address-check" type="checkbox" role="switch" name="" value="1" onchange="handlePrimaryAddress(this)">
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
          <?php foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Ladakh','Puducherry','Chandigarh','Dadra and Nagar Haveli','Daman and Diu','Lakshadweep','Andaman and Nicobar Islands'] as $st): ?>
          <option value="<?= e($st) ?>"><?= e($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Pincode</label>
        <input type="text" class="form-control addr-pincode" name="" placeholder="000000" maxlength="6" pattern="[0-9]{6}">
      </div>

      <div class="col-12"><div class="ls-sub-label">Google Map Location (Optional)</div></div>
      <div class="col-12">
        <div class="d-flex align-items-center gap-2">
          <div class="input-group shadow-sm" style="flex:1 1 0;min-width:0;">
            <span class="input-group-text bg-white border-end-0 pe-1"><i class="bi bi-geo-alt-fill text-danger" style="font-size:13px;"></i></span>
            <input type="text" class="form-control addr-gsearch border-start-0" placeholder="Search address or click Locate Me to use GPS...">
            <button type="button" class="btn btn-outline-primary btn-fetch-location d-flex align-items-center gap-1 px-3" onclick="fetchLocationForCard(this)" title="Fetch Current Location">
              <i class="bi bi-geo-fill"></i> <span class="small fw-semibold">Locate Me</span>
            </button>
          </div>
          <div class="addr-preview-container d-none flex-shrink-0" style="max-width:42%;">
            <div class="p-2 bg-light rounded border small d-flex align-items-center gap-1">
              <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
              <span class="addr-preview-text text-success fw-medium text-truncate" style="max-width:130px;"></span>
              <a href="#" target="_blank" class="btn btn-sm btn-outline-success addr-preview-map-link flex-shrink-0 d-none" style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i class="bi bi-map-fill me-1"></i>Map</a>
              <a href="#" target="_blank" class="btn btn-sm btn-outline-primary addr-preview-dir-link flex-shrink-0" style="font-size:10px;padding:2px 6px;white-space:nowrap;"><i class="bi bi-cursor-fill me-1"></i>Directions</a>
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

<script>
let contactCount = 0;

function toggleSection(header) {
  const body = header.nextElementSibling;
  const icon = header.querySelector('.ls-toggle');
  body.classList.toggle('collapsed');
  icon.classList.toggle('bi-chevron-up');
  icon.classList.toggle('bi-chevron-down');
}

// addContact() is no longer used directly — contacts are added via the modal (showAddContactModal)

function initRowContactSearch(row) {
    const searchInput = row.querySelector('.contact-name-input');
    const resultsContainer = row.querySelector('.contact-search-results');

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
                                <small style="font-size: 10px;">${item.contact.contact_type || ''}</small>
                            </div>
                            <small class="text-muted" style="font-size: 10px;">${subtitle}</small>`;

                            a.addEventListener('click', function(e) {
                                e.preventDefault();
                                row.querySelector('.contact-id-input').value = item.id;
                                row.querySelector('.contact-name-input').value = item.contact.name || '';
                                row.querySelector('.contact-mobile-input').value = item.contact.mobile || '';
                                row.querySelector('.contact-whatsapp-input').value = item.contact.whatsapp || '';
                                row.querySelector('.contact-email-input').value = item.contact.email || '';

                                resultsContainer.style.display = 'none';
                                updateMeetingWithDropdown();
                            });
                            resultsContainer.appendChild(a);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="list-group-item text-muted small py-2">No master contact found. A new one will be created.</div>';
                        resultsContainer.style.display = 'block';
                        row.querySelector('.contact-id-input').value = '';
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
}

function removeContact(btn) {
  btn.closest('tr').remove();
  updateContactBadge();
  updateMeetingWithDropdown();
}

function previewRowContactCard(input, idx) {
  const container = document.getElementById(`contact_card_previews_${idx}`);
  if (!container) return;

  // Clear any previously added new card previews
  container.querySelectorAll('.new-card-preview').forEach(el => el.remove());

  const imgExts = ['jpg','jpeg','png','webp','gif'];
  Array.from(input.files).forEach(f => {
    const ext = f.name.split('.').pop().toLowerCase();
    if (imgExts.includes(ext)) {
      const r = new FileReader();
      r.onload = e => {
        const div = document.createElement('div');
        div.className = 'saved-doc-thumb new-card-preview position-relative';
        div.style.cssText = 'width: 45px; height: 32px; border-radius: 4px; overflow: hidden;';
        div.innerHTML = `
          <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">
        `;
        container.appendChild(div);
      };
      r.readAsDataURL(f);
    }
  });
}

function updateContactBadge() {
  const n = document.querySelectorAll('#contacts-tbody .contact-row').length;
  document.getElementById('contact-count-badge').textContent = n + ' Contact' + (n !== 1 ? 's' : '');
}

function updateMeetingWithDropdown() {
  const sel = document.getElementById('meeting-with-contact');
  if (!sel) return;
  const names = Array.from(document.querySelectorAll('.contact-name-input')).map(i => i.value).filter(Boolean);
  sel.innerHTML = '<option value="">Select Contact Person</option>' +
    names.map(n => `<option value="${n}">${n}</option>`).join('');
}

document.addEventListener('input', e => {
  if (e.target.classList.contains('contact-name-input')) updateMeetingWithDropdown();
});

function previewDocs(input, containerId = 'doc-previews') {
  const container = document.getElementById(containerId);
  const imgExts = ['jpg','jpeg','png','webp','gif'];
  Array.from(input.files).forEach(f => {
    const ext = f.name.split('.').pop().toLowerCase();
    const div = document.createElement('div');
    div.className = 'saved-doc-thumb';
    if (imgExts.includes(ext)) {
      const r = new FileReader();
      r.onload = e => { div.innerHTML = `<img src="${e.target.result}">`; };
      r.readAsDataURL(f);
    } else {
      const icons = {pdf:'file-pdf text-danger',doc:'file-word text-primary',
                     docx:'file-word text-primary',xls:'file-excel text-success',xlsx:'file-excel text-success'};
      div.innerHTML = `<i class="bi bi-${icons[ext]||'file-earmark'} fs-3"></i>
                       <span style="font-size:9px;">${f.name.substring(0,8)}</span>`;
    }
    container.appendChild(div);
  });
}

const dropZone = document.getElementById('doc-drop-zone');
if (dropZone) {
  dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor='var(--lp)'; });
  dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor=''; });
  dropZone.addEventListener('drop', e => {
    e.preventDefault(); dropZone.style.borderColor='';
    const input = document.getElementById('doc-file-input');
    if (!input) return;
    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
    input.files = dt.files;
    previewDocs(input);
  });
}


document.getElementById('lead-create-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
});

function clearGoogleLocation() {
  ['google_address','google_maps_link','lat','lng','google_location'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('google-search-input').value = '';
  document.getElementById('google-address-preview').classList.add('d-none');
}

let addressCount = 0;
function addAddress(isPrimary = false) {
  const tpl = document.getElementById('address-tpl');
  const clone = tpl.content.cloneNode(true);
  const card = clone.querySelector('.address-card');
  const idx = addressCount;

  card.dataset.addressIndex = idx;

  card.querySelector('.address-type-input').name = 'addresses[' + idx + '][address_type]';
  card.querySelector('.primary-address-check').name = 'addresses[' + idx + '][is_primary]';
  card.querySelector('.addr-line1').name = 'addresses[' + idx + '][address_line1]';
  card.querySelector('.addr-line2').name = 'addresses[' + idx + '][address_line2]';
  card.querySelector('.addr-area').name = 'addresses[' + idx + '][area]';
  card.querySelector('.addr-city').name = 'addresses[' + idx + '][city]';
  card.querySelector('.addr-state').name = 'addresses[' + idx + '][state]';
  card.querySelector('.addr-pincode').name = 'addresses[' + idx + '][pincode]';

  card.querySelector('.addr-gaddress').name = 'addresses[' + idx + '][google_address]';
  card.querySelector('.addr-glink').name = 'addresses[' + idx + '][google_maps_link]';
  card.querySelector('.addr-lat').name = 'addresses[' + idx + '][lat]';
  card.querySelector('.addr-lng').name = 'addresses[' + idx + '][lng]';
  card.querySelector('.addr-gcode').name = 'addresses[' + idx + '][google_location]';

  if (isPrimary) {
    card.querySelector('.primary-address-check').checked = true;
    card.querySelector('.address-type-input').value = 'Site Address'; // default
  } else {
    card.querySelector('.remove-address-btn').style.display = 'inline-block';
  }

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
document.addEventListener('input', function(e) {
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
    function(position) {
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
          // Show full address in the search input as well
          gSearch.value = address;

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
    function(error) {
      alert("Error fetching location: " + error.message);
      btn.disabled = false;
      btn.innerHTML = originalHtml;
    },
    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
  );
}

// Init: add first primary address on page load
addAddress(true);

// Init: add first primary contact on page load - Removed since we use Modal now
// addContact(true);


function editLocalContact(btn) {
  const row = btn.closest('tr');
  const idx = row.dataset.contactIndex;
  
  document.getElementById('contactForm').reset();
  document.getElementById('contact_form_mode').value = 'edit_local';
  document.getElementById('contact_form_mode').dataset.editIndex = idx;
  
  document.getElementById('contactModalLabel').textContent = 'Edit Contact';
  
  document.getElementById('contact_id').value = row.querySelector('.contact-id-input').value;
  document.getElementById('contact_name').value = row.querySelector('.contact-name-input').value;
  document.getElementById('contact_contact_type').value = row.querySelector('.contact-contact-type-input').value;
  document.getElementById('contact_email').value = row.querySelector('.contact-email-input').value;
  document.getElementById('contact_mobile').value = row.querySelector('.contact-mobile-input').value;
  document.getElementById('contact_whatsapp').value = row.querySelector('.contact-whatsapp-input').value;
  document.getElementById('contact_organization_name').value = row.querySelector('.contact-organization-name-input').value;
  document.getElementById('contact_website').value = row.querySelector('.contact-website-input').value;
  document.getElementById('contact_address').value = row.querySelector('.contact-address-input').value;
  document.getElementById('contact_city').value = row.querySelector('.contact-city-input').value;
  document.getElementById('contact_state').value = row.querySelector('.contact-state-input').value;
  document.getElementById('contact_pincode').value = row.querySelector('.contact-pincode-input').value;
  document.getElementById('contact_existing_cards').value = row.querySelector('.contact-existing-cards-input').value;
  
  new bootstrap.Modal(document.getElementById('contactModal')).show();
}

function showAddContactModal() {
  document.getElementById('contact_form_mode').dataset.editIndex = '';
  document.getElementById('contactForm').reset();
  document.getElementById('contact_id').value = '';
  document.getElementById('contact_existing_cards').value = '';
  document.getElementById('contact_form_mode').value = 'create';

  document.getElementById('contactModalLabel').textContent = 'Add Contact';

  // Clear any existing preview cards
  const newPreviews = document.getElementById('visiting_cards_new_preview');
  if (newPreviews) newPreviews.innerHTML = '';
  const existingPreviews = document.getElementById('existing_cards_preview');
  if (existingPreviews) existingPreviews.innerHTML = '';

  new bootstrap.Modal(document.getElementById('contactModal')).show();
}

// Global Contact Search inside Modal
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
                fetch('../contacts/search_ajax.php?q=' + encodeURIComponent(val))
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
                                <small style="font-size: 10px;">${item.contact.contact_type || ''}</small>
                            </div>
                            <small class="text-muted" style="font-size: 10px;">${subtitle}</small>`;

                            a.addEventListener('click', function(e) {
                                e.preventDefault();
                                document.getElementById('contact_id').value = item.id;
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

                                resultsContainer.style.display = 'none';
                                searchInput.value = '';

                                // Show success feedback
                                searchInput.placeholder = `Populated: ${item.contact.name}`;
                                setTimeout(() => searchInput.placeholder = "Type name, mobile, email or organization to search...", 3000);
                            });
                            resultsContainer.appendChild(a);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="list-group-item text-muted small py-2">No master contact found. Continue typing to add as new.</div>';
                        resultsContainer.style.display = 'block';
                        document.getElementById('contact_id').value = '';
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

// Handle Modal Submission for Create Page (No AJAX — stores data locally in row)
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const formMode = document.getElementById('contact_form_mode').value;
  const isEdit = (formMode === 'edit_local');
  let row, idx;

  if (isEdit) {
    idx = document.getElementById('contact_form_mode').dataset.editIndex;
    row = document.querySelector(`.contact-row[data-contact-index='${idx}']`);
    if (!row) {
      console.error('Could not find row to edit with index', idx);
      return;
    }
  } else {
    // Create new row from template
    const tpl = document.getElementById('contact-tpl');
    const clone = tpl.content.cloneNode(true);
    row = clone.querySelector('tr');
    idx = contactCount;
    row.dataset.contactIndex = idx;

    // First contact gets PRIMARY badge
    if (contactCount === 0) {
      row.querySelector('.primary-check').checked = true;
      row.classList.add('contact-primary-row');
      const badge = row.querySelector('.contact-primary-badge');
      if (badge) badge.style.display = 'inline-block';
      const setBtn = row.querySelector('.set-primary-btn');
      if (setBtn) setBtn.style.display = 'none';
    }
  }

  // Gather values from modal form
  const cId    = document.getElementById('contact_id').value;
  const cName  = document.getElementById('contact_name').value;
  const cType  = document.getElementById('contact_contact_type').value;
  const cMobile= document.getElementById('contact_mobile').value;
  const cWa    = document.getElementById('contact_whatsapp').value;
  const cEmail = document.getElementById('contact_email').value;
  const cOrg   = document.getElementById('contact_organization_name').value;
  const cWeb   = document.getElementById('contact_website').value;
  const cAddr  = document.getElementById('contact_address').value;
  const cCity  = document.getElementById('contact_city').value;
  const cState = document.getElementById('contact_state').value;
  const cPin   = document.getElementById('contact_pincode').value;
  const cCards = document.getElementById('contact_existing_cards').value;

  // ---- Update Display cells ----
  const nameDisplay = row.querySelector('.contact-name-display');
  if (nameDisplay) nameDisplay.textContent = cName;

  const typeDisplay = row.querySelector('.contact-contact-type-display');
  if (typeDisplay) typeDisplay.textContent = cType;

  // Reset contact info visibility first (for edits)
  row.querySelector('.contact-mobile-container').classList.add('d-none');
  row.querySelector('.contact-whatsapp-container').classList.add('d-none');
  row.querySelector('.contact-email-container').classList.add('d-none');

  if (cMobile) {
    const el = row.querySelector('.contact-mobile-display');
    row.querySelector('.contact-mobile-container').classList.remove('d-none');
    if (el) { el.textContent = cMobile; el.href = 'tel:' + cMobile; }
  }
  if (cWa) {
    const el = row.querySelector('.contact-whatsapp-display');
    row.querySelector('.contact-whatsapp-container').classList.remove('d-none');
    if (el) { el.textContent = cWa; el.href = 'https://wa.me/' + cWa.replace(/[^0-9]/g, ''); }
  }
  if (cEmail) {
    const el = row.querySelector('.contact-email-display');
    row.querySelector('.contact-email-container').classList.remove('d-none');
    if (el) { el.textContent = cEmail; el.href = 'mailto:' + cEmail; }
  }

  const orgSec = row.querySelector('.contact-org-section');
  if (orgSec) {
    if (cOrg || cAddr || cCity || cState || cPin || cWeb) {
      orgSec.classList.remove('d-none');
      const orgNameCont = row.querySelector('.contact-org-name-container');
      const orgNameDisp = row.querySelector('.contact-organization-name-display');
      if (cOrg) {
        if (orgNameCont) orgNameCont.classList.remove('d-none');
        if (orgNameDisp) orgNameDisp.textContent = cOrg;
      } else {
        if (orgNameCont) orgNameCont.classList.add('d-none');
      }
      const addrCont = row.querySelector('.contact-address-display-container');
      const addrDisp = row.querySelector('.contact-address-display');
      if (cAddr || cCity || cState || cPin) {
        if (addrCont) addrCont.classList.remove('d-none');
        if (addrDisp) addrDisp.textContent = [cAddr, cCity, cState, cPin].filter(Boolean).join(', ');
      } else {
        if (addrCont) addrCont.classList.add('d-none');
      }
      const webCont = row.querySelector('.contact-website-container');
      const webDisp = row.querySelector('.contact-website-display');
      if (cWeb) {
        if (webCont) webCont.classList.remove('d-none');
        if (webDisp) webDisp.href = cWeb;
      } else {
        if (webCont) webCont.classList.add('d-none');
      }
    } else {
      orgSec.classList.add('d-none');
    }
  }

  // ---- Set hidden inputs (wire names for form submission) ----
  row.querySelector('.contact-id-input').name  = `contacts[${idx}][master_contact_id]`;
  row.querySelector('.contact-id-input').value = cId;

  row.querySelector('.contact-name-input').name  = `contacts[${idx}][name]`;
  row.querySelector('.contact-name-input').value = cName;

  row.querySelector('.contact-contact-type-input').name  = `contacts[${idx}][type]`;
  row.querySelector('.contact-contact-type-input').value = cType;

  row.querySelector('.contact-mobile-input').name  = `contacts[${idx}][mobile]`;
  row.querySelector('.contact-mobile-input').value = cMobile;

  row.querySelector('.contact-whatsapp-input').name  = `contacts[${idx}][whatsapp]`;
  row.querySelector('.contact-whatsapp-input').value = cWa;

  row.querySelector('.contact-email-input').name  = `contacts[${idx}][email]`;
  row.querySelector('.contact-email-input').value = cEmail;

  row.querySelector('.contact-organization-name-input').name  = `contacts[${idx}][organization_name]`;
  row.querySelector('.contact-organization-name-input').value = cOrg;

  row.querySelector('.contact-website-input').name  = `contacts[${idx}][website]`;
  row.querySelector('.contact-website-input').value = cWeb;

  row.querySelector('.contact-address-input').name  = `contacts[${idx}][address]`;
  row.querySelector('.contact-address-input').value = cAddr;

  row.querySelector('.contact-city-input').name  = `contacts[${idx}][city]`;
  row.querySelector('.contact-city-input').value = cCity;

  row.querySelector('.contact-state-input').name  = `contacts[${idx}][state]`;
  row.querySelector('.contact-state-input').value = cState;

  row.querySelector('.contact-pincode-input').name  = `contacts[${idx}][pincode]`;
  row.querySelector('.contact-pincode-input').value = cPin;

  row.querySelector('.contact-existing-cards-input').name  = `contacts[${idx}][existing_card]`;
  row.querySelector('.contact-existing-cards-input').value = cCards;

  row.querySelector('.primary-check').name = `contacts[${idx}][is_primary]`;

  // ---- Handle visiting card file uploads ----
  const fileInputDevice = document.getElementById('contact_visiting_card_device');
  const fileInputCamera = document.getElementById('contact_visiting_card_camera');
  const filesContainer  = row.querySelector('.contact-file-inputs-container');

  // Clear old file inputs in container (for edits)
  filesContainer.innerHTML = '';

  let totalCards = 0;
  if (cCards) {
    try {
      const arr = JSON.parse(cCards);
      if (Array.isArray(arr)) totalCards += arr.length;
      else if (typeof cCards === 'string' && cCards.length > 5) totalCards += 1;
    } catch(err) {}
  }

  const dt = new DataTransfer();
  if (fileInputDevice && fileInputDevice.files.length > 0) {
    Array.from(fileInputDevice.files).forEach(f => dt.items.add(f));
  }
  if (fileInputCamera && fileInputCamera.files.length > 0) {
    Array.from(fileInputCamera.files).forEach(f => dt.items.add(f));
  }
  if (dt.files.length > 0) {
    const newFileInput = document.createElement('input');
    newFileInput.type     = 'file';
    newFileInput.name     = `contacts[${idx}][card_file][]`;
    newFileInput.multiple = true;
    newFileInput.files    = dt.files;
    filesContainer.appendChild(newFileInput);
    totalCards += dt.files.length;
  }

  const cardsCount = row.querySelector('.contact-cards-count');
  if (cardsCount) cardsCount.textContent = totalCards > 0 ? totalCards + ' Card(s)' : '';

  // Only append the row to the table if it's a NEW contact (not an edit)
  if (!isEdit) {
    document.getElementById('contacts-tbody').appendChild(row);
    contactCount++;
    updateContactBadge();
    updateMeetingWithDropdown();
  }

  // Close the modal
  const modalEl = document.getElementById('contactModal');
  const modal = bootstrap.Modal.getInstance(modalEl);
  if (modal) modal.hide();
});

function setPrimaryContactNew(btn) {
  const allRows = document.querySelectorAll('.contact-row');
  allRows.forEach(row => {
    const cb = row.querySelector('.primary-check');
    if (cb) cb.checked = false;
    const badge = row.querySelector('.contact-primary-badge');
    if (badge) badge.style.display = 'none';
    const setBtn = row.querySelector('.set-primary-btn');
    if (setBtn) setBtn.style.display = 'inline-block';
  });

  const row = btn.closest('tr');
  const cb = row.querySelector('.primary-check');
  if (cb) cb.checked = true;
  const badge = row.querySelector('.contact-primary-badge');
  if (badge) badge.style.display = 'inline-block';
  btn.style.display = 'none';
}

</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

