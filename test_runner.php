<?php
ob_start();
require 'test_currency.php';
$out = ob_get_clean();
file_put_contents('test_out.txt', $out);
echo "done";
