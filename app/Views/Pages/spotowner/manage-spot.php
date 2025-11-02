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
    <!-- Leaflet Core -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Geoapify Autocomplete -->
<link rel="stylesheet" href="https://unpkg.com/@geoapify/geocoder-autocomplete@1.8.1/styles/minimal.css" />
<script src="https://unpkg.com/@geoapify/geocoder-autocomplete@1.8.1/dist/index.min.js"></script>

<!-- Geoapify Map Control -->
<link rel="stylesheet" href="https://unpkg.com/@geoapify/leaflet-address-search-plugin@1.2.1/dist/L.Control.GeoapifyAddressSearch.min.css" />
<script src="https://unpkg.com/@geoapify/leaflet-address-search-plugin@1.2.1/dist/L.Control.GeoapifyAddressSearch.min.js"></script>





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
                        <a href="/spotowner/bookings" class="sidebar-link active" data-page="bookings">
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
                        <?php if (!empty($touristSpots)): ?>
                            <?php foreach ($touristSpots as $spot): ?>
                                <div class="col-lg-4 col-md-6" data-spot-id="<?= $spot['id'] ?>" data-status="<?= $spot['status'] ?>">
                                    <div class="custom-card h-100">
                                        <div class="position-relative">
                                            <img src="<?= !empty($spot['images']) ? base_url('uploads/spots/gallery/' . $spot['images'][0]) : base_url('uploads/spots/' . $spot['primary_image']) ?>" 
                                                alt="<?= esc($spot['spot_name']) ?>" class="img-fluid rounded-top" style="height: 200px; width: 100%; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge <?= $spot['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= esc($spot['status']) ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($spot['images']) && count($spot['images']) > 1): ?>
                                                <div class="position-absolute bottom-0 end-0 m-3">
                                                    <span class="badge bg-dark bg-opacity-75">
                                                        <i class="bi bi-images me-1"></i><?= count($spot['images']) ?> photos
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


                                                <a href="<?= base_url('/spotowner/my-spots/delete/' . $spot['id']) ?>" 
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
                                                        <option value="">Select category</option>
                                                        <option value="Beach">Beach</option>
                                                        <option value="Mountain">Mountain</option>
                                                        <option value="Park">Park</option>
                                                        <option value="Museum">Museum</option>
                                                        <option value="Restaurant">Restaurant</option>
                                                        <option value="Restaurant">Historical</option>
                                                        <option value="Other">Other</option>
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
                                                <div class="mt-2">
                                                    <label for="group_discount_percent" class="form-label">Group Discount (%)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                                        <input type="number" class="form-control" name="group_discount_percent" id="group_discount_percent" min="0" max="100">
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
                                                        <input class="form-check-input" type="checkbox" id="newSpotActiveStatus" name = "status" checked>
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
                                                    <label for="spotName" class="form-label">Spot Name</label>
                                                    <input type="text" class="form-control" id="spotName" value="${spot.name}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spotDescription" class="form-label">Description</label>
                                                    <textarea class="form-control" id="spotDescription" rows="4">${spot.description}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spotLocation" class="form-label">Location</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                        <input type="text" class="form-control" id="spotLocation" value="${spot.location}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spotAmenities" class="form-label">Amenities</label>
                                                    <input type="text" class="form-control" id="spotAmenities" value="${spot.amenities}" placeholder="Comma-separated list of amenities">
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
                                                        <label for="spotPrice" class="form-label">Price per Person (₱)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                            <input type="number" class="form-control" id="spotPrice" value="${spot.price}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="spotMaxVisitors" class="form-label">Max Visitors per Day</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                                                            <input type="number" class="form-control" id="spotMaxVisitors" value="${spot.maxVisitors}">
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
                                                        <label for="spotOpenTime" class="form-label">Opening Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" id="spotOpenTime" value="${spot.openTime}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="spotCloseTime" class="form-label">Closing Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                            <input type="time" class="form-control" id="spotCloseTime" value="${spot.closeTime}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
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
                                                <p class="custom-card-description">Control visibility and availability</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <label class="form-label mb-0">Active Status</label>
                                                        <p class="text-muted-custom small mb-0">Make spot available for booking</p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="spotActiveStatus" ${spot.status === 'active' ? 'checked' : ''}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Spot Images Gallery -->
                                        <div class="custom-card mb-4">
                                            <div class="custom-card-header">
                                                <h3 class="custom-card-title">Spot Images</h3>
                                                <p class="custom-card-description">Manage your spot photos</p>
                                            </div>
                                            <div class="custom-card-body">
                                                <!-- Image Carousel -->
                                                <div class="position-relative mb-3">
                                                    <img src="${spot.images[0]}" 
                                                        alt="${spot.name}" 
                                                        class="rounded img-fluid"
                                                        id="editSpotImage"
                                                        style="width: 100%; height: 200px; object-fit: cover;">
                                                    
                                                    ${spot.images.length > 1 ? `
                                                        <!-- Previous Button -->
                                                        <button class="btn btn-dark btn-sm position-absolute top-50 start-0 translate-middle-y ms-2" 
                                                                id="prevEditImageBtn"
                                                                style="opacity: 0.8; z-index: 10;">
                                                            <i class="bi bi-chevron-left"></i>
                                                        </button>
                                                        
                                                        <!-- Next Button -->
                                                        <button class="btn btn-dark btn-sm position-absolute top-50 end-0 translate-middle-y me-2" 
                                                                id="nextEditImageBtn"
                                                                style="opacity: 0.8; z-index: 10;">
                                                            <i class="bi bi-chevron-right"></i>
                                                        </button>
                                                        
                                                        <!-- Image Counter -->
                                                        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2">
                                                            <span class="badge bg-dark bg-opacity-75" id="editImageCounter">
                                                                1 / ${spot.images.length}
                                                            </span>
                                                        </div>
                                                    ` : ''}
                                                </div>
                                                
                                                <!-- Thumbnail Strip -->
                                                ${spot.images.length > 1 ? `
                                                    <div class="d-flex gap-2 mb-3 overflow-auto" style="max-width: 100%;">
                                                        ${spot.images.map((img, idx) => `
                                                            <img src="${img}" 
                                                                alt="Thumbnail ${idx + 1}" 
                                                                class="img-thumbnail edit-thumbnail ${idx === 0 ? 'border-primary' : ''}"
                                                                style="width: 60px; height: 45px; object-fit: cover; cursor: pointer; flex-shrink: 0;"
                                                                data-edit-thumbnail-index="${idx}">
                                                        `).join('')}
                                                    </div>
                                                ` : ''}
                                                
                                                <button class="btn btn-outline-primary w-100" id="openImageUploadBtn">
                                                    <i class="bi bi-image me-2"></i>Manage Images
                                                </button>
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
                                                    <span class="fw-medium">${spot.totalVisits.toLocaleString()}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span class="text-muted-custom">Average Rating</span>
                                                    <span class="fw-medium">${spot.rating} ⭐</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted-custom">Total Reviews</span>
                                                    <span class="fw-medium">${spot.reviews}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

  
    

