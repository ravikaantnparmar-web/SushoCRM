<?php
$lines = file('c:/xampp/htdocs/SushobhaCRM/backups/db_backup_2026-05-19_182134.sql');
$start = 1345;
$end = 1385;
for ($i = $start; $i <= $end; $i++) {
    if (isset($lines[$i])) {
        echo $lines[$i];
    }
}
