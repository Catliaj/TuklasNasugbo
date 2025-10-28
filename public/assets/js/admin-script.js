// Tourism Admin Dashboard - JavaScript
// This uses mock data for frontend demonstration
// Backend developers: Replace mock data with actual API calls

// ========================================
// MOCK DATA (Replace with API calls)
// ========================================

const MOCK_DATA = {
    stats: {
        pending_requests: 12,
        total_attractions: 8,
        total_bookings: 8,
        today_bookings: 1,
        pending_reviews: 5,
        active_spots: 8
    },
    
    monthlyBookings: [
        { month: 'May', count: 45 },
        { month: 'Jun', count: 52 },
        { month: 'Jul', count: 61 },
        { month: 'Aug', count: 58 },
        { month: 'Sep', count: 67 },
        { month: 'Oct', count: 73 }
    ],
    
    categoryDistribution: [
        { category: 'Beaches & Resorts', count: 4, color: '#3b82f6' },
        { category: 'Mountains & Hiking', count: 3, color: '#10b981' },
        { category: 'Waterfalls & Rivers', count: 2, color: '#06b6d4' },
        { category: 'Viewpoints & Parks', count: 2, color: '#f59e0b' },
        { category: 'Caves & Hidden Spots', count: 1, color: '#8b5cf6' },
        { category: 'Camping & Glamping', count: 1, color: '#ef4444' }
    ],
    
    recentActivity: [
        { type: 'booking', title: 'New Booking', description: 'BK006 for Hidden Waterfall Trail', time: '5 minutes ago', icon: 'bi-calendar-check', color: 'success' },
        { type: 'review', title: 'New Review', description: '5 stars for Sunset Peak Viewpoint', time: '1 hour ago', icon: 'bi-star-fill', color: 'warning' },
        { type: 'registration', title: 'Registration Request', description: 'New spot owner registration pending', time: '2 hours ago', icon: 'bi-person-plus', color: 'info' },
        { type: 'attraction', title: 'Attraction Updated', description: 'Beach Paradise updated their details', time: '3 hours ago', icon: 'bi-geo-alt', color: 'primary' }
    ],
    
    registrations: [
        {
            id: 1,
            businessName: 'Hidden Paradise Tours',
            ownerName: 'Juan Dela Cruz',
            email: 'juan@hiddenparadise.com',
            phone: '+63 912 111 1111',
            location: 'Barangay San Miguel, Nasugbu, Batangas',
            category: 'Beaches & Resorts',
            description: 'A hidden beach paradise with crystal clear waters and white sand beaches',
            submitted: '2025-10-20',
            status: 'pending',
            documents: {
                bir_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600',
                business_permit: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600',
                dti_registration: 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600',
                mayor_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600'
            }
        },
        {
            id: 2,
            businessName: 'Mountain Adventures PH',
            ownerName: 'Maria Santos',
            email: 'maria@mountainadv.com',
            phone: '+63 912 222 2222',
            location: 'Barangay Alto, Nasugbu, Batangas',
            category: 'Mountains & Hiking Trails',
            description: 'Guided mountain climbing and hiking adventures to Mt. Batulao and Mt. Talamitam',
            submitted: '2025-10-21',
            status: 'pending',
            documents: {
                bir_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600',
                business_permit: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600',
                dti_registration: 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600',
                mayor_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600',
                tourism_accreditation: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600'
            }
        },
        {
            id: 3,
            businessName: 'Coastal Escapes',
            ownerName: 'Pedro Reyes',
            email: 'pedro@coastalescapes.com',
            phone: '+63 912 333 3333',
            location: 'Barangay Baybayon, Nasugbu, Batangas',
            category: 'Beaches & Resorts',
            description: 'Beachfront resort with water sports and island hopping activities',
            submitted: '2025-10-22',
            status: 'pending',
            documents: {
                bir_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600',
                business_permit: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600',
                dti_registration: 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600',
                mayor_permit: 'https://images.unsplash.com/photo-1554224311-beee4240a2f8?w=600'
            }
        }
    ],
    
    attractions: [
        {
            id: 1,
            name: 'Canyon Cove Beach Resort',
            category: 'Beaches & Resorts',
            location: 'Barangay Wawa, Nasugbu',
            owner: 'Juan Dela Cruz',
            rating: 4.8,
            reviews: 24,
            bookings: 156,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400'
        },
        {
            id: 2,
            name: 'Malabrigo Falls',
            category: 'Waterfalls & Rivers',
            location: 'Barangay Looc, Nasugbu',
            owner: 'Maria Santos',
            rating: 4.6,
            reviews: 18,
            bookings: 98,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1523712999610-f77fbcfc3843?w=400'
        },
        {
            id: 3,
            name: 'Mt. Batulao Hiking Trail',
            category: 'Mountains & Hiking Trails',
            location: 'Barangay Alto, Nasugbu',
            owner: 'Pedro Reyes',
            rating: 4.9,
            reviews: 32,
            bookings: 203,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400'
        },
        {
            id: 4,
            name: 'Kaybiang Tunnel Viewpoint',
            category: 'Viewpoints & Nature Parks',
            location: 'Barangay Nasasa, Nasugbu',
            owner: 'Ana Garcia',
            rating: 4.5,
            reviews: 15,
            bookings: 67,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400'
        },
        {
            id: 5,
            name: 'Fortune Island Beach',
            category: 'Beaches & Resorts',
            location: 'Barangay Wawa, Nasugbu',
            owner: 'Carlos Rivera',
            rating: 4.7,
            reviews: 28,
            bookings: 145,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400'
        },
        {
            id: 6,
            name: 'Mt. Talamitam Base Camp',
            category: 'Camping & Glamping Sites',
            location: 'Barangay Aga, Nasugbu',
            owner: 'Elena Cruz',
            rating: 4.6,
            reviews: 12,
            bookings: 45,
            status: 'active',
            image: 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=400'
        }
    ],
    
    reviews: [
        {
            id: 1,
            attraction: 'Hidden Waterfall Trail',
            reviewer: 'John Smith',
            rating: 5,
            comment: 'Amazing experience! The trail was well-maintained.',
            date: '2025-10-25',
            status: 'approved'
        },
        {
            id: 2,
            attraction: 'Heritage Cultural Center',
            reviewer: 'Jane Doe',
            rating: 4,
            comment: 'Very informative and educational. Worth the visit!',
            date: '2025-10-24',
            status: 'pending'
        }
    ],
    
    categories: [
        { id: 1, name: 'Beaches & Resorts', icon: 'bi-water', count: 4, description: 'e.g., Canyon Cove, Papaya Cove, Pico de Loro, Fortune Island Beach', examples: 'Canyon Cove, Papaya Cove, Pico de Loro, Fortune Island Beach' },
        { id: 2, name: 'Waterfalls & Rivers', icon: 'bi-droplet', count: 2, description: 'e.g., Malabrigo Falls, natural streams, hiking spots', examples: 'Malabrigo Falls, natural streams, hiking spots' },
        { id: 3, name: 'Mountains & Hiking Trails', icon: 'bi-triangle', count: 3, description: 'e.g., Mt. Batulao, Mt. Talamitam', examples: 'Mt. Batulao, Mt. Talamitam' },
        { id: 4, name: 'Caves & Hidden Spots', icon: 'bi-gem', count: 1, description: 'e.g., Kaybiang Tunnel area, lesser-known caves', examples: 'Kaybiang Tunnel area, lesser-known caves' },
        { id: 5, name: 'Viewpoints & Nature Parks', icon: 'bi-eye', count: 2, description: 'Sunset views, scenic drives, nature trails', examples: 'Sunset viewpoints, scenic drives, nature trails' },
        { id: 6, name: 'Camping & Glamping Sites', icon: 'bi-house', count: 1, description: 'Outdoor camping and glamping locations', examples: 'Mountain camps, beach glamping' },
        { id: 7, name: 'Transportation Hubs', icon: 'bi-bus-front', count: 0, description: 'Bus terminals, jeepney stations, transport services', examples: 'Bus terminals, transport services' },
        { id: 8, name: 'Eateries & Restaurants', icon: 'bi-shop', count: 0, description: 'Local restaurants, cafes, and food spots', examples: 'Local restaurants, cafes, carinderia' }
    ]
};

