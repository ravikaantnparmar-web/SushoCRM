<?php
$files = [
    'quotations/create.php', 'quotations/edit.php',
    'invoices/create.php', 'invoices/edit.php',
    'orders/create.php', 'orders/edit.php',
    'purchases/create.php', 'purchases/edit.php'
];

$correctJs = <<<EOD
function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        const price = parseFloat(tr.querySelector('.item-price').value) || 0;
        const lineTotal = qty * price;
        
        tr.querySelector('.item-total').value = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    
    const discVal = parseFloat(document.getElementById('discountValue').value) || 0;
    const discAmount = subtotal * (discVal / 100);
    const discountedSubtotal = subtotal - discAmount;
    
    let totalTax = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        const price = parseFloat(tr.querySelector('.item-price').value) || 0;
        const taxRate = parseFloat(tr.querySelector('.item-tax').value) || 0;
        
        const lineTotal = qty * price;
        const discountedLineTotal = lineTotal * (1 - (discVal / 100));
        const lineTax = discountedLineTotal * (taxRate / 100);
        
        totalTax += lineTax;
    });
    
    const grandTotal = discountedSubtotal + totalTax;
    
    document.getElementById('calcSubtotal').textContent = '₹ ' + subtotal.toFixed(2);
    if(document.getElementById('calcDiscount')) {
        document.getElementById('calcDiscount').textContent = '-₹ ' + discAmount.toFixed(2);
    }
    document.getElementById('calcTax').textContent = '₹ ' + totalTax.toFixed(2);
    document.getElementById('calcTotal').textContent = '₹ ' + grandTotal.toFixed(2);
    
    if(document.getElementById('calcBalance')) {
        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        const balance = grandTotal - amountPaid;
        document.getElementById('calcBalance').textContent = '₹ ' + balance.toFixed(2);
    }
}
EOD;

foreach ($files as $file) {
    $path = __DIR__ . '/modules/' . $file;
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);
    
    // The broken javascript starts at 'function calculateTotal() {' and ends right before 'document.addEventListener(\'DOMContentLoaded\''
    // Wait, let's use a regex that matches from 'function calculateTotal() {' up to (but not including) '\ndocument.addEventListener(\'DOMContentLoaded\''
    // or '// Add first item automatically'
    
    $pattern = '/function calculateTotal\(\) \{.*?(?=\/\/\s*Add first item automatically|document\.addEventListener\(\'DOMContentLoaded\')/is';
    
    $content = preg_replace($pattern, $correctJs . "\n\n", $content);
    
    file_put_contents($path, $content);
    echo "Fixed $file\n";
}
?>
