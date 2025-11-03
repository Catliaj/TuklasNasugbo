<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/dashboard.css")?>">

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
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="welcome-section">
                    <h2>Welcome back, Traveler!</h2>
                    <p>Ready for your next adventure in Nasugbu?</p>
                </div>
                <div class="user-actions">
                    <button class="btn btn-outline-primary" onclick="window.location.href='dashboard-notifications.html'">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="user-avatar">JD</div>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">5</div>
                            <div class="stat-label">Saved Itineraries</div>
                        </div>
                        <div class="stat-icon blue">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">12</div>
                            <div class="stat-label">Places Visited</div>
                        </div>
                        <div class="stat-icon green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">8</div>
                            <div class="stat-label">Favorite Spots</div>
                        </div>
                        <div class="stat-icon orange">
                            <i class="bi bi-heart"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">3</div>
                            <div class="stat-label">Upcoming Bookings</div>
                        </div>
                        <div class="stat-icon purple">
                            <i class="bi bi-bookmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="actions-grid">
                    <a href="dashboard-itinerary.html" class="action-btn">
                        <i class="bi bi-plus-circle"></i>
                        <span>Create Itinerary</span>
                    </a>
                    <a href="dashboard-explore.html" class="action-btn">
                        <i class="bi bi-search"></i>
                        <span>Explore Spots</span>
                    </a>
                    <a href="dashboard-bookings.html" class="action-btn">
                        <i class="bi bi-calendar-plus"></i>
                        <span>Make Booking</span>
                    </a>
                    <a href="dashboard-budget.html" class="action-btn">
                        <i class="bi bi-calculator"></i>
                        <span>Track Budget</span>
                    </a>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="recent-activity">
                <h3>Recent Activity</h3>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="activity-content">
                        <h6>New Itinerary Created</h6>
                        <p>3-Day Nasugbu Beach Adventure</p>
                    </div>
                    <div class="activity-time">2 hours ago</div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Added to Favorites</h6>
                        <p>Fortune Island</p>
                    </div>
                    <div class="activity-time">5 hours ago</div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-bookmark"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Booking Confirmed</h6>
                        <p>Canyon Cove Beach Resort</p>
                    </div>
                    <div class="activity-time">1 day ago</div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Review Posted</h6>
                        <p>Mount Batulao - 5 stars</p>
                    </div>
                    <div class="activity-time">2 days ago</div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
        
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>
