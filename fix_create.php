<?php
$file = 'modules/prospects/create.php';
$content = file_get_contents($file);

$search = "    if (searchInput && resultsContainer) {";
$pos = strpos($content, $search);

if ($pos !== false) {
    $goodContent = substr($content, 0, $pos);
    // Add the missing closing brackets before the search bar script
    $rest = <<<'EOD'
    },
    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
  );
}

// Init: add first primary address on page load
addAddress(true);

// Global Contact Search Autocomplete
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global_contact_search');
    const resultsContainer = document.getElementById('global_contact_search_results');
    
    if (searchInput && resultsContainer) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const val = this.value.trim();
            if (val.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }
            
            debounceTimer = setTimeout(() => {
                fetch('../contacts/search_ajax.php?q=' + encodeURIComponent(val))
                .then(r => r.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.results && data.results.length > 0) {
                        data.results.forEach(item => {
                            const a = document.createElement('a');
                            a.href = '#';
                            a.className = 'list-group-item list-group-item-action py-2';
                            
                            let subtitle = '';
                            if(item.contact.organization_name) subtitle += item.contact.organization_name;
                            if(item.contact.mobile) subtitle += (subtitle ? ' | ' : '') + item.contact.mobile;
                            
                            a.innerHTML = `<div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${item.contact.name}</h6>
                                <small>${item.contact.contact_type || ''}</small>
                            </div>
                            <small class="text-muted">${subtitle}</small>`;
                            
                            a.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                // Create new row by triggering addContact
                                addContact();
                                
                                // Get the last added row (which is the one we just added)
                                const rows = document.querySelectorAll('#contacts-tbody tr');
                                const lastRow = rows[rows.length - 1];
                                
                                // Populate the fields
                                if (lastRow) {
                                    lastRow.querySelector('.contact-name-input').value = item.contact.name || '';
                                    lastRow.querySelector('.contact-designation-input').value = item.contact.designation || '';
                                    lastRow.querySelector('.contact-mobile-input').value = item.contact.mobile || '';
                                    lastRow.querySelector('.contact-whatsapp-input').value = item.contact.whatsapp || '';
                                    lastRow.querySelector('.contact-email-input').value = item.contact.email || '';
                                }
                                
                                resultsContainer.style.display = 'none';
                                searchInput.value = '';
                                
                                // Show success feedback
                                searchInput.placeholder = `Added: ${item.contact.name}`;
                                setTimeout(() => searchInput.placeholder = "Search Master Contacts...", 3000);
                            });
                            resultsContainer.appendChild(a);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="list-group-item text-muted">No contacts found. Use "Add Blank Contact".</div>';
                        resultsContainer.style.display = 'block';
                    }
                });
            }, 300);
        });
        
        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && e.target !== resultsContainer && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
EOD;

    file_put_contents($file, $goodContent . $rest);
    echo "Fixed create.php";
} else {
    echo "Pattern not found";
}
