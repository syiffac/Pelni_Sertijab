{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\layouts\sidebar.blade.php --}}
<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-full">
                <!-- Logo PELNI menggunakan file gambar -->
                <div class="pelni-logo-full">
                    <img src="{{ asset('images/pelni_icon.png') }}" 
                         alt="PELNI Logo" 
                         class="pelni-img-full"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <!-- Fallback jika gambar tidak ada -->
                    <div class="pelni-fallback" style="display: none;">
                        <i class="bi bi-ship" style="font-size: 32px; color: #2A3F8E;"></i>
                    </div>
                </div>
                
                <div class="logo-text">
                    <span class="brand-name">SertijabPELNI</span>
                    <small class="brand-subtitle">Management System</small>
                </div>
            </div>
            
            <div class="logo-mini">
                <!-- Logo PELNI Mini -->
                <div class="pelni-logo-mini">
                    <img src="{{ asset('images/pelni_icon.png') }}" 
                         alt="PELNI Logo" 
                         class="pelni-img-mini"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <!-- Fallback jika gambar tidak ada -->
                    <div class="pelni-fallback-mini" style="display: none;">
                        <i class="bi bi-ship" style="font-size: 24px; color: #2A3F8E;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Toggle Button - DIPERBAIKI POSITIONING -->
        <button class="sidebar-toggle" id="sidebarToggle" type="button">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   data-tooltip="Dashboard">
                    <div class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Kelola ABK - DIPERBAIKI MENGGUNAKAN ROUTE HELPER -->
            <li class="nav-item has-submenu {{ request()->routeIs('abk.*') ? 'active' : '' }}">
                <a href="#" class="nav-link" data-submenu="abk" data-tooltip="Kelola ABK">
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
                <a href="#" class="nav-link" data-submenu="monitoring" data-tooltip="Monitoring">
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
                <a href="#" class="nav-link" data-submenu="arsip" data-tooltip="Arsip Sertijab">
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

            <!-- Data Kapal - JUGA DIPERBAIKI MENGGUNAKAN ROUTE HELPER -->
            <li class="nav-item has-submenu {{ request()->routeIs('kapal.*') ? 'active' : '' }}">
                <a href="#" class="nav-link" data-submenu="kapal" data-tooltip="Data Kapal">
                    <div class="nav-icon">
                        <i class="bi bi-ship-fill"></i>
                    </div>
                    <span class="nav-text">Data Kapal</span>
                    <i class="bi bi-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu" id="submenu-kapal">
                    <li><a href="{{ route('kapal.index') }}" class="submenu-link {{ request()->routeIs('kapal.index') ? 'active' : '' }}">Data Kapal</a></li>
                    <li><a href="{{ route('kapal.create') }}" class="submenu-link {{ request()->routeIs('kapal.create') ? 'active' : '' }}">Tambah Kapal</a></li>
                </ul>
            </li>

            <!-- Divider -->
            <li class="nav-divider">
                <span class="divider-line"></span>
                <span class="divider-text">Pengaturan</span>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" data-tooltip="Pengaturan">
                    <div class="nav-icon">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <span class="nav-text">Pengaturan</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item">
                <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-tooltip="Profil Admin">
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
                <div class="admin-name">Administrator</div>
                <div class="admin-role">System Admin</div>
            </div>
        </div>
        
        <div class="logout-section">
            <a href="#" class="logout-btn" data-tooltip="Logout">
                <i class="bi bi-box-arrow-right"></i>
                <span class="nav-text">Logout</span>
            </a>
        </div>
    </div>
</div>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- CSS dan JavaScript tetap sama seperti sebelumnya --}}
<style>
/* Keep all existing CSS styles unchanged */
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 70px;
    --sidebar-bg: #E3EEFF;
    --sidebar-hover: #D1DEFD;
    --sidebar-active: #2A3F8E;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --border-color: #cbd5e1;
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Sidebar Container */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    border-right: 2px solid var(--border-color);
    transition: var(--transition);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

/* Sidebar Header - DIPERBAIKI LAYOUT */
.sidebar-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    background: linear-gradient(135deg, #E3EEFF 0%, #D1DEFD 100%);
    position: relative;
}

