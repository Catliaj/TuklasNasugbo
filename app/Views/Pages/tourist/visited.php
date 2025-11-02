<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visited Places - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/visited.css")?>">
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
                        <a href="/tourist/myBookings" class="nav-link">
                            <i class="bi bi-ticket-perforated"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/budget" class="nav-link">
                            <i class="bi bi-wallet2"></i>
                            <span>Budget Tracker</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/favorites" class="nav-link">
                            <i class="bi bi-heart"></i>
                            <span>Favorites</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/visits" class="nav-link active">
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
                        <a href="/under-development" class="nav-link">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/profile" class="nav-link">
                            <i class="bi bi-person-circle"></i>
                            <span>Profile</span>
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
                    <h1 class="page-title">Visited Places</h1>
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

            <div class="visited-container">
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Total Places Visited</h3>
                            <p>12</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Trips Completed</h3>
                            <p>5</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="bi bi-camera"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Photos Shared</h3>
                            <p>48</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="timeline-section">
                    <div class="section-header">
                        <h3 class="section-title">Travel History</h3>
                        <div class="view-toggle">
                            <button class="toggle-btn active" onclick="switchView('timeline')">
                                <i class="bi bi-list-ul"></i> Timeline
                            </button>
                            <button class="toggle-btn" onclick="switchView('grid')">
                                <i class="bi bi-grid"></i> Grid
                            </button>
                        </div>
                    </div>

                    <!-- Timeline View -->
                    <div class="timeline active" id="timelineView">
                        <!-- Visit 1 -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div>
                                        <h4 class="timeline-title">Mount Batulao Hike</h4>
                                        <p class="timeline-date">
                                            <i class="bi bi-calendar"></i> November 20, 2024
                                        </p>
                                    </div>
                                    <span class="check-in-badge">
                                        <i class="bi bi-check-circle"></i> Checked In
                                    </span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Nasugbu, Batangas</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>With 3 friends</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Rated 5/5</span>
                                    </div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">
                                    Amazing sunrise hike! The rolling hills were breathtaking. Definitely coming back!
                                </p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+5</div>
                                </div>
                            </div>
                        </div>

                        <!-- Visit 2 -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div>
                                        <h4 class="timeline-title">Caleruega Church Visit</h4>
                                        <p class="timeline-date">
                                            <i class="bi bi-calendar"></i> October 15, 2024
                                        </p>
                                    </div>
                                    <span class="check-in-badge">
                                        <i class="bi bi-check-circle"></i> Checked In
                                    </span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Caleruega, Nasugbu</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>Solo trip</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Rated 5/5</span>
                                    </div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">
                                    Perfect place for meditation and reflection. The gardens are beautiful!
                                </p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1534798625261-00ffdb0abe77?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1534798625261-00ffdb0abe77?w=300" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+3</div>
                                </div>
                            </div>
                        </div>

                        <!-- Visit 3 -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div>
                                        <h4 class="timeline-title">Fortune Island Adventure</h4>
                                        <p class="timeline-date">
                                            <i class="bi bi-calendar"></i> September 8, 2024
                                        </p>
                                    </div>
                                    <span class="check-in-badge">
                                        <i class="bi bi-check-circle"></i> Checked In
                                    </span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Fortune Island</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>With partner</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Rated 5/5</span>
                                    </div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">
                                    The Greek ruins are so photogenic! Crystal clear waters perfect for swimming.
                                </p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+7</div>
                                </div>
                            </div>
                        </div>

                        <!-- Visit 4 -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div>
                                        <h4 class="timeline-title">Canyon Cove Beach Resort</h4>
                                        <p class="timeline-date">
                                            <i class="bi bi-calendar"></i> August 22, 2024
                                        </p>
                                    </div>
                                    <span class="check-in-badge">
                                        <i class="bi bi-check-circle"></i> Checked In
                                    </span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Piloto Wawa, Nasugbu</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>Family trip</span>
                                    </div>
                                    <div class="timeline-meta-item">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Rated 4/5</span>
                                    </div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">
                                    Great resort for families! Kids loved the beach and pool areas.
                                </p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1729717949948-56b52db111dd?w=300" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1729717949948-56b52db111dd?w=300" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+4</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div class="grid-view" id="gridView">
                        <!-- Grid cards would go here - similar structure to timeline but in card format -->
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

        function switchView(view) {
            const buttons = document.querySelectorAll('.toggle-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const timelineView = document.getElementById('timelineView');
            const gridView = document.getElementById('gridView');

            if (view === 'timeline') {
                timelineView.classList.add('active');
                timelineView.classList.remove('hidden');
                gridView.classList.remove('active');
            } else {
                timelineView.classList.remove('active');
                timelineView.classList.add('hidden');
                gridView.classList.add('active');
            }
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
