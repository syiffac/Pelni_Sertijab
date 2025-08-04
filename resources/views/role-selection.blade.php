{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\role-selection.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'PELNI Sertijab System') }} - Pilih Role</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pelni_icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #2A3F8E;
            --secondary-blue: #3b82f6;
            --light-blue: #8CB4F5;
            --accent-blue: #1d4ed8;
            --success-green: #10b981;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Background waves */
        body::before {
            content: '';
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, rgba(42, 63, 142, 0.4) 0%, rgba(140, 180, 245, 0.3) 100%);
            clip-path: polygon(0 0, 100% 0, 100% 65%, 80% 75%, 60% 55%, 40% 85%, 20% 65%, 0 75%);
            z-index: 1;
            animation: waveFloat 3s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(140, 180, 245, 0.4) 100%);
            clip-path: polygon(0 100%, 100% 100%, 100% 40%, 75% 50%, 50% 30%, 25% 45%, 0 35%);
            z-index: 1;
            animation: waveFloat 4s ease-in-out infinite reverse;
        }

        @keyframes waveFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .role-selection-container {
            width: 100%;
            max-width: 1000px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e293b 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='3'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 20s linear infinite;
        }

        .pelni-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .pelni-logo img {
            width: 50px;
            height: auto;
        }

        .pelni-logo i {
            font-size: 40px;
            color: var(--primary-blue);
        }

        .header-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
            position: relative;
            z-index: 2;
        }

        .roles-section {
            padding: 50px 40px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .section-title p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .role-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .role-card:hover::before {
            left: 100%;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .role-card.admin:hover {
            border-color: var(--primary-blue);
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        }

        .role-card.puk:hover {
            border-color: var(--success-green);
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .role-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 35px;
            color: white;
            position: relative;
            z-index: 2;
        }

        .role-icon.admin {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e293b 100%);
            box-shadow: 0 10px 30px rgba(42, 63, 142, 0.3);
        }

        .role-icon.puk {
            background: linear-gradient(135deg, var(--success-green) 0%, #047857 100%);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        .role-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .role-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .role-features {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .role-features li {
            padding: 8px 0;
            color: #4a5568;
            font-size: 0.9rem;
            position: relative;
            padding-left: 25px;
        }

        .role-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
        }

        .footer-section {
            background: #f7fafc;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .version-info {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% { transform: translateX(0); }
            100% { transform: translateX(-60px); }
        }

        .role-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .role-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        /* Loading animation */
        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .loading .role-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Alert styling - PERBAIKI INI */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            font-size: 14px;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-left: 4px solid #059669;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
            border-left: 4px solid #2563eb;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            opacity: 0.5;
            margin-left: auto;
            padding-left: 10px;
            cursor: pointer;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* Animation untuk alert */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Auto dismiss alert animation */
        .alert.fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .role-selection-container {
                padding: 0 15px;
            }

            .header-section,
            .roles-section {
                padding: 30px 25px;
            }

            .header-title {
                font-size: 1.6rem;
            }

            .header-subtitle {
                font-size: 1rem;
            }

            .role-card {
                padding: 30px 20px;
                margin-bottom: 20px;
            }

            .section-title h3 {
                font-size: 1.5rem;
            }

            body::before,
            body::after {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .section-title h3 {
                font-size: 1.3rem;
            }

            .role-title {
                font-size: 1.3rem;
            }

            .role-card {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="role-selection-container">
        <div class="main-card">
            <!-- Header Section -->
            <div class="header-section">
                <div class="pelni-logo">
                    @if(file_exists(public_path('images/pelni_logo.png')))
                        <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI Logo">
                    @else
                        <i class="bi bi-ship"></i>
                    @endif
                </div>
                <h1 class="header-title">PELNI Sertijab System</h1>
                <p class="header-subtitle">Sistem Manajemen Serah Terima Jabatan - PT PELNI</p>
            </div>

            <!-- Roles Selection Section -->
            <div class="roles-section">
                <div class="section-title">
                    <h3>Pilih Role Anda</h3>
                    <p>Silakan pilih peran Anda untuk mengakses sistem yang sesuai</p>
                </div>

                <!-- PERBAIKI BAGIAN ALERT INI -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert" id="infoAlert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="roleSelectionForm" action="{{ route('role.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" id="selectedRole">
                    
                    <div class="row g-4">
                        <!-- Admin Role -->
                        <div class="col-md-6">
                            <div class="role-card admin" data-role="admin">
                                <div class="role-icon admin">
                                    <i class="bi bi-gear-fill"></i>
                                </div>
                                <h4 class="role-title">Admin Pengawakan</h4>
                                <p class="role-description">
                                    Akses penuh untuk mengelola sistem, data ABK, kapal, monitoring, dan laporan
                                </p>
                                <ul class="role-features">
                                    <li>Kelola data ABK dan mutasi</li>
                                    <li>Kelola data kapal dan jabatan</li>
                                    <li>Monitoring dokumen sertijab</li>
                                    <li>Verifikasi dokumen</li>
                                    <li>Generate laporan dan arsip</li>
                                </ul>
                            </div>
                        </div>

                        <!-- PUK Role -->
                        <div class="col-md-6">
                            <div class="role-card puk" data-role="puk">
                                <div class="role-icon puk">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                </div>
                                <h4 class="role-title">Penata Usaha Kapal (PUK)</h4>
                                <p class="role-description">
                                    Website resmi PT PELNI untuk submit dan mengelola dokumen serah terima jabatan
                                </p>
                                <ul class="role-features">
                                    <li>Submit dokumen sertijab</li>
                                    <li>Upload file dokumen</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Section -->
            <div class="footer-section">
                <p class="footer-text">
                    <i class="bi bi-shield-check me-2"></i>
                    Sistem aman dan terpercaya untuk manajemen dokumen sertijab PT PELNI
                </p>
                <div class="version-info">
                    <small>Version 1.0.0 | © {{ date('Y') }} PT PELNI (Persero)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleCards = document.querySelectorAll('.role-card');
            const form = document.getElementById('roleSelectionForm');
            const selectedRoleInput = document.getElementById('selectedRole');

            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    const role = this.getAttribute('data-role');
                    
                    // Add loading state
                    this.classList.add('loading');
                    
                    // Set selected role
                    selectedRoleInput.value = role;
                    
                    // Add slight delay for better UX
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                });

                // Enhanced hover effects
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('loading')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });

            // Keyboard navigation support
            document.addEventListener('keydown', function(e) {
                if (e.key === '1' || e.key === 'a' || e.key === 'A') {
                    roleCards[0].click(); // Admin
                } else if (e.key === '2' || e.key === 'p' || e.key === 'P') {
                    roleCards[1].click(); // PUK
                }
            });

            // Add interaction feedback
            roleCards.forEach(card => {
                card.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-8px) scale(0.98)';
                });
                
                card.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });
            });
        });
        {{-- Di bagian <script> sebelum penutup </body> --}}

    document.addEventListener('DOMContentLoaded', function() {
        // Existing role card functionality...
        const roleCards = document.querySelectorAll('.role-card');
        const form = document.getElementById('roleSelectionForm');
        const selectedRoleInput = document.getElementById('selectedRole');

        // ... existing role card event listeners ...

        // AUTO-DISMISS ALERT FUNCTIONALITY
        const alerts = document.querySelectorAll('.alert');
        
        alerts.forEach(alert => {
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            }, 5000);

            // Manual close button
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                });
            }
        });

        // Enhanced success alert for logout
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            // Add special styling for logout success
            successAlert.style.boxShadow = '0 4px 20px rgba(16, 185, 129, 0.3)';
            successAlert.style.border = '1px solid rgba(16, 185, 129, 0.3)';
        }
    });
    </script>
</body>
</html>