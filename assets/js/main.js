/* SushobhaCRM — Main JavaScript */
'use strict';

// ── Sidebar toggle (mobile) ───────────────────────────────────
const sidebar = document.getElementById('sidebar');
const overlay = document.querySelector('.sidebar-overlay');
const hamburger = document.getElementById('hamburgerBtn');
const sidebarClose = document.getElementById('sidebarClose');

function openSidebar() {
  sidebar?.classList.add('show');
  overlay?.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  sidebar?.classList.remove('show');
  overlay?.classList.remove('show');
  document.body.style.overflow = '';
}
hamburger?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

// Auto-close sidebar when a menu link is tapped on mobile
document.querySelectorAll('#sidebar .menu-item').forEach(link => {
  link.addEventListener('click', () => {
    if (window.innerWidth < 992) closeSidebar();
  });
});

// ── Smart Mobile Back Button ───────────────────────────────────
// Detects if current page is a form/view page (not an index) and
// injects a back arrow into the topbar on mobile screens.
(function injectMobileBackBtn() {
  const path = window.location.pathname;
  // Pages that are "detail/form" pages — not list index pages
  const isDetailPage = /\/(create|edit|view|compare|add|update|detail)\.php/.test(path) ||
                       /\/(travels|quotations|orders|invoices|products|vendors|customers|contacts|projects|tasks|purchases|receipts|payments|prospects|employees|salary|reports|settings|approvals)\/((?!index\.php).)*\.php/.test(path);

  if (!isDetailPage) return;

  const topbar = document.querySelector('.topbar');
  if (!topbar) return;

  const backBtn = document.createElement('button');
  backBtn.id = 'mobileBackBtn';
  backBtn.className = 'mobile-back-btn d-lg-none';
  backBtn.setAttribute('aria-label', 'Go back');
  backBtn.innerHTML = '<i class="bi bi-arrow-left"></i>';
  backBtn.addEventListener('click', () => {
    // Navigate back to the parent list page
    const referrer = document.referrer;
    // If there's valid history within our app, go back
    if (referrer && referrer.includes(window.location.host) && !referrer.includes(path)) {
      history.back();
    } else {
      // Fallback: go to parent index
      const match = path.match(/\/modules\/([^/]+)\//);
      if (match) {
        window.location.href = window.location.origin + '/modules/' + match[1] + '/index.php';
      } else {
        history.back();
      }
    }
  });

  // Insert back button BEFORE hamburger
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  if (hamburgerBtn) {
    topbar.insertBefore(backBtn, hamburgerBtn.nextSibling);
    // Hide hamburger on detail pages on mobile and show back btn instead
    hamburgerBtn.classList.add('d-none', 'd-lg-flex');
  } else {
    topbar.insertBefore(backBtn, topbar.firstChild);
  }
})();

// ── AJAX Table Search ─────────────────────────────────────────
function initTableSearch(inputId, tableBodyId, searchUrl) {
  const input = document.getElementById(inputId);
  if (!input) return;
  let timer;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      fetch(searchUrl + '?q=' + encodeURIComponent(input.value))
        .then(r => r.json())
        .then(data => {
          const tbody = document.getElementById(tableBodyId);
          if (tbody && data.html) tbody.innerHTML = data.html;
        })
        .catch(() => {});
    }, 300);
  });
}

// ── Confirm Delete ────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('[data-confirm]');
  if (!btn) return;
  e.preventDefault();
  const msg = btn.dataset.confirm || 'Are you sure you want to delete this record?';
  if (confirm(msg)) {
    const href = btn.href || btn.dataset.href;
    if (href) window.location.href = href;
    else if (btn.closest('form')) btn.closest('form').submit();
  }
});

// ── Flash auto-dismiss ────────────────────────────────────────
setTimeout(() => {
  document.querySelectorAll('.alert-dismissible').forEach(el => {
    const bs = bootstrap.Alert.getOrCreateInstance(el);
    bs?.close();
  });
}, 4000);

