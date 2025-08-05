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
                                    <span>Export ABK</span>
                                </li>
                            @elseif(request()->routeIs('abk.index'))
                                <li class="breadcrumb-item active">
                                    <i class="bi bi-chevron-right"></i>
                                    <span>Data ABK</span>
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
                                    <span>Monitoring Sertijab</span>
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
                            
                        @elseif(request()->routeIs('settings.*'))
                            <li class="breadcrumb-item active">
                                <i class="bi bi-chevron-right"></i>
                                <span>Pengaturan</span>
                            </li>
                            
                        @elseif(request()->routeIs('profile.*'))
                            <li class="breadcrumb-item active">
                                <i class="bi bi-chevron-right"></i>
                                <span>Profil Admin</span>
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right Section - Actions & Profile -->
        <div class="navbar-right">
            <!-- Notifications -->
            <div class="navbar-notifications">
                <button class="notification-btn" id="notificationBtn">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h6>Notifikasi</h6>
                        <span class="notification-count">3 baru</span>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notification-icon bg-primary">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Dokumen Sertijab Baru</div>
                                <div class="notification-text">ABK Ahmad Yani telah mengirim dokumen</div>
                                <div class="notification-time">2 menit yang lalu</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon bg-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Dokumen Menunggu Verifikasi</div>
                                <div class="notification-text">5 dokumen memerlukan persetujuan</div>
                                <div class="notification-time">1 jam yang lalu</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon bg-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Backup Berhasil</div>
                                <div class="notification-text">Backup database telah selesai</div>
                                <div class="notification-time">3 jam yang lalu</div>
                            </div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="#" class="view-all-btn">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

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

                <!-- Profile Dropdown -->
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
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="dropdown-item">
                            <i class="bi bi-gear"></i>
                            <span>Pengaturan</span>
                        </a>
                        <div class="dropdown-divider"></div>
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
    padding: 10px 18px;
    color: var(--text-dark);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
}

.dropdown-item:hover {
    background: #f8fafc;
    color: var(--primary-blue);
}

.dropdown-item i {
    width: 16px;
    font-size: 14px;
    text-align: center;
}

.dropdown-item.logout-item {
    color: #ef4444;
}

.dropdown-item.logout-item:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 6px 0;
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
});
</script>