<!-- Main content area ends -->
</div><!-- /.main-content -->
</div><!-- /.app-wrapper -->
<!-- Global Toast Container for Reminders -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
  <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastPlacement" style="z-index: 1055;">
    <!-- Toasts will be dynamically injected here -->
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
