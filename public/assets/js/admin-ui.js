// admin-ui.js - shared UI helpers for admin pages
(function(){
    'use strict';

    // Safe DOM helpers
    const qs = s => document.querySelector(s);
    const qsa = s => document.querySelectorAll(s);

    // Toggle sidebar for small screens
    const sidebarToggle = qs('#sidebarToggle');
    const sidebar = qs('#sidebar');
    const overlay = qs('#sidebarOverlay');
    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Set primary CSS variable dynamically
    window.adminUI = window.adminUI || {};
    adminUI.setPrimaryColor = function(hex){
        if (!hex) return;
        document.documentElement.style.setProperty('--primary-blue', hex);
    }

        adminUI.setSiteTitle = function(title) {
            if(!title) return;
            document.title = title + ' - Admin';
            qsa('.sidebar-header span').forEach(s => s.innerText = title);
        }

    // Update topbar initials or text
    adminUI.updateTopbarName = function(name){
        if (!name) return;
        const initials = name.split(' ').map(n => n.charAt(0)).slice(0,2).join('').toUpperCase();
        qsa('.topbar-avatar--primary span').forEach(s => s.innerText = initials);
        qsa('.dropdown-menu .fw-bold').forEach(n => n.innerText = name);
    }

    // Simple toast / message helper (bootstrap-compatible alert)
    adminUI.toast = function(type, message, target){
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.setAttribute('role','alert');
        alert.style.margin = '8px 0';
        alert.innerHTML = message;
        (target || document.body).prepend(alert);
        setTimeout(() => alert.remove(), 3500);
        return alert;
    }

    // Polished bounce animation for KPI icons on update
    adminUI.pulseIcon = function(selector){
        const el = qs(selector);
        if (!el) return;
        el.classList.add('admin-pulse');
        setTimeout(()=> el.classList.remove('admin-pulse'), 600);
    }

    // Add listeners to profile image dropdowns to ensure keyboard accessibility
    qsa('.topbar-avatar--primary').forEach(btn => {
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault(); btn.click();
            }
        });
    });

    // Expose a helper to show success/error for forms (forms can use it)
    adminUI.formFeedback = function(el, type, message){
        if(!el) { adminUI.toast(type, message); return; }
        el.innerHTML = `<div class='alert alert-${type}'>${message}</div>`;
        el.style.display = 'block';
        setTimeout(()=> el.style.display = 'none', 3000);
    }

    // Export to window for legacy inline usage
    window.adminUI = adminUI;
})();
