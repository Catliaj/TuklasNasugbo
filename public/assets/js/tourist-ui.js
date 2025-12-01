(function(window, document){
  'use strict';

  // Avoid redefining if included multiple times
  if (window._touristUiLoaded) return;
  window._touristUiLoaded = true;

  window.toggleMobileSidebar = function(){
    var s = document.getElementById('sidebar');
    if (!s) return;
    s.classList.toggle('show');
  };

  window.toggleUserDropdown = function(){
    var dd = document.getElementById('userDropdown');
    var nd = document.getElementById('notificationDropdown');
    if (nd) nd.classList.remove('show');
    if (dd) dd.classList.toggle('show');
  };

  window.toggleNotificationDropdown = function(){
    var nd = document.getElementById('notificationDropdown');
    var ud = document.getElementById('userDropdown');
    if (ud) ud.classList.remove('show');
    if (nd) nd.classList.toggle('show');
  };

  window.handleLogout = function(e){
    e && e.preventDefault && e.preventDefault();
    if (confirm('Are you sure you want to logout?')){
      // nice UX: show a toast when available
      if (typeof window.showToast === 'function') window.showToast('Logged Out', 'You are being logged out...');
      setTimeout(function(){ window.location.href = '/users/logout'; }, 600);
    }
  };

  window.showToast = window.showToast || function(title, body){
    var container = document.getElementById('toastContainer');
    if (!container) return;
    var div = document.createElement('div');
    div.className = 'toast align-items-center text-bg-primary border-0';
    div.setAttribute('role','alert');
    div.setAttribute('aria-live','assertive');
    div.setAttribute('aria-atomic','true');
    div.innerHTML = '<div class="d-flex">' +
            '<div class="toast-body"><strong>' + (title || '') + ':</strong> ' + (body || '') + '</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>';
  
    try{
      container.appendChild(div);
      var t = new bootstrap.Toast(div, { delay: 3000 });
      t.show();
      div.addEventListener('hidden.bs.toast', function(){ div.remove(); });
    }catch(e){
      // fallback: alert
      try{ console.log('Toast error', e); }catch(_){}
    }
  };

  window.setLoading = window.setLoading || function(btn, isLoading){
    if (!btn) return;
    var sp = btn.querySelector('.spinner-border');
    var st = btn.querySelector('.save-text');
    if (isLoading){ sp && sp.classList.remove('d-none'); btn.disabled = true; if (st) st.textContent = 'Saving...'; }
    else { sp && sp.classList.add('d-none'); btn.disabled = false; if (st) st.textContent = 'Save Changes'; }
  };

  // Attach one global click handler to close dropdowns when clicking outside
  if (!window._touristUiClickHandlerAttached){
    document.addEventListener('click', function(ev){
      var sidebar = document.getElementById('sidebar');
      var menuBtn = document.querySelector('.mobile-menu-btn');
      var userDropdown = document.getElementById('userDropdown');
      var userAvatar = document.querySelector('.user-avatar');
      var notifDropdown = document.getElementById('notificationDropdown');
      var notifBtn = document.querySelector('.notification-btn');

      if (window.innerWidth <= 992){
        if (sidebar && menuBtn && !sidebar.contains(ev.target) && !menuBtn.contains(ev.target)){
          sidebar.classList.remove('show');
        }
      }

      if (userDropdown && userAvatar && !userAvatar.contains(ev.target) && !userDropdown.contains(ev.target)){
        userDropdown.classList.remove('show');
      }
      if (notifDropdown && notifBtn && !notifBtn.contains(ev.target) && !notifDropdown.contains(ev.target)){
        notifDropdown.classList.remove('show');
      }
    });
    window._touristUiClickHandlerAttached = true;
  }

})(window, document);

// Apply a global SweetAlert2 theme to match the Ocean header design
(function(window, document){
  'use strict';
  function applySwalTheme(){
    if (!window.Swal || window._oceanSwalApplied) return;
    try {
      var themed = window.Swal.mixin({
        customClass: {
          popup: 'ocean-alert',
          title: 'ocean-alert-title',
          htmlContainer: 'ocean-alert-text',
          actions: 'ocean-alert-actions',
          confirmButton: 'ocean-btn',
          cancelButton: 'ocean-btn-cancel',
          icon: 'ocean-alert-icon'
        },
        buttonsStyling: false,
        backdrop: true
      });
      window.Swal = themed;
      window._oceanSwalApplied = true;
    } catch (e) {
      try { console.warn('Failed to apply SweetAlert theme', e); } catch(_){}
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applySwalTheme);
  } else {
    applySwalTheme();
  }
  // Retry in case SweetAlert loads after this file
  setTimeout(applySwalTheme, 500);
  setTimeout(applySwalTheme, 1500);
})(window, document);

