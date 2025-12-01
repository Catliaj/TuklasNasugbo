<?php
// --- Top PHP block (replace the existing session code near the top) ---
$session = session();
$userFirstName = $session->get('FirstName') ?? '';
$userLastName  = $session->get('LastName') ?? '';
$userEmail     = $session->get('Email') ?? '';
$userInitials  = strtoupper(substr($userFirstName,0,1) . substr($userLastName,0,1));

// Ensure FullName variable is defined (avoid undefined var usage)
$FullName = trim(($userFirstName ?: '') . ' ' . ($userLastName ?: ''));
// Role and preference helpers (used by the preference modal)
$roleVal = $session->get('Role') ?? $session->get('role') ?? $session->get('user_role') ?? '';
$isTourist = strcasecmp($roleVal, 'tourist') === 0;
$userPreference = $userPreference ?? null;
$hasPref = !empty($userPreference);
?>
...




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
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/dashboard.css')?>">
<?php
// Get session values for profile display
$session = session();
$userFirstName = $session->get('FirstName') ?? '';
$userLastName = $session->get('LastName') ?? '';
$userEmail = $session->get('Email') ?? '';
$userInitials = strtoupper(substr($userFirstName,0,1) . substr($userLastName,0,1));
?>
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
                <button class="tourist-sidebar-toggle d-lg-none" onclick="toggleMobileSidebar()">
                    <i class="bi bi-x fs-3"></i>
                </button>
            </div>
            
            <nav class="tourist-sidebar-nav">
                <ul class="tourist-nav-menu">
                    <li class="tourist-nav-item">
                        <a href="/tourist/dashboard" class="tourist-nav-link active">
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
                    <!-- My Reviews link removed (reviews integrated into Visited/Explore) -->
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Ocean Wave Header -->
            <div class="page-header">
                <div class="page-header-actions">
                    <!-- Notification Button -->
                    <div style="position: relative;">
                        <button class="notification-btn" onclick="toggleNotificationDropdown()">
                            <i class="bi bi-bell-fill"></i>
                            <span class="notification-badge" id="notifBadge">3</span>
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h6>Notifications</h6>
                                <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
                            </div>
                            <ul class="notification-list" id="notificationList">
                                <li class="notification-item unread" onclick="openNotificationDetail(this)" style="cursor:pointer;">
                                    <div class="notification-content">
                                        <div class="notification-icon success">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6>Booking Confirmed</h6>
                                            <p>Your booking at Canyon Cove has been confirmed for Dec 20, 2025</p>
                                            <div class="notification-time">2 hours ago</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item unread">
                                    <div class="notification-content">
                                        <div class="notification-icon info">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6>Review Reminder</h6>
                                            <p>Don't forget to review your visit to Mount Batulao!</p>
                                            <div class="notification-time">1 day ago</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item unread">
                                    <div class="notification-content">
                                        <div class="notification-icon warning">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6>Itinerary Update</h6>
                                            <p>Your 3-Day Beach Adventure itinerary starts in 3 days</p>
                                            <div class="notification-time">2 days ago</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item">
                                    <div class="notification-content">
                                        <div class="notification-icon info">
                                            <i class="bi bi-heart-fill"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6>New Spot Added</h6>
                                            <p>Fortune Island has been added to your favorites</p>
                                            <div class="notification-time">3 days ago</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item">
                                    <div class="notification-content">
                                        <div class="notification-icon success">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6>Check-in Successful</h6>
                                            <p>You checked in at Kaybiang Tunnel</p>
                                            <div class="notification-time">1 week ago</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="notification-footer">
                                <a href="#" onclick="viewAllNotifications(event)">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Avatar -->
                    <div class="user-avatar" onclick="toggleUserDropdown()"><?= $userInitials ?? 'JD' ?></div>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <h6><?= esc(($userFirstName ?? 'Juan') . ' ' . ($userLastName ?? 'Dela Cruz')) ?></h6>
                            <p><?= esc($userEmail ?? 'juan.delacruz@email.com') ?></p>
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
                <h2><i class="bi bi-house-door-fill"></i> Welcome back, <?= esc($FullName ?? 'Traveler') ?>!</h2>
                <p>Ready to explore Nasugbu today?</p>
            </div>
             <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><span class="count-up" id="itineraryCount" data-target="<?= isset($TotalSaveItineray) ? intval($TotalSaveItineray) : 0 ?>">0</span></div>
                            <div class="stat-label">Saved Itineraries</div>
                        </div>
                        <!-- preference modal moved to end-of-body for correct positioning -->
                        <div class="stat-icon blue">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><span class="count-up" id="visitedCount" data-target="<?= isset($placesVisited) ? intval($placesVisited) : 0 ?>">0</span></div>
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
                            <div class="stat-value"><span class="count-up" id="favoriteCount" data-target="<?= isset($favoriteCount) ? intval($favoriteCount) : 0 ?>">0</span></div>
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
                            <div class="stat-value"><span class="count-up" id="bookingCount" data-target="<?= isset($upcomingBookings) ? intval($upcomingBookings) : 0 ?>">0</span></div>
                            <div class="stat-label">Upcoming Bookings</div>
                        </div>
                        <div class="stat-icon purple">
                            <i class="bi bi-bookmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Weather Widget and Quick Tip -->
            <div class="row g-3 mb-4">
                <!-- Weather Widget -->
                <div class="col-md-6">
                    <div class="stat-card" style="height:100%;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-sun-fill" style="color:#fdb813;"></i> 
                                <span>Nasugbu Weather</span>
                            </h5>
                        </div>
                        <div class="weather-info" id="weatherWidget">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading weather data...</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Tip -->
                <div class="col-md-6">
                    <div class="stat-card" style="height:100%;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:white;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-lightbulb-fill" style="font-size:1.5rem;"></i>
                            <h5 class="mb-0">Travel Tip of the Day</h5>
                        </div>
                        <div id="quickTip">
                            <p class="mb-2" style="font-size:1rem;line-height:1.6;" id="tipText">
                                Start your beach adventures early in the morning to avoid crowds and enjoy the peaceful sunrise views!
                            </p>
                            <small style="opacity:0.9;">
                                <i class="bi bi-info-circle"></i> Pro tip from experienced travelers
                            </small>
                        </div>
                    </div>
                </div>
            </div>

           

            <!-- Your Favorites -->
            <div class="recent-activity">
                <h3 class="d-flex align-items-center gap-2">
                    <i class="bi bi-heart-fill" style="color:#ff5d5d;"></i> Your Favorites
                </h3>
                
                <div id="favoritesList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                    <?php if (!empty($favoriteSpots) && count($favoriteSpots) > 0): ?>
                        <?php foreach (array_slice($favoriteSpots, 0, 6) as $spot): ?>
                            <?php 
                                $imagePath = 'uploads/spots/' . ($spot['primary_image'] ?? '');
                                if (empty($spot['primary_image']) || !is_file(FCPATH . $imagePath)) { 
                                        $imagePath = 'uploads/spots/Spot-No-Image.png';
                                }
                            ?>
                            <div class="favorite-mini-card" 
                                 data-spot-id="<?= esc($spot['id'] ?? $spot['spot_id'] ?? '') ?>"
                                 data-spot-name="<?= esc($spot['spot_name'] ?? $spot['name'] ?? '') ?>"
                                 data-category="<?= esc($spot['category'] ?? '') ?>"
                                 data-rating="<?= esc($spot['rating'] ?? '4.5') ?>"
                                 data-description="<?= esc($spot['description'] ?? '') ?>"
                                 data-location="<?= esc($spot['location'] ?? '') ?>"
                                 data-image="<?= base_url($imagePath) ?>"
                                 onclick="viewFavoriteDetails(this)"
                                 style="cursor:pointer;background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:all 0.3s;">
                                <div style="background-image:url('<?= base_url($imagePath) ?>');background-size:cover;background-position:center;height:120px;position:relative;">
                                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.7),transparent);padding:0.5rem;">
                                        <div style="color:white;font-size:0.75rem;font-weight:600;"><?= esc($spot['spot_name'] ?? $spot['name'] ?? '') ?></div>
                                    </div>
                                </div>
                                <div style="padding:0.75rem;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.75rem;color:#666;">
                                        <span><i class="bi bi-geo-alt"></i> <?= esc($spot['category'] ?? '') ?></span>
                                        <span><i class="bi bi-star-fill" style="color:#ffc107;"></i> <?= esc($spot['rating'] ?? '4.5') ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="activity-item" id="noFavoritesPlaceholder">
                            <div class="activity-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div class="activity-content">
                                <h6>No Favorites Yet</h6>
                                <p>Start exploring and add your favorite spots!</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular Spots Gallery -->
            <div class="spots-gallery">
                <h3><i class="bi bi-fire"></i> Popular Spots in Nasugbu</h3>
                <div class="gallery-grid">
                    <?php if (!empty($popularSpots) && is_array($popularSpots)): ?>
                        <?php foreach ($popularSpots as $spot): ?>
                            <div class="gallery-card">
                                <?php
                                    // Determine image path: prefer uploaded image in `upload/spots/` (or `uploads/spots/`),
                                    // fallback to `upload/no-image.png` in project root and finally a remote placeholder.
                                    $imgFile = $spot['primary_image'] ?? '';
                                    $imgUrl = '';
                                    if ($imgFile) {
                                        // try both common folders
                                        $localPath1 = FCPATH . 'upload/spots/' . $imgFile;
                                        $localPath2 = FCPATH . 'uploads/spots/' . $imgFile;
                                        if (is_file($localPath1)) {
                                            $imgUrl = base_url('upload/spots/' . $imgFile);
                                        } elseif (is_file($localPath2)) {
                                            $imgUrl = base_url('uploads/spots/' . $imgFile);
                                        }
                                    }
                                    if (empty($imgUrl)) {
                                        // default local no-image
                                        $noImgPath1 = FCPATH . 'upload/no-image.png';
                                        $noImgPath2 = FCPATH . 'uploads/no-image.png';
                                        if (is_file($noImgPath1)) $imgUrl = base_url('upload/no-image.png');
                                        elseif (is_file($noImgPath2)) $imgUrl = base_url('uploads/no-image.png');
                                        else $imgUrl = 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=500&h=300&fit=crop';
                                    }
                                ?>
                                <img src="<?= esc($imgUrl) ?>" alt="<?= esc($spot['spot_name'] ?? '') ?>" class="gallery-image">
                                <div class="gallery-badge"><?= esc($spot['category'] ?? 'Popular') ?></div>
                                <div class="gallery-overlay">
                                    <div class="gallery-title"><?= esc($spot['spot_name'] ?? $spot['name'] ?? 'Spot') ?></div>
                                    <div class="gallery-location">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span><?= esc($spot['location'] ?? '') ?></span>
                                    </div>
                                    <div class="gallery-views" title="Total views">
                                        <i class="bi bi-eye-fill"></i>
                                        <span class="views-count"><?= esc($spot['views'] ?? 0) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback static cards if no data -->
                        <div class="gallery-card">
                            <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=500&h=300&fit=crop" alt="Fortune Island" class="gallery-image">
                            <div class="gallery-badge">Popular</div>
                            <div class="gallery-overlay">
                                <div class="gallery-title">Fortune Island</div>
                                <div class="gallery-location">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Nasugbu, Batangas</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Explore Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="exploreOffcanvas" aria-labelledby="exploreLabel">
      <div class="offcanvas-header">
        <h5 id="exploreLabel">Explore Spots</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <input type="text" class="form-control mb-3" id="exploreSearch" placeholder="Search spots...">
        <ul class="list-group" id="exploreList"></ul>
      </div>
    </div>

    <!-- Create Itinerary Modal -->
        <!-- Category preference modal (moved here so Bootstrap can center it correctly) -->
        <?php if ($isTourist && !$hasPref): ?>
        <div class="modal fade" id="prefModal" tabindex="-1" aria-labelledby="prefModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="prefModalLabel">Choose up to 5 categories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Select up to five categories to personalize recommendations.</p>
                        <div id="prefList" class="d-flex flex-wrap gap-2">
                            <?php
                                $categories = ['Historical', 'Cultural', 'Natural', 'Recreational', 'Religious', 'Adventure', 'Ecotourism', 'Urban', 'Rural' ,'Beach' ,'Mountain' ,'Resort', 'Park', 'Restaurant'];
                                foreach ($categories as $cat):
                            ?>
                                <div class="form-check me-3 mb-2">
                                    <input class="form-check-input pref-checkbox" type="checkbox" value="<?= esc($cat) ?>" id="pref_<?= esc($cat) ?>">
                                    <label class="form-check-label" for="pref_<?= esc($cat) ?>"><?= esc($cat) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="prefError" class="text-danger mt-2" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Skip</button>
                        <button type="button" id="savePrefsBtn" class="btn btn-primary" disabled>Save Preferences</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <div class="modal fade" id="itineraryModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form id="itineraryForm">
            <div class="modal-header">
              <h5 class="modal-title">Create Itinerary</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" id="itTitle" required placeholder="e.g., 3-Day Beach Adventure">
              </div>
              <div class="mb-3">
                <label class="form-label">Days</label>
                <input type="number" min="1" class="form-control" id="itDays" required value="3">
              </div>
              <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" id="itNotes" rows="3" placeholder="Optional notes..."></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary" type="submit" id="itinerarySaveBtn">
                <span class="save-text">Save</span>
                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form id="bookingForm">
            <div class="modal-header">
              <h5 class="modal-title">Make Booking</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Destination</label>
                <input type="text" class="form-control" id="bkDestination" required placeholder="e.g., Canyon Cove">
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Date</label>
                  <input type="date" class="form-control" id="bkDate" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Guests</label>
                  <input type="number" min="1" class="form-control" id="bkGuests" required value="2">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary" type="submit" id="bookingSaveBtn">
                <span class="save-text">Confirm</span>
                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Budget Modal -->
    <div class="modal fade" id="budgetModal" tabindex="-1" aria-hidden="true">
    </div>

    <!-- My Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="profileForm">
                    <div class="modal-header">
                        <h5 class="modal-title">My Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="profile-avatar-large" id="profileAvatar">
                                JD
                                <label class="avatar-upload-btn">
                                    <i class="bi bi-camera-fill"></i>
                                    <input type="file" id="avatarUpload" accept="image/*">
                                </label>
                            </div>
                            <small class="text-muted">Click the camera icon to change profile picture</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profileFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="profileFirstName" value="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="profileLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="profileLastName" value="Dela Cruz" required>
                            </div>
                            <div class="col-12">
                                <label for="profileEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="profileEmail" value="juan.delacruz@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="profilePhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="profilePhone" value="+63 912 345 6789">
                            </div>
                            <div class="col-md-6">
                                <label for="profileBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="profileBirthdate" value="1995-05-15">
                            </div>
                            <div class="col-12">
                                <label for="profileAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="profileAddress" rows="2">Nasugbu, Batangas, Philippines</textarea>
                            </div>
                            <div class="col-12">
                                <label for="profileBio" class="form-label">Bio</label>
                                <textarea class="form-control" id="profileBio" rows="3" placeholder="Tell us about yourself...">Adventure seeker and travel enthusiast exploring Nasugbu!</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Change Password</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="profileSaveBtn">
                            <span class="save-text">Save Changes</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/tourist-ui.js') ?>"></script>
    
    <script>
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const notifDropdown = document.getElementById('notificationDropdown');
            notifDropdown.classList.remove('show'); // Close notification dropdown
            dropdown.classList.toggle('show');
        }

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            const userDropdown = document.getElementById('userDropdown');
            userDropdown.classList.remove('show'); // Close user dropdown
            dropdown.classList.toggle('show');
        }

        function markAllAsRead() {
            const items = document.querySelectorAll('.notification-item.unread');
            items.forEach(item => item.classList.remove('unread'));
            const badge = document.querySelector('.notification-badge');
            if (badge) badge.textContent = '0';
            showToast('Notifications', 'All notifications marked as read');
        }

        function viewAllNotifications(event) {
            event.preventDefault();
            showToast('Notifications', 'Opening all notifications page...');
            // window.location.href = 'notifications.html';
        }

        function handleLogout(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                showToast('Logged Out', 'You have been successfully logged out.');
                setTimeout(() => {
                     window.location.href = '/users/logout';
                }, 1500);
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
            const notifDropdown = document.getElementById('notificationDropdown');
            const userAvatar = document.querySelector('.user-avatar');
            const notifBtn = document.querySelector('.notification-btn');
            
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

            // Close notification dropdown
            if (notifDropdown && notifBtn && !notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
                notifDropdown.classList.remove('show');
            }
        });

        // Toggle notification dropdown
        function toggleNotificationDropdown(){
            const dd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            ud?.classList.remove('show');
            dd?.classList.toggle('show');
        }

        // Open notification detail modal when clicking a notification
        function openNotificationDetail(item){
            const title = item.querySelector('.notification-text h6')?.textContent || 'Notification';
            const message = item.querySelector('.notification-text p')?.textContent || '';
            const time = item.querySelector('.notification-time')?.textContent || '';
            
            // Mark as read
            item.classList.remove('unread');
            
            // Close dropdown
            document.getElementById('notificationDropdown')?.classList.remove('show');
            
            // Open detail modal
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

        // ===== Interactive Enhancements =====
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize modals
            const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));

            // Profile link click
            document.querySelector('a[href="profile.html"]').addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('userDropdown').classList.remove('show');
                profileModal.show();
            });

            // Avatar upload preview
            const avatarUpload = document.getElementById('avatarUpload');
            const profileAvatar = document.getElementById('profileAvatar');
            avatarUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileAvatar.style.backgroundImage = `url(${e.target.result})`;
                        profileAvatar.style.backgroundSize = 'cover';
                        profileAvatar.style.backgroundPosition = 'center';
                        profileAvatar.textContent = '';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Profile form submit
            const profileForm = document.getElementById('profileForm');
            const profileSaveBtn = document.getElementById('profileSaveBtn');
            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Validate passwords if entered
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;
                
                if (newPass || confirmPass) {
                    if (newPass !== confirmPass) {
                        alert('New passwords do not match!');
                        return;
                    }
                    if (!document.getElementById('currentPassword').value) {
                        alert('Please enter your current password to change it.');
                        return;
                    }
                }

                setLoading(profileSaveBtn, true);
                setTimeout(() => {
                    setLoading(profileSaveBtn, false);
                    profileModal.hide();
                    
                    // Update user name in header
                    const firstName = document.getElementById('profileFirstName').value;
                    document.querySelector('.welcome-section h2').textContent = `Welcome back, ${firstName}!`;
                    
                    showToast('Profile Updated', 'Your profile has been successfully updated.');
                }, 1000);
            });

            // Count-up animation for stats
            document.querySelectorAll('.count-up').forEach(el => {
                const target = parseInt(el.dataset.target || '0', 10);
                const duration = 1200;
                const start = 0;
                const startTime = performance.now();
                function tick(now) {
                    const p = Math.min((now - startTime) / duration, 1);
                    const easeOut = 1 - Math.pow(1 - p, 3);
                    const val = Math.floor(start + easeOut * (target - start));
                    el.textContent = val.toString();
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });

            // Gallery card click
            document.querySelectorAll('.gallery-card').forEach(card => {
                card.addEventListener('click', function() {
                    const title = this.querySelector('.gallery-title').textContent;
                    showToast('Explore', `Opening details for ${title}...`);
                });
            });

            // Click notification items
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    const badge = document.querySelector('.notification-badge');
                    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                    if (badge) badge.textContent = unreadCount.toString();
                });
            });
        });

        // ===== Favorites dynamic update =====
        // Fetches favorites from the server and re-renders the favorites cards.
        async function refreshFavorites() {
            try {
                const url = '<?= esc(base_url('/tourist/getFavorites')) ?>';
                const res = await fetch(url, { credentials: 'same-origin' });
                if (!res.ok) {
                    console.warn('Failed to fetch favorites', res.status);
                    return;
                }
                const data = await res.json();
                console.log('Favorites data:', data);
                const container = document.getElementById('favoritesList');
                if (!container) return;
                if (!Array.isArray(data) || data.length === 0) {
                    container.innerHTML = `
                        <div class="activity-item" id="noFavoritesPlaceholder">
                            <div class="activity-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div class="activity-content">
                                <h6>No Favorites Yet</h6>
                                <p>Start exploring and add your favorite spots!</p>
                            </div>
                        </div>`;
                    return;
                }

                const baseUrl = '<?= esc(base_url('uploads/spots/')) ?>';
                const fallbackImg = '<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>';
                
                const items = data.slice(0, 6).map(spot => {
                    const imgFile = spot.primary_image || '';
                    const imgSrc = imgFile ? (baseUrl + imgFile) : fallbackImg;
                    const name = escapeHtml(spot.spot_name || spot.name || 'Spot');
                    const category = escapeHtml(spot.category || '');
                    const rating = spot.rating ? parseFloat(spot.rating).toFixed(1) : '0.0';
                    const spotId = escapeHtml(spot.id || spot.spot_id || '');
                    
                    return `
                        <div class="favorite-mini-card" 
                             data-spot-id="${spotId}"
                             data-spot-name="${name}"
                             data-category="${category}"
                             data-rating="${rating}"
                             style="cursor:pointer;background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:all 0.3s;">
                            <div style="background-image:url('${imgSrc}');background-size:cover;background-position:center;height:120px;position:relative;background-color:#f0f0f0;">
                                <img src="${imgSrc}" alt="${name}" style="display:none;" onerror="this.parentElement.style.backgroundImage='url(${fallbackImg})'" />
                                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.7),transparent);padding:0.5rem;">
                                    <div style="color:white;font-size:0.75rem;font-weight:600;">${name}</div>
                                </div>
                            </div>
                            <div style="padding:0.75rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.75rem;color:#666;">
                                    <span><i class="bi bi-geo-alt"></i> ${category}</span>
                                    <span><i class="bi bi-star-fill" style="color:#ffc107;"></i> ${rating}</span>
                                </div>
                            </div>
                        </div>`;
                }).join('');

                container.innerHTML = items;
            } catch (err) {
                console.error('refreshFavorites error', err);
            }
        }

        // Toggle favorite on the server and refresh UI.
        async function toggleFavorite(spotId) {
            try {
                const url = '<?= esc(base_url('/tourist/toggleFavorite')) ?>';
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ spot_id: spotId }),
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error('Favorite toggle failed');
                const json = await res.json();
                // Refresh favorites after toggling
                refreshFavorites();
                return json;
            } catch (err) {
                console.error(err);
                showToast('Error', 'Unable to update favorites.');
            }
        }

        // Public helper: call when a favorite is added/removed elsewhere
        window.onFavoriteAdded = async function(spotId) {
            await toggleFavorite(spotId);
        };

        // Basic HTML escape helper for insertion into the DOM
        function escapeHtml(str) {
            return String(str === undefined || str === null ? '' : str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // ===== Refresh Dashboard Stats =====
        async function refreshDashboardStats() {
            try {
                const url = '<?= base_url('/tourist/dashboardStats') ?>';
                const res = await fetch(url, { credentials: 'same-origin' });
                if (!res.ok) {
                    console.warn('Failed to fetch dashboard stats', res.status);
                    return;
                }
                const data = await res.json();
                console.log('Dashboard stats received:', data);
                
                // Update stat cards
                if (data.savedItineraries !== undefined) {
                    const el = document.getElementById('itineraryCount');
                    if (el) {
                        el.dataset.target = data.savedItineraries;
                        animateCountUp(el, parseInt(el.textContent) || 0, data.savedItineraries);
                    }
                }
                
                if (data.placesVisited !== undefined) {
                    const el = document.getElementById('visitedCount');
                    if (el) {
                        el.dataset.target = data.placesVisited;
                        animateCountUp(el, parseInt(el.textContent) || 0, data.placesVisited);
                    }
                }
                
                if (data.favoriteCount !== undefined) {
                    const el = document.getElementById('favoriteCount');
                    if (el) {
                        el.dataset.target = data.favoriteCount;
                        animateCountUp(el, parseInt(el.textContent) || 0, data.favoriteCount);
                    }
                }
                
                if (data.upcomingBookings !== undefined) {
                    const el = document.getElementById('bookingCount');
                    if (el) {
                        el.dataset.target = data.upcomingBookings;
                        animateCountUp(el, parseInt(el.textContent) || 0, data.upcomingBookings);
                    }
                }
                
                // Refresh favorites
                await refreshFavorites();
            } catch (err) {
                console.error('refreshDashboardStats error', err);
            }
        }

        // Helper to animate count-up from one value to another
        function animateCountUp(el, start, target) {
            const duration = 800;
            const startTime = performance.now();
            function tick(now) {
                const p = Math.min((now - startTime) / duration, 1);
                const easeOut = 1 - Math.pow(1 - p, 3);
                const val = Math.floor(start + easeOut * (target - start));
                el.textContent = val.toString();
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        // ===== Update Travel Tip Based on Weather =====
        // Mock travel tips for different weather conditions
        function updateTravelTip(weatherCode, temperature, humidity, windSpeed) {
            const tipContainer = document.getElementById('quickTip');
            if (!tipContainer) return;

            // Weather-based travel tips
            const travelTips = {
                // Clear/Sunny weather (0-2)
                clear: [
                    '☀️ Perfect beach day! Apply sunscreen with SPF 50+ and bring a hat to protect yourself from UV rays.',
                    '🏖️ Clear skies are ideal for snorkeling! Visit Fortune Island for the best underwater views.',
                    '🧢 Sunny weather means strong UV exposure. Wear light-colored, loose clothing to stay cool and protected.',
                    '📸 Golden hour is best at sunrise! Head to Kaybiang Tunnel early for stunning photos.'
                ],
                // Cloudy weather (3-48)
                cloudy: [
                    '☁️ Cloudy weather is perfect for hiking! The shade will keep you cool during your mountain adventure.',
                    '🥾 No sun glare today - perfect conditions for exploring Nasugbu\'s waterfalls and trails.',
                    '🌤️ Cloudy skies are ideal for cultural site visits. No sun, so you can explore without time constraints.',
                    '📷 Better lighting for photography without harsh shadows. Great day for landscape shots!'
                ],
                // Drizzle/Light Rain (51-55)
                drizzle: [
                    '🌧️ Light rain? Perfect time to visit indoor attractions like local museums and restaurants.',
                    '☔ Bring a light rain jacket and explore wet season trails - the waterfalls will be fuller!',
                    '🏛️ Quiet weather means fewer crowds. Great for peaceful exploration of temples and historical sites.',
                    '💧 Rice terraces look more beautiful after rain. Consider a scenic drive through nearby farms.'
                ],
                // Heavy Rain (61-82)
                rain: [
                    '⛈️ Heavy rain day? Perfect for indoor activities like cooking classes or art workshops.',
                    '🏨 Stay indoors and enjoy Nasugbu\'s spas and wellness centers. Rain is a great relaxation day!',
                    '📚 Rainy day = perfect for visiting galleries, bookstores, and learning local history.',
                    '☕ Visit local cafes and try authentic Nasugbu cuisine. Rainy weather is cozy conversation time!'
                ],
                // Snow (71-86)
                snow: [
                    '❄️ Rare snow in Nasugbu! Stay safe and bundle up if you venture out to see it.',
                    '🎿 Snow weather calls for indoor activities. Enjoy hot meals and warm hospitality at local resorts.',
                    '📸 Capture unique snowy landscapes - this is a rare sight in Nasugbu!',
                    '🏠 Perfect day to stay warm indoors and plan your next Nasugbu adventure.'
                ],
                // Thunderstorm (95-99)
                storm: [
                    '⚡ Lightning detected! Stay indoors and avoid beach and mountain activities for safety.',
                    '🏨 Perfect time for indoor water activities like pools and hot springs at local resorts.',
                    '🎬 Storm day = movie day! Enjoy cozy indoor entertainment with local snacks.',
                    '📺 Lightning storms are dangerous outdoors. Stay safe indoors and relax with a good book.'
                ]
            };

            // Determine weather category
            let category = 'clear';
            if (weatherCode >= 95) category = 'storm';
            else if (weatherCode >= 71 && weatherCode <= 86) category = 'snow';
            else if (weatherCode >= 61 && weatherCode <= 82) category = 'rain';
            else if (weatherCode >= 51 && weatherCode <= 55) category = 'drizzle';
            else if (weatherCode >= 3 && weatherCode <= 48) category = 'cloudy';
            
            // Get random tip for the category
            const tips = travelTips[category] || travelTips['clear'];
            const randomTip = tips[Math.floor(Math.random() * tips.length)];

            // Update the tip display
            const tipText = tipContainer.querySelector('#tipText');
            if (tipText) {
                tipText.textContent = randomTip;
                // Fade in animation
                tipText.style.opacity = '0.7';
                tipText.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    tipText.style.opacity = '1';
                }, 10);
            }
        }

        // ===== Weather Update =====
        // Fetches real weather data for Nasugbu via backend endpoint
        async function refreshWeather() {
            try {
                // Call our backend endpoint that fetches from Open-Meteo
                const url = '<?= esc(base_url('/tourist/getWeather')) ?>';
                const res = await fetch(url, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Weather fetch failed');
                
                const data = await res.json();
                if (!data.success) throw new Error(data.message || 'Weather data error');
                
                const container = document.getElementById('weatherWidget');
                if (!container) return;
                
                // Map WMO weather codes to human-readable descriptions
                const weatherDesc = {
                    0: 'Clear Sky',
                    1: 'Mainly Clear',
                    2: 'Partly Cloudy',
                    3: 'Overcast',
                    45: 'Foggy',
                    48: 'Foggy',
                    51: 'Light Drizzle',
                    53: 'Moderate Drizzle',
                    55: 'Dense Drizzle',
                    61: 'Slight Rain',
                    63: 'Moderate Rain',
                    65: 'Heavy Rain',
                    71: 'Slight Snow',
                    73: 'Moderate Snow',
                    75: 'Heavy Snow',
                    77: 'Snow Grains',
                    80: 'Slight Rain Showers',
                    81: 'Moderate Rain Showers',
                    82: 'Violent Rain Showers',
                    85: 'Slight Snow Showers',
                    86: 'Heavy Snow Showers',
                    95: 'Thunderstorm',
                    96: 'Thunderstorm with Slight Hail',
                    99: 'Thunderstorm with Heavy Hail'
                };
                
                // Map weather codes to Bootstrap Icons
                const weatherIcon = {
                    0: 'bi-sun-fill',
                    1: 'bi-cloud-sun',
                    2: 'bi-cloud-sun',
                    3: 'bi-cloud',
                    45: 'bi-cloud-fog2',
                    48: 'bi-cloud-fog2',
                    51: 'bi-cloud-drizzle',
                    53: 'bi-cloud-drizzle',
                    55: 'bi-cloud-drizzle',
                    61: 'bi-cloud-rain',
                    63: 'bi-cloud-rain',
                    65: 'bi-cloud-rain-heavy',
                    71: 'bi-snow',
                    73: 'bi-snow',
                    75: 'bi-snow',
                    77: 'bi-snow',
                    80: 'bi-cloud-rain',
                    81: 'bi-cloud-rain-heavy',
                    82: 'bi-cloud-rain-heavy',
                    85: 'bi-snow',
                    86: 'bi-snow',
                    95: 'bi-cloud-lightning',
                    96: 'bi-cloud-lightning-rain',
                    99: 'bi-cloud-lightning-rain'
                };
                
                const temp = Math.round(data.temperature);
                const desc = weatherDesc[data.weather_code] || 'Unknown';
                const icon = weatherIcon[data.weather_code] || 'bi-cloud';
                const humidity = data.humidity;
                const windSpeed = Math.round(data.wind_speed * 10) / 10;
                
                // Update travel tip based on weather
                updateTravelTip(data.weather_code, temp, humidity, windSpeed);
                
                container.innerHTML = `
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <div style="flex:1;">
                            <div style="font-size:3rem;font-weight:700;color:#1a73e8;margin-bottom:0.25rem;">
                                ${temp}°C
                            </div>
                            <div style="font-size:1rem;color:#666;margin-bottom:0.5rem;">
                                ${desc}
                            </div>
                        </div>
                        <div style="font-size:3.5rem;color:#fdb813;">
                            <i class="bi ${icon}"></i>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;border-top:1px solid #eee;padding-top:1rem;">
                        <div>
                            <div style="font-size:0.75rem;color:#999;margin-bottom:0.25rem;">Humidity</div>
                            <div style="font-size:1.25rem;font-weight:600;color:#333;">${humidity}%</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;color:#999;margin-bottom:0.25rem;">Wind Speed</div>
                            <div style="font-size:1.25rem;font-weight:600;color:#333;">${windSpeed} km/h</div>
                        </div>
                    </div>
                    <div style="font-size:0.7rem;color:#bbb;margin-top:1rem;text-align:center;">
                        Last updated: ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                    </div>`;
            } catch (err) {
                console.error('refreshWeather error:', err);
                const container = document.getElementById('weatherWidget');
                if (container) {
                    container.innerHTML = `
                        <div style="text-align:center;color:#999;padding:1rem;">
                            <i class="bi bi-exclamation-triangle" style="font-size:2rem;color:#fdb813;"></i>
                            <p style="margin-top:0.5rem;">Unable to load weather data</p>
                            <small>Please try refreshing the page</small>
                        </div>`;
                }
            }
        }

        // Call on page load
        document.addEventListener('DOMContentLoaded', () => {
            refreshDashboardStats();
            refreshWeather();
            // Refresh weather every 10 minutes
            setInterval(refreshWeather, 600000);
        });
    </script>
    <script>
(function(){
    var isTourist = <?= $isTourist ? 'true' : 'false' ?>;
    var hasPref = <?= $hasPref ? 'true' : 'false' ?>;
    if (!isTourist || hasPref) return;

    var prefModalEl = document.getElementById('prefModal');
    if (!prefModalEl) return;
    var prefModal = new bootstrap.Modal(prefModalEl);
    var checkboxes = document.querySelectorAll('.pref-checkbox');
    var saveBtn = document.getElementById('savePrefsBtn');
    var errorEl = document.getElementById('prefError');

    function updateState() {
        var checked = Array.from(checkboxes).filter(function(ch){ return ch.checked; });
        saveBtn.disabled = checked.length === 0 || checked.length > 5;
        if (checked.length > 5) {
            errorEl.style.display = 'block';
            errorEl.textContent = 'You can select up to 5 categories only.';
        } else {
            errorEl.style.display = 'none';
        }
    }

    checkboxes.forEach(function(ch){ ch.addEventListener('change', updateState); });
    updateState();

    saveBtn.addEventListener('click', function(){
        var selected = Array.from(checkboxes).filter(function(c){ return c.checked; }).map(function(c){ return c.value; });
        if (selected.length === 0) return;
        if (selected.length > 5) {
            errorEl.style.display = 'block';
            errorEl.textContent = 'You can select up to 5 categories only.';
            return;
        }

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        // Fetch CSRF token from meta tag if your app exposes it (recommended).
        // Example: <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
        var headers = { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
        var csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
        if (csrfMeta && csrfMeta.content) headers['X-CSRF-TOKEN'] = csrfMeta.content;

        fetch('<?= esc(base_url('/tourist/savePreferences')) ?>', {
            method: 'POST',
            headers: headers,
            credentials: 'same-origin',
            body: JSON.stringify({ categories: selected })
        }).then(function(res){ return res.json(); }).then(function(json){
            if (json && json.success) {
                window.userPreference = (json.categories || []).join(',');
                prefModal.hide();
                location.reload();
            } else {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Preferences';
                errorEl.style.display = 'block';
                errorEl.textContent = json.message || 'Failed to save preferences';
            }
        }).catch(function(err){
            console.error('savePreferences error', err);
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Preferences';
            errorEl.style.display = 'block';
            errorEl.textContent = 'Network error';
        });
    });

    setTimeout(function(){ prefModal.show(); }, 250);
})();
</script>

<!-- JS: guard profile link selector -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileLink = document.querySelector('a[href="profile.html"]');
    if (profileLink) {
        profileLink.addEventListener('click', (e) => {
            e.preventDefault();
            const pd = document.getElementById('userDropdown');
            if (pd) pd.classList.remove('show');
            const profileModal = document.getElementById('profileModal');
            if (profileModal) new bootstrap.Modal(profileModal).show();
        });
    }
});
</script>

<script>
// Weather Display
document.addEventListener('DOMContentLoaded', function() {
    // Mock weather data for Nasugbu
    const weatherData = {
        temp: 28,
        feels_like: 31,
        description: "Partly Cloudy",
        humidity: 75,
        wind_speed: 12
    };

    // Display weather if weather container exists
    const weatherContainer = document.getElementById('weather-info');
    if (weatherContainer) {
        const weatherIcon = weatherData.description.toLowerCase().includes('cloud') ? '⛅' : 
                           weatherData.description.toLowerCase().includes('rain') ? '🌧️' : '☀️';
        
        weatherContainer.innerHTML = `
            <div class="weather-display p-3 bg-light rounded shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="weather-main">
                        <div class="d-flex align-items-center mb-2">
                            <span class="weather-icon" style="font-size: 3rem;">${weatherIcon}</span>
                            <div class="ms-3">
                                <h3 class="mb-0">${weatherData.temp}°C</h3>
                                <p class="text-muted mb-0">Feels like ${weatherData.feels_like}°C</p>
                            </div>
                        </div>
                        <p class="mb-0 fw-bold">${weatherData.description}</p>
                    </div>
                    <div class="weather-details text-end">
                        <div class="mb-2">
                            <i class="bi bi-droplet-fill text-primary"></i>
                            <span class="ms-1">${weatherData.humidity}%</span>
                            <small class="text-muted d-block">Humidity</small>
                        </div>
                        <div>
                            <i class="bi bi-wind text-info"></i>
                            <span class="ms-1">${weatherData.wind_speed} km/h</span>
                            <small class="text-muted d-block">Wind Speed</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Travel Tips Rotation
    const travelTips = [
        "Start your beach adventures early in the morning to avoid crowds and enjoy the peaceful sunrise views!",
        "Always bring sunscreen with SPF 50+ when visiting beaches. Reapply every 2 hours for best protection.",
        "Visit Fortune Island during low tide for the best photo opportunities at the Greek-inspired ruins.",
        "Book your accommodations in advance during peak season (December to May) for better rates.",
        "Try local delicacies at Nasugbu Public Market for an authentic taste of Batangas cuisine.",
        "Bring waterproof bags to protect your electronics and valuables during island hopping tours.",
        "The best time to visit Kaybiang Tunnel is during sunset for stunning photo opportunities.",
        "Respect local customs and marine life - take only pictures, leave only footprints.",
        "Download offline maps of Nasugbu before your trip in case of limited signal in remote areas.",
        "Check weather forecasts before planning beach or mountain activities for your safety."
    ];

    let currentTipIndex = 0;
    const tipElement = document.getElementById('travel-tip');
    
    if (tipElement) {
        // Set initial tip
        tipElement.textContent = travelTips[0];
        tipElement.style.transition = 'opacity 0.5s ease-in-out';
        
        // Rotate tips every 10 seconds
        setInterval(() => {
            // Fade out
            tipElement.style.opacity = '0';
            
            setTimeout(() => {
                // Change tip
                currentTipIndex = (currentTipIndex + 1) % travelTips.length;
                tipElement.textContent = travelTips[currentTipIndex];
                
                // Fade in
                tipElement.style.opacity = '1';
            }, 500); // Wait for fade out to complete
        }, 10000); // Every 10 seconds
    }
});
</script>
<script>
// View Favorite Details - Navigate to explore and open modal
function viewFavoriteDetails(card) {
    const spotId = card.dataset.spotId || '';
    if (spotId) {
        // Redirect to explore page with spot ID parameter
        window.location.href = `/tourist/exploreSpots?viewSpot=${spotId}`;
    }
}

// Add hover effect to favorite cards
document.addEventListener('DOMContentLoaded', () => {
    const favoriteCards = document.querySelectorAll('.favorite-mini-card');
    favoriteCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
            card.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
        });
    });
});
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