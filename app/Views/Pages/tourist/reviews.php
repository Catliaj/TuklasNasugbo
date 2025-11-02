<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/reviews.css")?>">
    
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
            <div class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">My Reviews</h1>
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

            <div class="reviews-container">
                <!-- Review Stats -->
                <div class="review-stats">
                    <div class="stat-item">
                        <div class="stat-value">8</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="stat-item">
                        <div class="star-rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <div class="stat-value">4.7</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">142</div>
                        <div class="stat-label">Helpful Votes</div>
                    </div>
                </div>

                <button class="btn-write-review" onclick="writeReview()">
                    <i class="bi bi-plus-circle"></i> Write New Review
                </button>

                <!-- Review Cards -->
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-place">
                            <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Mount Batulao" class="place-image">
                            <div class="place-info">
                                <h3>Mount Batulao</h3>
                                <div class="place-location">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Nasugbu, Batangas</span>
                                </div>
                            </div>
                        </div>
                        <div class="review-actions">
                            <button class="btn-icon" onclick="editReview()">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-icon delete" onclick="deleteReview()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="review-text">
                        Absolutely amazing experience! The sunrise hike was worth the early wake-up call. The rolling hills provide stunning panoramic views of Batangas. Trail is well-maintained and suitable for beginners. Highly recommended for nature lovers!
                    </p>
                    <div class="review-photos">
                        <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Review photo" class="review-photo">
                        <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Review photo" class="review-photo">
                        <img src="https://images.unsplash.com/photo-1623622863859-2931a6c3bc80?w=300" alt="Review photo" class="review-photo">
                    </div>
                    <div class="review-meta">
                        <div class="review-date">
                            <i class="bi bi-calendar"></i>
                            <span>November 20, 2024</span>
                        </div>
                        <div class="review-helpful">
                            <span class="helpful-count">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <span>42 found this helpful</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-place">
                            <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Fortune Island" class="place-image">
                            <div class="place-info">
                                <h3>Fortune Island</h3>
                                <div class="place-location">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Nasugbu, Batangas</span>
                                </div>
                            </div>
                        </div>
                        <div class="review-actions">
                            <button class="btn-icon" onclick="editReview()">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-icon delete" onclick="deleteReview()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="review-text">
                        The Greek-inspired ruins are breathtaking! Crystal clear waters perfect for swimming and snorkeling. Great spot for photography. The boat ride is about 30 minutes from the mainland. Bring your own food and water as there are no facilities on the island.
                    </p>
                    <div class="review-photos">
                        <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Review photo" class="review-photo">
                        <img src="https://images.unsplash.com/photo-1653730442000-c1514b290be3?w=300" alt="Review photo" class="review-photo">
                    </div>
                    <div class="review-meta">
                        <div class="review-date">
                            <i class="bi bi-calendar"></i>
                            <span>September 8, 2024</span>
                        </div>
                        <div class="review-helpful">
                            <span class="helpful-count">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <span>38 found this helpful</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-place">
                            <img src="https://images.unsplash.com/photo-1534798625261-00ffdb0abe77?w=300" alt="Caleruega Church" class="place-image">
                            <div class="place-info">
                                <h3>Caleruega Church</h3>
                                <div class="place-location">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Nasugbu, Batangas</span>
                                </div>
                            </div>
                        </div>
                        <div class="review-actions">
                            <button class="btn-icon" onclick="editReview()">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-icon delete" onclick="deleteReview()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="review-text">
                        Perfect place for reflection and meditation. The hilltop chapel offers stunning views of Taal Lake. Well-maintained gardens are beautiful and peaceful. Great for pre-nuptial shoots! Very clean and serene atmosphere.
                    </p>
                    <div class="review-meta">
                        <div class="review-date">
                            <i class="bi bi-calendar"></i>
                            <span>October 15, 2024</span>
                        </div>
                        <div class="review-helpful">
                            <span class="helpful-count">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <span>31 found this helpful</span>
                            </span>
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

        function writeReview() {
            alert('Write new review - Backend integration needed');
        }

        function editReview() {
            alert('Edit review - Backend integration needed');
        }

        function deleteReview() {
            if (confirm('Are you sure you want to delete this review?')) {
                alert('Delete review - Backend integration needed');
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
