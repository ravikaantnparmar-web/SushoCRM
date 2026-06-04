<?php
require 'config/db.php';
$check = db()->prepare("SELECT * FROM contacts WHERE email = 'raja@gmail.com' LIMIT 1");
$check->execute();
echo json_encode($check->fetch());
