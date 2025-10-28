<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Spot Owner Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
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
                        <a href="/spotowner/dashboard" class="sidebar-link " data-page="home">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/bookings" class="sidebar-link active" data-page="bookings">
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
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/settings" class="sidebar-link" data-page="profile">
                            <i class="bi bi-person"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="#" class="sidebar-link text-danger" id="logoutBtn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">
            <!-- Mobile Header -->
            <div class="mobile-header d-lg-none">
                <button class="btn btn-link" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <div class="mobile-logo">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="mobile-title mb-0">Tourist Spot</h3>
                        <p class="mobile-subtitle mb-0">Owner Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <!-- Content will be loaded here dynamically -->
                <div class="container-fluid">
                    <div class="mb-4">
                        <h2>Booking Management</h2>
                        <p class="text-muted-custom">Manage and track all bookings for your tourist spot</p>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Bookings</div>
                                        <div class="stat-value">6</div>
                                        <div class="stat-description">4 confirmed</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Visitors</div>
                                        <div class="stat-value">22</div>
                                        <div class="stat-description">Expected visitors</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Revenue</div>
                                        <div class="stat-value">₱2,750</div>
                                        <div class="stat-description">From active bookings</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Table -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h3 class="custom-card-title">Recent Bookings</h3>
                            <p class="custom-card-description">List of all bookings for your tourist spot</p>
                        </div>
                        <div class="custom-card-body">
                            <div class="table-responsive">
                                <table class="table table-custom">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Visitors</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookingsTableBody">
                                        <tr>
                                            <td>BK001</td>
                                            <td>Maria Santos</td>
                                            <td>10/10/2025</td>
                                            <td>4</td>
                                            <td>₱500</td>
                                            <td><span class="badge badge-confirmed">confirmed</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK001">View</button></td>
                                        </tr>
                                        <tr>
                                            <td>BK002</td>
                                            <td>Juan Dela Cruz</td>
                                            <td>10/12/2025</td>
                                            <td>2</td>
                                            <td>₱250</td>
                                            <td><span class="badge badge-confirmed">confirmed</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK002">View</button></td>
                                        </tr>
                                        <tr>
                                            <td>BK003</td>
                                            <td>Ana Reyes</td>
                                            <td>10/15/2025</td>
                                            <td>6</td>
                                            <td>₱750</td>
                                            <td><span class="badge badge-pending">pending</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK003">View</button></td>
                                        </tr>
                                        <tr>
                                            <td>BK004</td>
                                            <td>Carlos Garcia</td>
                                            <td>10/18/2025</td>
                                            <td>3</td>
                                            <td>₱375</td>
                                            <td><span class="badge badge-confirmed">confirmed</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK004">View</button></td>
                                        </tr>
                                        <tr>
                                            <td>BK005</td>
                                            <td>Sofia Martinez</td>
                                            <td>10/20/2025</td>
                                            <td>5</td>
                                            <td>₱625</td>
                                            <td><span class="badge badge-pending">pending</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK005">View</button></td>
                                        </tr>
                                        <tr>
                                            <td>BK006</td>
                                            <td>Diego Lopez</td>
                                            <td>10/09/2025</td>
                                            <td>2</td>
                                            <td>₱250</td>
                                            <td><span class="badge badge-cancelled">cancelled</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK006">View</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Booking Modal -->
                <div class="modal fade" id="viewBookingModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Booking Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="bookingModalBody">
                                <!-- Content loaded dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="<?= base_url("assets/js/spotownerJS/shared-data.js")?>"></script>

    <!-- ✅ Fixed path -->
     <script src="<?= base_url("assets/js/spotownerJS/bookings.js")?>"></script>
   
</body>

</html>