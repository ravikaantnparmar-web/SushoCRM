<?php
$content = file_get_contents("c:\\xampp\\htdocs\\SushobhaCRM\\modules\\prospects\\create.php");
$lines = explode("\n", $content);
$newLines = [];
for ($i = 0; $i < 1032; $i++) {
    $newLines[] = rtrim($lines[$i]);
}

$block = "

document.getElementById('lead-create-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '<span class=\"spinner-border spinner-border-sm me-2\"></span>Creating...';
});

function clearGoogleLocation() {
  ['google_address','google_maps_link','lat','lng','google_location'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('google-search-input').value = '';
  document.getElementById('google-address-preview').classList.add('d-none');
}

let addressCount = 0;
function addAddress(isPrimary = false) {
  const tpl = document.getElementById('address-tpl');
  const clone = tpl.content.cloneNode(true);
  const card = clone.querySelector('.address-card');
  const idx = addressCount;

  card.dataset.addressIndex = idx;

  card.querySelector('.address-type-input').name = 'addresses[' + idx + '][address_type]';
  card.querySelector('.primary-address-check').name = 'addresses[' + idx + '][is_primary]';
  card.querySelector('.addr-line1').name = 'addresses[' + idx + '][address_line1]';
  card.querySelector('.addr-line2').name = 'addresses[' + idx + '][address_line2]';
  card.querySelector('.addr-area').name = 'addresses[' + idx + '][area]';
  card.querySelector('.addr-city').name = 'addresses[' + idx + '][city]';
  card.querySelector('.addr-state').name = 'addresses[' + idx + '][state]';
  card.querySelector('.addr-pincode').name = 'addresses[' + idx + '][pincode]';

  card.querySelector('.addr-gaddress').name = 'addresses[' + idx + '][google_address]';
  card.querySelector('.addr-glink').name = 'addresses[' + idx + '][google_maps_link]';
  card.querySelector('.addr-lat').name = 'addresses[' + idx + '][lat]';
  card.querySelector('.addr-lng').name = 'addresses[' + idx + '][lng]';
  card.querySelector('.addr-gcode').name = 'addresses[' + idx + '][google_location]';

  if (isPrimary) {
    card.querySelector('.primary-address-check').checked = true;
    card.querySelector('.address-type-input').value = 'Site Address'; // default
  } else {
    card.querySelector('.remove-address-btn').style.display = 'inline-block';
  }

  document.getElementById('addresses-container').appendChild(clone);
  addressCount++;
  updateAddressBadge();
}
";

$newLines[] = $block;

for ($i = 1084; $i < count($lines); $i++) {
    $newLines[] = rtrim($lines[$i]);
}

file_put_contents("c:\\xampp\\htdocs\\SushobhaCRM\\modules\\prospects\\create.php", implode("\n", $newLines));
