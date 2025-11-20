<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Spot Owner Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
</head>

<body>
    <div class="d-flex min-vh-100" id="wrapper">
        <!-- Sidebar -->
                <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="sidebar-logo">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="sidebar-title mb-0">Tourist Spot</h3>
                        <p class="sidebar-subtitle mb-0">Owner Dashboard</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-content">
               <ul class="sidebar-menu">
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/dashboard" class="sidebar-link " data-page="home">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/bookings" class="sidebar-link" data-page="bookings">
                            <i class="bi bi-calendar-check"></i>
                            <span>Booking Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/earnings" class="sidebar-link active" data-page="earnings">
                            <i class="bi bi-graph-up"></i>
                            <span>Earnings & Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/mySpots" class="sidebar-link" data-page="manage">
                            <i class="bi bi-geo-alt"></i>
                            <span>Manage Spot</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="/" class="sidebar-link text-danger" id="logoutBtn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">
            <!-- Mobile Header -->
            <div class="mobile-header d-lg-none">
                <button class="btn btn-link" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <div class="mobile-logo">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="mobile-title mb-0">Tourist Spot</h3>
                        <p class="mobile-subtitle mb-0">Owner Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <!-- Content will be loaded here dynamically -->
                <div class="container-fluid">
                    <div class="mb-4">
                        <h2>Earnings & Reports</h2>
                        <p class="text-muted-custom">Track your revenue and financial performance</p>
                    </div>

                    <!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">₱<?= number_format($totalRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">All time</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-value">₱<?= number_format($monthlyRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">
                        <?php if(isset($comparison) && $comparison['change'] != 0): ?>
                            <span class="<?= $comparison['direction'] == 'up' ? 'text-success' : 'text-danger' ?>">
                                <?= $comparison['direction'] == 'up' ? '↑' : '↓' ?> 
                                <?= abs(number_format($comparison['change'], 1)) ?>%
                            </span> from last month
                        <?php else: ?>
                            No change from last month
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Avg. Per Booking</div>
                    <div class="stat-value">₱<?= number_format($averageRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">Based on <?= $totalBookings ?? 0 ?> bookings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">₱<?= number_format($pendingRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">From pending bookings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

                    <!-- Interactive Charts Section -->
                    <div class="custom-card mb-4">
                        <div class="custom-card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h3 class="custom-card-title mb-1">Revenue & Booking Analytics</h3>
                                    <p class="custom-card-description mb-0">Select a view to analyze your performance</p>
                                </div>
                                <div class="btn-group" role="group" aria-label="Chart selection">
                                    <button type="button" class="btn btn-outline-primary active" id="btnMonthlyRevenue" onclick="switchChart('monthly-revenue', 'bar')">
                                        <i class="bi bi-bar-chart me-1"></i>Monthly Revenue (Bar)
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="btnWeeklyRevenue" onclick="switchChart('weekly-revenue', 'line')">
                                        <i class="bi bi-graph-up me-1"></i>Weekly Revenue (Line)
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="btnBookingTrends" onclick="switchChart('booking-trends', 'bar')">
                                        <i class="bi bi-bar-chart-fill me-1"></i>Booking Trends (Bar)
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="custom-card-body">
                            <!-- Chart Container -->
                            <div id="chartContainer" style="min-height: 450px; position: relative;">
                                <canvas id="mainChart" style="width: 100%; height: 450px;"></canvas>
                            </div>

                            <!-- Chart Info -->
                            <div id="chartInfo" class="mt-3 p-3 bg-beige rounded">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Period</div>
                                        <div class="fw-medium" id="chartPeriod">Last 6 Months</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Total</div>
                                        <div class="fw-medium text-ocean-medium" id="chartTotal">₱15,750</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Average</div>
                                        <div class="fw-medium" id="chartAverage">₱2,625/month</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
<div class="col-lg-6">
    <div class="custom-card">
        <div class="custom-card-header">
            <h3 class="custom-card-title">Recent Transactions</h3>
            <p class="custom-card-description">Latest payment activities</p>
        </div>
        <div class="custom-card-body">
            <?php if (!empty($recentTransactions)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentTransactions as $transaction): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-medium"><?= esc($transaction['customer_name']) ?></div>
                                <small class="text-muted-custom">
                                    <?= date('M d, Y', strtotime($transaction['booking_date'])) ?>
                                    <?php if ($transaction['booking_status'] == 'Pending'): ?>
                                        (Pending)
                                    <?php endif; ?>
                                </small>
                            </div>
                            <span class="fw-medium <?= $transaction['booking_status'] == 'Confirmed' ? 'text-success' : 'text-warning' ?>">
                                <?= $transaction['booking_status'] == 'Confirmed' ? '+' : '' ?>₱<?= number_format($transaction['total_price'], 2) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted-custom">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mb-0 mt-2">No recent transactions</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

                       <!-- Top Performing Days -->
<div class="col-lg-6">
    <div class="custom-card">
        <div class="custom-card-header">
            <h3 class="custom-card-title">Top Performing Days</h3>
            <p class="custom-card-description">Highest revenue days this month</p>
        </div>
        <div class="custom-card-body">
            <?php if (!empty($topDays)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($topDays as $day): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-medium"><?= esc($day['day_name']) ?>, <?= esc($day['formatted_date']) ?></div>
                                <small class="text-muted-custom"><?= $day['bookings'] ?> booking<?= $day['bookings'] != 1 ? 's' : '' ?></small>
                            </div>
                            <span class="fw-medium text-ocean-medium">₱<?= number_format($day['revenue'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted-custom">
                    <i class="bi bi-calendar-x fs-1"></i>
                    <p class="mb-0 mt-2">No bookings this month yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

                    <!-- Export Options -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h3 class="custom-card-title">Export Reports</h3>
                            <p class="custom-card-description">Download financial reports</p>
                        </div>
                        <div class="custom-card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Export as PDF
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export as Excel
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-text me-2"></i>Export as CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Custom JavaScript -->
<script src="<?= base_url("assets/js/spotownerJS/shared-data.js")?>"></script>
<script src="<?= base_url("assets/js/spotownerJS/earnings.js")?>"></script>
    
</body>

</html>