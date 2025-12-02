<?php
// Shared Spot Owner mobile header (notifications + profile)
// Usage: <?= view('Pages/spotowner/_mobile_header', ['subtitle' => 'Booking Management']) ?>
?>
<div class="mobile-header">
    <button class="btn btn-link d-lg-none spotowner-toggle" id="sidebarToggle">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="mobile-header-actions">
        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn p-0 border-0 notification-button topbar-avatar topbar-avatar--notification" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <i class="bi bi-bell-fill text-white"></i>
                <span class="notification-badge badge rounded-pill bg-danger" id="notificationBadge" style="display:none"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 380px; max-height: 500px;">
                <div class="dropdown-header d-flex justify-content-between align-items-center bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold">Notifications</h6>
                    <button class="btn btn-sm btn-link text-white text-decoration-none" id="markAllReadBtn">Mark all read</button>
                </div>
                <div class="dropdown-divider m-0"></div>
                <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-1"></i>
                        <p class="mb-0 mt-2">No notifications</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <?php 
            $nameParts = array_filter(explode(' ', trim($FullName ?? '')));
            $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        ?>
        <div class="dropdown">
            <button class="btn p-0 border-0 topbar-avatar topbar-avatar--primary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                <span><?= esc($initials ?: 'SO') ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width:220px">
                <li class="px-3 py-2">
                    <div class="fw-bold"><?= esc($FullName ?? '') ?></div>
                    <div class="small text-muted"><?= esc($email ?? '') ?></div>
                </li>
                <li><a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>         
                <li><hr class="dropdown-divider"></li>
            </ul>
        </div>
    </div>
</div>