.sidebar.collapsed .sidebar-header {
    justify-content: center;
    padding: 16px 5px; /* Reduced padding */
    flex-direction: column;
    gap: 8px;
}

/* Logo Section - DIPERBAIKI */
.logo-section {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
    overflow: hidden;
    min-width: 0;
}

.sidebar.collapsed .logo-section {
    flex: none;
    width: 100%;
    justify-content: center;
    margin-bottom: 4px;
}

/* Logo Full - DENGAN PELNI FILE */
.logo-full {
    display: flex;
    align-items: center;
    gap: 12px;
    opacity: 1;
    transition: var(--transition);
    width: 100%;
    min-width: 0;
}

.pelni-logo-full {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--transition);
    width: 40px;
    height: 40px;
}

.pelni-img-full {
    width: 40px;
    height: auto;
    max-height: 40px;
    object-fit: contain;
    display: block;
    transition: var(--transition);
}

.pelni-logo-full:hover .pelni-img-full {
    transform: scale(1.05);
    filter: drop-shadow(0 2px 8px rgba(42, 63, 142, 0.3));
}

.pelni-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    transition: var(--transition);
}

/* Logo Mini - DENGAN PELNI FILE */
.logo-mini {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    pointer-events: none;
}

.pelni-logo-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    width: 32px;
    height: 32px;
}

.pelni-img-mini {
    width: 32px;
    height: auto;
    max-height: 32px;
    object-fit: contain;
    display: block;
    transition: var(--transition);
}

.pelni-logo-mini:hover .pelni-img-mini {
    transform: scale(1.1);
    filter: drop-shadow(0 2px 8px rgba(42, 63, 142, 0.3));
}

.pelni-fallback-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    transition: var(--transition);
}

/* Sidebar State Changes - DIPERBAIKI */
.sidebar.collapsed .logo-full {
    opacity: 0;
    pointer-events: none;
    transform: scale(0.8);
    position: absolute;
}

.sidebar.collapsed .logo-mini {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
    position: relative;
    left: auto;
    top: auto;
    transform: none;
}

/* Logo Text */
.logo-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
    overflow: hidden;
}

.brand-name {
    font-size: 18px;
    font-weight: 800;
    color: var(--text-primary);
    font-family: 'Poppins', sans-serif;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brand-subtitle {
    font-size: 11px;
    color: var(--text-secondary);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Toggle Button - DIPERBAIKI POSITIONING UNTUK TIDAK OVERLAP */
.sidebar-toggle {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    position: relative;
    z-index: 1002;
}

/* DIPERBAIKI: Positioning saat collapsed */
.sidebar.collapsed .sidebar-toggle {
    position: relative; /* Changed from absolute */
    right: auto;
    top: auto;
    transform: none;
    width: 32px;
    height: 32px;
    font-size: 16px;
    margin-top: 4px; /* Space from logo */
}

.sidebar-toggle:hover {
    background: var(--sidebar-hover);
    border-color: var(--sidebar-active);
    color: var(--sidebar-active);
    transform: scale(1.05);
}

.sidebar.collapsed .sidebar-toggle:hover {
    transform: scale(1.1);
}

/* Navigation - Keep existing styles */
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
    margin-bottom: 4px;
    padding: 0 16px;
}

.sidebar.collapsed .nav-item {
    padding: 0 10px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 10px;
    transition: var(--transition);
    position: relative;
    font-weight: 500;
    font-size: 14px;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 12px 8px;
}

.nav-link:hover {
    background: var(--sidebar-hover);
    color: var(--text-primary);
    transform: translateX(2px);
}

.sidebar.collapsed .nav-link:hover {
    transform: none;
}

.nav-link.active {
    background: var(--sidebar-active);
    color: white;
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
}

/* Nav Icon */
.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 16px;
    flex-shrink: 0;
}

.sidebar.collapsed .nav-icon {
    margin-right: 0;
    width: 24px;
    height: 24px;
    font-size: 18px;
}

.nav-text {
    flex: 1;
    white-space: nowrap;
    opacity: 1;
    transition: var(--transition);
    min-width: 0;
}

.sidebar.collapsed .nav-text {
    opacity: 0;
    pointer-events: none;
    width: 0;
    overflow: hidden;
}

