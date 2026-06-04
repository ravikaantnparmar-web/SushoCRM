<?php
require 'config/db.php';

try {
    // 1. Create `contacts` table
    db()->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contact_type VARCHAR(100) DEFAULT 'Other',
        name VARCHAR(255) NOT NULL,
        organization_name VARCHAR(255) NULL,
        designation VARCHAR(100) NULL,
        mobile VARCHAR(20) NULL,
        whatsapp VARCHAR(20) NULL,
        email VARCHAR(255) NULL,
        address TEXT NULL,
        city VARCHAR(100) NULL,
        state VARCHAR(100) NULL,
        pincode VARCHAR(20) NULL,
        website VARCHAR(255) NULL,
        gst_number VARCHAR(50) NULL,
        visiting_card TEXT NULL,
        social_links TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY idx_contact_mobile_name (mobile, name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Create `contact_relations` table
    db()->exec("CREATE TABLE IF NOT EXISTS contact_relations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contact_id INT UNSIGNED NOT NULL,
        entity_type VARCHAR(50) NOT NULL,
        entity_id INT UNSIGNED NOT NULL,
        role VARCHAR(100) NULL,
        is_primary TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
        UNIQUE KEY idx_relation_unique (contact_id, entity_type, entity_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    db()->beginTransaction();

    $leadsSt = db()->query("SELECT * FROM lead_contacts");
    $leadContacts = $leadsSt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($leadContacts as $lc) {
        if (empty(trim($lc['name']))) continue;

        // Try to find existing contact in the new table by mobile or email
        $existing = false;
        if (!empty($lc['mobile'])) {
            $check = db()->prepare("SELECT id FROM contacts WHERE mobile = ? LIMIT 1");
            $check->execute([$lc['mobile']]);
            $existing = $check->fetchColumn();
        }
        
        if (!$existing && !empty($lc['email'])) {
            $check = db()->prepare("SELECT id FROM contacts WHERE email = ? LIMIT 1");
            $check->execute([$lc['email']]);
            $existing = $check->fetchColumn();
        }
        
        if (!$existing) {
            $check = db()->prepare("SELECT id FROM contacts WHERE name = ? LIMIT 1");
            $check->execute([$lc['name']]);
            $existing = $check->fetchColumn();
        }

        if ($existing) {
            $contactId = $existing;
        } else {
            $ins = db()->prepare("INSERT INTO contacts (contact_type, name, designation, mobile, whatsapp, email, visiting_card, organization_name, address, city, state, pincode, website) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $ins->execute([
                $lc['contact_type'] ?: 'Owner',
                $lc['name'],
                $lc['designation'] ?? null,
                $lc['mobile'] ?? null,
                $lc['whatsapp'] ?? null,
                $lc['email'] ?? null,
                $lc['visiting_card'] ?? null,
                $lc['organization_name'] ?? null,
                $lc['address'] ?? null,
                $lc['city'] ?? null,
                $lc['state'] ?? null,
                $lc['pincode'] ?? null,
                $lc['website'] ?? null
            ]);
            $contactId = db()->lastInsertId();
        }

        // Link to lead
        $link = db()->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'lead', ?, ?, ?)");
        $link->execute([
            $contactId,
            $lc['lead_id'],
            $lc['contact_type'] ?: 'Owner',
            $lc['is_primary'] ?? 0
        ]);
    }

    $tableExists = db()->query("SHOW TABLES LIKE 'customer_contacts'")->rowCount() > 0;
    if ($tableExists) {
        $custSt = db()->query("SELECT * FROM customer_contacts");
        $custContacts = $custSt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($custContacts as $cc) {
            if (empty(trim($cc['name']))) continue;

            $existing = false;
            if (!empty($cc['mobile'])) {
                $check = db()->prepare("SELECT id FROM contacts WHERE mobile = ? LIMIT 1");
                $check->execute([$cc['mobile']]);
                $existing = $check->fetchColumn();
            }
            if (!$existing && !empty($cc['email'])) {
                $check = db()->prepare("SELECT id FROM contacts WHERE email = ? LIMIT 1");
                $check->execute([$cc['email']]);
                $existing = $check->fetchColumn();
            }
            if (!$existing) {
                $check = db()->prepare("SELECT id FROM contacts WHERE name = ? LIMIT 1");
                $check->execute([$cc['name']]);
                $existing = $check->fetchColumn();
            }

            if ($existing) {
                $contactId = $existing;
            } else {
                $ins = db()->prepare("INSERT INTO contacts (contact_type, name, designation, mobile, whatsapp, email, visiting_card, organization_name, address, city, state, pincode, website) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $ins->execute([
                    $cc['contact_type'] ?? 'Owner',
                    $cc['name'],
                    $cc['designation'] ?? null,
                    $cc['mobile'] ?? null,
                    $cc['whatsapp'] ?? null,
                    $cc['email'] ?? null,
                    $cc['visiting_card'] ?? null,
                    $cc['organization_name'] ?? null,
                    $cc['address'] ?? null,
                    $cc['city'] ?? null,
                    $cc['state'] ?? null,
                    $cc['pincode'] ?? null,
                    $cc['website'] ?? null
                ]);
                $contactId = db()->lastInsertId();
            }

            $link = db()->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'customer', ?, ?, ?)");
            $link->execute([
                $contactId,
                $cc['customer_id'],
                $cc['contact_type'] ?? 'Owner',
                $cc['is_primary'] ?? 0
            ]);
        }
    }

    db()->commit();
    echo "Migration completed successfully!";
} catch (Exception $e) {
    if (db()->inTransaction()) {
        db()->rollBack();
    }
    echo "Migration failed: " . $e->getMessage();
}
