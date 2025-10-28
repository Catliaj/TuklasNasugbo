// Home Page - Multiple Tourist Spots Management (FIXED FRAME SIZE)
function renderHomePage() {
    return `
        <div class="container-fluid px-0">
            <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2>My Tourist Spots</h2>
                    <p class="text-muted-custom">Manage all your tourist spot properties</p>
                </div>
                
            </div>

            <!-- Overall Stats Overview -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Spots</div>
                                <div class="stat-value">3</div>
                                <div class="stat-description">Active properties</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Bookings</div>
                                <div class="stat-value">18</div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Revenue</div>
                                <div class="stat-value">₱8,250</div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Average Rating</div>
                                <div class="stat-value">4.7</div>
                                <div class="stat-description">Across all spots</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tourist Spots Grid -->
            <div class="row g-4 mb-4" id="touristSpotsGrid">
                <!-- Spot cards will be loaded here -->
            </div>
        </div>

        <!-- View Spot Details Modal -->
        <div class="modal fade" id="viewSpotModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="spotModalTitle">Spot Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="spotModalBody">
                        <!-- Content loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        
    `;
}

// Data is managed by shared-data.js via window.sharedTouristSpots
// All functions below reference window.sharedTouristSpots directly

let currentImageIndex = 0;

function initHomePage() {
    // Load tourist spots grid
    loadTouristSpotsGrid();
    
    // Add new spot button
    document.getElementById('addNewSpotBtn')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('addNewSpotModal'));
        modal.show();
    });
}

