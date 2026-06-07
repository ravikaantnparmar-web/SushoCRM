<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requirePermission('products', 'view');
$pageTitle = 'Products & Services';
$search = sanitize($_GET['search'] ?? '');
$type   = sanitize($_GET['type'] ?? '');
$cat    = (int)($_GET['cat'] ?? 0);
$page   = max(1,(int)($_GET['page'] ?? 1));
$per    = RECORDS_PER_PAGE;

$where = ['1=1']; $params = [];
if ($search) { $where[] = '(p.name LIKE ? OR p.sku LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%"]); }
if ($type)   { $where[] = 'p.type = ?'; $params[] = $type; }
if ($cat)    { $where[] = 'p.category_id = ?'; $params[] = $cat; }
$whereStr = implode(' AND ', $where);

$total = db()->prepare("SELECT COUNT(*) FROM products p WHERE $whereStr");
$total->execute($params); $totalCount = $total->fetchColumn();
$pag = paginate($totalCount, $per, $page, BASE_URL.'/modules/products/index.php?search='.urlencode($search).'&type='.urlencode($type).'&cat='.$cat);

$stmt = db()->prepare("SELECT p.*, pc.name AS category_name FROM products p LEFT JOIN product_categories pc ON p.category_id=pc.id WHERE $whereStr ORDER BY p.name ASC LIMIT $per OFFSET {$pag['offset']}");
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = db()->query("SELECT * FROM product_categories ORDER BY name")->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Products & Services</div>
</div>
<div class="page-content">
<?= flashHtml() ?>
<div class="page-header">
  <div class="page-header-left"><h1>Products & Services</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Products</li></ol></nav>
  </div>
  <a href="<?= BASE_URL ?>/modules/products/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
</div>

<div class="table-wrapper">
  <div class="table-toolbar">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <div class="table-search"><i class="bi bi-search"></i><input type="text" name="search" placeholder="Search products..." value="<?= e($search) ?>"></div>
      <select name="type" class="form-select form-select-sm" style="width:130px">
        <option value="">All Types</option>
        <option value="product" <?= $type==='product'?'selected':'' ?>>Product</option>
        <option value="service" <?= $type==='service'?'selected':'' ?>>Service</option>
      </select>
      <select name="cat" class="form-select form-select-sm" style="width:160px">
        <option value="0">All Categories</option>
        <?php foreach($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $cat==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
      <?php if($search||$type||$cat): ?><a href="<?= BASE_URL ?>/modules/products/index.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
    <div class="d-flex gap-2">
      <span class="text-muted small align-self-center"><?= number_format($totalCount) ?> records</span>
      <button class="btn btn-sm btn-outline-secondary" onclick="exportTableToCSV('productsTable','products.csv')"><i class="bi bi-download me-1"></i>CSV</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table crm-table mb-0" id="productsTable">
      <thead><tr>
        <th>#</th><th>Product / SKU</th><th>Category</th><th>Type</th>
        <th>Unit</th><th>Purchase Price</th><th>Selling Price</th><th>Stock</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php if(empty($products)): ?>
          <tr><td colspan="10"><div class="empty-state"><i class="bi bi-box-seam"></i><p>No products found</p><a href="<?= BASE_URL ?>/modules/products/create.php" class="btn btn-primary btn-sm">Add First Product</a></div></td></tr>
        <?php else: ?>
          <?php foreach($products as $i => $pr): ?>
            <tr>
              <td class="text-muted small align-middle"><?= ($pag['offset']+$i+1) ?></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <?php if($pr['image']): ?>
                    <img src="<?= BASE_URL . '/' . e($pr['image']) ?>" alt="Image" style="width: 36px; height: 36px; object-fit: cover; border-radius: 4px;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light text-secondary rounded" style="width: 36px; height: 36px;">
                      <i class="bi bi-box-seam"></i>
                    </div>
                  <?php endif; ?>
                  <div>
                    <div class="fw-semibold text-dark"><a href="<?= BASE_URL ?>/modules/products/view.php?id=<?= $pr['id'] ?>" class="text-dark"><?= e($pr['name']) ?></a></div>
                    <div class="text-muted" style="font-size:11px">
                      <?= $pr['brand'] ? 'Brand: ' . e($pr['brand']) . ' | ' : '' ?>
                      SKU: <?= e($pr['sku'] ?: '—') ?>
                    </div>
                  </div>
                </div>
              </td>
              <td class="align-middle"><?= e($pr['category_name'] ?? '—') ?></td>
            <td><span class="badge <?= $pr['type']==='service'?'bg-info':'bg-secondary' ?>"><?= ucfirst($pr['type']) ?></span></td>
            <td><?= e($pr['unit']) ?></td>
            <td><?= formatCurrency($pr['purchase_price']) ?></td>
            <td class="fw-semibold text-success"><?= formatCurrency($pr['selling_price']) ?></td>
            <td>
              <?php if($pr['type']==='product'): ?>
                <span class="<?= $pr['stock_qty'] <= $pr['min_stock'] ? 'text-danger fw-semibold' : '' ?>"><?= number_format($pr['stock_qty'],2) ?></span>
              <?php else: ?><span class="text-muted">—</span><?php endif; ?>
            </td>
            <td><?= statusBadge($pr['is_active'] ? 'active' : 'inactive') ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?= BASE_URL ?>/modules/products/view.php?id=<?= $pr['id'] ?>" class="btn btn-icon btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                <a href="<?= BASE_URL ?>/modules/products/edit.php?id=<?= $pr['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/modules/products/delete.php?id=<?= $pr['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" data-confirm="Delete product <?= e($pr['name']) ?>?"><i class="bi bi-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($pag['total_pages']>1): ?>
  <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
    <small class="text-muted">Showing <?= $pag['offset']+1 ?>–<?= min($pag['offset']+$per,$totalCount) ?> of <?= $totalCount ?></small>
    <?= paginationHtml($pag) ?>
  </div>
  <?php endif; ?>
</div>
</div></div></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
