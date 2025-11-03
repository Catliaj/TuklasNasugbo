<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Itinerary - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/itinerary.css")?>">
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
                        <a href="/tourist/myBookings" class="nav-link active">
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
                        <a href="/tourist/reviews" class="nav-link">
                            <i class="bi bi-star"></i>
                            <span>My Reviews</span>
                        </a>
                    </li>
                  
                
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">My Itinerary</h1>
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

            <!-- Itinerary Content -->
            <div class="itinerary-container">
                <!-- Itinerary Header -->
                <div class="itinerary-header">
                    <div class="trip-title-section">
                        <h2 class="trip-title">Nasugbu Adventure Trip</h2>
                        <div class="trip-actions">
                            <button class="btn-action" onclick="shareTrip()">
                                <i class="bi bi-share"></i> Share
                            </button>
                            <button class="btn-action" onclick="exportTrip()">
                                <i class="bi bi-download"></i> Export
                            </button>
                            <button class="btn-action primary" onclick="editTripDetails()">
                                <i class="bi bi-pencil"></i> Edit Trip
                            </button>
                        </div>
                    </div>
                    <div class="trip-info">
                        <div class="trip-info-item">
                            <i class="bi bi-calendar3"></i>
                            <span>December 15-18, 2024</span>
                        </div>
                        <div class="trip-info-item">
                            <i class="bi bi-clock"></i>
                            <span>4 Days</span>
                        </div>
                        <div class="trip-info-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>Nasugbu, Batangas</span>
                        </div>
                        <div class="trip-info-item">
                            <i class="bi bi-people"></i>
                            <span>2 Travelers</span>
                        </div>
                    </div>
                </div>

                <!-- Itinerary Layout -->
                <div class="itinerary-layout">
                    <!-- Timeline Section -->
                    <div class="timeline-section">
                        <div class="timeline-header">
                            <h3 class="timeline-title">Trip Timeline</h3>
                            <button class="btn-add-day" onclick="addNewDay()">
                                <i class="bi bi-plus-circle"></i> Add Day
                            </button>
                        </div>

                        <!-- Day 1 -->
                        <div class="day-card" id="day1">
                            <div class="day-header" onclick="toggleDay('day1')">
                                <div class="day-header-left">
                                    <div class="day-number">Day 1</div>
                                    <div class="day-date">Saturday, December 15</div>
                                </div>
                                <div class="day-header-right">
                                    <div class="day-stats">
                                        <div class="day-stat">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>3 places</span>
                                        </div>
                                        <div class="day-stat">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>₱3,500</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>
                            <div class="day-content">
                                <!-- Activity 1 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon lodging">
                                        <i class="bi bi-house-door"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Check-in at Canyon Cove Resort</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 2:00 PM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Piloto Wawa, Nasugbu</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱2,000</span>
                                            </div>
                                        </div>
                                        <div class="activity-notes">
                                            📝 Bring confirmation number. Early check-in available.
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Activity 2 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon place">
                                        <i class="bi bi-water"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Beach Relaxation</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 3:30 PM - 6:00 PM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Canyon Cove Beach</span>
                                            </div>
                                        </div>
                                        <div class="activity-notes">
                                            📝 Don't forget sunscreen and beach towels
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Activity 3 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon food">
                                        <i class="bi bi-cup-hot"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Dinner at Resort Restaurant</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 7:00 PM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Canyon Cove</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱1,500</span>
                                            </div>
                                        </div>
                                        <div class="activity-notes">
                                            📝 Try the seafood platter - highly recommended!
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button class="add-activity-btn" onclick="openAddActivityModal('day1')">
                                    <i class="bi bi-plus-circle"></i> Add Activity
                                </button>
                            </div>
                        </div>

                        <!-- Day 2 -->
                        <div class="day-card" id="day2">
                            <div class="day-header" onclick="toggleDay('day2')">
                                <div class="day-header-left">
                                    <div class="day-number">Day 2</div>
                                    <div class="day-date">Sunday, December 16</div>
                                </div>
                                <div class="day-header-right">
                                    <div class="day-stats">
                                        <div class="day-stat">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>2 places</span>
                                        </div>
                                        <div class="day-stat">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>₱2,800</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>
                            <div class="day-content">
                                <!-- Activity 1 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon place">
                                        <i class="bi bi-island"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Fortune Island Tour</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 6:00 AM - 2:00 PM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Fortune Island</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱2,000</span>
                                            </div>
                                        </div>
                                        <div class="activity-notes">
                                            📝 Boat leaves at 6 AM sharp. Bring water and snacks. Greek ruins photo spot!
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Activity 2 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon food">
                                        <i class="bi bi-cup-hot"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Lunch at Local Restaurant</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 3:00 PM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Nasugbu Town</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱800</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button class="add-activity-btn" onclick="openAddActivityModal('day2')">
                                    <i class="bi bi-plus-circle"></i> Add Activity
                                </button>
                            </div>
                        </div>

                        <!-- Day 3 -->
                        <div class="day-card" id="day3">
                            <div class="day-header" onclick="toggleDay('day3')">
                                <div class="day-header-left">
                                    <div class="day-number">Day 3</div>
                                    <div class="day-date">Monday, December 17</div>
                                </div>
                                <div class="day-header-right">
                                    <div class="day-stats">
                                        <div class="day-stat">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>2 places</span>
                                        </div>
                                        <div class="day-stat">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>₱1,200</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>
                            <div class="day-content">
                                <!-- Activity 1 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon place">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Visit Caleruega Church</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 9:00 AM - 11:00 AM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Caleruega, Nasugbu</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱200</span>
                                            </div>
                                        </div>
                                        <div class="activity-notes">
                                            📝 Beautiful hilltop chapel with garden views. Great for photos!
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Activity 2 -->
                                <div class="activity-item" draggable="true">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon food">
                                        <i class="bi bi-cup-hot"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Brunch at Mountain Cafe</h4>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i> 11:30 AM
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Near Caleruega</span>
                                            </div>
                                            <div class="activity-meta-item">
                                                <i class="bi bi-currency-dollar"></i>
                                                <span>₱1,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" onclick="editActivity(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button class="add-activity-btn" onclick="openAddActivityModal('day3')">
                                    <i class="bi bi-plus-circle"></i> Add Activity
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="summary-sidebar">
                        <!-- Budget Summary -->
                        <div class="summary-card">
                            <h3 class="summary-card-title">
                                <i class="bi bi-wallet2"></i> Trip Budget
                            </h3>
                            <div class="budget-total">
                                <div class="budget-label">Total Estimated Cost</div>
                                <div class="budget-amount">₱7,500</div>
                            </div>
                            <div class="budget-breakdown">
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon lodging">
                                            <i class="bi bi-house-door"></i>
                                        </div>
                                        <span>Accommodation</span>
                                    </div>
                                    <div class="budget-item-amount">₱2,000</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon food">
                                            <i class="bi bi-cup-hot"></i>
                                        </div>
                                        <span>Food & Drinks</span>
                                    </div>
                                    <div class="budget-item-amount">₱3,300</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon place">
                                            <i class="bi bi-ticket"></i>
                                        </div>
                                        <span>Activities</span>
                                    </div>
                                    <div class="budget-item-amount">₱2,200</div>
                                </div>
                            </div>
                        </div>

                        <!-- Places Map -->
                        <div class="summary-card">
                            <h3 class="summary-card-title">
                                <i class="bi bi-map"></i> Places to Visit
                            </h3>
                            <div class="map-container">
                                <i class="bi bi-geo-alt" style="font-size: 3rem;"></i>
                                <div style="margin-top: 1rem;">Map Preview</div>
                            </div>
                            <div class="places-list">
                                <div class="place-item">
                                    <div class="place-number">1</div>
                                    <div class="place-name">Canyon Cove Resort</div>
                                </div>
                                <div class="place-item">
                                    <div class="place-number">2</div>
                                    <div class="place-name">Fortune Island</div>
                                </div>
                                <div class="place-item">
                                    <div class="place-number">3</div>
                                    <div class="place-name">Caleruega Church</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="summary-card">
                            <h3 class="summary-card-title">
                                <i class="bi bi-bar-chart"></i> Trip Stats
                            </h3>
                            <div class="budget-breakdown">
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <i class="bi bi-calendar3"></i>
                                        <span>Total Days</span>
                                    </div>
                                    <div class="budget-item-amount">4</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Places</span>
                                    </div>
                                    <div class="budget-item-amount">7</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <i class="bi bi-cup-hot"></i>
                                        <span>Meals</span>
                                    </div>
                                    <div class="budget-item-amount">5</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Activity Modal -->
    <div class="modal fade" id="addActivityModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="activityForm">
                        <div class="mb-3">
                            <label class="form-label">Activity Type</label>
                            <select class="form-select" id="activityType" required>
                                <option value="place">Place/Attraction</option>
                                <option value="food">Food & Drinks</option>
                                <option value="lodging">Accommodation</option>
                                <option value="transport">Transportation</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Activity Name</label>
                            <input type="text" class="form-control" id="activityName" placeholder="e.g., Visit Mount Batulao" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="activityStartTime">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" id="activityEndTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" id="activityLocation" placeholder="e.g., Nasugbu, Batangas">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estimated Cost (₱)</label>
                            <input type="number" class="form-control" id="activityCost" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="activityNotes" rows="3" placeholder="Add notes, tips, or reminders..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveActivity()" style="background-color: var(--ocean-blue); border: none;">
                        <i class="bi bi-plus-circle"></i> Add Activity
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Global variables
        let currentDay = '';
        let draggedElement = null;

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Day card toggle
        function toggleDay(dayId) {
            const dayCard = document.getElementById(dayId);
            dayCard.classList.toggle('collapsed');
        }

        // Add new day
        function addNewDay() {
            alert('Add new day functionality - Backend integration needed');
        }

        // Share trip
        function shareTrip() {
            alert('Share trip functionality - Backend integration needed');
        }

        // Export trip
        function exportTrip() {
            alert('Export trip to PDF/iCal - Backend integration needed');
        }

        // Edit trip details
        function editTripDetails() {
            alert('Edit trip details modal - Backend integration needed');
        }

        // Open add activity modal
        function openAddActivityModal(dayId) {
            currentDay = dayId;
            const modal = new bootstrap.Modal(document.getElementById('addActivityModal'));
            modal.show();
        }

        // Save activity
        function saveActivity() {
            const type = document.getElementById('activityType').value;
            const name = document.getElementById('activityName').value;
            const startTime = document.getElementById('activityStartTime').value;
            const endTime = document.getElementById('activityEndTime').value;
            const location = document.getElementById('activityLocation').value;
            const cost = document.getElementById('activityCost').value;
            const notes = document.getElementById('activityNotes').value;

            if (!name) {
                alert('Please enter activity name');
                return;
            }

            // Create time string
            let timeStr = '';
            if (startTime && endTime) {
                timeStr = `${formatTime(startTime)} - ${formatTime(endTime)}`;
            } else if (startTime) {
                timeStr = formatTime(startTime);
            }

            // Get icon based on type
            let iconClass = '';
            let iconBg = '';
            switch(type) {
                case 'place':
                    iconClass = 'bi-geo-alt';
                    iconBg = 'place';
                    break;
                case 'food':
                    iconClass = 'bi-cup-hot';
                    iconBg = 'food';
                    break;
                case 'lodging':
                    iconClass = 'bi-house-door';
                    iconBg = 'lodging';
                    break;
                case 'transport':
                    iconClass = 'bi-car-front';
                    iconBg = 'transport';
                    break;
            }

            // Create activity HTML
            const activityHTML = `
                <div class="activity-item" draggable="true">
                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                    <div class="activity-icon ${iconBg}">
                        <i class="bi ${iconClass}"></i>
                    </div>
                    <div class="activity-details">
                        <div class="activity-header">
                            <h4 class="activity-title">${name}</h4>
                            ${timeStr ? `<div class="activity-time"><i class="bi bi-clock"></i> ${timeStr}</div>` : ''}
                        </div>
                        <div class="activity-meta">
                            ${location ? `<div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>${location}</span></div>` : ''}
                            ${cost ? `<div class="activity-meta-item"><i class="bi bi-currency-dollar"></i><span>₱${cost}</span></div>` : ''}
                        </div>
                        ${notes ? `<div class="activity-notes">📝 ${notes}</div>` : ''}
                    </div>
                    <div class="activity-actions">
                        <button class="btn-activity-action" onclick="editActivity(this)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-activity-action delete" onclick="deleteActivity(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            // Add to day content before the add button
            const dayContent = document.querySelector(`#${currentDay} .day-content`);
            const addButton = dayContent.querySelector('.add-activity-btn');
            addButton.insertAdjacentHTML('beforebegin', activityHTML);

            // Setup drag and drop for new element
            setupDragAndDrop();

            // Update day stats
            updateDayStats(currentDay);

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addActivityModal'));
            modal.hide();
            document.getElementById('activityForm').reset();
        }

        // Format time to 12-hour format
        function formatTime(time24) {
            const [hours, minutes] = time24.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        // Edit activity
        function editActivity(button) {
            alert('Edit activity functionality - Backend integration needed');
        }

        // Delete activity
        function deleteActivity(button) {
            if (confirm('Are you sure you want to delete this activity?')) {
                const activityItem = button.closest('.activity-item');
                const dayCard = button.closest('.day-card');
                activityItem.remove();
                updateDayStats(dayCard.id);
            }
        }

        // Update day stats
        function updateDayStats(dayId) {
            const dayCard = document.getElementById(dayId);
            const activities = dayCard.querySelectorAll('.activity-item');
            const placesCount = activities.length;
            
            // Calculate total cost
            let totalCost = 0;
            activities.forEach(activity => {
                const costElement = activity.querySelector('.activity-meta-item:has(.bi-currency-dollar) span');
                if (costElement) {
                    const cost = parseInt(costElement.textContent.replace('₱', '').replace(',', ''));
                    if (!isNaN(cost)) {
                        totalCost += cost;
                    }
                }
            });

            // Update stats display
            const statsDiv = dayCard.querySelector('.day-stats');
            statsDiv.innerHTML = `
                <div class="day-stat">
                    <i class="bi bi-geo-alt"></i>
                    <span>${placesCount} places</span>
                </div>
                <div class="day-stat">
                    <i class="bi bi-currency-dollar"></i>
                    <span>₱${totalCost.toLocaleString()}</span>
                </div>
            `;

            // Update total budget
            updateTotalBudget();
        }

        // Update total budget
        function updateTotalBudget() {
            let totalBudget = 0;
            let accommodationTotal = 0;
            let foodTotal = 0;
            let activitiesTotal = 0;

            document.querySelectorAll('.activity-item').forEach(activity => {
                const costElement = activity.querySelector('.activity-meta-item:has(.bi-currency-dollar) span');
                if (costElement) {
                    const cost = parseInt(costElement.textContent.replace('₱', '').replace(',', ''));
                    if (!isNaN(cost)) {
                        totalBudget += cost;

                        // Categorize by icon
                        const icon = activity.querySelector('.activity-icon');
                        if (icon.classList.contains('lodging')) {
                            accommodationTotal += cost;
                        } else if (icon.classList.contains('food')) {
                            foodTotal += cost;
                        } else if (icon.classList.contains('place')) {
                            activitiesTotal += cost;
                        }
                    }
                }
            });

            // Update budget display
            document.querySelector('.budget-amount').textContent = `₱${totalBudget.toLocaleString()}`;
            
            const budgetItems = document.querySelectorAll('.budget-breakdown .budget-item-amount');
            if (budgetItems.length >= 3) {
                budgetItems[0].textContent = `₱${accommodationTotal.toLocaleString()}`;
                budgetItems[1].textContent = `₱${foodTotal.toLocaleString()}`;
                budgetItems[2].textContent = `₱${activitiesTotal.toLocaleString()}`;
            }
        }

        // Drag and Drop functionality
        function setupDragAndDrop() {
            const activities = document.querySelectorAll('.activity-item');
            
            activities.forEach(activity => {
                activity.addEventListener('dragstart', handleDragStart);
                activity.addEventListener('dragend', handleDragEnd);
                activity.addEventListener('dragover', handleDragOver);
                activity.addEventListener('drop', handleDrop);
                activity.addEventListener('dragenter', handleDragEnter);
                activity.addEventListener('dragleave', handleDragLeave);
            });
        }

        function handleDragStart(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }

        function handleDragEnd(e) {
            this.classList.remove('dragging');
            
            // Remove all drag-over classes
            document.querySelectorAll('.activity-item').forEach(item => {
                item.classList.remove('drag-over');
            });
        }

        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDragEnter(e) {
            if (this !== draggedElement) {
                this.classList.add('drag-over');
            }
        }

        function handleDragLeave(e) {
            this.classList.remove('drag-over');
        }

        function handleDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (draggedElement !== this) {
                const parent = this.parentNode;
                const draggedIndex = Array.from(parent.children).indexOf(draggedElement);
                const targetIndex = Array.from(parent.children).indexOf(this);

                if (draggedIndex < targetIndex) {
                    parent.insertBefore(draggedElement, this.nextSibling);
                } else {
                    parent.insertBefore(draggedElement, this);
                }
            }

            return false;
        }

        // Initialize drag and drop on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();
        });

        // Close sidebar on mobile when clicking outside
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
