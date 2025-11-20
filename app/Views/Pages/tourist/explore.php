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
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/explore.css')?>">
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
                <button class="sidebar-toggle d-lg-none btn btn-link text-white" onclick="toggleMobileSidebar()">
                    <i class="bi bi-x fs-3"></i>
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
                        <a href="/tourist/exploreSpots" class="nav-link active">
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
            <!-- Fixed User Actions (Top Right) -->
            <div class="user-actions-fixed">
                <!-- Notification Button -->
                <div style="position: relative;">
                    <button class="notification-btn" onclick="toggleNotificationDropdown()">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notifBadge">3</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
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
                                        <div class="notification-time">
                                            <i class="bi bi-clock"></i>
                                            <span>2 hours ago</span>
                                        </div>
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
                                        <div class="notification-time">
                                            <i class="bi bi-clock"></i>
                                            <span>1 day ago</span>
                                        </div>
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
                                        <div class="notification-time">
                                            <i class="bi bi-clock"></i>
                                            <span>2 days ago</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="notification-footer">
                            <a href="#" onclick="viewAllNotifications(event)">View All Notifications</a>
                        </div>
                    </div>
                </div>

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
                                <a href="#" class="dropdown-item-custom" onclick="openProfile(event)">
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
         
            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
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
                
                <div class="spots-grid" id="spotsGrid">

                    <?php foreach ($spots as $spot): ?>
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
        
        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); color: #fff; border: none;">
                <h5 class="modal-title" id="bookingModalLabel"><i class="bi bi-calendar-check"></i> Book Your Visit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                                <form id="bookingForm">

                    <input type="hidden" id="bookingSpotId" name="spot_id" />
                    <div class="mb-3">
                        <label class="form-label"><strong>Spot Name</strong></label>
                        <input type="text" class="form-control" id="bookingSpotDisplay" disabled style="background-color: #f6f6f6;" />
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label"><strong>Adults</strong> <span id="priceAdult" class="text-muted"></span></label>
                            <input type="number" class="form-control" id="bookingAdults" name="num_adults" min="0" value="1" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Children</strong> <span id="priceChild" class="text-muted"></span></label>
                            <input type="number" class="form-control" id="bookingChildren" name="num_children" min="0" value="0" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Seniors</strong> <span id="priceSenior" class="text-muted"></span></label>
                            <input type="number" class="form-control" id="bookingSeniors" name="num_seniors" min="0" value="0" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Visit Date</strong></label>
                            <input type="date" class="form-control" id="bookingDate" name="visit_date" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Visit Time</strong></label>
                            <input type="time" class="form-control" id="bookingStartTime" name="visit_time" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Special Requests (Optional)</strong></label>
                        <textarea class="form-control" id="bookingNotes" name="special_requests" rows="3" placeholder="e.g., dietary requirements, accessibility needs, photography permits..."></textarea>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <strong><i class="bi bi-info-circle"></i> Booking Summary:</strong>
                        <div style="margin-top: 0.5rem;">
                            <div>Adults: <strong id="bookingSummaryAdults">1</strong> <span id="summaryPriceAdult"></span></div>
                            <div>Children: <strong id="bookingSummaryChildren">0</strong> <span id="summaryPriceChild"></span></div>
                            <div>Seniors: <strong id="bookingSummarySeniors">0</strong> <span id="summaryPriceSenior"></span></div>
                            <div style="margin-top: 0.5rem; font-size: 1.1rem; color: var(--primary-color);">
                                Total Cost: <strong id="bookingSummaryTotal">₱0</strong>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmBooking()" style="background: linear-gradient(90deg, var(--primary-color), #012c4a); border: none;"><i class="bi bi-check-circle"></i> Confirm Booking</button>
              </div>
            </div>
          </div>
        </div>
         
         <!-- Spot Details Modal -->
         <div class="modal fade" id="spotModal" tabindex="-1" aria-labelledby="spotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="spotModalLabel"><i class="bi bi-compass"></i> Spot Details</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Left column: Carousel -->
          <div class="col-md-6">
            <div id="spotModalCarousel" class="carousel slide" data-bs-ride="carousel">
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

          <!-- Right column: Description + details -->
          <div class="col-md-6">
            <div class="mb-3">
              <p id="spotModalDesc" class="mb-2"></p>
              <p class="mb-1"><strong>Category:</strong> <span id="spotModalCategory"></span></p>
              <p class="mb-2"><strong>Rating:</strong> <span id="spotModalRating"></span></p>
            </div>

            <!-- Extra details -->
            <div id="spotModalDetails" class="border p-2 rounded bg-light" style="max-height: 400px; overflow-y: auto;">
              <!-- dynamically inserted details -->
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
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            const userDropdown = document.getElementById('userDropdown');
            userDropdown.classList.remove('show'); // Close user dropdown
            dropdown.classList.toggle('show');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const notifDropdown = document.getElementById('notificationDropdown');
            notifDropdown.classList.remove('show'); // Close notification dropdown
            dropdown.classList.toggle('show');
        }

        function markAllAsRead() {
            const items = document.querySelectorAll('.notification-item.unread');
            items.forEach(item => item.classList.remove('unread'));
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
            showToast('Notifications', 'All notifications marked as read');
        }

        function viewAllNotifications(event) {
            event.preventDefault();
            showToast('Notifications', 'Opening all notifications page...');
            // window.location.href = 'notifications.html';
        }

        function openProfile(event) {
            event.preventDefault();
            document.getElementById('userDropdown').classList.remove('show');
            const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
            profileModal.show();
        }

        function handleLogout(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                showToast('Logged Out', 'You have been successfully logged out.');
                setTimeout(() => {
                    // window.location.href = 'login.html';
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

        // Click notification items to mark as read
        document.addEventListener('DOMContentLoaded', function() {
            // Notification item click handlers
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Only update if it was unread
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');
                        const badge = document.querySelector('.notification-badge');
                        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                        if (badge) {
                            badge.textContent = unreadCount.toString();
                            // Hide badge if no unread notifications
                            if (unreadCount === 0) {
                                badge.style.display = 'none';
                            }
                        }
                    }
                });
            });

            // Avatar upload preview
            const avatarUpload = document.getElementById('avatarUpload');
            const profileAvatar = document.getElementById('profileAvatar');
            if (avatarUpload && profileAvatar) {
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
            }

            // Profile form submit
            const profileForm = document.getElementById('profileForm');
            const profileSaveBtn = document.getElementById('profileSaveBtn');
            if (profileForm && profileSaveBtn) {
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
                        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                        showToast('Profile Updated', 'Your profile has been successfully updated.');
                    }, 1000);
                });
            }
        });
        
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
                        item.innerHTML = `<img src="${img.image_url}" class="d-block w-100 rounded" alt="Spot Image">`;
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
                            class="d-block w-100 rounded" 
                            alt="Spot Image">
                    `;
                    imagesContainer.appendChild(item);
                }

                // Populate all extra details
                detailsEl.innerHTML = `
                    <p><strong>Business ID:</strong> ${spot.business_id || ''}</p>
                    <p><strong>Latitude / Longitude:</strong> ${spot.latitude || ''}, ${spot.longitude || ''}</p>
                    <p><strong>Location:</strong> ${spot.location || ''}</p>
                    <p><strong>Capacity:</strong> ${spot.capacity || ''}</p>
                    <p><strong>Opening Time:</strong> ${spot.opening_time || ''}</p>
                    <p><strong>Closing Time:</strong> ${spot.closing_time || ''}</p>
                    <p><strong>Operating Days:</strong> ${spot.operating_days || ''}</p>
                    <p><strong>Status:</strong> ${spot.status || ''}</p>
                    <p><strong>Status Reason:</strong> ${spot.status_reason || ''}</p>
                    <p><strong>Price per Person:</strong> ${spot.price_per_person || ''}</p>
                    <p><strong>Child Price:</strong> ${spot.child_price || ''}</p>
                    <p><strong>Senior Price:</strong> ${spot.senior_price || ''}</p>
                    <p><strong>Group Discount (%):</strong> ${spot.group_discount_percent || ''}</p>
                    <p><strong>Created At:</strong> ${spot.created_at || ''}</p>
                    <p><strong>Updated At:</strong> ${spot.updated_at || ''}</p>
                `;

                // Wire modal favorite button
                const favBtn = document.getElementById('spotModalFavBtn');
        
        
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

        
        // addToItinerary removed — functionality deprecated
        

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
                'primary_image' => $spot['primary_image'],
            ]) ?>;
        <?php endforeach; ?>

        let currentSpotPrices = { adult: 0, child: 0, senior: 0 };

        function bookSpot(spotId, spotName, btn) {
            const spot = spotDataMap[spotId];
            document.getElementById('bookingForm').reset();
            document.getElementById('bookingSpotId').value = spotId;
            document.getElementById('bookingSpotDisplay').value = spot.spot_name;
            document.getElementById('bookingDate').valueAsDate = new Date();
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
            updateBookingSummary();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).show();
        }

        function updateBookingSummary() {
            const numAdults = parseInt(document.getElementById('bookingAdults').value) || 0;
            const numChildren = parseInt(document.getElementById('bookingChildren').value) || 0;
            const numSeniors = parseInt(document.getElementById('bookingSeniors').value) || 0;
            const total = (numAdults * currentSpotPrices.adult) + (numChildren * currentSpotPrices.child) + (numSeniors * currentSpotPrices.senior);
            document.getElementById('bookingSummaryAdults').textContent = numAdults;
            document.getElementById('bookingSummaryChildren').textContent = numChildren;
            document.getElementById('bookingSummarySeniors').textContent = numSeniors;
            document.getElementById('summaryPriceAdult').textContent = numAdults > 0 ? `x ₱${currentSpotPrices.adult.toLocaleString()}` : '';
            document.getElementById('summaryPriceChild').textContent = numChildren > 0 ? `x ₱${currentSpotPrices.child.toLocaleString()}` : '';
            document.getElementById('summaryPriceSenior').textContent = numSeniors > 0 ? `x ₱${currentSpotPrices.senior.toLocaleString()}` : '';
            document.getElementById('bookingSummaryTotal').textContent = '₱' + total.toLocaleString();
        }

        // Update summary on input changes
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                document.getElementById('bookingAdults').addEventListener('input', updateBookingSummary);
                document.getElementById('bookingChildren').addEventListener('input', updateBookingSummary);
                document.getElementById('bookingSeniors').addEventListener('input', updateBookingSummary);
            }
        });

        function confirmBooking() {
            const spotName = document.getElementById('bookingSpotName').value;
            const date = document.getElementById('bookingDate').value;
            const visitors = document.getElementById('bookingVisitors').value;
            const packageType = document.getElementById('bookingPackage').value;
            const startTime = document.getElementById('bookingStartTime').value;
            const endTime = document.getElementById('bookingEndTime').value;
            const notes = document.getElementById('bookingNotes').value;

            if (!date || !packageType) {
                alert('Please fill in all required fields.');
                return;
            }

            // mock confirmation
            const summary = `✅ Booking Confirmed!

