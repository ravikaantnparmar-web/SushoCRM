<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

// All master tables grouped for the UI
$masterGroups = [
    'Lead Masters' => [
        'lead_statuses'      => ['col' => 'status_name',    'label' => 'Lead Statuses',          'has_color' => true],
        'lead_priorities'    => ['col' => 'priority_name',  'label' => 'Lead Priorities',         'has_color' => true],
        'lead_sources'       => ['col' => 'source_name',    'label' => 'Lead Sources',            'has_color' => true],
        'lead_types'         => ['col' => 'type_name',      'label' => 'Lead Types',              'has_color' => true],
        'site_stages'        => ['col' => 'stage_name',     'label' => 'Site Stages',             'has_color' => false],
        'project_types'      => ['col' => 'type_name',      'label' => 'Project Types',           'has_color' => false],
        'lead_product_types' => ['col' => 'type_name',      'label' => 'Product Types',           'has_color' => false],
        'sales_stages'       => ['col' => 'stage_name',     'label' => 'Sales Stages',            'has_color' => false],
    ],
    'Product Masters' => [
        'interested_products'=> ['col' => 'product_name',   'label' => 'Interested Products',     'has_color' => false],
    ],
    'Contact & Company' => [
        'contact_types'      => ['col' => 'type_name',      'label' => 'Contact Types / Designations', 'has_color' => false],
        'company_types'      => ['col' => 'type_name',      'label' => 'Company Types',           'has_color' => false],
        'industry_types'     => ['col' => 'type_name',      'label' => 'Industry Types',          'has_color' => false],
        'business_categories'=> ['col' => 'category_name',  'label' => 'Business Categories',     'has_color' => false],
        'company_statuses'   => ['col' => 'status_name',    'label' => 'Company Statuses',        'has_color' => true],
    ],
    'Meeting Masters' => [
        'meeting_types'      => ['col' => 'type_name',      'label' => 'Meeting Types',           'has_color' => false],
        'meeting_statuses'   => ['col' => 'status_name',    'label' => 'Meeting Statuses',        'has_color' => true],
    ],
    'Customer Masters' => [
        'customer_types'     => ['col' => 'type_name',      'label' => 'Customer Types',          'has_color' => false],
        'address_types'      => ['col' => 'type_name',      'label' => 'Address Types',           'has_color' => false],
    ],
];

$pageTitle = 'Masters Management';
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Masters Management</div>
</div>

<div class="page-content">
  <?= flashHtml() ?>
  <div class="page-header">
    <div class="page-header-left">
      <h1>Masters Management</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item">Leads</li>
          <li class="breadcrumb-item active">Masters</li>
        </ol>
      </nav>
    </div>
    <div class="page-header-right">
      <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Leads</a>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="crm-card mb-4">
    <div class="crm-card-body p-0">
      <ul class="nav nav-tabs px-3 pt-3 gap-1 flex-wrap" id="masterTabs" role="tablist">
        <?php $first = true; foreach ($masterGroups as $groupName => $tables): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= $first ? 'active' : '' ?>" 
                  id="tab-<?= md5($groupName) ?>" 
                  data-bs-toggle="tab" 
                  data-bs-target="#panel-<?= md5($groupName) ?>" 
                  type="button" role="tab">
            <?= e($groupName) ?>
          </button>
        </li>
        <?php $first = false; endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="tab-content" id="masterTabContent">
    <?php $first = true; foreach ($masterGroups as $groupName => $tables): ?>
    <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" 
         id="panel-<?= md5($groupName) ?>" role="tabpanel">
      <div class="row g-3">
        <?php foreach ($tables as $tableName => $meta): ?>
        <div class="col-md-6 col-xl-4">
          <div class="crm-card h-100">
            <div class="crm-card-header d-flex justify-content-between align-items-center">
              <h6 class="crm-card-title mb-0"><?= e($meta['label']) ?></h6>
              <?php if (isAdmin()): ?>
              <button class="btn btn-sm btn-primary" 
                      onclick="openAddModal('<?= $tableName ?>', '<?= e($meta['label']) ?>', '<?= $meta['col'] ?>', <?= $meta['has_color'] ? 'true' : 'false' ?>)">
                <i class="bi bi-plus-lg me-1"></i>Add
              </button>
              <?php endif; ?>
            </div>
            <div class="crm-card-body p-0">
              <div class="master-list" id="list-<?= $tableName ?>" 
                   data-table="<?= $tableName ?>" 
                   data-col="<?= $meta['col'] ?>"
                   data-has-color="<?= $meta['has_color'] ? '1' : '0' ?>">
                <div class="text-center text-muted py-3 small">
                  <div class="spinner-border spinner-border-sm" role="status"></div> Loading...
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php $first = false; endforeach; ?>
  </div>
