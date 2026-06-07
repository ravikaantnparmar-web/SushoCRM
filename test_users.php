<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\db.php';
$stmt = db()->query('SELECT u.id, u.name, u.role_id, r.name as role_name, r.slug as role_slug FROM users u JOIN roles r ON u.role_id = r.id');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
