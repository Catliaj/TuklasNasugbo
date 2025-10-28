// Enhanced Earnings & Reports Page with Chart Selection
function renderEarningsPage() {
    return `
        <div class="container-fluid">
            <div class="mb-4">
                <h2>Earnings & Reports</h2>
                <p class="text-muted-custom">Track your revenue and financial performance</p>
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label">Total Revenue</div>
                                <div class="stat-value">₱15,750</div>
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
                                <div class="stat-value">₱2,750</div>
                                <div class="stat-description">+12% from last month</div>
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
                                <div class="stat-value">₱458</div>
                                <div class="stat-description">Based on 34 bookings</div>
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
                                <div class="stat-value">₱1,375</div>
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
                            <button type="button" class="btn btn-outline-primary" id="btnBookingTrends" onclick="switchChart('booking-trends', 'bar')">
                                <i class="bi bi-bar-chart-fill me-1"></i>Booking Trends (Bar)
                            </button>
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

            <!-- Recent Transactions -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h3 class="custom-card-title">Recent Transactions</h3>
                            <p class="custom-card-description">Latest payment activities</p>
                        </div>
                        <div class="custom-card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Maria Santos</div>
                                        <small class="text-muted-custom">Oct 10, 2025</small>
                                    </div>
                                    <span class="text-success fw-medium">+₱500</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Juan Dela Cruz</div>
                                        <small class="text-muted-custom">Oct 12, 2025</small>
                                    </div>
                                    <span class="text-success fw-medium">+₱250</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Carlos Garcia</div>
                                        <small class="text-muted-custom">Oct 18, 2025</small>
                                    </div>
                                    <span class="text-success fw-medium">+₱375</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Ana Reyes</div>
                                        <small class="text-muted-custom">Oct 15, 2025 (Pending)</small>
                                    </div>
                                    <span class="text-warning fw-medium">₱750</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Sofia Martinez</div>
                                        <small class="text-muted-custom">Oct 20, 2025 (Pending)</small>
                                    </div>
                                    <span class="text-warning fw-medium">₱625</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Days -->
                <div class="col-lg-6">
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h3 class="custom-card-title">Top Performing Days</h3>
                            <p class="custom-card-description">Highest revenue days this month</p>
                        </div>
                        <div class="custom-card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Saturday, Oct 18</div>
                                        <small class="text-muted-custom">8 bookings</small>
                                    </div>
                                    <span class="fw-medium text-ocean-medium">₱1,000</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Sunday, Oct 12</div>
                                        <small class="text-muted-custom">6 bookings</small>
                                    </div>
                                    <span class="fw-medium text-ocean-medium">₱750</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Saturday, Oct 4</div>
                                        <small class="text-muted-custom">5 bookings</small>
                                    </div>
                                    <span class="fw-medium text-ocean-medium">₱625</span>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Friday, Oct 10</div>
                                        <small class="text-muted-custom">4 bookings</small>
                                    </div>
                                    <span class="fw-medium text-ocean-medium">₱500</span>
                                </div>
                            </div>
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
    `;
}

// Chart data
const chartData = {
    monthlyRevenue: {
        labels: ['May 2025', 'Jun 2025', 'Jul 2025', 'Aug 2025', 'Sep 2025', 'Oct 2025'],
        data: [2100, 2450, 2200, 2800, 2450, 2750],
        label: 'Monthly Revenue (₱)',
        period: 'Last 6 Months',
        total: '₱15,750',
        average: '₱2,625/month'
    },
    weeklyRevenue: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        data: [650, 700, 550, 850],
        label: 'Weekly Revenue (₱)',
        period: 'Current Month (October)',
        total: '₱2,750',
        average: '₱688/week'
    },
    bookingTrends: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        data: [5, 6, 4, 7],
        label: 'Number of Bookings',
        period: 'Current Month (October)',
        total: '22 Bookings',
        average: '5.5/week'
    }
};

let currentChart = null;
let currentChartType = 'monthly-revenue';
let currentGraphType = 'bar';

function initEarningsPage() {
    // Initialize with monthly revenue chart
    renderChart('monthly-revenue', 'bar');
}

function switchChart(chartType, graphType) {
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (chartType === 'monthly-revenue') {
        document.getElementById('btnMonthlyRevenue').classList.add('active');
    } else if (chartType === 'weekly-revenue') {
        document.getElementById('btnWeeklyRevenue').classList.add('active');
    } else if (chartType === 'booking-trends') {
        document.getElementById('btnBookingTrends').classList.add('active');
    }
    
    currentChartType = chartType;
    currentGraphType = graphType;
    renderChart(chartType, graphType);
}

