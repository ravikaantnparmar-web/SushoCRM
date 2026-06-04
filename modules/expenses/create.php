<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Record Expense';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = sanitize($_POST['category_id'] ?? '');
    if (!$category_id && !empty($_POST['new_category'])) {
        $stmtCat = db()->prepare("INSERT INTO expense_categories (name) VALUES (?)");
        $stmtCat->execute([sanitize($_POST['new_category'])]);
        $category_id = db()->lastInsertId();
    }
    $title        = sanitize($_POST['title'] ?? 'Expense');
    $amount       = (float)($_POST['amount'] ?? 0);
    $expense_date = sanitize($_POST['expense_date'] ?? date('Y-m-d'));
    $reference    = sanitize($_POST['reference_number'] ?? '');
    $description  = sanitize($_POST['notes'] ?? '');

    if ($amount <= 0)   $errors['amount']      = 'Valid amount is required.';
    if (!$category_id)  $errors['category_id'] = 'Category is required.';
    if (!$title)        $errors['title']        = 'Title is required.';

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
            $uploadDir = __DIR__ . '/../../uploads/expenses/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
            $newName   = 'EXP_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                $attachment = $newName;
            } else {
                $errors['attachment'] = 'Failed to save file. Please try again.';
            }
        }
    }

    if (!$errors) {
        $stmt = db()->prepare("INSERT INTO expenses (category_id, title, amount, expense_date, reference, description, attachment, created_by) VALUES (?,?,?,?,?,?,?,?)");
        if ($stmt->execute([$category_id, $title, $amount, $expense_date?:null, $reference, $description, $attachment, $_SESSION['user_id']])) {
            $newId = db()->lastInsertId();
            logActivity('expenses','create',"Recorded expense of ".formatCurrency($amount),$newId);
            setFlash('success',"Expense recorded successfully.");
            header('Location: '.BASE_URL.'/modules/expenses/index.php');
            exit;
        } else {
            // If DB fails but file was uploaded, remove the orphaned file
            if ($attachment) @unlink(__DIR__ . '/../../uploads/expenses/' . $attachment);
            $errors['general'] = 'Failed to record expense.';
        }
    }
}

$categories = db()->query("SELECT id, name FROM expense_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Record Expense</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<?php if(isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
<div class="page-header">
  <div class="page-header-left"><h1>Record Expense</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/expenses/index.php">Expenses</a></li><li class="breadcrumb-item active">Record</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/expenses/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
  <!-- Main Form -->
  <div class="col-lg-8">
    <div class="crm-card">
      <div class="crm-card-body p-4">
        <form method="POST" enctype="multipart/form-data" id="expenseForm">
          <div class="row g-3">

            <div class="col-md-12">
              <label class="form-label">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control <?= isset($errors['title'])?'is-invalid':'' ?>" value="<?= e($_POST['title']??'') ?>" placeholder="e.g. Office Supplies - April" required>
              <?php if(isset($errors['title'])): ?><div class="invalid-feedback"><?= $errors['title'] ?></div><?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label">Date <span class="text-danger">*</span></label>
              <input type="date" name="expense_date" class="form-control" value="<?= e($_POST['expense_date']??date('Y-m-d')) ?>" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Amount <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="amount" class="form-control <?= isset($errors['amount'])?'is-invalid':'' ?>" value="<?= e($_POST['amount']??'') ?>" step="0.01" min="0.01" required>
                <?php if(isset($errors['amount'])): ?><div class="invalid-feedback"><?= $errors['amount'] ?></div><?php endif; ?>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select <?= isset($errors['category_id'])?'is-invalid':'' ?>" onchange="document.getElementById('newCatDiv').style.display = this.value==='_new'?'block':'none'">
                <option value="">— Select Category —</option>
                <?php foreach($categories as $cat): ?>
                  <option value="<?= e($cat['id']) ?>" <?= ($_POST['category_id']??'')==$cat['id']?'selected':'' ?>><?= e($cat['name']) ?></option>
                <?php endforeach; ?>
                <option value="_new" <?= ($_POST['category_id']??'')==='_new'?'selected':'' ?>>+ Add New Category</option>
              </select>
              <?php if(isset($errors['category_id'])): ?><div class="invalid-feedback"><?= $errors['category_id'] ?></div><?php endif; ?>
            </div>

            <div class="col-md-6" id="newCatDiv" style="display:<?= ($_POST['category_id']??'')==='_new'?'block':'none' ?>">
              <label class="form-label">New Category Name</label>
              <input type="text" name="new_category" class="form-control" value="<?= e($_POST['new_category']??'') ?>">
            </div>

            <div class="col-md-12">
              <label class="form-label">Reference / Bill Number</label>
              <input type="text" name="reference_number" class="form-control" value="<?= e($_POST['reference_number']??'') ?>" placeholder="e.g. INV-2024-001">
            </div>

            <div class="col-md-12">
              <label class="form-label">Notes</label>
              <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes or description..."><?= e($_POST['notes']??'') ?></textarea>
            </div>

          </div>
          <hr class="my-4">
          <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/modules/expenses/index.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Record Expense</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Attachment Panel -->
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
          <div id="filePreview" class="d-none text-center">
            <div id="previewImg" class="d-none mb-2">
              <img id="previewImgEl" src="" alt="Preview" class="img-thumbnail" style="max-height:180px;max-width:100%;object-fit:contain;">
            </div>
            <div id="previewDoc" class="d-none mb-2">
              <i class="bi bi-file-earmark-text text-primary" style="font-size:3rem;"></i>
            </div>
            <div id="previewPdf" class="d-none mb-2">
              <i class="bi bi-file-earmark-pdf text-danger" style="font-size:3rem;"></i>
            </div>
            <div class="fw-semibold small text-dark" id="fileName"></div>
            <div class="text-muted small" id="fileSize"></div>
            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearFile(event)"><i class="bi bi-x-circle me-1"></i>Remove</button>
          </div>
        </div>

        <!-- Hidden actual file input linked to the main form -->
        <input type="file" id="attachmentInput" name="attachment" form="expenseForm"
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
</div><!-- row -->

</div></div><!-- page-content / main-content -->

<style>
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
.upload-dropzone-icon {
  font-size: 2.5rem;
  color: #c0c8e6;
}
.upload-dropzone:hover .upload-dropzone-icon { color: var(--primary, #4361ee); }
.upload-dropzone-inner { width: 100%; }
#filePreview { width: 100%; }
</style>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('attachmentInput');

// Drag & drop handlers
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

  // Reset all previews
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
  fileInput.value = '';
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
