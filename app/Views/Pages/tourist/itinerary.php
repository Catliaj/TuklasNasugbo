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
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/itinerary.css')?>"> 
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
                <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()" style="background: none; border: none; color: #fff; font-size: 1.5rem;">
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
                        <a href="/tourist/itinerary" class="nav-link active">
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
            <h1 class="page-title">My Itinerary</h1>

            <!-- Fixed User Actions (Top Right) -->
            <div class="user-actions-fixed">
                <!-- Notification -->
                <div style="position: relative;">
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
                                        <h6>Itinerary Updated</h6>
                                        <p>Your Nasugbu Adventure itinerary has been updated</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>1 hour ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item unread">
                                <div class="notification-content">
                                    <div class="notification-icon info"><i class="bi bi-people-fill"></i></div>
                                    <div class="notification-text">
                                        <h6>Buddy Invited</h6>
                                        <p>Alex Brown accepted your trip buddy invitation</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>3 hours ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item unread">
                                <div class="notification-content">
                                    <div class="notification-icon warning"><i class="bi bi-calendar-event"></i></div>
                                    <div class="notification-text">
                                        <h6>Trip Reminder</h6>
                                        <p>Your trip starts in 5 days - December 15, 2024</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>1 day ago</span></div>
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
                <div style="position: relative;">
                    <div class="user-avatar" onclick="toggleUserDropdown()">JD</div>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <h6>Juan Dela Cruz</h6>
                            <p>juan.delacruz@email.com</p>
                        </div>
                        <ul class="menu">
                            <li><a href="#" onclick="openProfile(event); hideUserDropdown(event)"><i class="bi bi-person-circle"></i> <span>My Profile</span></a></li>
                            <li><a class="logout" href="#" onclick="handleLogout(event)"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Itinerary Content -->
            <div class="itinerary-container">
                <!-- Itinerary Header -->
                <div class="itinerary-header">
                    <div class="trip-title-section">
                        <h2 class="trip-title" id="tripTitle">Nasugbu Adventure Trip</h2>
                        <div class="trip-actions">
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                                <i class="bi bi-plus-lg"></i> Create New
                            </button>
                            <button class="btn-action" id="autoGenBtn">
                                <i class="bi bi-lightning-charge"></i> Auto-generate
                            </button>
                            <button class="btn-action">
                                <i class="bi bi-share"></i> Share
                            </button>
                            <button class="btn-action">
                                <i class="bi bi-download"></i> Export
                            </button>
                            <button class="btn-action primary" id="editTripBtn">
                                <i class="bi bi-pencil"></i> Edit Trip
                            </button>
                        </div>
                    </div>
                    <div class="trip-info" id="tripInfo">
                        <div class="trip-info-item"><i class="bi bi-calendar3"></i><span>December 15-18, 2024</span></div>
                        <div class="trip-info-item"><i class="bi bi-clock"></i><span>4 Days</span></div>
                        <div class="trip-info-item"><i class="bi bi-geo-alt"></i><span>Nasugbu, Batangas</span></div>
                        <div class="trip-info-item"><i class="bi bi-people"></i><span>2 Travelers</span></div>
                    </div>
                </div>

                <!-- Itinerary Layout -->
                <div class="itinerary-layout">
                    <!-- Timeline Section -->
                    <div class="timeline-section">
                        <div class="timeline-header">
                            <h3 class="timeline-title">Trip Timeline</h3>
                            <button class="btn-add-day" onclick="openAddDayModal()"><i class="bi bi-plus-circle"></i> Add Day</button>
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
                                        <div class="day-stat"><i class="bi bi-geo-alt"></i><span>3 places</span></div>
                                        <div class="day-stat"><i class="bi bi-cash-stack"></i><span>₱3,500</span></div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>
                            <div class="day-content">
                                <div class="activity-item" data-type="lodging">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon lodging"><i class="bi bi-house-door"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Check-in at Canyon Cove Resort</h4>
                                            <div class="activity-time"><i class="bi bi-clock"></i> 2:00 PM</div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>Canyon Cove Resort</span></div>
                                            <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱2,000</span></div>
                                        </div>
                                        <div class="activity-notes">📝 Bring confirmation number. Early check-in available.</div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <div class="activity-item" data-type="place">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon place"><i class="bi bi-water"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Beach Relaxation</h4>
                                            <div class="activity-time"><i class="bi bi-clock"></i> 3:30 PM - 6:00 PM</div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>Canyon Cove Beach</span></div>
                                        </div>
                                        <div class="activity-notes">📝 Don't forget sunscreen and beach towels</div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <div class="activity-item" data-type="food">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon food"><i class="bi bi-cup-hot"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Dinner at Resort Restaurant</h4>
                                            <div class="activity-time"><i class="bi bi-clock"></i> 7:00 PM</div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>Canyon Cove</span></div>
                                            <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱1,500</span></div>
                                        </div>
                                        <div class="activity-notes">📝 Try the seafood platter - highly recommended!</div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <button class="add-activity-btn">
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
                                        <div class="day-stat"><i class="bi bi-geo-alt"></i><span>2 places</span></div>
                                        <div class="day-stat"><i class="bi bi-cash-stack"></i><span>₱2,800</span></div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>
                            <div class="day-content">
                                <div class="activity-item" data-type="place">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon place"><i class="bi bi-compass"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Fortune Island Tour</h4>
                                            <div class="activity-time"><i class="bi bi-clock"></i> 6:00 AM - 2:00 PM</div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>Fortune Island</span></div>
                                            <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱2,000</span></div>
                                        </div>
                                        <div class="activity-notes">📝 Boat leaves at 6 AM sharp. Bring water and snacks. Greek ruins photo spot!</div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <div class="activity-item" data-type="food">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon food"><i class="bi bi-cup-hot"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title">Lunch at Local Restaurant</h4>
                                            <div class="activity-time"><i class="bi bi-clock"></i> 3:00 PM</div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>Nasugbu Town</span></div>
                                            <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱800</span></div>
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <button class="add-activity-btn">
                                    <i class="bi bi-plus-circle"></i> Add Activity
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="summary-sidebar">
                        <!-- Map Card -->
                        <div class="summary-card">
                            <h3 class="summary-card-title"><i class="bi bi-map"></i> Map View</h3>
                            <div class="map-container">
                                <div id="map" style="display: flex; align-items: center; justify-content: center; color: #999;">Map placeholder</div>
                            </div>
                        </div>

                        <!-- Budget & Buddies Card -->
                        <div class="summary-card">
                            <h3 class="summary-card-title"><i class="bi bi-wallet2"></i> Trip Budget</h3>
                            <div class="budget-total">
                                <div class="budget-label">Total Estimated Cost</div>
                                <div class="budget-amount">₱6,300</div>
                            </div>
                            <div class="budget-breakdown">
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon" style="background: #3498db;"><i class="bi bi-house-door"></i></div>
                                        <span>Accommodation</span>
                                    </div>
                                    <div class="budget-item-amount">₱2,000</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon" style="background: #e67e22;"><i class="bi bi-cup-hot"></i></div>
                                        <span>Food & Drinks</span>
                                    </div>
                                    <div class="budget-item-amount">₱2,300</div>
                                </div>
                                <div class="budget-item">
                                    <div class="budget-item-left">
                                        <div class="budget-item-icon" style="background: #9b59b6;"><i class="bi bi-geo-alt"></i></div>
                                        <span>Activities</span>
                                    </div>
                                    <div class="budget-item-amount">₱2,000</div>
                                </div>
                            </div>

                            <h3 class="summary-card-title" style="margin-top: 1.5rem;"><i class="bi bi-people"></i> Trip Buddies</h3>
                            <div class="trip-buddies" id="tripBuddies">
                                <div class="buddy-item">
                                    <div class="buddy-info">
                                        <div class="buddy-avatar">AB</div>
                                        <div>
                                            <div class="buddy-name">Alex Brown</div>
                                            <div class="buddy-share">₱3,150 (50%)</div>
                                        </div>
                                    </div>
                                    <div class="buddy-actions">
                                        <button class="btn-buddy-action" onclick="viewBuddyDetails(this)"><i class="bi bi-info-circle"></i></button>
                                        <button class="btn-buddy-action" onclick="removeBuddy(this)"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-invite-buddy" onclick="openInviteBuddyModal()">
                                <i class="bi bi-person-plus"></i> Invite Trip Buddy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- My Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-circle"></i> My Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="profile-avatar-large">
                            JD
                            <label class="avatar-upload-btn">
                                <i class="bi bi-camera-fill"></i>
                                <input type="file" accept="image/*" onchange="handleAvatarUpload(event)">
                            </label>
                        </div>
                        <small class="text-muted">Click the camera icon to change profile picture</small>
                    </div>
                    <form id="profileForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="profileFirstName" value="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="profileLastName" value="Dela Cruz" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="profileEmail" value="juan.delacruz@email.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="profilePhone" value="+63 912 345 6789" placeholder="+63 XXX XXX XXXX">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" id="profileBio" rows="3" placeholder="Tell us about yourself...">Adventure seeker and travel enthusiast exploring the beautiful beaches of Batangas!</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" id="profileLocation" value="Manila, Philippines">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="profileDOB" value="1995-06-15">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveProfile()">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Buddy Modal -->
    <div class="modal fade" id="inviteBuddyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Invite Trip Buddy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="inviteBuddyForm">
                        <div class="mb-3">
                            <label class="form-label">Buddy's Email</label>
                            <input type="email" class="form-control" id="buddyEmail" placeholder="friend@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expense Share (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="buddyShare" value="50" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="sendBuddyInvite()">Send Invite</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Buddy Details Modal -->
    <div class="modal fade" id="buddyDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buddy Expense Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="buddyDetailsBody">
                    <!-- populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Activity Modal -->
    <div class="modal fade" id="addActivityModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> <span id="activityModalTitle">Add Activity</span>
                        <small class="text-muted ms-2" id="activityModalDayLabel"></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Recommended -->
                    <div class="mb-3">
                        <div class="suggested-header">Recommended nearby</div>
                        <input type="text" class="form-control mb-2" id="recommendSearch" placeholder="Search...">
                        <div class="suggested-grid" id="suggestedGrid">
                            <div class="suggested-card" data-type="place" data-title="Mount Batulao Viewpoint" data-location="Nasugbu, Batangas" data-cost="0">
                                <div class="sugg-icon place"><i class="bi bi-geo-alt"></i></div>
                                <div class="sugg-info">
                                    <div class="sugg-title">Mount Batulao Viewpoint</div>
                                    <div class="sugg-meta">Attraction • Nasugbu, Batangas • ₱0</div>
                                </div>
                                <button class="sugg-add" type="button"><i class="bi bi-plus-lg"></i></button>
                            </div>
                            <div class="suggested-card" data-type="place" data-title="Calayo Beach" data-location="Calayo, Nasugbu" data-cost="50">
                                <div class="sugg-icon place"><i class="bi bi-geo-alt"></i></div>
                                <div class="sugg-info">
                                    <div class="sugg-title">Calayo Beach</div>
                                    <div class="sugg-meta">Attraction • Calayo, Nasugbu • ₱50</div>
                                </div>
                                <button class="sugg-add" type="button"><i class="bi bi-plus-lg"></i></button>
                            </div>
                            <div class="suggested-card" data-type="food" data-title="Beachfront Grill" data-location="Nasugbu Town" data-cost="350">
                                <div class="sugg-icon food"><i class="bi bi-cup-hot"></i></div>
                                <div class="sugg-info">
                                    <div class="sugg-title">Beachfront Grill</div>
                                    <div class="sugg-meta">Food • Nasugbu Town • ₱350</div>
                                </div>
                                <button class="sugg-add" type="button"><i class="bi bi-plus-lg"></i></button>
                            </div>
                            <div class="suggested-card" data-type="lodging" data-title="Coastal Inn" data-location="Nasugbu" data-cost="1500">
                                <div class="sugg-icon lodging"><i class="bi bi-house-door"></i></div>
                                <div class="sugg-info">
                                    <div class="sugg-title">Coastal Inn</div>
                                    <div class="sugg-meta">Accommodation • Nasugbu • ₱1,500</div>
                                </div>
                                <button class="sugg-add" type="button"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="sugg-divider"><span>or customize</span></div>

                    <!-- Form -->
                    <form id="activityForm">
                        <div class="mb-2">
                            <label class="form-label">Activity Type</label>
                            <select class="form-select" id="activityType">
                                <option value="place">Place/Attraction</option>
                                <option value="food">Food & Drinks</option>
                                <option value="lodging">Accommodation</option>
                                <option value="transport">Transportation</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Activity Name</label>
                            <input type="text" class="form-control" id="activityName" placeholder="e.g., Visit Mount Batulao">
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="activityStart">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" id="activityEnd">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" id="activityLocation" placeholder="e.g., Nasugbu, Batangas">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Estimated Cost (₱)</label>
                            <input type="number" class="form-control" id="activityCost" placeholder="0" min="0" step="1">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="activityNotes" rows="3" placeholder="Add notes, tips, or reminders..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="activitySaveBtn">
                        <i class="bi bi-check2-circle"></i> Add / Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Trip Modal -->
    <div class="modal fade" id="editTripModal" tabindex="-1" aria-labelledby="editTripModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTripModalLabel">Edit Trip Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="tripForm">
              <div class="mb-3"><label class="form-label">Trip Title</label><input id="tripTitleInput" class="form-control" /></div>
              <div class="row mb-3"><div class="col"><label class="form-label">Start Date</label><input id="tripStart" class="form-control" type="date" /></div><div class="col"><label class="form-label">End Date</label><input id="tripEnd" class="form-control" type="date" /></div></div>
              <div class="mb-3"><label class="form-label">Location</label><input id="tripLocationInput" class="form-control" /></div>
              <div class="mb-3"><label class="form-label">Travelers</label><input id="tripTravelersInput" class="form-control" type="number" min="1" /></div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" data-bs-dismiss="modal">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Auto-generated Itinerary Modal -->
    <div class="modal fade" id="generatedModal" tabindex="-1" aria-labelledby="generatedModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="generatedModalLabel">
              <i class="bi bi-lightning-charge-fill"></i> Auto-generate Itinerary
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="generatedModalBody">
            <form id="autoGenerateForm">
              <!-- Trip Title -->
              <div class="mb-3">
                <label class="form-label">Trip Title</label>
                <input type="text" class="form-control" id="autoGenTripTitle" placeholder="e.g., Nasugbu Adventure" required>
              </div>

              <!-- Date Range -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Start Date</label>
                  <input type="date" class="form-control" id="autoGenStartDate" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">End Date</label>
                  <input type="date" class="form-control" id="autoGenEndDate" required>
                </div>
              </div>

              <!-- Number of Days -->
              <div class="mb-3">
                <label class="form-label">Number of Days</label>
                <input type="number" class="form-control" id="autoGenDay" min="1" max="30" value="3" required>
                <small class="form-text text-muted">How many days do you want to plan?</small>
              </div>

              <!-- Budget -->
              <div class="mb-3">
                <label class="form-label">Budget (₱)</label>
                <input type="number" class="form-control" id="autoGenBudget" min="0" step="100" placeholder="e.g., 5000" required>
                <small class="form-text text-muted">Your estimated budget for the entire trip</small>
              </div>

              <!-- Travelers Section -->
              <div class="mb-3">
                <label class="form-label">Number of Travelers</label>
                <div class="row g-2">
                  <div class="col-md-4">
                    <label class="form-label small">Adults</label>
                    <input type="number" class="form-control" id="autoGenAdults" min="0" value="1" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label small">Children</label>
                    <input type="number" class="form-control" id="autoGenChildren" min="0" value="0">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label small">Seniors</label>
                    <input type="number" class="form-control" id="autoGenSeniors" min="0" value="0">
                  </div>
                </div>
              </div>

              <!-- Preview Section (initially hidden) -->
              <div id="generatedPreview" style="display: none;">
                <hr class="my-4">
                <h6 class="text-muted mb-3">Generated Itinerary Preview:</h6>
                <div id="generatedContent"></div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" id="btnGenerateItinerary">
              <i class="bi bi-lightning-charge-fill"></i> Generate
            </button>
            <button class="btn btn-success" id="btnApplyItinerary" style="display: none;">
              <i class="bi bi-check-circle"></i> Apply to Trip
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create New Itinerary Modal -->
    <div class="modal fade" id="createItineraryModal" tabindex="-1" aria-labelledby="createItineraryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createItineraryModalLabel"><i class="bi bi-plus-lg"></i> Create New Itinerary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="createItineraryForm">
              <div class="mb-3">
                <label class="form-label">Trip Title</label>
                <input type="text" class="form-control" id="newTripTitle" placeholder="e.g., Nasugbu Adventure" required>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label class="form-label">Start Date</label>
                  <input type="date" class="form-control" id="newTripStart" required>
                </div>
                <div class="col">
                  <label class="form-label">End Date</label>
                  <input type="date" class="form-control" id="newTripEnd" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" id="newTripLocation" placeholder="e.g., Nasugbu, Batangas" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Travelers</label>
                <input type="number" class="form-control" id="newTripTravelers" min="1" value="1" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" data-bs-dismiss="modal">Create</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add New Day Modal -->
    <div class="modal fade" id="addDayModal" tabindex="-1" aria-labelledby="addDayModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addDayModalLabel"><i class="bi bi-plus-circle"></i> Add New Day</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="addDayForm">
              <div class="mb-3">
                <label class="form-label">Day Label</label>
                <input type="text" class="form-control" id="newDayLabel" placeholder="e.g., Day 3 (Monday, Dec 17)" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" data-bs-dismiss="modal">Add Day</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toasts (UI only) -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS only (no app logic) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Minimal UI-only dropdown script -->
    <script>
      (function () {
        const notifBtn = document.querySelector('.notification-btn');
        const notifDrop = document.getElementById('notificationDropdown');
        const badge = document.getElementById('notifBadge');
        const userBtn = document.querySelector('.user-avatar');
        const userDrop = document.getElementById('userDropdown');

        // Expose the handlers your HTML calls
        window.toggleNotificationDropdown = function () {
          if (!notifDrop) return;
          userDrop?.classList.remove('show');
          notifDrop.classList.toggle('show');
        };
        window.toggleUserDropdown = function () {
          if (!userDrop) return;
          notifDrop?.classList.remove('show');
          userDrop.classList.toggle('show');
        };
        window.markAllAsRead = function () {
          document.querySelectorAll('#notificationList .notification-item.unread').forEach(li => li.classList.remove('unread'));
          badge?.classList.add('d-none');
        };
        window.viewAllNotifications = function (e) {
          e?.preventDefault();
          notifDrop?.classList.remove('show');
        };
        window.hideUserDropdown = function (e) {
          e?.preventDefault();
          userDrop?.classList.remove('show');
        };
        window.openProfile = function (e) {
          e?.preventDefault();
          const m = document.getElementById('profileModal');
          if (m) bootstrap.Modal.getOrCreateInstance(m).show();
        };
        window.handleLogout = function (e) {
          e?.preventDefault();
          userDrop?.classList.remove('show');
        };

        // Close when clicking outside
        document.addEventListener('click', (e) => {
          if (notifDrop && !notifDrop.contains(e.target) && !notifBtn.contains(e.target)) notifDrop.classList.remove('show');
          if (userDrop && !userDrop.contains(e.target) && !userBtn.contains(e.target)) userDrop.classList.remove('show');
        });
        // Close on Escape
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape') { notifDrop?.classList.remove('show'); userDrop?.classList.remove('show'); }
        });
      })();
    </script>

    <!-- Lightweight UI behavior for Add Day, Trip Buddy, Drag/Drop, Delete -->
 <script>
