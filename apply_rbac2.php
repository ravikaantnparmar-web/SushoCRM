<?php
$rules = [
    // Customers
    'customers/index.php' => ['mod' => 'customers', 'act' => 'view'],
    'customers/view.php' => ['mod' => 'customers', 'act' => 'view'],
    'customers/create.php' => ['mod' => 'customers', 'act' => 'create'],
    'customers/edit.php' => ['mod' => 'customers', 'act' => 'edit'],
    'customers/delete.php' => ['mod' => 'customers', 'act' => 'delete'],
    
    // Prospects
    'prospects/index.php' => ['mod' => 'prospects', 'act' => 'view'],
    'prospects/view.php' => ['mod' => 'prospects', 'act' => 'view'],
    'prospects/create.php' => ['mod' => 'prospects', 'act' => 'create'],
    'prospects/edit.php' => ['mod' => 'prospects', 'act' => 'edit'],
    'prospects/update.php' => ['mod' => 'prospects', 'act' => 'edit'],
    'prospects/delete.php' => ['mod' => 'prospects', 'act' => 'delete'],
    
    // Invoices
    'invoices/index.php' => ['mod' => 'invoices', 'act' => 'view'],
    'invoices/view.php' => ['mod' => 'invoices', 'act' => 'view'],
    'invoices/create.php' => ['mod' => 'invoices', 'act' => 'create'],
    'invoices/edit.php' => ['mod' => 'invoices', 'act' => 'edit'],
    'invoices/delete.php' => ['mod' => 'invoices', 'act' => 'delete'],

    // Expenses
    'expenses/index.php' => ['mod' => 'expenses', 'act' => 'view'],
    'expenses/view.php' => ['mod' => 'expenses', 'act' => 'view'],
    'expenses/create.php' => ['mod' => 'expenses', 'act' => 'create'],
    'expenses/edit.php' => ['mod' => 'expenses', 'act' => 'edit'],
    'expenses/delete.php' => ['mod' => 'expenses', 'act' => 'delete'],

    // Products
    'products/index.php' => ['mod' => 'products', 'act' => 'view'],
    'products/create.php' => ['mod' => 'products', 'act' => 'create'],
    'products/edit.php' => ['mod' => 'products', 'act' => 'edit'],
    'products/delete.php' => ['mod' => 'products', 'act' => 'delete'],

    // Vendors
    'vendors/index.php' => ['mod' => 'vendors', 'act' => 'view'],
    'vendors/create.php' => ['mod' => 'vendors', 'act' => 'create'],
    'vendors/edit.php' => ['mod' => 'vendors', 'act' => 'edit'],
    'vendors/delete.php' => ['mod' => 'vendors', 'act' => 'delete'],
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
