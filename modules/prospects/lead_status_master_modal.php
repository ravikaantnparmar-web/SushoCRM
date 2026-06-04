<?php if (isAdmin()): ?>
<style>
/* ─── Premium Enterprise Lead Status Master ──────────────────────── */
#leadStatusMasterModal .modal-content {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    background: #ffffff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

#leadStatusMasterModal .modal-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-bottom: 1px solid rgba(255,255,255,0.05);
    padding: 0.75rem 1.25rem;
    position: sticky;
    top: 0;
    z-index: 1060;
}

#leadStatusMasterModal .modal-title {
    color: #f8fafc;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 8px;
}

#leadStatusMasterModal .modal-title i {
    color: #818cf8;
    font-size: 0.95rem;
}

#leadStatusMasterModal .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.8;
    transition: all 0.2s ease;
}

#leadStatusMasterModal .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}

#leadStatusMasterModal .modal-body {
    padding: 0;
    background: #f8fafc;
}

/* Toolbar Form Section */
.status-toolbar {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 1.25rem;
    position: sticky;
    top: 0;
    z-index: 1050;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.status-form-title {
    font-size: 0.6rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-form-title.editing {
    color: #6366f1;
}

/* Compact Toolbar Row */
.toolbar-row {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.toolbar-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.toolbar-field label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #475569;
    padding-left: 2px;
}

.toolbar-input {
    height: 32px;
    font-size: 0.8rem;
    padding: 0 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #ffffff;
    color: #1e293b;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.toolbar-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.toolbar-color {
    width: 42px;
    padding: 1px;
    cursor: pointer;
}

.toolbar-sort {
    width: 54px;
}

.toolbar-btn {
    height: 32px;
    padding: 0 14px;
    background: #6366f1;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    cursor: pointer;
    white-space: nowrap;
}

.toolbar-btn:hover {
    background: #4f46e5;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(99, 102, 241, 0.35);
}

/* Table Area */
.status-list-container {
    padding: 0.85rem 1.25rem 1.25rem;
}

.table-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.6rem;
}

.search-wrapper {
    position: relative;
    width: 240px;
}

.search-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
}

.search-input {
    width: 100%;
    height: 30px;
    padding: 0 10px 0 32px;
    font-size: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: #fff;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.premium-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.premium-table thead th {
    background: #f8fafc;
    padding: 0.5rem 0.85rem;
    font-size: 0.6rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.premium-table tbody tr {
    transition: background 0.15s ease;
}

.premium-table tbody tr:hover {
    background: #f8fafc;
}

.premium-table td {
    padding: 0.45rem 0.85rem;
    font-size: 0.8rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.premium-table tr:last-child td {
    border-bottom: none;
}

/* Status Identity Badge */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.drag-handle {
    color: #cbd5e1;
    cursor: grab;
    padding-right: 8px;
    font-size: 1rem;
}

.color-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

/* Action Icon Buttons */
.action-btn-group {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
}

.icon-action-btn {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    transition: all 0.2s;
    cursor: pointer;
}

.icon-action-btn:hover {
    background: #f8fafc;
    color: #6366f1;
    border-color: #6366f1;
    transform: translateY(-1px);
}

.icon-action-btn.delete:hover {
    background: #fef2f2;
    color: #ef4444;
    border-color: #ef4444;
}

/* Transitions */
.modal.fade .modal-dialog {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Empty State */
.empty-state-container {
    padding: 3rem;
    text-align: center;
    color: #94a3b8;
}

.empty-state-container i {
    font-size: 3rem;
    display: block;
    margin-bottom: 1rem;
    opacity: 0.5;
}
</style>

<div class="modal fade" id="leadStatusMasterModal" tabindex="-1" aria-labelledby="leadStatusMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadStatusMasterModalLabel">
                    <i class="bi bi-stack"></i> Lead Status Pipeline
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Premium Toolbar Form -->
                <div class="status-toolbar">
                    <div class="status-form-title" id="formActionTitle">
                        <i class="bi bi-plus-circle-fill"></i> Add New Pipeline Stage
                    </div>
                    <form id="leadStatusForm" class="toolbar-row">
                        <input type="hidden" name="id" id="status_id">
                        
                        <div class="toolbar-field flex-grow-1">
                            <label>Status Name</label>
                            <input type="text" name="status_name" id="status_name" class="toolbar-input" placeholder="e.g. Qualified Lead" required>
                        </div>
                        
                        <div class="toolbar-field flex-grow-1">
                            <label>Description</label>
                            <input type="text" name="description" id="status_description_master" class="toolbar-input" placeholder="Brief definition...">
                        </div>
                        
                        <div class="toolbar-field">
                            <label>Color</label>
                            <input type="color" name="color_code" id="color_code" class="toolbar-input toolbar-color" value="#6366f1">
                        </div>
                        
                        <div class="toolbar-field">
                            <label>Sort</label>
                            <input type="number" name="sort_order" id="sort_order" class="toolbar-input toolbar-sort" value="0">
                        </div>
                        
                        <button type="submit" class="toolbar-btn" id="saveStatusBtn">
                            <i class="bi bi-check-lg"></i> <span>Save</span>
                        </button>
                    </form>
                </div>

                <div class="status-list-container">
                    <div class="table-header-actions">
                        <div class="small text-muted fw-500">
                            Manage your lead lifecycle stages.
                        </div>
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" id="statusQuickFilter" class="search-input" placeholder="Search stages..." onkeyup="filterStatuses()">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th width="40"></th>
                                    <th width="80">Order</th>
                                    <th>Status Identity</th>
                                    <th>Definition</th>
                                    <th width="100" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="leadStatusList">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="statusEmptyState" class="empty-state-container d-none">
                        <i class="bi bi-inbox"></i>
                        <p>No status matches found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'F2') {
        const focused = document.activeElement;
        const leadStatusNames = ['status', 'lead_status', 'meeting_lead_status'];
        const isLeadStatusFocused = focused && leadStatusNames.includes(focused.name);
        
        if (!isLeadStatusFocused) return;

        e.preventDefault();
        const modalEl = document.getElementById('leadStatusMasterModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            loadLeadStatuses();
        }
    }
});

