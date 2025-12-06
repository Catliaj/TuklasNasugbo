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

    // Booking Trends feature removed: no handler needed
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
    }
}

// Load Monthly Revenue Chart (Bar)
function loadMonthlyRevenueChart() {
    showChartLoading();

    // Request unpaid-inclusive results so the chart reflects all bookings across all spots
    fetch(window.location.origin + '/spotowner/api/monthly-revenue?onlyPaid=0&months=6')
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

            // Update chart info (format numbers with thousand separators)
            const totalLabel = '₱' + totalRevenue.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            const avgLabel = '₱' + avgRevenue.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + '/month';
            updateChartInfo('Last 6 Months (All spots)', totalLabel, avgLabel);

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

    // Request unpaid-inclusive weekly revenue for all spots
    fetch(window.location.origin + '/spotowner/api/weekly-revenue?onlyPaid=0&weeks=8')
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

            const totalLabel = '₱' + totalRevenue.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            const avgLabel = '₱' + avgRevenue.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + '/week';
            updateChartInfo('Last 8 Weeks (All spots)', totalLabel, avgLabel);

            // Create chart
            createLineChart(labels, revenues, 'Weekly Revenue', chartColors.primary);
        })
        .catch(error => {
            console.error('Error loading weekly revenue:', error);
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