<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourism Admin Dashboard - Attractions</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    
    <!-- ========================================================== -->
    <!-- REQUIRED META TAGS AND SCRIPT FOR API CALLS -->
    <!-- ========================================================== -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
    <script>const BASE_URL = '<?= base_url() ?>';</script>
    <!-- ========================================================== -->

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
                <span class="badge-pending-registrations badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/attractions" class="nav-item active">
                <i class="bi bi-geo-alt"></i>
                <span>Attractions</span>
                <span class="badge-pending-attractions badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/reports" class="nav-item">
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
                
                <div class="dropdown">
                    <button class="btn btn-link text-dark position-relative notification-button" id="notificationButtonAttractions" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuAttractions" style="min-width:320px">
                        <li class="dropdown-item text-muted small">No new notifications</li>
                    </ul>
                </div>
                
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

            <!-- Attractions Page Content -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Attractions Management</h1>
                    <p class="text-muted mb-0">View and manage all tourist attractions</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="loadPendingAttractions_API()">
                        <i class="bi bi-hourglass-split me-2"></i>Pending Requests
                    </button>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search attractions by name or location..." id="searchAttractions">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterCategory">
                                <option value="">All Categories</option>
                                <!-- Database categories: Eateries, Beaches, Events, Islands, Heritage, Adventure, Waterfalls -->
                                <option value="Eateries">Eateries</option>
                                <option value="Beaches">Beaches</option>
                                <option value="Events">Events</option>
                                <option value="Islands">Islands</option>
                                <option value="Heritage">Heritage</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Waterfalls">Waterfalls</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="approved">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <!-- Filter button removed: filtering happens automatically on input/select change -->
                    </div>
                </div>
            </div>

            <div class="row" id="attractionsGrid">
                <!-- Attraction cards will be loaded by JavaScript -->
            </div>

            <!-- View Attraction Modal -->
            <div class="modal fade" id="viewAttractionModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attraction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="viewAttractionContent">
                            <!-- Content will be loaded by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Modal -->
            <div class="modal fade" id="pendingRequestsModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pending Attraction Requests</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="pendingRequestsContent">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Attraction</th><th>Business</th><th>Category</th><th>Submitted</th><th class="text-center">Actions</th></tr>
                                    </thead>
                                    <tbody id="pendingRequestsTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Approve Attraction Modal -->
                <div class="modal fade" id="approveAttractionModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Approve Attraction</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to approve this attraction?</p>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    The attraction will become visible to users when approved.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success" onclick="confirmApproveAttraction_API()"><i class="bi bi-check-circle me-2"></i>Approve</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reject Attraction Modal -->
                <div class="modal fade" id="rejectAttractionModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Reject Attraction</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject this attraction?</p>
                                <div class="mb-3">
                                    <label class="form-label">Reason for Rejection:</label>
                                    <textarea class="form-control" rows="3" id="pendingRejectReason" placeholder="Enter reason for rejection..."></textarea>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    The spot owner will be notified with the reason provided.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" onclick="confirmRejectAttraction_API()"><i class="bi bi-x-circle me-2"></i>Reject</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Suspend Modal -->
            <div class="modal fade" id="suspendModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Suspend Attraction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to suspend this attraction?</p>
                            <div class="mb-3">
                                <label class="form-label">Reason for Suspension:</label>
                                <textarea class="form-control" rows="3" id="suspendReason" placeholder="Enter reason for suspension..."></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                This attraction will be hidden from users until reactivated.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning" onclick="confirmSuspend_API()">
                                <i class="bi bi-pause-circle me-2"></i>Suspend
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Delete Attraction</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger fw-bold">⚠️ Warning: This action cannot be undone!</p>
                            <p>Are you sure you want to permanently delete this attraction?</p>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-octagon me-2"></i>
                                All associated data including bookings and reviews will be deleted.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type "DELETE" to confirm:</label>
                                <input type="text" class="form-control" id="deleteConfirmText" placeholder="DELETE">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete_API()">
                                <i class="bi bi-trash me-2"></i>Delete Permanently
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Container Fluid -->
    </div> <!-- End Main Content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url("assets/js/admin-script.js")?>"></script>

    <!-- Initializer script for this page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // initSidebarToggle is already in the main script, so we don't need to call it again.
            // This call will fetch the data from the database and display it.
            loadAttractions_API();
        });
    </script>
</body>
</html>