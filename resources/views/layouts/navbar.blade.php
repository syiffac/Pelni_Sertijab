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
            <!-- Search -->
            <div class="navbar-search">
                <div class="search-container">
                    <form action="{{ route('arsip.search') }}" method="GET" id="searchForm">
                        <input type="text" name="q" class="search-input" placeholder="Cari arsip sertijab..." value="{{ request('q') }}">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

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
                        <span class="profile-name">{{ auth()->guard('admin')->user()->nama_admin ?? 'Administrator' }}</span>
                        <span class="profile-role">{{ auth()->guard('admin')->user()->jabatan ?? 'Admin' }}</span>
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
                            <div class="profile-name-large">{{ auth()->guard('admin')->user()->nama_admin ?? 'Administrator' }}</div>
                            <div class="profile-email">{{ auth()->guard('admin')->user()->email ?? 'admin@pelni.co.id' }}</div>
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

<!-- ... existing CSS ... -->
<style>
/* Navbar Variables */
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
    padding: 0 24px;
    position: relative;
}

/* Left Section - Logo */
.navbar-left {
    display: flex;
    align-items: center;
    margin-right: 32px;
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-img {
    height: 40px;
    width: auto;
    object-fit: contain;
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.logo-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-blue);
    letter-spacing: -0.5px;
}

.logo-subtitle {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Center Section - Breadcrumb */
.navbar-center {
    flex: 1;
    display: flex;
    align-items: center;
    margin: 0 32px;
}

.breadcrumb-container {
    width: 100%;
}

.breadcrumb {
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 14px;
    gap: 8px;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
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
}

/* Right Section */
.navbar-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* Search */
.navbar-search {
    position: relative;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
}

.search-container form {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.search-container .search-input {
    width: 280px;
    height: 40px;
    padding: 0 40px 0 16px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 14px;
    background: #f8fafc;
    transition: var(--transition);
    outline: none;
}

.search-container .search-input:focus {
    border-color: var(--primary-blue);
    background: white;
    box-shadow: 0 0 0 3px rgba(42, 63, 142, 0.1);
}

.search-container .search-btn {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: var(--transition);
}

.search-container .search-btn:hover {
    color: var(--primary-blue);
    background: rgba(42, 63, 142, 0.1);
}

/* Notifications */
.navbar-notifications {
    position: relative;
}

.notification-btn {
    position: relative;
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transition);
}

.notification-btn:hover {
    color: var(--primary-blue);
    background: rgba(42, 63, 142, 0.1);
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    line-height: 1.2;
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 350px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid var(--border-color);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
    margin-top: 8px;
}

.notification-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.notification-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
}

.notification-count {
    font-size: 12px;
    color: var(--text-muted);
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    transition: var(--transition);
    cursor: pointer;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: rgba(42, 63, 142, 0.02);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.notification-icon.bg-primary { background: var(--primary-blue); }
.notification-icon.bg-warning { background: #f59e0b; }
.notification-icon.bg-success { background: #10b981; }

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.notification-text {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.notification-time {
    font-size: 11px;
    color: var(--text-muted);
}

.notification-footer {
    padding: 12px 20px;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.view-all-btn {
    color: var(--primary-blue);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

/* Profile */
.navbar-profile {
    position: relative;
}

.profile-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 10px;
    transition: var(--transition);
}

.profile-btn:hover {
    background: #f8fafc;
}

.profile-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
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
}

.profile-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.2;
}

.profile-role {
    font-size: 11px;
    color: var(--text-muted);
    line-height: 1.2;
}

.profile-arrow {
    font-size: 12px;
    color: var(--text-muted);
    transition: var(--transition);
}

.profile-btn.active .profile-arrow {
    transform: rotate(180deg);
}

.profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 280px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid var(--border-color);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
    margin-top: 8px;
}

.profile-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-dropdown-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-avatar-large {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    flex: 1;
}

.profile-name-large {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 2px;
}

.profile-email {
    font-size: 13px;
    color: var(--text-muted);
}

.profile-dropdown-menu {
    padding: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: var(--text-dark);
    text-decoration: none;
    font-size: 14px;
    transition: var(--transition);
}

.dropdown-item:hover {
    background: #f8fafc;
    color: var(--primary-blue);
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
    margin: 8px 0;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transition);
}

.mobile-menu-toggle:hover {
    color: var(--primary-blue);
    background: rgba(42, 63, 142, 0.1);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .search-input {
        width: 200px;
    }
    
    .profile-info {
        display: none;
    }
}

@media (max-width: 768px) {
    .navbar {
        left: 0;
        padding: 0 16px;
    }
    
    .navbar-container {
        padding: 0 16px;
    }
    
    .navbar-center {
        display: none;
    }
    
    .navbar-search {
        display: none;
    }
    
    .notification-dropdown,
    .profile-dropdown {
        width: calc(100vw - 32px);
        right: 16px;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .logo-text {
        display: none;
    }
}

@media (max-width: 480px) {
    .navbar-right {
        gap: 8px;
    }
    
    .navbar-notifications,
    .profile-info {
        display: none;
    }
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

    // Enhanced search functionality
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');

    if (searchForm && searchInput && searchBtn) {
        // Handle form submission
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (!query) {
                e.preventDefault();
                searchInput.focus();
                return false;
            }
        });

        // Handle enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchForm.submit();
            }
        });

        // Handle search button click
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchForm.submit();
        });

        // Auto-focus search input with keyboard shortcut (Ctrl/Cmd + K)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
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
});
</script>