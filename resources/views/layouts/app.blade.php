<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'PELNI Sertijab System') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pelni_icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        /* Global App Variables */
        :root {
            --sidebar-width: 280px;
            --sidebar-width-collapsed: 70px;
            --navbar-height: 70px;
            --footer-height: auto;
            --primary-blue: #2A3F8E;
            --secondary-blue: #3b82f6;
            --light-blue: #8CB4F5;
            --accent-blue: #1d4ed8;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --background: #f8fafc;
            --surface: #ffffff;
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
        }
        
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Body */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* App Wrapper */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - var(--navbar-height));
            transition: var(--transition);
            position: relative;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-width-collapsed);
        }
        
        /* Content Container */
        .content-container {
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            width: 100%;
        }
        
        .content-wrapper {
            background: var(--surface);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            min-height: 600px;
            overflow: hidden;
            position: relative;
        }
        
        /* Content Header */
        .content-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--surface) 0%, #f8fafc 100%);
            position: relative;
        }
        
        .content-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }
        
        .content-title {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            line-height: 1.2;
        }
        
        .content-subtitle {
            font-size: 16px;
            color: var(--text-muted);
            font-weight: 500;
            margin: 0;
        }
        
        /* Content Body */
        .content-body {
            padding: 32px;
            flex: 1;
            min-height: 400px;
        }
        
        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
            transition: var(--transition);
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        
        /* Page Transitions */
        .page-enter {
            animation: pageEnter 0.5s ease-out;
        }
        
        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Utility Classes */
        .text-primary { color: var(--primary-blue) !important; }
        .text-secondary { color: var(--secondary-blue) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--error-color) !important; }
        .text-muted { color: var(--text-muted) !important; }
        
        .bg-primary { background-color: var(--primary-blue) !important; }
        .bg-secondary { background-color: var(--secondary-blue) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .bg-danger { background-color: var(--error-color) !important; }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .main-content.sidebar-collapsed {
                margin-left: 0;
            }
            
            .content-container {
                padding: 16px;
            }
            
            .content-wrapper {
                min-height: calc(100vh - var(--navbar-height) - 32px);
            }
            
            .content-header {
                padding: 20px 24px;
            }
            
            .content-title {
                font-size: 24px;
            }
            
            .content-subtitle {
                font-size: 14px;
            }
            
            .content-body {
                padding: 24px;
            }
        }
        
        @media (max-width: 480px) {
            .content-container {
                padding: 12px;
            }
            
            .content-header {
                padding: 16px 20px;
            }
            
            .content-title {
                font-size: 20px;
            }
            
            .content-body {
                padding: 20px;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar,
            .navbar,
            .app-footer,
            .sidebar-overlay {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                margin-top: 0 !important;
            }
            
            .content-wrapper {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
    
    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>
        
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Navbar -->
        @include('layouts.navbar')
        
        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <div class="content-container">
                <div class="content-wrapper page-enter">
                    <!-- Content Header (Optional) -->
                    @hasSection('header')
                        <div class="content-header">
                            <h1 class="content-title">@yield('page-title', 'Dashboard')</h1>
                            <p class="content-subtitle">@yield('page-description', 'Sistem Manajemen Sertijab PELNI')</p>
                        </div>
                    @endif
                    
                    <!-- Content Body -->
                    <div class="content-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        @include('layouts.footer')
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <!-- Toast notifications will be inserted here dynamically -->
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main App Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Adjust main content based on sidebar state
            function adjustMainContent() {
                if (sidebar && sidebar.classList.contains('collapsed')) {
                    mainContent.classList.add('sidebar-collapsed');
                } else {
                    mainContent.classList.remove('sidebar-collapsed');
                }
            }
            
            // Listen for sidebar changes
            if (sidebar) {
                const observer = new MutationObserver(adjustMainContent);
                observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
                adjustMainContent(); // Initial check
            }
            
            // Handle window resize for mobile
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    mainContent.classList.remove('sidebar-collapsed');
                } else {
                    adjustMainContent();
                }
            });
            
            // Loading state management
            window.showLoading = function() {
                loadingOverlay.classList.add('active');
            };
            
            window.hideLoading = function() {
                loadingOverlay.classList.remove('active');
            };
            
            // Auto-hide loading after page load
            window.addEventListener('load', function() {
                setTimeout(hideLoading, 300);
            });
            
            // Toast notification function
            window.showToast = function(message, type = 'info', duration = 5000) {
                const toastContainer = document.querySelector('.toast-container');
                const toastId = 'toast-' + Date.now();
                
                const toastHtml = `
                    <div class="toast" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="${duration}">
                        <div class="toast-header bg-${type} text-white">
                            <i class="bi bi-${getToastIcon(type)} me-2"></i>
                            <strong class="me-auto">${getToastTitle(type)}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                
                // Remove toast element after it's hidden
                toastElement.addEventListener('hidden.bs.toast', function() {
                    toastElement.remove();
                });
            };
            
            function getToastIcon(type) {
                const icons = {
                    'success': 'check-circle-fill',
                    'danger': 'exclamation-triangle-fill',
                    'warning': 'exclamation-circle-fill',
                    'info': 'info-circle-fill',
                    'primary': 'info-circle-fill'
                };
                return icons[type] || 'info-circle-fill';
            }
            
            function getToastTitle(type) {
                const titles = {
                    'success': 'Berhasil',
                    'danger': 'Error',
                    'warning': 'Peringatan',
                    'info': 'Informasi',
                    'primary': 'Informasi'
                };
                return titles[type] || 'Informasi';
            }
            
            // CSRF Token for AJAX
            window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Setup AJAX headers
            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                });
            }
            
            // Handle navigation loading states
            const navLinks = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"]):not([target="_blank"])');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Don't show loading for same page links or external links
                    if (this.href === window.location.href || 
                        this.hostname !== window.location.hostname ||
                        this.hasAttribute('data-no-loading')) {
                        return;
                    }
                    showLoading();
                });
            });
            
            // Handle browser back/forward button
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    hideLoading();
                }
            });
            
            // Error handling for failed resource loads
            window.addEventListener('error', function(e) {
                if (e.target.tagName === 'IMG') {
                    // Handle broken images
                    e.target.src = '/images/placeholder.png';
                }
            });
            
            // Page transition effects
            const contentWrapper = document.querySelector('.content-wrapper');
            if (contentWrapper) {
                contentWrapper.classList.add('fade-in');
            }
        });
        
        // Global error handler
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            hideLoading();
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + S for sidebar toggle
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                const sidebarToggle = document.getElementById('sidebarToggle');
                if (sidebarToggle) {
                    sidebarToggle.click();
                }
            }
            
            // Esc key to close dropdowns
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown.show, .show').forEach(element => {
                    element.classList.remove('show');
                });
            }
        });
    </script>
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>