<script>
// Global variables
let currentEditingSpot = null;
let newSpotImageData = [];
let currentEditImageIndex = 0;




function initManageSpotPage() {
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
                <img src="${img}" alt="Preview ${idx + 1}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
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
         initGeocoder();
        
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
    document.getElementById('addSpotForm').addEventListener('submit', function(e) {
        const operatingDays = document.querySelectorAll('input[name="operating_days[]"]:checked');
        if (operatingDays.length === 0) {
            e.preventDefault();
            alert('Please select at least one operating day');
            return;
        }
        
        const formData = new FormData(this);
        const days = Array.from(operatingDays).map(day => day.value);
        formData.set('operating_days', days.join(','));
    });
});

async function fetchManageSpots() {
    try {
        const res = await fetch('/spotowner/my-spots/data');
        if (!res.ok) throw new Error('Failed to fetch spots');

        const data = await res.json();
        window.sharedTouristSpots = data;
        loadManageSpotsGrid(); // render the grid
    } catch (err) {
        console.error(err);
        const grid = document.getElementById('manageSpotsGrid');
        if (grid) grid.innerHTML = '<div class="col-12"><p class="text-center text-danger">Failed to load spots.</p></div>';
    }
}

    // Call this function on page load
    fetchManageSpots();


    function loadManageSpotsGrid() {
        const grid = document.getElementById('manageSpotsGrid');
        if (!grid) return;

        if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
            console.error('No tourist spots data available!');
            grid.innerHTML = '<div class="col-12"><p class="text-center text-muted">No tourist spots available.</p></div>';
            return;
        }
        
        //dito me magrender ng cards
        grid.innerHTML = window.sharedTouristSpots.map(spot => {
            return `
            <div class="col-lg-4 col-md-6" data-spot-id="${spot.id}" data-status="${spot.status}">
                <div class="custom-card h-100">
                    <div class="position-relative">
                        <img src="${spot.images ? spot.images[0] : spot.image}" alt="${spot.name}" class="img-fluid rounded-top" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge ${spot.status === 'active' ? 'bg-success' : 'bg-secondary'}">${spot.status}</span>
                        </div>
                        ${spot.images && spot.images.length > 1 ? `
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
                                    <i class="bi bi-star-fill text-warning"></i> ${spot.rating} (${spot.reviews})
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

        // Add event listeners to buttons using event delegation
        // Replace onclick handler to avoid accumulating multiple listeners on repeated renders
        grid.onclick = async function(e) {
            const editBtn = e.target.closest('.btn-edit-spot');
            const deleteBtn = e.target.closest('.btn-delete-spot');
            
            if (editBtn) {
                const spotId = parseInt(editBtn.getAttribute('data-spot-id'));
                try {
                    const response = await fetch(`<?= base_url('spotowner/my-spots/get-spot') ?>/${spotId}`);
                    const spot = await response.json();

                    if (!spot || spot.error) {
                        alert('Failed to load spot details.');
                        return;
                    }

                    // Generate modal content
                    const modalBody = document.getElementById('editSpotModalBody');
                    modalBody.innerHTML = generateEditSpotModalContent(spot);

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editSpotModal'));
                    modal.show();

                } catch (err) {
                    console.error('Error fetching spot details:', err);
                    alert('Something went wrong while loading the spot details.');
                }
            }

            
            if (deleteBtn) {
                const spotId = parseInt(deleteBtn.getAttribute('data-spot-id'));
                const spotName = deleteBtn.getAttribute('data-spot-name');
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
                <img src="${img}" alt="Spot image ${idx + 1}">
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
    // Initialize page when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initManageSpotPage);
    } else {
        initManageSpotPage();
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

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editSpotModal'));
                modal.show();

            } catch (err) {
                console.error('Error fetching spot details:', err);
                alert('Something went wrong while loading the spot details.');
            }
        });
    });
});

