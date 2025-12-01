<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?> - Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/css/admin-style.css")?>">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
</head>
<body>
    <div class="sidebar" id="sidebar" role="navigation" aria-label="Admin sidebar">
        <div class="sidebar-header"><i class="bi bi-compass"></i><span><?= esc($currentSettings['site_title'] ?? 'Tourism Admin') ?></span></div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-item" aria-label="Dashboard"><i class="bi bi-grid"></i><span>Dashboard</span></a>
            <a href="/admin/registrations" class="nav-item" aria-label="Registrations"><i class="bi bi-person-plus"></i><span>Registrations</span></a>
            <a href="/admin/attractions" class="nav-item" aria-label="Attractions"><i class="bi bi-geo-alt"></i><span>Attractions</span></a>
            <a href="/admin/reports" class="nav-item" aria-label="Reports &amp; Analytics"><i class="bi bi-file-bar-graph"></i><span>Reports & Analytics</span></a>
        </nav>
        <div class="sidebar-footer"><!-- Logout moved to profile menu --></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <div class="top-bar" role="banner">
            <button class="btn btn-link text-dark" id="sidebarToggle" aria-controls="sidebar" aria-label="Toggle sidebar"><i class="bi bi-list fs-4"></i></button>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationButtonSettings" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="bi bi-bell-fill text-white"></i>
                        <span class="notification-badge badge rounded-pill bg-danger"><?= (isset($unreadNotifications) && $unreadNotifications > 0) ? esc($unreadNotifications) : '' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" id="notificationMenuSettings" style="min-width:320px">
                        <li class="dropdown-item text-muted small">No new notifications</li>
                    </ul>
                </div>
                <div class="dropdown">
                    <?php $FullName = session()->get('FirstName') . ' ' . session()->get('LastName'); $nameParts = array_filter(explode(' ', trim($FullName))); $initials = strtoupper(substr($nameParts[0] ?? '',0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : '')); ?>
                    <button class="btn p-0 border-0 topbar-avatar topbar-avatar--primary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                        <span><?= esc($initials) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width:220px">
                        <li class="px-3 py-2">
                            <div class="fw-bold"><?= esc($FullName) ?></div>
                            <div class="small text-muted"><?= esc(session()->get('Email')) ?></div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="/admin/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid p-4">
                <?php
                    $settingsPath = WRITEPATH . 'settings.json';
                    $currentSettings = [];
                    if (file_exists($settingsPath)) {
                        $currentSettings = json_decode(file_get_contents($settingsPath), true) ?: [];
                    }
                ?>
            <div class="page-header mb-4">
                <h1 class="h3 fw-bold">Settings</h1>
                <p class="text-muted">Application settings and preferences</p>
            </div>
            <div class="card card-modern mb-4">
                <div class="card-body">
                    <form id="settingsForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Site Title</label>
                                <input type="text" class="form-control" name="site_title" value="<?= esc($currentSettings['site_title'] ?? 'Tuklas Nasugbo') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Primary Color</label>
                                <input type="color" class="form-control form-control-color" name="primary_color" value="<?= esc($currentSettings['primary_color'] ?? '#004a7c') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Items Per Page</label>
                                <input type="number" class="form-control" name="items_per_page" min="1" value="<?= esc($currentSettings['items_per_page'] ?? 12) ?>">
                            </div>
                            <div class="col-12">
                                <div id="settingsAlert" style="display:none"></div>
                                <button class="btn btn-primary" id="saveSettingsBtn">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/admin-ui.js') ?>"></script>
</body>
<script>
document.getElementById('settingsForm').addEventListener('submit', async function(e){
    e.preventDefault(); const btn = document.getElementById('saveSettingsBtn'); btn.disabled = true; btn.innerHTML='Saving...';
    const formData = new FormData(e.target);
    try{
        const tokenName = document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
        const tokenValue = document.querySelector('meta[name="csrf-token-value"]').getAttribute('content');
        formData.append(tokenName, tokenValue);
        const res = await fetch('/admin/settings/update', { method: 'POST', body: formData });
        const data = await res.json();
        const alert = document.getElementById('settingsAlert');
        if (res.ok && data.success) {
            if (window.adminUI && adminUI.formFeedback) adminUI.formFeedback(alert, 'success', data.success);
            else { alert.innerHTML = '<div class="alert alert-success">' + data.success + '</div>'; alert.style.display='block'; setTimeout(()=>alert.style.display='none',3000); }
            // Apply primary color dynamically
            const primaryColor = formData.get('primary_color');
            if (primaryColor) {
                document.documentElement.style.setProperty('--primary-blue', primaryColor);
                if (window.adminUI && adminUI.setPrimaryColor) adminUI.setPrimaryColor(primaryColor);
            }
            // Update site title dynamically if provided
            const siteTitle = formData.get('site_title');
            if (siteTitle) { document.title = siteTitle + ' - Settings'; if (window.adminUI && adminUI.setSiteTitle) adminUI.setSiteTitle(siteTitle); }
            // Pulse a KPI icon to indicate the settings were applied
            if (window.adminUI && adminUI.pulseIcon) adminUI.pulseIcon('.analytics-card .icon-wrapper');
        } else {
            const msg = data.error || 'Failed to save';
            if (window.adminUI && adminUI.formFeedback) adminUI.formFeedback(alert, 'danger', msg);
            else { alert.innerHTML = '<div class="alert alert-danger">' + msg + '</div>'; alert.style.display='block'; }
        }
    }catch(err){ console.error(err); }
    finally { btn.disabled=false; btn.innerHTML='Save Settings'; }
});
</script>
</html>
    