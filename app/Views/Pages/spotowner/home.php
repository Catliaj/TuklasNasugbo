<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tourist Spot Owner Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
    
    <!-- Inline styles to prevent Chart.js canvases from auto-stretching -->
    <style>
        .chart-container {
            width: 100%;
            max-width: 100%;
            height: 320px;
            display: block;
        }
        canvas.chart-canvas {
            width: 100% !important;
            height: 320px !important;
            display: block;
            max-width: 100%;
        }
        @media (max-width: 767px) {
            .chart-container, canvas.chart-canvas { height: 240px !important; }
        }
    </style>
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
                        <a href="/spotowner/dashboard" class="sidebar-link active" data-page="home">
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
                        <a href="/spotowner/earnings" class="sidebar-link" data-page="earnings">
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
    <!-- Desktop Notification Bell -->
    <div class="dropdown mb-3">
      
               
                    
            
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow-lg w-100" style="max-width: 380px; max-height: 500px;">
            <div class="dropdown-header d-flex justify-content-between align-items-center bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold">Notifications</h6>
                <button class="btn btn-sm btn-link text-white text-decoration-none" id="markAllReadBtnDesktop">
                    Mark all read
                </button>
            </div>
            <div class="dropdown-divider m-0"></div>
            <div id="notificationListDesktop" style="max-height: 400px; overflow-y: auto;">
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mb-0 mt-2">No notifications</p>
                </div>
            </div>
        </div>
    </div>
    
    
</div>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">


        <?= view('Pages/spotowner/_mobile_header', ['subtitle' => 'Owner Dashboard', 'FullName' => $FullName ?? null, 'email' => $email ?? null]) ?>

            <!-- Page Content - This will be populated by JavaScript -->


            <!-- Page Content - This will be populated by JavaScript -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <!-- Content will be loaded here dynamically by home.js -->
                <div class="container-fluid px-0">
                    <!-- Overview Stat Cards Placeholder -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Spots</div>
                                        <div class="stat-value" id="stat-total-spots">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </div>
                                        <div class="stat-description">Active properties</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Bookings</div>
                                        <div class="stat-value" id="stat-total-bookings">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </div>
                                        <div class="stat-description">This month</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Revenue</div>
                                        <div class="stat-value" id="stat-total-revenue">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </div>
                                        <div class="stat-description">This month</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Average Rating</div>
                                        <div class="stat-value" id="stat-avg-rating">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </div>
                                        <div class="stat-description">Across all spots</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </main>
        </div>
    </div>

  

 

    <!-- 1. Chart.js (MUST BE FIRST) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- 2. Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 3. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 4. Sidebar Toggle (IMPORTANT!) -->
    <script src="<?= base_url('assets/js/sidebar.js')?>"></script>

    <!-- 5. Page-specific scripts -->
    <script src="<?= base_url('assets/js/spotownerJS/shared-data.js')?>"></script>
    <script src="<?= base_url('assets/js/spotownerJS/home.js')?>"></script>

    <!-- 6. Initialize home page -->
    <script>
    // Wait for DOM to be ready and then render the page
document.addEventListener('DOMContentLoaded', async function() {
    // Prevent double initialization when scripts are loaded multiple times
    if (!window._home) window._home = { initialized: false, refreshing: false };
    if (window._home.initialized) {
        console.log('Home page already initialized — skipping duplicate DOMContentLoaded handler');
        return;
    }

    window._home.initialized = true;
    console.log('DOM loaded, initializing home page...');
    
    // Get the main content area
    const mainContent = document.getElementById('mainContent');
    
    if (mainContent && typeof renderHomePage === 'function') {
        // Render the HTML
        mainContent.innerHTML = renderHomePage();
        console.log('Home page HTML rendered');
        
        // Fetch real data from database FIRST
        await fetchTouristSpots();
        
        // Then initialize the page (load data and charts)
        if (typeof initHomePage === 'function') {
            await initHomePage();
            console.log('Home page initialized');
        } else {
            console.error('initHomePage function not found!');
        }
    } else {
        console.error('mainContent element or renderHomePage function not found!');
    }
});
</script>

<script>
// Function to refresh home page data
window.refreshHomeData = async function() {
    if (!window._home) window._home = { initialized: false, refreshing: false };
    if (window._home.refreshing) {
        console.log('Refresh already in progress — skipping');
        return;
    }

    window._home.refreshing = true;
    console.log('🔄 Refreshing home page data...');
    try {
        if (typeof fetchTouristSpots === 'function') {
            await fetchTouristSpots();
            if (typeof loadTouristSpotsGrid === 'function') {
                loadTouristSpotsGrid();
            }
            if (typeof updateOverviewStats === 'function') {
                updateOverviewStats();
            }
        }
    } catch (e) {
        console.error('Error during refreshHomeData:', e);
    } finally {
        window._home.refreshing = false;
    }
};

// Check for updates every 30 seconds when page is visible
let refreshInterval = null;

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Clear interval when tab is hidden
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
    } else {
        // Refresh immediately when tab becomes visible
        if (typeof window.refreshHomeData === 'function') {
            window.refreshHomeData();
        }
        
        // Set up periodic refresh every 30 seconds
        if (!refreshInterval) {
            refreshInterval = setInterval(function() {
                if (typeof window.refreshHomeData === 'function') {
                    window.refreshHomeData();
                }
            }, 30000); // 30 seconds
        }
    }
});

// Initial setup when page loads
if (!document.hidden && !refreshInterval) {
    refreshInterval = setInterval(function() {
        if (typeof window.refreshHomeData === 'function') {
            window.refreshHomeData();
        }
    }, 30000);
}
</script>
<script src="<?= base_url('assets/js/spotownerJS/notifications.js') ?>"></script>
</body>
</html>


