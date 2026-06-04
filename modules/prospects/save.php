<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

try {
    db()->beginTransaction();
    $createdBy = $_SESSION['user_id'];
    $leadCode  = generateLeadCode();

    $primaryAddr = null;
    if (!empty($_POST['addresses'])) {
        foreach ($_POST['addresses'] as $a) {
            if (!empty($a['address_line1']) || !empty($a['city']) || !empty($a['google_address'])) {
                if (!empty($a['is_primary'])) {
                    $primaryAddr = $a;
                    break;
                }
            }
        }
        if (!$primaryAddr) {
            foreach ($_POST['addresses'] as $a) {
                if (!empty($a['address_line1']) || !empty($a['city']) || !empty($a['google_address'])) {
                    $primaryAddr = $a;
                    break;
                }
            }
        }
    }

    $sql = "INSERT INTO leads (
        lead_code, lead_date, lead_status, lead_priority, lead_source, lead_type,
        assigned_to, actual_followup_date, next_followup_date, expected_closing_date,
        company_name, company_type, industry_type, tin_number,
        company_email, company_website, gst_number, company_status,
        site_stage, project_type,
        google_location, google_address, google_maps_link, lat, lng,
        address_line1, address_line2, area, city, state, pincode,
        product_type, requirement_description, estimated_budget,
        purchase_timeline, competitor_info, created_by
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    db()->prepare($sql)->execute([
        $leadCode,
        $_POST['lead_date']             ?? date('Y-m-d'),
        $_POST['meeting_lead_status']   ?? ($_POST['lead_status'] ?? 'New'),
        $_POST['lead_priority']         ?? null,
        $_POST['lead_source']           ?? null,
        $_POST['lead_type']             ?? null,
        $_POST['assigned_to']           ?: null,
        $_POST['actual_followup_date']  ?: null,
        $_POST['next_followup_date']    ?: null,
        $_POST['expected_closing_date'] ?: null,
        $_POST['company_name']          ?: null,
        $_POST['company_type']          ?: null,
        $_POST['industry_type']         ?: null,
        $_POST['tin_number']            ?: null,
        $_POST['company_email']         ?: null,
        $_POST['company_website']       ?: null,
        $_POST['gst_number']            ?: null,
        $_POST['company_status']        ?? 'Active',
        $_POST['site_stage']            ?: null,
        $_POST['project_type']          ?: null,
        $primaryAddr['google_location']   ?? null ?: null,
        $primaryAddr['google_address']    ?? null ?: null,
        $primaryAddr['google_maps_link']  ?? null ?: null,
        $primaryAddr['lat']               ?? null ?: null,
        $primaryAddr['lng']               ?? null ?: null,
        $primaryAddr['address_line1']     ?? null ?: null,
        $primaryAddr['address_line2']     ?? null ?: null,
        $primaryAddr['area']              ?? null ?: null,
        $primaryAddr['city']              ?? null ?: null,
        $primaryAddr['state']             ?? null ?: null,
        $primaryAddr['pincode']           ?? null ?: null,
        $_POST['product_type']          ?: null,
        $_POST['requirement_description'] ?: null,
        (float)($_POST['estimated_budget'] ?? 0),
        $_POST['purchase_timeline']     ?: null,
        $_POST['competitor_info']       ?: null,
        $createdBy
    ]);
    
    $leadId = db()->lastInsertId();

    if (!empty($_POST['contacts'])) {
        $stmtContact = db()->prepare("INSERT INTO contacts (contact_type, name, mobile, whatsapp, email, visiting_card, organization_name, address, city, state, pincode, website) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmtRelation = db()->prepare("INSERT IGNORE INTO contact_relations (contact_id, entity_type, entity_id, role, is_primary) VALUES (?, 'lead', ?, ?, ?)");
        
        foreach ($_POST['contacts'] as $idx => $c) {
            if (empty($c['name'])) continue;

            $cardPaths = [];
            if (isset($_FILES['contacts']) && !empty($_FILES['contacts']['name'][$idx]['card_file'][0])) {
                $files = $_FILES['contacts'];
                foreach ($files['name'][$idx]['card_file'] as $fIdx => $fName) {
                    if ($files['error'][$idx]['card_file'][$fIdx] == UPLOAD_ERR_OK) {
                        $fd = [
                            'name'     => $files['name'][$idx]['card_file'][$fIdx],
                            'type'     => $files['type'][$idx]['card_file'][$fIdx],
                            'tmp_name' => $files['tmp_name'][$idx]['card_file'][$fIdx],
                            'error'    => $files['error'][$idx]['card_file'][$fIdx],
                            'size'     => $files['size'][$idx]['card_file'][$fIdx],
                        ];
                        $path = uploadFile($fd, 'leads/cards');
                        if ($path) $cardPaths[] = $path;
                    }
                }
            }
            
            if (!empty($c['master_contact_id'])) {
                $contactId = (int)$c['master_contact_id'];
            } else {
                // Duplicate check before insert
                $stmtCheck = db()->prepare("SELECT id FROM contacts WHERE (mobile != '' AND mobile = ?) OR (email != '' AND email = ?) LIMIT 1");
                $stmtCheck->execute([$c['mobile'] ?? '', $c['email'] ?? '']);
                $existing = $stmtCheck->fetchColumn();
                
                if ($existing) {
                    $contactId = $existing;
                } else {
                    $stmtContact->execute([
                        $c['type']        ?? 'Owner',
                        $c['name'],

                        $c['mobile']      ?: null,
                        $c['whatsapp']    ?: null,
                        $c['email']       ?: null,
                        !empty($cardPaths) ? json_encode($cardPaths) : null,
                        $c['organization_name'] ?? null,
                        $c['address'] ?? null,
                        $c['city'] ?? null,
                        $c['state'] ?? null,
                        $c['pincode'] ?? null,
                        $c['website'] ?? null
                    ]);
                    $contactId = db()->lastInsertId();
                }
            }
            $stmtRelation->execute([
                $contactId,
                $leadId,
                $c['type'] ?? 'Owner',
                !empty($c['is_primary']) ? 1 : 0
            ]);
        }
    }

    if (!empty($_POST['addresses'])) {
        $stmtAddr = db()->prepare(
            "INSERT INTO lead_addresses (lead_id, address_type, address_line1, address_line2, area, city, state, pincode, lat, lng, google_location, google_address, google_maps_link, is_primary)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        foreach ($_POST['addresses'] as $a) {
            if (empty($a['address_line1']) && empty($a['city']) && empty($a['google_address'])) continue;
            
            $stmtAddr->execute([
                $leadId,
                $a['address_type']    ?: null,
                $a['address_line1']   ?: null,
                $a['address_line2']   ?: null,
                $a['area']            ?: null,
                $a['city']            ?: null,
                $a['state']           ?: null,
                $a['pincode']         ?: null,
                $a['lat']             ?: null,
                $a['lng']             ?: null,
                $a['google_location'] ?: null,
                $a['google_address']  ?: null,
                $a['google_maps_link']?: null,
                !empty($a['is_primary']) ? 1 : 0,
            ]);
        }
    }

    if (!empty($_POST['interested_products'])) {
        $stmtProd = db()->prepare("INSERT INTO lead_interested_products (lead_id, product_name) VALUES (?, ?)");
        foreach ($_POST['interested_products'] as $prod) {
            if (trim($prod) !== '') {
                $stmtProd->execute([$leadId, trim($prod)]);
            }
        }
    }

    db()->prepare("INSERT INTO lead_timeline (lead_id, action_type, description, user_id) VALUES (?, ?, ?, ?)")
        ->execute([$leadId, 'Created', 'Lead created successfully', $createdBy]);

    db()->commit();
    setFlash('success', 'Lead created successfully.');
    header("Location: view.php?id=$leadId");
} catch (Exception $e) {
    if (db()->inTransaction()) db()->rollBack();
    setFlash('danger', 'Failed to create lead: ' . $e->getMessage());
    header('Location: create.php');
}
exit;
