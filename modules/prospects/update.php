<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/constants.php';
requireLogin();
requirePermission('prospects', 'edit');

    // Detect post_max_size overflow (PHP clears $_POST and $_FILES if total uploaded size exceeds server limits)
    if (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        $_SESSION['flash_error'] = "File too large. The total upload size exceeds the server's limit. Please try smaller files.";
        header("Location: $referer");
        exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        $_SESSION['flash_error'] = "Invalid form submission or missing Lead ID.";
        header('Location: index.php');
        exit;
    }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit.php?id=$id"); exit;
}

try {
    db()->beginTransaction();
    $updatedBy = $_SESSION['user_id'];

    // Find primary address (or first valid address) to sync to main leads table legacy columns
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

    // ── 1. UPDATE Main Lead ───────────────────────────────────
    $sql = "UPDATE leads SET
        lead_date = ?, lead_status = ?, lead_priority = ?, lead_source = ?, lead_type = ?,
        assigned_to = ?, actual_followup_date = ?, next_followup_date = ?, expected_closing_date = ?,
        company_name = ?, company_type = ?, industry_type = ?, tin_number = ?,
        company_email = ?, company_website = ?, gst_number = ?, company_status = ?,
        site_stage = ?, project_type = ?,
        google_location = ?, google_address = ?, google_maps_link = ?, lat = ?, lng = ?,
        address_line1 = ?, address_line2 = ?, area = ?, city = ?, state = ?, pincode = ?,
        product_type = ?, requirement_description = ?, estimated_budget = ?,
        purchase_timeline = ?, competitor_info = ?, updated_by = ?
        WHERE id = ?";

    $oldLeadStmt = db()->prepare("SELECT * FROM leads WHERE id = ?");
    $oldLeadStmt->execute([$id]);
    $oldLead = $oldLeadStmt->fetch(PDO::FETCH_ASSOC);

    $newLeadData = [
        'lead_date'             => $_POST['lead_date'] ?? date('Y-m-d'),
        'lead_status'           => $_POST['meeting_lead_status'] ?? ($_POST['lead_status'] ?? 'New'),
        'lead_priority'         => $_POST['lead_priority'] ?? null,
        'lead_source'           => $_POST['lead_source'] ?: null,
        'lead_type'             => $_POST['lead_type'] ?: null,
        'assigned_to'           => $_POST['assigned_to'] ?: null,
        'actual_followup_date'  => $_POST['actual_followup_date'] ?: null,
        'next_followup_date'    => $_POST['next_followup_date'] ?: null,
        'expected_closing_date' => $_POST['expected_closing_date'] ?: null,
        'company_name'          => $_POST['company_name'] ?: null,
        'company_type'          => $_POST['company_type'] ?: null,
        'industry_type'         => $_POST['industry_type'] ?: null,
        'tin_number'            => $_POST['tin_number'] ?: null,
        'company_email'         => $_POST['company_email'] ?: null,
        'company_website'       => $_POST['company_website'] ?: null,
        'gst_number'            => $_POST['gst_number'] ?: null,
        'company_status'        => $_POST['company_status'] ?? 'Active',
        'site_stage'            => $_POST['site_stage'] ?: null,
        'project_type'          => $_POST['project_type'] ?: null,
        'google_location'       => $primaryAddr['google_location'] ?? null ?: null,
        'google_address'        => $primaryAddr['google_address'] ?? null ?: null,
        'google_maps_link'      => $primaryAddr['google_maps_link'] ?? null ?: null,
        'lat'                   => $primaryAddr['lat'] ?? null ?: null,
        'lng'                   => $primaryAddr['lng'] ?? null ?: null,
        'address_line1'         => $primaryAddr['address_line1'] ?? null ?: null,
        'address_line2'         => $primaryAddr['address_line2'] ?? null ?: null,
        'area'                  => $primaryAddr['area'] ?? null ?: null,
        'city'                  => $primaryAddr['city'] ?? null ?: null,
        'state'                 => $primaryAddr['state'] ?? null ?: null,
        'pincode'               => $primaryAddr['pincode'] ?? null ?: null,
        'product_type'          => $_POST['product_type'] ?: null,
        'requirement_description'=> $_POST['requirement_description'] ?: null,
        'estimated_budget'      => (float)($_POST['estimated_budget'] ?? 0),
        'purchase_timeline'     => $_POST['purchase_timeline'] ?: null,
        'competitor_info'       => $_POST['competitor_info'] ?: null,
        'updated_by'            => $updatedBy
    ];

    $params = array_values($newLeadData);
    $params[] = $id;

    db()->prepare($sql)->execute($params);

    // ── 2. Refresh Contacts (REMOVED) ─────────────────────────
    // Contacts are now managed exclusively via AJAX in both view.php and edit.php
    // to allow full master-contact sync and extended fields.


    // ── 2b. Refresh Addresses ───────────────────────────────────
    db()->prepare("DELETE FROM lead_addresses WHERE lead_id = ?")->execute([$id]);
    if (!empty($_POST['addresses'])) {
        $stmtAddr = db()->prepare(
            "INSERT INTO lead_addresses (lead_id, address_type, address_line1, address_line2, area, city, state, pincode, lat, lng, google_location, google_address, google_maps_link, is_primary)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        foreach ($_POST['addresses'] as $a) {
            if (empty($a['address_line1']) && empty($a['city']) && empty($a['google_address'])) continue;

            $stmtAddr->execute([
                $id,
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

    // ── 3. Refresh Products ───────────────────────────────────
    db()->prepare("DELETE FROM lead_interested_products WHERE lead_id = ?")->execute([$id]);
    if (!empty($_POST['interested_products'])) {
        $stmtProd = db()->prepare("INSERT INTO lead_interested_products (lead_id, product_name) VALUES (?,?)");
        foreach ($_POST['interested_products'] as $p) {
            if ($p) $stmtProd->execute([$id, $p]);
        }
    }

    // ── 4. New Document Uploads ───────────────────────────────
    $allFiles = [];
    if (!empty($_FILES['documents']['name'][0])) {
        foreach ($_FILES['documents']['name'] as $k => $name) {
            $allFiles[] = ['name'=>$name,'type'=>$_FILES['documents']['type'][$k],'tmp_name'=>$_FILES['documents']['tmp_name'][$k],'error'=>$_FILES['documents']['error'][$k],'size'=>$_FILES['documents']['size'][$k],'source'=>'Device'];
        }
    }
    if (!empty($_FILES['camera_photos']['name'][0])) {
        foreach ($_FILES['camera_photos']['name'] as $k => $name) {
            $allFiles[] = ['name'=>$name,'type'=>$_FILES['camera_photos']['type'][$k],'tmp_name'=>$_FILES['camera_photos']['tmp_name'][$k],'error'=>$_FILES['camera_photos']['error'][$k],'size'=>$_FILES['camera_photos']['size'][$k],'source'=>'Mobile'];
        }
    }
    if (!empty($_FILES['camera_photos_2']['name'][0])) {
        foreach ($_FILES['camera_photos_2']['name'] as $k => $name) {
            $allFiles[] = ['name'=>$name,'type'=>$_FILES['camera_photos_2']['type'][$k],'tmp_name'=>$_FILES['camera_photos_2']['tmp_name'][$k],'error'=>$_FILES['camera_photos_2']['error'][$k],'size'=>$_FILES['camera_photos_2']['size'][$k],'source'=>'Mobile'];
        }
    }
    $stmtDoc = db()->prepare("INSERT INTO lead_documents (lead_id, file_path, file_name, file_type, category, remark, uploaded_from, uploaded_by) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($allFiles as $fd) {
        $path = uploadFile($fd, 'leads');
        if ($path) $stmtDoc->execute([$id, $path, $fd['name'], $fd['type'], 'Site Media', $_POST['upload_remark'] ?? null, $fd['source'], $updatedBy]);
    }

    // ── 5. New Meeting ────────────────────────────────────────
    if (!empty($_POST['meeting_type']) && (!empty($_POST['meeting_purpose']) || !empty($_POST['meeting_with_name']) || !empty($_POST['meeting_followup_date']))) {
        db()->prepare("INSERT INTO lead_meetings (lead_id, meeting_with, type, purpose, status, sales_stage, followup_date, created_by) VALUES (?,?,?,?,?,?,?,?)")
           ->execute([$id, $_POST['meeting_with_name'] ?? null, $_POST['meeting_type'], $_POST['meeting_purpose'] ?? null, $_POST['meeting_status'] ?? 'Scheduled', $_POST['sales_stage'] ?: null, $_POST['meeting_followup_date'] ?: null, $updatedBy]);
    }

    // ── 6. Timeline ───────────────────────────────────────────
    $trackFields = [
        'lead_status' => 'Status',
        'lead_priority' => 'Priority',
        'assigned_to' => 'Assigned To',
        'expected_closing_date' => 'Expected Closing Date',
        'estimated_budget' => 'Estimated Budget',
        'company_name' => 'Company Name',
        'company_status' => 'Company Status',
        'site_stage' => 'Site Stage',
        'project_type' => 'Project Type',
    ];

    $changes = [];
    if (isset($oldLead) && $oldLead) {
        foreach ($trackFields as $key => $label) {
            $oldVal = (string)($oldLead[$key] ?? '');
            $newVal = (string)($newLeadData[$key] ?? '');

            if ($key === 'estimated_budget' && $oldVal != $newVal) {
                $oldVal = round((float)$oldVal, 2);
                $newVal = round((float)$newVal, 2);
            }

            if ($oldVal !== $newVal) {
                if ($key === 'assigned_to') {
                    $newName = $newVal ? db()->query("SELECT name FROM users WHERE id = " . (int)$newVal)->fetchColumn() : 'None';
                    $changes[] = "$label to $newName";
                } else {
                    $displayNew = $newVal ?: 'None';
                    $changes[] = "$label to $displayNew";
                }
            }
        }
    }

    $description = !empty($changes) ? 'Updated: ' . implode(', ', $changes) . '.' : 'Lead details updated.';

    db()->prepare("INSERT INTO lead_timeline (lead_id, user_id, action_type, description) VALUES (?,?,?,?)")
       ->execute([$id, $updatedBy, 'Updated', $description]);

    db()->commit();
    setFlash('success', 'Lead updated successfully.');
    header("Location: view.php?id=$id");

} catch (Exception $e) {
    if (db()->inTransaction()) db()->rollBack();
    setFlash('danger', 'Update failed: ' . $e->getMessage());
    header("Location: edit.php?id=$id");
}
exit;