// Tourist Notifications: shared fetch/render logic across pages
(function(window, document){
  'use strict';

  if (window._touristNotificationsLoaded) return;
  window._touristNotificationsLoaded = true;

  function apiUrl(path){
    try { return new URL(path, window.location.origin).toString(); } catch { return path; }
  }

  function formatTime(ts){
    if (!ts) return '';
    try { return new Date(String(ts).replace(' ', 'T')).toLocaleString(); } catch { return ts; }
  }

  function escapeHtml(str){
    return String(str || '').replace(/[&<>"']/g, function(s){
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[s]);
    });
  }

  async function refreshUnreadBadge(){
    var badge = document.getElementById('notifBadge');
    if (!badge) return; // page may not have notifications UI
    try {
      var res = await fetch(apiUrl('/tourist/notifications/unread-count'));
      var data = await res.json();
      var count = Number((data && data.count) || 0);
      if (count > 0){
        badge.textContent = String(count);
        badge.style.display = 'inline-block';
      } else {
        badge.style.display = 'none';
      }
    } catch (e){
      // silent fail to avoid breaking page
      try { console.warn('Unread badge fetch failed', e); } catch(_){ }
    }
  }

  async function loadNotifications(){
    var listEl = document.getElementById('notificationList');
    if (!listEl) return; // page may not have dropdown list
    listEl.innerHTML = '<li class="notification-item"><div class="notification-content"><div class="notification-text"><p>Loading...</p></div></div></li>';
    try {
      var res = await fetch(apiUrl('/tourist/notifications/list'));
      var data = await res.json();
      var items = Array.isArray(data && data.notifications) ? data.notifications : [];
      if (items.length === 0){
        listEl.innerHTML = '<li class="notification-item"><div class="notification-content"><div class="notification-text"><p>No notifications yet.</p></div></div></li>';
        return;
      }
      listEl.innerHTML = '';
      items.forEach(function(n){
        var li = document.createElement('li');
        li.className = 'notification-item' + (Number(n.is_read) ? '' : ' unread');
        li.innerHTML = ''+
          '<div class="notification-content">'
            + '<div class="notification-icon ' + (Number(n.is_read) ? 'info' : 'success') + '">'
              + '<i class="bi ' + (Number(n.is_read) ? 'bi-info-circle-fill' : 'bi-check-circle-fill') + '"></i>'
            + '</div>'
            + '<div class="notification-text">'
              + '<h6>' + escapeHtml(n.message || 'Notification') + '</h6>'
              + (n.url ? ('<p><a href="' + encodeURI(n.url) + '">View details</a></p>') : '')
              + '<div class="notification-time">' + formatTime(n.created_at) + '</div>'
            + '</div>'
          + '</div>';
        li.addEventListener('click', function(){ markNotificationRead(n.id); });
        listEl.appendChild(li);
      });
    } catch (e){
      try { console.error('Notifications list fetch failed', e); } catch(_){ }
      listEl.innerHTML = '<li class="notification-item"><div class="notification-content"><div class="notification-text"><p>Failed to load notifications.</p></div></div></li>';
    }
  }

  async function markNotificationRead(id){
    try {
      await fetch(apiUrl('/tourist/notifications/mark-read/' + encodeURIComponent(id)), { method: 'POST' });
      refreshUnreadBadge();
      loadNotifications();
    } catch (e){ try { console.warn('Mark read failed', e); } catch(_){ } }
  }

  async function markAllAsRead(){
    try {
      await fetch(apiUrl('/tourist/notifications/mark-all-read'), { method: 'POST' });
      refreshUnreadBadge();
      loadNotifications();
    } catch (e){ try { console.warn('Mark all read failed', e); } catch(_){ } }
  }

  function boot(){
    refreshUnreadBadge();
    loadNotifications();
  }

  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  // expose to global so existing pages can call
  window.refreshUnreadBadge = refreshUnreadBadge;
  window.loadNotifications = loadNotifications;
  window.markNotificationRead = markNotificationRead;
  window.markAllAsRead = markAllAsRead;

})(window, document);
