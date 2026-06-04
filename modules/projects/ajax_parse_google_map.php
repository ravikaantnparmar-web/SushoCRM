<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();

header('Content-Type: application/json');

$url = $_GET['url'] ?? '';

if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['success' => false, 'error' => 'Invalid URL']);
    exit;
}

// Only allow google maps urls to prevent arbitrary SSRF
if (!preg_match('/(google\.com\/maps|goo\.gl\/maps|maps\.app\.goo\.gl|maps\.google\.com)/i', $url)) {
    echo json_encode(['success' => false, 'error' => 'Not a valid Google Maps URL']);
    exit;
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
// Follow redirects to resolve shortened URLs like goo.gl
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);

$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

$address = '';

// Try to parse the place name or address from the final URL path
if (preg_match('/\/maps\/place\/([^\/]+)/i', $finalUrl, $matches)) {
    $address = urldecode($matches[1]);
    $address = str_replace('+', ' ', $address);
} elseif (preg_match('/\/maps\/search\/([^\/]+)/i', $finalUrl, $matches)) {
    $address = urldecode($matches[1]);
    $address = str_replace('+', ' ', $address);
} elseif (preg_match('/\/maps\/dir\/[^\/]+\/([^\/]+)/i', $finalUrl, $matches)) {
    $address = urldecode($matches[1]);
    $address = str_replace('+', ' ', $address);
} elseif (preg_match('/[?&]q=([^&]+)/i', $finalUrl, $matches)) {
    $address = urldecode($matches[1]);
    $address = str_replace('+', ' ', $address);
}

if ($address) {
    echo json_encode(['success' => true, 'address' => $address, 'final_url' => $finalUrl]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not extract exact address from URL', 'final_url' => $finalUrl]);
}
