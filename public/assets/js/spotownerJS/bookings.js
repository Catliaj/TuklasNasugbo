document.addEventListener('DOMContentLoaded', async () => {
    await loadBookings();
});

async function loadBookings() {
    try {
        // Fetch bookings from backend
        const response = await fetch('/spotowner/getBookings'); // <-- Your CodeIgniter route
        const data = await response.json();

        const tbody = document.getElementById('bookingsTableBody');
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No bookings found</td></tr>`;
            return;
        }

        data.forEach(b => {
            const badgeClass =
        b.booking_status === 'Confirmed'
            ? 'badge-confirmed'
            : b.booking_status === 'Pending'
            ? 'badge-pending'
            : b.booking_status === 'Cancelled'
            ? 'badge-cancelled'
            : b.booking_status === 'Rejected'
            ? 'badge-rejected'
            : '';

                   
            tbody.innerHTML += `
                <tr>
                    <td>${b.booking_id}</td>
                    <td>${b.customer_name}</td>
                    <td>${new Date(b.booking_date).toLocaleDateString()}</td>
                    <td>${b.total_guests}</td>
                    <td>₱${b.total_price}</td>
                    <td><span class="badge ${badgeClass}">${b.booking_status.toLowerCase()}</span></td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewBooking('${b.booking_id}')">View</button>
                        ${
                            b.booking_status === 'Pending'
                                ? `
                                    <button class="btn btn-sm btn-success" onclick="confirmBooking('${b.booking_id}')">Confirm</button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectBooking('${b.booking_id}')">Reject</button>
                                  `
                                : ''
                        }
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        console.error('Error loading bookings:', error);
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
    if (!confirm('Confirm this booking?')) return;
    try {
        const res = await fetch(`/spotowner/confirmBooking/${bookingId}`, { method: 'POST' });
        const result = await res.json();
        if (result.success) {
            alert('Booking confirmed successfully.');
            await loadBookings();
        }
    } catch (error) {
        console.error(error);
    }
}

async function rejectBooking(bookingId) {
    if (!confirm('Reject this booking?')) return;
    try {
        const res = await fetch(`/spotowner/rejectBooking/${bookingId}`, { method: 'POST' });
        const result = await res.json();
        if (result.success) {
            alert('Booking rejected.');
            await loadBookings();
        }
    } catch (error) {
        console.error(error);
    }
}
