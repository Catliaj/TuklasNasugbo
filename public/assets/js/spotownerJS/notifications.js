// Notification system for admin
let notificationInterval = null;

// Load notifications
async function loadNotifications() {
    try {
        const response = await fetch('/spotowner/notifications/list');
        const notifications = await response.json();

        const notificationList = document.getElementById('notificationList');

        if (!notifications || notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mb-0 mt-2">No notifications</p>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications.map(notif => `
            <a href="${notif.url || '#'}" 
               class="dropdown-item ${notif.is_read == 0 ? 'bg-light' : ''} py-3 border-bottom" 
               data-notification-id="${notif.id}"
               onclick="markAsRead(${notif.id}); return true;">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                            <i class="bi bi-bell-fill text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 ${notif.is_read == 0 ? 'fw-bold' : ''}">${notif.message}</p>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> ${formatTimeAgo(notif.created_at)}
                        </small>
                    </div>
                    ${notif.is_read == 0 ? '<div class="flex-shrink-0"><span class="badge bg-primary rounded-pill">New</span></div>' : ''}
                </div>
            </a>
        `).join('');

    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Update notification count
async function updateNotificationCount() {
    try {
        const response = await fetch('/spotowner/notifications/unread-count');
        const data = await response.json();

        const badge = document.getElementById('notificationBadge');
        if (data.count > 0) {
            badge.textContent = data.count > 99 ? '99+' : data.count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Error updating notification count:', error);
    }
}

// Mark notification as read
async function markAsRead(notificationId) {
    try {
        await fetch(`/spotowner/notifications/mark-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        updateNotificationCount();
        loadNotifications();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Mark all as read
async function markAllAsRead() {
    try {
        await fetch('/spotowner/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        updateNotificationCount();
        loadNotifications();
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Format time ago
function formatTimeAgo(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`;
    if (seconds < 2592000) return `${Math.floor(seconds / 86400)} days ago`;
    return date.toLocaleDateString();
}

// Initialize notifications
document.addEventListener('DOMContentLoaded', function() {
    // Initial load
    updateNotificationCount();

    // Refresh every 30 seconds
    notificationInterval = setInterval(() => {
        updateNotificationCount();
    }, 30000);

    // Load notifications when dropdown is opened
    const dropdownToggle = document.getElementById('notificationDropdown');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('show.bs.dropdown', loadNotifications);
    }

    // Mark all as read button
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            markAllAsRead();
        });
    }
});

// Cleanup
window.addEventListener('beforeunload', function() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
    }
});

// Make functions globally available
window.markAsRead = markAsRead;
window.markAllAsRead = markAllAsRead;

// Sync desktop and mobile notification badges
function syncNotificationBadges(count) {
    const mobileBadge = document.getElementById('notificationBadge');
    const desktopBadge = document.getElementById('notificationBadgeDesktop');

    [mobileBadge, desktopBadge].forEach(badge => {
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    });
}

// Update the existing updateNotificationCount function
async function updateNotificationCount() {
    try {
        const response = await fetch('/spotowner/notifications/unread-count');
        const data = await response.json();
        syncNotificationBadges(data.count);
    } catch (error) {
        console.error('Error updating notification count:', error);
    }
}

// Load notifications for both dropdowns
async function loadNotifications() {
    try {
        const response = await fetch('/spotowner/notifications/list');
        const notifications = await response.json();

        const notificationLists = [
            document.getElementById('notificationList'),
            document.getElementById('notificationListDesktop')
        ];

        const content = notifications && notifications.length > 0 ?
            notifications.map(notif => `
                <a href="${notif.url || '#'}" 
                   class="dropdown-item ${notif.is_read == 0 ? 'bg-light' : ''} py-3 border-bottom" 
                   data-notification-id="${notif.id}"
                   onclick="markAsRead(${notif.id}); return true;">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                <i class="bi bi-bell-fill text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 ${notif.is_read == 0 ? 'fw-bold' : ''}">${notif.message}</p>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> ${formatTimeAgo(notif.created_at)}
                            </small>
                        </div>
                        ${notif.is_read == 0 ? '<div class="flex-shrink-0"><span class="badge bg-primary rounded-pill">New</span></div>' : ''}
                    </div>
                </a>
            `).join('') :
            `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mb-0 mt-2">No notifications</p>
                </div>
            `;

        notificationLists.forEach(list => {
            if (list) list.innerHTML = content;
        });

    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Initialize both dropdowns
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();

    notificationInterval = setInterval(() => {
        updateNotificationCount();
    }, 30000);

    // Mobile dropdown
    const mobileDropdown = document.getElementById('notificationDropdown');
    if (mobileDropdown) {
        mobileDropdown.addEventListener('show.bs.dropdown', loadNotifications);
    }

    // Desktop dropdown
    const desktopDropdown = document.getElementById('notificationDropdownDesktop');
    if (desktopDropdown) {
        desktopDropdown.addEventListener('show.bs.dropdown', loadNotifications);
    }

    // Mark all read buttons
    const markAllBtns = ['markAllReadBtn', 'markAllReadBtnDesktop'];
    markAllBtns.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                markAllAsRead();
            });
        }
    });
});