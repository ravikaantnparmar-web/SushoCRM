<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requireRole(['super_admin', 'admin']);

$pageTitle = 'Manage Communication Board';
$db = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        setFlash('danger', 'Invalid security token (CSRF). Please try again.');
        logActivity('Announcements', 'CSRF Failure', 'Failed CSRF token validation on ' . sanitize($_POST['action']));
        header('Location: announcements.php');
        exit;
    }

    if ($_POST['action'] === 'add') {
        $title = sanitize($_POST['title']);
        $content = sanitize($_POST['content']);
        $category = sanitize($_POST['category']);
        $priority = sanitize($_POST['priority']);
        
        $allowedPriorities = ['Low', 'Medium', 'High'];
        $allowedCategories = ['Announcement', 'Management Note', 'Policy Update', 'Target Reminder', 'Operational Alert'];
        if (!in_array($priority, $allowedPriorities)) $priority = 'Medium';
        if (!in_array($category, $allowedCategories)) $category = 'Announcement';
        
        $stmt = $db->prepare("INSERT INTO announcements (title, content, category, priority, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $category, $priority, $_SESSION['user_id']]);
        
        $newId = $db->lastInsertId();
        logActivity('Announcements', 'Created', "Created announcement: {$title}", $newId);
        setFlash('success', 'Announcement posted successfully.');
    } elseif ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $title = sanitize($_POST['title']);
        $content = sanitize($_POST['content']);
        $category = sanitize($_POST['category']);
        $priority = sanitize($_POST['priority']);
        
        $allowedPriorities = ['Low', 'Medium', 'High'];
        $allowedCategories = ['Announcement', 'Management Note', 'Policy Update', 'Target Reminder', 'Operational Alert'];
        if (!in_array($priority, $allowedPriorities)) $priority = 'Medium';
        if (!in_array($category, $allowedCategories)) $category = 'Announcement';
        
        $stmt = $db->prepare("UPDATE announcements SET title=?, content=?, category=?, priority=? WHERE id=?");
        $stmt->execute([$title, $content, $category, $priority, $id]);
        
        logActivity('Announcements', 'Updated', "Updated announcement: {$title}", $id);
        setFlash('success', 'Announcement updated successfully.');
    } elseif ($_POST['action'] === 'toggle') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("UPDATE announcements SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('Announcements', 'Toggled Status', "Toggled status for announcement ID: {$id}", $id);
        setFlash('success', 'Announcement status updated.');
    } elseif ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        $db->prepare("DELETE FROM announcements WHERE id = ?")->execute([$id]);
        logActivity('Announcements', 'Deleted', "Deleted announcement ID: {$id}");
        setFlash('success', 'Announcement deleted.');
    }
    header('Location: announcements.php');
    exit;
}

