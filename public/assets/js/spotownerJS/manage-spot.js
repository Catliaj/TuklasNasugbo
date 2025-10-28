// Manage Spot Page - Multiple Tourist Spots (FIXED)
function renderManageSpotPage() {
    return `
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
    `;
}




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

function saveNewSpot() {
    const name = document.getElementById('newSpotName').value;
    const description = document.getElementById('newSpotDescription').value;
    const location = document.getElementById('newSpotLocation').value;
    const amenities = document.getElementById('newSpotAmenities').value;
    const price = document.getElementById('newSpotPrice').value;
    const maxVisitors = document.getElementById('newSpotMaxVisitors').value;
    const openTime = document.getElementById('newSpotOpenTime').value;
    const closeTime = document.getElementById('newSpotCloseTime').value;
    const activeStatus = document.getElementById('newSpotActiveStatus').checked;
    
    // Validation
    if (!name || !description || !location || !price || !maxVisitors) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Generate new ID
    const newId = Math.max(...window.sharedTouristSpots.map(s => s.id)) + 1;
    
    // Create new spot object
    const newSpot = {
        id: newId,
        name: name,
        location: location,
        description: description,
        images: newSpotImageData.length > 0 ? newSpotImageData : ['https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop'],
        price: parseInt(price),
        maxVisitors: parseInt(maxVisitors),
        openTime: openTime,
        closeTime: closeTime,
        rating: 0,
        reviews: 0,
        bookings: 0,
        revenue: 0,
        visitors: 0,
        totalVisits: 0,
        status: activeStatus ? 'active' : 'inactive',
        amenities: amenities || 'Basic amenities',
        highlights: ['New tourist spot', 'Coming soon']
    };
    
    // Add to array
    window.sharedTouristSpots.push(newSpot);
    
    
    
    alert(`New spot "${name}" added successfully!`);
    bootstrap.Modal.getInstance(document.getElementById('addNewSpotModal')).hide();
    
    // Clear form and reset
    document.getElementById('newSpotName').value = '';
    document.getElementById('newSpotDescription').value = '';
    document.getElementById('newSpotLocation').value = '';
    document.getElementById('newSpotAmenities').value = '';
    document.getElementById('newSpotPrice').value = '';
    document.getElementById('newSpotMaxVisitors').value = '';
    document.getElementById('newSpotOpenTime').value = '09:00';
    document.getElementById('newSpotCloseTime').value = '18:00';
    document.getElementById('newSpotActiveStatus').checked = true;
    newSpotImageData = [];
    updateNewSpotImagePreview();
    
    // Reload grid
    loadManageSpotsGrid();
    
    // Also reload HOME grid if the function exists
    if (typeof window.loadTouristSpotsGrid === 'function') {
        window.loadTouristSpotsGrid();
    }
}

function loadManageSpotsGrid() {
    const grid = document.getElementById('manageSpotsGrid');
    if (!grid) return;

    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.error('No tourist spots data available!');
        grid.innerHTML = '<div class="col-12"><p class="text-center text-muted">No tourist spots available.</p></div>';
        return;
    }
    
    
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
    grid.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit-spot');
        const deleteBtn = e.target.closest('.btn-delete-spot');
        
        if (editBtn) {
            const spotId = parseInt(editBtn.getAttribute('data-spot-id'));
            editSpot(spotId);
        }
        
        if (deleteBtn) {
            const spotId = parseInt(deleteBtn.getAttribute('data-spot-id'));
            const spotName = deleteBtn.getAttribute('data-spot-name');
            deleteSpot(spotId, spotName);
        }
    });
}

function editSpot(spotId) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot) {
        console.error('Spot not found:', spotId);
        return;
    }
    
    // Ensure images array exists
    if (!spot.images) {
        spot.images = [spot.image || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop'];
    }
    
    currentEditingSpot = spotId;
    currentEditImageIndex = 0;
    
    const modalBody = document.getElementById('editSpotModalBody');
    if (!modalBody) {
        console.error('Modal body not found');
        return;
    }
    
    modalBody.innerHTML = `
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
    `;
    
    const editModal = document.getElementById('editSpotModal');
    if (!editModal) {
        console.error('Edit modal not found');
        return;
    }
    
    const modal = new bootstrap.Modal(editModal);
    modal.show();

    // Add event listeners for the buttons inside the modal after a brief delay
    setTimeout(() => {

        // Save changes button
        // Save changes button
        const saveBtn = document.getElementById('saveSpotChangesBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => saveSpotChanges(spotId));
        } else {
            console.error('Save button not found');
        }

        // Image navigation buttons
        const prevBtn = document.getElementById('prevEditImageBtn');
        const nextBtn = document.getElementById('nextEditImageBtn');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => changeEditImage(spotId, -1));
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => changeEditImage(spotId, 1));
        }

        // Thumbnail clicks
        const thumbnails = document.querySelectorAll('.edit-thumbnail');
        thumbnails.forEach((thumb, idx) => {
            thumb.addEventListener('click', () => setEditImage(spotId, idx));
        });

        // Open image upload button
        const uploadBtn = document.getElementById('openImageUploadBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', () => openImageUpload(spotId));
        } else {
            console.error('Upload button not found');
        }
    }, 150);
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
        editSpot(currentEditingSpot);
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