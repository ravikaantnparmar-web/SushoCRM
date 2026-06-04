<?php
session_start();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'] ?? 'localhost';
    $name = $_POST['db_name'] ?? 'sushobha_crm';
    $user = $_POST['db_user'] ?? 'root';
    $pass = $_POST['db_pass'] ?? '';

    try {
        // Connect to MySQL server without DB first
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$name`");

        // Import SQL Dump
        if (file_exists(__DIR__ . '/database.sql')) {
            $sql = file_get_contents(__DIR__ . '/database.sql');
            $pdo->exec($sql);
        } else {
            throw new Exception("database.sql not found! Ensure all files were extracted properly.");
        }

        // Update config/db.php
        $dbConfigFile = __DIR__ . '/config/db.php';
        if (file_exists($dbConfigFile)) {
            $content = file_get_contents($dbConfigFile);
            $content = preg_replace("/\\\$host\s*=\s*'.*?';/", "\$host   = '$host';", $content);
            $content = preg_replace("/\\\$dbname\s*=\s*'.*?';/", "\$dbname = '$name';", $content);
            $content = preg_replace("/\\\$user\s*=\s*'.*?';/", "\$user   = '$user';", $content);
            $content = preg_replace("/\\\$pass\s*=\s*'.*?';/", "\$pass   = '$pass';", $content);
            file_put_contents($dbConfigFile, $content);
        }

        // Cleanup
        @unlink(__DIR__ . '/database.sql');
        $success = "Installation successful! The installer file will now delete itself.";
        
        // Output success and JS to redirect and delete self via AJAX or just header redirect
        echo "<html><body><h2>$success</h2><p>Redirecting to CRM...</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script></body></html>";
        
        // Delete self using register_shutdown_function to ensure output is sent
        register_shutdown_function(function() {
            @unlink(__FILE__);
        });
        exit;

    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SushobhaCRM Installer</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: 500; margin-bottom: 5px; color: #374151; }
        input { width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #2563eb; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-top:0; color:#111827; text-align:center;">Install SushobhaCRM</h2>
        <p style="color:#6b7280; font-size:14px; text-align:center; margin-bottom:20px;">Provide your database credentials to setup the system.</p>
        
        <?php if($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Database Host</label>
                <input type="text" name="db_host" value="localhost" required>
            </div>
            <div class="form-group">
                <label>Database Name</label>
                <input type="text" name="db_name" value="sushobha_crm" required>
            </div>
            <div class="form-group">
                <label>Database User</label>
                <input type="text" name="db_user" value="root" required>
            </div>
            <div class="form-group">
                <label>Database Password</label>
                <input type="password" name="db_pass">
            </div>
            <button type="submit">Install System</button>
        </form>
    </div>
</body>
</html>