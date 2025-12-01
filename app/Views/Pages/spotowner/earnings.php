<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tourist Spot Owner Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css")?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/earnings-mobile.css')?>">
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
                        <a href="/spotowner/dashboard" class="sidebar-link " data-page="home">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/bookings" class="sidebar-link" data-page="bookings">
                            <i class="bi bi-calendar-check"></i>
                            <span>Booking Management</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="/spotowner/earnings" class="sidebar-link active" data-page="earnings">
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

            
        </nav>

        <!-- Main Content -->
        <div class="flex-fill d-flex flex-column">
            <!-- Mobile Header -->
            <?= view('Pages/spotowner/_mobile_header', ['subtitle' => 'Earnings & Reports', 'FullName' => $FullName ?? null, 'email' => $email ?? null]) ?>

            <!-- Page Content - This will be populated by JavaScript -->

            <!-- Page Content -->
            <main class="flex-fill p-3 p-lg-4" id="mainContent">
                <!-- Content will be loaded here dynamically -->
                <div class="container-fluid">
                    <div class="mb-4">
                        <h3>Earnings & Reports</h3>
                        <p class="text-muted-custom">           </p>
                    </div>

                    <!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value" id="statTotalRevenue">₱<?= number_format($totalRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">All time</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-value" id="statMonthlyRevenue">₱<?= number_format($monthlyRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">
                        <?php if(isset($comparison) && $comparison['change'] != 0): ?>
                            <span class="<?= $comparison['direction'] == 'up' ? 'text-success' : 'text-danger' ?>">
                                <?= $comparison['direction'] == 'up' ? '↑' : '↓' ?> 
                                <?= abs(number_format($comparison['change'], 1)) ?>%
                            </span> from last month
                        <?php else: ?>
                            No change from last month
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Avg. Per Booking</div>
                    <div class="stat-value" id="statAverageRevenue">₱<?= number_format($averageRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">Based on <span id="statBookingsCount"><?= $totalBookings ?? 0 ?></span> bookings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" id="statPendingRevenue">₱<?= number_format($pendingRevenue ?? 0, 2) ?></div>
                    <div class="stat-description">From pending bookings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

                    <!-- Interactive Charts Section -->
                    <div class="custom-card mb-4">
                        <div class="custom-card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h3 class="custom-card-title mb-1">Revenue & Booking Analytics</h3>
                                    <p class="custom-card-description mb-0">Select a view to analyze your performance</p>
                                </div>
                                <div class="btn-group" role="group" aria-label="Chart selection">
                                    <button type="button" class="btn btn-outline-primary active" id="btnMonthlyRevenue" onclick="switchChart('monthly-revenue', 'bar')">
                                        <i class="bi bi-bar-chart me-1"></i>Monthly Revenue (Bar)
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="btnWeeklyRevenue" onclick="switchChart('weekly-revenue', 'line')">
                                        <i class="bi bi-graph-up me-1"></i>Weekly Revenue (Line)
                                    </button>
                                    <!-- Booking Trends button removed -->
                                </div>
                            </div>
                        </div>
                        <div class="custom-card-body">
                            <!-- Chart Container -->
                            <div id="chartContainer" style="min-height: 450px; position: relative;">
                                <canvas id="mainChart" style="width: 100%; height: 450px;"></canvas>
                            </div>

                            <!-- Chart Info -->
                            <div id="chartInfo" class="mt-3 p-3 bg-beige rounded">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Period</div>
                                        <div class="fw-medium" id="chartPeriod">Last 6 Months</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Total</div>
                                        <div class="fw-medium text-ocean-medium" id="chartTotal">₱15,750</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted-custom small">Average</div>
                                        <div class="fw-medium" id="chartAverage">₱2,625/month</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Recent Transactions & Top Performing Days Row -->
                   <div class="row g-4 mb-4">
                        <!-- Recent Transactions -->
                        <div class="col-lg-6">
                            <div class="custom-card h-100">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Recent Transactions</h3>
                                    <p class="custom-card-description">Latest payment activities</p>
                                </div>
                                <div class="custom-card-body">
                                    <div id="recentTransactionsList">
                                        <?php if (!empty($recentTransactions)): ?>
                                            <div class="list-group list-group-flush">
                                                <?php foreach ($recentTransactions as $transaction): ?>
                                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-medium"><?= esc($transaction['customer_name']) ?></div>
                                                            <small class="text-muted-custom">
                                                                <?= date('M d, Y', strtotime($transaction['booking_date'])) ?>
                                                                <?php if ($transaction['booking_status'] == 'Pending'): ?>
                                                                    <span class="badge bg-warning text-dark ms-2">Pending</span>
                                                                <?php elseif(in_array($transaction['booking_status'], ['Confirmed','Checked-in','Checked-out','Completed'])): ?>
                                                                    <span class="badge bg-success ms-2"><?= esc($transaction['booking_status']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary ms-2"><?= esc($transaction['booking_status']) ?></span>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                        <span class="fw-medium <?= $transaction['booking_status'] == 'Confirmed' ? 'text-success' : 'text-muted' ?>">
                                                            <?= $transaction['booking_status'] == 'Confirmed' ? '+' : '' ?>₱<?= number_format($transaction['total_price'], 2) ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-4 text-muted-custom">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mb-0 mt-2">No recent transactions</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performing Days -->
                        <div class="col-lg-6">
                            <div class="custom-card h-100">
                                <div class="custom-card-header">
                                    <h3 class="custom-card-title">Top Performing Days</h3>
                                    <p class="custom-card-description">Highest revenue days this month</p>
                                </div>
                                <div class="custom-card-body">
                                    <?php if (!empty($topDays)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($topDays as $day): ?>
                                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-medium"><?= esc($day['day_name']) ?>, <?= esc($day['formatted_date']) ?></div>
                                                        <small class="text-muted-custom"><?= $day['bookings'] ?> booking<?= $day['bookings'] != 1 ? 's' : '' ?></small>
                                                    </div>
                                                    <span class="fw-medium text-ocean-medium">₱<?= number_format($day['revenue'], 2) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted-custom">
                                            <i class="bi bi-calendar-x fs-1"></i>
                                            <p class="mb-0 mt-2">No bookings this month yet</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h3 class="custom-card-title">Export Reports</h3>
                            <p class="custom-card-description">Download financial reports</p>
                        </div>
                        <div class="custom-card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Export as PDF
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export as Excel
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-text me-2"></i>Export as CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- 1. Chart.js (MUST BE FIRST) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- 2. Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 3. SweetAlert2 (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 4. Sidebar Toggle (IMPORTANT!) -->
    <script src="<?= base_url('assets/js/sidebar.js')?>"></script>

    <!-- 5. Page-specific scripts -->
    <script src="<?= base_url('assets/js/spotownerJS/earnings.js')?>"></script>
    
    <!-- Notification System -->
    <script src="<?= base_url('assets/js/spotownerJS/notifications.js') ?>"></script>
    
    <!-- Inline: dynamic earnings summary & recent transactions updater -->
    <script>
    (function(){
        // Use relative paths to avoid origin/subfolder mismatches on shared hosts
        const base = '';

        let _authBannerShown = false;
        function showAuthExpiredBanner(bodyText){
            if(_authBannerShown) return;
            _authBannerShown = true;
            try{
                const banner = document.createElement('div');
                banner.id = 'authExpiredBanner';
                banner.className = 'alert alert-warning text-center';
                banner.style.position = 'fixed';
                banner.style.top = '0';
                banner.style.left = '0';
                banner.style.right = '0';
                banner.style.zIndex = '1050';
                banner.style.margin = '0';
                banner.style.borderRadius = '0';
                banner.innerHTML = `<strong>Session expired</strong> — you will be redirected to the login page. ${bodyText ? '<small class="d-block">' + escapeHtml(bodyText).slice(0,200) + '...</small>' : ''} <button class="btn btn-sm btn-link" id="authNowBtn">Sign in now</button>`;
                document.body.appendChild(banner);
                document.getElementById('authNowBtn').addEventListener('click', function(){ window.location.href = '/users/login'; });
                // Auto-redirect after 3s
                setTimeout(()=> { window.location.href = '/users/login'; }, 3000);
            }catch(e){ console.warn('Failed to show auth banner', e); window.location.href = '/users/login'; }
        }

        async function fetchJson(url){
            const r = await fetch(url, {credentials: 'same-origin'});
            if(!r.ok){
                // try to read response body for more details
                let body = null;
                try{ body = await r.text(); }catch(e){ body = '<unable to read body>'; }
                console.error(`fetchJson: ${url} returned ${r.status} ${r.statusText}:`, body);
                // If unauthorized, show friendly banner and redirect
                if(r.status === 401){
                    showAuthExpiredBanner(body);
                }
                // surface a clearer error for callers
                const err = new Error('HTTP ' + r.status + ' ' + r.statusText);
                err.status = r.status;
                err.body = body;
                throw err;
            }
            return r.json();
        }

        // Update summary cards: monthly revenue, avg per booking, pending revenue
        async function updateEarningsSummary(){
            try{
                const monthly = await fetchJson(base + '/spotowner/api/monthly-revenue');
                const bookings = await fetchJson(base + '/spotowner/getBookings');

                // Determine current month key YYYY-MM
                const now = new Date();
                const curKey = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0');

                let thisMonthRow = monthly.find(m => m.month === curKey) || null;
                let thisMonthRevenue = thisMonthRow ? parseFloat(thisMonthRow.revenue) : 0;
                let thisMonthBookings = thisMonthRow ? parseInt(thisMonthRow.bookings || 0) : 0;

                // Fallback: compute from bookings list if API did not return current month
                if(thisMonthBookings === 0){
                    const filtered = bookings.filter(b => {
                        const d = new Date(b.booking_date);
                        return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
                    });
                    thisMonthBookings = filtered.length;
                    thisMonthRevenue = filtered.reduce((s,b) => s + (parseFloat(b.total_price)||0), 0);
                }

                // Pending revenue: sum unpaid/pending bookings in current month
                const pending = bookings.reduce((s,b) => {
                    const d = new Date(b.booking_date);
                    if(d.getFullYear()===now.getFullYear() && d.getMonth()===now.getMonth()){
                        const pay = (b.payment_status || '').toLowerCase();
                        if(pay !== 'paid') return s + (parseFloat(b.total_price)||0);
                    }
                    return s;
                }, 0);

                // Average per booking for this month
                const avg = thisMonthBookings > 0 ? (thisMonthRevenue / thisMonthBookings) : 0;

                // Update DOM
                const toCurrency = v => '₱' + Number(v || 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
                const elMonthly = document.getElementById('statMonthlyRevenue');
                if(elMonthly) elMonthly.textContent = toCurrency(thisMonthRevenue);

                const elAvg = document.getElementById('statAverageRevenue');
                if(elAvg) elAvg.textContent = toCurrency(avg);

                const elPending = document.getElementById('statPendingRevenue');
                if(elPending) elPending.textContent = toCurrency(pending);

                const elBookings = document.getElementById('statBookingsCount');
                if(elBookings) elBookings.textContent = thisMonthBookings;

                // Set total revenue (sum of returned months) if present
                try{
                    const totalRevenue = Array.isArray(monthly) ? monthly.reduce((s,m)=> s + (parseFloat(m.revenue)||0), 0) : 0;
                    const elTotal = document.getElementById('statTotalRevenue');
                    if(elTotal) elTotal.textContent = toCurrency(totalRevenue);
                }catch(e){}

                // Also warm-up weekly endpoint so charts can use cached responses
                try{
                    fetchJson(base + '/spotowner/api/weekly-revenue').catch(()=>{});
                }catch(e){}

            }catch(err){
                console.warn('updateEarningsSummary failed', err);
            }
        }

        // Render recent transactions: use /spotowner/getBookings and pick latest 5
        async function updateRecentTransactions(){
            try{
                const bookings = await fetchJson(base + '/spotowner/getBookings');
                if(!Array.isArray(bookings)) return;

                bookings.sort((a,b)=> new Date(b.booking_date) - new Date(a.booking_date));
                const recent = bookings.slice(0,5);

                const container = document.getElementById('recentTransactionsList');
                if(!container) return;

                if(recent.length===0){
                    container.innerHTML = `<div class="text-center py-4 text-muted-custom"><i class="bi bi-inbox fs-1"></i><p class="mb-0 mt-2">No recent transactions</p></div>`;
                    return;
                }

                const rows = recent.map(tr => {
                    const name = tr.customer_name || tr.customer || 'Unknown';
                    const date = new Date(tr.booking_date);
                    const dateStr = date.toLocaleString(undefined, {month:'short', day:'2-digit', year:'numeric'});
                    const status = (tr.booking_status || '').toLowerCase();
                    let badgeClass = 'bg-secondary text-white';
                    if(['confirmed','checked-in','checked-in','checked-out','completed'].includes(status)) badgeClass = 'bg-success text-white';
                    else if(status === 'pending') badgeClass = 'bg-warning text-dark';
                    else if(['rejected','cancelled','canceled'].includes(status)) badgeClass = 'bg-danger text-white';

                    const price = Number(tr.total_price||0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

                    return `
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-medium">${escapeHtml(name)}</div>
                                <small class="text-muted-custom">${dateStr} <span class="badge ${badgeClass} ms-2">${escapeHtml(tr.booking_status||'')}</span></small>
                            </div>
                            <span class="fw-medium ${status==='confirmed' ? 'text-success' : 'text-muted'}">${status==='confirmed' ? '+' : ''}₱${price}</span>
                        </div>
                    `;
                }).join('');

                container.innerHTML = `<div class="list-group list-group-flush">${rows}</div>`;

            }catch(err){
                console.warn('updateRecentTransactions failed', err);
            }
        }

        function escapeHtml(s){
            if(!s) return '';
            return String(s).replace(/[&"'<>]/g, function(m){ return ({'&':'&amp;','"':'&quot;','\'':'&#39;','<':'&lt;','>':'&gt;'}[m]); });
        }

        // Initialize on page load (after other scripts)
        document.addEventListener('DOMContentLoaded', function(){
            updateEarningsSummary();
            updateRecentTransactions();

            // Refresh periodically (optional)
            setInterval(updateEarningsSummary, 1000 * 60 * 2); // every 2 minutes
            setInterval(updateRecentTransactions, 1000 * 60 * 2);
        });

    })();
    </script>
</body>

</html>