Spot: ${spotName}
Date: ${new Date(date).toLocaleDateString()}
Visitors: ${visitors}
Package: ${document.getElementById('bookingPackage').options[document.getElementById('bookingPackage').selectedIndex].text}
Time: ${startTime || 'Not specified'} - ${endTime || 'Not specified'}
${notes ? `\nSpecial Requests: ${notes}` : ''}

Your booking reference has been sent to your email.
You can manage your booking in the Bookings section.`;

            alert(summary);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();
            showToast('Booking Confirmed', spotName + ' has been booked successfully!');
        }
        
         // Filter functionality
         document.querySelectorAll('.filter-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                // Update active state for filter buttons
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
    </script>
    <script>
        // Override confirmBooking with server-backed implementation
        async function confirmBooking() {

            const spotId = document.getElementById('bookingSpotId')?.value || null;
            const spotName = document.getElementById('bookingSpotDisplay')?.value || '';
            const date = document.getElementById('bookingDate')?.value || '';
            const numAdults = parseInt(document.getElementById('bookingAdults')?.value) || 0;
            const numChildren = parseInt(document.getElementById('bookingChildren')?.value) || 0;
            const numSeniors = parseInt(document.getElementById('bookingSeniors')?.value) || 0;
            const startTime = document.getElementById('bookingStartTime')?.value || '';
            const notes = document.getElementById('bookingNotes')?.value || '';

            if (!date || !spotId || (numAdults + numChildren + numSeniors) < 1) {
                alert('Please fill in all required fields and at least one guest.');
                return;
            }

            // Use currentSpotPrices from booking modal context
            const totalPrice = (numAdults * currentSpotPrices.adult) + (numChildren * currentSpotPrices.child) + (numSeniors * currentSpotPrices.senior);

            const payload = {
                spot_id: spotId,
                visit_date: date,
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
                showToast('Booking Confirmed', spotName + ' has been booked successfully!');
            } catch (err) {
                console.error(err);
                alert('Booking failed: ' + (err.message || 'unknown error'));
            }
        }
    </script>
</body>
</html>