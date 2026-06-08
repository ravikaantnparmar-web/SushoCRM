<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

// --- Output & Sanitization ---
function e(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitize(?string $str): string {
    return trim(strip_tags($str ?? ''));
}

function formatCurrency(float $amount): string {
    $sym = defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : '&#8377;';
    // Ensure ₹ always displays correctly regardless of encoding (&#8377; = U+20B9)
    if (empty(trim($sym)) || is_numeric(trim($sym))) {
        $sym = '&#8377;';
    }
    return $sym . ' ' . number_format($amount, 2);
}

function formatDate(string $date, string $format = 'd M Y'): string {
    if (empty($date) || $date === '0000-00-00') return '-';
    return date($format, strtotime($date));
}

function formatDateTime(string $dt): string {
    return formatDate($dt, 'd M Y, h:i A');
}

// --- Flash Messages ---
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function flashHtml(): string {
    $flash = getFlash();
    if (!$flash) return '';
    $icons = ['success'=>'check-circle','danger'=>'x-circle','warning'=>'exclamation-triangle','info'=>'info-circle'];
    $icon = $icons[$flash['type']] ?? 'info-circle';
    return '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-' . $icon . '-fill me-2"></i>
        <div>' . e($flash['message']) . '</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

// --- Code Generators ---
function generateCode(string $prefix, string $table, string $column): string {
    $stmt = db()->query("SELECT MAX(CAST(SUBSTRING({$column}, " . (strlen($prefix)+1) . ") AS UNSIGNED)) AS max_num FROM {$table}");
    $row = $stmt->fetch();
    $next = ($row['max_num'] ?? 0) + 1;
    return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
}

function generateInvoiceNumber(): string { return generateCode(INVOICE_PREFIX, 'invoices', 'invoice_number'); }
function generateOrderNumber(): string   { return generateCode(ORDER_PREFIX,   'orders',   'order_number'); }
function generateQuoteNumber(): string   { return generateCode(QUOTE_PREFIX,   'quotations','quote_number'); }
function generatePurchaseNumber(): string{ return generateCode(PURCHASE_PREFIX,'purchases', 'purchase_number'); }
function generateCustomerCode(): string  { return generateCode(CUSTOMER_PREFIX,'customers', 'customer_code'); }
function generateVendorCode(): string    { return generateCode(VENDOR_PREFIX,  'vendors',   'vendor_code'); }
function generateEmpCode(): string       { return generateCode(EMP_PREFIX,     'employees', 'emp_code'); }
function generateTaskNumber(): string    { return generateCode('TSK-',         'tasks',     'task_number'); }
function generateLeadCode(): string      { 
    $prefix = LEAD_PREFIX; // "LEAD" (4 chars)
    $column = 'lead_code';
    $table = 'leads';
    $stmt = db()->query("SELECT MAX(CAST(SUBSTRING({$column}, " . (strlen($prefix)+1) . ") AS UNSIGNED)) AS max_num FROM {$table}");
    $row = $stmt->fetch();
    $next = ($row['max_num'] ?? 0) + 1;
    return $prefix . str_pad($next, 6, '0', STR_PAD_LEFT); 
}


// --- Activity Logging ---
function logActivity(string $module, string $action, string $description, ?int $recordId = null): void {
    try {
        $userId = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
        db()->prepare("INSERT INTO activity_logs (user_id, module, action, description, record_id, ip_address, user_agent) VALUES (?,?,?,?,?,?,?)")
           ->execute([$userId, $module, $action, $description, $recordId, $ip, $ua]);
    } catch (Exception $e) { /* silent */ }
}

// --- Notifications ---
function createNotification(int $userId, string $title, string $message, string $type = 'info', string $link = ''): void {
    db()->prepare("INSERT INTO notifications (user_id, title, message, type, link) VALUES (?,?,?,?,?)")
       ->execute([$userId, $title, $message, $type, $link]);
}

function getUnreadNotifications(): array {
    if (!isset($_SESSION['user_id'])) return [];
    $stmt = db()->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchAll();
}

// --- Pagination ---
function paginate(int $total, int $perPage, int $currentPage, string $url): array {
    $totalPages = max(1, (int)ceil($total / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    return ['total'=>$total,'per_page'=>$perPage,'current'=>$currentPage,'total_pages'=>$totalPages,'offset'=>$offset,'url'=>$url];
}

function paginationHtml(array $p): string {
    if ($p['total_pages'] <= 1) return '';
    $html = '<nav><ul class="pagination pagination-sm mb-0">';
    $html .= '<li class="page-item' . ($p['current']<=1?' disabled':'') . '"><a class="page-link" href="' . $p['url'] . '&page=' . ($p['current']-1) . '">‹</a></li>';
    $start = max(1, $p['current']-2);
    $end   = min($p['total_pages'], $p['current']+2);
    if ($start > 1) $html .= '<li class="page-item"><a class="page-link" href="' . $p['url'] . '&page=1">1</a></li>' . ($start>2?'<li class="page-item disabled"><span class="page-link">…</span></li>':'');
    for ($i=$start; $i<=$end; $i++) {
        $html .= '<li class="page-item' . ($i==$p['current']?' active':'') . '"><a class="page-link" href="' . $p['url'] . '&page=' . $i . '">' . $i . '</a></li>';
    }
    if ($end < $p['total_pages']) $html .= ($end<$p['total_pages']-1?'<li class="page-item disabled"><span class="page-link">…</span></li>':'') . '<li class="page-item"><a class="page-link" href="' . $p['url'] . '&page=' . $p['total_pages'] . '">' . $p['total_pages'] . '</a></li>';
    $html .= '<li class="page-item' . ($p['current']>=$p['total_pages']?' disabled':'') . '"><a class="page-link" href="' . $p['url'] . '&page=' . ($p['current']+1) . '">›</a></li>';
    $html .= '</ul></nav>';
    return $html;
}

// --- File Upload ---
function uploadFile(array $file, string $subfolder = 'misc', array $allowed = []): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        logActivity('System', 'Upload Failed', 'File upload error code: ' . $file['error']);
        return null;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) { 
        setFlash('danger', 'File too large (max ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB).'); 
        logActivity('System', 'Upload Blocked', 'File exceeded maximum size limit.');
        return null; 
    }

    // Prevent Directory Traversal on subfolder
    $subfolder = preg_replace('/[^a-zA-Z0-9_-]/', '', $subfolder);
    if (empty($subfolder)) $subfolder = 'misc';

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = $allowed ?: array_merge(ALLOWED_IMAGES, ALLOWED_DOCS);
    
    if (!in_array($ext, $allowedExts)) { 
        setFlash('danger', 'File extension not allowed.'); 
        logActivity('System', 'Upload Blocked', "Attempted to upload blocked extension: {$ext}");
        return null; 
    }

    // Validate MIME type using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    $allowedMimes = [
        // Images
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp',
        // Docs
        'pdf' => 'application/pdf', 'txt' => 'text/plain', 'csv' => 'text/csv',
        'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (!isset($allowedMimes[$ext]) || $allowedMimes[$ext] !== $mimeType) {
        // Fallback for some generic text types or Edge cases, but strict by default
        setFlash('danger', 'Invalid file content or MIME type mismatch.');
        logActivity('System', 'Upload Blocked', "MIME type mismatch. Ext: {$ext}, MIME: {$mimeType}");
        return null;
    }

    $dir = rtrim(UPLOAD_PATH, '/') . '/' . $subfolder . '/';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            logActivity('System', 'Upload Error', "Failed to create directory: {$dir}");
            return null;
        }
    }

    // Use cryptographically secure random names to prevent prediction/overwrites
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $targetPath = $dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        logActivity('System', 'Upload Success', "File uploaded successfully: {$filename}");
        return 'uploads/' . $subfolder . '/' . $filename;
    }
    
    logActivity('System', 'Upload Error', 'Failed to move uploaded file.');
    return null;
}

