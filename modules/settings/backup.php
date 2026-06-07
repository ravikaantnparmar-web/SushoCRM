<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
requireRole(['super_admin', 'admin']);

$pageTitle = 'System Backup';
$db = db();

// Ensure backup directory exists
$backupDir = __DIR__ . '/../../backups/';
if (!is_dir($backupDir)) mkdir($backupDir, 0775, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $timestamp = date('Y-m-d_His');
    
    if ($action === 'backup_db') {
        $filename = 'db_backup_' . $timestamp . '.sql';
        $filePath = $backupDir . $filename;
        
        try {
            // Simple PHP-based SQL dump for portability
            $tables = [];
            $result = $db->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $sqlDump = "-- SushobhaCRM Database Backup\n";
            $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                $res = $db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
                $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n" . $res[1] . ";\n\n";
                
                $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $keys = array_keys($row);
                    $vals = array_map(function($v) use ($db) {
                        if ($v === null) return 'NULL';
                        return $db->quote($v);
                    }, array_values($row));
                    $sqlDump .= "INSERT INTO `$table` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $vals) . ");\n";
                }
                $sqlDump .= "\n\n";
            }
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            file_put_contents($filePath, $sqlDump);
            logActivity('settings', 'backup', "Database backup created: $filename");
            setFlash('success', 'Database backup created successfully: ' . $filename);
        } catch (Exception $e) {
            setFlash('danger', 'Database backup failed: ' . $e->getMessage());
        }
    } 
    elseif ($action === 'restore_db') {
        $file = $_POST['filename'];
        $filePath = $backupDir . $file;
        if (file_exists($filePath)) {
            try {
                $sql = file_get_contents($filePath);
                $db->exec("SET FOREIGN_KEY_CHECKS=0;");
                $db->exec($sql);
                $db->exec("SET FOREIGN_KEY_CHECKS=1;");
                logActivity('settings', 'restore', "Database restored from: $file");
                setFlash('success', 'Database restored successfully from ' . $file);
            } catch (Exception $e) {
                setFlash('danger', 'Database restore failed: ' . $e->getMessage());
            }
        }
    }
    elseif ($action === 'backup_files') {
        $filename = 'files_backup_' . $timestamp . '.zip';
        $filePath = $backupDir . $filename;
        
        if (!extension_loaded('zip')) {
            setFlash('danger', 'PHP Zip extension is not loaded.');
        } else {
            $zip = new ZipArchive();
            if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $rootPath = realpath(__DIR__ . '/../../');
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePathRelative = substr($file->getRealPath(), strlen($rootPath) + 1);
                        
                        // Exclude existing backups and some system files
                        if (strpos($filePathRelative, 'backups' . DIRECTORY_SEPARATOR) === 0) continue;
                        if (strpos($filePathRelative, '.git' . DIRECTORY_SEPARATOR) === 0) continue;
                        if (strpos($filePathRelative, 'node_modules' . DIRECTORY_SEPARATOR) === 0) continue;
                        
                        $zip->addFile($file->getRealPath(), $filePathRelative);
                    }
                }
                $zip->close();
                logActivity('settings', 'backup', "Program files backup created: $filename");
                setFlash('success', 'Files backup created successfully: ' . $filename);
            } else {
                setFlash('danger', 'Failed to create zip file.');
            }
        }
    }
    elseif ($action === 'restore_files') {
        $file = $_POST['filename'];
        $filePath = $backupDir . $file;
        if (file_exists($filePath)) {
            if (!extension_loaded('zip')) {
                setFlash('danger', 'PHP Zip extension is not loaded.');
            } else {
                $zip = new ZipArchive();
                if ($zip->open($filePath) === TRUE) {
                    $rootPath = realpath(__DIR__ . '/../../');
                    $zip->extractTo($rootPath);
                    $zip->close();
                    logActivity('settings', 'restore', "Program files restored from: $file");
                    setFlash('success', 'Program files restored successfully from ' . $file);
                } else {
                    setFlash('danger', 'Failed to open zip file.');
                }
            }
        }
    }
    elseif ($action === 'restore_full') {
        $file = $_POST['filename'];
        $filePath = $backupDir . $file;
        if (file_exists($filePath)) {
            if (!extension_loaded('zip')) {
                setFlash('danger', 'PHP Zip extension is not loaded.');
            } else {
                $zip = new ZipArchive();
                if ($zip->open($filePath) === TRUE) {
                    $rootPath = realpath(__DIR__ . '/../../');
                    $zip->extractTo($rootPath);
                    $zip->close();
                    
                    // Now import the DB
                    $sqlFile = $rootPath . '/database.sql';
                    if (file_exists($sqlFile)) {
                        try {
                            $sql = file_get_contents($sqlFile);
                            $db->exec("SET FOREIGN_KEY_CHECKS=0;");
                            $db->exec($sql);
                            $db->exec("SET FOREIGN_KEY_CHECKS=1;");
                            @unlink($sqlFile);
                            @unlink($rootPath . '/install.php');
                            logActivity('settings', 'restore', "Full system restored from: $file");
                            setFlash('success', 'Full system (Files & Database) restored successfully from ' . $file);
                        } catch (Exception $e) {
                            setFlash('danger', 'Files restored, but DB restore failed: ' . $e->getMessage());
                        }
                    } else {
                        setFlash('warning', 'Files restored, but database.sql was not found in the package.');
                    }
                } else {
                    setFlash('danger', 'Failed to open zip file.');
                }
            }
        }
    }
    elseif ($action === 'backup_full') {
        $timestamp = date('Y-m-d_His');
        $zipFile = 'full_backup_' . $timestamp . '.zip';
        $zipPath = $backupDir . $zipFile;
        $errors = [];

        try {
            // 1. Generate SQL Dump in memory
            $tables = [];
            $result = $db->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) $tables[] = $row[0];
            
            $sqlDump = "-- Full System Backup - Unified Installer\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            foreach ($tables as $table) {
                $res = $db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
                $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n" . $res[1] . ";\n\n";
                $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $keys = array_keys($row);
                    $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, array_values($row));
                    $sqlDump .= "INSERT INTO `$table` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $vals) . ");\n";
                }
                $sqlDump .= "\n\n";
            }
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // 2. Generate Installer Script
            $installerScript = <<<'INSTALLER'
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
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
            $pdo->exec($sql);
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
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
INSTALLER;

            // 3. Create ZIP
            if (extension_loaded('zip')) {
                $zip = new ZipArchive();
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $rootPath = realpath(__DIR__ . '/../../');
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
                    
                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePathRelative = substr($file->getRealPath(), strlen($rootPath) + 1);
                            
                            // Exclude backups folder and .git
                            if (strpos($filePathRelative, 'backups' . DIRECTORY_SEPARATOR) === 0) continue;
                            if (strpos($filePathRelative, '.git' . DIRECTORY_SEPARATOR) === 0) continue;
                            
                            $zip->addFile($file->getRealPath(), $filePathRelative);
                        }
                    }
                    
                    // Inject SQL and Installer
                    $zip->addFromString('database.sql', $sqlDump);
                    $zip->addFromString('install.php', $installerScript);
                    
                    $zip->close();
                } else {
                    $errors[] = "Failed to create unified zip file.";
                }
            } else {
                $errors[] = "Zip extension is missing.";
            }

        } catch (Exception $e) {
            $errors[] = "Backup generation failed: " . $e->getMessage();
        }

        if (empty($errors)) {
            logActivity('settings', 'backup', "Unified portable backup created: $zipFile");
            setFlash('success', 'Unified System Installer created successfully.');
        } else {
            setFlash('danger', 'Backup completed with errors: ' . implode(", ", $errors));
        }
    }
    elseif ($action === 'delete') {
        $file = $_POST['filename'];
        if (file_exists($backupDir . $file)) {
            unlink($backupDir . $file);
            setFlash('success', 'Backup file deleted.');
        }
    }
    
    header('Location: backup.php');
    exit;
}

