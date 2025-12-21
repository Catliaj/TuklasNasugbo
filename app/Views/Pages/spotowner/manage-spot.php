<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tourist Spot Owner Dashboard</title>

 


    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
    <!-- Leaflet Core -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />






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
                        <a href="/spotowner/dashboard" class="sidebar-link " data-page="home">
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
                </ul>
            </div>

            
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">
            <!-- Mobile Header -->
            <?= view('Pages/spotowner/_mobile_header', ['subtitle' => 'Manage Spots', 'FullName' => $FullName ?? null, 'email' => $email ?? null]) ?>

            <!-- Page Content - This will be populated by JavaScript -->

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
             <!-- Content will be loaded here dynamically -->
<div class="container-fluid px-0">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2></h2>
            <p class="text-muted-custom"></p>
        </div>
    </div>

    <div class="mt-3 mb-4"> <!-- added mb-4 for more spacing -->
    <button class="btn btn-primary mt-2" id="addNewSpotManageBtn">
        <i class="bi bi-plus-circle me-2"></i>Add New Spot
    </button>
</div>

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
                        <?php if (!empty($touristSpots)): ?>
                            <?php foreach ($touristSpots as $spot): ?>
                                <div class="col-lg-4 col-md-6" data-spot-id="<?= $spot['spot_id'] ?>" data-status="<?= $spot['status'] ?>">
                                    <div class="custom-card h-100">
                                        <div class="position-relative">
                                            <?php
                                                $firstGalleryUrl = (!empty($spot['gallery']) && count($spot['gallery']) > 0) ? $spot['gallery'][0]['image_url'] : null;
                                                $primaryUrl = $spot['primary_image_url'] ?? base_url('uploads/spots/Spot-No-Image.png');
                                                $imgSrc = $firstGalleryUrl ?? $primaryUrl;
                                            ?>
                                            <img src="<?= esc($imgSrc) ?>" onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'" 
                                                alt="<?= esc($spot['spot_name'] ?? $spot['name'] ?? '') ?>" class="img-fluid rounded-top" style="height: 200px; width: 100%; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge <?= $spot['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= esc($spot['status']) ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($spot['gallery']) && count($spot['gallery']) > 0): ?>
                                                <div class="position-absolute bottom-0 end-0 m-3">
                                                    <span class="badge bg-dark bg-opacity-75">
                                                        <i class="bi bi-images me-1"></i><?= count($spot['gallery']) ?> photos
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="custom-card-body">
                                            <h4 class="custom-card-title"><?= esc($spot['spot_name']) ?></h4>
                                            <p class="text-muted-custom mb-2">
                                                <i class="bi bi-geo-alt me-1"></i><?= esc($spot['location']) ?>
                                            </p>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted-custom small">Price:</span>
                                                    <span class="fw-medium">₱<?= esc($spot['price_per_person']) ?>/person</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted-custom small">Capacity:</span>
                                                    <span class="fw-medium"><?= esc($spot['capacity']) ?> visitors</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted-custom small">Hours:</span>
                                                    <span class="fw-medium"><?= esc($spot['opening_time']) ?> - <?= esc($spot['closing_time']) ?></span>
                                                </div>
                                                <!-- Example Rating (if available in DB) -->
                                                <?php if(isset($spot['rating']) && isset($spot['reviews'])): ?>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted-custom small">Rating:</span>
                                                        <span class="fw-medium">
                                                            <i class="bi bi-star-fill text-warning"></i> <?= esc($spot['rating']) ?> (<?= esc($spot['reviews']) ?>)
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button 
                                                    class="btn btn-primary btn-edit-spot" 
                                                    data-spot-id="<?= $spot['spot_id'] ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>


                                                <a href="<?= base_url('/spotowner/my-spots/delete/' . $spot['spot_id']) ?>" 
                                                class="btn btn-outline-danger flex-fill" 
                                                onclick="return confirm('Are you sure you want to delete <?= esc($spot['spot_name']) ?>?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-center text-muted">No tourist spots available.</p>
                            </div>
                        <?php endif; ?>
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
                                <form  action="<?= base_url('/spotowner/my-spots/store') ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
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
                                                    <label for="spot_name" class="form-label">Spot Name</label>
                                                    <input type="text" class="form-control" name="spot_name" id="spot_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="location" class="form-label">Location Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                        <input type="text" class="form-control" name="location" id="location" required>
                                                       
                                                    </div>
                                                </div>

                                                <!-- Map Container -->
                                                <div class="mb-3">
                                                    <label class="form-label">Pin Location on Map</label>
                                                    <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                                                </div>

                                                <!-- Hidden Coordinate Fields -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="latitude" class="form-label">Latitude</label>
                                                        <input type="text" class="form-control" name="latitude" id="latitude" readonly required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="longitude" class="form-label">Longitude</label>
                                                        <input type="text" class="form-control" name="longitude" id="longitude" readonly required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="category" class="form-label">Category</label>
                                                    <select class="form-select" name="category" id="category" required>
                                                        <!-- 'Historical', 'Cultural', 'Natural', 'Recreational', 'Religious', 'Adventure', 'Ecotourism', 'Urban', 'Rural' ,'Beach' ,'Mountain' ,'Resort', 'Park', 'Restaurant' -->
                                                        <option value="" disabled selected>Select category</option>
                                                        <option value="Historical">Historical</option>
                                                        <option value="Cultural">Cultural</option>
                                                        <option value="Natural">Natural</option>
                                                        <option value="Recreational">Recreational</option>
                                                        <option value="Religious">Religious</option>
                                                        <option value="Adventure">Adventure</option>
                                                        <option value="Ecotourism">Ecotourism</option>
                                                        <option value="Urban">Urban</option>
                                                        <option value="Rural">Rural</option>
                                                        <option value="Beach">Beach</option>
                                                        <option value="Mountain">Mountain</option>
                                                        <option value="Resort">Resort</option>
                                                        <option value="Park">Park</option>
                                                        <option value="Restaurant">Restaurant</option>
                                                    </select>
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
                                                        <label for="price_per_person" class="form-label">Regular Price (₱)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                            <input type="number" class="form-control" name="price_per_person" id="price_per_person" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="capacity" class="form-label">Capacity</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                                                            <input type="number" class="form-control" name="capacity" id="capacity" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-2">
                                                    <div class="col-md-6">
                                                        <label for="child_price" class="form-label">Child Price (₱)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                            <input type="number" class="form-control" name="child_price" id="child_price">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="senior_price" class="form-label">Senior Price (₱)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                            <input type="number" class="form-control" name="senior_price" id="senior_price">
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
                                                        <label for="opening_time" class="form-label">Opening Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" name="opening_time" id="opening_time" value="09:00" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="closing_time" class="form-label">Closing Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" name="closing_time" id="closing_time" value="18:00" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="operating_days" class="form-label">Operating Days</label>
                                                    <div class="btn-group d-flex flex-wrap" role="group" id="operatingDaysGroup">
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="monday" value="Monday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="monday">Mon</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="tuesday" value="Tuesday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="tuesday">Tue</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="wednesday" value="Wednesday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="wednesday">Wed</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="thursday" value="Thursday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="thursday">Thu</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="friday" value="Friday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="friday">Fri</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="saturday" value="Saturday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="saturday">Sat</label>
                                                        
                                                        <input type="checkbox" class="btn-check" name="operating_days[]" id="sunday" value="Sunday" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="sunday">Sun</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <!-- Spot Status (Read-only: defaults to Pending) -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Spot Status</h3>
                                                <p class="custom-card-description">New spots require admin approval</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <label class="form-label mb-1">Status</label>
                                                        <p class="text-muted-custom small mb-0">Newly added spots are set to <strong>Pending</strong> until approved by an administrator.</p>
                                                    </div>
                                                    <span class="badge bg-warning text-dark">Pending</span>
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
                                                <div class="mb-3">
                                                    <label for="primary_image" class="form-label">Primary Image</label>
                                                    <input type="file" class="form-control" name="primary_image" id="primary_image" accept="image/*" required>
                                                    <div id="primaryImagePreview" class="mt-2 d-none">
                                                        <img src="" alt="Primary image preview" class="img-thumbnail" style="max-height: 200px">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="gallery_images" class="form-label">Gallery Images</label>
                                                    <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" accept="image/*" multiple>
                                                    <div id="galleryPreview" class="mt-2 row g-2">
                                                        <!-- Gallery previews will be added here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Tourist Spot</button>
                            </div>
                        </form>
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
    <!-- Edit form will be loaded here dynamically -->
</div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Geoapify Autocomplete -->
    

    <!-- Geoapify Map Control -->
    

    <!-- 1. Bootstrap JS (MUST BE BEFORE sidebar.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 2. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 3. Sidebar Toggle (AFTER Bootstrap!) -->
    <script src="<?= base_url('assets/js/sidebar.js')?>"></script>



  
    

<script>
// Global variables
let currentEditingSpot = null;
let newSpotImageData = [];
let currentEditImageIndex = 0;




function initManageSpotPage() {
    // Check if a spot was just added
    <?php if (session()->getFlashdata('spot_added')): ?>
        // Update home page data if it exists
        if (window.opener && !window.opener.closed) {
            // If this was opened from home page, trigger reload there
            try {
                if (typeof window.opener.fetchTouristSpots === 'function') {
                    window.opener.fetchTouristSpots();
                }
            } catch (e) {
                console.log('Could not update home page:', e);
            }
        }
        
        // Update shared data for this page and potentially home
        if (typeof window.fetchManageSpots === 'function') {
            fetchManageSpots();
        }
    <?php endif; ?>
    
    // Load spots grid
    loadManageSpotsGrid();
    
    // Wait for DOM to be fully ready
    const initializeEventListeners = () => {
        const addBtn = document.getElementById('addNewSpotManageBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('addNewSpotModal'));
                modal.show();
                initNewSpotImageUpload();
            });
        }

        // Save new spot button
        const saveNewBtn = document.getElementById('saveNewSpotBtn');
        if (saveNewBtn) {
            saveNewBtn.addEventListener('click', saveNewSpot);
        }

        // Confirm delete button
        const confirmDelBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDelBtn) {
            confirmDelBtn.addEventListener('click', confirmDeleteSpot);
        }

        // Save images button
        const saveImgBtn = document.getElementById('saveImagesBtn');
        if (saveImgBtn) {
            saveImgBtn.addEventListener('click', saveImages);
        }

        // Search functionality
        const searchInput = document.getElementById('searchSpots');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                filterSpots(e.target.value);
            });
        }

        // Filter by status
        const filterSelect = document.getElementById('filterStatus');
        if (filterSelect) {
            filterSelect.addEventListener('change', function(e) {
                filterByStatus(e.target.value);
            });
        }
    };
    
    // Use proper DOM ready check instead of setTimeout
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeEventListeners);
    } else {
        initializeEventListeners();
    }
}

// Bind handlers for image delete/upload inside the edit modal
function bindEditModalImageHandlers(spot) {
    try {
        const spotId = spot.spot_id || spot.id;

        const btnDeletePrimary = document.getElementById('btnDeletePrimary');
        const btnAddPrimaryLabel = document.getElementById('btnAddPrimaryLabel');
        const primaryUploadInput = document.getElementById('primaryUploadInput');

        if (btnDeletePrimary) {
            btnDeletePrimary.addEventListener('click', async function() {
                if (!confirm('Delete primary image?')) return;
                await deletePrimaryImage(spotId);
                await refreshSpotInModal(spotId);
            });
        }

        if (primaryUploadInput) {
            primaryUploadInput.addEventListener('change', async function(e) {
                const f = e.target.files[0];
                if (!f) return;
                const fd = new FormData();
                fd.append('primary_image', f);
                await uploadPrimary(spotId, fd);
                await refreshSpotInModal(spotId);
            });
        }

        // Gallery delete buttons
        document.querySelectorAll('.gallery-delete-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const idx = parseInt(btn.getAttribute('data-index'));
                const dataImageId = btn.getAttribute('data-image-id');

                // Prefer explicit image id from DOM attribute
                if (dataImageId && dataImageId !== '') {
                    if (!confirm('Delete this gallery image?')) return;
                    await deleteGalleryImage(dataImageId);
                    await refreshSpotInModal(spot.spot_id || spot.id);
                    return;
                }

                // Fallback: try to resolve via spot.gallery or spot.images
                const gallery = (spot.gallery && Array.isArray(spot.gallery)) ? spot.gallery : (spot.images || []);
                let item = gallery[idx];

                // If item lacks an id (e.g. frontend list uses URLs only), resolve id by fetching fresh spot data
                if (!item || typeof (item.id || item.image_id) === 'undefined') {
                    // try to get image URL from button attribute
                    const imageUrl = btn.getAttribute('data-image-url');
                    if (!imageUrl) return;
                    try {
                        const resp = await fetch(`<?= base_url('spotowner/my-spots/get-spot') ?>/${spot.spot_id || spot.id}`);
                        if (!resp.ok) throw new Error('Failed to fetch spot details');
                        const fresh = await resp.json();
                        const freshGallery = fresh.gallery || fresh.images || [];
                        // freshGallery items may be objects {image_id, image_url} or {id, url} or strings
                        let found = freshGallery.find(g => (typeof g === 'string' && imageUrl === g) || (g.image_url && g.image_url === imageUrl) || (g.url && g.url === imageUrl) || (g.image && imageUrl.endsWith('/' + g.image)) );
                        if (!found) {
                            alert('Could not resolve gallery image id; try refreshing the page.');
                            return;
                        }
                        // normalize id property
                        const foundId = found.id || found.image_id || found.imageId || null;
                        if (!foundId) {
                            alert('Could not resolve gallery image id; try refreshing the page.');
                            return;
                        }
                        if (!confirm('Delete this gallery image?')) return;
                        await deleteGalleryImage(foundId);
                        await refreshSpotInModal(spot.spot_id || spot.id);
                        return;
                    } catch (err) {
                        console.error('Failed to resolve gallery image id', err);
                        alert('Failed to resolve gallery image id');
                        return;
                    }
                }

                // If we have an item with id property
                const imageIdToDelete = (typeof item === 'object') ? (item.id || item.image_id) : item;
                if (!imageIdToDelete) {
                    alert('Could not determine image id to delete.');
                    return;
                }
                if (!confirm('Delete this gallery image?')) return;
                await deleteGalleryImage(imageIdToDelete);
                await refreshSpotInModal(spot.spot_id || spot.id);
            });
        });

        const galleryUploadInput = document.getElementById('galleryUploadInput');
        if (galleryUploadInput) {
            galleryUploadInput.addEventListener('change', async function(e) {
                const files = Array.from(e.target.files || []);
                if (files.length === 0) return;
                const fd = new FormData();
                files.forEach(f => fd.append('gallery_images[]', f));
                await uploadGallery(spot.spot_id || spot.id, fd);
                await refreshSpotInModal(spot.spot_id || spot.id);
            });
        }

        // If there is no primary image, show add label and hide delete button
        const primaryImageEl = document.getElementById('editSpotImage');
        const addLabel = document.getElementById('btnAddPrimaryLabel');
        if (primaryImageEl && (primaryImageEl.src.indexOf('Spot-No-Image.png') !== -1 || !spot.primary_image)) {
            if (addLabel) addLabel.classList.remove('d-none');
            if (btnDeletePrimary) btnDeletePrimary.classList.add('d-none');
        } else {
            if (addLabel) addLabel.classList.add('d-none');
            if (btnDeletePrimary) btnDeletePrimary.classList.remove('d-none');
        }
    } catch (err) {
        console.error('bindEditModalImageHandlers error', err);
    }
}

async function deletePrimaryImage(spotId) {
    try {
        const res = await fetch(`<?= base_url('spotowner/my-spots/delete-primary') ?>/${spotId}`, { method: 'POST' });
        let data = null;
        try { data = await res.json(); } catch(e){ /* non-json response */ }
        if (!res.ok) {
            const msg = data && data.error ? data.error : (data && data.message ? data.message : res.statusText || 'Server error');
            console.error('deletePrimaryImage server error', res.status, msg, data);
            alert('Failed to delete primary image: ' + msg);
            return false;
        }
        if (data && data.success === false) {
            const msg = data.error || data.message || 'Delete failed';
            console.error('deletePrimaryImage failed:', data);
            alert('Failed to delete primary image: ' + msg);
            return false;
        }

        return true;
    } catch (err) {
        console.error('deletePrimaryImage', err);
        alert('Failed to delete primary image');
        return false;
    }
}

