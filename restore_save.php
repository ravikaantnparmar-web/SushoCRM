<?php
$file = 'modules/prospects/save.php';
$content = file_get_contents($file);

$badBlock = <<<PHP
                foreach (\$files['name'][\$idx]['card_file'] as \$fIdx => \$fName) {
                    if (\$files['error'][\$idx]['card_file'][\$fIdx] == UPLOAD_ERR_OK) {
                        \$fd = [
                            'name'     => \$files['name'][\$idx]['card_file'][\$fIdx],
                            'type'     => \$files['type'][\$idx]['card_file'][\$fIdx],
                            'tmp_name' => \$files['tmp_name'][\$idx]['card_file'][\$fIdx],
                            'error'    => \$files['error'][\$idx]['card_file'][\$fIdx],
                            'size'     => \$files['size'][\$idx]['card_file'][\$fIdx],
                        ];
                        \$path = uploadFile(\$fd, 'leads/cards');
                        if (\$path) \$cardPaths[] = \$path;
                    }
                }
            }
            \$stmtContact->execute([
                \$leadId,
                \$c['type']        ?? 'Owner',
                \$c['name'],
                \$c['designation'] ?: null,
                \$c['mobile']      ?: null,
                \$c['alt_mobile']  ?: null,
                \$c['whatsapp']    ?: null,
                \$c['email']       ?: null,
                !empty(\$cardPaths) ? json_encode(\$cardPaths) : null,
                !empty(\$c['is_primary']) ? 1 : 0,
                \$c['organization_name'] ?? null,
                \$c['address'] ?? null,
                \$c['city'] ?? null,
                \$c['state'] ?? null,
                \$c['pincode'] ?? null,
                \$c['website'] ?? null,
            ]);
        }
    }
PHP;

$goodBlock = <<<PHP
        \$createdBy,
    ]);
    \$leadId = db()->lastInsertId();

    // ── 2. Insert Contacts ────────────────────────────────────
    if (!empty(\$_POST['contacts'])) {
        \$stmtContact = db()->prepare(
            "INSERT INTO lead_contacts (lead_id, contact_type, name, designation, mobile, alt_mobile, whatsapp, email, visiting_card, is_primary, organization_name, address, city, state, pincode, website)
             VALUES (?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?)"
        );
        foreach (\$_POST['contacts'] as \$idx => \$c) {
            if (empty(\$c['name'])) continue;
            \$cardPaths = [];
            if (!empty(\$_FILES['contacts']['name'][\$idx]['card_file'][0])) {
                \$files = \$_FILES['contacts'];
                foreach (\$files['name'][\$idx]['card_file'] as \$fIdx => \$fName) {
                    if (\$files['error'][\$idx]['card_file'][\$fIdx] == UPLOAD_ERR_OK) {
                        \$fd = [
                            'name'     => \$files['name'][\$idx]['card_file'][\$fIdx],
                            'type'     => \$files['type'][\$idx]['card_file'][\$fIdx],
                            'tmp_name' => \$files['tmp_name'][\$idx]['card_file'][\$fIdx],
                            'error'    => \$files['error'][\$idx]['card_file'][\$fIdx],
                            'size'     => \$files['size'][\$idx]['card_file'][\$fIdx],
                        ];
                        \$path = uploadFile(\$fd, 'leads/cards');
                        if (\$path) \$cardPaths[] = \$path;
                    }
                }
            }
            \$stmtContact->execute([
                \$leadId,
                \$c['type']        ?? 'Owner',
                \$c['name'],
                \$c['designation'] ?: null,
                \$c['mobile']      ?: null,
                \$c['alt_mobile']  ?: null,
                \$c['whatsapp']    ?: null,
                \$c['email']       ?: null,
                !empty(\$cardPaths) ? json_encode(\$cardPaths) : null,
                !empty(\$c['is_primary']) ? 1 : 0,
                \$c['organization_name'] ?? null,
                \$c['address'] ?? null,
                \$c['city'] ?? null,
                \$c['state'] ?? null,
                \$c['pincode'] ?? null,
                \$c['website'] ?? null,
            ]);
        }
    }
PHP;

$content = str_replace($badBlock, $goodBlock, $content);
file_put_contents($file, $content);

// Also fix update.php 
$fileUpdate = 'modules/prospects/update.php';
$contentUpdate = file_get_contents($fileUpdate);