$announcements = $db->query("SELECT a.*, u.name as author FROM announcements a LEFT JOIN users u ON a.created_by = u.id ORDER BY a.created_at DESC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Communication Board Settings</div>
</div>
<div class="page-content">
<?= flashHtml() ?>

<div class="page-header">
  <div class="page-header-left">
    <h1>Communication Board</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Settings</a></li>
        <li class="breadcrumb-item active">Announcements</li>
      </ol>
    </nav>
  </div>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal"><i class="bi bi-plus-lg me-1"></i>New Announcement</button>
</div>

<div class="table-wrapper">
  <table class="table crm-table mb-0">
    <thead>
      <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Title</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($announcements as $a): ?>
      <tr>
        <td><?= formatDate($a['created_at']) ?></td>
        <td><span class="badge bg-light text-dark border"><?= e($a['category']) ?></span></td>
        <td class="fw-bold"><?= e($a['title']) ?></td>
        <td>
            <span class="badge bg-<?= $a['priority']=='High'?'danger':($a['priority']=='Medium'?'warning':'info') ?>">
                <?= $a['priority'] ?>
            </span>
        </td>
        <td>
            <form method="POST" class="d-inline">
                <input type="hidden" name="action" value="toggle">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-sm <?= $a['is_active']?'btn-success':'btn-secondary' ?>">
                    <?= $a['is_active']?'Active':'Inactive' ?>
                </button>
            </form>
        </td>
        <td>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $a['id'] ?>"><i class="bi bi-eye"></i></button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $a['id'] ?>"><i class="bi bi-pencil"></i></button>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this announcement?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</div></div>

<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="POST">
        <input type="hidden" name="action" value="add">
        <?= csrfField() ?>
        <div class="modal-header bg-primary text-white py-3 border-0 rounded-top">
          <h5 class="modal-title fw-semibold fs-5"><i class="bi bi-megaphone me-2"></i>New Announcement</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small text-muted fw-bold mb-1">Title</label>
              <input type="text" name="title" class="form-control form-control-sm" required placeholder="Quick summary...">
            </div>
            <div class="col-md-4">
              <label class="form-label small text-muted fw-bold mb-1">Priority</label>
              <select name="priority" class="form-select form-select-sm">
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold mb-1">Category</label>
            <select name="category" class="form-select form-select-sm" required>
              <option value="Announcement">Critical Announcement</option>
              <option value="Management Note">Daily Management Note</option>
              <option value="Policy Update">Policy Update</option>
              <option value="Target Reminder">Target Reminder</option>
              <option value="Operational Alert">Operational Alert</option>
            </select>
          </div>
          <div class="mb-1">
            <label class="form-label small text-muted fw-bold mb-1">Content</label>
            <textarea name="content" class="form-control form-control-sm" rows="4" required placeholder="Detailed message..." oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light py-2 border-0 rounded-bottom">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary px-4"><i class="bi bi-send me-1"></i>Post Announcement</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php foreach($announcements as $a): ?>
<!-- View Modal -->
<div class="modal fade" id="viewModal<?= $a['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 800px; width: fit-content; min-width: 500px;">
    <div class="modal-content border-0 shadow" style="min-width: 100%;">
      <div class="modal-header bg-info text-white py-3 border-0 rounded-top">
        <h5 class="modal-title fw-semibold fs-5"><i class="bi bi-eye me-2"></i>View Announcement</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <h4 class="fw-bold mb-2"><?= e($a['title']) ?></h4>
        <div class="mb-3">
          <span class="badge bg-light text-dark border me-2"><?= e($a['category']) ?></span>
          <span class="badge bg-<?= $a['priority']=='High'?'danger':($a['priority']=='Medium'?'warning':'info') ?>"><?= $a['priority'] ?></span>
          <small class="text-muted ms-2"><i class="bi bi-clock me-1"></i><?= formatDate($a['created_at']) ?></small>
        </div>
        <div class="bg-light p-3 rounded border">
          <?= nl2br(e($a['content'])) ?>
        </div>
      </div>
      <div class="modal-footer bg-light py-2 border-0 rounded-bottom">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $a['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <?= csrfField() ?>
        <div class="modal-header bg-primary text-white py-3 border-0 rounded-top">
          <h5 class="modal-title fw-semibold fs-5"><i class="bi bi-pencil me-2"></i>Edit Announcement</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small text-muted fw-bold mb-1">Title</label>
              <input type="text" name="title" class="form-control form-control-sm" value="<?= e($a['title']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small text-muted fw-bold mb-1">Priority</label>
              <select name="priority" class="form-select form-select-sm">
                <option value="Low" <?= $a['priority']=='Low'?'selected':'' ?>>Low</option>
                <option value="Medium" <?= $a['priority']=='Medium'?'selected':'' ?>>Medium</option>
                <option value="High" <?= $a['priority']=='High'?'selected':'' ?>>High</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold mb-1">Category</label>
            <select name="category" class="form-select form-select-sm" required>
              <option value="Announcement" <?= $a['category']=='Announcement'?'selected':'' ?>>Critical Announcement</option>
              <option value="Management Note" <?= $a['category']=='Management Note'?'selected':'' ?>>Daily Management Note</option>
              <option value="Policy Update" <?= $a['category']=='Policy Update'?'selected':'' ?>>Policy Update</option>
              <option value="Target Reminder" <?= $a['category']=='Target Reminder'?'selected':'' ?>>Target Reminder</option>
              <option value="Operational Alert" <?= $a['category']=='Operational Alert'?'selected':'' ?>>Operational Alert</option>
            </select>
          </div>
          <div class="mb-1">
            <label class="form-label small text-muted fw-bold mb-1">Content</label>
            <textarea name="content" class="form-control form-control-sm" rows="4" required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"><?= e($a['content']) ?></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light py-2 border-0 rounded-bottom">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
