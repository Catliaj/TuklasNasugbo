<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Flatpickr CSS for date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <?php
    // Get user session data for profile
    $session = session();
    $userFirstName = $session->get('FirstName') ?? '';
    $userLastName = $session->get('LastName') ?? '';
    $userEmail = $session->get('Email') ?? '';
    $userPhone = $session->get('Phone') ?? '';
    $userBirthdate = $session->get('Birthdate') ?? '';
    $userAddress = $session->get('Address') ?? '';
    $userInitials = strtoupper(substr($userFirstName, 0, 1) . substr($userLastName, 0, 1));
    ?>
    <style>
        /* Enhanced Reviews Header - matches explore */
        :root { --ocean-accent:#4ecbff; --ocean-accent-soft:#b5ecff; --ocean-text:#e6f8ff; }
        .page-header {background:#002e55;color:var(--ocean-text);height:210px;min-height:210px;padding:1.6rem 2.4rem 1.8rem;border-radius:28px;position:relative;overflow:hidden;box-shadow:0 12px 34px -10px rgba(0,56,108,.55);display:flex;flex-direction:column;justify-content:center;}            
        .page-header h2 {font-weight:700;display:flex;align-items:center;gap:.85rem;margin:0 0 .55rem;color:#e2e8f0;font-size:2.6rem;letter-spacing:.6px;line-height:1.1;position:relative;top:-6px;}
        .page-header h2 i {background:rgba(255,255,255,.12);padding:.8rem;border-radius:18px;font-size:2.2rem;animation:slow-spin 18s linear infinite;color:var(--ocean-text);position:relative;top:-4px;} 
        @keyframes slow-spin {from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
        .page-header p {font-size:1.05rem;letter-spacing:.5px;margin:0;color:var(--ocean-accent-soft);text-shadow:0 1px 2px rgba(0,0,0,.25);}
        /* Wave layers */
        .page-header:before {content:"";position:absolute;left:0;right:0;bottom:0;height:110px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,80 C150,120 300,40 450,70 C600,100 750,50 900,80 C1050,110 1200,60 1200,60 L1200,120 L0,120 Z" fill="%2300487a"/></svg>') repeat-x;background-size:1200px 110px;opacity:.55;filter:drop-shadow(0 4px 8px rgba(0,0,0,.3));}
        .page-header:after {content:"";position:absolute;left:0;right:0;bottom:0;height:90px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,60 C200,100 400,20 600,60 C800,100 1000,30 1200,70 L1200,120 L0,120 Z" fill="%23005fae"/></svg>') repeat-x;background-size:1200px 90px;opacity:.35;}
        .page-header-actions {position:absolute;top:1.1rem;right:1.3rem;display:flex;align-items:center;gap:1rem;z-index:5;}
        .page-header-actions .user-avatar {background:linear-gradient(135deg,#004b8d,#001d33);color:#e2e8f0;font-weight:600;width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 12px -3px rgba(0,0,0,.5);transition:.25s;border:2px solid rgba(255,255,255,.18);}
        .page-header-actions .user-avatar:hover {transform:translateY(-2px);background:linear-gradient(135deg,#005fae,#002e55);}
        @media (max-width: 768px){
            .page-header {padding:2rem 1.2rem 2.4rem;border-radius:22px;}
            .page-header-actions {position:static;justify-content:flex-start;margin-bottom:1rem;}
            .page-header h2{font-size:1.4rem;}
            .page-header p{font-size:.9rem;}
        }
        /* Modern Rating Stars */
        .rating-star {
            transition: all 0.2s ease;
            position: relative;
        }
        .rating-star i {
            color: #d1d5db;
            transition: all 0.2s ease;
            font-size: 2rem !important;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        .rating-star:hover i,
        .rating-star.active i {
            color: #fbbf24;
            transform: scale(1.2);
            filter: drop-shadow(0 4px 8px rgba(251,191,36,0.4));
        }
        .rating-star.filled i {
            color: #fbbf24;
        }
        #ratingStars:hover .rating-star i,
        #editRatingStars:hover .rating-star i {
            color: #e5e7eb;
        }
        #ratingStars .rating-star:hover i,
        #editRatingStars .rating-star:hover i,
        #ratingStars .rating-star:hover ~ .rating-star i,
        #editRatingStars .rating-star:hover ~ .rating-star i {
            color: #d1d5db;
        }
        #ratingStars .rating-star.hovered i,
        #editRatingStars .rating-star.hovered i {
            color: #fbbf24;
            transform: scale(1.15);
        }
        
        /* Critical Review Card Layout - Inline to prevent caching issues */
        .review-card {
            display: flex !important;
            flex-direction: row !important;
            gap: 1.5rem !important;
            align-items: flex-start !important;
            background: #fff !important;
            border-radius: 16px !important;
            padding: 1.25rem !important;
            border: 1px solid #e9ecef !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
            margin-bottom: 1.5rem !important;
        }
        
        .review-card .place-thumb {
            flex: 0 0 180px !important;
            min-width: 180px !important;
            max-width: 180px !important;
        }
        
        .review-card .place-image {
            width: 180px !important;
            height: 180px !important;
            object-fit: cover !important;
            border-radius: 12px !important;
            display: block !important;
            border: 2px solid #f0f0f0 !important;
        }
        
        .review-card .review-body {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
            min-width: 0 !important;
        }
        
        .review-card .review-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            margin-bottom: 0.3rem !important;
        }
        
        .review-card .place-info {
            flex: 1 !important;
        }
        
        .review-card .place-info h3 {
            margin: 0 0 0.3rem 0 !important;
            font-size: 1.25rem !important;
            line-height: 1.3 !important;
            color: #013A63 !important;
            font-weight: 700 !important;
        }
        
        .review-card .place-location {
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            font-size: 0.88rem !important;
            color: #6c757d !important;
            margin: 0 !important;
        }
        
        .review-card .review-rating {
            display: flex !important;
            gap: 0.25rem !important;
            margin: 0 0 0.5rem 0 !important;
            font-size: 1.05rem !important;
            color: #ffc107 !important;
        }
        
        .review-card .review-text {
            margin: 0 0 0.75rem 0 !important;
            line-height: 1.7 !important;
            font-size: 0.94rem !important;
            color: #2c3e50 !important;
        }
        
        .review-card .review-photos {
            display: flex !important;
            gap: 0.65rem !important;
            flex-wrap: wrap !important;
            margin: 0 0 0.75rem 0 !important;
        }
        
        .review-card .review-photo {
            width: 80px !important;
            height: 80px !important;
            border-radius: 8px !important;
            object-fit: cover !important;
            border: 2px solid #e9ecef !important;
        }
        
        .review-card .review-meta {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding-top: 0.75rem !important;
            border-top: 1px solid #e9ecef !important;
            margin-top: auto !important;
        }
        
        .review-card .review-date {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.84rem !important;
            color: #6c757d !important;
        }
        
        .review-card .review-actions {
            display: flex !important;
            gap: 0.5rem !important;
        }
        
        @media (max-width: 767px) {
            .review-card {
                flex-direction: column !important;
            }
            .review-card .place-thumb {
                width: 100% !important;
                max-width: 100% !important;
            }
            .review-card .place-image {
                width: 100% !important;
                height: 200px !important;
            }
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/reviews.css')?>?v=<?= time() ?>"> 
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="tourist-sidebar" id="sidebar">
            <div class="tourist-sidebar-header">
                <a href="/tourist/dashboard" class="tourist-sidebar-logo">
                    <i class="bi bi-compass"></i>
                    <div class="tourist-sidebar-logo-text">
                        <span class="tourist-sidebar-logo-main">Tuklas</span>
                        <span class="tourist-sidebar-logo-sub">Nasugbu</span>
                    </div>
                </a>
                <button class="tourist-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <nav class="tourist-sidebar-nav">
                <ul class="tourist-nav-menu">
                    <li class="tourist-nav-item">
                        <a href="/tourist/dashboard" class="tourist-nav-link">
                            <i class="bi bi-house-door"></i>
                            <span class="tourist-nav-link-text">Home</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/exploreSpots" class="tourist-nav-link">
                            <i class="bi bi-search"></i>
                            <span class="tourist-nav-link-text">Explore</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/itinerary" class="tourist-nav-link">
                            <i class="bi bi-calendar-check"></i>
                            <span class="tourist-nav-link-text">My Itinerary</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/myBookings" class="tourist-nav-link">
                            <i class="bi bi-ticket-perforated"></i>
                            <span class="tourist-nav-link-text">Bookings</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/visits" class="tourist-nav-link">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span class="tourist-nav-link-text">Visited Places</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/reviews" class="tourist-nav-link active">
                            <i class="bi bi-star"></i>
                            <span class="tourist-nav-link-text">My Reviews</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header (matches explore exactly) -->
                <div class="page-header">
                    <div class="page-header-actions">
                        <div style="position: relative;display:flex;align-items:center;gap:1rem;">
                            <div style="position:relative;">
                            <button class="notification-btn" onclick="toggleNotificationDropdown()">
                                <i class="bi bi-bell-fill"></i>
                                <span class="notification-badge" id="notifBadge" style="display:none">0</span>
                            </button>
                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h6>Notifications</h6>
                                    <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
                                </div>
                                <ul class="notification-list" id="notificationList"></ul>
                                <div class="notification-footer">
                                    <a href="#" onclick="viewAllNotifications(event)">View all notifications</a>
                                </div>
                            </div>
                            </div>
                            <div style="position:relative;">
                            <div class="user-avatar" onclick="toggleUserDropdown()"><?= esc($userInitials ?: 'JD') ?></div>
                            <div class="user-dropdown" id="userDropdown">
                                <div class="dropdown-header">
                                    <h6><?= esc($userFirstName . ' ' . $userLastName) ?></h6>
                                    <p><?= esc($userEmail) ?></p>
                                </div>
                                <ul class="dropdown-menu-custom">
                                    <li>
                                        <a href="#" onclick="openProfile(event); hideUserDropdown(event)" class="dropdown-item-custom">
                                            <i class="bi bi-person-circle"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/users/logout" class="dropdown-item-custom logout" onclick="handleLogout(event)">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                    </div>
                    <h2><i class="bi bi-star-fill"></i> My Reviews</h2>
                    <p>Share your experiences & help fellow travelers.</p>
                </div>
                <div class="reviews-container">
                <!-- KPI Cards -->
                <div class="review-stats">
                    <div class="stat-item stagger-1">
                        <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                        <div class="stat-value" id="kpiTotalReviews">3</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="stat-item stagger-2">
                        <div class="stat-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <div class="stat-value" id="kpiAvgRating">4.7</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-item stagger-3">
                        <div class="stat-icon"><i class="bi bi-hand-thumbs-up"></i></div>
                        <div class="stat-value" id="kpiHelpfulVotes">111</div>
                        <div class="stat-label">Helpful Votes</div>
                    </div>
                </div>

                <button class="btn-write-review" onclick="writeReview()">
                    <i class="bi bi-pencil-square"></i> Write New Review
                </button>

                <!-- Review Cards -->
                <div class="review-card stagger-1">
                    <div class="place-thumb">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&q=80" alt="Mount Batulao" class="place-image">
                        <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                    </div>
                    <div class="review-body">
                        <div class="review-header">
                            <div class="place-info">
                                <h3>Mount Batulao</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
                            <div class="review-actions">
                                <button class="btn-icon" onclick="editReview(this)"><i class="bi bi-pencil"></i></button>
                                <button class="btn-icon delete" onclick="deleteReview()"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="review-rating"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        <p class="review-text">Absolutely amazing experience! The sunrise hike was worth the early wake-up call. The rolling hills provide stunning panoramic views of Batangas. Trail is well-maintained and suitable for beginners. Highly recommended for nature lovers!</p>
                        <div class="review-photos">
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&h=300&fit=crop" alt="Review photo" class="review-photo">
                            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=300&h=300&fit=crop" alt="Review photo" class="review-photo">
                        </div>
                        <div class="review-meta">
                            <div class="review-date"><i class="bi bi-calendar"></i><span>November 20, 2024</span></div>
                            <div class="review-helpful"><span class="helpful-count"><i class="bi bi-hand-thumbs-up"></i><span>42 found this helpful</span></span></div>
                        </div>
                    </div>
                </div>

                <div class="review-card stagger-2">
                    <div class="place-thumb">
                        <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=300&q=80" alt="Fortune Island" class="place-image">
                        <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                    </div>
                    <div class="review-body">
                        <div class="review-header">
                            <div class="place-info">
                                <h3>Fortune Island</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
                            <div class="review-actions">
                                <button class="btn-icon" onclick="editReview(this)"><i class="bi bi-pencil"></i></button>
                                <button class="btn-icon delete" onclick="deleteReview()"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="review-rating"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                        <p class="review-text">The Greek-inspired ruins are breathtaking, but the island could be better maintained. Crystal clear waters perfect for swimming. Bring your own food and water as there are no facilities. The boat ride can be choppy.</p>
                        <div class="review-photos">
                            <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=300&h=300&fit=crop" alt="Review photo" class="review-photo">
                        </div>
                        <div class="review-meta">
                            <div class="review-date"><i class="bi bi-calendar"></i><span>September 8, 2024</span></div>
                            <div class="review-helpful"><span class="helpful-count"><i class="bi bi-hand-thumbs-up"></i><span>38 found this helpful</span></span></div>
                        </div>
                    </div>
                </div>

                <div class="review-card stagger-3">
                    <div class="place-thumb">
                        <img src="https://images.unsplash.com/photo-1519290916420-e67e00a83cde?w=300&q=80" alt="Caleruega Church" class="place-image">
                        <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                    </div>
                    <div class="review-body">
                        <div class="review-header">
                            <div class="place-info">
                                <h3>Caleruega Church</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
                            <div class="review-actions">
                                <button class="btn-icon" onclick="editReview(this)"><i class="bi bi-pencil"></i></button>
                                <button class="btn-icon delete" onclick="deleteReview()"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="review-rating"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i></div>
                        <p class="review-text">A beautiful and peaceful place for reflection. The hilltop chapel offers great views. However, it can get very crowded on weekends, which slightly diminishes the serene atmosphere. Best to visit on a weekday if you can.</p>
                        <div class="review-meta">
                            <div class="review-date"><i class="bi bi-calendar"></i><span>October 15, 2024</span></div>
                            <div class="review-helpful"><span class="helpful-count"><i class="bi bi-hand-thumbs-up"></i><span>31 found this helpful</span></span></div>
                        </div>
                    </div>
                </div>
                </div> <!-- end .reviews-container -->
            </div> <!-- end .content-area -->

            <!-- Write Review Modal -->
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                            <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Write a Review</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="reviewForm">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reviewPlace" class="form-label"><strong>Place</strong></label>
                                    <select id="reviewPlace" class="form-select" required>
                                        <option value="">Loading your visited places...</option>
                                    </select>
                                    <div class="form-text">Only places you've checked in to are available here.</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Date visited</strong></label>
                                        <div id="reviewDateDisplay" class="form-control-plaintext" style="padding:.5rem .75rem;border:1px solid #e9ecef;border-radius:.375rem;background:#fff;">—</div>
                                        <input type="hidden" id="reviewVisitDate" name="visit_date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Rating</strong></label>
                                        <div id="ratingStars" class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="1" aria-label="1 star"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="2" aria-label="2 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="3" aria-label="3 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="4" aria-label="4 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="5" aria-label="5 stars"><i class="bi bi-star-fill"></i></button>
                                            <input type="hidden" id="reviewRating" value="0" required>
                                        </div>
                                        <div class="form-text">Click or hover over stars to rate.</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="reviewText" class="form-label"><strong>Your review</strong></label>
                                    <textarea class="form-control" id="reviewText" rows="4" placeholder="Share your experience..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="reviewPhotos" class="form-label"><strong>Photos (optional)</strong></label>
                                    <input type="file" class="form-control" id="reviewPhotos" accept="image/*" multiple>
                                    <div id="photoPreview" class="d-flex gap-2 mt-2 flex-wrap"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); border: none;"><i class="bi bi-check-circle"></i> Save Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- My Profile Modal (copied from Explore) -->
            <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                            <h5 class="modal-title"><i class="bi bi-person-circle"></i> My Profile</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="profileForm">
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <div class="profile-avatar-large" id="profileAvatar">
                                        <?= esc($userInitials ?: 'JD') ?>
                                        <label class="avatar-upload-btn">
                                            <i class="bi bi-camera-fill"></i>
                                            <input type="file" id="avatarUpload" accept="image/*">
                                        </label>
                                    </div>
                                    <small class="text-muted">Click the camera icon to change profile picture</small>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="profileFirstName" class="form-label"><strong>First Name</strong></label>
                                        <input type="text" class="form-control" id="profileFirstName" value="<?= esc($userFirstName) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profileLastName" class="form-label"><strong>Last Name</strong></label>
                                        <input type="text" class="form-control" id="profileLastName" value="<?= esc($userLastName) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="profileEmail" class="form-label"><strong>Email Address</strong></label>
                                        <input type="email" class="form-control" id="profileEmail" value="<?= esc($userEmail) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profilePhone" class="form-label"><strong>Phone Number</strong></label>
                                        <input type="tel" class="form-control" id="profilePhone" value="<?= esc($session->get('Phone') ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profileBirthdate" class="form-label"><strong>Birthdate</strong></label>
                                        <input type="date" class="form-control" id="profileBirthdate" value="<?= esc($session->get('Birthdate') ?? '') ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="profileAddress" class="form-label"><strong>Address</strong></label>
                                        <textarea class="form-control" id="profileAddress" rows="2"><?= esc($session->get('Address') ?? '') ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="profileBio" class="form-label"><strong>Bio</strong></label>
                                        <textarea class="form-control" id="profileBio" rows="3" placeholder="Tell us about yourself..."><?= esc($session->get('Bio') ?? '') ?></textarea>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Change Password</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="currentPassword" class="form-label"><strong>Current Password</strong></label>
                                        <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="newPassword" class="form-label"><strong>New Password</strong></label>
                                        <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirmPassword" class="form-label"><strong>Confirm Password</strong></label>
                                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="profileSaveBtn" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); border: none;">
                                    <i class="bi bi-check-circle"></i> <span class="save-text">Save Changes</span>
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Review Modal -->
            <div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                            <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Review</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editReviewForm">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="editReviewPlace" class="form-label"><strong>Place</strong></label>
                                    <input type="text" class="form-control" id="editReviewPlace" required>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editReviewDate" class="form-label"><strong>Date visited</strong></label>
                                        <input type="text" class="form-control" id="editReviewDate" placeholder="Select date" autocomplete="off" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Rating</strong></label>
                                        <div id="editRatingStars" class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="1" aria-label="1 star"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="2" aria-label="2 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="3" aria-label="3 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="4" aria-label="4 stars"><i class="bi bi-star-fill"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="5" aria-label="5 stars"><i class="bi bi-star-fill"></i></button>
                                            <input type="hidden" id="editReviewRating" value="0" required>
                                        </div>
                                        <div class="form-text">Click or hover over stars to rate.</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editReviewText" class="form-label"><strong>Your review</strong></label>
                                    <textarea class="form-control" id="editReviewText" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); border: none;"><i class="bi bi-check-circle"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Review Confirmation Modal -->
            <div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #002e55 0%, #000814 100%); color: #fff; border: none;">
                            <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Delete Review</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="delete-icon mb-3">
                                <i class="bi bi-trash3" style="font-size: 4rem; color: #002e55;"></i>
                            </div>
                            <h5 class="mb-3">Are you sure you want to delete this review?</h5>
                            <p class="text-muted mb-0">This action cannot be undone. Your review will be permanently removed.</p>
                        </div>
                        <div class="modal-footer justify-content-center border-0 pb-4">
                            <button class="btn btn-secondary px-4" type="button" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                            <button class="btn btn-danger px-4" type="button" onclick="confirmDeleteReview()" style="background: linear-gradient(135deg, #002e55, #000814); border: none;">
                                <i class="bi bi-trash-fill"></i> Delete Review
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
     </div>

     <!-- Mobile Menu Button -->
     <button class="mobile-menu-btn" onclick="toggleSidebar()">
         <i class="bi bi-list"></i>
     </button>

    <!-- Toasts -->
    <div class="toast-container" id="toastContainer"></div>    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/tourist-ui.js') ?>"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        if (typeof toggleUserDropdown === 'undefined') {
            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                const notifDropdown = document.getElementById('notificationDropdown');
                notifDropdown?.classList.remove('show');
                if (dropdown) dropdown.classList.toggle('show');
            }
        }

        function hideUserDropdown(e){
            e?.preventDefault();
            document.getElementById('userDropdown')?.classList.remove('show');
        }

        function openProfile(e){
            e?.preventDefault();
            hideUserDropdown(e);
            const modal = document.getElementById('profileModal');
            if(modal) bootstrap.Modal.getOrCreateInstance(modal).show();
        }

        if (typeof window.markAllAsRead === 'undefined') {
            function markAllAsRead(){
                document.querySelectorAll('.notification-item.unread').forEach(i=>i.classList.remove('unread'));
                const badge = document.getElementById('notifBadge');
                if(badge) { badge.textContent = '0'; badge.style.display = 'none'; }
            }
        }

        function viewAllNotifications(e){
            e?.preventDefault();
            // Could navigate to notifications page
        }

        if (typeof handleLogout === 'undefined') {
            function handleLogout(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    showToast && showToast('Logged Out', 'You are being logged out...');
                    setTimeout(() => { window.location.href = '/users/logout'; }, 600);
                }
            }
        }

        if (!window._touristUiClickHandlerAttached) {
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('sidebar');
                const menuBtn = document.querySelector('.mobile-menu-btn');
                const userDropdown = document.getElementById('userDropdown');
                const userAvatar = document.querySelector('.user-avatar');
                const notifDropdown = document.getElementById('notificationDropdown');
                const notifBtn = document.querySelector('.notification-btn');

                if (window.innerWidth <= 992) {
                    if (sidebar && menuBtn && !sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }

                if (userDropdown && userAvatar && !userAvatar.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.remove('show');
                }

                if (notifDropdown && notifBtn && !notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
                    notifDropdown.classList.remove('show');
                }
            });
            window._touristUiClickHandlerAttached = true;
        }

        function toggleNotificationDropdown(){
            const dd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            ud?.classList.remove('show');
            dd?.classList.toggle('show');
        }

        function openNotificationDetail(item){
            const title = item.querySelector('.notification-text h6')?.textContent || 'Notification';
            const message = item.querySelector('.notification-text p')?.textContent || '';
            const time = item.querySelector('.notification-time')?.textContent || '';
            item.classList.remove('unread');
            document.getElementById('notificationDropdown')?.classList.remove('show');
            const modal = document.getElementById('notificationDetailModal');
            if(modal){
                document.getElementById('notifDetailTitle').textContent = title;
                document.getElementById('notifDetailMessage').textContent = message;
                document.getElementById('notifDetailTime').textContent = time;
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
        }

        if (typeof showToast === 'undefined') {
            function showToast(title, msg){
                const container = document.getElementById('toastContainer');
                if (!container) return;
                const el = document.createElement('div');
                el.className = 'toast text-bg-primary';
                el.role = 'alert'; el.ariaLive = 'assertive'; el.ariaAtomic = 'true';
                el.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="toast-body"><strong>${title}:</strong> ${msg}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>`;
                container.appendChild(el);
                const t = new bootstrap.Toast(el, { delay: 3000 });
                t.show();
                el.addEventListener('hidden.bs.toast', () => el.remove());
            }
        }

        // Enhance interactions + modal logic
        let reviewModal, profileModal, editModal, currentEditingCard = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Modals
            reviewModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('reviewModal'));
            profileModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('profileModal'));
            editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editReviewModal'));

            // Tooltips on action buttons
            document.querySelectorAll('.btn-icon').forEach(btn => {
                btn.setAttribute('data-bs-toggle','tooltip');
                if (btn.querySelector('.bi-pencil')) btn.setAttribute('title','Edit review');
                if (btn.classList.contains('delete')) btn.setAttribute('title','Delete review');
            });
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(el));

            // Helpful count toggle
            document.querySelectorAll('.helpful-count').forEach(pill => {
                pill.addEventListener('click', () => {
                    const active = pill.classList.toggle('active');
                    const span = pill.querySelector('span');
                    const icon = pill.querySelector('i');
                    const num = parseInt((span.textContent.match(/\d+/) || ['0'])[0], 10);
                    const newNum = active ? num + 1 : Math.max(0, num - 1);
                    span.textContent = `${newNum} found this helpful`;
                    icon.classList.toggle('bi-hand-thumbs-up-fill', active);
                    icon.classList.toggle('bi-hand-thumbs-up', !active);
                });
            });

            // Initialize Flatpickr for review dates
            if (window.flatpickr) {
                // Only initialize flatpickr for the edit modal date (write modal date is auto-populated)
                flatpickr('#editReviewDate', {
                    dateFormat: 'Y-m-d',
                    maxDate: 'today',
                    altInput: true,
                    altFormat: 'F j, Y',
                    allowInput: true
                });
            }

            // Modern Rating stars with hover effects
            function initRatingStars(containerId, inputId) {
                const container = document.getElementById(containerId);
                const stars = container.querySelectorAll('.rating-star');
                const ratingInput = document.getElementById(inputId);

                // Hover effect
                stars.forEach((btn, index) => {
                    btn.addEventListener('mouseenter', () => {
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.classList.add('hovered');
                            } else {
                                s.classList.remove('hovered');
                            }
                        });
                    });
                });

                container.addEventListener('mouseleave', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                });

                // Click to set rating
                stars.forEach((btn, index) => {
                    btn.addEventListener('click', () => {
                        const val = parseInt(btn.dataset.value, 10);
                        ratingInput.value = val;
                        stars.forEach((s, i) => {
                            if (i < val) {
                                s.classList.add('filled');
                            } else {
                                s.classList.remove('filled');
                            }
                        });
                    });
                });
            }

            initRatingStars('ratingStars', 'reviewRating');
            initRatingStars('editRatingStars', 'editReviewRating');

            // Photo preview
            const reviewPhotos = document.getElementById('reviewPhotos');
            const photoPreview = document.getElementById('photoPreview');
            reviewPhotos?.addEventListener('change', (e) => {
                photoPreview.innerHTML = '';
                [...e.target.files].slice(0, 6).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = ev => {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'review-photo';
                        img.style.width = '70px';
                        img.style.height = '70px';
                        img.style.objectFit = 'cover';
                        photoPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Profile: avatar upload preview
            const avatarUpload = document.getElementById('avatarUpload');
            const profileAvatar = document.getElementById('profileAvatar');
            avatarUpload?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        profileAvatar.style.backgroundImage = `url(${ev.target.result})`;
                        profileAvatar.style.backgroundSize = 'cover';
                        profileAvatar.style.backgroundPosition = 'center';
                        profileAvatar.textContent = '';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Profile form submit with spinner
            const profileForm = document.getElementById('profileForm');
            const profileSaveBtn = document.getElementById('profileSaveBtn');
            profileForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;
                if (newPass || confirmPass) {
                    if (newPass !== confirmPass) { alert('New passwords do not match!'); return; }
                    if (!document.getElementById('currentPassword').value) { alert('Please enter your current password.'); return; }
                }
                setLoading(profileSaveBtn, true);
                setTimeout(() => {
                    setLoading(profileSaveBtn, false);
                    bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                    showToast('Profile Updated', 'Your profile has been successfully updated.');
                }, 1000);
            });

            // Edit stars are now initialized with initRatingStars function above

            // Save edit
            document.getElementById('editReviewForm').addEventListener('submit', (e) => {
                e.preventDefault();
                if (!currentEditingCard) return;

                const name = document.getElementById('editReviewPlace').value.trim();
                const date = document.getElementById('editReviewDate').value;
                const text = document.getElementById('editReviewText').value.trim();
                const rating = parseInt(document.getElementById('editReviewRating').value || '0', 10);

                // Update card fields
                currentEditingCard.querySelector('.place-info h3').textContent = name;
                // Date format (YYYY-MM-DD -> Month D, YYYY) basic
                if (date) {
                    const d = new Date(date);
                    const formatted = d.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                    currentEditingCard.querySelector('.review-date span').textContent = formatted;
                }
                currentEditingCard.querySelector('.review-text')?.replaceChildren(document.createTextNode(text));

                // Update stars
                const starWrap = currentEditingCard.querySelector('.review-rating');
                if (starWrap) {
                    starWrap.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const iEl = document.createElement('i');
                        iEl.className = 'bi ' + (i < rating ? 'bi-star-fill' : 'bi-star');
                        starWrap.appendChild(iEl);
                    }
                }

                editModal.hide();
                showToast('Review Updated', 'Your changes have been saved.');
            });

            // Submit new review via AJAX to server endpoint
            const reviewFormEl = document.getElementById('reviewForm');
            reviewFormEl?.addEventListener('submit', async (ev) => {
                ev.preventDefault();
                const place = document.getElementById('reviewPlace').value.trim();
                // read visit_date from hidden field (auto-populated from selected visited place)
                const date = document.getElementById('reviewVisitDate') ? document.getElementById('reviewVisitDate').value : '';
                const rating = parseInt(document.getElementById('reviewRating').value || '0', 10);
                const text = document.getElementById('reviewText').value.trim();
                if (!place || !date || !rating || !text) {
                    alert('Please fill required fields: place, date, rating and review text.');
                    return;
                }

                const fd = new FormData();
                fd.append('place', place);
                fd.append('visit_date', date);
                fd.append('rating', rating);
                fd.append('comment', text);

                const photos = document.getElementById('reviewPhotos');
                if (photos && photos.files && photos.files.length) {
                    Array.from(photos.files).slice(0,6).forEach((f, idx) => fd.append('photos[]', f));
                }

                try {
                    const res = await fetch('/tourist/saveReview', { method: 'POST', body: fd, credentials: 'same-origin' });
                    const payload = await res.json();
                    if (res.ok && payload.success) {
                        bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                        showToast('Review Saved', payload.message || 'Your review was saved.');
                        // reload to show saved reviews list (simple and reliable)
                        setTimeout(() => window.location.reload(), 900);
                    } else {
                        const msg = payload.message || 'Failed to save review';
                        alert(msg);
                    }
                } catch (err) {
                    console.error(err);
                    alert('An error occurred while saving review.');
                }
            });

            // Load visited places into the Place select so users can only review places they've visited
            async function loadVisitedPlacesForReviews() {
                const sel = document.getElementById('reviewPlace');
                if (!sel) return;
                sel.innerHTML = '<option value="">Loading your visited places...</option>';
                try {
                    const res = await fetch('/tourist/visited/ajax', { method: 'GET', credentials: 'same-origin' });
                    const payload = await res.json();
                    if (!res.ok || !payload.success) {
                        sel.innerHTML = '<option value="">No visited places found</option>';
                        return;
                    }
                    const data = payload.data || [];
                    if (!data.length) {
                        sel.innerHTML = '<option value="">You have no visited places</option>';
                        return;
                    }
                    sel.innerHTML = '<option value="">Select a place you visited</option>';
                    data.forEach(item => {
                        // item: booking_id, visit_date, spot_name, etc.
                        const opt = document.createElement('option');
                        opt.value = item.spot_name || item.name || '';
                        opt.textContent = (item.spot_name || item.name || '') + (item.visit_date ? (' — ' + item.visit_date) : '');
                        if (item.visit_date) opt.dataset.visitDate = item.visit_date;
                        if (item.booking_id) opt.dataset.bookingId = item.booking_id;
                        sel.appendChild(opt);
                    });
                } catch (err) {
                    console.error('Failed to load visited places', err);
                    sel.innerHTML = '<option value="">Failed to load visited places</option>';
                }
            }

            // Prefill visit date when a place is selected (populate hidden input and display)
            document.getElementById('reviewPlace')?.addEventListener('change', function(e){
                const opt = e.target.selectedOptions && e.target.selectedOptions[0];
                const display = document.getElementById('reviewDateDisplay');
                const hidden = document.getElementById('reviewVisitDate');
                if (!opt) {
                    if (display) display.textContent = '—';
                    if (hidden) hidden.value = '';
                    return;
                }
                const visit = opt.dataset.visitDate;
                if (visit) {
                    // show a human-friendly date in the display
                    try {
                        const d = new Date(visit);
                        const formatted = d.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                        if (display) display.textContent = formatted;
                    } catch (err) {
                        if (display) display.textContent = visit;
                    }
                    if (hidden) hidden.value = visit;
                } else {
                    if (display) display.textContent = '—';
                    if (hidden) hidden.value = '';
                }
            });

            // Ensure places are loaded when modal opens
            document.getElementById('reviewModal').addEventListener('show.bs.modal', loadVisitedPlacesForReviews);
            // Also load once on page load so select is ready fast
            loadVisitedPlacesForReviews();
        });

        function writeReview() { reviewModal?.show(); }
        function editReview(btn) {
            const card = btn.closest('.review-card'); if (!card) return;
            currentEditingCard = card;
            const name = card.querySelector('.place-info h3')?.textContent?.trim() || '';
            const dateText = card.querySelector('.review-date span')?.textContent?.trim() || '';
            const text = card.querySelector('.review-text')?.textContent?.trim() || '';
            const rating = card.querySelectorAll('.review-rating .bi-star-fill').length || 0;

            // Prefill
            document.getElementById('editReviewPlace').value = name;
            // Attempt to parse dateText to yyyy-mm-dd; if fail, leave empty
            let iso = '';
            try { const d = new Date(dateText); if (!isNaN(d)) iso = d.toISOString().slice(0,10); } catch(e){}
            const dateInput = document.getElementById('editReviewDate');
            if (dateInput._flatpickr) {
                dateInput._flatpickr.setDate(iso, true);
            } else {
                dateInput.value = iso;
            }
            document.getElementById('editReviewText').value = text;
            document.getElementById('editReviewRating').value = rating;

            // Paint stars
            document.querySelectorAll('#editRatingStars .rating-star').forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('filled');
                } else {
                    s.classList.remove('filled');
                }
            });

            editModal.show();
        }
        
        let deleteModal;
        function deleteReview() {
            if (!deleteModal) {
                deleteModal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));
            }
            deleteModal.show();
        }
        
        function confirmDeleteReview() {
            deleteModal.hide();
            showToast('Delete', 'Review deleted successfully.');
            // Add actual delete logic here
        }

        // Open Profile modal
        function openProfile(e){
            e.preventDefault();
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                userDropdown.classList.remove('show');
            }
            profileModal?.show();
        }
        function setLoading(btn, isLoading) {
            const sp = btn.querySelector('.spinner-border');
            const saveText = btn.querySelector('.save-text');
            if (isLoading) { sp?.classList.remove('d-none'); btn.disabled = true; if (saveText) saveText.textContent = 'Saving...'; }
            else { sp?.classList.add('d-none'); btn.disabled = false; if (saveText) saveText.textContent = 'Save Changes'; }
        }
    </script>

    <!-- Notification Detail Modal (opens when clicking a notification) -->
    <div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-bell-fill"></i> <span id="notifDetailTitle">Notification</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p id="notifDetailMessage" style="font-size:1rem;color:#333;margin-bottom:1rem;"></p>
            <p class="text-muted" style="font-size:0.875rem;margin:0;"><i class="bi bi-clock"></i> <span id="notifDetailTime"></span></p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" data-bs-dismiss="modal">Take Action</button>
          </div>
        </div>
      </div>
    </div>

</body>
</html>