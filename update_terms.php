<?php
require 'config/db.php';
$db = db();
$db->exec("UPDATE invoices SET terms = '1. Payment is due within 7 days.\n2. Please include invoice number on your check.' WHERE terms IS NULL OR terms = ''");
$db->exec("UPDATE quotations SET terms = '1. Quotation valid for 30 days.\n2. Payment: 100% advance.' WHERE terms IS NULL OR terms = ''");
$db->exec("UPDATE orders SET terms = '1. Delivery subject to stock availability.\n2. Payment: 100% advance.' WHERE terms IS NULL OR terms = ''");
echo "Updated terms in DB.\n";
