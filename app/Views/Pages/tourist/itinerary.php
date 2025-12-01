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
  <!-- Flatpickr (date range like booking modal) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/touristStyle/itinerary.css')?>">
    <style>
      /* Ensure modals appear above custom overlays (sidebar-overlay etc.) */
      .modal {
        z-index: 20050 !important;
      }
      .modal-backdrop {
        z-index: 20040 !important;
      }
      .modal .modal-footer .btn { pointer-events: auto; }
      /* SweetAlert2 z-index above Bootstrap modals */
      .swal2-container {
        z-index: 20100 !important;
      }
      /* Enhanced Page Header (from Explore) */
      :root { --ocean-accent:#4ecbff; --ocean-accent-soft:#b5ecff; --ocean-text:#e6f8ff; }
      .page-header {background:#002e55;color:var(--ocean-text);height:210px;min-height:210px;padding:1.6rem 2.4rem 1.8rem;border-radius:28px;position:relative;overflow:hidden;box-shadow:0 12px 34px -10px rgba(0,56,108,.55);display:flex;flex-direction:column;justify-content:center;margin-bottom:2rem;}
      .page-header h2 {font-weight:700;display:flex;align-items:center;gap:.85rem;margin:0 0 .55rem;color:#e2e8f0;font-size:2.10rem;letter-spacing:.6px;line-height:1.1;position:relative;top:-6px;z-index:2;}
      .page-header h2 i {background:rgba(255,255,255,.10);padding:.8rem;border-radius:18px;font-size:2.2rem;animation:slow-spin 18s linear infinite;color:var(--ocean-text);position:relative;top:-4px;}
      @keyframes slow-spin {from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
      .page-header p {font-size:1.05rem;letter-spacing:.5px;margin:0;color:var(--ocean-accent-soft);text-shadow:0 1px 2px rgba(0,0,0,.25);z-index:2;position:relative;}
      /* Wave layers */
      .page-header:before {content:"";position:absolute;left:0;right:0;bottom:0;height:110px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,80 C150,120 300,40 450,70 C600,100 750,50 900,80 C1050,110 1200,60 1200,60 L1200,120 L0,120 Z" fill="%2300487a"/></svg>') repeat-x;background-size:1200px 110px;opacity:.55;filter:drop-shadow(0 4px 8px rgba(0,0,0,.3));z-index:1;}
      .page-header:after {content:"";position:absolute;left:0;right:0;bottom:0;height:90px;pointer-events:none;display:block;background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,60 C200,100 400,20 600,60 C800,100 1000,30 1200,70 L1200,120 L0,120 Z" fill="%23005fae"/></svg>') repeat-x;background-size:1200px 90px;opacity:.35;z-index:1;}
      .page-header-actions {position:absolute;top:1.1rem;right:1.3rem;display:flex;align-items:center;gap:1rem;z-index:5;}
      .page-header-actions .user-avatar {background:linear-gradient(135deg,#004b8d,#001d33);color:#e2e8f0;font-weight:600;width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 12px -3px rgba(0,0,0,.5);transition:.25s;border:2px solid rgba(255,255,255,.18);}
      .page-header-actions .user-avatar:hover {transform:translateY(-2px);background:linear-gradient(135deg,#005fae,#002e55);}
      @media (max-width: 768px){
        .page-header {padding:2rem 1.2rem 2.4rem;border-radius:22px;height:auto;min-height:180px;}
        .page-header h2 {font-size:1.75rem;}
        .page-header p {font-size:.95rem;}
      }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="tourist-sidebar" id="sidebar">
            <div class="tourist-sidebar-header">
                <a href="/tourist/dashboard" class="tourist-sidebar-logo">
                    <i class="bi bi-compass"></i>
                    <div class="tourist-sidebar-logo-text">
                        <span class="tourist-sidebar-logo-main">Tuklas</span>
                        <span class="tourist-sidebar-logo-sub">Nasugbu</span>
                    </div>
                </a>
                <button class="tourist-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <nav class="tourist-sidebar-nav">
                <ul class="tourist-nav-menu">
                    <li class="tourist-nav-item">
                        <a href="/tourist/dashboard" class="tourist-nav-link">
                            <i class="bi bi-house-door"></i>
                            <span class="tourist-nav-link-text">Home</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/exploreSpots" class="tourist-nav-link">
                            <i class="bi bi-search"></i>
                            <span class="tourist-nav-link-text">Explore</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/itinerary" class="tourist-nav-link active">
                            <i class="bi bi-calendar-check"></i>
                            <span class="tourist-nav-link-text">My Itinerary</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/myBookings" class="tourist-nav-link">
                            <i class="bi bi-ticket-perforated"></i>
                            <span class="tourist-nav-link-text">Bookings</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/visits" class="tourist-nav-link">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span class="tourist-nav-link-text">Visited Places</span>
                        </a>
                    </li>
                    <!-- My Reviews link removed (reviews integrated into Visited/Explore) -->
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header (Ocean Theme) -->
            <div class="page-header">
                <div class="page-header-actions">
                <div style="position: relative;">
                  <?php $session = session(); $userFirstName = $session->get('FirstName') ?? ''; $userLastName = $session->get('LastName') ?? ''; $userEmail = $session->get('Email') ?? ''; $userInitials = strtoupper(substr($userFirstName,0,1).substr($userLastName,0,1)); ?>
                  <div style="position:relative;display:flex;align-items:center;gap:1rem;">
                    <button class="notification-btn" onclick="toggleNotificationDropdown()">
                      <i class="bi bi-bell-fill"></i>
                      <span class="notification-badge" id="notifBadge">3</span>
                    </button>
                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
                        </div>
                        <ul class="notification-list">
                            <li class="notification-item unread" onclick="openNotificationDetail(this)" style="cursor:pointer;">
                                <div class="notification-content">
                                    <div class="notification-icon info"><i class="bi bi-calendar-check"></i></div>
                                    <div class="notification-text">
                                        <h6>Itinerary Reminder</h6>
                                        <p>Your Nasugbu Adventure starts tomorrow at 9:00 AM</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i> 5 hours ago</div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item unread" onclick="openNotificationDetail(this)" style="cursor:pointer;">
                                <div class="notification-content">
                                    <div class="notification-icon success"><i class="bi bi-check-circle-fill"></i></div>
                                    <div class="notification-text">
                                        <h6>Itinerary Saved</h6>
                                        <p>Your Beach Hopping Trip has been saved successfully</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i> 2 days ago</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="notification-footer">
                            <a href="#" onclick="viewAllNotifications(event)">View all notifications</a>
                        </div>
                    </div>
                    <div class="user-avatar" onclick="toggleUserDropdown()"><?= esc($userInitials ?: 'JD') ?></div>
                  </div>
                  <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                      <h6><?= esc(($userFirstName ?: 'Juan') . ' ' . ($userLastName ?: 'Dela Cruz')) ?></h6>
                      <p><?= esc($userEmail ?: 'juan.delacruz@email.com') ?></p>
                    </div>
                    <ul class="menu">
                      <li><a href="#" onclick="openProfile(event); hideUserDropdown(event)"><i class="bi bi-person-circle"></i> <span>My Profile</span></a></li>
                      <li><a class="logout" href="/users/logout" onclick="handleLogout(event)"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
                <h2><i class="bi bi-calendar-check"></i> My Itinerary</h2>
                <p>Plan your perfect Nasugbu adventure with personalized trip schedules.</p>
            </div>

            <!-- Itinerary Content -->
            <div class="itinerary-container">
                <!-- Itinerary Header -->
                <div class="itinerary-header">
                    <div class="trip-title-section">
                        <h2 class="trip-title" id="tripTitle">Nasugbu Adventure Trip</h2>
                        <div class="trip-actions">
                          <button class="btn-action btn-create" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                            <i class="bi bi-plus-lg"></i> Create New
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
                              <div class="activities-list" data-day="<?= $day['day_number'] ?>">
                              <?php foreach ($day['activities'] as $activity): ?>
                              <div class="activity-item" data-type="<?= $activity['type'] ?>" data-id="<?= $activity['id'] ?? '' ?>" data-title="<?= esc($activity['title'] ?? '') ?>" data-location="<?= esc($activity['location'] ?? '') ?>" data-lat="<?= $activity['lat'] ?? ($activity['latitude'] ?? '') ?>" data-lng="<?= $activity['lng'] ?? ($activity['longitude'] ?? '') ?>">
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
                                            <div class="activity-time"><?= $activity['start_time'] ?><?= isset($activity['end_time']) ? " - ".$activity['end_time'] : "" ?> <i class="bi bi-clock"></i></div>
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
                                </div>

                                <button class="add-activity-btn" data-day="<?= $day['day_number'] ?>">
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
                        <div class="summary-card ocean-card map-card">
                          <div class="summary-card-header d-flex align-items-center justify-content-between">
                            <h3 class="summary-card-title mb-0"><i class="bi bi-map"></i> Map View</h3>
                            <button id="itineraryMapExpandBtn" type="button" class="map-expand-header-btn" title="Expand map" style="margin-left:8px;">
                              <span class="map-btn-inner"><i class="bi bi-arrows-fullscreen" style="font-size:16px;color:#fff"></i></span>
                            </button>
                          </div>
                          <div class="map-container" style="margin-top:18px; position:relative;">
                            <div id="itineraryMap" class="map-placeholder" aria-hidden="false">Map placeholder</div>
                          </div>
                        </div>

                        <!-- Budget Card -->
                        <div class="summary-card ocean-card budget-card">
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

                      <!-- All Spots (collapsible) -->
                      <div class="mb-3">
                        <div class="suggested-header d-flex align-items-center justify-content-between">
                          <span>All spots</span>
                          <button class="btn btn-link btn-sm p-0" id="toggleAllSpotsBtn" aria-expanded="false" title="Show all spots">
                            <i class="bi bi-chevron-down" id="allSpotsChevron"></i>
                          </button>
                        </div>
                        <div id="allSpotsContainer" style="display:none;">
                          <div class="input-group mb-2">
                            <input type="text" class="form-control" id="allSpotsSearch" placeholder="Search all spots...">
                            <button class="btn btn-outline-secondary" type="button" id="allSpotsSearchBtn"><i class="bi bi-search"></i></button>
                          </div>
                          <div class="suggested-grid" id="allSpotsGrid"></div>
                        </div>
                      </div>

                      <!-- Customize (hidden; used only for editing) -->
                      <div id="customizeDivider" class="sugg-divider" style="display:none;"><span>edit time</span></div>
                      <form id="activityForm" style="display:none;" aria-hidden="true">
                        <div class="mb-2">
                          <label class="form-label">Time</label>
                          <input type="time" class="form-control" id="activityTime" placeholder="HH:MM">
                        </div>
                      </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="activitySaveBtn" style="display:none;">
                    <i class="bi bi-check2-circle"></i> Save
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

    

    <!-- Create New Itinerary Modal -->
    <div class="modal fade" id="createItineraryModal" tabindex="-1" aria-labelledby="createItineraryModalLabel">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header create-modal-header">
            <h5 class="modal-title" id="createItineraryModalLabel"><i class="bi bi-plus-lg"></i> Create New Itinerary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Left Panel - Trip Details -->
              <div class="col-md-6 border-end trip-details-panel">
                <h6 class="mb-3"><i class="bi bi-clipboard-check"></i> Trip Details</h6>
                <form id="createItineraryForm">
                  <div class="mb-3">
                    <label class="form-label">Trip Title</label>
                    <input type="text" class="form-control" id="newTripTitle" placeholder="e.g., Nasugbu Adventure" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><strong>Date Range</strong></label>
                    <input type="text" class="form-control" id="newTripDateRange" placeholder="Select date range" autocomplete="off" required>
                    <input type="hidden" id="newTripStart">
                    <input type="hidden" id="newTripEnd">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Budget (₱)</label>
                    <input type="number" class="form-control" id="newTripBudget" min="0" step="100" placeholder="e.g., 5000">
                    <small class="form-text text-muted">Estimated total trip budget</small>
                  </div>
                  <div class="mb-3">
                    <label class="form-label d-block"><strong>Guests</strong></label>
                    <div class="guest-list d-flex flex-column gap-2">
                      <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                        <div class="me-3"><strong>Adults</strong> <span id="itPriceAdult" class="text-muted small"></span></div>
                        <div class="guest-spinner" data-input="itAdults">
                          <button type="button" class="btn-guest btn-guest-minus" data-target="itAdults">−</button>
                          <input type="number" class="guest-input" id="itAdults" min="0" value="1" required>
                          <button type="button" class="btn-guest btn-guest-plus" data-target="itAdults">+</button>
                        </div>
                      </div>
                      <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                        <div class="me-3"><strong>Children</strong> <span id="itPriceChild" class="text-muted small"></span></div>
                        <div class="guest-spinner" data-input="itChildren">
                          <button type="button" class="btn-guest btn-guest-minus" data-target="itChildren">−</button>
                          <input type="number" class="guest-input" id="itChildren" min="0" value="0" required>
                          <button type="button" class="btn-guest btn-guest-plus" data-target="itChildren">+</button>
                        </div>
                      </div>
                      <div class="guest-row p-2 border rounded d-flex align-items-center justify-content-between">
                        <div class="me-3"><strong>Seniors</strong> <span id="itPriceSenior" class="text-muted small"></span></div>
                        <div class="guest-spinner" data-input="itSeniors">
                          <button type="button" class="btn-guest btn-guest-minus" data-target="itSeniors">−</button>
                          <input type="number" class="guest-input" id="itSeniors" min="0" value="0" required>
                          <button type="button" class="btn-guest btn-guest-plus" data-target="itSeniors">+</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Selected Spot Details -->
                  <div id="selectedSpotDetails" style="display:none;">
                    <hr>
                    <h6 class="mb-3"><i class="bi bi-info-circle"></i> Selected Spot Details (<span id="selectedCount">0</span>)</h6>
                    <div class="selected-spot-glass">
                      <div id="selectedSpotItems" class="d-flex flex-column gap-3"></div>
                      <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <div class="small fw-semibold text-primary" id="aggregatePricing"></div>
                        <button class="btn btn-outline-danger btn-sm clear-selection-btn" type="button" id="clearSpotBtn"><i class="bi bi-x-circle"></i> Clear All</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Right Panel - Recommended / Generated Preview -->
              <div class="col-md-6 glass-panel">
                <div id="rightPanelRecommended">
                  <h6 class="mb-3"><i class="bi bi-star-fill text-warning"></i> Recommended Tourist Spots</h6>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" id="spotSearchInput" placeholder="Search tourist spots...">
                    <button class="btn btn-outline-secondary" type="button" id="searchSpotBtn">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                  <div class="recommended-spots-grid" id="recommendedSpotsGrid" style="max-height: 450px; overflow-y: auto;">
                    <!-- Spots will be loaded here -->
                    <div class="text-center text-muted py-3">
                      <i class="bi bi-compass"></i>
                      <p class="mb-0 small">Search or browse recommended spots</p>
                    </div>
                  </div>
                </div>
                <div id="rightPanelGenerated" style="display:none;">
                  <h6 class="mb-3" style="display:flex;align-items:center;gap:.5rem;font-weight:700;color:#003a6e;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;padding:.45rem .55rem;border-radius:14px;box-shadow:0 4px 12px -4px rgba(0,46,85,.45);font-size:.95rem;"><i class="bi bi-eye"></i></span>
                    Generated Itinerary Preview
                  </h6>
                  <div id="generatedPreview" class="selected-spot-glass mt-2" style="display:block;">
                    <div id="generatedContent"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn-add-day" id="createAutoGenerateBtn" type="button"><i class="bi bi-lightning-charge"></i> Auto generate</button>
            <button class="btn btn-gradient-primary" id="createItineraryBtn">Create / Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add New Day Modal -->
    <div class="modal fade" id="addDayModal" tabindex="-1" aria-labelledby="addDayModalLabel">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header create-modal-header">
            <h5 class="modal-title" id="addDayModalLabel"><i class="bi bi-plus-circle"></i> Add New Day</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="addDayForm">
              <div class="mb-3">
                <label class="form-label">Number of Days to Add</label>
                <input type="number" class="form-control" id="newDayNumber" placeholder="e.g., 3" min="1" required>
                <small class="form-text text-muted">Adds this many consecutive days after the last existing day.</small>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-gradient-primary" id="addDayBtn" data-bs-dismiss="modal"><i class="bi bi-check-circle"></i> Add Day</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Itinerary History Modal -->
    <div class="modal fade" id="tripsHistoryModal" tabindex="-1" aria-labelledby="historyModalLabel">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header create-modal-header">
            <h5 class="modal-title" id="historyModalLabel" style="display:flex;align-items:center;gap:.55rem;font-weight:700;letter-spacing:.4px;">
              <span style="display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;padding:.55rem .65rem;border-radius:14px;box-shadow:0 6px 16px -6px rgba(0,46,85,.45);"><i class="bi bi-clock-history"></i></span>
              Itinerary History
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background:linear-gradient(140deg,#f5f9fc,#eef5fa);border-radius:0 0 18px 18px;">
            <div class="glass-panel" style="padding:18px;">
              <div id="historyContent" class="trips-list" style="display:flex;flex-direction:column;gap:14px;">
                <!-- History items populated by JavaScript -->
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toasts (UI only) -->
    <div class="toast-container" id="toastContainer"></div>

    <style>
      .recommended-spots-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
      }
      .spot-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
      }
      .spot-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        transform: translateY(-2px);
      }
      .spot-card.selected {
        border-color: #0d6efd;
        background-color: #e7f1ffff;
      }
      .spot-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 8px;
      }
      .spot-card-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
        color: #212529;
      }
      .spot-card-category {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: #e9ecef;
        color: #495057;
      }
      .spot-card-body {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 8px;
      }
      .spot-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #6c757d;
      }
      .spot-price {
        font-weight: 600;
        color: #198754;
      }
      /* Larger Trip Details heading & icon */
      #createItineraryModal h6.mb-3 {font-size:1.35rem;display:flex;align-items:center;gap:.75rem;font-weight:700;letter-spacing:.5px;color:#003a6e;}
      #createItineraryModal h6.mb-3 i {font-size:1.6rem;background:rgba(0,75,141,0.15);padding:.55rem .65rem;border-radius:14px;color:#004b8d;box-shadow:0 4px 10px -4px rgba(0,46,85,.35);}
      /* Selected spot glass design */
      .selected-spot-glass {background:rgba(255,255,255,0.55);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(0,75,141,0.25);border-radius:20px;padding:18px 20px;position:relative;overflow:hidden;box-shadow:0 10px 30px -10px rgba(0,46,85,.35);}
      .selected-spot-glass:before {content:"";position:absolute;inset:0;border-radius:20px;background:linear-gradient(145deg,rgba(0,75,141,0.10),rgba(0,46,85,0.05));pointer-events:none;}
      .spot-header-line {display:flex;align-items:center;gap:14px;margin-bottom:12px;}
      .spot-icon-circle {width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#004b8d,#002e55);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;box-shadow:0 6px 18px -6px rgba(0,46,85,.55);}
      .spot-detail-title {margin:0;font-weight:700;font-size:1.15rem;color:#003a6e;letter-spacing:.4px;}
      .spot-chips {display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;}
      .spot-chip {background:linear-gradient(135deg,#0072c6,#004b8d);color:#fff;font-size:.65rem;font-weight:600;padding:6px 12px;border-radius:40px;letter-spacing:.5px;box-shadow:0 4px 12px -4px rgba(0,46,85,.45);}
      .spot-desc-box {background:rgba(255,255,255,0.6);border:1px solid rgba(0,75,141,0.15);border-radius:16px;padding:14px 16px;box-shadow:0 6px 20px -8px rgba(0,46,85,.25);margin-bottom:10px;}
      .spot-desc-box p {font-size:.9rem;line-height:1.4;color:#0b2236;font-weight:500;margin:0;}
      .spot-meta-lines {display:flex;flex-wrap:wrap;gap:12px;margin-top:4px;}
      .spot-meta-item {display:inline-flex;align-items:center;gap:6px;font-size:.75rem;font-weight:600;color:#004b8d;background:rgba(0,75,141,0.08);padding:6px 10px;border-radius:12px;}
      .spot-pricing-tags {display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
      .price-pill {background:linear-gradient(135deg,#ffffff,#f0f6fa);border:1px solid #d5e4ee;color:#003a6e;font-size:.65rem;font-weight:600;padding:6px 10px;border-radius:40px;box-shadow:0 2px 6px -2px rgba(0,46,85,.25);display:inline-flex;align-items:center;gap:4px;}
      .price-pill:before {content:"";width:6px;height:6px;border-radius:50%;background:#004b8d;display:inline-block;box-shadow:0 0 0 2px rgba(0,75,141,.18);}
      .clear-selection-btn {border-radius:40px;}
      @media (max-width:768px){
        .spot-icon-circle {width:50px;height:50px;font-size:1.3rem;}
        .spot-detail-title {font-size:1.05rem;}
        .selected-spot-glass {padding:16px;}
      }
      /* Solid white background for Trip Details (like booking form) */
      .trip-details-panel {background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:18px 20px;box-shadow:0 6px 22px -8px rgba(0,0,0,.08);}
      /* Guest spinner styles (mirroring booking modal) */
      .guest-spinner { display:inline-flex; align-items:center; gap:6px; }
      .guest-input { width:50px; text-align:center; border:1px solid #ced4da; border-radius:6px; padding:4px 6px; }
      .guest-input::-webkit-outer-spin-button, .guest-input::-webkit-inner-spin-button { -webkit-appearance:none; margin:0; }
      .guest-input { -moz-appearance:textfield; appearance:textfield; }
      .btn-guest { width:36px; height:36px; border-radius:50%; border:1px solid #0d4d7d; background:linear-gradient(135deg,#004b8d,#002e55); color:#fff; font-weight:600; display:flex; align-items:center; justify-content:center; padding:0; cursor:pointer; transition:background .2s, transform .15s; }
      .btn-guest:hover { background:linear-gradient(135deg,#005fae,#003a6e); }
      .btn-guest:active { transform:scale(.92); }
      .guest-row { background:#fff; }
      .guest-row:hover { background:#f5f9fc; }
      /* Flatpickr override for glass look */
      .flatpickr-calendar { border-radius:14px; box-shadow:0 12px 34px -10px rgba(0,46,85,.35); }
      .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background:#004b8d; border-color:#004b8d; }
      /* Deep ocean gradient & glass styles to match booking modal */
      .create-modal-header {background:linear-gradient(135deg,#004b8d 0%,#002e55 50%,#000814 100%);color:#fff;border:none;}
      .create-modal-header .btn-close {filter:invert(1);}
      .btn-create {background:linear-gradient(135deg,#004b8d,#002e55);color:#fff !important;border:1px solid rgba(255,255,255,0.35);border-radius:14px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);box-shadow:0 6px 18px -6px rgba(0,46,85,.45);font-weight:600;letter-spacing:.4px;transition:.35s;}
      .btn-create:hover {transform:translateY(-3px);box-shadow:0 10px 28px -8px rgba(0,46,85,.55);} .btn-create:active {transform:translateY(0);box-shadow:none;}
      #createItineraryModal .glass-panel {background:rgba(255,255,255,0.55);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(0,75,141,0.18);border-radius:18px;padding:18px 20px;box-shadow:0 8px 26px -8px rgba(0,46,85,0.35);position:relative;overflow:hidden;}
      #createItineraryModal .glass-panel:before {content:"";position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,0.10),rgba(0,46,85,0.06));pointer-events:none;}
      .btn-gradient-primary {background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;border:none;border-radius:14px;font-weight:600;box-shadow:0 6px 18px -6px rgba(0,46,85,.45);transition:.25s;}
      .btn-gradient-primary:hover {transform:translateY(-3px);box-shadow:0 10px 26px -8px rgba(0,46,85,.55);}
      .btn-gradient-primary:active {transform:translateY(0);box-shadow:none;}
      .btn-gradient-success {background:linear-gradient(135deg,#1b8d2f,#0e5620);color:#fff;border:none;border-radius:14px;font-weight:600;box-shadow:0 6px 18px -6px rgba(27,141,47,.45);transition:.25s;}
      .btn-gradient-success:hover {transform:translateY(-3px);box-shadow:0 10px 26px -8px rgba(27,141,47,.55);}
      .btn-gradient-success:active {transform:translateY(0);box-shadow:none;}
      #selectedSpotDetails .card {background:rgba(255,255,255,0.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(0,75,141,0.15);box-shadow:0 6px 20px -8px rgba(0,46,85,.3);border-radius:16px;}
      #selectedSpotDetails h6 {font-weight:700;color:#003a6e;}
      
        /* View Details Button Style (from Explore) for Auto-generate & Add Day buttons */
        #autoGenBtn, .btn-add-day {
          position: relative;
          padding: 0.65rem 0.9rem;
          border-radius: 14px;
          cursor: pointer;
          font-weight: 600;
          letter-spacing: .4px;
          backdrop-filter: blur(14px);
          -webkit-backdrop-filter: blur(14px);
          overflow: hidden;
          transition: all .35s ease;
          background: rgba(255,255,255,0.55);
          color: #003a6e;
          border: 1px solid rgba(0,75,141,0.35);
        }
        #autoGenBtn:before, .btn-add-day:before {
          content: "";
          position: absolute;
          inset: 0;
          border-radius: 14px;
          background: linear-gradient(145deg,rgba(0,75,141,0.15),rgba(0,46,85,0.10));
          opacity: 0;
          transition: opacity .35s ease;
        }
        #autoGenBtn:hover:before, .btn-add-day:hover:before {
          opacity: 1;
        }
        #autoGenBtn:hover, .btn-add-day:hover {
          transform: translateY(-3px);
          box-shadow: 0 8px 22px -6px rgba(0,46,85,.35);
          border-color: rgba(0,75,141,0.55);
        }
        #autoGenBtn:active, .btn-add-day:active {
          transform: translateY(0);
          box-shadow: none;
        }
        /* Ocean themed summary cards */
        .summary-sidebar {display:flex;flex-direction:column;gap:1.2rem;}
        .summary-card.ocean-card {position:relative;background:rgba(255,255,255,0.55);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(0,75,141,0.20);border-radius:20px;padding:18px 20px;overflow:hidden;box-shadow:0 10px 30px -10px rgba(0,46,85,.30);}
        .summary-card.ocean-card:before {content:"";position:absolute;inset:0;border-radius:20px;background:linear-gradient(145deg,rgba(0,75,141,0.10),rgba(0,46,85,0.06));pointer-events:none;}
        
        /* Ocean themed itinerary container */
        .itinerary-container {position:relative;background:rgba(255,255,255,0.65);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1px solid rgba(0,75,141,0.18);border-radius:22px;padding:2rem;overflow:hidden;box-shadow:0 12px 36px -12px rgba(0,46,85,.32);}
        .itinerary-container:before {content:"";position:absolute;inset:0;border-radius:22px;background:linear-gradient(145deg,rgba(0,75,141,0.08),rgba(0,46,85,0.05));pointer-events:none;z-index:0;}
        .itinerary-header {position:relative;z-index:1;border-bottom:1px solid rgba(0,75,141,0.15);padding-bottom:1.5rem;margin-bottom:1.5rem;}
        .trip-title {color:#003a6e;font-weight:700;letter-spacing:.5px;position:relative;z-index:1;}
        .trip-actions {position:relative;z-index:1;}
        .trip-title-section {position:relative;z-index:1;}
        .trip-info {position:relative;z-index:1;}
        .trip-info-item {color:#0d4d7d;}
        .trip-info-item i {color:#004b8d;}
        .itinerary-layout {position:relative;z-index:1;}
        
        /* Ocean themed timeline section */
        .timeline-section {position:relative;background:rgba(255,255,255,0.55);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(0,75,141,0.20);border-radius:20px;padding:20px 22px;overflow:hidden;box-shadow:0 10px 30px -10px rgba(0,46,85,.30);}
        .timeline-section:before {content:"";position:absolute;inset:0;border-radius:20px;background:linear-gradient(145deg,rgba(0,75,141,0.10),rgba(0,46,85,0.06));pointer-events:none;z-index:0;}
        .timeline-header {position:relative;z-index:1;}
        .timeline-title {color:#003a6e;font-weight:700;letter-spacing:.4px;}
        
        /* Ocean themed day cards */
        .day-card {position:relative;background:rgba(255,255,255,0.65);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(0,75,141,0.18);border-radius:18px;margin-bottom:1rem;overflow:hidden;box-shadow:0 6px 22px -8px rgba(0,46,85,.28);transition:.3s;z-index:1;}
        .day-card:before {content:"";position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,0.08),rgba(0,46,85,0.04));pointer-events:none;z-index:0;}
        .day-header {position:relative;z-index:1;background:linear-gradient(90deg,rgba(0,75,141,0.05),transparent);border-bottom:1px solid rgba(0,75,141,0.15);}
        .day-header:hover {background:linear-gradient(90deg,rgba(0,75,141,0.10),transparent);}
        .day-number {color:#003a6e;font-weight:700;}
        .day-date {color:#004b8d;}
        .day-stat {color:#0d4d7d;}
        .day-stat i {color:#004b8d;}
        .day-content {position:relative;z-index:1;}
        
        /* Ocean themed activity items */
        .activity-item {position:relative;background:rgba(255,255,255,0.75);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(0,75,141,0.12);border-radius:14px;padding:1rem;overflow:hidden;box-shadow:0 4px 16px -6px rgba(0,46,85,.22);transition:.3s;}
        .activity-item:before {content:"";position:absolute;inset:0;border-radius:14px;background:linear-gradient(145deg,rgba(0,75,141,0.06),rgba(0,46,85,0.02));pointer-events:none;}
        .activity-item:hover {transform:translateY(-2px);box-shadow:0 8px 24px -8px rgba(0,46,85,.35);border-color:rgba(0,75,141,0.25);}
        .activity-title {color:#003a6e;position:relative;z-index:1;}
        .activity-header {display:flex;align-items:center;justify-content:space-between;gap:.5rem;}
        .activity-time {display:inline-flex;align-items:center;gap:.35rem;color:#004b8d;font-weight:600;}
        .activity-details {position:relative;z-index:1;}
        .activity-icon.place {background:linear-gradient(135deg,#004b8d,#002e55);}
        .activity-icon.lodging {background:linear-gradient(135deg,#1b8d2f,#0e5620);}
        .activity-icon.food {background:linear-gradient(135deg,#d4a574,#c49563);}
        .activity-icon.transport {background:linear-gradient(135deg,#1abc9c,#16a085);}
        .add-activity-btn {position:relative;z-index:1;background:rgba(255,255,255,0.6);border:2px dashed rgba(0,75,141,0.35);color:#004b8d;border-radius:14px;}
        .add-activity-btn:hover {background:rgba(0,75,141,0.08);border-color:#004b8d;color:#003a6e;}
        .summary-card-title {display:flex;align-items:center;gap:.55rem;font-size:1.05rem;font-weight:700;color:#003a6e;letter-spacing:.4px;margin:0 0 .75rem;}
        .summary-card-title i {background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;padding:.55rem .6rem;border-radius:14px;font-size:1.15rem;box-shadow:0 6px 16px -6px rgba(0,46,85,.45);}
        .map-placeholder {display:flex;align-items:center;justify-content:center;height:160px;border:2px dashed rgba(0,75,141,0.30);border-radius:16px;color:#0d4d7d;font-weight:600;font-size:.9rem;background:linear-gradient(135deg,#e9f4fb,#d9ecf7);position:relative;overflow:hidden;}
        .map-placeholder:after {content:"";position:absolute;inset:0;background:radial-gradient(circle at 30% 25%,rgba(0,75,141,0.18),transparent 60%);mix-blend-mode:overlay;opacity:.6;}
        /* Numbered markers for itinerary map */
        .numbered-marker .marker-number{background:#007bff;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.25);}
        .map-route-summary{font-size:0.9rem;color:#034;display:flex;flex-direction:column;gap:4px;margin-top:6px}
        .budget-total {background:rgba(255,255,255,0.6);border:1px solid rgba(0,75,141,0.15);border-radius:16px;padding:14px 16px;display:flex;flex-direction:column;gap:6px;}
        .budget-label {font-size:.75rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#004b8d;opacity:.85;}
        .budget-amount {font-size:1.4rem;font-weight:700;color:#003a6e;letter-spacing:.5px;background:linear-gradient(90deg,#004b8d,#0072c6);-webkit-background-clip:text;background-clip:text;color:transparent;filter:drop-shadow(0 4px 10px rgba(0,46,85,.25));}
        @media (max-width:768px){
          .map-placeholder {height:140px;}
          .budget-amount {font-size:1.25rem;}
        }
    </style>

    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Ocean theme globals for SweetAlert buttons (incl. .ocean-btn-cancel) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      /* Tidy Edit Time popup actions: align right, add inner padding */
      .swal2-popup.ocean-alert .swal2-actions,
      .ocean-alert-actions { display:flex; width:100%; justify-content:flex-end; gap:12px; padding:0 1.5rem; box-sizing:border-box; }
    </style>

    <!-- Bootstrap JS only (no app logic) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/tourist-ui.js') ?>"></script>
    <!-- jQuery is available but the main rendering uses vanilla JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- SortableJS for drag-and-drop and Leaflet for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <style>
      /* Visually indicate a suggested card that is disabled for the currently selected day */
      .suggested-card.disabled-for-day {
        opacity: 0.5;
        filter: grayscale(60%);
        pointer-events: auto; /* keep pointer events for selection, but add button is disabled */
      }
      .suggested-card .sugg-add[disabled] {
        cursor: not-allowed;
        opacity: 0.9;
      }
      /* Suggested/All Spots card with photo */
      .suggested-card {position:relative;display:flex;gap:12px;align-items:center;background:rgba(255,255,255,0.7);border:1px solid rgba(0,75,141,0.18);border-radius:14px;padding:10px 60px 10px 12px;margin-bottom:10px;box-shadow:0 6px 18px -8px rgba(0,46,85,.28);transition:.2s;}
      .suggested-card:hover {transform:translateY(-2px);box-shadow:0 10px 24px -10px rgba(0,46,85,.35);}
      .suggested-card.selected {outline:2px solid #004b8d1f;background:rgba(0,75,141,0.06);} 
      .suggested-card .sugg-photo {flex:0 0 120px;height:86px;border-radius:10px;overflow:hidden;background:linear-gradient(135deg,#e9f4fb,#d9ecf7);display:flex;align-items:center;justify-content:center;border:1px solid #d7e6f2;}
      .suggested-card .sugg-img {width:100%;height:100%;object-fit:cover;display:block;}
      .suggested-card .sugg-info {flex:1 1 auto;min-width:0;}
      .suggested-card .sugg-title {font-weight:700;color:#003a6e;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
      .suggested-card .sugg-meta {font-size:.8rem;color:#0d4d7d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
      .suggested-card .sugg-add {position:absolute;right:12px;top:50%;transform:translateY(-50%);border-radius:10px;border:1px solid #0d4d7d;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;width:36px;height:36px;display:flex;align-items:center;justify-content:center}
    </style>

    <!-- Minimal UI-only dropdown script -->
    <script>
      (function () {
        const userBtn = document.querySelector('.user-avatar');
        const userDrop = document.getElementById('userDropdown');

        // Expose the handlers your HTML calls
        window.toggleUserDropdown = function () {
          if (!userDrop) return;
          userDrop.classList.toggle('show');
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
        window.openNotificationModal = function(){
          userDrop?.classList.remove('show');
          const modal = document.getElementById('notificationDetailModal');
          if(!modal) return;
          bootstrap.Modal.getOrCreateInstance(modal).show();
        };
        window.toggleNotificationDropdown = function(){
          const dd = document.getElementById('notificationDropdown');
          userDrop?.classList.remove('show');
          dd?.classList.toggle('show');
        };
        window.openNotificationDetail = function(item){
          const title = item.querySelector('.notification-text h6')?.textContent || 'Notification';
          const message = item.querySelector('.notification-text p')?.textContent || '';
          const time = item.querySelector('.notification-time')?.textContent || '';
          item.classList.remove('unread');
          document.getElementById('notificationDropdown')?.classList.remove('show');
          const modal = document.getElementById('notificationDetailModal');
          if(modal){
            document.getElementById('notifDetailTitle').textContent = title;
            document.getElementById('notifDetailMessage').textContent = message;
            document.getElementById('notifDetailTime').textContent = time;
            bootstrap.Modal.getOrCreateInstance(modal).show();
          }
        };
        window.hideUserDropdown = function(e){
          e?.preventDefault();
          userDrop?.classList.remove('show');
        };
        window.openProfile = function(e){
          e?.preventDefault();
          window.hideUserDropdown(e);
          const modal = document.getElementById('profileModal');
          if(modal) bootstrap.Modal.getOrCreateInstance(modal).show();
        };
        window.markAllAsRead = function() {
          const items = document.querySelectorAll('.notification-item.unread');
          items.forEach(item => item.classList.remove('unread'));
          const badge = document.querySelector('.notification-badge');
          if (badge) badge.textContent = '0';
        };
        window.viewAllNotifications = function(event) {
          event.preventDefault();
          // Navigate to notifications page
        };
        window.handleLogout = function(event) {
          event.preventDefault();
          if (confirm('Are you sure you want to logout?')) {
            window.location.href = '/users/logout';
          }
        };

        // Close when clicking outside
        document.addEventListener('click', (e) => {
          const notifDropdown = document.getElementById('notificationDropdown');
          const notifBtn = document.querySelector('.notification-btn');
          if (userDrop && !userDrop.contains(e.target) && !userBtn.contains(e.target)) userDrop.classList.remove('show');
          if (notifDropdown && notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) notifDropdown.classList.remove('show');
        });
        // Close on Escape
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape') { userDrop?.classList.remove('show'); }
        });
      })();
    </script>

    <script>
      // Map fallback + resize handling
      (function(){
        document.addEventListener('DOMContentLoaded', function(){
          const mapEl = document.getElementById('itineraryMap');
          if (!mapEl) return;

          // If Leaflet didn't load, show a graceful message
          if (typeof L === 'undefined') {
            mapEl.classList.add('map-placeholder');
            mapEl.innerHTML = '<div class="text-muted">Map not available (Leaflet not loaded)</div>';
            return;
          }

          // Ensure map resizes correctly when window changes
          try {
            window.addEventListener('resize', () => {
              try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(e){}
            });
          } catch (e) {
            console.warn('Could not attach resize handler for itineraryMap', e);
          }
        });
      })();
    </script>

    <script>
      // Helpers for add/edit activity modal interactions
      function openAddActivityModalForDay(day) {
        const modal = document.getElementById('addActivityModal');
        if (!modal) return;
        // reset form (kept hidden by default for add flow)
        const form = document.getElementById('activityForm');
        if (form) { form.reset(); form.style.display = 'none'; }
        const divider = document.getElementById('customizeDivider');
        if (divider) divider.style.display = 'none';
        const saveBtn = document.getElementById('activitySaveBtn');
        if (saveBtn) saveBtn.style.display = 'none';
        // clear any previously selected suggested cards and search
        const grid = document.getElementById('suggestedGrid');
        if (grid) {
          grid.querySelectorAll('.suggested-card.selected').forEach(c=>c.classList.remove('selected'));
        }
        // no recommended search field anymore
        // store current day for later insertion
        if (form) form.dataset.targetDay = day;
        modal.dataset.targetDay = day;
        // also keep a global quick-reference for immediate '+' adds
        window.lastAddActivityTargetDay = day;
        // Update suggested cards UI: enable/disable + buttons based on whether the card was already added for this day
        try {
          const dayStr = String(day);
          if (grid) {
            Array.from(grid.querySelectorAll('.suggested-card')).forEach(card => {
              const addBtn = card.querySelector('.sugg-add');
              const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
              const isAdded = addedDays.includes(dayStr);
              if (addBtn) {
                addBtn.disabled = isAdded;
                addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                card.classList.toggle('disabled', isAdded);
                card.classList.toggle('disabled-for-day', isAdded);
              }
            });
          }
        } catch(e) { console.warn('Could not update suggested card states for target day', e); }
        // Do not preload All Spots; it will load when user expands the section
        bootstrap.Modal.getOrCreateInstance(modal).show();
      }

      function openEditActivityModal(itemEl) {
        // Use SweetAlert2 time input popup instead of the Add Activity modal
        try {
          let timeVal = itemEl.getAttribute('data-time') || itemEl.getAttribute('data-start-time') || '';
          if (!timeVal) {
            const timeText = itemEl.querySelector('.activity-time')?.textContent || '';
            const match = timeText.match(/(\d{1,2}:\d{2})/);
            timeVal = match ? match[1] : '';
          }
          Swal.fire({
            title: '<i class="bi bi-clock me-1"></i> Edit Time',
            html: '<label class="swal2-input-label">Set the start time</label>'+
                  '<div class="input-group ocean-time-group">'+
                    '<input type="time" id="editTimeInput" class="form-control" placeholder="HH:MM" />'+
                    '<span class="input-group-text"><i class="bi bi-clock"></i></span>'+
                  '</div>',
            willOpen: () => {
              try {
                const el = document.getElementById('editTimeInput');
                if (el) { el.value = timeVal || ''; setTimeout(()=>el.focus(), 30); }
              } catch(_){ }
            },
            preConfirm: () => {
              const v = (document.getElementById('editTimeInput')?.value || '').trim();
              if (!v) { Swal.showValidationMessage('Please select a time'); return false; }
              return v;
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Cancel',
            allowOutsideClick: true,
            buttonsStyling: false,
            customClass: {
              popup: 'ocean-alert',
              title: 'ocean-alert-title',
              htmlContainer: 'ocean-alert-text',
              actions: 'ocean-alert-actions',
              confirmButton: 'btn-add-day',
              cancelButton: 'btn-add-day'
            }
          }).then((result) => {
            if (!result.isConfirmed) return;
            const newTime = (result.value || '').trim();
            // Update display next to the clock icon (icon on the right)
            const timeEl = itemEl.querySelector('.activity-time');
            if (timeEl) {
              timeEl.innerHTML = `${newTime} <i class=\"bi bi-clock\"></i>`;
            }
            // Persist attributes
            if (newTime) itemEl.setAttribute('data-time', newTime); else itemEl.removeAttribute('data-time');
            itemEl.removeAttribute('data-start-time');
            itemEl.removeAttribute('data-end-time');
            try { if (typeof window.__rebuildCurrentItinerary === 'function') setTimeout(()=>window.__rebuildCurrentItinerary(), 50); } catch(e){}
          });
        } catch (e) {
          try { console.warn('Edit time popup failed', e); } catch(_){ }
        }
      }

      // Save handler for activity form
      (function(){
        const form = document.getElementById('activityForm');
        const saveBtn = document.getElementById('activitySaveBtn');
        // Handler logic extracted so we can attach it via multiple strategies and log diagnostics
        function handleActivitySave(e) {
          if (e) e.preventDefault();
          const modal = document.getElementById('addActivityModal');
          const targetDay = (form && form.dataset.targetDay) || (modal && modal.dataset.targetDay) || window.lastAddActivityTargetDay || '';
          if (!targetDay) { Swal.fire('Error', 'Please open Add Activity from a specific day.', 'error'); return; }

          // If editing an existing activity, update time only and exit
          if (form && form.dataset.editingId) {
            try {
              const editingId = form.dataset.editingId;
              const item = document.querySelector(`.activity-item[data-id="${editingId}"]`);
              if (!item) { delete form.dataset.editingId; return; }
              const timeVal = form.querySelector('#activityTime')?.value || '';
              const timeEl = item.querySelector('.activity-time');
              if (timeEl) {
                const t = timeVal || '';
                timeEl.innerHTML = `<i class="bi bi-clock"></i> ${t}`;
              }
              // Persist on attributes
              if (timeVal) item.setAttribute('data-time', timeVal); else item.removeAttribute('data-time');
              // Clean up legacy attrs
              item.removeAttribute('data-start-time');
              item.removeAttribute('data-end-time');
              // Refresh map/itinerary if needed
              try { if (typeof window.__rebuildCurrentItinerary === 'function') setTimeout(()=>window.__rebuildCurrentItinerary(), 50); } catch(e){}
            } finally {
              delete form.dataset.editingId;
              // keep targetDay for further adds if user continues
              bootstrap.Modal.getInstance(document.getElementById('addActivityModal'))?.hide();
            }
            return;
          }

          // If user selected suggested or all-spot cards, add them
          const grid = document.getElementById('suggestedGrid');
          const allGrid = document.getElementById('allSpotsGrid');
          const selectedCards = [
            ...(grid ? Array.from(grid.querySelectorAll('.suggested-card.selected')) : []),
            ...(allGrid ? Array.from(allGrid.querySelectorAll('.suggested-card.selected')) : [])
          ];

          // Require at least one selected card (customize disabled in add flow)
          if (selectedCards.length > 0) {
            try {
              const confirmMsg = `Add ${selectedCards.length} selected spot${selectedCards.length>1?'s':''} to Day ${targetDay}?`;
              if (!confirm(confirmMsg)) return;
            } catch(e) {}
          } else {
            Swal.fire('Select spots', 'Please select from Recommended or All spots.', 'info');
            return;
          }

          if (selectedCards.length > 0) {
            selectedCards.forEach(card => {
              const title = card.dataset.title || card.querySelector('.sugg-title')?.textContent || 'Untitled';
              const type = card.dataset.type || 'place';
              const location = card.dataset.location || '';
              const cost = card.dataset.cost || '';

              const node = document.createElement('div');
              node.className = 'activity-item';
              node.setAttribute('data-type', type);
              node.setAttribute('data-id', card.dataset.id || ('tmp-' + Date.now() + Math.floor(Math.random()*1000)));
              // preserve link to source card so we can update addedDays later
              if (card && (card.dataset.id || card.dataset.title)) node.setAttribute('data-source-card-id', card.dataset.id || card.dataset.title);
              node.setAttribute('data-title', title);
              node.setAttribute('data-location', location);
              // Preserve coordinates from the suggested card if present
              if (card.dataset.lat) node.setAttribute('data-lat', card.dataset.lat);
              if (card.dataset.lng) node.setAttribute('data-lng', card.dataset.lng);
              if (card.dataset.lat) node.setAttribute('data-lat', card.dataset.lat);
              if (card.dataset.lng) node.setAttribute('data-lng', card.dataset.lng);
              node.innerHTML = `
                <i class="bi bi-grip-vertical activity-drag-handle"></i>
                <div class="activity-icon ${type}"><i class="bi bi-geo-alt"></i></div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">${title}</h4>
                    <div class="activity-time"><span class="time-text"></span> <i class="bi bi-clock"></i></div>
                  </div>
                  <div class="activity-meta"><div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>${location}</span></div><div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱${cost}</span></div></div>
                </div>
                <div class="activity-actions">
                  <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                </div>
              `;

              const list = document.querySelector(`.activities-list[data-day="${targetDay}"]`);
              if (list) {
                list.appendChild(node);
                // Mark this suggested card as added for the target day (day-scoped disable)
                try {
                  const addBtn = card.querySelector('.sugg-add');
                  const dayStr = String(targetDay);
                  const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                  if (!addedDays.includes(dayStr)) addedDays.push(dayStr);
                  card.dataset.addedDays = addedDays.join(',');
                  const isAdded = addedDays.includes(dayStr);
                  if (addBtn) {
                    addBtn.disabled = isAdded;
                    addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                    card.classList.toggle('disabled', isAdded);
                    card.classList.toggle('disabled-for-day', isAdded);
                  }
                } catch(e) {}
              }
            });
            // update in-memory itinerary and map
            setTimeout(()=>{ if (typeof window.__rebuildCurrentItinerary === 'function') window.__rebuildCurrentItinerary(); }, 80);
          }

          // cleanup
          if (form) {
            delete form.dataset.editingId;
            delete form.dataset.targetDay;
          }
          // clear selected in suggested grid
          if (selectedCards.length) selectedCards.forEach(c => c.classList.remove('selected'));
          bootstrap.Modal.getInstance(document.getElementById('addActivityModal'))?.hide();
        }

        // Attach handler directly if button exists
        if (saveBtn) saveBtn.addEventListener('click', handleActivitySave);

        // Also attach delegated listener to catch clicks if something intercepts the button
        document.addEventListener('click', function(e){
          const btn = e.target.closest && e.target.closest('#activitySaveBtn');
          if (btn) handleActivitySave(e);
        });
      })();
    </script>

    <!-- Lightweight UI behavior, generator and history integration -->
    <script>
    (function () {
      // Utilities
      const $ = (sel, root = document) => root.querySelector(sel);
      const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

      // Global flag whether we're running on localhost
      const isLocalhostGlobal = ['localhost','127.0.0.1'].includes(window.location.hostname);

      // Unified modal elements (Create modal now hosts Auto-generate too)
      const modalEl = $('#createItineraryModal');
      const btn = $('#autoGenBtn');
      const generateBtn = $('#createAutoGenerateBtn');
      const previewSection = $('#generatedPreview');
      const previewContent = $('#generatedContent');
      const rightPanelRec = $('#rightPanelRecommended');
      const rightPanelGen = $('#rightPanelGenerated');

      const userPreference = "<?= esc($categories ?? '') ?>";
      const currentUserID = "<?= esc($userID ?? '') ?>";
      let lastItineraryRequest = null;

      // Store references to Flatpickr instances for real-time updates
      let dateRangePickerInstance;
      let autoGenRangePickerInstance;

      // Function to get disabled dates from existing itineraries
      async function getDisabledDates() {
        try {
          const response = await fetch('/itinerary/list');
          if (!response.ok) return [];
          const data = await response.json();
          if (!data.trips || !Array.isArray(data.trips)) return [];
          
          const disabledRanges = [];
          data.trips.forEach(trip => {
            if (trip.start_date && trip.end_date) {
              disabledRanges.push({
                from: new Date(trip.start_date),
                to: new Date(trip.end_date)
              });
            }
          });
          return disabledRanges;
        } catch (err) {
          console.error('Failed to fetch disabled dates:', err);
          return [];
        }
      }

      // Function to refresh disabled dates in both Flatpickr instances
      async function refreshDisabledDates() {
        const disabledRanges = await getDisabledDates();
        
        // Refresh the newTripDateRange picker
        const dateRangeInput = document.getElementById('newTripDateRange');
        if (dateRangeInput && dateRangeInput._flatpickr) {
          dateRangeInput._flatpickr.set('disable', disabledRanges);
        }
        // (auto-gen date range removed; unified in create modal)
      }

      function placeholderMarkup() {
        return `
          <div class="mb-3 text-center text-muted" style="font-weight:600;letter-spacing:.3px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;padding:.5rem .55rem;border-radius:14px;box-shadow:0 6px 14px -6px rgba(0,46,85,.45);margin-right:.5rem;">
              <i class="bi bi-lightning-charge-fill"></i>
            </span>
            Generating your personalized itinerary...
          </div>
          <div class="placeholder-glow">
            <div class="selected-spot-glass mb-3 p-3">
              <h6 class="placeholder col-6" style="height:1.1rem;"></h6>
              <p class="mb-0"><span class="placeholder col-8" style="height:.9rem;"></span></p>
            </div>
            <div class="selected-spot-glass mb-3 p-3">
              <h6 class="placeholder col-5" style="height:1.1rem;"></h6>
              <p class="mb-0"><span class="placeholder col-7" style="height:.9rem;"></span></p>
            </div>
            <div class="selected-spot-glass mb-3 p-3">
              <h6 class="placeholder col-4" style="height:1.1rem;"></h6>
              <p class="mb-0"><span class="placeholder col-6" style="height:.9rem;"></span></p>
            </div>
          </div>
        `;
      }

      async function generateItinerary() {
        // Read inputs from Create modal
        const titleEl = $('#newTripTitle');
        const startEl = $('#newTripStart');
        const endEl = $('#newTripEnd');
        const budgetEl = $('#newTripBudget');
        const adultsEl = $('#itAdults');
        const childrenEl = $('#itChildren');
        const seniorsEl = $('#itSeniors');

        const startVal = (startEl && startEl.value) || '';
        const endVal = (endEl && endEl.value) || '';
        const titleVal = (titleEl && titleEl.value) || '';
        const budgetVal = (budgetEl && budgetEl.value) || '';

        if (!startVal || !endVal) {
          Swal.fire('Incomplete Form', 'Please select a complete date range first.', 'warning');
          return;
        }
        if (!budgetVal || Number(budgetVal) < 0) {
          Swal.fire('Budget Required', 'Please enter a valid budget.', 'warning');
          return;
        }

        // Compute days from date range
        const sDate = new Date(startVal);
        const eDate = new Date(endVal);
        const diffDays = Math.round((eDate - sDate) / (1000*60*60*24)) + 1;
        if (!Number.isFinite(diffDays) || diffDays <= 0) {
          Swal.fire('Invalid Dates', 'Please select a valid date range.', 'warning');
          return;
        }

        // Show preview panel and loading
        if (rightPanelRec) rightPanelRec.style.display = 'none';
        if (rightPanelGen) rightPanelGen.style.display = 'block';
        previewContent.innerHTML = placeholderMarkup();
        if (previewSection) previewSection.style.display = 'block';
        if (generateBtn) generateBtn.disabled = true;

        // Build API URL
        const adults = (adultsEl && adultsEl.value) || 0;
        const children = (childrenEl && childrenEl.value) || 0;
        const seniors = (seniorsEl && seniorsEl.value) || 0;
        const recommendBase = isLocalhostGlobal ? 'http://127.0.0.1:8000/api/recommend/' : 'https://tuklasnasugbu.com/dj/api/recommend/';
        const url = `${recommendBase}?days=${diffDays}&budget=${encodeURIComponent(budgetVal)}&adults=${encodeURIComponent(adults)}&children=${encodeURIComponent(children)}&seniors=${encodeURIComponent(seniors)}&preference=${encodeURIComponent(userPreference)}&start_date=${encodeURIComponent(startVal)}&end_date=${encodeURIComponent(endVal)}`;


        try {
          const controller = new AbortController();
          const timeoutId = setTimeout(() => controller.abort(), 15000); // 15s timeout for AI recommendation
          const response = await fetch(url, { signal: controller.signal });
          clearTimeout(timeoutId);

          if (response.status === 409) {
            const data = await response.json();
            const errorMsg = data.error || 'An itinerary already exists for these dates.';
            Swal.fire({
              title: 'Schedule Conflict',
              text: errorMsg,
              html: '<p style="text-align: left;"><strong>You already have a trip planned for those dates.</strong></p><p style="text-align: left; margin-top: 10px;">You can:</p><ul style="text-align: left;"><li>Choose different dates</li><li>Cancel your existing trip and create a new one</li></ul>',
              icon: 'warning',
              confirmButtonText: 'OK'
            });
            generateBtn.disabled = false;
            previewSection.style.display = 'none';
            return;
          }

          if (!response.ok) throw new Error('Network response was not OK');

          const data = await response.json();
          if (!data.itinerary || !Array.isArray(data.itinerary)) {
            throw new Error('Invalid API response');
          }

          lastItineraryRequest = {
            days: diffDays,
            budget: budgetVal,
            adults: adults,
            children: children,
            seniors: seniors,
            preference: userPreference,
            trip_title: titleVal,
            start_date: startVal,
            end_date: endVal,
            user_id: currentUserID
          };
          // expose for other scripts
          window.__lastItineraryRequest = lastItineraryRequest;
          window.__hasGeneratedPreview = true;

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
          if (data.itinerary.length < diffDays) {
            message = `<div class="alert alert-warning">Only ${data.itinerary.length} day(s) could be generated due to budget constraints.</div>`;
          }
          message += `<div class="alert alert-info">Remaining Budget: ₱${data.remaining_budget}</div>`;
          if (data.note) message += `<div class="alert alert-info">${data.note}</div>`;
          previewContent.innerHTML += message;
        } catch (err) {
          console.error('Auto-generate error:', err);
          const errorMsg = err.name === 'AbortError' 
            ? 'Request timed out. The recommendation service is temporarily unavailable. Please try again in a moment.'
            : err.message.includes('Failed to fetch') || err.message.includes('NetworkError')
            ? 'Cannot connect to the recommendation service. Please check your internet connection and try again.'
            : err.message;
          previewContent.innerHTML = `
            <div class="alert alert-danger">
              <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ${errorMsg}
            </div>
          `;
          if (generateBtn) generateBtn.disabled = false;
        }
      }

      function resetModal() {
        // Reset unified modal preview and toggle panels
        if (previewContent) previewContent.innerHTML = '';
        if (previewSection) previewSection.style.display = 'none';
        if (rightPanelGen) rightPanelGen.style.display = 'none';
        if (rightPanelRec) rightPanelRec.style.display = 'block';
        if (generateBtn) generateBtn.disabled = false;
        window.__hasGeneratedPreview = false;
        window.__lastItineraryRequest = null;
      }

      document.addEventListener('DOMContentLoaded', () => {
        // Debug banner so you can visually confirm the enhanced itinerary script is loaded
        try {
          const itCont = document.querySelector('.itinerary-container');
          if (itCont && !document.getElementById('itineraryEnhBanner')) {
            const b = document.createElement('div');
            b.id = 'itineraryEnhBanner';
            b.style.cssText = 'background:linear-gradient(90deg,#004b8d,#0072c6);color:#fff;padding:6px 10px;border-radius:10px;display:inline-block;font-weight:700;margin-bottom:12px;';
            b.textContent = 'Itinerary: Interactive mode enabled';
            itCont.insertBefore(b, itCont.firstChild);
          }
          console.log('Itinerary enhancements loaded (Leaflet + Sortable)');
        } catch(e){console.warn('Failed to insert itinerary debug banner', e)}
        if (btn) {
          btn.addEventListener('click', () => {
            resetModal();
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
          });
        }

        generateBtn?.addEventListener('click', (e) => {
          e.preventDefault();
          generateItinerary();
        });
        modalEl?.addEventListener('hidden.bs.modal', resetModal);
      });

      // --------------------------
      // Map helper: update markers & routes from an itinerary (supports day filter)
      // --------------------------
      window.__updateMapMarkers = function(itinerary, dayFilter = 'all'){
        try {
          if (typeof L === 'undefined' || !window.itineraryMap) return;
          // ensure markers layer
          if (window.itineraryMarkers && typeof window.itineraryMarkers.clearLayers === 'function') {
            window.itineraryMarkers.clearLayers();
          } else {
            window.itineraryMarkers = L.layerGroup().addTo(window.itineraryMap);
          }
          // remove previous route and distance markers
          if (window.itineraryRoute) { try { window.itineraryMap.removeLayer(window.itineraryRoute); } catch(e){} window.itineraryRoute = null; }
          if (window.__routeDistanceMarkers && Array.isArray(window.__routeDistanceMarkers)) { window.__routeDistanceMarkers.forEach(m => { try { window.itineraryMap.removeLayer(m); } catch(e){} }); window.__routeDistanceMarkers = []; }

          // Build orderedPoints based on filter (be permissive with day types and coordinate formats)
          const orderedPoints = [];
          const normalizeNum = v => {
            if (v === undefined || v === null) return '';
            const s = String(v).trim();
            const digits = s.replace(/[^0-9\-]/g, '');
            return digits;
          };
          const filterNorm = (dayFilter === 'all') ? '' : normalizeNum(dayFilter);

          (itinerary || []).forEach((dayData, idx) => {
            const day = dayData.day ?? dayData.day_number ?? (dayData.index ?? (idx+1));
            const spots = dayData.spots || dayData.places || dayData.activities || [];
            const dayNorm = normalizeNum(day);

            spots.forEach(s => {
              // Accept lat/lng from different property names and formats (strings with commas, etc.)
              const rawLat = s.lat ?? s.latitude ?? s.lat_deg ?? s.latitude_deg ?? s.y ?? '';
              const rawLng = s.lng ?? s.longitude ?? s.lon ?? s.lng_deg ?? s.longitude_deg ?? s.x ?? '';
              const parseCoord = v => {
                if (v === undefined || v === null) return NaN;
                const str = String(v).trim().replace(/,/g, '.');
                const num = parseFloat(str);
                return Number.isFinite(num) ? num : NaN;
              };
              const lat = parseCoord(rawLat);
              const lng = parseCoord(rawLng);

              if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

              // If a specific day is requested, compare normalized forms; otherwise include all
              if (filterNorm) {
                if (!dayNorm) return; // cannot match
                if (filterNorm !== dayNorm) return;
              }

              orderedPoints.push({ lat, lng, meta: s, day });
            });
          });

          // helper: haversine
          const toRad = v => v * Math.PI / 180;
          const haversineKm = (a,b) => {
            const R = 6371; const dLat = toRad(b[0]-a[0]); const dLon = toRad(b[1]-a[1]); const lat1=toRad(a[0]); const lat2=toRad(b[0]); const aa = Math.sin(dLat/2)**2 + Math.cos(lat1)*Math.cos(lat2)*Math.sin(dLon/2)**2; return 2*R*Math.atan2(Math.sqrt(aa), Math.sqrt(1-aa));
          };

          const routeLatLngs = orderedPoints.map(p => [p.lat, p.lng]);
          let totalKm = 0;

          // add numbered markers
          orderedPoints.forEach((p, i) => {
            try {
              const num = i+1;
              const icon = L.divIcon({ className: 'numbered-marker', html: `<div class="marker-number">${num}</div>`, iconSize: [28,28], iconAnchor: [14,28] });
              const marker = L.marker([p.lat, p.lng], { icon }).addTo(window.itineraryMarkers);
              let popup = `<strong>${(p.meta.name||p.meta.title||p.meta.place_name||'Untitled')}</strong><div class="small text-muted">Day ${p.day}</div>`;
              if (i>0) { const prev = routeLatLngs[i-1]; const cur = routeLatLngs[i]; const km = haversineKm(prev, cur); totalKm += km; popup += `<div class="small text-muted">Distance from previous: ${km.toFixed(2)} km</div>`; }
              marker.bindPopup(popup);
            } catch(e){ console.warn('marker add fail',e); }
          });

          // draw polyline
          window.__routeDistanceMarkers = [];
          if (routeLatLngs.length >= 2) {
            try {
              window.itineraryRoute = L.polyline(routeLatLngs, { color: '#007bff', weight: 4, opacity: 0.9 }).addTo(window.itineraryMap);
              for (let i=1;i<routeLatLngs.length;i++){
                const a = routeLatLngs[i-1]; const b = routeLatLngs[i]; const mid = [(a[0]+b[0])/2,(a[1]+b[1])/2]; const km = haversineKm(a,b);
                const lbl = L.divIcon({ className: 'map-route-distance', html: `<div class="small text-muted" style="background:rgba(255,255,255,0.85);padding:4px 6px;border-radius:6px;border:1px solid rgba(0,0,0,0.06)">${km.toFixed(2)} km</div>`, iconSize:[80,20], iconAnchor:[40,10] });
                const m = L.marker(mid, { icon: lbl, interactive: false }).addTo(window.itineraryMap);
                window.__routeDistanceMarkers.push(m);
              }
              const bounds = L.latLngBounds(routeLatLngs);
              if (bounds.isValid && bounds.isValid()) window.itineraryMap.fitBounds(bounds.pad(0.25));
              else window.itineraryMap.setView(routeLatLngs[0], 12);
            } catch(e){ console.warn('polyline fail',e); }
          } else if (routeLatLngs.length === 1) {
            window.itineraryMap.setView(routeLatLngs[0], 12);
          }

          // update summary
          try {
            const mapContainer = document.getElementById('itineraryMap');
            if (mapContainer) {
              let summary = document.getElementById('mapRouteSummary');
              if (!summary) { summary = document.createElement('div'); summary.id='mapRouteSummary'; summary.className='map-route-summary'; mapContainer.parentElement.appendChild(summary); }
              if (routeLatLngs.length >= 2) summary.innerHTML = `<div><strong>Route</strong>: ${routeLatLngs.length} points • Total: ${totalKm.toFixed(2)} km</div>`;
              else if (routeLatLngs.length === 1) summary.innerHTML = `<div><strong>Point</strong>: 1 location</div>`;
              else summary.innerHTML = `<div class="text-muted">No geocoded spots to show on the map.</div>`;
            }
          } catch(e){ console.warn('summary fail', e); }
          try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(e){}
        } catch (err) {
          console.warn('updateMapMarkers failed', err);
        }
      };

      // helper to rebuild window.currentItinerary from DOM and update map
      window.__rebuildCurrentItinerary = function(){
        try {
          const timelineSection = document.querySelector('.timeline-section');
          if (!timelineSection) return;
          const rebuilt = [];
          timelineSection.querySelectorAll('.day-card').forEach(dc => {
            const dayId = dc.id ? dc.id.replace('day','') : '';
            const activities = [];
            dc.querySelectorAll('.activity-item').forEach(ai => {
              const rawLat = ai.dataset.lat ?? ai.getAttribute('data-lat') ?? '';
              const rawLng = ai.dataset.lng ?? ai.getAttribute('data-lng') ?? '';
              const lat = rawLat !== '' ? parseFloat(String(rawLat).replace(/,/g,'.')) : undefined;
              const lng = rawLng !== '' ? parseFloat(String(rawLng).replace(/,/g,'.')) : undefined;
              activities.push({ id: ai.dataset.id || undefined, name: ai.querySelector('.activity-title')?.textContent || ai.dataset.title || '', location: ai.dataset.location || '', lat: Number.isFinite(lat) ? lat : undefined, lng: Number.isFinite(lng) ? lng : undefined });
            });
            rebuilt.push({ day: dayId, spots: activities });
          });
          window.currentItinerary = rebuilt;
          const selected = document.getElementById('mapDayFilter')?.value || 'all';
          if (typeof window.__updateMapMarkers === 'function') window.__updateMapMarkers(window.currentItinerary, selected);
        } catch(e){ console.warn('rebuildItinerary failed', e); }
      };

      // Refresh or create the map day filter controls and attach onchange handler
      window.__updateMapControls = function(){
        try {
          const mapCard = document.querySelector('.summary-card.map-card');
          if (!mapCard) return;
          let controls = mapCard.querySelector('.map-controls');
          if (!controls) {
            controls = document.createElement('div');
            controls.className = 'map-controls d-flex align-items-center gap-2 mb-2';
            controls.style.justifyContent = 'flex-end';
            controls.innerHTML = `<label class="small text-muted mb-0">Show:</label><select id="mapDayFilter" class="form-select form-select-sm" style="width:auto"><option value="all">All days</option></select>`;
            const container = mapCard.querySelector('.map-container');
            if (container) mapCard.insertBefore(controls, container);
            else mapCard.appendChild(controls);
          }
          const select = controls.querySelector('#mapDayFilter');
          if (select) {
            // compute number of days from DOM
            const dayCards = document.querySelectorAll('.day-card');
            const numDays = Math.max(dayCards.length || 0, 0);
            select.innerHTML = '<option value="all">All days</option>';
            for (let i=1;i<=numDays;i++) select.insertAdjacentHTML('beforeend', `<option value="${i}">Day ${i}</option>`);
            select.onchange = function(){ try { if (typeof window.__updateMapMarkers === 'function') window.__updateMapMarkers(window.currentItinerary || [], select.value); } catch(e){} };
          }
        } catch (err) { console.warn('updateMapControls failed', err); }
      };

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
            <div style="display:flex;gap:.5rem;align-items:center;">
              <button class="btn-add-day btn btn-sm btn-outline-primary" onclick="openAddDayModal()"><i class="bi bi-plus-circle"></i> Add Day</button>
              <small class="text-muted">Drag items between days to reorder</small>
            </div>
          </div>
        `;

        itinerary.forEach((dayData, __idx) => {
          const dayIndex = dayData.day ?? dayData.day_number ?? (dayData.index ?? (__idx + 1));
          // Compute a human-friendly date for this day when trip start is known
          let dayDateText = '';
          try {
            if (tripInfo.start_date) {
              const startDateObj = new Date(tripInfo.start_date);
              const numericDay = Number(dayIndex);
              if (!Number.isNaN(numericDay) && numericDay > 0 && !Number.isNaN(startDateObj.getTime())) {
                const d = new Date(startDateObj);
                d.setDate(d.getDate() + (numericDay - 1));
                dayDateText = d.toLocaleDateString();
              } else {
                // If dayIndex is already a date string, try parsing it
                const parsed = new Date(dayIndex);
                if (!Number.isNaN(parsed.getTime())) dayDateText = parsed.toLocaleDateString();
              }
            } else if (dayData.date) {
              const parsed = new Date(dayData.date);
              if (!Number.isNaN(parsed.getTime())) dayDateText = parsed.toLocaleDateString();
            }
          } catch (e) { /* silently ignore date parsing errors */ }
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
                  ${dayDateText ? `<div class="day-date">${dayDateText}</div>` : ''}
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
                <div class="activities-list" data-day="${dayIndex}">
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
                    <div class="activity-item" data-type="${spot.type || 'place'}" data-id="${spot.id || ''}" data-title="${(name||'').replace(/"/g,'\"')}" data-location="${(location||'').replace(/"/g,'\"')}" data-lat="${spot.lat || spot.latitude || ''}" data-lng="${spot.lng || spot.longitude || spot.lon || ''}">
                      <i class="bi bi-grip-vertical activity-drag-handle"></i>
                      <div class="activity-icon place"><i class="bi bi-geo-alt"></i></div>
                      <div class="activity-details">
                        <div class="activity-header">
                          <h4 class="activity-title">${name}</h4>
                          <div class="activity-time">${spot.start_time ?? ''}${spot.end_time ? ' - ' + spot.end_time : ''} <i class="bi bi-clock"></i></div>
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
                </div>
                <div style="margin-top:10px;">
                  <button class="add-activity-btn btn btn-sm btn-outline-success" data-day="${dayIndex}">
                    <i class="bi bi-plus-circle"></i> Add Activity
                  </button>
                </div>
              </div>
            </div>
           
          `;
        });

        timelineSection.innerHTML = timelineHTML;

        // Ensure a single Book Itinerary button exists at the bottom
        (function(){
          const existing = timelineSection.querySelector('.book-itinerary-wrapper');
          if (!existing) {
            const wrapper = document.createElement('div');
            wrapper.className = 'book-itinerary-wrapper';
            wrapper.style.marginTop = '14px';
            wrapper.innerHTML = '<button id="bookItineraryBtn" class="btn btn-primary w-100">Book Itinerary</button>';
            timelineSection.appendChild(wrapper);
          }
        })();

        // Delegated click handler for Book Itinerary (persists across re-renders)
        if (!window.__bookItineraryHandlerAdded) {
          window.__bookItineraryHandlerAdded = true;
          document.addEventListener('click', async function(e){
          const btn = e.target.closest && e.target.closest('#bookItineraryBtn');
          if (!btn) return;
          e.preventDefault();

          // Collect itinerary from DOM
          function collectItineraryFromDOM(){
            const days = [];
            const dayCards = timelineSection.querySelectorAll('.day-card');
            dayCards.forEach(dc => {
              const dayNumMatch = dc.id && dc.id.replace('day','');
              const dayNumber = dayNumMatch || dc.querySelector('.day-number')?.textContent?.trim() || '';
              const dateText = dc.querySelector('.day-date')?.textContent?.trim() || '';
              const activities = [];
              const list = dc.querySelectorAll('.activity-item');
              list.forEach(ai => {
                activities.push({
                  id: ai.dataset.id || null,
                  title: ai.dataset.title || ai.querySelector('.activity-title')?.textContent?.trim() || null,
                  type: ai.dataset.type || null,
                  location: ai.dataset.location || ai.querySelector('.activity-meta .activity-meta-item span')?.textContent || null,
                  lat: ai.dataset.lat || null,
                  lng: ai.dataset.lng || null,
                });
              });
              days.push({ day_number: dayNumber, date: dateText, activities });
            });
            return days;
          }

          const itineraryPayload = collectItineraryFromDOM();
          if (!itineraryPayload || itineraryPayload.length === 0) {
            showAlert('Please add at least one day with activities before booking.', 'warning');
            return;
          }

          // Disable button while submitting
          btn.disabled = true;
          const originalText = btn.innerHTML;
          btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Booking...';

          try {
            const resp = await fetch('/tourist/createBooking', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              credentials: 'same-origin',
              body: JSON.stringify({ itinerary: itineraryPayload, title: document.getElementById('tripTitle')?.textContent || '' })
            });
            const json = await resp.json().catch(()=>({ success: resp.ok }));
            if (resp.ok && json && (json.success === true || json.success === 'true')) {
              // Bookings are created with status 'Pending' until payment is completed
              showAlert('Itinerary booking created (Pending Payment)!', 'success');
              // Optionally redirect to bookings page so user can proceed to payment
              setTimeout(()=>{ window.location.href = '/tourist/myBookings'; }, 900);
            } else {
              console.error('Booking failed', json);
              showAlert(json.message || 'Failed to create booking. Try again.', 'danger');
            }
          } catch (err) {
            console.error('Booking error', err);
            showAlert('Network error while creating booking.', 'danger');
          } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
          }
        });
      } // end __bookItineraryHandlerAdded guard

        // Simple alert helper that shows transient alerts in #toastContainer
        function showAlert(message, type='info'){
          const container = document.getElementById('toastContainer');
          if (!container) { Swal.fire({title: type === 'success' ? 'Success' : type === 'danger' ? 'Error' : type === 'warning' ? 'Warning' : 'Info', text: message, icon: type === 'danger' ? 'error' : type}); return; }
          const el = document.createElement('div');
          const map = { success: 'success', danger: 'danger', warning: 'warning', info: 'info' };
          const cls = 'alert alert-' + (map[type] || 'info');
          el.className = cls;
          el.style.marginBottom = '8px';
          el.innerText = message;
          container.appendChild(el);
          setTimeout(()=>{ el.style.transition = 'opacity .4s'; el.style.opacity = '0'; setTimeout(()=>el.remove(),400); }, 3000);
        }

            // Initialize or show map if present (guard Leaflet availability)
        try {
          if (typeof L === 'undefined') {
            console.warn('Leaflet library not loaded; skipping map initialization');
          } else {
            const mapEl = document.getElementById('itineraryMap');
            if (mapEl) {
              // Make sure the placeholder visuals don't cover Leaflet's map
              mapEl.style.display = 'block';
              try { mapEl.innerHTML = ''; } catch(e){}
              mapEl.classList.remove('map-placeholder');
              if (!mapEl.style.height) mapEl.style.height = '320px';
            }

            if (!window.itineraryMap || !window.itineraryMap._container) {
              // Create map only once
              window.itineraryMap = L.map('itineraryMap', { zoomControl: true }).setView([12.92, 121.06], 9);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
              }).addTo(window.itineraryMap);
              window.itineraryMarkers = L.layerGroup().addTo(window.itineraryMap);
              // Ensure correct sizing after it's added to DOM
              setTimeout(() => { try { window.itineraryMap.invalidateSize(); } catch(e){} }, 250);
            } else {
              if (window.itineraryMarkers && typeof window.itineraryMarkers.clearLayers === 'function') {
                window.itineraryMarkers.clearLayers();
              } else {
                window.itineraryMarkers = L.layerGroup().addTo(window.itineraryMap);
              }
            }

            // Build map day filter UI (based on number of days) and refresh markers/route using helper
            try {
              const mapCard = document.querySelector('.summary-card.map-card');
              const numDays = Math.max(itinerary.length || 0, (tripInfo.days || 0));
              if (mapCard && numDays > 0) {
                let controls = mapCard.querySelector('.map-controls');
                if (!controls) {
                  controls = document.createElement('div');
                  controls.className = 'map-controls d-flex align-items-center gap-2 mb-2';
                  controls.style.justifyContent = 'flex-end';
                  controls.innerHTML = `<label class="small text-muted mb-0">Show:</label><select id="mapDayFilter" class="form-select form-select-sm" style="width:auto"><option value="all">All days</option></select>`;
                  const container = mapCard.querySelector('.map-container');
                  if (container) mapCard.insertBefore(controls, container);
                  else mapCard.appendChild(controls);
                }
                const select = controls.querySelector('#mapDayFilter');
                if (select) {
                  // populate options
                  select.innerHTML = '<option value="all">All days</option>';
                  for (let i=1;i<=numDays;i++) select.insertAdjacentHTML('beforeend', `<option value="${i}">Day ${i}</option>`);
                  select.value = select.value || 'all';
                  select.onchange = function(){ try { if (typeof window.__updateMapMarkers === 'function') window.__updateMapMarkers(window.currentItinerary || itinerary, select.value); } catch(e){console.warn(e);} };
                }
              }
              if (typeof window.__updateMapMarkers === 'function') window.__updateMapMarkers(itinerary, document.getElementById('mapDayFilter')?.value || 'all');
            } catch(e){ console.warn('failed to setup map controls or call updateMapMarkers', e); }
          }
        } catch (err) {
          console.warn('Map init failed', err);
        }

        // Make activities sortable (drag & drop between days)
        window.currentItinerary = itinerary; // keep in-memory copy

        // Global function to open itinerary map in centered modal (header expand button uses this)
        window.openItineraryMapViewer = function(){
          const mapEl = document.getElementById('itineraryMap');
          if (!mapEl) return;

          function createViewerModal() {
            if (document.getElementById('itineraryMapViewerModal')) return document.getElementById('itineraryMapViewerModal');
            const html = `
            <div class="modal fade" id="itineraryMapViewerModal" tabindex="-1" aria-hidden="true">
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
            return document.getElementById('itineraryMapViewerModal');
          }

          const viewerModalEl = createViewerModal();
          const viewerModal = new bootstrap.Modal(viewerModalEl, { keyboard: true });
          const originalParent = mapEl.parentElement;
          const nextSibling = mapEl.nextSibling;
          const originalHeight = mapEl.style.height || '';

          const body = viewerModalEl.querySelector('.modal-body');
          body.appendChild(mapEl);
          mapEl.style.height = '100%';
          viewerModal.show();
          setTimeout(()=>{ try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(e){} }, 250);

          // one-time restore on close
          viewerModalEl.addEventListener('hidden.bs.modal', function(){
            if (nextSibling) originalParent.insertBefore(mapEl, nextSibling);
            else originalParent.appendChild(mapEl);
            mapEl.style.height = originalHeight || '320px';
            setTimeout(()=>{ try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(e){} }, 80);
          }, { once: true });
        };

        // Hook header expand button (if present)
        try {
          const hdrBtn = document.getElementById('itineraryMapExpandBtn');
          if (hdrBtn) hdrBtn.addEventListener('click', function(e){ e.stopPropagation(); window.openItineraryMapViewer(); });
        } catch(e){}
        try {
          const activityLists = timelineSection.querySelectorAll('.activities-list');
          activityLists.forEach(list => {
            Sortable.create(list, {
              group: 'days',
              animation: 150,
              handle: '.activity-drag-handle',
              onEnd: function (evt) {
                try {
                  const item = evt.item; // moved element
                  const fromList = evt.from && evt.from.closest && evt.from.closest('.day-card') ? evt.from.closest('.day-card') : evt.from;
                  const toList = evt.to && evt.to.closest && evt.to.closest('.day-card') ? evt.to.closest('.day-card') : evt.to;
                  const oldDay = fromList ? fromList.dataset.day || fromList.getAttribute('data-day') : null;
                  const newDay = toList ? toList.dataset.day || toList.getAttribute('data-day') : null;
                  // If this activity came from a suggested card, update that card's addedDays
                  if (item && item.dataset && item.dataset.sourceCardId) {
                    const sourceId = item.dataset.sourceCardId;
                    const card = document.querySelector(`.suggested-card[data-id="${sourceId}"]`) || Array.from(document.querySelectorAll('.suggested-card')).find(c => (c.dataset.title || '') === sourceId);
                    if (card) {
                      const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                      // remove oldDay
                      if (oldDay) {
                        const idx = addedDays.indexOf(String(oldDay));
                        if (idx !== -1) addedDays.splice(idx,1);
                      }
                      // add newDay
                      if (newDay && !addedDays.includes(String(newDay))) addedDays.push(String(newDay));
                      card.dataset.addedDays = addedDays.join(',');
                      const addBtn = card.querySelector('.sugg-add');
                      const isAdded = addedDays.includes(String(newDay));
                      if (addBtn) {
                        addBtn.disabled = isAdded;
                        addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                        card.classList.toggle('disabled', isAdded);
                        card.classList.toggle('disabled-for-day', isAdded);
                      }
                    }
                  }
                } catch (e) { console.warn('Error updating suggested-card day state after drag', e); }

                // Rebuild in-memory itinerary and refresh map
                if (typeof window.__rebuildCurrentItinerary === 'function') {
                  window.__rebuildCurrentItinerary();
                  console.log('Itinerary reordered and map refreshed');
                }
              }
            });
          });
        } catch (err) {
          console.warn('Sortable init failed', err);
        }

        // Attach delegated handlers for edit/delete/add activity
        timelineSection.querySelectorAll('.add-activity-btn').forEach(btn => {
          btn.addEventListener('click', (e) => {
            const day = btn.getAttribute('data-day');
            openAddActivityModalForDay(day);
          });
        });

        timelineSection.addEventListener('click', (e) => {
          const editBtn = e.target.closest('.btn-activity-action');
          const delBtn = e.target.closest('.btn-activity-action.delete');
          if (delBtn) {
            const item = delBtn.closest('.activity-item');
            if (item && confirm('Delete this activity?')) {
              try {
                // If this activity originated from a suggested card, remove the day from that card's addedDays
                if (item.dataset && item.dataset.sourceCardId) {
                  const sourceId = item.dataset.sourceCardId;
                  const card = document.querySelector(`.suggested-card[data-id="${sourceId}"]`) || Array.from(document.querySelectorAll('.suggested-card')).find(c => (c.dataset.title || '') === sourceId);
                  if (card) {
                    const list = item.closest('.activities-list');
                    const day = list ? (list.dataset.day || list.getAttribute('data-day')) : null;
                    const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                    if (day) {
                      const idx = addedDays.indexOf(String(day));
                      if (idx !== -1) addedDays.splice(idx,1);
                    }
                    card.dataset.addedDays = addedDays.join(',');
                    // enable the add button if no longer added for this day
                    const addBtn = card.querySelector('.sugg-add');
                    const isAdded = addedDays.length > 0;
                    if (addBtn) {
                      addBtn.disabled = false;
                      addBtn.innerHTML = '<i class="bi bi-plus-lg"></i>';
                      card.classList.remove('disabled', 'disabled-for-day');
                    }
                  }
                }
              } catch(err) { console.warn('Failed to update suggested-card after delete', err); }
              item.remove();
            }

            // Map viewer modal helper — open itinerary map inside centered modal for better navigation
            (function(){
              const mapEl = document.getElementById('itineraryMap');
              if (!mapEl) return;

              function createViewerModal() {
                if (document.getElementById('itineraryMapViewerModal')) return document.getElementById('itineraryMapViewerModal');
                const html = `
                <div class="modal fade" id="itineraryMapViewerModal" tabindex="-1" aria-hidden="true">
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
                return document.getElementById('itineraryMapViewerModal');
              }

              const viewerModalEl = createViewerModal();
              const viewerModal = new bootstrap.Modal(viewerModalEl, { keyboard: true });

              // Remember original parent and next sibling to restore later
              const originalParent = mapEl.parentElement;
              const nextSibling = mapEl.nextSibling;
              const originalHeight = mapEl.style.height || '';

              mapEl.style.cursor = 'pointer';
              if (!mapEl.style.position) mapEl.style.position = 'relative';

              // open viewer function (reusable for map click and expand button)
              function openViewer(e){
                if (e && e.stopPropagation) e.stopPropagation();
                // avoid opening modal when clicking controls inside map (if any)
                if (e && e.target && e.target.closest && e.target.closest('.leaflet-control')) return;
                const body = viewerModalEl.querySelector('.modal-body');
                body.appendChild(mapEl);
                // make map fill modal body
                mapEl.style.height = '100%';
                viewerModal.show();
                setTimeout(()=>{ try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(err){} }, 250);
              }

              mapEl.addEventListener('click', openViewer);

              // Add an explicit expand button overlay so users can expand the map reliably
              (function addExpandButton(){
                // if header-level expand button exists, skip overlay button to avoid overlap
                if (document.getElementById('itineraryMapExpandBtn')) return;
                // avoid duplicating button
                if (mapEl.querySelector('.map-expand-btn')) return;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'map-expand-btn';
                btn.title = 'Expand map';
                btn.innerHTML = '<i class="bi bi-arrows-fullscreen" style="color:#fff"></i>';
                // positioning will be handled by positionMapExpandButton
                mapEl.appendChild(btn);

                // helper to compute and set position
                function positionMapExpandButton() {
                  try {
                    const mapContainer = mapEl.closest('.map-container') || mapEl.parentElement;
                    if (!mapContainer) return;
                    // responsive fallback
                    if (window.innerWidth <= 640) {
                      btn.style.top = '';
                      btn.style.bottom = '12px';
                      btn.style.right = '12px';
                      return;
                    }
                    // if header-level button exists, position below it
                    const headerBtn = document.getElementById('itineraryMapExpandBtn');
                    if (headerBtn) {
                      const headerRect = headerBtn.getBoundingClientRect();
                      const containerRect = mapContainer.getBoundingClientRect();
                      let top = Math.round(headerRect.bottom - containerRect.top + 8);
                      top = Math.max(top, 8);
                      btn.style.top = top + 'px';
                      btn.style.bottom = '';
                      btn.style.right = '12px';
                      return;
                    }
                    // fallback: place under map container padding
                    btn.style.top = '16px';
                    btn.style.right = '12px';
                    btn.style.bottom = '';
                  } catch (err) { console.warn('positionMapExpandButton error', err); }
                }

                positionMapExpandButton();
                window.addEventListener('resize', positionMapExpandButton);
              })();

              viewerModalEl.addEventListener('hidden.bs.modal', function(){
                // move back the map element to its original place
                if (nextSibling) originalParent.insertBefore(mapEl, nextSibling);
                else originalParent.appendChild(mapEl);
                mapEl.style.height = originalHeight || '320px';
                setTimeout(()=>{ try { if (window.itineraryMap && typeof window.itineraryMap.invalidateSize === 'function') window.itineraryMap.invalidateSize(); } catch(err){} }, 80);
              });
            })();
          } else if (editBtn) {
            const item = editBtn.closest('.activity-item');
            if (item) openEditActivityModal(item);
          }
        });

        // Update budget card
        let totalEstimatedCost = 0;
        itinerary.forEach((dayData, __idx) => {
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
              Swal.fire('Error', 'Failed to load trip details. ' + (err && err.message ? err.message : ''), 'error');
            }
          });
        });

        modalEl.querySelectorAll('.btn-delete-trip').forEach(btn => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const tripTitle = decodeURIComponent(btn.dataset.tripTitle);
            const result = await Swal.fire({
              title: 'Delete Trip?',
              text: `Are you sure you want to delete "${tripTitle}"? This action cannot be undone.`,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#dc3545',
              cancelButtonColor: '#6c757d',
              confirmButtonText: 'Yes, delete it!'
            });
            if (!result.isConfirmed) return;
            try {
              const resp = await fetch(`/itinerary/delete?trip_title=${encodeURIComponent(tripTitle)}`, { method: 'DELETE' });
              if (!resp.ok) throw new Error('Delete failed: ' + resp.status);
              Swal.fire('Deleted', `Trip "${tripTitle}" has been deleted.`, 'success');
              bsModal.hide();
            } catch (err) {
              console.error(err);
              Swal.fire('Failed', 'Failed to delete: ' + err.message, 'error');
            }
          });
        });
      }
      // Expose the attach function
      window.__attachHistoryModalHandlers = attachHistoryModalHandlers;

      // Provide a backward-compatible loadSavedTrip that uses renderer
      window.loadSavedTrip = async function(tripTitle, startDate) {
        try {
          // Use relative endpoint on the main app instead of hitting Django on port 8000
          const resp = await fetch(`/itinerary/get?trip_title=${encodeURIComponent(tripTitle)}&start_date=${encodeURIComponent(startDate)}`);
          if (!resp.ok) throw new Error('Failed to load trip');
          const data = await resp.json();
          if (typeof window.__renderTripItinerary === 'function') window.__renderTripItinerary(data);
        } catch (err) {
          console.error('Error loading saved trip:', err);
          Swal.fire('Info', 'Trip saved but could not auto-load details. Error: ' + err.message, 'info');
        }
      };
    })();
    </script>

    <!-- Naive Bayes Recommendations Loader -->
    <script>
      (function(){
        // Load NB ranked spots into the suggestedGrid (Add Activity modal)
        async function loadNbRecommendations(){
          const grid = document.getElementById('suggestedGrid');
          if(!grid) return;
          
          // Use server-side recommended spots endpoint (from DB)
          const nbUrl = `/tourist/recommendedSpots?limit=8`;
          
          try {
            grid.innerHTML = '<div class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm"></div><p class="small mb-0 mt-2">Loading recommendations...</p></div>';
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
            
            const resp = await fetch(nbUrl, { signal: controller.signal });
            clearTimeout(timeoutId);
            
            if(!resp.ok) {
              throw new Error('NB endpoint returned ' + resp.status);
            }
            
            const data = await resp.json();
            
            const spots = data.spots || data.results || [];
            if (!Array.isArray(spots) || spots.length === 0) {
              loadDefaultRecommendations(grid);
              return;
            }

            grid.innerHTML = spots.map(s => {
              const price = Number(s.price_per_person || 0);
              const freeTag = price === 0 ? '<span class="badge bg-success ms-1">Free</span>' : '';
              const imgUrl = (s.primary_image || s.image || s.thumbnail || s.photo || '').toString();
              const safeImg = imgUrl ? imgUrl : 'https://via.placeholder.com/120x86?text=Spot';
              return `
                <div class="suggested-card" data-id="${s.id||''}" data-type="place" data-title="${(s.name||'').replace(/"/g,'\"')}" data-location="${(s.location||'').replace(/"/g,'\"')}" data-cost="${price}" data-lat="${s.lat||''}" data-lng="${s.lng||''}">
                  <div class="sugg-photo"><img class="sugg-img" src="${safeImg}" alt="${(s.name||'Spot').replace(/"/g,'&quot;')}" /></div>
                  <div class="sugg-info">
                    <div class="sugg-title">${s.name||'Spot'}</div>
                    <div class="sugg-meta">${s.category || 'Spot'} • ${s.location || ''} • ₱${price}${freeTag}</div>
                  </div>
                  <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
                </div>`;
            }).join('');
            
            // Attach add button handlers
            grid.querySelectorAll('.suggested-card .sugg-add').forEach(btn => {
              btn.addEventListener('click', () => {
                const card = btn.closest('.suggested-card');
                if(card) card.classList.toggle('selected');
              });
            });
          } catch(err){
            console.warn('NB recommendation unavailable, using defaults:', err.message);
            // Fall back to default static recommendations
            loadDefaultRecommendations(grid);
          }
        }
        
        function loadDefaultRecommendations(grid){
          // Default fallback recommendations (keep existing static cards)
          grid.innerHTML = `
            <div class="suggested-card" data-type="place" data-title="Mount Batulao Viewpoint" data-location="Nasugbu, Batangas" data-cost="0">
              <div class="sugg-photo"><img class="sugg-img" src="https://via.placeholder.com/120x86?text=Batulao" alt="Mount Batulao Viewpoint"/></div>
              <div class="sugg-info">
                <div class="sugg-title">Mount Batulao Viewpoint</div>
                <div class="sugg-meta">Attraction • Nasugbu, Batangas • ₱0</div>
              </div>
              <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
            </div>
            <div class="suggested-card" data-type="place" data-title="Calayo Beach" data-location="Calayo, Nasugbu" data-cost="50">
              <div class="sugg-photo"><img class="sugg-img" src="https://via.placeholder.com/120x86?text=Beach" alt="Calayo Beach"/></div>
              <div class="sugg-info">
                <div class="sugg-title">Calayo Beach</div>
                <div class="sugg-meta">Attraction • Calayo, Nasugbu • ₱50</div>
              </div>
              <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
            </div>
            <div class="suggested-card" data-type="food" data-title="Beachfront Grill" data-location="Nasugbu Town" data-cost="350">
              <div class="sugg-photo"><img class="sugg-img" src="https://via.placeholder.com/120x86?text=Food" alt="Beachfront Grill"/></div>
              <div class="sugg-info">
                <div class="sugg-title">Beachfront Grill</div>
                <div class="sugg-meta">Food • Nasugbu Town • ₱350</div>
              </div>
              <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
            </div>
            <div class="suggested-card" data-type="lodging" data-title="Coastal Inn" data-location="Nasugbu" data-cost="1500">
              <div class="sugg-photo"><img class="sugg-img" src="https://via.placeholder.com/120x86?text=Stay" alt="Coastal Inn"/></div>
              <div class="sugg-info">
                <div class="sugg-title">Coastal Inn</div>
                <div class="sugg-meta">Accommodation • Nasugbu • ₱1,500</div>
              </div>
              <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
            </div>
          `;
        }
        
        // Expose for manual triggering if needed
        window.loadNbRecommendations = loadNbRecommendations;

        // Load All Spots for Add Activity modal
        async function loadAllSpotsForActivityModal(query=''){
          const grid = document.getElementById('allSpotsGrid');
          if (!grid) return;
          try {
            grid.innerHTML = '<div class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm"></div><p class="small mb-0 mt-2">Loading all spots...</p></div>';
            const url = `/api/tourist-spots${query? ('?search='+encodeURIComponent(query)) : ''}`;
            const resp = await fetch(url);
            let spots = [];
            if (resp.ok) {
              const data = await resp.json();
              spots = data.spots || data.data || [];
            }
            if (!Array.isArray(spots) || spots.length === 0) {
              grid.innerHTML = '<div class="text-center text-muted py-2">No spots found.</div>';
              return;
            }
            grid.innerHTML = spots.map(s => {
              const price = Number(s.price_per_person || s.adult_price || 0);
              const freeTag = price === 0 ? '<span class="badge bg-success ms-1">Free</span>' : '';
              const imgUrl = (s.primary_image || s.image || s.thumbnail || s.photo || '').toString();
              const safeImg = imgUrl ? imgUrl : 'https://via.placeholder.com/120x86?text=Spot';
              return `
                <div class="suggested-card" data-id="${s.id||''}" data-type="place" data-title="${(s.name||'').replace(/"/g,'\"')}" data-location="${(s.location||'').replace(/"/g,'\"')}" data-cost="${price}" data-lat="${s.lat||''}" data-lng="${s.lng||''}">
                  <div class="sugg-photo"><img class="sugg-img" src="${safeImg}" alt="${(s.name||'Spot').replace(/"/g,'&quot;')}" /></div>
                  <div class="sugg-info">
                    <div class="sugg-title">${s.name||'Spot'}</div>
                    <div class="sugg-meta">${s.category || 'Spot'} • ${s.location || ''} • ₱${price}${freeTag}</div>
                  </div>
                  <button class="sugg-add" type="button" title="Add"><i class="bi bi-plus-lg"></i></button>
                </div>`;
            }).join('');

            // Disable + button for already added in this day
            try {
              const modal = document.getElementById('addActivityModal');
              const dayStr = (modal?.dataset?.targetDay || '').toString();
              if (dayStr) {
                Array.from(grid.querySelectorAll('.suggested-card')).forEach(card => {
                  const addBtn = card.querySelector('.sugg-add');
                  const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                  const isAdded = addedDays.includes(dayStr);
                  if (addBtn) {
                    addBtn.disabled = isAdded;
                    addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                    card.classList.toggle('disabled', isAdded);
                    card.classList.toggle('disabled-for-day', isAdded);
                  }
                });
              }
            } catch(e){}
          } catch (err) {
            console.warn('Failed to load all spots', err);
            grid.innerHTML = '<div class="text-center text-muted py-2">Failed to load spots.</div>';
          }
        }
        window.loadAllSpotsForActivityModal = loadAllSpotsForActivityModal;
        
        document.addEventListener('DOMContentLoaded', () => {
          // Defer slightly to avoid blocking other scripts
          setTimeout(loadNbRecommendations, 400);

          // Toggle for All Spots section in Add Activity modal
          try {
            const toggleBtn = document.getElementById('toggleAllSpotsBtn');
            const container = document.getElementById('allSpotsContainer');
            const chevron = document.getElementById('allSpotsChevron');
            if (toggleBtn && container) {
              toggleBtn.addEventListener('click', () => {
                const isShown = container.style.display !== 'none';
                container.style.display = isShown ? 'none' : '';
                toggleBtn.setAttribute('aria-expanded', String(!isShown));
                if (chevron) chevron.className = isShown ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
                // Lazy-load when first expanded
                if (!isShown && typeof window.loadAllSpotsForActivityModal === 'function') {
                  try { window.loadAllSpotsForActivityModal(document.getElementById('allSpotsSearch')?.value || ''); } catch(e){}
                }
              });
            }
          } catch(e) { console.warn('Failed to init All Spots toggle', e); }

          // Delegated click handler for suggested grid (works for static + dynamic cards)
          const grid = document.getElementById('suggestedGrid');
          if (grid) {
            // Helper to add a suggested card immediately to the target day
            function addSuggestedCardToDay(card) {
              const form = document.getElementById('activityForm');
              const targetDay = (form && form.dataset.targetDay) || window.lastAddActivityTargetDay || '';
              if (!targetDay) {
                // No popup: silently ignore if no target day is set
                console.warn('Add Activity: target day not set');
                return;
              }
              const title = card.dataset.title || card.querySelector('.sugg-title')?.textContent || 'Untitled';
              const type = card.dataset.type || 'place';
              const location = card.dataset.location || '';
              const cost = card.dataset.cost || '';

              const node = document.createElement('div');
              node.className = 'activity-item';
              node.setAttribute('data-type', type);
              node.setAttribute('data-id', 'tmp-' + Date.now() + Math.floor(Math.random()*1000));
              // link back to source suggested card so deletes/moves can update its day-state
              if (card && (card.dataset.id || card.dataset.title)) {
                node.setAttribute('data-source-card-id', card.dataset.id || card.dataset.title);
              }
              node.setAttribute('data-title', title);
              node.setAttribute('data-location', location);
              node.innerHTML = `
                <i class="bi bi-grip-vertical activity-drag-handle"></i>
                <div class="activity-icon ${type}"><i class="bi bi-geo-alt"></i></div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">${title}</h4>
                    <div class="activity-time"><span class="time-text"></span> <i class="bi bi-clock"></i></div>
                  </div>
                  <div class="activity-meta"><div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>${location}</span></div><div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱${cost}</span></div></div>
                </div>
                <div class="activity-actions">
                  <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                </div>
              `;

              const list = document.querySelector(`.activities-list[data-day="${targetDay}"]`);
              if (list) {
                list.appendChild(node);
                // No auto-open of customize/edit on add
                console.log('Added suggested card to day', targetDay, title);
                // Mark the source suggested card as added for this target day (day-scoped)
                try {
                  const addBtn = card.querySelector('.sugg-add');
                  const dayStr = String(targetDay);
                  const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                  if (!addedDays.includes(dayStr)) addedDays.push(dayStr);
                  card.dataset.addedDays = addedDays.join(',');
                  const isAdded = addedDays.includes(dayStr);
                  if (addBtn) {
                    addBtn.disabled = isAdded;
                    addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                    card.classList.toggle('disabled', isAdded);
                    card.classList.toggle('disabled-for-day', isAdded);
                  }
                } catch (e) { /* ignore */ }
                // If map is present and the suggested card has lat/lng, add a temporary marker
                try {
                  const lat = node.getAttribute('data-lat');
                  const lng = node.getAttribute('data-lng');
                  if (lat && lng && window.itineraryMap && window.itineraryMarkers) {
                    const m = L.marker([Number(lat), Number(lng)]).bindPopup(`<strong>${title}</strong><div class="small text-muted">Day ${targetDay}</div>`);
                    window.itineraryMarkers.addLayer(m);
                    try { const bounds = window.itineraryMarkers.getBounds(); if (bounds.isValid && bounds.isValid()) window.itineraryMap.fitBounds(bounds.pad(0.25)); } catch(e){}
                  }
                } catch(e){ console.warn('Could not add suggested marker to map', e); }
                // Rebuild current itinerary and refresh numbered markers/route
                try {
                  if (typeof window.__rebuildCurrentItinerary === 'function') {
                    // small delay to ensure DOM insertion is complete before rebuild
                    setTimeout(() => { window.__rebuildCurrentItinerary(); }, 50);
                  }
                  // ensure map controls updated (day count may not change here but safe to call)
                  if (typeof window.__updateMapControls === 'function') window.__updateMapControls();
                } catch(e) { console.warn('post-add suggested refresh failed', e); }
              } else {
                Swal.fire('Error', 'Could not find the target day list to add activity.', 'error');
              }
            }

            grid.addEventListener('click', (e) => {
              const btn = e.target.closest('.sugg-add');
              const card = e.target.closest('.suggested-card');
              if (btn && card) {
                // Determine target day (modal or lastAddActivityTargetDay)
                const form = document.getElementById('activityForm');
                const targetDay = (form && form.dataset.targetDay) || window.lastAddActivityTargetDay || '';
                const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                if (targetDay && addedDays.includes(String(targetDay))) {
                  console.log('Spot already added for day', targetDay, card.dataset.title || '');
                  return;
                }
                // Immediate add when + button clicked
                addSuggestedCardToDay(card);
                return;
              }
              // Clicking the card toggles selection (for multi-select before Save)
              if (card && !e.target.closest('.sugg-add')) {
                card.classList.toggle('selected');
              }
            });
          }

          // Delegated click handler for All Spots grid
          const allGrid = document.getElementById('allSpotsGrid');
          if (allGrid) {
            function addAllSpotCardToDay(card) {
              const form = document.getElementById('activityForm');
              const modal = document.getElementById('addActivityModal');
              const targetDay = (form && form.dataset.targetDay) || (modal && modal.dataset.targetDay) || window.lastAddActivityTargetDay || '';
              if (!targetDay) {
                // No popup: silently ignore if no target day is set
                console.warn('Add Activity: target day not set');
                return;
              }
              const title = card.dataset.title || card.querySelector('.sugg-title')?.textContent || 'Untitled';
              const type = card.dataset.type || 'place';
              const location = card.dataset.location || '';
              const cost = card.dataset.cost || '';

              const node = document.createElement('div');
              node.className = 'activity-item';
              node.setAttribute('data-type', type);
              node.setAttribute('data-id', 'tmp-' + Date.now() + Math.floor(Math.random()*1000));
              if (card && (card.dataset.id || card.dataset.title)) {
                node.setAttribute('data-source-card-id', card.dataset.id || card.dataset.title);
              }
              node.setAttribute('data-title', title);
              node.setAttribute('data-location', location);
              node.innerHTML = `
                <i class="bi bi-grip-vertical activity-drag-handle"></i>
                <div class="activity-icon ${type}"><i class="bi bi-geo-alt"></i></div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">${title}</h4>
                    <div class="activity-time"><i class="bi bi-clock"></i></div>
                  </div>
                  <div class="activity-meta"><div class="activity-meta-item"><i class="bi bi-geo-alt"></i><span>${location}</span></div><div class="activity-meta-item"><i class="bi bi-cash-stack"></i><span>₱${cost}</span></div></div>
                </div>
                <div class="activity-actions">
                  <button class="btn-activity-action" title="Edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn-activity-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                </div>
              `;

              const list = document.querySelector(`.activities-list[data-day="${targetDay}"]`);
              if (list) {
                list.appendChild(node);
                // No auto-open of customize/edit on add
                try {
                  const addBtn = card.querySelector('.sugg-add');
                  const dayStr = String(targetDay);
                  const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                  if (!addedDays.includes(dayStr)) addedDays.push(dayStr);
                  card.dataset.addedDays = addedDays.join(',');
                  const isAdded = addedDays.includes(dayStr);
                  if (addBtn) {
                    addBtn.disabled = isAdded;
                    addBtn.innerHTML = isAdded ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-plus-lg"></i>';
                    card.classList.toggle('disabled', isAdded);
                    card.classList.toggle('disabled-for-day', isAdded);
                  }
                } catch (e) { /* ignore */ }
                try { if (typeof window.__rebuildCurrentItinerary === 'function') setTimeout(()=>window.__rebuildCurrentItinerary(), 50); } catch(e){}
              } else {
                Swal.fire('Error', 'Could not find the target day list to add activity.', 'error');
              }
            }

            allGrid.addEventListener('click', (e) => {
              const btn = e.target.closest('.sugg-add');
              const card = e.target.closest('.suggested-card');
              if (btn && card) {
                const form = document.getElementById('activityForm');
                const modal = document.getElementById('addActivityModal');
                const targetDay = (form && form.dataset.targetDay) || (modal && modal.dataset.targetDay) || window.lastAddActivityTargetDay || '';
                const addedDays = (card.dataset.addedDays || '').split(',').map(s=>s.trim()).filter(Boolean);
                if (targetDay && addedDays.includes(String(targetDay))) return;
                addAllSpotCardToDay(card);
                return;
              }
              if (card && !e.target.closest('.sugg-add')) {
                card.classList.toggle('selected');
              }
            });
          }

          // Simple search/filter for suggested cards
          const searchInput = document.getElementById('recommendSearch');
          if (searchInput && grid) {
            searchInput.addEventListener('input', (ev) => {
              const q = ev.target.value.trim().toLowerCase();
              Array.from(grid.querySelectorAll('.suggested-card')).forEach(card => {
                const title = (card.dataset.title || card.querySelector('.sugg-title')?.textContent || '').toLowerCase();
                const meta = (card.querySelector('.sugg-meta')?.textContent || '').toLowerCase();
                if (!q || title.includes(q) || meta.includes(q)) {
                  card.style.display = '';
                } else {
                  card.style.display = 'none';
                }
              });
            });
          }

          // Search for All Spots grid
          const allSearchInput = document.getElementById('allSpotsSearch');
          const allSearchBtn = document.getElementById('allSpotsSearchBtn');
          if (allSearchBtn && allSearchInput) {
            allSearchBtn.addEventListener('click', () => {
              const q = allSearchInput.value.trim();
              try { window.loadAllSpotsForActivityModal(q); } catch(e){}
            });
            allSearchInput.addEventListener('keypress', (e) => {
              if (e.key === 'Enter') { e.preventDefault(); allSearchBtn.click(); }
            });
          }

          // Initialize Sortable on existing activity lists so drag & drop works on server-rendered page
          try {
            if (typeof Sortable !== 'undefined') {
              document.querySelectorAll('.activities-list').forEach(list => {
                Sortable.create(list, {
                  group: 'days', animation: 150, handle: '.activity-drag-handle',
                  onEnd: function (evt) {
                    // Rebuild in-memory itinerary from DOM order
                    const timelineSection = document.querySelector('.timeline-section');
                    if (!timelineSection) return;
                    const rebuilt = [];
                    timelineSection.querySelectorAll('.day-card').forEach(dc => {
                      const dayId = dc.id ? dc.id.replace('day','') : '';
                      const activities = [];
                      dc.querySelectorAll('.activity-item').forEach(ai => {
                        activities.push({ id: ai.dataset.id || undefined, name: ai.querySelector('.activity-title')?.textContent || ai.dataset.title || '' });
                      });
                      rebuilt.push({ day: dayId, spots: activities });
                    });
                    window.currentItinerary = rebuilt;
                    console.log('Itinerary reordered (init)', window.currentItinerary);
                  }
                });
              });
            }
          } catch (err) {
            console.warn('Init sortable failed', err);
          }

          // Wire add/edit/delete handlers for server-rendered timeline
          try {
            const timelineSection = document.querySelector('.timeline-section');
            if (timelineSection) {
              // add-activity buttons
              timelineSection.querySelectorAll('.add-activity-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                  const day = btn.getAttribute('data-day') || '';
                  openAddActivityModalForDay(day);
                });
              });

              // delegated edit/delete
              timelineSection.addEventListener('click', (e) => {
                const editBtn = e.target.closest('.btn-activity-action');
                const delBtn = e.target.closest('.btn-activity-action.delete');
                if (delBtn) {
                  const item = delBtn.closest('.activity-item');
                  if (item && confirm('Delete this activity?')) item.remove();
                } else if (editBtn) {
                  const item = editBtn.closest('.activity-item');
                  if (item) openEditActivityModal(item);
                }
              });

              // build initial in-memory itinerary
              const rebuilt = [];
              timelineSection.querySelectorAll('.day-card').forEach(dc => {
                const dayId = dc.id ? dc.id.replace('day','') : '';
                const activities = [];
                dc.querySelectorAll('.activity-item').forEach(ai => {
                  activities.push({ id: ai.dataset.id || undefined, name: ai.querySelector('.activity-title')?.textContent || ai.dataset.title || '' });
                });
                rebuilt.push({ day: dayId, spots: activities });
              });
              window.currentItinerary = rebuilt;
            }
          } catch (err) {
            console.warn('Init timeline handlers failed', err);
          }
        });
      })();
    </script>

    <!-- Itinerary History modal population -->
    <script>
    (function () {
      const historyBtn = document.getElementById('historyBtn');
      const modal = document.getElementById('tripsHistoryModal');
      const historyContent = document.getElementById('historyContent');
      
      if (!historyBtn || !modal || !historyContent) return;

      historyBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
          const response = await fetch('/itinerary/list');
          if (!response.ok) throw new Error('Failed to fetch trips');
          const data = await response.json();

          if (!data.trips || !data.trips.length) {
            historyContent.innerHTML = `
              <div class="text-center py-4" style="color:#6c757d;">
                <i class="bi bi-inbox" style="font-size:3rem;opacity:.5;"></i>
                <p class="mt-2 mb-0" style="font-weight:600;">No saved trips found</p>
                <p class="small">Create your first itinerary to see it here</p>
              </div>
            `;
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            return;
          }

          // Build trips HTML
          let tripsHtml = '';
          data.trips.forEach((trip, idx) => {
            const startDate = new Date(trip.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const endDate = new Date(trip.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            tripsHtml += `
              <div class="history-trip-card" data-trip-index="${idx}" style="position:relative;background:rgba(255,255,255,0.55);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(0,75,141,0.20);border-radius:18px;padding:16px 18px;overflow:hidden;box-shadow:0 8px 26px -8px rgba(0,46,85,0.35);transition:.3s;">
                <div style="position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,0.10),rgba(0,46,85,0.06));pointer-events:none;"></div>
                <div class="d-flex justify-content-between align-items-start position-relative" style="z-index:2;">
                  <div>
                    <h6 class="mb-1" style="font-weight:700;color:#003a6e;letter-spacing:.3px;">${trip.trip_title || 'Untitled Trip'}</h6>
                    <div class="d-flex flex-wrap gap-2 mt-1" style="font-size:.7rem;font-weight:600;color:#004b8d;letter-spacing:.4px;">
                      <span style="background:rgba(0,75,141,0.08);padding:4px 8px;border-radius:12px;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-calendar"></i> ${startDate} – ${endDate}</span>
                      <span style="background:rgba(0,75,141,0.08);padding:4px 8px;border-radius:12px;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-geo-alt"></i> ${trip.spot_count || 0} spots</span>
                      <span style="background:rgba(0,75,141,0.08);padding:4px 8px;border-radius:12px;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-clock"></i> Created ${new Date(trip.created_at).toLocaleDateString()}</span>
                    </div>
                  </div>
                  <div class="btn-group btn-group-sm" style="z-index:3;">
                    <button class="btn btn-outline-primary btn-view-trip" data-trip-title="${encodeURIComponent(trip.trip_title)}" data-start-date="${encodeURIComponent(trip.start_date)}">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-delete-trip" data-trip-title="${encodeURIComponent(trip.trip_title)}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            `;
          });

          historyContent.innerHTML = tripsHtml;

          // Show modal
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
                  Swal.fire('Info', `View trip: ${tripTitle} (${startDate})`, 'info');
                }
              });
            });
          }

        } catch (err) {
          console.error('Error loading trips:', err);
          historyContent.innerHTML = `
            <div class="text-center py-4 text-danger">
              <i class="bi bi-exclamation-triangle" style="font-size:3rem;"></i>
              <p class="mt-2 mb-0" style="font-weight:600;">Failed to load trips</p>
              <p class="small">${err.message}</p>
            </div>
          `;
          const bsModal = new bootstrap.Modal(modal);
          bsModal.show();
        }
      });
    })();
    </script>

    <!-- Create New Itinerary with Tourist Spots -->
    <script>
    (function() {
      // Multi-selection state
      let selectedSpots = [];
      let allSpots = [];

      // Load tourist spots when modal opens
      const createModal = document.getElementById('createItineraryModal');
      if (createModal) {
        createModal.addEventListener('shown.bs.modal', async () => {
          await loadTouristSpots();
        });

        // Reset when modal closes
        createModal.addEventListener('hidden.bs.modal', () => {
          selectedSpots = [];
          updateSelectedSpotDetails();
          document.getElementById('createItineraryForm').reset();
          document.querySelectorAll('.spot-card.selected').forEach(card => card.classList.remove('selected'));
        });
      }

      // Load tourist spots from API or database
      async function loadTouristSpots(searchQuery = '') {
        const grid = document.getElementById('recommendedSpotsGrid');
        if (!grid) return;

        try {
          grid.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"></div><p class="mb-0 small mt-2">Loading spots...</p></div>';

          // Replace with your actual API endpoint
          const response = await fetch(`/api/tourist-spots${searchQuery ? '?search=' + encodeURIComponent(searchQuery) : ''}`);
          
          if (!response.ok) {
            // Fallback to sample data if API fails
            allSpots = getSampleSpots();
          } else {
            const data = await response.json();
            allSpots = data.spots || data.data || getSampleSpots();
          }

          renderSpots(allSpots);
        } catch (err) {
          console.error('Error loading spots:', err);
          allSpots = getSampleSpots();
          renderSpots(allSpots);
        }
      }

      // Helper to check if a spot is already selected
      function isSpotSelected(spot) {
        return selectedSpots.some(s => 
          (s.name || s.title) === (spot.name || spot.title) && 
          s.location === spot.location
        );
      }

      // Render spots in the grid
      function renderSpots(spots) {
        const grid = document.getElementById('recommendedSpotsGrid');
        if (!grid) return;

        if (!spots || spots.length === 0) {
          grid.innerHTML = '<div class="text-center text-muted py-3"><i class="bi bi-inbox"></i><p class="mb-0 small">No spots found</p></div>';
          return;
        }

        grid.innerHTML = spots.map((spot, index) => `
          <div class="spot-card${isSpotSelected(spot) ? ' selected' : ''}" data-spot-index="${index}">
            <div class="spot-card-header">
              <h6 class="spot-card-title">${spot.name || spot.title || 'Unnamed Spot'}</h6>
              <span class="spot-card-category">${spot.category || 'Attraction'}</span>
            </div>
            <div class="spot-card-body">
              <div class="mb-1"><i class="bi bi-geo-alt-fill"></i> ${spot.location || 'Location not specified'}</div>
              ${spot.description ? `<p class="mb-0 small">${spot.description.substring(0, 80)}${spot.description.length > 80 ? '...' : ''}</p>` : ''}
            </div>
            <div class="spot-card-footer">
              <span class="spot-price">₱${spot.price_per_person || spot.adult_price || 0}</span>
              <small class="text-muted">per adult</small>
            </div>
          </div>
        `).join('');
        // Attach click handlers (toggle selection)
        grid.querySelectorAll('.spot-card').forEach(card => {
          card.addEventListener('click', () => {
            const index = parseInt(card.dataset.spotIndex);
            toggleSpotSelection(spots[index], card);
          });
        });
      }

      // Toggle selection of a spot
      function toggleSpotSelection(spot, cardElement) {
        const idx = selectedSpots.findIndex(s => 
          (s.name || s.title) === (spot.name || spot.title) && 
          s.location === spot.location
        );
        if (idx > -1) {
          selectedSpots.splice(idx, 1);
          cardElement.classList.remove('selected');
        } else {
          selectedSpots.push(spot);
          cardElement.classList.add('selected');
        }
        updateSelectedSpotDetails();
      }

      // Render selected spot details list & aggregate pricing
      function updateSelectedSpotDetails() {
        const container = document.getElementById('selectedSpotDetails');
        const itemsWrapper = document.getElementById('selectedSpotItems');
        const countEl = document.getElementById('selectedCount');
        const aggregateEl = document.getElementById('aggregatePricing');
        if (!container || !itemsWrapper || !countEl) return;

        countEl.textContent = selectedSpots.length;
        if (selectedSpots.length === 0) {
          container.style.display = 'none';
          itemsWrapper.innerHTML = '';
          if (aggregateEl) aggregateEl.textContent = '';
          return;
        }
        container.style.display = 'block';

        // Current guest counts for dynamic aggregate pricing
        const adults = parseInt(document.getElementById('itAdults')?.value) || 0;
        const children = parseInt(document.getElementById('itChildren')?.value) || 0;
        const seniors = parseInt(document.getElementById('itSeniors')?.value) || 0;

        let totalAdult = 0, totalChild = 0, totalSenior = 0;

        itemsWrapper.innerHTML = selectedSpots.map((spot, i) => {
          const name = spot.name || spot.title || 'Unnamed Spot';
          const cat = spot.category || 'Attraction';
          const loc = spot.location || 'Location not specified';
          const pAdult = Number(spot.price_per_person || spot.adult_price || 0);
          const pChild = Number(spot.child_price || 0);
          const pSenior = Number(spot.senior_price || 0);

          totalAdult += pAdult * adults;
          totalChild += pChild * children;
          totalSenior += pSenior * seniors;

          return `
            <div class="d-flex flex-column gap-1 border-bottom pb-2">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-semibold">${name}</div>
                  <div class="small text-muted">${cat} • ${loc}</div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-index="${i}"><i class="bi bi-x"></i></button>
              </div>
              <div class="spot-pricing-tags">
                 <span class="price-pill">Adult: ₱${pAdult}</span>
                 <span class="price-pill">Child: ₱${pChild}</span>
                 <span class="price-pill">Senior: ₱${pSenior}</span>
              </div>
            </div>`;
        }).join('');

        if (aggregateEl) {
          const grandTotal = totalAdult + totalChild + totalSenior;
          aggregateEl.innerHTML = `Selected Cost (x Guests): <span class="text-dark">₱${grandTotal.toLocaleString()} (Adult ₱${totalAdult.toLocaleString()} • Child ₱${totalChild.toLocaleString()} • Senior ₱${totalSenior.toLocaleString()})</span>`;
        }

        // Attach remove handlers
        itemsWrapper.querySelectorAll('[data-remove-index]').forEach(btn => {
          btn.addEventListener('click', () => {
            const removeIdx = parseInt(btn.getAttribute('data-remove-index'));
            if (!isNaN(removeIdx)) {
              const spot = selectedSpots[removeIdx];
              selectedSpots.splice(removeIdx, 1);
              document.querySelectorAll('.spot-card').forEach(card => {
                const index = parseInt(card.getAttribute('data-spot-index'));
                if (!isNaN(index) && allSpots[index] === spot) card.classList.remove('selected');
              });
              updateSelectedSpotDetails();
            }
          });
        });
      }

      // Search spots
      const searchBtn = document.getElementById('searchSpotBtn');
      const searchInput = document.getElementById('spotSearchInput');

      if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', () => {
          const query = searchInput.value.trim();
          if (query) {
            const filtered = allSpots.filter(spot => {
              const searchText = `${spot.name} ${spot.category} ${spot.location} ${spot.description}`.toLowerCase();
              return searchText.includes(query.toLowerCase());
            });
            renderSpots(filtered);
          } else {
            renderSpots(allSpots);
          }
        });

        searchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            e.preventDefault();
            searchBtn.click();
          }
        });
      }

      // Clear spot selection
      const clearBtn = document.getElementById('clearSpotBtn');
      if (clearBtn) {
        clearBtn.addEventListener('click', () => {
          selectedSpots = [];
          document.querySelectorAll('.spot-card.selected').forEach(c => c.classList.remove('selected'));
          updateSelectedSpotDetails();
        });
      }

      // Function to get disabled dates from existing itineraries
      async function getDisabledDates() {
        try {
          const response = await fetch('/itinerary/list');
          if (!response.ok) return [];
          const data = await response.json();
          if (!data.trips || !Array.isArray(data.trips)) return [];
          
          const disabledRanges = [];
          data.trips.forEach(trip => {
            if (trip.start_date && trip.end_date) {
              disabledRanges.push({
                from: new Date(trip.start_date),
                to: new Date(trip.end_date)
              });
            }
          });
          return disabledRanges;
        } catch (err) {
          console.error('Failed to fetch disabled dates:', err);
          return [];
        }
      }

      // Function to refresh disabled dates in both Flatpickr instances
      async function refreshDisabledDates() {
        const disabledRanges = await getDisabledDates();
        
        // Refresh the newTripDateRange picker
        const dateRangeInput = document.getElementById('newTripDateRange');
        if (dateRangeInput && dateRangeInput._flatpickr) {
          dateRangeInput._flatpickr.set('disable', disabledRanges);
        }
        // (auto-gen date range removed; unified in create modal)
      }

      // Store references to Flatpickr instances for real-time updates
      let dateRangePickerInstance;

      // Initialize Flatpickr range for Create New Itinerary
      const dateRangeInput = document.getElementById('newTripDateRange');
      if (dateRangeInput && window.flatpickr) {
        getDisabledDates().then(disabledRanges => {
          dateRangePickerInstance = flatpickr(dateRangeInput, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disable: disabledRanges,
            onChange: function(selectedDates) {
              const startEl = document.getElementById('newTripStart');
              const endEl = document.getElementById('newTripEnd');
              if (selectedDates.length > 0 && startEl) {
                const date = selectedDates[0];
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                startEl.value = `${year}-${month}-${day}`;
              }
              if (selectedDates.length > 1 && endEl) {
                const date = selectedDates[1];
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                endEl.value = `${year}-${month}-${day}`;
              }
              updateSelectedSpotDetails(); // Recompute if date impacts pricing later
            }
          });
        });
      }

      // (Removed: auto-generate modal picker and guest spinner; handled in unified create modal)

      // Guest spinner buttons for itinerary create modal
      document.querySelectorAll('#createItineraryModal .btn-guest').forEach(btn => {
        btn.addEventListener('click', () => {
          const targetId = btn.getAttribute('data-target');
          const input = document.getElementById(targetId);
          if (!input) return;
          let val = parseInt(input.value) || 0;
          if (btn.classList.contains('btn-guest-minus')) {
            val = Math.max(0, val - 1);
          } else if (btn.classList.contains('btn-guest-plus')) {
            val = val + 1;
          }
          input.value = val;
          updateSelectedSpotDetails();
        });
      });

      // Create itinerary button
      const createBtn = document.getElementById('createItineraryBtn');
      if (createBtn) {
        createBtn.addEventListener('click', async (e) => {
          e.preventDefault();
          const form = document.getElementById('createItineraryForm');

          // If we have a generated preview, save that instead of manual
          if (window.__hasGeneratedPreview && window.__lastItineraryRequest) {
            try {
              // Build save URL for generated itinerary
              const req = window.__lastItineraryRequest;
              const params = new URLSearchParams({
                days: req.days,
                budget: req.budget,
                adults: req.adults,
                children: req.children,
                seniors: req.seniors,
                preference: req.preference || '',
                user_id: req.user_id || '',
                save: '1',
                trip_title: req.trip_title || '',
                start_date: req.start_date || '',
                end_date: req.end_date || ''
              });
              const isLocal = ['localhost','127.0.0.1'].includes(window.location.hostname);
              const saveBase = isLocal ? 'http://127.0.0.1:8000/api/recommend/' : 'https://tuklasnasugbu.com/dj/api/recommend/';
              const saveUrl = `${saveBase}?${params.toString()}`;

              const resp = await fetch(saveUrl);
              if (!resp.ok) throw new Error('Save request failed');
              const saveData = await resp.json();
              if (saveData.saved) {
                const count = saveData.saved_count || 0;
                Swal.fire('Success', `Itinerary saved to your trips (${count} items).`, 'success');
                // Refresh disabled dates
                try { await refreshDisabledDates(); } catch(e){}
                // Close modal
                bootstrap.Modal.getInstance(createModal).hide();
                // Load saved trip into timeline
                setTimeout(async () => {
                  try {
                    const res = await fetch(`/itinerary/get?trip_title=${encodeURIComponent(req.trip_title)}&start_date=${encodeURIComponent(req.start_date)}`);
                    if (res.ok) {
                      const json = await res.json();
                      if (typeof window.__renderTripItinerary === 'function') window.__renderTripItinerary(json);
                    }
                  } catch (err) { console.warn('Failed to auto-load saved trip', err); }
                }, 400);
              } else {
                const errMsg = saveData.saved_error || 'Unknown error while saving';
                Swal.fire('Failed', 'Failed to save itinerary: ' + errMsg, 'error');
              }
            } catch (err) {
              console.error('Save generated itinerary error', err);
              Swal.fire('Error', 'Failed to save generated itinerary: ' + err.message, 'error');
            }
            return; // Do not continue to manual flow
          }

          // Manual flow validation
          if (!form.checkValidity()) {
            form.reportValidity();
            return;
          }

          // Build form data and ensure start/end dates are present.
          let startVal = document.getElementById('newTripStart').value || '';
          let endVal = document.getElementById('newTripEnd').value || '';
          // Fallback: try to parse visible date range input if hidden fields empty
          if ((!startVal || !endVal) && document.getElementById('newTripDateRange')) {
            const dr = document.getElementById('newTripDateRange').value || '';
            if (dr && dr.indexOf(' to ') !== -1) {
              const parts = dr.split(' to ').map(s => s.trim());
              if (parts[0]) startVal = startVal || parts[0];
              if (parts[1]) endVal = endVal || parts[1];
            } else if (dr) {
              // single date selected, use it for both
              startVal = startVal || dr;
              endVal = endVal || dr;
            }
          }

          const formData = {
            title: document.getElementById('newTripTitle').value,
            start_date: startVal,
            end_date: endVal,
            adults: parseInt(document.getElementById('itAdults').value) || 0,
            children: parseInt(document.getElementById('itChildren').value) || 0,
            seniors: parseInt(document.getElementById('itSeniors').value) || 0,
            selected_spots: selectedSpots.map(s => ({
              name: s.name || s.spot_name || s.title || 'Unnamed Spot',
              category: s.category || 'Attraction',
              location: s.location || '',
              price_per_person: s.price_per_person || s.adult_price || 0,
              child_price: s.child_price || 0,
              senior_price: s.senior_price || 0
            }))
          };

          try {
            console.log('Creating itinerary with data:', formData);
            const resp = await fetch('/itinerary/create', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(formData)
            });
            const data = await resp.json();
            if (!resp.ok) {
              console.error('Failed creating itinerary', data);
              Swal.fire('Failed', 'Failed to create itinerary: ' + (data.error || resp.statusText), 'error');
              return;
            }

            if (data.success) {
              // Refresh disabled dates in calendar pickers
              try { await refreshDisabledDates(); } catch(e){}
              // Close modal
              bootstrap.Modal.getInstance(createModal).hide();
              // Try to reload/display the saved trip in the timeline (if loader exists)
              if (typeof window.loadSavedTrip === 'function') {
                try { window.loadSavedTrip(formData.title, formData.start_date); } catch(e) { console.warn('loadSavedTrip failed', e); }
              } else {
                // fallback: reload page
                window.location.reload();
              }
            } else {
              console.warn('Create itinerary returned:', data);
              Swal.fire('Error', 'Could not create itinerary. Check console for details.', 'error');
            }
          } catch (err) {
            console.error('Create itinerary error', err);
            Swal.fire('Error', 'An error occurred while creating itinerary', 'error');
          }
        });
      }

      // Sample spots data (fallback)
      function getSampleSpots() {
        return [
          {
            name: 'Mount Batulao',
            category: 'Mountain',
            location: 'Nasugbu, Batangas',
            description: 'A popular hiking destination with stunning views and accessible trails for beginners and experienced hikers.',
            price_per_person: 50,
            child_price: 30,
            senior_price: 40
          },
          {
            name: 'Fortune Island',
            category: 'Beach',
            location: 'Nasugbu, Batangas',
            description: 'An island paradise featuring Greek-inspired ruins, clear waters, and pristine beaches perfect for swimming and photography.',
            price_per_person: 500,
            child_price: 300,
            senior_price: 400
          },
          {
            name: 'Punta Fuego',
            category: 'Beach Resort',
            location: 'Nasugbu, Batangas',
            description: 'An exclusive beach club with luxurious amenities, white sand beaches, and crystal-clear waters.',
            price_per_person: 1500,
            child_price: 1000,
            senior_price: 1200
          },
          {
            name: 'Calayo Beach',
            category: 'Beach',
            location: 'Calayo, Nasugbu',
            description: 'A beautiful public beach with golden sand, gentle waves, and affordable entrance fees.',
            price_per_person: 50,
            child_price: 30,
            senior_price: 40
          },
          {
            name: 'Tali Beach',
            category: 'Beach',
            location: 'Nasugbu, Batangas',
            description: 'A long stretch of white sand beach ideal for swimming, beach volleyball, and sunset viewing.',
            price_per_person: 100,
            child_price: 50,
            senior_price: 80
          },
          {
            name: 'Munting Buhangin',
            category: 'Beach',
            location: 'Nasugbu, Batangas',
            description: 'A hidden gem beach with fine white sand and calm turquoise waters, perfect for relaxation.',
            price_per_person: 200,
            child_price: 150,
            senior_price: 180
          }
        ];
      }
    })();
    </script>

    <!-- Add Day Modal Handler -->
    <script>
      function openAddDayModal() {
        const modal = document.getElementById('addDayModal');
        if (modal) {
          const bsModal = new bootstrap.Modal(modal);
          bsModal.show();
        }
      }

      // Toggle expand/collapse for a day card
      function toggleDay(id) {
        try {
          const el = document.getElementById(id);
          if (!el) return;
          const content = el.querySelector('.day-content');
          const icon = el.querySelector('.collapse-icon');
          if (!content) return;
          const isHidden = getComputedStyle(content).display === 'none' || content.classList.contains('collapsed');
          if (isHidden) {
            content.style.display = '';
            content.classList.remove('collapsed');
            if (icon) { icon.classList.remove('bi-chevron-up'); icon.classList.add('bi-chevron-down'); }
          } else {
            content.style.display = 'none';
            content.classList.add('collapsed');
            if (icon) { icon.classList.remove('bi-chevron-down'); icon.classList.add('bi-chevron-up'); }
          }
        } catch (err) {
          console.warn('toggleDay failed', err);
        }
      }

      // Handler to actually add a new day card into the timeline
      (function(){
        const addBtn = document.getElementById('addDayBtn');
        if (!addBtn) return;
        addBtn.addEventListener('click', (e) => {
          try {
            const input = document.getElementById('newDayNumber');
            if (!input) { Swal.fire('Error', 'Day input not found', 'error'); return; }
            const count = parseInt(input.value, 10);
            if (!count || count < 1) { Swal.fire('Invalid Input', 'Please enter a valid number of days to add (>= 1)', 'warning'); return; }

            // Find last existing day number
            const dayCards = Array.from(document.querySelectorAll('.day-card'));
            let maxDay = 0;
            dayCards.forEach(dc => {
              const match = (dc.id || '').match(/day(\d+)/);
              if (match) {
                const n = parseInt(match[1], 10);
                if (!isNaN(n) && n > maxDay) maxDay = n;
              }
            });

            const timeline = document.querySelector('.timeline-section');
            if (!timeline) { Swal.fire('Error', 'Timeline section not found', 'error'); return; }

            const newDays = [];
            for (let i = 1; i <= count; i++) {
              const num = maxDay + i;
              // Build simple day card markup
              const card = document.createElement('div');
              card.className = 'day-card';
              card.id = 'day' + num;
              card.innerHTML = `
                <div class="day-header" onclick="toggleDay('day${num}')">
                  <div class="day-header-left">
                    <div class="day-number">Day ${num}</div>
                    <div class="day-date">To be set</div>
                  </div>
                  <div class="day-header-right">
                    <div class="day-stats">
                      <div class="day-stat"><i class="bi bi-geo-alt"></i><span>0 places</span></div>
                      <div class="day-stat"><i class="bi bi-cash-stack"></i><span>₱0</span></div>
                    </div>
                    <i class="bi bi-chevron-down collapse-icon"></i>
                  </div>
                </div>
                <div class="day-content">
                  <div class="activities-list" data-day="${num}"></div>
                  <button class="add-activity-btn" data-day="${num}"><i class="bi bi-plus-circle"></i> Add Activity</button>
                </div>
              `;

              // Insert before the Book Itinerary wrapper if present, otherwise append at end
              const bookWrapper = timeline.querySelector('.book-itinerary-wrapper');
              if (bookWrapper) timeline.insertBefore(card, bookWrapper);
              else timeline.appendChild(card);

              // Initialize Sortable for the new list (if Sortable available)
              const list = card.querySelector('.activities-list');
              if (window.Sortable && list) {
                Sortable.create(list, { group: 'days', animation: 150, handle: '.activity-drag-handle' });
              }

              // Attach Add Activity click handler for the new day's Add button
              const addActBtn = card.querySelector('.add-activity-btn');
              if (addActBtn) {
                addActBtn.addEventListener('click', (ev) => {
                  const day = addActBtn.getAttribute('data-day') || '';
                  openAddActivityModalForDay(day);
                });
              }

              // Track for in-memory itinerary
              newDays.push({ day: String(num), spots: [] });
            }

            // Update in-memory itinerary
            window.currentItinerary = window.currentItinerary || [];
            window.currentItinerary = window.currentItinerary.concat(newDays);
            console.log('Added days', newDays.map(d=>d.day), window.currentItinerary);
            // Update map controls (day selector) and refresh markers/route immediately
            try {
              if (typeof window.__updateMapControls === 'function') window.__updateMapControls();
              if (typeof window.__updateMapMarkers === 'function') window.__updateMapMarkers(window.currentItinerary, document.getElementById('mapDayFilter')?.value || 'all');
            } catch(e) { console.warn('map refresh after add days failed', e); }
          } catch (err) {
            console.error('Failed to add day(s)', err);
            Swal.fire('Error', 'Could not add day(s): ' + (err && err.message), 'error');
          }
        });
      })();
    </script>

    <!-- Notification Detail Modal (opens when clicking a notification) -->
    <div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-bell-fill"></i> <span id="notifDetailTitle">Notification</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p id="notifDetailMessage" style="font-size:1rem;color:#333;margin-bottom:1rem;"></p>
            <p class="text-muted" style="font-size:0.875rem;margin:0;"><i class="bi bi-clock"></i> <span id="notifDetailTime"></span></p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" data-bs-dismiss="modal">Take Action</button>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>