// ── Quotation / Order Line-Item Builder ───────────────────────
const lineItemsTable = document.getElementById('lineItemsTable');
if (lineItemsTable) {
  // Add row
  document.getElementById('addLineItem')?.addEventListener('click', addLineItemRow);
  // Remove row (delegated)
  lineItemsTable.addEventListener('click', e => {
    if (e.target.closest('.remove-line-item')) {
      e.target.closest('tr').remove();
      recalcTotals();
    }
  });
  // Recalc on change
  lineItemsTable.addEventListener('input', e => {
    const row = e.target.closest('tr');
    if (row) recalcRow(row);
    recalcTotals();
  });
  // Product select auto-fill
  lineItemsTable.addEventListener('change', e => {
    const sel = e.target.closest('.product-select');
    if (!sel) return;
    const row = sel.closest('tr');
    const opt = sel.selectedOptions[0];
    if (opt && opt.dataset.price) {
      row.querySelector('.item-price').value  = parseFloat(opt.dataset.price).toFixed(2);
      row.querySelector('.item-tax').value    = parseFloat(opt.dataset.tax || 0).toFixed(2);
      row.querySelector('.item-unit').value   = opt.dataset.unit || 'Nos';
    }
    recalcRow(row);
    recalcTotals();
  });
}

function addLineItemRow() {
  const tbody = lineItemsTable.querySelector('tbody');
  const idx = tbody.querySelectorAll('tr').length;
  const productsData = window.CRM_PRODUCTS || [];
  let opts = '<option value="">-- Select Product --</option>';
  productsData.forEach(p => {
    opts += `<option value="${p.id}" data-price="${p.selling_price}" data-tax="${p.tax_rate}" data-unit="${p.unit}">${p.name}</option>`;
  });
  const row = document.createElement('tr');
  row.innerHTML = `
    <td><select class="form-select form-select-sm product-select" name="items[${idx}][product_id]">${opts}</select></td>
    <td><input type="text" class="form-control form-control-sm item-desc" name="items[${idx}][description]" placeholder="Description" required></td>
    <td><input type="text" class="form-control form-control-sm item-unit" name="items[${idx}][unit]" value="Nos" style="width:60px"></td>
    <td><input type="number" class="form-control form-control-sm item-qty" name="items[${idx}][qty]" value="1" min="0.01" step="0.01" style="width:70px"></td>
    <td><input type="number" class="form-control form-control-sm item-price" name="items[${idx}][unit_price]" value="0.00" step="0.01" style="width:100px"></td>
    <td><input type="number" class="form-control form-control-sm item-tax" name="items[${idx}][tax_rate]" value="18" step="0.01" style="width:65px"></td>
    <td class="item-total fw-semibold">0.00</td>
    <td><button type="button" class="btn btn-sm btn-danger btn-icon remove-line-item"><i class="bi bi-trash"></i></button></td>`;
  tbody.appendChild(row);
  recalcRow(row);
}

function recalcRow(row) {
  const qty   = parseFloat(row.querySelector('.item-qty')?.value)   || 0;
  const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
  const tax   = parseFloat(row.querySelector('.item-tax')?.value)   || 0;
  const taxAmt = (qty * price * tax) / 100;
  const total  = (qty * price) + taxAmt;
  const totalCell = row.querySelector('.item-total');
  if (totalCell) totalCell.textContent = total.toFixed(2);
  return { qty, price, tax, taxAmt, total };
}

function recalcTotals() {
  if (!lineItemsTable) return;
  let subtotal = 0, taxTotal = 0;
  lineItemsTable.querySelectorAll('tbody tr').forEach(row => {
    const r = recalcRow(row);
    subtotal += r.qty * r.price;
    taxTotal += r.taxAmt;
  });
  const discountType = document.getElementById('discountType')?.value || 'fixed';
  const discountVal  = parseFloat(document.getElementById('discountValue')?.value) || 0;
  let discountAmt = discountType === 'percent' ? (subtotal * discountVal / 100) : discountVal;
  const grand = subtotal + taxTotal - discountAmt;

  setText('calcSubtotal',   subtotal.toFixed(2));
  setText('calcTax',        taxTotal.toFixed(2));
  setText('calcDiscount',   discountAmt.toFixed(2));
  setText('calcTotal',      grand.toFixed(2));
  setValue('hiddenSubtotal', subtotal.toFixed(2));
  setValue('hiddenTax',      taxTotal.toFixed(2));
  setValue('hiddenDiscount', discountAmt.toFixed(2));
  setValue('hiddenTotal',    grand.toFixed(2));
}

