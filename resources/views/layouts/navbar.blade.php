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

<!-- filepath: resources/views/layouts/navbar.blade.php -->
<!-- Hapus bagian JavaScript yang duplikat dan conflicting -->

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

    // NOTIFICATION FUNCTIONALITY - SIMPLIFIED VERSION
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const clearAllNotificationsBtn = document.getElementById('clearAllNotificationsBtn');
    
    // Session storage untuk tracking dismissed notifications
    const dismissedNotifications = JSON.parse(sessionStorage.getItem('dismissedNotifications') || '[]');

    // Initial load
    loadNotifications();

    // Set interval to periodically check for new notifications (every 30 seconds)
    setInterval(loadNotifications, 30000);

    // Load notifications function
    function loadNotifications() {
        fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(data.unread_count);
                    updateNotificationDropdown(data.notifications);
                    
                    // Show pop-up for new unread notifications (excluding dismissed ones)
                    const newUnreadNotifications = data.notifications.filter(n => 
                        !n.read && !dismissedNotifications.includes(n.id)
                    );
                    
                    if (newUnreadNotifications.length > 0) {
                        showNotificationPopup(newUnreadNotifications[0]); // Show first new notification
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }

    function updateNotificationBadge(count) {
        if (count > 0) {
            notificationBadge.textContent = count > 9 ? '9+' : count;
            notificationBadge.style.display = 'flex';
        } else {
            notificationBadge.style.display = 'none';
        }
    }

    function updateNotificationDropdown(notifications) {
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
            // PERBAIKAN: Sesuaikan icon CSS dengan jenis notifikasi
            let iconClass = 'bg-primary'; // default
            if (notification.type === 'info') iconClass = 'bg-primary';
            else if (notification.type === 'warning') iconClass = 'bg-warning';
            else if (notification.type === 'success') iconClass = 'bg-success';
            else if (notification.type === 'danger') iconClass = 'bg-danger';
            
            html += `
                <div class="notification-item ${!notification.read ? 'unread' : ''}" data-id="${notification.id}">
                    <div class="notification-icon ${iconClass}">
                        <i class="bi bi-${notification.icon}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-text">${notification.message}</div>
                        <div class="notification-time">${notification.created_at}</div>
                        <div class="notification-actions">
                            <button class="notification-delete-btn" data-id="${notification.id}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        notificationList.innerHTML = html;

        // Attach event listeners for delete buttons
        document.querySelectorAll('.notification-delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const notificationId = this.dataset.id;
                deleteNotificationFromDropdown(notificationId);
            });
        });
        
        // Add click handlers for notification items
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const link = notifications.find(n => n.id == this.dataset.id)?.link;
                if (link && link !== '#') {
                    window.location.href = link;
                }
            });
        });
    }

    // Di navbar.blade.php, ganti function showNotificationPopup:

function showNotificationPopup(notification) {
    // Create popup HTML
    const popup = document.createElement('div');
    popup.className = 'notification-popup position-fixed';
    popup.id = `popup-${notification.id}`;
    popup.style.cssText = `
        top: 20px;
        right: 20px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 300px;
        z-index: 9999;
        animation: slideInRight 0.3s ease;
    `;
    
    popup.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0">${notification.title}</h6>
            <button type="button" class="btn-close popup-close-btn" data-id="${notification.id}" style="font-size: 0.8rem;"></button>
        </div>
        <p class="small mb-2">${notification.message}</p>
        <div class="d-flex gap-2">
            ${notification.link && notification.link !== '#' ? 
                `<a href="${notification.link}" class="btn btn-sm btn-primary popup-view-btn">
                    <i class="bi bi-eye"></i> Lihat
                </a>` : ''
            }
            <button class="btn btn-sm btn-outline-danger popup-delete-btn" data-id="${notification.id}">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    `;
    
    // Add CSS animation if not exists
    if (!document.getElementById('notification-popup-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-popup-styles';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .notification-popup .btn-close {
                background: none;
                border: none;
                font-size: 1rem;
                cursor: pointer;
                opacity: 0.5;
                transition: opacity 0.3s;
            }
            .notification-popup .btn-close:hover {
                opacity: 1;
            }
            .notification-popup .btn-close::before {
                content: '×';
                font-size: 1.5rem;
                font-weight: bold;
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(popup);
    
    // PERBAIKAN: Add event listeners dengan method yang benar
    // Close button
    const closeBtn = popup.querySelector('.popup-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dismissNotificationPopup(notification.id, popup);
        });
    }
    
    // Delete button
    const deleteBtn = popup.querySelector('.popup-delete-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            deleteNotificationFromPopup(notification.id, popup);
        });
    }
    
    // View button (jika ada)
    const viewBtn = popup.querySelector('.popup-view-btn');
    if (viewBtn) {
        viewBtn.addEventListener('click', function(e) {
            // Let the link work normally, but also dismiss popup
            dismissNotificationPopup(notification.id, popup);
        });
    }
    
    // Auto close after 10 seconds
    setTimeout(() => {
        if (popup.parentNode) {
            dismissNotificationPopup(notification.id, popup);
        }
    }, 10000);
}

    // Ganti function dismissNotificationPopup:

