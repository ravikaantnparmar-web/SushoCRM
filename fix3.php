<?php
$files = [
    'modules/orders/create.php', 'modules/orders/edit.php',
    'modules/invoices/create.php', 'modules/invoices/edit.php'
];

foreach ($files as $f) {
    if (!file_exists($f)) continue;
    
    $content = file_get_contents($f);
    
    // Replace the first loop
    // Notice we use \s+ to handle varying whitespace
    $pattern1 = '/\$subtotal\s*=\s*0;\s*\$tax_amount\s*=\s*0;\s*foreach \(\$items as \$item\) \{(.*?)\$line_total\s*=\s*\$qty \* \$price;\s*\$line_tax\s*=\s*\$line_total \* \(\$tax_rate \/ 100\);\s*\$subtotal\s*\+=\s*\$line_total;\s*\$tax_amount\s*\+=\s*\$line_tax;\s*\}/s';
    
    $replacement1 = '$subtotal = 0;
            
            foreach ($items as $item) {
                $qty = (float)($item[\'qty\'] ?? 1);
                $price = (float)($item[\'unit_price\'] ?? 0);
                $subtotal += ($qty * $price);
            }
            
            $discount_amount = $discount_type === \'percent\' ? ($subtotal * ($discount_value / 100)) : $discount_value;
            $discount_ratio = $subtotal > 0 ? ($discount_amount / $subtotal) : 0;
            
            $tax_amount = 0;
            foreach ($items as $item) {
                $qty = (float)($item[\'qty\'] ?? 1);
                $price = (float)($item[\'unit_price\'] ?? 0);
                $tax_rate = (float)($item[\'tax_rate\'] ?? 0);
                
                $line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);
                
                $tax_amount += $line_tax;
            }';
            
    // The discount_amount line
    $pattern_disc = '/\s*\$discount_amount = \$discount_type === \'percent\' \? \(\$subtotal \* \(\$discount_value \/ 100\)\) : \$discount_value;/s';
    
    // Replace the second loop
    $pattern2 = '/\$line_total\s*=\s*\$qty \* \$price;\s*\$line_tax\s*=\s*\$line_total \* \(\$tax_rate \/ 100\);/s';
    
    $replacement2 = '$line_total = $qty * $price;
                $discounted_line_total = $line_total * (1 - $discount_ratio);
                $line_tax = $discounted_line_total * ($tax_rate / 100);';
                
    $new_content = preg_replace($pattern1, $replacement1, $content);
    if ($new_content !== null && $new_content !== $content) {
        $new_content = preg_replace($pattern_disc, '', $new_content);
        $new_content = preg_replace($pattern2, $replacement2, $new_content);
        file_put_contents($f, $new_content);
        echo "Successfully patched $f\n";
    } else {
        echo "Failed to match $f\n";
    }
}
?>
