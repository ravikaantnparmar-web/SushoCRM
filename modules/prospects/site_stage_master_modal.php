<?php if (isAdmin()): ?>
<style>
    #siteStageMasterModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    #siteStageMasterModal .modal-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 1.5rem;
        border-bottom: 0;
    }
    #siteStageMasterModal .modal-title {
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    #siteStageMasterModal .modal-body {
        padding: 2rem;
        background-color: #f8fafc;
    }
    .stage-form-container {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .stage-form-title {
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
    .stage-table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    #siteStageMasterModal .table thead th {
        background-color: #f1f5f9;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    #siteStageMasterModal .table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }
    .stage-badge-preview {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-stage-action {
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
    .btn-stage-action:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateY(-1px);
    }
    .btn-stage-action.delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fee2e2;
    }
</style>

<div class="modal fade" id="siteStageMasterModal" tabindex="-1" aria-labelledby="siteStageMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="siteStageMasterModalLabel">
                    <i class="bi bi-layers-half me-2 text-info"></i>Site Stage Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="stage-form-container">
                    <div class="stage-form-title" id="stageFormActionTitle">
                        <i class="bi bi-plus-circle-fill"></i> Add New Site Stage
                    </div>
                    <form id="siteStageForm" class="row g-3">
                        <input type="hidden" name="id" id="stage_id">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Stage Name</label>
                            <input type="text" name="stage_name" id="stage_name" class="form-control shadow-sm" placeholder="e.g. Foundation Work" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Label Color</label>
                            <input type="color" name="color_code" id="stage_color_code" class="form-control color-swatch-input w-100 shadow-sm" value="#0ea5e9">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sort</label>
                            <input type="number" name="sort_order" id="stage_sort_order" class="form-control shadow-sm" value="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-info w-100 shadow-sm py-2 fw-bold text-white">
                                <i class="bi bi-save2"></i> Save
                            </button>
                        </div>
                    </form>
                </div>

                <div class="stage-table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="80">Order</th>
                                    <th>Stage Identity</th>
                                    <th>Color Code</th>
                                    <th class="text-end" width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="siteStageList">
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
        
        // ONLY open Site Stage Master if the Site Stage dropdown is focused
        if (focused && focused.name === 'site_stage') {
            e.preventDefault();
            const modalEl = document.getElementById('siteStageMasterModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                loadSiteStages();
            }
        }
    }
});

function loadSiteStages() {
    fetch('<?= BASE_URL ?>/modules/prospects/site_stage_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('siteStageList');
                tbody.innerHTML = '';
                data.data.forEach(stage => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="fw-bold text-muted">${stage.sort_order}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="stage-badge-preview" style="background-color: ${stage.color_code}">
                                        ${stage.stage_name}
                                    </span>
                                </div>
                            </td>
                            <td><code class="small text-info">${stage.color_code}</code></td>
                            <td class="text-end">
                                <button class="btn-stage-action me-1" onclick="editSiteStage(${stage.id}, '${stage.stage_name}', '${stage.color_code}', ${stage.sort_order})" title="Edit Stage">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn-stage-action delete" onclick="deleteSiteStage(${stage.id})" title="Delete Stage">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        });
}

function editSiteStage(id, name, color, order) {
    document.getElementById('stage_id').value = id;
    document.getElementById('stage_name').value = name;
    document.getElementById('stage_color_code').value = color;
    document.getElementById('stage_sort_order').value = order;
    
    document.getElementById('stageFormActionTitle').innerHTML = '<i class="bi bi-pencil-fill text-warning"></i> Edit Site Stage';
    document.getElementById('stageFormActionTitle').classList.add('text-warning');
    
    document.getElementById('stage_name').focus();
}

function deleteSiteStage(id) {
    if (confirm('Are you sure you want to delete this site stage?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('<?= BASE_URL ?>/modules/prospects/site_stage_master_ajax.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSiteStages();
                refreshSiteStageDropdowns();
            } else {
                alert(data.message);
            }
        });
    }
}

document.getElementById('siteStageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('<?= BASE_URL ?>/modules/prospects/site_stage_master_ajax.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.reset();
            document.getElementById('stage_id').value = '';
            document.getElementById('stageFormActionTitle').innerHTML = '<i class="bi bi-plus-circle-fill"></i> Add New Site Stage';
            document.getElementById('stageFormActionTitle').classList.remove('text-warning');
            loadSiteStages();
            refreshSiteStageDropdowns();
        } else {
            alert(data.message);
        }
    });
});

function refreshSiteStageDropdowns() {
    const selectors = ['select[name="site_stage"]'];
    
    fetch('<?= BASE_URL ?>/modules/prospects/site_stage_master_ajax.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectors.forEach(selector => {
                    const dropdowns = document.querySelectorAll(selector);
                    dropdowns.forEach(select => {
                        const currentValue = select.value;
                        select.innerHTML = '<option value="">Select Stage</option>';
                        data.data.forEach(stage => {
                            const selected = stage.stage_name === currentValue ? 'selected' : '';
                            select.innerHTML += `<option value="${stage.stage_name}" ${selected}>${stage.stage_name}</option>`;
                        });
                    });
                });
            }
        });
}
</script>
<?php endif; ?>
