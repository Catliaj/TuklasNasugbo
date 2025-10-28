<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourism Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
</head>
<style>
    #bookingsChart, #categoryChart {
  height: 300px !important; /* fixed height for the chart area */
  max-height: 400px;
}

    </style>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-compass"></i>
            <span>Tourism Admin</span>
        </div>
        
               <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item active">
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
            <a href="/admin/reports" class="nav-item">
                <i class="bi bi-file-bar-graph"></i>
                <span>Reports & Analytics</span>
            </a>
            <a href="/admin/settings" class="nav-item">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../logout.php" class="nav-item text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <button class="btn btn-link text-dark" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex align-items-center gap-3">
                <div class="search-box d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                
                <button class="btn btn-link text-dark position-relative">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="notification-dot"></span>
                </button>
                
                <div class="dropdown">
                    <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 me-2"></i>
                        <span class="d-none d-md-inline">Admin User</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?page=settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="#profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content Container -->
        <div class="container-fluid">


<!-- Dashboard Page Content -->
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back! Here's what's happening with your tourism sites today.</p>
    </div>
    <button class="btn btn-primary" onclick="refreshDashboard()">
        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
    </button>
</div>

<!-- Stats Cards -->
<div class="row mb-4" id="statsContainer">
     <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card primary">
                <div class="stats-card-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="stats-card-content">
                    <h3><?=$TotalPendingRequests?></h3>
                    <p>Pending Requests</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card success">
                <div class="stats-card-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="stats-card-content">
                    <h3><?= $TotalTouristSpots?></h3>
                    <p>Total Attractions</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card info">
                <div class="stats-card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stats-card-content">
                    <h3><?=$TotalBookingsThisMonth?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card revenue">
                <div class="stats-card-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-card-content">
                    <h3><?= $TotalTodayBookings?></h3>
                    <p>Today's Bookings</p>
                </div>
            </div>
        </div>
</div>

<div class="row mb-4">
    <!-- Monthly Bookings Chart -->
    <div class="col-lg-8 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-4">Monthly Bookings Trend</h5>
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Distribution Chart -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-4">Attraction Categories</h5>
                <canvas id="categoryChart"></canvas>

            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Recent Activity</h5>
                <div class="activity-feed" id="activityFeed">
                    <!-- Activity items will be loaded by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
  
    loadDashboard();
});
</script>

    </div>
    <!-- End Main Content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
<script>
  window.monthlyBookingsTrend = <?= $MonthlyBookingsTrend ?>;
   const categoryData = <?= $TotalCategories  ?>;
   console.log("Category data:", categoryData);

</script>



    <script src="<?= base_url('assets/js/admin-script.js') ?>"></script>



</body>
</html>
