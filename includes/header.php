<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . APP_NAME : APP_NAME;
$notifs = function_exists('getUnreadNotifications') ? getUnreadNotifications() : [];
$notifCount = count($notifs);
$currentUser = function_exists('currentUser') ? currentUser() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= APP_NAME ?> — Professional Business Management System">
<title><?= e($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="app-wrapper">
