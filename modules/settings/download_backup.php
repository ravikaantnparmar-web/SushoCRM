<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
requireRole(['super_admin', 'admin']);
require_once __DIR__ . '/../../includes/functions.php';

// Only admins can download backups
requireRole(['super_admin', 'admin']);

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filePath = __DIR__ . '/../../backups/' . $file;

    if (file_exists($filePath)) {
        // Log the download
        logActivity('settings', 'download', "Backup downloaded: $file");

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die("File not found.");
    }
} else {
    die("No file specified.");
}
