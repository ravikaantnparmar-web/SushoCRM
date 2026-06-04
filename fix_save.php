<?php
$file = 'modules/prospects/save.php';
$content = file_get_contents($file);

// Find the first occurrence of "    // ── 2b. Insert Addresses"
$pos2b = strpos($content, "    // ── 2b. Insert Addresses");

if ($pos2b !== false) {
    // Keep everything before the bad insertion, which started around line 87
    // Let's just reconstruct the file cleanly.
}
