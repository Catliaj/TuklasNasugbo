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
    
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/reviews.css')?>"> 
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
                        <a href="/tourist/visits" class="nav-link">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Visited Places</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/reviews" class="nav-link active">
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
                <!-- Fixed User Actions (Top Right) -->
                <div class="user-actions-fixed">
                    <!-- Notification -->
                    <div class="dropdown-wrap">
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
                                        <div class="notification-icon success"><i class="bi bi-check-circle-fill"></i></div>
                                        <div class="notification-text">
                                            <h6>Review Published</h6>
                                            <p>Your review for Mount Batulao is now live</p>
                                            <div class="notification-time"><i class="bi bi-clock"></i><span>20 min ago</span></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item unread">
                                    <div class="notification-content">
                                        <div class="notification-icon info"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                                        <div class="notification-text">
                                            <h6>New Helpful Vote</h6>
                                            <p>Someone found your Fortune Island review helpful</p>
                                            <div class="notification-time"><i class="bi bi-clock"></i><span>2 hours ago</span></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-item">
                                    <div class="notification-content">
                                        <div class="notification-icon warning"><i class="bi bi-flag-fill"></i></div>
                                        <div class="notification-text">
                                            <h6>Guidelines Update</h6>
                                            <p>Review community rules updated</p>
                                            <div class="notification-time"><i class="bi bi-clock"></i><span>Yesterday</span></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="notification-footer">
                                <a href="#" onclick="viewAllNotifications(event)">View all</a>
                            </div>
                        </div>
                    </div>

                    <!-- Avatar -->
                    <div class="dropdown-wrap">
                        <div class="user-avatar" onclick="toggleUserDropdown()">JD</div>
                        <div class="user-dropdown" id="userDropdown">
                            <div class="dropdown-header">
                                <h6>Juan Dela Cruz</h6>
                                <p>juan.delacruz@email.com</p>
                            </div>
                            <ul class="menu">
                                <li><a href="#" onclick="openProfile(event); hideUserDropdown(event)"><i class="bi bi-person-circle"></i><span>My Profile</span></a></li>
                                <li><a class="logout" href="#" onclick="handleLogout(event)"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reviews-container">
                <!-- KPI Cards -->
                <div class="review-stats">
                    <div class="stat-item stagger-1">
                        <div class="stat-icon blue"><i class="bi bi-journal-text"></i></div>
                        <div class="stat-value" id="kpiTotalReviews">3</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="stat-item stagger-2">
                        <div class="stat-icon orange"><i class="bi bi-star-fill"></i></div>
                        <div class="stat-value" id="kpiAvgRating">4.7</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-item stagger-3">
                        <div class="stat-icon green"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                        <div class="stat-value" id="kpiHelpfulVotes">111</div>
                        <div class="stat-label">Helpful Votes</div>
                    </div>
                </div>

                <button class="btn-write-review" onclick="writeReview()">
                    <i class="bi bi-pencil-square"></i> Write New Review
                </button>

                <!-- Review Cards -->
                <div class="review-card stagger-1">
                    <div class="review-header">
                        <div class="review-place">
                            <div class="place-thumb">
                                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&q=80" alt="Mount Batulao" class="place-image">
                                <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                            </div>
                            <div class="place-info">
                                <h3>Mount Batulao</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
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

                <div class="review-card stagger-2">
                    <div class="review-header">
                        <div class="review-place">
                            <div class="place-thumb">
                                <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=300&q=80" alt="Fortune Island" class="place-image">
                                <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                            </div>
                            <div class="place-info">
                                <h3>Fortune Island</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
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

                <div class="review-card stagger-3">
                    <div class="review-header">
                        <div class="review-place">
                            <div class="place-thumb">
                                <img src="https://images.unsplash.com/photo-1519290916420-e67e00a83cde?w=300&q=80" alt="Caleruega Church" class="place-image">
                                <span class="place-badge"><i class="bi bi-geo-alt-fill"></i></span>
                            </div>
                            <div class="place-info">
                                <h3>Caleruega Church</h3>
                                <div class="place-location"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                            </div>
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
            </div> <!-- end .reviews-container -->

            <!-- Write Review Modal -->
            <div class="modal fade modal-zoom" id="reviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="reviewForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Write a Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="reviewPlace" class="form-label">Place</label>
                                        <input type="text" class="form-control" id="reviewPlace" placeholder="e.g., Mount Batulao" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reviewDate" class="form-label">Date visited</label>
                                        <input type="date" class="form-control" id="reviewDate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rating</label>
                                        <div id="ratingStars" class="d-flex align-items-center gap-1">
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="1" aria-label="1 star"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="2" aria-label="2 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="3" aria-label="3 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="4" aria-label="4 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="5" aria-label="5 stars"><i class="bi bi-star fs-4"></i></button>
                                            <input type="hidden" id="reviewRating" value="0" required>
                                        </div>
                                        <div class="form-text">Click a star to set your rating.</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="reviewText" class="form-label">Your review</label>
                                        <textarea class="form-control" id="reviewText" rows="4" placeholder="Share your experience..." required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="reviewPhotos" class="form-label">Photos (optional)</label>
                                        <input type="file" class="form-control" id="reviewPhotos" accept="image/*" multiple>
                                        <div id="photoPreview" class="d-flex gap-2 mt-2 flex-wrap"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit">Save Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- My Profile Modal (copied from Explore) -->
            <div class="modal fade modal-zoom" id="profileModal" tabindex="-1" aria-hidden="true">
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

            <!-- Edit Review Modal -->
            <div class="modal fade modal-zoom" id="editReviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="editReviewForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="editReviewPlace" class="form-label">Place</label>
                                        <input type="text" class="form-control" id="editReviewPlace" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editReviewDate" class="form-label">Date visited</label>
                                        <input type="date" class="form-control" id="editReviewDate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rating</label>
                                        <div id="editRatingStars" class="d-flex align-items-center gap-1">
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="1" aria-label="1 star"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="2" aria-label="2 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="3" aria-label="3 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="4" aria-label="4 stars"><i class="bi bi-star fs-4"></i></button>
                                            <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-value="5" aria-label="5 stars"><i class="bi bi-star fs-4"></i></button>
                                            <input type="hidden" id="editReviewRating" value="0" required>
                                        </div>
                                        <div class="form-text">Click a star to set rating.</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="editReviewText" class="form-label">Your review</label>
                                        <textarea class="form-control" id="editReviewText" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Toasts -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // === Dashboard-like dropdowns + toasts ===
        function toggleNotificationDropdown() {
            const dd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            ud?.classList.remove('show');
            dd.classList.toggle('show');
        }
        function toggleUserDropdown() {
            const dd = document.getElementById('userDropdown');
            const nd = document.getElementById('notificationDropdown');
            nd?.classList.remove('show');
            dd.classList.toggle('show');
        }
        function hideUserDropdown(e){ e?.preventDefault?.(); document.getElementById('userDropdown')?.classList.remove('show'); }

        function markAllAsRead() {
            document.querySelectorAll('.notification-item.unread').forEach(li => li.classList.remove('unread'));
            const badge = document.getElementById('notifBadge');
            if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }
            showToast('Notifications', 'All notifications marked as read.');
        }
        function viewAllNotifications(e){ e.preventDefault(); showToast('Notifications', 'Opening all notifications...'); }

        // Close on outside click
        document.addEventListener('click', (e) => {
            const nd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            const bell = document.querySelector('.notification-btn');
            const avatar = document.querySelector('.user-avatar');

            if (nd && !nd.contains(e.target) && !bell.contains(e.target)) nd.classList.remove('show');
            if (ud && !ud.contains(e.target) && !avatar.contains(e.target)) ud.classList.remove('show');
        });

        // Toast helper
        function showToast(title, msg){
            const container = document.getElementById('toastContainer');
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

            // Notification item click -> mark read and update badge
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(){
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');
                        const unread = document.querySelectorAll('.notification-item.unread').length;
                        const badge = document.getElementById('notifBadge');
                        if (badge){
                            badge.textContent = unread;
                            if (unread === 0) badge.style.display = 'none';
                        }
                    }
                });
            });

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

            // Rating stars
            const stars = document.querySelectorAll('#ratingStars .rating-star');
            const ratingInput = document.getElementById('reviewRating');
            stars.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = parseInt(btn.dataset.value, 10);
                    ratingInput.value = val;
                    stars.forEach((s, i) => {
                        const icon = s.querySelector('i');
                        icon.classList.toggle('bi-star-fill', i < val);
                        icon.classList.toggle('bi-star', i >= val);
                    });
                });
            });

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

            // Edit stars
            const editStars = document.querySelectorAll('#editRatingStars .rating-star');
            const editRatingInput = document.getElementById('editReviewRating');
            editStars.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = parseInt(btn.dataset.value, 10);
                    editRatingInput.value = val;
                    editStars.forEach((s, i) => {
                        const icon = s.querySelector('i');
                        icon.classList.toggle('bi-star-fill', i < val);
                        icon.classList.toggle('bi-star', i >= val);
                    });
                });
            });

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
            document.getElementById('editReviewDate').value = iso;
            document.getElementById('editReviewText').value = text;
            document.getElementById('editReviewRating').value = rating;

            // Paint stars
            document.querySelectorAll('#editRatingStars .rating-star').forEach((s, i) => {
                const icon = s.querySelector('i');
                icon.classList.toggle('bi-star-fill', i < rating);
                icon.classList.toggle('bi-star', i >= rating);
            });

            editModal.show();
        }
        function deleteReview() { if (confirm('Delete this review?')) showToast('Delete', 'Review deleted (demo).'); }

        // Open Profile modal (from Explore)
        function openProfile(e){
            e?.preventDefault?.();
            document.getElementById('userDropdown')?.classList.remove('show');
            profileModal?.show();
        }
        function setLoading(btn, isLoading) {
            const sp = btn.querySelector('.spinner-border');
            const saveText = btn.querySelector('.save-text');
            if (isLoading) { sp?.classList.remove('d-none'); btn.disabled = true; if (saveText) saveText.textContent = 'Saving...'; }
            else { sp?.classList.add('d-none'); btn.disabled = false; if (saveText) saveText.textContent = 'Save Changes'; }
        }
    </script>
</body>
</html>