function dismissNotificationPopup(notificationId, popup) {
    // Add to dismissed list
    if (!dismissedNotifications.includes(notificationId)) {
        dismissedNotifications.push(notificationId);
        sessionStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
    }
    
    // Remove popup with animation
    if (popup && popup.parentNode) {
        popup.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (popup.parentNode) {
                popup.remove();
            }
        }, 300);
    }
}

    // Ganti function deleteNotificationFromPopup:

function deleteNotificationFromPopup(notificationId, popup) {
    if (!confirm('Hapus notifikasi ini?')) {
        return;
    }
    
    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to dismissed list
            if (!dismissedNotifications.includes(notificationId)) {
                dismissedNotifications.push(notificationId);
                sessionStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
            }
            
            // Remove popup with animation
            if (popup && popup.parentNode) {
                popup.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (popup.parentNode) {
                        popup.remove();
                    }
                }, 300);
            }
            
            // Reload notifications to update badge and dropdown
            loadNotifications();
            
            // Show success message
            showToast('Notifikasi berhasil dihapus', 'success');
        } else {
            showToast('Gagal menghapus notifikasi', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
        showToast('Terjadi kesalahan saat menghapus notifikasi', 'error');
    });
}

    function deleteNotificationFromDropdown(notificationId) {
        if (!confirm('Hapus notifikasi ini?')) {
            return;
        }
        
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toast
                showToast('Notifikasi berhasil dihapus', 'success');
                
                // Reload notifications to update badge and dropdown
                loadNotifications();
            } else {
                showToast(data.message || 'Gagal menghapus notifikasi', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            showToast('Terjadi kesalahan saat menghapus notifikasi', 'error');
        });
    }

    // Clear all notifications
    clearAllNotificationsBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        if (!confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) return;
        
        fetch('/notifications/destroy-all', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Semua notifikasi berhasil dihapus', 'success');
                // Reload notifications
                loadNotifications();
            } else {
                showToast(data.message || 'Gagal menghapus semua notifikasi', 'error');
            }
        })
        .catch(error => {
            console.error('Error clearing all notifications:', error);
            showToast('Terjadi kesalahan saat menghapus notifikasi', 'error');
        });
    });

    // Tambahkan function showToast di akhir script navbar:

// Show toast message for user feedback
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'custom-toast position-fixed';
    toast.style.cssText = `
        top: 80px;
        right: 20px;
        background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
        border: 1px solid ${type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : '#bee5eb'};
        border-radius: 6px;
        padding: 12px 16px;
        max-width: 300px;
        z-index: 9999;
        animation: slideInRight 0.3s ease;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    `;
    
    toast.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <span>${message}</span>
            <button type="button" class="btn-close ms-2" style="font-size: 0.8rem; opacity: 0.7;"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Close button
    const closeBtn = toast.querySelector('.btn-close');
    closeBtn.addEventListener('click', function() {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    });
    
    // Auto close after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }
    }, 3000);
}

});
</script>