function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}
function setValue(id, val) {
  const el = document.getElementById(id);
  if (el) el.value = val;
}

// Trigger recalc on discount change (only if lineItemsTable is active)
if (lineItemsTable) {
  document.getElementById('discountValue')?.addEventListener('input', recalcTotals);
  document.getElementById('discountType')?.addEventListener('change', recalcTotals);
}

// ── Tooltips init ─────────────────────────────────────────────
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
  new bootstrap.Tooltip(el);
});

// ── Global CSV Export ─────────────────────────────────────────
window.exportTableToCSV = function(tableId, filename) {
  const table = document.getElementById(tableId);
  if (!table) return;
  let csv = [];
  table.querySelectorAll('tr').forEach(row => {
    const cols = [...row.querySelectorAll('th,td')].map(c => '"' + c.textContent.trim().replace(/"/g,'""') + '"');
    csv.push(cols.join(','));
  });
  const blob = new Blob([csv.join('\n')], {type: 'text/csv'});
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename || 'export.csv';
  link.click();
};

// ── Global Image Hover Previews ────────────────────────────────
document.addEventListener('mouseover', function(e) {
  const target = e.target.closest('img, .visiting-card-thumb-link, .doc-thumb, .saved-doc-thumb, [data-hover-preview]');
  if (!target) return;
  
  // Find the image URL
  let imgUrl = '';
  if (target.tagName === 'IMG') {
    imgUrl = target.src;
  } else if (target.querySelector('img')) {
    imgUrl = target.querySelector('img').src;
  } else if (target.dataset.hoverPreview) {
    imgUrl = target.dataset.hoverPreview;
  }
  
  if (!imgUrl || imgUrl.endsWith('.pdf') || imgUrl.endsWith('.docx') || imgUrl.endsWith('.xlsx')) return;

  // Create the global hover card if it doesn't exist
  let previewDiv = document.getElementById('global-image-hover-preview');
  if (!previewDiv) {
    previewDiv = document.createElement('div');
    previewDiv.id = 'global-image-hover-preview';
    previewDiv.style.cssText = `
      position: fixed;
      display: none;
      pointer-events: none;
      z-index: 99999;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      padding: 8px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: opacity 0.15s ease, transform 0.15s ease;
      opacity: 0;
      transform: scale(0.95);
    `;
    const previewImg = document.createElement('img');
    previewImg.id = 'global-image-hover-preview-img';
    previewImg.style.cssText = `
      max-width: 480px;
      max-height: 360px;
      border-radius: 8px;
      display: block;
      object-fit: contain;
    `;
    const previewMeta = document.createElement('div');
    previewMeta.id = 'global-image-hover-preview-meta';
    previewMeta.style.cssText = `
      font-size: 11px;
      color: #64748b;
      margin-top: 6px;
      text-align: center;
      font-weight: 600;
    `;
    previewDiv.appendChild(previewImg);
    previewDiv.appendChild(previewMeta);
    document.body.appendChild(previewDiv);
  }

  const previewImg = document.getElementById('global-image-hover-preview-img');
  const previewMeta = document.getElementById('global-image-hover-preview-meta');
  
  previewImg.src = imgUrl;
  
  // Set dimensions text when image loads
  previewImg.onload = function() {
    previewMeta.textContent = `${this.naturalWidth} × ${this.naturalHeight}px`;
  };

  previewDiv.style.display = 'block';
  // Force reflow
  previewDiv.offsetHeight;
  previewDiv.style.opacity = '1';
  previewDiv.style.transform = 'scale(1)';

  function moveHandler(evt) {
    const previewDiv = document.getElementById('global-image-hover-preview');
    if (!previewDiv) return;
    
    // Position it to the right of the cursor, or left if not enough space
    const padding = 15;
    let x = evt.clientX + padding;
    let y = evt.clientY + padding;
    
    const rect = previewDiv.getBoundingClientRect();
    if (x + rect.width > window.innerWidth) {
      x = evt.clientX - rect.width - padding;
    }
    if (y + rect.height > window.innerHeight) {
      y = evt.clientY - rect.height - padding;
    }
    
    previewDiv.style.left = x + 'px';
    previewDiv.style.top = y + 'px';
  }

  document.addEventListener('mousemove', moveHandler);

  target.addEventListener('mouseleave', function onLeave() {
    target.removeEventListener('mouseleave', onLeave);
    document.removeEventListener('mousemove', moveHandler);
    const previewDiv = document.getElementById('global-image-hover-preview');
    if (previewDiv) {
      previewDiv.style.opacity = '0';
      previewDiv.style.transform = 'scale(0.95)';
      setTimeout(() => {
        if (previewDiv.style.opacity === '0') {
          previewDiv.style.display = 'none';
        }
      }, 150);
    }
  }, { once: true });
});


// ── Sidebar toggle (mobile) ───────────────────────────────────
const sidebar = document.getElementById('sidebar');
const overlay = document.querySelector('.sidebar-overlay');
const hamburger = document.getElementById('hamburgerBtn');
const sidebarClose = document.getElementById('sidebarClose');

function openSidebar() {
  sidebar?.classList.add('show');
  overlay?.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  sidebar?.classList.remove('show');
  overlay?.classList.remove('show');
  document.body.style.overflow = '';
}
hamburger?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

// ── AJAX Table Search ─────────────────────────────────────────
function initTableSearch(inputId, tableBodyId, searchUrl) {
  const input = document.getElementById(inputId);
  if (!input) return;
  let timer;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      fetch(searchUrl + '?q=' + encodeURIComponent(input.value))
        .then(r => r.json())
        .then(data => {
          const tbody = document.getElementById(tableBodyId);
          if (tbody && data.html) tbody.innerHTML = data.html;
        })
        .catch(() => {});
    }, 300);
  });
}