</div>
</div>

<!-- Add / Edit Modal -->
<div class="modal fade" id="masterItemModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-0 px-4 py-3 bg-primary text-white">
        <h6 class="modal-title fw-bold" id="masterModalTitle">Add Master Item</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <input type="hidden" id="modal-table">
        <input type="hidden" id="modal-col">
        <input type="hidden" id="modal-id">
        <div class="mb-3">
          <label class="form-label fw-semibold small">NAME <span class="text-danger">*</span></label>
          <input type="text" id="modal-name" class="form-control" placeholder="Enter name">
        </div>
        <div class="mb-3" id="modal-color-row">
          <label class="form-label fw-semibold small">COLOR</label>
          <div class="d-flex gap-2 align-items-center">
            <input type="color" id="modal-color" class="form-control form-control-color" value="#64748b" style="width:50px;">
            <input type="text" id="modal-color-text" class="form-control form-control-sm" value="#64748b" maxlength="7">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold small">SORT ORDER</label>
          <input type="number" id="modal-order" class="form-control" value="0" min="0">
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="modal-active" checked>
          <label class="form-check-label small" for="modal-active">Active</label>
        </div>
      </div>
      <div class="modal-footer border-0 px-4 pb-3 pt-0 gap-2">
        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary flex-grow-1 fw-semibold" onclick="saveMasterItem()">
          <i class="bi bi-check2 me-1"></i>Save
        </button>
      </div>
    </div>
  </div>
</div>

<style>
.master-list-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  border-bottom: 1px solid var(--bs-border-color);
  font-size: 13px;
  transition: background 0.15s;
}
.master-list-item:last-child { border-bottom: none; }
.master-list-item:hover { background: #f8fafc; }
.master-list-item .color-dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  flex-shrink: 0;
}
.master-list-item .item-name { flex: 1; font-weight: 500; }
.master-list-item .item-actions { display: flex; gap: 4px; opacity: 0; transition: opacity 0.2s; }
.master-list-item:hover .item-actions { opacity: 1; }
.badge-inactive { opacity: 0.45; }
</style>

<script>
const BASE_URL = '<?= BASE_URL ?>';

// Load a master list by table name
function loadMasterList(table) {
  const container = document.getElementById('list-' + table);
  if (!container) return;
  const hasColor = container.dataset.hasColor === '1';
  
  fetch(`generic_master_ajax.php?action=list&table=${table}`)
    .then(r => r.json())
    .then(res => {
      if (!res.success) {
        container.innerHTML = `<div class="text-danger text-center py-3 small">${res.message}</div>`;
        return;
      }
      const col = container.dataset.col;
      if (!res.data.length) {
        container.innerHTML = '<div class="text-muted text-center py-3 small">No items yet. Click Add to create one.</div>';
        return;
      }
      container.innerHTML = res.data.map(item => `
        <div class="master-list-item ${item.is_active == 0 ? 'badge-inactive' : ''}" data-id="${item.id}">
          ${hasColor ? `<span class="color-dot" style="background:${item.color_code||'#94a3b8'}"></span>` : ''}
          <span class="item-name">${escHtml(item[col] || '')}</span>
          ${item.is_active == 0 ? '<span class="badge bg-secondary" style="font-size:9px;">Inactive</span>' : ''}
          <span class="item-actions">
            <button class="btn btn-xs btn-outline-primary border-0 px-1" style="font-size:11px;"
              onclick="editMasterItem('${table}', ${JSON.stringify(item).replace(/'/g,"&#39;")}, ${hasColor})">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-xs btn-outline-danger border-0 px-1" style="font-size:11px;"
              onclick="deactivateMasterItem('${table}', ${item.id}, this)">
              <i class="bi bi-dash-circle"></i>
            </button>
          </span>
        </div>
      `).join('');
    })
    .catch(() => {
      container.innerHTML = '<div class="text-danger text-center py-3 small">Load failed. Ensure migration has run.</div>';
    });
}

