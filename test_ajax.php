<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'id' => 1,
    'meeting_status' => 'High',
    'meeting_type' => 'Telephonic',
    'meeting_with_name' => 'Dhruv',
    'meeting_purpose' => 'Test',
    'actual_followup_date' => '2026-06-10T12:00',
    'lead_id' => 3,
    'meeting_lead_status' => 'Won',
    'sales_stage' => 'Stage 3'
];

require_once __DIR__ . '/config/session.php';
$_SESSION['user_id'] = 1;

ob_start();
require_once __DIR__ . '/modules/prospects/update_meeting_ajax.php';
$output = ob_get_clean();
echo "Output: " . $output;
