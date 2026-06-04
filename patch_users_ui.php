<?php
$file = 'modules/settings/users.php';
$content = file_get_contents($file);

// Replace Edit Modal Dialog class
$content = str_replace(
    '<div class="modal-dialog">', 
    '<div class="modal-dialog modal-dialog-centered">', 
    $content
);

// Replace form-check with form-check form-switch for checkboxes in Edit User
$oldCheckboxEdit = <<<HTML
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="access_rights[]" value="<?= \$r ?>" id="edit_right_<?= \$u['id'] ?>_<?= \$r ?>" <?= in_array(\$r, \$userRights)?'checked':'' ?>>
                                                <label class="form-check-label" for="edit_right_<?= \$u['id'] ?>_<?= \$r ?>" style="font-size: 0.8rem;">
                                                    <?= \$r ?>
                                                </label>
                                            </div>
HTML;
$newCheckboxEdit = <<<HTML
                                            <div class="form-check form-switch">
                                                <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="access_rights[]" value="<?= \$r ?>" id="edit_right_<?= \$u['id'] ?>_<?= \$r ?>" <?= in_array(\$r, \$userRights)?'checked':'' ?>>
                                                <label class="form-check-label cursor-pointer" for="edit_right_<?= \$u['id'] ?>_<?= \$r ?>" style="font-size: 0.85rem; padding-top: 2px;">
                                                    <?= \$r ?>
                                                </label>
                                            </div>
HTML;
$content = str_replace($oldCheckboxEdit, $newCheckboxEdit, $content);

// Replace form-check with form-check form-switch for checkboxes in Add User
$oldCheckboxAdd = <<<HTML
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="access_rights[]" value="<?= \$r ?>" id="add_right_<?= \$r ?>" <?= \$r==='Read'||\$r==='View'?'checked':'' ?>>
                                <label class="form-check-label" for="add_right_<?= \$r ?>" style="font-size: 0.8rem;">
                                    <?= \$r ?>
                                </label>
                            </div>
HTML;
$newCheckboxAdd = <<<HTML
                            <div class="form-check form-switch">
                                <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="access_rights[]" value="<?= \$r ?>" id="add_right_<?= \$r ?>" <?= \$r==='Read'||\$r==='View'?'checked':'' ?>>
                                <label class="form-check-label cursor-pointer" for="add_right_<?= \$r ?>" style="font-size: 0.85rem; padding-top: 2px;">
                                    <?= \$r ?>
                                </label>
                            </div>
HTML;
$content = str_replace($oldCheckboxAdd, $newCheckboxAdd, $content);

file_put_contents($file, $content);
echo "Successfully patched users.php\n";
