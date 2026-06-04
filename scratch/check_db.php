<?php
require 'config/db.php';
try {
    $stmt = db()->query("SHOW TABLES LIKE 'lead_documents'");
    $exists = $stmt->fetch();
    if ($exists) {
        echo "Table lead_documents exists.\n";
        $cols = db()->query("DESCRIBE lead_documents")->fetchAll(PDO::FETCH_ASSOC);
        print_r($cols);
    } else {
        echo "Table lead_documents DOES NOT exist.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
