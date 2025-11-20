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
    
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/visited.css')?>">
</head>
<body>
    <div class="dashboard-wrapper">
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
                        <a href="/tourist/visits" class="nav-link active">
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

        <main class="main-content">
            <div class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Visited Places</h1>
                </div>
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
                                        <h6>Check-in Logged</h6>
                                        <p>Added Mount Batulao to your visited list</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>1 hour ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item unread">
                                <div class="notification-content">
                                    <div class="notification-icon info"><i class="bi bi-star-fill"></i></div>
                                    <div class="notification-text">
                                        <h6>Review Reminder</h6>
                                        <p>Share thoughts on Caleruega Church</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>3 hours ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon warning"><i class="bi bi-calendar-event"></i></div>
                                    <div class="notification-text">
                                        <h6>Upcoming Trip</h6>
                                        <p>Fortune Island this weekend</p>
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

            <div class="visited-container">
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="stat-content"><h3>Total Places Visited</h3><p>12</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
                        <div class="stat-content"><h3>Trips Completed</h3><p>5</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-camera"></i></div>
                        <div class="stat-content"><h3>Photos Shared</h3><p>48</p></div>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="timeline-section">
                    <div class="section-header">
                        <h3 class="section-title">Travel History</h3>
                        <div class="view-toggle">
                            <button class="toggle-btn active" onclick="switchView('timeline', this)"><i class="bi bi-list-ul"></i> Timeline</button>
                            <button class="toggle-btn" onclick="switchView('grid', this)"><i class="bi bi-grid"></i> Grid</button>
                        </div>
                    </div>

                    <!-- Timeline View -->
                    <div class="timeline active" id="timelineView">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div><h4 class="timeline-title">Mount Batulao Hike</h4><p class="timeline-date"><i class="bi bi-calendar"></i> November 20, 2024</p></div>
                                    <span class="check-in-badge"><i class="bi bi-check-circle"></i> Checked In</span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-people"></i><span>With 3 friends</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-star-fill"></i><span>Rated 5/5</span></div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">Amazing sunrise hike! The rolling hills were breathtaking. Definitely coming back!</p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=100&h=100&fit=crop" alt="Photo" class="photo-thumbnail">
                                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=100&h=100&fit=crop" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+5</div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div><h4 class="timeline-title">Caleruega Church Visit</h4><p class="timeline-date"><i class="bi bi-calendar"></i> October 15, 2024</p></div>
                                    <span class="check-in-badge"><i class="bi bi-check-circle"></i> Checked In</span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item"><i class="bi bi-geo-alt"></i><span>Caleruega, Nasugbu</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-people"></i><span>Solo trip</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-star-fill"></i><span>Rated 5/5</span></div>
                                </div>
                                <p style="color: #666; font-size: 0.9rem;">Perfect place for meditation and reflection. The gardens are beautiful!</p>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1519290916420-e67e00a83cde?w=100&h=100&fit=crop" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+3</div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <div><h4 class="timeline-title">Fortune Island Adventure</h4><p class="timeline-date"><i class="bi bi-calendar"></i> September 8, 2024</p></div>
                                    <span class="check-in-badge"><i class="bi bi-check-circle"></i> Checked In</span>
                                </div>
                                <div class="timeline-meta">
                                    <div class="timeline-meta-item"><i class="bi bi-geo-alt"></i><span>Fortune Island</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-people"></i><span>With partner</span></div>
                                    <div class="timeline-meta-item"><i class="bi bi-star-fill"></i><span>Rated 5/5</span></div>
                                </div>
                                <div class="timeline-photos">
                                    <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=100&h=100&fit=crop" alt="Photo" class="photo-thumbnail">
                                    <div class="more-photos">+7</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div class="grid-view" id="gridView">
                        <div class="grid-card">
                           <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&h=200&fit=crop" alt="Mount Batulao">
                           <div class="grid-card-content">
                               <h4>Mount Batulao</h4>
                               <p><i class="bi bi-calendar"></i> Nov 20, 2024</p>
                           </div>
                        </div>
                        <div class="grid-card">
                           <img src="https://images.unsplash.com/photo-1519290916420-e67e00a83cde?w=300&h=200&fit=crop" alt="Caleruega Church">
                           <div class="grid-card-content">
                               <h4>Caleruega Church</h4>
                               <p><i class="bi bi-calendar"></i> Oct 15, 2024</p>
                           </div>
                        </div>
                        <div class="grid-card">
                           <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=300&h=200&fit=crop" alt="Fortune Island">
                           <div class="grid-card-content">
                               <h4>Fortune Island</h4>
                               <p><i class="bi bi-calendar"></i> Sep 8, 2024</p>
                           </div>
                        </div>
                        <div class="grid-card">
                           <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=300&h=200&fit=crop" alt="Canyon Cove">
                           <div class="grid-card-content">
                               <h4>Canyon Cove Resort</h4>
                               <p><i class="bi bi-calendar"></i> Aug 22, 2024</p>
                           </div>
                        </div>
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
                                JD
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
                                <input class="form-control" id="profileFirstName" value="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profileLastName">Last Name</label>
                                <input class="form-control" id="profileLastName" value="Dela Cruz" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileEmail">Email Address</label>
                                <input type="email" class="form-control" id="profileEmail" value="juan.delacruz@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profilePhone">Phone Number</label>
                                <input type="tel" class="form-control" id="profilePhone" value="+63 912 345 6789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="profileBirthdate">Birthdate</label>
                                <input type="date" class="form-control" id="profileBirthdate" value="1995-05-15">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileAddress">Address</label>
                                <textarea class="form-control" id="profileAddress" rows="2">Nasugbu, Batangas, Philippines</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profileBio">Bio</label>
                                <textarea class="form-control" id="profileBio" rows="3">Adventure seeker and travel enthusiast exploring Nasugbu!</textarea>
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
    
    <script>
        function toggleSidebar() {
          const sidebar = document.getElementById('sidebar');
          sidebar.classList.toggle('show');
        }

        function switchView(view, clickedButton) {
            // Update button active state
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            clickedButton.classList.add('active');

            const timelineView = document.getElementById('timelineView');
            const gridView = document.getElementById('gridView');

            // Update view active state
            timelineView.classList.remove('active');
            gridView.classList.remove('active');

            if (view === 'timeline') {
                timelineView.classList.add('active');
            } else {
                gridView.classList.add('active');
            }
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = event.target.closest('.sidebar-toggle');
            
            if (window.innerWidth < 992) {
                if (sidebar.classList.contains('show') && !sidebar.contains(event.target) && !toggleButton) {
                    sidebar.classList.remove('show');
                }
            }
        });

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
        document.addEventListener('click', (e) => {
            const nd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            const bell = document.querySelector('.notification-btn');
            const avatar = document.querySelector('.user-avatar');
            if (nd && !nd.contains(e.target) && !bell.contains(e.target)) nd.classList.remove('show');
            if (ud && !ud.contains(e.target) && !avatar.contains(e.target)) ud.classList.remove('show');
        });
        function showToast(title, msg){
            const container = document.getElementById('toastContainer');
            const el = document.createElement('div');
            el.className = 'toast text-bg-primary';
            el.role = 'alert'; el.ariaLive = 'assertive'; el.ariaAtomic = 'true';
            el.innerHTML = `<div class="d-flex align-items-center">
                <div class="toast-body"><strong>${title}:</strong> ${msg}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
            container.appendChild(el);
            const t = new bootstrap.Toast(el, { delay: 3000 });
            t.show(); el.addEventListener('hidden.bs.toast', () => el.remove());
        }
        function handleLogout(e){ e?.preventDefault?.(); logout(); }
        function openProfile(event) {
            event?.preventDefault?.();
            document.getElementById('userDropdown')?.classList.remove('show');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('profileModal')).show();
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
    </style>
</body>
</html>