(function () {
  // Helpers
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // Elements
  const modalEl = $('#generatedModal');
  const btn = $('#autoGenBtn');
  const generateBtn = $('#btnGenerateItinerary');
  const applyBtn = $('#btnApplyItinerary');
  const previewSection = $('#generatedPreview');
  const previewContent = $('#generatedContent');

  // === Set your user preference here ===
const userPreference = "Historical,Natural,Urban,Adventure";
    // Keep last generated request so Apply can trigger server-side save
    let lastItineraryRequest = null;



  // Placeholder markup while fetching
  function placeholderMarkup() {
    return `
      <div class="mb-3 text-center text-muted">
        <i class="bi bi-lightning-charge-fill text-warning"></i>
        <span class="ms-2">Generating your personalized itinerary...</span>
      </div>
      <div class="placeholder-glow">
        <div class="card mb-2"><div class="card-body"><h6 class="placeholder col-6"></h6><p><span class="placeholder col-7"></span></p></div></div>
        <div class="card mb-2"><div class="card-body"><h6 class="placeholder col-5"></h6><p><span class="placeholder col-8"></span></p></div></div>
        <div class="card mb-2"><div class="card-body"><h6 class="placeholder col-4"></h6><p><span class="placeholder col-6"></span></p></div></div>
      </div>
    `;
  }

  // Generate itinerary
 async function generateItinerary() {
  const formData = {
    trip_title: $('#autoGenTripTitle').value,
    start_date: $('#autoGenStartDate').value,
    end_date: $('#autoGenEndDate').value,
    day: $('#autoGenDay').value,
    budget: $('#autoGenBudget').value,
    adults: $('#autoGenAdults').value,
    children: $('#autoGenChildren').value,
    seniors: $('#autoGenSeniors').value
  };

  previewContent.innerHTML = placeholderMarkup();
  previewSection.style.display = 'block';
  generateBtn.disabled = true;

  const url = `http://127.0.0.1:8000/api/recommend/?days=${formData.day}&budget=${formData.budget}&adults=${formData.adults}&children=${formData.children}&seniors=${formData.seniors}&preference=${userPreference}`;

  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Network response was not OK');

    const data = await response.json(); // <-- data is defined here
    console.log("DEBUG: Received itinerary data:", data);
    // Render itinerary inside try
    if (!data.itinerary || !Array.isArray(data.itinerary)) {
      throw new Error('Invalid API response');
    }
    // Save the parameters used so Apply can persist this itinerary later
    lastItineraryRequest = {
      days: formData.day,
      budget: formData.budget,
      adults: formData.adults,
      children: formData.children,
      seniors: formData.seniors,
      preference: userPreference,
      trip_title: formData.trip_title,
      start_date: formData.start_date,
      end_date: formData.end_date
    };
previewContent.innerHTML = data.itinerary.map((day) => {
  const spotsList = day.spots.map((spot) => {
    const isFree = Number(spot.price_per_person) === 0 && Number(spot.child_price) === 0 && Number(spot.senior_price) === 0;
    const priceHtml = isFree
      ? `<li class="text-success"><strong>This spot is free — no admission fee</strong></li>`
      : `
        <li>Price (Adult): ₱${spot.price_per_person}</li>
        <li>Price (Child): ₱${spot.child_price}</li>
        <li>Price (Senior): ₱${spot.senior_price}</li>
      `;

    const totalHtml = Number(spot.total_cost_for_day) === 0
      ? `<li><strong>Total Cost for Day: Free</strong></li>`
      : `<li>Total Cost for Day: ₱${spot.total_cost_for_day}</li>`;

    return `
      <li>
        <strong>${spot.name}</strong> (${spot.category})
        <p>${spot.description}</p>
        <ul>
          <li>Location: ${spot.location}</li>
          ${priceHtml}
          ${totalHtml}
        </ul>
      </li>
    `;
  }).join("");

  return `
    <div class="card mb-2">
      <div class="card-body">
        <h6>Day ${day.day}</h6>
        <ul class="list-unstyled mb-0">
          ${spotsList}
        </ul>
      </div>
    </div>
  `;
}).join("");


// Handle remaining budget message
let message = '';
if (data.itinerary.length < parseInt($('#autoGenDay').value)) {
  message = `<div class="alert alert-warning">
               Only ${data.itinerary.length} day(s) could be generated due to budget constraints.
             </div>`;
} 
message += `<div class="alert alert-info">
              Remaining Budget: ₱${data.remaining_budget}
            </div>`;

// Only append note if present
if (data.note) {
  message += `<div class="alert alert-info">${data.note}</div>`;
}
previewContent.innerHTML += message;



    generateBtn.style.display = 'none';
    applyBtn.style.display = 'inline-block';

  } catch (err) {
    previewContent.innerHTML = `<div class="alert alert-danger">Failed to generate itinerary: ${err.message}</div>`;
    generateBtn.disabled = false;
  }
}


  // Reset modal
  function resetModal() {
    $('#autoGenerateForm').reset();
    previewSection.style.display = 'none';
    generateBtn.style.display = 'inline-block';
    generateBtn.disabled = false;
    applyBtn.style.display = 'none';
  }

  // Event listeners
  document.addEventListener('DOMContentLoaded', () => {
    if (!btn || !modalEl) return;

    btn.addEventListener('click', () => {
      resetModal();
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });

    generateBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      const form = $('#autoGenerateForm');
      if (form.checkValidity()) {
        generateItinerary();
      } else {
        form.reportValidity();
      }
    });

    applyBtn?.addEventListener('click', async () => {
      if (!lastItineraryRequest) {
        alert('No generated itinerary found. Please generate first.');
        return;
      }

      // Disable button to avoid double-clicks
      applyBtn.disabled = true;

      const params = new URLSearchParams({
        days: lastItineraryRequest.days,
        budget: lastItineraryRequest.budget,
        adults: lastItineraryRequest.adults,
        children: lastItineraryRequest.children,
        seniors: lastItineraryRequest.seniors,
        preference: lastItineraryRequest.preference,
        save: '1',
        trip_title: lastItineraryRequest.trip_title || '',
        start_date: lastItineraryRequest.start_date || '',
        end_date: lastItineraryRequest.end_date || ''
      });

      const saveUrl = `http://127.0.0.1:8000/api/recommend/?${params.toString()}`;

      try {
        const resp = await fetch(saveUrl);
        if (!resp.ok) throw new Error('Save request failed');
        const saveData = await resp.json();

        if (saveData.saved) {
          const count = saveData.saved_count || 0;
          alert(`Itinerary saved to your trips (${count} items).`);
          // close modal after successful save
          bootstrap.Modal.getInstance(modalEl).hide();
        } else {
          const err = saveData.saved_error || 'Unknown error while saving';
          alert('Failed to save itinerary: ' + err);
        }
      } catch (err) {
        console.error('Save error', err);
        alert('Failed to save itinerary: ' + err.message);
      } finally {
        applyBtn.disabled = false;
      }
    });

    modalEl.addEventListener('hidden.bs.modal', resetModal);
  });
})();
</script>

  </body>
</html>