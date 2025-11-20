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
    
    <!-- Custom CSS (Inlined Below) -->
    <link rel="stylesheet" href="<?=base_url('assets/css/touristStyle/bookings.css')?>">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar (animated, unified) -->
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
            <!-- Standalone Page Title -->
            <h1 class="page-title">My Bookings</h1>

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
                                        <h6>Booking Confirmed</h6>
                                        <p>Your Canyon Cove reservation is confirmed for Dec 20, 2025</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>2 hours ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item unread">
                                <div class="notification-content">
                                    <div class="notification-icon info"><i class="bi bi-star-fill"></i></div>
                                    <div class="notification-text">
                                        <h6>Leave a Review</h6>
                                        <p>Share your experience at Fortune Island</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>1 day ago</span></div>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon warning"><i class="bi bi-calendar-event"></i></div>
                                    <div class="notification-text">
                                        <h6>Upcoming Trip</h6>
                                        <p>Van Transfer starts in 3 days</p>
                                        <div class="notification-time"><i class="bi bi-clock"></i><span>2 days ago</span></div>
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

            <div class="bookings-container">
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filterBookings('all', this)">All Bookings</button>
                    <button class="filter-tab" onclick="filterBookings('confirmed', this)">Confirmed</button>
                    <button class="filter-tab" onclick="filterBookings('pending', this)">Pending</button>
                    <button class="filter-tab" onclick="filterBookings('completed', this)">Completed</button>
                    <button class="filter-tab" onclick="filterBookings('cancelled', this)">Cancelled</button>
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
                                <button class="btn-booking primary" onclick="viewBookingDetails(this)"><i class="bi bi-eye"></i> View Details</button>

                                <?php if ($status === 'pending'): ?>
                                    <button class="btn-booking danger" onclick="cancelBooking(this)"><i class="bi bi-x-circle"></i> Cancel</button>
                                <?php elseif ($status === 'confirmed'): ?>
                                    <button class="btn-booking secondary" onclick="downloadCheckinQr(this)"><i class="bi bi-download"></i> Download Check-in QR</button>
                                    <button class="btn-booking outline" onclick="showCheckinQrModal(this)"><i class="bi bi-qr-code"></i> Show Check-in QR</button>
                                <?php elseif ($status === 'completed'): ?>
                                    <button class="btn-booking secondary" onclick="bookAgain()"><i class="bi bi-arrow-repeat"></i> Book Again</button>
                                    <button class="btn-booking primary" onclick="writeReview()"><i class="bi bi-star"></i> Write Review</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info mt-4">No bookings found.</div>
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
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="bookingModalBody">
            <!-- Filled dynamically -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        function viewBookingDetails(btn) {
            const card = btn.closest('.booking-card');
            if (!card) return;

            // set currentBookingData for modal actions
            currentBookingData = readBookingDataFromButton(btn);

            const title = card.querySelector('.booking-title')?.textContent.trim() || '';
            const type = card.querySelector('.booking-type')?.textContent.trim() || '';
            const status = card.querySelector('.booking-status')?.textContent.trim() || '';

            // Build details list from booking-detail-item nodes
            const detailItems = Array.from(card.querySelectorAll('.booking-detail-item')).map(item => {
                const label = item.querySelector('h4')?.textContent.trim() || '';
                const value = item.querySelector('p')?.textContent.trim() || '';
                return { label, value };
            });

            // Build modal HTML
            const modalTitle = document.getElementById('bookingModalLabel');
            const modalBody = document.getElementById('bookingModalBody');
            modalTitle.textContent = title;

            let html = '';
            html += `<p><strong>Type:</strong> ${type}</p>`;
            html += `<p><strong>Status:</strong> <span class="booking-status">${status}</span></p>`;
            html += '<hr />';
            html += '<div class="list-group">';
            detailItems.forEach(d => {
                html += `<div class="list-group-item d-flex justify-content-between align-items-start"><div><small class="text-muted">${d.label}</small><div class="fw-bold">${d.value}</div></div></div>`;
            });
            html += '</div>';

            // Optional actions area - show checkin actions only for confirmed bookings
            html += `<div class="mt-3 d-flex gap-2">`;
            if (card.dataset.status === 'confirmed') {
                html += `<button class="btn btn-primary" onclick="downloadCheckinQr();bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();"><i class="bi bi-download"></i> Download Check-in QR</button>`;
                html += `<button class="btn btn-outline-secondary" onclick="showCheckinQrModal();bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();"><i class="bi bi-qr-code"></i> Show Check-in QR</button>`;
            }
            if (card.dataset.status === 'pending') {
                html += `<button class="btn btn-success" onclick="completePayment();bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();">Complete Payment</button>`;
            } else if (card.dataset.status === 'completed') {
                html += `<button class="btn btn-outline-primary" onclick="writeReview();bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();">Write Review</button>`;
            } else {
                html += `<button class="btn btn-danger" onclick="cancelBooking();bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide();">Cancel Booking</button>`;
            }
            html += `</div>`;

            modalBody.innerHTML = html;

            // Show Bootstrap modal
            const modalEl = document.getElementById('bookingModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
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

        function cancelBooking(btn) {
            const data = btn ? readBookingDataFromButton(btn) : currentBookingData;
            if (!confirm('Are you sure you want to cancel this booking? This action might be irreversible.')) return;
            // TODO: call backend cancel endpoint with data.booking_id
            alert('Cancellation request sent (implement server call).');
        }

        function completePayment() { alert('Redirect to payment (implement).'); }
        function writeReview() { alert('Navigate to write review (implement).'); }
        function bookAgain() { alert('Navigate to book again (implement).'); }

        // Dropdown, profile, small toasts and other UI helpers (unchanged)
        function toggleNotificationDropdown() {
            const dd = document.getElementById('notificationDropdown');
            const ud = document.getElementById('userDropdown');
            ud?.classList.remove('show');
            dd.classList.toggle('show');
        }
        function toggleUserDropdown() {
            const dd = document.getElementById('userDropdown');
            const nd = document.getElementById('notificationDropdown');
            nd?.classList.remove('show');
            dd.classList.toggle('show');
        }
        function hideUserDropdown(e){ e?.preventDefault?.(); document.getElementById('userDropdown')?.classList.remove('show'); }
        function markAllAsRead() {
            document.querySelectorAll('.notification-item.unread').forEach(li => li.classList.remove('unread'));
            const badge = document.getElementById('notifBadge');
            if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }
            showToast('Notifications', 'All notifications marked as read.');
        }
        function viewAllNotifications(e){ e.preventDefault(); showToast('Notifications', 'Opening all notifications...'); }

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
 
</body>
</html>