// --- Badge Helpers ---
function statusBadge(string $status, array $map = []): string {
    $defaults = [
        'active'=>'success','inactive'=>'secondary','pending'=>'warning','paid'=>'success','unpaid'=>'danger',
        'partial'=>'info','draft'=>'secondary','sent'=>'primary','accepted'=>'success','rejected'=>'danger',
        'converted'=>'info','processing'=>'warning','delivered'=>'success','cancelled'=>'danger',
        'new'=>'primary','contacted'=>'info','qualified'=>'success','won'=>'success','lost'=>'danger',
        'overdue'=>'danger','present'=>'success','absent'=>'danger','half_day'=>'warning',
        'low'=>'secondary','medium'=>'warning','high'=>'danger','urgent'=>'dark',
        'in_progress'=>'primary','completed'=>'success',
        'planned'=>'secondary','ongoing'=>'info','successful'=>'success',
    ];
    $map = array_merge($defaults, $map);
    $color = $map[$status] ?? 'secondary';
    $label = ucwords(str_replace('_', ' ', $status));
    return '<span class="badge bg-' . $color . '">' . $label . '</span>';
}

// --- Data fetch helpers ---
function getAllCustomers(): array {
    return db()->query("
        SELECT c.id, 
               COALESCE(con.name, '') AS name, 
               c.company_name AS company 
        FROM customers c 
        LEFT JOIN contact_relations cr ON c.id = cr.entity_id AND cr.entity_type = 'customer' AND cr.is_primary = 1 
        LEFT JOIN contacts con ON cr.contact_id = con.id
        WHERE c.company_status='Active' 
        ORDER BY c.company_name
    ")->fetchAll();
}
function getAllProducts(): array {
    return db()->query("SELECT id, name, sku, selling_price, tax_rate, unit FROM products WHERE is_active=1 ORDER BY name")->fetchAll();
}
function getAllVendors(): array {
    return db()->query("SELECT id, name, company FROM vendors WHERE status='active' ORDER BY name")->fetchAll();
}
function getAllUsers(): array {
    return db()->query("SELECT id, name, email FROM users WHERE is_active=1 ORDER BY name")->fetchAll();
}

// --- Amount in Words ---
function amountInWords(float $number): string {
    $decimal = round($number - floor($number), 2) * 100;
    $hundred = null;
    $digits_length = strlen((string)floor($number));
    $i = 0;
    $str = array();
    $words = array(
        0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    $num = floor($number);
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($num % $divider);
        $num = floor($num / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else {
            $str[] = null;
        }
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? " and " . ($words[floor($decimal / 10) * 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . ' Only';
}

