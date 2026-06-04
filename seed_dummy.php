<?php
require 'config/db.php';
$db = db();
try {
    $db->exec("INSERT INTO tasks (title, status, priority) VALUES ('Finalize Architecture Plans', 'in_progress', 'high'), ('Order Cement', 'pending', 'medium')");
    $db->exec("INSERT INTO projects (project_number, name, status, budget) VALUES ('PRJ-2026-001', 'Skyline Tower Construction', 'in_progress', 5000000)");
    $pid = $db->lastInsertId();
    $db->exec("INSERT INTO project_boq (project_id, item_description, unit, estimated_qty, estimated_rate, estimated_amount) VALUES ($pid, 'Cement Bags', 'Bags', 1000, 400, 400000)");
    echo "Seeded dummy data.\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
