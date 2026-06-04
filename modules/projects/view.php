<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);

// Handle BOQ Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_boq') {
        $desc = sanitize($_POST['item_description'] ?? '');
        $unit = sanitize($_POST['unit'] ?? '');
        $est_qty = (float)($_POST['estimated_qty'] ?? 0);
        $est_rate = (float)($_POST['estimated_rate'] ?? 0);
        $est_amt = $est_qty * $est_rate;
        
        if ($desc) {
            $stmt = db()->prepare("INSERT INTO project_boq (project_id, item_description, unit, estimated_qty, estimated_rate, estimated_amount) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$id, $desc, $unit, $est_qty, $est_rate, $est_amt]);
            setFlash('success', 'BOQ item added.');
        }
    } elseif ($action === 'delete_boq') {
        $boq_id = (int)($_POST['boq_id'] ?? 0);
        db()->prepare("DELETE FROM project_boq WHERE id=? AND project_id=?")->execute([$boq_id, $id]);
        setFlash('success', 'BOQ item deleted.');
    } elseif ($action === 'update_actuals') {
        $boq_id = (int)($_POST['boq_id'] ?? 0);
        $act_qty = (float)($_POST['actual_qty'] ?? 0);
        $act_rate = (float)($_POST['actual_rate'] ?? 0);
        $act_amt = $act_qty * $act_rate;
        
        db()->prepare("UPDATE project_boq SET actual_qty=?, actual_rate=?, actual_amount=? WHERE id=? AND project_id=?")->execute([$act_qty, $act_rate, $act_amt, $boq_id, $id]);
    } elseif ($action === 'upload_image') {
        $uploaded_file = null;
        if (!empty($_FILES['image_file_camera']['name']) && $_FILES['image_file_camera']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = $_FILES['image_file_camera'];
        } elseif (!empty($_FILES['image_file_gallery']['name']) && $_FILES['image_file_gallery']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = $_FILES['image_file_gallery'];
        }

        if ($uploaded_file) {
            $desc = sanitize($_POST['image_description'] ?? '');
            
            $file = $uploaded_file;
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($ext, $allowed)) {
                $filename = 'proj_' . $id . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $dest = __DIR__ . '/../../uploads/projects/' . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $rel_path = 'uploads/projects/' . $filename;
                    $stmt = db()->prepare("INSERT INTO project_images (project_id, file_name, file_path, description, uploaded_by) VALUES (?,?,?,?,?)");
                    $stmt->execute([$id, $file['name'], $rel_path, $desc, $_SESSION['user_id']]);
                    setFlash('success', 'Image uploaded successfully.');
                } else {
                    setFlash('danger', 'Failed to move uploaded file.');
                }
            } else {
                setFlash('danger', 'Invalid file type or upload error.');
            }
        } else {
            setFlash('danger', 'No file selected.');
        }
    } elseif ($action === 'delete_image') {
        $image_id = (int)($_POST['image_id'] ?? 0);
        $stmt = db()->prepare("SELECT file_path FROM project_images WHERE id=? AND project_id=?");
        $stmt->execute([$image_id, $id]);
        $img = $stmt->fetch();
        if ($img) {
            $full_path = __DIR__ . '/../../' . $img['file_path'];
            if (file_exists($full_path)) unlink($full_path);
            db()->prepare("DELETE FROM project_images WHERE id=?")->execute([$image_id]);
            setFlash('success', 'Image deleted.');
        }
    }
    
    header('Location: '.BASE_URL.'/modules/projects/view.php?id='.$id);
    exit;
}

