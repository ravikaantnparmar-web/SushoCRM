<?php
// OPcache reset - run this once via browser then delete this file
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache cleared successfully. <a href='/SushobhaCRM/modules/dashboard/index.php'>Go to Dashboard</a>";
} else {
    echo "⚠️ OPcache not enabled or not available.";
}
// Self-delete after run
// unlink(__FILE__);
