<!-- Main content area ends -->
</div><!-- /.main-content -->
</div><!-- /.app-wrapper -->
<!-- Global Toast Container for Reminders -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
  <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastPlacement" style="z-index: 1055;">
    <!-- Toasts will be dynamically injected here -->
  </div>
</div>

<!-- Global Logout Modal -->
<div class="modal fade" id="globalLogoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:14px;">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center gap-2">
          <div style="width:38px;height:38px;background:#fff3cd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-box-arrow-right text-warning fs-5"></i>
          </div>
          <h5 class="modal-title fw-bold">Confirm Logout</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-2 pb-2">
        <p class="text-muted mb-0">Are you sure you want to log out of the system?</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= BASE_URL ?>/modules/auth/logout.php" class="btn btn-warning btn-sm fw-semibold">Yes, Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js?v=<?= time() ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/meeting-reminders.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var sidebar = document.getElementById('sortableSidebar');
    if (sidebar) {
        Sortable.create(sidebar, {
            animation: 150,
            handle: '.drag-handle', // use the grip icon as the drag handle for precision
            onEnd: function (evt) {
                var newOrder = [];
                sidebar.querySelectorAll('.sidebar-sortable-item').forEach(function(el) {
                    var id = el.getAttribute('data-id');
                    if (id) {
                        newOrder.push(id);
                    }
                });
                
                // Send AJAX request to save order
                fetch('<?= BASE_URL ?>/modules/auth/save_sidebar_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: newOrder })
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        console.error('Failed to save sidebar order');
                    }
                });
            }
        });
    }
});
</script>
<?php if (isset($extraScripts)): ?>
  <?= $extraScripts ?>
<?php endif; ?>

<!-- Navigation Protection -->
<script>
window.CRM_CONFIG = {
  baseUrl:        '<?= BASE_URL ?>',
  sessionTimeout: <?= SESSION_TIMEOUT ?>,
  userId:         <?= json_encode($_SESSION['user_id'] ?? null) ?>
};
</script>
<script src="<?= BASE_URL ?>/assets/js/nav-guard.js?v=<?= filemtime(__DIR__.'/../assets/js/nav-guard.js') ?>"></script>
</body>
</html>
