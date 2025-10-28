// Bookings Page
function renderBookingsPage() {
    return `
        <div class="container-fluid">
            <div class="mb-4">
                <h2>Booking Management</h2>
                <p class="text-muted-custom">Manage and track all bookings for your tourist spot</p>
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Bookings</div>
                                <div class="stat-value">6</div>
                                <div class="stat-description">4 confirmed</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Visitors</div>
                                <div class="stat-value">22</div>
                                <div class="stat-description">Expected visitors</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Revenue</div>
                                <div class="stat-value">₱2,750</div>
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
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Recent Bookings</h3>
                    <p class="custom-card-description">List of all bookings for your tourist spot</p>
                </div>
                <div class="custom-card-body">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Visitors</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTableBody">
                                <tr>
                                    <td>BK001</td>
                                    <td>Maria Santos</td>
                                    <td>10/10/2025</td>
                                    <td>4</td>
                                    <td>₱500</td>
                                    <td><span class="badge badge-confirmed">confirmed</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK001">View</button></td>
                                </tr>
                                <tr>
                                    <td>BK002</td>
                                    <td>Juan Dela Cruz</td>
                                    <td>10/12/2025</td>
                                    <td>2</td>
                                    <td>₱250</td>
                                    <td><span class="badge badge-confirmed">confirmed</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK002">View</button></td>
                                </tr>
                                <tr>
                                    <td>BK003</td>
                                    <td>Ana Reyes</td>
                                    <td>10/15/2025</td>
                                    <td>6</td>
                                    <td>₱750</td>
                                    <td><span class="badge badge-pending">pending</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK003">View</button></td>
                                </tr>
                                <tr>
                                    <td>BK004</td>
                                    <td>Carlos Garcia</td>
                                    <td>10/18/2025</td>
                                    <td>3</td>
                                    <td>₱375</td>
                                    <td><span class="badge badge-confirmed">confirmed</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK004">View</button></td>
                                </tr>
                                <tr>
                                    <td>BK005</td>
                                    <td>Sofia Martinez</td>
                                    <td>10/20/2025</td>
                                    <td>5</td>
                                    <td>₱625</td>
                                    <td><span class="badge badge-pending">pending</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK005">View</button></td>
                                </tr>
                                <tr>
                                    <td>BK006</td>
                                    <td>Diego Lopez</td>
                                    <td>10/09/2025</td>
                                    <td>2</td>
                                    <td>₱250</td>
                                    <td><span class="badge badge-cancelled">cancelled</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary view-booking-btn" data-id="BK006">View</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Booking Modal -->
        <div class="modal fade" id="viewBookingModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="bookingModalBody">
                        <!-- Content loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    `;
}

function initBookingsPage() {
    // Mock booking data
    const bookings = {
        'BK001': {
            id: 'BK001',
            customerName: 'Maria Santos',
            email: 'maria.santos@example.com',
            phone: '+63 912 345 6789',
            date: '2025-10-10',
            checkIn: '09:00 AM',
            visitors: 4,
            amount: 500,
            status: 'confirmed',
            specialRequests: 'Please reserve beach chairs near the sunset viewpoint'
        },
        'BK002': {
            id: 'BK002',
            customerName: 'Juan Dela Cruz',
            email: 'juan.delacruz@example.com',
            phone: '+63 923 456 7890',
            date: '2025-10-12',
            checkIn: '10:00 AM',
            visitors: 2,
            amount: 250,
            status: 'confirmed',
            specialRequests: 'None'
        },
        'BK003': {
            id: 'BK003',
            customerName: 'Ana Reyes',
            email: 'ana.reyes@example.com',
            phone: '+63 934 567 8901',
            date: '2025-10-15',
            checkIn: '08:00 AM',
            visitors: 6,
            amount: 750,
            status: 'pending',
            specialRequests: 'Birthday celebration - need access to picnic area'
        }
    };

    // Initialize view booking buttons
    document.querySelectorAll('.view-booking-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            const booking = bookings[bookingId] || {
                id: bookingId,
                customerName: 'Customer',
                email: 'customer@example.com',
                phone: '+63 900 000 0000',
                date: '2025-10-10',
                checkIn: '09:00 AM',
                visitors: 2,
                amount: 250,
                status: 'confirmed',
                specialRequests: 'None'
            };
            
            showBookingDetails(booking);
        });
    });
}

function showBookingDetails(booking) {
    const modalBody = document.getElementById('bookingModalBody');
    
    const statusBadge = booking.status === 'confirmed' ? 'badge-confirmed' : 
                       booking.status === 'pending' ? 'badge-pending' : 'badge-cancelled';
    
    modalBody.innerHTML = `
        <div class="row g-4">
            <div class="col-md-6">
                <h6 class="text-ocean-medium mb-3">Customer Information</h6>
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-person text-muted-custom"></i>
                        <span class="text-muted-custom small">Name</span>
                    </div>
                    <div class="fw-medium">${booking.customerName}</div>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-envelope text-muted-custom"></i>
                        <span class="text-muted-custom small">Email</span>
                    </div>
                    <div class="fw-medium">${booking.email}</div>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-telephone text-muted-custom"></i>
                        <span class="text-muted-custom small">Phone</span>
                    </div>
                    <div class="fw-medium">${booking.phone}</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-ocean-medium mb-3">Booking Information</h6>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Booking ID</div>
                    <div class="fw-medium">${booking.id}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Status</div>
                    <div><span class="badge ${statusBadge}">${booking.status}</span></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Visit Date</div>
                    <div class="fw-medium">${new Date(booking.date).toLocaleDateString()}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Check-in Time</div>
                    <div class="fw-medium">${booking.checkIn}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Number of Visitors</div>
                    <div class="fw-medium">${booking.visitors} people</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted-custom small mb-1">Total Amount</div>
                    <div class="fw-medium text-ocean-medium">₱${booking.amount}</div>
                </div>
            </div>
            
            <div class="col-12">
                <h6 class="text-ocean-medium mb-2">Special Requests</h6>
                <div class="p-3 bg-beige rounded">
                    ${booking.specialRequests}
                </div>
            </div>
            
            <div class="col-12">
                <div class="d-flex gap-2 justify-content-end">
                    ${booking.status === 'pending' ? `
                        <button class="btn btn-outline-danger" onclick="declineBooking('${booking.id}')">Decline</button>
                        <button class="btn btn-primary" onclick="confirmBooking('${booking.id}')">Confirm Booking</button>
                    ` : booking.status === 'confirmed' ? `
                        <button class="btn btn-outline-primary" onclick="sendReminder('${booking.id}')">Send Reminder</button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('viewBookingModal'));
    modal.show();
}

function confirmBooking(bookingId) {
    alert(`Booking ${bookingId} confirmed!`);
    bootstrap.Modal.getInstance(document.getElementById('viewBookingModal')).hide();
}

function declineBooking(bookingId) {
    if (confirm(`Are you sure you want to decline booking ${bookingId}?`)) {
        alert(`Booking ${bookingId} declined.`);
        bootstrap.Modal.getInstance(document.getElementById('viewBookingModal')).hide();
    }
}

function sendReminder(bookingId) {
    alert(`Reminder sent for booking ${bookingId}!`);
}
