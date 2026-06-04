<?php
$file = 'modules/prospects/edit.php';
$content = file_get_contents($file);

$oldCode = <<<PHP
                        <td>
                          <input type="tel" name="contacts[<?= \$idx ?>][whatsapp]"
                            class="form-control contact-whatsapp-input" placeholder="WhatsApp"
                            value="<?= e(\$c['whatsapp']) ?>">
                        </td>
                        <td class="text-center">
PHP;

$newCode = <<<PHP
                        <td>
                          <input type="tel" name="contacts[<?= \$idx ?>][whatsapp]"
                            class="form-control contact-whatsapp-input" placeholder="WhatsApp"
                            value="<?= e(\$c['whatsapp']) ?>">
                        </td>
                        <td>
                          <input type="text" name="contacts[<?= \$idx ?>][organization_name]" class="form-control contact-org-input" placeholder="Org Name" value="<?= e(\$c['organization_name'] ?? '') ?>">
                        </td>
                        <td>
                          <input type="text" name="contacts[<?= \$idx ?>][city]" class="form-control contact-city-input" placeholder="City" value="<?= e(\$c['city'] ?? '') ?>">
                        </td>
                        <td>
                          <input type="text" name="contacts[<?= \$idx ?>][state]" class="form-control contact-state-input" placeholder="State" value="<?= e(\$c['state'] ?? '') ?>">
                        </td>
                        <td>
                          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="$(this).next('.more-info-box').toggle()"><i class="bi bi-three-dots"></i></button>
                          <div class="more-info-box position-absolute bg-white border p-2 shadow-sm rounded" style="display:none; z-index:1000; width:250px; margin-top:5px;">
                            <input type="text" name="contacts[<?= \$idx ?>][address]" class="form-control form-control-sm mb-1 contact-address-input" placeholder="Full Address" value="<?= e(\$c['address'] ?? '') ?>">
                            <input type="text" name="contacts[<?= \$idx ?>][pincode]" class="form-control form-control-sm mb-1 contact-pincode-input" placeholder="Pincode" value="<?= e(\$c['pincode'] ?? '') ?>">
                            <input type="text" name="contacts[<?= \$idx ?>][website]" class="form-control form-control-sm contact-website-input" placeholder="Website" value="<?= e(\$c['website'] ?? '') ?>">
                          </div>
                        </td>
                        <td class="text-center">
PHP;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "Successfully patched edit.php loop.\n";
