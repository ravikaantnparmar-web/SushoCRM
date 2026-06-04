<?php
$file = 'modules/quotations/view.php';
$content = file_get_contents($file);

$oldLoop = <<<HTML
          <?php foreach(\$versions as \$v): ?>
          <tr <?= \$v['id'] == \$q['id'] ? 'class="table-primary"' : '' ?>>
            <td>
              <span class="badge <?= \$v['is_latest'] ? 'bg-success' : 'bg-secondary' ?>">V<?= \$v['version'] ?></span>
              <?php if(\$v['is_latest']) echo ' <small class="text-success fw-bold">(Latest)</small>'; ?>
            </td>
            <td><?= formatDate(\$v['created_at']) ?></td>
            <td><?= e(\$v['author']) ?></td>
            <td><?= e(\$v['revision_notes'] ?: '—') ?></td>
            <td class="fw-bold"><?= formatCurrency(\$v['total']) ?></td>
            <td><?= statusBadge(\$v['status']) ?></td>
            <td>
              <?php if(\$v['id'] != \$q['id']): ?>
                <a href="view.php?id=<?= \$v['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <?php else: ?>
                <span class="text-muted small">Currently Viewing</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
HTML;

$newLoop = <<<HTML
          <?php foreach(\$versions as \$index => \$v): ?>
          <?php 
          \$diffHtml = '';
          if (isset(\$versions[\$index + 1])) {
              \$prevV = \$versions[\$index + 1];
              \$diff = \$v['total'] - \$prevV['total'];
              if (\$diff > 0) {
                  \$diffHtml = '<div class="small text-danger fw-semibold mt-1" style="font-size: 0.75rem;"><i class="bi bi-arrow-up-short"></i>' . formatCurrency(abs(\$diff)) . '</div>';
              } elseif (\$diff < 0) {
                  \$diffHtml = '<div class="small text-success fw-semibold mt-1" style="font-size: 0.75rem;"><i class="bi bi-arrow-down-short"></i>' . formatCurrency(abs(\$diff)) . '</div>';
              } else {
                  \$diffHtml = '<div class="small text-muted fw-semibold mt-1" style="font-size: 0.75rem;">No change</div>';
              }
          }
          ?>
          <tr <?= \$v['id'] == \$q['id'] ? 'class="table-primary"' : '' ?>>
            <td>
              <span class="badge <?= \$v['is_latest'] ? 'bg-success' : 'bg-secondary' ?>">V<?= \$v['version'] ?></span>
              <?php if(\$v['is_latest']) echo ' <small class="text-success fw-bold">(Latest)</small>'; ?>
            </td>
            <td><?= formatDate(\$v['created_at']) ?></td>
            <td><?= e(\$v['author']) ?></td>
            <td><?= e(\$v['revision_notes'] ?: '—') ?></td>
            <td class="fw-bold">
              <?= formatCurrency(\$v['total']) ?>
              <?= \$diffHtml ?>
            </td>
            <td><?= statusBadge(\$v['status']) ?></td>
            <td>
              <?php if(\$v['id'] != \$q['id']): ?>
                <a href="view.php?id=<?= \$v['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <?php else: ?>
                <span class="text-muted small">Currently Viewing</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
HTML;

$content = str_replace($oldLoop, $newLoop, $content);
file_put_contents($file, $content);
echo "Successfully patched view.php to show diffs\n";
