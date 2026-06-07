<?php
$content = file_get_contents("c:\\xampp\\htdocs\\SushobhaCRM\\modules\\prospects\\create.php");

$jsToInsert = "
function editLocalContact(btn) {
  const row = btn.closest('tr');
  const idx = row.dataset.contactIndex;
  
  document.getElementById('contactForm').reset();
  document.getElementById('contact_form_mode').value = 'edit_local';
  document.getElementById('contact_form_mode').dataset.editIndex = idx;
  
  document.getElementById('contactModalLabel').textContent = 'Edit Contact';
  
  document.getElementById('contact_id').value = row.querySelector('.contact-id-input').value;
  document.getElementById('contact_name').value = row.querySelector('.contact-name-input').value;
  document.getElementById('contact_contact_type').value = row.querySelector('.contact-contact-type-input').value;
  document.getElementById('contact_email').value = row.querySelector('.contact-email-input').value;
  document.getElementById('contact_mobile').value = row.querySelector('.contact-mobile-input').value;
  document.getElementById('contact_whatsapp').value = row.querySelector('.contact-whatsapp-input').value;
  document.getElementById('contact_organization_name').value = row.querySelector('.contact-organization-name-input').value;
  document.getElementById('contact_website').value = row.querySelector('.contact-website-input').value;
  document.getElementById('contact_address').value = row.querySelector('.contact-address-input').value;
  document.getElementById('contact_city').value = row.querySelector('.contact-city-input').value;
  document.getElementById('contact_state').value = row.querySelector('.contact-state-input').value;
  document.getElementById('contact_pincode').value = row.querySelector('.contact-pincode-input').value;
  document.getElementById('contact_existing_cards').value = row.querySelector('.contact-existing-cards-input').value;
  
  new bootstrap.Modal(document.getElementById('contactModal')).show();
}
";

// Insert the JS function right before showAddContactModal
$content = str_replace("function showAddContactModal() {", $jsToInsert . "\nfunction showAddContactModal() {\n  document.getElementById('contact_form_mode').dataset.editIndex = '';", $content);

// Update the Edit button template
$content = str_replace("<button type=\"button\" class=\"btn btn-outline-primary btn-sm px-2 py-1 edit-btn\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>", "<button type=\"button\" class=\"btn btn-outline-primary btn-sm px-2 py-1 edit-btn\" onclick=\"editLocalContact(this)\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>", $content);

// Update the submit listener
$oldSubmit = "  const tpl = document.getElementById('contact-tpl');
  const clone = tpl.content.cloneNode(true);
  const row = clone.querySelector('tr');
  const idx = contactCount;";

$newSubmit = "  const formMode = document.getElementById('contact_form_mode').value;
  let row, idx;
  
  if (formMode === 'edit_local') {
    idx = document.getElementById('contact_form_mode').dataset.editIndex;
    row = document.querySelector(`.contact-row[data-contact-index='\${idx}']`);
  } else {
    const tpl = document.getElementById('contact-tpl');
    const clone = tpl.content.cloneNode(true);
    row = clone.querySelector('tr');
    idx = contactCount;
  }";

$content = str_replace($oldSubmit, $newSubmit, $content);

$oldAppend = "  document.getElementById('contacts-tbody').appendChild(row);
  contactCount++;
  
  // Initialize file inputs functionality for this new row
  initContactRowFiles(row, idx);";

$newAppend = "  if (formMode !== 'edit_local') {
    document.getElementById('contacts-tbody').appendChild(row);
    contactCount++;
    initContactRowFiles(row, idx);
  } else {
    // Already in DOM, but maybe files updated. 
    // DataTransfer logic below will overwrite the file input.
  }";
  
$content = str_replace($oldAppend, $newAppend, $content);

file_put_contents("c:\\xampp\\htdocs\\SushobhaCRM\\modules\\prospects\\create.php", $content);
