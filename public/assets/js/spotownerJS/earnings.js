// Get base URL from current page
const BASE_URL = window.location.origin;

// Global chart variable
let currentChart = null;

// Chart configuration with modern styling
const chartColors = {
    primary: '#0d6efd',
    success: '#198754',
    warning: '#ffc107',
    danger: '#dc3545',
    info: '#0dcaf0',
    ocean: '#2B7A78',
    sand: '#DEF2F1',
    gradient: ['#2B7A78', '#3AAFA9', '#17252A']
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load default chart (Monthly Revenue - Bar)
    loadMonthlyRevenueChart();

    // Setup button click handlers
    document.getElementById('btnMonthlyRevenue').addEventListener('click', function() {
        switchChart('monthly-revenue', 'bar');
    });

    document.getElementById('btnWeeklyRevenue').addEventListener('click', function() {
        switchChart('weekly-revenue', 'line');
    });

    document.getElementById('btnBookingTrends').addEventListener('click', function() {
        switchChart('booking-trends', 'bar');
    });
});

// Switch between different charts
function switchChart(chartType, chartStyle) {
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('button').classList.add('active');

    // Load appropriate chart
    switch (chartType) {
        case 'monthly-revenue':
            loadMonthlyRevenueChart();
            break;
        case 'weekly-revenue':
            loadWeeklyRevenueChart();
            break;
        case 'booking-trends':
            loadBookingTrendsChart();
            break;
    }
}

// Load Monthly Revenue Chart (Bar)
function loadMonthlyRevenueChart() {
    showChartLoading();

    fetch(window.location.origin + '/spotowner/api/monthly-revenue')
        .then(response => {
            console.log('Monthly Revenue Response Status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Monthly Revenue Data:', data);

            // Support two API shapes:
            // 1) Legacy: API returns an array of rows
            // 2) New: API returns an object { months: [...], monthly: [...], by_spot: [...] }
            let rows = [];
            if (!data) rows = [];
            else if (Array.isArray(data)) rows = data;
            else if (Array.isArray(data.monthly)) rows = data.monthly;
            else if (Array.isArray(data.data)) rows = data.data; // defensive
            else rows = [];

            if (!rows || rows.length === 0) {
                console.warn('No data returned from API');
                showChartError('No data available for the selected period');
                return;
            }

            const labels = rows.map(item => item.month_name || item.month || 'Unknown');
            const revenues = rows.map(item => parseFloat(item.revenue) || 0);
            const bookings = rows.map(item => parseInt(item.bookings) || 0);

            // Calculate totals
            const totalRevenue = revenues.reduce((a, b) => a + b, 0);
            const avgRevenue = totalRevenue / revenues.length || 0;

            // Update chart info
            updateChartInfo('Last 6 Months', `₱${totalRevenue.toFixed(2)}`, `₱${avgRevenue.toFixed(2)}/month`);

            // Create chart
            createBarChart(labels, revenues, 'Monthly Revenue', chartColors.ocean);
        })
        .catch(error => {
            console.error('Error loading monthly revenue:', error);
            showChartError('Error: ' + error.message);
        });
}

// Load Weekly Revenue Chart (Line)
function loadWeeklyRevenueChart() {
    showChartLoading();

    fetch(window.location.origin + '/spotowner/api/weekly-revenue')
        .then(response => {
            console.log('Weekly Revenue Response Status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Weekly Revenue Data:', data);

            if (!data || data.length === 0) {
                console.warn('No data returned from API');
                showChartError('No data available for the selected period');
                return;
            }

            const labels = data.map(item => `${item.week_start} - ${item.week_end}`);
            const revenues = data.map(item => parseFloat(item.revenue) || 0);

            // Calculate totals
            const totalRevenue = revenues.reduce((a, b) => a + b, 0);
            const avgRevenue = totalRevenue / revenues.length || 0;

            // Update chart info
            updateChartInfo('Last 8 Weeks', `₱${totalRevenue.toFixed(2)}`, `₱${avgRevenue.toFixed(2)}/week`);

            // Create chart
            createLineChart(labels, revenues, 'Weekly Revenue', chartColors.primary);
        })
        .catch(error => {
            console.error('Error loading weekly revenue:', error);
            showChartError('Error: ' + error.message);
        });
}

