<?php
require 'config/config.php';
require 'config/db.php';
print_r(db()->query('SELECT * FROM quotation_items ORDER BY id DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC));
