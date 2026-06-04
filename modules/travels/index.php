<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Travels & Visits';

// Fetch all travels
$where = "";
$params = [];
if (!isAdmin()) {
    $where = " WHERE t.employee_id = ? ";
    $params[] = $_SESSION['user_id'];
}

$query = "SELECT t.*, u.name as employee_name 
          FROM travels t 
          LEFT JOIN users u ON t.employee_id = u.id 
          $where
          ORDER BY t.created_at DESC";
$stmt = db()->prepare($query);
$stmt->execute($params);
$travels = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Travels & Visits</div>
</div>
<div class="page-content">

<?= flashHtml() ?>

<div class="page-header">
  <div class="page-header-left">
    <h1>Travels & Visits</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item active">Travels</li>
      </ol>
    </nav>
  </div>
  <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Request</a>
</div>

<div class="crm-card">
  <div class="crm-card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Travel #</th>
            <th>Employee</th>
            <th>Type</th>
            <th>Purpose</th>
            <th>Period</th>
            <th>Location</th>
            <th>Status</th>
            <th>Approval</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($travels)): ?>
            <tr><td colspan="9" class="text-center py-4 text-muted">No travels found. <a href="create.php">Create one</a></td></tr>
          <?php else: foreach($travels as $t): ?>
          <tr>
            <td class="fw-bold"><a href="view.php?id=<?= $t['id'] ?>" class="text-decoration-none"><?= e($t['travel_number']) ?></a></td>
            <td><?= e($t['employee_name']) ?></td>
            <td><?= e($t['travel_type'] ?: '—') ?></td>
            <td><?= e($t['purpose_category'] ?: '—') ?></td>
            <td>
              <?php if($t['from_date'] && $t['to_date']): ?>
                  <?= date('d M', strtotime($t['from_date'])) ?> to <?= date('d M Y', strtotime($t['to_date'])) ?>
                  <div class="small text-muted"><?= $t['number_of_days'] ?> Days</div>
              <?php else: ?>
                  —
              <?php endif; ?>
            </td>
            <td><?= e($t['location_city']) ?><?= $t['location_state'] ? ', '.e($t['location_state']) : '' ?></td>
            <td><?= statusBadge($t['travel_status']) ?></td>
            <td>
              <?php if($t['overall_approval_status'] == 'Approved'): ?>
                <span class="badge bg-success">Approved</span>
              <?php elseif($t['overall_approval_status'] == 'Rejected'): ?>
                <span class="badge bg-danger">Rejected</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <div class="dropdown">
                <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                  <li><a class="dropdown-item" href="view.php?id=<?= $t['id'] ?>"><i class="bi bi-eye me-2 text-primary"></i>View Details</a></li>
                  <li><a class="dropdown-item" href="edit.php?id=<?= $t['id'] ?>"><i class="bi bi-pencil me-2 text-warning"></i>Edit</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="delete.php" method="POST" onsubmit="return confirm('Delete this travel record? This cannot be undone.');">
                      <input type="hidden" name="id" value="<?= $t['id'] ?>">
                      <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
