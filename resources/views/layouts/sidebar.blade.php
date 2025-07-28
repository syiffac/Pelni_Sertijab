<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <!-- Logo Full (when expanded) -->
        <div class="logo-full">
            <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI" class="sidebar-logo">
        </div>
        
        <!-- Logo Icon Only (when collapsed) -->
        <div class="logo-icon">
            <img src="{{ asset('images/pelni_icon.png') }}" alt="PELNI" class="sidebar-icon">
        </div>
        
        <!-- Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Kelola ABK -->
            <li class="nav-item has-submenu {{ request()->routeIs('abk.*') ? 'active' : '' }}">
                <a href="#" class="nav-link" data-submenu="abk">
                    <div class="nav-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="nav-text">Kelola ABK</span>
                    <i class="bi bi-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu" id="submenu-abk">
                    <li><a href="{{ route('abk.index') }}" class="submenu-link {{ request()->routeIs('abk.index') ? 'active' : '' }}">Data ABK</a></li>
                    <li><a href="{{ route('abk.create') }}" class="submenu-link {{ request()->routeIs('abk.create') ? 'active' : '' }}">Tambah ABK</a></li>
                    <li><a href="{{ route('abk.export') }}" class="submenu-link {{ request()->routeIs('abk.export*') ? 'active' : '' }}">Export ABK</a></li>
                </ul>
            </li>

            <!-- Monitoring -->
            <li class="nav-item has-submenu {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                <a href="#" class="nav-link" data-submenu="monitoring">
                    <div class="nav-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <span class="nav-text">Monitoring</span>
                    <i class="bi bi-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu" id="submenu-monitoring">
                    <li><a href="{{ route('monitoring.index') }}" class="submenu-link {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">Dashboard Monitoring</a></li>
                    <li><a href="{{ route('monitoring.sertijab') }}" class="submenu-link {{ request()->routeIs('monitoring.sertijab*') ? 'active' : '' }}">Monitoring Sertijab</a></li>
                </ul>
            </li>

            <!-- Arsip Sertijab -->
            <li class="nav-item has-submenu {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
                <a href="#" class="nav-link" data-submenu="arsip">
                    <div class="nav-icon">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                    <span class="nav-text">Arsip Sertijab</span>
                    <i class="bi bi-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu" id="submenu-arsip">
                    <li><a href="{{ route('arsip.index') }}" class="submenu-link {{ request()->routeIs('arsip.index') ? 'active' : '' }}">Data Arsip</a></li>
                    <li><a href="{{ route('arsip.search') }}" class="submenu-link {{ request()->routeIs('arsip.search') ? 'active' : '' }}">Pencarian Arsip</a></li>
                    <li><a href="{{ route('arsip.create') }}" class="submenu-link {{ request()->routeIs('arsip.create') ? 'active' : '' }}">Tambah Arsip</a></li>
                    <li><a href="{{ route('arsip.laporan') }}" class="submenu-link {{ request()->routeIs('arsip.laporan*') ? 'active' : '' }}">Laporan Arsip</a></li>
                </ul>
            </li>

            <!-- Divider -->
            <li class="nav-divider">
                <span class="nav-text">Pengaturan</span>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <span class="nav-text">Pengaturan</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item">
                <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <span class="nav-text">Profil Admin</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="admin-details">
                <div class="admin-name">{{ auth()->guard('admin')->user()->nama_admin ?? 'Administrator' }}</div>
                <div class="admin-role">{{ auth()->guard('admin')->user()->jabatan ?? 'Admin' }}</div>
            </div>
        </div>
        
        <div class="logout-section">
            <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span class="nav-text">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
/* Sidebar Variables */
:root {
    --sidebar-width: 280px;
    --sidebar-width-collapsed: 70px;
    --primary-blue: #2A3F8E;
    --secondary-blue: #3b82f6;
    --light-blue: #8CB4F5;
    --sidebar-bg: #1e293b;
    --sidebar-hover: #334155;
    --text-light: #f1f5f9;
    --text-muted: #94a3b8;
    --border-color: #475569;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Sidebar Container */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
    border-right: 1px solid var(--border-color);
    transition: var(--transition);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
    width: var(--sidebar-width-collapsed);
}

/* Sidebar Header */
.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    position: relative;
}

.logo-full {
    opacity: 1;
    transition: var(--transition);
    display: flex;
    align-items: center;
}

.logo-icon {
    opacity: 0;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    transition: var(--transition);
    display: flex;
    align-items: center;
}

.sidebar.collapsed .logo-full {
    opacity: 0;
    pointer-events: none;
}

.sidebar.collapsed .logo-icon {
    opacity: 1;
    pointer-events: auto;
}

.sidebar-logo {
    height: 40px;
    width: auto;
    object-fit: contain;
}

.sidebar-icon {
    height: 35px;
    width: 35px;
    object-fit: contain;
    border-radius: 8px;
    background: white;
    padding: 5px;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: var(--text-light);
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
}

