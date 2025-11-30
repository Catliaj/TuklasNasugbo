<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Flatpickr CSS for date range picker -->
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
            .guest-spinner { display: inline-flex; align-items: center; gap: 6px; }
            .guest-input { width: 50px; text-align: center; border: 1px solid #ced4da; border-radius: 6px; padding: 4px 6px; }
            .guest-input::-webkit-outer-spin-button, .guest-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
            .guest-input { -moz-appearance: textfield; appearance: textfield; }
            .btn-guest { width: 36px; height: 36px; border-radius: 50%; border: 1px solid #0d4d7d; background: linear-gradient(135deg,#004b8d,#002e55); color: #fff; font-weight: 600; display:flex; align-items:center; justify-content:center; padding:0; cursor:pointer; transition: background .2s, transform .15s; }
            .btn-guest:hover { background: linear-gradient(135deg,#005fae,#003a6e); }
            .btn-guest:active { transform: scale(.92); }
            .guest-row { background:#fff; }
            .guest-row:hover { background:#f5f9fc; }
            .summary-card { background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:18px 20px; box-shadow:0 4px 14px rgba(0,0,0,.06); position:relative; overflow:hidden; }
            .summary-card:before { content:""; position:absolute; inset:0; pointer-events:none; background:linear-gradient(135deg,rgba(0,75,141,0.08),rgba(0,8,20,0.05)); }
            .summary-lines { display:flex; flex-direction:column; gap:8px; }
            .summary-line { display:flex; justify-content:space-between; font-size:.9rem; }
            .summary-line .label { color:#334155; }
            .summary-line .value { color:#0f172a; }
            .total-row { font-size:.95rem; }
            /* Enhanced Explore Header */
            :root { --ocean-accent:#4ecbff; --ocean-accent-soft:#b5ecff; --ocean-text:#e6f8ff; }
            .page-header {background:#002e55;color:var(--ocean-text);height:210px;min-height:210px;padding:1.6rem 2.4rem 1.8rem;border-radius:28px;position:relative;overflow:hidden;box-shadow:0 12px 34px -10px rgba(0,56,108,.55);display:flex;flex-direction:column;justify-content:center;}            
            .page-header h2 {font-weight:700;display:flex;align-items:center;gap:.85rem;margin:0 0 .55rem;color:#e2e8f0;font-size:.10rem;letter-spacing:.6px;line-height:1.1;position:relative;top:-6px;}
            .page-header h2 i {background:rgba(255,255,255,.12);padding:.8rem;border-radius:18px;font-size:2.2rem;animation:slow-spin 18s linear infinite;color:var(--ocean-text);position:relative;top:-4px;} 
            @keyframes slow-spin {from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
            .page-header p {font-size:1.05rem;letter-spacing:.5px;margin:0;color:var(--ocean-accent-soft);text-shadow:0 1px 2px rgba(0,0,0,.25);}
            /* Wave layers */
            .page-header:before {content:"";position:absolute;left:0;right:0;bottom:0;height:110px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,80 C150,120 300,40 450,70 C600,100 750,50 900,80 C1050,110 1200,60 1200,60 L1200,120 L0,120 Z" fill="%2300487a"/></svg>') repeat-x;background-size:1200px 110px;opacity:.55;filter:drop-shadow(0 4px 8px rgba(0,0,0,.3));}
            .page-header:after {content:"";position:absolute;left:0;right:0;bottom:0;height:90px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,60 C200,100 400,20 600,60 C800,100 1000,30 1200,70 L1200,120 L0,120 Z" fill="%23005fae"/></svg>') repeat-x;background-size:1200px 90px;opacity:.35;}
            .page-header-actions {position:absolute;top:1.1rem;right:1.3rem;display:flex;align-items:center;gap:1rem;z-index:5;}
            .page-header-actions .user-avatar {background:linear-gradient(135deg,#004b8d,#001d33);color:#e2e8f0;font-weight:600;width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 12px -3px rgba(0,0,0,.5);transition:.25s;border:2px solid rgba(255,255,255,.18);}
            .page-header-actions .user-avatar:hover {transform:translateY(-2px);background:linear-gradient(135deg,#005fae,#002e55);}
            .page-header h2 i {background:rgba(255,255,255,.10);} /* subtle adjust */
            .header-badges {display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1rem;}
            .header-badge {background:rgba(255,255,255,.10);color:var(--ocean-accent-soft);padding:.45rem .75rem;border-radius:40px;font-size:.7rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;backdrop-filter:blur(6px);border:1px solid rgba(227, 216, 216, 0.28);transition:.25s;box-shadow:0 2px 6px -2px rgba(0,0,0,.35);}
            .header-badge:hover {background:rgba(255,255,255,.25);color:#ffffff;box-shadow:0 4px 12px -4px rgba(0,0,0,.45);}
            /* Spot Details Modern Panel */
            .spot-details-panel {background:rgba(255,255,255,0.35);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.55);border-radius:18px;padding:18px;box-shadow:0 8px 28px -8px rgba(0,46,85,0.35);max-height:320px;overflow-y:auto;position:relative;}
            .spot-details-panel:before {content:"";position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,0.08),rgba(0,46,85,0.06));pointer-events:none;}
            .detail-grid {display:flex;flex-direction:column;gap:14px;}
            .detail-item {display:flex;gap:14px;align-items:flex-start;background:rgba(255,255,255,0.55);border:1px solid rgba(0,75,141,0.12);padding:12px 14px;border-radius:14px;position:relative;transition:.25s;}
            .detail-item:hover {background:rgba(255,255,255,0.75);box-shadow:0 6px 18px -6px rgba(0,46,85,0.25);}
            .detail-icon {width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.35rem;color:#fff;position:relative;overflow:hidden;}
            .detail-icon.gradient {background:linear-gradient(135deg,#004b8d,#002e55);box-shadow:0 4px 14px -4px rgba(0,46,85,0.5);}
            .detail-content {flex:1;display:flex;flex-direction:column;gap:4px;}
            .detail-label {font-size:.75rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#003a6e;opacity:.85;}
            .detail-value {font-size:.95rem;color:#0b2236;font-weight:500;}
            .pricing-tags {display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;}
            .price-pill {background:linear-gradient(135deg,#ffffff,#f0f6fa);border:1px solid #d5e4ee;color:#003a6e;font-size:.70rem;font-weight:600;padding:6px 10px;border-radius:40px;box-shadow:0 2px 6px -2px rgba(0,46,85,.25);display:inline-flex;align-items:center;gap:4px;}
            .price-pill:before {content:"";width:8px;height:8px;border-radius:50%;background:#004b8d;display:inline-block;box-shadow:0 0 0 3px rgba(0,75,141,.18);}
            .spot-details-panel::-webkit-scrollbar {width:10px;}
            .spot-details-panel::-webkit-scrollbar-track {background:rgba(255,255,255,.35);border-radius:10px;}
            .spot-details-panel::-webkit-scrollbar-thumb {background:linear-gradient(135deg,#004b8d,#002e55);border-radius:10px;border:2px solid rgba(255,255,255,.5);}
            .spot-details-panel::-webkit-scrollbar-thumb:hover {background:linear-gradient(135deg,#005fae,#003a6e);}
            .spot-modal-carousel img {max-height:300px;object-fit:cover;border-radius:16px;box-shadow:0 6px 20px -8px rgba(0,46,85,.4);}
            @media (max-width: 768px){
                .spot-modal-carousel img {max-height:220px;}
                .detail-item {padding:10px 12px;}
                .detail-icon {width:40px;height:40px;font-size:1.1rem;}
            }
            /* Carousel image design & animation */
            .spot-modal-carousel {position:relative;}
            .spot-modal-carousel .carousel-item {overflow:hidden;border-radius:16px;}
            .spot-modal-carousel .carousel-item:after {content:"";position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.15),rgba(0,46,85,0.55));opacity:.55;pointer-events:none;mix-blend-mode:overlay;}
            .kb-img {width:100%;height:100%;object-fit:cover;transform-origin:center;animation:kenburns 18s ease-in-out infinite alternate;}
            @keyframes kenburns {0%{transform:scale(1) translate(0,0);}50%{transform:scale(1.08) translate(2%, -2%);}100%{transform:scale(1.12) translate(-2%,2%);} }
            .spot-modal-carousel .carousel-control-prev-icon, .spot-modal-carousel .carousel-control-next-icon {filter:drop-shadow(0 2px 4px rgba(0,0,0,.45));}
            .spot-modal-carousel .carousel-control-prev, .spot-modal-carousel .carousel-control-next {width:48px;}
            .spot-modal-carousel .carousel-control-prev:hover .carousel-control-prev-icon, .spot-modal-carousel .carousel-control-next:hover .carousel-control-next-icon {filter:drop-shadow(0 4px 10px rgba(0,0,0,.55));}
            /* Description & meta styling */
            .spot-modal-info {display:flex;flex-direction:column;gap:16px;}
            .spot-desc-box {background:rgba(255,255,255,0.5);backdrop-filter:blur(12px);border:1px solid rgba(0,75,141,0.15);border-radius:18px;padding:16px 18px;position:relative;overflow:hidden;box-shadow:0 6px 20px -8px rgba(0,46,85,.3);}
            .spot-desc-box:before {content:"";position:absolute;inset:0;background:radial-gradient(circle at 25% 20%,rgba(0,75,141,0.12),transparent 60%);pointer-events:none;}
            #spotModalDesc {margin:0;font-size:.95rem;line-height:1.45;color:#0b2236;font-weight:500;}
            .spot-meta-lines {display:flex;flex-wrap:wrap;gap:12px;}
            .spot-meta-chip {display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;font-size:.70rem;font-weight:600;padding:8px 14px;border-radius:40px;letter-spacing:.5px;box-shadow:0 4px 14px -4px rgba(0,46,85,.5);position:relative;}
            .spot-meta-chip i {font-size:1rem;}
            .spot-meta-chip.category {background:linear-gradient(135deg,#0072c6,#004b8d);}
            .spot-meta-chip.rating {background:linear-gradient(135deg,#ffb400,#ff7a18);}
            .spot-meta-chip.rating i {color:#fff;}
            @media (max-width:768px){
                .spot-meta-chip {font-size:.65rem;padding:6px 12px;}
                #spotModalDesc {font-size:.9rem;}
            }
            @media (max-width: 768px){
                .page-header {padding:2rem 1.2rem 2.4rem;border-radius:22px;}
                .page-header-actions {position:static;justify-content:flex-start;margin-bottom:1rem;}
                .page-header h2{font-size:1.4rem;}
                .page-header p{font-size:.9rem;}
            }
        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/explore.css')?>">
</head>
<body>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Runtime flag: server configured for hosted PayMongo checkout?
        window.PAYMONGO_HOSTED = <?= (getenv('PAYMONGO_SECRET_KEY') || getenv('PAYMONGO_SECRET')) ? 'true' : 'false' ?>;
    </script>
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
                <button class="tourist-sidebar-toggle d-lg-none" onclick="toggleMobileSidebar()">
                    <i class="bi bi-x fs-3"></i>
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
                        <a href="/tourist/exploreSpots" class="tourist-nav-link active">
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
                    <!-- My Reviews link removed (reviews integrated into Visited/Explore) -->
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Fixed User Actions (Top Right) -->
            <div class="user-actions-fixed" style="display:none;">
                <!-- User Avatar -->
                <div style="position: relative;">
                    <div class="user-avatar" onclick="toggleUserDropdown()">JD</div>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <h6>Juan Dela Cruz</h6>
                            <p>juan.delacruz@email.com</p>
                        </div>
                        <ul class="dropdown-menu-custom">
                            <li>
                                <a href="#" class="dropdown-item-custom logout" onclick="handleLogout(event)">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
         
            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-actions">
                        <div style="position: relative;">
                            <div style="position:relative;display:flex;align-items:center;gap:1rem;">
                        <button class="notification-btn" onclick="toggleNotificationDropdown()">
                            <i class="bi bi-bell-fill"></i>
                            <span class="notification-badge" id="notifBadge">3</span>
                        </button>
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h6>Notifications</h6>
                                <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
                            </div>
                            <ul class="notification-list">
                                <li class="notification-item unread" onclick="openNotificationDetail(this)" style="cursor:pointer;">
                                    <div class="notification-content">
                                        <div class="notification-icon success"><i class="bi bi-check-circle-fill"></i></div>
                                        <div class="notification-text">
                                            <h6>New Spot Available</h6>
                                            <p>Punta Fuego Beach is now open for bookings</p>
                                            <div class="notification-time"><i class="bi bi-clock"></i> 30 minutes ago</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item unread" onclick="openNotificationDetail(this)" style="cursor:pointer;">
                                    <div class="notification-content">
                                        <div class="notification-icon info"><i class="bi bi-star-fill"></i></div>
                                        <div class="notification-text">
                                            <h6>Special Offer</h6>
                                            <p>Get 20% off Canyon Cove bookings this weekend</p>
                                            <div class="notification-time"><i class="bi bi-clock"></i> 1 day ago</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="notification-footer">
                                <a href="#" onclick="viewAllNotifications(event)">View all notifications</a>
                            </div>
                        </div>
                        <div class="user-avatar" onclick="toggleUserDropdown()"><?= $userInitials ?></div>
                    </div>
                            <div class="user-dropdown" id="userDropdown">
                                <div class="dropdown-header">
                                    <h6><?= esc($userFirstName . ' ' . $userLastName) ?></h6>
                                    <p><?= esc($userEmail) ?></p>
                                </div>
                                <ul class="dropdown-menu-custom">
                                    <li>
                                        <a href="#" class="dropdown-item-custom" onclick="openProfile(event); hideUserDropdown(event)">
                                            <i class="bi bi-person-circle"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item-custom logout" onclick="handleLogout(event)">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <h2><i class="bi bi-compass"></i> Explore Nasugbu</h2>
                    <p>Discover amazing tourist spots & hidden coastal gems.</p>
                </div>

                <!-- Search and Filter -->
                <?php
                // Build recommended, favorites, and other spots collections
                $recommendedSpots = [];
                $favoriteSpots = [];
                $otherSpots = [];
                foreach ($spots as $s) {
                    $idVal = $s['spot_id'] ?? $s['id'] ?? null;
                    $ratingVal = isset($s['rating']) ? (float)$s['rating'] : 0.0;
                    $isFav = isset($favoriteSpotIds) && in_array($idVal, $favoriteSpotIds);
                    
                    if ($isFav) {
                        $favoriteSpots[] = $s;
                    }
                    
                    // Criteria: Favorited OR high rating (>=4.6) -> recommended
                    if ($isFav || $ratingVal >= 4.6) {
                        $recommendedSpots[] = $s;
                    } else {
                        $otherSpots[] = $s;
                    }
                }
                ?>

                <?php if (!empty($recommendedSpots)): ?>
                <div class="recommended-section mb-4">
                    <h4 class="recommended-title d-flex align-items-center gap-2 mb-3" style="font-weight:700;color:#003a6e;">
                        <i class="bi bi-stars" style="font-size:1.3rem;color:#ffb400;"></i> Recommended For You
                    </h4>
                    <div class="recommended-grid d-flex gap-3 pb-2" style="overflow-x:auto;scrollbar-width:thin;">
                        <?php foreach ($recommendedSpots as $spot): ?>
                            <?php 
                                $imagePath = 'uploads/spots/' . $spot['primary_image'];
                                if (!is_file(FCPATH . $imagePath)) { 
                                    $imagePath = 'uploads/spots/Spot-No-Image.png';
                                }
                                $isFav = in_array(($spot['spot_id'] ?? $spot['id'] ?? null), $favoriteSpotIds ?? []);
                            ?>
                            <div class="spot-card recommended-card flex-shrink-0" style="width:280px;min-width:280px;" data-category="<?= esc($spot['category']) ?>" data-spot-id="<?= esc($spot['spot_id'] ?? $spot['id'] ?? '') ?>">
                                <div class="spot-image" style="background-image: url('<?= base_url($imagePath) ?>');height:180px;">
                                    <button class="favorite-btn<?= $isFav ? ' active' : '' ?>" data-spot-id="<?= esc($spot['spot_id'] ?? $spot['id'] ?? '') ?>" onclick="toggleFavorite(this)">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                                <div class="spot-content" style="min-height:200px;display:flex;flex-direction:column;">
                                    <h3 class="spot-title" style="font-size:1rem;height:2.4em;overflow:hidden;line-height:1.2em;"><?= esc($spot['spot_name']) ?></h3>
                                    <p class="spot-description" style="height:54px;overflow:hidden;line-height:1.35em;flex-grow:0;"><?= esc($spot['description']) ?></p>
                                    <div class="spot-meta">
                                        <span class="spot-category">
                                            <i class="bi bi-geo-alt"></i>
                                            <?= esc($spot['category']) ?>
                                        </span>
                                        <div class="spot-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <span><?= esc($spot['rating'] ?? '4.5') ?></span>
                                        </div>
                                    </div>
                                    <div class="spot-actions">
                                        <button class="btn-view" onclick="viewDetails(this)">View Details</button>
                                        <button class="btn-book" onclick="bookSpot(<?= (int)($spot['spot_id'] ?? $spot['id'] ?? 0) ?>, '<?= esc(addslashes($spot['spot_name'])) ?>', this)">
                                            <i class="bi bi-ticket-detailed"></i> Book
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="search-filter-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search for tourist spots...">
                        <i class="bi bi-search"></i>
                    </div>
                    
                    <div class="filter-tags" style="display:grid;grid-template-columns:repeat(7,1fr);gap:0.5rem;max-width:100%;">
                        <button class="filter-tag active" data-filter="all">All</button>
                        <?php
                        $uiCategories = ['Historical','Cultural','Natural','Recreational','Religious','Adventure','Ecotourism','Urban','Rural','Beach','Mountain','Resort','Park','Restaurant'];
                        foreach ($uiCategories as $uc):
                        ?>
                            <button class="filter-tag" data-filter="<?= strtolower($uc) ?>"><?= esc($uc) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="spots-grid" id="spotsGrid">

                    <?php foreach ($otherSpots as $spot): ?>
                        <?php 
                            // IMAGE PATH CHECK
                            $imagePath = 'uploads/spots/' . $spot['primary_image'];
                            if (!is_file(FCPATH . $imagePath)) { 
                                $imagePath = 'uploads/spots/Spot-No-Image.png';
                            }
                        ?>

                        <div class="spot-card" data-category="<?= esc($spot['category']) ?>" data-spot-id="<?= esc($spot['spot_id'] ?? $spot['id'] ?? '') ?>">
                            <div class="spot-image" style="background-image: url('<?= base_url($imagePath) ?>')">
                                <?php $isFav = in_array(($spot['spot_id'] ?? $spot['id'] ?? null), $favoriteSpotIds ?? []); ?>
                                <button class="favorite-btn<?= $isFav ? ' active' : '' ?>" data-spot-id="<?= esc($spot['spot_id'] ?? $spot['id'] ?? '') ?>" onclick="toggleFavorite(this)">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>

                            <div class="spot-content">
                                <h3 class="spot-title"><?= esc($spot['spot_name']) ?></h3>
                                <p class="spot-description"><?= esc($spot['description']) ?></p>

                                <div class="spot-meta">
                                    <span class="spot-category">
                                        <i class="bi bi-geo-alt"></i>
                                        <?= esc($spot['category']) ?>
                                    </span>

                                    <div class="spot-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <span><?= esc($spot['rating'] ?? '4.5') ?></span>
                                    </div>
                                </div>

                                <div class="spot-actions">
                                    <button class="btn-view" onclick="viewDetails(this)">View Details</button>
                                    <button class="btn-book" onclick="bookSpot(<?= (int)($spot['spot_id'] ?? $spot['id'] ?? 0) ?>, '<?= esc(addslashes($spot['spot_name'])) ?>', this)">
                                        <i class="bi bi-ticket-detailed"></i> Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </main>
        
                <!-- Booking Modal (Range + Vertical Guests) -->
                <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                                <h5 class="modal-title" id="bookingModalLabel"><i class="bi bi-calendar-range"></i> Reserve Your Visit</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                                        <div class="modal-body">
                                                            <div class="row g-4">
                                                                <div class="col-md-7">
                                                                    <form id="bookingForm">
                                                                        <input type="hidden" id="bookingSpotId" name="spot_id" />
                                                                        <input type="hidden" id="bookingDate" name="visit_date" />
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Spot Name</strong></label>
                                                                            <input type="text" class="form-control" id="bookingSpotDisplay" disabled style="background-color:#f6f6f6;" />
                                                                        </div>
                                                                        <div class="row mb-3 align-items-end">
                                                                            <div class="col-md-7">
                                                                                <label class="form-label"><strong>Date Range</strong></label>
                                                                                <input type="text" class="form-control" id="bookingDateRange" placeholder="Select date range" autocomplete="off" />
                                                                                <input type="hidden" id="bookingDateStart" />
                                                                                <input type="hidden" id="bookingDateEnd" />
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <label class="form-label"><strong>Visit Time</strong></label>
                                                                                <select class="form-select" id="bookingStartTime" name="visit_time" required>
                                                                                    <option value="">Select time</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label d-block"><strong>Guests</strong></label>
                                                                            <div class="guest-list d-flex flex-column gap-2">
                                                                                <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                                                                                    <div class="me-3"><strong>Adults</strong> <span id="priceAdult" class="text-muted small"></span></div>
                                                                                    <div class="guest-spinner" data-input="bookingAdults">
                                                                                        <button type="button" class="btn-guest btn-guest-minus" aria-label="Decrease adults" data-target="bookingAdults">−</button>
                                                                                        <input type="number" class="guest-input" id="bookingAdults" name="num_adults" min="0" value="1" required />
                                                                                        <button type="button" class="btn-guest btn-guest-plus" aria-label="Increase adults" data-target="bookingAdults">+</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                                                                                    <div class="me-3"><strong>Children</strong> <span id="priceChild" class="text-muted small"></span></div>
                                                                                    <div class="guest-spinner" data-input="bookingChildren">
                                                                                        <button type="button" class="btn-guest btn-guest-minus" aria-label="Decrease children" data-target="bookingChildren">−</button>
                                                                                        <input type="number" class="guest-input" id="bookingChildren" name="num_children" min="0" value="0" required />
                                                                                        <button type="button" class="btn-guest btn-guest-plus" aria-label="Increase children" data-target="bookingChildren">+</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                                                                                    <div class="me-3"><strong>Seniors</strong> <span id="priceSenior" class="text-muted small"></span></div>
                                                                                    <div class="guest-spinner" data-input="bookingSeniors">
                                                                                        <button type="button" class="btn-guest btn-guest-minus" aria-label="Decrease seniors" data-target="bookingSeniors">−</button>
                                                                                        <input type="number" class="guest-input" id="bookingSeniors" name="num_seniors" min="0" value="0" required />
                                                                                        <button type="button" class="btn-guest btn-guest-plus" aria-label="Increase seniors" data-target="bookingSeniors">+</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Special Requests (Optional)</strong></label>
                                                                            <textarea class="form-control" id="bookingNotes" name="special_requests" rows="3" placeholder="e.g., dietary requirements, accessibility needs, photography permits..."></textarea>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="summary-card h-100 d-flex flex-column justify-content-between">
                                                                        <div>
                                                                            <div class="d-flex align-items-center mb-3">
                                                                                <i class="bi bi-receipt fs-4 me-2 text-primary"></i>
                                                                                <h6 class="mb-0 fw-bold">Reservation Summary</h6>
                                                                            </div>
                                                                            <div class="summary-lines">
                                                                                <div class="summary-line" id="summaryNightsWrapper" style="display:none;">
                                                                                    <span class="label">Days</span>
                                                                                    <span class="value" id="bookingSummaryNights">1</span>
                                                                                </div>
                                                                                <div class="summary-line">
                                                                                    <span class="label">Adults</span>
                                                                                    <span class="value"><strong id="bookingSummaryAdults">1</strong> <span id="summaryPriceAdult" class="text-muted"></span></span>
                                                                                </div>
                                                                                <div class="summary-line">
                                                                                    <span class="label">Children</span>
                                                                                    <span class="value"><strong id="bookingSummaryChildren">0</strong> <span id="summaryPriceChild" class="text-muted"></span></span>
                                                                                </div>
                                                                                <div class="summary-line">
                                                                                    <span class="label">Seniors</span>
                                                                                    <span class="value"><strong id="bookingSummarySeniors">0</strong> <span id="summaryPriceSenior" class="text-muted"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-3 pt-3 border-top total-row">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <span class="fw-semibold">Total Cost</span>
                                                                                <span class="fw-bold fs-5 text-primary" id="bookingSummaryTotal">₱0</span>
                                                                            </div>
                                                                            <small class="text-muted d-block mt-2">Prices are provisional and may vary on confirmation.</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="confirmBooking()" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); border: none;"><i class="bi bi-check-circle"></i> Reserve</button>
                            </div>
                        </div>
                    </div>
                </div>
         
         <!-- Spot Details Modal -->
                 <div class="modal fade" id="spotModal" tabindex="-1" aria-labelledby="spotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color:#fff; border:none;">
                <h5 class="modal-title" id="spotModalLabel" style="display:flex;align-items:center;gap:.5rem;">
                        <i class="bi bi-compass" style="font-size:1.2rem;"></i>
                        Spot Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <!-- Top: Full-width carousel -->
                <div class="spot-modal-carousel mb-3">
                      <div id="spotModalCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="spotModalImages">
                            <!-- inserted image items dynamically -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#spotModalCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#spotModalCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <!-- Bottom: Description and details -->
                <div class="spot-modal-info">
                    <div class="spot-desc-box">
                        <p id="spotModalDesc"></p>
                    </div>
                    <div class="spot-meta-lines">
                        <div class="spot-meta-chip category"><i class="bi bi-tag"></i><span id="spotModalCategory"></span></div>
                        <div class="spot-meta-chip rating"><i class="bi bi-star-fill"></i><span id="spotModalRating"></span></div>
                    </div>
                    <div id="spotModalDetails" class="spot-details-panel">
                        <!-- dynamically inserted details -->
                    </div>
                    <!-- Reviews Section -->
                    <div id="spotReviewsSection" style="display:none;">
                        <h6 style="margin-top: 1.5rem; margin-bottom: 1rem; color: #004b8d; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-star-fill" style="color: #ffb400;"></i> Guest Reviews
                        </h6>
                        <div id="spotReviewsList" style="display: flex; flex-direction: column; gap: 1rem; max-height: 300px; overflow-y: auto;">
                                <!-- Reviews inserted dynamically -->
                            </div>
                            <div id="spotReviewsControls" style="margin-top:0.75rem; display:flex; justify-content:center;">
                                <button id="spotReviewsLoadMore" type="button" class="btn btn-outline-primary" style="display:none;">View more reviews</button>
                            </div>
                    </div>
                </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

     </div>
     
     <!-- Mobile Menu Button -->
     <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
         <i class="bi bi-list"></i>
     </button>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleUserDropdown() {
            console.log('toggleUserDropdown called');
            const dropdown = document.getElementById('userDropdown');
            console.log('Dropdown element:', dropdown);
            if (dropdown) {
                dropdown.classList.toggle('show');
                console.log('Dropdown show class toggled, current classes:', dropdown.className);
            } else {
                console.error('User dropdown element not found!');
            }
        }

        function handleLogout(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/logout';
            }
        }

        function showToast(title, body) {
            const container = document.getElementById('toastContainer');
            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-bg-primary border-0';
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${body}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>`;
            container.appendChild(toastEl);
            const t = new bootstrap.Toast(toastEl, { delay: 3000 });
            t.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        function setLoading(btn, isLoading) {
            const sp = btn.querySelector('.spinner-border');
            const saveText = btn.querySelector('.save-text');
            if (isLoading) {
                sp.classList.remove('d-none');
                btn.disabled = true;
                saveText.textContent = 'Saving...';
            } else {
                sp.classList.add('d-none');
                btn.disabled = false;
                saveText.textContent = 'Save Changes';
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const userDropdown = document.getElementById('userDropdown');
            const userAvatar = document.querySelector('.user-avatar');
            
            // Close sidebar on mobile
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }

            // Close user dropdown
            if (userDropdown && userAvatar && !userAvatar.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
            }
        });

        function toggleUserDropdown() {
            const notifDropdown = document.getElementById('notificationDropdown');
            const dropdown = document.getElementById('userDropdown');
            notifDropdown?.classList.remove('show');
            dropdown?.classList.toggle('show');
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

        function markAllAsRead() {
            const items = document.querySelectorAll('.notification-item.unread');
            items.forEach(item => item.classList.remove('unread'));
            const badge = document.querySelector('.notification-badge');
            if (badge) badge.textContent = '0';
        }

        function viewAllNotifications(event) {
            event.preventDefault();
            // Navigate to notifications page or show all notifications
        }


        
        async function toggleFavorite(button) {
            // toggle UI immediately
            const card = button.closest('.spot-card');
            const spotId = button.dataset.spotId || card?.dataset.spotId || '';
            const spotName = card?.querySelector('.spot-title')?.textContent || '';
            if (!spotId) {
                // fallback to UI-only behavior
                button.classList.toggle('active');
                showToast('Favorites', (button.classList.contains('active') ? spotName + ' added to favorites!' : spotName + ' removed from favorites.'));
                return;
            }

            const isNowFavorite = !button.classList.contains('active');
            // optimistic UI
            if (isNowFavorite) button.classList.add('active'); else button.classList.remove('active');

            try {
                const res = await fetch('/tourist/toggleFavorite', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ spot_id: spotId, action: isNowFavorite ? 'add' : 'remove' })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data?.error || 'Failed to update favorite');
                showToast('Favorites', data.message || (isNowFavorite ? spotName + ' added to favorites!' : spotName + ' removed from favorites.'));
                
                // If removing from favorites section, remove the card
                if (!isNowFavorite) {
                    const favoriteCard = card.closest('.col-12');
                    if (favoriteCard && favoriteCard.closest('#favoritesSection')) {
                        favoriteCard.remove();
                        
                        // If no favorites left, show a message
                        const remainingFavorites = document.querySelectorAll('#favoritesSection .spot-card').length;
                        if (remainingFavorites === 0) {
                            const favoritesGrid = document.querySelector('#favoritesSection .favorites-grid');
                            if (favoritesGrid) {
                                favoritesGrid.innerHTML = '<div class="col-12"><p class="text-center text-muted">No favorite spots yet. Click the heart icon on any spot to add it to your favorites!</p></div>';
                            }
                        }
                    }
                }
            } catch (err) {
                console.error(err);
                // revert optimistic UI
                if (isNowFavorite) button.classList.remove('active'); else button.classList.add('active');
                showToast('Favorites', 'Failed to update favorites.');
            }
        }

            // Show modal populated from the clicked card
    function viewDetails(btn) {
        const card = btn.closest('.spot-card');
        if (!card) return;

        const spotId = card.dataset.spotId;
        if (!spotId) return;

        // Show loading state in modal
        const modalLabel = document.getElementById('spotModalLabel');
        const descEl = document.getElementById('spotModalDesc');
        const categoryEl = document.getElementById('spotModalCategory');
        const ratingEl = document.getElementById('spotModalRating');
        const imagesContainer = document.getElementById('spotModalImages');
        const detailsEl = document.getElementById('spotModalDetails'); // extra details container
        modalLabel.innerHTML = '<i class="bi bi-compass"></i> Loading...';
        descEl.textContent = 'Loading...';
        categoryEl.textContent = '';
        ratingEl.textContent = '';
        detailsEl.innerHTML = '';
        imagesContainer.innerHTML = '<div class="carousel-item active"><div class="d-flex align-items-center justify-content-center" style="height:240px;background:#f6f6f6;border-radius:8px;"><div class="spinner-border text-primary" role="status"></div></div></div>';

        // Show modal immediately (with loading)
        const modalEl = document.getElementById('spotModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        fetch(`/tourist/viewSpot/${spotId}`)
            .then(res => res.json())
            .then(data => {
                if (!data || !data.spot) throw new Error('Spot not found');
                const spot = data.spot;
                const gallery = data.gallery || [];

                // Fill main modal content
                modalLabel.innerHTML = `<i class="bi bi-compass"></i> ${spot.spot_name}`;
                descEl.textContent = spot.description || '';
                categoryEl.textContent = spot.category || '';
                ratingEl.textContent = spot.rating !== undefined ? spot.rating : '';

                // Build carousel items
                imagesContainer.innerHTML = '';
                let hasImage = false;
                if (gallery.length > 0) {
                    gallery.forEach((img, idx) => {
                        const item = document.createElement('div');
                        item.className = 'carousel-item' + (idx === 0 ? ' active' : '');
                        item.innerHTML = `<img src="${img.image_url}" class="d-block w-100 rounded kb-img" alt="Spot Image">`;
                        imagesContainer.appendChild(item);
                        hasImage = true;
                    });
                }
                if (!hasImage) {
                    let imgUrl = spot.primary_image 
                        ? `/uploads/spots/${spot.primary_image}` 
                        : '/uploads/spots/Spot-No-Image.png';

                    const item = document.createElement('div');
                    item.className = 'carousel-item active';
                    item.innerHTML = `
                        <img src="${imgUrl}" 
                            onerror="this.src='/uploads/spots/Spot-No-Image.png'" 
                            class="d-block w-100 rounded kb-img" 
                            alt="Spot Image">
                    `;
                    imagesContainer.appendChild(item);
                }
                                // Modern details layout (glass cards + icons)
                                detailsEl.innerHTML = `
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="detail-icon gradient"><i class="bi bi-geo-alt"></i></div>
                                            <div class="detail-content">
                                                <div class="detail-label">Location</div>
                                                <div class="detail-value">${spot.location || '—'}</div>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-icon gradient"><i class="bi bi-clock"></i></div>
                                            <div class="detail-content">
                                                <div class="detail-label">Visiting Hours</div>
                                                <div class="detail-value">${(spot.opening_time || '—') + ' - ' + (spot.closing_time || '—')}</div>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-icon gradient"><i class="bi bi-cash-stack"></i></div>
                                            <div class="detail-content">
                                                <div class="detail-label">Pricing</div>
                                                <div class="pricing-tags">
                                                    <span class="price-pill">Adult: ${spot.price_per_person ? '₱' + parseFloat(spot.price_per_person).toLocaleString() : '—'}</span>
                                                    <span class="price-pill">Child: ${spot.child_price ? '₱' + parseFloat(spot.child_price).toLocaleString() : '—'}</span>
                                                    <span class="price-pill">Senior: ${spot.senior_price ? '₱' + parseFloat(spot.senior_price).toLocaleString() : '—'}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                // Fetch and display reviews for this spot
                fetchSpotReviews(spotId);
        
        
            })
            .catch(err => {
                modalLabel.innerHTML = '<i class="bi bi-compass"></i> Spot Details';
                descEl.textContent = 'Failed to load spot details.';
                categoryEl.textContent = '';
                ratingEl.textContent = '';
                detailsEl.innerHTML = '';
                imagesContainer.innerHTML = '<div class="carousel-item active"><div class="d-flex align-items-center justify-content-center" style="height:240px;background:#f6f6f6;border-radius:8px;"><i class="bi bi-exclamation-triangle text-danger fs-1"></i></div></div>';
            });
    }

        // Current logged-in user id (if any) - used to highlight their review
    const CURRENT_USER_ID = <?= json_encode($session->get('UserID') ?? null) ?>;
    const CURRENT_USER_EMAIL = <?= json_encode($session->get('Email') ?? null) ?>;

        // Fetch and display reviews for a spot (renders testimonial-style cards)
    function fetchSpotReviews(spotId, offset = 0) {
        const reviewsSection = document.getElementById('spotReviewsSection');
        const reviewsList = document.getElementById('spotReviewsList');
        const loadMoreBtn = document.getElementById('spotReviewsLoadMore');
        
        if (!reviewsSection || !reviewsList) return;
        
        // Set current spot id/offset on section for subsequent loads
        reviewsSection.dataset.spotId = spotId;
        reviewsSection.dataset.offset = offset;
        const pageSize = 10;

        // If offset is zero, clear current list (fresh load). Otherwise we'll append.
        if (offset === 0) {
            reviewsList.innerHTML = '<div class="text-center text-muted">Loading reviews...</div>';
            reviewsSection.style.display = 'none';
        } else {
            // show loading state on button
            if (loadMoreBtn) { loadMoreBtn.disabled = true; loadMoreBtn.textContent = 'Loading...'; loadMoreBtn.style.display = 'inline-block'; }
        }

        fetch(`/tourist/spot/${spotId}/reviews?limit=${pageSize}&offset=${offset}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.reviews)) {
                    const reviews = data.reviews;
                    if (reviews.length === 0) {
                        // If first page and no reviews, show empty state
                        if (offset === 0) {
                            reviewsSection.style.display = 'block';
                            reviewsList.innerHTML = '<div class="text-center text-muted">No reviews yet. Be the first to review this place.</div>';
                        }
                        return;
                    }

                    reviewsSection.style.display = 'block';

                    // If the API provided the current user's review, move it to the top
                    let userIndex = -1;
                    for (let i = 0; i < reviews.length; i++) {
                        const r = reviews[i];
                        const reviewerId = r.customer_id ?? r.user_id ?? r.userId ?? r.customerId ?? null;
                        const reviewerEmail = (r.customer_email || r.email || '').toLowerCase();
                        if (CURRENT_USER_ID && reviewerId && String(reviewerId) === String(CURRENT_USER_ID)) { userIndex = i; break; }
                        if (CURRENT_USER_EMAIL && reviewerEmail && String(reviewerEmail) === String((CURRENT_USER_EMAIL||'').toLowerCase())) { userIndex = i; break; }
                    }
                    if (userIndex > 0) {
                        const userReview = reviews.splice(userIndex, 1)[0];
                        reviews.unshift(userReview);
                    }

                    // Build markup for this page
                    const markup = reviews.map((review, idx) => {
                        const isMyReview = (idx === 0) && (CURRENT_USER_ID && (String(review.customer_id ?? review.user_id ?? review.userId ?? review.customerId ?? '') === String(CURRENT_USER_ID)));
                        const name = escapeHtml(review.customer_name || 'Anonymous');
                        const initials = name.split(' ').map(s => s.charAt(0)).filter(Boolean).slice(0,2).join('').toUpperCase();
                        const comment = escapeHtml(review.comment || '');
                        const commentHtml = comment.replace(/\n/g, '<br>');
                        const rating = parseInt(review.rating) || 0;

                        // Build star markup (filled + empty up to 5)
                        let stars = '';
                        for (let i = 1; i <= 5; i++) {
                            if (i <= rating) stars += '<i class="bi bi-star-fill" style="color: #ffb400;"></i>';
                            else stars += '<i class="bi bi-star" style="color: #ddd;"></i>';
                        }

                        const recommendHtml = (review.recommend === 1)
                            ? '<span class="badge bg-success ms-1"><i class="bi bi-hand-thumbs-up-fill"></i> Recommends</span>'
                            : (review.recommend === 0 ? '<span class="badge bg-danger ms-1"><i class="bi bi-hand-thumbs-down-fill"></i> Does not recommend</span>' : '');

                        return `
                            <div class="card mb-3 ${isMyReview ? 'my-review' : ''}" style="border:1px solid ${isMyReview ? 'rgba(0,123,255,0.18)' : 'rgba(0,75,141,0.06)'};">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3" style="width:48px; height:48px; border-radius:50%; background:#e9f2fb; display:flex; align-items:center; justify-content:center; font-weight:700; color:#004b8d;">${initials}</div>
                                        <div style="flex:1;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div style="font-weight:600; color:#004b8d;">${name} ${isMyReview ? '<span class="badge bg-primary ms-2" style="font-size:.7rem;">Your review</span>' : ''}</div>
                                                    <div style="font-size:0.85rem; color:#666;">${formatDate(review.created_at)}</div>
                                                </div>
                                                <div style="text-align:right;">
                                                    <div style="line-height:1">${stars}</div>
                                                    ${recommendHtml}
                                                </div>
                                            </div>
                                            <div style="margin-top:0.5rem; color:#333; font-size:0.95rem;">${commentHtml}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');

                    if (offset === 0) {
                        reviewsList.innerHTML = markup;
                    } else {
                        // append
                        reviewsList.insertAdjacentHTML('beforeend', markup);
                    }

                    // handle load more visibility
                    const hasMore = data.has_more === true;
                    if (loadMoreBtn) {
                        if (hasMore) {
                            loadMoreBtn.style.display = 'inline-block';
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.textContent = 'View more reviews';
                            loadMoreBtn.onclick = function() {
                                const nextOffset = offset + pageSize;
                                fetchSpotReviews(spotId, nextOffset);
                            };
                        } else {
                            loadMoreBtn.style.display = 'none';
                        }
                    }
                } else {
                    reviewsSection.style.display = 'block';
                    reviewsList.innerHTML = '<div class="text-center text-muted">No reviews available.</div>';
                }
            })
            .catch(err => {
                console.error('Error fetching reviews:', err);
                reviewsSection.style.display = 'block';
                if (offset === 0) {
                    reviewsList.innerHTML = '<div class="text-center text-danger">Failed to load reviews.</div>';
                }
                const loadMoreBtn = document.getElementById('spotReviewsLoadMore');
                if (loadMoreBtn) { loadMoreBtn.style.display = 'none'; }
            });
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        if (text === null || text === undefined) return '';
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function formatDate(isoDate) {
        try {
            const date = new Date(isoDate);
            return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
        } catch {
            return isoDate;
        }
    }
        

        // --- BOOKING FUNCTIONS ---
        // Pass PHP spot data to JS
        const spotDataMap = {};
        <?php foreach ($spots as $spot): ?>
            spotDataMap[<?= json_encode((string)($spot['spot_id'] ?? $spot['id'])) ?>] = <?= json_encode([
                'spot_id' => $spot['spot_id'] ?? $spot['id'],
                'spot_name' => $spot['spot_name'],
                'price_per_person' => $spot['price_per_person'],
                'child_price' => $spot['child_price'],
                'senior_price' => $spot['senior_price'],
                'description' => $spot['description'],
                'category' => $spot['category'],
                'location' => $spot['location'],
                'opening_time' => $spot['opening_time'] ?? null,
                'closing_time' => $spot['closing_time'] ?? null,
                'primary_image' => $spot['primary_image'],
            ]) ?>;
        <?php endforeach; ?>

        let currentSpotPrices = { adult: 0, child: 0, senior: 0 };

        // Function to populate visit time dropdown based on operating hours
        function populateVisitTimeOptions(openingTime, closingTime) {
            const timeSelect = document.getElementById('bookingStartTime');
            if (!timeSelect) return;
            
            // Clear existing options except the first one
            timeSelect.innerHTML = '<option value="">Select time</option>';
            
            if (!openingTime || !closingTime) {
                timeSelect.innerHTML = '<option value="">Operating hours not available</option>';
                return;
            }
            
            // Parse opening and closing times (format: "HH:MM:SS" or "HH:MM")
            const parseTime = (timeStr) => {
                const parts = timeStr.split(':');
                return {
                    hours: parseInt(parts[0]),
                    minutes: parseInt(parts[1] || 0)
                };
            };
            
            const opening = parseTime(openingTime);
            const closing = parseTime(closingTime);
            
            // Generate time slots every 30 minutes
            let currentHour = opening.hours;
            let currentMinute = opening.minutes;
            
            while (currentHour < closing.hours || (currentHour === closing.hours && currentMinute <= closing.minutes)) {
                const hourStr = currentHour.toString().padStart(2, '0');
                const minuteStr = currentMinute.toString().padStart(2, '0');
                const timeValue = `${hourStr}:${minuteStr}:00`;
                const timeDisplay = `${hourStr}:${minuteStr}`;
                
                const option = document.createElement('option');
                option.value = timeValue;
                option.textContent = timeDisplay;
                timeSelect.appendChild(option);
                
                // Increment by 30 minutes
                currentMinute += 30;
                if (currentMinute >= 60) {
                    currentMinute = 0;
                    currentHour++;
                }
                
                // Break if we've gone past closing time
                if (currentHour > closing.hours || (currentHour === closing.hours && currentMinute > closing.minutes)) {
                    break;
                }
            }
        }

                function bookSpot(spotId, spotName, btn) {
                        const spot = spotDataMap[spotId];
                        if (!spot) return;
                        const form = document.getElementById('bookingForm');
                        if (form) form.reset();
                        document.getElementById('bookingSpotId').value = spotId;
                        document.getElementById('bookingSpotDisplay').value = spot.spot_name;
                    // Initialize default date range (today -> tomorrow)
                    const today = new Date();
                    const tomorrow = new Date(Date.now() + 24*60*60*1000);
                    const startStr = today.toISOString().split('T')[0];
                    const endStr = tomorrow.toISOString().split('T')[0];
                    const startInput = document.getElementById('bookingDateStart');
                    const endInput = document.getElementById('bookingDateEnd');
                    const rangeInput = document.getElementById('bookingDateRange');
                    if (startInput) startInput.value = startStr;
                    if (endInput) endInput.value = endStr;
                    if (rangeInput) rangeInput.value = startStr + ' to ' + endStr;
                    if (window.dateRangePicker) {
                        window.dateRangePicker.setDate([today, tomorrow], true);
                    }
                    const hiddenDate = document.getElementById('bookingDate');
                    if (hiddenDate) hiddenDate.value = startStr;
                        document.getElementById('bookingAdults').value = 1;
                        document.getElementById('bookingChildren').value = 0;
                        document.getElementById('bookingSeniors').value = 0;
                        document.getElementById('priceAdult').textContent = `₱${parseFloat(spot.price_per_person).toLocaleString()}`;
                        document.getElementById('priceChild').textContent = `₱${parseFloat(spot.child_price).toLocaleString()}`;
                        document.getElementById('priceSenior').textContent = `₱${parseFloat(spot.senior_price).toLocaleString()}`;
                        currentSpotPrices = {
                            adult: parseFloat(spot.price_per_person),
                            child: parseFloat(spot.child_price),
                            senior: parseFloat(spot.senior_price)
                        };
                        
                        // Populate visit time dropdown based on operating hours
                        populateVisitTimeOptions(spot.opening_time, spot.closing_time);
                        
                        updateBookingSummary();
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).show();
                }

        function updateBookingSummary() {
            const numAdults = parseInt(document.getElementById('bookingAdults').value) || 0;
            const numChildren = parseInt(document.getElementById('bookingChildren').value) || 0;
            const numSeniors = parseInt(document.getElementById('bookingSeniors').value) || 0;
            // Determine number of days based on date range (minimum 1)
            const start = document.getElementById('bookingDateStart')?.value;
            const end = document.getElementById('bookingDateEnd')?.value;
            let days = 1;
            if (start && end) {
                const startDate = new Date(start + 'T00:00:00');
                const endDate = new Date(end + 'T00:00:00');
                const diff = Math.round((endDate - startDate) / (1000*60*60*24));
                // Use diff (nights) as days; if diff < 1 keep 1
                days = diff >= 1 ? diff : 1;
            }
            const baseTotal = (numAdults * currentSpotPrices.adult) + (numChildren * currentSpotPrices.child) + (numSeniors * currentSpotPrices.senior);
            const total = baseTotal * days;
            document.getElementById('bookingSummaryAdults').textContent = numAdults;
            document.getElementById('bookingSummaryChildren').textContent = numChildren;
            document.getElementById('bookingSummarySeniors').textContent = numSeniors;
            document.getElementById('summaryPriceAdult').textContent = numAdults > 0 ? `x ₱${currentSpotPrices.adult.toLocaleString()}` : '';
            document.getElementById('summaryPriceChild').textContent = numChildren > 0 ? `x ₱${currentSpotPrices.child.toLocaleString()}` : '';
            document.getElementById('summaryPriceSenior').textContent = numSeniors > 0 ? `x ₱${currentSpotPrices.senior.toLocaleString()}` : '';
            document.getElementById('bookingSummaryTotal').textContent = '₱' + total.toLocaleString();
            const wrapper = document.getElementById('summaryNightsWrapper');
            if (wrapper) {
                if (start && end) {
                    wrapper.style.display = 'flex';
                    const nightsEl = document.getElementById('bookingSummaryNights');
                    if (nightsEl) nightsEl.textContent = days;
                } else {
                    wrapper.style.display = 'none';
                }
            }
        }

        // Update summary on input changes & init date range picker
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                ['bookingAdults','bookingChildren','bookingSeniors'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', updateBookingSummary);
                });
                // Guest spinner buttons
                document.querySelectorAll('.btn-guest').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const targetId = btn.getAttribute('data-target');
                        const input = document.getElementById(targetId);
                        if (!input) return;
                        let val = parseInt(input.value) || 0;
                        if (btn.classList.contains('btn-guest-minus')) {
                            val = Math.max(0, val - 1);
                        } else if (btn.classList.contains('btn-guest-plus')) {
                            val = val + 1;
                        }
                        input.value = val;
                        updateBookingSummary();
                    });
                });
            }
            // Initialize Flatpickr range if available
            if (window.flatpickr) {
                window.dateRangePicker = flatpickr('#bookingDateRange', {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    onChange: function(selectedDates) {
                        const startEl = document.getElementById('bookingDateStart');
                        const endEl = document.getElementById('bookingDateEnd');
                        const hiddenDate = document.getElementById('bookingDate');
                        if (selectedDates.length > 0 && startEl) {
                            const sd = selectedDates[0].toISOString().split('T')[0];
                            startEl.value = sd;
                            if (hiddenDate) hiddenDate.value = sd; // keep legacy field
                        }
                        if (selectedDates.length > 1 && endEl) {
                            endEl.value = selectedDates[1].toISOString().split('T')[0];
                        }
                        updateBookingSummary();
                    }
                });
            }
        });

        
         // Filter functionality
         document.querySelectorAll('.filter-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                // Update active state for filter buttons
                document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                const spots = document.querySelectorAll('.spots-grid .spot-card');

                spots.forEach(spot => {
                    const cat = (spot.getAttribute('data-category') || '').toLowerCase();
                    if (filter === 'all' || cat === filter) {
                        spot.style.display = 'block';
                    } else {
                        spot.style.display = 'none';
                    }
                });
            });
        });
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const spots = document.querySelectorAll('.spots-grid .spot-card');
             
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

        // Check if viewSpot parameter exists in URL and auto-open modal
        const urlParams = new URLSearchParams(window.location.search);
        const viewSpotId = urlParams.get('viewSpot');
        
        if (viewSpotId) {
            // Find the spot card with this ID
            const spotCard = document.querySelector(`.spot-card[data-spot-id="${viewSpotId}"]`);
            if (spotCard) {
                // Scroll to the spot
                spotCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Wait a bit for scroll, then open the modal
                setTimeout(() => {
                    const viewBtn = spotCard.querySelector('.btn-view');
                    if (viewBtn) {
                        viewBtn.click();
                    }
                }, 500);
            }
        }
    </script>
    <script>
        // Override confirmBooking with server-backed implementation
        async function confirmBooking() {
            const spotId = document.getElementById('bookingSpotId')?.value || null;
            const spotName = document.getElementById('bookingSpotDisplay')?.value || '';
            const startDate = document.getElementById('bookingDateStart')?.value || '';
            const endDate = document.getElementById('bookingDateEnd')?.value || '';
            const hiddenDate = document.getElementById('bookingDate'); // mirrors start
            if (hiddenDate) hiddenDate.value = startDate;
            const numAdults = parseInt(document.getElementById('bookingAdults')?.value) || 0;
            const numChildren = parseInt(document.getElementById('bookingChildren')?.value) || 0;
            const numSeniors = parseInt(document.getElementById('bookingSeniors')?.value) || 0;
            const startTime = document.getElementById('bookingStartTime')?.value || '';
            const notes = document.getElementById('bookingNotes')?.value || '';

            if (!startDate || !spotId || (numAdults + numChildren + numSeniors) < 1) {
                alert('Please select a start date and at least one guest.');
                return;
            }
            if (!startTime) {
                alert('Please select a visit time.');
                return;
            }
            if (endDate && endDate < startDate) {
                alert('End date cannot be before start date.');
                return;
            }

            const totalPrice = (numAdults * currentSpotPrices.adult) + (numChildren * currentSpotPrices.child) + (numSeniors * currentSpotPrices.senior);
            const payload = {
                spot_id: spotId,
                visit_date: startDate,
                end_date: endDate || null,
                visit_time: startTime || null,
                num_adults: numAdults,
                num_children: numChildren,
                num_seniors: numSeniors,
                total_guests: numAdults + numChildren + numSeniors,
                special_requests: notes || null,
                total_price: totalPrice
            };

            try {
                const res = await fetch('/tourist/createBooking', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data?.error || 'Booking failed');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();
                // Booking records are created with status 'Pending' until payment is completed
                showToast('Reservation Pending', spotName + ' reservation created and is pending payment.' + (endDate ? (' (from ' + startDate + ' to ' + endDate + ')') : (' (on ' + startDate + ')')) );

                // If server is configured for hosted checkout, start the hosted flow immediately
                if (window.PAYMONGO_HOSTED) {
                    try {
                        const piResp = await fetch('/tourist/createPaymentIntent', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ booking_id: data.booking_id, amount: payload.total_price, method: 'hosted' })
                        });
                        const piData = await piResp.json();
                        if (piResp.ok && piData.checkout_url) {
                            // Redirect user to provider-hosted checkout
                            window.location.href = piData.checkout_url;
                            return;
                        } else {
                            console.warn('createPaymentIntent failed', piData);
                            showToast('Payment', 'Unable to start hosted checkout. You can complete payment from My Bookings.');
                        }
                    } catch (err) {
                        console.error('Hosted checkout error', err);
                        showToast('Payment', 'Hosted checkout failed to start. Please try again or pay from My Bookings.');
                    }
                }
            } catch (err) {
                console.error(err);
                alert('Reservation failed: ' + (err.message || 'unknown error'));
            }
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