// ── Confirm Delete ────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('[data-confirm]');
  if (!btn) return;
  e.preventDefault();
  const msg = btn.dataset.confirm || 'Are you sure you want to delete this record?';
  if (confirm(msg)) {
    const href = btn.href || btn.dataset.href;
    if (href) window.location.href = href;
    else if (btn.closest('form')) btn.closest('form').submit();
  }
});

// ── Flash auto-dismiss ────────────────────────────────────────
setTimeout(() => {
  document.querySelectorAll('.alert-dismissible').forEach(el => {
    const bs = bootstrap.Alert.getOrCreateInstance(el);
    bs?.close();
  });
}, 4000);

// ── Quotation / Order Line-Item Builder ───────────────────────
const lineItemsTable = document.getElementById('lineItemsTable');
if (lineItemsTable) {
  // Add row
  document.getElementById('addLineItem')?.addEventListener('click', addLineItemRow);
  // Remove row (delegated)
  lineItemsTable.addEventListener('click', e => {
    if (e.target.closest('.remove-line-item')) {
      e.target.closest('tr').remove();
      recalcTotals();
    }
  });
  // Recalc on change
  lineItemsTable.addEventListener('input', e => {
    const row = e.target.closest('tr');
    if (row) recalcRow(row);
    recalcTotals();
  });
  // Product select auto-fill
  lineItemsTable.addEventListener('change', e => {
    const sel = e.target.closest('.product-select');
    if (!sel) return;
    const row = sel.closest('tr');
    const opt = sel.selectedOptions[0];
    if (opt && opt.dataset.price) {
      row.querySelector('.item-price').value  = parseFloat(opt.dataset.price).toFixed(2);
      row.querySelector('.item-tax').value    = parseFloat(opt.dataset.tax || 0).toFixed(2);
      row.querySelector('.item-unit').value   = opt.dataset.unit || 'Nos';
    }
    recalcRow(row);
    recalcTotals();
  });
}

