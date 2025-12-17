<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?> - Reports & Analytics</title>
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="<?= base_url('assets/js/admin-charts.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    <?php
        $settingsPath = WRITEPATH . 'settings.json';
        $currentSettings = [];
        if (file_exists($settingsPath)) {
            $currentSettings = json_decode(file_get_contents($settingsPath), true) ?: [];
        }
    ?>
    <style>:root { --primary-blue: <?= esc($currentSettings['primary_color'] ?? '#004a7c') ?>; }</style>
    <script>const BASE_URL = '<?= base_url() ?>';</script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;background:linear-gradient(180deg,#f8fafc 0%, #ffffff 100%);color:#23303b}
        .chart-container { position: relative; height: 320px; max-height: 420px; }
        .chart-container.flex-grow-1 { min-height: 220px; }
        .chart-container canvas { display:block; width:100% !important; height:100% !important; max-height:100% !important }
        /* Strong safety: lock per-chart heights to avoid any resize loop */
        .chart-container { overflow: hidden; }
        #monthlyBookingsChart, #performanceRadarChart, #revenueByCategoryChart, #demographicsChart, #peakBookingChart, #leadTimeChart { height: 320px !important; max-height: 380px !important }
        .chart-container.flex-grow-1 { min-height: 240px !important }
        .kpi-spark { height: 36px !important; max-height: 36px !important }
        .card-modern { border-radius: 0.85rem; box-shadow: 0 10px 30px rgba(35,48,59,0.05); border: none }
        .analytics-card { border-radius: 0.75rem; }
        .section-title { font-weight:700 }
        /* Fix flex overflow for charts */
        .card-body { min-height: 0; }
    </style>
    <style>
        .chart-container { position: relative; height: 320px; }
        .analytics-card { display: flex; align-items: center; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.04); height: 100%; }
        .analytics-card .analytics-icon { flex-shrink: 0; width: 48px; height: 48px; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; }
        .analytics-card .analytics-icon i { font-size: 1.5rem; color: #fff; }
        .analytics-card .analytics-content { flex-grow: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center; }
        .analytics-card .analytics-value { font-size: 1.5rem; font-weight: 700; line-height: 1.2; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .analytics-card .analytics-label { font-size: 0.8rem; color: #6c757d; line-height: 1.3; margin: 0; }
        .analytics-card.primary { border-color: #dbe4ff; background-color: #f3f6ff; } .analytics-card.primary .analytics-icon { background-color: #364fc7; } .analytics-card.primary .analytics-value { color: #364fc7; }
        .analytics-card.info { border-color: #c5f6fa; background-color: #f1f9fa; } .analytics-card.info .analytics-icon { background-color: #17a2b8; } .analytics-card.info .analytics-value { color: #107280; }
        .analytics-card.success { border-color: #d3f9d8; background-color: #f2fbf5; } .analytics-card.success .analytics-icon { background-color: #28a745; } .analytics-card.success .analytics-value { color: #1c7430; }
        .analytics-card.warning { border-color: #ffec99; background-color: #fff9f0; } .analytics-card.warning .analytics-icon { background-color: #ffc107; } .analytics-card.warning .analytics-value { color: #b98b04; }
        .analytics-card.secondary { border-color: #e9ecef; background-color: #f6f7f8; } .analytics-card.secondary .analytics-icon { background-color: #6c757d; } .analytics-card.secondary .analytics-value { color: #495057; }
    </style>
        <style>
        /* Logo sizing and visibility in sidebar */
        .sidebar-header img {
            display: block;
            width: 200px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            /* subtle padded background and rounded corners to ensure contrast */
            /* background-color: #D4C5A9; */
            background-color: white;
            padding: 6px 8px;
            border-radius: 6px;
            /* small outline and shadow to separate from same-color sidebar */
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* If the sidebar uses a very light background, invert the logo slightly to keep contrast */
        .sidebar.light-theme .sidebar-header img { filter: drop-shadow(0 1px 1px rgba(0,0,0,0.25)); }
    </style>
</head>
<body>
     <div class="sidebar" id="sidebar" role="navigation" aria-label="Admin sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/Tuklas_logo.png')?>" alt="Tuklas Nasugbu Logo">
            
        </div>
        
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item " aria-label="Dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/registrations" class="nav-item" aria-label="Registrations">
                <i class="bi bi-person-plus"></i>
                <span>Registrations</span>
                <span class="badge-pending-registrations badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/attractions" class="nav-item " aria-current="page">
                <i class="bi bi-geo-alt"></i>
                <span>Attractions</span>
                <span class="badge-pending-attractions badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/reports" class="nav-item active" aria-label="Reports &amp; Analytics">
                <i class="bi bi-file-bar-graph"></i>
                <span>Reports & Analytics</span>
            </a>
        </nav>
        
        <div class="sidebar-footer"><!-- Logout moved to profile menu; removed duplicate link here --></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <div class="top-bar" role="banner">
            <button class="btn btn-link text-dark" id="sidebarToggle" aria-controls="sidebar" aria-label="Toggle sidebar"><i class="bi bi-list fs-4"></i></button>

            <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                    <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationButtonReports" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="bi bi-bell-fill text-white"></i>
                        <span class="notification-badge badge rounded-pill bg-danger"><?= (isset($unreadNotifications) && $unreadNotifications > 0) ? esc($unreadNotifications) : '' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuReports" style="min-width:320px">
                        <li class="dropdown-item text-muted small">No new notifications</li>
                    </ul>
                </div>

                <div class="dropdown">
                    <?php $adminName = 'Admin User'; $nameParts = array_filter(explode(' ', trim($adminName))); $initials = strtoupper(substr($nameParts[0] ?? '',0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : '')); ?>
                    <button class="btn p-0 border-0 topbar-avatar topbar-avatar--primary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                        <span><?= esc($initials) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width:220px">
                        <li class="px-3 py-2">
                            <div class="fw-bold">Admin User</div>
                            <div class="small text-muted">admin@example.com</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="/admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="page-header mb-4">
                <div>
                    <h1 class="page-title fw-700">📊 Reports & Analytics</h1>
                    <p class="text-muted mb-0">Comprehensive analytics and downloadable business insights</p>
                </div>
            </div>

            <div class="card search-filter-card mb-4">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600 d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-range text-primary"></i>
                                <span>From Date</span>
                            </label>
                            <input type="date" class="form-control form-control-enhanced" id="reportFromDate">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-range text-primary"></i>
                                <span>To Date</span>
                            </label>
                            <input type="date" class="form-control form-control-enhanced" id="reportToDate">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" id="applyFilterBtn">
                                <i class="bi bi-funnel"></i>
                                <span>Apply Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg col-md-6 mb-3"><div class="analytics-card primary border-0 shadow-sm rounded-3"><div class="analytics-icon rounded-2"><i class="bi bi-calendar-check-fill"></i></div><div class="analytics-content"><div class="analytics-value text-primary fw-800" id="totalBookings">•••</div><div class="analytics-label text-muted small">Total Bookings</div></div></div></div>
                <div class="col-lg col-md-6 mb-3"><div class="analytics-card info border-0 shadow-sm rounded-3"><div class="analytics-icon rounded-2"><i class="bi bi-currency-dollar"></i></div><div class="analytics-content"><div class="analytics-value text-info fw-800" id="totalRevenue">•••</div><div class="analytics-label text-muted small">Total Revenue</div></div></div></div>
                <div class="col-lg col-md-6 mb-3"><div class="analytics-card success border-0 shadow-sm rounded-3"><div class="analytics-icon rounded-2"><i class="bi bi-cash"></i></div><div class="analytics-content"><div class="analytics-value text-success fw-800" id="arpb">•••</div><div class="analytics-label text-muted small">Avg. Revenue/Booking</div></div></div></div>
                <div class="col-lg col-md-6 mb-3"><div class="analytics-card warning border-0 shadow-sm rounded-3"><div class="analytics-icon rounded-2"><i class="bi bi-star-fill"></i></div><div class="analytics-content"><div class="analytics-value text-warning fw-800" id="avgRating">•••</div><div class="analytics-label text-muted small">Average Rating</div></div></div></div>
                <div class="col-lg col-md-6 mb-3"><div class="analytics-card secondary border-0 shadow-sm rounded-3"><div class="analytics-icon rounded-2"><i class="bi bi-geo-alt-fill"></i></div><div class="analytics-content"><div class="analytics-value text-secondary fw-800" id="totalAttractions">•••</div><div class="analytics-label text-muted small">Active Attractions</div></div></div></div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-7 mb-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-graph-up text-primary"></i>
                            <span>Performance Metrics</span>
                        </h5>
                        <p class="text-muted small mt-n2">Comparison of top 3 spots by revenue</p>
                        <div class="chart-container flex-grow-1"><canvas id="performanceRadarChart"></canvas></div>
                    </div></div>
                </div>
                <div class="col-lg-5 mb-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-pie-chart-fill text-success"></i>
                            <span>Revenue by Category</span>
                        </h5>
                         <p class="text-muted small mt-n2">Contribution by each spot category</p>
                        <div class="chart-container flex-grow-1"><canvas id="revenueByCategoryChart"></canvas></div>
                    </div></div>
                </div>
            </div>
            <!-- Monthly bookings trend (new) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100 rounded-4 mb-3"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-700 d-flex align-items-center gap-2">
                                <i class="bi bi-bar-chart-line text-primary"></i>
                                <span>Monthly Bookings</span>
                            </h5>
                            <div class="small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-calendar3"></i>
                                <span>Last 12 months</span>
                            </div>
                        </div>
                        <div class="chart-container"><canvas id="monthlyBookingsChart"></canvas></div>
                    </div></div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-4 mb-3"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3 d-flex align-items-center gap-2"><i class="bi bi-people-fill text-primary"></i><span>Visitor Demographics</span></h5><div class="chart-container flex-grow-1"><canvas id="demographicsChart"></canvas></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3 d-flex align-items-center gap-2"><i class="bi bi-calendar-event text-primary"></i><span>Peak Booking Days</span></h5><div class="chart-container flex-grow-1"><canvas id="peakBookingChart"></canvas></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3 d-flex align-items-center gap-2"><i class="bi bi-hourglass-split text-primary"></i><span>Booking Lead Time</span></h5><div class="chart-container flex-grow-1"><canvas id="leadTimeChart"></canvas></div></div></div></div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-8 mb-3"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body"><h5 class="card-title mb-3 d-flex align-items-center gap-2"><i class="bi bi-trophy-fill text-warning"></i><span>Top Performing Attractions</span></h5><div class="table-responsive"><table class="table table-hover align-middle table-enhanced"><thead class="table-light"><tr><th>Rank</th><th>Attraction</th><th>Bookings</th><th>Revenue</th></tr></thead><tbody id="topAttractionsTable"></tbody></table></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body"><h5 class="card-title mb-3 d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i><span>Lowest Rated</span></h5><div class="table-responsive"><table class="table table-hover align-middle table-enhanced"><thead class="table-light"><tr><th>Attraction</th><th>Rating</th></tr></thead><tbody id="lowestRatedTable"></tbody></table></div></div></div></div>
            </div>

            <!-- Export Buttons (bottom) -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <button id="exportReportCsvBtn" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-text me-2"></i>Export Full Report (CSV)
                        </button>
                        <button id="exportReportPdfBtn" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Export Full Report (PDF)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>

    <script>
        (() => {
            if (!document.getElementById('reportFromDate')) return;
            let chartInstances = {};
            const modernColors = { blue: '#004a7c', red: '#e74a3b', green: '#1cc88a', teal: '#003d66', yellow: '#e8d5c4', gray: '#d4183d' };
            const modernColorsArray = Object.values(modernColors);

            const applyFilter = () => {
                const startDate = document.getElementById('reportFromDate').value;
                const endDate = document.getElementById('reportToDate').value;
                loadAnalyticsData(startDate, endDate);
            };

            const loadAnalyticsData = async (startDate, endDate) => {
                document.querySelectorAll('.analytics-value').forEach(el => el.textContent = '•••');
                const csrfTokenName = document.querySelector('meta[name="csrf-token-name"]').content;
                const csrfTokenValue = document.querySelector('meta[name="csrf-token-value"]').content;
                const formData = new FormData();
                formData.append('startDate', startDate); formData.append('endDate', endDate); formData.append(csrfTokenName, csrfTokenValue);
                try {
                    const response = await fetch(`${BASE_URL}admin/reports/analytics`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Network error.');
                    if (result.success) updateUI(result);
                } catch (error) { console.error('Error fetching analytics:', error); alert('Failed to load analytics data.'); }
            };

            const updateUI = (data) => {
                // Defensive guards for missing response structure
                const summary = data.summary || {};
                const charts = data.charts || {};
                const tables = data.tables || {};

                document.getElementById('totalBookings').textContent = summary.totalBookings ?? 0;
                document.getElementById('totalRevenue').textContent = summary.totalRevenue ?? '₱0.00';
                document.getElementById('arpb').textContent = summary.averageRevenuePerBooking ?? '₱0.00';
                document.getElementById('avgRating').textContent = summary.averageRating ?? '0.00';
                document.getElementById('totalAttractions').textContent = summary.activeAttractions ?? 0;

                // Ensure arrays exist before mapping
                const performanceMetrics = Array.isArray(charts.performanceMetrics) ? charts.performanceMetrics : [];
                const revenueByCategory = Array.isArray(charts.revenueByCategory) ? charts.revenueByCategory : [];
                const monthlyBookings = Array.isArray(charts.monthlyBookings) ? charts.monthlyBookings : [];
                // Keep references for any client-side needs
                window._reportsMonthlyBookings = monthlyBookings;
                const visitorDemographics = charts.visitorDemographics || {};
                const peakBookingDays = Array.isArray(charts.peakBookingDays) ? charts.peakBookingDays : [];
                const bookingLeadTime = Array.isArray(charts.bookingLeadTime) ? charts.bookingLeadTime : [];

                renderPerformanceRadar('performanceRadarChart', performanceMetrics);

                renderChart('revenueByCategoryChart', 'doughnut', {
                    labels: revenueByCategory.map(c => c.category || ''),
                    datasets: [{ data: revenueByCategory.map(c => c.total_revenue || 0), backgroundColor: modernColorsArray }]
                }, {}, true);

                // Monthly bookings line (safe)
                renderChart('monthlyBookingsChart', 'line', {
                    labels: monthlyBookings.map(m => m.month || m.label || ''),
                    datasets: [{ label: 'Bookings', data: monthlyBookings.map(m => m.total_bookings || m.count || 0), borderColor: modernColors.blue, backgroundColor: modernColors.blue + '33', tension: 0.3, fill: true }]
                }, { scales: { y: { beginAtZero: true } } }, false);

                renderChart('demographicsChart', 'pie', {
                    labels: ['Adults', 'Children', 'Seniors'],
                    datasets: [{ data: [visitorDemographics.total_adults || 0, visitorDemographics.total_children || 0, visitorDemographics.total_seniors || 0], backgroundColor: [modernColors.blue, modernColors.yellow, modernColors.gray] }]
                });

                renderChart('peakBookingChart', 'bar', { labels: peakBookingDays.map(d => d.day || ''), datasets: [{ label: 'Bookings', data: peakBookingDays.map(d => d.total || 0), backgroundColor: modernColors.teal, borderRadius: 4 }] });

                renderChart('leadTimeChart', 'bar', { labels: bookingLeadTime.map(d => d.lead_time_group || ''), datasets: [{ label: 'Bookings', data: bookingLeadTime.map(d => d.total || 0), backgroundColor: modernColors.blue, borderRadius: 4 }] });

                populateTable('topAttractionsTable', tables.topPerformingSpots || [], (spot, index) => `<tr><td>${index + 1}</td><td>${spot.spot_name}</td><td>${spot.total_bookings}</td><td>₱${parseFloat(spot.total_revenue || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`);
                populateTable('lowestRatedTable', tables.lowestRatedSpots || [], (spot) => `<tr><td>${spot.spot_name}</td><td>${(parseFloat(spot.average_rating) || 0).toFixed(2)} <i class="bi bi-star-fill text-warning"></i></td></tr>`);
            };

            const renderPerformanceRadar = (canvasId, performanceData) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas || !performanceData || performanceData.length === 0) return;
                // Ensure any existing Chart.js instance attached to this canvas is destroyed
                const existing = Chart.getChart(canvas);
                if (existing) existing.destroy();
                const labels = ['Bookings', 'Revenue', 'Visitors', 'Avg Rating'];
                const maxBookings = Math.max(1, ...performanceData.map(d => d.total_bookings));
                const maxRevenue = Math.max(1, ...performanceData.map(d => d.total_revenue));
                const maxVisitors = Math.max(1, ...performanceData.map(d => d.total_visitors));
                const datasets = performanceData.map((spot, index) => ({
                    label: spot.spot_name,
                    data: [
                        (spot.total_bookings / maxBookings) * 10 || 0,
                        (spot.total_revenue / maxRevenue) * 10 || 0,
                        (spot.total_visitors / maxVisitors) * 10 || 0,
                        ((spot.avg_rating || 0) / 5) * 10 || 0,
                    ],
                    borderColor: Object.values(modernColors)[index],
                    backgroundColor: `${Object.values(modernColors)[index]}40`,
                    pointBackgroundColor: Object.values(modernColors)[index],
                }));
                // Fix canvas height to its container to avoid layout bounce
                const radarHeight = canvas.parentElement.clientHeight || 320;
                canvas.style.height = radarHeight + 'px'; canvas.height = radarHeight;
                if (window.adminCharts && adminCharts.createChart) {
                    chartInstances[canvasId] = adminCharts.createChart(canvas.getContext('2d'), { type: 'radar', data: { labels, datasets }, options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, max: 10, ticks: { display: false } } }, plugins: { legend: { position: 'bottom' } } } });
                } else {
                    chartInstances[canvasId] = new Chart(canvas.getContext('2d'), {
                        type: 'radar', data: { labels, datasets },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            scales: { r: { beginAtZero: true, max: 10, ticks: { display: false } } },
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                }
            };
            
            const renderChart = (canvasId, type, data, extraOptions = {}, isCurrency = false) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;
                // Destroy any existing Chart.js instance on this canvas (covers charts created by other scripts too)
                const existing = Chart.getChart(canvas);
                if (existing) existing.destroy();
                // Ensure canvas has explicit height from parent to avoid infinite resize loops
                const cHeight = canvas.parentElement.clientHeight || 320;
                canvas.style.height = cHeight + 'px'; canvas.height = cHeight;
                if (window.adminCharts && adminCharts.createChart) {
                    chartInstances[canvasId] = adminCharts.createChart(canvas.getContext('2d'), { type, data, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: type.startsWith('d') || type === 'pie', position: 'bottom' }, tooltip: { callbacks: { label: c => `${c.dataset.label||c.label}: ${isCurrency?new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(c.raw):c.raw}` } } }, scales: { x: { grid: { display: false } }, y: { grid: { drawOnChartArea: true, borderDash: [2, 3] } } }, ...extraOptions } });
                } else {
                    chartInstances[canvasId] = new Chart(canvas.getContext('2d'), { type, data, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: type.startsWith('d') || type === 'pie', position: 'bottom' }, tooltip: { callbacks: { label: c => `${c.dataset.label||c.label}: ${isCurrency?new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(c.raw):c.raw}` } } }, scales: { x: { grid: { display: false } }, y: { grid: { drawOnChartArea: true, borderDash: [2, 3] } } }, ...extraOptions } });
                }
            };
            
            const populateTable = (tableId, data, rowTemplate) => {
                const tableBody = document.getElementById(tableId);
                if (!tableBody) return;
                if (!data || data.length === 0) {
                    const colspan = tableBody.previousElementSibling?.firstElementChild?.childElementCount || 2;
                    tableBody.innerHTML = `<tr><td colspan="${colspan}" class="text-center text-muted py-3">No data available for this period.</td></tr>`;
                    return;
                }
                tableBody.innerHTML = data.map(rowTemplate).join('');
            };
            
            const toDate = new Date();
            const fromDate = new Date();
            fromDate.setDate(toDate.getDate() - 29);
            document.getElementById('reportToDate').valueAsDate = toDate;
            document.getElementById('reportFromDate').valueAsDate = fromDate;

            // Trigger analytics using the central function if available (admin-script.js),
            // otherwise fall back to the local loader `loadAnalyticsData`.
            const triggerAnalytics = () => {
                const startDate = document.getElementById('reportFromDate').value;
                const endDate = document.getElementById('reportToDate').value;
                if (typeof window.fetchAnalytics === 'function') {
                    // Use shared analytics loader from admin-script.js
                    window.fetchAnalytics(startDate, endDate);
                } else if (typeof loadAnalyticsData === 'function') {
                    // Local fallback defined in this file
                    loadAnalyticsData(startDate, endDate);
                } else {
                    console.warn('No analytics loader available to fetch data');
                }
            };

            const applyBtn = document.getElementById('applyFilterBtn');
            if (applyBtn) applyBtn.addEventListener('click', triggerAnalytics);
            // Initial load
            triggerAnalytics();

            // Server-side export: open endpoints with date range
            const exportServer = (type) => {
                const start = document.getElementById('reportFromDate')?.value || '';
                const end = document.getElementById('reportToDate')?.value || '';
                const qs = new URLSearchParams();
                if (start) qs.set('startDate', start);
                if (end) qs.set('endDate', end);
                const url = `${BASE_URL}admin/reports/export/${type}` + (qs.toString() ? `?${qs.toString()}` : '');
                window.open(url, '_blank');
            };
            const csvBtn = document.getElementById('exportReportCsvBtn');
            const pdfBtn = document.getElementById('exportReportPdfBtn');
            if (csvBtn) csvBtn.addEventListener('click', () => exportServer('csv'));
            if (pdfBtn) pdfBtn.addEventListener('click', () => exportServer('pdf'));
        })();
    </script>
</body>
    <script src="<?= base_url('assets/js/admin-ui.js') ?>"></script>
</html>