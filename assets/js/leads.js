/**
 * SushobhaCRM - Lead Management JS
 */

function previewImage(input, imgSelector, boxSelector) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector(imgSelector).setAttribute('src', e.target.result);
            document.querySelector(boxSelector).classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // --- Dynamic Contact Persons ---
    const contactContainer = document.getElementById('contacts-container');
    const addContactBtn = document.getElementById('add-contact-btn');

    if (addContactBtn && contactContainer) {
        addContactBtn.addEventListener('click', function() {
            const index = contactContainer.querySelectorAll('.contact-item').length;
            const html = `
                <div class="contact-item border rounded p-3 mb-3 position-relative bg-light bg-opacity-50">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-contact"></button>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name</label>
                            <input type="text" name="contacts[${index}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Designation</label>
                                <span class="x-small text-muted"><i class="bi bi-keyboard"></i> F2 to Add</span>
                            </div>
                            <select name="contacts[${index}][type]" class="form-select designation-select">
                                <option value="Primary">Primary</option>
                                <option value="Site Engineer">Site Engineer</option>
                                <option value="Architect">Architect / PMC</option>
                                <option value="Owner">Owner</option>
                                <option value="Manager">Manager</option>
                                <option value="Contractor">Contractor</option>
                                <option value="Purchase Head">Purchase Head</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="contacts[${index}][email]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="contacts[${index}][mobile]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="contacts[${index}][whatsapp]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Alt. Mobile</label>
                            <input type="text" name="contacts[${index}][alt_mobile]" class="form-control">
                        </div>
                        <div class="col-12 mt-2">
                            <div class="d-flex align-items-center justify-content-between p-2 bg-white border rounded">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="card-previews-container d-flex gap-2" id="card-preview-${index}">
                                        <div class="card-preview-box border rounded d-flex align-items-center justify-content-center bg-light" style="width:80px; height:50px;">
                                            <i class="bi bi-card-image text-muted fs-4"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">Visiting Card(s)</div>
                                        <div class="text-muted x-small">Capture or upload multiple images</div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('card-input-${index}').click()">
                                        <i class="bi bi-camera-fill me-1"></i>Capture Card
                                    </button>
                                    <input type="file" name="contacts[${index}][card_file][]" id="card-input-${index}" class="d-none card-input" accept="image/*" capture="environment" multiple data-preview="#card-preview-${index}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            contactContainer.insertAdjacentHTML('beforeend', html);
        });

        // F2 Shortcut for Designations
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F2') {
                const focused = document.activeElement;
                if (focused && focused.classList.contains('designation-select')) {
                    e.preventDefault();
                    lastFocusedDesignation = focused;
                    const modal = new bootstrap.Modal(document.getElementById('addDesignationModal'));
                    modal.show();
                    setTimeout(() => document.getElementById('new-designation-input').focus(), 500);
                }
            }
        });

        let lastFocusedDesignation = null;
        const saveDesignationBtn = document.getElementById('save-designation-btn');
        if (saveDesignationBtn) {
            saveDesignationBtn.addEventListener('click', function() {
                const input = document.getElementById('new-designation-input');
                const val = input.value.trim();
                if (val) {
                    // Add to all designation selects
                    const allSelects = document.querySelectorAll('.designation-select');
                    allSelects.forEach(sel => {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = val;
                        sel.appendChild(opt);
                    });
                    
                    if (lastFocusedDesignation) {
                        lastFocusedDesignation.value = val;
                    }
                    
                    input.value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addDesignationModal')).hide();
                }
            });
        }

        contactContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-contact')) {
                e.target.closest('.contact-item').remove();
                updateMeetingWithDropdown();
            }
        });

        // Sync Contact Names to Meeting Dropdown
        contactContainer.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[name]')) {
                updateMeetingWithDropdown();
            }
        });

        function updateMeetingWithDropdown() {
            const meetingDropdown = document.getElementById('meeting-with-contact');
            if (!meetingDropdown) return;
            
            const currentVal = meetingDropdown.value;
            meetingDropdown.innerHTML = '<option value="">Select Contact Person</option>';
            
            const nameInputs = contactContainer.querySelectorAll('input[name*="[name]"]');
            nameInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    const opt = document.createElement('option');
                    opt.value = input.value;
                    opt.textContent = input.value;
                    if (input.value === currentVal) opt.selected = true;
                    meetingDropdown.appendChild(opt);
                }
            });
        }
    }

    // --- Google Maps Integration ---
    // Placeholder for Map logic. In a real app, this would use Google Maps JS API.
    const fetchLocationBtn = document.getElementById('fetch-location-btn');
    if (fetchLocationBtn) {
        fetchLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                fetchLocationBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Fetching...';
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    document.getElementById('google_location').value = `${lat}, ${lng}`;
                    
                    const mapsLink = `https://www.google.com/maps?q=${lat},${lng}`;
                    const mapsLinkInput = document.getElementById('google_maps_link');
                    if (mapsLinkInput) mapsLinkInput.value = mapsLink;

                    // Reverse Geocoding using Nominatim (OpenStreetMap)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.address) {
                                const addr = data.address;
                                const fullAddr = data.display_name;
                                
                                // Populate Google Address field
                                if (document.getElementById('google_address')) {
                                    document.getElementById('google_address').value = fullAddr;
                                }

                                // Populate standard address fields
                                if (document.getElementsByName('area')[0]) {
                                    document.getElementsByName('area')[0].value = addr.neighbourhood || addr.suburb || addr.commercial || '';
                                }
                                if (document.getElementsByName('city')[0]) {
                                    document.getElementsByName('city')[0].value = addr.city || addr.town || addr.village || '';
                                }
                                if (document.getElementsByName('state')[0]) {
                                    document.getElementsByName('state')[0].value = addr.state || '';
                                }
                                if (document.getElementsByName('pincode')[0]) {
                                    document.getElementsByName('pincode')[0].value = addr.postcode || '';
                                }
                            }
                        })
                        .catch(err => console.error("Geocoding error:", err));

                    fetchLocationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Fetched';
                    fetchLocationBtn.classList.add('text-success');
                }, function(error) {
                    alert("Error fetching location: " + error.message);
                    fetchLocationBtn.innerHTML = '<i class="bi bi-geo-alt me-1"></i>Fetch GPS & Address';
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    }

    // --- Collapsible Sections Toggle ---
    const sectionHeaders = document.querySelectorAll('.form-section-title.collapsible');
    sectionHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i.bi-chevron-down, i.bi-chevron-up');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                if (icon) { icon.classList.replace('bi-chevron-down', 'bi-chevron-up'); }
            } else {
                content.style.display = 'none';
                if (icon) { icon.classList.replace('bi-chevron-up', 'bi-chevron-down'); }
            }
        });
    });

    // --- File Preview Logic ---
    const documentInput = document.getElementById('documentInput');
    const cameraInput = document.getElementById('cameraInput');
    const previewContainer = document.getElementById('file-preview-container');

    if (previewContainer) {
        // Use the globally defined saveDocsBtn if available, or fetch it here
        const saveDocsBtn = document.getElementById('save-docs-btn');
        const handleFiles = (files) => {
            if (!files) return;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const col = document.createElement('div');
                col.className = 'col-4 col-md-3';
                
                const isImage = file.type.startsWith('image/');
                const reader = new FileReader();
                
                reader.onload = (e) => {
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative">
                            <div class="position-absolute top-0 end-0 p-1" style="z-index:10">
                                <button type="button" class="btn btn-xs btn-danger rounded-circle p-1 leading-none shadow remove-file-btn" style="width:20px;height:20px;">
                                    <i class="bi bi-x x-small"></i>
                                </button>
                            </div>
                            <div class="ratio ratio-1x1 bg-light">
                                ${isImage ? 
                                    `<img src="${e.target.result}" style="object-fit:cover;">` : 
                                    `<div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                        <i class="bi bi-file-earmark-text fs-3"></i>
                                        <span class="x-small mt-1">${file.name.substring(0, 8)}...</span>
                                     </div>`
                                }
                            </div>
                        </div>
                    `;
                    col.querySelector('.remove-file-btn').onclick = () => {
                        col.remove();
                        if (previewContainer.children.length === 0) {
                            if (saveDocsBtn) {
                                saveDocsBtn.disabled = true;
                                saveDocsBtn.classList.add('disabled');
                            }
                        }
                    };
                    previewContainer.appendChild(col);
                };
                
                if (isImage) reader.readAsDataURL(file);
                else reader.onload({target: {result: null}});
            }

            // Enable button immediately when files are handled (Only in EDIT mode)
            const leadIdInput = document.querySelector('input[name="id"]');
            const hasLeadId = leadIdInput && parseInt(leadIdInput.value) > 0;

            if (files.length > 0 && saveDocsBtn && hasLeadId) {
                saveDocsBtn.disabled = false;
                saveDocsBtn.classList.remove('disabled');
            } else if (files.length > 0 && saveDocsBtn) {
                // In Create mode, we don't enable the independent save button
                saveDocsBtn.title = "Please save the lead first to enable independent document upload";
            }
        };

        if (documentInput) documentInput.addEventListener('change', (e) => handleFiles(e.target.files));
        if (cameraInput) cameraInput.addEventListener('change', (e) => handleFiles(e.target.files));
    }

    // Reset forms when modals are opened (unless it's an EDIT modal)
    document.addEventListener('show.bs.modal', function (event) {
        const modal = event.target;
        if (modal.id && modal.id.toLowerCase().includes('edit')) return;
        
        const form = modal.querySelector('form');
        if (form && !form.dataset.noReset) {
            form.reset();
            // Reset select2 if applicable
            if (typeof jQuery !== 'undefined') {
                $(form).find('select').trigger('change');
            }
        }
    });

    // AJAX for Add Contact Modal (View Page)
    const addContactForm = document.getElementById('add-contact-form');
    if (addContactForm) {
        addContactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
            submitBtn.disabled = true;

            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                alert('Request failed');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // AJAX for Add Meeting Modal (View Page)
    const addMeetingForm = document.getElementById('add-meeting-form');
    if (addMeetingForm) {
        addMeetingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
            submitBtn.disabled = true;

            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                alert('Request failed');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // AJAX Save Logic for the inline "Save Lead" button (type="button", id="inline-save-btn")
    // This button lives in the Contact Person section of create.php and edit.php.
    // It is type="button" (NOT type="submit") so no native form submission occurs — only this handler fires.
    const inlineSaveBtn = document.getElementById('inline-save-btn');
    if (inlineSaveBtn) {
        inlineSaveBtn.addEventListener('click', function() {
            const form = document.getElementById('lead-form');
            if (!form) return;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
            this.disabled = true;

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.lead_id) {
                        // CREATE mode: redirect to the new lead view page — prevents any re-submission
                        window.location.href = 'view.php?id=' + data.lead_id;
                    } else {
                        // EDIT mode: show brief success feedback, stay on form
                        this.innerHTML = '<i class="bi bi-check-all me-1"></i>Saved!';
                        this.classList.replace('btn-success', 'btn-outline-success');
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.replace('btn-outline-success', 'btn-success');
                            this.disabled = false;
                        }, 2500);
                    }
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(err => {
                alert('Error saving lead: ' + err.message);
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }

    // Visiting Card Preview Logic (Delegated)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('card-input')) {
            const input = e.target;
            const previewId = input.dataset.preview;
            const previewContainer = document.querySelector(previewId);
            
            if (input.files && input.files.length > 0 && previewContainer) {
                // Keep existing previews (those from DB) and append new ones? 
                // Or clear and show only new ones? For "Add New" button flow, clearing is easier to understand.
                const existingImages = previewContainer.querySelectorAll('img').length;
                
                // If it's a fresh selection, we might want to clear previous *new* previews but keep *existing* ones?
                // Actually, for simplicity, let's just append new ones if they are capturing more.
                // But the input.files will contain the full set of files for that specific input change.
                
                // Clear only the placeholder if it exists
                const placeholder = previewContainer.querySelector('.bi-card-image');
                if (placeholder) previewContainer.innerHTML = '';

                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(ex) {
                        const div = document.createElement('div');
                        div.className = 'card-preview-box border rounded d-flex align-items-center justify-content-center bg-light';
                        div.style.cssText = 'width:80px; height:50px; overflow:hidden;';
                        div.innerHTML = `<img src="${ex.target.result}" style="width:100%; height:100%; object-fit: cover;">`;
                        previewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    });
});

// Global function for document upload (called from onclick for maximum reliability)
function handleDocumentUpload(btn) {
    const form = btn.closest('form') || document.getElementById('lead-form');
    if (!form) {
        alert('Critical Error: Lead form not found.');
        return;
    }

    if (btn.disabled) return;

    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving Documents...';
    btn.disabled = true;

    const formData = new FormData(form);

    fetch('save_docs_ajax.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async res => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error('Server returned invalid data: ' + text.substring(0, 100));
        }
    })
    .then(data => {
        if (data.status === 'success') {
            btn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Documents Saved!';
            btn.classList.replace('btn-success', 'btn-primary');
            
            // Append new docs to gallery
            const existingGallery = document.querySelector('.existing-docs .row');
            if (existingGallery && data.new_docs) {
                data.new_docs.forEach(doc => {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-md-3';
                    col.innerHTML = `
                        <a href="${doc.path}" target="_blank" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative">
                                <div class="ratio ratio-1x1 bg-light">
                                    ${doc.is_image ? 
                                        `<img src="${doc.path}" style="object-fit:cover;">` : 
                                        `<div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <i class="bi bi-file-earmark-pdf fs-3"></i>
                                            <span class="x-small mt-1">PDF</span>
                                        </div>`
                                    }
                                </div>
                            </div>
                        </a>
                    `;
                    existingGallery.prepend(col);
                });
                const existingDocsSection = document.querySelector('.existing-docs');
                if (existingDocsSection) existingDocsSection.classList.remove('d-none');
            }

            // Reset after delay
            setTimeout(() => {
                const pContainer = document.getElementById('file-preview-container');
                if (pContainer) pContainer.innerHTML = '';
                btn.innerHTML = originalText;
                btn.disabled = true;
                btn.classList.add('disabled');
                btn.classList.replace('btn-primary', 'btn-success');
                // Reset file inputs
                const dInput = document.getElementById('documentInput');
                const cInput = document.getElementById('cameraInput');
                if (dInput) dInput.value = '';
                if (cInput) cInput.value = '';
            }, 2500);
        } else {
            alert('Upload Error: ' + data.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.classList.remove('disabled');
        }
    })
    .catch(err => {
        alert('CRITICAL ERROR: ' + err.message);
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.remove('disabled');
    });
}
