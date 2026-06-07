/**
 * nav-guard.js  — SushobhaCRM Enterprise Navigation Protection
 * Version: 1.0.0
 *
 * Features:
 *  A. Dirty-form detection + beforeunload confirmation
 *  B. Session timeout warning (5-min ahead) + extend without reload
 *  C. Logout confirmation modal (warns if form is dirty)
 *  D. Mobile "Press Back Again to Exit" guard
 *  E. Auto-save drafts every 60 seconds
 *  F. Draft restore banner on page load
 *  G. Draft deletion on successful form submit
 */
'use strict';

(function () {

  // ── Config (injected by footer.php) ────────────────────────────────────────
  const CFG = window.CRM_CONFIG || {};
  const BASE_URL       = CFG.baseUrl       || '';
  const SESSION_TIMEOUT = CFG.sessionTimeout || 3600;
  const DRAFT_API      = BASE_URL + '/modules/auth/draft_handler.php';
  const PING_API       = BASE_URL + '/modules/auth/session_ping.php';
  const LOGIN_URL      = BASE_URL + '/modules/auth/login.php';
  const AUTOSAVE_INTERVAL  = 60000;  // 60 seconds
  const PING_INTERVAL      = 60000;  // 60 seconds
  const SESSION_WARN_SECS  = 300;    // warn 5 min before expiry
  const BACK_PRESS_WINDOW  = 3000;   // ms to detect double-back

  // ── State ──────────────────────────────────────────────────────────────────
  let dirtyForms        = new Set();
  let formSubmitting    = false;
  let sessionWarnShown  = false;
  let sessionInterval   = null;
  let autosaveInterval  = null;
  let backPressedOnce   = false;
  let backPressTimer    = null;
  let sessionCountdown  = null;

  // ── Utilities ──────────────────────────────────────────────────────────────
  function isMobile() {
    return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  }

  function formatTime(secs) {
    const m = Math.floor(secs / 60);
    const s = secs % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
  }

  function serializeForm(form) {
    const data = {};
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(el => {
      if (!el.name) return;
      if (el.type === 'file') return;           // Skip file inputs
      if (el.type === 'password') return;       // Skip passwords
      if (el.type === 'hidden' && el.name === 'csrf_token') return; // Skip CSRF
      if (el.type === 'checkbox' || el.type === 'radio') {
        if (el.checked) {
          if (!data[el.name]) data[el.name] = [];
          if (Array.isArray(data[el.name])) data[el.name].push(el.value);
          else data[el.name] = [el.value];
        }
      } else {
        data[el.name] = el.value;
      }
    });
    return JSON.stringify(data);
  }

  function restoreFormData(form, data) {
    if (!data || typeof data !== 'object') return;
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(el => {
      if (!el.name || el.type === 'file' || el.type === 'hidden') return;
      const val = data[el.name];
      if (val === undefined) return;
      if (el.type === 'checkbox') {
        el.checked = Array.isArray(val) ? val.includes(el.value) : el.value === val;
      } else if (el.type === 'radio') {
        el.checked = el.value === val;
      } else {
        el.value = val;
        // Trigger change for select fields that have listeners (e.g. customer selects)
        if (el.tagName === 'SELECT') {
          el.dispatchEvent(new Event('change', { bubbles: true }));
        }
      }
    });
  }

  function getDraftKey() {
    return window.location.pathname;
  }

  function getPageTitle() {
    return document.title.split('|')[0].trim();
  }

  // ── A. DIRTY FORM DETECTION ────────────────────────────────────────────────
  function initDirtyDetection() {
    const forms = document.querySelectorAll('form[data-guard], form[data-autosave]');
    forms.forEach(form => {
      const snapshot = serializeForm(form);
      form.dataset.snapshot = snapshot;

      const markDirty = () => {
        const current = serializeForm(form);
        if (current !== form.dataset.snapshot) {
          dirtyForms.add(form);
        } else {
          dirtyForms.delete(form);
        }
      };

      form.addEventListener('input',  markDirty);
      form.addEventListener('change', markDirty);

      // Clear dirty on submit
      form.addEventListener('submit', () => {
        formSubmitting = true;
        dirtyForms.delete(form);
        // Fire-and-forget: delete draft on successful submit
        deleteDraft();
      });
    });
  }

  window.isDirty = function() {
    return dirtyForms.size > 0;
  };

  // ── B. BEFOREUNLOAD GUARD ──────────────────────────────────────────────────
  function initBeforeUnload() {
    window.addEventListener('beforeunload', function (e) {
      if (formSubmitting || dirtyForms.size === 0) return;
      e.preventDefault();
      e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
      return e.returnValue;
    });
  }

  // ── C. LOGOUT CONFIRMATION MODAL ──────────────────────────────────────────
  function injectLogoutModal() {
    if (document.getElementById('navGuardLogoutModal')) return;
    const modal = document.createElement('div');
    modal.innerHTML = `
      <div class="modal fade" id="navGuardLogoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
              <div class="d-flex align-items-center gap-2">
                <div style="width:38px;height:38px;background:#fff3cd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-box-arrow-right text-warning fs-5"></i>
                </div>
                <div>
                  <h5 class="modal-title mb-0 fw-bold">Confirm Logout</h5>
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
              <p class="text-muted mb-0" id="logoutModalMsg">Are you sure you want to log out?</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                <i class="bi bi-x me-1"></i>Cancel
              </button>
              <a href="" id="navGuardLogoutConfirm" class="btn btn-warning btn-sm fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i>Yes, Logout
              </a>
            </div>
          </div>
        </div>
      </div>`;
    document.body.appendChild(modal.firstElementChild);
  }

  function initLogoutInterception() {
    injectLogoutModal();
    document.addEventListener('click', function(e) {
      const link = e.target.closest('a[href*="logout"], #logoutLink, [id*="logout"]');
      if (!link) return;
      const href = link.getAttribute('href');
      if (!href || !href.includes('logout')) return;

      e.preventDefault();
      const msg = document.getElementById('logoutModalMsg');
      if (msg) {
        msg.innerHTML = isDirty()
          ? '<span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>You have unsaved changes that will be lost.</span><br><span class="text-muted">Are you sure you want to log out?</span>'
          : 'Are you sure you want to log out?';
      }
      document.getElementById('navGuardLogoutConfirm').href = href;
      const modal = new bootstrap.Modal(document.getElementById('navGuardLogoutModal'));
      modal.show();
    });
  }

  // ── D. MOBILE BACK-BUTTON GUARD ────────────────────────────────────────────
  function injectBackConfirmModal() {
    if (document.getElementById('navGuardBackModal')) return;
    const modal = document.createElement('div');
    modal.innerHTML = `
      <div class="modal fade" id="navGuardBackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg" style="border-radius:14px;">
            <div class="modal-header border-0 pb-0">
              <div class="d-flex align-items-center gap-2">
                <div style="width:38px;height:38px;background:#fef2f2;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-box-arrow-left text-danger fs-5"></i>
                </div>
                <h5 class="modal-title fw-bold">Exit Application?</h5>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-2">
              <p class="text-muted mb-0" id="backModalMsg">Are you sure you want to go back? Any unsaved changes will be lost.</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Stay Here</button>
              <button type="button" class="btn btn-danger btn-sm fw-semibold" id="navGuardBackConfirmBtn">Yes, Exit</button>
            </div>
          </div>
        </div>
      </div>`;
    document.body.appendChild(modal.firstElementChild);

    document.getElementById('navGuardBackConfirmBtn').addEventListener('click', function() {
      // Allow navigation to proceed
      window.navGuardAllowBack = true;
      history.back();
      const m = bootstrap.Modal.getInstance(document.getElementById('navGuardBackModal'));
      if (m) m.hide();
    });
  }

  function initMobileBackGuard() {
    // We apply this to all pages, not just forms, to ensure consistent mobile exit behavior
    injectBackConfirmModal();

    // Browsers ignore pushState traps on initial load if there's no user interaction.
    // We push the state immediately, but also on the first touch/click to guarantee it sticks.
    let statePushed = false;
    const pushStateTrap = () => {
      if (!statePushed) {
        history.pushState({ navGuard: true }, '');
        statePushed = true;
      }
    };

    pushStateTrap(); // Try immediately
    document.addEventListener('touchstart', pushStateTrap, { once: true, passive: true });
    document.addEventListener('click', pushStateTrap, { once: true, passive: true });
    document.addEventListener('scroll', pushStateTrap, { once: true, passive: true });

    window.addEventListener('popstate', function(e) {
      if (formSubmitting || window.navGuardAllowBack) return;

      // Intercept the back button and maintain the trap
      history.pushState({ navGuard: true }, '');

      const msg = document.getElementById('backModalMsg');
      if (isDirty()) {
        msg.innerHTML = '<span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>You have unsaved changes!</span><br>Are you sure you want to exit without saving?';
      } else {
        msg.innerHTML = 'Are you sure you want to exit the application?';
      }

      const modal = new bootstrap.Modal(document.getElementById('navGuardBackModal'));
      modal.show();
    });
  }

  // ── E. AUTO-SAVE DRAFTS ────────────────────────────────────────────────────
  function initAutoSave() {
    const forms = document.querySelectorAll('form[data-autosave]');
    if (forms.length === 0) return;

    autosaveInterval = setInterval(() => {
      if (!isDirty()) return;
      const form = [...dirtyForms][0]; // Save the first dirty form
      if (!form) return;
      saveDraft(form, true);
    }, AUTOSAVE_INTERVAL);
  }

  function saveDraft(form, silent = false) {
    const key       = getDraftKey();
    const data      = serializeForm(form);
    const formTitle = getPageTitle();

    const body = new FormData();
    body.append('action',     'save');
    body.append('key',        key);
    body.append('data',       data);
    body.append('form_title', formTitle);

    fetch(DRAFT_API, { method: 'POST', body })
      .then(r => r.json())
      .then(resp => {
        if (resp.saved && !silent) {
          showDraftSavedBadge(resp.saved_at);
        } else if (resp.saved) {
          showDraftSavedBadge(resp.saved_at);
        }
      })
      .catch(() => {}); // Silent fail
  }

  function deleteDraft() {
    const key = getDraftKey();
    navigator.sendBeacon
      ? navigator.sendBeacon(DRAFT_API + `?action=delete&key=${encodeURIComponent(key)}`, '')
      : fetch(DRAFT_API + `?action=delete&key=${encodeURIComponent(key)}`).catch(() => {});
  }

  function showDraftSavedBadge(savedAt) {
    let badge = document.getElementById('navGuardDraftBadge');
    if (!badge) {
      badge = document.createElement('div');
      badge.id = 'navGuardDraftBadge';
      badge.style.cssText = `
        position: fixed; bottom: 20px; right: 24px;
        background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7;
        border-radius: 8px; padding: 8px 14px; font-size: 12px; font-weight: 600;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 9999;
        display: flex; align-items: center; gap: 6px;
        animation: fadeInUp 0.3s ease;
        transition: opacity 0.4s ease;
      `;
      document.body.appendChild(badge);
    }
    const time = savedAt ? new Date(savedAt.replace(' ', 'T')).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '';
    badge.innerHTML = `<i class="bi bi-cloud-check"></i> Draft saved ${time}`;
    badge.style.opacity = '1';
    clearTimeout(badge._hideTimer);
    badge._hideTimer = setTimeout(() => {
      badge.style.opacity = '0';
      setTimeout(() => badge?.remove(), 400);
    }, 4000);
  }

  // ── F. DRAFT RESTORE ON PAGE LOAD ─────────────────────────────────────────
  function initDraftRestore() {
    const forms = document.querySelectorAll('form[data-autosave]');
    if (forms.length === 0) return;

    const key = getDraftKey();
    fetch(`${DRAFT_API}?action=load&key=${encodeURIComponent(key)}`)
      .then(r => r.json())
      .then(resp => {
        if (!resp.draft) return;
        showDraftBanner(resp, forms[0]);
      })
      .catch(() => {});
  }

  function showDraftBanner(resp, form) {
    if (document.getElementById('navGuardDraftBanner')) return;

    const savedTime = resp.saved_at
      ? new Date(resp.saved_at.replace(' ', 'T')).toLocaleString([], {
          month: 'short', day: 'numeric',
          hour: '2-digit', minute: '2-digit'
        })
      : 'earlier';

    const banner = document.createElement('div');
    banner.id = 'navGuardDraftBanner';
    banner.style.cssText = `
      position: fixed; top: 0; left: 0; right: 0; z-index: 99998;
      background: linear-gradient(90deg, #eff6ff, #dbeafe);
      border-bottom: 2px solid #93c5fd;
      padding: 10px 20px;
      display: flex; align-items: center; gap: 12px;
      font-size: 13px; font-weight: 500; color: #1e40af;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    `;
    banner.innerHTML = `
      <i class="bi bi-floppy2-fill" style="font-size:16px;"></i>
      <span>You have an <strong>unsaved draft</strong> from <strong>${savedTime}</strong>.</span>
      <button id="navGuardRestoreBtn" class="btn btn-sm btn-primary ms-2" style="font-size:12px; padding:3px 12px;">
        <i class="bi bi-arrow-counterclockwise me-1"></i>Restore Draft
      </button>
      <button id="navGuardDiscardBtn" class="btn btn-sm btn-outline-secondary" style="font-size:12px; padding:3px 12px;">
        <i class="bi bi-trash me-1"></i>Discard
      </button>
      <button id="navGuardBannerClose" class="btn-close ms-auto" style="font-size:11px;"></button>
    `;
    document.body.prepend(banner);

    // Shift page content down
    document.body.style.paddingTop = (banner.offsetHeight + 2) + 'px';

    document.getElementById('navGuardRestoreBtn').addEventListener('click', () => {
      restoreFormData(form, resp.draft);
      banner.remove();
      document.body.style.paddingTop = '';
      // Snapshot the form as restored (not dirty yet)
      form.dataset.snapshot = serializeForm(form);
      showToast('Draft restored successfully!', 'success');
    });

    document.getElementById('navGuardDiscardBtn').addEventListener('click', () => {
      deleteDraft();
      banner.remove();
      document.body.style.paddingTop = '';
    });

    document.getElementById('navGuardBannerClose').addEventListener('click', () => {
      banner.remove();
      document.body.style.paddingTop = '';
    });
  }

  // ── B2. SESSION TIMEOUT WARNING ────────────────────────────────────────────
  function injectSessionModal() {
    if (document.getElementById('navGuardSessionModal')) return;
    const modal = document.createElement('div');
    modal.innerHTML = `
      <div class="modal fade" id="navGuardSessionModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
              <div class="d-flex align-items-center gap-2">
                <div style="width:38px;height:38px;background:#fef3c7;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-clock-history text-warning fs-5"></i>
                </div>
                <div>
                  <h5 class="modal-title mb-0 fw-bold">Session Expiring Soon</h5>
                </div>
              </div>
            </div>
            <div class="modal-body pt-2">
              <p class="text-muted mb-2">Your session will expire in:</p>
              <div id="navGuardCountdown"
                   style="font-size:2.5rem;font-weight:800;color:#dc2626;text-align:center;letter-spacing:2px;font-variant-numeric:tabular-nums;">
                5:00
              </div>
              <p class="text-muted small mt-2 mb-0 text-center">
                Click <strong>Extend Session</strong> to continue working, or your work may be lost.
              </p>
            </div>
            <div class="modal-footer border-0">
              <a href="${BASE_URL}/modules/auth/login.php" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i>Login Again
              </a>
              <button type="button" class="btn btn-success btn-sm fw-semibold" id="navGuardExtendBtn">
                <i class="bi bi-arrow-repeat me-1"></i>Extend Session
              </button>
            </div>
          </div>
        </div>
      </div>`;
    document.body.appendChild(modal.firstElementChild);

    document.getElementById('navGuardExtendBtn').addEventListener('click', () => {
      extendSession();
    });
  }

  function initSessionPing() {
    if (!CFG.userId) return; // Not logged in

    injectSessionModal();

    const ping = () => {
      fetch(PING_API)
        .then(r => r.json())
        .then(data => {
          if (!data.valid) {
            // Session expired → redirect
            clearInterval(sessionInterval);
            clearInterval(autosaveInterval);
            window.location.href = LOGIN_URL + '?timeout=1';
            return;
          }
          if (data.remaining_seconds <= SESSION_WARN_SECS && !sessionWarnShown) {
            sessionWarnShown = true;
            showSessionWarning(data.remaining_seconds);
          }
        })
        .catch(() => {}); // Silent fail on network error
    };

    ping(); // Immediate check
    sessionInterval = setInterval(ping, PING_INTERVAL);
  }

  function showSessionWarning(remainingSecs) {
    const modal = new bootstrap.Modal(document.getElementById('navGuardSessionModal'), { backdrop: 'static' });
    modal.show();

    let secs = remainingSecs;
    const el = document.getElementById('navGuardCountdown');

    clearInterval(sessionCountdown);
    sessionCountdown = setInterval(() => {
      secs--;
      if (el) el.textContent = formatTime(Math.max(0, secs));
      if (secs <= 0) {
        clearInterval(sessionCountdown);
        modal.hide();
        window.location.href = LOGIN_URL + '?timeout=1';
      }
    }, 1000);
  }

  function extendSession() {
    const body = new FormData();
    body.append('extend', '1');
    fetch(PING_API, { method: 'POST', body })
      .then(r => r.json())
      .then(data => {
        if (data.extended) {
          clearInterval(sessionCountdown);
          sessionWarnShown = false;
          const modal = bootstrap.Modal.getInstance(document.getElementById('navGuardSessionModal'));
          if (modal) modal.hide();
          showToast('Session extended successfully!', 'success');
        }
      })
      .catch(() => {});
  }

  // ── GLOBAL TOAST HELPER ────────────────────────────────────────────────────
  function showToast(message, type = 'info') {
    const colors = {
      success: { bg: '#ecfdf5', text: '#065f46', border: '#6ee7b7', icon: 'bi-check-circle-fill' },
      warning: { bg: '#fffbeb', text: '#92400e', border: '#fcd34d', icon: 'bi-exclamation-triangle-fill' },
      danger:  { bg: '#fef2f2', text: '#991b1b', border: '#fca5a5', icon: 'bi-x-circle-fill' },
      info:    { bg: '#eff6ff', text: '#1e40af', border: '#93c5fd', icon: 'bi-info-circle-fill' },
    };
    const c = colors[type] || colors.info;

    let container = document.getElementById('navGuardToastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'navGuardToastContainer';
      container.style.cssText = 'position:fixed;bottom:80px;right:24px;z-index:99999;display:flex;flex-direction:column;gap:8px;';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.style.cssText = `
      background:${c.bg}; color:${c.text}; border:1px solid ${c.border};
      border-radius:10px; padding:12px 16px;
      font-size:13px; font-weight:600;
      box-shadow:0 4px 16px rgba(0,0,0,0.12);
      display:flex; align-items:center; gap:8px;
      animation: fadeInUp 0.3s ease;
      max-width: 320px;
    `;
    toast.innerHTML = `<i class="bi ${c.icon}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.4s';
      setTimeout(() => toast.remove(), 400);
    }, 4000);
  }

  // ── CSS ANIMATIONS (injected once) ─────────────────────────────────────────
  function injectStyles() {
    if (document.getElementById('navGuardStyles')) return;
    const style = document.createElement('style');
    style.id = 'navGuardStyles';
    style.textContent = `
      @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      #navGuardDraftBanner { animation: fadeInUp 0.4s ease; }
    `;
    document.head.appendChild(style);
  }

  // ── BOOTSTRAP READY CHECK ──────────────────────────────────────────────────
  function waitForBootstrap(cb) {
    if (window.bootstrap) { cb(); return; }
    let tries = 0;
    const iv = setInterval(() => {
      if (window.bootstrap) { clearInterval(iv); cb(); }
      if (++tries > 30) clearInterval(iv);
    }, 100);
  }

  // ── INIT ───────────────────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    injectStyles();

    waitForBootstrap(() => {
      initDirtyDetection();
      initBeforeUnload();
      initLogoutInterception();
      initMobileBackGuard();
      initAutoSave();
      initDraftRestore();
      initSessionPing();
    });
  });

})();
