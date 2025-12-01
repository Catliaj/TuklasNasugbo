/**
 * assets/js/admin-script.js
 * FINAL FIX: Mapped 'day' and 'total_visits' correctly for Peak Visit Chart.
 */

(() => {
  'use strict';

  // ------------------------------
  // 1. Configuration / Globals
  // ------------------------------
  const COLOR_PALETTE = [
    '#004a7c', // Ocean Blue Primary
    '#003d66', // Ocean Blue Dark
    '#1cc88a', // Green (complementary)
    '#e8d5c4', // Beige Border
    '#e74a3b', // Red (complementary)
    '#d4183d', // Danger Red
    '#f5e6d3'  // Beige Light
  ];

  const chartInstances = {};

  // Helpers
  function el(id) { return document.getElementById(id); }
  function safeText(s) { return (s === null || s === undefined) ? '' : String(s); }
  function formatDateShort(dateString) {
    if (!dateString) return 'N/A';
    const d = new Date(dateString);
    return isNaN(d.getTime()) ? 'Invalid Date' : d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  // Toast Notification Helper
  function showToast(message, type = 'info') {
    let container = el('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toastContainer';
      container.className = 'position-fixed bottom-0 end-0 p-3';
      container.style.zIndex = '9999';
      document.body.appendChild(container);
    }
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : type === 'danger' ? 'bg-danger' : type === 'warning' ? 'bg-warning' : 'bg-primary';
    const toastHTML = `
      <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>`;
    container.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = el(toastId);
    try {
      const bsToast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
      bsToast.show();
      toastElement.addEventListener('hidden.bs.toast', () => { toastElement.remove(); });
    } catch (e) {
      toastElement.remove();
      console.log(message);
    }
  }

  // Helper to safely destroy a chart
  function destroyChartIfExists(canvasId) {
    const canvas = el(canvasId);
    if (!canvas) return;
    
    // 1. Ask Chart.js if a chart exists on this canvas
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
      existingChart.destroy();
    }
    
    // 2. Clear local cache just in case
    if (chartInstances[canvasId]) {
      delete chartInstances[canvasId];
    }
  }

  // ------------------------------
  // 2. Initialization
  // ------------------------------
  document.addEventListener('DOMContentLoaded', function init() {
    console.log('Initializing Admin Script...');

    // Sidebar Toggle
    initSidebarToggle();

    // Detect which page we are on and load appropriate logic
    
    // A. Dashboard Page
    if (el('peakVisitChart') || el('userPreferenceChart')) {
      loadDashboard();
    }

    // B. Registrations Page
    if (el('registrationsTable')) {
      loadRegistrations_API();
        // Search input on registrations page (moved into page header)
        const regSearch = el('searchRegistrations');
        if (regSearch) regSearch.addEventListener('input', () => window.filterRegistrations_API(registrationsCurrentFilter || 'all'));
    }

    // C. Attractions Page
    if (el('attractionsGrid')) {
      loadAttractions_API();
      
      // Search/Filter Listeners
      const searchInput = el('searchAttractions');
      if (searchInput) searchInput.addEventListener('input', window.applyAttractionsFilter_API);
      
      const catFilter = el('filterCategory'); 
      if (catFilter) catFilter.addEventListener('change', window.applyAttractionsFilter_API);
      
      const statFilter = el('filterStatus'); 
      if (statFilter) statFilter.addEventListener('change', window.applyAttractionsFilter_API);
    }

    // D. Reports Page
    if (el('reportFromDate') || el('reportToDate')) {
      setDefaultDateRange();
      
      const startInput = el('reportFromDate');
      const endInput = el('reportToDate');
      if(startInput && endInput) {
          fetchAnalytics(startInput.value, endInput.value);
      }

      const applyBtn = el('applyFilterBtn');
      if (applyBtn) applyBtn.addEventListener('click', () => {
        fetchAnalytics(el('reportFromDate').value, el('reportToDate').value);
      });
    }

    // Initialize notification dropdowns (if any)
    try { initNotifications(); } catch (e) { /* ignore if not applicable */ }
    console.log('Admin Script initialized.');
  });

  // ------------------------------
  // 3. UI Interaction (Sidebar)
  // ------------------------------
  function initSidebarToggle() {
    const sidebar = el('sidebar');
    const sidebarToggle = el('sidebarToggle');
    const overlay = el('sidebarOverlay');

    if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener('click', (ev) => {
        try {
          ev.preventDefault();
        } catch (e) { /* ignore */ }
        const willOpen = !sidebar.classList.contains('active');
        sidebar.classList.toggle('active');
        if (overlay) {
          overlay.classList.toggle('active', willOpen);
        }
        // Accessibility hint
        try { sidebarToggle.setAttribute('aria-expanded', String(willOpen)); } catch (e) {}
        // Prevent body scroll when sidebar is open on small screens
        if (willOpen && window.innerWidth <= 991) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = '';
        }
      });
    }

    // Auto-close sidebar when a nav item is clicked on narrow screens (mobile UX)
    try {
      const navLinks = sidebar.querySelectorAll('.sidebar-nav .nav-item');
      navLinks.forEach(link => {
        link.addEventListener('click', () => {
          // Use Bootstrap's lg breakpoint (992px) as the threshold
          if (window.innerWidth <= 991) {
            sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
          }
        });
      });
    } catch (e) { /* ignore if sidebar not present */ }

    if (overlay) {
      overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      });
    }

    // Also ensure clicking the overlay (anywhere outside) closes the sidebar
    document.addEventListener('click', function (e) {
      const isSmall = window.innerWidth <= 991;
      if (!isSmall) return;
      if (e.target.closest && e.target.closest('.sidebar')) return; // clicks inside sidebar
      if (e.target.closest && e.target.closest('#sidebarToggle')) return; // clicks on toggle
      const openSidebar = document.querySelector('.sidebar.active');
      const openOverlay = document.getElementById('sidebarOverlay');
      if (openSidebar && openOverlay && openOverlay.classList.contains('active')) {
        openSidebar.classList.remove('active');
        openOverlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });

    // If the window is resized to desktop width, ensure sidebar is visible and body scroll restored
    window.addEventListener('resize', () => {
      if (window.innerWidth > 991) {
        if (sidebar && !sidebar.classList.contains('active')) {
          // make sure desktop keeps visible state (no transform applied)
          sidebar.classList.remove('active');
        }
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  }

  // ------------------------------
  // Notifications (Reusable)
  // ------------------------------
  function initNotifications() {
    // Helper to toggle dropdown visibility when Bootstrap JS is not available
    function toggleDropdownFallback(btn) {
      try {
        const dropdown = btn.closest('.dropdown');
        if (!dropdown) return;
        const menu = dropdown.querySelector('.dropdown-menu');
        if (!menu) return;
        const isShown = menu.classList.contains('show');
        if (isShown) {
          menu.classList.remove('show');
          menu.style.display = 'none';
          btn.setAttribute('aria-expanded', 'false');
          // Fallback close: mark notifications as read
          markAllReadForButton(btn);
        } else {
          menu.classList.add('show');
          menu.style.display = 'block';
          btn.setAttribute('aria-expanded', 'true');
        }
      } catch (e) { /* ignore */ }
    }

    // Helper to mark all notifications as read for this button context
    async function markAllReadForButton(btn) {
      try {
        const fd = new FormData(); appendCsrf(fd);
        await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/notifications/mark-read`, { method: 'POST', body: fd, headers: {'X-Requested-With':'XMLHttpRequest'} });
      } catch (e) { /* ignore errors */ }
      // Clear badge immediately
      const dot = btn.querySelector('.notification-badge');
      if (dot) { dot.textContent = ''; dot.style.display = 'none'; }
    }

    // Helper: fetch notifications and render into dropdown menu for a given button
    async function fetchAndRenderNotifications(btn) {
      const dropdown = btn.closest('.dropdown');
      const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;
      if (!menu) return;

      // Loading state
      menu.innerHTML = `
        <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
          <span class="small text-muted fw-600">Notifications</span>
        </li>
        <li><hr class="dropdown-divider m-0"></li>
        <li class="dropdown-item text-center small text-muted py-3">Loading...</li>`;

      try {
        const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/notifications/list`);
        if (!resp.ok) {
          menu.innerHTML = `
            <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
              <span class="small text-muted fw-600">Notifications</span>
            </li>
            <li><hr class="dropdown-divider m-0"></li>
            <li class="dropdown-item text-muted small py-3 text-center">No new notifications</li>`;
          return;
        }
        const data = await resp.json();

        if (!data || data.length === 0) {
          menu.innerHTML = `
            <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
              <span class="small text-muted fw-600">Notifications</span>
            </li>
            <li><hr class="dropdown-divider m-0"></li>
            <li class="dropdown-item text-muted small py-3 text-center">No new notifications</li>`;
          return;
        }

        const itemsHtml = data.map(n => {
          const time = n.created_at ? new Date(n.created_at).toLocaleString() : '';
          const isUnread = String(n.is_read) === '0' || n.is_read === 0;
          const badgeHtml = isUnread ? '<span class="badge bg-danger flex-shrink-0" style="font-size:0.65rem;padding:0.35rem 0.5rem;">new</span>' : '';
          const bgStyle = isUnread ? 'background:#f9fafb;' : 'background:#ffffff;';
          return `
            <li class="notification-item ${isUnread ? 'notification-unread' : 'notification-read'}">
              <div class="dropdown-item d-flex justify-content-between align-items-start gap-2 px-3 py-2 border-bottom" style="cursor: default; ${bgStyle}">
                <div class="flex-grow-1">
                  <div class="fw-semibold text-dark" style="font-size:0.95rem;">${safeText(n.message)}</div>
                  <div class="small text-muted" style="font-size:0.8rem;">${time}</div>
                </div>
                ${badgeHtml}
              </div>
            </li>`;
        }).join('');

        menu.innerHTML = `
          <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
            <span class="small text-muted fw-600">Notifications</span>
          </li>
            <li><hr class="dropdown-divider m-0"></li>
          ${itemsHtml}`;
      } catch (err) {
        console.error('Notification fetch error:', err);
        menu.innerHTML = `
          <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
            <span class="small text-muted fw-600">Notifications</span>
          </li>
          <li><hr class="dropdown-divider m-0"></li>
          <li class="dropdown-item text-danger small py-3 text-center">Error loading notifications</li>`;
      }
    }

    // Attach click handlers to any notification-button in the page (for backward compatibility)
    document.querySelectorAll('.notification-button').forEach(btn => {
      if (btn._notifAttached) return; btn._notifAttached = true;
      btn.addEventListener('click', async function (e) {
        try {
          e.preventDefault();
          // Ensure Bootstrap toggles the dropdown (some pages may not initialize automatically)
          // Try Bootstrap toggle first; if bootstrap is not available use fallback
          try {
            if (window.bootstrap && bootstrap.Dropdown) {
              new bootstrap.Dropdown(btn).toggle();
            } else {
              toggleDropdownFallback(btn);
            }
          } catch (ex) { toggleDropdownFallback(btn); }
          await fetchAndRenderNotifications(btn);
        } catch (err) { console.error('Notification click error', err); }
      });
    });

    // Fallback: delegated capturing listener to ensure clicks are handled even
    // if some layer interferes with normal event propagation.
    document.addEventListener('click', function (e) {
      const btn = e.target.closest && e.target.closest('.notification-button');
      if (!btn) return;
      // If already handled by direct listener, skip
      if (btn._notifAttachedHandled) return;
      btn._notifAttachedHandled = true;
      (async () => {
        try {
          e.preventDefault();
          try { new bootstrap.Dropdown(btn).toggle(); } catch (ex) { /* ignore */ }
          await fetchAndRenderNotifications(btn);
        } catch (err) { console.error('Delegated notification click error', err); }
        // allow re-handling in future clicks
        setTimeout(() => { btn._notifAttachedHandled = false; }, 250);
      })();
    }, true);

    // Also listen to Bootstrap dropdown events to populate menus reliably (fixes reports page)
    document.addEventListener('show.bs.dropdown', function (e) {
      try {
        // In Bootstrap 5 the event target is the toggle button itself, not the parent .dropdown
        let btn = null;
        if (e.target && e.target.classList && e.target.classList.contains('notification-button')) {
          btn = e.target;
        } else if (e.target) {
          // Attempt to find a descendant button (original logic)
          btn = e.target.querySelector && e.target.querySelector('.notification-button');
        }
        if (!btn && e.target && e.target.closest) {
          // Fallback: climb to .dropdown and locate button
          const parentDropdown = e.target.closest('.dropdown');
          if (parentDropdown) btn = parentDropdown.querySelector('.notification-button');
        }
        if (btn) fetchAndRenderNotifications(btn);
      } catch (ex) { /* ignore */ }
    });

    // Mark as read when dropdown hides
    document.addEventListener('hide.bs.dropdown', function (e) {
      try {
        let btn = null;
        if (e.target && e.target.classList && e.target.classList.contains('notification-button')) {
          btn = e.target;
        } else if (e.target) {
          btn = e.target.querySelector && e.target.querySelector('.notification-button');
        }
        if (!btn && e.target && e.target.closest) {
          const parentDropdown = e.target.closest('.dropdown');
          if (parentDropdown) btn = parentDropdown.querySelector('.notification-button');
        }
        if (btn) markAllReadForButton(btn);
      } catch (ex) { /* ignore */ }
    });

  }

  // Poll unread count periodically (realtime-ish)
  (function startNotificationPolling() {
    const POLL_MS = 15000; // 15 seconds
    async function updateAllDots() {
      try {
        const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/notifications/unread-count`);
        if (!resp.ok) return;
        const data = await resp.json();
        const unread = parseInt(data.unread || 0, 10);
        // cap display to avoid badge stretching
        const displayCount = unread > 99 ? '99+' : (unread > 0 ? String(unread) : '');
        document.querySelectorAll('.notification-button').forEach(btn => {
          const dot = btn.querySelector('.notification-badge');
          if (!dot) return;
          dot.textContent = displayCount;
          dot.style.display = displayCount ? 'inline-block' : 'none';
        });
      } catch (e) { /* ignore */ }
    }
    // Run immediately and then poll
    updateAllDots();
    setInterval(updateAllDots, POLL_MS);
  })();

  // ------------------------------
  // 4. DASHBOARD LOGIC
  // ------------------------------
  function loadDashboard() {
    const dbData = window.dashboardData || {};
    console.log("Dashboard Data:", dbData);

    if (el('peakVisitChart') && dbData.peakVisitTimes) {
      loadPeakVisitChart(dbData.peakVisitTimes);
    }

    if (el('userPreferenceChart') && dbData.userPreferences) {
      loadUserPreferenceChart(dbData.userPreferences);
    }
  }

  function loadPeakVisitChart(data) {
    const canvasId = 'peakVisitChart';
    destroyChartIfExists(canvasId);

    // --- FIX HERE: Updated to match BookingModel keys ---
    // Database returns: 'day' and 'total_visits'
    const labels = data.map(d => d.day || d.hour || 'Unknown');
    const values = data.map(d => Number(d.total_visits || d.total || 0));

    const ctx = el(canvasId).getContext('2d');
    chartInstances[canvasId] = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Visits',
          data: values,
          backgroundColor: '#4e73df',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { display: true } } }
      }
    });
  }

  function loadUserPreferenceChart(data) {
    const canvasId = 'userPreferenceChart';
    destroyChartIfExists(canvasId);

    const labels = data.map(d => d.category || d.preference || 'Other');
    const values = data.map(d => Number(d.total || d.count || d.preference_count || 0));

    const ctx = el(canvasId).getContext('2d');
    chartInstances[canvasId] = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: COLOR_PALETTE,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }

  // ------------------------------
  // 5. REGISTRATIONS API
  // ------------------------------
  let registrationsCache = [];
  let currentRegistrationId = null;
  let registrationsCurrentFilter = 'all';

  async function loadRegistrations_API() {
    const tableBody = el('registrationsTable');
    if (!tableBody) return;
    tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">Loading...</td></tr>`;
    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/registrations/list`);
      if (!resp.ok) throw new Error('API Error');
      registrationsCache = await resp.json();
      renderRegistrations_API(registrationsCache);
      // Ensure header buttons and search are applied after data load
      try { window.filterRegistrations_API(registrationsCurrentFilter || 'all'); } catch(e) {}
    } catch (e) {
      console.error(e);
      tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger p-4">Could not load data.</td></tr>`;
    }
  }

  function renderRegistrations_API(registrations) {
    const tableBody = el('registrationsTable');
    if (!tableBody) return;
    if (!registrations || registrations.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">No registrations found.</td></tr>`;
      return;
    }

    tableBody.innerHTML = registrations.map((reg, index) => {
      const statusBadge = getStatusBadgeClass(reg.status, reg.status);
      const isPending = String(reg.status || '').toLowerCase() === 'pending';
      const actions = isPending ? `
        <button class="btn btn-outline-success btn-sm" onclick="openApproveModal_API(${reg.business_id})"><i class="bi bi-check-circle"></i></button>
        <button class="btn btn-outline-danger btn-sm" onclick="openRejectModal_API(${reg.business_id})"><i class="bi bi-x-circle"></i></button>
      ` : '';

      return `
        <tr>
          <td class="text-muted">${index + 1}</td>
          <td><strong>${safeText(reg.business_name)}</strong></td>
          <td>${safeText(reg.FirstName)} ${safeText(reg.LastName)}</td>
          <td><small>${safeText(reg.contact_email)}</small></td>
          <td><small>${safeText(reg.contact_phone)}</small></td>
          <td><small>${safeText(reg.business_address)}</small></td>
          <td><small>${formatDateShort(reg.created_at)}</small></td>
          <td class="text-center">${statusBadge}</td>
          <td class="text-center">
            <div class="btn-group btn-group-sm">
              <button class="btn btn-outline-primary" onclick="viewRegistrationDetails_API(${reg.business_id})"><i class="bi bi-eye"></i></button>
              ${actions}
            </div>
          </td>
        </tr>`;
    }).join('');
  }

  /**
   * Filter registrations by status and update the table.
   * Called from the buttons in `registrations.php` via `onclick="filterRegistrations_API('pending')"`.
   */
  window.filterRegistrations_API = function(status) {
    const s = String(status || '').trim().toLowerCase();
    registrationsCurrentFilter = s || 'all';
    let list = (registrationsCache || []).slice();

    // Apply status filter
    if (s && s !== 'all') {
      list = list.filter(r => String(r.status || '').trim().toLowerCase() === s);
    }

    // Apply search term if present
    const term = el('searchRegistrations') ? String(el('searchRegistrations').value || '').trim().toLowerCase() : '';
    if (term) {
      list = list.filter(r => {
        const name = `${r.FirstName || ''} ${r.LastName || ''}`.toLowerCase();
        return (String(r.business_name || '').toLowerCase().includes(term) ||
                name.includes(term) ||
                String(r.contact_email || '').toLowerCase().includes(term) ||
                String(r.contact_phone || '').toLowerCase().includes(term) ||
                String(r.business_address || '').toLowerCase().includes(term));
      });
    }
    renderRegistrations_API(list);

    // Update button styles in the page header to reflect active filter.
    // Find all buttons that call filterRegistrations_API(...) and update classes.
    const btns = document.querySelectorAll('[onclick*="filterRegistrations_API"]');
    btns.forEach(btn => {
      // Remove any existing 'active' or primary classes and outline classes
      btn.classList.remove('active');
      btn.classList.remove('btn-primary');
      // Remove any btn-outline-* classes
      Array.from(btn.classList).forEach(cls => {
        if (cls.startsWith('btn-outline-')) btn.classList.remove(cls);
      });

      // Determine the filter value embedded in the onclick attribute
      const attr = btn.getAttribute('onclick') || '';
      const m = attr.match(/filterRegistrations_API\(\s*['\"]?(\w+)['\"]?\s*\)/i);
      const val = m ? String(m[1]).toLowerCase() : '';

      if ((s === 'all' && val === 'all') || (s && val === s)) {
        // Active: use filled primary style for visibility
        btn.classList.add('active');
        btn.classList.add('btn-primary');
      } else {
        // Restore appropriate outline style based on the button intent
        let outline = 'btn-outline-primary';
        if (val === 'pending') outline = 'btn-outline-warning';
        else if (val === 'approved') outline = 'btn-outline-success';
        else if (val === 'rejected') outline = 'btn-outline-danger';
        btn.classList.add(outline);
      }
    });
  };

  function getStatusBadgeClass(status, label) {
    const s = String(status || '').toLowerCase();
    let cls = 'bg-secondary';
    if (s === 'approved' || s === 'active') cls = 'bg-success';
    else if (s === 'pending') cls = 'bg-warning text-dark';
    else if (s === 'rejected' || s === 'suspended') cls = 'bg-danger';
    return `<span class="badge ${cls}">${safeText(label)}</span>`;
  }

  window.openApproveModal_API = function(id) { currentRegistrationId = id; new bootstrap.Modal(el('approveModal')).show(); };
  window.openRejectModal_API = function(id) { currentRegistrationId = id; el('rejectReason').value = ''; new bootstrap.Modal(el('rejectModal')).show(); };

  window.confirmApprove_API = async function() { await sendRegAction('approve'); };
  window.confirmReject_API = async function() {
    const reason = el('rejectReason').value.trim();
    if (!reason) return alert('Reason required');
    await sendRegAction('reject', reason);
  };

  async function sendRegAction(action, reason = null) {
    try {
      const formData = new FormData();
      if (reason) formData.append('reason', reason);
      appendCsrf(formData);

      const resp = await fetch(`${BASE_URL}admin/registrations/${action}/${currentRegistrationId}`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const res = await resp.json();
      if (!resp.ok) throw new Error(res.error || 'Action failed');
      
      bootstrap.Modal.getInstance(el(action === 'approve' ? 'approveModal' : 'rejectModal')).hide();
      loadRegistrations_API();
      showToast(res.success || 'Success', 'success');
    } catch (e) {
      alert(e.message);
    }
  }

  window.viewRegistrationDetails_API = async function(id) {
    const content = el('viewRegistrationContent');
    content.innerHTML = 'Loading...';
    new bootstrap.Modal(el('viewRegistrationModal')).show();
    try {
      const resp = await fetch(`${BASE_URL}admin/registrations/view/${id}`);
      const reg = await resp.json();
      content.innerHTML = `
        <h5>${safeText(reg.business_name)}</h5>
        <p>Owner: ${safeText(reg.FirstName)} ${safeText(reg.LastName)}</p>
        <p>Email: ${safeText(reg.contact_email)}</p>
        <p>Status: ${safeText(reg.status)}</p>
        ${reg.rejection_reason ? `<p class="text-danger">Reason: ${reg.rejection_reason}</p>` : ''}
      `;
    } catch(e) { content.innerHTML = 'Error loading details.'; }
  };

  // ------------------------------
  // 6. ATTRACTIONS API
  // ------------------------------
  let allAttractions = [];
  let currentAttractionId = null;

  async function loadAttractions_API() {
    const grid = el('attractionsGrid');
    if (!grid) return;
    grid.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border"></div></div>';
    try {
      const resp = await fetch(`${BASE_URL}admin/attractions/list`);
      allAttractions = await resp.json();
      renderAttractions_API(allAttractions);
    } catch (e) {
      grid.innerHTML = '<div class="text-center text-danger">Failed to load attractions.</div>';
    }
  }

  function renderAttractions_API(list) {
    const grid = el('attractionsGrid');
    if (!grid) return;
    // Exclude pending attractions from the main grid; pending items are shown only in the Pending Requests modal
    const visibleList = (list || []).filter(a => String(a.status || '').trim().toLowerCase() !== 'pending');
    if (!visibleList.length) { grid.innerHTML = '<div class="text-center p-5">No attractions found.</div>'; return; }

    grid.innerHTML = visibleList.map(a => {
      // Image fallback
      const uploadsPath = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'uploads/spots/';
      const primaryImage = a.primary_image ? `${uploadsPath}${a.primary_image}` : `${uploadsPath}Spot-No-Image.png`;
      
      return `
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card attraction-card h-100">
            <img src="${primaryImage}" class="card-img-top" style="height:180px;object-fit:cover" 
                 onerror="this.onerror=null;this.src='${uploadsPath}Spot-No-Image.png'">
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-primary">${safeText(a.category)}</span>
                ${getStatusBadgeClass(a.status, a.status)}
              </div>
              <h5 class="card-title">${safeText(a.spot_name)}</h5>
              <p class="small text-muted mb-2 flex-grow-1"><i class="bi bi-geo-alt"></i> ${safeText(a.location)}</p>
              <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-sm btn-outline-info flex-fill" onclick="viewAttraction_API(${a.spot_id})">View</button>
                <button class="btn btn-sm btn-outline-warning" onclick="openSuspendModal_API(${a.spot_id})"><i class="bi bi-pause"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal_API(${a.spot_id})"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
        </div>`;
    }).join('');
  }

  window.applyAttractionsFilter_API = function() {
    const term = el('searchAttractions') ? el('searchAttractions').value.toLowerCase() : '';
    const cat = el('filterCategory') ? el('filterCategory').value : '';
    const stat = el('filterStatus') ? el('filterStatus').value : '';

    const filtered = allAttractions.filter(a => {
      const name = (a.spot_name || '').toLowerCase();
      const matchName = name.includes(term);

      // Normalize category/status for robust matching (trim + case-insensitive)
      const aCat = String(a.category || '').trim().toLowerCase();
      const aStat = String(a.status || '').trim().toLowerCase();
      const selCat = String(cat || '').trim().toLowerCase();
      const selStat = String(stat || '').trim().toLowerCase();

      const matchCat = !selCat || aCat === selCat;
      const matchStat = !selStat || aStat === selStat;
      return matchName && matchCat && matchStat;
    });
    renderAttractions_API(filtered);
  };

  window.viewAttraction_API = async function(id) {
    const content = el('viewAttractionContent');
    content.innerHTML = 'Loading...';
    // If pending modal is open, hide it temporarily so the view modal appears on top
    const pendingModalEl = el('pendingRequestsModal');
    let pendingWasOpen = false;
    if (pendingModalEl && pendingModalEl.classList.contains('show')) {
      pendingWasOpen = true;
      const pm = bootstrap.Modal.getInstance(pendingModalEl) || new bootstrap.Modal(pendingModalEl);
      pm.hide();
    }
    new bootstrap.Modal(el('viewAttractionModal')).show();
    try {
      const resp = await fetch(`${BASE_URL}admin/attractions/view/${id}`);
      const data = await resp.json();
      // Build a richer detail view including gallery and owner info
      const galleryHtml = (data.images && data.images.length) ? data.images.map(img => `<img src="${(BASE_URL||'')+'uploads/spots/gallery/'+img.image}" style="height:100px;object-fit:cover;margin-right:8px;border-radius:6px">`).join('') : '';
      content.innerHTML = `
        <div class="row">
          <div class="col-md-6">
            <h4 class="mb-2">${safeText(data.spot_name)}</h4>
            <p class="small text-muted mb-1">${safeText(data.location)}</p>
            <p>${safeText(data.description)}</p>
            <div class="mb-2"><strong>Category:</strong> ${safeText(data.category)}</div>
            <div class="mb-2"><strong>Capacity:</strong> ${safeText(data.capacity || 'N/A')}</div>
            <div class="mb-2"><strong>Operating Hours:</strong> ${safeText(data.opening_time || 'N/A')} - ${safeText(data.closing_time || 'N/A')}</div>
            <div class="mb-2"><strong>Price:</strong> ₱${safeText(data.price_per_person || '0.00')}</div>
            <div class="mb-2"><strong>Status:</strong> ${safeText(data.status)}</div>
            <hr>
            <h6>Owner / Business</h6>
            <p class="mb-0"><strong>${safeText(data.business_name)}</strong></p>
            <p class="small text-muted">${safeText(data.FirstName || '')} ${safeText(data.LastName || '')}</p>
          </div>
          <div class="col-md-6">
            <div class="mb-3" style="display:flex;flex-wrap:wrap">${galleryHtml}</div>
            <div class="small text-muted">Primary Image</div>
            <img src="${(BASE_URL||'')+'uploads/spots/'+(data.primary_image||'Spot-No-Image.png')}" style="width:100%;height:180px;object-fit:cover;border-radius:6px;margin-top:8px" onerror="this.onerror=null;this.src='${(BASE_URL||'')+'uploads/spots/Spot-No-Image.png'}'">
          </div>
        </div>
      `;
    } catch(e) { content.innerHTML = 'Error loading.'; }

    // When the view modal hides, if the pending modal was open before, re-open it
    const viewModalEl = el('viewAttractionModal');
    if (viewModalEl) {
      viewModalEl.addEventListener('hidden.bs.modal', function handler() {
        if (pendingWasOpen && pendingModalEl) {
          new bootstrap.Modal(pendingModalEl).show();
        }
        viewModalEl.removeEventListener('hidden.bs.modal', handler);
      });
    }
  };

  // Load pending attractions for admin review
  window.loadPendingAttractions_API = async function() {
    const table = el('pendingRequestsTable');
    if (!table) return;
    table.innerHTML = '<tr><td colspan="6" class="text-center p-3">Loading...</td></tr>';
    try {
      const resp = await fetch(`${BASE_URL}admin/attractions/pending`);
      if (!resp.ok) throw new Error('Failed to fetch');
      const data = await resp.json();
      if (!data || data.length === 0) {
        table.innerHTML = '<tr><td colspan="6" class="text-center p-3 text-muted">No pending requests.</td></tr>';
      } else {
        table.innerHTML = data.map((r, idx) => `
          <tr>
            <td>${idx+1}</td>
            <td><strong>${safeText(r.spot_name)}</strong></td>
            <td>${safeText(r.business_name)}</td>
            <td>${safeText(r.category)}</td>
            <td>${safeText(new Date(r.created_at).toLocaleDateString())}</td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" onclick="viewAttraction_API(${r.spot_id})"><i class="bi bi-eye"></i></button>
                <button class="btn btn-outline-success" onclick="openApproveAttractionModal_API(${r.spot_id})" title="Approve"><i class="bi bi-check-circle"></i></button>
                <button class="btn btn-outline-warning" onclick="openRejectAttractionModal_API(${r.spot_id})" title="Reject"><i class="bi bi-x-circle"></i></button>
                <button class="btn btn-outline-danger" onclick="openDeleteModal_API(${r.spot_id})"><i class="bi bi-trash"></i></button>
              </div>
            </td>
          </tr>`).join('');
      }
      new bootstrap.Modal(el('pendingRequestsModal')).show();
    } catch (e) {
      table.innerHTML = '<tr><td colspan="6" class="text-center p-3 text-danger">Error loading pending requests.</td></tr>';
    }
  };

  // Modal-based approve/reject flows for pending attractions
  let currentPendingAttractionId = null;
  window.openApproveAttractionModal_API = function(id) {
    currentPendingAttractionId = id;
    if (el('approveAttractionModal')) new bootstrap.Modal(el('approveAttractionModal')).show();
  };

  window.confirmApproveAttraction_API = async function() {
    if (!currentPendingAttractionId) return alert('Missing ID');
    try {
      const formData = new FormData(); appendCsrf(formData);
      const resp = await fetch(`${BASE_URL}admin/attractions/approve/${currentPendingAttractionId}`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
      const res = await resp.json();
      if (!resp.ok) throw new Error(res.error || 'Approve failed');
      bootstrap.Modal.getInstance(el('approveAttractionModal')).hide();
      showToast(res.success || 'Approved', 'success');
      loadPendingAttractions_API(); loadAttractions_API();
    } catch (e) { alert(e.message || 'Error approving attraction'); }
  };

  window.openRejectAttractionModal_API = function(id) {
    currentPendingAttractionId = id;
    if (el('rejectAttractionModal')) {
      el('pendingRejectReason').value = '';
      new bootstrap.Modal(el('rejectAttractionModal')).show();
    }
  };

  window.confirmRejectAttraction_API = async function() {
    const reason = el('pendingRejectReason') ? el('pendingRejectReason').value.trim() : '';
    if (!reason) return alert('Reason is required');
    try {
      const formData = new FormData(); formData.append('reason', reason); appendCsrf(formData);
      const resp = await fetch(`${BASE_URL}admin/attractions/reject/${currentPendingAttractionId}`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
      const res = await resp.json();
      if (!resp.ok) throw new Error(res.error || 'Reject failed');
      bootstrap.Modal.getInstance(el('rejectAttractionModal')).hide();
      showToast(res.success || 'Rejected', 'warning');
      loadPendingAttractions_API(); loadAttractions_API();
    } catch (e) { alert(e.message || 'Error rejecting attraction'); }
  };

  window.openSuspendModal_API = function(id) { currentAttractionId = id; el('suspendReason').value = ''; new bootstrap.Modal(el('suspendModal')).show(); };
  window.openDeleteModal_API = function(id) { currentAttractionId = id; el('deleteConfirmText').value = ''; new bootstrap.Modal(el('deleteModal')).show(); };

  window.confirmSuspend_API = async function() {
    const reason = el('suspendReason').value;
    if (!reason) return alert('Reason required');
    await sendAttrAction('suspend', { reason });
  };

  window.confirmDelete_API = async function() {
    if (el('deleteConfirmText').value !== 'DELETE') return alert('Type DELETE to confirm');
    await sendAttrAction('delete');
  };

  async function sendAttrAction(action, payload = {}) {
    try {
      const formData = new FormData();
      for (const k in payload) formData.append(k, payload[k]);
      appendCsrf(formData);
      const resp = await fetch(`${BASE_URL}admin/attractions/${action}/${currentAttractionId}`, {
        method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'}
      });
      if (!resp.ok) throw new Error('Action failed');
      bootstrap.Modal.getInstance(el(action === 'suspend' ? 'suspendModal' : 'deleteModal')).hide();
      loadAttractions_API();
      showToast('Success', 'success');
    } catch(e) { alert(e.message); }
  }

  // ------------------------------
  // 7. REPORTS & ANALYTICS
  // ------------------------------
  function setDefaultDateRange() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    if (el('reportFromDate') && !el('reportFromDate').value) el('reportFromDate').valueAsDate = firstDay;
    if (el('reportToDate') && !el('reportToDate').value) el('reportToDate').valueAsDate = today;
  }

  async function fetchAnalytics(startDate, endDate) {
    try {
      const formData = new FormData();
      formData.append('startDate', startDate);
      formData.append('endDate', endDate);
      appendCsrf(formData);

      const resp = await fetch(`${BASE_URL}admin/reports/analytics`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const result = await resp.json();

      if (result.success) {
        // Update Summary Cards
        if (el('totalBookings')) el('totalBookings').innerText = result.summary.totalBookings;
        if (el('totalRevenue')) el('totalRevenue').innerText = '₱' + Number(result.summary.totalRevenue).toLocaleString();
        if (el('avgRating')) el('avgRating').innerText = result.summary.averageRating;
        
        // --- FIX 1: Explicitly Map Demographics ---
        // We map the keys exactly to ensure the labels match the data
        const demoData = result.charts.visitorDemographics;
        const demoValues = [
            Number(demoData.total_adults || 0),
            Number(demoData.total_children || 0),
            Number(demoData.total_seniors || 0)
        ];
        // Pass the array directly
        renderReportChart('demographicsChart', 'pie', demoValues, ['Adults','Children','Seniors']);

        // --- FIX 2: Peak Booking Days ---
        renderReportChart('peakBookingChart', 'bar', result.charts.peakBookingDays);

        // --- FIX 3: Revenue By Category ---
        renderReportChart('revenueByCategoryChart', 'doughnut', result.charts.revenueByCategory);
      }
    } catch (e) {
      console.error('Analytics Error:', e);
    }
  }

  function renderReportChart(id, type, dataRaw, labelsOverride = null) {
    destroyChartIfExists(id); // Safety clear
    
    const canvas = el(id);
    if (!canvas) return;

    let labels = [], data = [];

    if (Array.isArray(dataRaw)) {
        // Case A: It's a simple array of numbers (Like our fixed Demographics above)
        if (typeof dataRaw[0] === 'number') {
            labels = labelsOverride || [];
            data = dataRaw;
        } 
        // Case B: It's an array of objects from DB (Like Revenue By Category)
        else {
            labels = dataRaw.map(d => d.category || d.day || d.lead_time_group || 'Label');
            data = dataRaw.map(d => Number(d.total || d.total_revenue || 0));
        }
    } 
    else if (typeof dataRaw === 'object' && dataRaw !== null) {
        // Case C: Raw Object (Backup)
        labels = labelsOverride || Object.keys(dataRaw);
        data = Object.values(dataRaw);
    }

    const ctx = canvas.getContext('2d');
    chartInstances[id] = new Chart(ctx, {
      type: type,
      data: {
        labels: labels,
        datasets: [{ 
            data: data, 
            backgroundColor: COLOR_PALETTE,
            borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
      }
    });
  }

  function appendCsrf(formData) {
    const name = document.querySelector('meta[name="csrf-token-name"]');
    const val = document.querySelector('meta[name="csrf-token-value"]');
    if (name && val) formData.append(name.content, val.content);
  }

  // Expose essential functions for inline HTML onclick attributes
  window.loadDashboard = loadDashboard;
  window.loadRegistrations_API = loadRegistrations_API;
  window.loadAttractions_API = loadAttractions_API;
  window.applyAttractionsFilter_API = window.applyAttractionsFilter_API;
  window.showToast = showToast;

})();