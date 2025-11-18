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
                            $imagePath = 'uploads' . $spot['primary_image'];
                            if (!is_file(FCPATH . $imagePath)) { 
                                $imagePath = 'uploads/spots/Spot-No-Image.png';
                            }
                        ?>

                        <div class="spot-card" data-category="<?= esc($spot['category']) ?>">
                            <div class="spot-image" style="background-image: url('<?= base_url($imagePath) ?>')">
                                <button class="favorite-btn" onclick="toggleFavorite(this)">
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
                                    <button class="btn-add" onclick="addToItinerary('<?= esc($spot['spot_name']) ?>')">Add to Plan</button>
                                    <button class="btn-book" onclick="bookSpot('<?= esc($spot['spot_name']) ?>', this)">
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
                  <input type="hidden" id="bookingSpotName" />
                  
                  <div class="mb-3">
                    <label class="form-label"><strong>Spot Name</strong></label>
                    <input type="text" class="form-control" id="bookingSpotDisplay" disabled style="background-color: #f6f6f6;" />
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label"><strong>Visit Date</strong></label>
                      <input type="date" class="form-control" id="bookingDate" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label"><strong>Number of Visitors</strong></label>
                      <input type="number" class="form-control" id="bookingVisitors" min="1" value="1" required />
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label"><strong>Start Time</strong></label>
                      <input type="time" class="form-control" id="bookingStartTime" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label"><strong>End Time</strong></label>
                      <input type="time" class="form-control" id="bookingEndTime" />
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label"><strong>Package Type</strong></label>
                    <select class="form-select" id="bookingPackage" required>
                      <option value="">Select a package</option>
                      <option value="standard">Standard Visit - ₱500</option>
                      <option value="guided">Guided Tour - ₱1,200</option>
                      <option value="premium">Premium Package - ₱2,500</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label"><strong>Special Requests (Optional)</strong></label>
                    <textarea class="form-control" id="bookingNotes" rows="3" placeholder="e.g., dietary requirements, accessibility needs, photography permits..."></textarea>
                  </div>

                  <div class="alert alert-info" role="alert">
                    <strong><i class="bi bi-info-circle"></i> Booking Summary:</strong>
                    <div style="margin-top: 0.5rem;">
                      <div>Package: <strong id="bookingSummaryPackage">-</strong></div>
                      <div>Visitors: <strong id="bookingSummaryVisitors">1</strong></div>
                      <div style="margin-top: 0.5rem; font-size: 1.1rem; color: var(--primary-color);">
                        Total Cost: <strong id="bookingSummaryTotal">₱500</strong>
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
                  <div class="col-md-6">
                    <div id="spotModalCarousel" class="carousel slide" data-bs-ride="carousel">
                      <div class="carousel-inner" id="spotModalImages">
                        <!-- inserted image items -->
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
                  <div class="col-md-6">
                    <p id="spotModalDesc" class="mb-2"></p>
                    <p class="mb-1"><strong>Category:</strong> <span id="spotModalCategory"></span></p>
                    <p class="mb-2"><strong>Rating:</strong> <span id="spotModalRating"></span></p>
                    <div class="d-flex gap-2 mt-3">
                      <button class="btn btn-primary" id="spotModalAddBtn">Add to Plan</button>
                      <button class="btn btn-outline-secondary" id="spotModalFavBtn"><i class="bi bi-heart"></i> Favorite</button>
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
        
        function toggleFavorite(button) {
            button.classList.toggle('active');
            const spotName = button.closest('.spot-card').querySelector('.spot-title').textContent;
            const isFavorite = button.classList.contains('active');
            
            if (isFavorite) {
                showToast('Favorites', spotName + ' added to favorites!');
            } else {
                showToast('Favorites', spotName + ' removed from favorites.');
            }
        }

        // Show modal populated from the clicked card
        function viewDetails(btn) {
            const card = btn.closest('.spot-card');
            if (!card) return;

            const title = card.querySelector('.spot-title')?.textContent.trim() || '';
            const desc = card.querySelector('.spot-description')?.textContent.trim() || '';
            const category = card.querySelector('.spot-category')?.textContent.trim() || '';
            const rating = card.querySelector('.spot-rating span')?.textContent.trim() || '';

            // Extract image from inline background-image style
            const imgDiv = card.querySelector('.spot-image');
            let imgUrl = '';
            if (imgDiv) {
                const bg = imgDiv.style.backgroundImage; // e.g. url("...")
                const m = /url\(["']?(.*?)["']?\)/.exec(bg);
                imgUrl = m ? m[1] : '';
            }

            // fill modal title and content
            const modalLabel = document.getElementById('spotModalLabel');
            modalLabel.innerHTML = `<i class="bi bi-compass"></i> ${title}`;

            document.getElementById('spotModalDesc').textContent = desc;
            document.getElementById('spotModalCategory').textContent = category;
            document.getElementById('spotModalRating').textContent = rating;

            // build carousel items (use single image if only one)
            const imagesContainer = document.getElementById('spotModalImages');
            imagesContainer.innerHTML = '';
            if (imgUrl) {
                const item = document.createElement('div');
                item.className = 'carousel-item active';
                item.innerHTML = `<img src="${imgUrl}" class="d-block w-100 rounded" alt="${title}">`;
                imagesContainer.appendChild(item);
            } else {
                const item = document.createElement('div');
                item.className = 'carousel-item active';
                item.innerHTML = `<div class="d-flex align-items-center justify-content-center" style="height:240px;background:#f6f6f6;border-radius:8px;"><i class="bi bi-image fs-1 text-muted"></i></div>`;
                imagesContainer.appendChild(item);
            }

            // wire modal buttons
            const addBtn = document.getElementById('spotModalAddBtn');
            addBtn.onclick = function() { addToItinerary(title); bootstrap.Modal.getOrCreateInstance(document.getElementById('spotModal')).hide(); };

            const favBtn = document.getElementById('spotModalFavBtn');
            favBtn.onclick = function() {
                const favToggle = card.querySelector('.favorite-btn');
                if (favToggle) favToggle.classList.toggle('active');
                toggleFavorite(favToggle || favBtn);
            };

            // show modal
            const modalEl = document.getElementById('spotModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
        
        function addToItinerary(spotName) {
            showToast('Itinerary', spotName + ' added to your itinerary!');
        }
        
        // --- BOOKING FUNCTIONS ---
        function bookSpot(spotName, btn) {
            document.getElementById('bookingSpotName').value = spotName;
            document.getElementById('bookingSpotDisplay').value = spotName;
            document.getElementById('bookingForm').reset();
            document.getElementById('bookingDate').valueAsDate = new Date();
            document.getElementById('bookingPackage').value = 'standard';
            updateBookingSummary();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).show();
        }

        function updateBookingSummary() {
            const packageSelect = document.getElementById('bookingPackage');
            const visitors = parseInt(document.getElementById('bookingVisitors').value) || 1;
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const packageText = selectedOption.text || '-';
            
            // extract price from package text (e.g., "Standard Visit - ₱500")
            const priceMatch = packageText.match(/₱([\d,]+)/);
            const basePrice = priceMatch ? parseInt(priceMatch[1].replace(',', '')) : 0;
            const totalCost = basePrice * visitors;

            document.getElementById('bookingSummaryPackage').textContent = packageText || '-';
            document.getElementById('bookingSummaryVisitors').textContent = visitors;
            document.getElementById('bookingSummaryTotal').textContent = '₱' + totalCost.toLocaleString();
        }

        // Update summary on input changes
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                document.getElementById('bookingPackage')?.addEventListener('change', updateBookingSummary);
                document.getElementById('bookingVisitors')?.addEventListener('input', updateBookingSummary);
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
</body>
</html>