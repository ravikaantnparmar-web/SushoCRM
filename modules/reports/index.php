<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$pageTitle = 'Reports';

// Basic summary metrics for reports
$sales = db()->query("SELECT SUM(total) FROM invoices WHERE status != 'cancelled'")->fetchColumn() ?: 0;
$expenses = db()->query("SELECT SUM(amount) FROM expenses")->fetchColumn() ?: 0;
$purchases = db()->query("SELECT SUM(total) FROM purchases WHERE status != 'cancelled'")->fetchColumn() ?: 0;
$profit = $sales - $expenses - $purchases;

// Monthly sales for the current year
$year = date('Y');
$monthlySales = db()->query("SELECT MONTH(created_at) as m, SUM(total) as t FROM invoices WHERE YEAR(created_at) = '$year' AND status != 'cancelled' GROUP BY m")->fetchAll(PDO::FETCH_KEY_PAIR);

$monthlySalesData = [];
for ($i=1; $i<=12; $i++) {
    $monthlySalesData[] = $monthlySales[$i] ?? 0;
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">Reports & Analytics</div>
</div>
<div class="page-content">
<div class="page-header">
  <div class="page-header-left"><h1>Reports</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
  </div>
  <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print Report</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="crm-card bg-primary text-white h-100">
            <div class="crm-card-body p-4">
                <div class="text-white-50 text-uppercase small fw-bold mb-2">Total Sales</div>
                <h3 class="fw-bold mb-0"><?= formatCurrency($sales) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card bg-danger text-white h-100">
            <div class="crm-card-body p-4">
                <div class="text-white-50 text-uppercase small fw-bold mb-2">Total Expenses</div>
                <h3 class="fw-bold mb-0"><?= formatCurrency($expenses) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card bg-warning text-dark h-100">
            <div class="crm-card-body p-4">
                <div class="text-dark text-opacity-50 text-uppercase small fw-bold mb-2">Total Purchases</div>
                <h3 class="fw-bold mb-0"><?= formatCurrency($purchases) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card bg-success text-white h-100">
            <div class="crm-card-body p-4">
                <div class="text-white-50 text-uppercase small fw-bold mb-2">Net Profit/Loss</div>
                <h3 class="fw-bold mb-0"><?= formatCurrency($profit) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="crm-card h-100">
            <div class="crm-card-body p-4">
                <h5 class="fw-bold mb-4">Sales Overview (<?= $year ?>)</h5>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="crm-card h-100">
            <div class="crm-card-body p-4">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <div class="list-group list-group-flush border-top border-bottom mb-3">
                    <a href="<?= BASE_URL ?>/modules/invoices/index.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-receipt me-2 text-primary"></i>Invoice Reports</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/modules/expenses/index.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-cash-stack me-2 text-danger"></i>Expense Reports</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/modules/purchases/index.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-bag me-2 text-warning"></i>Purchase Reports</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/modules/payments/index.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-credit-card me-2 text-success"></i>Payment Logs</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/modules/reports/tasks.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div><i class="bi bi-list-task me-2 text-info"></i>Task Analytics</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('salesChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Sales (₹)',
                data: <?= json_encode($monthlySalesData) ?>,
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#e5e7eb' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