// ========================================
// INITIALIZATION
// ========================================

let bookingsChart = null;
let categoryChart = null;
let currentAttractionId = null;
let currentRegistrationId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar toggle
    initSidebarToggle();
    
    // Load dashboard by default
    loadDashboard();
    
    // Initialize navigation
    initNavigation();
});

// ========================================
// SIDEBAR & NAVIGATION
// ========================================

function initSidebarToggle() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');
    
    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    // Check screen size
    function isMobile() {
        return window.innerWidth < 992;
    }
    
    // Toggle sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (isMobile()) {
                // Mobile: Toggle active class (slide in/out)
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            } else {
                // Desktop: Toggle hidden class (expand/collapse)
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('expanded');
            }
        });
    }
    
    // Close sidebar when clicking overlay (mobile only)
    overlay.addEventListener('click', function() {
        closeMobileSidebar();
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            // Desktop: Reset mobile states
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            // Mobile: Reset desktop states
            sidebar.classList.remove('hidden');
            mainContent.classList.remove('expanded');
        }
    });
}

function initNavigation() {
    const navItems = document.querySelectorAll('.sidebar .nav-item');
    const sidebar = document.getElementById('sidebar');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Prevent default for internal links
            const href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
            }
            
            // Update active state FIRST
            navItems.forEach(n => n.classList.remove('active'));
            this.classList.add('active');
            
            // AUTO-CLOSE SIDEBAR ON MOBILE BEFORE navigation
            if (window.innerWidth < 992) {
                closeMobileSidebar();
            }
            
            // Handle navigation
            if (href === '#dashboard') {
                showSection('dashboard');
            } else if (href === '#registrations') {
                showSection('registrations');
            } else if (href === '#attractions') {
                showSection('attractions');
            } else if (href === '#reports') {
                showSection('reports');
            } else if (href === '#settings') {
                showSection('settings');
            }
        });
    });
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar) {
        sidebar.classList.remove('active');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    const section = document.getElementById(`${sectionName}-section`);
    if (section) {
        section.classList.add('active');
    }
    
    // Load section data
    switch(sectionName) {
        case 'dashboard':
            loadDashboard();
            break;
        case 'registrations':
            loadRegistrations();
            break;
        case 'attractions':
            loadAttractions();
            break;
        case 'reports':
            loadReports();
            break;
    }
}

