<?php
// 1. Patch create.php
$create = file_get_contents('modules/quotations/create.php');
// PHP block replacements
$create = str_replace(
    "\$valid_until = sanitize(\$_POST['valid_until'] ?? '');\n    \$notes = sanitize(\$_POST['notes'] ?? '');",
    "\$valid_until = sanitize(\$_POST['valid_until'] ?? '');\n    \$project_name = sanitize(\$_POST['project_name'] ?? '');\n    \$contact_person = sanitize(\$_POST['contact_person'] ?? '');\n    \$notes = sanitize(\$_POST['notes'] ?? '');",
    $create
);
$create = str_replace(
    "INSERT INTO quotations (quote_number, customer_id, status, valid_until, subtotal, discount_type, discount_value, discount_amount, tax_amount, total, notes, terms, created_by)",
    "INSERT INTO quotations (quote_number, customer_id, project_name, contact_person, status, valid_until, subtotal, discount_type, discount_value, discount_amount, tax_amount, total, notes, terms, created_by)",
    $create
);
$create = str_replace(
    "execute([\$quote_number, \$customer_id, \$status, \$valid_until?:null, \$subtotal,",
    "execute([\$quote_number, \$customer_id, \$project_name, \$contact_person, \$status, \$valid_until?:null, \$subtotal,",
    $create
);

// HTML form replacements
$htmlBlock = <<<HTML
            <select name="status" class="form-select">
              <option value="draft" <?= (\$_POST['status']??'draft')==='draft'?'selected':'' ?>>Draft</option>
              <option value="sent" <?= (\$_POST['status']??'')==='sent'?'selected':'' ?>>Sent</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Project Name</label>
            <input type="text" name="project_name" class="form-control" value="<?= e(\$_POST['project_name']??'') ?>" placeholder="Project Name (Optional)">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= e(\$_POST['contact_person']??'') ?>" placeholder="Contact Person (Optional)">
          </div>
HTML;

$create = preg_replace(
    '/<select name="status" class="form-select">\s*<option value="draft" <\?= \(\$_POST\[\'status\'\]\?\?\'draft\'\)===\'draft\'\?\'selected\':\'\' \?>\>Draft<\/option>\s*<option value="sent" <\?= \(\$_POST\[\'status\'\]\?\?\'\'\)===\'sent\'\?\'selected\':\'\' \?>\>Sent<\/option>\s*<\/select>\s*<\/div>/',
    $htmlBlock,
    $create
);
file_put_contents('modules/quotations/create.php', $create);


// 2. Patch edit.php
$edit = file_get_contents('modules/quotations/edit.php');
// PHP block replacements
$edit = str_replace(
    "\$valid_until = sanitize(\$_POST['valid_until'] ?? '');\n    \$notes = sanitize(\$_POST['notes'] ?? '');",
    "\$valid_until = sanitize(\$_POST['valid_until'] ?? '');\n    \$project_name = sanitize(\$_POST['project_name'] ?? '');\n    \$contact_person = sanitize(\$_POST['contact_person'] ?? '');\n    \$notes = sanitize(\$_POST['notes'] ?? '');",
    $edit
);
$edit = str_replace(
    "UPDATE quotations SET quote_number=?, customer_id=?, status=?, valid_until=?, subtotal=?, discount_type=?, discount_value=?, discount_amount=?, tax_amount=?, total=?, notes=?, terms=? WHERE id=?",
    "UPDATE quotations SET quote_number=?, customer_id=?, project_name=?, contact_person=?, status=?, valid_until=?, subtotal=?, discount_type=?, discount_value=?, discount_amount=?, tax_amount=?, total=?, notes=?, terms=? WHERE id=?",
    $edit
);
$edit = str_replace(
    "execute([\$quote_number, \$customer_id, \$status, \$valid_until?:null, \$subtotal,",
    "execute([\$quote_number, \$customer_id, \$project_name, \$contact_person, \$status, \$valid_until?:null, \$subtotal,",
    $edit
);

// HTML form replacements
$htmlBlockEdit = <<<HTML
            <select name="status" class="form-select">
              <?php foreach(['draft','sent','accepted','rejected','converted','expired'] as \$s): ?>
                <option value="<?= \$s ?>" <?= (\$q['status']??'draft')===\$s?'selected':'' ?>><?= ucfirst(\$s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Project Name</label>
            <input type="text" name="project_name" class="form-control" value="<?= e(\$_POST['project_name'] ?? \$q['project_name']) ?>" placeholder="Project Name (Optional)">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= e(\$_POST['contact_person'] ?? \$q['contact_person']) ?>" placeholder="Contact Person (Optional)">
          </div>
HTML;

$edit = preg_replace(
    '/<select name="status" class="form-select">\s*<\?php foreach\(\[\'draft\',\'sent\',\'accepted\',\'rejected\',\'converted\',\'expired\'\] as \$s\): \?>\s*<option value="<\?= \$s \?>" <\?= \(\$q\[\'status\'\]\?\?\'draft\'\)===\$s\?\'selected\':\'\' \?>><\?= ucfirst\(\$s\) \?><\/option>\s*<\?php endforeach; \?>\s*<\/select>\s*<\/div>/',
    $htmlBlockEdit,
    $edit
);
file_put_contents('modules/quotations/edit.php', $edit);

echo "Success patching create.php and edit.php\n";
