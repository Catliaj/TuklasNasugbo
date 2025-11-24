<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Booking Management - Tourist Spot Owner</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
    
    <style>
        /* Additional mobile-specific styles for bookings page */
        @media (max-width: 767.98px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .custom-card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
            
            .custom-card-header .d-flex {
                width: 100%;
                margin-top: 0.75rem;
            }
            
            #openScannerBtn {
                width: 100%;
                margin-top: 0.5rem;
            }
            
            /* Mobile-friendly table */
            .table-responsive {
                border: none;
            }
            
            .table-custom {
                font-size: 0.75rem;
            }
            
            .table-custom th,
            .table-custom td {
                padding: 0.5rem 0.25rem;
                white-space: nowrap;
            }
            
            /* QR Scanner modal adjustments */
            #qr-reader {
                width: 100% !important;
                min-height: 250px;
            }
            
            .modal-lg {
                margin: 0.5rem;
            }
        }
        
        @media (max-width: 575.98px) {
            /* Stack stat cards */
            .row.g-3 {
                gap: 0.75rem !important;
            }
            
            .stat-value {
                font-size: 1.25rem;
            }
            
            /* Make action buttons smaller */
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
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
                        <a href="/spotowner/dashboard" class="sidebar-link" data-page="home">
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
            <p class="mobile-subtitle mb-0">Booking Management</p>
        </div>
    </div>
    
    <!-- Notification Bell -->
    <!-- Notification Bell -->
<div class="dropdown">
<button class="btn btn-link position-relative p-2" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; margin-right: 5px;">
    <i class="bi bi-bell-fill" style="font-size: 1.25rem;"></i>
    <span class="position-absolute badge rounded-pill bg-danger" 
          id="notificationBadge" 
          style="display: none; font-size: 0.6rem; top: 2px; right: 0px; padding: 0.25rem 0.4rem; min-width: 18px;">
        0
    </span>
</button>
    <div class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 380px; max-height: 500px;">
        <div class="dropdown-header d-flex justify-content-between align-items-center bg-primary text-white py-3">
            <h6 class="mb-0 fw-bold">Notifications</h6>
            <button class="btn btn-sm btn-link text-white text-decoration-none" id="markAllReadBtn">
                Mark all read
            </button>
        </div>
        <div class="dropdown-divider m-0"></div>
        <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center py-4 text-muted">
                <i class="bi bi-bell-slash fs-1"></i>
                <p class="mb-0 mt-2">No notifications</p>
            </div>
        </div>
    </div>
</div>
</div>

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <div class="container-fluid">
                    <div class="mb-3 mb-lg-4">
                        <h2 class="h4 h-lg-2">Booking Management</h2>
                        <p class="text-muted-custom small"></p>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-3 mb-lg-4">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Bookings</div>
                                        <div class="stat-value"><?= esc($totalbookings ?? 0) ?></div>
                                        <div class="stat-description">This month</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Visitors</div>
                                        <div class="stat-value"><?= esc($totalvisitors ?? 0) ?></div>
                                        <div class="stat-description">Expected visitors</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stat-label">Total Revenue</div>
                                        <div class="stat-value">₱<?= esc($totalrevenue ?? '0.00') ?></div>
                                        <div class="stat-description">From active bookings</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Table -->
                    <div class="custom-card">
                        <div class="custom-card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <h3 class="custom-card-title">Recent Bookings</h3>
                                <p class="custom-card-description d-none d-md-block">List of all bookings for your tourist spot</p>
                            </div>
                            <div class="d-flex gap-2 align-items-center w-100 w-md-auto">
                                <button class="btn btn-outline-primary w-100 w-md-auto" id="openScannerBtn" onclick="openQrScannerModal()">
                                    <i class="bi bi-qr-code-scan"></i> <span class="d-none d-sm-inline">Open</span> QR Scanner
                                </button>
                            </div>
                        </div>

                        <div class="custom-card-body p-0 p-md-3">
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th class="d-none d-md-table-cell">Visitors</th>
                                            <th class="d-none d-lg-table-cell">Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookingsTableBody">
                                        <!-- Populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Modal -->
                <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-qr-code-scan"></i> Scan Check-in QR</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopScanner()"></button>
                            </div>
                            <div class="modal-body">
                                <div id="scannerContainer" style="min-height:250px;">
                                    <div id="qr-reader" style="width:100%"></div>
                                </div>

                                <div id="qrScanResult" class="mt-3" style="display:none;">
                                    <h6>Scan result</h6>
                                    <div id="qrPayloadInfo" class="mb-2 small"></div>
                                    <div class="d-flex gap-2 flex-column flex-sm-row">
                                        <button class="btn btn-success flex-fill" id="confirmCheckinBtn" onclick="confirmScannedCheckin()">Confirm</button>
                                        <button class="btn btn-secondary flex-fill" onclick="resumeScanner()">Scan again</button>
                                    </div>
                                </div>

                                <div id="qrScanError" class="mt-3 text-danger small" style="display:none;"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary w-100 w-sm-auto" data-bs-dismiss="modal" onclick="stopScanner()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Booking Modal -->
                <div class="modal fade" id="viewBookingModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-info-circle"></i> Booking Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="bookingModalBody">
                                <!-- Content will be loaded dynamically -->
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Modal -->
                
                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-wallet2"></i> Collect Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-2"><strong>Booking ID:</strong> <span id="payBookingId"></span></div>
                        <div class="mb-2"><strong>Customer:</strong> <span id="payCustomerName"></span></div>
                        <div class="mb-2"><strong>Amount:</strong> <span id="payAmount"></span></div>
                        <div class="mb-2"><strong>Notes:</strong> <div id="payDetails" class="small text-muted"></div></div>

                        <div class="mt-3">
                          <p class="small text-muted">Select a payment option below. For production integrate PayMango SDK/server-side checkout and validate via webhooks. For now you can verify payment externally then click <strong>Mark as Paid</strong>.</p>
                          <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" disabled>Pay via Card (integrate SDK)</button>
                            <button class="btn btn-outline-secondary" disabled>Generate QR (integrate)</button>
                            <button class="btn btn-primary" id="startCheckoutBtn">Proceed to Checkout</button>
                            <button class="btn btn-outline-info" onclick="window.open('/payments/manual?booking='+document.getElementById('payBookingId').textContent, '_blank')">Open External Link</button>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" onclick="markPaymentPaid(document.getElementById('payBookingId').textContent)">Mark as Paid</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Payment Receipt Modal -->
                <div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-receipt"></i> Payment Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body" id="paymentReceiptBody">
                        <!-- populated by JS -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
            </main>
        </div>
    </div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- QR Code Scanner -->
<script src="<?= base_url('assets/vendor/html5-qrcode/html5-qrcode.min.js') ?>"></script>

<!-- Sidebar Toggle (IMPORTANT!) -->
<script src="<?= base_url('assets/js/sidebar.js')?>"></script>

<!-- Page-specific scripts -->
<script src="<?= base_url('assets/js/spotownerJS/shared-data.js')?>"></script>
<script src="<?= base_url('assets/js/spotownerJS/bookings.js')?>"></script>

 
    </script>


 
   
    <!-- Expose endpoints to JS (use correct site_url values) -->
    <script>
      window.verifyTokenUrl = '<?= site_url("tourist/verifyCheckinToken") ?>';
      window.recordCheckinUrl = '<?= site_url("spotowner/recordCheckin") ?>';
      window.getBookingsUrl = '<?= site_url("spotowner/getBookings") ?>';
    </script>

    <!-- Inline implementation of bookings.js (scanner + verify + record flow) -->
    <script>
    /* bookings.js (inlined)
       - Loads html5-qrcode if needed (local preferred)
       - Verifies token via verifyCheckinToken
       - Ensures booking_date matches today's visit_date before recording
       - Uses SweetAlert2 for UX
    */

   // bookings.js - Spot Owner bookings page behavior including QR scanner + checkin/checkout flow
// Dynamically loads html5-qrcode if needed, verifies tokens with server,
// and posts record requests including the signed token. Uses SweetAlert2 for confirmation UX.

const HTML5_QR_CANDIDATES = [
  '/assets/vendor/html5-qrcode/html5-qrcode.min.js', // local preferred
  'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.7/minified/html5-qrcode.min.js'
];

let html5QrcodeScanner = null;
let lastScannedToken = null; // signed token string
let scannedPayload = null;   // server-verified payload

// ---------------------- utils ----------------------
function escHtml(s) {
  if (s === null || s === undefined) return '';
  return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]);
}

