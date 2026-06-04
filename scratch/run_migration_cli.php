<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','sushobha_crm');
$baseDir = 'C:\\xampp\\htdocs\\SushobhaCRM';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) { die('Connect failed: ' . $mysqli->connect_error . "\n"); }
echo "Connected to sushobha_crm database\n";

// ── Run masters_migration.sql ─────────────────────────────
$sql2 = file_get_contents($baseDir . '\\sql\\masters_migration.sql');
if (!$sql2) { die("Cannot read masters_migration.sql\n"); }

// Use fresh connection for masters
$m2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($m2->connect_error) { die('Connect 2 failed: ' . $m2->connect_error . "\n"); }

$m2->multi_query($sql2);
$errors2 = 0;
$stmts = 0;
do {
    $stmts++;
    if($m2->errno && $m2->errno != 1062 && $m2->errno != 1060) {
        echo "MASTER Error ({$m2->errno}): {$m2->error}\n";
        $errors2++;
    }
} while($m2->next_result());
$m2->close();
echo "masters_migration.sql: done ({$stmts} statements, {$errors2} errors)\n";

// ── Verify tables created ─────────────────────────────────
$tables = [
    'lead_statuses','lead_priorities','lead_sources','lead_types',
    'site_stages','project_types','lead_product_types','interested_products',
    'meeting_types','meeting_statuses','contact_types','company_types',
    'industry_types','business_categories','sales_stages',
    'customer_types','address_types','company_statuses'
];

$missing = [];
foreach ($tables as $t) {
    $r = $mysqli->query("SHOW TABLES LIKE '$t'");
    if ($r->num_rows === 0) {
        $missing[] = $t;
    }
}

if (empty($missing)) {
    echo "✓ All 18 master tables verified.\n";
} else {
    echo "✗ Missing tables: " . implode(', ', $missing) . "\n";
}

// ── Count rows per table ──────────────────────────────────
echo "\nRow counts:\n";
foreach ($tables as $t) {
    $r = $mysqli->query("SELECT COUNT(*) as c FROM `$t`");
    if ($r) {
        $row = $r->fetch_assoc();
        echo "  $t: {$row['c']} rows\n";
    }
}

$mysqli->close();
echo "\nMigration complete!\n";