async function deleteGalleryImage(imageId) {
    try {
        const res = await fetch(`<?= base_url('spotowner/my-spots/delete-gallery') ?>/${imageId}`, { method: 'POST' });
        let data = null;
        try { data = await res.json(); } catch(e){ }
        if (!res.ok) {
            const msg = data && data.error ? data.error : (data && data.message ? data.message : res.statusText || 'Server error');
            console.error('deleteGalleryImage server error', res.status, msg, data);
            alert('Failed to delete gallery image: ' + msg);
            return false;
        }
        if (data && data.success === false) {
            const msg = data.error || data.message || 'Delete failed';
            console.error('deleteGalleryImage failed:', data);
            alert('Failed to delete gallery image: ' + msg);
            return false;
        }

        return true;
    } catch (err) {
        console.error('deleteGalleryImage', err);
        alert('Failed to delete gallery image');
        return false;
    }
}

async function uploadPrimary(spotId, formData) {
    try {
        const res = await fetch(`<?= base_url('spotowner/my-spots/upload-primary') ?>/${spotId}`, { method: 'POST', body: formData });
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'Upload failed');
        return data;
    } catch (err) {
        console.error('uploadPrimary', err);
        alert('Failed to upload primary image');
        return null;
    }
}

async function uploadGallery(spotId, formData) {
    try {
        const res = await fetch(`<?= base_url('spotowner/my-spots/upload-gallery') ?>/${spotId}`, { method: 'POST', body: formData });
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'Upload failed');
        return data;
    } catch (err) {
        console.error('uploadGallery', err);
        alert('Failed to upload gallery images');
        return null;
    }
}

