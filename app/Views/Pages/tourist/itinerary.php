<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
                            <!-- IMPORTANT: unique id for history button so JS can find it reliably -->
                            <button class="btn-action primary" id="historyBtn">
                                <i class="bi bi-clock-history"></i> Itinerary History
                            </button>
                        </div>
                    </div>
                    <div class="trip-info" id="tripInfo">
                        <div class="trip-info-item"><i class="bi bi-calendar3"></i><span></span></div>
                        <div class="trip-info-item"><i class="bi bi-clock"></i><span></span></div>
                        <div class="trip-info-item"><i class="bi bi-geo-alt"></i><span></span></div>
                        <div class="trip-info-item"><i class="bi bi-people"></i><span></span></div>
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
                      <?php if(!empty($itinerary) && is_array($itinerary)): ?>
                        <?php foreach ($itinerary as $day): ?>
                        <div class="day-card" id="day<?= $day['day_number'] ?>">
                            <div class="day-header" onclick="toggleDay('day<?= $day['day_number'] ?>')">
                                <div class="day-header-left">
                                    <div class="day-number">Day <?= $day['day_number'] ?></div>
                                    <div class="day-date"><?= date('l, F d', strtotime($day['date'])) ?></div>
                                </div>
                                <div class="day-header-right">
                                    <div class="day-stats">
                                        <div class="day-stat"><i class="bi bi-geo-alt"></i><span><?= count($day['activities']) ?> places</span></div>
                                        <div class="day-stat"><i class="bi bi-cash-stack"></i><span>₱<?= array_sum(array_column($day['activities'], 'cost')) ?></span></div>
                                    </div>
                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                </div>
                            </div>

                            <div class="day-content">
                                <?php foreach ($day['activities'] as $activity): ?>
                                <div class="activity-item" data-type="<?= $activity['type'] ?>">
                                    <i class="bi bi-grip-vertical activity-drag-handle"></i>
                                    <div class="activity-icon <?= $activity['type'] ?>">
                                        <?php
                                            if($activity['type'] == 'lodging') echo '<i class="bi bi-house-door"></i>';
                                            elseif($activity['type'] == 'place') echo '<i class="bi bi-geo-alt"></i>';
                                            elseif($activity['type'] == 'food') echo '<i class="bi bi-cup-hot"></i>';
                                            elseif($activity['type'] == 'transport') echo '<i class="bi bi-bus-front"></i>';
                                        ?>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-header">
                                            <h4 class="activity-title"><?= $activity['title'] ?></h4>
                                             <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span><?= isset($activity['description']) ? $activity['description'] : '' ?></span></div>
                                            <div class="activity-time"><i class="bi bi-clock"></i> <?= $activity['start_time'] ?><?= isset($activity['end_time']) ? " - ".$activity['end_time'] : "" ?></div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span><?= $activity['location'] ?></span></div>
                                            <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱<?= $activity['cost'] ?></span></div>

                                        </div>
                                        <?php if(!empty($activity['notes'])): ?>
                                            <div class="activity-notes">📝 <?= $activity['notes'] ?></div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="activity-actions">
                                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <button class="add-activity-btn">
                                    <i class="bi bi-plus-circle"></i> Add Activity
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="day-card">
                            <div class="day-header">
                                <div class="day-header-left">
                                    <div class="day-number"></div>
                                    <div class="day-date"></div>
                                </div>
                            </div>
                        </div>
                      <?php endif; ?>
                     
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
                                <div class="budget-amount"></div>
                            </div>
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
    <!-- jQuery is available but the main rendering uses vanilla JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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

    <!-- Lightweight UI behavior, generator and history integration -->
    <script>
    (function () {
      // Utilities
      const $ = (sel, root = document) => root.querySelector(sel);
      const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

      // Elements for auto-generate modal (kept from your earlier code)
      const modalEl = $('#generatedModal');
      const btn = $('#autoGenBtn');
      const generateBtn = $('#btnGenerateItinerary');
      const applyBtn = $('#btnApplyItinerary');
      const previewSection = $('#generatedPreview');
      const previewContent = $('#generatedContent');

      const userPreference = "<?= esc($categories ?? '') ?>";
      const currentUserID = "<?= esc($userID ?? '') ?>";
      let lastItineraryRequest = null;

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

       const url = `http://127.0.0.1:8000/api/recommend/?days=${formData.day}&budget=${formData.budget}&adults=${formData.adults}&children=${formData.children}&seniors=${formData.seniors}&preference=${userPreference}&start_date=${formData.start_date}&end_date=${formData.end_date}`;  

        try {
          const response = await fetch(url);

          if (response.status === 409) {
            const data = await response.json();
            throw new Error(data.error || 'Date range conflict detected.');
          }

          if (!response.ok) throw new Error('Network response was not OK');

          const data = await response.json();
          if (!data.itinerary || !Array.isArray(data.itinerary)) {
            throw new Error('Invalid API response');
          }

          lastItineraryRequest = {
            days: formData.day,
            budget: formData.budget,
            adults: formData.adults,
            children: formData.children,
            seniors: formData.seniors,
            preference: userPreference,
            trip_title: formData.trip_title,
            start_date: formData.start_date,
            end_date: formData.end_date,
            user_id: currentUserID
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
              const totalHtml = Number(spot.total_cost_for_day) === 0 ? `<li><strong>Total Cost for Day: Free</strong></li>` : `<li>Total Cost for Day: ₱${spot.total_cost_for_day}</li>`;
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

          let message = '';
          if (data.itinerary.length < parseInt($('#autoGenDay').value)) {
            message = `<div class="alert alert-warning">Only ${data.itinerary.length} day(s) could be generated due to budget constraints.</div>`;
          }
          message += `<div class="alert alert-info">Remaining Budget: ₱${data.remaining_budget}</div>`;
          if (data.note) message += `<div class="alert alert-info">${data.note}</div>`;
          previewContent.innerHTML += message;

          generateBtn.style.display = 'none';
          applyBtn.style.display = 'inline-block';
        } catch (err) {
          previewContent.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${err.message}</div>`;
          generateBtn.disabled = false;
        }
      }

      function resetModal() {
        $('#autoGenerateForm').reset();
        previewSection.style.display = 'none';
        generateBtn.style.display = 'inline-block';
        generateBtn.disabled = false;
        applyBtn.style.display = 'none';
      }

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
          applyBtn.disabled = true;

          const params = new URLSearchParams({
            days: lastItineraryRequest.days,
            budget: lastItineraryRequest.budget,
            adults: lastItineraryRequest.adults,
            children: lastItineraryRequest.children,
            seniors: lastItineraryRequest.seniors,
            preference: lastItineraryRequest.preference,
            user_id: lastItineraryRequest.user_id || '',
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
              bootstrap.Modal.getInstance(modalEl).hide();
              setTimeout(async () => {
                if (typeof window.__renderTripItinerary === 'function') {
                  // attempt to load saved trip using server itinerary/get
                  try {
                    const res = await fetch(`/itinerary/get?trip_title=${encodeURIComponent(lastItineraryRequest.trip_title)}&start_date=${encodeURIComponent(lastItineraryRequest.start_date)}`);
                    if (res.ok) {
                      const data = await res.json();
                      window.__renderTripItinerary(data);
                    }
                  } catch (err) {
                    // silent failure; user already alerted
                    console.warn('Failed to auto-load saved trip', err);
                  }
                }
              }, 500);
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

      // --------------------------
      // Renderer + history modal helpers
      // --------------------------
      // Central renderer used by both loadSavedTrip and history modal "View"
      function renderTripItinerary(data) {
        const tripInfo = data.trip_info || data.trip || {};
        const itinerary = data.itinerary || data.itin || [];

        // Update trip title
        if (tripInfo.trip_title) {
          const titleEl = document.getElementById('tripTitle');
          if (titleEl) titleEl.textContent = tripInfo.trip_title;
        }

        // Build trip info summary
        try {
          const start = tripInfo.start_date ? new Date(tripInfo.start_date) : null;
          const end = tripInfo.end_date ? new Date(tripInfo.end_date) : null;
          const days = (start && end) ? (Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1) : (tripInfo.days || itinerary.length);

          const infoHtml = [];
          if (start && end) {
            infoHtml.push(`<div class="trip-info-item"><i class="bi bi-calendar3"></i><span>${start.toLocaleDateString()} - ${end.toLocaleDateString()}</span></div>`);
          } else if (tripInfo.date_range) {
            infoHtml.push(`<div class="trip-info-item"><i class="bi bi-calendar3"></i><span>${tripInfo.date_range}</span></div>`);
          }
          infoHtml.push(`<div class="trip-info-item"><i class="bi bi-clock"></i><span>${days} Days</span></div>`);

          const people = [];
          if (tripInfo.adults !== undefined) people.push(`Adults: ${tripInfo.adults}`);
          if (tripInfo.children !== undefined) people.push(`Children: ${tripInfo.children}`);
          if (tripInfo.seniors !== undefined) people.push(`Seniors: ${tripInfo.seniors}`);
          if (people.length) infoHtml.push(`<div class="trip-info-item"><i class="bi bi-people"></i><span>${people.join(', ')}</span></div>`);

          if (tripInfo.budget !== undefined) infoHtml.push(`<div class="trip-info-item"><i class="bi bi-wallet"></i><span>Budget: ₱${tripInfo.budget}</span></div>`);

          const tripInfoContainer = document.getElementById('tripInfo');
          if (tripInfoContainer) tripInfoContainer.innerHTML = infoHtml.join('');
        } catch (err) {
          console.warn('Could not render tripInfo block', err);
        }

        // Build timeline
        const timelineSection = document.querySelector('.timeline-section');
        if (!timelineSection) return;

        let timelineHTML = `
          <div class="timeline-header">
            <h3 class="timeline-title">Trip Timeline</h3>
            <button class="btn-add-day" onclick="openAddDayModal()"><i class="bi bi-plus-circle"></i> Add Day</button>
          </div>
        `;

        itinerary.forEach((dayData) => {
          const dayIndex = dayData.day ?? dayData.day_number ?? (dayData.index ?? '');
          const spots = dayData.spots || dayData.places || dayData.activities || [];

          const totalCost = spots.reduce((sum, spot) => {
              const adultCost = (tripInfo.adults || 0) * (Number(spot.price_per_person) || Number(spot.adult_price) || 0);
              const childCost = (tripInfo.children || 0) * (Number(spot.child_price) || 0);
              const seniorCost = (tripInfo.seniors || 0) * (Number(spot.senior_price) || 0);
              const fallbackCost = Number(spot.cost) || Number(spot.price) || 0;
              return sum + adultCost + childCost + seniorCost + (adultCost + childCost + seniorCost ? 0 : fallbackCost);
          }, 0);
        


          timelineHTML += `
            <div class="day-card" id="day${dayIndex}">
              <div class="day-header" onclick="toggleDay('day${dayIndex}')">
                <div class="day-header-left">
                  <div class="day-number">Day ${dayIndex}</div>
                </div>
                <div class="day-header-right">
                  <div class="day-stats">
                    <div class="day-stat"><i class="bi bi-geo-alt"></i><span>${spots.length} places</span></div>
                    <div class="day-stat"><i class="bi bi-cash-stack"></i><span>₱${totalCost}</span></div>
                  </div>
                  <i class="bi bi-chevron-down collapse-icon"></i>
                </div>
              </div>
              <div class="day-content">
                ${spots.map((spot) => {
                  const name = spot.name || spot.title || spot.place_name || 'Untitled';
                  const location = spot.location || spot.address || '';
                  const category = spot.category || spot.type || '';
                  const priceAdult = spot.price_per_person ?? spot.adult_price ?? spot.price ?? 0;
                  const isFree = Number(priceAdult) === 0 && Number(spot.child_price || 0) === 0 && Number(spot.senior_price || 0) === 0;
                  const priceHtml = isFree
                  
                
                    ? `<li class="text-success"><strong>This spot is free — no admission fee</strong></li>`
                    : `
                      <li>Price (Adult): ₱${priceAdult}</li>
                      <li>Price (Child): ₱${spot.child_price ?? 0}</li>
                      <li>Price (Senior): ₱${spot.senior_price ?? 0}</li>
                    `;
                  return `
                    <div class="activity-item" data-type="${spot.type || 'place'}">
                      <i class="bi bi-grip-vertical activity-drag-handle"></i>
                      <div class="activity-icon place"><i class="bi bi-geo-alt"></i></div>
                      <div class="activity-details">
                        <div class="activity-header">
                          <h4 class="activity-title">${name}</h4>
                          <div class="activity-time"><i class="bi bi-clock"></i> ${spot.start_time ?? ''}${spot.end_time ? ' - ' + spot.end_time : ''}</div>
                        </div>
                        <div class="activity-meta">
                          <div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>${location}</span></div>
                          <div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱${priceAdult}</span></div>
                        </div>
                        ${spot.description ? `<div class="activity-notes">${spot.description}</div>` : ''}
                        ${spot.notes ? `<div class="activity-notes">📝 ${spot.notes}</div>` : ''}
                        <div class="activity-notes">
                          <ul class="list-unstyled mb-0">
                            <li><strong>${category}</strong></li>
                            ${priceHtml}
                          </ul>
                        </div>
                      </div>
                      <div class="activity-actions">
                        <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                      </div>
                    </div>
                  `;
                }).join('')}
                <button class="add-activity-btn">
                  <i class="bi bi-plus-circle"></i> Add Activity
                </button>
              </div>
            </div>
           
             <button id="bookItineraryBtn" class="btn btn-primary w-100">Book Itinerary</button>
            
          `;
        });

        timelineSection.innerHTML = timelineHTML;

        // Update budget card
        let totalEstimatedCost = 0;
        itinerary.forEach((dayData) => {
          const spots = dayData.spots || dayData.places || [];
          spots.forEach((spot) => {
            const adultCost = (tripInfo.adults || 0) * (Number(spot.price_per_person) || Number(spot.adult_price) || 0);
            const childCost = (tripInfo.children || 0) * (Number(spot.child_price) || 0);
            const seniorCost = (tripInfo.seniors || 0) * (Number(spot.senior_price) || 0);
            const fallback = Number(spot.cost) || Number(spot.price) || 0;
            totalEstimatedCost += adultCost + childCost + seniorCost + (adultCost + childCost + seniorCost ? 0 : fallback);
          });
        });
        const walletCard = Array.from(document.querySelectorAll('.summary-card')).find(c => c.querySelector('.bi-wallet2') || c.querySelector('.bi-wallet'));
        if (walletCard) {
          const target = walletCard.querySelector('.budget-amount') || walletCard.querySelector('.budget-total .budget-amount');
          if (target) target.textContent = `₱${totalEstimatedCost.toLocaleString()}`;
        }
      } // end renderTripItinerary

      // Expose renderer globally
      window.__renderTripItinerary = renderTripItinerary;

      // Helper to attach handlers to History modal (call after modal is created and shown)
      function attachHistoryModalHandlers(modalEl, bsModal) {
        const dec = v => v ? decodeURIComponent(v) : v;

        modalEl.querySelectorAll('.btn-view-trip').forEach(btn => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const tripTitle = dec(btn.dataset.tripTitle);
            const startDate = dec(btn.dataset.startDate);

            // Close history modal for better UX
            bsModal.hide();

            // Prefer using the shared loader which already fetches `/itinerary/get`
            try {
              if (typeof window.loadSavedTrip === 'function') {
                await window.loadSavedTrip(tripTitle, startDate);
                return;
              }

              // Fallback: fetch directly and render
              const resp = await fetch(`/itinerary/get?trip_title=${encodeURIComponent(tripTitle)}&start_date=${encodeURIComponent(startDate)}`);
              if (!resp.ok) throw new Error('Failed to fetch trip details');
              const data = await resp.json();
              if (typeof window.__renderTripItinerary === 'function') window.__renderTripItinerary(data);
            } catch (err) {
              console.error('Failed to load trip details from history view', err);
              alert('Failed to load trip details. ' + (err && err.message ? err.message : ''));
            }
          });
        });

        modalEl.querySelectorAll('.btn-delete-trip').forEach(btn => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const tripTitle = decodeURIComponent(btn.dataset.tripTitle);
            if (!confirm(`Delete trip "${tripTitle}"? This action cannot be undone.`)) return;
            try {
              const resp = await fetch(`/itinerary/delete?trip_title=${encodeURIComponent(tripTitle)}`, { method: 'DELETE' });
              if (!resp.ok) throw new Error('Delete failed: ' + resp.status);
              alert(`Deleted trip: ${tripTitle}`);
              bsModal.hide();
            } catch (err) {
              console.error(err);
              alert('Failed to delete: ' + err.message);
            }
          });
        });
      }
      // Expose the attach function
      window.__attachHistoryModalHandlers = attachHistoryModalHandlers;

      // Provide a backward-compatible loadSavedTrip that uses renderer
      window.loadSavedTrip = async function(tripTitle, startDate) {
        try {
          const resp = await fetch(`/itinerary/get?trip_title=${encodeURIComponent(tripTitle)}&start_date=${encodeURIComponent(startDate)}`);
          if (!resp.ok) throw new Error('Failed to load trip');
          const data = await resp.json();
          if (typeof window.__renderTripItinerary === 'function') window.__renderTripItinerary(data);
        } catch (err) {
          console.error('Error loading saved trip:', err);
          alert('Note: Trip saved but could not auto-load details. Error: ' + err.message);
        }
      };
    })();
    </script>

    <!-- Itinerary History modal builder (calls attach helper) -->
    <script>
    (function () {
      const historyBtn = document.getElementById('historyBtn');
      if (!historyBtn) return;

      historyBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
          const response = await fetch('/itinerary/list');
          if (!response.ok) throw new Error('Failed to fetch trips');
          const data = await response.json();

          if (!data.trips || !data.trips.length) {
            alert('No saved trips found.');
            return;
          }

          // Build trips modal HTML
          let tripsHtml = `
            <div class="modal fade" id="tripsHistoryModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-history"></i> Saved Trips</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="trips-list">
          `;

          data.trips.forEach((trip, idx) => {
            const startDate = new Date(trip.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const endDate = new Date(trip.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            tripsHtml += `
              <div class="trip-card mb-3 p-3 border rounded" data-trip-index="${idx}">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h6 class="mb-1"><strong>${trip.trip_title || 'Untitled Trip'}</strong></h6>
                    <small class="text-muted d-block"><i class="bi bi-calendar"></i> ${startDate} to ${endDate}</small>
                    <small class="text-muted d-block"><i class="bi bi-geo-alt"></i> ${trip.spot_count || 0} spots</small>
                    <small class="text-muted d-block"><i class="bi bi-clock"></i> Created: ${new Date(trip.created_at).toLocaleDateString()}</small>
                  </div>
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-view-trip" data-trip-title="${encodeURIComponent(trip.trip_title)}" data-start-date="${encodeURIComponent(trip.start_date)}">
                      <i class="bi bi-eye"></i> View
                    </button>
                    <button class="btn btn-outline-danger btn-delete-trip" data-trip-title="${encodeURIComponent(trip.trip_title)}">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
            `;
          });

          tripsHtml += `
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          `;

          // Append and show modal
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = tripsHtml;
          const modal = tempDiv.querySelector('#tripsHistoryModal');
          document.body.appendChild(modal);

          const bsModal = new bootstrap.Modal(modal);
          bsModal.show();

          // Attach handlers using the helper we exposed earlier
          if (typeof window.__attachHistoryModalHandlers === 'function') {
            window.__attachHistoryModalHandlers(modal, bsModal);
          } else {
            // fallback: basic view behavior if helper missing
            modal.querySelectorAll('.btn-view-trip').forEach(btn => {
              btn.addEventListener('click', (ev) => {
                ev.preventDefault();
                const tripTitle = decodeURIComponent(btn.dataset.tripTitle);
                const startDate = decodeURIComponent(btn.dataset.startDate);
                bsModal.hide();
                // attempt to call global loader
                if (typeof window.loadSavedTrip === 'function') {
                  window.loadSavedTrip(tripTitle, startDate);
                } else {
                  alert(`View trip: ${tripTitle} (${startDate})`);
                }
              });
            });
          }

          // Cleanup when modal hidden
          modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
          });

        } catch (err) {
          console.error('Error loading trips:', err);
          alert('Failed to load saved trips: ' + err.message);
        }
      });
    })();
    </script>

  </body>
</html>