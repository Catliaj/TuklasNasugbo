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