async function refreshSpotInModal(spotId) {
    try {
        const res = await fetch(`<?= base_url('spotowner/my-spots/get-spot') ?>/${spotId}`);
        const spot = await res.json();
        const modalBody = document.getElementById('editSpotModalBody');
        modalBody.innerHTML = generateEditSpotModalContent(spot);
        setTimeout(() => bindEditModalImageHandlers(spot), 120);
    } catch (err) {
        console.error('refreshSpotInModal', err);
    }
}


function initNewSpotImageUpload() {
    const uploadArea = document.getElementById('newSpotUploadArea');
    const fileInput = document.getElementById('newSpotImageInput');
    
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                files.forEach(file => {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        newSpotImageData.push(e.target.result);
                        updateNewSpotImagePreview();
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
}

function updateNewSpotImagePreview() {
    const previewContainer = document.getElementById('newSpotImagePreview');
    const previewGrid = document.getElementById('newSpotPreviewGrid');
    const uploadArea = document.getElementById('newSpotUploadArea');
    
    if (newSpotImageData.length > 0) {
        previewContainer.classList.remove('d-none');
        uploadArea.classList.add('d-none');
        
        previewGrid.innerHTML = newSpotImageData.map((img, idx) => `
            <div class="position-relative mb-2">
                <img src="${img}" alt="Preview ${idx + 1}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;" onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'">
                <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="removeNewSpotImageAt(${idx})" type="button">
                    <i class="bi bi-x-lg"></i>
                </button>
                ${idx === 0 ? '<span class="badge bg-primary position-absolute top-0 start-0 m-2">Main</span>' : ''}
            </div>
        `).join('');
    } else {
        previewContainer.classList.add('d-none');
        uploadArea.classList.remove('d-none');
    }
}

function removeNewSpotImageAt(index) {
    newSpotImageData.splice(index, 1);
    updateNewSpotImagePreview();
    if (newSpotImageData.length === 0) {
        const fileInput = document.getElementById('newSpotImageInput');
        if (fileInput) fileInput.value = '';
    }
}

// Map initialization variables
let map;
let marker;
const defaultLocation = { lat: 14.0667, lng: 120.6333 }; // Default to Nasugbu, Batangas

// ✅ Unified, clean, and fixed search function
window.searchLocation = async function(address, button = null) {
    if (!address) {
        alert('Please enter a location to search');
        return;
    }

    // Show loading spinner on button (if provided)
    let originalIcon = '';
    if (button) {
        const icon = button.querySelector('i');
        if (icon) {
            originalIcon = icon.className;
            icon.className = 'bi bi-arrow-clockwise spin';
        }
        button.disabled = true;
    }

    try {
        // Always bias to Nasugbu, Batangas
        let searchText = address;
        if (!address.toLowerCase().includes('nasugbu')) {
            searchText += ', Nasugbu, Batangas';
        }

        const apiKey = "<?= getenv('GEOAPIFY_KEY') ?>"; // Make sure this returns your actual key
        const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(searchText)}&filter=countrycode:ph,state:Batangas,city:Nasugbu&limit=1&format=json&apiKey=${apiKey}`;

        const res = await fetch(url);
        if (!res.ok) throw new Error('Network error');

        const data = await res.json();

        if (data.results && data.results.length > 0) {
            const location = data.results[0];
            const lat = location.lat;
            const lon = location.lon;
            const formatted = location.formatted;

            // ✅ Update map and marker globally
            if (window.map && window.marker) {
                window.map.setView([lat, lon], 17);
                window.marker.setLatLng([lat, lon]);
            }

            // ✅ Update hidden inputs and location text
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;
            document.getElementById('location').value = formatted;
        } else {
            alert('Could not find this location in Nasugbu, Batangas. Try again.');
        }
    } catch (err) {
        console.error(err);
        alert('Error searching location. Please check your internet or API key.');
    } finally {
        // Restore button
        if (button) {
            const icon = button.querySelector('i');
            if (icon) icon.className = originalIcon;
            button.disabled = false;
        }
    }
};

// ✅ Initialize button click handler when modal opens
document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchLocation');
    const locationInput = document.getElementById('location');
    if (searchButton && locationInput) {
        searchButton.addEventListener('click', () => {
            window.searchLocation(locationInput.value, searchButton);
        });
    }
});


// Initialize map when the modal is shown
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addNewSpotModal').addEventListener('shown.bs.modal', function () {
      
        initMap();

        // Map viewer modal helper — open map inside a centered modal for better navigation
        (function(){
            const mapEl = document.getElementById('map');
            if (!mapEl) return;

            // Ensure Bootstrap's Modal is available
            function createViewerModal() {
                if (document.getElementById('mapViewerModal')) return document.getElementById('mapViewerModal');
                const html = `
                <div class="modal fade" id="mapViewerModal" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Map View</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body p-0" style="height:80vh;">
                        <!-- map element will be moved here -->
                      </div>
                    </div>
                  </div>
                </div>`;
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;
                document.body.appendChild(wrapper.firstElementChild);
                return document.getElementById('mapViewerModal');
            }

            const viewerModalEl = createViewerModal();
            const viewerModal = new bootstrap.Modal(viewerModalEl, { keyboard: true });

            // Remember original parent and next sibling to restore later
            const originalParent = mapEl.parentElement;
            const nextSibling = mapEl.nextSibling;
            const originalHeight = mapEl.style.height || '';

            mapEl.style.cursor = 'pointer';
            if (!mapEl.style.position) mapEl.style.position = 'relative';

            // open viewer function (reusable)
            function openViewer(e){
                if (e && e.stopPropagation) e.stopPropagation();
                if (e && e.target && e.target.closest && e.target.closest('.leaflet-control')) return;
                const body = viewerModalEl.querySelector('.modal-body');
                body.appendChild(mapEl);
                // make map fill modal body
                mapEl.style.height = '100%';
                viewerModal.show();
                setTimeout(()=>{ try { if (window.map && typeof window.map.invalidateSize === 'function') window.map.invalidateSize(); } catch(err){} }, 250);
            }

            mapEl.addEventListener('click', openViewer);

            // Add an explicit expand button overlay so users can expand the map reliably
            (function addExpandButton(){
                if (mapEl.querySelector('.map-expand-btn')) return;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'map-expand-btn';
                btn.title = 'Expand map';
                btn.innerHTML = '<i class="bi bi-arrows-fullscreen" style="color:#fff"></i>';
                mapEl.appendChild(btn);

                function positionMapExpandButtonForSpotOwner() {
                    try {
                        const mapContainer = mapEl.closest('.map-container') || mapEl.parentElement;
                        if (!mapContainer) return;
                        if (window.innerWidth <= 640) {
                            btn.style.top = '';
                            btn.style.bottom = '12px';
                            btn.style.right = '12px';
                            return;
                        }
                        // try modal header if inside a modal
                        const modalHeader = mapEl.closest('.modal-content')?.querySelector('.modal-header');
                        if (modalHeader) {
                            const headerRect = modalHeader.getBoundingClientRect();
                            const containerRect = mapContainer.getBoundingClientRect();
                            let top = Math.round(headerRect.bottom - containerRect.top + 8);
                            top = Math.max(top, 8);
                            btn.style.top = top + 'px';
                            btn.style.bottom = '';
                            btn.style.right = '12px';
                            return;
                        }
                        // fallback: small offset from top
                        btn.style.top = '16px';
                        btn.style.right = '12px';
                        btn.style.bottom = '';
                    } catch (err) { console.warn('positionMapExpandButtonForSpotOwner error', err); }
                }

                positionMapExpandButtonForSpotOwner();
                window.addEventListener('resize', positionMapExpandButtonForSpotOwner);
            })();

            viewerModalEl.addEventListener('hidden.bs.modal', function(){
                // move back the map element to its original place
                if (nextSibling) originalParent.insertBefore(mapEl, nextSibling);
                else originalParent.appendChild(mapEl);
                mapEl.style.height = originalHeight || '300px';
                setTimeout(()=>{ try { if (window.map && typeof window.map.invalidateSize === 'function') window.map.invalidateSize(); } catch(err){} }, 80);
            });
        })();
         //initGeocoder();
        
        // Set up search button handler
        const searchButton = document.getElementById('searchLocation');
        const locationInput = document.getElementById('location');
        if (searchButton && locationInput) {
            searchButton.addEventListener('click', () => {
                window.searchLocation(locationInput.value, searchButton);
            });
        }
    });
});

// Initialize the search function in the window scope
window.searchLocation = async function(address, button = null) {
    if (!address) {
        alert('Please enter a location to search');
        return;
    }

    // Handle loading state if button is provided
    let searchIcon, originalIcon;
    if (button) {
        searchIcon = button.querySelector('i') || button;
        originalIcon = searchIcon.className;
        searchIcon.className = 'bi bi-arrow-clockwise';
        button.disabled = true;
    }
    
    try {
        // Construct the search query with Nasugbu, Batangas context if not explicitly provided
        let searchText = address;
        if (!address.toLowerCase().includes('nasugbu')) {
            searchText += ', Nasugbu, Batangas';
        }

        const response = await fetch(
            `https://api.geoapify.com/v1/geocode/search?` + 
            `text=${encodeURIComponent(searchText)}` + 
            `&filter=countrycode:ph,state:Batangas,city:Nasugbu` +
            `&bias=lat:14.0667,lon:120.6333,radiusKm:10` + // Bias to Nasugbu area
            `&limit=1` + 
            `&format=json` +
            `&apiKey=<?= getenv('GEOAPIFY_KEY') ?>`
        );

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (data.features && data.features.length > 0) {
            const location = data.features[0];
            const lat = location.properties.lat;
            const lon = location.properties.lon;
            const formatted = location.properties.formatted;

            // Update map view and marker
            window.map.setView([lat, lon], 17);
            window.marker.setLatLng([lat, lon]);
            updateCoordinates({lat: lat, lng: lon});
            
            // Update location input with formatted address
            document.getElementById('location').value = formatted;
        } else {
            throw new Error('Location not found');
        }
    } catch (error) {
        console.error('Geocoding error:', error);
        if (button) { // Only show alert if triggered by button click
            alert(error.message === 'Location not found' ? 
                'Could not find this location in Nasugbu, Batangas. Please try a different search.' : 
                'An error occurred while searching. Please try again.');
        }
    } finally {
        // Restore button state if button was provided
        if (button) {
            searchIcon.className = originalIcon;
            button.disabled = false;
        }
    }
};

