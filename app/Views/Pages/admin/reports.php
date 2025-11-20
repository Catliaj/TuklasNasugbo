<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourism Admin Dashboard - Reports & Analytics</title>
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    <script>const BASE_URL = '<?= base_url() ?>';</script>
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
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="bi bi-compass"></i><span>Tourism Admin</span></div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item"><i class="bi bi-grid"></i><span>Dashboard</span></a>
            <a href="/admin/registrations" class="nav-item"><i class="bi bi-person-plus"></i><span>Registrations</span></a>
            <a href="/admin/attractions" class="nav-item"><i class="bi bi-geo-alt"></i><span>Attractions</span></a>
            <a href="/admin/reports" class="nav-item active"><i class="bi bi-file-bar-graph"></i><span>Reports & Analytics</span></a>
        </nav>
        <div class="sidebar-footer"><a href="/users/logout" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i><span>Logout</span></a></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <div class="top-bar">
            <button class="btn btn-link text-dark" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
            <div class="dropdown">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i><span class="d-none d-md-inline">Admin User</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Reports & Analytics</h1>
                    <p class="text-muted mb-0">Comprehensive analytics and downloadable reports</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4"><label class="form-label">From Date</label><input type="date" class="form-control" id="reportFromDate"></div>
                        <div class="col-md-4"><label class="form-label">To Date</label><input type="date" class="form-control" id="reportToDate"></div>
                        <div class="col-md-4"><button class="btn btn-primary w-100" id="applyFilterBtn"><i class="bi bi-funnel me-2"></i>Apply Filter</button></div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg col-md-4 mb-3"><div class="analytics-card primary"><div class="analytics-icon"><i class="bi bi-calendar-check-fill"></i></div><div class="analytics-content"><div class="analytics-value" id="totalBookings">•••</div><div class="analytics-label">Total Bookings</div></div></div></div>
                <div class="col-lg col-md-4 mb-3"><div class="analytics-card info"><div class="analytics-icon"><i class="bi bi-currency-dollar"></i></div><div class="analytics-content"><div class="analytics-value" id="totalRevenue">•••</div><div class="analytics-label">Total Revenue</div></div></div></div>
                <div class="col-lg col-md-4 mb-3"><div class="analytics-card success"><div class="analytics-icon"><i class="bi bi-cash"></i></div><div class="analytics-content"><div class="analytics-value" id="arpb">•••</div><div class="analytics-label">Avg. Revenue/Booking</div></div></div></div>
                <div class="col-lg col-md-4 mb-3"><div class="analytics-card warning"><div class="analytics-icon"><i class="bi bi-star-fill"></i></div><div class="analytics-content"><div class="analytics-value" id="avgRating">•••</div><div class="analytics-label">Average Rating</div></div></div></div>
                <div class="col-lg col-md-4 mb-3"><div class="analytics-card secondary"><div class="analytics-icon"><i class="bi bi-geo-alt-fill"></i></div><div class="analytics-content"><div class="analytics-value" id="totalAttractions">•••</div><div class="analytics-label">Active Attractions</div></div></div></div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-7 mb-3">
                    <div class="card h-100"><div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3"><i class="bi bi-graph-up me-2"></i>Performance Metrics</h5>
                        <p class="text-muted small mt-n2">Comparison of top 3 spots by revenue</p>
                        <div class="chart-container flex-grow-1"><canvas id="performanceRadarChart"></canvas></div>
                    </div></div>
                </div>
                <div class="col-lg-5 mb-3">
                    <div class="card h-100"><div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3"><i class="bi bi-pie-chart-fill me-2"></i>Revenue Share by Category</h5>
                         <p class="text-muted small mt-n2">Contribution by each spot category</p>
                        <div class="chart-container flex-grow-1"><canvas id="revenueByCategoryChart"></canvas></div>
                    </div></div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-4 mb-3"><div class="card h-100"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3"><i class="bi bi-people-fill me-2"></i>Visitor Demographics</h5><div class="chart-container flex-grow-1"><canvas id="demographicsChart"></canvas></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card h-100"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3"><i class="bi bi-calendar-event me-2"></i>Peak Booking Days</h5><div class="chart-container flex-grow-1"><canvas id="peakBookingChart"></canvas></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card h-100"><div class="card-body d-flex flex-column"><h5 class="card-title mb-3"><i class="bi bi-hourglass-split me-2"></i>Booking Lead Time</h5><div class="chart-container flex-grow-1"><canvas id="leadTimeChart"></canvas></div></div></div></div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-8 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title mb-3"><i class="bi bi-trophy-fill me-2 text-warning"></i>Top Performing Attractions</h5><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Rank</th><th>Attraction</th><th>Bookings</th><th>Revenue</th></tr></thead><tbody id="topAttractionsTable"></tbody></table></div></div></div></div>
                <div class="col-lg-4 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title mb-3"><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Lowest Rated Attractions</h5><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Attraction</th><th>Rating</th></tr></thead><tbody id="lowestRatedTable"></tbody></table></div></div></div></div>
            </div>
        </div>
    </div>

    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>

    <script>
        (() => {
            if (!document.getElementById('reportFromDate')) return;
            let chartInstances = {};
            const modernColors = { blue: '#4e73df', red: '#e74a3b', green: '#1cc88a', teal: '#36b9cc', yellow: '#f6c23e', gray: '#858796' };
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
                document.getElementById('totalBookings').textContent = data.summary.totalBookings ?? 0;
                document.getElementById('totalRevenue').textContent = data.summary.totalRevenue ?? '₱0.00';
                document.getElementById('arpb').textContent = data.summary.averageRevenuePerBooking ?? '₱0.00';
                document.getElementById('avgRating').textContent = data.summary.averageRating ?? '0.00';
                document.getElementById('totalAttractions').textContent = data.summary.activeAttractions ?? 0;

                renderPerformanceRadar('performanceRadarChart', data.charts.performanceMetrics);
                renderChart('revenueByCategoryChart', 'doughnut', { labels: data.charts.revenueByCategory.map(c => c.category), datasets: [{ data: data.charts.revenueByCategory.map(c => c.total_revenue), backgroundColor: modernColorsArray }] }, {}, true);
                renderChart('demographicsChart', 'pie', { labels: ['Adults', 'Children', 'Seniors'], datasets: [{ data: [data.charts.visitorDemographics?.total_adults || 0, data.charts.visitorDemographics?.total_children || 0, data.charts.visitorDemographics?.total_seniors || 0], backgroundColor: [modernColors.blue, modernColors.yellow, modernColors.gray] }] });
                renderChart('peakBookingChart', 'bar', { labels: data.charts.peakBookingDays.map(d => d.day), datasets: [{ label: 'Bookings', data: data.charts.peakBookingDays.map(d => d.total), backgroundColor: modernColors.teal, borderRadius: 4 }] });
                renderChart('leadTimeChart', 'bar', { labels: data.charts.bookingLeadTime.map(d => d.lead_time_group), datasets: [{ label: 'Bookings', data: data.charts.bookingLeadTime.map(d => d.total), backgroundColor: modernColors.blue, borderRadius: 4 }] });

                populateTable('topAttractionsTable', data.tables.topPerformingSpots, (spot, index) => `<tr><td>${index + 1}</td><td>${spot.spot_name}</td><td>${spot.total_bookings}</td><td>₱${parseFloat(spot.total_revenue).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>`);
                populateTable('lowestRatedTable', data.tables.lowestRatedSpots, (spot) => `<tr><td>${spot.spot_name}</td><td>${(parseFloat(spot.average_rating) || 0).toFixed(2)} <i class="bi bi-star-fill text-warning"></i></td></tr>`);
            };

            const renderPerformanceRadar = (canvasId, performanceData) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas || !performanceData || performanceData.length === 0) return;
                if (chartInstances[canvasId]) chartInstances[canvasId].destroy();
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
                chartInstances[canvasId] = new Chart(canvas.getContext('2d'), {
                    type: 'radar', data: { labels, datasets },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { r: { beginAtZero: true, max: 10, ticks: { display: false } } },
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            };
            
            const renderChart = (canvasId, type, data, extraOptions = {}, isCurrency = false) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;
                if (chartInstances[canvasId]) chartInstances[canvasId].destroy();
                chartInstances[canvasId] = new Chart(canvas.getContext('2d'), { type, data, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: type.startsWith('d') || type === 'pie', position: 'bottom' }, tooltip: { callbacks: { label: c => `${c.dataset.label||c.label}: ${isCurrency?new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(c.raw):c.raw}` } } }, scales: { x: { grid: { display: false } }, y: { grid: { drawOnChartArea: true, borderDash: [2, 3] } } }, ...extraOptions } });
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
            document.getElementById('applyFilterBtn').addEventListener('click', applyFilter);
            applyFilter();
        })();
    </script>
</body>
</html>