$updateBadBlock = <<<PHP
    // ── 2. Refresh Contacts ───────────────────────────────────
    db()->prepare("DELETE FROM lead_contacts WHERE lead_id = ?")->execute([\$id]);
    if (!empty(\$_POST['contacts'])) {
        \$stmtContact = db()->prepare(
            "INSERT INTO lead_contacts (lead_id, contact_type, name, designation, mobile, alt_mobile, whatsapp, email, visiting_card, is_primary)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        foreach (\$_POST['contacts'] as \$idx => \$c) {
            if (empty(\$c['name'])) continue;
            // Preserve existing cards + add new ones
            \$cardPaths = !empty(\$c['existing_card'])
                ? (json_decode(\$c['existing_card'], true) ?: [\$c['existing_card']])
                : [];
            if (!empty(\$_FILES['contacts']['name'][\$idx]['card_file'][0])) {
                \$files = \$_FILES['contacts'];
                foreach (\$files['name'][\$idx]['card_file'] as \$fIdx => \$fName) {
                    if (\$files['error'][\$idx]['card_file'][\$fIdx] == UPLOAD_ERR_OK) {
                        \$fd = [
                            'name' => \$fName, 'type' => \$files['type'][\$idx]['card_file'][\$fIdx],
                            'tmp_name' => \$files['tmp_name'][\$idx]['card_file'][\$fIdx],
                            'error' => \$files['error'][\$idx]['card_file'][\$fIdx],
                            'size' => \$files['size'][\$idx]['card_file'][\$fIdx],
                        ];
                        \$path = uploadFile(\$fd, 'leads/cards');
                        if (\$path) \$cardPaths[] = \$path;
                    }
                }
            }
            \$stmtContact->execute([
                \$id, \$c['type'] ?? 'Owner', \$c['name'], \$c['designation'] ?: null,
                \$c['mobile'] ?: null, \$c['alt_mobile'] ?: null,
                \$c['whatsapp'] ?: null, \$c['email'] ?: null,
                !empty(\$cardPaths) ? json_encode(\$cardPaths) : null,
                !empty(\$c['is_primary']) ? 1 : 0,
            ]);
        }
    }
PHP;

$updateGoodBlock = <<<PHP
    // ── 2. Refresh Contacts ───────────────────────────────────
    db()->prepare("DELETE FROM lead_contacts WHERE lead_id = ?")->execute([\$id]);
    if (!empty(\$_POST['contacts'])) {
        \$stmtContact = db()->prepare(
            "INSERT INTO lead_contacts (lead_id, contact_type, name, designation, mobile, alt_mobile, whatsapp, email, visiting_card, is_primary, organization_name, address, city, state, pincode, website)
             VALUES (?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?)"
        );
        foreach (\$_POST['contacts'] as \$idx => \$c) {
            if (empty(\$c['name'])) continue;
            // Preserve existing cards + add new ones
            \$cardPaths = !empty(\$c['existing_card'])
                ? (json_decode(\$c['existing_card'], true) ?: [\$c['existing_card']])
                : [];
            if (!empty(\$_FILES['contacts']['name'][\$idx]['card_file'][0])) {
                \$files = \$_FILES['contacts'];
                foreach (\$files['name'][\$idx]['card_file'] as \$fIdx => \$fName) {
                    if (\$files['error'][\$idx]['card_file'][\$fIdx] == UPLOAD_ERR_OK) {
                        \$fd = [
                            'name' => \$fName, 'type' => \$files['type'][\$idx]['card_file'][\$fIdx],
                            'tmp_name' => \$files['tmp_name'][\$idx]['card_file'][\$fIdx],
                            'error' => \$files['error'][\$idx]['card_file'][\$fIdx],
                            'size' => \$files['size'][\$idx]['card_file'][\$fIdx],
                        ];
                        \$path = uploadFile(\$fd, 'leads/cards');
                        if (\$path) \$cardPaths[] = \$path;
                    }
                }
            }
            \$stmtContact->execute([
                \$id, \$c['type'] ?? 'Owner', \$c['name'], \$c['designation'] ?: null,
                \$c['mobile'] ?: null, \$c['alt_mobile'] ?: null,
                \$c['whatsapp'] ?: null, \$c['email'] ?: null,
                !empty(\$cardPaths) ? json_encode(\$cardPaths) : null,
                !empty(\$c['is_primary']) ? 1 : 0,
                \$c['organization_name'] ?? null,
                \$c['address'] ?? null,
                \$c['city'] ?? null,
                \$c['state'] ?? null,
                \$c['pincode'] ?? null,
                \$c['website'] ?? null,
            ]);
        }
    }
PHP;

$contentUpdate = str_replace($updateBadBlock, $updateGoodBlock, $contentUpdate);
file_put_contents($fileUpdate, $contentUpdate);

echo "Fixed save.php and update.php\n";
