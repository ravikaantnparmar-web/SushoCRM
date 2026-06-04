<?php
require 'config/config.php';
require 'config/db.php';
print_r(db()->query('DESCRIBE quotation_items')->fetchAll(PDO::FETCH_ASSOC));
