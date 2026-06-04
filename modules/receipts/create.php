<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Record Receipt';
$errors = [];
$db = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $customer_id = (int)($_POST['customer_id'] ?? 0) ?: null;
    $category_id = (int)($_POST['category_id'] ?? 0) ?: null;
    $amount = (float)($_POST['amount'] ?? 0);
    $receipt_date = $_POST['receipt_date'] ?: date('Y-m-d');
    $payment_method = $_POST['payment_method'] ?: 'cash';
    $reference = sanitize($_POST['reference'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (!$title) $errors['title'] = 'Title is required.';
    if ($amount <= 0) $errors['amount'] = 'Valid amount is required.';
    if (!$category_id) $errors['category_id'] = 'Please select a category.';

    // --- Handle file upload ---
    $attachment = null;
    if (!empty($_FILES['attachment']['name'])) {
        $file     = $_FILES['attachment'];
        $origName = $file['name'];
        $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','gif','webp','pdf','doc','docx','xls','xlsx','csv','txt'];
        $maxSize  = 10 * 1024 * 1024; // 10 MB

        if (!in_array($ext, $allowed)) {
            $errors['attachment'] = 'Invalid file type. Allowed: JPG, PNG, PDF, DOC, XLS, TXT.';
        } elseif ($file['size'] > $maxSize) {
            $errors['attachment'] = 'File too large. Maximum size is 10 MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['attachment'] = 'Upload error. Please try again.';
        } else {
            $uploadDir = __DIR__ . '/../../uploads/receipts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
            $newName   = 'REC_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                $attachment = $newName;
            } else {
                $errors['attachment'] = 'Failed to save file. Please try again.';
            }
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("INSERT INTO receipts (title, customer_id, category_id, amount, receipt_date, payment_method, reference, description, attachment, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $customer_id, $category_id, $amount, $receipt_date, $payment_method, $reference, $description, $attachment, $_SESSION['user_id']]);
            
            setFlash('success', 'Money receipt recorded successfully.');
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            if ($attachment) @unlink(__DIR__ . '/../../uploads/receipts/' . $attachment);
            $errors['db'] = "Error: " . $e->getMessage();
        }
    }
}

$categories = $db->query("SELECT * FROM receipt_categories WHERE is_active=1 ORDER BY name ASC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Record Receipt</div>
</div>
<div class="page-content">

<div class="page-header">
  <div class="page-header-left">
    <h1>Record Receipt</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Receipts</a></li>
        <li class="breadcrumb-item active">Record</li>
      </ol>
    </nav>
  </div>
  <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="crm-card">
      <div class="crm-card-body p-4">
        <form method="POST" enctype="multipart/form-data" id="receiptForm">
          <div class="mb-3">
            <label class="form-label fw-bold">Title *</label>
            <input type="text" name="title" class="form-control <?= isset($errors['title'])?'is-invalid':'' ?>" value="<?= e($_POST['title']??'') ?>" placeholder="e.g. Advance from client" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Receipt Date *</label>
                <input type="date" name="receipt_date" class="form-control" value="<?= e($_POST['receipt_date']??date('Y-m-d')) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Amount *</label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input type="number" name="amount" class="form-control <?= isset($errors['amount'])?'is-invalid':'' ?>" value="<?= e($_POST['amount']??'') ?>" step="0.01" placeholder="0.00" required>
                </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Category *</label>
                <select name="category_id" class="form-select <?= isset($errors['category_id'])?'is-invalid':'' ?>" required>
                    <option value="">- Select Category -</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id']??'')==$cat['id']?'selected':'' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Client / Customer <small class="text-muted">(type to search • <span class="badge bg-dark">F2</span>)</small></label>
                <div class="input-group position-relative">
                    <input type="text" id="customer_search" class="form-control" placeholder="Search Customer..." autocomplete="off">
                    <div id="search_results" class="list-group position-absolute shadow w-100 d-none" style="z-index: 1000; top: 100%; max-height: 200px; overflow-y: auto;"></div>
                </div>
                <input type="hidden" name="customer_id" id="customer_id" value="<?= e($_POST['customer_id']??'') ?>">
                <div id="selection_badge" class="mt-2 d-none">
                    <span class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                        <i class="bi bi-person-check-fill me-2 text-success"></i>
                        <span id="selected_name"></span>
                        <button type="button" class="btn-close ms-2" style="font-size: 0.6rem;" onclick="clearSelection()"></button>
                    </span>
                </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <?php 
                    $methods = ['cash'=>'💵 Cash', 'bank_transfer'=>'🏦 Bank Transfer', 'upi'=>'📱 UPI', 'card'=>'💳 Card', 'cheque'=>'📄 Cheque', 'other'=>'Other'];
                    foreach($methods as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= ($_POST['payment_method']??'cash')==$val?'selected':'' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Reference / Bill Number</label>
                <input type="text" name="reference" class="form-control" value="<?= e($_POST['reference']??'') ?>" placeholder="e.g. INV-2024-001 or TXN ID">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Notes</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Additional notes or description..."><?= e($_POST['description']??'') ?></textarea>
          </div>

          <hr class="my-4">
          <div class="d-flex justify-content-end gap-2">
            <a href="index.php" class="btn btn-outline-secondary px-4">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Record Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="crm-card h-auto">
      <div class="crm-card-body p-4">
        <h6 class="fw-bold mb-1"><i class="bi bi-paperclip text-primary me-2"></i>Supporting Document</h6>
        <p class="text-muted small mb-3">Attach a scan copy, invoice photo, or receipt. Accepted: JPG, PNG, PDF, DOC, XLS (max 10 MB)</p>

        <?php if(isset($errors['attachment'])): ?>
          <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-triangle me-1"></i><?= $errors['attachment'] ?></div>
        <?php endif; ?>

        <!-- Drop Zone -->
        <div class="upload-dropzone" id="dropZone" onclick="document.getElementById('attachmentInput').click()">
          <div class="upload-dropzone-inner" id="dropZoneInner">
            <i class="bi bi-cloud-arrow-up-fill upload-dropzone-icon"></i>
            <div class="fw-semibold mt-2">Click or drag & drop</div>
            <div class="text-muted small mt-1">Scan copy, bill, receipt...</div>
          </div>
          <div id="filePreview" class="d-none text-center w-100">
            <div id="previewImg" class="d-none mb-2">
              <img id="previewImgEl" src="" alt="Preview" class="img-thumbnail" style="max-height:180px;max-width:100%;object-fit:contain;">
            </div>
            <div id="previewDoc" class="d-none mb-2">
              <i class="bi bi-file-earmark-text text-primary" style="font-size:3rem;"></i>
            </div>
            <div id="previewPdf" class="d-none mb-2">
              <i class="bi bi-file-earmark-pdf text-danger" style="font-size:3rem;"></i>
            </div>
            <div class="fw-semibold small text-dark px-2 text-truncate" id="fileName"></div>
            <div class="text-muted small" id="fileSize"></div>
            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearFile(event)"><i class="bi bi-x-circle me-1"></i>Remove</button>
          </div>
        </div>

        <input type="file" id="attachmentInput" name="attachment" form="receiptForm"
               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt"
               class="d-none" onchange="handleFileSelect(this)">

        <div class="mt-3 p-3 rounded-2" style="background:var(--surface-secondary,#f8f9fa);border:1px dashed #dee2e6;">
          <div class="small text-muted fw-semibold mb-1"><i class="bi bi-shield-check text-success me-1"></i>Accepted File Types</div>
          <div class="d-flex flex-wrap gap-1 mt-1">
            <?php foreach(['PDF','JPG','PNG','DOC','DOCX','XLS','XLSX','CSV','TXT'] as $ft): ?>
              <span class="badge bg-light text-secondary border" style="font-size:.7rem;"><?= $ft ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div></div>

<!-- Quick Add Customer Modal -->
<div class="modal fade" id="quickAddCustomerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="quickCustomerForm">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Quick Add Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Customer Name *</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Company Name</label>
              <input type="text" name="company" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="btnSaveCustomer">Save & Select</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.upload-dropzone {
  border: 2px dashed #c7d0e2;
  border-radius: 12px;
  padding: 2rem 1rem;
  text-align: center;
  cursor: pointer;
  transition: border-color .2s, background .2s;
  background: #fafbff;
  min-height: 160px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.upload-dropzone:hover, .upload-dropzone.dragover {
  border-color: var(--primary, #4361ee);
  background: #f0f3ff;
}
.upload-dropzone-icon { font-size: 2.5rem; color: #c0c8e6; }
.upload-dropzone:hover .upload-dropzone-icon { color: var(--primary, #4361ee); }
</style>

<script>
function clearSelection() {
    document.getElementById('customer_id').value = '';
    document.getElementById('selected_name').textContent = '';
    document.getElementById('selection_badge').classList.add('d-none');
    document.getElementById('customer_search').value = '';
    document.getElementById('customer_search').classList.remove('d-none');
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('customer_search');
    const searchResults = document.getElementById('search_results');
    const withIdInput = document.getElementById('customer_id');
    const selectionBadge = document.getElementById('selection_badge');
    const selectedName = document.getElementById('selected_name');

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        if (query.length < 2) {
            searchResults.classList.add('d-none');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/modules/customers/ajax_search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'list-group-item list-group-item-action cursor-pointer p-3';
                            div.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold fs-6">${item.company || item.name}</span>
                                        ${item.company ? `<span class="text-muted ms-2">(${item.name})</span>` : ''}
                                    </div>
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">${item.customer_type || 'CUSTOMER'}</small>
                                </div>
                            `;
                            div.onclick = function() {
                                selectEntity(item.id, item.name);
                            };
                            searchResults.appendChild(div);
                        });
                        searchResults.classList.remove('d-none');
                    } else {
                        searchResults.innerHTML = '<div class="list-group-item text-muted">No results found. Press F2 to add.</div>';
                        searchResults.classList.remove('d-none');
                    }
                });
        }, 300);
    });

    function selectEntity(id, name) {
        withIdInput.value = id;
        selectedName.textContent = name;
        selectionBadge.classList.remove('d-none');
        searchInput.classList.add('d-none');
        searchResults.classList.add('d-none');
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            new bootstrap.Modal(document.getElementById('quickAddCustomerModal')).show();
        }
    });

    // Quick Add Customer Logic
    const quickForm = document.getElementById('quickCustomerForm');
    quickForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveCustomer');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const formData = new FormData(this);
        fetch('<?= BASE_URL ?>/modules/customers/ajax_create.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectEntity(data.customer.id, data.customer.name);
                bootstrap.Modal.getInstance(document.getElementById('quickAddCustomerModal')).hide();
                quickForm.reset();
            } else {
                alert(data.error || 'Failed to save customer');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Save & Select';
        });
    });

    // File Upload Logic
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('attachmentInput');

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
      e.preventDefault();
      dropZone.classList.remove('dragover');
      if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        handleFileSelect(fileInput);
      }
    });
});

function handleFileSelect(input) {
  const file = input.files[0];
  if (!file) return;
  const ext = file.name.split('.').pop().toLowerCase();
  const inner = document.getElementById('dropZoneInner');
  const preview = document.getElementById('filePreview');
  const imgEl = document.getElementById('previewImgEl');
  const imgWrap = document.getElementById('previewImg');
  const docWrap = document.getElementById('previewDoc');
  const pdfWrap = document.getElementById('previewPdf');

  imgWrap.classList.add('d-none');
  docWrap.classList.add('d-none');
  pdfWrap.classList.add('d-none');

  if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
    const reader = new FileReader();
    reader.onload = e => { imgEl.src = e.target.result; imgWrap.classList.remove('d-none'); };
    reader.readAsDataURL(file);
  } else if (ext === 'pdf') {
    pdfWrap.classList.remove('d-none');
  } else {
    docWrap.classList.remove('d-none');
  }

  document.getElementById('fileName').textContent = file.name;
  document.getElementById('fileSize').textContent = formatBytes(file.size);
  inner.classList.add('d-none');
  preview.classList.remove('d-none');
}

function clearFile(e) {
  e.stopPropagation();
  document.getElementById('attachmentInput').value = '';
  document.getElementById('dropZoneInner').classList.remove('d-none');
  document.getElementById('filePreview').classList.add('d-none');
}

function formatBytes(b) {
  if (b >= 1048576) return (b/1048576).toFixed(1)+' MB';
  if (b >= 1024)    return (b/1024).toFixed(1)+' KB';
  return b+' B';
}
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
