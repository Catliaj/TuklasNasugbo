/**
 * Mobile Sidebar Toggle Handler
 * Handles responsive sidebar behavior for mobile devices
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }

    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const body = document.body;

        if (!sidebar || !sidebarToggle) {
            console.warn('Sidebar or toggle button not found');
            return;
        }

        // Create overlay element for mobile
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
        }

        // Toggle sidebar function
        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                body.classList.add('sidebar-open');
            } else {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        }

        // Hamburger button click
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = sidebar.classList.contains('show');
            toggleSidebar(!isOpen);
        });

        // Overlay click - close sidebar
        overlay.addEventListener('click', function() {
            toggleSidebar(false);
        });

        // Close sidebar when clicking on a link (mobile only)
        const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Only auto-close on mobile
                if (window.innerWidth < 992) {
                    setTimeout(function() {
                        toggleSidebar(false);
                    }, 200);
                }
            });
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Close sidebar on desktop view
                if (window.innerWidth >= 992) {
                    toggleSidebar(false);
                }
            }, 250);
        });

        // Prevent body scroll when sidebar is open on mobile
        sidebar.addEventListener('touchmove', function(e) {
            if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                e.stopPropagation();
            }
        }, { passive: true });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                toggleSidebar(false);
            }
        });

        console.log('Mobile sidebar initialized successfully');
    }


    // Swipe gesture support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const edgeThreshold = 50;

        // Swipe left to close
        if (touchEndX < touchStartX - swipeThreshold && sidebar.classList.contains('show')) {
            toggleSidebar(false);
        }

        // Swipe right from left edge to open
        if (touchEndX > touchStartX + swipeThreshold && touchStartX < edgeThreshold) {
            toggleSidebar(true);
        }
    }

    console.log('Mobile sidebar with swipe gestures initialized successfully');
})();