function renderChart(chartType, graphType) {
    const canvas = document.getElementById('mainChart');
    const ctx = canvas.getContext('2d');
    
    let data, color, borderColor;
    
    switch(chartType) {
        case 'monthly-revenue':
            data = chartData.monthlyRevenue;
            color = 'rgba(0, 74, 124, 0.7)';
            borderColor = 'rgba(0, 74, 124, 1)';
            break;
        case 'weekly-revenue':
            data = chartData.weeklyRevenue;
            color = 'rgba(26, 95, 122, 0.7)';
            borderColor = 'rgba(26, 95, 122, 1)';
            break;
        case 'booking-trends':
            data = chartData.bookingTrends;
            color = 'rgba(232, 213, 196, 0.7)';
            borderColor = 'rgba(212, 197, 185, 1)';
            break;
    }
    
    // Destroy previous chart if exists
    if (currentChart) {
        currentChart.destroy();
    }
    
    // Create new chart based on type
    if (graphType === 'line') {
        currentChart = createLineChart(ctx, data.labels, data.data, data.label, color, borderColor);
    } else {
        currentChart = createBarChart(ctx, data.labels, data.data, data.label, color, borderColor);
    }
    
    // Update chart info
    document.getElementById('chartPeriod').textContent = data.period;
    document.getElementById('chartTotal').textContent = data.total;
    document.getElementById('chartAverage').textContent = data.average;
}

function createBarChart(ctx, labels, data, label, backgroundColor, borderColor) {
    const canvas = ctx.canvas;
    const container = canvas.parentElement;
    
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Set canvas size to match container
    canvas.width = container.offsetWidth;
    canvas.height = 450;
    
    const padding = { top: 40, right: 40, bottom: 70, left: 80 };
    const chartWidth = canvas.width - padding.left - padding.right;
    const chartHeight = canvas.height - padding.top - padding.bottom;
    const barWidth = (chartWidth / labels.length) * 0.6;
    const barSpacing = chartWidth / labels.length;
    const maxValue = Math.max(...data) * 1.15;
    
    // Draw title
    ctx.fillStyle = '#003D66';
    ctx.font = 'bold 16px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(label, canvas.width / 2, 25);
    
    // Draw grid lines and y-axis labels
    ctx.strokeStyle = '#E8D5C4';
    ctx.lineWidth = 1;
    ctx.fillStyle = '#6B7280';
    ctx.font = '13px sans-serif';
    ctx.textAlign = 'right';
    
    for (let i = 0; i <= 5; i++) {
        const y = padding.top + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding.left, y);
        ctx.lineTo(canvas.width - padding.right, y);
        ctx.stroke();
        
        const value = Math.round(maxValue - (maxValue / 5) * i);
        ctx.fillText(value.toString(), padding.left - 15, y + 5);
    }
    
    // Draw bars
    data.forEach((value, index) => {
        const barHeight = (value / maxValue) * chartHeight;
        const x = padding.left + (index * barSpacing) + (barSpacing - barWidth) / 2;
        const y = canvas.height - padding.bottom - barHeight;
        
        // Draw shadow
        ctx.shadowColor = 'rgba(0, 0, 0, 0.1)';
        ctx.shadowBlur = 4;
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 2;
        
        // Draw bar
        ctx.fillStyle = backgroundColor;
        ctx.fillRect(x, y, barWidth, barHeight);
        
        // Reset shadow
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
        
        // Draw border
        ctx.strokeStyle = borderColor;
        ctx.lineWidth = 2;
        ctx.strokeRect(x, y, barWidth, barHeight);
        
        // Draw value on top of bar
        ctx.fillStyle = '#003D66';
        ctx.font = 'bold 14px sans-serif';
        ctx.textAlign = 'center';
        const displayValue = currentChartType === 'booking-trends' ? value : `₱${value}`;
        ctx.fillText(displayValue, x + barWidth / 2, y - 10);
        
        // Draw x-axis label
        ctx.fillStyle = '#6B7280';
        ctx.font = '13px sans-serif';
        ctx.textAlign = 'center';
        ctx.save();
        ctx.translate(x + barWidth / 2, canvas.height - padding.bottom + 25);
        
        // Split long labels into multiple lines
        const words = labels[index].split(' ');
        if (words.length > 1) {
            ctx.fillText(words[0], 0, 0);
            ctx.fillText(words.slice(1).join(' '), 0, 15);
        } else {
            ctx.fillText(labels[index], 0, 0);
        }
        ctx.restore();
    });
    
    // Draw axes
    ctx.strokeStyle = '#003D66';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding.left, padding.top);
    ctx.lineTo(padding.left, canvas.height - padding.bottom);
    ctx.lineTo(canvas.width - padding.right, canvas.height - padding.bottom);
    ctx.stroke();
    
    return {
        destroy: function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    };
}