function loadTouristSpotsGrid() {
    const grid = document.getElementById('touristSpotsGrid');
    if (!grid) return;
    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.error('No tourist spots data available!');
        grid.innerHTML = '<div class="col-12"><p class="text-center text-muted">No tourist spots available.</p></div>';
        return;
    }
    
    grid.innerHTML = window.sharedTouristSpots.map(spot => `
        <div class="col-lg-4 col-md-6">
            <div class="custom-card h-100 d-flex flex-column">
                <div class="position-relative">
                    <img src="${spot.images[0]}" alt="${spot.name}" class="img-fluid rounded-top" style="height: 220px; width: 100%; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge ${spot.status === 'active' ? 'bg-success' : 'bg-secondary'}">${spot.status}</span>
                    </div>
                    ${spot.images.length > 1 ? `
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-dark bg-opacity-75">
                                <i class="bi bi-images me-1"></i>${spot.images.length} photos
                            </span>
                        </div>
                    ` : ''}
                </div>
                <div class="custom-card-body flex-grow-1 d-flex flex-column">
                    <h4 class="custom-card-title" style="min-height: 60px;">${spot.name}</h4>
                    <p class="text-muted-custom mb-2" style="min-height: 45px;">
                        <i class="bi bi-geo-alt me-1"></i>${spot.location}
                    </p>
                    
                    <div class="d-flex align-items-center gap-3 mb-3" style="min-height: 30px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-medium">${spot.rating}</span>
                            <span class="text-muted-custom small ms-1">(${spot.reviews})</span>
                        </div>
                        <div class="text-muted-custom">|</div>
                        <div class="text-ocean-medium fw-medium">₱${spot.price}/person</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 bg-beige rounded text-center">
                                <div class="small text-muted-custom">Bookings</div>
                                <div class="fw-medium">${spot.bookings}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-beige rounded text-center">
                                <div class="small text-muted-custom">Revenue</div>
                                <div class="fw-medium">₱${spot.revenue.toLocaleString()}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-auto">
                        <button class="btn btn-primary flex-fill" onclick="viewSpotDetails(${spot.id})">
                            <i class="bi bi-eye me-1"></i>View Details
                        </button>
                        <button class="btn btn-outline-primary" onclick="manageSpot(${spot.id})" title="Manage">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function viewSpotDetails(spotId) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot) return;
    
    currentImageIndex = 0;
    const modalTitle = document.getElementById('spotModalTitle');
    const modalBody = document.getElementById('spotModalBody');
    
    modalTitle.textContent = spot.name;
    
    modalBody.innerHTML = `
        <div class="container-fluid">
            <!-- Stats Overview for this spot -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Bookings</div>
                                <div class="stat-value">${spot.bookings}</div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Revenue</div>
                                <div class="stat-value">₱${spot.revenue.toLocaleString()}</div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Visitors</div>
                                <div class="stat-value">${spot.visitors}</div>
                                <div class="stat-description">This week</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Rating</div>
                                <div class="stat-value">${spot.rating}</div>
                                <div class="stat-description">From ${spot.reviews} reviews</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spot Overview -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">${spot.name}</h3>
                    <p class="custom-card-description">Tourist Spot Overview</p>
                </div>
                <div class="custom-card-body">
                    <div class="row">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <!-- Image Gallery Carousel -->
                            <div class="position-relative">
                                <img src="${spot.images[0]}" 
                                     alt="${spot.name}" 
                                     class="img-fluid rounded"
                                     id="spotDetailImage"
                                     style="width: 100%; height: 400px; object-fit: cover;">
                                
                                ${spot.images.length > 1 ? `
                                    <!-- Previous Button -->
                                    <button class="btn btn-dark btn-sm position-absolute top-50 start-0 translate-middle-y ms-3" 
                                            onclick="changeSpotImage(${spotId}, -1)"
                                            style="opacity: 0.8; z-index: 10;">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    
                                    <!-- Next Button -->
                                    <button class="btn btn-dark btn-sm position-absolute top-50 end-0 translate-middle-y me-3" 
                                            onclick="changeSpotImage(${spotId}, 1)"
                                            style="opacity: 0.8; z-index: 10;">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                    
                                    <!-- Image Counter -->
                                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                                        <span class="badge bg-dark bg-opacity-75" id="imageCounter">
                                            1 / ${spot.images.length}
                                        </span>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <!-- Thumbnail Strip -->
                            ${spot.images.length > 1 ? `
                                <div class="d-flex gap-2 mt-3 overflow-auto" style="max-width: 100%;">
                                    ${spot.images.map((img, idx) => `
                                        <img src="${img}" 
                                             alt="Thumbnail ${idx + 1}" 
                                             class="img-thumbnail spot-thumbnail ${idx === 0 ? 'border-primary' : ''}"
                                             style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                             onclick="setSpotImage(${spotId}, ${idx})"
                                             data-thumbnail-index="${idx}">
                                    `).join('')}
                                </div>
                            ` : ''}
                        </div>
                        <div class="col-lg-4">
                            <h4>About This Spot</h4>
                            <p class="text-muted-custom">${spot.description}</p>
                            
                            <h5 class="mt-4">Highlights</h5>
                            <ul class="list-unstyled">
                                ${spot.highlights.map(h => `
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>${h}</li>
                                `).join('')}
                            </ul>

                            <h5 class="mt-4">Amenities</h5>
<div class="d-flex flex-wrap gap-2">
    ${(typeof spot.amenities === 'string' ? spot.amenities.split(',') : spot.amenities).map(a => `
        <span class="badge bg-secondary">${a.trim()}</span>
    `).join('')}
</div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted-custom">Price per Person:</span>
                                    <span class="fw-medium text-ocean-medium">₱${spot.price}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted-custom">Location:</span>
                                    <span class="fw-medium">${spot.location}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted-custom">Status:</span>
                                    <span class="badge ${spot.status === 'active' ? 'bg-success' : 'bg-secondary'}">${spot.status}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings for this spot -->
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Upcoming Bookings</h3>
                    <p class="custom-card-description">Next scheduled visits for this spot</p>
                </div>
                <div class="custom-card-body">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Visitors</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Maria Santos</td>
                                    <td>Oct 25, 2025</td>
                                    <td>4</td>
                                    <td><span class="badge badge-confirmed">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td>Juan Dela Cruz</td>
                                    <td>Oct 27, 2025</td>
                                    <td>2</td>
                                    <td><span class="badge badge-confirmed">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td>Ana Reyes</td>
                                    <td>Oct 30, 2025</td>
                                    <td>6</td>
                                    <td><span class="badge badge-pending">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('viewSpotModal'));
    modal.show();
}

function changeSpotImage(spotId, direction) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot || spot.images.length <= 1) return;
    
    currentImageIndex += direction;
    
    // Loop around
    if (currentImageIndex < 0) {
        currentImageIndex = spot.images.length - 1;
    } else if (currentImageIndex >= spot.images.length) {
        currentImageIndex = 0;
    }
    
    // Update image
    const imgElement = document.getElementById('spotDetailImage');
    if (imgElement) {
        imgElement.src = spot.images[currentImageIndex];
    }
    
    // Update counter
    const counter = document.getElementById('imageCounter');
    if (counter) {
        counter.textContent = `${currentImageIndex + 1} / ${spot.images.length}`;
    }
    
    // Update thumbnails
    updateThumbnailBorders();
}

function setSpotImage(spotId, index) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot) return;
    
    currentImageIndex = index;
    
    // Update image
    const imgElement = document.getElementById('spotDetailImage');
    if (imgElement) {
        imgElement.src = spot.images[currentImageIndex];
    }
    
    // Update counter
    const counter = document.getElementById('imageCounter');
    if (counter) {
        counter.textContent = `${currentImageIndex + 1} / ${spot.images.length}`;
    }
    
    // Update thumbnails
    updateThumbnailBorders();
}

function updateThumbnailBorders() {
    const thumbnails = document.querySelectorAll('.spot-thumbnail');
    thumbnails.forEach((thumb, idx) => {
        if (idx === currentImageIndex) {
            thumb.classList.add('border-primary');
            thumb.classList.remove('border-secondary');
        } else {
            thumb.classList.remove('border-primary');
            thumb.classList.add('border-secondary');
        }
    });
}

function manageSpot(spotId) {
    // This will redirect to the manage spot page with the specific spot ID
    alert(`Redirecting to manage spot ${spotId}...`);
    // In real implementation, you would load the manage page with this spot's data
}

function saveNewSpot() {
    const name = document.getElementById('newSpotName').value;
    const location = document.getElementById('newSpotLocation').value;
    const description = document.getElementById('newSpotDescription').value;
    const price = document.getElementById('newSpotPrice').value;
    const capacity = document.getElementById('newSpotCapacity').value;
    
    if (!name || !location || !description || !price || !capacity) {
        alert('Please fill in all fields');
        return;
    }
    
    alert(`New spot "${name}" added successfully!`);
    bootstrap.Modal.getInstance(document.getElementById('addNewSpotModal')).hide();
    
    // Clear form
    document.getElementById('addSpotForm').reset();
    
    // In real implementation, you would add the new spot to the array and reload the grid
}

// Make functions available globally
window.viewSpotDetails = viewSpotDetails;
window.changeSpotImage = changeSpotImage;
window.setSpotImage = setSpotImage;
window.manageSpot = manageSpot;
window.saveNewSpot = saveNewSpot;
window.loadTouristSpotsGrid = loadTouristSpotsGrid;