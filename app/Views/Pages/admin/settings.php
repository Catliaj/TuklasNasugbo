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
            <a href="/admin/reports" class="nav-item">
                <i class="bi bi-file-bar-graph"></i>
                <span>Reports & Analytics</span>
            </a>
            <a href="/admin/settings" class="nav-item active">
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


<!-- Settings Page Content -->
<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <p class="text-muted mb-0">Manage your account and preferences</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-4">Profile Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="profileName" value="Tourism Office Admin">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="profileEmail" value="admin@tourism.gov">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="profilePhone" value="+63 912 345 6789">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="Super Admin" disabled>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" onclick="saveProfile()">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-4">Change Password</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword">
                    </div>
                    <div class="col-12">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword">
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" onclick="changePassword()">
                        <i class="bi bi-key me-2"></i>Update Password
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Notification Preferences</h5>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    <label class="form-check-label" for="emailNotifications">
                        Email Notifications
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="newRegistrations" checked>
                    <label class="form-check-label" for="newRegistrations">
                        New Registration Requests
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="newBookings" checked>
                    <label class="form-check-label" for="newBookings">
                        New Bookings
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="weeklyReports">
                    <label class="form-check-label" for="weeklyReports">
                        Weekly Summary Reports
                    </label>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" onclick="saveNotificationSettings()">
                        <i class="bi bi-save me-2"></i>Save Preferences
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-4">Account Information</h5>
                <div class="mb-3">
                    <small class="text-muted">Account Created</small>
                    <p class="mb-0">January 15, 2025</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Last Login</small>
                    <p class="mb-0">October 27, 2025 - 10:30 AM</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Account Status</small>
                    <p class="mb-0"><span class="badge bg-success">Active</span></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">System Information</h5>
                <div class="mb-3">
                    <small class="text-muted">Version</small>
                    <p class="mb-0">Admin Dashboard v1.0.0</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Database</small>
                    <p class="mb-0">MySQL 8.0</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">PHP Version</small>
                    <p class="mb-0">8.2.0</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize settings page
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    loadSettings();
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
