<?php if (isAdmin()): ?>
<style>
    #leadTypeMasterModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    #leadTypeMasterModal .modal-header {
        background: linear-gradient(135deg, #334155 0%, #0f172a 100%);
        padding: 1.5rem;
        border-bottom: 0;
    }
    #leadTypeMasterModal .modal-title {
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    #leadTypeMasterModal .modal-body {
        padding: 2rem;
        background-color: #f8fafc;
    }
    .type-form-container {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .type-form-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .type-table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    #leadTypeMasterModal .table thead th {
        background-color: #f1f5f9;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    #leadTypeMasterModal .table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }
    .type-badge-preview {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-type-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
    }
    .btn-type-action:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateY(-1px);
    }
    .btn-type-action.delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fee2e2;
    }
</style>

<div class="modal fade" id="leadTypeMasterModal" tabindex="-1" aria-labelledby="leadTypeMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="leadTypeMasterModalLabel">
                    <i class="bi bi-tag-fill me-2 text-warning"></i>Lead Type Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="type-form-container">
                    <div class="type-form-title" id="typeFormActionTitle">
                        <i class="bi bi-plus-circle-fill"></i> Add New Lead Type
                    </div>
                    <form id="leadTypeForm" class="row g-3">
                        <input type="hidden" name="id" id="type_id">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Type Name</label>
                            <input type="text" name="type_name" id="type_name" class="form-control shadow-sm" placeholder="e.g. Website Inquiry" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Label Color</label>
                            <input type="color" name="color_code" id="type_color_code" class="form-control color-swatch-input w-100 shadow-sm" value="#6366f1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sort</label>
                            <input type="number" name="sort_order" id="type_sort_order" class="form-control shadow-sm" value="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100 shadow-sm py-2 fw-bold">
                                <i class="bi bi-save2"></i> Save
                            </button>
                        </div>
                    </form>
                </div>

                <div class="type-table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="80">Order</th>
                                    <th>Type Identity</th>
                                    <th>Color Code</th>
                                    <th class="text-end" width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="leadTypeList">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
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
        
        // ONLY open Lead Type Master if the Lead Type dropdown is focused
        if (focused && focused.name === 'lead_type') {
            e.preventDefault();
            const modalEl = document.getElementById('leadTypeMasterModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                loadLeadTypes();
            }
        }
    }
});

function loadLeadTypes() {
    fetch('<?= BASE_URL ?>/modules/prospects/lead_type_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('leadTypeList');
                tbody.innerHTML = '';
                data.data.forEach(type => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="fw-bold text-muted">${type.sort_order}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="type-badge-preview" style="background-color: ${type.color_code}">
                                        ${type.type_name}
                                    </span>
                                </div>
                            </td>
                            <td><code class="small text-primary">${type.color_code}</code></td>
                            <td class="text-end">
                                <button class="btn-type-action me-1" onclick="editLeadType(${type.id}, '${type.type_name}', '${type.color_code}', ${type.sort_order})" title="Edit Type">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn-type-action delete" onclick="deleteLeadType(${type.id})" title="Delete Type">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        });
}

function editLeadType(id, name, color, order) {
    document.getElementById('type_id').value = id;
    document.getElementById('type_name').value = name;
    document.getElementById('type_color_code').value = color;
    document.getElementById('type_sort_order').value = order;
    
    document.getElementById('typeFormActionTitle').innerHTML = '<i class="bi bi-pencil-fill text-warning"></i> Edit Lead Type';
    document.getElementById('typeFormActionTitle').classList.add('text-warning');
    
    document.getElementById('type_name').focus();
}

function deleteLeadType(id) {
    if (confirm('Are you sure you want to delete this lead type?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('<?= BASE_URL ?>/modules/prospects/lead_type_master_ajax.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLeadTypes();
                refreshLeadTypeDropdowns();
            } else {
                alert(data.message);
            }
        });
    }
}

document.getElementById('leadTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('<?= BASE_URL ?>/modules/prospects/lead_type_master_ajax.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.reset();
            document.getElementById('type_id').value = '';
            document.getElementById('typeFormActionTitle').innerHTML = '<i class="bi bi-plus-circle-fill"></i> Add New Lead Type';
            document.getElementById('typeFormActionTitle').classList.remove('text-warning');
            loadLeadTypes();
            refreshLeadTypeDropdowns();
        } else {
            alert(data.message);
        }
    });
});

function refreshLeadTypeDropdowns() {
    const selectors = ['select[name="lead_type"]'];
    
    fetch('<?= BASE_URL ?>/modules/prospects/lead_type_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectors.forEach(selector => {
                    const dropdowns = document.querySelectorAll(selector);
                    dropdowns.forEach(select => {
                        const currentValue = select.value;
                        select.innerHTML = '<option value="">Select Type</option>';
                        data.data.forEach(type => {
                            const selected = type.type_name === currentValue ? 'selected' : '';
                            select.innerHTML += `<option value="${type.type_name}" ${selected}>${type.type_name}</option>`;
                        });
                    });
                });
            }
        });
}
</script>
<?php endif; ?>