function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function openAddModal(table, label, col, hasColor) {
  document.getElementById('masterModalTitle').textContent = 'Add ' + label;
  document.getElementById('modal-table').value = table;
  document.getElementById('modal-col').value = col;
  document.getElementById('modal-id').value = '';
  document.getElementById('modal-name').value = '';
  document.getElementById('modal-color').value = '#64748b';
  document.getElementById('modal-color-text').value = '#64748b';
  document.getElementById('modal-order').value = '0';
  document.getElementById('modal-active').checked = true;
  document.getElementById('modal-color-row').style.display = hasColor ? 'block' : 'none';
  new bootstrap.Modal(document.getElementById('masterItemModal')).show();
}

function editMasterItem(table, item, hasColor) {
  const col = document.getElementById('list-' + table).dataset.col;
  document.getElementById('masterModalTitle').textContent = 'Edit Item';
  document.getElementById('modal-table').value = table;
  document.getElementById('modal-col').value = col;
  document.getElementById('modal-id').value = item.id;
  document.getElementById('modal-name').value = item[col] || '';
  document.getElementById('modal-color').value = item.color_code || '#64748b';
  document.getElementById('modal-color-text').value = item.color_code || '#64748b';
  document.getElementById('modal-order').value = item.sort_order || 0;
  document.getElementById('modal-active').checked = item.is_active == 1;
  document.getElementById('modal-color-row').style.display = hasColor ? 'block' : 'none';
  new bootstrap.Modal(document.getElementById('masterItemModal')).show();
}

function saveMasterItem() {
  const table  = document.getElementById('modal-table').value;
  const id     = document.getElementById('modal-id').value;
  const name   = document.getElementById('modal-name').value.trim();
  const color  = document.getElementById('modal-color').value;
  const order  = document.getElementById('modal-order').value;
  const active = document.getElementById('modal-active').checked ? 1 : 0;

  if (!name) { alert('Name is required.'); return; }

  const fd = new FormData();
  fd.append('action', 'save');
  fd.append('table', table);
  if (id) fd.append('id', id);
  fd.append('name', name);
  fd.append('color_code', color);
  fd.append('sort_order', order);
  fd.append('is_active', active);

  fetch('generic_master_ajax.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        bootstrap.Modal.getInstance(document.getElementById('masterItemModal'))?.hide();
        loadMasterList(table);
        // Also reload any related visible tabs
      } else {
        alert('Error: ' + res.message);
      }
    });
}

function deactivateMasterItem(table, id, btn) {
  if (!confirm('Deactivate this item? It will no longer appear in dropdowns.')) return;
  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('table', table);
  fd.append('id', id);
  fetch('generic_master_ajax.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        loadMasterList(table);
      } else {
        alert('Error: ' + res.message);
      }
    });
}

// Sync color picker and text
document.getElementById('modal-color')?.addEventListener('input', function() {
  document.getElementById('modal-color-text').value = this.value;
});
document.getElementById('modal-color-text')?.addEventListener('input', function() {
  if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
    document.getElementById('modal-color').value = this.value;
  }
});

// Load all visible tables on page load
document.addEventListener('DOMContentLoaded', () => {
  // Load all master lists
  <?php foreach ($masterGroups as $groupName => $tables): ?>
    <?php foreach ($tables as $tableName => $meta): ?>
      loadMasterList('<?= $tableName ?>');
    <?php endforeach; ?>
  <?php endforeach; ?>
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