function generateEditSpotModalContent(spot) {
    const images = spot.images && spot.images.length ? spot.images : ['/uploads/default.jpg'];
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
                            <input type="text" class="form-control" id="spotName" value="${spot.spot_name || ''}">
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
                        <div class="mb-3">
                            <label class="form-label">Amenities</label>
                            <input type="text" class="form-control" id="spotAmenities" value="${spot.amenities || ''}" placeholder="Comma-separated list of amenities">
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
                                <label class="form-label">Price per Person (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" class="form-control" id="spotPrice" value="${spot.price_per_person || 0}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Max Visitors per Day</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" class="form-control" id="spotMaxVisitors" value="${spot.capacity || 0}">
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
                                <label class="form-label">Opening Time</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control" id="spotOpenTime" value="${spot.opening_time || ''}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Closing Time</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control" id="spotCloseTime" value="${spot.closing_time || ''}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
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
                        <p class="custom-card-description">Control visibility and availability</p>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label class="form-label mb-0">Active Status</label>
                                <p class="text-muted-custom small mb-0">Make spot available for booking</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="spotActiveStatus" ${spot.status === 'active' ? 'checked' : ''}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spot Images Gallery -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Images</h3>
                        <p class="custom-card-description">Manage your spot photos</p>
                    </div>
                    <div class="custom-card-body">
                        <div class="position-relative mb-3">
                            <img src="${images[0]}" alt="${spot.spot_name}" class="rounded img-fluid"
                                id="editSpotImage" style="width: 100%; height: 200px; object-fit: cover;">

                            ${images.length > 1 ? `
                                <button class="btn btn-dark btn-sm position-absolute top-50 start-0 translate-middle-y ms-2" 
                                        id="prevEditImageBtn" style="opacity: 0.8; z-index: 10;">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="btn btn-dark btn-sm position-absolute top-50 end-0 translate-middle-y me-2" 
                                        id="nextEditImageBtn" style="opacity: 0.8; z-index: 10;">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2">
                                    <span class="badge bg-dark bg-opacity-75" id="editImageCounter">
                                        1 / ${images.length}
                                    </span>
                                </div>
                            ` : ''}
                        </div>

                        ${images.length > 1 ? `
                            <div class="d-flex gap-2 mb-3 overflow-auto" style="max-width: 100%;">
                                ${images.map((img, idx) => `
                                    <img src="${img}" 
                                        alt="Thumbnail ${idx + 1}" 
                                        class="img-thumbnail edit-thumbnail ${idx === 0 ? 'border-primary' : ''}"
                                        style="width: 60px; height: 45px; object-fit: cover; cursor: pointer;"
                                        data-edit-thumbnail-index="${idx}">
                                `).join('')}
                            </div>
                        ` : ''}
                        <button class="btn btn-outline-primary w-100" id="openImageUploadBtn">
                            <i class="bi bi-image me-2"></i>Manage Images
                        </button>
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
                            <span class="text-muted-custom">Average Rating</span>
                            <span class="fw-medium"> ⭐ ${rating}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-custom">Total Reviews</span>
                            <span class="fw-medium">${reviews}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>



    
</body>

</html>