function addLineItemRow() {
  const tbody = lineItemsTable.querySelector('tbody');
  const idx = tbody.querySelectorAll('tr').length;
  const productsData = window.CRM_PRODUCTS || [];
  let opts = '<option value="">-- Select Product --</option>';
  productsData.forEach(p => {
    opts += `<option value="${p.id}" data-price="${p.selling_price}" data-tax="${p.tax_rate}" data-unit="${p.unit}">${p.name}</option>`;
  });
  const row = document.createElement('tr');
  row.innerHTML = `
    <td><select class="form-select form-select-sm product-select" name="items[${idx}][product_id]">${opts}</select></td>
    <td><input type="text" class="form-control form-control-sm item-desc" name="items[${idx}][description]" placeholder="Description" required></td>
    <td><input type="text" class="form-control form-control-sm item-unit" name="items[${idx}][unit]" value="Nos" style="width:60px"></td>
    <td><input type="number" class="form-control form-control-sm item-qty" name="items[${idx}][qty]" value="1" min="0.01" step="0.01" style="width:70px"></td>
    <td><input type="number" class="form-control form-control-sm item-price" name="items[${idx}][unit_price]" value="0.00" step="0.01" style="width:100px"></td>
    <td><input type="number" class="form-control form-control-sm item-tax" name="items[${idx}][tax_rate]" value="18" step="0.01" style="width:65px"></td>
    <td class="item-total fw-semibold">0.00</td>
    <td><button type="button" class="btn btn-sm btn-danger btn-icon remove-line-item"><i class="bi bi-trash"></i></button></td>`;
  tbody.appendChild(row);
  recalcRow(row);
}

function recalcRow(row) {
  const qty   = parseFloat(row.querySelector('.item-qty')?.value)   || 0;
  const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
  const tax   = parseFloat(row.querySelector('.item-tax')?.value)   || 0;
  const taxAmt = (qty * price * tax) / 100;
  const total  = (qty * price) + taxAmt;
  const totalCell = row.querySelector('.item-total');
  if (totalCell) totalCell.textContent = total.toFixed(2);
  return { qty, price, tax, taxAmt, total };
}

function recalcTotals() {
  if (!lineItemsTable) return;
  let subtotal = 0, taxTotal = 0;
  lineItemsTable.querySelectorAll('tbody tr').forEach(row => {
    const r = recalcRow(row);
    subtotal += r.qty * r.price;
    taxTotal += r.taxAmt;
  });
  const discountType = document.getElementById('discountType')?.value || 'fixed';
  const discountVal  = parseFloat(document.getElementById('discountValue')?.value) || 0;
  let discountAmt = discountType === 'percent' ? (subtotal * discountVal / 100) : discountVal;
  const grand = subtotal + taxTotal - discountAmt;

  setText('calcSubtotal',   subtotal.toFixed(2));
  setText('calcTax',        taxTotal.toFixed(2));
  setText('calcDiscount',   discountAmt.toFixed(2));
  setText('calcTotal',      grand.toFixed(2));
  setValue('hiddenSubtotal', subtotal.toFixed(2));
  setValue('hiddenTax',      taxTotal.toFixed(2));
  setValue('hiddenDiscount', discountAmt.toFixed(2));
  setValue('hiddenTotal',    grand.toFixed(2));
}

function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}
function setValue(id, val) {
  const el = document.getElementById(id);
  if (el) el.value = val;
}

// Trigger recalc on discount change (only if lineItemsTable is active)
if (lineItemsTable) {
  document.getElementById('discountValue')?.addEventListener('input', recalcTotals);
  document.getElementById('discountType')?.addEventListener('change', recalcTotals);
}

// ── Tooltips init ─────────────────────────────────────────────
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
  new bootstrap.Tooltip(el);
});

