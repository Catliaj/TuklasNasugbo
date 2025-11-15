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

        .analytics-card {
            display: flex;
            align-items: center;
            padding: 0.75rem; 
            border-radius: 0.75rem;
            border: 1px solid;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            height: 100%;
        }
        .analytics-card .analytics-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }
        .analytics-card .analytics-icon i {
            font-size: 1.5rem;
            color: #fff;
        }
        .analytics-card .analytics-content {
            flex-grow: 1;
            min-width: 0; 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .analytics-card .analytics-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .analytics-card .analytics-label {
            font-size: 0.8rem;
            color: #6c757d;
            line-height: 1.3;
            margin: 0;
        }

        .analytics-card.primary { border-color: #dbe4ff; background-color: #f3f6ff; }
        .analytics-card.primary .analytics-icon { background-color: #364fc7; }
        .analytics-card.primary .analytics-value { color: #364fc7; }

        .analytics-card.info { border-color: #c5f6fa; background-color: #f1f9fa; }
        .analytics-card.info .analytics-icon { background-color: #17a2b8; }
        .analytics-card.info .analytics-value { color: #107280; }

        .analytics-card.success { border-color: #d3f9d8; background-color: #f2fbf5; }
        .analytics-card.success .analytics-icon { background-color: #28a745; }
        .analytics-card.success .analytics-value { color: #1c7430; }
        
        .analytics-card.warning { border-color: #ffec99; background-color: #fff9f0; }
        .analytics-card.warning .analytics-icon { background-color: #ffc107; }
        .analytics-card.warning .analytics-value { color: #b98b04; }
        
        .analytics-card.secondary { border-color: #e9ecef; background-color: #f6f7f8; }
        .analytics-card.secondary .analytics-icon { background-color: #6c757d; }
        .analytics-card.secondary .analytics-value { color: #495057; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-compass"></i>
            <span>Tourism Admin</span>
        </div>

        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/registrations" class="nav-item">
                <i class="bi bi-person-plus"></i>
                <span>Registrations</span>
                <span class="badge">12</span>
            </a>
            <a href="/admin/attractions" class="nav-item">
                <i class="bi bi-geo-alt"></i>
                <span>Attractions</span>
            </a>
            <a href="/admin/reports" class="nav-item active">
                <i class="bi bi-file-bar-graph"></i>
                <span>Reports & Analytics</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/users/logout" class="nav-item text-danger">
                <i class="bi bi-box-arrow-left"></i><span>Logout</span>
            </a>
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="top-bar">
            <button class="btn btn-link text-dark" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="d-none d-md-inline">Admin User</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Page Content Container -->
        <div class="container-fluid">

            <div class="page-header">
                <h1 class="page-title">Reports & Analytics</h1>
                <p class="text-muted mb-0">Comprehensive analytics and downloadable reports</p>
            </div>

            <!-- Date Range Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" id="reportFromDate">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" id="reportToDate">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="applyReportFilter()">
                                <i class="bi bi-funnel me-2"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="row mb-4">
                <div class="col-lg col-md-4 mb-3">
                    <div class="analytics-card primary">
                        <div class="analytics-icon"><i class="bi bi-calendar-check-fill"></i></div>
                        <div class="analytics-content">
                            <div class="analytics-value" id="totalBookings">•••</div>
                            <div class="analytics-label">Total Bookings</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg col-md-4 mb-3">
                    <div class="analytics-card info">
                        <div class="analytics-icon"><i class="bi bi-currency-dollar"></i></div>
                        <div class="analytics-content">
                            <div class="analytics-value" id="totalRevenue">•••</div>
                            <div class="analytics-label">Total Revenue</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg col-md-4 mb-3">
                    <div class="analytics-card success">
                        <div class="analytics-icon"><i class="bi bi-cash"></i></div>
                        <div class="analytics-content">
                            <div class="analytics-value" id="arpb">•••</div>
                            <div class="analytics-label">Avg. Revenue/Booking</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg col-md-4 mb-3">
                    <div class="analytics-card warning">
                        <div class="analytics-icon"><i class="bi bi-star-fill"></i></div>
                        <div class="analytics-content">
                            <div class="analytics-value" id="avgRating">•••</div>
                            <div class="analytics-label">Average Rating</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg col-md-4 mb-3">
                    <div class="analytics-card secondary">
                        <div class="analytics-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="analytics-content">
                            <div class="analytics-value" id="totalAttractions">•••</div>
                            <div class="analytics-label">Active Attractions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-bar-chart me-2"></i>Revenue by Category</h5>
                            <div class="chart-container flex-grow-1">
                                <canvas id="revenueByCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-people me-2"></i>Visitor Demographics</h5>
                            <div class="chart-container flex-grow-1">
                                <canvas id="demographicsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row of Charts -->
            <div class="row mb-4">
                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-hourglass-split me-2"></i>Booking Lead Time</h5>
                            <div class="chart-container flex-grow-1">
                                <canvas id="leadTimeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-calendar-event me-2"></i>Peak Booking Days</h5>
                            <div class="chart-container flex-grow-1">
                                <canvas id="peakBookingChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-calendar-check me-2"></i>Peak Visit Days</h5>
                            <div class="chart-container flex-grow-1">
                                <canvas id="peakVisitChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLES -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-trophy-fill me-2 text-warning"></i>
                                Top Performing Attractions (by Revenue)
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Attraction</th>
                                            <th>Bookings</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topAttractionsTable"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                                Lowest Rated Attractions
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Attraction</th>
                                            <th>Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lowestRatedTable"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Container -->
    </div> <!-- End Main Content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>

    <script>
        let chartInstances = {};

        document.addEventListener('DOMContentLoaded', function() {
            const toDate = new Date();
            const fromDate = new Date();
            fromDate.setDate(toDate.getDate() - 29);
            document.getElementById('reportToDate').valueAsDate = toDate;
            document.getElementById('reportFromDate').valueAsDate = fromDate;
            applyReportFilter();
        });

        function applyReportFilter() {
            const startDate = document.getElementById('reportFromDate').value;
            const endDate = document.getElementById('reportToDate').value;
            loadAnalyticsData(startDate, endDate);
        }

        async function loadAnalyticsData(startDate, endDate) {
            document.querySelectorAll('.analytics-value').forEach(el => el.textContent = '•••');

            const csrfTokenName = document.querySelector('meta[name="csrf-token-name"]').content;
            const csrfTokenValue = document.querySelector('meta[name="csrf-token-value"]').content;

            const formData = new FormData();
            formData.append('startDate', startDate);
            formData.append('endDate', endDate);
            formData.append(csrfTokenName, csrfTokenValue);

            try {
                const response = await fetch(`${BASE_URL}admin/reports/analytics`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();
                if (result.success) {
                    updateUI(result);
                }
            } catch (error) {
                console.error('Error fetching analytics:', error);
            }
        }

        function updateUI(data) {
            document.getElementById('totalBookings').textContent = data.summary.totalBookings;
            document.getElementById('totalRevenue').textContent = data.summary.totalRevenue;
            document.getElementById('arpb').textContent = data.summary.averageRevenuePerBooking;
            document.getElementById('avgRating').textContent = data.summary.averageRating;
            document.getElementById('totalAttractions').textContent = data.summary.activeAttractions;

            renderChart("revenueByCategoryChart", "bar", {
                labels: data.charts.revenueByCategory.map(c => c.category),
                datasets: [{
                    label: "Revenue",
                    data: data.charts.revenueByCategory.map(c => c.total_revenue),
                    backgroundColor: "#28a745"
                }]
            }, { indexAxis: "y" });

            renderChart("demographicsChart", "pie", {
                labels: ["Adults", "Children", "Seniors"],
                datasets: [{
                    data: [
                        data.charts.visitorDemographics.total_adults,
                        data.charts.visitorDemographics.total_children,
                        data.charts.visitorDemographics.total_seniors
                    ],
                    backgroundColor: ["#007bff", "#ffc107", "#6f42c1"]
                }]
            });

            renderChart("leadTimeChart", "doughnut", {
                labels: data.charts.bookingLeadTime.map(d => d.lead_time_group),
                datasets: [{
                    data: data.charts.bookingLeadTime.map(d => d.total),
                    backgroundColor: ["#17a2b8", "#fd7e14", "#6610f2", "#20c997"]
                }]
            });

            renderChart("peakBookingChart", "bar", {
                labels: data.charts.peakBookingDays.map(d => d.day),
                datasets: [{
                    label: "Bookings",
                    data: data.charts.peakBookingDays.map(d => d.total),
                    backgroundColor: "#007bff"
                }]
            });

            renderChart("peakVisitChart", "bar", {
                labels: data.charts.peakVisitDays.map(d => d.day),
                datasets: [{
                    label: "Visits",
                    data: data.charts.peakVisitDays.map(d => d.total),
                    backgroundColor: "#ffc107"
                }]
            });

            populateTable("topAttractionsTable", data.tables.topPerformingSpots, (spot, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${spot.spot_name}</td>
                    <td>${spot.total_bookings}</td>
                    <td>₱${parseFloat(spot.total_revenue).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                </tr>`);

            populateTable("lowestRatedTable", data.tables.lowestRatedSpots, (spot) => `
                <tr>
                    <td>${spot.spot_name}</td>
                    <td>${parseFloat(spot.average_rating).toFixed(2)} <i class="bi bi-star-fill text-warning"></i></td>
                </tr>`);
        }

        function renderChart(canvasId, type, data, extraOptions = {}) {
            if (chartInstances[canvasId]) {
                chartInstances[canvasId].destroy();
            }
            const ctx = document.getElementById(canvasId).getContext('2d');
            chartInstances[canvasId] = new Chart(ctx, {
                type,
                data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: type !== "bar" } },
                    ...extraOptions
                }
            });
        }

        function populateTable(tableId, data, templateFn) {
            const table = document.getElementById(tableId);
            table.innerHTML = data.length
                ? data.map(templateFn).join("")
                : `<tr><td colspan="4" class="text-center text-muted">No data available</td></tr>`;
        }
    </script>

</body>
</html>
