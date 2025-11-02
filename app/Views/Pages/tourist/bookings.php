<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/bookings.css")?>">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-compass"></i>
                    <span>Tuklas Nasugbu</span>
                </div>
                <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="/tourist/dashboard" class="nav-link">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/exploreSpots" class="nav-link">
                            <i class="bi bi-search"></i>
                            <span>Explore</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/itinerary" class="nav-link">
                            <i class="bi bi-calendar-check"></i>
                            <span>My Itinerary</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/myBookings" class="nav-link active">
                            <i class="bi bi-ticket-perforated"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                   
                  
                    <li class="nav-item">
                        <a href="/tourist/visits" class="nav-link">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Visited Places</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/reviews" class="nav-link">
                            <i class="bi bi-star"></i>
                            <span>My Reviews</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/reviews" class="nav-link">
                            <i class="bi bi-star"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">My Bookings</h1>
                </div>
                <div class="user-actions">
                    <button class="notification-btn">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-profile">
                        <div class="user-avatar">JD</div>
                    </div>
                </div>
            </div>

            <div class="bookings-container">
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filterBookings('all')">All Bookings</button>
                    <button class="filter-tab" onclick="filterBookings('confirmed')">Confirmed</button>
                    <button class="filter-tab" onclick="filterBookings('pending')">Pending</button>
                    <button class="filter-tab" onclick="filterBookings('completed')">Completed</button>
                    <button class="filter-tab" onclick="filterBookings('cancelled')">Cancelled</button>
                </div>

                <!-- Booking Cards -->
                <div id="bookingsContainer">
                    <!-- Booking 1 - Confirmed -->
                    <div class="booking-card" data-status="confirmed">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">Canyon Cove Beach Resort</h3>
                                <span class="booking-type accommodation">
                                    <i class="bi bi-house-door"></i> Accommodation
                                </span>
                            </div>
                            <span class="booking-status confirmed">Confirmed</span>
                        </div>
                        <div class="booking-details">
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Check-in</h4>
                                    <p>Dec 15, 2024</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Check-out</h4>
                                    <p>Dec 18, 2024</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Guests</h4>
                                    <p>2 Adults</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Total Cost</h4>
                                    <p>₱6,000</p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <button class="btn-booking primary" onclick="viewBookingDetails()">
                                <i class="bi bi-eye"></i> View Details
                            </button>
                            <button class="btn-booking secondary" onclick="downloadVoucher()">
                                <i class="bi bi-download"></i> Download Voucher
                            </button>
                            <button class="btn-booking danger" onclick="cancelBooking()">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Booking 2 - Confirmed -->
                    <div class="booking-card" data-status="confirmed">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">Fortune Island Tour</h3>
                                <span class="booking-type tour">
                                    <i class="bi bi-geo-alt"></i> Island Tour
                                </span>
                            </div>
                            <span class="booking-status confirmed">Confirmed</span>
                        </div>
                        <div class="booking-details">
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Tour Date</h4>
                                    <p>Dec 16, 2024</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Departure Time</h4>
                                    <p>6:00 AM</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Pax</h4>
                                    <p>2 Persons</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Total Cost</h4>
                                    <p>₱2,000</p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <button class="btn-booking primary" onclick="viewBookingDetails()">
                                <i class="bi bi-eye"></i> View Details
                            </button>
                            <button class="btn-booking secondary" onclick="downloadVoucher()">
                                <i class="bi bi-download"></i> Download Voucher
                            </button>
                            <button class="btn-booking danger" onclick="cancelBooking()">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Booking 3 - Pending -->
                    <div class="booking-card" data-status="pending">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">Van Transfer to Nasugbu</h3>
                                <span class="booking-type transport">
                                    <i class="bi bi-car-front"></i> Transportation
                                </span>
                            </div>
                            <span class="booking-status pending">Pending Payment</span>
                        </div>
                        <div class="booking-details">
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Travel Date</h4>
                                    <p>Dec 15, 2024</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Pick-up Time</h4>
                                    <p>8:00 AM</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Route</h4>
                                    <p>Manila → Nasugbu</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Total Cost</h4>
                                    <p>₱3,500</p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <button class="btn-booking primary" onclick="completePayment()">
                                <i class="bi bi-credit-card"></i> Complete Payment
                            </button>
                            <button class="btn-booking danger" onclick="cancelBooking()">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Booking 4 - Completed -->
                    <div class="booking-card" data-status="completed">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">Mount Batulao Hiking Tour</h3>
                                <span class="booking-type tour">
                                    <i class="bi bi-mountain"></i> Adventure Tour
                                </span>
                            </div>
                            <span class="booking-status completed">Completed</span>
                        </div>
                        <div class="booking-details">
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Tour Date</h4>
                                    <p>Nov 20, 2024</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Start Time</h4>
                                    <p>5:00 AM</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Pax</h4>
                                    <p>2 Persons</p>
                                </div>
                            </div>
                            <div class="booking-detail-item">
                                <div class="booking-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="booking-detail-content">
                                    <h4>Total Cost</h4>
                                    <p>₱1,500</p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <button class="btn-booking primary" onclick="writeReview()">
                                <i class="bi bi-star"></i> Write Review
                            </button>
                            <button class="btn-booking secondary" onclick="bookAgain()">
                                <i class="bi bi-arrow-repeat"></i> Book Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        function filterBookings(status) {
            const tabs = document.querySelectorAll('.filter-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const bookings = document.querySelectorAll('.booking-card');
            bookings.forEach(booking => {
                if (status === 'all') {
                    booking.style.display = 'block';
                } else {
                    if (booking.dataset.status === status) {
                        booking.style.display = 'block';
                    } else {
                        booking.style.display = 'none';
                    }
                }
            });
        }

        function viewBookingDetails() {
            alert('View booking details - Backend integration needed');
        }

        function downloadVoucher() {
            alert('Download voucher - Backend integration needed');
        }

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel this booking?')) {
                alert('Cancel booking - Backend integration needed');
            }
        }

        function completePayment() {
            alert('Complete payment - Backend integration needed');
        }

        function writeReview() {
            window.location.href = 'dashboard-reviews.html';
        }

        function bookAgain() {
            alert('Book again - Backend integration needed');
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = event.target.closest('.sidebar-toggle');
            
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>
