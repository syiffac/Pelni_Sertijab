<nav class="navbar" id="navbar">
    <div class="navbar-container">
        <!-- Left Section - Logo & Title -->
        <div class="navbar-left">
            <div class="navbar-logo">
                <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI" class="logo-img">
                <div class="logo-text">
                    <span class="logo-title">PELNI</span>
                    <span class="logo-subtitle">Sertijab System</span>
                </div>
            </div>
        </div>

        <!-- Center Section - Breadcrumb -->
        <div class="navbar-center">
            <div class="breadcrumb-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <i class="bi bi-house-door"></i>
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        
                        @if(request()->routeIs('abk.*'))
                            <li class="breadcrumb-item">
                                <i class="bi bi-chevron-right"></i>
                                <span>Kelola ABK</span>
                            </li>
                            @if(request()->routeIs('abk.create'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Tambah ABK</span>
                                </li>
                            @elseif(request()->routeIs('abk.edit'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Edit ABK</span>
                                </li>
                            @elseif(request()->routeIs('abk.export*'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Export & Import</span>
                                </li>
                            @elseif(request()->routeIs('abk.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Data ABK</span>
                                </li>
                            @endif
                            
                        @elseif(request()->routeIs('mutasi.*'))
                            <li class="breadcrumb-item">
                                <i class="bi bi-chevron-right"></i>
                                <span>Kelola Mutasi</span>
                            </li>
                            @if(request()->routeIs('mutasi.create'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Tambah Mutasi</span>
                                </li>
                            @elseif(request()->routeIs('mutasi.edit'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Edit Mutasi</span>
                                </li>
                            @elseif(request()->routeIs('mutasi.show'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Detail Mutasi</span>
                                </li>
                            @elseif(request()->routeIs('mutasi.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Data Mutasi</span>
                                </li>
                            @endif
                            

                        @elseif(request()->routeIs('kapal.*'))
                            <li class="breadcrumb-item">
                                <i class="bi bi-chevron-right"></i>
                                <span>Data Kapal</span>
                            </li>
                            @if(request()->routeIs('kapal.create'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Tambah Kapal</span>
                                </li>
                            @elseif(request()->routeIs('kapal.edit'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Edit Kapal</span>
                                </li>
                            @elseif(request()->routeIs('kapal.show'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Detail Kapal</span>
                                </li>
                            @elseif(request()->routeIs('kapal.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Data Kapal</span>
                                </li>
                            @endif
                            
                        @elseif(request()->routeIs('monitoring.*'))
                            <li class="breadcrumb-item">
                                <i class="bi bi-chevron-right"></i>
                                <span>Monitoring</span>
                            </li>
                            @if(request()->routeIs('monitoring.sertijab*'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Dokumen Sertijab</span>
                                </li>
                            @elseif(request()->routeIs('monitoring.documents*'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Verifikasi Dokumen</span>
                                </li>
                            @elseif(request()->routeIs('monitoring.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Dashboard Monitoring</span>
                                </li>
                            @endif
                            
                        @elseif(request()->routeIs('arsip.*'))
                            <li class="breadcrumb-item">
                                <i class="bi bi-chevron-right"></i>
                                <span>Arsip Sertijab</span>
                            </li>
                            @if(request()->routeIs('arsip.search'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Pencarian Arsip</span>
                                </li>
                            @elseif(request()->routeIs('arsip.create'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Tambah Arsip</span>
                                </li>
                            @elseif(request()->routeIs('arsip.edit'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Edit Arsip</span>
                                </li>
                            @elseif(request()->routeIs('arsip.show'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Detail Arsip</span>
                                </li>
                            @elseif(request()->routeIs('arsip.laporan*'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Laporan Arsip</span>
                                </li>
                            @elseif(request()->routeIs('arsip.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Data Arsip</span>
                                </li>
                            @endif
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right Section - Actions & Profile -->
        <div class="navbar-right">
            <!-- Notifications - START EDIT -->
            <div class="navbar-notifications">
                <button class="notification-btn" id="notificationBtn">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge" id="notificationBadge"></span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h6>Notifikasi</h6>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm p-0 text-decoration-none" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item" type="button" id="markAllAsReadBtn">
                                        <i class="bi bi-check-all me-2"></i> Tandai semua telah dibaca
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" type="button" id="clearAllNotificationsBtn">
                                        <i class="bi bi-trash me-2"></i> Hapus semua notifikasi
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="empty-notification text-center py-4">
                            <i class="bi bi-bell text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">Tidak ada notifikasi</p>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="{{ route('notifications.index') }}" class="view-all-btn">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>
            <!-- Notifications - END EDIT -->

            <!-- User Profile -->
            <div class="navbar-profile">
                <button class="profile-btn" id="profileBtn">
                    <div class="profile-avatar">
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Admin" class="avatar-img">
                    </div>
                    <div class="profile-info">
                        <span class="profile-name">{{ auth()->user()->nama_admin ?? 'Administrator' }}</span>
                        <span class="profile-role">{{ auth()->user()->jabatan ?? 'Admin' }}</span>
                    </div>
                    <i class="bi bi-chevron-down profile-arrow"></i>
                </button>

                <!-- Profile Dropdown - SIMPLIFIED -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-avatar-large">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Admin">
                        </div>
                        <div class="profile-details">
                            <div class="profile-name-large">{{ auth()->user()->nama_admin ?? 'Administrator' }}</div>
                            <div class="profile-email">{{ auth()->user()->email ?? 'admin@pelni.co.id' }}</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-menu">
                        <!-- ONLY LOGOUT ITEM - REMOVED PROFILE & SETTINGS -->
                        <a href="{{ route('logout') }}" class="dropdown-item logout-item" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</nav>

<style>
/* Navbar Variables - UPDATED */
:root {
    --navbar-height: 70px;
    --primary-blue: #2A3F8E;
    --secondary-blue: #3b82f6;
    --light-blue: #8CB4F5;
    --navbar-bg: #ffffff;
    --navbar-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Navbar Container */
.navbar {
    position: fixed;
    top: 0;
    left: 280px; /* Default sidebar width */
    right: 0;
    height: var(--navbar-height);
    background: var(--navbar-bg);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-color);
    box-shadow: var(--navbar-shadow);
    z-index: 100;
    transition: var(--transition);
}

.navbar.sidebar-collapsed {
    left: 70px; /* Collapsed sidebar width */
}

.navbar-container {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: relative;
    max-width: 100%;
}

/* Left Section - Logo */
.navbar-left {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 8px;
}

.logo-img {
    height: 28px;
    width: auto;
    object-fit: contain;
    transition: var(--transition);
}

.logo-img:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 2px 6px rgba(42, 63, 142, 0.2));
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.logo-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary-blue);
    letter-spacing: -0.3px;
}

.logo-subtitle {
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Center Section - Breadcrumb - DIPERLUAS KARENA TIDAK ADA SEARCH */
.navbar-center {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 40px; /* INCREASED MARGIN karena tidak ada search */
    min-width: 0;
}

.breadcrumb-container {
    width: 100%;
    max-width: 800px; /* INCREASED MAX WIDTH */
}

.breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 14px;
    gap: 8px;
    flex-wrap: wrap;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    white-space: nowrap;
}

.breadcrumb-item a {
    color: var(--text-muted);
    text-decoration: none;
    transition: var(--transition);
}

.breadcrumb-item a:hover {
    color: var(--primary-blue);
}

.breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
}

.breadcrumb-item i {
    font-size: 12px;
    flex-shrink: 0;
}

/* Right Section - SIMPLIFIED */
.navbar-right {
    display: flex;
    align-items: center;
    gap: 16px; /* INCREASED GAP karena search sudah dihapus */
    flex-shrink: 0;
    margin-left: auto;
}

/* Notifications */
.navbar-notifications {
    position: relative;
    flex-shrink: 0;
}

.notification-btn {
    position: relative;
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.notification-btn:hover {
    color: var(--primary-blue);
    background: rgba(42, 63, 142, 0.1);
}

.notification-badge {
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ef4444;
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 8px;
    min-width: 16px;
    text-align: center;
    line-height: 1.1;
    border: 2px solid white;
}

.notification-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 340px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid var(--border-color);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
}

.notification-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.notification-header {
    padding: 16px 18px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
}

.notification-count {
    font-size: 11px;
    color: var(--text-muted);
    background: #f3f4f6;
    padding: 3px 8px;
    border-radius: 6px;
}

.notification-list {
    max-height: 280px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    padding: 14px 18px;
    border-bottom: 1px solid #f3f4f6;
    transition: var(--transition);
    cursor: pointer;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: rgba(42, 63, 142, 0.02);
    position: relative;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--primary-blue);
}

.notification-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.notification-icon.bg-primary { background: var(--primary-blue); }
.notification-icon.bg-warning { background: #f59e0b; }
.notification-icon.bg-success { background: #10b981; }

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 3px;
    line-height: 1.3;
}

.notification-text {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 3px;
    line-height: 1.3;
}

.notification-time {
    font-size: 10px;
    color: var(--text-muted);
}

.notification-footer {
    padding: 12px 18px;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.view-all-btn {
    color: var(--primary-blue);
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: var(--transition);
}

.view-all-btn:hover {
    color: var(--secondary-blue);
}

/* Profile */
.navbar-profile {
    position: relative;
    flex-shrink: 0;
}

.profile-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 10px;
    transition: var(--transition);
    max-width: 220px; /* INCREASED WIDTH karena ada lebih banyak ruang */
}

.profile-btn:hover {
    background: #f8fafc;
}

.profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--border-color);
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
    min-width: 0;
    flex: 1;
}

.profile-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px; /* INCREASED WIDTH */
}

.profile-role {
    font-size: 10px;
    color: var(--text-muted);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px; /* INCREASED WIDTH */
}

.profile-arrow {
    font-size: 11px;
    color: var(--text-muted);
    transition: var(--transition);
    flex-shrink: 0;
}

.profile-btn.active .profile-arrow {
    transform: rotate(180deg);
}

.profile-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 260px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid var(--border-color);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
}

.profile-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-dropdown-header {
    padding: 18px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-avatar-large {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--border-color);
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    flex: 1;
    min-width: 0;
}

.profile-name-large {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.profile-email {
    font-size: 12px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.profile-dropdown-menu {
    padding: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px; /* INCREASED PADDING for single logout item */
    color: var(--text-dark);
    text-decoration: none;
    font-size: 14px; /* INCREASED FONT SIZE */
    transition: var(--transition);
    justify-content: center; /* CENTER the logout item */
}

.dropdown-item:hover {
    background: #f8fafc;
    color: var(--primary-blue);
}

.dropdown-item i {
    width: 18px; /* INCREASED ICON SIZE */
    font-size: 16px; /* INCREASED ICON SIZE */
    text-align: center;
}

.dropdown-item.logout-item {
    color: #ef4444;
    font-weight: 600; /* MAKE LOGOUT MORE PROMINENT */
}

.dropdown-item.logout-item:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    transform: translateY(-1px); /* SUBTLE HOVER EFFECT */
}

/* Remove dropdown divider styles since not needed */
.dropdown-divider {
    display: none;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transition);
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
}

.mobile-menu-toggle:hover {
    color: var(--primary-blue);
    background: rgba(42, 63, 142, 0.1);
}

/* Toast Container */
.toast-container {
    z-index: 1080;
}

.toast {
    width: 350px;
    max-width: 100%;
    font-size: 14px;
    pointer-events: auto;
    background-color: white;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    opacity: 1;
    transition: all 0.3s ease;
}

.toast:not(.show) {
    opacity: 0;
    transform: translateX(100%);
}

/* Notification Actions */
.notification-actions {
    display: flex;
    gap: 8px;
    margin-top: 3px;
    opacity: 0;
    height: 0;
    overflow: hidden;
    transition: all 0.2s ease;
}

.notification-item:hover .notification-actions {
    opacity: 1;
    height: 20px;
}

.notification-actions button {
    padding: 0;
    background: none;
    border: none;
    font-size: 11px;
    color: #6c757d;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 3px;
}

.notification-actions button:hover {
    color: var(--primary-blue);
}

.notification-delete-btn:hover {
    color: #dc3545 !important;
}

/* Empty notification styling */
.empty-notification {
    padding: 20px;
    text-align: center;
    color: var(--text-muted);
}

/* Animation for new notifications */
@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-item {
    animation: fadeInRight 0.3s forwards;
}

/* Responsive Design - DIPERBAIKI untuk layout tanpa search */
@media (max-width: 1200px) {
    .profile-info {
        display: none;
    }
    
    .profile-btn {
        padding: 6px;
    }
    
    .navbar-center {
        margin: 0 24px; /* ADJUSTED margin */
    }
}

@media (max-width: 768px) {
    .navbar {
        left: 0;
        padding: 0;
    }
    
    .navbar-container {
        padding: 0 16px;
    }
    
    .navbar-center {
        display: none;
    }
    
    .notification-dropdown,
    .profile-dropdown {
        width: calc(100vw - 32px);
        right: 16px;
    }
    
    .mobile-menu-toggle {
        display: flex;
    }
    
    .logo-text {
        display: none;
    }
    
    .logo-img {
        height: 24px;
    }
    
    .navbar-right {
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .navbar-container {
        padding: 0 12px;
    }
    
    .navbar-right {
        gap: 4px;
    }
    
    .navbar-notifications {
        display: none;
    }
    
    .profile-info {
        display: none;
    }
    
    .notification-dropdown,
    .profile-dropdown {
        width: calc(100vw - 24px);
        right: 12px;
    }
}

/* Scrollbar for dropdowns */
.notification-list::-webkit-scrollbar {
    width: 4px;
}

.notification-list::-webkit-scrollbar-track {
    background: transparent;
}

.notification-list::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 2px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

/* Animation improvements */
.notification-dropdown,
.profile-dropdown {
    animation: dropdownFadeIn 0.2s ease-out;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Focus states for accessibility */
.notification-btn:focus,
.profile-btn:focus,
.mobile-menu-toggle:focus {
    outline: 2px solid var(--primary-blue);
    outline-offset: 2px;
    border-radius: 8px;
}

.dropdown-item:focus {
    background: #f8fafc;
    color: var(--primary-blue);
    outline: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const navbar = document.getElementById('navbar');
    const sidebar = document.getElementById('sidebar');

    // Toggle notification dropdown
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('show');
        profileDropdown.classList.remove('show');
        profileBtn.classList.remove('active');
    });

    // Toggle profile dropdown
    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('show');
        profileBtn.classList.toggle('active');
        notificationDropdown.classList.remove('show');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        notificationDropdown.classList.remove('show');
        profileDropdown.classList.remove('show');
        profileBtn.classList.remove('active');
    });

    // Prevent dropdown close when clicking inside
    notificationDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    profileDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Adjust navbar position based on sidebar state
    function adjustNavbar() {
        if (sidebar && sidebar.classList.contains('collapsed')) {
            navbar.classList.add('sidebar-collapsed');
        } else {
            navbar.classList.remove('sidebar-collapsed');
        }
    }

    // Listen for sidebar changes
    if (sidebar) {
        const observer = new MutationObserver(adjustNavbar);
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        adjustNavbar(); // Initial check
    }

    // Mark notifications as read when clicked
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            this.classList.remove('unread');
            // Update notification count
            const badge = document.querySelector('.notification-badge');
            const count = document.querySelector('.notification-count');
            if (badge && count) {
                let currentCount = parseInt(badge.textContent);
                if (currentCount > 0) {
                    currentCount--;
                    badge.textContent = currentCount;
                    count.textContent = currentCount + ' baru';
                    
                    if (currentCount === 0) {
                        badge.style.display = 'none';
                        count.textContent = 'Tidak ada notifikasi baru';
                    }
                }
            }
        });
    });

    // Handle mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    if (mobileMenuToggle && sidebar) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
    }

    // Keyboard navigation for dropdowns
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            notificationDropdown.classList.remove('show');
            profileDropdown.classList.remove('show');
            profileBtn.classList.remove('active');
        }
    });

    // Auto-hide dropdowns on window resize
    window.addEventListener('resize', function() {
        notificationDropdown.classList.remove('show');
        profileDropdown.classList.remove('show');
        profileBtn.classList.remove('active');
    });

    // New notification functionality - START EDIT
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
    const clearAllNotificationsBtn = document.getElementById('clearAllNotificationsBtn');
    let notifications = [];
    let lastSeenNotifications = [];

    // Initial load
    fetchNotifications();

    // Set interval to periodically check for new notifications (every 30 seconds)
    setInterval(fetchNotifications, 30000);

    // Fetch notifications
    function fetchNotifications() {
        fetch('{{ route("api.notifications") }}')
            .then(response => response.json())
            .then(data => {
                notifications = data.notifications;
                updateNotificationBadge();
                renderNotificationDropdown();
                
                // Check for new notifications
                const newNotifications = checkForNewNotifications(data.notifications);
                if (newNotifications.length > 0) {
                    newNotifications.forEach(notification => {
                        showToastNotification(notification);
                    });
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Check for new notifications
    function checkForNewNotifications(currentNotifications) {
        const currentIds = currentNotifications.map(n => n.id);
        const newNotifications = currentNotifications.filter(n => !lastSeenNotifications.includes(n.id));
        lastSeenNotifications = currentIds;
        return newNotifications;
    }

    // Update notification badge
    function updateNotificationBadge() {
        const unreadCount = notifications.filter(n => !n.read).length;
        
        if (unreadCount > 0) {
            notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            notificationBadge.style.display = 'flex';
        } else {
            notificationBadge.style.display = 'none';
        }
    }

    // Render notification dropdown
    function renderNotificationDropdown() {
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="empty-notification text-center py-4">
                    <i class="bi bi-bell text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0 mt-2">Tidak ada notifikasi</p>
                </div>
            `;
            return;
        }

        let html = '';
        notifications.slice(0, 5).forEach(notification => {
            html += createNotificationItemHTML(notification);
        });

        notificationList.innerHTML = html;

        // Attach event listeners to notification items
        document.querySelectorAll('.mark-as-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const notificationId = this.dataset.id;
                markAsRead(notificationId);
            });
        });

        document.querySelectorAll('.notification-delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const notificationId = this.dataset.id;
                deleteNotification(notificationId);
            });
        });
        
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const link = this.dataset.link;
                if (link) {
                    window.location.href = link;
                }
            });
        });
    }

    // Create HTML for notification item
    function createNotificationItemHTML(notification) {
        return `
            <div class="notification-item ${!notification.read ? 'unread' : ''}" 
                 id="notification-${notification.id}" 
                 data-link="${notification.link || ''}">
                <div class="notification-icon ${notification.type}">
                    <i class="bi bi-${notification.icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-text">${notification.message}</div>
                    <div class="notification-time">${formatDate(notification.created_at)}</div>
                    <div class="notification-actions">
                        ${!notification.read ? 
                            `<button class="mark-as-read-btn" data-id="${notification.id}">
                                <i class="bi bi-check2"></i> Tandai dibaca
                            </button>` : ''}

                        <button class="notification-delete-btn" data-id="${notification.id}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Show toast notification
    function showToastNotification(notification) {
        // Create toast container if not exists
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1080';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast
        const toastId = `toast-${Date.now()}`;
        const toast = document.createElement('div');
        toast.className = 'toast show';
        toast.id = toastId;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="toast-header">
                <div class="notification-icon ${notification.type} me-2" style="width: 20px; height: 20px;">
                    <i class="bi bi-${notification.icon}" style="font-size: 10px;"></i>
                </div>
                <strong class="me-auto">${notification.title}</strong>
                <small>${formatDate(notification.created_at)}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <p class="mb-2">${notification.message}</p>
                ${notification.link ? 
                    `<div class="mt-2 pt-2 border-top">
                        <a href="${notification.link}" class="btn btn-primary btn-sm">Lihat detail</a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">
                            Tutup
                        </button>
                    </div>` : 
                    `<div class="mt-2 pt-2 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">
                            Tutup
                        </button>
                    </div>`
                }
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Handle close button
        toast.querySelector('.btn-close, .btn-secondary').addEventListener('click', function() {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    // Mark notification as read
    function markAsRead(notificationId) {
        fetch(`{{ url('/notifications') }}/${notificationId}/mark-as-read`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local notifications data
                const index = notifications.findIndex(n => n.id == notificationId);
                if (index !== -1) {
                    notifications[index].read = true;
                    
                    // Update UI
                    const notificationItem = document.getElementById(`notification-${notificationId}`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        const markAsReadBtn = notificationItem.querySelector('.mark-as-read-btn');
                        if (markAsReadBtn) markAsReadBtn.remove();
                    }
                    
                    // Update badge
                    updateNotificationBadge();
                }
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Delete notification
    function deleteNotification(notificationId) {
        fetch(`{{ url('/notifications') }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local notifications data
                notifications = notifications.filter(n => n.id != notificationId);
                
                // Update UI with animation
                const notificationItem = document.getElementById(`notification-${notificationId}`);
                if (notificationItem) {
                    notificationItem.style.height = notificationItem.offsetHeight + 'px';
                    notificationItem.style.opacity = '0';
                    notificationItem.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        notificationItem.style.height = '0';
                        notificationItem.style.padding = '0';
                        notificationItem.style.margin = '0';
                        notificationItem.style.borderWidth = '0';
                        
                        setTimeout(() => {
                            notificationItem.remove();
                            
                            // If no more notifications, show empty state
                            if (notifications.length === 0) {
                                renderNotificationDropdown();
                            }
                        }, 300);
                    }, 300);
                }
                
                // Update badge
                updateNotificationBadge();
            }
        })
        .catch(error => console.error('Error deleting notification:', error));
    }

    // Format date for display
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            // Today
            return 'Hari ini ' + date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } else if (diffDays === 1) {
            // Yesterday
            return 'Kemarin ' + date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } else if (diffDays < 7) {
            // This week
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return days[date.getDay()] + ' ' + date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } else {
            // Older
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }
    }

    // Mark all as read
    markAllAsReadBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        fetch('{{ route("notifications.mark-all-as-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local notifications data
                notifications.forEach(notification => {
                    notification.read = true;
                });
                
                // Update UI
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const markAsReadBtn = item.querySelector('.mark-as-read-btn');
                    if (markAsReadBtn) markAsReadBtn.remove();
                });
                
                // Update badge
                updateNotificationBadge();
            }
        })
        .catch(error => console.error('Error marking all notifications as read:', error));
    });

    // Clear all notifications
    clearAllNotificationsBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        if (!confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) return;
        
        fetch('{{ route("notifications.destroy-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local notifications data
                notifications = [];
                
                // Update UI
                renderNotificationDropdown();
                
                // Update badge
                updateNotificationBadge();
            }
        })
        .catch(error => console.error('Error clearing all notifications:', error));
    });
});
</script>