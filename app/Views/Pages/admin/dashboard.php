<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Ensure settings are loaded before use
        if (!isset($currentSettings) || !is_array($currentSettings)) {
            $settingsPath = WRITEPATH . 'settings.json';
            $currentSettings = [];
            if (file_exists($settingsPath)) {
                $currentSettings = json_decode(file_get_contents($settingsPath), true) ?: [];
            }
        }
    ?>
    <title><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?> - Dashboard</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    <?php /* settings already loaded above */ ?>
    <style>:root { --primary-blue: <?= esc($currentSettings['primary_color'] ?? '#004a7c') ?>; }</style>
    <!-- Modern font + small UI polish -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="<?= base_url('assets/js/admin-charts.js') ?>"></script>

    <style>
        :root{--card-bg:#ffffff;--muted:#6c757d;--surface:#f8fafc}
        body{font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;background:linear-gradient(180deg,#f6f8fb 0%, #ffffff 100%);color:#23303b}
        /* Ported Styles from Reports Page for Consistency */
        .chart-container { position: relative; height: 320px; max-height: 420px; width: 100%; }
        .chart-container.flex-grow-1 { min-height: 220px; }
        /* Ensure canvas doesn't force parent to grow uncontrollably */
        .chart-container canvas { display: block; width: 100% !important; height: 100% !important; max-height: 100% !important; }
        /* Strong safety: lock per-chart heights to avoid any resize loop */
        .chart-container { overflow: hidden; }
        #monthlyBookingsChart, #peakVisitChart, #userPreferenceChart, #insightsChart, #performanceRadarChart { height: 320px !important; max-height: 360px !important; }
        #monthlyBookingsChart.canvas-fixed, #peakVisitChart.canvas-fixed, #userPreferenceChart.canvas-fixed { height: 320px !important }
        /* small sparkline */
        .kpi-spark { height: 36px !important; max-height: 36px !important }
        
        .analytics-card { 
            display: flex; align-items: center; padding: 1.25rem; 
            border-radius: 0.75rem; background-color: var(--card-bg); 
            box-shadow: 0 6px 18px rgba(35,48,59,0.06); border: 1px solid rgba(35,48,59,0.04);
            height: 100%; transition: transform 0.18s, box-shadow 0.18s;
        }
        .analytics-card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(35,48,59,0.07); }
        .analytics-card:focus-within { outline: 2px solid var(--primary-blue); outline-offset: 2px; }
        
        .analytics-card .icon-wrapper { 
            width: 56px; height: 56px; border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; 
            margin-right: 1rem; font-size: 1.75rem;
        }
        
        .analytics-card .content { flex-grow: 1; }
        .analytics-card .value { font-size: 1.75rem; font-weight: 700; line-height: 1.2; margin-bottom: 0.25rem; color: #2d3748; }
        .analytics-card .label { font-size: 0.875rem; color: #718096; font-weight: 500; }

        /* mini-sparkline inside KPI */
        .kpi-spark { width:120px; height:36px; display:block }
        .card-modern { border-radius: 0.9rem; box-shadow: 0 8px 30px rgba(35,48,59,0.04); border: none }
        .section-title { font-size:1.05rem;font-weight:700;color:#1f2937 }

        /* Fix flex overflow: allow children to constrain correctly */
        .card-body { min-height: 0; }

        /* Color Variants */
        .bg-soft-primary { background-color: #ebf8ff; color: #3182ce; }
        .bg-soft-success { background-color: #f0fff4; color: #38a169; }
        .bg-soft-warning { background-color: #fffff0; color: #d69e2e; }
        .bg-soft-info { background-color: #e6fffa; color: #319795; }

        /* Lists Styling */
        .top-list-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 0; border-bottom: 1px solid #f0f0f0;
        }
        .top-list-item:last-child { border-bottom: none; }
        .rank-badge { 
            width: 28px; height: 28px; background: #edf2f7; color: #4a5568; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-size: 0.8rem; font-weight: bold; margin-right: 10px;
        }

        /* KPI grid: show up to 5 cards per row on very wide screens */
        @media (min-width: 1400px) {
            .kpi-row > [class*="col-"] {
                flex: 0 0 20%;
                max-width: 20%;
            }
        }

        /* topbar styles moved to shared admin stylesheet for consistency */
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar" role="navigation" aria-label="Admin sidebar">
        <div class="sidebar-header"><i class="bi bi-compass"></i><span><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?></span></div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item active" aria-current="page"><i class="bi bi-grid"></i><span>Dashboard</span></a>
            <a href="/admin/registrations" class="nav-item"><i class="bi bi-person-plus"></i><span>Registrations</span><span class="badge-pending-registrations badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span></a>
            <a href="/admin/attractions" class="nav-item"><i class="bi bi-geo-alt"></i><span>Attractions</span><span class="badge-pending-attractions badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span></a>
            <a href="/admin/reports" class="nav-item"><i class="bi bi-file-bar-graph"></i><span>Reports & Analytics</span></a>
        </nav>
        <div class="sidebar-footer"><!-- Logout moved to profile menu; removed duplicate link here -->
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        
        <!-- TOPBAR -->
            <div class="top-bar" role="banner">
            <button class="btn btn-link text-dark" id="sidebarToggle" aria-controls="sidebar" aria-label="Toggle sidebar"><i class="bi bi-list fs-4"></i></button>

            <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <!-- Notification button now uses shared topbar-avatar class for uniform size/alignment -->
                        <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationButtonDashboard" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                            <i class="bi bi-bell-fill text-white"></i>
                            <span class="notification-badge badge rounded-pill bg-danger"><?= (isset($unreadNotifications) && $unreadNotifications > 0) ? esc($unreadNotifications) : '' ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuDashboard" style="min-width:320px">
                            <li class="dropdown-item text-muted small">No new notifications</li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <?php $nameParts = array_filter(explode(' ', trim($FullName))); $initials = strtoupper(substr($nameParts[0] ?? '',0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : '')); ?>
                        <button class="btn p-0 border-0 topbar-avatar topbar-avatar--primary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                            <span><?= esc($initials) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width:220px">
                            <li class="px-3 py-2">
                                <div class="fw-bold"><?= esc($FullName) ?></div>
                                <div class="small text-muted"><?= esc($email ?? $Email ?? '') ?></div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><a class="dropdown-item" href="/admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                        </ul>
                    </div>
            </div>

            <!-- Pending Spots Card removed from top-bar; it's displayed with KPI cards below -->
        </div>

        <div class="container-fluid p-4">
            
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title fw-700">📊 Dashboard Overview</h1>
                    <p class="text-muted">Real-time insights and performance metrics</p>
                </div>
            </div>

            <!-- KPI CARDS -->
            <div class="row g-4 mb-4 kpi-row">
                <!-- Tourist Satisfaction Score -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card shadow-sm border-0 rounded-3" style="background: linear-gradient(135deg, #f0f8ff 0%, #e6f4ff 100%); border: 1.5px solid #cce5ff;">
                        <div class="icon-wrapper rounded-2" style="background: linear-gradient(180deg, #004a7c, #003d66);">
                            <i class="bi bi-emoji-smile" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div class="content">
                            <div class="value" style="color: #004a7c;"><?= esc($satisfactionScore) ?> / 5.0</div>
                            <div class="label" style="color: #003d66;">Tourist Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Total Pending Requests -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card shadow-sm border-0 rounded-3" style="background: linear-gradient(135deg, #f5f1e6 0%, #ede7d9 100%); border: 1.5px solid #e0d0b8;">
                        <div class="icon-wrapper rounded-2" style="background: linear-gradient(180deg, #6b5b38, #5a4a30);">
                            <i class="bi bi-hourglass-split" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div class="content">
                            <div class="value" style="color: #6b5b38;"><?= esc($TotalPendingRequests) ?></div>
                            <div class="label" style="color: #5a4a30;">Pending Requests</div>
                        </div>
                    </div>
                </div>

                <!-- Pending Attractions (spots) -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card shadow-sm border-0 rounded-3" style="background: linear-gradient(135deg, #f5f1e6 0%, #ede7d9 100%); border: 1.5px solid #e0d0b8;">
                        <div class="icon-wrapper rounded-2" style="background: linear-gradient(180deg, #6b5b38, #5a4a30);">
                            <i class="bi bi-flag" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div class="content">
                            <div class="value" style="color: #6b5b38;"><?= esc($TotalPendingSpots ?? 0) ?></div>
                            <div class="label" style="color: #5a4a30;">Pending Attractions</div>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card shadow-sm border-0 rounded-3" style="background: linear-gradient(135deg, #eef5ff 0%, #e6edff 100%); border: 1.5px solid #d4e3ff;">
                        <div class="icon-wrapper rounded-2" style="background: linear-gradient(180deg, #004a7c, #003d66);">
                            <i class="bi bi-calendar-check" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div class="content">
                            <div class="d-flex align-items-baseline gap-2">
                                <div class="value" style="color: #004a7c; line-height: 1.2;"><?= esc($TotalBookingsThisMonth) ?></div>
                                <div class="label" style="color: #003d66; font-size: 0.85rem; line-height: 1.4; margin: 0;">Bookings<br>(This Month)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Attractions -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card shadow-sm border-0 rounded-3" style="background: linear-gradient(135deg, #f0f8ff 0%, #e6f4ff 100%); border: 1.5px solid #cce5ff;">
                        <div class="icon-wrapper rounded-2" style="background: linear-gradient(180deg, #004a7c, #003d66);">
                            <i class="bi bi-geo-alt" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div class="content">
                            <div class="value" style="color: #004a7c;"><?= esc($TotalTouristSpots) ?></div>
                            <div class="label" style="color: #003d66;">Active Attractions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly trend and charts -->
            <div class="row g-4 mb-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100 card-modern rounded-4" style="overflow: hidden;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0 section-title fw-700">📈 Monthly Bookings Trend</h5>
                                <div class="small text-muted d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    <span>Last 12 months</span>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="monthlyBookingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100 card-modern rounded-4" style="overflow: hidden;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-primary"></i>
                                <span>Peak Visit Times</span>
                            </h5>
                            <div class="chart-container flex-grow-1"><canvas id="peakVisitChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW -->
            <div class="row g-4 mb-4">
                <!-- User Preference Distribution -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-hand-thumbs-up text-primary"></i>
                                <span>User Preferences</span>
                            </h5>
                            <div class="chart-container" style="height: 280px;">
                                <canvas id="userPreferenceChart"></canvas>
                            </div>
                            <div class="text-center mt-3 text-muted small">
                                Most popular categories selected by users
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-lightbulb text-primary"></i>
                                <span>Additional Insights</span>
                            </h5>
                            <div class="row g-3">
                                <!-- Avg Booking Value -->
                                <div class="col-md-6">
                                    <div class="insight-card p-3 rounded-3 bg-light border-0" style="background: linear-gradient(135deg, #eef5ff 0%, #f5e6d3 100%); border: 1px solid #e8d5c4;">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div>
                                                <p class="text-muted small fw-600 mb-1">Average Booking Value</p>
                                                <h5 class="fw-700" style="color: #004a7c;">₱<span id="avgBookingValue">0</span></h5>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> <span id="avgBookingTrend">0</span>% this month</small>
                                            </div>
                                            <i class="bi bi-cash-coin text-primary" style="font-size: 1.8rem; color: #004a7c; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Booking Conversion Rate -->
                                <div class="col-md-6">
                                    <div class="insight-card p-3 rounded-3 bg-light border-0" style="background: linear-gradient(135deg, #eef5ff 0%, #f5e6d3 100%); border: 1px solid #e8d5c4;">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div>
                                                <p class="text-muted small fw-600 mb-1">Booking Conversion Rate</p>
                                                <h5 class="fw-700" style="color: #004a7c;"><span id="conversionRate">0</span>%</h5>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> <span id="conversionTrend">0</span>% vs last month</small>
                                            </div>
                                            <i class="bi bi-percent text-primary" style="font-size: 1.8rem; color: #004a7c; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Peak Hours -->
                                <div class="col-md-6">
                                    <div class="insight-card p-3 rounded-3 bg-light border-0" style="background: linear-gradient(135deg, #eef5ff 0%, #f5e6d3 100%); border: 1px solid #e8d5c4;">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div>
                                                <p class="text-muted small fw-600 mb-1">Peak Booking Hours</p>
                                                <h5 class="fw-700" style="color: #004a7c;"><span id="peakHours">N/A</span></h5>
                                                <small class="text-muted"><i class="bi bi-clock"></i> Most bookings during this time</small>
                                            </div>
                                            <i class="bi bi-graph-up text-primary" style="font-size: 1.8rem; color: #004a7c; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Avg Rating -->
                                <div class="col-md-6">
                                    <div class="insight-card p-3 rounded-3 bg-light border-0" style="background: linear-gradient(135deg, #eef5ff 0%, #f5e6d3 100%); border: 1px solid #e8d5c4;">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div>
                                                <p class="text-muted small fw-600 mb-1">Platform Average Rating</p>
                                                <h5 class="fw-700" style="color: #004a7c;"><span id="avgRating">0</span>/5 <i class="bi bi-star-fill text-warning"></i></h5>
                                                <small class="text-muted">Based on <span id="ratingCount">0</span> reviews</small>
                                            </div>
                                            <i class="bi bi-star-fill text-primary" style="font-size: 1.8rem; color: #004a7c; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LISTS ROW -->
            <div class="row g-4">
                
                <!-- Top 5 Hidden Spots -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden;">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-stars text-warning"></i>
                                <span>Top 5 Recommended Hidden Spots</span>
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($topHiddenSpots)): ?>
                                <?php foreach ($topHiddenSpots as $index => $spot): ?>
                                    <div class="top-list-item">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-badge bg-gradient text-white fw-700 me-2"><?= $index + 1 ?></div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($spot['spot_name']) ?></div>
                                                <div class="small text-muted"><i class="bi bi-geo-alt-fill me-1"></i><?= esc($spot['location']) ?></div>
                                            </div>
                                        </div>
                                        <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-600">
                                            <?= number_format($spot['recommendation_count'], 1) ?> <i class="bi bi-star-fill ms-1"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No recommendations available yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Local Businesses -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4" style="overflow: hidden;">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-shop text-primary"></i>
                                <span>Top Viewed Businesses</span>
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($topViewedBusinesses)): ?>
                                <?php foreach ($topViewedBusinesses as $index => $biz): ?>
                                    <div class="top-list-item border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-badge bg-gradient text-white fw-700 me-2"><?= $index + 1 ?></div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($biz['business_name']) ?></div>
                                                <div class="small text-muted d-flex align-items-center gap-1">
                                                    <i class="bi bi-building"></i>
                                                    <span>Registered Partner</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="badge bg-primary text-white rounded-pill px-3 py-2 fw-600">
                                            <i class="bi bi-eye-fill me-1"></i><?= number_format($biz['view_count']) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No data available yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PERFORMANCE METRICS ROW -->
            <div class="row g-4 mt-1">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">
                        <div class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-graph-up-arrow text-success"></i>
                                <span>Performance Metrics: Top 3 Spots by Revenue (Last 30 Days)</span>
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($topSpotsPerformance)): ?>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-muted small">#</th>
                                                <th class="text-muted small">Tourist Spot</th>
                                                <th class="text-muted small text-end">Revenue</th>
                                                <th class="text-muted small text-end">Avg Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topSpotsPerformance as $idx => $row): ?>
                                                <tr>
                                                    <td class="fw-700"><?= $idx + 1 ?></td>
                                                    <td class="fw-600 text-dark"><?= esc($row['spot_name'] ?? 'Spot') ?></td>
                                                    <td class="text-end">₱<?= number_format((float)($row['total_revenue'] ?? $row['revenue'] ?? 0), 2) ?></td>
                                                    <td class="text-end">
                                                        <?= number_format((float)($row['avg_rating'] ?? 0), 1) ?>
                                                        <i class="bi bi-star-fill text-warning ms-1"></i>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-4 mb-0">No performance data available for the period.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>const BASE_URL = "<?= base_url() ?>";</script>

    <!-- DATA INJECTION -->
    <script>
        window.dashboardData = {
            peakVisitTimes: <?= !empty($peakVisitTimes) ? $peakVisitTimes : '[]' ?>,
            userPreferences: <?= !empty($userPreferences) ? $userPreferences : '[]' ?>,
            monthlyBookings: <?= !empty($MonthlyBookingsTrend) ? $MonthlyBookingsTrend : '[]' ?>,
            metrics: <?= !empty($metricsJSON) ? $metricsJSON : '{"conversionRate":0,"conversionTrend":0}' ?>
        };
    </script>

    <script src="<?= base_url('assets/js/admin-script.js') ?>"></script>
    <script>
        // Render additional charts on dashboard using Chart.js and the injected data.
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const monthly = window.dashboardData.monthlyBookings || [];
                const peak = window.dashboardData.peakVisitTimes || [];
                const prefs = window.dashboardData.userPreferences || [];

                // Monthly bookings line chart
                const mbCanvas = document.getElementById('monthlyBookingsChart');
                if (mbCanvas && monthly.length) {
                    const existing = Chart.getChart(mbCanvas);
                    if (existing) existing.destroy();
                    const labels = monthly.map(m => m.month || m.label);
                    const dataPoints = monthly.map(m => parseFloat(m.total_bookings || m.count || 0));
                    // Ensure canvas has an explicit pixel height matching its container
                    const mbContainerHeight = mbCanvas.parentElement.clientHeight || 320;
                    mbCanvas.style.height = mbContainerHeight + 'px';
                    mbCanvas.height = mbContainerHeight;
                    const mbCtx = mbCanvas.getContext('2d');
                    const mbConfig = {
                        type: 'line',
                        data: { labels: labels, datasets: [{ label: 'Bookings', data: dataPoints, borderColor: '#4e73df', backgroundColor: '#4e73df33', tension: 0.35, fill: true, pointRadius: 3 }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { drawOnChartArea: true, borderDash: [2,3] } } } }
                    };
                    // Use centralized chart factory to apply theme
                    if (window.adminCharts && adminCharts.createChart) adminCharts.createChart(mbCtx, mbConfig);
                    else new Chart(mbCtx, mbConfig);
                }

                // Peak visit chart (may be already used elsewhere, safe re-render)
                const pvCanvas = document.getElementById('peakVisitChart');
                if (pvCanvas && peak.length) {
                    const existing = Chart.getChart(pvCanvas);
                    if (existing) existing.destroy();
                    const labels = peak.map(p => p.label || p.hour || p.slot);
                    const data = peak.map(p => p.value || p.total || 0);
                    // Fix canvas height to container to avoid layout bouncing
                    const pvHeight = pvCanvas.parentElement.clientHeight || 320;
                    pvCanvas.style.height = pvHeight + 'px'; pvCanvas.height = pvHeight;
                    adminCharts.createChart(pvCanvas.getContext('2d'), { type: 'bar', data: { labels, datasets: [{ label: 'Visitors', data, backgroundColor: '#36b9cc', gradient: ['#36b9cc', '#a0dfe7'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true } } } });
                }

                // Small sparkline for bookings (if Monthly bookings available)
                const sparkHost = document.querySelector('.analytics-card .content');
                // render a small sparkline inside the Bookings card if element exists
                const bookingCard = Array.from(document.querySelectorAll('.analytics-card')).find(c => c.querySelector('.label')?.textContent?.includes('Bookings'));
                if (bookingCard && monthly.length) {
                    // create tiny canvas
                    let canvas = bookingCard.querySelector('.kpi-spark');
                    if (!canvas) {
                        canvas = document.createElement('canvas');
                        canvas.className = 'kpi-spark mt-2';
                        bookingCard.querySelector('.content').appendChild(canvas);
                    }
                    const existing = Chart.getChart(canvas);
                    if (existing) existing.destroy();
                    const sparkHeight = Math.min(48, bookingCard.querySelector('.content').clientHeight || 36);
                    canvas.style.height = sparkHeight + 'px'; canvas.height = sparkHeight;
                    const sparkCtx = canvas.getContext('2d');
                    const sparkConfig = {
                        type: 'line',
                        data: { labels: monthly.map(m => m.month || ''), datasets: [{ data: monthly.map(m => parseFloat(m.total_bookings||0)), borderColor: '#4e73df', backgroundColor: '#4e73df22', fill: true, tension: 0.3, pointRadius:0 }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins:{legend:{display:false}}, elements:{line:{borderWidth:1.5}}, scales:{x:{display:false},y:{display:false}} }
                    };
                    if (window.adminCharts && adminCharts.createChart) adminCharts.createChart(sparkCtx, sparkConfig);
                    else new Chart(sparkCtx, sparkConfig);
                }

                // Populate Additional Insights metrics
                if (monthly.length) {
                    // Calculate Average Booking Value
                    const totalRevenue = monthly.reduce((sum, m) => sum + (parseFloat(m.total_revenue || 0)), 0);
                    const totalBookings = monthly.reduce((sum, m) => sum + (parseFloat(m.total_bookings || m.count || 0)), 0);
                    const avgValue = totalBookings > 0 ? (totalRevenue / totalBookings).toFixed(2) : 0;
                    document.getElementById('avgBookingValue').textContent = parseFloat(avgValue).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    
                    // Calculate trend (current month vs previous)
                    if (monthly.length >= 2) {
                        const currentMonth = parseFloat(monthly[monthly.length - 1].total_bookings || 0);
                        const previousMonth = parseFloat(monthly[monthly.length - 2].total_bookings || 0);
                        const trend = previousMonth > 0 ? (((currentMonth - previousMonth) / previousMonth) * 100).toFixed(1) : 0;
                        document.getElementById('avgBookingTrend').textContent = Math.abs(trend);
                    }
                }

                // Use server-side conversion metrics from DB
                if (window.dashboardData.metrics) {
                    const m = window.dashboardData.metrics;
                    if (typeof m.conversionRate !== 'undefined') {
                        document.getElementById('conversionRate').textContent = (parseFloat(m.conversionRate) || 0).toFixed(1);
                    }
                    if (typeof m.conversionTrend !== 'undefined') {
                        document.getElementById('conversionTrend').textContent = (parseFloat(m.conversionTrend) || 0).toFixed(1);
                    }
                }

                if (peak.length) {
                    // Find peak hours
                    const peakHour = peak.reduce((max, p) => {
                        const val = parseFloat(p.value || p.total || 0);
                        return val > (max.value || 0) ? p : max;
                    }, {});
                    const peakLabel = peakHour.label || peakHour.hour || peakHour.slot || 'N/A';
                    document.getElementById('peakHours').textContent = peakLabel;
                }

                // Display actual average rating and feedback count from database
                const avgRating = '<?= esc($data['satisfactionScore'] ?? 3.5) ?>';
                const ratingCount = <?= (int)($data['totalFeedbackCount'] ?? 0) ?>;
                document.getElementById('avgRating').textContent = avgRating;
                document.getElementById('ratingCount').textContent = ratingCount.toLocaleString('en-US');
            } catch (e) { console.warn('Dashboard chart render error', e); }
        });
    </script>
</body>
    <script src="<?= base_url('assets/js/admin-ui.js') ?>"></script>
</html>