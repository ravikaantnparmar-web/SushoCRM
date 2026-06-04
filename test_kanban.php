<?php
require_once __DIR__ . '/config/session.php';
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'Admin';
ob_start();
require_once __DIR__ . '/modules/prospects/kanban.php';
$output = ob_get_clean();

// Find and print the kanban-board section
$start = strpos($output, '<div class="kanban-board');
$end = strpos($output, '<style>', $start);
echo substr($output, $start, $end - $start);
