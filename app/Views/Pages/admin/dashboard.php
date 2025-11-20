<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourism Admin Dashboard</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        /* Ported Styles from Reports Page for Consistency */
        .chart-container { position: relative; height: 320px; width: 100%; }
        
        .analytics-card { 
            display: flex; align-items: center; padding: 1.5rem; 
            border-radius: 1rem; background-color: #fff; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #f0f0f0;
            height: 100%; transition: transform 0.2s;
        }
        .analytics-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
        
        .analytics-card .icon-wrapper { 
            width: 56px; height: 56px; border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; 
            margin-right: 1rem; font-size: 1.75rem;
        }
        
        .analytics-card .content { flex-grow: 1; }
        .analytics-card .value { font-size: 1.75rem; font-weight: 700; line-height: 1.2; margin-bottom: 0.25rem; color: #2d3748; }
        .analytics-card .label { font-size: 0.875rem; color: #718096; font-weight: 500; }

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
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="bi bi-compass"></i><span>Tourism Admin</span></div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item active"><i class="bi bi-grid"></i><span>Dashboard</span></a>
            <a href="/admin/registrations" class="nav-item"><i class="bi bi-person-plus"></i><span>Registrations</span></a>
            <a href="/admin/attractions" class="nav-item"><i class="bi bi-geo-alt"></i><span>Attractions</span></a>
            <a href="/admin/reports" class="nav-item"><i class="bi bi-file-bar-graph"></i><span>Reports & Analytics</span></a>
        </nav>
        <div class="sidebar-footer"><a href="/users/logout" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i><span>Logout</span></a></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        
        <!-- TOPBAR -->
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

        <div class="container-fluid p-4">
            
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-gray-800">Dashboard Overview</h1>
                    <p class="text-muted">Real-time insights and performance metrics.</p>
                </div>
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                </button>
            </div>

            <!-- KPI CARDS (DESIGN MATCHING REPORTS) -->
            <div class="row g-4 mb-4">
                <!-- Tourist Satisfaction Score -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card">
                        <div class="icon-wrapper bg-soft-primary"><i class="bi bi-emoji-smile"></i></div>
                        <div class="content">
                            <div class="value"><?= esc($satisfactionScore) ?> / 5.0</div>
                            <div class="label">Tourist Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Total Pending Requests -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card">
                        <div class="icon-wrapper bg-soft-warning"><i class="bi bi-hourglass-split"></i></div>
                        <div class="content">
                            <div class="value"><?= esc($TotalPendingRequests) ?></div>
                            <div class="label">Pending Requests</div>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card">
                        <div class="icon-wrapper bg-soft-info"><i class="bi bi-calendar-check"></i></div>
                        <div class="content">
                            <div class="value"><?= esc($TotalBookingsThisMonth) ?></div>
                            <div class="label">Bookings (This Month)</div>
                        </div>
                    </div>
                </div>

                <!-- Total Attractions -->
                <div class="col-xl-3 col-md-6">
                    <div class="analytics-card">
                        <div class="icon-wrapper bg-soft-success"><i class="bi bi-geo-alt"></i></div>
                        <div class="content">
                            <div class="value"><?= esc($TotalTouristSpots) ?></div>
                            <div class="label">Active Attractions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW -->
            <div class="row g-4 mb-4">
                <!-- Peak Visit Times -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Peak Visit Times (Last 7 Days)</h5>
                            <div class="chart-container">
                                <canvas id="peakVisitChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Preference Distribution -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">User Preferences</h5>
                            <div class="chart-container" style="height: 280px;">
                                <canvas id="userPreferenceChart"></canvas>
                            </div>
                            <div class="text-center mt-3 text-muted small">
                                Most popular categories selected by users.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LISTS ROW -->
            <div class="row g-4">
                
                <!-- Top 5 Hidden Spots -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="card-title fw-bold mb-0"><i class="bi bi-stars text-warning me-2"></i>Top 5 Recommended Hidden Spots</h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($topHiddenSpots)): ?>
                                <?php foreach ($topHiddenSpots as $index => $spot): ?>
                                    <div class="top-list-item">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-badge"><?= $index + 1 ?></div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($spot['spot_name']) ?></div>
                                                <div class="small text-muted"><i class="bi bi-geo-alt-fill me-1"></i><?= esc($spot['location']) ?></div>
                                            </div>
                                        </div>
                                        <div class="badge bg-soft-success text-success rounded-pill px-3 py-2">
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
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="card-title fw-bold mb-0"><i class="bi bi-shop text-primary me-2"></i>Top Viewed Businesses</h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($topViewedBusinesses)): ?>
                                <?php foreach ($topViewedBusinesses as $index => $biz): ?>
                                    <div class="top-list-item">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-badge"><?= $index + 1 ?></div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($biz['business_name']) ?></div>
                                                <div class="small text-muted">Registered Partner</div>
                                            </div>
                                        </div>
                                        <div class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-eye-fill me-1"></i> <?= number_format($biz['view_count']) ?> Views
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

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>const BASE_URL = "<?= base_url() ?>";</script>

    <!-- DATA INJECTION -->
    <script>
        window.dashboardData = {
            peakVisitTimes: <?= $peakVisitTimes ?>,
            userPreferences: <?= $userPreferences ?>
        };
    </script>

    <script src="<?= base_url('assets/js/admin-script.js') ?>"></script>
</body>
</html>