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
                    <li class="tourist-nav-item">
                        <a href="/tourist/reviews" class="tourist-nav-link">
                            <i class="bi bi-star"></i>
                            <span class="tourist-nav-link-text">My Reviews</span>
                        </a>
                    </li>
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
                            <span class="notification-badge">3</span>
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h6>Notifications</h6>
                                <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
                            </div>
                            <ul class="notification-list" id="notificationList">
                                <li class="notification-item unread">
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
                                <a href="profile.html" class="dropdown-item-custom">
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
                            <div class="stat-value"><span class="count-up" data-target="<?= esc($TotalSaveItineray ?? 0) ?>">0</span></div>
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
                            <div class="stat-value"><span class="count-up" data-target="<?= esc($placesVisited ?? 0) ?>">0</span></div>
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
                            <div class="stat-value"><span class="count-up" data-target="<?= esc($favoriteCount ?? 0) ?>">0</span></div>
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
                            <div class="stat-value"><span class="count-up" data-target="<?= esc($upcomingBookings ?? 0) ?>">0</span></div>
                            <div class="stat-label">Upcoming Bookings</div>
                        </div>
                        <div class="stat-icon purple">
                            <i class="bi bi-bookmark"></i>
                        </div>
                    </div>
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
            
            <!-- Your Favorites -->
            <div class="recent-activity">
                <h3 class="d-flex align-items-center gap-2">
                    <i class="bi bi-heart-fill" style="color:#ff5d5d;"></i> Your Favorites
                </h3>
                
                <?php if (!empty($favoriteSpots) && count($favoriteSpots) > 0): ?>
                    <?php foreach (array_slice($favoriteSpots, 0, 4) as $spot): ?>
                        <?php 
                            $imagePath = 'uploads/spots/' . $spot['primary_image'];
                            if (!is_file(FCPATH . $imagePath)) { 
                                $imagePath = 'uploads/spots/Spot-No-Image.png';
                            }
                        ?>
                        <div class="activity-item" style="cursor:pointer;" onclick="window.location.href='/tourist/exploreSpots'">
                            <div class="activity-icon" style="background-image:url('<?= base_url($imagePath) ?>');background-size:cover;background-position:center;width:48px;height:48px;border-radius:8px;">
                            </div>
                            <div class="activity-content">
                                <h6><?= esc($spot['spot_name']) ?></h6>
                                <p><?= esc($spot['category']) ?> • ⭐ <?= esc($spot['rating'] ?? '4.5') ?></p>
                            </div>
                            <div class="activity-time">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="activity-item">
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
    </script>
</body>
</html>