// ========================================
// GLOBAL INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log("Initializing Admin Script...");
    initSidebarToggle();

    // 1. DASHBOARD PAGE
    if (document.getElementById('bookingsChart') || document.getElementById('peakVisitChart')) {
        loadDashboardCharts();
    } 
    
    // 2. REPORTS PAGE
    if (document.getElementById('reportFromDate')) {
        initReportsPage();
    }

    // 3. REGISTRATIONS PAGE
    if (document.getElementById('registrationsTable')) {
        loadRegistrations_API();
    }

    // 4. ATTRACTIONS PAGE
    if (document.getElementById('attractionsGrid') || document.getElementById('searchAttractions')) {
        loadAttractions_API();
        
        // Attach filter listeners
        const searchInput = document.getElementById('searchAttractions');
        if(searchInput) searchInput.addEventListener('keyup', applyAttractionsFilter_API);
        
        const catFilter = document.getElementById('filterCategory');
        if(catFilter) catFilter.addEventListener('change', applyAttractionsFilter_API);
        
        const statFilter = document.getElementById('filterStatus');
        if(statFilter) statFilter.addEventListener('change', applyAttractionsFilter_API);
    }
});

// ========================================
// PART A: DASHBOARD CHARTS
// ========================================
function loadDashboardCharts() {
    const palette = [
        '#4e73df', // Blue
        '#1cc88a', // Green
        '#36b9cc', // Teal
        '#f6c23e', // Yellow
        '#e74a3b', // Red
        '#6f42c1', // Purple
        '#858796'  // Gray
    ];

    // 1. Peak Visit Times (Bar Chart)
    if (document.getElementById('peakVisitChart') && window.dashboardData?.peakVisitTimes) {
        const canvas = document.getElementById('peakVisitChart');
        const ctx = canvas.getContext('2d');
        
        // Create a Gradient Fill (Top to Bottom)
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 1)');   // Strong Blue at top
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0.1)'); // Faded Blue at bottom

        const rawData = window.dashboardData.peakVisitTimes;
        const data = (typeof rawData === 'string') ? JSON.parse(rawData) : rawData;

        // Check if chart exists and destroy it to prevent glitches
        const existingChart = Chart.getChart(canvas);
        if (existingChart) existingChart.destroy();

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.day),
                datasets: [{
                    label: 'Visits',
                    data: data.map(d => d.total_visits),
                    backgroundColor: gradient, // Apply the gradient
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    borderRadius: 8, // Rounded corners at the top
                    barPercentage: 0.6, // Make bars slightly thinner
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.9)',
                        titleColor: '#6e707e',
                        bodyColor: '#858796',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5], // Dotted grid lines
                            color: '#eaecf4',
                            drawBorder: false
                        },
                        ticks: { color: '#858796', padding: 10 }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }, // Remove vertical lines
                        ticks: { color: '#858796' }
                    }
                }
            }
        });
    }

    // 2. User Preferences (Doughnut Chart)
    if (document.getElementById('userPreferenceChart') && window.dashboardData?.userPreferences) {
        const canvas = document.getElementById('userPreferenceChart');
        const ctx = canvas.getContext('2d');
        
        const rawData = window.dashboardData.userPreferences;
        const data = (typeof rawData === 'string') ? JSON.parse(rawData) : rawData;

        // Check if chart exists and destroy it
        const existingChart = Chart.getChart(canvas);
        if (existingChart) existingChart.destroy();

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(d => d.category),
                datasets: [{
                    data: data.map(d => d.total),
                    backgroundColor: palette, // Use the colorful palette
                    hoverBackgroundColor: palette,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 4, // White space between segments
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%', // Thinner ring for modern look
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true, // Use circles instead of squares in legend
                            padding: 20,
                            color: '#858796'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.9)',
                        bodyColor: '#858796',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                let value = context.raw;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let percentage = Math.round((value / total) * 100) + '%';
                                return label + value + ' (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    }
}

// ========================================
// PART B: REPORTS PAGE LOGIC
// ========================================
function initReportsPage() {
    const toDate = new Date();
    const fromDate = new Date();
    fromDate.setDate(toDate.getDate() - 29);
    
    if(document.getElementById('reportToDate')) document.getElementById('reportToDate').valueAsDate = toDate;
    if(document.getElementById('reportFromDate')) document.getElementById('reportFromDate').valueAsDate = fromDate;
    
    const applyBtn = document.getElementById('applyFilterBtn');
    if(applyBtn) applyBtn.addEventListener('click', applyFilter);
    
    applyFilter(); // Load initial data
}

function applyFilter() {
    const startDate = document.getElementById('reportFromDate').value;
    const endDate = document.getElementById('reportToDate').value;
    fetchAnalytics(startDate, endDate);
}

async function fetchAnalytics(startDate, endDate) {
    const csrfMetaName = document.querySelector('meta[name="csrf-token-name"]');
    const csrfMetaValue = document.querySelector('meta[name="csrf-token-value"]');
    
    const formData = new FormData();
    formData.append('startDate', startDate);
    formData.append('endDate', endDate);
    if(csrfMetaName) formData.append(csrfMetaName.content, csrfMetaValue.content);

    try {
        const response = await fetch(`${BASE_URL}admin/reports/analytics`, {
            method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();
        
        if(result.success) {
            // 1. Update Summary Cards
            if(document.getElementById('totalBookings')) document.getElementById('totalBookings').innerText = result.summary.totalBookings;
            if(document.getElementById('totalRevenue')) document.getElementById('totalRevenue').innerText = '₱' + parseFloat(result.summary.totalRevenue).toLocaleString();
            if(document.getElementById('arpb')) document.getElementById('arpb').innerText = '₱' + parseFloat(result.summary.averageRevenuePerBooking).toLocaleString();
            if(document.getElementById('avgRating')) document.getElementById('avgRating').innerText = result.summary.averageRating;
            if(document.getElementById('totalAttractions')) document.getElementById('totalAttractions').innerText = result.summary.activeAttractions;

            // 2. Update Charts
            const modernColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

            renderChart('demographicsChart', 'pie', {
                labels: ['Adults', 'Children', 'Seniors'],
                datasets: [{ 
                    data: [result.charts.visitorDemographics.total_adults, result.charts.visitorDemographics.total_children, result.charts.visitorDemographics.total_seniors], 
                    backgroundColor: ['#4e73df', '#f6c23e', '#858796'] 
                }]
            });

            renderChart('peakBookingChart', 'bar', {
                labels: result.charts.peakBookingDays.map(d => d.day),
                datasets: [{ label: 'Bookings', data: result.charts.peakBookingDays.map(d => d.total), backgroundColor: '#36b9cc', borderRadius: 4 }]
            });
            
            renderChart('revenueByCategoryChart', 'doughnut', {
                labels: result.charts.revenueByCategory.map(c => c.category),
                datasets: [{
                    data: result.charts.revenueByCategory.map(c => c.total_revenue),
                    backgroundColor: modernColors
                }]
            });

            renderChart('leadTimeChart', 'bar', {
                labels: result.charts.bookingLeadTime.map(d => d.lead_time_group),
                datasets: [{
                    label: 'Bookings',
                    data: result.charts.bookingLeadTime.map(d => d.total),
                    backgroundColor: modernColors[0],
                    borderRadius: 4
                }]
            });

        }
    } catch (e) {
        console.error("Analytics Fetch Error:", e);
    }
}

// *** CRITICAL FIX FOR "CANVAS ALREADY IN USE" ERROR ***
let chartInstances = {};
function renderChart(canvasId, type, data) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    // 1. Destroy existing chart instance stored in Chart.js registry
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }

    // 2. Destroy local reference if exists (double safety)
    if (chartInstances[canvasId]) {
        delete chartInstances[canvasId];
    }

    // 3. Create new chart
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

// ========================================
// PART C: REGISTRATIONS API
// ========================================
let allRegistrations = [];
let currentRegistrationId = null;

async function loadRegistrations_API() {
    const tableBody = document.getElementById("registrationsTable");
    if (!tableBody) return;
    tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">Loading...</td></tr>`;
    
    try {
        const response = await fetch(`${BASE_URL}admin/registrations/list`);
        if (!response.ok) throw new Error(`API Error: ${response.statusText}`);
        allRegistrations = await response.json();
        renderRegistrations_API(allRegistrations);
    } catch (error) {
        console.error("Failed to load registrations:", error);
        tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger p-4">Could not load data.</td></tr>`;
    }
}

function renderRegistrations_API(registrations) {
    const tableBody = document.getElementById("registrationsTable");
    if (!tableBody) return;
    if (!registrations || registrations.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="9" class="text-center p-4">No registrations found.</td></tr>`;
        return;
    }
    tableBody.innerHTML = registrations.map((reg, index) => `
        <tr>
            <td class="text-muted">${index + 1}</td>
            <td><strong>${reg.business_name || "N/A"}</strong></td>
            <td>${(reg.FirstName || "") + " " + (reg.LastName || "")}</td>
            <td><small>${reg.contact_email || "N/A"}</small></td>
            <td><small>${reg.contact_phone || "N/A"}</small></td>
            <td><small>${reg.location || reg.business_address || "N/A"}</small></td>
            <td><small>${formatDate(reg.created_at)}</small></td>
            <td class="text-center"><span class="badge ${getStatusClass(reg.status)}">${capitalize(reg.status)}</span></td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewRegistrationDetails_API(${reg.business_id})"><i class="bi bi-eye"></i></button>
                    ${reg.status.toLowerCase() === 'pending' ? `
                    <button class="btn btn-outline-success" onclick="openApproveModal_API(${reg.business_id})"><i class="bi bi-check-circle"></i></button>
                    <button class="btn btn-outline-danger" onclick="openRejectModal_API(${reg.business_id})"><i class="bi bi-x-circle"></i></button>` : ''}
                </div>
            </td>
        </tr>`).join("");
}

// Registration Modals
function openApproveModal_API(id) { currentRegistrationId = id; new bootstrap.Modal(document.getElementById("approveModal")).show(); }
function openRejectModal_API(id) { currentRegistrationId = id; document.getElementById("rejectReason").value = ""; new bootstrap.Modal(document.getElementById("rejectModal")).show(); }

async function confirmApprove_API() { if (!currentRegistrationId) return; await sendRegistrationAction('approve'); }
async function confirmReject_API() { if (!currentRegistrationId) return; await sendRegistrationAction('reject', document.getElementById("rejectReason").value); }

async function sendRegistrationAction(action, reason = null) {
    const csrfName = document.querySelector('meta[name="csrf-token-name"]').content;
    const csrfHash = document.querySelector('meta[name="csrf-token-value"]').content;
    const formData = new FormData();
    if(reason) formData.append('reason', reason);
    formData.append(csrfName, csrfHash);

    try {
        const response = await fetch(`${BASE_URL}admin/registrations/${action}/${currentRegistrationId}`, {
            method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        const result = await response.json();
        if(response.ok) {
            bootstrap.Modal.getInstance(document.getElementById(action === 'approve' ? "approveModal" : "rejectModal")).hide();
            loadRegistrations_API();
            alert("Success: " + (result.success || "Action completed"));
        } else {
            alert("Error: " + result.error);
        }
    } catch(e) { alert("Network Error"); }
}

// ========================================
// PART D: ATTRACTIONS API
// ========================================
let allAttractions = [];
let currentAttractionId = null;

async function loadAttractions_API() {
    const grid = document.getElementById("attractionsGrid");
    if (!grid) return;
    grid.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border"></div></div>';
    try {
        const response = await fetch(`${BASE_URL}admin/attractions/list`);
        if (!response.ok) throw new Error("Failed to fetch");
        allAttractions = await response.json();
        renderAttractions_API(allAttractions);
    } catch (error) {
        grid.innerHTML = '<p class="text-danger text-center">Could not load attractions.</p>';
    }
}

function renderAttractions_API(attractions) {
    const grid = document.getElementById("attractionsGrid");
    if (!grid) return;
    if (!attractions || attractions.length === 0) {
        grid.innerHTML = '<p class="text-muted text-center">No attractions found.</p>';
        return;
    }
    grid.innerHTML = attractions.map(attraction => {
        const img = attraction.primary_image ? `${BASE_URL}uploads/spots/${attraction.primary_image}` : `${BASE_URL}uploads/spots/Spot-No-Image.png`;
        return `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <img src="${img}" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.src='${BASE_URL}uploads/spots/Spot-No-Image.png'">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${attraction.spot_name}</h5>
                    <p class="text-muted small flex-grow-1">${attraction.location}</p>
                    <div class="d-flex gap-2 mt-auto">
                        <button class="btn btn-sm btn-outline-info" onclick="viewAttraction_API(${attraction.spot_id})">View</button>
                        <button class="btn btn-sm btn-outline-warning" onclick="openSuspendModal_API(${attraction.spot_id})">Suspend</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal_API(${attraction.spot_id})">Delete</button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join("");
}

function applyAttractionsFilter_API() {
    const term = document.getElementById("searchAttractions")?.value.toLowerCase() || "";
    const cat = document.getElementById("filterCategory")?.value || "";
    const status = document.getElementById("filterStatus")?.value || "";
    
    const filtered = allAttractions.filter(a => 
        (a.spot_name.toLowerCase().includes(term) || a.location.toLowerCase().includes(term)) &&
        (!cat || a.category === cat) &&
        (!status || a.status === status)
    );
    renderAttractions_API(filtered);
}

// ========================================
// UTILITIES
// ========================================
function initSidebarToggle() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if(toggle && sidebar) {
        toggle.addEventListener('click', () => { sidebar.classList.toggle('active'); if(overlay) overlay.classList.toggle('active'); });
    }
    if(overlay) overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); });
}

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return isNaN(date.getTime()) ? "Invalid Date" : date.toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
}

function getStatusClass(status) {
    if (!status) return "bg-secondary";
    switch (status.toLowerCase()) {
        case "approved": case "active": return "bg-success";
        case "pending": return "bg-warning text-dark";
        case "rejected": case "suspended": return "bg-danger";
        default: return "bg-secondary";
    }
}
function capitalize(s) { return (typeof s === "string" && s) ? s.charAt(0).toUpperCase() + s.slice(1) : ""; }