function formatYmdToReadable(ymd){
  if (!ymd) return '';
  const d = new Date(ymd + 'T00:00:00');
  if (isNaN(d.getTime())) return ymd;
  return d.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
}

// ---------------------- dynamic script loader ----------------------
function loadScriptUrl(url) {
  return new Promise((resolve, reject) => {
    if (typeof Html5Qrcode !== 'undefined') return resolve(url);
    const existing = Array.from(document.getElementsByTagName('script')).find(s => s.src && s.src.indexOf(url) !== -1);
    if (existing) {
      if (typeof Html5Qrcode !== 'undefined') return resolve(url);
      existing.addEventListener('load', () => resolve(url));
      existing.addEventListener('error', () => reject(new Error('Failed to load ' + url)));
      return;
    }
    const s = document.createElement('script');
    s.src = url;
    s.async = true;
    s.onload = () => resolve(url);
    s.onerror = () => reject(new Error('Failed to load ' + url));
    document.head.appendChild(s);
  });
}

async function ensureHtml5Qrcode() {
  if (typeof Html5Qrcode !== 'undefined') return;
  let lastErr = null;
  for (const url of HTML5_QR_CANDIDATES) {
    try {
      await loadScriptUrl(url);
      await new Promise(r => setTimeout(r, 50));
      if (typeof Html5Qrcode !== 'undefined') return;
    } catch (e) {
      lastErr = e;
    }
  }
  throw lastErr || new Error('html5-qrcode not available');
}