function initMap() {
    const mapElement = document.getElementById("map");
    if (!mapElement) return;

    // Initialize the Leaflet map
    window.map = L.map(mapElement).setView([defaultLocation.lat, defaultLocation.lng], 13);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.map);

    // Add a draggable marker
    window.marker = L.marker([defaultLocation.lat, defaultLocation.lng], {
        draggable: true
    }).addTo(window.map);

    // Update coordinates when marker is dragged
    window.marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        updateCoordinates(position);
    });

    // Click on map to move marker
    window.map.on('click', function(e) {
        window.marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng);
    });
}

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function initGeocoder() {
    const locationInput = document.getElementById('location');
    if (!locationInput) return;

    // Initialize Geoapify autocomplete with Nasugbu, Batangas bias
    const autocomplete = new GeocoderAutocomplete(
        locationInput, 
        '<?= getenv('GEOAPIFY_KEY') ?>', 
        {
            limit: 5,
            filter: {
                countrycode: 'ph',
                state: 'Batangas',
                city: 'Nasugbu'
            },
            bias: {
                // Nasugbu, Batangas approximate center coordinates
                lat: 14.0667,
                lon: 120.6333,
                radiusKm: 10
            },
            placeholder: "Enter location in Nasugbu, Batangas"
        }
    );

    // Handle autocomplete selection
    autocomplete.on('select', (location) => {
        if (location) {
            const lat = location.properties.lat;
            const lon = location.properties.lon;

            // Update map and marker
            map.setView([lat, lon], 17);
            marker.setLatLng([lat, lon]);
            updateCoordinates({lat: lat, lng: lon});
        }
    });

    // Handle input changes for automatic search
    locationInput.addEventListener('input', debounce(async (e) => {
        const address = e.target.value.trim();
        if (address && address.toLowerCase().includes('nasugbu')) {
            await window.searchLocation(address);
        }
    }, 500));
    
    // Set up search button handler
    const searchButton = document.getElementById('searchLocation');
    if (searchButton) {
        searchButton.addEventListener('click', () => {
            window.searchLocation(locationInput.value, searchButton);
        });
    }
}



