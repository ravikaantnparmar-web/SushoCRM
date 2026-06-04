<?php
require 'config/db.php';

$tables = db()->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
echo "Tables:\n";
print_r($tables);

$important_tables = ['leads', 'lead_contacts', 'customers', 'customer_contacts', 'quotations', 'projects', 'sales_orders'];
foreach ($important_tables as $table) {
    if (in_array($table, $tables)) {
        echo "\nStructure of $table:\n";
        $stmt = db()->query("SHOW COLUMNS FROM $table");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
