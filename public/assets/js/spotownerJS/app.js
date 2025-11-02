// Main Application Controller
class App {
    constructor() {
        this.currentPage = 'home';
        this.init();
    }

    init() {
        // Initialize sidebar navigation
        this.initSidebarNavigation();
        
        // Initialize mobile menu
        this.initMobileMenu();
        
        // Initialize logout
        this.initLogout();
        
        // Load initial page
        this.loadPage('home');
    }

    initSidebarNavigation() {
        const links = document.querySelectorAll('.sidebar-link[data-page]');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                this.loadPage(page);
                
                // Close mobile menu if open
                this.closeMobileMenu();
            });
        });
    }

    initMobileMenu() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                this.toggleMobileMenu();
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (sidebar && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    this.closeMobileMenu();
                }
            }
        });
    }

    toggleMobileMenu() {
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        
        if (sidebar.classList.contains('show')) {
            this.closeMobileMenu();
        } else {
            sidebar.classList.add('show');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            backdrop.addEventListener('click', () => this.closeMobileMenu());
            body.appendChild(backdrop);
        }
    }

    closeMobileMenu() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.querySelector('.sidebar-backdrop');
        
        sidebar.classList.remove('show');
        if (backdrop) {
            backdrop.remove();
        }
    }

    initLogout() {
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    alert('Logging out...');
                    // Add actual logout logic here
                }
            });
        }
    }

    loadPage(pageName) {
        // Update active link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.classList.remove('active');
        });
        const activeLink = document.querySelector(`.sidebar-link[data-page="${pageName}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }

        // Load page content
        const mainContent = document.getElementById('mainContent');
        this.currentPage = pageName;

        switch(pageName) {
            case 'home':
                mainContent.innerHTML = renderHomePage();
                initHomePage();
                break;
            case 'bookings':
                mainContent.innerHTML = renderBookingsPage();
                initBookingsPage();
                break;
            case 'earnings':
                mainContent.innerHTML = renderEarningsPage();
                initEarningsPage();
                break;
            case 'manage':
                mainContent.innerHTML = renderManageSpotPage();
                initManageSpotPage();
                break;
            case 'profile':
                mainContent.innerHTML = renderProfilePage();
                initProfilePage();
                break;
            default:
                mainContent.innerHTML = renderHomePage();
                initHomePage();
        }

        // Scroll to top
        window.scrollTo(0, 0);
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new App();
});