.sidebar-toggle:hover {
    background: var(--sidebar-hover);
    color: var(--light-blue);
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 2px;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin-bottom: 8px;
    padding: 0 15px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: var(--text-muted);
    text-decoration: none;
    border-radius: 10px;
    transition: var(--transition);
    position: relative;
    font-weight: 500;
}

.nav-link:hover {
    background: var(--sidebar-hover);
    color: var(--text-light);
    transform: translateX(2px);
}

.nav-link.active {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
}

.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 18px;
    flex-shrink: 0;
}

.nav-text {
    flex: 1;
    font-size: 14px;
    white-space: nowrap;
    opacity: 1;
    transition: var(--transition);
}

.sidebar.collapsed .nav-text {
    opacity: 0;
    pointer-events: none;
}

.submenu-arrow {
    font-size: 12px;
    transition: var(--transition);
    margin-left: auto;
}

.sidebar.collapsed .submenu-arrow {
    opacity: 0;
}

/* Submenu */
.has-submenu.active .submenu-arrow {
    transform: rotate(180deg);
}

.submenu {
    list-style: none;
    margin: 8px 0 0 0;
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: var(--transition);
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

.has-submenu.active .submenu {
    max-height: 200px;
    padding: 8px 0;
}

.submenu-link {
    display: block;
    padding: 8px 15px 8px 50px;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
    border-radius: 6px;
    margin: 2px 10px;
}

.submenu-link:hover {
    background: var(--sidebar-hover);
    color: var(--text-light);
    transform: translateX(3px);
}

/* Tambahan CSS untuk active state submenu */
.submenu-link.active {
    background: var(--primary-blue);
    color: white;
    font-weight: 600;
}

.has-submenu.active > .nav-link {
    background: var(--sidebar-hover);
    color: var(--text-light);
}

.sidebar.collapsed .submenu {
    display: none;
}

/* Divider */
.nav-divider {
    margin: 20px 15px 15px;
    padding: 8px 15px;
    border-top: 1px solid var(--border-color);
    position: relative;
}

.nav-divider .nav-text {
    color: var(--text-muted);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.sidebar.collapsed .nav-divider {
    border-top: 1px solid var(--border-color);
    margin: 20px 20px 15px;
}

.sidebar.collapsed .nav-divider .nav-text {
    display: none;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 20px 15px;
    border-top: 1px solid var(--border-color);
    margin-top: auto;
}

.admin-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    transition: var(--transition);
}

.admin-avatar {
    width: 35px;
    height: 35px;
    background: var(--primary-blue);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.admin-details {
    flex: 1;
    opacity: 1;
    transition: var(--transition);
}

.admin-name {
    color: var(--text-light);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.admin-role {
    color: var(--text-muted);
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar.collapsed .admin-details {
    opacity: 0;
    pointer-events: none;
}

.logout-btn {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #ef4444;
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition);
    font-weight: 500;
    font-size: 14px;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
    transform: translateX(2px);
}

.logout-btn i {
    margin-right: 10px;
    font-size: 16px;
}

.sidebar.collapsed .logout-btn .nav-text {
    opacity: 0;
}

/* Sidebar Overlay */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .sidebar.collapsed {
        width: var(--sidebar-width);
    }
}

/* Tooltip for collapsed sidebar */
.sidebar.collapsed .nav-link {
    position: relative;
}

.sidebar.collapsed .nav-link::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--sidebar-bg);
    color: var(--text-light);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
    z-index: 1001;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border-color);
}

.sidebar.collapsed .nav-link:hover::after {
    opacity: 1;
    visibility: visible;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const submenuItems = document.querySelectorAll('.has-submenu > .nav-link');

    // Auto-expand submenu if current route is within that submenu
    const activeSubmenuParent = document.querySelector('.has-submenu.active');
    if (activeSubmenuParent) {
        activeSubmenuParent.classList.add('active');
    }

    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
    });

    // Close sidebar on overlay click (mobile)
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-open');
        sidebarOverlay.classList.remove('active');
    });

    // Handle submenu toggles
    submenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (sidebar.classList.contains('collapsed') && window.innerWidth > 768) {
                return;
            }

            const parentItem = this.parentElement;
            const isActive = parentItem.classList.contains('active');

            // Close all other submenus
            document.querySelectorAll('.has-submenu.active').forEach(activeItem => {
                if (activeItem !== parentItem) {
                    activeItem.classList.remove('active');
                }
            });

            // Toggle current submenu
            parentItem.classList.toggle('active', !isActive);
        });
    });

    // Restore sidebar state from localStorage
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
        sidebar.classList.add('collapsed');
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        } else {
            sidebar.classList.remove('collapsed');
        }
    });

    // Add tooltips for collapsed sidebar
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const navText = link.querySelector('.nav-text');
        if (navText) {
            link.setAttribute('data-tooltip', navText.textContent.trim());
        }
    });

    // Close submenu when sidebar is collapsed
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if (sidebar.classList.contains('collapsed')) {
                    document.querySelectorAll('.has-submenu.active').forEach(item => {
                        item.classList.remove('active');
                    });
                }
            }
        });
    });

    observer.observe(sidebar, { attributes: true });
});
</script>