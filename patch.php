<?php
$files = [
    'quotations/create.php', 'quotations/edit.php',
    'invoices/create.php', 'invoices/edit.php',
    'orders/create.php', 'orders/edit.php',
    'purchases/create.php', 'purchases/edit.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/modules/' . $file;
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);
    
    // 1. Replace PHP logic
    // Pattern to find the PHP block where it calculates total
    $phpPattern = '/\s*\$subtotal = 0;\s*\$total_tax = 0;\s*foreach\s*\(\$items as \$it\)\s*\{\s*\$lt = \$it\[\'qty\'\] \* \$it\[\'unit_price\'\];\s*\$subtotal \+= \$lt;\s*\$total_tax \+= \$lt \* \(\$it\[\'tax_rate\'\]\s*\/\s*100\);\s*\}\s*\$discount_amount = \(\$discount_type === \'percent\'\) \? \(\$subtotal \* \$discount_value \/ 100\) : \$discount_value;\s*\$total = \(\$subtotal - \$discount_amount\) \+ \$total_tax;/s';
    
    $phpReplacement = '
    $subtotal = 0;
    foreach($items as $it) {
        $subtotal += $it[\'qty\'] * $it[\'unit_price\'];
    }
    
    $discount_type = \'percent\'; // enforce percent
    $discount_amount = $subtotal * ($discount_value / 100);
    $discount_ratio = 1 - ($discount_value / 100);
    
    $total_tax = 0;
    foreach($items as $it) {
        $lt = $it[\'qty\'] * $it[\'unit_price\'];
        $discounted_lt = $lt * $discount_ratio;
        $total_tax += $discounted_lt * ($it[\'tax_rate\'] / 100);
    }
    
    $total = ($subtotal - $discount_amount) + $total_tax;';

    $content = preg_replace($phpPattern, $phpReplacement, $content);
    
    // 2. Replace HTML Discount Block
    $htmlPattern = '/<div class="d-flex gap-1" style="width:140px">\s*<input type="number" name="discount_value" id="discountValue" class="form-control form-control-sm text-end" value="([^"]+)" step="0.01" min="0" oninput="calculateTotal\(\)">\s*<select name="discount_type"[^>]+>.*?<\/select>\s*<\/div>/s';
    
    $htmlReplacement = '<div class="input-group input-group-sm" style="width:140px">
              <input type="number" name="discount_value" id="discountValue" class="form-control text-end" value="$1" step="0.01" min="0" oninput="calculateTotal()">
              <span class="input-group-text">%</span>
              <input type="hidden" name="discount_type" value="percent">
            </div>';
            
    $content = preg_replace($htmlPattern, $htmlReplacement, $content);
    
    // 3. Replace HTML Discount Name
    $content = str_replace('<span>Discount</span>', '<span>Discount (%)</span>', $content);
    
    // 4. Insert Discount Amount line in Summary
    $summaryTarget = '<div class="line-total-row-item">
          <span>Tax</span>';
    $summaryReplacement = '<div class="line-total-row-item text-danger">
          <span>Discount Amt</span>
          <span id="calcDiscount">-? 0.00</span>
        </div>
        <div class="line-total-row-item">
          <span>Tax</span>';
    // If it doesn't already have calcDiscount
    if (strpos($content, 'id="calcDiscount"') === false) {
        $content = str_replace($summaryTarget, $summaryReplacement, $content);
        // Also handle the invoices one which has indentation differences
        $summaryTarget2 = '<div class="line-total-row-item">
            <span>Tax</span>';
        $summaryReplacement2 = '<div class="line-total-row-item text-danger">
            <span>Discount Amt</span>
            <span id="calcDiscount">-? 0.00</span>
          </div>
          <div class="line-total-row-item">
            <span>Tax</span>';
        $content = str_replace($summaryTarget2, $summaryReplacement2, $content);
    }
    
    // 5. Replace calculateTotal() JS function
    $jsPattern = '/function calculateTotal\(\) \{.*?\}/s';
    $jsReplacement = 'function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll(\'#itemsTable tbody tr\').forEach(tr => {
        const qty = parseFloat(tr.querySelector(\'.item-qty\').value) || 0;
        const price = parseFloat(tr.querySelector(\'.item-price\').value) || 0;
        const lineTotal = qty * price;
        
        tr.querySelector(\'.item-total\').value = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    
    const discVal = parseFloat(document.getElementById(\'discountValue\').value) || 0;
    const discAmount = subtotal * (discVal / 100);
    const discountedSubtotal = subtotal - discAmount;
    
    let totalTax = 0;
    document.querySelectorAll(\'#itemsTable tbody tr\').forEach(tr => {
        const qty = parseFloat(tr.querySelector(\'.item-qty\').value) || 0;
        const price = parseFloat(tr.querySelector(\'.item-price\').value) || 0;
        const taxRate = parseFloat(tr.querySelector(\'.item-tax\').value) || 0;
        
        const lineTotal = qty * price;
        const discountedLineTotal = lineTotal * (1 - (discVal / 100));
        const lineTax = discountedLineTotal * (taxRate / 100);
        
        totalTax += lineTax;
    });
    
    const grandTotal = discountedSubtotal + totalTax;
    
    document.getElementById(\'calcSubtotal\').textContent = \'? \' + subtotal.toFixed(2);
    if(document.getElementById(\'calcDiscount\')) {
        document.getElementById(\'calcDiscount\').textContent = \'-? \' + discAmount.toFixed(2);
    }
    document.getElementById(\'calcTax\').textContent = \'? \' + totalTax.toFixed(2);
    document.getElementById(\'calcTotal\').textContent = \'? \' + grandTotal.toFixed(2);
    
    if(document.getElementById(\'calcBalance\')) {
        const amountPaid = parseFloat(document.getElementById(\'amountPaid\').value) || 0;
        const balance = grandTotal - amountPaid;
        document.getElementById(\'calcBalance\').textContent = \'? \' + balance.toFixed(2);
    }
}';
    
    $content = preg_replace($jsPattern, $jsReplacement, $content);
    
    file_put_contents($path, $content);
    echo "Updated $file\n";
}
?>
