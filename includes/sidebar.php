<?php
$currentPath = $_SERVER['PHP_SELF'];
function isActive(string $path): string {
    return strpos($_SERVER['PHP_SELF'], $path) !== false ? 'active' : '';
}
$user = currentUser();
?>
<nav class="sidebar" id="sidebar">
  <!-- Brand -->
  <div class="sidebar-brand">
    <div class="brand-logo">
      <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="brand-text">
      <span class="brand-name"><?= APP_NAME ?></span>
      <span class="brand-tagline"><?= APP_TAGLINE ?></span>
    </div>
    <button class="sidebar-toggle-btn d-lg-none" id="sidebarClose"><i class="bi bi-x-lg"></i></button>
  </div>

  <!-- User Card -->
  <div class="sidebar-user">
    <div class="sidebar-user-avatar">
      <?php if (!empty($user['avatar'])): ?>
        <img src="<?= BASE_URL ?>/<?= e($user['avatar']) ?>" alt="Avatar">
      <?php else: ?>
        <span><?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?></span>
      <?php endif; ?>
    </div>
    <div class="sidebar-user-info">
      <span class="user-name"><?= e($user['name'] ?? 'User') ?></span>
      <span class="user-role"><?= ucwords(str_replace('_', ' ', $user['role_slug'] ?? '')) ?></span>
    </div>
  </div>

  <div class="sidebar-menu" id="sortableSidebar">
    <?php
    $defaultMenu = [
        ['type' => 'link', 'id' => 'dashboard', 'url' => '/modules/dashboard/index.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'match' => '/dashboard/'],
        
        ['type' => 'header', 'id' => 'h_crm', 'label' => 'CRM'],
        ['type' => 'link', 'id' => 'leads', 'url' => '/modules/prospects/index.php', 'icon' => 'bi-funnel-fill', 'label' => 'Leads', 'match' => '/prospects/'],
        ['type' => 'link', 'id' => 'customers', 'url' => '/modules/customers/index.php', 'icon' => 'bi-people-fill', 'label' => 'Customers', 'match' => '/customers/'],
        ['type' => 'link', 'id' => 'contacts', 'url' => '/modules/contacts/index.php', 'icon' => 'bi-person-lines-fill', 'label' => 'Contact Management', 'match' => '/contacts/'],

        ['type' => 'header', 'id' => 'h_sales', 'label' => 'Sales'],
        ['type' => 'link', 'id' => 'quotations', 'url' => '/modules/quotations/index.php', 'icon' => 'bi-file-text-fill', 'label' => 'Quotations', 'match' => '/quotations/'],
        ['type' => 'link', 'id' => 'orders', 'url' => '/modules/orders/index.php', 'icon' => 'bi-cart-fill', 'label' => 'Orders', 'match' => '/orders/'],
        ['type' => 'link', 'id' => 'invoices', 'url' => '/modules/invoices/index.php', 'icon' => 'bi-receipt-cutoff', 'label' => 'Invoices', 'match' => '/invoices/'],

        ['type' => 'header', 'id' => 'h_inventory', 'label' => 'Inventory'],
        ['type' => 'link', 'id' => 'products', 'url' => '/modules/products/index.php', 'icon' => 'bi-box-seam-fill', 'label' => 'Products & Services', 'match' => '/products/'],

        ['type' => 'header', 'id' => 'h_purchase', 'label' => 'Purchase'],
        ['type' => 'link', 'id' => 'vendors', 'url' => '/modules/vendors/index.php', 'icon' => 'bi-truck', 'label' => 'Vendors', 'match' => '/vendors/'],
        ['type' => 'link', 'id' => 'purchases', 'url' => '/modules/purchases/index.php', 'icon' => 'bi-bag-fill', 'label' => 'Purchases', 'match' => '/purchases/'],

        ['type' => 'header', 'id' => 'h_work', 'label' => 'Work Management'],
        ['type' => 'link', 'id' => 'tasks', 'url' => '/modules/tasks/index.php', 'icon' => 'bi-list-task', 'label' => 'Tasks', 'match' => '/tasks/'],
        ['type' => 'link', 'id' => 'projects', 'url' => '/modules/projects/index.php', 'icon' => 'bi-briefcase', 'label' => 'Projects', 'match' => '/projects/'],
        ['type' => 'link', 'id' => 'travels', 'url' => '/modules/travels/index.php', 'icon' => 'bi-airplane-fill', 'label' => 'Travel & Visits', 'match' => '/travels/'],

        ['type' => 'header', 'id' => 'h_finance', 'label' => 'Finance'],
        ['type' => 'link', 'id' => 'payments', 'url' => '/modules/payments/index.php', 'icon' => 'bi-wallet2', 'label' => 'Invoice Payments', 'match' => '/payments/'],
        ['type' => 'link', 'id' => 'receipts', 'url' => '/modules/receipts/index.php', 'icon' => 'bi-receipt', 'label' => 'Receipts', 'match' => '/receipts/'],
        ['type' => 'link', 'id' => 'expenses', 'url' => '/modules/expenses/index.php', 'icon' => 'bi-cash-stack', 'label' => 'Expenses', 'match' => '/expenses/'],

        ['type' => 'header', 'id' => 'h_hr', 'label' => 'HR'],
        ['type' => 'link', 'id' => 'employees', 'url' => '/modules/employees/index.php', 'icon' => 'bi-person-badge-fill', 'label' => 'Employees', 'match' => '/employees/'],
        ['type' => 'link', 'id' => 'attendance', 'url' => '/modules/attendance/index.php', 'icon' => 'bi-calendar-check', 'label' => 'Attendance', 'match' => '/attendance/'],
        ['type' => 'link', 'id' => 'salary', 'url' => '/modules/salary/index.php', 'icon' => 'bi-currency-exchange', 'label' => 'Salary Management', 'match' => '/salary/'],

        ['type' => 'header', 'id' => 'h_reports', 'label' => 'Analytics'],
        ['type' => 'link', 'id' => 'reports', 'url' => '/modules/reports/index.php', 'icon' => 'bi-bar-chart-fill', 'label' => 'Reports', 'match' => '/reports/']
    ];

    if (isAdmin()) {
        $defaultMenu[] = ['type' => 'header', 'id' => 'h_admin', 'label' => 'Admin'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'approvals', 'url' => '/modules/approvals/index.php', 'icon' => 'bi-shield-check', 'label' => 'Approvals', 'match' => '/approvals/'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'users', 'url' => '/modules/settings/users.php', 'icon' => 'bi-person-gear', 'label' => 'Users', 'match' => '/settings/users'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'announcements', 'url' => '/modules/settings/announcements.php', 'icon' => 'bi-megaphone-fill', 'label' => 'Comm. Board', 'match' => '/settings/announcements'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'settings', 'url' => '/modules/settings/index.php', 'icon' => 'bi-gear-fill', 'label' => 'Settings', 'match' => '/settings/index'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'backup', 'url' => '/modules/settings/backup.php', 'icon' => 'bi-shield-check', 'label' => 'System Backup', 'match' => '/settings/backup'];
        $defaultMenu[] = ['type' => 'link', 'id' => 'activity_logs', 'url' => '/modules/settings/activity-logs.php', 'icon' => 'bi-clock-history', 'label' => 'Activity Logs', 'match' => '/activity-logs'];
    }

    // Convert to associative array for easy lookup
    $menuDict = [];
    foreach ($defaultMenu as $item) {
        $menuDict[$item['id']] = $item;
    }

    // Fetch user preferences for order
    $userId = $_SESSION['user_id'] ?? 0;
    $stmt = db()->prepare("SELECT preference_value FROM user_preferences WHERE user_id = ? AND preference_key = 'sidebar_order'");
    $stmt->execute([$userId]);
    $savedOrderJson = $stmt->fetchColumn();
    $savedOrder = $savedOrderJson ? json_decode($savedOrderJson, true) : null;

    $menu = [];
    if ($savedOrder && is_array($savedOrder)) {
        foreach ($savedOrder as $id) {
            if (isset($menuDict[$id])) {
                $menu[] = $menuDict[$id];
                unset($menuDict[$id]); // Remove from dict to find remaining
            }
        }
        // Add any new items that weren't in the saved order
        foreach ($menuDict as $item) {
            $menu[] = $item;
        }
    } else {
        $menu = $defaultMenu;
    }

    foreach ($menu as $item):
    ?>
      <div class="sidebar-sortable-item" data-id="<?= e($item['id']) ?>">
        <?php if ($item['type'] === 'header'): ?>
          <div class="menu-section-title d-flex align-items-center" style="margin-top: 15px;">
            <i class="bi bi-grip-vertical text-muted drag-handle" style="font-size: 1.2rem; cursor: grab; margin-left: -15px; margin-right: 2px;" title="Drag to reorder"></i>
            <span><?= e($item['label']) ?></span>
          </div>
        <?php else: ?>
          <div class="d-flex align-items-center position-relative">
            <i class="bi bi-grip-vertical text-muted drag-handle" style="font-size: 1.2rem; cursor: grab; opacity: 0.6; position: absolute; left: -15px; z-index: 10; padding: 10px 5px;" title="Drag to reorder"></i>
            <a href="<?= BASE_URL . $item['url'] ?>" class="menu-item flex-grow-1 mb-0 <?= isActive($item['match']) ?>">
              <i class="bi <?= $item['icon'] ?>"></i><span class="ms-1"><?= e($item['label']) ?></span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Security & Logout -->
  <div class="sidebar-footer">
    <a href="<?= BASE_URL ?>/modules/auth/change-password.php" class="menu-item <?= isActive('/auth/change-password') ?>">
      <i class="bi bi-shield-lock"></i><span>Change Password</span>
    </a>
    <a href="<?= BASE_URL ?>/modules/auth/logout.php" id="logoutLink" class="menu-item menu-item-logout">
      <i class="bi bi-box-arrow-left"></i><span>Logout</span>
    </a>
  </div>
</nav>
