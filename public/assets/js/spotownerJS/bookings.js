// Ensure escHtml helper exists (may be provided inline in page). Provide fallback.
if (typeof escHtml === 'undefined') {
    function escHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s).replace(/[&<>"']/g, function(c) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"})[c] || c;
        });
    }
}

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
                            ${
                                status === 'Confirmed'
                                    ? (
                                        (b.payment_status && b.payment_status === 'Paid')
                                            ? `<button class="btn btn-sm btn-outline-success" onclick="viewReceipt('${b.booking_id}')">Receipt</button>`
                                            : `<button class="btn btn-sm btn-warning" onclick="collectPayment('${b.booking_id}')">Collect Payment</button>`
                                      )
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

// --- Payment flow helpers ---
async function collectPayment(bookingId) {
    try {
        const res = await fetch(`/spotowner/getBooking/${bookingId}`);
        const booking = await res.json();
        if (!booking) return alert('Booking not found');

        // populate modal with booking info
        let modal = document.getElementById('paymentModal');
        if (!modal) {
            console.error('Payment modal not found in DOM');
            return;
        }

        document.getElementById('payBookingId').textContent = booking.booking_id;
        document.getElementById('payCustomerName').textContent = booking.customer_name || '';
        document.getElementById('payAmount').textContent = '₱' + parseFloat(booking.total_price).toFixed(2);
        document.getElementById('payDetails').textContent = booking.special_requests || '—';

        // show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // wire checkout button to create server-side payment session
        try {
            const startBtn = document.getElementById('startCheckoutBtn');
            if (startBtn) {
                startBtn.disabled = false;
                startBtn.textContent = 'Proceed to Checkout';
                startBtn.onclick = async function () {
                    await startCheckout(booking.booking_id);
                };
            }
        } catch (e) { console.warn('Failed to attach checkout button handler', e); }
    } catch (err) {
        console.error('collectPayment error', err);
        alert('Failed to open payment modal');
    }
}

async function startCheckout(bookingId) {
    const startBtn = document.getElementById('startCheckoutBtn');
    if (startBtn) { startBtn.disabled = true; startBtn.textContent = 'Opening checkout…'; }

    try {
        const res = await fetch(`/spotowner/createPaymentSession/${bookingId}`, { method: 'POST' });
        const data = await res.json().catch(() => null);
        if (!res.ok) {
            throw new Error((data && data.error) ? data.error : 'Payment provider error');
        }
        const url = data.checkout_url || data.url;
        if (!url) throw new Error('No checkout URL returned by payment provider');

        // open checkout in a new tab/window
        const w = window.open(url, '_blank');
        if (w) w.focus();

        // Optionally inform the user to wait for webhook/redirect
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'info', title: 'Checkout opened', text: 'Complete the payment in the new tab. Receipt will be available after confirmation.' });
        } else {
            alert('Checkout opened in a new tab. Complete the payment there.');
        }
    } catch (err) {
        console.error('startCheckout error', err);
        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Payment error', text: err.message });
        else alert('Payment error: ' + err.message);
    } finally {
        if (startBtn) { startBtn.disabled = false; startBtn.textContent = 'Proceed to Checkout'; }
    }
}

async function markPaymentPaid(bookingId) {
    if (!confirm('Mark this booking as PAID? Only do this after verifying the payment.')) return;
    try {
        const res = await fetch(`/spotowner/markPaymentPaid/${bookingId}`, { method: 'POST' });
        const data = await res.json();
        if (data && data.success) {
            // close payment modal if open
            const pm = document.getElementById('paymentModal');
            try { bootstrap.Modal.getInstance(pm)?.hide(); } catch(e){}

            // show receipt modal
            await showReceipt(bookingId);
            // reload bookings
            await loadBookings();
        } else {
            throw new Error(data?.error || 'Failed to mark paid');
        }
    } catch (err) {
        console.error('markPaymentPaid error', err);
        alert('Failed to mark payment as paid');
    }
}

async function showReceipt(bookingId) {
    try {
        const res = await fetch(`/spotowner/getBooking/${bookingId}`);
        const booking = await res.json();
        if (!booking) return alert('Booking not found');

        const rModalBody = document.getElementById('paymentReceiptBody');
        rModalBody.innerHTML = `
            <h5>Receipt — Booking #${escHtml(booking.booking_id)}</h5>
            <p><strong>Customer:</strong> ${escHtml(booking.customer_name || '')}</p>
            <p><strong>Date:</strong> ${new Date(booking.visit_date || booking.booking_date).toLocaleString()}</p>
            <p><strong>Visitors:</strong> ${escHtml(String(booking.total_guests || ''))}</p>
            <p><strong>Amount Paid:</strong> ₱${parseFloat(booking.total_price).toFixed(2)}</p>
            <p><strong>Payment Status:</strong> ${escHtml(booking.payment_status || 'Unpaid')}</p>
            <p class="small text-muted">This is a simple receipt record. For authoritative records integrate PayMango webhooks/backend validation.</p>
        `;

        const rModal = new bootstrap.Modal(document.getElementById('paymentReceiptModal'));
        rModal.show();
    } catch (err) {
        console.error('showReceipt error', err);
    }
}

function viewReceipt(bookingId) { showReceipt(bookingId); }