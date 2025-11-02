<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/explore.css")?>">
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
            <div class="page-header">
                <h2><i class="bi bi-compass"></i> Explore Nasugbu</h2>
                <p>Discover amazing tourist spots and hidden gems</p>
            </div>
            
            <!-- Search and Filter -->
            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search for tourist spots...">
                    <i class="bi bi-search"></i>
                </div>
                
                <div class="filter-tags">
                    <button class="filter-tag active" data-filter="all">All</button>
                    <button class="filter-tag" data-filter="beach">Beach</button>
                    <button class="filter-tag" data-filter="mountain">Mountain</button>
                    <button class="filter-tag" data-filter="island">Island</button>
                    <button class="filter-tag" data-filter="landmark">Landmark</button>
                    <button class="filter-tag" data-filter="resort">Resort</button>
                </div>
            </div>
            
            <!-- Spots Grid -->
            <div class="spots-grid" id="spotsGrid">
                <!-- Fortune Island -->
                <div class="spot-card" data-category="island">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Fortune Island</h3>
                        <p class="spot-description">Explore Greek-inspired ruins surrounded by crystal-clear waters and pristine beaches.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-geo-alt"></i> Island</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.8</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Fortune Island')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Fortune Island')">Add to Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Mount Batulao -->
                <div class="spot-card" data-category="mountain">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Mount Batulao</h3>
                        <p class="spot-description">Challenge yourself with stunning mountain trails and breathtaking panoramic views.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-mountain"></i> Mountain</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.7</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Mount Batulao')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Mount Batulao')">Add to Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Caleruega Church -->
                <div class="spot-card" data-category="landmark">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1519290916420-e67e00a83cde?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Caleruega Church</h3>
                        <p class="spot-description">Find peace in this beautiful hilltop chapel with stunning garden views.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-building"></i> Landmark</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.9</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Caleruega Church')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Caleruega Church')">Add to Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Canyon Cove -->
                <div class="spot-card" data-category="beach resort">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Canyon Cove</h3>
                        <p class="spot-description">Relax at this exclusive beach resort with spectacular cove scenery.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-water"></i> Beach Resort</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.6</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Canyon Cove')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Canyon Cove')">Add to Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Munting Buhangin -->
                <div class="spot-card" data-category="beach">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Munting Buhangin Beach Camp</h3>
                        <p class="spot-description">Perfect beach camping spot with golden sands and clear blue waters.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-water"></i> Beach</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.5</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Munting Buhangin')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Munting Buhangin')">Add to Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Pico de Loro -->
                <div class="spot-card" data-category="mountain">
                    <div class="spot-image" style="background-image: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600')">
                        <button class="favorite-btn" onclick="toggleFavorite(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="spot-content">
                        <h3 class="spot-title">Pico de Loro</h3>
                        <p class="spot-description">Trek to the parrot's beak peak for an unforgettable mountain adventure.</p>
                        <div class="spot-meta">
                            <span class="spot-category"><i class="bi bi-mountain"></i> Mountain</span>
                            <div class="spot-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>4.8</span>
                            </div>
                        </div>
                        <div class="spot-actions">
                            <button class="btn-view" onclick="viewDetails('Pico de Loro')">View Details</button>
                            <button class="btn-add" onclick="addToItinerary('Pico de Loro')">Add to Plan</button>
                        </div>
                    </div>
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
        
        function toggleFavorite(button) {
            button.classList.toggle('active');
            const spotName = button.closest('.spot-card').querySelector('.spot-title').textContent;
            const isFavorite = button.classList.contains('active');
            
            if (isFavorite) {
                alert(spotName + ' added to favorites!');
            } else {
                alert(spotName + ' removed from favorites.');
            }
        }
        
        function viewDetails(spotName) {
            alert('Viewing details for ' + spotName + '\n\nThis would open a modal or navigate to a detail page with:\n- Full description\n- Photo gallery\n- Reviews\n- Location map\n- Pricing\n- Contact info');
        }
        
        function addToItinerary(spotName) {
            alert(spotName + ' added to your itinerary planning list!\n\nYou can view and organize it in the Itinerary section.');
        }
        
        // Filter functionality
        document.querySelectorAll('.filter-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const spots = document.querySelectorAll('.spot-card');
                
                spots.forEach(spot => {
                    if (filter === 'all') {
                        spot.style.display = 'block';
                    } else {
                        const category = spot.getAttribute('data-category');
                        if (category.includes(filter)) {
                            spot.style.display = 'block';
                        } else {
                            spot.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const spots = document.querySelectorAll('.spot-card');
             
            spots.forEach(spot => {
                const title = spot.querySelector('.spot-title').textContent.toLowerCase();
                const description = spot.querySelector('.spot-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    spot.style.display = 'block';
                } else {
                    spot.style.display = 'none';
                }
            });
        });
        
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
