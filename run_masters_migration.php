<?php
/**
 * SushobhaCRM - Master Tables Migration Runner
 * Run this file ONCE via browser: http://localhost/SushobhaCRM/run_masters_migration.php
 * Then DELETE this file for security.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

$results = [];

function runSqlFile(string $path): array {
    $results = [];
    $sql = file_get_contents($path);
    if ($sql === false) return [['status'=>'error','msg'=>"Cannot read $path"]];
    
    // Split on semicolons but ignore those inside strings (basic split)
    $pdo = db();
    
    // Use multi_query approach: split by SET/CREATE/INSERT blocks
    $statements = array_filter(
        array_map('trim', preg_split('/;\s*\n/', $sql)),
        fn($s) => strlen($s) > 5 && !str_starts_with($s, '--')
    );
    
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (empty($stmt) || $stmt === ';') continue;
        try {
            $pdo->exec($stmt);
            $results[] = ['status'=>'ok','sql'=>substr($stmt,0,80).'...'];
        } catch (PDOException $e) {
            // Duplicate entry is fine for ON DUPLICATE KEY
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), '1062')) {
                $results[] = ['status'=>'skip','sql'=>substr($stmt,0,80).'...'];
            } else {
                $results[] = ['status'=>'error','sql'=>substr($stmt,0,80).'...','msg'=>$e->getMessage()];
            }
        }
    }
    return $results;
}

echo '<!DOCTYPE html><html><head><title>CRM Migration</title>
<style>
  body{font-family:monospace;padding:20px;background:#0f172a;color:#e2e8f0;}
  h2{color:#38bdf8;} h3{color:#94a3b8;margin-top:20px;}
  .ok{color:#4ade80;} .error{color:#f87171;} .skip{color:#fbbf24;}
  .box{background:#1e293b;padding:15px;border-radius:8px;margin:10px 0;}
  .done{background:#14532d;padding:20px;border-radius:8px;font-size:18px;color:#4ade80;margin-top:20px;}
</style></head><body>';
echo '<h2>🚀 SushobhaCRM — Master Tables Migration</h2>';

$files = [
    'Alter Leads & Customers Tables' => __DIR__ . '/sql/alter_leads.sql',
    'Create & Seed 17 Master Tables' => __DIR__ . '/sql/masters_migration.sql',
];

$hasErrors = false;
foreach ($files as $label => $file) {
    echo "<div class='box'><h3>▶ $label</h3>";
    if (!file_exists($file)) {
        echo "<div class='error'>❌ File not found: $file</div></div>";
        $hasErrors = true;
        continue;
    }
    
    // Execute via mysqli for multi-statement support
    $dbConfig = ['host'=>DB_HOST,'user'=>DB_USER,'pass'=>DB_PASS,'name'=>DB_NAME];
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);
    if ($mysqli->connect_error) {
        echo "<div class='error'>❌ Connection failed: " . $mysqli->connect_error . "</div></div>";
        $hasErrors = true;
        continue;
    }
    
    $sql = file_get_contents($file);
    $mysqli->multi_query($sql);
    $stmtCount = 0;
    $errCount = 0;
    do {
        $stmtCount++;
        if ($mysqli->errno && $mysqli->errno !== 1060 && $mysqli->errno !== 1062) {
            echo "<div class='error'>⚠ Statement $stmtCount: " . htmlspecialchars($mysqli->error) . "</div>";
            $errCount++;
        }
    } while ($mysqli->next_result());
    
    $mysqli->close();
    
    if ($errCount === 0) {
        echo "<div class='ok'>✅ Completed $stmtCount statements — 0 errors</div>";
    } else {
        echo "<div class='error'>❌ Completed $stmtCount statements — $errCount errors</div>";
        $hasErrors = true;
    }
    echo "</div>";
}

if (!$hasErrors) {
    echo "<div class='done'>✅ All migrations completed successfully!<br><br>
    <small>⚠ Delete this file now: <strong>run_masters_migration.php</strong></small></div>";
} else {
    echo "<div style='background:#7f1d1d;padding:20px;border-radius:8px;color:#fca5a5;margin-top:20px;'>
    ❌ Some migrations had errors. Review above and fix before proceeding.</div>";
}

echo '</body></html>';
