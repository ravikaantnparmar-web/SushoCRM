<?php
// Patching add_contact_ajax.php
$file = 'modules/prospects/add_contact_ajax.php';
$content = file_get_contents($file);
$content = str_replace(
    '$email = $_POST[\'email\'] ?: null;',
    '$email = $_POST[\'email\'] ?: null;
        $organization_name = $_POST[\'organization_name\'] ?? null;
        $address = $_POST[\'address\'] ?? null;
        $city = $_POST[\'city\'] ?? null;
        $state = $_POST[\'state\'] ?? null;
        $pincode = $_POST[\'pincode\'] ?? null;
        $website = $_POST[\'website\'] ?? null;',
    $content
);
$content = str_replace(
    'visiting_card, is_primary) 
                VALUES (?,?,?,?,?,?,?,?,0)',
    'visiting_card, is_primary, organization_name, address, city, state, pincode, website) 
                VALUES (?,?,?,?,?,?,?,?,0, ?,?,?,?,?,?)',
    $content
);
$content = str_replace(
    'execute([$leadId, $contactType, $name, $designation, $mobile, $whatsapp, $email, $cardPathJson])',
    'execute([$leadId, $contactType, $name, $designation, $mobile, $whatsapp, $email, $cardPathJson, $organization_name, $address, $city, $state, $pincode, $website])',
    $content
);
file_put_contents($file, $content);

// Patching update_contact_ajax.php
$file = 'modules/prospects/update_contact_ajax.php';
$content = file_get_contents($file);
$content = str_replace(
    '$email = $_POST[\'email\'] ?: null;',
    '$email = $_POST[\'email\'] ?: null;
        $organization_name = $_POST[\'organization_name\'] ?? null;
        $address = $_POST[\'address\'] ?? null;
        $city = $_POST[\'city\'] ?? null;
        $state = $_POST[\'state\'] ?? null;
        $pincode = $_POST[\'pincode\'] ?? null;
        $website = $_POST[\'website\'] ?? null;',
    $content
);
$content = str_replace(
    'email = ?, visiting_card = ? 
                WHERE id = ?',
    'email = ?, visiting_card = ?, organization_name = ?, address = ?, city = ?, state = ?, pincode = ?, website = ? 
                WHERE id = ?',
    $content
);
$content = str_replace(
    'execute([$contactType, $name, $designation, $mobile, $whatsapp, $email, $cardPathJson, $id])',
    'execute([$contactType, $name, $designation, $mobile, $whatsapp, $email, $cardPathJson, $organization_name, $address, $city, $state, $pincode, $website, $id])',
    $content
);
file_put_contents($file, $content);

// Patching save.php
$file = 'modules/prospects/save.php';
$content = file_get_contents($file);
$content = str_replace(
    'email, visiting_card, is_primary)
             VALUES (?,?,?,?,?,?,?,?,?,?)',
    'email, visiting_card, is_primary, organization_name, address, city, state, pincode, website)
             VALUES (?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?)',
    $content
);
$content = str_replace(
    '!empty($c[\'is_primary\']) ? 1 : 0,',
    '!empty($c[\'is_primary\']) ? 1 : 0,
                $c[\'organization_name\'] ?? null,
                $c[\'address\'] ?? null,
                $c[\'city\'] ?? null,
                $c[\'state\'] ?? null,
                $c[\'pincode\'] ?? null,
                $c[\'website\'] ?? null,',
    $content
);
file_put_contents($file, $content);


// Patching view.php
$file = 'modules/prospects/view.php';
$content = file_get_contents($file);

// Add to modal
$modalInsert = <<<HTML
            <div class="col-12"><hr class="my-2"><h6 class="fw-bold mb-0">Organization Details (Optional)</h6></div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Organization Name</label>
              <input type="text" name="organization_name" id="contact_organization_name" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Website</label>
              <input type="url" name="website" id="contact_website" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Address</label>
              <textarea name="address" id="contact_address" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">City</label>
              <input type="text" name="city" id="contact_city" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">State</label>
              <input type="text" name="state" id="contact_state" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Pincode</label>
              <input type="text" name="pincode" id="contact_pincode" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold d-block">Visiting Card (Select multiple if needed)</label>
HTML;

$content = str_replace(
    '<div class="col-12">
              <label class="form-label fw-semibold d-block">Visiting Card (Select multiple if needed)</label>',
    $modalInsert,
    $content
);

// update showEditContactModal
$jsInsert = <<<JS
    document.getElementById('contact_whatsapp').value = c.whatsapp || '';
    document.getElementById('contact_email').value = c.email || '';
    
    document.getElementById('contact_organization_name').value = c.organization_name || '';
    document.getElementById('contact_website').value = c.website || '';
    document.getElementById('contact_address').value = c.address || '';
    document.getElementById('contact_city').value = c.city || '';
    document.getElementById('contact_state').value = c.state || '';
    document.getElementById('contact_pincode').value = c.pincode || '';
JS;

$content = str_replace(
    'document.getElementById(\'contact_whatsapp\').value = c.whatsapp || \'\';
    document.getElementById(\'contact_email\').value = c.email || \'\';',
    $jsInsert,
    $content
);

file_put_contents($file, $content);

// Patching create.php
$file = 'modules/prospects/create.php';
$content = file_get_contents($file);

// Table headers
$headersInsert = <<<HTML
                  <th>Mobile <span class="text-danger">*</span></th>
                  <th>WhatsApp</th>
                  <th>Org Name</th>
                  <th>City</th>
                  <th>State</th>
                  <th>More Info</th>
HTML;
$content = str_replace(
    '<th>Mobile <span class="text-danger">*</span></th>
                  <th>WhatsApp</th>',
    $headersInsert,
    $content
);

// Table row template
$rowInsert = <<<HTML
    <td><input type="tel" name="" class="form-control contact-mobile-input" placeholder="Mobile"></td>
    <td><input type="tel" name="" class="form-control contact-whatsapp-input" placeholder="WhatsApp"></td>
    <td><input type="text" name="" class="form-control contact-org-input" placeholder="Org Name"></td>
    <td><input type="text" name="" class="form-control contact-city-input" placeholder="City"></td>
    <td><input type="text" name="" class="form-control contact-state-input" placeholder="State"></td>
    <td>
      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="$(this).next('.more-info-box').toggle()"><i class="bi bi-three-dots"></i></button>
      <div class="more-info-box position-absolute bg-white border p-2 shadow-sm rounded" style="display:none; z-index:1000; width:250px; margin-top:5px;">
        <input type="text" name="" class="form-control form-control-sm mb-1 contact-address-input" placeholder="Full Address">
        <input type="text" name="" class="form-control form-control-sm mb-1 contact-pincode-input" placeholder="Pincode">
        <input type="text" name="" class="form-control form-control-sm contact-website-input" placeholder="Website">
      </div>
    </td>
HTML;

$content = str_replace(
    '<td><input type="tel" name="" class="form-control contact-mobile-input" placeholder="Mobile"></td>
    <td><input type="tel" name="" class="form-control contact-whatsapp-input" placeholder="WhatsApp"></td>',
    $rowInsert,
    $content
);

// update JS logic for mapping names
$jsLogic = <<<JS
        row.querySelector('.contact-mobile-input').name    = `contacts[\${index}][mobile]`;
        row.querySelector('.contact-whatsapp-input').name  = `contacts[\${index}][whatsapp]`;
        
        if(row.querySelector('.contact-org-input')) row.querySelector('.contact-org-input').name = `contacts[\${index}][organization_name]`;
        if(row.querySelector('.contact-city-input')) row.querySelector('.contact-city-input').name = `contacts[\${index}][city]`;
        if(row.querySelector('.contact-state-input')) row.querySelector('.contact-state-input').name = `contacts[\${index}][state]`;
        if(row.querySelector('.contact-address-input')) row.querySelector('.contact-address-input').name = `contacts[\${index}][address]`;
        if(row.querySelector('.contact-pincode-input')) row.querySelector('.contact-pincode-input').name = `contacts[\${index}][pincode]`;
        if(row.querySelector('.contact-website-input')) row.querySelector('.contact-website-input').name = `contacts[\${index}][website]`;

        row.querySelector('.primary-check').name           = `contacts[\${index}][is_primary]`;
JS;
$content = str_replace(
    'row.querySelector(\'.contact-mobile-input\').name    = `contacts[${index}][mobile]`;
        row.querySelector(\'.contact-whatsapp-input\').name  = `contacts[${index}][whatsapp]`;
        row.querySelector(\'.primary-check\').name           = `contacts[${index}][is_primary]`;',
    $jsLogic,
    $content
);

file_put_contents($file, $content);

// Patching edit.php
$file = 'modules/prospects/edit.php';
$content = file_get_contents($file);

// Table headers
$content = str_replace(
    '<th>Mobile <span class="text-danger">*</span></th>
                  <th>WhatsApp</th>',
    $headersInsert,
    $content
);

// Table row template
$content = str_replace(
    '<td><input type="tel" name="" class="form-control contact-mobile-input" placeholder="Mobile"></td>
    <td><input type="tel" name="" class="form-control contact-whatsapp-input" placeholder="WhatsApp"></td>',
    $rowInsert,
    $content
);

$content = str_replace(
    'row.querySelector(\'.contact-mobile-input\').name    = `contacts[${index}][mobile]`;
        row.querySelector(\'.contact-whatsapp-input\').name  = `contacts[${index}][whatsapp]`;
        row.querySelector(\'.primary-check\').name           = `contacts[${index}][is_primary]`;',
    $jsLogic,
    $content
);

file_put_contents($file, $content);


echo "Successfully patched prospect scripts.\n";