$stmt = db()->prepare("SELECT p.*, c.company_name AS customer_company, c.company_name AS customer_name, m.name AS manager_name, u.name AS creator_name FROM projects p LEFT JOIN customers c ON p.customer_id = c.id LEFT JOIN users m ON p.manager_id = m.id LEFT JOIN users u ON p.created_by = u.id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('danger','Project not found.'); header('Location: '.BASE_URL.'/modules/projects/index.php'); exit; }

// Fetch BOQ
$boq_stmt = db()->prepare("SELECT * FROM project_boq WHERE project_id = ? ORDER BY id ASC");
$boq_stmt->execute([$id]);
$boq_items = $boq_stmt->fetchAll();

// Fetch Images
$img_stmt = db()->prepare("SELECT i.*, u.name as uploader_name FROM project_images i LEFT JOIN users u ON i.uploaded_by = u.id WHERE i.project_id = ? ORDER BY i.created_at DESC");
$img_stmt->execute([$id]);
$project_images = $img_stmt->fetchAll();

$pageTitle = 'Project: ' . $p['name'];
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">View Project</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header mb-4">
  <div class="page-header-left">
    <h1 class="mb-1"><?= e($p['name']) ?> <span class="text-muted fs-5 ms-2">#<?= e($p['project_number']) ?></span></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/projects/index.php">Projects</a></li><li class="breadcrumb-item active"><?= e($p['project_number']) ?></li></ol></nav>
  </div>
  <div class="d-flex flex-column align-items-end gap-2">
    <div><?= statusBadge($p['status']) ?></div>
    <div>
        <?php
        $pClasses = ['low'=>'bg-info','medium'=>'bg-primary','high'=>'bg-warning text-dark','urgent'=>'bg-danger'];
        $pClass = $pClasses[$p['priority']] ?? 'bg-secondary';
        ?>
        <span class="badge <?= $pClass ?>"><i class="bi bi-flag-fill me-1"></i><?= ucfirst($p['priority']) ?> Priority</span>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="row g-4">
      <div class="col-12">
        <div class="crm-card h-100">
          <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Project Details</h5></div>
          <div class="crm-card-body p-4">
            <div class="row mb-3">
              <div class="col-sm-3 text-muted">Client / Customer</div>
              <div class="col-sm-9 fw-semibold">
                <?php if($p['customer_company']): ?>
                  <i class="bi bi-building me-1 text-primary"></i> <?= e($p['customer_company']) ?> (<?= e($p['customer_name']) ?>)
                <?php else: ?>
                  <span class="text-muted fst-italic">Internal Project</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3 text-muted">Project Type</div>
              <div class="col-sm-9"><?= e($p['project_type'] ?: '—') ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3 text-muted">Category</div>
              <div class="col-sm-9"><?= e($p['project_category'] ?: '—') ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3 text-muted">Description</div>
              <div class="col-sm-9"><div class="p-3 bg-light rounded" style="white-space:pre-line"><?= e($p['description'] ?: 'No description provided.') ?></div></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="crm-card h-100">
          <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Location & Site Details</h5></div>
          <div class="crm-card-body p-4">
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Site Address</div>
              <div class="col-sm-8">
                <?= e($p['site_address']) ?><br>
                <?= e(implode(', ', array_filter([$p['site_city'], $p['site_state'], $p['site_pincode']]))) ?>
                <?php if($p['google_maps_location']): ?>
                    <div class="mt-1"><a href="<?= e($p['google_maps_location']) ?>" target="_blank" class="small"><i class="bi bi-geo-alt-fill me-1"></i>View on Google Maps</a></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Site Contact Person</div>
              <div class="col-sm-8"><?= e($p['site_contact_person'] ?: '—') ?> <?php if($p['site_contact_number']): ?> <br><a href="tel:<?= e($p['site_contact_number']) ?>"><i class="bi bi-telephone-fill me-1"></i><?= e($p['site_contact_number']) ?></a><?php endif; ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Site Engineer</div>
              <div class="col-sm-8"><?= e($p['site_engineer_name_number'] ?: '—') ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="crm-card mb-4">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Timeline & Status</h5></div>
      <div class="crm-card-body p-4">
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Project Manager</span>
          <span class="fw-semibold"><?= e($p['manager_name'] ?: 'Unassigned') ?></span>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Project Stage</span>
          <span class="badge bg-secondary text-capitalize"><?= e($p['stage']) ?></span>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Expected Duration</span>
          <span class="fw-semibold"><?= e($p['expected_duration'] ?: '—') ?></span>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Start Date</span>
          <span class="fw-semibold"><?= $p['start_date'] ? formatDate($p['start_date']) : '—' ?></span>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Target End</span>
          <span class="fw-semibold <?= ($p['target_end_date'] && $p['target_end_date'] < date('Y-m-d') && $p['status'] !== 'completed') ? 'text-danger' : '' ?>">
            <?= $p['target_end_date'] ? formatDate($p['target_end_date']) : '—' ?>
          </span>
        </div>
        <?php if($p['actual_end_date']): ?>
        <div class="mb-3 d-flex justify-content-between align-items-center">
          <span class="text-muted small text-uppercase">Actual End</span>
          <span class="fw-semibold text-success"><?= formatDate($p['actual_end_date']) ?></span>
        </div>
        <?php endif; ?>

        <div class="mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small text-uppercase">Completion</span>
                <span class="fw-bold"><?= $p['completion_percentage'] ?>%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar <?= $p['completion_percentage'] == 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= $p['completion_percentage'] ?>%;" aria-valuenow="<?= $p['completion_percentage'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
      </div>
    </div>

    <div class="crm-card">
      <div class="crm-card-header bg-light"><h5 class="mb-0 fw-bold">Financials</h5></div>
      <div class="crm-card-body p-4 text-center">
        <div class="mb-4">
            <span class="text-muted small text-uppercase fw-bold d-block mb-1">Approved Budget</span>
            <span class="fs-4 fw-bold text-success"><?= formatCurrency($p['budget']) ?></span>
        </div>
        <div>
            <span class="text-muted small text-uppercase fw-bold d-block mb-1">Estimated Project Cost</span>
            <span class="fs-4 fw-bold text-dark"><?= formatCurrency($p['project_cost']) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BOQ Section -->
<div class="crm-card mb-4">
  <div class="crm-card-header bg-light d-flex justify-content-between align-items-center py-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-list-columns-reverse me-2 text-primary"></i>Bill of Quantities (BOQ)</h5>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBoqModal"><i class="bi bi-plus me-1"></i>Add Item</button>
  </div>
  <div class="crm-card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Item Description</th>
            <th>Unit</th>
            <th class="text-end border-start">Est. Qty</th>
            <th class="text-end">Est. Rate</th>
            <th class="text-end bg-light fw-bold">Est. Amount</th>
            <th class="text-end border-start text-primary">Actual Qty</th>
            <th class="text-end text-primary">Actual Rate</th>
            <th class="text-end bg-primary bg-opacity-10 fw-bold text-primary">Actual Amount</th>
            <th class="text-center" style="width: 80px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $tot_est = 0; $tot_act = 0;
          if(empty($boq_items)): ?>
            <tr><td colspan="9" class="text-center py-4 text-muted">No BOQ items added yet.</td></tr>
          <?php else: foreach($boq_items as $item): 
            $tot_est += $item['estimated_amount'];
            $tot_act += $item['actual_amount'];
          ?>
          <tr>
            <td class="fw-semibold"><?= e($item['item_description']) ?></td>
            <td><?= e($item['unit']) ?></td>
            <td class="text-end border-start"><?= (float)$item['estimated_qty'] ?></td>
            <td class="text-end"><?= formatCurrency($item['estimated_rate']) ?></td>
            <td class="text-end bg-light fw-bold"><?= formatCurrency($item['estimated_amount']) ?></td>
            
            <td class="text-end border-start text-primary"><?= (float)$item['actual_qty'] ?></td>
            <td class="text-end text-primary"><?= formatCurrency($item['actual_rate']) ?></td>
            <td class="text-end bg-primary bg-opacity-10 fw-bold text-primary"><?= formatCurrency($item['actual_amount']) ?></td>
            
            <td class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                  <li><a class="dropdown-item" href="#" onclick="updateActuals(<?= $item['id'] ?>, <?= $item['actual_qty'] ?>, <?= $item['actual_rate'] ?>)"><i class="bi bi-pencil-square me-2 text-primary"></i>Update Actuals</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form method="POST" onsubmit="return confirm('Delete this BOQ item?')">
                      <input type="hidden" name="action" value="delete_boq">
                      <input type="hidden" name="boq_id" value="<?= $item['id'] ?>">
                      <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete Item</button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
        <tfoot class="table-light fw-bold border-top-2">
          <tr>
            <td colspan="4" class="text-end text-uppercase">Total Estimated</td>
            <td class="text-end fs-5 text-dark"><?= formatCurrency($tot_est) ?></td>
            <td colspan="2" class="text-end text-uppercase border-start text-primary">Total Actual</td>
            <td class="text-end fs-5 text-primary"><?= formatCurrency($tot_act) ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- Site Images & Gallery -->
<div class="crm-card mb-4">
  <div class="crm-card-header bg-light d-flex justify-content-between align-items-center py-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-images me-2 text-primary"></i>Site Images & Gallery</h5>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal"><i class="bi bi-upload me-1"></i>Upload Image</button>
  </div>
  <div class="crm-card-body p-4">
    <?php if(empty($project_images)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-image fs-1 d-block mb-2 text-light"></i>
        <p class="mb-0">No site images uploaded yet.</p>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach($project_images as $img): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm border-0 position-relative group-hover">
            <a href="<?= BASE_URL ?>/<?= e($img['file_path']) ?>" target="_blank" class="text-decoration-none">
              <div style="height: 160px; overflow: hidden; background-color: #f8f9fa; border-radius: 6px 6px 0 0;" class="d-flex align-items-center justify-content-center">
                <img src="<?= BASE_URL ?>/<?= e($img['file_path']) ?>" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;" alt="<?= e($img['file_name']) ?>">
              </div>
            </a>
            <div class="card-body p-3">
              <?php if($img['description']): ?>
                <p class="card-text small text-dark fw-semibold mb-1 text-truncate" title="<?= e($img['description']) ?>"><?= e($img['description']) ?></p>
              <?php endif; ?>
              <p class="card-text small text-muted mb-0">By <?= e($img['uploader_name']) ?> on <?= date('M d, Y', strtotime($img['created_at'])) ?></p>
            </div>
            
            <form method="POST" class="position-absolute top-0 end-0 p-2" onsubmit="return confirm('Delete this image?')">
                <input type="hidden" name="action" value="delete_image">
                <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger shadow" style="border-radius: 50%; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Delete Image"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

</div></div>

<!-- Add BOQ Modal -->
<div class="modal fade" id="addBoqModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="action" value="add_boq">
        <div class="modal-header">
          <h5 class="modal-title">Add BOQ Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Item Description <span class="text-danger">*</span></label>
            <input type="text" name="item_description" class="form-control" required>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Unit</label>
              <input type="text" name="unit" class="form-control" placeholder="e.g. Sqft, Nos">
            </div>
            <div class="col-md-4">
              <label class="form-label">Estimated Qty <span class="text-danger">*</span></label>
              <input type="number" name="estimated_qty" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Estimated Rate <span class="text-danger">*</span></label>
              <input type="number" name="estimated_rate" class="form-control" step="0.01" min="0" required>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Actuals Modal -->
<div class="modal fade" id="updateActualsModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="action" value="update_actuals">
        <input type="hidden" name="boq_id" id="ua_boq_id">
        <div class="modal-header">
          <h5 class="modal-title">Update Actuals</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Actual Quantity</label>
            <input type="number" name="actual_qty" id="ua_qty" class="form-control" step="0.01" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Actual Rate</label>
            <input type="number" name="actual_rate" id="ua_rate" class="form-control" step="0.01" min="0">
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Actuals</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Upload Image Modal -->
<div class="modal fade" id="uploadImageModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload_image">
        <div class="modal-header">
          <h5 class="modal-title">Upload Site Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label d-block">Image Source <span class="text-danger">*</span></label>
            <div class="row g-2 mb-2">
              <div class="col-6">
                <label class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center" id="lblCamera" style="cursor:pointer; transition: all 0.2s;">
                  <i class="bi bi-camera fs-3 mb-1"></i>
                  <span class="fw-semibold">Take Photo</span>
                  <input type="file" name="image_file_camera" id="image_file_camera" class="d-none" accept="image/*" capture="environment">
                </label>
              </div>
              <div class="col-6">
                <label class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center" id="lblGallery" style="cursor:pointer; transition: all 0.2s;">
                  <i class="bi bi-images fs-3 mb-1"></i>
                  <span class="fw-semibold">Upload File</span>
                  <input type="file" name="image_file_gallery" id="image_file_gallery" class="d-none" accept="image/jpeg, image/png, image/gif, image/webp">
                </label>
              </div>
            </div>
            <div id="fileSelectionText" class="form-text text-success d-none fw-semibold"><i class="bi bi-check-circle-fill me-1"></i><span id="fileNameDisplay">Image selected.</span></div>
            <div class="form-text mt-1">Allowed types: JPG, PNG, GIF, WEBP. On mobile, "Take Photo" will open your camera directly.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Description / Note</label>
            <textarea name="image_description" class="form-control" rows="2" placeholder="E.g. Front elevation progress..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function updateActuals(id, qty, rate) {
    document.getElementById('ua_boq_id').value = id;
    document.getElementById('ua_qty').value = qty;
    document.getElementById('ua_rate').value = rate;
    var myModal = new bootstrap.Modal(document.getElementById('updateActualsModal'));
    myModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const camInput = document.getElementById('image_file_camera');
    const galInput = document.getElementById('image_file_gallery');
    const lblCam = document.getElementById('lblCamera');
    const lblGal = document.getElementById('lblGallery');
    const fileSel = document.getElementById('fileSelectionText');
    const fileDisp = document.getElementById('fileNameDisplay');
    const uploadForm = document.querySelector('#uploadImageModal form');

    function updateFileDisplay(input, lblActive, lblInactive) {
        if (input.files && input.files.length > 0) {
            lblActive.classList.add('btn-primary', 'text-white', 'border-primary');
            lblActive.classList.remove('btn-outline-primary', 'btn-outline-secondary');
            
            lblInactive.classList.remove('btn-primary', 'btn-secondary', 'text-white', 'border-primary');
            lblInactive.classList.add('btn-outline-secondary');
            
            // Clear the other input
            if(input.id === 'image_file_camera') {
                galInput.value = '';
            } else {
                camInput.value = '';
            }

            fileDisp.textContent = input.files[0].name || 'Image selected.';
            fileSel.classList.remove('d-none');
        }
    }

    if (camInput && galInput) {
        camInput.addEventListener('change', function() { updateFileDisplay(this, lblCam, lblGal); });
        galInput.addEventListener('change', function() { updateFileDisplay(this, lblGal, lblCam); });

        // Require at least one file before submit
        uploadForm.addEventListener('submit', function(e) {
            if (!camInput.value && !galInput.value) {
                e.preventDefault();
                alert('Please take a photo or select an image from your gallery.');
            }
        });

        // Reset modal on close
        document.getElementById('uploadImageModal').addEventListener('hidden.bs.modal', function () {
            camInput.value = '';
            galInput.value = '';
            uploadForm.reset();
            
            lblCam.classList.remove('btn-primary', 'text-white', 'border-primary');
            lblCam.classList.add('btn-outline-primary');
            
            lblGal.classList.remove('btn-secondary', 'text-white', 'border-primary');
            lblGal.classList.add('btn-outline-secondary');
            
            fileSel.classList.add('d-none');
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
