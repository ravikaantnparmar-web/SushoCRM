<?php
require_once __DIR__ . '/config/session.php';
$_SESSION['user_id'] = 1;
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
require_once __DIR__ . '/modules/prospects/check_reminders_ajax.php';
$output = ob_get_clean();
echo $output;
