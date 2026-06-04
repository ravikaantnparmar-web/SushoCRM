<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) { setFlash('danger', 'Contact not found.'); header('Location: index.php'); exit; }

// --- Aggregate Data ---
$custCount = db()->prepare("SELECT COUNT(*) FROM contact_relations WHERE contact_id = ? AND entity_type = 'customer'");
$custCount->execute([$id]);
$totalCustomers = $custCount->fetchColumn();

$leadCount = db()->prepare("SELECT COUNT(*) FROM contact_relations WHERE contact_id = ? AND entity_type = 'lead'");
$leadCount->execute([$id]);
$totalLeads = $leadCount->fetchColumn();

// --- Linked Customers ---
$stmtCust = db()->prepare("
    SELECT c.*, cr.role, cr.is_primary 
    FROM customers c 
    JOIN contact_relations cr ON c.id = cr.entity_id 
    WHERE cr.contact_id = ? AND cr.entity_type = 'customer'
    ORDER BY c.created_at DESC
");
$stmtCust->execute([$id]);
$linkedCustomers = $stmtCust->fetchAll();

// --- Linked Leads ---
$stmtLead = db()->prepare("
    SELECT l.*, cr.role, cr.is_primary 
    FROM leads l 
    JOIN contact_relations cr ON l.id = cr.entity_id 
    WHERE cr.contact_id = ? AND cr.entity_type = 'lead'
    ORDER BY l.created_at DESC
");
$stmtLead->execute([$id]);
$linkedLeads = $stmtLead->fetchAll();

$pageTitle = e($c['name']) . ' - Contact Profile';
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Contact Profile</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header d-flex justify-content-between align-items-center">
  <div class="page-header-left">
    <h1><?= e($c['name']) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="index.php">Contacts</a></li><li class="breadcrumb-item active"><?= e($c['name']) ?></li></ol></nav>
  </div>
  <div class="page-header-right">
    <a href="edit.php?id=<?= $id ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit Profile</a>
  </div>
</div>

<div class="row g-4">
  <!-- Left Side: Profile -->
  <div class="col-12 col-lg-4">
    <div class="crm-card mb-4 text-center">
      <div class="crm-card-body pt-4">
        <div class="stat-icon primary mx-auto mb-3" style="width:80px;height:80px;border-radius:20px;font-size:32px;">
          <?= strtoupper(substr($c['name'],0,1)) ?>
        </div>
        <h3 class="mb-1"><?= e($c['name']) ?></h3>
        <p class="text-muted mb-2"><?= e($c['contact_type']) ?></p>
        <span class="badge bg-primary px-3 py-2 rounded-pill"><?= e($c['contact_type']) ?></span>
      </div>
    </div>
    
    <div class="crm-card mb-4">
      <div class="crm-card-header"><h3 class="crm-card-title">Contact Information</h3></div>
      <div class="crm-card-body">
        <?php if($c['organization_name']): ?>
          <div class="d-flex gap-3 mb-3">
            <div class="text-muted" style="width:20px"><i class="bi bi-building"></i></div>
            <div><div class="text-muted small">Organization</div><div class="fw-semibold small"><?= e($c['organization_name']) ?></div></div>
          </div>
        <?php endif; ?>
        <?php if($c['mobile']): ?>
          <div class="d-flex gap-3 mb-3">
            <div class="text-muted" style="width:20px"><i class="bi bi-telephone"></i></div>
            <div><div class="text-muted small">Mobile</div><div class="fw-semibold small"><a href="tel:<?= e($c['mobile']) ?>"><?= e($c['mobile']) ?></a></div></div>
          </div>
        <?php endif; ?>
        <?php if($c['whatsapp']): ?>
          <div class="d-flex gap-3 mb-3">
            <div class="text-muted" style="width:20px"><i class="bi bi-whatsapp text-success"></i></div>
            <div><div class="text-muted small">WhatsApp</div><div class="fw-semibold small"><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$c['whatsapp']) ?>" class="text-success" target="_blank"><?= e($c['whatsapp']) ?></a></div></div>
          </div>
        <?php endif; ?>
        <?php if($c['email']): ?>
          <div class="d-flex gap-3 mb-3">
            <div class="text-muted" style="width:20px"><i class="bi bi-envelope"></i></div>
            <div><div class="text-muted small">Email</div><div class="fw-semibold small"><a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a></div></div>
          </div>
        <?php endif; ?>
        <?php if($c['website']): ?>
          <div class="d-flex gap-3 mb-3">
            <div class="text-muted" style="width:20px"><i class="bi bi-globe"></i></div>
            <div><div class="text-muted small">Website</div><div class="fw-semibold small"><a href="<?= e($c['website']) ?>" target="_blank"><?= e($c['website']) ?></a></div></div>
          </div>
        <?php endif; ?>
        <?php if($c['city']): ?>
          <div class="d-flex gap-3 mb-0">
            <div class="text-muted" style="width:20px"><i class="bi bi-geo-alt"></i></div>
            <div><div class="text-muted small">Location</div><div class="fw-semibold small"><?= implode(', ', array_filter([e($c['city']), e($c['state'])])) ?></div></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Right Side: Dashboard & Tabs -->
  <div class="col-12 col-lg-8">
    <div class="row g-3 mb-4">
      <div class="col-md-6 col-xl-4">
        <div class="crm-stat-card primary">
          <div class="crm-stat-icon"><i class="bi bi-people"></i></div>
          <div class="crm-stat-content">
            <div class="crm-stat-label">Total Customers</div>
            <div class="crm-stat-value"><?= $totalCustomers ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4">
        <div class="crm-stat-card info">
          <div class="crm-stat-icon"><i class="bi bi-funnel"></i></div>
          <div class="crm-stat-content">
            <div class="crm-stat-label">Total Leads</div>
            <div class="crm-stat-value"><?= $totalLeads ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4">
        <div class="crm-stat-card success">
          <div class="crm-stat-icon"><i class="bi bi-cash-stack"></i></div>
          <div class="crm-stat-content">
            <div class="crm-stat-label">Business Value</div>
            <div class="crm-stat-value">₹0</div> <!-- Placeholder for Quote Logic -->
          </div>
        </div>
      </div>
    </div>

    <div class="crm-card">
      <div class="crm-card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs px-4 pt-3" id="contactTabs">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabCustomers">Associated Customers</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabLeads">Associated Leads</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabPerformance">Performance</button></li>
        </ul>
      </div>
      <div class="crm-card-body p-0">
        <div class="tab-content" id="contactTabsContent">
          <!-- Customers Tab -->
          <div class="tab-pane fade show active" id="tabCustomers">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Customer Name</th><th>Status</th><th>Role</th><th>Actions</th></tr></thead>
                <tbody>
                  <?php if (empty($linkedCustomers)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No associated customers found</td></tr>
                  <?php else: ?>
                    <?php foreach ($linkedCustomers as $lc): ?>
                    <tr>
                      <td class="fw-semibold"><a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $lc['id'] ?>"><?= e($lc['company_name'] ?: 'Customer') ?></a></td>
                      <td><?= statusBadge($lc['company_status'] ?? '') ?></td>
                      <td><span class="badge bg-light text-dark border"><?= e($lc['role']) ?></span></td>
                      <td><a href="<?= BASE_URL ?>/modules/customers/view.php?id=<?= $lc['id'] ?>" class="btn btn-sm btn-outline-info">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- Leads Tab -->
          <div class="tab-pane fade" id="tabLeads">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Lead / Project</th><th>Status</th><th>Role</th><th>Actions</th></tr></thead>
                <tbody>
                  <?php if (empty($linkedLeads)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No associated leads found</td></tr>
                  <?php else: ?>
                    <?php foreach ($linkedLeads as $ll): ?>
                    <tr>
                      <td class="fw-semibold"><a href="<?= BASE_URL ?>/modules/prospects/view.php?id=<?= $ll['id'] ?>"><?= e($ll['company_name'] ?: 'Lead') ?></a></td>
                      <td><?= statusBadge($ll['lead_status'] ?? '') ?></td>
                      <td><span class="badge bg-light text-dark border"><?= e($ll['role']) ?></span></td>
                      <td><a href="<?= BASE_URL ?>/modules/prospects/view.php?id=<?= $ll['id'] ?>" class="btn btn-sm btn-outline-info">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- Performance Tab -->
          <div class="tab-pane fade p-4" id="tabPerformance">
            <div class="row text-center">
              <div class="col-md-4 mb-3">
                <h4 class="fw-bold text-primary">0</h4>
                <div class="text-muted small">Quotations Won</div>
              </div>
              <div class="col-md-4 mb-3">
                <h4 class="fw-bold text-success">0%</h4>
                <div class="text-muted small">Win Ratio</div>
              </div>
              <div class="col-md-4 mb-3">
                <h4 class="fw-bold text-info">₹0</h4>
                <div class="text-muted small">Avg. Deal Size</div>
              </div>
            </div>
            <hr>
            <p class="text-muted text-center small mt-3"><i class="bi bi-info-circle me-1"></i>Advanced analytics will populate as quotations and orders are processed through this contact.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>