async function updateCoordinates(position) {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locationInput = document.getElementById('location');

    if (latInput && lngInput) {
        latInput.value = position.lat;
        lngInput.value = position.lng;

        // Update address using Geoapify reverse geocoding
        try {
            const response = await fetch(
                `https://api.geoapify.com/v1/geocode/reverse?lat=${position.lat}&lon=${position.lng}&apiKey=<?= getenv('GEOAPIFY_KEY') ?>`
            );
            const data = await response.json();

            if (data.features && data.features.length > 0 && locationInput) {
                locationInput.value = data.features[0].properties.formatted;
            }
        } catch (error) {
            console.error('Reverse geocoding error:', error);
        }
    }
}

async function updateLocationFromCoordinates(lat, lng) {
    try {
        const response = await fetch(
            `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lng}&apiKey=<?= getenv('GEOAPIFY_KEY') ?>`
        );
        const data = await response.json();

        if (data.features && data.features.length > 0) {
            document.getElementById('location').value = data.features[0].properties.formatted;
        }
    } catch (error) {
        console.error('Reverse geocoding error:', error);
    }
}
// Handle form submission with AJAX to update home page
document.addEventListener('DOMContentLoaded', function() {
    const addSpotForm = document.querySelector('form[action*="my-spots/store"]');
    
    if (addSpotForm) {
        addSpotForm.addEventListener('submit', function(e) {
            // Check operating days validation
            const operatingDays = document.querySelectorAll('input[name="operating_days[]"]:checked');
            if (operatingDays.length === 0) {
                e.preventDefault();
                alert('Please select at least one operating day');
                return false;
            }
            
            // Store a flag that a new spot is being added
            sessionStorage.setItem('spotAdded', 'true');
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
  

    // Preview primary image
    document.getElementById('primary_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = document.getElementById('primaryImagePreview');
            const previewImg = preview.querySelector('img');
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Preview gallery images
    document.getElementById('gallery_images').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const gallery = document.getElementById('galleryPreview');
        gallery.innerHTML = '';
        
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                gallery.innerHTML += `
                    <div class="col-4">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 150px; width: 100%; object-fit: cover;">
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        });
    });

    // Form validation and operating days handling
    const addSpotForm = document.querySelector('form[action*="my-spots/store"]');
    if (addSpotForm) {
        addSpotForm.addEventListener('submit', function(e) {
            const operatingDays = document.querySelectorAll('input[name="operating_days[]"]:checked');
            if (operatingDays.length === 0) {
                e.preventDefault();
                alert('Please select at least one operating day');
                return false;
            }
        });
    }
});


async function fetchManageSpots() {
    try {
        console.log('🔍 Starting to fetch spots...');
        const res = await fetch('/spotowner/my-spots/data');
        
        console.log('📡 Response status:', res.status);
        console.log('📡 Response OK:', res.ok);
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }

        const data = await res.json();
        console.log('📦 Raw API response:', data);
        console.log('📊 Data type:', typeof data);
        console.log('📊 Is array:', Array.isArray(data));
        console.log('📊 Data length:', data ? data.length : 'null/undefined');
        
        if (!data) {
            console.error('❌ API returned null/undefined');
            window.sharedTouristSpots = [];
        } else if (!Array.isArray(data)) {
            console.error('❌ API did not return an array:', data);
            window.sharedTouristSpots = [];
        } else if (data.length === 0) {
            console.warn('⚠️ API returned empty array');
            window.sharedTouristSpots = [];
        } else {
            console.log('✅ Successfully received', data.length, 'spots');
            window.sharedTouristSpots = data;
        }
        
        loadManageSpotsGrid();
        
        // Also update home page data if the function exists
        if (typeof window.refreshHomeData === 'function') {
            console.log('🔄 Refreshing home page data...');
            window.refreshHomeData();
        }
        
    } catch (err) {
        console.error('❌ Fetch error:', err);
        window.sharedTouristSpots = [];
        const grid = document.getElementById('manageSpotsGrid');
        if (grid) {
            grid.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <h5>Failed to load spots</h5>
                        <p>Error: ${err.message}</p>
                        <p>Check the console for more details.</p>
                    </div>
                </div>
            `;
        }
    }
}



    // Call this function on page load
    fetchManageSpots();


    function loadManageSpotsGrid() {
    const grid = document.getElementById('manageSpotsGrid');
    if (!grid) {
        console.error('❌ Grid element not found!');
        return;
    }

    console.log('🎨 Loading spots grid...');
    console.log('📊 window.sharedTouristSpots:', window.sharedTouristSpots);
    console.log('📊 Number of spots:', window.sharedTouristSpots ? window.sharedTouristSpots.length : 'undefined');

    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.warn('⚠️ No tourist spots data available!');
        grid.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    <h5><i class="bi bi-info-circle me-2"></i>No Spots Found</h5>
                    <p>You haven't added any tourist spots yet.</p>
                    <button class="btn btn-primary" id="addFirstSpotBtn">
                        <i class="bi bi-plus-circle me-2"></i>Add Your First Spot
                    </button>
                </div>
            </div>
        `;
        
        // Add event listener for the new button
        setTimeout(() => {
            const addBtn = document.getElementById('addFirstSpotBtn');
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('addNewSpotModal'));
                    modal.show();
                });
            }
        }, 100);
        
        return;
    }
    
    // Continue with rendering spots
    console.log('✅ Rendering', window.sharedTouristSpots.length, 'spots...');
    
    //dito me magrender ng cards
    grid.innerHTML = window.sharedTouristSpots.map(spot => {
        return `
        <div class="col-lg-4 col-md-6" data-spot-id="${spot.id}" data-status="${spot.status}">
            <div class="custom-card h-100">
                <div class="position-relative">
                    <img src="${spot.image}" onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'" 
                         alt="${spot.name}" 
                         class="img-fluid rounded-top" 
                         style="height: 200px; width: 100%; object-fit: cover;"
                         onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge ${spot.status === 'active' ? 'bg-success' : 'bg-secondary'}">${spot.status}</span>
                    </div>
                    ${spot.images && spot.images.length > 0 ? `
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-dark bg-opacity-75">
                                <i class="bi bi-images me-1"></i>${spot.images.length} photos
                            </span>
                        </div>
                    ` : ''}
                </div>
                <div class="custom-card-body">
                    <h4 class="custom-card-title">${spot.name}</h4>
                    <p class="text-muted-custom mb-2">
                        <i class="bi bi-geo-alt me-1"></i>${spot.location}
                    </p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom small">Price:</span>
                            <span class="fw-medium">₱${spot.price}/person</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom small">Capacity:</span>
                            <span class="fw-medium">${spot.maxVisitors} visitors</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom small">Hours:</span>
                            <span class="fw-medium">${spot.openTime} - ${spot.closeTime}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-custom small">Rating:</span>
                            <span class="fw-medium">
                                <i class="bi bi-star-fill text-warning"></i> ${spot.rating || 0} (${spot.reviews || 0})
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill btn-edit-spot" data-spot-id="${spot.id}">
                            <i class="bi bi-pencil me-1"></i>Edit Spot
                        </button>
                        <button class="btn btn-outline-danger btn-delete-spot" data-spot-id="${spot.id}" data-spot-name="${spot.name}" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    }).join('');

    console.log('✅ Grid rendered successfully');

    // Add event listeners to buttons using event delegation
    // Replace onclick handler to avoid accumulating multiple listeners on repeated renders
    grid.onclick = async function(e) {
        const editBtn = e.target.closest('.btn-edit-spot');
        const deleteBtn = e.target.closest('.btn-delete-spot');
        
        if (editBtn) {
    const spotId = parseInt(editBtn.getAttribute('data-spot-id'));
    currentEditingSpot = spotId; // Track the spot being edited
    console.log('🔍 Edit button clicked for spot ID:', spotId);
    try {
        const url = `<?= base_url('spotowner/my-spots/get-spot') ?>/${spotId}`;
        console.log('📡 Fetching from URL:', url);
        
        const response = await fetch(url);
        console.log('📡 Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const spot = await response.json();
        console.log('📦 Received spot data:', spot);

        if (!spot || spot.error) {
            alert('Failed to load spot details: ' + (spot?.error || 'Unknown error'));
            return;
        }

        // Generate modal content
        const modalBody = document.getElementById('editSpotModalBody');
        modalBody.innerHTML = generateEditSpotModalContent(spot);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('editSpotModal'));
        modal.show();
        // bind image handlers (delete/upload) after modal is visible
        setTimeout(() => bindEditModalImageHandlers(spot), 120);

    } catch (err) {
        console.error('❌ Error fetching spot details:', err);
        alert('Something went wrong while loading the spot details: ' + err.message);
    }
}

        
        if (deleteBtn) {
            const spotId = parseInt(deleteBtn.getAttribute('data-spot-id'));
            const spotName = deleteBtn.getAttribute('data-spot-name');
            console.log('🗑️ Delete button clicked for spot:', spotName, '(ID:', spotId + ')');
            deleteSpot(spotId, spotName);
        }
    };
}


    function changeEditImage(spotId, direction) {
        const spot = window.sharedTouristSpots.find(s => s.id === spotId);
        if (!spot || spot.images.length <= 1) return;
        
        currentEditImageIndex += direction;
        
        // Loop around
        if (currentEditImageIndex < 0) {
            currentEditImageIndex = spot.images.length - 1;
        } else if (currentEditImageIndex >= spot.images.length) {
            currentEditImageIndex = 0;
        }
        
        // Update image
        const imgElement = document.getElementById('editSpotImage');
        if (imgElement) {
            imgElement.src = spot.images[currentEditImageIndex];
        }
        
        // Update counter
        const counter = document.getElementById('editImageCounter');
        if (counter) {
            counter.textContent = `${currentEditImageIndex + 1} / ${spot.images.length}`;
        }
        
        // Update thumbnails
        updateEditThumbnailBorders();
    }

    function setEditImage(spotId, index) {
        const spot = window.sharedTouristSpots.find(s => s.id === spotId);
        if (!spot) return;
        
        currentEditImageIndex = index;
        
        // Update image
        const imgElement = document.getElementById('editSpotImage');
        if (imgElement) {
            imgElement.src = spot.images[currentEditImageIndex];
        }
        
        // Update counter
        const counter = document.getElementById('editImageCounter');
        if (counter) {
            counter.textContent = `${currentEditImageIndex + 1} / ${spot.images.length}`;
        }
        
        // Update thumbnails
        updateEditThumbnailBorders();
    }

    function updateEditThumbnailBorders() {
        const thumbnails = document.querySelectorAll('.edit-thumbnail');
        thumbnails.forEach((thumb, idx) => {
            if (idx === currentEditImageIndex) {
                thumb.classList.add('border-primary');
                thumb.classList.remove('border-secondary');
                thumb.style.borderWidth = '2px';
            } else {
                thumb.classList.remove('border-primary');
                thumb.classList.add('border-secondary');
                thumb.style.borderWidth = '1px';
            }
        });
    }

    function deleteSpot(spotId, spotName) {
        currentEditingSpot = spotId;
        document.getElementById('deleteSpotName').textContent = spotName;
        const modal = new bootstrap.Modal(document.getElementById('deleteSpotModal'));
        modal.show();
    }

    function confirmDeleteSpot() {
        const index = window.sharedTouristSpots.findIndex(s => s.id === currentEditingSpot);
        
        if (index !== -1) {
            window.sharedTouristSpots.splice(index, 1);
            alert(`Spot deleted successfully!`);
        }
        
        bootstrap.Modal.getInstance(document.getElementById('deleteSpotModal')).hide();
        loadManageSpotsGrid();
        
        // Also reload HOME grid if the function exists
        if (typeof window.loadTouristSpotsGrid === 'function') {
            window.loadTouristSpotsGrid();
        }
    }

    function saveSpotChanges(spotId) {
        const name = document.getElementById('spotName').value;
        const description = document.getElementById('spotDescription').value;
        const location = document.getElementById('spotLocation').value;
        const amenities = document.getElementById('spotAmenities').value;
        const price = document.getElementById('spotPrice').value;
        const maxVisitors = document.getElementById('spotMaxVisitors').value;
        const openTime = document.getElementById('spotOpenTime').value;
        const closeTime = document.getElementById('spotCloseTime').value;
        const activeStatus = document.getElementById('spotActiveStatus').checked;
        
        // Update the spot data in the shared array
        const spot = window.sharedTouristSpots.find(s => s.id === spotId);
        if (spot) {
            spot.name = name;
            spot.description = description;
            spot.location = location;
            spot.amenities = amenities;
            spot.price = parseInt(price);
            spot.maxVisitors = parseInt(maxVisitors);
            spot.openTime = openTime;
            spot.closeTime = closeTime;
            spot.status = activeStatus ? 'active' : 'inactive';
            
        
        }
        
        alert(`Changes saved for "${name}"!`);
        bootstrap.Modal.getInstance(document.getElementById('editSpotModal')).hide();
        
        // Reload grid to show updated status
        loadManageSpotsGrid();
        
        // Also reload HOME grid if the function exists
        if (typeof window.loadTouristSpotsGrid === 'function') {
            window.loadTouristSpotsGrid();
        }
    }

    function openImageUpload(spotId) {
        const spot = window.sharedTouristSpots.find(s => s.id === spotId);
        if (!spot) return;
        
        // Close edit modal temporarily
        const editModal = bootstrap.Modal.getInstance(document.getElementById('editSpotModal'));
        if (editModal) {
            editModal.hide();
        }
        
        // Open upload modal
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadImagesModal'));
        uploadModal.show();
        
        // Load current images
        const imagePreviewGrid = document.getElementById('imagePreviewGrid');
        const imageCount = document.getElementById('imageCount');
        
        imageCount.textContent = spot.images.length;
        imagePreviewGrid.innerHTML = spot.images.map((img, idx) => `
            <div class="image-preview-item">
                <img src="${img}" alt="Spot image ${idx + 1}" onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'">
                <div class="image-preview-overlay">
                    <button class="btn btn-danger btn-sm btn-remove-image" data-image-index="${idx}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                ${idx === 0 ? '<div class="main-photo-badge">Main Photo</div>' : ''}
            </div>
        `).join('');
        
        // Add event listeners for remove buttons
        setTimeout(() => {
            document.querySelectorAll('.btn-remove-image').forEach(btn => {
                btn.addEventListener('click', function() {
                    const imageIndex = parseInt(this.getAttribute('data-image-index'));
                    removeImageFromSpot(spotId, imageIndex);
                });
            });
        }, 100);
        
        // Setup upload area
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('imageUploadInput');
        
        uploadArea.onclick = function() {
            fileInput.click();
        };

        // Handle file upload
        fileInput.onchange = function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                files.forEach(file => {
                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        spot.images.push(e.target.result);
                        
                        
                        
                        // Refresh the preview
                        openImageUpload(spotId);
                    };
                    reader.readAsDataURL(file);
                });
            }
        };
    }

    function removeImageFromSpot(spotId, imageIndex) {
        const spot = window.sharedTouristSpots.find(s => s.id === spotId);
        
        if (!spot || spot.images.length <= 1) {
            alert('You must keep at least one image for the spot');
            return;
        }
        
        spot.images.splice(imageIndex, 1);
        
        
        
        // Refresh the preview
        openImageUpload(spotId);
    }

    function saveImages() {
        alert('Images saved successfully!');
        bootstrap.Modal.getInstance(document.getElementById('uploadImagesModal')).hide();
        
        // Reopen edit modal with updated images
        if (currentEditingSpot) {
        
        }
    }

    function filterSpots(searchTerm) {
        const cards = document.querySelectorAll('#manageSpotsGrid > div');
        searchTerm = searchTerm.toLowerCase();
        
        cards.forEach(card => {
            const spotName = card.querySelector('.custom-card-title').textContent.toLowerCase();
            const spotLocation = card.querySelector('.text-muted-custom').textContent.toLowerCase();
            
            if (spotName.includes(searchTerm) || spotLocation.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterByStatus(status) {
        const cards = document.querySelectorAll('#manageSpotsGrid > div');
        
        cards.forEach(card => {
            const spotStatus = card.dataset.status;
            
            if (status === 'all' || spotStatus === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }


    // Handle Save Changes button click
// Handle Save Changes button click
document.addEventListener('click', async function(e) {
    if (e.target && e.target.id === 'saveSpotChangesBtn') {
        e.preventDefault();
        
        const spotId = currentEditingSpot;
        
        if (!spotId) {
            alert('Error: No spot selected for editing');
            return;
        }
        
        // Gather form data
        const formData = {
            spot_name: document.getElementById('spotName').value,
            description: document.getElementById('spotDescription').value,
            location: document.getElementById('spotLocation').value,
            price_per_person: document.getElementById('spotPrice').value,
            capacity: document.getElementById('spotMaxVisitors').value,
            opening_time: document.getElementById('spotOpenTime').value,
            closing_time: document.getElementById('spotCloseTime').value,
            status: document.getElementById('spotActiveStatus').checked ? 'active' : 'inactive'
        };
        
        console.log('💾 Saving spot data:', formData);
        
        try {
            const response = await fetch(`<?= base_url('spotowner/my-spots/update') ?>/${spotId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            console.log('📦 Save response:', result);
            
            if (result.success) {
                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Spot updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('✅ Spot updated successfully!');
                }
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSpotModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Update the local data immediately
                const spotIndex = window.sharedTouristSpots.findIndex(s => s.spot_id === spotId || s.id === spotId);
                if (spotIndex !== -1) {
                    window.sharedTouristSpots[spotIndex].status = formData.status;
                    window.sharedTouristSpots[spotIndex].spot_name = formData.spot_name;
                    window.sharedTouristSpots[spotIndex].name = formData.spot_name;
                    window.sharedTouristSpots[spotIndex].description = formData.description;
                    window.sharedTouristSpots[spotIndex].location = formData.location;
                    window.sharedTouristSpots[spotIndex].price_per_person = formData.price_per_person;
                    window.sharedTouristSpots[spotIndex].price = formData.price_per_person;
                    window.sharedTouristSpots[spotIndex].capacity = formData.capacity;
                    window.sharedTouristSpots[spotIndex].maxVisitors = formData.capacity;
                    window.sharedTouristSpots[spotIndex].opening_time = formData.opening_time;
                    window.sharedTouristSpots[spotIndex].openTime = formData.opening_time;
                    window.sharedTouristSpots[spotIndex].closing_time = formData.closing_time;
                    window.sharedTouristSpots[spotIndex].closeTime = formData.closing_time;
                }
                
                // Reload the spots grid to show updated status
                fetchManageSpots();
                
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update spot: ' + (result.error || 'Unknown error')
                    });
                } else {
                    alert('❌ Failed to update spot: ' + (result.error || 'Unknown error'));
                }
            }
        } catch (err) {
            console.error('❌ Error saving spot:', err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong while saving: ' + err.message
                });
            } else {
                alert('Something went wrong while saving: ' + err.message);
            }
        }
    }
});
    // Initialize page when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initManageSpotPage);
    } else {
        initManageSpotPage();
    }


    // Add this at the very end of your JavaScript, before 
window.refreshAllSpotData = function() {
    // Refresh manage spots page
    if (typeof fetchManageSpots === 'function') {
        fetchManageSpots();
    }
    
    // Refresh home page if the function exists
    if (typeof fetchTouristSpots === 'function') {
        fetchTouristSpots();
    }
};

// Check if we just added a spot
if (window.location.search.includes('success')) {
    setTimeout(function() {
        window.refreshAllSpotData();
    }, 500);
}
   </script>


<!-- Script For Modal Edit  -->
   <script>
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.editSpotBtn');

    editButtons.forEach(btn => {
        btn.addEventListener('click', async function () {
            const spotId = this.getAttribute('data-id');

            try {

                const response = await fetch(`<?= base_url('spotowner/my-spots/get-spot') ?>/${spotId}`);
                const spot = await response.json();

                if (!spot || spot.error) {
                    alert('Failed to load spot details.');
                    return;
                }

                // Generate and inject the modal content
                const modalBody = document.getElementById('editSpotModalBody');
                modalBody.innerHTML = generateEditSpotModalContent(spot);

                // Show the modal and bind handlers
                const modal = new bootstrap.Modal(document.getElementById('editSpotModal'));
                modal.show();
                setTimeout(() => bindEditModalImageHandlers(spot), 120);

            } catch (err) {
                console.error('Error fetching spot details:', err);
                alert('Something went wrong while loading the spot details.');
            }
        });
    });
});

function generateEditSpotModalContent(spot) {
    // Prefer `spot.gallery` (objects with image_id/image_url). Fallback to `spot.images` (legacy array of urls).
    const gallery = (spot.gallery && Array.isArray(spot.gallery)) ? spot.gallery : ((spot.images && Array.isArray(spot.images)) ? spot.images : []);
    const images = gallery.length > 0 ? gallery.map(g => (typeof g === 'string' ? g : (g.image_url || g.url || ''))) : (spot.image ? [spot.image] : ['<?= esc(base_url("uploads/spots/Spot-No-Image.png")) ?>']);
    const primaryUrl = spot.primary_image_url || spot.primaryUrl || spot.image || images[0] || '<?= esc(base_url("uploads/spots/Spot-No-Image.png")) ?>';
    
    const totalVisits = spot.totalVisits || 0;
    const rating = spot.rating || 0;
    const reviews = spot.reviews || 0;

    return `
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
                            <label class="form-label">Spot Name</label>
                            <input type="text" class="form-control" id="spotName" value="${spot.spot_name || spot.name || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="spotDescription" rows="4">${spot.description || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="spotLocation" value="${spot.location || ''}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Capacity -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Pricing & Capacity</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Price per Person (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" class="form-control" id="spotPrice" value="${spot.price_per_person || spot.price || 0}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Max Visitors</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" class="form-control" id="spotMaxVisitors" value="${spot.capacity || spot.maxVisitors || 0}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Operating Hours</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Opening Time</label>
                                <input type="time" class="form-control" id="spotOpenTime" value="${spot.opening_time || spot.openTime || ''}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Closing Time</label>
                                <input type="time" class="form-control" id="spotCloseTime" value="${spot.closing_time || spot.closeTime || ''}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveSpotChangesBtn">Save Changes</button>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Spot Status -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Status</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label class="form-label mb-0">Active Status</label>
                                <p class="text-muted-custom small mb-0">Make spot available</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="spotActiveStatus" ${spot.status === 'active' ? 'checked' : ''}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spot Images -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Images</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="mb-3">
                            <label class="form-label">Primary Image</label>
                            <div class="position-relative mb-2">
                                <img src="${primaryUrl}" alt="${spot.spot_name || spot.name || 'Spot'}" onerror="this.src='<?= esc(base_url('uploads/spots/Spot-No-Image.png')) ?>'" 
                                    class="rounded img-fluid" id="editSpotImage" 
                                    style="width: 100%; height: 200px; object-fit: cover;">

                                <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="btnDeletePrimary" title="Delete primary image">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                                <label class="position-absolute bottom-0 start-0 m-2 d-none" id="btnAddPrimaryLabel" style="cursor:pointer;">
                                    <input type="file" id="primaryUploadInput" name="primary_image" accept="image/*" class="d-none">
                                    <span class="badge bg-light text-dark">+ Add Primary Image</span>
                                </label>
                            </div>
                            <div>
                                <label class="form-label">Gallery</label>
                                    <div class="d-flex flex-wrap gap-2" id="editGalleryContainer">
                                    ${gallery.map((g, idx) => {
                                        const imgUrl = (typeof g === 'string') ? g : (g.image_url || g.url || '');
                                        const imgId = (typeof g === 'object') ? (g.image_id || g.id || g.imageId || '') : '';
                                        return `
                                        <div class="position-relative" style="width:96px; height:96px;">
                                            <img src="${imgUrl}" class="img-thumbnail" style="width:100%; height:100%; object-fit:cover;"/>
                                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 gallery-delete-btn" data-image-url="${imgUrl}" data-index="${idx}" data-image-id="${imgId}" title="Delete">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        `;
                                    }).join('')}

                                    <div style="width:96px; height:96px;" class="d-flex align-items-center justify-content-center border rounded" id="addGalleryTile">
                                        <label style="cursor:pointer; width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                            <input type="file" id="galleryUploadInput" name="gallery_images[]" accept="image/*" multiple class="d-none">
                                            <div class="text-center text-muted">
                                                <i class="bi bi-plus-lg" style="font-size: 1.25rem"></i>
                                                <div style="font-size: 12px">Add</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="custom-card">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Quick Stats</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted-custom">Total Visits</span>
                            <span class="fw-medium">${totalVisits.toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted-custom">Rating</span>
                            <span class="fw-medium">⭐ ${rating}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-custom">Reviews</span>
                            <span class="fw-medium">${reviews}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Listen for success messages and reload data
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const successMsg = '<?= session()->getFlashdata("success") ?>';
    
    if (successMsg && successMsg.includes('successfully')) {
        console.log('✅ Spot added successfully, refreshing data...');
        
        // Reload the spots grid
        setTimeout(function() {
            fetchManageSpots();
        }, 500);
    }
});



</script>

<!-- Notification System -->
<script src="<?= base_url('assets/js/spotownerJS/notifications.js') ?>"></script>
</body>

</html>