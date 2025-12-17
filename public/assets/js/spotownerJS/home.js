// Home Page - Multiple Tourist Spots Management with Statistics Charts

function renderHomePage() {
    return `
        <div class="container-fluid px-0">
            <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3>My Tourist Spots</h3>
                    <p class="text-muted-custom"></p>
                </div>
            </div>

            <!-- Overall Stats Overview -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Spots</div>
                                <div class="stat-value" id="stat-total-spots">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="stat-description">Active properties</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Bookings</div>
                                <div class="stat-value" id="stat-total-bookings">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Revenue</div>
                                <div class="stat-value" id="stat-total-revenue">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="stat-description">This month</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Average Rating</div>
                                <div class="stat-value" id="stat-avg-rating">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="stat-description">Across all spots</div>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Statistics Section -->
            <div class="mb-4">
                <h3 class="mb-3">Performance Statistics</h3>

                <!-- First Row: Main Line Charts -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h3 class="custom-card-title">Revenue Trend</h3>
                                <p class="custom-card-description">Monthly revenue comparison across all spots</p>
                            </div>
                            <div class="custom-card-body">
                                <div class="chart-container">
                                    <canvas id="revenueTrendChart" class="chart-canvas" height="320"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h3 class="custom-card-title">Booking Trends</h3>
                                <p class="custom-card-description">6-month booking comparison</p>
                            </div>
                            <div class="custom-card-body">
                                <div class="chart-container">
                                    <canvas id="bookingTrendChart" class="chart-canvas" height="320"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tourist Spots Grid -->
            <div class="row g-4 mb-4" id="touristSpotsGrid">
                <!-- Spot cards will be loaded here -->
            </div>
        </div>

        <!-- View Spot Details Modal -->
        <div class="modal fade" id="viewSpotModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="spotModalTitle">Spot Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="spotModalBody">
                        <!-- Content loaded dynamically -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

let currentImageIndex = 0;

// Fetch dashboard analytics from API
async function fetchDashboardAnalytics() {
    try {
        console.log('📊 Fetching dashboard analytics...');

        // If server provided immediate values, use them first for instant display
        if (window.__SERVER_DASHBOARD) {
            const s = window.__SERVER_DASHBOARD;
            try {
                document.getElementById('stat-total-spots').textContent = s.totalSpots || 0;
                document.getElementById('stat-total-bookings').textContent = s.totalBookings || 0;
                document.getElementById('stat-total-revenue').textContent = `₱${(s.totalRevenue || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                // Annual visitors provided by server dashboard
                const avEl = document.getElementById('stat-annual-visitors');
                if (avEl) avEl.textContent = (s.annualVisitors || 0).toLocaleString();
            } catch (e) {
                // ignore DOM errors
            }
        }

        // If server provided values, skip the API refresh to avoid overwriting correct server values
        if (window.__SERVER_DASHBOARD) {
            console.log('Using server dashboard values; skipping API refresh to avoid overwrite.');
            return;
        }

        // Still attempt to fetch live analytics to refresh values asynchronously when server values are not present
        const res = await fetch('/spotowner/api/dashboard-analytics');
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        const data = await res.json();
        console.log('📦 Received analytics:', data);

        // Update stat cards with API data if available, but do not overwrite a non-zero server value with a zero API result
        const serverVals = window.__SERVER_DASHBOARD || {};
        const totalSpotsVal = (typeof data.totalSpots !== 'undefined' && data.totalSpots !== null) ? data.totalSpots : (serverVals.totalSpots ?? 0);
        const totalBookingsVal = (typeof data.totalBookings !== 'undefined' && data.totalBookings !== null) ? data.totalBookings : (serverVals.totalBookings ?? 0);

        // For revenue: prefer API when it's non-null; however if API returns 0 while server had a positive value, keep server value to avoid flashing 0
        const apiRevenue = (typeof data.totalRevenue !== 'undefined' && data.totalRevenue !== null) ? Number(data.totalRevenue) : null;
        let revenueVal;
        if (apiRevenue === null) {
            revenueVal = Number(serverVals.totalRevenue ?? 0);
        } else if (apiRevenue === 0 && Number(serverVals.totalRevenue || 0) > 0) {
            revenueVal = Number(serverVals.totalRevenue);
        } else {
            revenueVal = apiRevenue;
        }

        const avgRatingVal = (typeof data.averageRating !== 'undefined' && data.averageRating !== null) ? data.averageRating : (serverVals.averageRating ?? '0.0');
        const annualVisitorsVal = (typeof data.annualVisitors !== 'undefined' && data.annualVisitors !== null) ? data.annualVisitors : (serverVals.annualVisitors ?? 0);

        document.getElementById('stat-total-spots').textContent = totalSpotsVal;
        document.getElementById('stat-total-bookings').textContent = totalBookingsVal;
        document.getElementById('stat-total-revenue').textContent = `₱${(Number(revenueVal) || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
        document.getElementById('stat-avg-rating').textContent = avgRatingVal;
        // Update description below average rating with rated spots count if available
        const avgDesc = document.querySelector('#stat-avg-rating')?.closest('.stat-card')?.querySelector('.stat-description');
        if (avgDesc && typeof data.ratedSpots !== 'undefined') {
            avgDesc.textContent = `Across ${data.ratedSpots} rated spots`;
        }

        // Set annual visitors element
        const avEl2 = document.getElementById('stat-annual-visitors');
        if (avEl2) avEl2.textContent = (annualVisitorsVal || 0).toLocaleString('en-PH');

        console.log('✅ Analytics updated successfully');

    } catch (err) {
        console.error('❌ Error fetching analytics:', err);
        // Show error state
        document.getElementById('stat-total-spots').textContent = '0';
        document.getElementById('stat-total-bookings').textContent = '0';
        document.getElementById('stat-total-revenue').textContent = '₱0.00';
        document.getElementById('stat-avg-rating').textContent = '0.0';
    }
}

async function fetchTouristSpots() {
    try {
        console.log('🔍 Fetching tourist spots for home page...');
        const res = await fetch('/spotowner/my-spots/data');

        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }

        const data = await res.json();
        console.log('📦 Received spots data:', data);

        if (!data || !Array.isArray(data)) {
            console.error('❌ Invalid data received');
            window.sharedTouristSpots = [];
        } else {
            console.log('✅ Successfully loaded', data.length, 'spots');

            // Fetch analytics for each spot
            const spotsWithAnalytics = await Promise.all(
                data.map(async(spot) => {
                    try {
                        const analyticsRes = await fetch(`/spotowner/api/spot-analytics/${spot.spot_id}`);
                        const analytics = await analyticsRes.json();

                        return {
                            id: spot.spot_id,
                            name: spot.spot_name,
                            location: spot.location,
                            description: spot.description,
                            images: spot.images && spot.images.length > 0 ? spot.images : [spot.image],
                            price: spot.price_per_person,
                            maxVisitors: spot.capacity,
                            openTime: spot.opening_time,
                            closeTime: spot.closing_time,
                            rating: analytics.rating || 0,
                            reviews: analytics.reviews || 0,
                            bookings: analytics.bookings || 0,
                            revenue: analytics.revenue || 0,
                            visitors: analytics.visitors || 0,
                            totalVisits: analytics.visitors || 0,
                            status: spot.status,
                            amenities: spot.amenities || 'Basic amenities',
                            highlights: spot.highlights || ['Great location', 'Beautiful views']
                        };
                    } catch (err) {
                        console.error(`Error fetching analytics for spot ${spot.spot_id}:`, err);
                        // Return spot without analytics
                        return {
                            id: spot.spot_id,
                            name: spot.spot_name,
                            location: spot.location,
                            description: spot.description,
                            images: spot.images && spot.images.length > 0 ? spot.images : [spot.image],
                            price: spot.price_per_person,
                            maxVisitors: spot.capacity,
                            openTime: spot.opening_time,
                            closeTime: spot.closing_time,
                            rating: 0,
                            reviews: 0,
                            bookings: 0,
                            revenue: 0,
                            visitors: 0,
                            totalVisits: 0,
                            status: spot.status,
                            amenities: spot.amenities || 'Basic amenities',
                            highlights: spot.highlights || ['Great location', 'Beautiful views']
                        };
                    }
                })
            );

            window.sharedTouristSpots = spotsWithAnalytics;
        }

        console.log('✅ Data mapped and ready');

    } catch (err) {
        console.error('❌ Error fetching tourist spots:', err);
        window.sharedTouristSpots = [];
    }
}

async function initHomePage() {
    console.log('🎯 [home.js] initHomePage called');

    // Fetch dashboard analytics first
    await fetchDashboardAnalytics();

    // Then fetch spots with their individual analytics
    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.log('⚠️ [home.js] No data available, attempting to fetch...');
        await fetchTouristSpots();
    }

    // Load the grid with the data
    loadTouristSpotsGrid();

    // Initialize all charts AFTER data is loaded
    initializeCharts();
}

function updateOverviewStats() {
    // Prefer server-side analytics for accuracy. Fallback to client-side aggregation only if server fails.
    (async () => {
        try {
            const res = await fetch('/spotowner/api/dashboard-analytics', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Server analytics fetch failed');
            const data = await res.json();

            // Update stat cards using the server values
            const totalSpots = data.totalSpots ?? 0;
            const totalBookings = data.totalBookings ?? 0;
            const totalRevenue = data.totalRevenue ?? 0;
            const averageRating = (data.averageRating !== undefined) ? data.averageRating : 0;

            const totalSpotsEl = document.getElementById('stat-total-spots');
            const totalBookingsEl = document.getElementById('stat-total-bookings');
            const totalRevenueEl = document.getElementById('stat-total-revenue');
            const avgRatingEl = document.getElementById('stat-avg-rating');

            if (totalSpotsEl) totalSpotsEl.textContent = totalSpots;
            if (totalBookingsEl) totalBookingsEl.textContent = totalBookings;
            if (totalRevenueEl) totalRevenueEl.textContent = `₱${(totalRevenue || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
            if (avgRatingEl) avgRatingEl.textContent = averageRating;
            const avgDesc = document.querySelector('#stat-avg-rating')?.closest('.stat-card')?.querySelector('.stat-description');
            if (avgDesc) avgDesc.textContent = `Across ${spots.filter(s => (s.reviews||0) > 0).length} rated spots`;

            console.log('✅ Overview stats updated from server:', { totalSpots, totalBookings, totalRevenue, averageRating });
        } catch (err) {
            console.warn('⚠️ Falling back to client aggregation for overview stats:', err);

            // Fallback: compute from sharedTouristSpots
            const spots = window.sharedTouristSpots || [];
            const totalSpots = spots.length;
            const totalBookings = spots.reduce((sum, spot) => sum + (spot.bookings || 0), 0);
            const totalRevenue = spots.reduce((sum, spot) => sum + (spot.revenue || 0), 0);
            const averageRating = spots.length > 0 ?
                (spots.reduce((sum, spot) => sum + (spot.rating || 0), 0) / spots.length).toFixed(1) :
                0;

            const totalSpotsEl = document.getElementById('stat-total-spots');
            const totalBookingsEl = document.getElementById('stat-total-bookings');
            const totalRevenueEl = document.getElementById('stat-total-revenue');
            const avgRatingEl = document.getElementById('stat-avg-rating');

            if (totalSpotsEl) totalSpotsEl.textContent = totalSpots;
            if (totalBookingsEl) totalBookingsEl.textContent = totalBookings;
            if (totalRevenueEl) totalRevenueEl.textContent = `₱${(totalRevenue || 0).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
            if (avgRatingEl) avgRatingEl.textContent = averageRating;
            const avgDesc2 = document.querySelector('#stat-avg-rating')?.closest('.stat-card')?.querySelector('.stat-description');
            if (avgDesc2) avgDesc2.textContent = `Across ${spots.filter(s => (s.reviews||0) > 0).length} rated spots`;

            console.log('✅ Overview stats updated from client aggregation:', { totalSpots, totalBookings, totalRevenue, averageRating });
        }
    })();
}

// NOTE: periodic refresh is handled by the page-level `refreshInterval` logic
// in the view (visibilitychange + 30s interval). Avoid duplicating intervals
// here to prevent overlapping repeated updates.

function initializeCharts() {
    // Guard: Chart must be loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        return;
    }

    console.log('📊 Initializing charts with data:', window.sharedTouristSpots);

    // Color scheme
    const colors = {
        primary: '#4A90E2',
        secondary: '#E74C3C',
        tertiary: '#2ECC71',
        gradient1: 'rgba(74, 144, 226, 0.2)',
        gradient2: 'rgba(231, 76, 60, 0.2)',
        gradient3: 'rgba(46, 204, 113, 0.2)',
    };

    // Utility to safely get canvas element
    const getEl = (id) => document.getElementById(id);

    // Check if we have data
    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.warn('⚠️ No data available for charts');
        // Show "No Data" charts
        initNoDataCharts(colors);
        return;
    }

    const spots = window.sharedTouristSpots.slice(0, 3); // Get first 3 spots
    const spotColors = [colors.primary, colors.secondary, colors.tertiary];
    const spotGradients = [colors.gradient1, colors.gradient2, colors.gradient3];

    // 1. Revenue Trend Line Chart - Using API data
    const revenueTrendCtx = getEl('revenueTrendChart');
    if (revenueTrendCtx) {
        // Fetch monthly revenue data from API (per-spot series across all spots)
        fetch('/spotowner/api/monthly-revenue?onlyPaid=0&bySpot=1&months=6')
            .then(res => res.json())
            .then(data => {
                try {
                    // New API shape: { months: [...], monthly: [...], by_spot: [{spot_id, spot_name, series: [...]}, ...] }
                    if (data && typeof data === 'object') {
                        // Prefer per-spot multi-line series if available
                        if (Array.isArray(data.by_spot) && data.by_spot.length > 0 && Array.isArray(data.months)) {
                            const labels = data.months;
                            const datasets = data.by_spot.map((spot, idx) => ({
                                label: spot.spot_name || `Spot ${spot.spot_id || idx+1}`,
                                data: (Array.isArray(spot.series) ? spot.series : []),
                                borderColor: spotColors[idx % spotColors.length],
                                backgroundColor: spotGradients[idx % spotGradients.length],
                                tension: 0.4,
                                fill: true,
                            }));

                            new Chart(revenueTrendCtx, {
                                type: 'line',
                                data: { labels, datasets },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    plugins: { legend: { position: 'bottom' } },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } }
                                        }
                                    }
                                }
                            });
                            return;
                        }

                        // Fallback to single monthly array (legacy shape)
                        if (Array.isArray(data.monthly) && data.monthly.length > 0) {
                            const labels = data.monthly.map(item => item.month);
                            const revenueData = data.monthly.map(item => parseFloat(item.revenue) || 0);

                            new Chart(revenueTrendCtx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Monthly Revenue',
                                        data: revenueData,
                                        borderColor: colors.primary,
                                        backgroundColor: colors.gradient1,
                                        tension: 0.4,
                                        fill: true,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    plugins: { legend: { position: 'bottom' } },
                                    scales: {
                                        y: { beginAtZero: true, ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } } }
                                    }
                                },
                            });
                            return;
                        }
                    }

                    // Fallback: use spot revenue data
                    createRevenueChartFromSpots(revenueTrendCtx, spots, spotColors, spotGradients, colors);
                } catch (e) {
                    console.error('Error processing revenue data:', e);
                    createRevenueChartFromSpots(revenueTrendCtx, spots, spotColors, spotGradients, colors);
                }
            })
            .catch(err => {
                console.error('Error fetching revenue data:', err);
                createRevenueChartFromSpots(revenueTrendCtx, spots, spotColors, spotGradients, colors);
            });
    }

    // 2. Booking Trend Multi-line Chart - Using API data
    const bookingTrendCtx = getEl('bookingTrendChart');
    if (bookingTrendCtx) {
        fetch('/spotowner/api/booking-trends')
            .then(res => res.json())
            .then(data => {
                if (data && Array.isArray(data) && data.length > 0) {
                    const labels = data.map(item => item.month);
                    const bookingData = data.map(item => parseInt(item.bookings) || 0);

                    new Chart(bookingTrendCtx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Monthly Bookings',
                                data: bookingData,
                                borderColor: colors.secondary,
                                backgroundColor: colors.secondary,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { position: 'bottom' } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 2 },
                                },
                            },
                        },
                    });
                } else {
                    // Fallback: use spot booking data
                    createBookingChartFromSpots(bookingTrendCtx, spots, spotColors, colors);
                }
            })
            .catch(err => {
                console.error('Error fetching booking data:', err);
                createBookingChartFromSpots(bookingTrendCtx, spots, spotColors, colors);
            });
    }
}

// Helper function: Create revenue chart from spot data
function createRevenueChartFromSpots(ctx, spots, spotColors, spotGradients, colors) {
    const datasets = spots.map((spot, idx) => ({
        label: spot.name,
        data: [
            (spot.revenue || 0) * 0.8,
            (spot.revenue || 0) * 0.9,
            (spot.revenue || 0) * 0.85,
            (spot.revenue || 0) * 1.1,
            (spot.revenue || 0) * 0.95,
            (spot.revenue || 0)
        ],
        borderColor: spotColors[idx],
        backgroundColor: spotGradients[idx],
        tension: 0.4,
        fill: true,
    }));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                    },
                },
            },
        },
    });
}

// Helper function: Create booking chart from spot data
function createBookingChartFromSpots(ctx, spots, spotColors, colors) {
    const datasets = spots.map((spot, idx) => ({
        label: spot.name,
        data: [
            Math.floor((spot.bookings || 0) * 0.7),
            Math.floor((spot.bookings || 0) * 0.8),
            Math.floor((spot.bookings || 0) * 0.75),
            Math.floor((spot.bookings || 0) * 1.0),
            Math.floor((spot.bookings || 0) * 0.9),
            Math.floor((spot.bookings || 0))
        ],
        borderColor: spotColors[idx],
        backgroundColor: spotColors[idx],
        tension: 0.4,
        borderWidth: 3,
        pointRadius: 5,
        pointHoverRadius: 7,
    }));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2 },
                },
            },
        },
    });
}

// Helper function: Show "No Data" charts
function initNoDataCharts(colors) {
    const revenueTrendCtx = document.getElementById('revenueTrendChart');
    const bookingTrendCtx = document.getElementById('bookingTrendChart');

    if (revenueTrendCtx) {
        new Chart(revenueTrendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'No Data',
                    data: [0, 0, 0, 0, 0, 0],
                    borderColor: colors.primary,
                    backgroundColor: colors.gradient1,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    if (bookingTrendCtx) {
        new Chart(bookingTrendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'No Data',
                    data: [0, 0, 0, 0, 0, 0],
                    borderColor: colors.secondary,
                    backgroundColor: colors.secondary,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }
}



function loadTouristSpotsGrid() {
    const grid = document.getElementById('touristSpotsGrid');
    if (!grid) {
        console.error('❌ Tourist spots grid element not found!');
        return;
    }

    console.log('🎨 Loading tourist spots grid...');
    console.log('📊 window.sharedTouristSpots:', window.sharedTouristSpots);
    console.log('📊 Number of spots:', window.sharedTouristSpots ? window.sharedTouristSpots.length : 'undefined');

    if (!window.sharedTouristSpots || window.sharedTouristSpots.length === 0) {
        console.warn('⚠️ No tourist spots data available!');
        grid.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    <h5><i class="bi bi-info-circle me-2"></i>No Spots Found</h5>
                    <p>You haven't added any tourist spots yet.</p>
                    <a href="/spotowner/mySpots" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Your First Spot
                    </a>
                </div>
            </div>
        `;
        return;
    }

    console.log('✅ Rendering', window.sharedTouristSpots.length, 'spots...');

    grid.innerHTML = window.sharedTouristSpots.map(spot => {
                // Ensure images array exists and has valid data
                    const imageUrl = spot.images && spot.images.length > 0 ?
                    spot.images[0] :
                    (spot.image || '/uploads/spots/Spot-No-Image.png');

                const imageCount = spot.images ? spot.images.length : 0;

                return `
        <div class="col-lg-4 col-md-6">
            <div class="custom-card h-100 d-flex flex-column">
                <div class="position-relative">
                    <img src="${imageUrl}" 
                         alt="${spot.name}" 
                         class="img-fluid rounded-top" 
                         style="height: 220px; width: 100%; object-fit: cover;"
                         onerror="this.src='/uploads/spots/Spot-No-Image.png'">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge ${spot.status === 'active' ? 'bg-success' : 'bg-secondary'}">${spot.status}</span>
                    </div>
                    ${imageCount > 1 ? `
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-dark bg-opacity-75">
                                <i class="bi bi-images me-1"></i>${imageCount} photos
                            </span>
                        </div>
                    ` : ''}
                </div>
                <div class="custom-card-body flex-grow-1 d-flex flex-column">
                    <h4 class="custom-card-title" style="min-height: 60px;">${spot.name}</h4>
                    <p class="text-muted-custom mb-2" style="min-height: 45px;">
                        <i class="bi bi-geo-alt me-1"></i>${spot.location}
                    </p>
                    
                    <div class="d-flex align-items-center gap-3 mb-3" style="min-height: 30px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-medium">${spot.rating || 0}</span>
                            <span class="text-muted-custom small ms-1">(${spot.reviews || 0})</span>
                        </div>
                        <div class="text-muted-custom">|</div>
                        <div class="text-ocean-medium fw-medium">₱${spot.price}/person</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 bg-beige rounded text-center">
                                <div class="small text-muted-custom">Bookings</div>
                                <div class="fw-medium">${spot.bookings || 0}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-beige rounded text-center">
                                <div class="small text-muted-custom">Revenue</div>
                                <div class="fw-medium">₱${(spot.revenue || 0).toLocaleString()}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-auto">
                        <button class="btn btn-primary flex-fill" onclick="viewSpotDetails(${spot.id})">
                            <i class="bi bi-eye me-1"></i>View Details
                        </button>
                        <button class="btn btn-outline-primary" onclick="manageSpot(${spot.id})" title="Manage">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    }).join('');
}

async function viewSpotDetails(spotId) {
    console.log('🔍 Looking for spot with ID:', spotId);
    console.log('📊 Available spots:', window.sharedTouristSpots);
    
    // Try to find the spot by id or spot_id
    const spot = window.sharedTouristSpots.find(s => {
        return s.id === spotId || 
               s.spot_id === spotId || 
               s.id == spotId || 
               s.spot_id == spotId;
    });
    
    if (!spot) {
        console.error('❌ Spot not found with ID:', spotId);
        console.log('Available spot IDs:', window.sharedTouristSpots.map(s => ({id: s.id, spot_id: s.spot_id})));
        alert('Spot not found!');
        return;
    }
    
    console.log('✅ Found spot:', spot);
    
    // Show the modal with cached data immediately
    const modalTitle = document.getElementById('spotModalTitle');
    const modalBody = document.getElementById('spotModalBody');
    
    if (modalTitle) {
        modalTitle.textContent = spot.name || spot.spot_name;
    }
    
    if (modalBody) {
        modalBody.innerHTML = generateSimpleViewModalContent(spot);
    }
    
    const modal = new bootstrap.Modal(document.getElementById('viewSpotModal'));
    modal.show();
}

function generateSimpleViewModalContent(spot) {
    // Handle both naming conventions
    const spotName = spot.name || spot.spot_name || 'Unknown';
    const spotLocation = spot.location || 'Unknown location';
    const spotDescription = spot.description || 'No description available';
    const spotPrice = spot.price || spot.price_per_person || 0;
    const spotCapacity = spot.maxVisitors || spot.capacity || 0;
    const spotOpenTime = spot.openTime || spot.opening_time || 'N/A';
    const spotCloseTime = spot.closeTime || spot.closing_time || 'N/A';
    const spotStatus = spot.status || 'inactive';
    const spotId = spot.id || spot.spot_id;
    
    // Get the first image or use a placeholder
    const mainImage = spot.images && spot.images.length > 0 ? spot.images[0] : '/uploads/spots/Spot-No-Image.png';
    const imageCount = spot.images ? spot.images.length : 0;
    
    return `
        <div class="container-fluid">
            <!-- Main Image -->
            <div class="row mb-4">
                <div class="col-12">
                    <img src="${mainImage}" 
                         alt="${spotName}" 
                         class="img-fluid rounded" 
                         style="width: 100%; height: 400px; object-fit: cover;"
                         onerror="this.src='/uploads/spots/Spot-No-Image.png'">
                    ${imageCount > 1 ? `
                        <p class="text-center text-muted mt-2">
                            <i class="bi bi-images"></i> ${imageCount} photos available
                        </p>
                    ` : ''}
                </div>
            </div>

            <!-- Spot Information -->
            <div class="row g-4">
                <div class="col-md-8">
                    <h4 class="mb-3">${spotName}</h4>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bi bi-geo-alt"></i> Location</h6>
                        <p>${spotLocation}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bi bi-info-circle"></i> Description</h6>
                        <p>${spotDescription}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="bi bi-currency-dollar"></i> Price per Person
                                    </h6>
                                    <h4 class="card-title text-primary">₱${spotPrice}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="bi bi-people"></i> Max Capacity
                                    </h6>
                                    <h4 class="card-title text-primary">${spotCapacity} visitors</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted"><i class="bi bi-clock"></i> Operating Hours</h6>
                        <p><strong>Open:</strong> ${spotOpenTime} - ${spotCloseTime}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Badge -->
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Status</h6>
                            <span class="badge ${spotStatus === 'active' ? 'bg-success' : 'bg-secondary'} fs-5">
                                ${spotStatus}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Quick Stats</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-star-fill text-warning"></i> Rating</span>
                                <strong>${spot.rating || 0}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-chat-left-text"></i> Reviews</span>
                                <strong>${spot.reviews || 0}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-calendar-check"></i> Bookings</span>
                                <strong>${spot.bookings || 0}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-cash-stack"></i> Revenue</span>
                                <strong>₱${(spot.revenue || 0).toLocaleString()}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-people"></i> Visitors</span>
                                <strong>${spot.visitors || 0}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="manageSpot(${spotId})">
                            <i class="bi bi-pencil"></i> Edit Spot
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateViewSpotModalContent(spot) {
    // Fix image paths - ensure they're actual URLs, not template literals
    let images = [];
    if (spot.images && Array.isArray(spot.images) && spot.images.length > 0) {
        images = spot.images;
    } else if (spot.image) {
        images = [spot.image];
    } else {
        images = ['/uploads/default.jpg'];
    }
    
    const totalVisits = spot.totalVisits || spot.total_visits || 0;
    const rating = spot.rating || 0;
    const reviews = spot.reviews || 0;
    const spotName = spot.spot_name || spot.name || 'Unknown Spot';
    const description = spot.description || 'No description available';
    const location = spot.location || 'Unknown location';
    const pricePerPerson = spot.price_per_person || spot.price || 0;
    const capacity = spot.capacity || spot.maxVisitors || 0;
    const openingTime = spot.opening_time || spot.openTime || 'N/A';
    const closingTime = spot.closing_time || spot.closeTime || 'N/A';
    const status = spot.status || 'inactive';

    return `
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Basic Information (Read-Only) -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Information</h3>
                        <p class="custom-card-description">Details about your tourist spot</p>
                    </div>
                    <div class="custom-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Spot Name</label>
                            <p class="text-muted-custom">${spotName}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <p class="text-muted-custom">${description}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <p class="text-muted-custom">
                                <i class="bi bi-geo-alt me-1"></i>${location}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Capacity (Read-Only) -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Pricing & Capacity</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Price per Person</label>
                                <p class="text-muted-custom">
                                    <i class="bi bi-currency-dollar me-1"></i>₱${pricePerPerson}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Max Visitors</label>
                                <p class="text-muted-custom">
                                    <i class="bi bi-people me-1"></i>${capacity} visitors
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours (Read-Only) -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Operating Hours</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Opening Time</label>
                                <p class="text-muted-custom">
                                    <i class="bi bi-clock me-1"></i>${openingTime}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Closing Time</label>
                                <p class="text-muted-custom">
                                    <i class="bi bi-clock me-1"></i>${closingTime}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="manageSpot(${spot.spot_id || spot.id})">
                        <i class="bi bi-pencil me-1"></i>Edit Spot
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Spot Status -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Status</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label class="form-label mb-0 fw-bold">Current Status</label>
                                <p class="text-muted-custom small mb-0">Spot availability</p>
                            </div>
                            <div>
                                <span class="badge ${status === 'active' ? 'bg-success' : 'bg-secondary'} fs-6">
                                    ${status}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spot Images -->
                <div class="custom-card mb-4">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Spot Images</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="position-relative mb-3">
                            <img src="${images[0]}" alt="${spotName}" 
                                class="rounded img-fluid" 
                                style="width: 100%; height: 200px; object-fit: cover;"
                                onerror="this.src='/uploads/default.jpg'">
                        </div>
                        ${images.length > 1 ? `
                            <p class="small text-muted-custom text-center">
                                <i class="bi bi-images me-1"></i>${images.length} photos available
                            </p>
                        ` : ''}
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="custom-card">
                    <div class="custom-card-header">
                        <h3 class="custom-card-title">Quick Stats</h3>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted-custom">Total Visits</span>
                            <span class="fw-medium">${totalVisits.toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted-custom">Rating</span>
                            <span class="fw-medium">⭐ ${rating}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-custom">Reviews</span>
                            <span class="fw-medium">${reviews}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function changeSpotImage(spotId, direction) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot || spot.images.length <= 1) return;
    
    currentImageIndex += direction;
    
    // Loop around
    if (currentImageIndex < 0) {
        currentImageIndex = spot.images.length - 1;
    } else if (currentImageIndex >= spot.images.length) {
        currentImageIndex = 0;
    }
    
    // Update image
    const imgElement = document.getElementById('spotDetailImage');
    if (imgElement) {
        imgElement.src = spot.images[currentImageIndex];
    }
    
    // Update counter
    const counter = document.getElementById('imageCounter');
    if (counter) {
        counter.textContent = `${currentImageIndex + 1} / ${spot.images.length}`;
    }
    
    // Update thumbnails
    updateThumbnailBorders();
}

function setSpotImage(spotId, index) {
    const spot = window.sharedTouristSpots.find(s => s.id === spotId);
    if (!spot) return;
    
    currentImageIndex = index;
    
    // Update image
    const imgElement = document.getElementById('spotDetailImage');
    if (imgElement) {
        imgElement.src = spot.images[currentImageIndex];
    }
    
    // Update counter
    const counter = document.getElementById('imageCounter');
    if (counter) {
        counter.textContent = `${currentImageIndex + 1} / ${spot.images.length}`;
    }
    
    // Update thumbnails
    updateThumbnailBorders();
}

function updateThumbnailBorders() {
    const thumbnails = document.querySelectorAll('.spot-thumbnail');
    thumbnails.forEach((thumb, idx) => {
        if (idx === currentImageIndex) {
            thumb.classList.add('border-primary');
            thumb.classList.remove('border-secondary');
        } else {
            thumb.classList.remove('border-primary');
            thumb.classList.add('border-secondary');
        }
    });
}

function manageSpot(spotId) {
    // Close the modal if it's open
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewSpotModal'));
    if (modal) {
        modal.hide();
    }
    
    // Redirect to manage spot page
    window.location.href = '/spotowner/mySpots';
}

function saveNewSpot() {
    const name = document.getElementById('newSpotName').value;
    const location = document.getElementById('newSpotLocation').value;
    const description = document.getElementById('newSpotDescription').value;
    const price = document.getElementById('newSpotPrice').value;
    const capacity = document.getElementById('newSpotCapacity').value;
    
    if (!name || !location || !description || !price || !capacity) {
        alert('Please fill in all fields');
        return;
    }
    
    alert(`New spot "${name}" added successfully!`);
    bootstrap.Modal.getInstance(document.getElementById('addNewSpotModal')).hide();
    
    // Clear form
    document.getElementById('addSpotForm').reset();
    
    // In real implementation, you would add the new spot to the array and reload the grid
}

// Make functions available globally
window.renderHomePage = renderHomePage;
window.initHomePage = initHomePage;
window.fetchTouristSpots = fetchTouristSpots; // Add this line
window.viewSpotDetails = viewSpotDetails;
window.changeSpotImage = changeSpotImage;
window.setSpotImage = setSpotImage;
window.manageSpot = manageSpot;
window.saveNewSpot = saveNewSpot;
window.loadTouristSpotsGrid = loadTouristSpotsGrid;
window.initializeCharts = initializeCharts;

console.log('home.js loaded successfully');
console.log('Available functions:', {
    renderHomePage: typeof renderHomePage,
    initHomePage: typeof initHomePage,
    fetchTouristSpots: typeof fetchTouristSpots,
    loadTouristSpotsGrid: typeof loadTouristSpotsGrid,
    initializeCharts: typeof initializeCharts
});