<?php
$file = 'modules/customers/view.php';
$content = file_get_contents($file);

$oldQuoteTable = <<<HTML
        <!-- Quotations -->
        <div class="tab-pane fade" id="tabQuotes">
          <table class="table crm-table"><thead><tr><th>Quote #</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
          <tbody><?php foreach(\$quotations as \$q): ?>
            <tr><td><a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= \$q['id'] ?>"><?= e(\$q['quote_number']) ?></a></td>
            <td><?= formatDate(\$q['created_at']) ?></td>
            <td><?= formatCurrency(\$q['total']) ?></td>
            <td><?= statusBadge(\$q['status']) ?></td></tr>
          <?php endforeach; if(empty(\$quotations)): ?><tr><td colspan="4" class="text-center text-muted py-3">No quotations</td></tr><?php endif; ?>
          </tbody></table>
        </div>
HTML;

$newQuoteTable = <<<HTML
        <!-- Quotations -->
        <div class="tab-pane fade" id="tabQuotes">
          <table class="table crm-table"><thead><tr><th>Quote #</th><th>Version</th><th>Date</th><th>Total</th><th>Status</th><th>Remarks</th></tr></thead>
          <tbody><?php foreach(\$quotations as \$q): ?>
            <tr><td><a href="<?= BASE_URL ?>/modules/quotations/view.php?id=<?= \$q['id'] ?>"><?= e(\$q['quote_number']) ?></a></td>
            <td><span class="badge <?= !empty(\$q['is_latest']) ? 'bg-success' : 'bg-secondary' ?>">V<?= \$q['version'] ?? 1 ?></span></td>
            <td><?= formatDate(\$q['created_at']) ?></td>
            <td><?= formatCurrency(\$q['total']) ?></td>
            <td><?= statusBadge(\$q['status']) ?></td>
            <td><span class="small text-muted"><?= e(\$q['revision_notes'] ?: '—') ?></span></td></tr>
          <?php endforeach; if(empty(\$quotations)): ?><tr><td colspan="6" class="text-center text-muted py-3">No quotations</td></tr><?php endif; ?>
          </tbody></table>
        </div>
HTML;

$content = str_replace($oldQuoteTable, $newQuoteTable, $content);
file_put_contents($file, $content);
echo "Successfully patched customer view quotations table.\n";
