<?php
$file = 'modules/customers/view.php';
$content = file_get_contents($file);

// Replace query
$oldQuery = <<<PHP
\$stmt = db()->prepare("SELECT c.*, u.name AS created_by_name,
    (SELECT name FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_name,
    (SELECT mobile FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_phone,
    (SELECT email FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_email,
    (SELECT whatsapp FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_whatsapp
    FROM customers c LEFT JOIN users u ON c.created_by=u.id WHERE c.id=?");
PHP;

$newQuery = <<<PHP
\$stmt = db()->prepare("SELECT c.*, u.name AS created_by_name,
    (SELECT name FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_name,
    (SELECT mobile FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_phone,
    (SELECT email FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_email,
    (SELECT whatsapp FROM customer_contacts cc WHERE cc.customer_id=c.id AND cc.is_primary=1 LIMIT 1) AS primary_contact_whatsapp,
    (SELECT lead_code FROM leads l WHERE l.id=c.source_lead_id LIMIT 1) AS source_lead_code
    FROM customers c LEFT JOIN users u ON c.created_by=u.id WHERE c.id=?");
PHP;

$content = str_replace($oldQuery, $newQuery, $content);

// Insert HTML block
$oldHtml = <<<HTML
        <?php if (\$customer['address_line1']): ?>
        <div class="d-flex gap-3">
          <div class="text-muted" style="width:20px"><i class="bi bi-house"></i></div>
          <div><div class="text-muted small">Address</div><div class="fw-semibold small"><?= nl2br(e(\$customer['address_line1'])) ?></div></div>
        </div>
        <?php endif; ?>
      </div>
HTML;

$newHtml = <<<HTML
        <?php if (\$customer['address_line1']): ?>
        <div class="d-flex gap-3">
          <div class="text-muted" style="width:20px"><i class="bi bi-house"></i></div>
          <div><div class="text-muted small">Address</div><div class="fw-semibold small"><?= nl2br(e(\$customer['address_line1'])) ?></div></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty(\$customer['source_lead_id'])): ?>
        <div class="d-flex gap-3 mt-3 pt-3 border-top">
          <div class="text-primary" style="width:20px"><i class="bi bi-funnel"></i></div>
          <div>
            <div class="text-muted small">Origin</div>
            <div class="fw-semibold small">
              Converted from Lead: <a href="<?= BASE_URL ?>/modules/prospects/view.php?id=<?= \$customer['source_lead_id'] ?>" class="text-decoration-none fw-bold"><?= e(\$customer['source_lead_code']) ?></a>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
HTML;

$content = str_replace($oldHtml, $newHtml, $content);

file_put_contents($file, $content);
echo "Successfully patched customer view with lead origin link.\n";
