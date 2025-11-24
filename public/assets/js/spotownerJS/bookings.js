document.addEventListener('DOMContentLoaded', async() => {
    await loadBookings();
});

async function loadBookings() {
    try {
        // Fetch bookings from backend
        const response = await fetch('/spotowner/getBookings');
        const data = await response.json();

        const tbody = document.getElementById('bookingsTableBody');
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No bookings found</td></tr>`;
            return;
        }

        data.forEach(b => {
                    // Normalize status to handle case variations
                    const status = (b.booking_status || '').trim();

                    // Map status to badge class
                    let badgeClass = 'badge bg-secondary'; // default

                    if (status === 'Confirmed') {
                        badgeClass = 'badge badge-confirmed';
                    } else if (status === 'Pending') {
                        badgeClass = 'badge badge-pending';
                    } else if (status === 'Cancelled') {
                        badgeClass = 'badge badge-cancelled';
                    } else if (status === 'Rejected') {
                        badgeClass = 'badge badge-rejected';
                    } else if (status === 'Checked-in') {
                        badgeClass = 'badge badge-checkedin';
                    } else if (status === 'Checked-out') {
                        badgeClass = 'badge badge-checkedout';
                    }

                    // Format the date properly
                    const formattedDate = new Date(b.visit_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    tbody.innerHTML += `
                <tr>
                    <td>${b.booking_id}</td>
                    <td>${b.customer_name || 'N/A'}</td>
                    <td>${formattedDate}</td>
                    <td class="d-none d-md-table-cell">${b.total_guests}</td>
                    <td class="d-none d-lg-table-cell">₱${parseFloat(b.total_price).toFixed(2)}</td>
                    <td><span class="${badgeClass}">${status}</span></td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewBooking('${b.booking_id}')">View</button>
                            ${
                                status === 'Pending'
                                    ? `
                                        <button class="btn btn-sm btn-success" onclick="confirmBooking('${b.booking_id}')">Confirm</button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectBooking('${b.booking_id}')">Reject</button>
                                      `
                                    : ''
                            }
                        </div>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        console.error('Error loading bookings:', error);
        const tbody = document.getElementById('bookingsTableBody');
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading bookings. Please refresh.</td></tr>`;
    }
}


async function viewBooking(bookingId) {
    try {
        const response = await fetch(`/spotowner/getBooking/${bookingId}`);
        const booking = await response.json();

        if (!booking) {
            alert('Booking not found.');
            return;
        }
        const statusBadge =
            booking.booking_status === 'Confirmed'
               ? 'badge-confirmed'
                : booking.booking_status === 'Pending'
                ? 'badge-pending'
                : booking.booking_status === 'Cancelled'
                ? 'badge-cancelled'
                : booking.booking_status === 'Rejected'
                ? 'badge-rejected'
                : booking.booking_status === 'Checked-in'
                ? 'badge-checkedin'
                : booking.booking_status === 'Checked-out'
                ? 'badge-checkedout'
                : '';
        const modalBody = document.getElementById('bookingModalBody');
        modalBody.innerHTML = `
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-ocean-medium mb-3">Customer Information</h6>
                    <p><strong>Name:</strong> ${booking.customer_name}</p>
                    <p><strong>Email:</strong> ${booking.email}</p>
                    <p><strong>Phone:</strong> ${booking.phone}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-ocean-medium mb-3">Booking Details</h6>
                    <p><strong>Date:</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                    <p><strong>Visitors:</strong> ${booking.total_guests}</p>
                    <p><strong>Total:</strong> ₱${booking.total_price}</p>
                    <p><strong>Status:</strong> <span class="badge ${statusBadge}">${booking.booking_status}</span></p>
                </div>
                <div class="col-12">
                    <h6 class="text-ocean-medium mb-2">Special Requests</h6>
                    <div class="p-3 bg-beige rounded">${booking.special_requests || 'None'}</div>
                </div>
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('viewBookingModal'));
        modal.show();
    } catch (error) {
        console.error('Error fetching booking:', error);
    }
}

async function confirmBooking(bookingId) {
    if (!confirm('Are you sure you want to confirm this booking?')) return;
    
    try {
        const res = await fetch(`/spotowner/confirmBooking/${bookingId}`, { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const result = await res.json();
        
        if (result.success) {
            // Use SweetAlert if available, otherwise use alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Confirmed!',
                    text: 'Booking confirmed successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Booking confirmed successfully.');
            }
            
            // Reload bookings to show updated status
            await loadBookings();
        } else {
            throw new Error(result.error || 'Failed to confirm booking');
        }
    } catch (error) {
        console.error('Error confirming booking:', error);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to confirm booking: ' + error.message
            });
        } else {
            alert('Failed to confirm booking: ' + error.message);
        }
    }
}

async function rejectBooking(bookingId) {
    if (!confirm('Are you sure you want to reject this booking?')) return;
    
    try {
        const res = await fetch(`/spotowner/rejectBooking/${bookingId}`, { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const result = await res.json();
        
        if (result.success) {
            // Use SweetAlert if available, otherwise use alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected!',
                    text: 'Booking rejected successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Booking rejected successfully.');
            }
            
            // Reload bookings to show updated status
            await loadBookings();
        } else {
            throw new Error(result.error || 'Failed to reject booking');
        }
    } catch (error) {
        console.error('Error rejecting booking:', error);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to reject booking: ' + error.message
            });
        } else {
            alert('Failed to reject booking: ' + error.message);
        }
    }
}