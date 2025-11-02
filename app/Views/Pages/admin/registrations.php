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
            <a href="/admin/registrations" class="nav-item active">
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
            <!-- Registrations Page Content -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Registration Requests</h1>
                    <p class="text-muted mb-0">Review and approve spot owner registration requests</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="filterRegistrations('all')">
                        <i class="bi bi-list-ul me-2"></i>All
                    </button>
                    <button class="btn btn-outline-warning" onclick="filterRegistrations('pending')">
                        <i class="bi bi-clock me-2"></i>Pending
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Business Name</th>
                                    <th style="width: 15%;">Owner Name</th>
                                    <th style="width: 15%;">Email</th>
                                    <th style="width: 12%;">Phone</th>
                                    <th style="width: 15%;">Location</th>
                                    <th style="width: 10%;">Submitted</th>
                                    <th style="width: 8%;" class="text-center">Status</th>
                                    <th style="width: 10%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="registrationsTable">
                                <!-- Table rows will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- View Registration Modal -->
            <div class="modal fade" id="viewRegistrationModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Registration Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="viewRegistrationContent">
                            <!-- Content will be loaded by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Approve Registration</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to approve this registration?</p>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                The spot owner will receive an email notification and can start using the system.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" onclick="confirmApprove()">
                                <i class="bi bi-check-circle me-2"></i>Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Reject Registration</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject this registration?</p>
                            <div class="mb-3">
                                <label class="form-label">Reason for Rejection:</label>
                                <textarea class="form-control" rows="3" id="rejectReason" placeholder="Enter reason for rejection..."></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                The spot owner will be notified via email with the reason provided.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" onclick="confirmReject()">
                                <i class="bi bi-x-circle me-2"></i>Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        initSidebar();
        loadRegistrations();
    });
    </script>
</body>
</html>
