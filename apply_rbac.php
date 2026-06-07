<?php
$rules = [
    'quotations/index.php' => ['mod' => 'quotations', 'act' => 'view'],
    'quotations/view.php' => ['mod' => 'quotations', 'act' => 'view'],
    'quotations/compare.php' => ['mod' => 'quotations', 'act' => 'view'],
    'quotations/create.php' => ['mod' => 'quotations', 'act' => 'create'],
    'quotations/create_revision.php' => ['mod' => 'quotations', 'act' => 'create'],
    'quotations/edit.php' => ['mod' => 'quotations', 'act' => 'edit'],
    'quotations/delete.php' => ['mod' => 'quotations', 'act' => 'delete'],
    'orders/index.php' => ['mod' => 'orders', 'act' => 'view'],
    'orders/view.php' => ['mod' => 'orders', 'act' => 'view'],
    'orders/create.php' => ['mod' => 'orders', 'act' => 'create'],
    'orders/edit.php' => ['mod' => 'orders', 'act' => 'edit'],
    'orders/delete.php' => ['mod' => 'orders', 'act' => 'delete'],
    'orders/generate_invoice.php' => ['mod' => 'invoices', 'act' => 'create'],
];

$base = 'c:\xampp\htdocs\SushobhaCRM\modules\\';

foreach ($rules as $file => $perms) {
    $path = $base . str_replace('/', '\\', $file);
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Check if already injected
        if (strpos($content, 'requirePermission(') !== false) continue;
        
        // Find requireLogin();
        $target = "requireLogin();";
        $replace = "requireLogin();\nrequirePermission('{$perms['mod']}', '{$perms['act']}');";
        
        if (strpos($content, $target) !== false) {
            $newContent = preg_replace('/requireLogin\(\);\s*/', $replace . "\n", $content, 1);
            file_put_contents($path, $newContent);
            echo "Updated: $file\n";
        } else {
            echo "Skipped (no requireLogin): $file\n";
        }
    } else {
        echo "Not found: $file\n";
    }
}
