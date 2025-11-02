<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/profile.css")?>">

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
                        <a href="/tourist/budget" class="nav-link">
                            <i class="bi bi-wallet2"></i>
                            <span>Budget Tracker</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/favorites" class="nav-link">
                            <i class="bi bi-heart"></i>
                            <span>Favorites</span>
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
                    <li class="nav-item">
                        <a href="/under-development" class="nav-link">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/profile" class="nav-link active">
                            <i class="bi bi-person-circle"></i>
                            <span>Profile</span>
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
                    <h1 class="page-title">My Profile</h1>
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

            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        JD
                        <label class="avatar-upload">
                            <i class="bi bi-camera" style="color: white;"></i>
                            <input type="file" accept="image/*">
                        </label>
                    </div>
                    <div class="profile-info">
                        <h2>John Doe</h2>
                        <p style="opacity: 0.9; margin: 0;">johndoe@example.com</p>
                        <div class="profile-meta">
                            <div class="profile-meta-item">
                                <i class="bi bi-calendar-check"></i>
                                <span>Member since Oct 2024</span>
                            </div>
                            <div class="profile-meta-item">
                                <i class="bi bi-geo-alt"></i>
                                <span>12 places visited</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <!-- Personal Information -->
                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="bi bi-person"></i> Personal Information
                        </h3>
                        <form id="personalInfoForm">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="John Doe">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="johndoe@example.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" value="+63 912 345 6789">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" value="Manila, Philippines">
                            </div>
                            <button type="submit" class="btn-save" onclick="savePersonalInfo(event)">
                                <i class="bi bi-check-circle"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="bi bi-shield-lock"></i> Change Password
                        </h3>
                        <form id="passwordForm">
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" placeholder="Enter current password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" placeholder="Confirm new password">
                            </div>
                            <button type="submit" class="btn-save" onclick="changePassword(event)">
                                <i class="bi bi-key"></i> Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="profile-section full-width">
                        <h3 class="section-title">
                            <i class="bi bi-bell"></i> Notification Preferences
                        </h3>
                        
                        <div class="switch-container">
                            <div>
                                <div class="switch-label">Email Notifications</div>
                                <div class="switch-description">Receive booking confirmations and updates via email</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="switch-container">
                            <div>
                                <div class="switch-label">SMS Alerts</div>
                                <div class="switch-description">Get important booking reminders via SMS</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="switch-container">
                            <div>
                                <div class="switch-label">Promotional Emails</div>
                                <div class="switch-description">Receive special offers and travel deals</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="switch-container">
                            <div>
                                <div class="switch-label">Review Reminders</div>
                                <div class="switch-description">Get reminded to review places you've visited</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Privacy & Security -->
                    <div class="profile-section full-width">
                        <h3 class="section-title">
                            <i class="bi bi-shield-check"></i> Privacy & Security
                        </h3>
                        
                        <div class="switch-container">
                            <div>
                                <div class="switch-label">Profile Visibility</div>
                                <div class="switch-description">Allow other users to see your profile</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="switch-container">
                            <div>
                                <div class="switch-label">Show Travel Stats</div>
                                <div class="switch-description">Display your visited places and reviews publicly</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="danger-zone">
                            <h4><i class="bi bi-exclamation-triangle"></i> Danger Zone</h4>
                            <p style="color: #666; font-size: 0.9rem; margin-bottom: 0;">
                                Once you delete your account, there is no going back. Please be certain.
                            </p>
                            <button class="btn-danger" onclick="deleteAccount()">
                                <i class="bi bi-trash"></i> Delete Account
                            </button>
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

        function savePersonalInfo(event) {
            event.preventDefault();
            alert('Personal information updated successfully! - Backend integration needed');
        }

        function changePassword(event) {
            event.preventDefault();
            alert('Password changed successfully! - Backend integration needed');
        }

        function deleteAccount() {
            if (confirm('Are you absolutely sure? This action cannot be undone.')) {
                if (confirm('This will permanently delete all your data. Continue?')) {
                    alert('Account deletion - Backend integration needed');
                }
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