$backups = array_diff(scandir($backupDir), array('.', '..'));

// Sort by modification time descending (newest first)
usort($backups, function($a, $b) use ($backupDir) {
    return filemtime($backupDir . $b) <=> filemtime($backupDir . $a);
});

include __DIR__ . '/../../includes/header.php';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>
<div class="main-content">
<div class="topbar">
  <button class="topbar-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
  <div class="topbar-title">System Backup</div>
</div>
<div class="page-content">
<?= flashHtml() ?>

<div class="page-header">
  <div class="page-header-left">
    <h1>System Backup</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/modules/dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php">Settings</a></li>
        <li class="breadcrumb-item active">Backup</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex gap-2">
    <form method="POST" class="d-inline">
        <input type="hidden" name="action" value="backup_full">
        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-fill-check me-1"></i>Full System Backup</button>
    </form>
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Partial Backups
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form method="POST">
                    <input type="hidden" name="action" value="backup_db">
                    <button type="submit" class="dropdown-item"><i class="bi bi-database me-2"></i>Backup DB Only</button>
                </form>
            </li>
            <li>
                <form method="POST">
                    <input type="hidden" name="action" value="backup_files">
                    <button type="submit" class="dropdown-item"><i class="bi bi-file-earmark-zip me-2"></i>Backup Files Only</button>
                </form>
            </li>
        </ul>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="crm-card">
            <div class="crm-card-body p-0">
                <table class="table crm-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Created Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($backups)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-5">No backups found. Click above to create one.</td></tr>
                        <?php else: 
                            $sn = 1;
                            foreach($backups as $file): 
                            $fullPath = $backupDir . $file;
                            $size = filesize($fullPath);
                            
                            $type = 'Unknown';
                            $badgeClass = 'secondary';
                            if (strpos($file, 'db_') === 0) {
                                $type = 'Database';
                                $badgeClass = 'info-subtle text-info';
                            } elseif (strpos($file, 'files_') === 0) {
                                $type = 'Program Files';
                                $badgeClass = 'dark-subtle text-dark';
                            } elseif (strpos($file, 'full_backup_') === 0) {
                                $type = 'Unified Package';
                                $badgeClass = 'primary-subtle text-primary';
                            }
                            ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td class="fw-semibold text-dark"><?= e($file) ?></td>
                                <td>
                                    <span class="badge bg-<?= $badgeClass ?> border">
                                        <?= $type ?>
                                    </span>
                                </td>
                                <td><?= formatBytes($size) ?></td>
                                <td><?= date('d M Y, h:i A', filemtime($fullPath)) ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="download_backup.php?file=<?= urlencode($file) ?>" class="btn btn-sm btn-icon btn-outline-success" title="Download"><i class="bi bi-download"></i></a>
                                        
                                        <form method="POST" class="d-inline" onsubmit="return confirm('WARNING: This will overwrite your entire system (files and database). Are you absolutely sure?')">
                                            <?php 
                                                $actionValue = '';
                                                if ($type === 'Database') $actionValue = 'restore_db';
                                                elseif ($type === 'Program Files') $actionValue = 'restore_files';
                                                else $actionValue = 'restore_full';
                                            ?>
                                            <input type="hidden" name="action" value="<?= $actionValue ?>">
                                            <input type="hidden" name="filename" value="<?= e($file) ?>">
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>

                                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this backup file?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="filename" value="<?= e($file) ?>">
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div></div>

<?php 
function formatBytes($b) {
    if ($b >= 1073741824) return number_format($b / 1073741824, 2) . ' GB';
    if ($b >= 1048576)    return number_format($b / 1048576, 2) . ' MB';
    if ($b >= 1024)       return number_format($b / 1024, 2) . ' KB';
    return $b . ' B';
}
include __DIR__ . '/../../includes/footer.php'; 
?>
