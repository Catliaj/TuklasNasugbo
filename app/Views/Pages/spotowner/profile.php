<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Spot Owner Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
</head>

<body>
    <div class="d-flex min-vh-100" id="wrapper">
        <!-- Sidebar -->
              <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="sidebar-logo">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="sidebar-title mb-0">Tourist Spot</h3>
                        <p class="sidebar-subtitle mb-0">Owner Dashboard</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/dashboard" class="sidebar-link" data-page="home">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/bookings" class="sidebar-link" data-page="bookings">
                            <i class="bi bi-calendar-check"></i>
                            <span>Booking Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/earnings" class="sidebar-link" data-page="earnings">
                            <i class="bi bi-graph-up"></i>
                            <span>Earnings & Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/mySpots" class="sidebar-link" data-page="manage">
                            <i class="bi bi-geo-alt"></i>
                            <span>Manage Spot</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/settings" class="sidebar-link active" data-page="profile">
                            <i class="bi bi-person"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="/" class="sidebar-link text-danger" id="logoutBtn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">
            <!-- Mobile Header -->
            <div class="mobile-header d-lg-none">
                <button class="btn btn-link" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <div class="mobile-logo">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="mobile-title mb-0">Tourist Spot</h3>
                        <p class="mobile-subtitle mb-0">Owner Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <!-- Content will be loaded here dynamically -->
                <div class="container-fluid">
                    <div class="mb-4">
                        <h2>Profile Settings</h2>
                        <p class="text-muted-custom">Manage your account information and security</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <!-- Personal Information -->
                            <div class="custom-card mb-4">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Personal Information</h3>
                                    <p class="custom-card-description">Update your personal details</p>
                                </div>
                                <div class="custom-card-body">
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <div class="avatar">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Maria" alt="Profile">
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm mb-2" id="changePhotoBtn">Change Photo</button>
                                            <p class="text-muted-custom small mb-0">JPG, PNG or GIF. Max 2MB</p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstName" value="Maria">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" value="Santos">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" value="maria.santos@example.com">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                            <input type="tel" class="form-control" id="phone" value="+63 912 345 6789">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Business Information -->
                            <div class="custom-card mb-4">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Business Information</h3>
                                    <p class="custom-card-description">Details about your tourist spot business</p>
                                </div>
                                <div class="custom-card-body">
                                    <div class="mb-3">
                                        <label for="businessName" class="form-label">Business Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                            <input type="text" class="form-control" id="businessName" value="Sunset Beach Paradise">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="businessAddress" class="form-label">Business Address</label>
                                        <input type="text" class="form-control" id="businessAddress" value="Coastal Highway, Bay City">
                                    </div>

                                    <div class="mb-3">
                                        <label for="taxId" class="form-label">Tax ID / Business Registration</label>
                                        <input type="text" class="form-control" id="taxId" value="123-456-789-000">
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="custom-card mb-4">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Change Password</h3>
                                    <p class="custom-card-description">Update your password to keep your account secure</p>
                                </div>
                                <div class="custom-card-body">
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="currentPassword">
                                    </div>

                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword">
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword">
                                    </div>

                                    <button class="btn btn-outline-primary" id="updatePasswordBtn">Update Password</button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-outline-primary" id="cancelProfileBtn">Cancel</button>
                                <button class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Account Status -->
                            <div class="custom-card mb-4">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Account Status</h3>
                                </div>
                                <div class="custom-card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted-custom">Account Type</span>
                                        <span class="badge bg-primary">Owner</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted-custom">Member Since</span>
                                        <span>Jan 2024</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted-custom">Verification Status</span>
                                        <span class="text-success">✓ Verified</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Danger Zone -->
                            <div class="custom-card danger-zone">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title text-danger">Danger Zone</h3>
                                    <p class="custom-card-description">Irreversible account actions</p>
                                </div>
                                <div class="custom-card-body">
                                    <button class="btn btn-outline-danger w-100 mb-3" id="deactivateAccountBtn">
                                        Deactivate Account
                                    </button>
                                    <button class="btn btn-danger w-100" id="deleteAccountBtn">
                                        Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Photo Modal -->
                <div class="modal fade" id="changePhotoModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Change Profile Photo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted-custom mb-4">Upload a new profile photo. Recommended size: 400x400px</p>

                                <div class="text-center mb-4">
                                    <div class="avatar mx-auto mb-3" style="width: 128px; height: 128px;">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Maria" alt="Profile" id="photoPreview">
                                    </div>
                                </div>

                                <div class="upload-area" id="photoUploadArea">
                                    <input type="file" id="photoUploadInput" class="d-none" accept="image/*">
                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <p class="mb-1">Click to upload or drag and drop</p>
                                    <p class="text-muted-custom small mb-0">JPG, PNG or GIF (max. 2MB)</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="confirmPhotoChange()">Save Photo</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Password Modal -->
                <div class="modal fade" id="updatePasswordModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Password?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to update your password? You will need to use the new password for future logins.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="confirmPasswordUpdate()">Update Password</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Profile Modal -->
                <div class="modal fade" id="saveProfileModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Save Changes?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to save these changes to your profile? This will update your account information.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="confirmProfileSave()">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancel Profile Modal -->
                <div class="modal fade" id="cancelProfileModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Discard Changes?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to discard your changes? All unsaved modifications will be lost.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Continue Editing</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Discard Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deactivate Account Modal -->
                <div class="modal fade" id="deactivateAccountModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Deactivate Account?
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Your account will be temporarily disabled and you won't be able to receive bookings. You can reactivate your account anytime by logging in again.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" onclick="confirmDeactivate()">Deactivate Account</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Modal -->
                <div class="modal fade" id="deleteAccountModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Account Permanently?
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>This action cannot be undone. This will permanently delete your account, all your tourist spot data, bookings, and remove all associated information from our servers.</p>

                                <div class="alert alert-danger">
                                    <p class="mb-2">To confirm deletion, please type: <strong>DELETE</strong></p>
                                    <input type="text" class="form-control" id="deleteConfirmInput" placeholder="Type DELETE to confirm">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="assets/js/spotownerJS/shared-data.js"></script>
    <!-- ✅ Fixed path -->
    <script src="assets/js/spotownerJS/profile.js"></script>
</body>

</html>