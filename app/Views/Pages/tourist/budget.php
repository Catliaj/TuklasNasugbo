<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url("assets/css/touristStyle/budget.css")?>">
    
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
               <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-compass"></i>
                    <span>Tuklas Nasugbu</span>
                </div>
                <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="/tourist/dashboard" class="nav-link">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/exploreSpots" class="nav-link">
                            <i class="bi bi-search"></i>
                            <span>Explore</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/itinerary" class="nav-link">
                            <i class="bi bi-calendar-check"></i>
                            <span>My Itinerary</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/myBookings" class="nav-link">
                            <i class="bi bi-ticket-perforated"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/budget" class="nav-link active">
                            <i class="bi bi-wallet2"></i>
                            <span>Budget Tracker</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/favorites" class="nav-link">
                            <i class="bi bi-heart"></i>
                            <span>Favorites</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/visits" class="nav-link">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Visited Places</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/reviews" class="nav-link">
                            <i class="bi bi-star"></i>
                            <span>My Reviews</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/under-development" class="nav-link">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tourist/profile" class="nav-link">
                            <i class="bi bi-person-circle"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Budget Tracker</h1>
                </div>
                <div class="user-actions">
                    <button class="notification-btn">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-profile">
                        <div class="user-avatar">JD</div>
                    </div>
                </div>
            </div>

            <div class="budget-container">
                <!-- Budget Summary -->
                <div class="budget-header">
                    <div class="budget-summary-card primary">
                        <div class="budget-label">Total Budget</div>
                        <div class="budget-amount">₱15,000</div>
                        <div class="budget-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 75%"></div>
                            </div>
                            <div class="category-info mt-2">
                                <span>₱11,500 spent</span>
                                <span>₱3,500 remaining</span>
                            </div>
                        </div>
                    </div>

                    <div class="budget-summary-card">
                        <div class="budget-label">Spent This Trip</div>
                        <div class="budget-amount">₱11,500</div>
                        <div class="budget-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="budget-summary-card">
                        <div class="budget-label">Remaining Balance</div>
                        <div class="budget-amount">₱3,500</div>
                        <div class="budget-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 23%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Content -->
                <div class="budget-content">
                    <!-- Expenses List -->
                    <div class="expenses-section">
                        <div class="section-header">
                            <h3 class="section-title">Recent Expenses</h3>
                            <button class="btn-add" onclick="addExpense()">
                                <i class="bi bi-plus-circle"></i> Add Expense
                            </button>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon lodging">
                                    <i class="bi bi-house-door"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Canyon Cove Resort</h4>
                                    <p>Dec 15, 2024 • Accommodation</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱6,000</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon transport">
                                    <i class="bi bi-car-front"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Van Transfer</h4>
                                    <p>Dec 15, 2024 • Transportation</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱3,500</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon activity">
                                    <i class="bi bi-island"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Fortune Island Tour</h4>
                                    <p>Dec 16, 2024 • Activity</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱2,000</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon food">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Lunch at Local Restaurant</h4>
                                    <p>Dec 16, 2024 • Food & Drinks</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱800</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon food">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Dinner at Resort Restaurant</h4>
                                    <p>Dec 15, 2024 • Food & Drinks</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱1,500</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-icon other">
                                    <i class="bi bi-bag"></i>
                                </div>
                                <div class="expense-details">
                                    <h4>Souvenirs & Shopping</h4>
                                    <p>Dec 17, 2024 • Other</p>
                                </div>
                            </div>
                            <div class="expense-amount">₱700</div>
                            <div class="expense-actions">
                                <button class="btn-icon" onclick="editExpense()">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteExpense()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Categories -->
                    <div class="categories-section">
                        <div class="section-header">
                            <h3 class="section-title">By Category</h3>
                        </div>

                        <div class="category-item">
                            <div class="category-header">
                                <div class="category-name">
                                    <i class="bi bi-house-door" style="color: #7b1fa2;"></i>
                                    <span>Accommodation</span>
                                </div>
                                <div class="category-amount">₱6,000</div>
                            </div>
                            <div class="category-progress">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%; background: #7b1fa2;"></div>
                                </div>
                            </div>
                            <div class="category-info">
                                <span>100% of ₱6,000 budget</span>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="category-header">
                                <div class="category-name">
                                    <i class="bi bi-car-front" style="color: #388e3c;"></i>
                                    <span>Transportation</span>
                                </div>
                                <div class="category-amount">₱3,500</div>
                            </div>
                            <div class="category-progress">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 87.5%; background: #388e3c;"></div>
                                </div>
                            </div>
                            <div class="category-info">
                                <span>87.5% of ₱4,000 budget</span>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="category-header">
                                <div class="category-name">
                                    <i class="bi bi-cup-hot" style="color: #f57c00;"></i>
                                    <span>Food & Drinks</span>
                                </div>
                                <div class="category-amount">₱2,300</div>
                            </div>
                            <div class="category-progress">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 76.67%; background: #f57c00;"></div>
                                </div>
                            </div>
                            <div class="category-info">
                                <span>76.67% of ₱3,000 budget</span>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="category-header">
                                <div class="category-name">
                                    <i class="bi bi-ticket" style="color: #1976d2;"></i>
                                    <span>Activities</span>
                                </div>
                                <div class="category-amount">₱2,000</div>
                            </div>
                            <div class="category-progress">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%; background: #1976d2;"></div>
                                </div>
                            </div>
                            <div class="category-info">
                                <span>100% of ₱2,000 budget</span>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="category-header">
                                <div class="category-name">
                                    <i class="bi bi-bag" style="color: #c2185b;"></i>
                                    <span>Other</span>
                                </div>
                                <div class="category-amount">₱700</div>
                            </div>
                            <div class="category-progress">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%; background: #c2185b;"></div>
                                </div>
                            </div>
                            <div class="category-info">
                                <span>70% of ₱1,000 budget</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        function addExpense() {
            alert('Add expense functionality - Backend integration needed');
        }

        function editExpense() {
            alert('Edit expense functionality - Backend integration needed');
        }

        function deleteExpense() {
            if (confirm('Are you sure you want to delete this expense?')) {
                alert('Delete expense - Backend integration needed');
            }
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = event.target.closest('.sidebar-toggle');
            
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>
