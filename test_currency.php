<?php
require 'c:\xampp\htdocs\SushobhaCRM\config\config.php';
require 'c:\xampp\htdocs\SushobhaCRM\includes\functions.php';

echo "CURRENCY_SYMBOL is: " . CURRENCY_SYMBOL . "\n";
echo "Hex: " . bin2hex(CURRENCY_SYMBOL) . "\n";
echo "formatCurrency(500000) = " . formatCurrency(500000) . "\n";
echo "Line 217 equivalent = " . '₹'.formatCurrency(500000) . "\n";
