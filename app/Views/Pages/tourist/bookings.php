<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS (for icons and some base styles) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Global CSS (Unified Sidebar) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/globals.css')?>">
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?=base_url('assets/css/touristStyle/bookings.css')?>">
    <style>
        /* Enhanced Booking Details Modal Styling (mirrors Explore view details) */
        .booking-details-modal-header{background:linear-gradient(135deg,#004b8d 0%,#002e55 50%,#000814 100%);color:#fff;border:none;}
        .booking-details-modal-header h5{display:flex;align-items:center;gap:.5rem;font-weight:600;}
        .booking-detail-carousel{position:relative;margin-bottom:1rem;}
        .booking-detail-carousel .carousel-item{overflow:hidden;border-radius:16px;}
        .booking-detail-carousel .carousel-item:after{content:"";position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.15),rgba(0,46,85,.55));opacity:.55;pointer-events:none;mix-blend-mode:overlay;}
        .booking-detail-img{width:100%;height:100%;object-fit:cover;max-height:240px;border-radius:16px;box-shadow:0 6px 20px -8px rgba(0,46,85,.4);}
        @keyframes kbSlow {0%{transform:scale(1) translate(0,0);}50%{transform:scale(1.06) translate(2%, -2%);}100%{transform:scale(1.1) translate(-2%,2%);} }
        .booking-detail-img.kb{animation:kbSlow 18s ease-in-out infinite alternate;}
        .booking-desc-box{background:rgba(255,255,255,.5);backdrop-filter:blur(12px);border:1px solid rgba(0,75,141,.15);border-radius:18px;padding:16px 18px;position:relative;overflow:hidden;box-shadow:0 6px 20px -8px rgba(0,46,85,.3);margin-bottom:1rem;}
        .booking-desc-box:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 25% 20%,rgba(0,75,141,.12),transparent 60%);pointer-events:none;}
        .booking-meta-chips{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:1rem;}
        .booking-meta-chip{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;font-size:.70rem;font-weight:600;padding:8px 14px;border-radius:40px;letter-spacing:.5px;box-shadow:0 4px 14px -4px rgba(0,46,85,.5);}
        .booking-meta-chip.category{background:linear-gradient(135deg,#0072c6,#004b8d);}
        .booking-meta-chip.status{background:linear-gradient(135deg,#ffb400,#ff7a18);}
        .booking-details-panel{background:rgba(255,255,255,.35);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.55);border-radius:18px;padding:16px;box-shadow:0 8px 28px -8px rgba(0,46,85,.35);max-height:280px;overflow-y:auto;position:relative;}
        .booking-details-panel:before{content:"";position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,.08),rgba(0,46,85,.06));pointer-events:none;}
        .booking-detail-grid{display:flex;flex-direction:column;gap:14px;}
        .booking-detail-item-card{display:flex;gap:14px;align-items:flex-start;background:rgba(255,255,255,.55);border:1px solid rgba(0,75,141,.12);padding:12px 14px;border-radius:14px;position:relative;transition:.25s;}
        .booking-detail-item-card:hover{background:rgba(255,255,255,.75);box-shadow:0 6px 18px -6px rgba(0,46,85,.25);}
        .booking-detail-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.35rem;color:#fff;background:linear-gradient(135deg,#004b8d,#002e55);box-shadow:0 4px 14px -4px rgba(0,46,85,.5);}
        .booking-detail-content{flex:1;display:flex;flex-direction:column;gap:4px;}
        .booking-detail-label{font-size:.70rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#003a6e;opacity:.85;}
        .booking-detail-value{font-size:.9rem;color:#0b2236;font-weight:500;}
        .booking-action-bar{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px;}
        .booking-action-bar .btn{display:inline-flex;align-items:center;gap:6px;}
        @media (max-width:768px){.booking-detail-img{max-height:220px;} .booking-detail-icon{width:40px;height:40px;font-size:1.1rem;} .booking-detail-item-card{padding:10px 12px;} }
            /* Two-column layout for booking details + payment panel */
            .booking-two-col{display:flex;flex-wrap:nowrap;gap:20px;align-items:flex-start;max-width:900px;margin:0 auto;}
            .booking-main{flex:1 1 50%;min-width:280px;}
            .payment-panel{flex:0 0 35%;min-width:260px;max-width:340px;background:rgba(255,255,255,.6);backdrop-filter:blur(14px);border:1px solid rgba(0,75,141,.15);border-radius:18px;padding:16px 18px;position:sticky;top:0;box-shadow:0 8px 28px -10px rgba(0,46,85,.35);display:none;flex-direction:column;gap:14px;opacity:0;transform:translateX(20px);transition:opacity .4s ease, transform .4s ease;}
            .payment-panel.show{display:flex;opacity:1;transform:translateX(0);}
            .payment-panel:before{content:"";position:absolute;inset:0;border-radius:18px;background:linear-gradient(145deg,rgba(0,75,141,.08),rgba(0,46,85,.06));pointer-events:none;}
            .payment-panel h6{display:flex;align-items:center;gap:.55rem;font-weight:600;margin:0;color:#003a6e;}
            .payment-summary-lines{display:flex;flex-direction:column;gap:6px;margin-top:8px;}
            .payment-summary-line{display:flex;justify-content:space-between;font-size:.75rem;background:#ffffff;border:1px solid #dbe4ea;padding:6px 10px;border-radius:10px;}
            .payment-summary-line .label{color:#334155;font-weight:500;}
            .payment-summary-line .value{color:#0f172a;font-weight:600;}
            .payment-total-row{display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-radius:12px;background:linear-gradient(135deg,#004b8d,#002e55);color:#fff;font-weight:600;}
            .payment-methods{display:flex;flex-wrap:wrap;gap:10px;}
            .payment-method{flex:1 1 120px;min-width:120px;background:#ffffff;border:1px solid #d5e4ee;border-radius:14px;padding:10px;cursor:pointer;display:flex;align-items:center;gap:8px;font-size:.65rem;font-weight:600;color:#003a6e;transition:.25s;}
            .payment-method:hover{background:#f6fbff;box-shadow:0 4px 12px -4px rgba(0,46,85,.25);}
            .payment-method.active{border-color:#004b8d;box-shadow:0 0 0 3px rgba(0,75,141,.15);background:#ffffff;}
            .payment-method i{font-size:1.05rem;color:#004b8d;}
            .payment-notes{resize:vertical;min-height:70px;font-size:.8rem;}
            .inline-payment-actions{display:flex;flex-direction:column;gap:10px;}
            .btn-inline-pay{background:linear-gradient(90deg,#004b8d,#012c4a);border:none;color:#fff;font-weight:600;}
            .btn-inline-pay:hover{background:linear-gradient(90deg,#005fae,#003a6e);} 
            .btn-inline-cancel{background:linear-gradient(135deg,#c90026,#6d0014);border:none;color:#fff;font-weight:600;}
            .btn-inline-cancel:hover{background:linear-gradient(135deg,#ff002e,#8c001d);} 
            @media (max-width:992px){.booking-two-col{flex-direction:column;flex-wrap:wrap;} .payment-panel{flex:1 1 100%;position:relative;} }
            /* Cancellation popup retained */
            .cancel-reason-box{background:rgba(255,255,255,.55);border:1px solid #d5e4ee;border-radius:14px;padding:14px;}
            .cancel-warning{display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#ffb400,#ff7a18);color:#fff;padding:10px 14px;border-radius:14px;font-size:.8rem;font-weight:600;box-shadow:0 4px 12px -4px rgba(0,0,0,.35);margin-bottom:14px;}
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar (animated, unified) -->
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
                        <a href="/tourist/itinerary" class="tourist-nav-link">
                            <i class="bi bi-calendar-check"></i>
                            <span class="tourist-nav-link-text">My Itinerary</span>
                        </a>
                    </li>
                    <li class="tourist-nav-item">
                        <a href="/tourist/myBookings" class="tourist-nav-link active">
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
                    <li class="tourist-nav-item">
                        <!-- My Reviews link removed from bookings sidebar -->
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Ocean Wave Header -->
            <div class="page-header">
                <div class="page-header-actions">
                    <!-- Notification -->
                    <div style="position: relative;">
                        <button class="notification-btn" onclick="toggleNotificationDropdown()">
                            <i class="bi bi-bell-fill"></i>
                            <span class="notification-badge" id="notifBadge" style="display:none">0</span>
                        </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
                        </div>
                        <ul class="notification-list" id="notificationList"></ul>
                        <div class="notification-footer">
                            <a href="#" onclick="viewAllNotifications(event)">View all</a>
                        </div>
                    </div>
                </div>

                <!-- Avatar -->
                <div style="position: relative;">
                    <?php $session = session(); $userFirstName = $session->get('FirstName') ?? ''; $userLastName = $session->get('LastName') ?? ''; $userEmail = $session->get('Email') ?? ''; $userInitials = strtoupper(substr($userFirstName,0,1).substr($userLastName,0,1)); ?>
                    <div class="user-avatar" onclick="toggleUserDropdown()"><?= esc($userInitials ?: 'JD') ?></div>
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
            <h2><i class="bi bi-ticket-perforated-fill"></i> My Bookings</h2>
            <p>Manage and track your reservations</p>
        </div>

            <div class="bookings-container">
                <!-- Filter Tabs and View Toggle -->
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
                    <div class="filter-tabs" style="margin-bottom:0;">
                        <button class="filter-tab active" onclick="filterBookings('all', this)">All Bookings</button>
                        <button class="filter-tab" onclick="filterBookings('confirmed', this)">Confirmed</button>
                        <button class="filter-tab" onclick="filterBookings('pending', this)">Pending</button>
                        <button class="filter-tab" onclick="filterBookings('completed', this)">Completed</button>
                        <button class="filter-tab" onclick="filterBookings('cancelled', this)">Cancelled</button>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="view-toggle" style="display:flex;gap:0.5rem;">
                        <button class="view-btn active" onclick="switchView('timeline')" data-view="timeline" title="Timeline View">
                            <i class="bi bi-list-ul"></i> Timeline
                        </button>
                        <button class="view-btn" onclick="switchView('grid')" data-view="grid" title="Grid View">
                            <i class="bi bi-grid-3x3-gap"></i> Grid
                        </button>
                    </div>
                </div>

                <!-- Booking Cards Container -->
                <div id="bookingsList">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): 
                        $status = strtolower($booking['booking_status'] ?? '');
                    ?>
                        <div class="booking-card"
                             data-status="<?= esc($status) ?>"
                             data-booking-id="<?= esc($booking['booking_id'] ?? '') ?>"
                             data-spot-id="<?= esc($booking['spot_id'] ?? '') ?>"
                             data-customer-id="<?= esc($booking['customer_id'] ?? session()->get('UserID')) ?>">
                            <div class="booking-header">
                                <div>
                                    <h3 class="booking-title"><?= esc($booking['spot_name'] ?? 'Tourist Spot') ?></h3>
                                    <span class="booking-type tour"><i class="bi bi-compass"></i> <?= esc($booking['category'] ?? 'Tour') ?></span>
                                </div>
                                <span class="booking-status <?= esc($status) ?>">
                                    <?= esc(ucfirst($status)) ?>
                                </span>
                            </div>
                            <div class="booking-details">
                                <div class="booking-detail-item"><div class="booking-icon"><i class="bi bi-calendar-event"></i></div><div class="booking-detail-content"><h4>Visit Date</h4><p><?= esc(date('M d, Y', strtotime($booking['visit_date']))) ?></p></div></div>
                                <div class="booking-detail-item"><div class="booking-icon"><i class="bi bi-clock"></i></div><div class="booking-detail-content"><h4>Time</h4><p><?= esc($booking['visit_time'] ?? 'N/A') ?></p></div></div>
                                <div class="booking-detail-item"><div class="booking-icon"><i class="bi bi-people"></i></div><div class="booking-detail-content"><h4>Guests</h4><p><?= esc($booking['num_adults']) ?> Adults, <?= esc($booking['num_children']) ?> Children, <?= esc($booking['num_seniors']) ?> Seniors</p></div></div>
                                <div class="booking-detail-item"><div class="booking-icon"><i class="bi bi-cash-stack"></i></div><div class="booking-detail-content"><h4>Total Cost</h4><p>₱<?= number_format($booking['total_price'], 2) ?></p></div></div>
                            </div>
                            <div class="booking-actions">
                                <button class="btn-booking ocean" onclick="viewBookingDetails(this)"><i class="bi bi-eye"></i> View Details</button>

                                <?php if ($status === 'pending'): ?>
                                    <button class="btn-booking light" onclick="cancelBooking(this)"><i class="bi bi-x-circle"></i> Cancel</button>
                                <?php elseif ($status === 'confirmed'): ?>
                                    <button class="btn-booking light" onclick="downloadCheckinQr(this)"><i class="bi bi-download"></i> Download Check-in QR</button>
                                    <button class="btn-booking light" onclick="showCheckinQrModal(this)"><i class="bi bi-qr-code"></i> Show Check-in QR</button>
                                <?php elseif ($status === 'completed'): ?>
                                    <button class="btn-booking light" onclick="bookAgain()"><i class="bi bi-arrow-repeat"></i> Book Again</button>
                                    <button class="btn-booking ocean" onclick="writeReview()"><i class="bi bi-star"></i> Write Review</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h3>No Bookings Yet</h3>
                        <p>You haven't made any reservations. Start exploring amazing destinations in Nasugbu!</p>
                        <a href="/tourist/exploreSpots" class="btn-explore">
                            <i class="bi bi-compass"></i> Explore Spots
                        </a>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- My Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="profileForm">
                    <div class="modal-header">
                        <h5 class="modal-title">My Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="profile-avatar-large" id="profileAvatar">
                                JD
                                <label class="avatar-upload-btn">
                                    <i class="bi bi-camera-fill"></i>
                                    <input type="file" id="avatarUpload" accept="image/*">
                                </label>
                            </div>
                            <small class="text-muted">Click the camera icon to change profile picture</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profileFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="profileFirstName" value="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="profileLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="profileLastName" value="Dela Cruz" required>
                            </div>
                            <div class="col-12">
                                <label for="profileEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="profileEmail" value="juan.delacruz@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="profilePhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="profilePhone" value="+63 912 345 6789">
                            </div>
                            <div class="col-md-6">
                                <label for="profileBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="profileBirthdate" value="1995-05-15">
                            </div>
                            <div class="col-12">
                                <label for="profileAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="profileAddress" rows="2">Nasugbu, Batangas, Philippines</textarea>
                            </div>
                            <div class="col-12">
                                <label for="profileBio" class="form-label">Bio</label>
                                <textarea class="form-control" id="profileBio" rows="3" placeholder="Tell us about yourself...">Adventure seeker and travel enthusiast exploring Nasugbu!</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Change Password</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="profileSaveBtn">
                            <span class="save-text">Save Changes</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toasts -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:800px;">
        <div class="modal-content">
                    <div class="modal-header booking-details-modal-header">
                        <h5 class="modal-title" id="bookingModalLabel"><i class="bi bi-compass"></i> Booking Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
                    <div class="modal-body" id="bookingModalBody">
                        <!-- Dynamic content injected by JS -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

        <!-- Removed separate payment modal: inline side panel used instead -->

        <!-- Cancel Booking Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header gradient-modal-header">
                        <h5 class="modal-title"><i class="bi bi-x-circle"></i> Cancel Booking</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cancel-warning"><i class="bi bi-exclamation-triangle-fill"></i><span>Cancellation may be irreversible. Provide a reason to proceed.</span></div>
                        <div class="cancel-reason-box">
                            <label class="form-label fw-semibold">Reason for Cancellation</label>
                            <textarea class="form-control" id="cancelReason" rows="4" placeholder="e.g., Change of plans, duplicated booking..."></textarea>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted" id="cancelSummaryInfo">Booking # --</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-booking light" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Keep Booking</button>
                        <button type="button" class="btn-booking ocean" id="confirmCancelBtn"><i class="bi bi-trash"></i> Confirm Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Check-in QR Modal (for preview and download) -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Check-in QR</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center" id="qrModalBody">
            <div id="qrContainer" style="display:flex;justify-content:center;"></div>
            <p class="small text-muted mt-2" id="qrPayloadPreview"></p>
          </div>
          <div class="modal-footer">
            <button id="downloadQrBtn" class="btn btn-primary">Download QR</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/tourist-ui.js') ?>"></script>

    <!-- qrcode library (browser build) -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
     
    <script>
        // Keep track of current booking used by modal actions (when opened from the details modal)
        let currentBookingData = null;

        // Helper: read booking data from a booking card element (button passed inside card)
        function readBookingDataFromButton(button) {
            const card = button?.closest ? button.closest('.booking-card') : null;
            if (!card) return null;
            return {
                booking_id: card.getAttribute('data-booking-id') || '',
                spot_id: card.getAttribute('data-spot-id') || '',
                customer_id: card.getAttribute('data-customer-id') || '',
                status: card.getAttribute('data-status') || ''
            };
        }

        // If PayMongo server-side session is configured, expose flag and hide local payment options
        <?php if (getenv('PAYMONGO_SECRET_KEY') || getenv('PAYMONGO_SECRET')): ?>
        (function(){
            try {
                document.documentElement.classList.add('paymongo-hosted');
                window.PAYMONGO_HOSTED = true;
                // Hide local payment method buttons
                try { document.querySelectorAll('.payment-method').forEach(m=>m.style.display='none'); } catch(e) {}
            } catch (e) {}
        })();
        <?php else: ?>
        window.PAYMONGO_HOSTED = false;
        <?php endif; ?>

        // Format ISO expiry to user-friendly local string and relative time
        function formatExpiryInfo(isoString) {
            try {
                const expiresAt = new Date(isoString);
                if (isNaN(expiresAt.getTime())) return { text: 'Invalid expiry', isExpired: true };

                // Local readable string
                const local = expiresAt.toLocaleString(undefined, {
                    year: 'numeric', month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                // relative time
                const ms = expiresAt.getTime() - Date.now();
                const absMs = Math.abs(ms);
                const rtf = new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' });

                let relative;
                const minute = 60000, hour = 3600000, day = 86400000;
                if (absMs < 60000) {
                    relative = ms >= 0 ? 'in a few seconds' : 'a few seconds ago';
                } else if (absMs < hour) {
                    relative = rtf.format(Math.round(ms / minute), 'minute');
                } else if (absMs < day) {
                    relative = rtf.format(Math.round(ms / hour), 'hour');
                } else {
                    relative = rtf.format(Math.round(ms / day), 'day');
                }

                return {
                    text: `${local} (${relative})`,
                    isExpired: ms < 0
                };
            } catch (e) {
                return { text: isoString, isExpired: false };
            }
        }

        // Sidebar toggle + close on outside click (mobile)
        function toggleSidebar() {
          const sidebar = document.getElementById('sidebar');
          sidebar.classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
          const sidebar = document.getElementById('sidebar');
          if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
            const toggleBtn = e.target.closest('.sidebar-toggle');
            if (!toggleBtn && !sidebar.contains(e.target)) sidebar.classList.remove('show');
          }
        });

        function switchView(viewType) {
            const bookingsList = document.getElementById('bookingsList');
            const viewButtons = document.querySelectorAll('.view-btn');
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            event.target.closest('.view-btn').classList.add('active');
            
            // Switch view layout
            if (viewType === 'grid') {
                bookingsList.style.display = 'grid';
                bookingsList.style.gridTemplateColumns = 'repeat(auto-fill, minmax(320px, 1fr))';
                bookingsList.style.gap = '1.5rem';
            } else {
                bookingsList.style.display = 'block';
                bookingsList.style.gridTemplateColumns = '';
                bookingsList.style.gap = '';
            }
        }

        function filterBookings(status, clickedTab) {
            // Update active tab style
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            // Filter the booking cards
            const bookings = document.querySelectorAll('.booking-card');
            bookings.forEach(booking => {
                if (status === 'all' || booking.dataset.status === status) {
                    booking.style.display = 'block';
                } else {
                    booking.style.display = 'none';
                }
            });
        }

        // --- Booking Details modal ---
        async function viewBookingDetails(btn) {
            const card = btn.closest('.booking-card');
            if (!card) return;
            currentBookingData = readBookingDataFromButton(btn);
            const spotId = card.getAttribute('data-spot-id');
            const bookingStatus = card.getAttribute('data-status') || '';
            const title = card.querySelector('.booking-title')?.textContent.trim() || 'Booking Details';

            // Extract basic booking info from existing detail items
            const rawDetails = Array.from(card.querySelectorAll('.booking-detail-item')).map(item => {
                const label = item.querySelector('h4')?.textContent.trim() || '';
                const value = item.querySelector('p')?.textContent.trim() || '';
                return { label, value };
            });

            // Skeleton loading UI
            const modalTitle = document.getElementById('bookingModalLabel');
            const modalBody = document.getElementById('bookingModalBody');
            modalTitle.innerHTML = `<i class="bi bi-compass"></i> ${title}`;
            modalBody.innerHTML = `
                <div class="booking-detail-carousel mb-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:240px;background:#f6f6f6;border-radius:16px;">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <div class="booking-desc-box"><p class="mb-0">Loading spot details...</p></div>
            `;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal'));
            modal.show();

            let spot = null; let gallery = [];
            try {
                if (spotId) {
                    const res = await fetch(`/tourist/viewSpot/${spotId}`);
                    const data = await res.json();
                    if (data && data.spot) {
                        spot = data.spot; gallery = data.gallery || [];
                    }
                }
            } catch(e) {
                // ignore, keep fallback
            }

            // Build carousel HTML
            let carouselHtml = '<div class="booking-detail-carousel">';
            carouselHtml += '<div id="bookingDetailCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">';
            carouselHtml += '<div class="carousel-inner">';
            if (gallery.length) {
                gallery.forEach((img, idx) => {
                    carouselHtml += `<div class="carousel-item ${idx===0?'active':''}"><img src="${img.image_url}" class="booking-detail-img kb" alt="Spot Image"></div>`;
                });
            } else {
                const fallback = spot?.primary_image ? `/uploads/spots/${spot.primary_image}` : '/uploads/spots/Spot-No-Image.png';
                carouselHtml += `<div class="carousel-item active"><img src="${fallback}" onerror="this.src='/uploads/spots/Spot-No-Image.png'" class="booking-detail-img kb" alt="Spot Image"></div>`;
            }
            carouselHtml += '</div>';
            carouselHtml += '<button class="carousel-control-prev" type="button" data-bs-target="#bookingDetailCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>';
            carouselHtml += '<button class="carousel-control-next" type="button" data-bs-target="#bookingDetailCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>';
            carouselHtml += '</div></div>';

            // Description
            const desc = spot?.description || 'No description available for this spot.';
            const category = spot?.category || 'N/A';
            const rating = spot?.rating !== undefined ? spot.rating : '—';

            const metaHtml = `
                <div class="booking-meta-chips">
                    <div class="booking-meta-chip category"><i class="bi bi-tag"></i>${category}</div>
                    <div class="booking-meta-chip status"><i class="bi bi-info-circle"></i>${bookingStatus.charAt(0).toUpperCase()+bookingStatus.slice(1)}</div>
                    <div class="booking-meta-chip"><i class="bi bi-star-fill"></i>${rating}</div>
                </div>
            `;

            // Detailed items combining booking info & spot pricing
            let detailsPanel = '<div class="booking-details-panel"><div class="booking-detail-grid">';
            rawDetails.forEach(d => {
                detailsPanel += `
                    <div class="booking-detail-item-card">
                        <div class="booking-detail-icon"><i class="bi bi-dot"></i></div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">${d.label}</div>
                            <div class="booking-detail-value">${d.value}</div>
                        </div>
                    </div>`;
            });
            if (spot) {
                detailsPanel += `
                    <div class="booking-detail-item-card">
                        <div class="booking-detail-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">Pricing</div>
                            <div class="booking-detail-value">Adult: ${spot.price_per_person?('₱'+parseFloat(spot.price_per_person).toLocaleString()):'—'} | Child: ${spot.child_price?('₱'+parseFloat(spot.child_price).toLocaleString()):'—'} | Senior: ${spot.senior_price?('₱'+parseFloat(spot.senior_price).toLocaleString()):'—'}</div>
                        </div>
                    </div>`;
                detailsPanel += `
                    <div class="booking-detail-item-card">
                        <div class="booking-detail-icon"><i class="bi bi-geo-alt"></i></div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">Location</div>
                            <div class="booking-detail-value">${spot.location || '—'}</div>
                        </div>
                    </div>`;
                detailsPanel += `
                    <div class="booking-detail-item-card">
                        <div class="booking-detail-icon"><i class="bi bi-clock"></i></div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">Visiting Hours</div>
                            <div class="booking-detail-value">${(spot.opening_time||'—')+' - '+(spot.closing_time||'—')}</div>
                        </div>
                    </div>`;
            }
            detailsPanel += '</div></div>';

            // Action buttons similar to old logic
            let actions = '<div class="booking-action-bar">';
            if (bookingStatus === 'confirmed') {
                actions += '<button class="btn btn-primary" onclick="downloadCheckinQr()"><i class="bi bi-download"></i> Download Check-in QR</button>';
                actions += '<button class="btn btn-outline-secondary" onclick="showCheckinQrModal()"><i class="bi bi-qr-code"></i> Show Check-in QR</button>';
            }
            if (bookingStatus === 'pending') {
                actions += '<button class="btn-explore-compact" onclick="completePayment()"><i class="bi bi-credit-card"></i> Complete Payment</button>';
                actions += '<button class="btn-explore-compact light" onclick="cancelBooking()"><i class="bi bi-x-circle"></i> Cancel Booking</button>';
            } else if (bookingStatus === 'completed') {
                actions += '<button class="btn btn-outline-primary" onclick="writeReview()"><i class="bi bi-star"></i> Write Review</button>';
                actions += '<button class="btn btn-secondary" onclick="bookAgain()"><i class="bi bi-arrow-repeat"></i> Book Again</button>';
            }
            actions += '</div>';

            // Two-column layout: left = details, right = payment panel (hidden initially)
            modalBody.innerHTML = `
                <div class="booking-two-col">
                  <div class="booking-main">
                    ${carouselHtml}
                    <div class="booking-desc-box"><p id="bookingDetailDesc" class="mb-0">${desc}</p></div>
                    ${metaHtml}
                    ${detailsPanel}
                    ${actions}
                  </div>
                  <div class="payment-panel" id="paymentPanelRight">
                    <h6><i class='bi bi-credit-card'></i> Payment Summary</h6>
                    <div class='payment-summary-lines' id='paymentSummaryLinesRight'></div>
                    <div class='payment-total-row'><span>Total</span><span id='paymentTotalRight'>₱0</span></div>
                    <div>
                      <label class='form-label fw-semibold mb-1'>Select Payment Method</label>
                      <div class='payment-methods' id='paymentMethodsRight'>
                        <div class='payment-method' data-method='card'><i class='bi bi-credit-card-2-front'></i><span>Card</span></div>
                        <div class='payment-method' data-method='gcash'><i class='bi bi-phone'></i><span>GCash</span></div>
                        <div class='payment-method' data-method='paypal'><i class='bi bi-paypal'></i><span>PayPal</span></div>
                        <div class='payment-method' data-method='cash'><i class='bi bi-cash-stack'></i><span>Cash</span></div>
                      </div>
                    </div>
                    <div>
                      <label class='form-label fw-semibold mb-1'>Notes (Optional)</label>
                      <textarea class='form-control payment-notes' id='paymentNotesRight' placeholder='Additional payment instructions...'></textarea>
                    </div>
                    <div class='inline-payment-actions'>
                      <button class='btn-booking ocean' id='confirmPaymentBtnRight'><i class='bi bi-shield-check'></i> Confirm & Pay</button>
                      <button class='btn-booking light' id='closePanelBtnRight'>Close Panel</button>
                    </div>
                  </div>
                </div>
            `;
        }

        /**
         * Strict: generate signed token from server and display QR.
         * No raw-payload fallback in production mode.
         */
        async function downloadCheckinQr(btn) {
            const data = btn ? readBookingDataFromButton(btn) : currentBookingData;
            if (!data) return alert('Booking data not found.');
            if (!data.booking_id) return alert('Booking ID is missing.');

            try {
                const resp = await fetch(`<?= site_url('tourist/generateCheckinToken') ?>/${encodeURIComponent(data.booking_id)}`, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });

                if (!resp.ok) {
                    let msg = `Server returned ${resp.status}`;
                    try {
                        const body = await resp.json();
                        if (body && body.error) msg += ': ' + body.error;
                    } catch (e) {}
                    showToast('Check-in', 'Unable to generate secure check-in QR. ' + msg);
                    return;
                }

                const json = await resp.json();
                if (!json || !json.token) {
                    showToast('Check-in', 'Server did not return a token. Contact support.');
                    return;
                }

                // check expiry
                if (json.expires_at) {
                    const info = formatExpiryInfo(json.expires_at);
                    if (info.isExpired) {
                        showToast('Check-in', 'This token is already expired (' + info.text + ').');
                        return;
                    }
                }

                const payload = { type: 'checkin_token', token: json.token };
                const dataUrl = await generateQrDataUrl(payload, { width: 800 });
                const filename = `checkin_booking_${data.booking_id || 'unknown'}.png`;
                downloadDataUrl(dataUrl, filename);
                showToast('Check-in', 'Check-in QR downloaded.');
            } catch (err) {
                console.error('downloadCheckinQr error:', err);
                showToast('Check-in', 'Network or server error. Please try again.');
            }
        }

        /**
         * Show Check-in QR modal for a booking and display human-friendly expiry.
         * No raw payload fallback; show friendly error if server token is unavailable.
         */
        async function showCheckinQrModal(btn) {
            const data = btn ? readBookingDataFromButton(btn) : currentBookingData;
            if (!data) return alert('Booking data not found.');
            if (!data.booking_id) return alert('Booking ID missing.');

            try {
                const resp = await fetch(`<?= site_url('tourist/generateCheckinToken') ?>/${encodeURIComponent(data.booking_id)}`, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });

                const qrContainer = document.getElementById('qrContainer');
                qrContainer.innerHTML = '';
                const previewEl = document.getElementById('qrPayloadPreview');
                previewEl.textContent = '';

                if (!resp.ok) {
                    // show error in modal (no fallback)
                    let msg = `Server returned ${resp.status}`;
                    try {
                        const body = await resp.json();
                        if (body && body.error) msg += ': ' + body.error;
                    } catch (e) {}
                    qrContainer.innerHTML = `<div class="text-danger">Secure token unavailable. ${escapeHtml(msg)}</div>`;
                    document.getElementById('downloadQrBtn').onclick = null;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('qrModal')).show();
                    return;
                }

                const json = await resp.json();
                if (!json || !json.token) {
                    qrContainer.innerHTML = `<div class="text-danger">Server did not return a valid token. Please contact support.</div>`;
                    document.getElementById('downloadQrBtn').onclick = null;
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('qrModal')).show();
                    return;
                }

                // show expiry in user-friendly format, and block if already expired
                if (json.expires_at) {
                    const info = formatExpiryInfo(json.expires_at);
                    if (info.isExpired) {
                        qrContainer.innerHTML = `<div class="text-danger">This token expired: ${escapeHtml(info.text)}</div>`;
                        document.getElementById('downloadQrBtn').onclick = null;
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('qrModal')).show();
                        return;
                    }
                    previewEl.textContent = `Expires: ${info.text}`;
                }

                const payloadToEncode = { type: 'checkin_token', token: json.token };
                const dataUrl = await generateQrDataUrl(payloadToEncode, { width: 500 });

                const img = document.createElement('img');
                img.src = dataUrl;
                img.alt = 'Check-in QR';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                qrContainer.appendChild(img);

                const downloadBtn = document.getElementById('downloadQrBtn');
                downloadBtn.onclick = function () {
                    const filename = `checkin_booking_${data.booking_id || 'unknown'}.png`;
                    downloadDataUrl(dataUrl, filename);
                };

                bootstrap.Modal.getOrCreateInstance(document.getElementById('qrModal')).show();
            } catch (err) {
                console.error('showCheckinQrModal error:', err);
                showToast('Check-in', 'Network or server error. Please try again.');
            }
        }

        // Utility: generate QR data URL
        function generateQrDataUrl(payload, options = { width: 400 }) {
            const text = typeof payload === 'string' ? payload : JSON.stringify(payload);
            return QRCode.toDataURL(text, {
                errorCorrectionLevel: 'H',
                margin: 1,
                width: options.width || 400,
                color: {
                    dark: "#000000",
                    light: "#ffffff"
                }
            });
        }

        // Utility: download data URL as file
        function downloadDataUrl(dataUrl, filename) {
            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
        }

        // small helper to avoid XSS in error text
        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, function (m) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m];
            });
        }

        function cancelBooking(btn){
            const data = btn ? readBookingDataFromButton(btn) : currentBookingData;
            if(!data || !data.booking_id){return showToast('Cancel','Missing booking data');}
            currentBookingData = data;
            document.getElementById('cancelReason').value='';
            document.getElementById('cancelSummaryInfo').textContent = `Booking #${data.booking_id}`;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('cancelModal')).show();
        }

        let paymentPanelActive = false;
        function completePayment(btn){
            const data = btn ? readBookingDataFromButton(btn) : currentBookingData;
            if(!data || !data.booking_id){return showToast('Payment','Missing booking data');}
            currentBookingData = data;
            
            // Populate the right panel with payment summary
            const card = document.querySelector(`.booking-card[data-booking-id='${data.booking_id}']`);
            const summaryContainer = document.getElementById('paymentSummaryLinesRight');
            if(summaryContainer){
                summaryContainer.innerHTML = '';
                if(card){
                    const items = card.querySelectorAll('.booking-detail-item');
                    items.forEach(it=>{
                        const label = it.querySelector('h4')?.textContent.trim() || '';
                        const value = it.querySelector('p')?.textContent.trim() || '';
                        const div = document.createElement('div');
                        div.className = 'payment-summary-line';
                        div.innerHTML = `<span class='label'>${label}</span><span class='value'>${value}</span>`;
                        summaryContainer.appendChild(div);
                    });
                    const totalEl = card.querySelector('.booking-detail-item .bi-cash-stack')?.closest('.booking-detail-item')?.querySelector('p');
                    let totalTxt = '₱0';
                    if(totalEl){ totalTxt = totalEl.textContent.trim(); }
                    const totalDisplay = document.getElementById('paymentTotalRight');
                    if(totalDisplay){ totalDisplay.textContent = totalTxt; }
                }
            }

            // Show the payment panel
            const panel = document.getElementById('paymentPanelRight');
            if(panel){
                panel.classList.add('show');
                paymentPanelActive = true;

                // Reset payment method selection
                panel.querySelectorAll('.payment-method').forEach(m=>m.classList.remove('active'));

                // Attach event listeners (use one-time handlers to avoid duplicates)
                const closePanelBtn = document.getElementById('closePanelBtnRight');
                const confirmPayBtn = document.getElementById('confirmPaymentBtnRight');

                if(closePanelBtn){
                    closePanelBtn.onclick = ()=>{
                        panel.classList.remove('show');
                        paymentPanelActive = false;
                    };
                }

                if(confirmPayBtn){
                    confirmPayBtn.onclick = ()=>{
                        // If PayMongo hosted flow is enabled on server, we don't require selection of local payment method
                        let method = 'hosted';
                        if (!window.PAYMONGO_HOSTED) {
                            const methodEl = panel.querySelector('.payment-method.active');
                            if(!methodEl){return alert('Select a payment method first.');}
                            method = methodEl.dataset.method;
                        }
                        const notes = document.getElementById('paymentNotesRight')?.value.trim() || '';

                        // Call server to create a payment intent and get back a checkout URL
                        (async function(){
                            try {
                                const bookingId = data.booking_id;
                                const totalDisplay = document.getElementById('paymentTotalRight')?.textContent || '';
                                let amount = totalDisplay.replace(/[^0-9\.\-]/g, '') || '';
                                const payload = { booking_id: bookingId, amount: amount, method: method };
                                const res = await fetch('/tourist/createPaymentIntent', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify(payload),
                                    credentials: 'same-origin'
                                });
                                const json = await res.json().catch(()=>null);
                                if (!res.ok || !json || !json.success || !json.checkout_url) {
                                    console.error('createPaymentIntent failed', json);
                                    alert('Failed to create payment. Please try again.');
                                    return;
                                }
                                showToast('Redirecting to payment', 'Opening payment checkout...');
                                window.location.href = json.checkout_url;
                            } catch (err) {
                                console.error('Payment start failed', err);
                                alert('Failed to start payment.');
                            }
                        })();
                    };
                }

                // Method selection
                panel.querySelectorAll('.payment-method').forEach(m=>{
                    m.onclick = ()=>{
                        panel.querySelectorAll('.payment-method').forEach(x=>x.classList.remove('active'));
                        m.classList.add('active');
                    };
                });
            }
        }

        // (Removed global payment modal listeners)

        // Confirm cancel
        document.getElementById('confirmCancelBtn').addEventListener('click', async ()=>{
            const reason = document.getElementById('cancelReason').value.trim();
            if(reason.length < 3){return alert('Please provide a brief reason (min 3 chars).');}
            const bookingId = currentBookingData?.booking_id;
            if(!bookingId){return alert('Missing booking ID');}
            try {
                const response = await fetch(`<?= base_url('tourist/cancelBooking/') ?>${bookingId}`, {
                    method:'POST',
                    headers:{'Content-Type':'application/json'},
                    body:JSON.stringify({reason})
                });
                const data = await response.json();
                if(data.success){
                    showToast('Cancel', data.message || 'Booking cancelled successfully.');
                    bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
                    // Close details modal and refresh page to update booking list
                    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
                    if(detailsModal){ detailsModal.hide(); }
                    setTimeout(()=>{ location.reload(); }, 1000);
                } else {
                    alert(data.message || 'Cancellation failed.');
                }
            } catch(e){
                alert('Cancellation failed: '+e.message);
            }
        });

        // Blur for cancel modal stacking similar to payment
        document.getElementById('cancelModal').addEventListener('show.bs.modal', ()=>{
            const bookingModalContent = document.querySelector('#bookingModal.show .modal-content');
            if(bookingModalContent && !bookingModalContent.classList.contains('layer-blur')){
                bookingModalContent.classList.add('layer-blur');
                const backdropDiv = document.createElement('div');
                backdropDiv.className='stack-overlay-backdrop';
                bookingModalContent.appendChild(backdropDiv);
            }
        });
        document.getElementById('cancelModal').addEventListener('hidden.bs.modal', ()=>{
            const bookingModalContent = document.querySelector('#bookingModal .modal-content.layer-blur');
            if(bookingModalContent){
                bookingModalContent.classList.remove('layer-blur');
                const backdropDiv = bookingModalContent.querySelector('.stack-overlay-backdrop');
                backdropDiv && backdropDiv.remove();
            }
        });

        function writeReview() { alert('Navigate to write review (implement).'); }
        function bookAgain() { alert('Navigate to book again (implement).'); }

        // Dropdown, profile, small toasts and other UI helpers (unchanged)
        if (typeof toggleNotificationDropdown === 'undefined') {
                    function toggleNotificationDropdown() {
                        const dd = document.getElementById('notificationDropdown');
                        const ud = document.getElementById('userDropdown');
                        ud?.classList.remove('show');
                        dd.classList.toggle('show');
                    }
                }
        if (typeof openNotificationDetail === 'undefined') {
            function openNotificationDetail(item){
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
            }
        }        // On page load, probe pending bookings to detect payments processed externally
        document.addEventListener('DOMContentLoaded', function(){
            const cards = document.querySelectorAll('.booking-card');
            if(!cards || cards.length === 0) return;
            cards.forEach(card=>{
                const bookingId = card.dataset.bookingId;
                const status = (card.dataset.status || '').toLowerCase();
                if(!bookingId) return;
                // Only probe if booking appears unpaid/pending
                if(status === 'pending' || status === 'unpaid'){
                    // Fire-and-forget check to server to see if payment was completed
                    fetch(`/tourist/checkPayment/${bookingId}`, { method: 'GET', credentials: 'same-origin' })
                        .then(r=>r.json().catch(()=>null))
                        .then(json=>{
                            if(json && json.success && json.paid){
                                // Simple UX: show toast and refresh to pick up updated state
                                if(typeof showToast === 'function') showToast('Payment detected', `Booking #${bookingId} marked as paid.`);
                                setTimeout(()=> location.reload(), 900);
                            }
                        }).catch(()=>{});
                }
            });
        });
        if (typeof toggleUserDropdown === 'undefined') {
            function toggleUserDropdown() {
                const dd = document.getElementById('userDropdown');
                const nd = document.getElementById('notificationDropdown');
                nd?.classList.remove('show');
                dd.classList.toggle('show');
            }
        }
        if (typeof hideUserDropdown === 'undefined') {
            function hideUserDropdown(e){ e?.preventDefault?.(); document.getElementById('userDropdown')?.classList.remove('show'); }
        }
        if (typeof markAllAsRead === 'undefined') {
            function markAllAsRead() {
                document.querySelectorAll('.notification-item.unread').forEach(li => li.classList.remove('unread'));
                const badge = document.getElementById('notifBadge');
                if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }
                showToast && showToast('Notifications', 'All notifications marked as read.');
            }
        }
        if (typeof viewAllNotifications === 'undefined') {
            function viewAllNotifications(e){ e.preventDefault(); showToast && showToast('Notifications', 'Opening all notifications...'); }
        }

        // Profile functions & DOMContentLoaded remains the same as before
        function setLoading(btn, isLoading) {
            const sp = btn.querySelector('.spinner-border');
            const saveText = btn.querySelector('.save-text');
            if (isLoading) {
                sp.classList.remove('d-none');
                btn.disabled = true;
                saveText.textContent = 'Saving...';
            } else {
                sp.classList.add('d-none');
                btn.disabled = false;
                saveText.textContent = 'Save Changes';
            }
        }
        function openProfile(event) {
            event.preventDefault();
            document.getElementById('userDropdown').classList.remove('show');
            const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
            profileModal.show();
        }

        document.addEventListener('DOMContentLoaded', () => {
            // keep existing DOM init behaviour
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(){
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');
                        const unread = document.querySelectorAll('.notification-item.unread').length;
                        const badge = document.getElementById('notifBadge');
                        if (badge){
                            badge.textContent = unread;
                            if (unread === 0) badge.style.display = 'none';
                        }
                    }
                });
            });

            const avatarUpload = document.getElementById('avatarUpload');
            const profileAvatar = document.getElementById('profileAvatar');
            if (avatarUpload && profileAvatar) {
                avatarUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profileAvatar.style.backgroundImage = `url(${e.target.result})`;
                            profileAvatar.style.backgroundSize = 'cover';
                            profileAvatar.style.backgroundPosition = 'center';
                            profileAvatar.textContent = '';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            const profileForm = document.getElementById('profileForm');
            const profileSaveBtn = document.getElementById('profileSaveBtn');
            if (profileForm && profileSaveBtn) {
                profileForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const newPass = document.getElementById('newPassword').value;
                    const confirmPass = document.getElementById('confirmPassword').value;
                    if (newPass || confirmPass) {
                        if (newPass !== confirmPass) {
                            alert('New passwords do not match!');
                            return;
                        }
                        if (!document.getElementById('currentPassword').value) {
                            alert('Please enter your current password to change it.');
                            return;
                        }
                    }
                    setLoading(profileSaveBtn, true);
                    setTimeout(() => {
                        setLoading(profileSaveBtn, false);
                        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                        showToast('Profile Updated', 'Your profile has been successfully updated.');
                    }, 1000);
                });
            }
        });

        /* small toast utility for user feedback */
        function showToast(title, message) {
            // Basic fallback; replace with your toast UI if you have one
            console.info(title, message);
        }
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