<?php
require 'config/config.php';
require 'config/db.php';
$idx = db()->query('SHOW INDEX FROM quotations')->fetchAll(PDO::FETCH_ASSOC);
print_r($idx);
