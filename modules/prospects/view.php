<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
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

$contacts = db()->query("SELECT c.*, cr.id as relation_id, cr.role as contact_type, cr.is_primary FROM contacts c JOIN contact_relations cr ON c.id = cr.contact_id WHERE cr.entity_type = 'lead' AND cr.entity_id = $id ORDER BY cr.is_primary DESC, cr.id ASC")->fetchAll();
$addresses = db()->query("SELECT * FROM lead_addresses WHERE lead_id = $id ORDER BY is_primary DESC, id ASC")->fetchAll();
$products = db()->query("SELECT product_name FROM lead_interested_products WHERE lead_id = $id")->fetchAll(PDO::FETCH_COLUMN);
$meetings = db()->query("SELECT * FROM lead_meetings WHERE lead_id = $id ORDER BY created_at DESC")->fetchAll();
$documents = db()->query("SELECT * FROM lead_documents WHERE lead_id = $id ORDER BY created_at DESC")->fetchAll();
$timeline = db()->query("SELECT t.*, u.name as user_name FROM lead_timeline t LEFT JOIN users u ON t.user_id = u.id WHERE t.lead_id = $id ORDER BY t.created_at DESC")->fetchAll();

$pageTitle = 'Lead: ' . $lead['lead_code'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Lead Details</div>
</div>

<style>
.lead-header { background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
.stat-card { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; height: 100%; }
.stat-card .text-muted { font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: .5px; margin-bottom: 5px; }
.stat-card h5 { margin: 0; font-size: 16px; font-weight: 700; color: #1e293b; }

.crm-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.crm-card-header { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: space-between; border-radius: 12px 12px 0 0; }
.crm-card-title { margin: 0; font-size: 14px; font-weight: 700; color: #334155; }
.crm-card-body { padding: 20px; }

.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
.info-item .label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 3px; }
.info-item .value { font-size: 14px; color: #1e293b; font-weight: 500; }

.timeline-item { position: relative; padding-left: 30px; padding-bottom: 20px; border-left: 2px solid #e2e8f0; margin-left: 10px; }
.timeline-item:last-child { border-left-color: transparent; padding-bottom: 0; }
.timeline-icon { position: absolute; left: -11px; top: 0; width: 20px; height: 20px; border-radius: 50%; background: #fff; border: 2px solid #6366f1; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #6366f1; }
.timeline-date { font-size: 11px; color: #64748b; margin-bottom: 2px; }
.timeline-content { font-size: 13px; color: #334155; }

.doc-thumb { width: 80px; height: 70px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 10px; color: #64748b; background: #f8fafc; cursor: pointer; transition: all .2s; text-decoration: none; }
.doc-thumb:hover { border-color: #6366f1; background: #eef2ff; }
.doc-thumb img { width: 100%; height: 60px; object-fit: cover; }
.doc-thumb .doc-name { padding: 2px 4px; text-align: center; font-size: 9px; word-break: break-all; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }


.table-contacts th { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600; background: #f8fafc; }
.table-contacts td { font-size: 13px; vertical-align: middle; }

/* Premium Compact Modals Styling */
.modal-content {
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}
.modal-header {
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  padding: 12px 20px;
  border-top-left-radius: 14px;
  border-top-right-radius: 14px;
}
.modal-header .modal-title {
  font-size: 15px;
  font-weight: 700;
  color: #1e293b;
}
.modal-body {
  padding: 20px;
}
.modal-footer {
  border-top: 1px solid #e2e8f0;
  padding: 12px 20px;
  background: #f8fafc;
  border-bottom-left-radius: 14px;
  border-bottom-right-radius: 14px;
}
.modal-body label {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #475569;
  margin-bottom: 4px;
}
.modal-body .form-control, 
.modal-body .form-select {
  font-size: 13px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  padding: 8px 12px;
  transition: all 0.2s ease;
  background-color: #fff;
  height: 38px;
}
.modal-body .form-control:focus, 
.modal-body .form-select:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  outline: none;
}
.modal-body textarea.form-control {
  height: auto;
}

/* Sleek compact upload buttons */
.upload-btn-label {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: #f8fafc;
  border: 1px dashed #cbd5e1;
  border-radius: 8px;
  padding: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
}
.upload-btn-label:hover {
  background: #eff6ff;
  border-color: #3b82f6;
  color: #2563eb;
}
.upload-btn-label i {
  font-size: 14px;
}

/* Modals sizing constraints */
#contactModal .modal-dialog,
#meetingModal .modal-dialog,
#documentModal .modal-dialog {
  max-width: 480px;
  margin: 1.75rem auto;
}
#addressModal .modal-dialog {
  max-width: 680px;
  margin: 1.75rem auto;
}
</style>

<div class="page-content">
  <?= flashHtml() ?>
  
  <div class="lead-header">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <div>
        <h2 class="mb-1 fw-bold"><?= e($lead['company_name']) ?> <span class="badge bg-light text-dark border fs-6 ms-2">#<?= e($lead['lead_code']) ?></span></h2>
        <div class="text-muted small">
          <i class="bi bi-geo-alt-fill me-1"></i><?= e($lead['city']) ?: 'City not set' ?>
          <?php if($lead['industry_type']): ?> | <?= e($lead['industry_type']) ?><?php endif; ?>
        </div>
      </div>
      <div class="d-flex gap-2 mt-3 mt-sm-0">
        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="edit.php?id=<?= $id ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill me-1"></i>Edit Lead</a>
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
          <ul class="dropdown-menu dropdown-menu-end">
            <!-- <li><a class="dropdown-item" href="export_pdf.php?id=<?= $id ?>"><i class="bi bi-file-pdf text-danger me-2"></i>Export PDF</a></li> -->
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#" onclick="if(confirm('Are you sure you want to delete this lead?')) { window.location.href='delete.php?id=<?= $id ?>'; }"><i class="bi bi-trash me-2"></i>Delete Lead</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="text-muted">Status</div>
          <h5><?= statusBadge($lead['lead_status']) ?></h5>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="text-muted">Priority</div>
          <h5><?= statusBadge($lead['lead_priority']) ?: '<span class="text-muted">-</span>' ?></h5>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="text-muted">Expected Closing Date</div>
          <h5><?= $lead['next_followup_date'] ? formatDate($lead['next_followup_date']) : '<span class="text-muted">Not set</span>' ?></h5>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="text-muted">Est. Budget</div>
          <h5 class="text-success"><?= $lead['estimated_budget'] > 0 ? formatCurrency($lead['estimated_budget']) : '<span class="text-muted">-</span>' ?></h5>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <!-- LEFT COLUMN -->
    <div class="col-lg-8">
      
      <!-- CARD 1: Lead Master -->
      <div class="crm-card">
        <div class="crm-card-header"><h3 class="crm-card-title"><i class="bi bi-star-fill text-warning me-2"></i>Lead Master Information</h3></div>
        <div class="crm-card-body">
          <div class="info-grid">
            <div class="info-item"><div class="label">Lead Date</div><div class="value"><?= formatDate($lead['lead_date']) ?></div></div>
            <div class="info-item"><div class="label">Lead Source</div><div class="value"><?= e($lead['lead_source']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Assigned To</div><div class="value"><?= e($lead['assigned_name']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Created By</div><div class="value"><?= e($lead['creator_name']) ?: '-' ?></div></div>
          </div>
        </div>
      </div>

      <!-- CARD 2: Company & Site -->
      <div class="crm-card">
        <div class="crm-card-header"><h3 class="crm-card-title"><i class="bi bi-building text-primary me-2"></i>Company & Site Details</h3></div>
        <div class="crm-card-body">
          <div class="info-grid mb-4">
            <div class="info-item"><div class="label">Company Type</div><div class="value"><?= e($lead['company_type']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">TIN Number</div><div class="value"><?= e($lead['tin_number']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">GST Number</div><div class="value"><?= e($lead['gst_number']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Company Status</div><div class="value"><?= statusBadge($lead['company_status']) ?></div></div>
            <div class="info-item">
              <div class="label">Company Email</div>
              <div class="value"><?= $lead['company_email'] ? '<a href="mailto:'.e($lead['company_email']).'">'.e($lead['company_email']).'</a>' : '-' ?></div>
            </div>
            <div class="info-item">
              <div class="label">Company Website</div>
              <div class="value"><?= $lead['company_website'] ? '<a href="'.e($lead['company_website']).'" target="_blank">View Site <i class="bi bi-box-arrow-up-right ms-1"></i></a>' : '-' ?></div>
            </div>
          </div>
          <hr>
          <div class="info-grid">
            <div class="info-item"><div class="label">Site Stage</div><div class="value"><?= e($lead['site_stage']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Project Type</div><div class="value"><?= e($lead['project_type']) ?: '-' ?></div></div>
          </div>
        </div>
      </div>

      <!-- CARD 3: Address -->
      <div class="crm-card">
        <div class="crm-card-header">
          <h3 class="crm-card-title"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Address Information (<?= count($addresses) ?>)</h3>
          <button class="btn btn-primary btn-sm" onclick="showAddAddressModal()"><i class="bi bi-plus-lg me-1"></i>Add Address</button>
        </div>
        <div class="crm-card-body p-0 table-responsive">
          <table class="table table-hover table-contacts mb-0">
            <thead>
              <tr>
                <th>Type</th>
                <th>Full Address</th>
                <th>Location</th>
                <th style="width: 100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($addresses as $addr): ?>
              <tr>
                <td>
                  <span class="badge bg-light text-dark border"><?= e($addr['address_type']) ?: 'Address' ?></span>
                  <?php if($addr['is_primary']): ?>
                    <span class="badge bg-primary ms-1" style="font-size:9px;">PRIMARY</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="small text-dark">
                    <?php
                      $addrParts = array_filter([$addr['address_line1'], $addr['address_line2'], $addr['area'], $addr['city'], $addr['state'], $addr['pincode']]);
                      echo $addrParts ? implode(', ', array_map('e', $addrParts)) : '-';
                    ?>
                  </div>
                </td>
                <td>
                  <?php if($addr['google_address'] || $addr['google_maps_link'] || $addr['google_location']): ?>
                    <?php if($addr['google_address']): ?>
                      <div class="small text-success mb-1" style="font-size: 11px;"><i class="bi bi-google me-1"></i><?= e($addr['google_address']) ?></div>
                    <?php endif; ?>
                    <?php if($addr['google_maps_link']): ?>
                      <a href="<?= e($addr['google_maps_link']) ?>" target="_blank" class="btn btn-sm btn-outline-success" style="font-size:10px; padding:2px 6px;"><i class="bi bi-map-fill me-1"></i>View Map</a>
                    <?php endif; ?>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= !empty($addr['lat']) && !empty($addr['lng']) ? $addr['lat'].','.$addr['lng'] : urlencode(implode(', ', array_filter([$addr['address_line1'], $addr['address_line2'], $addr['area'], $addr['city'], $addr['state'], $addr['pincode']]))) ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-1" style="font-size:10px; padding:2px 6px;">
                      <i class="bi bi-cursor-fill me-1"></i>Directions
                    </a>
                    <?php if($addr['google_location']): ?>
                      <span class="badge bg-light text-muted border ms-1" style="font-size:10px;"><?= e($addr['google_location']) ?></span>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="btn btn-outline-primary btn-sm px-2 py-1" onclick="editAddressById(<?= $addr['id'] ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger btn-sm px-2 py-1" onclick="deleteAddress(<?= $addr['id'] ?>)" title="Delete"><i class="bi bi-trash"></i></button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if(empty($addresses)): ?>
              <tr><td colspan="4" class="text-center text-muted py-3">No addresses recorded</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- CARD 4: Contacts -->
      <div class="crm-card">
        <div class="crm-card-header">
          <h3 class="crm-card-title"><i class="bi bi-people-fill text-info me-2"></i>Contact Persons (<?= count($contacts) ?>)</h3>
          <button class="btn btn-primary btn-sm" onclick="showAddContactModal()"><i class="bi bi-plus-lg me-1"></i>Add Contact</button>
        </div>
        <div class="crm-card-body p-0 table-responsive">
          <table class="table table-hover table-contacts mb-0">
            <thead>
              <tr>
                <th>Name / Designation</th>
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
                  <!-- removed designation -->
                </td>
                <td><span class="badge bg-light text-dark border"><?= e($c['contact_type']) ?></span></td>
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
                      <button class="btn btn-outline-primary btn-sm px-2 py-1" onclick="editContactById(<?= $c['id'] ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-outline-danger btn-sm px-2 py-1" onclick="deleteContact(<?= $c['id'] ?>)" title="Delete"><i class="bi bi-trash"></i></button>
                    </div>
                    <?php if(!$c['is_primary']): ?>
                      <button class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="setPrimaryContact(<?= $c['id'] ?>)" style="font-size: 10px;">Set as Primary</button>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if(empty($contacts)): ?>
              <tr><td colspan="5" class="text-center text-muted py-3">No contacts found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- CARD 5: Requirements & Products -->
      <div class="crm-card">
        <div class="crm-card-header"><h3 class="crm-card-title"><i class="bi bi-card-checklist text-success me-2"></i>Requirements & Products</h3></div>
        <div class="crm-card-body">
          <div class="mb-3">
            <div class="info-item mb-1"><div class="label">Interested Products</div></div>
            <?php if($products): ?>
              <div class="d-flex flex-wrap gap-2">
                <?php foreach($products as $p): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-tag-fill me-1"></i><?= e($p) ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: echo '-'; endif; ?>
          </div>
          <hr>
          <div class="info-grid">
            <div class="info-item"><div class="label">Product Type</div><div class="value"><?= e($lead['product_type']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Purchase Timeline</div><div class="value"><?= e($lead['purchase_timeline']) ?: '-' ?></div></div>
            <div class="info-item"><div class="label">Competitor Info</div><div class="value"><?= e($lead['competitor_info']) ?: '-' ?></div></div>
          </div>
          <div class="info-item mt-3">
            <div class="label">Requirement Description</div>
            <div class="value p-3 bg-light rounded text-dark" style="white-space:pre-wrap;"><?= e($lead['requirement_description']) ?: 'No description provided.' ?></div>
          </div>
        </div>
      </div>

      <!-- ROW: Meetings & Documents -->
      <div class="row g-3">
        <div class="col-md-6">
          <div class="crm-card h-100 mb-0">
            <div class="crm-card-header">
              <h3 class="crm-card-title"><i class="bi bi-calendar-check-fill text-warning me-2"></i>Meetings (<?= count($meetings) ?>)</h3>
              <button class="btn btn-primary btn-sm" onclick="showAddMeetingModal()"><i class="bi bi-plus-lg me-1"></i>Add Meeting</button>
            </div>
            <div class="crm-card-body p-3">
              <?php if($meetings): ?>
                <div class="list-group list-group-flush">
                  <?php foreach($meetings as $m): ?>
                  <div class="list-group-item px-0 py-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                      <div>
                        <strong class="small d-block"><?= e($m['type']) ?></strong>
                        <span class="badge bg-light text-dark border" style="font-size:10px;"><?= e($m['status']) ?></span>
                      </div>
                      <div class="d-flex gap-2">
                        <button class="btn btn-link btn-sm p-0 text-primary" onclick="editMeetingById(<?= $m['id'] ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-link btn-sm p-0 text-danger" onclick="deleteMeeting(<?= $m['id'] ?>)" title="Delete"><i class="bi bi-trash"></i></button>
                      </div>
                    </div>
                    <div class="small text-muted mb-1">With: <?= e($m['meeting_with']) ?></div>
                    <?php if($m['purpose']): ?><div class="small mb-1 fst-italic">"<?= e($m['purpose']) ?>"</div><?php endif; ?>
                    <div class="small text-muted" style="font-size:10px;"><i class="bi bi-clock me-1"></i><?= formatDateTime($m['created_at']) ?></div>
                  </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="text-muted small text-center py-3">No meetings recorded</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="crm-card h-100 mb-0">
            <div class="crm-card-header">
              <h3 class="crm-card-title"><i class="bi bi-folder-fill text-secondary me-2"></i>Documents (<?= count($documents) ?>)</h3>
              <button class="btn btn-primary btn-sm" onclick="showAddDocumentModal()"><i class="bi bi-plus-lg me-1"></i>Add Document</button>
            </div>
            <div class="crm-card-body p-3">
              <?php if($documents): ?>
                <div class="d-flex flex-wrap gap-2">
                  <?php foreach($documents as $doc): 
                    $ext = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                    $isImg = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                  ?>
                  <a href="<?= BASE_URL ?>/<?= e($doc['file_path']) ?>" target="_blank" class="doc-thumb" title="<?= e($doc['file_name']) ?>">
                    <?php if($isImg): ?>
                      <img src="<?= BASE_URL ?>/<?= e($doc['file_path']) ?>">
                    <?php else: 
                      $icon = 'file-earmark';
                      if(in_array($ext,['pdf'])) $icon='file-pdf text-danger';
                      if(in_array($ext,['doc','docx'])) $icon='file-word text-primary';
                      if(in_array($ext,['xls','xlsx'])) $icon='file-excel text-success';
                    ?>
                      <i class="bi bi-<?= $icon ?> fs-2 mt-1"></i>
                    <?php endif; ?>
                    <div class="doc-name"><?= substr($doc['file_name'],0,15) ?></div>
                  </a>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="text-muted small text-center py-3">No documents uploaded</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">
      <div class="crm-card">
        <div class="crm-card-header"><h3 class="crm-card-title"><i class="bi bi-clock-history text-info me-2"></i>Activity Timeline</h3></div>
        <div class="crm-card-body pt-4">
          <?php if($timeline): ?>
            <?php foreach($timeline as $t): ?>
            <div class="timeline-item">
              <div class="timeline-icon"><i class="bi bi-<?= $t['action_type']=='Created'?'plus-lg':'pencil' ?>"></i></div>
              <div class="timeline-date"><?= formatDateTime($t['created_at']) ?> by <?= e($t['user_name']) ?></div>
              <div class="timeline-content">
                <strong><?= e($t['action_type']) ?></strong>
                <p class="mb-0 mt-1 small text-muted"><?= e($t['description']) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-muted small">No activity recorded</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- ==========================================
     MODALS FOR INLINE CRUD
     ========================================== -->

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="addressForm">
      <input type="hidden" name="id" id="address_id">
      <input type="hidden" name="lead_id" value="<?= $id ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addressModalLabel">Add Address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address Type <span class="text-danger">*</span></label>
              <select name="address_type" id="address_type" class="form-select" required>
                <?php foreach($addressTypes as $type): ?>
                  <option value="<?= e($type) ?>"><?= e($type) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="is_primary" id="address_is_primary" value="1">
                <label class="form-check-label fw-semibold" for="address_is_primary">Set as Primary Address</label>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address Line 1 <span class="text-danger">*</span></label>
              <input type="text" name="address_line1" id="address_address_line1" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address Line 2</label>
              <input type="text" name="address_line2" id="address_address_line2" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Area</label>
              <input type="text" name="area" id="address_area" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
              <input type="text" name="city" id="address_city" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">State</label>
              <input type="text" name="state" id="address_state" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Pincode</label>
              <input type="text" name="pincode" id="address_pincode" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Latitude</label>
              <input type="text" name="lat" id="address_lat" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Longitude</label>
              <input type="text" name="lng" id="address_lng" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Google Address (Search Location)</label>
              <input type="text" name="google_address" id="address_google_address" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Google Location</label>
              <input type="text" name="google_location" id="address_google_location" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Google Maps Link</label>
              <input type="url" name="google_maps_link" id="address_google_maps_link" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="addressSubmitBtn">Save Address</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../../includes/contact_modal_ui.php'; ?>
<!-- Meeting Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="meetingForm">
      <input type="hidden" name="id" id="meeting_id">
      <input type="hidden" name="lead_id" value="<?= $id ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="meetingModalLabel">Add Meeting</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Meeting With <span class="text-danger">*</span></label>
              <select name="meeting_with_name" id="meeting_with_name" class="form-select" required>
                <option value="">Select Contact Person</option>
                <?php foreach ($contacts as $c):
                  if (!empty($c['name'])): ?>
                    <option value="<?= e($c['name']) ?>"><?= e($c['name']) ?></option>
                  <?php endif; endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Meeting Type <span class="text-danger">*</span></label>
              <select name="meeting_type" id="meeting_type" class="form-select" required>
                <?php foreach($meetingTypes as $type): ?>
                  <option value="<?= e($type) ?>"><?= e($type) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Meeting Priority <span class="text-danger">*</span></label>
              <select name="meeting_status" id="meeting_status" class="form-select" required>
                <?php foreach($meetingStatuses as $status): ?>
                  <option value="<?= e($status) ?>"><?= e($status) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12" id="meeting_lead_status_container">
              <label class="form-label fw-semibold">Lead Status (Update Lead)</label>
              <select name="meeting_lead_status" id="meeting_lead_status" class="form-select">
                <option value="">-- No Change --</option>
                <?php foreach($leadStatuses as $status): ?>
                  <option value="<?= e($status) ?>"><?= e($status) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12" id="meeting_sales_stage_container">
              <label class="form-label fw-semibold">Sales Stage</label>
              <select name="sales_stage" id="sales_stage" class="form-select">
                <option value="">-- Select Stage --</option>
                <?php foreach($salesStages as $stage): ?>
                  <option value="<?= e($stage) ?>"><?= e($stage) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Purpose / Remarks</label>
              <textarea name="meeting_purpose" id="meeting_purpose" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Follow-up Date</label>
              <input type="datetime-local" name="actual_followup_date" id="meeting_followup_date" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="meetingSubmitBtn">Save Meeting</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="documentForm" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="documentModalLabel">Upload Documents</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold d-block">Select Files to Upload</label>
              <div class="d-flex gap-2 mb-2">
                <label class="upload-btn-label flex-grow-1 text-center">
                  <i class="bi bi-folder2"></i>Upload from Device
                  <input type="file" name="documents[]" id="doc_device" class="d-none" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" onchange="previewDocFiles(this)">
                </label>
                <label class="upload-btn-label flex-grow-1 text-center">
                  <i class="bi bi-camera"></i>Take Photo
                  <input type="file" name="camera_photos[]" id="doc_camera" class="d-none" multiple accept="image/*" capture="environment" onchange="previewDocFiles(this)">
                </label>
              </div>
              <div id="doc_files_new_preview" class="d-flex gap-2 flex-wrap mb-2"></div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Upload Remark</label>
              <input type="text" name="upload_remark" id="doc_upload_remark" class="form-control" placeholder="e.g. Site entrance view">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="documentSubmitBtn">Save Documents</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// --- Global Data Arrays mapped from PHP to JS to prevent quote-escaping issues ---
const leadContacts = <?= json_encode($contacts) ?>;
const leadAddresses = <?= json_encode($addresses) ?>;
const leadMeetings = <?= json_encode($meetings) ?>;
const currentLeadStatus = <?= json_encode($lead['lead_status']) ?>;

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
    
    // Enable all fields in case they were disabled
    Array.from(document.getElementById('contactForm').elements).forEach(el => el.disabled = false);
    
    new bootstrap.Modal(document.getElementById('contactModal')).show();
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
        alert('Failed to set primary contact.');
    });
}

function editAddressById(id) {
    const addr = leadAddresses.find(item => parseInt(item.id) === parseInt(id));
    if (addr) showEditAddressModal(addr);
}

function editMeetingById(id) {
    const m = leadMeetings.find(item => parseInt(item.id) === parseInt(id));
    if (m) showEditMeetingModal(m);
}

// --- Address Helpers ---
function showAddAddressModal() {
    document.getElementById('addressForm').reset();
    document.getElementById('address_id').value = '';
    document.getElementById('addressModalLabel').textContent = 'Add Address';
    document.getElementById('address_is_primary').checked = false;
    new bootstrap.Modal(document.getElementById('addressModal')).show();
}

function showEditAddressModal(addr) {
    document.getElementById('addressForm').reset();
    document.getElementById('address_id').value = addr.id;
    document.getElementById('addressModalLabel').textContent = 'Edit Address';
    
    document.getElementById('address_type').value = addr.address_type || 'Office Address';
    document.getElementById('address_address_line1').value = addr.address_line1 || '';
    document.getElementById('address_address_line2').value = addr.address_line2 || '';
    document.getElementById('address_area').value = addr.area || '';
    document.getElementById('address_city').value = addr.city || '';
    document.getElementById('address_state').value = addr.state || '';
    document.getElementById('address_pincode').value = addr.pincode || '';
    document.getElementById('address_lat').value = addr.lat || '';
    document.getElementById('address_lng').value = addr.lng || '';
    document.getElementById('address_google_address').value = addr.google_address || '';
    document.getElementById('address_google_location').value = addr.google_location || '';
    document.getElementById('address_google_maps_link').value = addr.google_maps_link || '';
    document.getElementById('address_is_primary').checked = parseInt(addr.is_primary) === 1;

    new bootstrap.Modal(document.getElementById('addressModal')).show();
}

document.getElementById('addressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('addressSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    const id = document.getElementById('address_id').value;
    const url = id ? 'update_address_ajax.php' : 'add_address_ajax.php';
    const formData = new FormData(this);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred.');
            btn.disabled = false;
            btn.textContent = 'Save Address';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to connect to the server.');
        btn.disabled = false;
        btn.textContent = 'Save Address';
    });
});

function deleteAddress(id) {
    if (confirm('Are you sure you want to delete this address?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('delete_address_ajax.php', {
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
            alert('Failed to delete address.');
        });
    }
}

// --- Contact Helpers ---
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

function showAddContactModal() {
    document.getElementById('contactForm').reset();
    document.getElementById('contact_id').value = '';
    document.getElementById('contact_existing_cards').value = '';
    document.getElementById('existing_cards_preview').innerHTML = '';
    document.getElementById('visiting_cards_new_preview').innerHTML = '';
    document.getElementById('contactModalLabel').textContent = 'Add Contact';
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
        
        // Refresh preview
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
    
    // Temporarily re-enable all fields to grab their values for formData, in case they were disabled by link mode
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
                linkFormData.append('lead_id', document.querySelector('input[name="lead_id"]').value);
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

// --- Meeting Helpers ---
function showAddMeetingModal() {
    document.getElementById('meetingForm').reset();
    document.getElementById('meeting_id').value = '';
    document.getElementById('meetingModalLabel').textContent = 'Add Meeting';
    document.getElementById('meeting_lead_status_container').style.display = 'block';
    document.getElementById('meeting_sales_stage_container').style.display = 'block';
    document.getElementById('meeting_lead_status').value = currentLeadStatus || '';
    new bootstrap.Modal(document.getElementById('meetingModal')).show();
}

function showEditMeetingModal(m) {
    document.getElementById('meetingForm').reset();
    document.getElementById('meeting_id').value = m.id;
    document.getElementById('meetingModalLabel').textContent = 'Edit Meeting';
    
    document.getElementById('meeting_lead_status_container').style.display = 'block';
    document.getElementById('meeting_sales_stage_container').style.display = 'block';

    document.getElementById('meeting_with_name').value = m.meeting_with || '';
    document.getElementById('meeting_type').value = m.type || 'Site Visit';
    document.getElementById('meeting_purpose').value = m.purpose || '';
    document.getElementById('meeting_status').value = m.status || 'Scheduled';
    document.getElementById('sales_stage').value = m.sales_stage || '';
    document.getElementById('meeting_lead_status').value = currentLeadStatus || '';
    document.getElementById('meeting_followup_date').value = m.followup_date ? m.followup_date.replace(' ', 'T').slice(0, 16) : '';

    new bootstrap.Modal(document.getElementById('meetingModal')).show();
}

document.getElementById('meetingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('meetingSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    const id = document.getElementById('meeting_id').value;
    const url = id ? 'update_meeting_ajax.php' : 'add_meeting_ajax.php';
    const formData = new FormData(this);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred.');
            btn.disabled = false;
            btn.textContent = 'Save Meeting';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to connect to the server.');
        btn.disabled = false;
        btn.textContent = 'Save Meeting';
    });
});

function deleteMeeting(id) {
    if (confirm('Are you sure you want to delete this meeting?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('delete_meeting_ajax.php', {
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
            alert('Failed to delete meeting.');
        });
    }
}

// --- Document Helpers ---
function showAddDocumentModal() {
    document.getElementById('documentForm').reset();
    document.getElementById('doc_files_new_preview').innerHTML = '';
    new bootstrap.Modal(document.getElementById('documentModal')).show();
}

function previewDocFiles(input) {
    const container = document.getElementById('doc_files_new_preview');
    Array.from(input.files).forEach(f => {
        const ext = f.name.split('.').pop().toLowerCase();
        const div = document.createElement('div');
        div.className = 'position-relative d-inline-block border rounded p-1';
        div.style.width = '70px';
        
        const imgExts = ['jpg','jpeg','png','webp','gif'];
        if (imgExts.includes(ext)) {
            const r = new FileReader();
            r.onload = e => {
                div.innerHTML = `
                    <img src="${e.target.result}" style="width: 100%; height: 45px; object-fit: cover;" class="rounded">
                    <span class="badge bg-success position-absolute top-0 start-0 p-1" style="font-size: 8px; border-radius: 50%;"><i class="bi bi-check"></i></span>
                `;
            };
            r.readAsDataURL(f);
        } else {
            const icons = {pdf:'file-pdf text-danger',doc:'file-word text-primary',
                           docx:'file-word text-primary',xls:'file-excel text-success',xlsx:'file-excel text-success'};
            div.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center bg-light" style="width: 100%; height: 45px; font-size: 10px;">
                    <i class="bi bi-${icons[ext] || 'file-earmark'} fs-4"></i>
                </div>
                <span class="badge bg-success position-absolute top-0 start-0 p-1" style="font-size: 8px; border-radius: 50%;"><i class="bi bi-check"></i></span>
                <div class="text-truncate text-center mt-1" style="font-size: 9px; max-width: 60px;">${f.name}</div>
            `;
        }
        container.appendChild(div);
    });
}

document.getElementById('documentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('documentSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

    const formData = new FormData(this);

    fetch('save_docs_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred.');
            btn.disabled = false;
            btn.textContent = 'Save Documents';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to upload documents.');
        btn.disabled = false;
        btn.textContent = 'Save Documents';
    });
});

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
</script>

</div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