.submenu-arrow {
    font-size: 12px;
    transition: var(--transition);
    margin-left: auto;
    flex-shrink: 0;
}

.sidebar.collapsed .submenu-arrow {
    opacity: 0;
    width: 0;
    overflow: hidden;
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
    background: rgba(42, 63, 142, 0.05);
    border-radius: 8px;
}

.has-submenu.active .submenu {
    max-height: 300px;
    padding: 8px 0;
}

.submenu-link {
    display: block;
    padding: 8px 16px 8px 48px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
    border-radius: 6px;
    margin: 2px 12px;
}

.submenu-link:hover {
    background: var(--sidebar-hover);
    color: var(--text-primary);
    transform: translateX(4px);
}

.submenu-link.active {
    background: var(--sidebar-active);
    color: white;
    font-weight: 600;
}

.sidebar.collapsed .submenu {
    display: none;
}

/* Divider */
.nav-divider {
    margin: 24px 16px 16px;
    padding: 12px 0;
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar.collapsed .nav-divider {
    margin: 24px 10px 16px;
    justify-content: center;
}

.divider-line {
    flex: 1;
    height: 1px;
    background: var(--border-color);
}

.sidebar.collapsed .divider-line {
    width: 30px;
    flex: none;
}

.divider-text {
    color: var(--text-muted);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    padding: 0 8px;
    background: var(--sidebar-bg);
    white-space: nowrap;
    opacity: 1;
    transition: var(--transition);
}

.sidebar.collapsed .divider-text {
    opacity: 0;
    width: 0;
    padding: 0;
    overflow: hidden;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--border-color);
    background: linear-gradient(135deg, #E3EEFF 0%, #D1DEFD 100%);
}

.sidebar.collapsed .sidebar-footer {
    padding: 16px 10px;
}

.admin-info {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 10px;
    transition: var(--transition);
}

.sidebar.collapsed .admin-info {
    justify-content: center;
    padding: 12px 8px;
}

.admin-avatar {
    width: 36px;
    height: 36px;
    background: var(--sidebar-active);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.sidebar.collapsed .admin-avatar {
    margin-right: 0;
}

.admin-details {
    flex: 1;
    opacity: 1;
    transition: var(--transition);
    min-width: 0;
}

.admin-name {
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.admin-role {
    color: var(--text-secondary);
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar.collapsed .admin-details {
    opacity: 0;
    pointer-events: none;
    width: 0;
    overflow: hidden;
}

.logout-btn {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    color: #dc2626;
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition);
    font-weight: 500;
    font-size: 14px;
    border: 1px solid rgba(220, 38, 38, 0.2);
}

.sidebar.collapsed .logout-btn {
    justify-content: center;
    padding: 10px 8px;
}

.logout-btn:hover {
    background: rgba(220, 38, 38, 0.1);
    color: #b91c1c;
    transform: translateX(2px);
    border-color: rgba(220, 38, 38, 0.3);
}

.sidebar.collapsed .logout-btn:hover {
    transform: none;
}

.logout-btn i {
    margin-right: 10px;
    font-size: 16px;
    flex-shrink: 0;
}

.sidebar.collapsed .logout-btn i {
    margin-right: 0;
}

.sidebar.collapsed .logout-btn .nav-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* Tooltip untuk collapsed sidebar */
.sidebar.collapsed .nav-link[data-tooltip]:hover::after,
.sidebar.collapsed .logout-btn[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: calc(100% + 15px);
    top: 50%;
    transform: translateY(-50%);
    background: var(--text-primary);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1001;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    opacity: 1;
    animation: tooltipFadeIn 0.2s ease;
    pointer-events: none;
}

.sidebar.collapsed .nav-link[data-tooltip]:hover::before,
.sidebar.collapsed .logout-btn[data-tooltip]:hover::before {
    content: '';
    position: absolute;
    left: calc(100% + 9px);
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-right-color: var(--text-primary);
    z-index: 1001;
}

@keyframes tooltipFadeIn {
    from {
        opacity: 0;
        transform: translateY(-50%) translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }
}

/* Mobile Overlay */
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
        transform: translateX(-100%);
    }
    
    .sidebar.collapsed.mobile-open {
        transform: translateX(0);
        width: var(--sidebar-width);
    }
    
    /* Reset collapsed styles on mobile */
    .sidebar.collapsed .sidebar-header {
        flex-direction: row;
        justify-content: space-between;
        padding: 16px 20px;
    }
    
    .sidebar.collapsed .logo-section {
        flex: 1;
        justify-content: flex-start;
        margin-bottom: 0;
    }
    
    .sidebar.collapsed .logo-full {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1);
        position: relative;
    }
    
    .sidebar.collapsed .logo-mini {
        opacity: 0;
        pointer-events: none;
    }
    
    .sidebar.collapsed .sidebar-toggle {
        position: relative;
        margin-top: 0;
    }
    
    .sidebar.collapsed .nav-text {
        opacity: 1;
        width: auto;
    }
    
    .sidebar.collapsed .admin-details {
        opacity: 1;
        width: auto;
    }
    
    .sidebar.collapsed .nav-link {
        justify-content: flex-start;
        padding: 12px 16px;
    }
    
    .sidebar.collapsed .nav-icon {
        margin-right: 12px;
    }
    
    .sidebar.collapsed .submenu-arrow {
        opacity: 1;
        width: auto;
    }
    
    .sidebar.collapsed .divider-text {
        opacity: 1;
        width: auto;
        padding: 0 8px;
    }
}