function loadLeadStatuses() {
    const listContainer = document.getElementById('leadStatusList');
    listContainer.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';

    fetch('<?= BASE_URL ?>/modules/prospects/lead_status_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderStatusTable(data.data);
            }
        });
}

let allStatuses = [];

function renderStatusTable(statuses) {
    allStatuses = statuses;
    const tbody = document.getElementById('leadStatusList');
    tbody.innerHTML = '';
    
    if (statuses.length === 0) {
        document.getElementById('statusEmptyState').classList.remove('d-none');
        return;
    }
    document.getElementById('statusEmptyState').classList.add('d-none');

    statuses.forEach(status => {
        tbody.innerHTML += `
            <tr data-status-name="${status.status_name.toLowerCase()}">
                <td class="text-center"><i class="bi bi-grip-vertical drag-handle"></i></td>
                <td class="fw-700 text-muted small">${status.sort_order}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="status-pill" style="background-color: ${status.color_code}">
                            ${status.status_name}
                        </span>
                    </div>
                </td>
                <td><span class="small text-muted">${status.description || '<span class="opacity-30">No definition provided</span>'}</span></td>
                <td class="text-end">
                    <div class="action-btn-group">
                        <button onclick="editStatus(${status.id}, '${status.status_name.replace(/'/g, "\\'")}', '${(status.description || '').replace(/'/g, "\\'")}', '${status.color_code}', ${status.sort_order})" class="icon-action-btn" title="Edit Stage">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="icon-action-btn delete" onclick="deleteLeadStatus(${status.id})" title="Remove Stage">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function filterStatuses() {
    const query = document.getElementById('statusQuickFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#leadStatusList tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-status-name');
        if (name && name.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyState = document.getElementById('statusEmptyState');
    if (visibleCount === 0) {
        emptyState.classList.remove('d-none');
    } else {
        emptyState.classList.add('d-none');
    }
}

function editStatus(id, name, description, color, order) {
    document.getElementById('status_id').value = id;
    document.getElementById('status_name').value = name;
    document.getElementById('status_description_master').value = description;
    document.getElementById('color_code').value = color;
    document.getElementById('sort_order').value = order;
    
    const title = document.getElementById('formActionTitle');
    title.innerHTML = '<i class="bi bi-pencil-square"></i> Update Lifecycle Stage';
    title.classList.add('editing');
    
    const saveBtn = document.getElementById('saveStatusBtn');
    saveBtn.innerHTML = '<i class="bi bi-arrow-up-circle"></i> <span>Update</span>';
    
    document.getElementById('status_name').focus();
    
    // Smooth scroll to top toolbar
    document.querySelector('.modal-body').scrollTo({top: 0, behavior: 'smooth'});
}

function deleteLeadStatus(id) {
    if (confirm('Are you sure you want to remove this stage? This might affect existing leads.')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('<?= BASE_URL ?>/modules/prospects/lead_status_master_ajax.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLeadStatuses();
                refreshLeadStatusDropdowns();
            } else {
                alert(data.message);
            }
        });
    }
}

document.getElementById('leadStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const saveBtn = document.getElementById('saveStatusBtn');
    const originalContent = saveBtn.innerHTML;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    saveBtn.disabled = true;

    fetch('<?= BASE_URL ?>/modules/prospects/lead_status_master_ajax.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.innerHTML = originalContent;
        saveBtn.disabled = false;
        
        if (data.success) {
            this.reset();
            document.getElementById('status_id').value = '';
            document.getElementById('formActionTitle').innerHTML = '<i class="bi bi-plus-circle-fill"></i> Add New Pipeline Stage';
            document.getElementById('formActionTitle').classList.remove('editing');
            saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> <span>Save</span>';
            
            loadLeadStatuses();
            refreshLeadStatusDropdowns();
        } else {
            alert(data.message);
        }
    });
});

function refreshLeadStatusDropdowns() {
    const selectors = [
        'select[name="status"]', 
        'select[name="lead_status"]', 
        'select[name="meeting_lead_status"]'
    ];
    
    fetch('<?= BASE_URL ?>/modules/prospects/lead_status_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectors.forEach(selector => {
                    const dropdowns = document.querySelectorAll(selector);
                    dropdowns.forEach(select => {
                        const currentValue = select.value;
                        const isFilter = select.name === 'status';
                        select.innerHTML = isFilter ? '<option value="">All Status</option>' : '<option value="">Select Status</option>';
                        
                        data.data.forEach(status => {
                            const selected = status.status_name === currentValue ? 'selected' : '';
                            select.innerHTML += `<option value="${status.status_name}" ${selected}>${status.status_name}</option>`;
                        });
                    });
                });
            }
        });
}
</script>
<?php endif; ?>