function createLineChart(ctx, labels, data, label, lineColor, pointColor) {
    const canvas = ctx.canvas;
    const container = canvas.parentElement;
    
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Set canvas size to match container
    canvas.width = container.offsetWidth;
    canvas.height = 450;
    
    const padding = { top: 40, right: 40, bottom: 70, left: 80 };
    const chartWidth = canvas.width - padding.left - padding.right;
    const chartHeight = canvas.height - padding.top - padding.bottom;
    const maxValue = Math.max(...data) * 1.15;
    const pointSpacing = chartWidth / (labels.length - 1);
    
    // Draw title
    ctx.fillStyle = '#003D66';
    ctx.font = 'bold 16px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(label, canvas.width / 2, 25);
    
    // Draw grid lines and y-axis labels
    ctx.strokeStyle = '#E8D5C4';
    ctx.lineWidth = 1;
    ctx.fillStyle = '#6B7280';
    ctx.font = '13px sans-serif';
    ctx.textAlign = 'right';
    
    for (let i = 0; i <= 5; i++) {
        const y = padding.top + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding.left, y);
        ctx.lineTo(canvas.width - padding.right, y);
        ctx.stroke();
        
        const value = Math.round(maxValue - (maxValue / 5) * i);
        ctx.fillText(value.toString(), padding.left - 15, y + 5);
    }
    
    // Calculate points
    const points = data.map((value, index) => {
        const x = padding.left + (index * pointSpacing);
        const y = canvas.height - padding.bottom - (value / maxValue) * chartHeight;
        return { x, y, value };
    });
    
    // Draw area fill
    ctx.beginPath();
    ctx.moveTo(points[0].x, canvas.height - padding.bottom);
    points.forEach(point => {
        ctx.lineTo(point.x, point.y);
    });
    ctx.lineTo(points[points.length - 1].x, canvas.height - padding.bottom);
    ctx.closePath();
    ctx.fillStyle = lineColor.replace('0.7', '0.2');
    ctx.fill();
    
    // Draw line
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);
    points.forEach(point => {
        ctx.lineTo(point.x, point.y);
    });
    ctx.strokeStyle = pointColor;
    ctx.lineWidth = 3;
    ctx.stroke();
    
    // Draw points and values
    points.forEach((point, index) => {
        // Draw point shadow
        ctx.shadowColor = 'rgba(0, 0, 0, 0.2)';
        ctx.shadowBlur = 4;
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 2;
        
        // Draw point
        ctx.beginPath();
        ctx.arc(point.x, point.y, 6, 0, Math.PI * 2);
        ctx.fillStyle = '#FFFFFF';
        ctx.fill();
        ctx.strokeStyle = pointColor;
        ctx.lineWidth = 3;
        ctx.stroke();
        
        // Reset shadow
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        
        // Draw value above point
        ctx.fillStyle = '#003D66';
        ctx.font = 'bold 14px sans-serif';
        ctx.textAlign = 'center';
        const displayValue = currentChartType === 'booking-trends' ? point.value : `₱${point.value}`;
        ctx.fillText(displayValue, point.x, point.y - 15);
        
        // Draw x-axis label
        ctx.fillStyle = '#6B7280';
        ctx.font = '13px sans-serif';
        ctx.textAlign = 'center';
        ctx.save();
        ctx.translate(point.x, canvas.height - padding.bottom + 25);
        
        // Split long labels
        const words = labels[index].split(' ');
        if (words.length > 1) {
            ctx.fillText(words[0], 0, 0);
            ctx.fillText(words.slice(1).join(' '), 0, 15);
        } else {
            ctx.fillText(labels[index], 0, 0);
        }
        ctx.restore();
    });
    
    // Draw axes
    ctx.strokeStyle = '#003D66';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding.left, padding.top);
    ctx.lineTo(padding.left, canvas.height - padding.bottom);
    ctx.lineTo(canvas.width - padding.right, canvas.height - padding.bottom);
    ctx.stroke();
    
    return {
        destroy: function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    };
}

// Make switchChart available globally
window.switchChart = switchChart;