// ---------------------- UI helpers ----------------------
function showScanError(msg) {
  const el = document.getElementById('qrScanError');
  if (el) { el.style.display = 'block'; el.textContent = msg; }
  if (typeof Swal !== 'undefined') {
    Swal.fire({ icon: 'error', title: 'Error', text: msg });
  } else {
    alert(msg);
  }
}

function hideScanUI() {
  const res = document.getElementById('qrScanResult');
  if (res) res.style.display = 'none';
  const err = document.getElementById('qrScanError');
  if (err) { err.style.display = 'none'; err.textContent = ''; }
}

// ---------------------- scanner control ----------------------
async function startScanner() {
  try { await ensureHtml5Qrcode(); } catch (err) {
    console.error('Html5Qrcode failed to load:', err);
    showScanError('QR library failed to load. Include html5-qrcode locally or check connection.');
    return;
  }

  if (html5QrcodeScanner) return;

  try {
    html5QrcodeScanner = new Html5Qrcode('qr-reader', { verbose: false });
  } catch (err) {
    console.error('Failed to create Html5Qrcode instance', err);
    showScanError('Scanner initialization failed.');
    html5QrcodeScanner = null;
    return;
  }

  try {
    const cameras = await Html5Qrcode.getCameras();
    let cameraId = null;
    if (cameras && cameras.length) {
      const back = cameras.find(c => /back|rear|environment/gi.test(c.label));
      cameraId = back ? back.id : cameras[0].id;
    }
    const config = { fps: 10, qrbox: { width: 300, height: 300 } };
    await html5QrcodeScanner.start(
      cameraId,
      config,
      qrCodeMessage => { onQrScanned(qrCodeMessage); },
      errorMessage => { /* ignore per-frame errors */ }
    );
    hideScanUI();
  } catch (err) {
    console.error('QR start error', err);
    showScanError('Unable to access camera: ' + (err.message || err));
    try { html5QrcodeScanner.clear(); } catch(e){}
    html5QrcodeScanner = null;
  }
}

async function stopScanner() {
  if (!html5QrcodeScanner) return;
  try { await html5QrcodeScanner.stop(); await html5QrcodeScanner.clear(); } catch (err) { console.warn(err); }
  html5QrcodeScanner = null;
  hideScanUI();
}

function resumeScanner() {
  lastScannedToken = null;
  scannedPayload = null;
  hideScanUI();
  if (!html5QrcodeScanner) startScanner();
}

async function openQrScannerModal() {
  const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
  modal.show();
  await startScanner();
}

