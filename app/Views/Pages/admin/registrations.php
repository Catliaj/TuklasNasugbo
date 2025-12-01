<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?> - Registrations</title>

    <!-- ADD THESE TWO LINES FOR CSRF PROTECTION -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Chart.js (Optional for this page, but good to keep for consistency) -->
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

    <!-- DEFINE THE BASE_URL FOR JAVASCRIPT -->
    <script>
        const BASE_URL = '<?= base_url() ?>';
    </script>

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
            <a href="/admin/registrations" class="nav-item active" aria-current="page">
                <i class="bi bi-person-plus"></i>
                <span>Registrations</span>
                <span class="badge-pending-registrations badge bg-danger text-white ms-2" style="display:none;font-size:0.7rem;padding:2px 6px;border-radius:12px"></span>
            </a>
            <a href="/admin/attractions" class="nav-item" aria-label="Attractions">
                <i class="bi bi-geo-alt"></i>
                <span>Attractions</span>
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
                <!-- search moved to page header for registrations (see below) -->
                
                <div class="dropdown">
                    <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationButtonRegistrations" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="bi bi-bell-fill text-white"></i>
                        <span class="notification-badge badge rounded-pill bg-danger"><?= (isset($unreadNotifications) && $unreadNotifications > 0) ? esc($unreadNotifications) : '' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuRegistrations" style="min-width:320px">
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
            <!-- Registrations Page Content -->
            <div class="page-header mb-4">
                <div>
                    <h1 class="page-title fw-700">📝 Registration Requests</h1>
                    <p class="text-muted mb-0">Review and approve spot owner registration requests</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <!-- UPDATE ONCLICK TO CALL THE _API FUNCTIONS -->
                    <button class="btn btn-outline-primary d-flex align-items-center gap-2" onclick="filterRegistrations_API('all')">
                        <i class="bi bi-list-ul"></i>
                        <span>All</span>
                    </button>
                    <button class="btn btn-outline-warning d-flex align-items-center gap-2" onclick="filterRegistrations_API('pending')">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending</span>
                    </button>
                    <button class="btn btn-outline-success d-flex align-items-center gap-2" onclick="filterRegistrations_API('approved')">
                        <i class="bi bi-check-circle"></i>
                        <span>Approved</span>
                    </button>
                     <button class="btn btn-outline-danger d-flex align-items-center gap-2" onclick="filterRegistrations_API('rejected')">
                        <i class="bi bi-x-circle"></i>
                        <span>Rejected</span>
                    </button>
                </div>
            </div>

            <div class="card search-filter-card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input id="searchRegistrations" class="form-control form-control-enhanced border-start-0" placeholder="Search by business name, owner, or email...">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-enhanced">
                            <thead>
                                <tr>
                                    <th style="width: 5%;"><i class="bi bi-hash me-2"></i>#</th>
                                    <th style="width: 20%;"><i class="bi bi-shop me-2"></i>Business Name</th>
                                    <th style="width: 15%;"><i class="bi bi-person me-2"></i>Owner Name</th>
                                    <th style="width: 15%;"><i class="bi bi-envelope me-2"></i>Email</th>
                                    <th style="width: 12%;"><i class="bi bi-telephone me-2"></i>Phone</th>
                                    <th style="width: 15%;"><i class="bi bi-geo-alt me-2"></i>Location</th>
                                    <th style="width: 10%;"><i class="bi bi-calendar me-2"></i>Submitted</th>
                                    <th style="width: 8%;" class="text-center"><i class="bi bi-tag me-2"></i>Status</th>
                                    <th style="width: 10%;" class="text-center"><i class="bi bi-gear me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="registrationsTable">
                                <!-- Table rows will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- View Registration Modal - Enhanced -->
            <div class="modal fade" id="viewRegistrationModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header bg-gradient border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-person-check me-2"></i>Registration Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="viewRegistrationContent">
                            <!-- Content will be loaded by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Modal - Enhanced -->
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header bg-success bg-gradient text-white border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-check-circle me-2"></i>Approve Registration
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="lead fw-600">Are you sure you want to approve this registration?</p>
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-start gap-3">
                                <i class="bi bi-check-circle-fill flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>What happens next:</strong>
                                    <p class="mb-0 small">The spot owner will receive an email notification and can immediately start using the system to manage attractions.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <!-- UPDATE ONCLICK TO CALL THE _API FUNCTION -->
                            <button type="button" class="btn btn-success d-flex align-items-center gap-2" onclick="confirmApprove_API()">
                                <i class="bi bi-check-circle"></i>
                                <span>Approve</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal - Enhanced -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header bg-danger bg-gradient text-white border-0">
                            <h5 class="modal-title fw-700">
                                <i class="bi bi-x-circle me-2"></i>Reject Registration
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="lead fw-600">Are you sure you want to reject this registration?</p>
                            <div class="mb-3">
                                <label class="form-label fw-600">Reason for Rejection:</label>
                                <textarea class="form-control form-control-enhanced" rows="3" id="rejectReason" placeholder="Enter detailed reason for rejection..."></textarea>
                            </div>
                            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-start gap-3">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>Important:</strong>
                                    <p class="mb-0 small">The spot owner will be notified via email with the reason provided.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <!-- UPDATE ONCLICK TO CALL THE _API FUNCTION -->
                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2" onclick="confirmReject_API()">
                                <i class="bi bi-x-circle"></i>
                                <span>Reject</span>
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

    <!-- UPDATE THIS SCRIPT BLOCK TO CALL THE MAIN API FUNCTION -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // The initSidebarToggle() is already called in the main script,
        // so we only need to call the function to load this page's data.
        loadRegistrations_API();
    });
    </script>
</body>
    <script src="<?= base_url('assets/js/admin-ui.js') ?>"></script>
</html>
