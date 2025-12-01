<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?> - Attractions</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    <?php
        $settingsPath = WRITEPATH . 'settings.json';
        $currentSettings = [];
        if (file_exists($settingsPath)) {
            $currentSettings = json_decode(file_get_contents($settingsPath), true) ?: [];
        }
    ?>
    <style>:root { --primary-blue: <?= esc($currentSettings['primary_color'] ?? '#004a7c') ?>; }</style>
    
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
    <div class="sidebar" id="sidebar" role="navigation" aria-label="Admin sidebar">
        <div class="sidebar-header">
            <i class="bi bi-compass"></i>
            <span><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?></span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item " aria-label="Dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/registrations" class="nav-item" aria-label="Registrations">
                <i class="bi bi-person-plus"></i>
                <span>Registrations</span>
                <span class="badge-pending-registrations badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/attractions" class="nav-item active" aria-current="page">
                <i class="bi bi-geo-alt"></i>
                <span>Attractions</span>
                <span class="badge-pending-attractions badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/reports" class="nav-item" aria-label="Reports &amp; Analytics">
                <i class="bi bi-file-bar-graph"></i>
                <span>Reports & Analytics</span>
            </a>
        </nav>
        
        <div class="sidebar-footer"><!-- Logout moved to profile menu; removed duplicate link here --></div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar" role="banner">
            <button class="btn btn-link text-dark" id="sidebarToggle" aria-controls="sidebar" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex align-items-center gap-3">
                
                <div class="dropdown">
                        <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationButtonAttractions" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                            <i class="bi bi-bell-fill text-white"></i>
                            <span class="notification-badge badge rounded-pill bg-danger"><?= (isset($unreadNotifications) && $unreadNotifications > 0) ? esc($unreadNotifications) : '' ?></span>
                        </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuAttractions" style="min-width:320px">
                        <li class="dropdown-item text-muted small">No new notifications</li>
                    </ul>
                </div>
                
                <div class="dropdown">
                    <?php $adminName = 'Admin User'; $nameParts = array_filter(explode(' ', trim($adminName))); $initials = strtoupper(substr($nameParts[0] ?? '',0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : '')); ?>
                    <button class="btn p-0 border-0 topbar-avatar topbar-avatar--primary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                        <span><?= esc($initials) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width:220px">
                        <li class="px-3 py-2">
                            <div class="fw-bold">Admin User</div>
                            <div class="small text-muted">admin@example.com</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="/admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content Container -->
        <div class="container-fluid">

            <!-- Attractions Page Content -->
            <div class="page-header mb-4">
                <div>
                    <h1 class="page-title fw-700">✨ Attractions Management</h1>
                    <p class="text-muted mb-0">View, manage, and moderate all tourist attractions</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary d-flex align-items-center gap-2" onclick="loadPendingAttractions_API()">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending Requests</span>
                    </button>
                </div>
            </div>

            <div class="card mb-4 search-filter-card">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label fw-600 d-flex align-items-center gap-2">
                                <i class="bi bi-search text-primary"></i>
                                <span>Search Attractions</span>
                            </label>
                            <input type="text" class="form-control form-control-enhanced" placeholder="Name, location, or description..." id="searchAttractions">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-600 d-flex align-items-center gap-2">
                                <i class="bi bi-tag text-info"></i>
                                <span>Category</span>
                            </label>
                            <select class="form-select form-select-enhanced" id="filterCategory">
                                <option value="">All Categories</option>
                                <option value="Historical">🏛️ Historical</option>
                                <option value="Cultural">🎭 Cultural</option>
                                <option value="Natural">🌲 Natural</option>
                                <option value="Recreational">🎢 Recreational</option>
                                <option value="Religious">⛪ Religious</option>
                                <option value="Adventure">🚀 Adventure</option>
                                <option value="Ecotourism">🌿 Ecotourism</option>
                                <option value="Urban">🏙️ Urban</option>
                                <option value="Rural">🌾 Rural</option>
                                <option value="Beach">🏖️ Beach</option>
                                <option value="Mountain">⛰️ Mountain</option>
                                <option value="Resort">🏨 Resort</option>
                                <option value="Park">🎪 Park</option>
                                <option value="Restaurant">🍽️ Restaurant</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-600 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle text-success"></i>
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-enhanced" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="approved">✓ Active</option>
                                <option value="suspended">⏸ Suspended</option>
                                <option value="rejected">✗ Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="attractionsGrid" class="attractions-grid">
                <!-- Attraction cards will be loaded by JavaScript -->
            </div>

            <!-- View Attraction Modal - Enhanced -->
            <div class="modal fade" id="viewAttractionModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-gradient border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-geo-alt me-2"></i>Attraction Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="viewAttractionContent">
                            <!-- Content will be loaded by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Modal - Enhanced -->
            <div class="modal fade" id="pendingRequestsModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-gradient border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-hourglass-split me-2"></i>Pending Attraction Requests
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="pendingRequestsContent">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-enhanced">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Attraction</th>
                                            <th>Business</th>
                                            <th>Category</th>
                                            <th>Submitted</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingRequestsTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Approve Attraction Modal - Enhanced -->
                <div class="modal fade" id="approveAttractionModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-success bg-gradient text-white border-0">
                                <h5 class="modal-title fw-700">
                                    <i class="bi bi-check-circle me-2"></i>Approve Attraction
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-4">
                                <p class="lead fw-600">Are you sure you want to approve this attraction?</p>
                                <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-3">
                                    <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                                    <div>
                                        <strong>What happens next:</strong>
                                        <p class="mb-0 small">The attraction will become visible to users immediately and will appear in search results and recommendations.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success d-flex align-items-center gap-2" onclick="confirmApproveAttraction_API()">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Approve</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reject Attraction Modal - Enhanced -->
                <div class="modal fade" id="rejectAttractionModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-danger bg-gradient text-white border-0">
                                <h5 class="modal-title fw-700">
                                    <i class="bi bi-x-circle me-2"></i>Reject Attraction
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-4">
                                <p class="lead fw-600">Are you sure you want to reject this attraction?</p>
                                <div class="mb-3">
                                    <label class="form-label fw-600">Reason for Rejection:</label>
                                    <textarea class="form-control form-control-enhanced" rows="3" id="pendingRejectReason" placeholder="Enter detailed reason for rejection..."></textarea>
                                </div>
                                <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start gap-3">
                                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                                    <div>
                                        <strong>Important:</strong>
                                        <p class="mb-0 small">The spot owner will be notified with the reason provided.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger d-flex align-items-center gap-2" onclick="confirmRejectAttraction_API()">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Reject</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Suspend Modal - Enhanced -->
            <div class="modal fade" id="suspendModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-warning bg-gradient border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-pause-circle me-2"></i>Suspend Attraction
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="lead fw-600">Are you sure you want to suspend this attraction?</p>
                            <div class="mb-3">
                                <label class="form-label fw-600">Reason for Suspension:</label>
                                <textarea class="form-control form-control-enhanced" rows="3" id="suspendReason" placeholder="Enter reason for suspension..."></textarea>
                            </div>
                            <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start gap-3">
                                <i class="bi bi-exclamation-octagon-fill flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>What happens:</strong>
                                    <p class="mb-0 small">This attraction will be hidden from users until reactivated. All associated bookings will be affected.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning d-flex align-items-center gap-2" onclick="confirmSuspend_API()">
                                <i class="bi bi-pause-circle"></i>
                                <span>Suspend</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Modal - Enhanced -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-danger bg-gradient text-white border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-trash me-2"></i>Delete Attraction
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="lead fw-600 text-danger">⚠️ Warning: This action cannot be undone!</p>
                            <p class="mb-3">Are you sure you want to <strong>permanently delete</strong> this attraction?</p>
                            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-start gap-3 mb-4">
                                <i class="bi bi-exclamation-octagon-fill flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>All data will be deleted:</strong>
                                    <p class="mb-0 small">Associated bookings, reviews, images, and all other related data will be permanently removed.</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-600">Type "DELETE" to confirm:</label>
                                <input type="text" class="form-control form-control-enhanced border-danger" id="deleteConfirmText" placeholder="DELETE">
                                <small class="text-muted">This is case-sensitive</small>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2" onclick="confirmDelete_API()">
                                <i class="bi bi-trash"></i>
                                <span>Delete Permanently</span>
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
    <script src="<?= base_url('assets/js/admin-ui.js') ?>"></script>
</html>