/* Logo Animations */
.pelni-logo-full {
    animation: logoSlideIn 0.6s ease-out;
}

.pelni-logo-mini {
    animation: logoFadeIn 0.4s ease-out;
}

@keyframes logoSlideIn {
    from {
        opacity: 0;
        transform: translateX(-20px) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

@keyframes logoFadeIn {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Smooth Animations */
.nav-item {
    opacity: 0;
    animation: slideInLeft 0.3s ease forwards;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.15s; }
.nav-item:nth-child(3) { animation-delay: 0.2s; }
.nav-item:nth-child(4) { animation-delay: 0.25s; }
.nav-item:nth-child(5) { animation-delay: 0.3s; }
.nav-item:nth-child(6) { animation-delay: 0.35s; }
.nav-item:nth-child(7) { animation-delay: 0.4s; }
.nav-item:nth-child(8) { animation-delay: 0.45s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

{{-- Keep all existing JavaScript unchanged --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const submenuItems = document.querySelectorAll('.has-submenu > .nav-link');

    // Pastikan toggle button selalu bisa diklik
    if (sidebarToggle) {
        sidebarToggle.style.pointerEvents = 'auto';
        sidebarToggle.style.zIndex = '1002';
    }

    // Check localStorage for sidebar state
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed && window.innerWidth > 768) {
        sidebar.classList.add('collapsed');
    }

    // Auto-expand active submenu
    const activeSubmenuParent = document.querySelector('.has-submenu.active');
    if (activeSubmenuParent && !sidebar.classList.contains('collapsed')) {
        activeSubmenuParent.classList.add('active');
    }

    // Toggle sidebar function
    function toggleSidebar(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (window.innerWidth <= 768) {
            // Mobile behavior
            const isOpen = sidebar.classList.contains('mobile-open');
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active', !isOpen);
        } else {
            // Desktop behavior
            const isCollapsed = sidebar.classList.contains('collapsed');
            
            if (isCollapsed) {
                sidebar.classList.remove('collapsed');
                // Re-expand active submenu if any
                const activeSubmenu = document.querySelector('.has-submenu.active');
                if (activeSubmenu) {
                    activeSubmenu.classList.add('active');
                }
            } else {
                sidebar.classList.add('collapsed');
                // Close all submenus when collapsing
                document.querySelectorAll('.has-submenu.active').forEach(item => {
                    item.classList.remove('active');
                });
            }
            
            // Save state
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
    }

    // Function to auto-collapse sidebar after submenu selection
    function autoCollapseSidebar() {
        if (window.innerWidth > 768) {
            // Desktop: Collapse sidebar and close all submenus
            sidebar.classList.add('collapsed');
            document.querySelectorAll('.has-submenu.active').forEach(item => {
                item.classList.remove('active');
            });
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            // Mobile: Close sidebar
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        }
    }

    // Event listener untuk toggle
    if (sidebarToggle) {
        sidebarToggle.removeEventListener('click', toggleSidebar);
        sidebarToggle.addEventListener('click', toggleSidebar, true);
        
        sidebarToggle.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });
    }

    // Close sidebar on overlay click (mobile)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });
    }

    // Handle submenu toggles
    submenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Don't toggle submenus when sidebar is collapsed on desktop
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

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
            
            // Restore collapsed state from localStorage
            const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            sidebar.classList.toggle('collapsed', wasCollapsed);
        } else {
            // On mobile, remove collapsed class
            sidebar.classList.remove('collapsed');
        }
    });

    // ================== AUTO-COLLAPSE AFTER SUBMENU SELECTION ==================
    
    // Handle submenu link clicks (AUTO-COLLAPSE FEATURE)
    document.querySelectorAll('.submenu-link').forEach(submenuLink => {
        submenuLink.addEventListener('click', function(e) {
            // Get the href to check if it's a valid navigation
            const href = this.getAttribute('href');
            
            // Only auto-collapse if it's a valid navigation (not # or javascript:void(0))
            if (href && href !== '#' && href !== 'javascript:void(0)' && !href.startsWith('javascript:')) {
                // Small delay to ensure the navigation starts before collapsing
                setTimeout(() => {
                    autoCollapseSidebar();
                }, 150); // 150ms delay for smooth transition
            }
        });
    });

    // Handle direct nav-link clicks (non-submenu items) with auto-collapse
    document.querySelectorAll('.nav-link:not([data-submenu])').forEach(link => {
        link.addEventListener('click', function() {
            const href = this.getAttribute('href');
            
            // Check if it's a valid navigation
            if (href && href !== '#' && href !== 'javascript:void(0)' && !href.startsWith('javascript:')) {
                // Auto-collapse for mobile and desktop
                if (window.innerWidth <= 768) {
                    // Mobile: Close sidebar immediately
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                } else {
                    // Desktop: Collapse with delay
                    setTimeout(() => {
                        sidebar.classList.add('collapsed');
                        localStorage.setItem('sidebarCollapsed', 'true');
                    }, 150);
                }
            }
        });
    });

    // ================== ENHANCED AUTO-EXPAND ON PAGE LOAD ==================
    
    // Enhanced: Auto-expand submenu if current page matches submenu item
    function autoExpandActiveSubmenu() {
        const currentPath = window.location.pathname;
        
        // Find submenu link that matches current path
        document.querySelectorAll('.submenu-link').forEach(submenuLink => {
            const linkPath = new URL(submenuLink.href, window.location.origin).pathname;
            
            if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                const parentSubmenu = submenuLink.closest('.has-submenu');
                if (parentSubmenu && !sidebar.classList.contains('collapsed')) {
                    parentSubmenu.classList.add('active');
                }
            }
        });
    }

    // Call on page load
    autoExpandActiveSubmenu();

    // ================== SMART BEHAVIOR ENHANCEMENTS ==================
    
    // Smart behavior: If user manually expands sidebar, don't auto-collapse for a short period
    let userManuallyExpanded = false;
    let manualExpandTimeout;

    // Track manual sidebar expansion
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (sidebar.classList.contains('collapsed')) {
                userManuallyExpanded = true;
                
                // Reset the flag after 5 seconds
                clearTimeout(manualExpandTimeout);
                manualExpandTimeout = setTimeout(() => {
                    userManuallyExpanded = false;
                }, 5000);
            }
        });
    }

    // Enhanced auto-collapse function with smart behavior
    function smartAutoCollapse() {
        // Don't auto-collapse if user just manually expanded
        if (userManuallyExpanded) {
            return;
        }

        autoCollapseSidebar();
    }

    // Update submenu link event listeners to use smart auto-collapse
    document.querySelectorAll('.submenu-link').forEach(submenuLink => {
        // Remove previous listener and add smart one
        submenuLink.removeEventListener('click', autoCollapseSidebar);
        
        submenuLink.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href && href !== '#' && href !== 'javascript:void(0)' && !href.startsWith('javascript:')) {
                setTimeout(() => {
                    smartAutoCollapse();
                }, 150);
            }
        });
    });

    // ================== HOVER BEHAVIOR FOR COLLAPSED SIDEBAR ==================
    
    let hoverTimeout;
    
    // Show submenu on hover when collapsed (Desktop only)
    document.querySelectorAll('.has-submenu').forEach(submenuParent => {
        submenuParent.addEventListener('mouseenter', function() {
            if (sidebar.classList.contains('collapsed') && window.innerWidth > 768) {
                clearTimeout(hoverTimeout);
                
                // Create floating submenu
                createFloatingSubmenu(this);
            }
        });

        submenuParent.addEventListener('mouseleave', function() {
            if (sidebar.classList.contains('collapsed') && window.innerWidth > 768) {
                hoverTimeout = setTimeout(() => {
                    removeFloatingSubmenu();
                }, 300);
            }
        });
    });

    function createFloatingSubmenu(parentElement) {
        // Remove existing floating submenu
        removeFloatingSubmenu();
        
        const submenu = parentElement.querySelector('.submenu');
        if (!submenu) return;

        const floatingSubmenu = submenu.cloneNode(true);
        floatingSubmenu.classList.add('floating-submenu');
        
        // Position floating submenu
        const rect = parentElement.getBoundingClientRect();
        floatingSubmenu.style.position = 'fixed';
        floatingSubmenu.style.left = rect.right + 10 + 'px';
        floatingSubmenu.style.top = rect.top + 'px';
        floatingSubmenu.style.zIndex = '1050';
        floatingSubmenu.style.background = 'white';
        floatingSubmenu.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        floatingSubmenu.style.borderRadius = '8px';
        floatingSubmenu.style.border = '1px solid var(--border-color)';
        floatingSubmenu.style.minWidth = '200px';
        floatingSubmenu.style.maxHeight = '300px';
        floatingSubmenu.style.padding = '8px 0';
        floatingSubmenu.style.display = 'block';
        floatingSubmenu.style.opacity = '0';
        floatingSubmenu.style.transform = 'translateY(-10px)';
        floatingSubmenu.style.transition = 'all 0.2s ease';

        // Add click handlers to floating submenu links
        floatingSubmenu.querySelectorAll('.submenu-link').forEach(link => {
            link.addEventListener('click', function() {
                removeFloatingSubmenu();
                smartAutoCollapse();
            });
        });

        document.body.appendChild(floatingSubmenu);
        
        // Animate in
        setTimeout(() => {
            floatingSubmenu.style.opacity = '1';
            floatingSubmenu.style.transform = 'translateY(0)';
        }, 10);

        // Handle hover on floating submenu
        floatingSubmenu.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
        });

        floatingSubmenu.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => {
                removeFloatingSubmenu();
            }, 300);
        });
    }

    function removeFloatingSubmenu() {
        const existingFloating = document.querySelector('.floating-submenu');
        if (existingFloating) {
            existingFloating.style.opacity = '0';
            existingFloating.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (existingFloating.parentNode) {
                    existingFloating.parentNode.removeChild(existingFloating);
                }
            }, 200);
        }
    }

    // Prevent sidebar close when clicking inside sidebar
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            sidebar.classList.contains('mobile-open') && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        }
        
        // Also remove floating submenu when clicking outside
        if (!sidebar.contains(e.target)) {
            removeFloatingSubmenu();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + S: Toggle sidebar
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            toggleSidebar(e);
        }
        
        // Escape: Close floating submenu or mobile sidebar
        if (e.key === 'Escape') {
            removeFloatingSubmenu();
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
            }
        }
    });

    // ================== PAGE VISIBILITY CHANGE HANDLER ==================
    
    // Auto-collapse when user switches tabs/windows (optional)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // User switched away from tab - optionally auto-collapse
            // Uncomment below if you want this behavior:
            // setTimeout(() => {
            //     if (!userManuallyExpanded) {
            //         autoCollapseSidebar();
            //     }
            // }, 2000); // 2 second delay
        }
    });

    console.log('🎯 Enhanced Sidebar with Auto-Collapse initialized successfully!');
});
</script>