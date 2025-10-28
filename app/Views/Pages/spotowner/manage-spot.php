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
                        <a href="/spotowner/mySpots" class="sidebar-link active" data-page="manage">
                            <i class="bi bi-geo-alt"></i>
                            <span>Manage Spot</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/settings" class="sidebar-link" data-page="profile">
                            <i class="bi bi-person"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="#" class="sidebar-link text-danger" id="logoutBtn">
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

                <div class="container-fluid px-0">
                    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2>Manage Tourist Spots</h2>
                            <p class="text-muted-custom">Update information and settings for your tourist spots</p>
                        </div>
                        <button class="btn btn-primary" id="addNewSpotManageBtn">
                            <i class="bi bi-plus-circle me-2"></i>Add New Spot
                        </button>
                    </div>

                    <!-- Filter and Search -->
                    <div class="custom-card mb-4">
                        <div class="custom-card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="searchSpots" placeholder="Search tourist spots...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="filterStatus">
                                        <option value="all">All Spots</option>
                                        <option value="active">Active Only</option>
                                        <option value="inactive">Inactive Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tourist Spots Grid -->
                    <div class="row g-4" id="manageSpotsGrid">
                        <!-- Spot cards will be loaded here -->
                    </div>
                </div>

                <!-- Add New Spot Modal -->
                <div class="modal fade" id="addNewSpotModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Tourist Spot</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <!-- Basic Information -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Basic Information</h3>
                                                <p class="custom-card-description">Essential details about your tourist spot</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="mb-3">
                                                    <label for="newSpotName" class="form-label">Spot Name</label>
                                                    <input type="text" class="form-control" id="newSpotName" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="newSpotDescription" class="form-label">Description</label>
                                                    <textarea class="form-control" id="newSpotDescription" rows="4" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="newSpotLocation" class="form-label">Location</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                        <input type="text" class="form-control" id="newSpotLocation" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="newSpotAmenities" class="form-label">Amenities</label>
                                                    <input type="text" class="form-control" id="newSpotAmenities" placeholder="Comma-separated list of amenities">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pricing & Capacity -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Pricing & Capacity</h3>
                                                <p class="custom-card-description">Set your pricing and visitor limits</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="newSpotPrice" class="form-label">Price per Person (₱)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                            <input type="number" class="form-control" id="newSpotPrice" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="newSpotMaxVisitors" class="form-label">Max Visitors per Day</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                                                            <input type="number" class="form-control" id="newSpotMaxVisitors" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Operating Hours -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Operating Hours</h3>
                                                <p class="custom-card-description">Set your opening and closing times</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="newSpotOpenTime" class="form-label">Opening Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" id="newSpotOpenTime" value="09:00" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="newSpotCloseTime" class="form-label">Closing Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" id="newSpotCloseTime" value="18:00" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <!-- Spot Status -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Spot Status</h3>
                                                <p class="custom-card-description">Control visibility and availability</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <label class="form-label mb-0">Active Status</label>
                                                        <p class="text-muted-custom small mb-0">Make spot available for booking</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="newSpotActiveStatus" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Spot Images -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Spot Images</h3>
                                                <p class="custom-card-description">Upload photos of your spot</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="upload-area mb-3" id="newSpotUploadArea">
                                                    <input type="file" id="newSpotImageInput" class="d-none" accept="image/*" multiple>
                                                    <div class="upload-icon">
                                                        <i class="bi bi-cloud-upload"></i>
                                                    </div>
                                                    <p class="mb-1"><span class="text-ocean-medium">Click to upload</span></p>
                                                    <p class="text-muted-custom small mb-0">PNG, JPG (max 2MB each)</p>
                                                </div>
                                                <div id="newSpotImagePreview" class="d-none">
                                                    <div id="newSpotPreviewGrid" class="d-flex flex-column gap-2">
                                                        <!-- Images will be added here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveNewSpotBtn">Add Tourist Spot</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Spot Modal -->
                <div class="modal fade" id="editSpotModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Tourist Spot</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="editSpotModalBody" style="max-height: 70vh; overflow-y: auto;">
                                <!-- Content loaded dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteSpotModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Tourist Spot?
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong id="deleteSpotName"></strong>?</p>
                                <p class="text-danger">This action cannot be undone. All bookings and data associated with this spot will be permanently deleted.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Spot</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Images Modal (for Edit) -->
                <div class="modal fade" id="uploadImagesModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Manage Spot Images</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted-custom mb-4">Upload and manage photos of your tourist spot. Visitors will see these images when browsing.</p>

                                <!-- Upload Area -->
                                <div class="upload-area mb-4" id="uploadArea">
                                    <input type="file" id="imageUploadInput" class="d-none" accept="image/*" multiple>
                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <p class="mb-1"><span class="text-ocean-medium">Click to upload</span> or drag and drop</p>
                                    <p class="text-muted-custom small mb-0">PNG, JPG, GIF up to 10MB</p>
                                </div>

                                <!-- Image Preview Grid -->
                                <div id="imagePreviewContainer">
                                    <h6 class="mb-3">Uploaded Images (<span id="imageCount">0</span>)</h6>
                                    <div class="image-preview-grid" id="imagePreviewGrid">
                                        <!-- Images will be loaded here -->
                                    </div>
                                    <p class="text-muted-custom small mt-3">The first image will be used as the main photo. Drag to reorder.</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveImagesBtn">Save Images</button>
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
    <script src="<?= base_url("assets/js/spotownerJS/shared-data.js")?>"></script>
    <!-- ✅ Fixed path -->
   
    <script src="<?= base_url("assets/js/spotownerJS/manage-spot.js")?>"></script>
    
</body>

</html>