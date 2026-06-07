<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Dashboard';
$pdo = db();

// ── KPI Stats ──────────────────────────────────────────────────
$totalCustomers  = (int)$pdo->query("SELECT COUNT(*) FROM customers WHERE company_status='Active'")->fetchColumn();
$totalProspects  = (int)$pdo->query("SELECT COUNT(*) FROM leads WHERE lead_status NOT IN ('Won','Closed','Lost') AND deleted_at IS NULL")->fetchColumn();
$pendingQuotes   = (int)$pdo->query("SELECT COUNT(*) FROM quotations WHERE approval_status IN ('pending', 'under_review') AND is_latest = 1")->fetchColumn();
$openOrders      = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status NOT IN ('delivered','cancelled')")->fetchColumn();
$overdueInvoices = (int)$pdo->query("SELECT COUNT(*) FROM invoices WHERE status IN ('sent','partial') AND due_date < CURDATE()")->fetchColumn();
$monthRevenue    = (float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE MONTH(payment_date)=MONTH(CURDATE()) AND YEAR(payment_date)=YEAR(CURDATE())")->fetchColumn();
$monthExpenses   = (float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM expenses WHERE MONTH(expense_date)=MONTH(CURDATE()) AND YEAR(expense_date)=YEAR(CURDATE())")->fetchColumn();
$totalEmployees  = (int)$pdo->query("SELECT COUNT(*) FROM employees WHERE status='active'")->fetchColumn();

// ── Monthly Revenue chart (last 12 months) ─────────────────────
$revenueRows = $pdo->query("
    SELECT DATE_FORMAT(payment_date,'%b %Y') AS label,
           DATE_FORMAT(payment_date,'%Y-%m') AS ym,
           SUM(amount) AS total
    FROM payments
    WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY ym, label
    ORDER BY ym ASC
")->fetchAll();

// ── Expense by category ────────────────────────────────────────
$expCatRows = $pdo->query("
    SELECT ec.name AS label, SUM(e.amount) AS total
    FROM expenses e JOIN expense_categories ec ON e.category_id=ec.id
    WHERE YEAR(e.expense_date)=YEAR(CURDATE())
    GROUP BY ec.id ORDER BY total DESC LIMIT 6
")->fetchAll();

// ── Recent Invoices ────────────────────────────────────────────
$recentInvoices = $pdo->query("
    SELECT i.*, c.company_name AS customer_name
    FROM invoices i JOIN customers c ON i.customer_id=c.id
    ORDER BY i.created_at DESC LIMIT 8
")->fetchAll();

// ── Top Customers ──────────────────────────────────────────────
$topCustomers = $pdo->query("
    SELECT c.company_name AS name, SUM(p.amount) AS total_paid
    FROM payments p JOIN customers c ON p.customer_id=c.id
    GROUP BY c.id ORDER BY total_paid DESC LIMIT 5
")->fetchAll();



// ── Activity Feed ──────────────────────────────────────────────
$activityFeed = $pdo->query("
    SELECT al.*, u.name AS user_name
    FROM activity_logs al LEFT JOIN users u ON al.user_id=u.id
    ORDER BY al.created_at DESC LIMIT 8
")->fetchAll();

// ── Sandbox Demo Mode Seeder ──────────────────────────────────
// Automatically seeds a bustling operation dataset when fresh installations are empty
if ($totalCustomers === 0 && $totalProspects === 0 && $monthRevenue == 0) {
    $isDemoMode = true;
    
    // KPI metrics
    $totalCustomers  = 48;
    $totalProspects  = 23;
    $pendingQuotes   = 12;
    $openOrders      = 8;
    $overdueInvoices = 3;
    $monthRevenue    = 482500;
    $monthExpenses   = 165000;
    $totalEmployees  = 14;
    
    // Charts config
    $chartLabels  = json_encode(['Jun 25', 'Jul 25', 'Aug 25', 'Sep 25', 'Oct 25', 'Nov 25', 'Dec 25', 'Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'May 26']);
    $chartRevenue = json_encode([320000, 385000, 310000, 420000, 480000, 520000, 610000, 590000, 640000, 780000, 890000, 950000]);
    
    $expLabels = json_encode(['Office Rent', 'Salaries & Payroll', 'Marketing & SEO', 'Utilities & Power', 'Software SaaS', 'Travel & Fuel']);
    $expData   = json_encode([75000, 240000, 55000, 18000, 32000, 48000]);
    
    // List seed arrays
    $recentInvoices = [
        ['id' => 1, 'invoice_number' => 'INV-2026-001', 'customer_name' => 'Ravik N Parmar (Architects)', 'issued_date' => date('Y-m-d', strtotime('-1 day')), 'total' => 85000, 'status' => 'Paid'],
        ['id' => 2, 'invoice_number' => 'INV-2026-002', 'customer_name' => 'Sushobha Designs & Builders', 'issued_date' => date('Y-m-d', strtotime('-3 days')), 'total' => 142000, 'status' => 'Partial'],
        ['id' => 3, 'invoice_number' => 'INV-2026-003', 'customer_name' => 'Dhruv Ravikant Parmar', 'issued_date' => date('Y-m-d', strtotime('-5 days')), 'total' => 65000, 'status' => 'Sent'],
        ['id' => 4, 'invoice_number' => 'INV-2026-004', 'customer_name' => 'Krishna Enterprises Pvt Ltd', 'issued_date' => date('Y-m-d', strtotime('-8 days')), 'total' => 160000, 'status' => 'Overdue']
    ];
    
    $topCustomers = [
        ['name' => 'Sushobha Designs & Builders', 'total_paid' => 340000],
        ['name' => 'Ravik N Parmar (Architects)', 'total_paid' => 210000],
        ['name' => 'Krishna Enterprises Pvt Ltd', 'total_paid' => 185000],
        ['name' => 'Dhruv Ravikant Parmar', 'total_paid' => 120000],
        ['name' => 'Parmar Builders Group', 'total_paid' => 95000]
    ];
    

    
    $activityFeed = [
        ['user_name' => 'Ravik Parmar', 'description' => 'updated interested products for Sushobha CRM project', 'created_at' => date('Y-m-d H:i:s', strtotime('-10 mins'))],
        ['user_name' => 'System', 'description' => 'logged automated server database backup execution', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))],
        ['user_name' => 'Dhruv Parmar', 'description' => 'generated new customer account profile', 'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))],
        ['user_name' => 'Sales Team', 'description' => 'scheduled face-to-face meet with prospect builders', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))]
    ];
} else {
    $isDemoMode = false;
    $chartLabels  = json_encode(array_column($revenueRows, 'label'));
    $chartRevenue = json_encode(array_map('floatval', array_column($revenueRows, 'total')));
    
    $expLabels = json_encode(array_column($expCatRows, 'label'));
    $expData   = json_encode(array_map('floatval', array_column($expCatRows, 'total')));
}

// ── Announcements (Communication Board) ────────────────────────
$announcements = $pdo->query("
    SELECT a.*, u.name AS author 
    FROM announcements a 
    LEFT JOIN users u ON a.created_by = u.id 
    WHERE a.is_active = 1 
    ORDER BY a.priority DESC, a.created_at DESC
")->fetchAll();

$showAnnouncements = false;
if (!empty($announcements) && !isset($_SESSION['announcement_shown'])) {
    $showAnnouncements = true;
    $_SESSION['announcement_shown'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment'])) {
    $announcement_id = (int)$_POST['announcement_id'];
    $comment = sanitize($_POST['comment']);
    if ($announcement_id && $comment) {
        $stmt = $pdo->prepare("INSERT INTO announcement_comments (announcement_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$announcement_id, $_SESSION['user_id'], $comment]);
        setFlash('success', 'Comment posted.');
        header('Location: index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<style>
/* Premium Dashboard custom visual accents */
.dashboard-stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.kpi-stat-card {
  background: #ffffff;
  border: 1px solid #e4e7ec;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.04);
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.kpi-stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(99, 102, 241, 0.08), 0 4px 10px rgba(0, 0, 0, 0.02);
  border-color: rgba(99, 102, 241, 0.25);
}

.kpi-icon-box {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
  transition: all 0.25s ease;
}

/* Individual accent card hover effects */
.kpi-stat-card.primary:hover .kpi-icon-box { background-color: #4f46e5 !important; color: #ffffff !important; }
.kpi-stat-card.success:hover .kpi-icon-box { background-color: #10b981 !important; color: #ffffff !important; }
.kpi-stat-card.warning:hover .kpi-icon-box { background-color: #f59e0b !important; color: #ffffff !important; }
.kpi-stat-card.danger:hover .kpi-icon-box  { background-color: #ef4444 !important; color: #ffffff !important; }
.kpi-stat-card.info:hover .kpi-icon-box    { background-color: #06b6d4 !important; color: #ffffff !important; }

.kpi-info-block {
  display: flex;
  flex-direction: column;
}

.kpi-value {
  font-size: 22px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.1;
  margin-bottom: 4px;
  letter-spacing: -0.5px;
}

.kpi-label {
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* Operational action badges */
.status-indicator-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.status-indicator-badge.demo {
  background-color: #eef2ff;
  color: #4f46e5;
  border: 1px solid rgba(79, 70, 229, 0.15);
}

.status-indicator-badge.demo .pulse-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #4f46e5;
  animation: pulse-indigo 1.5s infinite;
}

.status-indicator-badge.active {
  background-color: #ecfdf5;
  color: #059669;
  border: 1px solid rgba(5, 150, 105, 0.15);
}

.status-indicator-badge.active .pulse-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #059669;
  animation: pulse-green 1.5s infinite;
}

@keyframes pulse-indigo {
  0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
  70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(79, 70, 229, 0); }
  100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
}

@keyframes pulse-green {
  0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.7); }
  70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(5, 150, 105, 0); }
  100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(5, 150, 105, 0); }
}

/* Custom Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<div class="main-content">
  <!-- Topbar -->
  <div class="topbar">
    <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
    <div class="topbar-title">Dashboard</div>
    <div class="topbar-right">
      <!-- Notification Bell -->
      <div class="dropdown">
        <button class="notif-btn" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <?php if ($notifCount > 0): ?><span class="notif-badge"><?= $notifCount ?></span><?php endif; ?>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0" style="width:300px;border-radius:12px;overflow:hidden;box-shadow:var(--shadow-md)">
          <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Notifications</span>
            <?php if($notifCount>0): ?><span class="badge bg-light text-primary"><?= $notifCount ?> new</span><?php endif; ?>
          </div>
          <div style="max-height:300px;overflow-y:auto">
            <?php if (empty($notifs)): ?>
              <div class="p-3 text-center text-muted small">No new notifications</div>
            <?php else: foreach ($notifs as $n): ?>
              <a href="<?= BASE_URL . e($n['link'] ?? '#') ?>" class="d-flex align-items-start gap-2 p-3 text-decoration-none border-bottom hover-bg">
                <span class="badge bg-<?= e($n['type']) ?> mt-1" style="font-size:8px;padding:5px">●</span>
                <div>
                  <div class="small fw-semibold text-dark"><?= e($n['title']) ?></div>
                  <div class="text-muted" style="font-size:11px"><?= e($n['message']) ?></div>
                </div>
              </a>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>
      <!-- User dropdown -->
      <div class="dropdown">
        <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown"
                style="background:var(--bg-main);border:1px solid var(--border-color);border-radius:8px;padding:5px 10px">
          <div class="stat-icon primary" style="width:28px;height:28px;border-radius:8px;font-size:12px">
            <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
          </div>
          <span class="small fw-semibold d-none d-md-inline"><?= e($currentUser['name'] ?? '') ?></span>
          <i class="bi bi-chevron-down small"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px">
          <li><a class="dropdown-item small" href="<?= BASE_URL ?>/modules/settings/users.php"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a class="dropdown-item small" href="<?= BASE_URL ?>/modules/settings/index.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item small text-danger" href="<?= BASE_URL ?>/modules/auth/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-content">
    <!-- Page Header -->
    <div class="page-header align-items-center">
      <div class="page-header-left">
        <h1>Dashboard Overview</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb">
          <li class="breadcrumb-item active">Home</li>
        </ol></nav>
      </div>
      
      <div class="d-flex align-items-center gap-3">
        <?php if ($isDemoMode): ?>
          <div class="status-indicator-badge demo" title="Sandbox active with beautiful representative metrics">
            <span class="pulse-dot"></span>
            Demo Sandbox Active
          </div>
        <?php else: ?>
          <div class="status-indicator-badge active" title="Displaying operational real-time DB data">
            <span class="pulse-dot"></span>
            Real-time Active
          </div>
        <?php endif; ?>
        
        <button class="btn btn-sm btn-outline-secondary px-3 py-2 fw-semibold" onclick="window.location.reload()">
          <i class="bi bi-arrow-clockwise me-1"></i>Refresh
        </button>
      </div>
    </div>

    <!-- Flash Messages -->
    <?= flashHtml() ?>

    <!-- ── KPI STATS GRID (ROW 1) ───────────────────────────────── -->
    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card primary">
          <div class="kpi-icon-box bg-light text-primary"><i class="bi bi-people-fill"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= number_format($totalCustomers) ?></div>
            <div class="kpi-label">Total Customers</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card success">
          <div class="kpi-icon-box bg-light text-success"><i class="bi bi-currency-rupee"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= formatCurrency($monthRevenue) ?></div>
            <div class="kpi-label">Revenue This Month</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card warning">
          <div class="kpi-icon-box bg-light text-warning"><i class="bi bi-file-text-fill"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= $pendingQuotes ?></div>
            <div class="kpi-label">Pending Approvals</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card danger">
          <div class="kpi-icon-box bg-light text-danger"><i class="bi bi-cart-fill"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= $openOrders ?></div>
            <div class="kpi-label">Open Orders</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── KPI STATS GRID (ROW 2) ───────────────────────────────── -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card info">
          <div class="kpi-icon-box bg-light text-info"><i class="bi bi-funnel-fill"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= $totalProspects ?></div>
            <div class="kpi-label">Active Leads</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card danger">
          <div class="kpi-icon-box bg-light text-danger"><i class="bi bi-receipt-cutoff"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= $overdueInvoices ?></div>
            <div class="kpi-label">Overdue Invoices</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card warning">
          <div class="kpi-icon-box bg-light text-warning"><i class="bi bi-cash-stack"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= formatCurrency($monthExpenses) ?></div>
            <div class="kpi-label">Expenses This Month</div>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="kpi-stat-card success">
          <div class="kpi-icon-box bg-light text-success"><i class="bi bi-person-badge-fill"></i></div>
          <div class="kpi-info-block">
            <div class="kpi-value"><?= $totalEmployees ?></div>
            <div class="kpi-label">Active Employees</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── CHARTS ─────────────────────────────────────────────── -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="crm-card h-100">
          <div class="crm-card-header">
            <h2 class="crm-card-title"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Monthly Revenue</h2>
          </div>
          <div class="crm-card-body">
            <div class="chart-container" style="height:280px">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="crm-card h-100">
          <div class="crm-card-header">
            <h2 class="crm-card-title"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Expense Breakdown</h2>
          </div>
          <div class="crm-card-body">
            <div class="chart-container" style="height:280px">
              <canvas id="expenseChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── RECENT INVOICES + TOP CUSTOMERS ───────────────────── -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="crm-card h-100">
          <div class="crm-card-header">
            <h2 class="crm-card-title"><i class="bi bi-receipt-cutoff text-success me-2"></i>Recent Invoices</h2>
            <a href="<?= BASE_URL ?>/modules/invoices/index.php" class="btn btn-sm btn-outline-primary">View All</a>
          </div>
          <div class="table-responsive">
            <table class="table crm-table table-hover mb-0">
              <thead><tr>
                <th>Invoice #</th><th>Customer Account</th><th>Issued Date</th><th>Grand Total</th><th>Status</th>
              </tr></thead>
              <tbody>
                <?php foreach ($recentInvoices as $inv): ?>
                <tr>
                  <td><a href="<?= BASE_URL ?>/modules/invoices/view.php?id=<?= $inv['id'] ?>" class="text-primary fw-semibold"><?= e($inv['invoice_number']) ?></a></td>
                  <td><?= e($inv['customer_name']) ?></td>
                  <td><?= formatDate($inv['issued_date']) ?></td>
                  <td class="fw-semibold text-dark"><?= formatCurrency($inv['total']) ?></td>
                  <td><?= statusBadge($inv['status']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentInvoices)): ?>
                  <tr><td colspan="5" class="text-center text-muted py-4">No invoices yet</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="crm-card h-100">
          <div class="crm-card-header">
            <h2 class="crm-card-title"><i class="bi bi-trophy-fill text-warning me-2"></i>Top Customers</h2>
          </div>
          <div class="crm-card-body p-0">
            <?php if (empty($topCustomers)): ?>
              <div class="text-center text-muted p-4 small">No data yet</div>
            <?php else: ?>
              <?php $maxRevenue = max(array_column($topCustomers,'total_paid')) ?: 1; ?>
              <?php foreach ($topCustomers as $idx => $c): ?>
              <div class="px-4 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon primary" style="width:28px;height:28px;border-radius:8px;font-size:11px;flex-shrink:0">
                      <?= ($idx+1) ?>
                    </div>
                    <span class="fw-semibold small"><?= e($c['name']) ?></span>
                  </div>
                  <span class="small fw-bold text-success"><?= formatCurrency($c['total_paid']) ?></span>
                </div>
                <div class="progress" style="height:4px;border-radius:4px">
                  <div class="progress-bar bg-primary" style="width:<?= round($c['total_paid']/$maxRevenue*100) ?>%"></div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ACTIVITY ───────────────────────────────────── -->
    <div class="row g-3">
      <div class="col-12">
        <div class="crm-card h-100">
          <div class="crm-card-header">
            <h2 class="crm-card-title"><i class="bi bi-clock-history text-info me-2"></i>Recent Activity</h2>
          </div>
          <div class="crm-card-body">
            <ul class="timeline">
              <?php foreach ($activityFeed as $log): ?>
              <li class="timeline-item">
                <div class="timeline-dot" style="background:var(--primary)"></div>
                <div class="timeline-content">
                  <span class="fw-semibold text-dark"><?= e($log['user_name'] ?? 'System') ?></span>
                  <span class="text-muted"> <?= e($log['description']) ?></span>
                  <div class="timeline-time"><i class="bi bi-clock me-1"></i><?= formatDateTime($log['created_at']) ?></div>
                </div>
              </li>
              <?php endforeach; ?>
              <?php if (empty($activityFeed)): ?>
                <li class="text-muted small text-center py-3">No activity yet</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.page-content -->
</div><!-- /.main-content -->
</div><!-- /.app-wrapper -->

<?php if ($showAnnouncements): ?>
<!-- Communication Board Modal -->
<div class="modal fade" id="announcementModal" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0" style="border-radius:12px; overflow:hidden;">
      <div class="modal-header bg-primary text-white border-0 py-2">
        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-megaphone-fill me-2"></i>Communication Board</h6>
        <button type="button" class="btn-close btn-close-white" style="font-size: 0.8rem;" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 position-relative" id="announcementBody">
        <div class="progress" style="height:3px; position:absolute; top:0; left:0; width:100%; border-radius:0; z-index:10;">
          <div id="announcementTimer" class="progress-bar bg-warning" style="width:100%; transition: width 0.1s linear;"></div>
        </div>
        
        <div id="announcementCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach($announcements as $idx => $a): ?>
            <div class="carousel-item <?= $idx===0?'active':'' ?> p-3">
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-<?= $a['priority']=='High'?'danger':'primary' ?> me-2" style="font-size: 0.7rem;"><?= e($a['category']) ?></span>
                <span class="text-muted" style="font-size: 0.75rem;"><?= formatDate($a['created_at']) ?> by <?= e($a['author']) ?></span>
              </div>
              <h5 class="fw-bold mb-2"><?= e($a['title']) ?></h5>
              <div class="announcement-content small mb-3" style="white-space: pre-line; line-height: 1.4;">
                <?= e($a['content']) ?>
              </div>
              
              <div class="mt-3 pt-3 border-top">
                <h6 class="fw-bold mb-2" style="font-size: 0.75rem; text-transform: uppercase; color: #6c757d;">Comments</h6>
                <div class="comments-list mb-2 pe-1" style="max-height:100px; overflow-y:auto; overflow-x:hidden;">
                  <?php
                  $comments = $pdo->prepare("SELECT ac.*, u.name FROM announcement_comments ac JOIN users u ON ac.user_id = u.id WHERE ac.announcement_id = ? ORDER BY ac.created_at ASC");
                  $comments->execute([$a['id']]);
                  $comList = $comments->fetchAll();
                  if (empty($comList)): ?>
                    <p class="text-muted mb-0" style="font-size: 0.8rem; font-style: italic;">No comments yet.</p>
                  <?php else: foreach($comList as $c): ?>
                    <div class="mb-2 p-2 bg-light rounded border-start border-primary border-3">
                      <div class="d-flex justify-content-between fw-bold" style="font-size: 0.8rem;"><span><?= e($c['name']) ?></span><span class="text-muted" style="font-size:0.65rem;"><?= formatDateTime($c['created_at']) ?></span></div>
                      <div style="font-size: 0.8rem;"><?= e($c['comment']) ?></div>
                    </div>
                  <?php endforeach; endif; ?>
                </div>
                <form method="POST" class="mt-2">
                  <input type="hidden" name="post_comment" value="1">
                  <input type="hidden" name="announcement_id" value="<?= $a['id'] ?>">
                  <div class="input-group input-group-sm">
                    <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                    <button class="btn btn-primary" type="submit">Post</button>
                  </div>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($announcements) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev" style="width:5%;">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next" style="width:5%;">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
          </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="modal-footer bg-light border-0 py-2 d-flex justify-content-between">
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Dismisses in 10s. Hover to pause.</small>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close Board</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('announcementModal');
    const modal = new bootstrap.Modal(modalEl);
    const progressBar = document.getElementById('announcementTimer');
    const totalTime = 10000; // 10 seconds
    let timeLeft = totalTime;
    let timerInterval;
    let isPaused = false;
 
    modal.show();

    function startTimer() {
        timerInterval = setInterval(() => {
            if (!isPaused) {
                timeLeft -= 100;
                const percentage = (timeLeft / totalTime) * 100;
                progressBar.style.width = percentage + '%';
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    modal.hide();
                }
            }
        }, 100);
    }

    startTimer();

    // Pause on hover
    modalEl.addEventListener('mouseenter', () => { isPaused = true; });
    modalEl.addEventListener('mouseleave', () => { isPaused = false; });
    
    // Also pause if they start typing in a comment field
    modalEl.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', () => { isPaused = true; });
    });
});
</script>
<?php endif; ?>

<!-- Charts Init -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js?v=<?= time() ?>"></script>
<script>
// Revenue Bar Chart
const rCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(rCtx, {
  type: 'bar',
  data: {
    labels: <?= $chartLabels ?>,
    datasets: [{
      label: 'Revenue (₹)',
      data: <?= $chartRevenue ?>,
      backgroundColor: 'rgba(79, 70, 229, 0.12)',
      borderColor: '#4f46e5',
      borderWidth: 2,
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.05)' },
           ticks: { callback: v => '\u20B9' + (v >= 100000 ? (v/100000)+'L' : (v >= 1000 ? (v/1000)+'k' : v)) } },
      x: { grid: { display: false } }
    }
  }
});

// Expense Doughnut Chart
const eCtx = document.getElementById('expenseChart').getContext('2d');
new Chart(eCtx, {
  type: 'doughnut',
  data: {
    labels: <?= $expLabels ?>,
    datasets: [{
      data: <?= $expData ?>,
      backgroundColor: ['#4f46e5','#10b981','#f59e0b','#ef4444','#06b6d4','#8b5cf6'],
      borderWidth: 0,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
    cutout: '65%'
  }
});

// Sidebar overlay
document.getElementById('sidebarOverlay')?.addEventListener('click', closeSidebar);
</script>
</body></html>
