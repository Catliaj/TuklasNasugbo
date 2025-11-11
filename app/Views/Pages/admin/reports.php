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
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-compass"></i>
            <span>Tourism Admin</span>
        </div>
        
                <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item ">
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
            <a href="/" class="nav-item text-danger">
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


<!-- Reports & Analytics Page Content -->
<div class="page-header">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="text-muted mb-0">Comprehensive analytics and downloadable reports</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="refreshReports()">
            <i class="bi bi-arrow-clockwise me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" id="reportFromDate">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" id="reportToDate">
            </div>
            <div class="col-md-3">
                <label class="form-label">Report Type</label>
                <select class="form-select" id="reportType">
                    <option value="all">All Data</option>
                    <option value="bookings">Bookings Only</option>
                    <option value="registrations">Registrations Only</option>
                    <option value="attractions">Attractions Only</option>
                    <option value="reviews">Reviews Only</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="applyReportFilter()">
                    <i class="bi bi-funnel me-2"></i>Apply Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Overview -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="analytics-card primary">
            <div class="analytics-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="analytics-content">
                <h3 id="totalBookings">0</h3>
                <p>Total Bookings</p>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> 12% from last month
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="analytics-card success">
            <div class="analytics-icon">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div class="analytics-content">
                <h3 id="totalAttractions">0</h3>
                <p>Active Attractions</p>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> 3 new this month
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="analytics-card warning">
            <div class="analytics-icon">
                <i class="bi bi-star"></i>
            </div>
            <div class="analytics-content">
                <h3 id="avgRating">0</h3>
                <p>Average Rating</p>
                <small class="text-muted">Based on all reviews</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="analytics-card info">
            <div class="analytics-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="analytics-content">
                <h3 id="totalRevenue">₱0</h3>
                <p>Total Revenue</p>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> 8% increase
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-bar-chart me-2"></i>Bookings Trend
                </h5>
                <div style="height: 300px; max-height: 300px; position: relative;">
                    <canvas id="bookingsTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-pie-chart me-2"></i>Category Distribution
                </h5>
                <div style="height: 300px; max-height: 300px; position: relative;">
                    <canvas id="categoryDistChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Attractions Table -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-trophy me-2"></i>Top Performing Attractions
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Rank</th>
                        <th>Attraction</th>
                        <th>Category</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody id="topAttractionsTable">
                    <!-- Data will be loaded by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-download me-2"></i>Download Reports
        </h5>
        <div class="row g-3">
            <div class="col-md-3">
                <button class="btn btn-outline-success w-100" onclick="exportExcel()">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-danger w-100" onclick="exportPDF()">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export to PDF
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100" onclick="exportCSV()">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export to CSV
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-info w-100" onclick="printReport()">
                    <i class="bi bi-printer me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize reports page
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    loadReports();
});
</script>

    </div>
    <!-- End Main Content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>
</body>
</html>