// ========================================
// DASHBOARD
// ========================================

function loadDashboard() {
    loadStats();
    loadCharts();
    loadActivity();
}

function loadStats() {
    const stats = MOCK_DATA.stats;
    
    const statsHTML = `
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card primary">
                <div class="stats-card-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="stats-card-content">
                    <h3>${stats.pending_requests}</h3>
                    <p>Pending Requests</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card success">
                <div class="stats-card-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="stats-card-content">
                    <h3>${stats.total_attractions}</h3>
                    <p>Total Attractions</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card info">
                <div class="stats-card-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stats-card-content">
                    <h3>${stats.total_bookings}</h3>
                    <p>Total Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="stats-card revenue">
                <div class="stats-card-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-card-content">
                    <h3>${stats.today_bookings}</h3>
                    <p>Today's Bookings</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('statsContainer').innerHTML = statsHTML;
}

function loadCharts() {
    loadBookingsChart();
    loadCategoryChart();
}

function loadBookingsChart() {
    const ctx = document.getElementById('bookingsChart');
    if (!ctx) return;
    
    // Destroy existing chart
    if (bookingsChart) {
        bookingsChart.destroy();
    }
    
    const data = MOCK_DATA.monthlyBookings;
    
    bookingsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.month),
            datasets: [{
                label: 'Bookings',
                data: data.map(d => d.count),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 20
                    }
                }
            }
        }
    });
}

function loadCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    // Destroy existing chart
    if (categoryChart) {
        categoryChart.destroy();
    }
    
    const data = MOCK_DATA.categoryDistribution;
    
    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(d => d.category),
            datasets: [{
                data: data.map(d => d.count),
                backgroundColor: data.map(d => d.color),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function loadActivity() {
    const activities = MOCK_DATA.recentActivity;
    
    const activityHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon bg-${activity.color}">
                <i class="${activity.icon}"></i>
            </div>
            <div class="activity-content">
                <p class="activity-title mb-1">${activity.title}</p>
                <p class="activity-description mb-0">${activity.description}</p>
            </div>
            <div class="activity-time">
                <small class="text-muted">${activity.time}</small>
            </div>
        </div>
    `).join('');
    
    document.getElementById('activityFeed').innerHTML = activityHTML;
}

