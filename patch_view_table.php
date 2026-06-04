<?php
$file = 'modules/prospects/view.php';
$content = file_get_contents($file);

$oldCode = <<<PHP
                  <?php if(\$c['email']): ?>
                    <div class="small"><i class="bi bi-envelope text-muted me-1"></i><a href="mailto:<?= e(\$c['email']) ?>"><?= e(\$c['email']) ?></a></div>
                  <?php endif; ?>
                </td>
                <td>
PHP;

$newCode = <<<PHP
                  <?php if(\$c['email']): ?>
                    <div class="small"><i class="bi bi-envelope text-muted me-1"></i><a href="mailto:<?= e(\$c['email']) ?>"><?= e(\$c['email']) ?></a></div>
                  <?php endif; ?>
                  
                  <?php if(!empty(\$c['organization_name']) || !empty(\$c['city']) || !empty(\$c['website'])): ?>
                    <div class="mt-2 pt-2 border-top border-light">
                      <?php if(\$c['organization_name']): ?>
                        <div class="small text-muted"><i class="bi bi-building me-1"></i><strong><?= e(\$c['organization_name']) ?></strong></div>
                      <?php endif; ?>
                      <?php if(\$c['address'] || \$c['city'] || \$c['state'] || \$c['pincode']): ?>
                        <div class="small text-muted" style="font-size:11px;">
                          <i class="bi bi-geo-alt me-1"></i>
                          <?= implode(', ', array_filter([e(\$c['address']), e(\$c['city']), e(\$c['state']), e(\$c['pincode'])])) ?>
                        </div>
                      <?php endif; ?>
                      <?php if(\$c['website']): ?>
                        <div class="small"><i class="bi bi-globe text-muted me-1"></i><a href="<?= e(\$c['website']) ?>" target="_blank" class="text-decoration-none" style="font-size:11px;">Website</a></div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
PHP;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "Successfully patched prospect view table.\n";
