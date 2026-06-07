<?php
echo "<pre>";
echo "INCLUDED FILES:\n";
print_r(get_included_files());
echo "\n\nDEFINED CONSTANTS:\n";
print_r(get_defined_constants(true)['user']);
echo "</pre>";
