<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Add Master Contact';
include __DIR__ . '/../../includes/header.php';
$contactTypes = ['Architect', 'Builder', 'Interior Designer', 'Contractor', 'Consultant', 'Developer', 'PMC', 'Fabricator', 'Vendor', 'Channel Partner', 'Owner', 'Other'];
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Add Contact</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1>Create New Master Contact</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/contacts/index.php">Contacts</a></li><li class="breadcrumb-item active">Add</li></ol></nav>
  </div>
</div>

<form action="save.php" method="POST" enctype="multipart/form-data">
  <div class="crm-card mb-4">
    <div class="crm-card-header"><h2 class="crm-card-title">Contact Information</h2></div>
    <div class="crm-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Contact Type <span class="text-danger">*</span></label>
          <select name="contact_type" class="form-select" required>
            <?php foreach($contactTypes as $t): ?>
              <option value="<?= $t ?>"><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Organization / Company Name</label>
          <input type="text" name="organization_name" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Mobile</label>
          <input type="text" name="mobile" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        
        <div class="col-12"><hr class="my-2"></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Address</label>
          <textarea name="address" class="form-control" rows="2"></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">City</label>
          <input type="text" name="city" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">State</label>
          <input type="text" name="state" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Pincode</label>
          <input type="text" name="pincode" class="form-control">
        </div>

        <div class="col-12"><hr class="my-2"></div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Website</label>
          <input type="text" name="website" class="form-control" placeholder="www.example.com">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">GST Number</label>
          <input type="text" name="gst_number" class="form-control">
        </div>
        <div class="col-12"><hr class="my-2"></div>
        <div class="col-12">
          <label class="form-label fw-semibold d-block">Visiting Card (Select multiple if needed)</label>
          <div class="d-flex gap-2 mb-2">
            <label class="btn btn-outline-primary flex-grow-1 text-center" style="cursor: pointer;">
              <i class="bi bi-folder2 me-2"></i>Upload from Device
              <input type="file" name="visiting_card[]" class="d-none" multiple accept="image/*" onchange="previewCardFiles(this)">
            </label>
            <label class="btn btn-outline-secondary flex-grow-1 text-center" style="cursor: pointer;">
              <i class="bi bi-camera me-2"></i>Take Photo
              <input type="file" name="visiting_card[]" class="d-none" multiple accept="image/*" capture="environment" onchange="previewCardFiles(this)">
            </label>
          </div>
          <div id="visiting_cards_new_preview" class="d-flex gap-2 flex-wrap mb-2"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="text-end pb-4">
    <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary px-4">Save Contact</button>
  </div>
</form>
</div>
</div>
<script>
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
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>