// Load Booking Trends Chart (Stacked Bar)
function loadBookingTrendsChart() {
    showChartLoading();

    fetch(window.location.origin + '/spotowner/api/booking-trends')
        .then(response => {
            console.log('Booking Trends Response Status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Booking Trends Data:', data);

            if (!data || data.length === 0) {
                console.warn('No data returned from API');
                showChartError('No data available for the selected period');
                return;
            }

            // Process data by status
            const months = [...new Set(data.map(item => item.month_name))];
            const statuses = [...new Set(data.map(item => item.booking_status))];

            // Create datasets for each status
            const datasets = statuses.map((status, index) => {
                const statusData = months.map(month => {
                    const found = data.find(item => item.month_name === month && item.booking_status === status);
                    return found ? parseInt(found.count) : 0;
                });

                // Assign colors based on status
                // Assign colors based on status - Beige, White, Ocean Blue theme
                let color;
                switch (status) {
                    case 'Confirmed':
                        color = '#0666cc'; // Ocean blue
                        break;
                    case 'Pending':
                        color = '#f5f5dc'; // Beige
                        break;
                    case 'Cancelled':
                        color = '#ffffff'; // White
                        break;
                    case 'Completed':
                        color = '#0080ff'; // Light ocean blue
                        break;
                    default:
                        color = '#0066cc'; // Ocean blue
                }

                return {
                    label: status,
                    data: statusData,
                    backgroundColor: color,
                    borderColor: status === 'Cancelled' ? '#cccccc' : color, // Gray border for white bars
                    borderWidth: 2
                };
            });

            // Calculate totals
            const totalBookings = data.reduce((sum, item) => sum + parseInt(item.count), 0);
            const avgBookings = totalBookings / months.length || 0;

            // Update chart info
            updateChartInfo('Last 6 Months', `${totalBookings} bookings`, `${avgBookings.toFixed(1)}/month`);

            // Create stacked bar chart
            createStackedBarChart(months, datasets, 'Booking Trends by Status');
        })
        .catch(error => {
            console.error('Error loading booking trends:', error);
            showChartError('Error: ' + error.message);
        });
}

// Create Bar Chart
function createBarChart(labels, data, label, color) {
    destroyCurrentChart();

    const ctx = document.getElementById('mainChart').getContext('2d');

    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: color,
                borderColor: color,
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ₱${context.parsed.y.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    hideChartLoading();
}

// Create Line Chart
function createLineChart(labels, data, label, color) {
    destroyCurrentChart();

    const ctx = document.getElementById('mainChart').getContext('2d');

    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, color + '40');
    gradient.addColorStop(1, color + '00');

    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: gradient,
                borderColor: color,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: color,
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ₱${context.parsed.y.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });

    hideChartLoading();
}

// Create Stacked Bar Chart
function createStackedBarChart(labels, datasets, title) {
    destroyCurrentChart();

    const ctx = document.getElementById('mainChart').getContext('2d');

    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    hideChartLoading();
}

// Helper Functions
function destroyCurrentChart() {
    if (currentChart) {
        currentChart.destroy();
        currentChart = null;
    }
}

function updateChartInfo(period, total, average) {
    document.getElementById('chartPeriod').textContent = period;
    document.getElementById('chartTotal').textContent = total;
    document.getElementById('chartAverage').textContent = average;
}

function showChartLoading() {
    const container = document.getElementById('chartContainer');

    // Destroy any existing chart first
    destroyCurrentChart();

    // Create/clear canvas
    container.innerHTML = '<canvas id="mainChart" style="width: 100%; height: 450px;"></canvas>';

    // Add loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.className = 'd-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100';
    loadingOverlay.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
    loadingOverlay.style.zIndex = '1000';
    loadingOverlay.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading chart data...</p>
        </div>
    `;
    container.appendChild(loadingOverlay);
}

function hideChartLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }

    // Ensure canvas still exists
    const canvas = document.getElementById('mainChart');
    if (!canvas) {
        const container = document.getElementById('chartContainer');
        container.innerHTML = '<canvas id="mainChart" style="width: 100%; height: 450px;"></canvas>';
    }
}

function showChartError(message = 'Failed to load chart data') {
    const container = document.getElementById('chartContainer');

    // Destroy any existing chart
    destroyCurrentChart();

    container.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="height: 450px;">
            <div class="text-center text-muted">
                <i class="bi bi-exclamation-triangle fs-1"></i>
                <p class="mt-3">${message}</p>
                <button class="btn btn-sm btn-primary" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Retry
                </button>
            </div>
        </div>
    `;
}