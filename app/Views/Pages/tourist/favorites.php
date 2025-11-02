<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/favorites.css")?>">
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
                        <a href="/tourist/favorites" class="nav-link active">
                            <i class="bi bi-heart"></i>
                            <span>Favorites</span>
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
                    <h1 class="page-title">My Favorites</h1>
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

            <div class="favorites-container">
                <!-- Filter Tabs -->
                <div class="favorites-header">
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterFavorites('all')">All Places</button>
                        <button class="filter-tab" onclick="filterFavorites('beaches')">Beaches</button>
                        <button class="filter-tab" onclick="filterFavorites('mountains')">Mountains</button>
                        <button class="filter-tab" onclick="filterFavorites('resorts')">Resorts</button>
                        <button class="filter-tab" onclick="filterFavorites('attractions')">Attractions</button>
                    </div>
                </div>

                <!-- Favorites Grid -->
                <div class="favorites-grid" id="favoritesGrid">
                    <!-- Favorite 1 -->
                    <div class="favorite-card" data-category="beaches">
                        <div style="position: relative;">
                            <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpc2xhbmQlMjBwYXJhZGlzZSUyMGJlYWNofGVufDF8fHx8MTc2MTgxNjE5MXww&ixlib=rb-4.1.0&q=80&w=1080" alt="Fortune Island" class="favorite-image">
                            <span class="favorite-badge">Beach</span>
                            <button class="favorite-heart" onclick="removeFavorite(this)">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        <div class="favorite-content">
                            <h3 class="favorite-title">Fortune Island</h3>
                            <div class="favorite-location">
                                <i class="bi bi-geo-alt"></i>
                                <span>Nasugbu, Batangas</span>
                            </div>
                            <div class="favorite-meta">
                                <div class="favorite-meta-item">
                                    <i class="bi bi-star-fill"></i>
                                    <span>4.8</span>
                                </div>
                                <div class="favorite-meta-item">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>₱1,000</span>
                                </div>
                            </div>
                            <p class="favorite-description">
                                Famous for its Greek-inspired ruins and crystal-clear waters. Perfect for island hopping and photography.
                            </p>
                            <div class="favorite-actions">
                                <button class="btn-favorite primary" onclick="viewDetails()">View Details</button>
                                <button class="btn-favorite" onclick="addToItinerary()">Add to Trip</button>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite 2 -->
                    <div class="favorite-card" data-category="mountains">
                        <div style="position: relative;">
                            <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb3VudGFpbiUyMGhpa2luZyUyMHRyYWlsfGVufDF8fHx8MTc2MTcxMDg5OHww&ixlib=rb-4.1.0&q=80&w=1080" alt="Mount Batulao" class="favorite-image">
                            <span class="favorite-badge">Mountain</span>
                            <button class="favorite-heart" onclick="removeFavorite(this)">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        <div class="favorite-content">
                            <h3 class="favorite-title">Mount Batulao</h3>
                            <div class="favorite-location">
                                <i class="bi bi-geo-alt"></i>
                                <span>Nasugbu, Batangas</span>
                            </div>
                            <div class="favorite-meta">
                                <div class="favorite-meta-item">
                                    <i class="bi bi-star-fill"></i>
                                    <span>4.7</span>
                                </div>
                                <div class="favorite-meta-item">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>₱500</span>
                                </div>
                            </div>
                            <p class="favorite-description">
                                Popular hiking destination with stunning rolling hills and panoramic views of Batangas.
                            </p>
                            <div class="favorite-actions">
                                <button class="btn-favorite primary" onclick="viewDetails()">View Details</button>
                                <button class="btn-favorite" onclick="addToItinerary()">Add to Trip</button>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite 3 -->
                    <div class="favorite-card" data-category="attractions">
                        <div style="position: relative;">
                            <img src="https://images.unsplash.com/photo-1534798625261-00ffdb0abe77?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaHVyY2glMjBhcmNoaXRlY3R1cmV8ZW58MXx8fHwxNzYxODE2MTkyfDA&ixlib=rb-4.1.0&q=80&w=1080" alt="Caleruega Church" class="favorite-image">
                            <span class="favorite-badge">Attraction</span>
                            <button class="favorite-heart" onclick="removeFavorite(this)">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        <div class="favorite-content">
                            <h3 class="favorite-title">Caleruega Church</h3>
                            <div class="favorite-location">
                                <i class="bi bi-geo-alt"></i>
                                <span>Nasugbu, Batangas</span>
                            </div>
                            <div class="favorite-meta">
                                <div class="favorite-meta-item">
                                    <i class="bi bi-star-fill"></i>
                                    <span>4.9</span>
                                </div>
                                <div class="favorite-meta-item">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>₱100</span>
                                </div>
                            </div>
                            <p class="favorite-description">
                                Tranquil hilltop retreat with beautiful chapel, gardens, and breathtaking views of Taal Lake.
                            </p>
                            <div class="favorite-actions">
                                <button class="btn-favorite primary" onclick="viewDetails()">View Details</button>
                                <button class="btn-favorite" onclick="addToItinerary()">Add to Trip</button>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite 4 -->
                    <div class="favorite-card" data-category="resorts">
                        <div style="position: relative;">
                            <img src="https://images.unsplash.com/photo-1729717949948-56b52db111dd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiZWFjaCUyMHJlc29ydCUyMHBvb2x8ZW58MXx8fHwxNzYxODE2MTkyfDA&ixlib=rb-4.1.0&q=80&w=1080" alt="Canyon Cove" class="favorite-image">
                            <span class="favorite-badge">Resort</span>
                            <button class="favorite-heart" onclick="removeFavorite(this)">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        <div class="favorite-content">
                            <h3 class="favorite-title">Canyon Cove Beach Resort</h3>
                            <div class="favorite-location">
                                <i class="bi bi-geo-alt"></i>
                                <span>Piloto Wawa, Nasugbu</span>
                            </div>
                            <div class="favorite-meta">
                                <div class="favorite-meta-item">
                                    <i class="bi bi-star-fill"></i>
                                    <span>4.6</span>
                                </div>
                                <div class="favorite-meta-item">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>₱2,000</span>
                                </div>
                            </div>
                            <p class="favorite-description">
                                Exclusive beach club with pristine white sand, pools, and excellent amenities.
                            </p>
                            <div class="favorite-actions">
                                <button class="btn-favorite primary" onclick="viewDetails()">View Details</button>
                                <button class="btn-favorite" onclick="addToItinerary()">Add to Trip</button>
                            </div>
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

        function filterFavorites(category) {
            const tabs = document.querySelectorAll('.filter-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const cards = document.querySelectorAll('.favorite-card');
            cards.forEach(card => {
                if (category === 'all') {
                    card.style.display = 'block';
                } else {
                    if (card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }

        function removeFavorite(button) {
            if (confirm('Remove from favorites?')) {
                const card = button.closest('.favorite-card');
                card.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    card.remove();
                    
                    // Check if grid is empty
                    const grid = document.getElementById('favoritesGrid');
                    if (grid.children.length === 0) {
                        grid.innerHTML = `
                            <div class="empty-state" style="grid-column: 1/-1;">
                                <i class="bi bi-heart"></i>
                                <h3>No Favorites Yet</h3>
                                <p>Start exploring and save your favorite places!</p>
                                <button class="btn-explore" onclick="location.href='dashboard-explore.html'">
                                    Explore Places
                                </button>
                            </div>
                        `;
                    }
                }, 300);
            }
        }

        function viewDetails() {
            alert('View place details - Backend integration needed');
        }

        function addToItinerary() {
            alert('Added to itinerary! - Backend integration needed');
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
