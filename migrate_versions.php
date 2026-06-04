<?php
require 'config/config.php';
require 'config/db.php';

try {
    // 1. Drop existing unique index on quote_number (if exists)
    // First, check if index exists
    $idx = db()->query('SHOW INDEX FROM quotations WHERE Key_name = "quote_number"')->fetch();
    if ($idx) {
        db()->exec('ALTER TABLE quotations DROP INDEX quote_number');
        echo "Dropped index quote_number\n";
    }

    // 2. Add columns
    // Check if columns exist
    $cols = db()->query('DESCRIBE quotations')->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('version', $cols)) {
        db()->exec('ALTER TABLE quotations ADD COLUMN version INT DEFAULT 1 AFTER quote_number');
        echo "Added column version\n";
    }
    if (!in_array('is_latest', $cols)) {
        db()->exec('ALTER TABLE quotations ADD COLUMN is_latest TINYINT(1) DEFAULT 1 AFTER version');
        echo "Added column is_latest\n";
    }
    if (!in_array('revision_notes', $cols)) {
        db()->exec('ALTER TABLE quotations ADD COLUMN revision_notes TEXT NULL AFTER notes');
        echo "Added column revision_notes\n";
    }

    // 3. Add new composite unique index
    $idx_version = db()->query('SHOW INDEX FROM quotations WHERE Key_name = "idx_quote_version"')->fetch();
    if (!$idx_version) {
        db()->exec('ALTER TABLE quotations ADD UNIQUE INDEX idx_quote_version (quote_number, version)');
        echo "Added unique index idx_quote_version\n";
    }

    echo "Database migration complete.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