// ---------------------- core flows ----------------------
// decode JSON-safe token wrapper or raw token string
function extractTokenFromDecodedText(decodedText) {
  try {
    const parsed = JSON.parse(decodedText);
    if (parsed && parsed.token) return parsed.token;
  } catch(e){}
  return decodedText;
}

async function onQrScanned(decodedText) {
  try { if (html5QrcodeScanner && html5QrcodeScanner.pause) await html5QrcodeScanner.pause(); } catch(e){}

  const token = extractTokenFromDecodedText(decodedText);
  if (!token) {
    showScanError('No token found in QR.');
    resumeScanner();
    return;
  }
  lastScannedToken = token;

  const verifyUrl = window.verifyTokenUrl || '/index.php/tourist/verifyCheckinToken';
  let resp;
  try {
    resp = await fetch(verifyUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ token })
    });
  } catch (err) {
    console.error(err);
    showScanError('Network error while verifying token.');
    resumeScanner();
    return;
  }

  if (resp.status === 410) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({ icon: 'error', title: 'Token expired', text: 'This token has expired.' });
    } else { alert('Token expired'); }
    resumeScanner();
    return;
  }

  const result = await resp.json().catch(() => null);
  if (!resp.ok || !result || !result.valid) {
    const msg = result?.error || `Server returned ${resp.status}`;
    if (typeof Swal !== 'undefined') {
      Swal.fire({ icon: 'error', title: 'Verification failed', text: msg });
    } else {
      alert('Verification failed: ' + msg);
    }
    resumeScanner();
    return;
  }

  scannedPayload = result;

  // show info area (if present)
  const rawVisit = result.visit_date || result.booking_date || result.book_date || '';
  const bookingDateDisplay = rawVisit ? formatYmdToReadable(rawVisit) : 'N/A';
  const issued = result.issued_at ? new Date(result.issued_at).toLocaleString() : 'N/A';
  const expires = result.expires_at ? new Date(result.expires_at).toLocaleString() : 'N/A';
  const infoEl = document.getElementById('qrPayloadInfo');
  if (infoEl) {
    infoEl.innerHTML = `
    `;
  }
  const resEl = document.getElementById('qrScanResult'); if (resEl) resEl.style.display = 'block';

  // ensure visit_date exists and is same-day
  const todayYmd = new Date().toISOString().slice(0,10);
  if (!rawVisit) {
    await (typeof Swal !== 'undefined'
      ? Swal.fire({ icon: 'warning', title: 'No visit date', text: 'No visit date found for this token/booking.' })
      : alert('No visit date found for this token/booking.'));
    resumeScanner();
    return;
  }
  if (rawVisit !== todayYmd) {
    if (new Date(rawVisit + 'T00:00:00') < new Date(todayYmd + 'T00:00:00')) {
      await (typeof Swal !== 'undefined'
        ? Swal.fire({ icon: 'error', title: 'Visit date passed', html: `This booking was for <strong>${formatYmdToReadable(rawVisit)}</strong>. Check-in not allowed.` })
        : alert('This booking is for past date; check-in not allowed.'));
    } else {
      await (typeof Swal !== 'undefined'
        ? Swal.fire({ icon: 'info', title: 'Future booking', html: `This booking is scheduled for <strong>${formatYmdToReadable(rawVisit)}</strong>. Check-in on that date.` })
        : alert('This booking is scheduled for a future date.'));
    }
    resumeScanner();
    return;
  }

  // action suggestion: checkin or checkout
  const action = result.action_suggestion || 'checkin';

  if (action === 'checkin') {
    // use SweetAlert2 to get optional actual visitors
    if (typeof Swal === 'undefined') {
      // fallback to prompt (rare)
      const v = prompt(`Confirm Check-in\nBooking ID: ${result.booking_id}\nDate: ${bookingDateDisplay}\nEnter actual number of visitors (leave empty to use booking total):`);
      if (v === null) { resumeScanner(); return; }
      const n = v.trim() === '' ? null : parseInt(v, 10);
      await sendRecordRequest({ booking_id: result.booking_id, customer_id: result.customer_id, actual_visitors: Number.isNaN(n) ? null : n, token: lastScannedToken });
    } else {
      const { value, isConfirmed } = await Swal.fire({
        title: 'Confirm Check-in',
        html: `<p>Booking ID: <strong>${escHtml(result.booking_id)}</strong><br/>Date: <strong>${escHtml(bookingDateDisplay)}</strong></p>`,
        input: 'number',
        inputLabel: 'Actual number of visitors (leave empty to use booking total)',
        inputAttributes: { min: 1, inputmode: 'numeric' },
        showCancelButton: true,
        confirmButtonText: 'Record Check-in',
        preConfirm: (v) => {
          if (v === '' || v === null) return null;
          const n = parseInt(v, 10);
          if (Number.isNaN(n) || n <= 0) { Swal.showValidationMessage('Enter a valid positive number or leave empty.'); return false; }
          return n;
        }
      });
      if (!isConfirmed) { resumeScanner(); return; }
      await sendRecordRequest({ booking_id: result.booking_id, customer_id: result.customer_id, actual_visitors: value === '' || value === null ? null : value, token: lastScannedToken });
    }
  } else {
    // checkout flow
    if (typeof Swal === 'undefined') {
      const ok = confirm(`Record check-out for booking ${result.booking_id}?`);
      if (!ok) { resumeScanner(); return; }
      await sendRecordRequest({ booking_id: result.booking_id, customer_id: result.customer_id, token: lastScannedToken });
    } else {
      const confirmed = await Swal.fire({
        title: 'Confirm Check-out',
        html: `<p>Booking ID: <strong>${escHtml(result.booking_id)}</strong><br/>Do you want to record check-out now?</p>`,
        showCancelButton: true,
        confirmButtonText: 'Record Check-out'
      });
      if (!confirmed.isConfirmed) { resumeScanner(); return; }
      await sendRecordRequest({ booking_id: result.booking_id, customer_id: result.customer_id, token: lastScannedToken });
    }
  }
}

