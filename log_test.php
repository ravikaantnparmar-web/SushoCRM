<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/config/config.php';
$log = "OPCACHE DEFINED: " . CURRENCY_SYMBOL . "\n";
file_put_contents(__DIR__ . '/test_log.txt', $log);
echo "LOGGED";