// ── Global CSV Export ─────────────────────────────────────────
window.exportTableToCSV = function(tableId, filename) {
  const table = document.getElementById(tableId);
  if (!table) return;
  let csv = [];
  table.querySelectorAll('tr').forEach(row => {
    const cols = [...row.querySelectorAll('th,td')].map(c => '"' + c.textContent.trim().replace(/"/g,'""') + '"');
    csv.push(cols.join(','));
  });
  const blob = new Blob([csv.join('\n')], {type: 'text/csv'});
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename || 'export.csv';
  link.click();
};

// ── Global Image Hover Previews ────────────────────────────────
document.addEventListener('mouseover', function(e) {
  const target = e.target.closest('img, .visiting-card-thumb-link, .doc-thumb, .saved-doc-thumb, [data-hover-preview]');
  if (!target) return;
  
  // Find the image URL
  let imgUrl = '';
  if (target.tagName === 'IMG') {
    imgUrl = target.src;
  } else if (target.querySelector('img')) {
    imgUrl = target.querySelector('img').src;
  } else if (target.dataset.hoverPreview) {
    imgUrl = target.dataset.hoverPreview;
  }
  
  if (!imgUrl || imgUrl.endsWith('.pdf') || imgUrl.endsWith('.docx') || imgUrl.endsWith('.xlsx')) return;

  // Create the global hover card if it doesn't exist
  let previewDiv = document.getElementById('global-image-hover-preview');
  if (!previewDiv) {
    previewDiv = document.createElement('div');
    previewDiv.id = 'global-image-hover-preview';
    previewDiv.style.cssText = `
      position: fixed;
      display: none;
      pointer-events: none;
      z-index: 99999;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      padding: 8px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: opacity 0.15s ease, transform 0.15s ease;
      opacity: 0;
      transform: scale(0.95);
    `;
    const previewImg = document.createElement('img');
    previewImg.id = 'global-image-hover-preview-img';
    previewImg.style.cssText = `
      max-width: 480px;
      max-height: 360px;
      border-radius: 8px;
      display: block;
      object-fit: contain;
    `;
    const previewMeta = document.createElement('div');
    previewMeta.id = 'global-image-hover-preview-meta';
    previewMeta.style.cssText = `
      font-size: 11px;
      color: #64748b;
      margin-top: 6px;
      text-align: center;
      font-weight: 600;
    `;
    previewDiv.appendChild(previewImg);
    previewDiv.appendChild(previewMeta);
    document.body.appendChild(previewDiv);
  }

  const previewImg = document.getElementById('global-image-hover-preview-img');
  const previewMeta = document.getElementById('global-image-hover-preview-meta');
  
  previewImg.src = imgUrl;
  
  // Set dimensions text when image loads
  previewImg.onload = function() {
    previewMeta.textContent = `${this.naturalWidth} × ${this.naturalHeight}px`;
  };

  previewDiv.style.display = 'block';
  // Force reflow
  previewDiv.offsetHeight;
  previewDiv.style.opacity = '1';
  previewDiv.style.transform = 'scale(1)';

  function moveHandler(evt) {
    const previewDiv = document.getElementById('global-image-hover-preview');
    if (!previewDiv) return;
    
    // Position it to the right of the cursor, or left if not enough space
    const padding = 15;
    let x = evt.clientX + padding;
    let y = evt.clientY + padding;
    
    const rect = previewDiv.getBoundingClientRect();
    if (x + rect.width > window.innerWidth) {
      x = evt.clientX - rect.width - padding;
    }
    if (y + rect.height > window.innerHeight) {
      y = evt.clientY - rect.height - padding;
    }
    
    previewDiv.style.left = x + 'px';
    previewDiv.style.top = y + 'px';
  }

  document.addEventListener('mousemove', moveHandler);

  target.addEventListener('mouseleave', function onLeave() {
    target.removeEventListener('mouseleave', onLeave);
    document.removeEventListener('mousemove', moveHandler);
    const previewDiv = document.getElementById('global-image-hover-preview');
    if (previewDiv) {
      previewDiv.style.opacity = '0';
      previewDiv.style.transform = 'scale(0.95)';
      setTimeout(() => {
        if (previewDiv.style.opacity === '0') {
          previewDiv.style.display = 'none';
        }
      }, 150);
    }
  }, { once: true });
});

