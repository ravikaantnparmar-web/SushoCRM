<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT c.*, u.name AS created_by_name,
    (SELECT co.name FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_name,
    (SELECT co.mobile FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_phone,
    (SELECT co.email FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_email,
    (SELECT co.whatsapp FROM contacts co JOIN contact_relations cr ON co.id = cr.contact_id WHERE cr.entity_type = 'customer' AND cr.entity_id = c.id AND cr.is_primary = 1 LIMIT 1) AS primary_contact_whatsapp,
    (SELECT lead_code FROM leads l WHERE l.id=c.source_lead_id LIMIT 1) AS source_lead_code
    FROM customers c LEFT JOIN users u ON c.created_by=u.id WHERE c.id=?");
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) { setFlash('danger','Customer not found.'); header('Location: ' . BASE_URL . '/modules/customers/index.php'); exit; }

$displayName = $customer['primary_contact_name'] ?: $customer['company_name'] ?: 'Customer';

$invoices = db()->prepare("SELECT i.* FROM invoices i WHERE i.customer_id=? ORDER BY i.created_at DESC LIMIT 10");
$invoices->execute([$id]); $invoices = $invoices->fetchAll();

$quotations = db()->prepare("SELECT q.* FROM quotations q WHERE q.customer_id=? ORDER BY q.created_at DESC LIMIT 5");
$quotations->execute([$id]); $quotations = $quotations->fetchAll();

$orders = db()->prepare("SELECT o.* FROM orders o WHERE o.customer_id=? ORDER BY o.created_at DESC LIMIT 5");
$orders->execute([$id]); $orders = $orders->fetchAll();

$totalPaid = db()->prepare("SELECT COALESCE(SUM(amount),0) FROM payments WHERE customer_id=?");
$totalPaid->execute([$id]); $totalPaid = $totalPaid->fetchColumn();

$notes = db()->prepare("SELECT n.*, u.name AS author FROM notes n JOIN users u ON n.user_id=u.id WHERE n.module='customers' AND n.record_id=? ORDER BY n.created_at DESC");
$notes->execute([$id]); $notes = $notes->fetchAll();

// Add note
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_note'])) {
    if (verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $note = sanitize($_POST['note'] ?? '');
        if ($note) {
            db()->prepare("INSERT INTO notes (user_id,module,record_id,note) VALUES (?,?,?,?)")->execute([$_SESSION['user_id'],'customers',$id,$note]);
            setFlash('success','Note added.');
            header('Location: ' . BASE_URL . '/modules/customers/view.php?id=' . $id . '#notes');
            exit;
        }
    }
}

$pageTitle = 'Customer: ' . $displayName;
include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title"><?= e($displayName) ?></div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left">
    <h1><?= e($displayName) ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/customers/index.php">Customers</a></li>
      <li class="breadcrumb-item active"><?= e($displayName) ?></li>
    </ol></nav>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/modules/quotations/create.php?customer_id=<?= $id ?>" class="btn btn-success btn-sm"><i class="bi bi-file-text me-1"></i>New Quote</a>
    <a href="<?= BASE_URL ?>/modules/customers/edit.php?id=<?= $id ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
    <a href="<?= BASE_URL ?>/modules/customers/delete.php?id=<?= $id ?>" class="btn btn-outline-danger btn-sm" data-confirm="Delete this customer?"><i class="bi bi-trash me-1"></i>Delete</a>
  </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat-card success"><div class="stat-icon success"><i class="bi bi-currency-rupee"></i></div><div class="stat-info"><div class="stat-value"><?= formatCurrency($totalPaid) ?></div><div class="stat-label">Total Paid</div></div></div></div>
  <div class="col-6 col-md-3"><div class="stat-card primary"><div class="stat-icon primary"><i class="bi bi-file-text-fill"></i></div><div class="stat-info"><div class="stat-value"><?= count($quotations) ?></div><div class="stat-label">Quotations</div></div></div></div>
  <div class="col-6 col-md-3"><div class="stat-card warning"><div class="stat-icon warning"><i class="bi bi-cart-fill"></i></div><div class="stat-info"><div class="stat-value"><?= count($orders) ?></div><div class="stat-label">Orders</div></div></div></div>
  <div class="col-6 col-md-3"><div class="stat-card info"><div class="stat-icon info"><i class="bi bi-receipt-cutoff"></i></div><div class="stat-info"><div class="stat-value"><?= count($invoices) ?></div><div class="stat-label">Invoices</div></div></div></div>
</div>

<div class="row g-4">
  <!-- Left: Details -->
  <div class="col-12 col-lg-4">
    <div class="crm-card mb-4">
      <div class="crm-card-header"><h2 class="crm-card-title">Contact Details</h2><?= statusBadge($customer['company_status'] ?? '') ?></div>
      <div class="crm-card-body">
        <?php $fields = [['bi-building','Company',$customer['company_name']],['bi-envelope','Email',$customer['company_email'] ?: $customer['primary_contact_email']],['bi-globe','Website',$customer['company_website']],['bi-telephone','Phone',$customer['primary_contact_phone']],['bi-whatsapp','WhatsApp',$customer['primary_contact_whatsapp']],['bi-geo-alt','City',$customer['city']],['bi-map','State',$customer['state']],['bi-123','GST',$customer['gst_number']],['bi-card-text','TIN',$customer['tin_number']]]; ?>
        <?php foreach($fields as [$icon,$label,$val]): if(!$val) continue; ?>
        <div class="d-flex gap-3 mb-3">
          <div class="text-muted" style="width:20px"><i class="bi <?= $icon ?>"></i></div>
          <div><div class="text-muted small"><?= $label ?></div><div class="fw-semibold small"><?= e($val) ?></div></div>
        </div>
        <?php endforeach; ?>
        <?php if ($customer['address_line1']): ?>
        <div class="d-flex gap-3">
          <div class="text-muted" style="width:20px"><i class="bi bi-house"></i></div>
          <div><div class="text-muted small">Address</div><div class="fw-semibold small"><?= nl2br(e($customer['address_line1'])) ?></div></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($customer['source_lead_id'])): ?>
        <div class="d-flex gap-3 mt-3 pt-3 border-top">
          <div class="text-primary" style="width:20px"><i class="bi bi-funnel"></i></div>
          <div>
            <div class="text-muted small">Origin</div>
            <div class="fw-semibold small">
              Converted from Lead: <a href="<?= BASE_URL ?>/modules/prospects/view.php?id=<?= $customer['source_lead_id'] ?>" class="text-decoration-none fw-bold"><?= e($customer['source_lead_code']) ?></a>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="crm-card mb-4">
      <div class="crm-card-header"><h2 class="crm-card-title">Profiling Information</h2></div>
      <div class="crm-card-body">
        <?php $profiling = [
          ['bi-person-badge', 'Customer Type', $customer['company_type']],
          ['bi-briefcase', 'Business Category', $customer['business_category']],
          ['bi-buildings', 'Industry Type', $customer['industry_type']]
        ]; ?>
        <?php foreach($profiling as [$icon,$label,$val]): if(!$val) continue; ?>
        <div class="d-flex gap-3 mb-3">
          <div class="text-muted" style="width:20px"><i class="bi <?= $icon ?>"></i></div>
          <div><div class="text-muted small"><?= $label ?></div><div class="fw-semibold small"><?= e($val) ?></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($customer['requirement_description']): ?>
    <div class="crm-card mb-4">
      <div class="crm-card-header"><h2 class="crm-card-title">Requirements</h2></div>
      <div class="crm-card-body"><p class="small mb-0"><?= nl2br(e($customer['requirement_description'])) ?></p></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Right: Tabs -->
  <div class="col-12 col-lg-8">
    <div class="crm-card">
      <div class="crm-card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs px-4 pt-3" id="custTabs">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabInvoices">Invoices</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabOrders">Orders</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabQuotes">Quotations</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabNotes" id="notes">Notes</button></li>
        </ul>
      </div>
      <div class="tab-content p-3">
        <!-- Invoices -->
        <div class="tab-pane fade show active" id="tabInvoices">
          <table class="table crm-table"><thead><tr><th>Invoice #</th><th>Date</th><th>Total</th><th>Paid</th><th>Status</th></tr></thead>
          <tbody><?php foreach($invoices as $inv): ?>
            <tr><td><a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $inv['id'] ?>"><?= e($inv['invoice_number']) ?></a></td>
            <td><?= formatDate($inv['issued_date']) ?></td>
            <td><?= formatCurrency($inv['total']) ?></td>
            <td class="text-success"><?= formatCurrency($inv['paid_amount']) ?></td>
            <td><?= statusBadge($inv['status']) ?></td></tr>
          <?php endforeach; if(empty($invoices)): ?><tr><td colspan="5" class="text-center text-muted py-3">No invoices</td></tr><?php endif; ?>
          </tbody></table>
        </div>
        <!-- Orders -->
        <div class="tab-pane fade" id="tabOrders">
          <table class="table crm-table"><thead><tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th><th>Payment</th></tr></thead>
          <tbody><?php foreach($orders as $ord): ?>
            <tr><td><a href="<?= BASE_URL ?>/modules/orders/view.php?id=<?= $ord['id'] ?>"><?= e($ord['order_number']) ?></a></td>
            <td><?= formatDate($ord['created_at']) ?></td>
            <td><?= formatCurrency($ord['total']) ?></td>
            <td><?= statusBadge($ord['status']) ?></td>
            <td><?= statusBadge($ord['payment_status']) ?></td></tr>
          <?php endforeach; if(empty($orders)): ?><tr><td colspan="5" class="text-center text-muted py-3">No orders</td></tr><?php endif; ?>
          </tbody></table>
        </div>
        <!-- Quotations -->
        <div class="tab-pane fade" id="tabQuotes">
          <table class="table crm-table"><thead><tr><th>Quote #</th><th>Version</th><th>Date</th><th>Total</th><th>Status</th><th>Remarks</th></tr></thead>
          <tbody><?php foreach($quotations as $q): ?>
            <tr><td><a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= $q['id'] ?>"><?= e($q['quote_number']) ?></a></td>
            <td><span class="badge <?= !empty($q['is_latest']) ? 'bg-success' : 'bg-secondary' ?>">V<?= $q['version'] ?? 1 ?></span></td>
            <td><?= formatDate($q['created_at']) ?></td>
            <td><?= formatCurrency($q['total']) ?></td>
            <td><?= statusBadge($q['status']) ?></td>
            <td><span class="small text-muted"><?= e($q['revision_notes'] ?: '—') ?></span></td></tr>
          <?php endforeach; if(empty($quotations)): ?><tr><td colspan="6" class="text-center text-muted py-3">No quotations</td></tr><?php endif; ?>
          </tbody></table>
        </div>
        <!-- Notes -->
        <div class="tab-pane fade" id="tabNotes">
          <form method="POST" class="mb-3">
            <?= csrfField() ?>
            <div class="input-group">
              <textarea name="note" class="form-control" rows="2" placeholder="Add a note..."></textarea>
              <button type="submit" name="add_note" class="btn btn-primary px-3"><i class="bi bi-send"></i></button>
            </div>
          </form>
          <ul class="timeline mt-3">
            <?php foreach($notes as $n): ?>
            <li class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-content">
                <strong class="small"><?= e($n['author']) ?></strong>
                <p class="mb-0 small"><?= nl2br(e($n['note'])) ?></p>
                <div class="timeline-time"><?= formatDateTime($n['created_at']) ?></div>
              </div>
            </li>
            <?php endforeach; if(empty($notes)): ?><li class="text-muted small">No notes yet.</li><?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
