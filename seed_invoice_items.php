<?php
require 'c:/xampp/htdocs/SushobhaCRM/config/db.php';

$pdo = db();

echo "--- Seeding invoice_items for existing invoices ---\n\n";

// Seed line items matching the known subtotals from the invoices
$invoiceItems = [
    // INV0001 - Rahul Joshi - subtotal=115000, tax=20700, total=135700
    1 => [
        ['desc' => 'Interior Design Consultation', 'qty' => 5,  'unit' => 'Days', 'price' => 8000,  'tax' => 18],
        ['desc' => 'Custom Furniture Design',      'qty' => 1,  'unit' => 'Set',  'price' => 35000, 'tax' => 18],
        ['desc' => 'Material Procurement',         'qty' => 1,  'unit' => 'Nos',  'price' => 40000, 'tax' => 18],
    ],
    // INV0002 - Arjun Mehta / TechVision - subtotal=128250, tax=23085, total=151335
    2 => [
        ['desc' => 'Office Space Planning',        'qty' => 3,  'unit' => 'Days', 'price' => 12000, 'tax' => 18],
        ['desc' => 'Premium Office Furniture',     'qty' => 1,  'unit' => 'Set',  'price' => 65000, 'tax' => 18],
        ['desc' => 'Lighting & Electrical Work',   'qty' => 1,  'unit' => 'Job',  'price' => 27250, 'tax' => 18],
    ],
    // INV0003 - Suresh Iyer / BrightFuture - subtotal=35000, tax=6300, total=41300
    3 => [
        ['desc' => 'Classroom Furniture',          'qty' => 10, 'unit' => 'Sets', 'price' => 2500,  'tax' => 18],
        ['desc' => 'Whiteboard & Display Setup',   'qty' => 2,  'unit' => 'Nos',  'price' => 7500,  'tax' => 18],
    ],
    // INV0004 - Arun Kumar / Delta Constructions - subtotal=58000, tax=10440, total=68440
    4 => [
        ['desc' => 'Site Inspection & Consulting', 'qty' => 4,  'unit' => 'Days', 'price' => 5000,  'tax' => 18],
        ['desc' => 'Construction Planning & Drawings', 'qty' => 1, 'unit' => 'Job', 'price' => 38000, 'tax' => 18],
    ],
    // INV0005 - Vikram Singh / Horizon - subtotal=120000, tax=21600, total=141600
    5 => [
        ['desc' => 'Residential Interior Design', 'qty' => 1,  'unit' => 'Job',  'price' => 75000, 'tax' => 18],
        ['desc' => 'Premium Décor Items',          'qty' => 1,  'unit' => 'Set',  'price' => 30000, 'tax' => 18],
        ['desc' => 'Installation & Setup',         'qty' => 1,  'unit' => 'Job',  'price' => 15000, 'tax' => 18],
    ],
];

$stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, description, qty, unit, unit_price, tax_rate, tax_amount, discount, line_total, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?)");

foreach ($invoiceItems as $invId => $items) {
    // Check if items already exist for this invoice
    $existing = $pdo->prepare("SELECT COUNT(*) FROM invoice_items WHERE invoice_id = ?");
    $existing->execute([$invId]);
    if ($existing->fetchColumn() > 0) {
        echo "INV #$invId already has items, skipping.\n";
        continue;
    }

    foreach ($items as $i => $item) {
        $lineTotal = $item['qty'] * $item['price'];
        $lineTax   = $lineTotal * ($item['tax'] / 100);
        $stmt->execute([
            $invId,
            $item['desc'],
            $item['qty'],
            $item['unit'],
            $item['price'],
            $item['tax'],
            $lineTax,
            0,
            $lineTotal,
            $i
        ]);
        echo "  ✓ INV #$invId → {$item['desc']} (qty:{$item['qty']} × ₹{$item['price']})\n";
    }
}

echo "\n--- Done! All invoice items seeded. ---\n";
echo "Total items inserted: " . $pdo->query("SELECT COUNT(*) FROM invoice_items")->fetchColumn() . "\n";