function refreshDashboard() {
    // Show loading state
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-2"></i>Refreshing...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        loadDashboard();
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        
        // Show success message
        showToast('Dashboard refreshed successfully!', 'success');
    }, 1000);
}

// ========================================
// REGISTRATIONS
// ========================================

function loadRegistrations() {
    const registrations = MOCK_DATA.registrations;
    
    const tableHTML = registrations.map((reg, index) => `
        <tr>
            <td class="text-muted">${index + 1}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="bi bi-building me-2 text-primary"></i>
                    <strong>${reg.businessName}</strong>
                </div>
            </td>
            <td>${reg.ownerName}</td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-envelope me-1"></i>${reg.email}
                </small>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-telephone me-1"></i>${reg.phone}
                </small>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-geo-alt me-1"></i>${reg.location}
                </small>
            </td>
            <td><small>${formatDate(reg.submitted)}</small></td>
            <td class="text-center">
                <span class="badge bg-warning text-dark">${reg.status}</span>
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary" onclick="viewRegistration(${reg.id})" title="View Details">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="approveRegistration(${reg.id})" title="Approve">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="rejectRegistration(${reg.id})" title="Reject">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    document.getElementById('registrationsTable').innerHTML = tableHTML;
}

function filterRegistrations(status) {
    const registrations = status === 'all' 
        ? MOCK_DATA.registrations 
        : MOCK_DATA.registrations.filter(r => r.status.toLowerCase() === status);
    
    const tableHTML = registrations.map((reg, index) => `
        <tr>
            <td class="text-muted">${index + 1}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="bi bi-building me-2 text-primary"></i>
                    <strong>${reg.businessName}</strong>
                </div>
            </td>
            <td>${reg.ownerName}</td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-envelope me-1"></i>${reg.email}
                </small>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-telephone me-1"></i>${reg.phone}
                </small>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-geo-alt me-1"></i>${reg.location}
                </small>
            </td>
            <td><small>${formatDate(reg.submitted)}</small></td>
            <td class="text-center">
                <span class="badge bg-warning text-dark">${reg.status}</span>
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary" onclick="viewRegistration(${reg.id})" title="View Details">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="approveRegistration(${reg.id})" title="Approve">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="rejectRegistration(${reg.id})" title="Reject">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    document.getElementById('registrationsTable').innerHTML = tableHTML || '<tr><td colspan="9" class="text-center text-muted py-4">No registrations found</td></tr>';
}

function viewRegistration(id) {
    const reg = MOCK_DATA.registrations.find(r => r.id === id);
    if (!reg) return;
    
    currentRegistrationId = id;
    
    // Build documents HTML with improved card design
    const documentsHTML = Object.entries(reg.documents).map(([key, url]) => {
        const docName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        return `
            <div class="col-md-6 mb-4">
                <div class="document-card">
                    <div class="document-card-header">
                        <i class="bi bi-file-earmark-text"></i>
                        <strong>${docName}</strong>
                    </div>
                    <div class="document-card-body">
                        <div class="document-image-container">
                            <img src="${url}" 
                                 class="document-image" 
                                 alt="${docName}" 
                                 onclick="window.open('${url}', '_blank')"
                                 onerror="this.parentElement.innerHTML='<div class=\\'document-placeholder\\'><i class=\\'bi bi-file-earmark\\'></i><span>Document Preview</span></div>'">
                        </div>
                    </div>
                    <div class="document-card-footer">
                        <button class="btn-view-document" onclick="window.open('${url}', '_blank')">
                            <i class="bi bi-eye"></i>
                            View Full Size
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    const detailsHTML = `
        <div class="info-section">
            <div class="info-section-title">
                <i class="bi bi-building"></i>
                Business Information
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Business Name:</span>
                        <div class="info-item-value">${reg.businessName}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Category:</span>
                        <div class="info-item-value">
                            <span class="badge bg-primary">${reg.category}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Description:</span>
                        <div class="info-item-value">${reg.description}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-item">
                        <span class="info-item-label">Location:</span>
                        <div class="info-item-value">
                            <i class="bi bi-geo-alt"></i>${reg.location}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-section-title">
                <i class="bi bi-person"></i>
                Owner Information
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Owner Name:</span>
                        <div class="info-item-value">${reg.ownerName}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Email:</span>
                        <div class="info-item-value">
                            <i class="bi bi-envelope"></i>${reg.email}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Phone:</span>
                        <div class="info-item-value">
                            <i class="bi bi-telephone"></i>${reg.phone}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-item">
                        <span class="info-item-label">Submitted:</span>
                        <div class="info-item-value">
                            <i class="bi bi-calendar"></i>${formatDate(reg.submitted)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-section-title">
                <i class="bi bi-file-earmark-check"></i>
                Business Permits & Requirements
            </div>
            <div class="alert-info-custom">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Verification Required:</strong> Please review all documents carefully to ensure compliance with business regulations.
            </div>
            <div class="row">
                ${documentsHTML}
            </div>
        </div>
    `;
    
    document.getElementById('registrationDetails').innerHTML = detailsHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('viewRegistrationModal'));
    modal.show();
}

function approveRegistration(id) {
    if (id) currentRegistrationId = id;
    
    if (confirm('Are you sure you want to approve this registration?')) {
        // TODO: Backend should implement approval logic here
        showToast('Registration approved successfully!', 'success');
        loadRegistrations();
        
        // Close modal if open
        const modal = bootstrap.Modal.getInstance(document.getElementById('viewRegistrationModal'));
        if (modal) modal.hide();
    }
}

function rejectRegistration(id) {
    if (id) currentRegistrationId = id;
    
    // Close view modal first
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRegistrationModal'));
    if (viewModal) viewModal.hide();
    
    // Open reject modal
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectRegistrationModal'));
    rejectModal.show();
    
    // Clear previous inputs
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectDetails').value = '';
    document.getElementById('allowResubmit').checked = true;
}

function confirmRejectRegistration() {
    const reason = document.getElementById('rejectReason').value;
    const details = document.getElementById('rejectDetails').value;
    
    if (!reason) {
        showToast('Please select a reason for rejection', 'error');
        return;
    }
    
    if (!details.trim()) {
        showToast('Please provide additional details', 'error');
        return;
    }
    
    // TODO: Backend API call here
    // Send: currentRegistrationId, reason, details, allowResubmit checkbox value
    
    showToast('Registration rejected. Owner has been notified.', 'danger');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectRegistrationModal'));
    if (modal) modal.hide();
    
    // Reload registrations
    loadRegistrations();
}

// ========================================
// ATTRACTIONS
// ========================================

function loadAttractions() {
    const attractions = MOCK_DATA.attractions;
    
    const gridHTML = attractions.map(attraction => `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card attraction-card">
                <img src="${attraction.image}" class="card-img-top" alt="${attraction.name}">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">${attraction.category}</span>
                    <h5 class="card-title">${attraction.name}</h5>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-geo-alt me-1"></i>${attraction.location}
                    </p>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-person me-1"></i>Owner: ${attraction.owner}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <i class="bi bi-star-fill text-warning"></i>
                            <span>${attraction.rating}</span>
                            <small class="text-muted">(${attraction.reviews})</small>
                        </div>
                        <div>
                            <i class="bi bi-calendar-check text-primary"></i>
                            <span>${attraction.bookings} bookings</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary flex-fill" onclick="viewAttraction(${attraction.id})">
                            <i class="bi bi-eye me-1"></i>View
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="suspendAttraction(${attraction.id})">
                            <i class="bi bi-pause"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteAttraction(${attraction.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    document.getElementById('attractionsGrid').innerHTML = gridHTML;
}

function viewAttraction(id) {
    const attraction = MOCK_DATA.attractions.find(a => a.id === id);
    if (!attraction) return;
    
    currentAttractionId = id;
    
    const detailsHTML = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <img src="${attraction.image}" class="img-fluid rounded" alt="${attraction.name}">
            </div>
            <div class="col-md-6">
                <h4>${attraction.name}</h4>
                <p><span class="badge bg-primary">${attraction.category}</span></p>
                <p><strong>Location:</strong> ${attraction.location}</p>
                <p><strong>Owner:</strong> ${attraction.owner}</p>
                <p><strong>Rating:</strong> ${attraction.rating} ⭐ (${attraction.reviews} reviews)</p>
                <p><strong>Total Bookings:</strong> ${attraction.bookings}</p>
                <p><strong>Status:</strong> <span class="badge bg-success">${attraction.status}</span></p>
            </div>
        </div>
    `;
    
    document.getElementById('attractionDetails').innerHTML = detailsHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('viewAttractionModal'));
    modal.show();
}

function openSuspendModal() {
    // Close attraction details modal first
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewAttractionModal'));
    if (viewModal) viewModal.hide();
    
    // Open suspend modal
    const suspendModal = new bootstrap.Modal(document.getElementById('suspendAttractionModal'));
    suspendModal.show();
    
    // Clear previous inputs
    document.getElementById('suspendReason').value = '';
    document.getElementById('suspendDetails').value = '';
    document.getElementById('allowReactivation').checked = true;
}

function confirmSuspendAttraction() {
    const reason = document.getElementById('suspendReason').value;
    const details = document.getElementById('suspendDetails').value;
    
    if (!reason) {
        showToast('Please select a reason for suspension', 'error');
        return;
    }
    
    if (!details.trim()) {
        showToast('Please provide additional details', 'error');
        return;
    }
    
    // TODO: Backend API call here
    // Send: currentAttractionId, reason, details, allowReactivation checkbox value
    
    showToast('Attraction suspended successfully. Owner has been notified.', 'warning');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('suspendAttractionModal'));
    if (modal) modal.hide();
    
    // Reload attractions
    loadAttractions();
}

function openDeleteModal() {
    // Close attraction details modal first
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewAttractionModal'));
    if (viewModal) viewModal.hide();
    
    // Open delete modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAttractionModal'));
    deleteModal.show();
    
    // Clear previous inputs
    document.getElementById('deleteReason').value = '';
    document.getElementById('deleteDetails').value = '';
    document.getElementById('deleteBookings').checked = false;
}

function confirmDeleteAttraction() {
    const reason = document.getElementById('deleteReason').value;
    const details = document.getElementById('deleteDetails').value;
    
    if (!reason) {
        showToast('Please select a reason for deletion', 'error');
        return;
    }
    
    if (!details.trim()) {
        showToast('Please provide additional details', 'error');
        return;
    }
    
    // TODO: Backend API call here
    // Send: currentAttractionId, reason, details, deleteBookings checkbox value
    
    showToast('Attraction deleted successfully. Owner has been notified.', 'danger');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteAttractionModal'));
    if (modal) modal.hide();
    
    // Reload attractions
    loadAttractions();
}

// Keep old functions for backward compatibility with grid buttons
function suspendAttraction(id) {
    currentAttractionId = id;
    openSuspendModal();
}

function deleteAttraction(id) {
    currentAttractionId = id;
    openDeleteModal();
}

function applyAttractionsFilter() {
    // TODO: Backend should implement filtering logic
    showToast('Filters applied!', 'info');
    loadAttractions();
}

function exportAttractions() {
    // TODO: Backend should implement export logic
    showToast('Exporting attractions data...', 'info');
}

// ========================================
// REPORTS & ANALYTICS
// ========================================

let reportBookingsChart = null;
let reportCategoryChart = null;

function loadReports() {
    loadReportAnalytics();
    loadReportCharts();
    loadTopAttractions();
    setDefaultDateRange();
}

function setDefaultDateRange() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('reportFromDate').valueAsDate = firstDay;
    document.getElementById('reportToDate').valueAsDate = today;
}

function loadReportAnalytics() {
    // Update analytics cards with mock data
    document.getElementById('totalBookings').textContent = '245';
    document.getElementById('totalAttractions').textContent = '8';
    document.getElementById('avgRating').textContent = '4.7';
    document.getElementById('totalRevenue').textContent = '₱125,430';
}

function loadReportCharts() {
    // Bookings Trend Chart
    const bookingsCtx = document.getElementById('bookingsTrendChart');
    if (bookingsCtx) {
        if (reportBookingsChart) reportBookingsChart.destroy();
        
        reportBookingsChart = new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                datasets: [{
                    label: 'Bookings',
                    data: [45, 52, 61, 58, 67, 73],
                    borderColor: '#003d66',
                    backgroundColor: 'rgba(0, 61, 102, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    
    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryDistChart');
    if (categoryCtx) {
        if (reportCategoryChart) reportCategoryChart.destroy();
        
        reportCategoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Beaches & Resorts', 'Mountains & Hiking', 'Waterfalls & Rivers', 'Others'],
                datasets: [{
                    data: [40, 30, 20, 10],
                    backgroundColor: ['#3b82f6', '#10b981', '#06b6d4', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
}

function loadTopAttractions() {
    const topAttractions = [
        { rank: 1, name: 'Munting Buhangin Beach', category: 'Beaches & Resorts', bookings: 156, revenue: '₱45,200', rating: 4.8 },
        { rank: 2, name: 'Punta Fuego', category: 'Beaches & Resorts', bookings: 98, revenue: '₱38,600', rating: 4.6 },
        { rank: 3, name: 'Mt. Batulao Hiking Trail', category: 'Mountains & Hiking', bookings: 87, revenue: '₱22,100', rating: 4.9 },
        { rank: 4, name: 'Kaybiang Tunnel Viewpoint', category: 'Viewpoints', bookings: 67, revenue: '₱15,800', rating: 4.5 },
        { rank: 5, name: 'Fortune Island Beach', category: 'Beaches & Resorts', bookings: 65, revenue: '₱18,900', rating: 4.7 }
    ];
    
    const tableHTML = topAttractions.map(attr => `
        <tr>
            <td>
                <span class="badge bg-${attr.rank === 1 ? 'warning' : attr.rank === 2 ? 'secondary' : attr.rank === 3 ? 'info' : 'light text-dark'}">
                    #${attr.rank}
                </span>
            </td>
            <td><strong>${attr.name}</strong></td>
            <td><span class="badge bg-primary">${attr.category}</span></td>
            <td>${attr.bookings}</td>
            <td>${attr.revenue}</td>
            <td>
                <i class="bi bi-star-fill text-warning"></i>
                ${attr.rating}
            </td>
        </tr>
    `).join('');
    
    document.getElementById('topAttractionsTable').innerHTML = tableHTML;
}

function applyReportFilter() {
    const fromDate = document.getElementById('reportFromDate').value;
    const toDate = document.getElementById('reportToDate').value;
    const reportType = document.getElementById('reportType').value;
    
    if (!fromDate || !toDate) {
        showToast('Please select both from and to dates', 'error');
        return;
    }
    
    // TODO: Backend should filter data based on date range and type
    showToast(`Filtering ${reportType} data from ${fromDate} to ${toDate}...`, 'info');
    loadReports();
}

function refreshReports() {
    showToast('Refreshing reports data...', 'info');
    loadReports();
}

function downloadReport(type) {
    const fromDate = document.getElementById('reportFromDate').value;
    const toDate = document.getElementById('reportToDate').value;
    const reportType = document.getElementById('reportType').value;
    
    // TODO: Backend should generate and download actual files
    // Example backend endpoint: /api/reports/download?type=excel&from=2024-01-01&to=2024-12-31&reportType=all
    
    let message = '';
    switch(type) {
        case 'excel':
            message = 'Downloading Excel report with full analytics data...';
            // window.location.href = `/api/reports/download?type=excel&from=${fromDate}&to=${toDate}&reportType=${reportType}`;
            break;
        case 'pdf':
            message = 'Generating PDF report with charts and graphs...';
            // window.location.href = `/api/reports/download?type=pdf&from=${fromDate}&to=${toDate}&reportType=${reportType}`;
            break;
        case 'csv':
            message = 'Downloading CSV data for custom analysis...';
            // window.location.href = `/api/reports/download?type=csv&from=${fromDate}&to=${toDate}&reportType=${reportType}`;
            break;
    }
    
    showToast(message, 'success');
    
    // Simulate download
    setTimeout(() => {
        showToast(`${type} report downloaded!`, 'success');
    }, 1500);
}

// ========================================
// SETTINGS
// ========================================

function saveProfile() {
    // TODO: Backend should implement profile saving
    showToast('Profile updated successfully!', 'success');
}

function changePassword() {
    // TODO: Backend should implement password change
    showToast('Password changed successfully!', 'success');
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : 
                    type === 'danger' ? 'bg-danger' : 
                    type === 'warning' ? 'bg-warning' : 
                    'bg-primary';
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
    toast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// ========================================
// BACKEND INTEGRATION NOTES
// ========================================

/*
FOR BACKEND DEVELOPERS:

1. REPLACE MOCK_DATA with actual API calls:
   - Use fetch() or AJAX to get data from your PHP backend
   - Example: fetch('api-stats.php').then(res => res.json()).then(data => {...})

2. IMPLEMENT THESE API ENDPOINTS:
   - api-stats.php: GET dashboard statistics
   - api-activity.php: GET recent activity
   - api-registrations.php: GET/POST/PUT/DELETE registration requests
   - api-attractions.php: GET/POST/PUT/DELETE attractions
   - api-reviews.php: GET/POST/PUT/DELETE reviews
   - api-categories.php: GET/POST/PUT/DELETE categories
   - api-reports.php: GET report generation

3. ADD SESSION HANDLING:
   - Check if user is logged in before loading data
   - Redirect to login.html if session expired

4. ADD FORM VALIDATION:
   - Validate all form inputs before submission
   - Show appropriate error messages

5. ADD ERROR HANDLING:
   - Wrap API calls in try-catch blocks
   - Show user-friendly error messages

6. SECURITY:
   - Sanitize all user inputs
   - Use prepared statements for database queries
   - Implement CSRF protection
   - Add rate limiting for API endpoints

Example API call pattern:

async function loadStats() {
    try {
        const response = await fetch('api-stats.php', {
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error('Failed to load stats');
        }
        
        const data = await response.json();
        
        if (data.error) {
            showToast(data.error, 'danger');
            return;
        }
        
        // Use data.stats instead of MOCK_DATA.stats
        displayStats(data.stats);
        
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to load statistics', 'danger');
    }
}
*/

// ========================================
// INITIALIZE ON PAGE LOAD
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Admin Dashboard...');
    
    // Initialize sidebar functionality
    initSidebar();
    
    // Initialize navigation with mobile auto-close
    initNavigation();
    
    // Load initial dashboard data
    loadDashboard();
    
    console.log('Admin Dashboard initialized successfully!');
});
