<?php
require 'config/db.php';
$db = db();
$db->exec("UPDATE purchases SET terms = '1. Please supply items as per specifications.\n2. Payment will be processed within 30 days of delivery.' WHERE terms IS NULL OR terms = ''");
echo "Updated terms for purchases in DB.\n";