// ---------------------- send record request ----------------------
async function sendRecordRequest(payload) {
  const recordUrl = window.recordCheckinUrl || '/index.php/spotowner/recordCheckin';
  // log payload in dev for debugging
  if (typeof console !== 'undefined') console.debug('sendRecordRequest payload', payload);
  try {
    const r = await fetch(recordUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await r.json().catch(()=>null);
    if (!r.ok) {
      const msg = data?.error || `Server returned ${r.status}`;
      if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Record failed', text: msg });
      else alert('Record failed: ' + msg);
      resumeScanner();
      return;
    }
    if (data && data.success) {
      const action = data.action || 'checkin';
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'success', title: action === 'checkout' ? 'Checked out' : 'Checked in', text: 'Recorded successfully.' });
      } else {
        alert((action === 'checkout' ? 'Checked out: ' : 'Checked in: ') + 'Recorded successfully.');
      }
      const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
      if (modal) modal.hide();
      if (typeof loadBookings === 'function') try { loadBookings(); } catch(e){}
      return;
    } else {
      const err = data?.error || 'Unknown error';
      if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Record error', text: err });
      else alert('Record error: ' + err);
      resumeScanner();
    }
  } catch (err) {
    console.error(err);
    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Network error', text: 'Failed to record check-in.' });
    else alert('Network error: Failed to record check-in.');
    resumeScanner();
  }
}

// Backwards compatible confirm button handler (calls sendRecordRequest)
async function confirmScannedCheckin() {
  if (!scannedPayload || !scannedPayload.booking_id) {
    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Nothing to confirm', text: 'No scanned booking to confirm.' });
    else alert('No scanned booking to confirm.');
    return;
  }
  const payload = {
    booking_id: scannedPayload.booking_id,
    customer_id: scannedPayload.customer_id,
    actual_visitors: null,
    token: lastScannedToken
  };
  await sendRecordRequest(payload);
}

// ---------------------- expose to global ----------------------
window.openQrScannerModal = openQrScannerModal;
window.stopScanner = stopScanner;
window.resumeScanner = resumeScanner;
window.confirmScannedCheckin = confirmScannedCheckin;

// cleanup scanner when modal closes
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('qrScannerModal');
  if (modalEl) modalEl.addEventListener('hidden.bs.modal', () => { stopScanner(); });
});



    </script>

<script src="<?= base_url('assets/js/spotownerJS/notifications.js') ?>"></script>
</body>

</html>