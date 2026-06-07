<?php
$log = "INCLUDED FILES:\n" . print_r(get_included_files(), true) . "\n";
$log .= "DEFINED CONSTANTS:\n" . print_r(get_defined_constants(true)['user'], true);
file_put_contents(__DIR__ . '/test_log.txt', $log);
echo "LOGGED 2";
