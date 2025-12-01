<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visited Places - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/dashboard.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/visited.css')?>">
</head>
<?php
// Populate user session data for dynamic profile display
$session = session();
$userFirstName = $session->get('FirstName') ?? '';
$userLastName = $session->get('LastName') ?? '';
$userEmail = $session->get('Email') ?? '';
$userInitials = strtoupper(substr($userFirstName, 0, 1) . substr($userLastName, 0, 1));
$FullName = trim(($userFirstName . ' ' . $userLastName));
?>
<body>
    <div class="dashboard-wrapper">
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
                        <a href="/tourist/visits" class="tourist-nav-link active">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span class="tourist-nav-link-text">Visited Places</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <!-- Reuse dashboard page header for consistent styling -->
            <div class="page-header">
                <div class="page-header-actions">
                    <div style="position: relative;">
                        <button class="notification-btn" onclick="toggleNotificationDropdown()">
                          <i class="bi bi-bell-fill"></i>
                          <span class="notification-badge" id="notifBadge" style="display:none">0</span>
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown" style="position:absolute; z-index:20060; right:0;">
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

                    <div style="position: relative;">
                        <div class="user-avatar" onclick="toggleUserDropdown()"><?= esc($userInitials ?: 'JD') ?></div>
                        <div class="user-dropdown" id="userDropdown" style="position:absolute; z-index:20060; right:0;">
                            <div class="dropdown-header">
                                <h6><?= esc($FullName ?: 'Juan Dela Cruz') ?></h6>
                                <p><?= esc($userEmail ?: 'juan.delacruz@email.com') ?></p>
                            </div>
                            <ul class="dropdown-menu-custom">
                                <li>
                                    <a href="#" class="dropdown-item-custom" onclick="openProfile(event); hideUserDropdown(event)">
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
                <h2><i class="bi bi-geo-alt-fill"></i> Visited Places</h2>
                <p>Your travel history and checked-in places.</p>
            </div>

            <div class="visited-container">
                <!-- Stats -->
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-value"><span id="stat-total-visits">0</span></div>
                                <div class="stat-label">Total Places Visited</div>
                            </div>
                            <div class="stat-icon blue"><i class="bi bi-geo-alt-fill"></i></div>
                        </div>
                    </div>
                  <div class="stat-card">
                    <div class="stat-header">
                      <div>
                        <div class="stat-value"><span id="stat-reviews-total">0</span></div>
                        <div class="stat-label">Total Reviews · <span id="stat-reviews-average">—</span></div>
                      </div>
                      <div class="stat-icon blue"><i class="bi bi-star-fill"></i></div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-header">
                      <div>
                        <div class="stat-value"><span id="stat-average-value">—</span></div>
                        <div class="stat-label">Average Rating</div>
                      </div>
                      <div class="stat-icon blue"><i class="bi bi-star" style="transform: rotate(10deg);"></i></div>
                    </div>
                    <div class="mt-1 d-flex align-items-center" style="gap:.5rem;">
                      <div id="stat-average-stars" class="d-flex" aria-hidden="true" style="line-height:1"><!-- stars populated by JS --></div>
                    </div>
                  </div>
                </div>

                <!-- Timeline Section -->
                <div class="timeline-section">
                    <div class="section-header">
                        <h3 class="section-title">Travel History</h3>
                        <div class="controls" style="display:flex;gap:.75rem;align-items:center;">
                            <div class="view-toggle">
                                <button class="view-btn active" onclick="switchVisitedView('timeline', this)"><i class="bi bi-list-ul"></i> Timeline</button>
                                <button class="view-btn" onclick="switchVisitedView('grid', this)"><i class="bi bi-grid-3x3-gap"></i> Grid</button>
                            </div>
                            <div class="dropdown">
                              <button class="view-btn" type="button" id="visitedFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">Filter: All</button>
                              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="visitedFilterDropdown">
                                <li><a class="dropdown-item" href="#" data-filter="all">All</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="reviewed">Reviewed</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="not-reviewed">Not Reviewed</a></li>
                              </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline View -->
                    <div class="timeline active" id="timelineView">
                        <!-- Populated by JavaScript from AJAX -->
                    </div>

                    <!-- Grid View -->
                    <div class="grid-view" id="gridView">
                        <!-- Populated by JavaScript from AJAX -->
                    </div>
                </div>
            </div>
        </main>
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
                                <?= esc($userInitials ?: 'JD') ?>
                                <label class="avatar-upload-btn">
                                    <i class="bi bi-camera-fill"></i>
                                    <input type="file" id="avatarUpload" accept="image/jpeg,image/png,image/gif,image/webp">
                                </label>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted d-block">Click the camera icon to change profile picture</small>
                                <small class="text-muted d-block">Accepted: JPG, PNG, GIF, WebP (Max 5MB)</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="profileFirstName">First Name</label>
                                <input class="form-control" id="profileFirstName" value="<?= esc($userFirstName) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profileLastName">Last Name</label>
                                <input class="form-control" id="profileLastName" value="<?= esc($userLastName) ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileEmail">Email Address</label>
                                <input type="email" class="form-control" id="profileEmail" value="<?= esc($userEmail) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profilePhone">Phone Number</label>
                                <input type="tel" class="form-control" id="profilePhone" value="<?= esc($session->get('Phone') ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profileBirthdate">Birthdate</label>
                                <input type="date" class="form-control" id="profileBirthdate" value="<?= esc($session->get('Birthdate') ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileAddress">Address</label>
                                <textarea class="form-control" id="profileAddress" rows="2"><?= esc($session->get('Address') ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileBio">Bio</label>
                                <textarea class="form-control" id="profileBio" rows="3"><?= esc($session->get('Bio') ?? '') ?></textarea>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h6 class="mb-3">Change Password</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="currentPassword">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="newPassword">New Password</label>
                                <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="confirmPassword">Confirm Password</label>
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

    <!-- Toasts -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/tourist-ui.js') ?>"></script>
    
    <script>
                function toggleSidebar() {
                    const sidebar = document.getElementById('sidebar');
                    if (!sidebar) return;
                    sidebar.classList.toggle('hide');
                }

                if (typeof handleLogout === 'undefined') {
                    function handleLogout(e){ e?.preventDefault?.(); if (confirm('Are you sure you want to logout?')) { window.location.href = '/users/logout'; } }
                }

                if (typeof toggleUserDropdown === 'undefined') {
                    function toggleUserDropdown(){
                        const dd = document.getElementById('userDropdown'); if (!dd) return; dd.classList.toggle('show');
                    }
                }

                if (typeof hideUserDropdown === 'undefined') {
                    function hideUserDropdown(e){ e?.preventDefault?.(); const dd = document.getElementById('userDropdown'); if (dd) dd.classList.remove('show'); }
                }

                if (typeof toggleNotificationDropdown === 'undefined') {
                    function toggleNotificationDropdown(){
                        const dd = document.getElementById('notificationDropdown');
                        const ud = document.getElementById('userDropdown');
                        ud?.classList.remove('show');
                        dd?.classList.toggle('show');
                    }
                }

                if (typeof openNotificationDetail === 'undefined') {
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
                }

                if (typeof markAllAsRead === 'undefined') {
                    function markAllAsRead(){ document.querySelectorAll('.notification-item.unread').forEach(i=>i.classList.remove('unread')); const b=document.getElementById('notifBadge'); if (b) { b.textContent='0'; b.style.display='none'; } }
                }

                if (typeof viewAllNotifications === 'undefined') {
                    function viewAllNotifications(e){ e?.preventDefault?.(); document.getElementById('notificationDropdown')?.classList.add('show'); }
                }

                if (typeof switchVisitedView === 'undefined') {
                    function switchVisitedView(view, btn){
                        // Update active button styling
                        document.querySelectorAll('.view-btn').forEach(b=>b.classList.remove('active'));
                        if (btn) btn.classList.add('active');

                        // Toggle visibility classes
                        const timelineEl = document.getElementById('timelineView');
                        const gridEl = document.getElementById('gridView');
                        if (timelineEl) timelineEl.classList.toggle('active', view === 'timeline');
                        if (gridEl) gridEl.classList.toggle('active', view === 'grid');

                        // Apply layout adjustments for grid view similar to bookings
                        if (view === 'grid' && gridEl) {
                            gridEl.style.display = 'grid';
                            gridEl.style.gridTemplateColumns = 'repeat(auto-fill, minmax(260px, 1fr))';
                            gridEl.style.gap = '1.25rem';
                        } else if (gridEl) {
                            gridEl.style.display = '';
                            gridEl.style.gridTemplateColumns = '';
                            gridEl.style.gap = '';
                        }
                    }
                }

                if (typeof openProfile === 'undefined') {
                    function openProfile(event) {
                        event?.preventDefault?.();
                        document.getElementById('userDropdown')?.classList.remove('show');
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('profileModal')).show();
                    }
                }

        // DOM Ready hooks
        document.addEventListener('DOMContentLoaded', () => {
            // Mark single notification read on click
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

            // Profile: avatar preview
            const avatarUpload = document.getElementById('avatarUpload');
            const profileAvatar = document.getElementById('profileAvatar');
            avatarUpload?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPG, PNG, GIF, WebP)');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file size (max 5MB)
                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('Image size should be less than 5MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        // Set background image
                        profileAvatar.style.backgroundImage = `url('${ev.target.result}')`;
                        profileAvatar.style.backgroundSize = 'cover';
                        profileAvatar.style.backgroundPosition = 'center';
                        profileAvatar.style.backgroundRepeat = 'no-repeat';
                        
                        // Clear text content (initials)
                        profileAvatar.textContent = '';
                        
                        // Update main avatar in header
                        const mainAvatar = document.querySelector('.user-avatar');
                        if (mainAvatar) {
                            mainAvatar.style.backgroundImage = `url('${ev.target.result}')`;
                            mainAvatar.style.backgroundSize = 'cover';
                            mainAvatar.style.backgroundPosition = 'center';
                            mainAvatar.textContent = '';
                        }
                        
                        showToast('Avatar Updated', 'Profile picture preview updated. Save to confirm changes.');
                    };
                    reader.onerror = function() {
                        alert('Error reading file. Please try again.');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Profile save with simple validation + spinner
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
        });

        function setLoading(btn, isLoading) {
            const sp = btn.querySelector('.spinner-border');
            const saveText = btn.querySelector('.save-text');
            if (isLoading) { sp?.classList.remove('d-none'); btn.disabled = true; if (saveText) saveText.textContent = 'Saving...'; }
            else { sp?.classList.add('d-none'); btn.disabled = false; if (saveText) saveText.textContent = 'Save Changes'; }
        }
    </script>

    <style>
    .sidebar{transition:transform .4s cubic-bezier(.77,0,.18,1),box-shadow .3s;transform:translateX(0);box-shadow:0 0 0 rgba(0,0,0,0)}
    .sidebar.hide{transform:translateX(-100%);box-shadow:none}
    .sidebar.show{transform:translateX(0);box-shadow:0 8px 32px rgba(0,0,0,.16);animation:sidebarPopIn .5s cubic-bezier(.77,0,.18,1)}
    @keyframes sidebarPopIn{0%{transform:translateX(-120%) scale(.96);opacity:.6}80%{transform:translateX(8px) scale(1.03);opacity:1}100%{transform:translateX(0) scale(1);opacity:1}}
    .user-avatar{background:linear-gradient(135deg,#004b8d,#001d33)!important;border:2px solid rgba(255,255,255,.18)!important}
    .user-avatar:hover{transform:translateY(-2px);background:linear-gradient(135deg,#005fae,#002e55)!important}
    /* Star Rating Styles - Match Reviews Page */
    .rating-star {
        transition: all 0.2s ease;
        position: relative;
        cursor: pointer;
    }
    .rating-star i {
        color: #d1d5db;
        transition: all 0.2s ease;
        font-size: 2rem !important;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }
    .rating-star:hover i,
    .rating-star.active i,
    .rating-star.filled i {
        color: #fbbf24;
        transform: scale(1.2);
        filter: drop-shadow(0 4px 8px rgba(251,191,36,0.4));
    }
    .rating-star.hovered i {
        color: #fbbf24;
        transform: scale(1.15);
    }
    #reviewRatingStars:hover .rating-star i {
        color: #e5e7eb;
    }
    #reviewRatingStars .rating-star:hover i,
    #reviewRatingStars .rating-star:hover ~ .rating-star i {
        color: #d1d5db;
    }
    #reviewRatingStars .rating-star.hovered i {
        color: #fbbf24;
    }
    /* Button Styling - Deep Ocean Blue (matches bookings page) */
    .btn-booking {
        position: relative;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 0.60rem 1rem;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        background: #f7efe6;
        color: #2c3e50;
        transition: transform 0.16s ease, box-shadow 0.22s ease, background 0.22s ease, border-color 0.22s ease;
    }
    .btn-booking.ocean {
        background: linear-gradient(135deg, #004b8d, #002e55);
        color: #fff;
        border-color: #004b8d;
        box-shadow: 0 6px 18px -6px rgba(0, 46, 85, 0.45);
    }
    .btn-booking.ocean:hover {
        background: linear-gradient(135deg, #005fae, #003a6e);
        box-shadow: 0 10px 24px -6px rgba(0, 75, 141, 0.55);
        transform: translateY(-2px);
    }
    .btn-booking.ocean.danger {
        background: linear-gradient(135deg, #dc3545, #a02834);
        border-color: #dc3545;
    }
    .btn-booking.ocean.danger:hover {
        background: linear-gradient(135deg, #ff002e, #8c001d);
    }
    /* View/Edit buttons styled like View Details */
    .btn-view-details {
        background: linear-gradient(135deg, #004b8d, #002e55);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.70rem 1.2rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        box-shadow: 0 6px 20px rgba(0, 46, 85, 0.25);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s ease, box-shadow 0.28s ease, background 0.28s ease;
    }
    .btn-view-details:hover {
        background: linear-gradient(135deg, #005fae, #003a6e);
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(0, 75, 141, 0.35);
    }
    .btn-view-details:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    .btn-view-details::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.45) 50%, rgba(255, 255, 255, 0) 100%);
        transform: translateX(-120%) skewX(-18deg);
        transition: transform 0.75s cubic-bezier(0.2, 0.6, 0.2, 1);
        pointer-events: none;
    }
    .btn-view-details:hover::after {
        transform: translateX(320%) skewX(-18deg);
    }
    .btn-booking.light {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(0, 75, 141, 0.06));
        color: #004b8d;
        border-color: rgba(0, 75, 141, 0.25);
    }
    .btn-booking.light:hover {
        background: linear-gradient(135deg, #ffffff, #e6f3fa);
        border-color: #004b8d;
        box-shadow: 0 8px 20px rgba(0, 46, 85, 0.18);
    }
    .btn-booking:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    .btn-booking::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.35) 50%, rgba(255, 255, 255, 0) 100%);
        transform: translateX(-120%) skewX(-18deg);
        transition: transform 0.75s cubic-bezier(0.2, 0.6, 0.2, 1);
        pointer-events: none;
    }
    .btn-booking:hover::after {
        transform: translateX(320%) skewX(-18deg);
    }
    /* Timeline and Grid Styling (matches bookings page) */
    .timeline {
      position: relative;
      padding: 1rem 0;
      /* create left gutter so the vertical line and dot don't overlap content */
      padding-left: 64px;
    }

    /* Stat card header layout: place icon on the left (fixed), text on the right */
    .stat-card { padding: 1rem; border-radius: 12px; background: linear-gradient(145deg, rgba(0,75,141,0.06), rgba(255,255,255,0.02)); border: 1px solid rgba(0,75,141,0.08); }
    .stat-header { display: flex; align-items: center; gap: 1rem; }
    /* make icon appear first (left) even if markup places it after text */
    .stat-header > .stat-icon { order: 0; flex: 0 0 56px; margin-right: 0.5rem; }
    .stat-header > div:first-child { order: 1; flex: 1 1 auto; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #004b8d; }
    .stat-label { color: #6b7280; font-size: 0.85rem; }
    .stat-icon { display:flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:10px; background: linear-gradient(135deg,#004b8d,#002e55); color:#fff; box-shadow: 0 6px 18px -6px rgba(0, 46, 85, 0.25); }
    .stat-icon i { font-size:1.1rem; }
    .timeline.active {
        display: block;
    }
    .timeline:not(.active) {
        display: none;
    }
    .timeline-item {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2rem;
      position: relative;
      animation: fadeInUp 0.35s ease both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* timeline dots are removed - keep no-dot layout */
    .timeline-dot { display: none !important; }
    .timeline::before {
      content: '';
      position: absolute;
      left: 32px; /* vertical line slightly right of dot center */
      top: 0;
      bottom: 0;
      width: 2px;
      background: linear-gradient(to bottom, #004b8d, transparent);
      z-index: 1;
    }
    .timeline-content {
      flex: 1;
      background: linear-gradient(145deg, rgba(0, 75, 141, 0.18), rgba(0, 46, 85, 0.14) 55%, rgba(255, 255, 255, 0.08));
      border: 1px solid rgba(0, 75, 141, 0.30);
      backdrop-filter: blur(6px);
      border-radius: 14px;
      padding: 1.25rem 1.5rem 1.5rem 1.5rem;
      box-shadow: 0 8px 24px rgba(0, 46, 85, 0.15);
      transition: all 0.3s ease;
      /* ensure content sits to the right of the gutter */
      margin-left: 0;
    }
    .timeline-content:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 46, 85, 0.22);
    }
    .grid-view {
        display: none;
        width: 100%;
    }
    .grid-view.active {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
        padding: 0;
        animation: fadeInUp 0.35s ease both;
    }
    .grid-card {
        background: linear-gradient(145deg, rgba(0, 75, 141, 0.18), rgba(0, 46, 85, 0.14) 55%, rgba(255, 255, 255, 0.08));
        border: 1px solid rgba(0, 75, 141, 0.30);
        backdrop-filter: blur(6px);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 46, 85, 0.15);
        transition: all 0.3s ease;
    }
    .grid-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 46, 85, 0.22);
    }
    .grid-card img,
    .grid-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        background: #f0f0f0;
    }
    .grid-card-content {
        padding: 1rem;
    }
    .grid-card-content h4 {
        margin: 0 0 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #004b8d;
    }
    .grid-card-content p {
        margin: 0 0 0.75rem;
        font-size: 0.85rem;
        color: #666;
    }
    /* Filter buttons */
    .filter-toggle { display:inline-flex; gap:0.5rem; align-items:center; }
    .filter-btn { padding:0.45rem 0.75rem; border-radius:10px; border:1px solid rgba(0,0,0,0.06); background:#fff; color:#004b8d; font-weight:600; cursor:pointer; }
    .filter-btn.active { background: linear-gradient(135deg,#004b8d,#002e55); color:#fff; border-color:transparent; }
    /* dropdown alignment tweaks */
    .controls .dropdown { margin-left: 0.5rem; }
    </style>

    <script>
// assets/js/visited-ajax.js
// Fetch visited places (Checked-in / Checked-out) via AJAX and render timeline/grid views.
// Ensures images come from uploads/spots/ and fall back to Spot-No-Image.png on missing files.

(function () {
  'use strict';

  // Ensure BASE_URL is defined on the page. If not, default to '/'.
  // Try to get from CodeIgniter config or use root
  let BASE = '/';
  
  // Check if BASE_URL is defined globally
  if (typeof BASE_URL !== 'undefined' && BASE_URL) {
    BASE = BASE_URL.replace(/\/+$/, '') + '/';
  } else {
    // Fallback: use current origin
    BASE = window.location.origin + '/';
  }
  
  console.log('BASE URL:', BASE);
  
  const FALLBACK_IMAGE = BASE + 'uploads/spots/Spot-No-Image.png';

  function escHtml(s) {
    if (s === null || s === undefined) return '';
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function formatDate(iso) {
    if (!iso) return '';
    const d = new Date(iso);
    if (isNaN(d.getTime())) return escHtml(iso);
    return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
  }

  async function loadVisitedPlaces() {
    const url = BASE + 'tourist/visited/ajax';
    try {
      const res = await fetch(url, { credentials: 'same-origin' });
      if (res.status === 401) {
        console.warn('User not authenticated for visited AJAX');
        return;
      }
      if (!res.ok) {
        console.error('Visited AJAX error', res.status);
        return;
      }
      const payload = await res.json();
      if (!payload || !payload.success) {
        console.warn('Visited AJAX no data or failure', payload);
        return;
      }
      // store master list and render according to current filter
      window._visitedItems = payload.data || [];
      renderVisited(getFilteredVisitedItems());
    } catch (err) {
      console.error('Error fetching visited places', err);
    }
  }

  // Current filter (all | reviewed | not-reviewed)
  window._visitedFilter = 'all';

  function getFilteredVisitedItems() {
    const items = Array.isArray(window._visitedItems) ? window._visitedItems : [];
    if (window._visitedFilter === 'all') return items;

    return items.filter(it => {
      const hasReviewFlag = !!(it.review_id && it.review_id > 0);
      // check cached review summary if available
      const cache = (window._reviewSummaryCache && window._reviewSummaryCache[it.spot_id]) || null;
      const cachedTotal = cache ? (parseInt(cache.total || 0, 10) || 0) : 0;
      const isReviewed = hasReviewFlag || cachedTotal > 0;
      if (window._visitedFilter === 'reviewed') return isReviewed;
      if (window._visitedFilter === 'not-reviewed') return !isReviewed;
      return true;
    });
  }

  function setVisitedFilter(filter, btnEl) {
    window._visitedFilter = filter;
    // update active state on buttons
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');
    renderVisited(getFilteredVisitedItems());
  }

  function buildImageTag(fileName, altText = '') {
    // If primary_image is empty/null use fallback immediately
    if (!fileName) {
      console.log('Timeline Image (no file):', { altText, src: FALLBACK_IMAGE });
      return `<img src="${FALLBACK_IMAGE}" alt="${escHtml(altText)}" class="photo-thumbnail" data-retry="false">`;
    }
    const src = BASE + 'uploads/spots/' + escHtml(fileName);
    console.log('Timeline Image:', { altText, fileName, fullPath: src });
    // Use onerror to swap to fallback if the file is missing or 404s
    return `<img src="${src}" alt="${escHtml(altText)}" class="photo-thumbnail" data-retry="true" data-fallback="${FALLBACK_IMAGE}" onerror="handleImageError(this)">`;
  }

  function handleImageError(img) {
    if (img.dataset.retry === 'true') {
      img.src = img.dataset.fallback;
      img.dataset.retry = 'false';
      img.onerror = null;
    } else {
      // Fallback also failed, use SVG placeholder
      img.src = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23e0e0e0%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%22 y=%2250%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E';
      img.onerror = null;
    }
  }

  function renderVisited(items) {
    const timeline = document.getElementById('timelineView');
    const grid = document.getElementById('gridView');
    if (!timeline && !grid) return;

    // Timeline
                if (timeline) {
      if (!items.length) {
        timeline.innerHTML = '<p class="text-muted p-3">You have no visited places yet.</p>';
      } else {
        const html = items.map(it => {
          const imgTag = buildImageTag(it.primary_image, it.spot_name);
          const hasReview = it.review_id && it.review_id > 0;
          const reviewBtn = hasReview 
            ? `<button class="btn-view-details" onclick="viewReview(${it.spot_id}, ${it.review_id})"><i class="bi bi-eye"></i> View Review</button>`
            : `<button class="btn-booking ocean" onclick="writeReview(${it.spot_id}, '${escHtml(it.spot_name)}')"><i class="bi bi-pencil"></i> Write a Review</button>`;
          
          return `
            <div class="timeline-item">
              <div class="timeline-dot" data-spot-id="${it.spot_id}"></div>
              <div class="timeline-content">
                <div class="timeline-header">
                  <div>
                    <h4 class="timeline-title">${escHtml(it.spot_name)}</h4>
                    <p class="timeline-date"><i class="bi bi-calendar"></i> ${formatDate(it.visit_date || it.booking_date)}</p>
                  </div>
                  <span class="check-in-badge"><i class="bi bi-check-circle"></i> ${escHtml(it.booking_status || '')}</span>
                </div>
                <div class="timeline-meta">
                  <div class="timeline-meta-item"><i class="bi bi-geo-alt"></i><span>${escHtml(it.location)}</span></div>
                  <div class="timeline-meta-item"><i class="bi bi-people"></i><span>${escHtml(it.total_guests)} guests</span></div>
                  <div class="timeline-meta-item"><i class="bi bi-currency-dollar"></i><span>₱${escHtml(it.total_price)}</span></div>
                </div>
                <div class="timeline-photos">
                  ${imgTag}
                </div>
                <div class="mt-3">
                  ${reviewBtn}
                </div>
              </div>
            </div>
          `;
        }).join('');
        timeline.innerHTML = html;
        // After inserting timeline HTML, position dots vertically centered for each item to avoid overlapping text
        setTimeout(() => {
          // remove any timeline dots (we no longer show them)
            document.querySelectorAll('.timeline-dot').forEach(d => d.remove());
        
        }, 40);
      }
    }

    // Grid
    if (grid) {
      if (!items.length) {
        grid.innerHTML = '<p class="text-muted p-3">No visited places to show.</p>';
      } else {
        const html = items.map(it => {
          // Build image tag with proper src
          const imgSrc = !it.primary_image ? FALLBACK_IMAGE : (BASE + 'uploads/spots/' + escHtml(it.primary_image));
          console.log('Grid Image:', { spot: it.spot_name, fileName: it.primary_image, fullPath: imgSrc });
          const imgTag = `<img src="${imgSrc}" alt="${escHtml(it.spot_name)}" class="grid-card-img" data-retry="${!it.primary_image ? 'false' : 'true'}" data-fallback="${FALLBACK_IMAGE}" onerror="handleImageError(this)">`;
          const hasReview = it.review_id && it.review_id > 0;
          const reviewBtn = hasReview 
            ? `<button class="btn-view-details w-100 mt-2" onclick="viewReview(${it.spot_id}, ${it.review_id})"><i class="bi bi-eye"></i> View Review</button>`
            : `<button class="btn-booking ocean w-100 mt-2" onclick="writeReview(${it.spot_id}, '${escHtml(it.spot_name)}')"><i class="bi bi-pencil"></i> Write a Review</button>`;
          
          return `
            <div class="grid-card">
               ${imgTag}
               <div class="grid-card-content">
                   <h4>${escHtml(it.spot_name)}</h4>
                   <p><i class="bi bi-calendar"></i> ${formatDate(it.visit_date || it.booking_date)}</p>
                   <p class="small text-muted">${escHtml(it.booking_status || '')}</p>
                   ${reviewBtn}
               </div>
            </div>
          `;
        }).join('');
        grid.innerHTML = html;
      }
    }

    // Update total visits count
    const totalVisitsEl = document.getElementById('stat-total-visits');
    if (totalVisitsEl) {
      totalVisitsEl.textContent = items.length;
    }
  }

  // Expose reload and auto-run on DOM ready
  window.reloadVisitedPlaces = loadVisitedPlaces;
  window.handleImageError = handleImageError;
  document.addEventListener('DOMContentLoaded', loadVisitedPlaces);

  // After rendering visited places, request review summaries for visible spots
  const origRenderVisited = renderVisited;
  renderVisited = function(items) {
    origRenderVisited(items);
    if (!Array.isArray(items)) return;
    items.forEach(it => {
      if (it && it.spot_id) loadReviewSummaryForSpot(it.spot_id);
    });
  };

  // Fetch review summary for a spot and update the placeholder (without re-rendering)
  async function loadReviewSummaryForSpot(spotId) {
    const elId = 'review-summary-' + spotId;
    const el = document.getElementById(elId);
    try {
      const res = await fetch(BASE + `tourist/spot/${spotId}/reviews`, { credentials: 'same-origin' });
      if (!res.ok) return;
      const data = await res.json();
      // store results in cache for aggregation even if per-spot UI is removed
      const avg = parseFloat((data && data.average_rating) || 0) || 0;
      const total = parseInt((data && data.total_reviews) || 0) || 0;

      // update DOM element if present (backwards compatible)
      if (el) {
        if (!data || !data.success || total === 0) {
          el.innerHTML = `
            <div class="review-summary-stars">
              <i class="bi bi-star" style="color:#d1d5db"></i>
              <i class="bi bi-star" style="color:#d1d5db"></i>
              <i class="bi bi-star" style="color:#d1d5db"></i>
              <i class="bi bi-star" style="color:#d1d5db"></i>
              <i class="bi bi-star" style="color:#d1d5db"></i>
            </div>
            <div class="review-summary-text ms-2 small text-muted">No reviews</div>
          `;
        } else {
          const full = Math.floor(avg);
          let stars = '';
          for (let i = 0; i < 5; i++) {
            if (i < full) stars += '<i class="bi bi-star-fill" style="color:#ffb400"></i>';
            else stars += '<i class="bi bi-star" style="color:#d1d5db"></i>';
          }
          el.innerHTML = `
            <div class="review-summary-stars">${stars}</div>
            <div class="review-summary-text ms-2 small text-muted">${avg.toFixed(2)} · ${total} review${total !== 1 ? 's' : ''}</div>
          `;
        }
        el.dataset.avg = String(avg);
        el.dataset.total = String(total);
      }

      // store into cache for aggregation (works even if element removed)
      window._reviewSummaryCache = window._reviewSummaryCache || {};
      window._reviewSummaryCache[spotId] = { avg: avg, total: total };

      // recalc aggregations
      recalcReviewStats();
    } catch (err) {
      console.error('Failed loading review summary for spot', spotId, err);
    }
  }

  // Recalculate aggregate reviews (total reviews and average rating) from per-spot data attributes
  function recalcReviewStats() {
    // aggregate from per-spot DOM nodes if present, otherwise use JS cache
    const nodes = document.querySelectorAll('.review-summary-stats');
    let totalReviews = 0;
    let weightedSum = 0;

    if (nodes && nodes.length > 0) {
      nodes.forEach(n => {
        const t = parseInt(n.dataset.total || '0', 10) || 0;
        const a = parseFloat(n.dataset.avg || '0') || 0;
        if (t > 0) {
          totalReviews += t;
          weightedSum += a * t;
        }
      });
    } else if (window._reviewSummaryCache) {
      Object.keys(window._reviewSummaryCache).forEach(k => {
        const r = window._reviewSummaryCache[k] || { avg: 0, total: 0 };
        const t = parseInt(r.total || 0, 10) || 0;
        const a = parseFloat(r.avg || 0) || 0;
        if (t > 0) {
          totalReviews += t;
          weightedSum += a * t;
        }
      });
    }

    const totalEl = document.getElementById('stat-reviews-total');
    const avgEl = document.getElementById('stat-reviews-average');
    const avgValueEl = document.getElementById('stat-average-value');
    const avgStarsEl = document.getElementById('stat-average-stars');
    const avgNoteEl = document.getElementById('stat-average-note');

    if (!totalEl || !avgEl) return;

    if (totalReviews === 0) {
      totalEl.textContent = '0';
      avgEl.textContent = '—';
      if (avgValueEl) avgValueEl.textContent = '—';
      if (avgStarsEl) avgStarsEl.innerHTML = '';
      if (avgNoteEl) avgNoteEl.textContent = 'no reviews yet';
    } else {
      const overallAvg = weightedSum / totalReviews;
      totalEl.textContent = String(totalReviews);
      avgEl.textContent = overallAvg.toFixed(2);
      if (avgValueEl) avgValueEl.textContent = overallAvg.toFixed(2);

      // render simple full/empty stars for average (floor)
      if (avgStarsEl) {
        const full = Math.floor(overallAvg);
        let starsHtml = '';
        for (let i = 0; i < 5; i++) {
          if (i < full) starsHtml += '<i class="bi bi-star-fill" style="color:#ffb400;margin-right:2px"></i>';
          else starsHtml += '<i class="bi bi-star" style="color:#d1d5db;margin-right:2px"></i>';
        }
        avgStarsEl.innerHTML = starsHtml;
      }

      if (avgNoteEl) avgNoteEl.textContent = `based on ${totalReviews} review${totalReviews !== 1 ? 's' : ''}`;
    }
  }

  // Handle filter dropdown clicks
  document.addEventListener('click', function (e) {
    const a = e.target.closest('.dropdown-item');
    if (!a) return;
    const filter = a.getAttribute('data-filter');
    if (!filter) return;
    e.preventDefault();
    const btn = document.getElementById('visitedFilterDropdown');
    if (btn) btn.textContent = 'Filter: ' + (filter === 'all' ? 'All' : filter === 'reviewed' ? 'Reviewed' : 'Not Reviewed');
    setVisitedFilter(filter, null);
  });

  // Review Modal Functions
  window.writeReview = function(spotId, spotName) {
    document.getElementById('reviewModalTitle').textContent = 'Write a Review';
    document.getElementById('reviewSpotId').value = spotId;
    document.getElementById('reviewId').value = '';
    document.getElementById('reviewSpotName').textContent = spotName;
    document.getElementById('reviewComment').value = '';
    document.getElementById('reviewRating').value = '0';
    document.getElementById('recommendYes').checked = true;
    document.getElementById('reviewBtnText').textContent = 'Submit Review';
    
    // Reset stars
    document.querySelectorAll('#reviewRatingStars .rating-star').forEach(star => {
      star.classList.remove('filled');
      star.querySelector('i').classList.remove('bi-star-fill');
      star.querySelector('i').classList.add('bi-star');
    });
    
    bootstrap.Modal.getOrCreateInstance(document.getElementById('reviewModal')).show();
  };

  window.viewReview = async function(spotId, reviewId) {
    try {
      const res = await fetch(BASE + `tourist/feedback/${reviewId}`, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('Failed to load review');
      
      const data = await res.json();
      if (data.success && data.review) {
        const review = data.review;
        document.getElementById('viewReviewSpotName').textContent = review.spot_name || '';
        document.getElementById('viewReviewComment').textContent = review.comment || '';
        document.getElementById('viewReviewDate').textContent = formatDate(review.created_at);
        
        // Display rating stars
        const ratingHtml = Array.from({length: 5}, (_, i) => 
          `<i class="bi bi-star${i < review.rating ? '-fill' : ''}"></i>`
        ).join('');
        document.getElementById('viewReviewRating').innerHTML = ratingHtml;
        
        // Display recommendation
        const recommendText = review.recommend == 1 
          ? '<i class="bi bi-hand-thumbs-up-fill text-success"></i> Yes, you recommend this place'
          : '<i class="bi bi-hand-thumbs-down-fill text-danger"></i> You do not recommend this place';
        document.getElementById('viewReviewRecommend').innerHTML = recommendText;
        
        // Store data for edit/delete
        document.getElementById('editReviewBtn').setAttribute('data-spot-id', spotId);
        document.getElementById('editReviewBtn').setAttribute('data-review-id', reviewId);
        document.getElementById('editReviewBtn').setAttribute('data-spot-name', review.spot_name);
        document.getElementById('editReviewBtn').setAttribute('data-rating', review.rating);
        document.getElementById('editReviewBtn').setAttribute('data-comment', review.comment);
        document.getElementById('editReviewBtn').setAttribute('data-recommend', review.recommend);
        document.getElementById('deleteReviewBtn').setAttribute('data-review-id', reviewId);
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('viewReviewModal')).show();
      }
    } catch (err) {
      console.error('Error loading review:', err);
      alert('Failed to load review. Please try again.');
    }
  };

  // Star rating interaction
  document.addEventListener('DOMContentLoaded', () => {
    const starsContainer = document.getElementById('reviewRatingStars');
    const ratingInput = document.getElementById('reviewRating');
    
    // Handle star click
    starsContainer.addEventListener('click', (e) => {
      const star = e.target.closest('.rating-star');
      if (!star) return;
      
      const rating = parseInt(star.getAttribute('data-rating'));
      ratingInput.value = rating;
      
      // Update star display
      document.querySelectorAll('#reviewRatingStars .rating-star').forEach((s, idx) => {
        const icon = s.querySelector('i');
        if (idx < rating) {
          s.classList.add('filled', 'active');
          icon.classList.remove('bi-star');
          icon.classList.add('bi-star-fill');
        } else {
          s.classList.remove('filled', 'active');
          icon.classList.remove('bi-star-fill');
          icon.classList.add('bi-star');
        }
      });
    });
    
    // Handle star hover
    starsContainer.addEventListener('mouseover', (e) => {
      const star = e.target.closest('.rating-star');
      if (!star) return;
      
      const rating = parseInt(star.getAttribute('data-rating'));
      
      document.querySelectorAll('#reviewRatingStars .rating-star').forEach((s, idx) => {
        const icon = s.querySelector('i');
        if (idx < rating) {
          s.classList.add('hovered');
        } else {
          s.classList.remove('hovered');
        }
      });
    });
    
    // Clear hover
    starsContainer.addEventListener('mouseout', () => {
      document.querySelectorAll('#reviewRatingStars .rating-star').forEach(s => {
        s.classList.remove('hovered');
      });
    });

    // Submit review
    document.getElementById('submitReviewBtn').addEventListener('click', async () => {
      const spotId = document.getElementById('reviewSpotId').value;
      const reviewId = document.getElementById('reviewId').value;
      const rating = document.getElementById('reviewRating').value;
      const comment = document.getElementById('reviewComment').value.trim();
      const recommend = document.querySelector('input[name="reviewRecommend"]:checked').value;
      
      if (!rating || rating == '0') {
        alert('Please select a rating');
        return;
      }
      
      if (comment.length < 10) {
        alert('Review must be at least 10 characters long');
        return;
      }
      
      const btn = document.getElementById('submitReviewBtn');
      const spinner = document.getElementById('reviewSpinner');
      const btnText = document.getElementById('reviewBtnText');
      
      btn.disabled = true;
      spinner.classList.remove('d-none');
      btnText.textContent = 'Submitting...';
      
      try {
        const url = reviewId ? BASE + `tourist/feedback/${reviewId}` : BASE + 'tourist/feedback';
        const method = reviewId ? 'PUT' : 'POST';
        
        const res = await fetch(url, {
          method: method,
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({ 
            spot_id: spotId, 
            rating: rating, 
            comment: comment, 
            recommend: recommend,
            status: 'published'
          })
        });
        
        const data = await res.json();
        
        if (data.success) {
          alert(reviewId ? 'Review updated successfully!' : 'Review submitted successfully!');
          bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
          loadVisitedPlaces(); // Reload to show updated button
        } else {
          alert(data.message || 'Failed to submit review');
        }
      } catch (err) {
        console.error('Error submitting review:', err);
        alert('An error occurred. Please try again.');
      } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
        btnText.textContent = reviewId ? 'Save Changes' : 'Save Review';
      }
    });

    // Edit review button
    document.getElementById('editReviewBtn').addEventListener('click', () => {
      const btn = document.getElementById('editReviewBtn');
      const spotId = btn.getAttribute('data-spot-id');
      const reviewId = btn.getAttribute('data-review-id');
      const spotName = btn.getAttribute('data-spot-name');
      const rating = parseInt(btn.getAttribute('data-rating'));
      const comment = btn.getAttribute('data-comment');
      const recommend = btn.getAttribute('data-recommend');
      
      // Close view modal
      bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
      
      // Open edit modal
      document.getElementById('reviewModalTitle').textContent = 'Edit Your Review';
      document.getElementById('reviewSpotId').value = spotId;
      document.getElementById('reviewId').value = reviewId;
      document.getElementById('reviewSpotName').textContent = spotName;
      document.getElementById('reviewComment').value = comment;
      document.getElementById('reviewRating').value = rating;
      document.getElementById('reviewBtnText').textContent = 'Update Review';
      
      if (recommend == '1') {
        document.getElementById('recommendYes').checked = true;
      } else {
        document.getElementById('recommendNo').checked = true;
      }
      
      // Set stars
      document.querySelectorAll('#reviewRatingStars .rating-star').forEach((s, idx) => {
        const icon = s.querySelector('i');
        if (idx < rating) {
          s.classList.add('filled');
          icon.classList.remove('bi-star');
          icon.classList.add('bi-star-fill');
        } else {
          s.classList.remove('filled');
          icon.classList.remove('bi-star-fill');
          icon.classList.add('bi-star');
        }
      });
      
      bootstrap.Modal.getOrCreateInstance(document.getElementById('reviewModal')).show();
    });

    // Delete review button
    document.getElementById('deleteReviewBtn').addEventListener('click', async () => {
      if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        return;
      }
      
      const reviewId = document.getElementById('deleteReviewBtn').getAttribute('data-review-id');
      
      try {
        const res = await fetch(BASE + `tourist/feedback/${reviewId}`, {
          method: 'DELETE',
          credentials: 'same-origin'
        });
        
        const data = await res.json();
        
        if (data.success) {
          alert('Review deleted successfully!');
          bootstrap.Modal.getInstance(document.getElementById('viewReviewModal')).hide();
          loadVisitedPlaces(); // Reload to show write button again
        } else {
          alert(data.message || 'Failed to delete review');
        }
      } catch (err) {
        console.error('Error deleting review:', err);
        alert('An error occurred. Please try again.');
      }
    });
  });
})();
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

        <!-- Review Modal (Write/Edit Review) -->
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> <span id="reviewModalTitle">Write a Review</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="reviewForm">
                  <input type="hidden" id="reviewSpotId" value="">
                  <input type="hidden" id="reviewId" value="">
                  
                  <div class="mb-3">
                    <label class="form-label"><strong>Place</strong></label>
                    <h6 id="reviewSpotName" class="text-primary fw-bold mb-0"></h6>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-12">
                      <label class="form-label"><strong>Rating</strong></label>
                      <div id="reviewRatingStars" class="d-flex align-items-center gap-2">
                        <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-rating="1" aria-label="1 star"><i class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-rating="2" aria-label="2 stars"><i class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-rating="3" aria-label="3 stars"><i class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-rating="4" aria-label="4 stars"><i class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn p-0 border-0 bg-transparent rating-star" data-rating="5" aria-label="5 stars"><i class="bi bi-star-fill"></i></button>
                        <input type="hidden" id="reviewRating" value="0" required>
                      </div>
                      <div class="form-text">Click or hover over stars to rate.</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="reviewComment" class="form-label"><strong>Your review</strong></label>
                    <textarea class="form-control" id="reviewComment" rows="4" placeholder="Share your experience..." required></textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label"><strong>Would you recommend this place?</strong></label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="reviewRecommend" id="recommendYes" value="1" checked>
                      <label class="form-check-label" for="recommendYes">
                        <i class="bi bi-hand-thumbs-up-fill text-success"></i> Yes, I recommend it
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="reviewRecommend" id="recommendNo" value="0">
                      <label class="form-check-label" for="recommendNo">
                        <i class="bi bi-hand-thumbs-down-fill text-danger"></i> No, not really
                      </label>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-booking ocean" id="submitReviewBtn" style="">
                  <i class="bi bi-check-circle"></i> <span id="reviewBtnText">Save Review</span>
                  <span class="spinner-border spinner-border-sm ms-2 d-none" id="reviewSpinner" role="status" aria-hidden="true"></span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- View Review Modal (Read-only) -->
        <div class="modal fade" id="viewReviewModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header" style="background: linear-gradient(135deg, #004b8d 0%, #002e55 50%, #000814 100%); color: #fff; border: none;">
                <h5 class="modal-title"><i class="bi bi-star-fill"></i> Your Review</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label"><strong>Place</strong></label>
                  <h6 id="viewReviewSpotName" class="text-primary fw-bold mb-0"></h6>
                </div>
                
                <div class="mb-3">
                  <label class="form-label"><strong>Your Rating</strong></label>
                  <div id="viewReviewRating" class="d-flex gap-1 mb-2" style="font-size: 1.5rem; color: #ffc107;">
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label"><strong>Your Review</strong></label>
                  <p id="viewReviewComment" class="border rounded p-3 bg-light" style="white-space: pre-wrap;"></p>
                </div>

                <div class="mb-3">
                  <label class="form-label"><strong>Recommendation</strong></label>
                  <p id="viewReviewRecommend"></p>
                </div>

                <div class="text-muted small">
                  <i class="bi bi-calendar"></i> Reviewed on <span id="viewReviewDate"></span>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-view-details" id="editReviewBtn">
                  <i class="bi bi-pencil"></i> Edit Review
                </button>
                <button type="button" class="btn-booking ocean danger" id="deleteReviewBtn">
                  <i class="bi bi-trash"></i> Delete Review
                </button>
              </div>
            </div>
          </div>
        </div>

</body>
</html>