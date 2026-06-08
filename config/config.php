<?php
date_default_timezone_set('Asia/Kolkata');
define('APP_NAME', 'SUSHOBHA');
define('APP_TAGLINE', 'Business Management System');
define('APP_VERSION', '1.0.0');
define('COMPANY_NAME', 'Sushobha Business Solutions');
define('COMPANY_EMAIL', 'info@sushobha.com');
define('COMPANY_PHONE', '+91 9898549909');
define('COMPANY_ADDRESS', 'A-102, Swagat Status 2, Off New CG Road, Chandkheda, Ahmedabad - 382424');
define('COMPANY_GST', '29ABCDE1234F1Z5');
define('COMPANY_WEBSITE', 'https://www.sushobha.com');
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    define('BASE_URL', $protocol . '://localhost/SushobhaCRM');
} else {
    define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST']);
}
define('CURRENCY_SYMBOL', "\u20B9"); // Indian Rupee ₹ (Unicode U+20B9)
define('CURRENCY_CODE', 'INR');
define('TAX_NAME', 'GST');
define('DEFAULT_TAX', 18);
define('SESSION_NAME', 'SUSHOBHA_SESSION');
define('SESSION_TIMEOUT', 3600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 900);
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('ALLOWED_IMAGES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCS', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt']);
define('RECORDS_PER_PAGE', 20);
define('INVOICE_PREFIX', 'INV');
define('ORDER_PREFIX', 'ORD');
define('QUOTE_PREFIX', 'QT');
define('PURCHASE_PREFIX', 'PUR');
define('EMP_PREFIX', 'EMP');
define('CUSTOMER_PREFIX', 'CUST');
define('VENDOR_PREFIX', 'VEN');
define('LEAD_PREFIX', 'LEAD');
error_reporting(E_ALL);
ini_set('display_errors', '1');
