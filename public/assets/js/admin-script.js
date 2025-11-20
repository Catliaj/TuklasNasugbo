/**
 * assets/js/admin-script.js
 * Consolidated Admin dashboard script — fixed missing references and guarded initialization.
 *
 * - Ensures functions referenced during initialization are defined before used
 * - Adds a concrete implementation for applyAttractionsFilter_API
 * - Adds safe/no-op fallbacks for report helper functions if not present
 * - Guards DOM access to avoid "Cannot set properties of null"
 * - Keeps single DOMContentLoaded entrypoint
 *
 * Replace your existing admin-script.js with this file (backup first).
 */

(() => {
  'use strict';

  // ------------------------------
  // Configuration / Globals
  // ------------------------------
  const COLOR_PALETTE = [
    '#4e73df', // Blue
    '#1cc88a', // Green
    '#36b9cc', // Teal
    '#f6c23e', // Yellow
    '#e74a3b', // Red
    '#6f42c1', // Purple
    '#858796'  // Gray
  ];

  // local chart references
  const chartInstances = {};

  // Small helpers
  function el(id) { return document.getElementById(id); }
  function safeText(s) { return (s === null || s === undefined) ? '' : String(s); }
  function isString(v) { return typeof v === 'string'; }

  function tryParseJSON(v) {
    if (!v) return null;
    if (Array.isArray(v) || typeof v === 'object') return v;
    if (isString(v)) {
      try { return JSON.parse(v); } catch(e) { return null; }
    }
    return null;
  }

  function formatDateShort(dateString) {
    if (!dateString) return 'N/A';
    const d = new Date(dateString);
    return isNaN(d.getTime()) ? 'Invalid Date' : d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

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
    const toastHTML = `<div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
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
      // fallback
      alert(message);
      toastElement.remove();
    }
  }

  // ------------------------------
  // Ensure critical API functions exist (no-ops / wrappers)
  // ------------------------------
  // applyAttractionsFilter_API: implement here using allAttractions/renderAttractions_API (safe)
  let allAttractions = []; // will be populated by loadAttractions_API
  window.applyAttractionsFilter_API = function() {
    // Read inputs (guarded)
    const searchInput = el('searchAttractions');
    const filterCategory = el('filterCategory');
    const filterStatus = el('filterStatus');

    const term = searchInput ? (searchInput.value || '').toLowerCase() : '';
    const cat = filterCategory ? (filterCategory.value || '') : '';
    const status = filterStatus ? (filterStatus.value || '') : '';

    // Filter using available allAttractions array
    if (!Array.isArray(allAttractions)) {
      console.warn('applyAttractionsFilter_API: allAttractions not initialized');
      return;
    }

    const filtered = allAttractions.filter(a => {
      const name = (a.spot_name || a.name || '').toString().toLowerCase();
      const location = (a.location || '').toString().toLowerCase();
      const matchesTerm = !term || name.includes(term) || location.includes(term);
      const matchesCat = !cat || (a.category === cat);
      const matchesStatus = !status || (a.status === status);
      return matchesTerm && matchesCat && matchesStatus;
    });

    if (typeof renderAttractions_API === 'function') {
      renderAttractions_API(filtered);
    } else {
      // fallback: render minimal grid
      const grid = el('attractionsGrid');
      if (!grid) return;
      grid.innerHTML = filtered.length === 0
        ? '<div class="col-12 text-center p-5 text-muted">No attractions found.</div>'
        : filtered.map(attraction => {
            const uploadsPath = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'uploads/spots/';
            const primaryImage = attraction.primary_image ? `${uploadsPath}${attraction.primary_image}` : `${uploadsPath}Spot-No-Image.png`;
            const safeImage = `onerror="this.onerror=null;this.src='${uploadsPath}Spot-No-Image.png'"`;
            return `
              <div class="col-md-6 col-lg-4 mb-3">
                <div class="card attraction-card h-100">
                  <img src="${primaryImage}" ${safeImage} class="card-img-top" alt="${safeText(attraction.spot_name)}" style="height:180px;object-fit:cover">
                  <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <span class="badge bg-primary">${safeText(attraction.category || 'N/A')}</span>
                      <span class="badge bg-secondary">${safeText(attraction.status || '')}</span>
                    </div>
                    <h5 class="card-title">${safeText(attraction.spot_name || 'Unnamed')}</h5>
                    <p class="text-muted small mb-2 flex-grow-1"><i class="bi bi-geo-alt me-1"></i>${safeText(attraction.location || 'No location')}</p>
                  </div>
                </div>
              </div>`;
          }).join('');
    }
  };

  // Fallbacks for report helper functions (if the original names are elsewhere, they'll be used instead)
  function setDefaultDateRange() {
    if (!el('reportFromDate') || !el('reportToDate')) return;
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    el('reportFromDate').valueAsDate = firstDay;
    el('reportToDate').valueAsDate = today;
  }

  function loadReportAnalytics() {
    // If the project has a server-provided loader, call it; otherwise show placeholder values.
    if (typeof fetchAnalytics === 'function') {
      // fetchAnalytics is the function that does the real AJAX call
      const start = el('reportFromDate') ? el('reportFromDate').value : '';
      const end = el('reportToDate') ? el('reportToDate').value : '';
      fetchAnalytics(start, end);
      return;
    }
    // fallback: fill placeholders without server data
    if (el('totalBookings')) el('totalBookings').textContent = '0';
    if (el('totalAttractions')) el('totalAttractions').textContent = '0';
    if (el('avgRating')) el('avgRating').textContent = '0';
    if (el('totalRevenue')) el('totalRevenue').textContent = '₱0.00';
  }

  function loadReportCharts() {
    // If the server flow already populates charts via fetchAnalytics -> renderChart, nothing needed here.
    // Provide minimal static chart if desired; otherwise do nothing.
  }

  function loadTopAttractions() {
    // If there is a server-driven function, call it; otherwise populate with nothing.
    // Placeholder: clear table if present
    if (el('topAttractionsTable')) el('topAttractionsTable').innerHTML = '<tr><td colspan="6" class="text-center text-muted p-3">No data</td></tr>';
  }

  // ------------------------------
  // Initialization (single entry)
  // ------------------------------
  document.addEventListener('DOMContentLoaded', function init() {
    console.log('Initializing Admin Script...');

    // Sidebar toggle / overlay
    initSidebarToggle();

    // Wire navigation if present
    try { initNavigation(); } catch (e) { console.warn('initNavigation failed', e); }

    // Dashboard pieces
    if (el('bookingsChart') || el('peakVisitChart') || el('userPreferenceChart')) {
      try { loadDashboard(); } catch (e) { console.error('loadDashboard failed', e); }
    }

    // Reports page
    if (el('reportFromDate') || el('reportToDate')) {
      try { setDefaultDateRange(); loadReportAnalytics(); } catch (e) { console.error('initReportsPage failed', e); }
      const applyBtn = el('applyFilterBtn');
      if (applyBtn) applyBtn.addEventListener('click', () => {
        const start = el('reportFromDate') ? el('reportFromDate').value : '';
        const end = el('reportToDate') ? el('reportToDate').value : '';
        fetchAnalytics(start, end);
      });
    }

    // Registrations page
    if (el('registrationsTable') || el('registrationsTableBody')) {
      try { loadRegistrations_API(); } catch (e) { console.error('loadRegistrations_API failed', e); }
    }

    // Attractions page
    if (el('attractionsGrid') || el('searchAttractions')) {
      try {
        loadAttractions_API();
        const searchInput = el('searchAttractions');
        if (searchInput) searchInput.addEventListener('input', window.applyAttractionsFilter_API);
        const catFilter = el('filterCategory'); if (catFilter) catFilter.addEventListener('change', window.applyAttractionsFilter_API);
        const statFilter = el('filterStatus'); if (statFilter) statFilter.addEventListener('change', window.applyAttractionsFilter_API);
      } catch (e) { console.error('loadAttractions_API failed', e); }
    }

    console.log('Admin Script initialized.');
  });

  // ------------------------------
  // Sidebar / Navigation
  // ------------------------------
  function initSidebarToggle() {
    const sidebar = el('sidebar');
    const sidebarToggle = el('sidebarToggle');
    const mainContent = document.querySelector('.main-content') || document.querySelector('#mainContent');
    let overlay = document.querySelector('.sidebar-overlay');

    if (!overlay) {
      overlay = document.createElement('div');
      overlay.className = 'sidebar-overlay';
      document.body.appendChild(overlay);
    }

    function isMobile() { return window.innerWidth < 992; }

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function () {
        if (!sidebar) return;
        if (isMobile()) {
          sidebar.classList.toggle('active');
          overlay.classList.toggle('active');
          document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        } else {
          sidebar.classList.toggle('hidden');
          if (mainContent) mainContent.classList.toggle('expanded');
        }
      });
    }

    overlay.addEventListener('click', closeMobileSidebar);

    window.addEventListener('resize', function () {
      if (!sidebar) return;
      if (window.innerWidth >= 992) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      } else {
        sidebar.classList.remove('hidden');
        if (mainContent) mainContent.classList.remove('expanded');
      }
    });
  }

  function closeMobileSidebar() {
    const sidebar = el('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar) sidebar.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  function initNavigation() {
    const navItems = document.querySelectorAll('.sidebar .nav-item, .sidebar .sidebar-menu-item a');
    navItems.forEach(item => {
      item.addEventListener('click', function (e) {
        const href = this.getAttribute('href') || this.dataset.href;
        navItems.forEach(n => n.classList && n.classList.remove('active'));
        this.classList && this.classList.add('active');

        if (window.innerWidth < 992) closeMobileSidebar();

        if (!href) return;
        if (href.startsWith('#')) {
          e.preventDefault();
          const section = href.replace('#', '');
          showSection(section);
        }
      });
    });
  }

  function showSection(sectionName) {
    document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
    const section = el(`${sectionName}-section`);
    if (section) section.classList.add('active');
    switch (sectionName) {
      case 'registrations': if (typeof loadRegistrations_API === 'function') loadRegistrations_API(); break;
      case 'attractions': if (typeof loadAttractions_API === 'function') loadAttractions_API(); break;
      case 'dashboard': if (typeof loadDashboard === 'function') loadDashboard(); break;
    }
  }

  // ------------------------------
  // DASHBOARD: Charts & Activity
  // ------------------------------
  function loadDashboard() {
    loadCharts();
    loadActivity();
    loadStatsIfPresent();
  }

  function loadCharts() {
    try { loadBookingsChart(); } catch (e) { console.warn('loadBookingsChart failed', e); }
    try { loadCategoryChart(); } catch (e) { /* ignore */ }
    try { /* optional extra charts (guarded) */ } catch (e) { /* ignore */ }
  }

  function loadStatsIfPresent() {
    const stats = window.MOCK_DATA?.stats || window.dashboardData?.stats || null;
    if (!stats || !el('statsContainer')) return;
    const statsHTML = `
      <div class="row g-3">
        <div class="col-sm-6 col-lg-3"><div class="stats-card primary"><div class="stats-card-icon"><i class="bi bi-person-plus"></i></div><div class="stats-card-content"><h3>${safeText(stats.pending_requests ?? 0)}</h3><p>Pending Requests</p></div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="stats-card success"><div class="stats-card-icon"><i class="bi bi-geo-alt"></i></div><div class="stats-card-content"><h3>${safeText(stats.total_attractions ?? 0)}</h3><p>Total Attractions</p></div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="stats-card info"><div class="stats-card-icon"><i class="bi bi-calendar-check"></i></div><div class="stats-card-content"><h3>${safeText(stats.total_bookings ?? 0)}</h3><p>Total Bookings</p></div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="stats-card revenue"><div class="stats-card-icon"><i class="bi bi-currency-dollar"></i></div><div class="stats-card-content"><h3>${safeText(stats.today_bookings ?? 0)}</h3><p>Today's Bookings</p></div></div></div>
      </div>`;
    el('statsContainer').innerHTML = statsHTML;
  }

  function loadBookingsChart() {
    const canvas = el('bookingsChart');
    if (!canvas) return;
    const existing = Chart.getChart(canvas);
    if (existing) existing.destroy();

    const dataRaw = window.monthlyBookingsTrend || window.dashboardData?.monthlyBookingsTrend || null;
    const data = tryParseJSON(dataRaw) || dataRaw;
    if (!Array.isArray(data) || data.length === 0) return;

    const ctx = canvas.getContext('2d');
    chartInstances.bookingsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.map(d => d.month),
        datasets: [{
          label: 'Bookings',
          data: data.map(d => Number(d.total_bookings || d.total || 0)),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.1)',
          tension: 0.35,
          fill: true
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
  }

  function loadCategoryChart() {
    const canvas = el('categoryChart');
    if (!canvas) return;
    if (chartInstances.categoryChart) {
      try { chartInstances.categoryChart.destroy(); } catch(e){}
    }

    const data = window.categoryData || window.dashboardData?.categoryData || [];
    if (!Array.isArray(data) || data.length === 0) return;

    const ctx = canvas.getContext('2d');
    const colors = data.map((_, i) => COLOR_PALETTE[i % COLOR_PALETTE.length]);
    chartInstances.categoryChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.map(d => d.category || 'Unknown'),
        datasets: [{ data: data.map(d => Number(d.total || 0)), backgroundColor: colors, borderWidth: 0 }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
  }

  function loadActivity() {
    const container = el('activityFeed') || el('activityFeedContainer') || null;
    if (!container) {
      console.debug('loadActivity: activity container not found, skipping.');
      return;
    }

    const activities = (window.MOCK_DATA && window.MOCK_DATA.recentActivity) || (window.dashboardData && dashboardData.recentActivity) || [];
    if (!Array.isArray(activities) || activities.length === 0) {
      container.innerHTML = '<p class="text-muted">No recent activity.</p>';
      return;
    }

    const activityHTML = activities.map(activity => `
      <div class="activity-item d-flex align-items-start mb-3">
        <div class="activity-icon me-3"><span class="badge ${activity.color ? 'bg-' + activity.color : 'bg-primary'}"><i class="${activity.icon || 'bi bi-info'}"></i></span></div>
        <div class="activity-content flex-grow-1">
          <p class="activity-title mb-1">${safeText(activity.title)}</p>
          <p class="activity-description mb-0 text-muted">${safeText(activity.description)}</p>
        </div>
        <div class="activity-time ms-3"><small class="text-muted">${safeText(activity.time)}</small></div>
      </div>
    `).join('');
    container.innerHTML = activityHTML;
  }

  // ------------------------------
  // REPORTS: fetchAnalytics and renderChart (kept as before)
  // ------------------------------
  async function fetchAnalytics(startDate, endDate) {
    try {
      const formData = new FormData();
      formData.append('startDate', startDate || '');
      formData.append('endDate', endDate || '');
      const nameMeta = document.querySelector('meta[name="csrf-token-name"]');
      const valueMeta = document.querySelector('meta[name="csrf-token-value"]');
      if (nameMeta && valueMeta) formData.append(nameMeta.getAttribute('content'), valueMeta.getAttribute('content'));

      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/reports/analytics`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!resp.ok) throw new Error(`Server returned ${resp.status}`);
      const result = await resp.json();

      if (result && result.success) {
        if (el('totalBookings')) el('totalBookings').innerText = safeText(result.summary.totalBookings ?? 0);
        if (el('totalRevenue')) el('totalRevenue').innerText = '₱' + Number(result.summary.totalRevenue ?? 0).toLocaleString();
        if (el('arpb')) el('arpb').innerText = '₱' + Number(result.summary.averageRevenuePerBooking ?? 0).toLocaleString();
        if (el('avgRating')) el('avgRating').innerText = safeText(result.summary.averageRating ?? 0);
        if (el('totalAttractions')) el('totalAttractions').innerText = safeText(result.summary.activeAttractions ?? 0);

        renderChart('demographicsChart', 'pie', {
          labels: ['Adults','Children','Seniors'],
          datasets: [{ data: [
            Number(result.charts.visitorDemographics.total_adults || 0),
            Number(result.charts.visitorDemographics.total_children || 0),
            Number(result.charts.visitorDemographics.total_seniors || 0)
          ], backgroundColor: [COLOR_PALETTE[0], COLOR_PALETTE[3], COLOR_PALETTE[6]] }]
        });

        renderChart('peakBookingChart', 'bar', {
          labels: (result.charts.peakBookingDays || []).map(d => d.day),
          datasets: [{ label: 'Bookings', data: (result.charts.peakBookingDays || []).map(d => Number(d.total || 0)), backgroundColor: COLOR_PALETTE[2], borderRadius: 4 }]
        });

        renderChart('revenueByCategoryChart', 'doughnut', {
          labels: (result.charts.revenueByCategory || []).map(c => c.category),
          datasets: [{ data: (result.charts.revenueByCategory || []).map(c => Number(c.total_revenue || 0)), backgroundColor: COLOR_PALETTE }]
        });

        renderChart('leadTimeChart', 'bar', {
          labels: (result.charts.bookingLeadTime || []).map(d => d.lead_time_group),
          datasets: [{ label: 'Bookings', data: (result.charts.bookingLeadTime || []).map(d => Number(d.total || 0)), backgroundColor: COLOR_PALETTE[0], borderRadius: 4 }]
        });
      }
    } catch (e) {
      console.error('Analytics Fetch Error:', e);
      showToast('Failed to load analytics', 'danger');
    }
  }

  function renderChart(canvasId, type, data) {
    const canvas = el(canvasId);
    if (!canvas) return;

    const existing = Chart.getChart(canvas);
    if (existing) existing.destroy();
    if (chartInstances[canvasId]) {
      try { chartInstances[canvasId].destroy(); } catch(e) {}
      delete chartInstances[canvasId];
    }

    const ctx = canvas.getContext('2d');
    chartInstances[canvasId] = new Chart(ctx, {
      type: type,
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: type === 'bar' ? { y: { beginAtZero: true }, x: { grid: { display: false } } } : {}
      }
    });
  }

  // ------------------------------
  // REGISTRATIONS (API)
  // ------------------------------
  let registrationsCache = [];
  let currentRegistrationId = null;

  async function loadRegistrations_API() {
    const tableBody = el('registrationsTable');
    if (!tableBody) return;
    tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">Loading...</td></tr>`;
    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/registrations/list`);
      if (!resp.ok) throw new Error(resp.statusText || 'API Error');
      registrationsCache = await resp.json();
      renderRegistrations_API(registrationsCache);
    } catch (e) {
      console.error('Failed to load registrations:', e);
      tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger p-4">Could not load data.</td></tr>`;
    }
  }

  function getStatusBadgeClass(status) {
    if (!status) return 'bg-secondary';
    const s = String(status).toLowerCase();
    if (s === 'approved' || s === 'active') return 'bg-success text-white';
    if (s === 'pending') return 'bg-warning text-dark';
    if (s === 'rejected' || s === 'suspended') return 'bg-danger text-white';
    return 'bg-secondary text-white';
  }

  function renderRegistrations_API(registrations) {
    const tableBody = el('registrationsTable');
    if (!tableBody) return;
    if (!registrations || registrations.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">No registrations found.</td></tr>`;
      return;
    }

    tableBody.innerHTML = registrations.map((reg, index) => {
      const statusBadge = `<span class="badge ${getStatusBadgeClass(reg.status)}">${safeText((reg.status || '').toString())}</span>`;
      const actions = (String(reg.status || '').toLowerCase() === 'pending') ? `
        <button class="btn btn-outline-success btn-sm" onclick="openApproveModal_API(${reg.business_id})"><i class="bi bi-check-circle"></i></button>
        <button class="btn btn-outline-danger btn-sm" onclick="openRejectModal_API(${reg.business_id})"><i class="bi bi-x-circle"></i></button>
      ` : '';
      return `
        <tr>
          <td class="text-muted">${index + 1}</td>
          <td><strong>${safeText(reg.business_name || 'N/A')}</strong></td>
          <td>${safeText((reg.FirstName || '') + ' ' + (reg.LastName || ''))}</td>
          <td><small class="text-muted">${safeText(reg.contact_email || 'N/A')}</small></td>
          <td><small class="text-muted">${safeText(reg.contact_phone || 'N/A')}</small></td>
          <td><small class="text-muted">${safeText(reg.business_address || reg.location || 'N/A')}</small></td>
          <td><small>${formatDateShort(reg.created_at)}</small></td>
          <td class="text-center">${statusBadge}</td>
          <td class="text-center"><div class="btn-group btn-group-sm" role="group">
            <button class="btn btn-outline-primary btn-sm" onclick="viewRegistrationDetails_API(${reg.business_id})"><i class="bi bi-eye"></i></button>
            ${actions}
          </div></td>
        </tr>
      `;
    }).join('');
  }

  window.openApproveModal_API = function (id) { currentRegistrationId = id; const m = new bootstrap.Modal(el('approveModal')); m && m.show(); };
  window.openRejectModal_API = function (id) { currentRegistrationId = id; if (el('rejectReason')) el('rejectReason').value = ''; const m = new bootstrap.Modal(el('rejectModal')); m && m.show(); };

  async function sendRegistrationAction(action, reason = null) {
    if (!currentRegistrationId) return;
    try {
      const nameMeta = document.querySelector('meta[name="csrf-token-name"]');
      const valueMeta = document.querySelector('meta[name="csrf-token-value"]');
      const formData = new FormData();
      if (reason) formData.append('reason', reason);
      if (nameMeta && valueMeta) formData.append(nameMeta.getAttribute('content'), valueMeta.getAttribute('content'));

      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/registrations/${action}/${currentRegistrationId}`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const result = await resp.json();
      if (!resp.ok) throw new Error(result.error || result.message || 'API error');
      const modalId = action === 'approve' ? 'approveModal' : 'rejectModal';
      const modalEl = el(modalId);
      bootstrap.Modal.getInstance(modalEl) && bootstrap.Modal.getInstance(modalEl).hide();
      await loadRegistrations_API();
      showToast(result.success || 'Action completed', 'success');
    } catch (e) {
      console.error('Registration action error', e);
      showToast(e.message || 'Failed to perform action', 'danger');
    }
  }

  window.confirmApprove_API = async function() { await sendRegistrationAction('approve'); };
  window.confirmReject_API = async function() {
    const reason = el('rejectReason') ? el('rejectReason').value.trim() : '';
    if (!reason) { alert('Reason required'); return; }
    await sendRegistrationAction('reject', reason);
  };

  async function viewRegistrationDetails_API(id) {
    const contentArea = el('viewRegistrationContent');
    if (contentArea) contentArea.innerHTML = '<div class="text-center p-4">Loading...</div>';
    const viewModalEl = el('viewRegistrationModal');
    if (viewModalEl) new bootstrap.Modal(viewModalEl).show();

    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/registrations/view/${id}`);
      if (!resp.ok) throw new Error('Registration not found');
      const reg = await resp.json();

      const html = `
        <h5>Business Information</h5>
        <p><strong>Business Name:</strong> ${safeText(reg.business_name || 'N/A')}</p>
        <p><strong>Location:</strong> ${safeText(reg.business_address || 'N/A')}</p>
        <hr/>
        <h5>Owner Information</h5>
        <p><strong>Email:</strong> ${safeText(reg.contact_email || 'N/A')}</p>
        <p><strong>Phone:</strong> ${safeText(reg.contact_phone || 'N/A')}</p>
        <hr/>
        <h5>Status & History</h5>
        <p><strong>Submitted:</strong> ${formatDateShort(reg.created_at)}</p>
        <p><strong>Status:</strong> <span class="badge ${getStatusBadgeClass(reg.status)}">${safeText(reg.status)}</span></p>
        ${reg.status === 'rejected' ? `<p class="text-danger"><strong>Rejection Reason:</strong> ${safeText(reg.rejection_reason || 'No reason provided.')}</p>` : ''}
      `;
      if (contentArea) contentArea.innerHTML = html;
    } catch (e) {
      console.error('Failed to fetch registration details', e);
      if (contentArea) contentArea.innerHTML = '<div class="text-center p-4 text-danger">Could not load details.</div>';
    }
  }

  window.viewRegistrationDetails_API = viewRegistrationDetails_API;

  // ------------------------------
  // ATTRACTIONS
  // ------------------------------
  let currentAttractionId = null;

  async function loadAttractions_API() {
    const grid = el('attractionsGrid');
    if (!grid) return;
    grid.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border" role="status"></div></div>';
    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/attractions/list`);
      if (!resp.ok) throw new Error('Failed to fetch');
      allAttractions = await resp.json();
      renderAttractions_API(allAttractions);
    } catch (e) {
      console.error('Load Attractions Error:', e);
      grid.innerHTML = '<div class="col-12 text-center p-5 text-danger">Could not load attractions.</div>';
    }
  }

  function renderAttractions_API(attractions) {
    const grid = el('attractionsGrid');
    if (!grid) return;
    if (!attractions || attractions.length === 0) {
      grid.innerHTML = '<div class="col-12 text-center p-5 text-muted">No attractions found.</div>';
      return;
    }

    grid.innerHTML = attractions.map(attraction => {
      const uploadsPath = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'uploads/spots/';
      const primaryImage = attraction.primary_image ? `${uploadsPath}${attraction.primary_image}` : `${uploadsPath}Spot-No-Image.png`;
      const safeImage = `onerror="this.onerror=null;this.src='${uploadsPath}Spot-No-Image.png'"`;
      return `
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card attraction-card h-100">
            <img src="${primaryImage}" ${safeImage} class="card-img-top" alt="${safeText(attraction.spot_name)}" style="height:180px;object-fit:cover">
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-primary">${safeText(attraction.category || 'N/A')}</span>
                <span class="badge ${getStatusBadgeClass(attraction.status)}">${safeText(attraction.status)}</span>
              </div>
              <h5 class="card-title">${safeText(attraction.spot_name || 'Unnamed')}</h5>
              <p class="text-muted small mb-2 flex-grow-1"><i class="bi bi-geo-alt me-1"></i>${safeText(attraction.location || 'No location')}</p>
              <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-sm btn-outline-info flex-fill" onclick="viewAttraction_API(${attraction.spot_id})"><i class="bi bi-eye me-1"></i>View</button>
                <button class="btn btn-sm btn-outline-warning" onclick="openSuspendModal_API(${attraction.spot_id})"><i class="bi bi-pause"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal_API(${attraction.spot_id})"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
        </div>`;
    }).join('');
  }

  window.viewAttraction_API = async function (id) {
    const contentArea = el('viewAttractionContent');
    if (contentArea) contentArea.innerHTML = '<div class="text-center p-4">Loading...</div>';
    const viewModalEl = el('viewAttractionModal'); if (viewModalEl) new bootstrap.Modal(viewModalEl).show();

    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/attractions/view/${id}`);
      if (!resp.ok) throw new Error('Attraction details not found.');
      const attraction = await resp.json();

      const images = Array.isArray(attraction.images) && attraction.images.length ? attraction.images : [ (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + 'uploads/spots/Spot-No-Image.png' ];
      let indicators = '', items = '';
      images.forEach((img, i) => {
        indicators += `<button type="button" data-bs-target="#attractionCarousel" data-bs-slide-to="${i}" ${i===0 ? 'class="active" aria-current="true"' : ''} aria-label="Slide ${i+1}"></button>`;
        items += `<div class="carousel-item ${i===0 ? 'active' : ''}"><img src="${img}" class="d-block w-100 rounded" alt="${safeText(attraction.spot_name)}"></div>`;
      });

      const detailsHTML = `
        <div class="row">
          <div class="col-md-6 mb-3">
            <div id="attractionCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-indicators">${indicators}</div>
              <div class="carousel-inner">${items}</div>
              <button class="carousel-control-prev" type="button" data-bs-target="#attractionCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
              <button class="carousel-control-next" type="button" data-bs-target="#attractionCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
            </div>
          </div>
          <div class="col-md-6">
            <h4>${safeText(attraction.spot_name)}</h4>
            <p><span class="badge bg-primary">${safeText(attraction.category)}</span></p>
            <p><strong>Owner:</strong> ${safeText((attraction.FirstName || '') + ' ' + (attraction.LastName || ''))}</p>
            <p><strong>Location:</strong> ${safeText(attraction.location)}</p>
            <p><strong>Capacity:</strong> ${safeText(attraction.capacity || 'N/A')}</p>
            <p><strong>Price:</strong> ₱${safeText(attraction.price_per_person || '0.00')}</p>
            <p><strong>Status:</strong> <span class="badge ${getStatusBadgeClass(attraction.status)}">${safeText(attraction.status)}</span></p>
          </div>
        </div>`;
      if (contentArea) contentArea.innerHTML = detailsHTML;
    } catch (e) {
      console.error('View Attraction Error:', e);
      if (contentArea) contentArea.innerHTML = '<p class="text-danger">Could not load attraction details.</p>';
    }
  };

  window.openSuspendModal_API = function(id) { currentAttractionId = id; if (el('suspendReason')) el('suspendReason').value = ''; const m = new bootstrap.Modal(el('suspendModal')); m && m.show(); };
  window.openDeleteModal_API = function(id) { currentAttractionId = id; if (el('deleteConfirmText')) el('deleteConfirmText').value = ''; const m = new bootstrap.Modal(el('deleteModal')); m && m.show(); };

  window.confirmSuspend_API = async function() {
    const reason = el('suspendReason') ? el('suspendReason').value.trim() : '';
    if (!reason) { alert('A reason for suspension is required.'); return; }
    try {
      const nameMeta = document.querySelector('meta[name="csrf-token-name"]');
      const valueMeta = document.querySelector('meta[name="csrf-token-value"]');
      const formData = new FormData(); formData.append('reason', reason);
      if (nameMeta && valueMeta) formData.append(nameMeta.getAttribute('content'), valueMeta.getAttribute('content'));
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/attractions/suspend/${currentAttractionId}`, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const result = await resp.json(); if (!resp.ok) throw new Error(result.error || 'Failed to suspend');
      bootstrap.Modal.getInstance(el('suspendModal')) && bootstrap.Modal.getInstance(el('suspendModal')).hide();
      await loadAttractions_API(); showToast('Attraction suspended.', 'warning');
    } catch (e) { console.error(e); alert(e.message || 'Error'); }
  };

  window.confirmDelete_API = async function() {
    const confirmText = el('deleteConfirmText') ? el('deleteConfirmText').value : '';
    if (confirmText !== 'DELETE') { alert('Type DELETE to confirm'); return; }
    try {
      const nameMeta = document.querySelector('meta[name="csrf-token-name"]');
      const valueMeta = document.querySelector('meta[name="csrf-token-value"]');
      const formData = new FormData(); if (nameMeta && valueMeta) formData.append(nameMeta.getAttribute('content'), valueMeta.getAttribute('content'));
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/attractions/delete/${currentAttractionId}`, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const result = await resp.json(); if (!resp.ok) throw new Error(result.error || 'Failed to delete');
      bootstrap.Modal.getInstance(el('deleteModal')) && bootstrap.Modal.getInstance(el('deleteModal')).hide();
      await loadAttractions_API(); showToast('Attraction permanently deleted.', 'danger');
    } catch (e) { console.error(e); alert(e.message || 'Error'); }
  };

  // Expose commonly-used functions on window for inline handlers
  window.loadDashboard = loadDashboard;
  window.loadAttractions_API = loadAttractions_API;
  window.loadRegistrations_API = loadRegistrations_API;
  window.fetchAnalytics = fetchAnalytics;
  window.applyAttractionsFilter_API = window.applyAttractionsFilter_API;
  window.showToast = showToast;

  console.log('Admin script module loaded.');
})();