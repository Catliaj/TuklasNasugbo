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
    '#4e73df', // Blue
    '#1cc88a', // Green
    '#36b9cc', // Teal
    '#f6c23e', // Yellow
    '#e74a3b', // Red
    '#6f42c1', // Purple
    '#858796'  // Gray
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
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        if (overlay) overlay.classList.toggle('active');
      });
    }
    if (overlay) {
      overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
      });
    }
  }

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

  async function loadRegistrations_API() {
    const tableBody = el('registrationsTable');
    if (!tableBody) return;
    tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">Loading...</td></tr>`;
    try {
      const resp = await fetch(`${(typeof BASE_URL !== 'undefined' ? BASE_URL : '/') }admin/registrations/list`);
      if (!resp.ok) throw new Error('API Error');
      registrationsCache = await resp.json();
      renderRegistrations_API(registrationsCache);
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
    if (!list.length) { grid.innerHTML = '<div class="text-center p-5">No attractions found.</div>'; return; }
    
    grid.innerHTML = list.map(a => {
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
      const matchCat = !cat || a.category === cat;
      const matchStat = !stat || a.status === stat;
      return matchName && matchCat && matchStat;
    });
    renderAttractions_API(filtered);
  };

  window.viewAttraction_API = async function(id) {
    const content = el('viewAttractionContent');
    content.innerHTML = 'Loading...';
    new bootstrap.Modal(el('viewAttractionModal')).show();
    try {
      const resp = await fetch(`${BASE_URL}admin/attractions/view/${id}`);
      const data = await resp.json();
      content.innerHTML = `
        <h4>${safeText(data.spot_name)}</h4>
        <p>Category: ${safeText(data.category)}</p>
        <p>Status: ${safeText(data.status)}</p>
        <p>Price: ₱${safeText(data.price_per_person)}</p>
      `;
    } catch(e) { content.innerHTML = 'Error loading.'; }
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

  // ------------------------------
  // 8. UTILS
  // ------------------------------
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