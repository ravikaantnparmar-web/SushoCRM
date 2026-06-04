<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','sushobha_crm');
$baseDir = 'C:\\xampp\\htdocs\\SushobhaCRM';

$m = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($m->connect_error) die("Connect failed: " . $m->connect_error . "\n");
echo "Connected.\n";

$sql = file_get_contents($baseDir . '\\sql\\leads_full_schema.sql');
$m->multi_query($sql);
$errs = 0;
$stmts = 0;
do {
    $stmts++;
    if ($m->errno && $m->errno != 1060 && $m->errno != 1061 && $m->errno != 1050) {
        echo "Error ({$m->errno}): {$m->error}\n";
        $errs++;
    }
} while ($m->next_result());
echo "leads_full_schema.sql: $stmts statements, $errs errors\n";
$m->close();

// Verify
$m2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$tables = ['leads','lead_contacts','lead_interested_products','lead_meetings','lead_documents','lead_timeline'];
foreach ($tables as $t) {
    $r = $m2->query("SHOW TABLES LIKE '$t'");
    echo ($r->num_rows > 0 ? "✓" : "✗") . " $t\n";
}

// Show leads columns
$r = $m2->query("SHOW COLUMNS FROM leads");
echo "\nLeads columns:\n";
while ($row = $r->fetch_assoc()) {
    echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
$m2->close();
echo "\nDone!\n";
