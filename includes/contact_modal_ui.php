<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="contactForm" enctype="multipart/form-data">
      <input type="hidden" name="id" id="contact_id">
      <input type="hidden" name="lead_id" id="contact_lead_id" value="<?= isset($id) ? $id : '' ?>">
      <input type="hidden" name="mode" id="contact_form_mode" value="create">
      <input type="hidden" name="existing_cards" id="contact_existing_cards">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contactModalLabel">Add Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12 mb-2 bg-light p-3 rounded border">
              <label class="form-label fw-bold text-primary mb-1"><i class="bi bi-search me-1"></i>Search Existing Master Contact</label>
              <div class="position-relative">
                <input type="text" id="contact_search" class="form-control border-primary" placeholder="Type name, mobile, email or organization to search..." autocomplete="off">
                <div id="contact_search_results" class="list-group position-absolute w-100 shadow" style="display:none; z-index:1050; max-height: 250px; overflow-y:auto;"></div>
              </div>
              <small class="text-muted mt-1 d-block">Selecting an existing contact will link it to this lead.</small>
            </div>
            
            <div class="col-12"><hr class="my-0"></div>
            
            <div class="col-12">
              <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="contact_name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Contact Type <span class="text-danger">*</span></label>
              <select name="contact_type" id="contact_contact_type" class="form-select" required>
                <?php foreach($contactTypes as $type): ?>
                  <option value="<?= e($type) ?>"><?= e($type) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Mobile <span class="text-danger">*</span></label>
              <input type="text" name="mobile" id="contact_mobile" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">WhatsApp</label>
              <input type="text" name="whatsapp" id="contact_whatsapp" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" id="contact_email" class="form-control">
            </div>

            <div class="col-12"><hr class="my-2"><h6 class="fw-bold mb-0">Organization Details (Optional)</h6></div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Organization Name</label>
              <input type="text" name="organization_name" id="contact_organization_name" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Website</label>
              <input type="url" name="website" id="contact_website" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Address</label>
              <textarea name="address" id="contact_address" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">City</label>
              <input type="text" name="city" id="contact_city" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">State</label>
              <input type="text" name="state" id="contact_state" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Pincode</label>
              <input type="text" name="pincode" id="contact_pincode" class="form-control">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold d-block">Visiting Card (Select multiple if needed)</label>
              <div class="d-flex gap-2 mb-2">
                <label class="upload-btn-label flex-grow-1 text-center">
                  <i class="bi bi-folder2"></i>Upload from Device
                  <input type="file" name="visiting_card[]" id="contact_visiting_card_device" class="d-none" multiple accept="image/*" onchange="previewCardFiles(this)">
                </label>
                <label class="upload-btn-label flex-grow-1 text-center">
                  <i class="bi bi-camera"></i>Take Photo
                  <input type="file" name="visiting_card[]" id="contact_visiting_card_camera" class="d-none" multiple accept="image/*" capture="environment" onchange="previewCardFiles(this)">
                </label>
              </div>
              <div id="visiting_cards_new_preview" class="d-flex gap-2 flex-wrap mb-2"></div>
              <div id="existing_cards_preview" class="mt-2 d-flex gap-2 flex-wrap"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="contactSubmitBtn">Save Contact</button>
        </div>
      </div>
    </form>
  </div>
</div>
