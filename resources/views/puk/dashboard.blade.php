{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\puk\dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Portal Unggah Dokumen Sertijab - PELNI</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pelni_icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-blue: #1e40af;
            --pelni-blue: #1d4ed8;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --text-light: #9ca3af;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 25%, #81d4fa 50%, #4fc3f7 75%, #29b6f6 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background decorative elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(29, 78, 216, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(37, 99, 235, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        /* Floating shapes - DIPERKECIL */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 60px; /* Diperkecil dari 100px */
            height: 60px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 40px; /* Diperkecil dari 60px */
            height: 40px;
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-shape:nth-child(3) {
            width: 50px; /* Diperkecil dari 80px */
            height: 50px;
            bottom: 20%;
            left: 5%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(120deg); } /* Diperkecil movement */
            66% { transform: translateY(-8px) rotate(240deg); }
        }

        /* Main container - DIPERKECIL */
        .main-container {
            position: relative;
            z-index: 10;
            min-height: 130vh;
            display: flex;
            align-items: center;
            padding: 20px; /* Diperkecil dari 20px */
        }

        /* Header Section - DIPERKECIL */
        .header-section {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(37, 99, 235, 0.1);
            padding: 10px 0; /* Diperkecil dari 15px */
            z-index: 20;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Container untuk back button dan logo - DIPERKECIL */
        .left-section {
            display: flex;
            align-items: center;
            gap: 15px; /* Diperkecil dari 20px */
        }

        /* Back button di navbar - DIPERKECIL */
        .back-button {
            background: transparent;
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: 6px; /* Diperkecil dari 8px */
            padding: 6px 10px; /* Diperkecil dari 8px 12px */
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem; /* Diperkecil dari 0.9rem */
            display: flex;
            align-items: center;
            gap: 5px; /* Diperkecil dari 6px */
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateX(-2px);
            border-color: var(--primary-blue);
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        /* Logo diperkecil */
        .logo-pelni {
            width: 80px; /* Diperkecil dari 100px */
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .logo-pelni:hover {
            transform: scale(1.05);
        }

        .logo-pelni img {
            width: 100%;
            height: 40px; /* Diperkecil dari 50px */
            max-width: 120px; /* Diperkecil dari 150px */
            object-fit: contain;
        }

        .logo-pelni .logo-fallback {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem; /* Diperkecil dari 1.8rem */
            font-weight: 800;
            color: var(--primary-blue);
        }

        .logo-text {
            display: none;
        }

        .header-nav {
            display: flex;
            gap: 25px; /* Diperkecil dari 30px */
            align-items: center;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem; /* Diperkecil */
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-blue);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-blue);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Main content - DIPERKECIL */
        .content-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 80px auto 0; /* Diperkecil dari 120px */
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px; /* Diperkecil dari 60px */
            align-items: center;
        }

        .text-section {
            padding-left: 20px;
        }

        .welcome-text {
            font-size: 1rem; /* Diperkecil dari 1.1rem */
            color: var(--text-muted);
            margin-bottom: 12px; /* Diperkecil dari 15px */
            font-weight: 500;
        }

        .main-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.8rem; /* Diperkecil dari 3.5rem */
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.1;
            margin-bottom: 15px; /* Diperkecil dari 20px */
        }

        .main-title .highlight {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1rem; /* Diperkecil dari 1.2rem */
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 30px; /* Diperkecil dari 40px */
            max-width: 450px; /* Diperkecil dari 500px */
        }

        .cta-buttons {
            display: flex;
            gap: 15px; /* Diperkecil dari 20px */
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border: none;
            padding: 12px 28px; /* Diperkecil dari 15px 35px */
            border-radius: 10px; /* Diperkecil dari 12px */
            color: white;
            font-weight: 600;
            font-size: 1rem; /* Diperkecil dari 1.1rem */
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px; /* Diperkecil dari 10px */
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--primary-blue);
            padding: 10px 28px; /* Diperkecil dari 13px 35px */
            border-radius: 10px; /* Diperkecil dari 12px */
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 1rem; /* Diperkecil dari 1.1rem */
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px; /* Diperkecil dari 10px */
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* Visual section - DIPERKECIL */
        .visual-section {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-right: 20px;
        }

        .ship-container {
            position: relative;
            width: 100%;
            max-width: 700px; /* Diperkecil dari 500px */
            height: 320px; /* Diperkecil dari 400px */
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(29, 78, 216, 0.2) 100%);
            border-radius: 20px; /* Diperkecil dari 30px */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ship-icon {
            font-size: 8rem; /* Diperkecil dari 12rem */
            color: rgba(37, 99, 235, 0.7);
            animation: shipFloat 4s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes shipFloat {
            0%, 100% { transform: translateY(0px) rotate(-2deg); }
            50% { transform: translateY(-10px) rotate(2deg); } /* Diperkecil movement */
        }

        /* Ocean waves - DIPERKECIL */
        .ocean-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 80px; /* Diperkecil dari 100px */
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(37, 99, 235, 0.4) 100%);
            border-radius: 0 0 25px 25px; /* Sesuaikan dengan container */
        }

        .ocean-wave::before {
            content: '';
            position: absolute;
            top: -15px; /* Diperkecil dari -20px */
            left: 0;
            width: 100%;
            height: 30px; /* Diperkecil dari 40px */
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z' opacity='.25' fill='%232563eb'/%3E%3Cpath d='M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z' opacity='.5' fill='%232563eb'/%3E%3Cpath d='M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z' fill='%232563eb'/%3E%3C/svg%3E") repeat-x;
            background-size: 1200px 30px; /* Sesuaikan dengan height */
            animation: waveMove 3s linear infinite;
        }

        @keyframes waveMove {
            0% { background-position-x: 0; }
            100% { background-position-x: 1200px; }
        }

        /* Statistics cards - DIPERKECIL */
        .stats-section {
            grid-column: 1 / -1;
            margin-top: 60px; /* Diperkecil dari 80px */
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Diperkecil dari 250px */
            gap: 25px; /* Diperkecil dari 30px */
            margin-top: 30px; /* Diperkecil dari 40px */
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px; /* Diperkecil dari 20px */
            padding: 25px; /* Diperkecil dari 30px */
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px; /* Diperkecil dari 60px */
            height: 50px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border-radius: 12px; /* Diperkecil dari 15px */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px; /* Diperkecil margin */
            color: white;
            font-size: 20px; /* Diperkecil dari 24px */
        }

        .stat-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem; /* Diperkecil dari 1.2rem */
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px; /* Diperkecil dari 10px */
        }

        .stat-description {
            color: var(--text-muted);
            font-size: 0.9rem; /* Diperkecil dari 0.95rem */
            line-height: 1.5;
        }

        /* Loading screen - DIPERKECIL */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .loading-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            animation: pulseScale 2s ease-in-out infinite;
        }

        .loading-logo img {
            width: 100%;
            height: auto;
            max-width: 60px;
            filter: brightness(0) invert(1); /* Membuat logo menjadi putih */
        }

        .loading-logo svg {
            width: 60px;
            height: auto;
        }

        .loading-subtext {
            font-size: 0.9rem; /* Diperkecil dari 1rem */
            opacity: 0.8;
            animation: fadeInOut 2s ease-in-out infinite 0.5s;
        }

        @keyframes pulseScale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        /* Page entrance animations */
        .page-content {
            opacity: 0;
            transform: translateY(20px); /* Diperkecil dari 30px */
            transition: opacity 1s ease, transform 1s ease;
        }

        .page-content.loaded {
            opacity: 1;
            transform: translateY(0);
        }

        /* Enhanced entrance animations */
        .animate-on-load {
            opacity: 0;
            transform: translateY(30px); /* Diperkecil dari 50px */
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .animate-on-load.show {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-slide-left {
            opacity: 0;
            transform: translateX(-30px); /* Diperkecil dari -50px */
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .animate-slide-left.show {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-slide-right {
            opacity: 0;
            transform: translateX(30px); /* Diperkecil dari 50px */
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .animate-slide-right.show {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-scale {
            opacity: 0;
            transform: scale(0.9); /* Sedikit diperbesar dari 0.8 */
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .animate-scale.show {
            opacity: 1;
            transform: scale(1);
        }

        /* Stagger animation delays */
        .animate-delay-1 { transition-delay: 0.1s; }
        .animate-delay-2 { transition-delay: 0.2s; }
        .animate-delay-3 { transition-delay: 0.3s; }
        .animate-delay-4 { transition-delay: 0.4s; }
        .animate-delay-5 { transition-delay: 0.5s; }

        /* Enhanced floating shapes animation - DIPERKECIL */
        .floating-shape.enhanced {
            animation: floatEnhanced 8s ease-in-out infinite;
        }

        @keyframes floatEnhanced {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
                opacity: 0.6;
            }
            25% { 
                transform: translateY(-20px) rotate(90deg) scale(1.1); /* Diperkecil movement */
                opacity: 0.8;
            }
            50% { 
                transform: translateY(-10px) rotate(180deg) scale(0.9); 
                opacity: 1;
            }
            75% { 
                transform: translateY(-15px) rotate(270deg) scale(1.05); /* Diperkecil movement */
                opacity: 0.7;
            }
        }

        /* Text reveal animation */
        .text-reveal {
            overflow: hidden;
        }

        .text-reveal span {
            display: block;
            transform: translateY(100%);
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .text-reveal.show span {
            transform: translateY(0);
        }

        /* Button hover enhancements */
        .btn-primary-custom {
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        /* Ship animation enhancement - DIPERKECIL */
        .ship-icon.enhanced {
            animation: shipFloatEnhanced 6s ease-in-out infinite;
        }

        @keyframes shipFloatEnhanced {
            0%, 100% { 
                transform: translateY(0px) rotate(-2deg) scale(1); 
            }
            25% { 
                transform: translateY(-15px) rotate(1deg) scale(1.02); /* Diperkecil movement */
            }
            50% { 
                transform: translateY(-10px) rotate(2deg) scale(1.05); 
            }
            75% { 
                transform: translateY(-8px) rotate(-1deg) scale(1.02); /* Diperkecil movement */
            }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
                margin-top: 60px;
            }

            .main-title {
                font-size: 2.2rem; /* Responsive sizing */
            }

            .ship-container {
                max-width: 300px;
                height: 250px;
            }

            .ship-icon {
                font-size: 6rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .left-section {
                gap: 10px;
            }

            .back-button {
                padding: 5px 8px;
                font-size: 0.7rem;
            }

            .back-button span {
                display: none; /* Hanya icon di mobile */
            }

            .logo-pelni {
                width: 60px;
            }

            .logo-pelni img {
                height: 30px;
                max-width: 90px;
            }

            .header-nav {
                gap: 15px;
            }

            .nav-link {
                font-size: 0.8rem;
            }
        }

        /* Parallax effect for background elements */
        .parallax-bg {
            transform: translateZ(0);
            transition: transform 0.1s ease-out;
        }
    </style>
</head>
<body>
    <!-- Loading Screen dengan logo PELNI yang lebih detail -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="loading-logo">
                @if(file_exists(public_path('images/pelni_logo.png')))
                    <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI">
                @else
                    <!-- Fallback dengan huruf P yang stylish -->
                    <div class="logo-fallback">P</div>
                @endif
            </div>
            <div class="loading-subtext">Memuat Portal Unggah Dokumen Sertijab...</div>
        </div>
    </div>

    <!-- Page Content dengan wrapper untuk animasi -->
    <div class="page-content" id="pageContent">
        <!-- Floating shapes dengan class enhanced -->
        <div class="floating-shape enhanced"></div>
        <div class="floating-shape enhanced"></div>
        <div class="floating-shape enhanced"></div>

        <!-- Header dengan animasi -->
        <div class="header-section animate-on-load">
            <div class="header-content">
                <!-- LEFT SECTION: Back Button + Logo -->
                <div class="left-section">
                    <a href="{{ route('role.selection') }}" class="back-button animate-scale">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <div class="logo-section animate-slide-left">
                        <div class="logo-pelni">
                            @if(file_exists(public_path('images/pelni_logo.png')))
                                <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI">
                            @else
                                <!-- Fallback menggunakan huruf P dengan styling yang clean -->
                                <div class="logo-fallback">P</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SECTION: Navigation -->
                <nav class="header-nav animate-slide-right">
                    <a href="#mulai" class="nav-link">Mulai Unggah</a>
                    <a href="#tutorial" class="nav-link">Tutorial Unggah</a>
                    <a href="#hubungi" class="nav-link">Hubungi Kami</a>
                </nav>
            </div>
        </div>

        <!-- Main container -->
        <div class="main-container">
            <div class="content-wrapper">
                <!-- Text section dengan animasi reveal -->
                <div class="text-section animate-slide-left animate-delay-2">
                    <p class="welcome-text animate-on-load animate-delay-3">Selamat Datang di,</p>
                    <h1 class="main-title text-reveal animate-delay-4">
                        <span>Portal Unggah Dokumen</span>
                        <span class="highlight">Sertijab Pegawai Laut</span>
                    </h1>
                    <p class="subtitle animate-on-load animate-delay-5">
                        Portal ini dirancang khusus untuk Penata Usaha Kapal (PUK) dalam mempermudah proses 
                        pengiriman dokumen serah terima jabatan (sertijab) awak kapal.
                    </p>
                    <div class="cta-buttons animate-scale animate-delay-6">
                        <a href="{{ route('puk.upload.form') }}" class="btn-primary-custom" id="mulai">
                            <i class="bi bi-upload"></i>
                            Mulai Unggah
                        </a>
                        <a href="#tutorial" class="btn-secondary-custom">
                            <i class="bi bi-play-circle"></i>
                            Tutorial Unggah
                        </a>
                    </div>
                </div>

                <!-- Visual section dengan animasi -->
                <div class="visual-section animate-slide-right animate-delay-3">
                    <div class="ship-container animate-scale animate-delay-4">
                        <i class="bi bi-ship ship-icon enhanced"></i>
                        <div class="ocean-wave"></div>
                    </div>
                </div>

                <!-- Statistics section dengan stagger animation -->
                <div class="stats-section animate-on-load animate-delay-5">
                    <div class="stats-grid">
                        <div class="stat-card animate-on-load animate-delay-1">
                            <div class="stat-icon">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <h3 class="stat-title">Upload Mudah</h3>
                            <p class="stat-description">
                                Interface yang user-friendly untuk mengunggah dokumen sertijab dengan mudah dan cepat.
                            </p>
                        </div>

                        <div class="stat-card animate-on-load animate-delay-2">
                            <div class="stat-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="stat-title">Aman & Terpercaya</h3>
                            <p class="stat-description">
                                Sistem keamanan berlapis untuk melindungi data dan dokumen penting Anda.
                            </p>
                        </div>

                        <div class="stat-card animate-on-load animate-delay-3">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h3 class="stat-title">Tracking Real-time</h3>
                            <p class="stat-description">
                                Pantau status verifikasi dokumen secara real-time dengan notifikasi otomatis.
                            </p>
                        </div>

                        <div class="stat-card animate-on-load animate-delay-4">
                            <div class="stat-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h3 class="stat-title">Support 24/7</h3>
                            <p class="stat-description">
                                Tim support siap membantu Anda kapan saja jika mengalami kendala.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Enhanced JavaScript dengan animasi reload -->
    <script>
        // Page Load Animation Controller
        class PageAnimationController {
            constructor() {
                this.loadingScreen = document.getElementById('loadingScreen');
                this.pageContent = document.getElementById('pageContent');
                this.animationElements = document.querySelectorAll('.animate-on-load, .animate-slide-left, .animate-slide-right, .animate-scale, .text-reveal');
                this.isFirstLoad = sessionStorage.getItem('pelni_visited') !== 'true';
                
                this.init();
            }

            init() {
                // Show loading screen on first visit or force reload
                if (this.isFirstLoad || performance.navigation.type === 1) {
                    this.showLoadingScreen();
                } else {
                    this.hideLoadingScreen();
                    this.startPageAnimations();
                }

                // Mark as visited
                sessionStorage.setItem('pelni_visited', 'true');
            }

            showLoadingScreen() {
                this.loadingScreen.style.display = 'flex';
                this.pageContent.style.display = 'none';

                // Simulate loading time
                setTimeout(() => {
                    this.hideLoadingScreen();
                    this.startPageAnimations();
                }, this.isFirstLoad ? 2000 : 1000);
            }

            hideLoadingScreen() {
                this.loadingScreen.classList.add('hidden');
                this.pageContent.style.display = 'block';
                
                setTimeout(() => {
                    this.loadingScreen.style.display = 'none';
                    this.pageContent.classList.add('loaded');
                }, 500);
            }

            startPageAnimations() {
                // Trigger entrance animations
                setTimeout(() => {
                    this.animationElements.forEach((element, index) => {
                        setTimeout(() => {
                            element.classList.add('show');
                        }, index * 100);
                    });
                }, 200);

                // Initialize other animations
                this.initParallaxEffect();
                this.initEnhancedInteractions();
                this.initSmoothScrolling();
                this.initAdvancedAnimations();
            }

            initParallaxEffect() {
                let ticking = false;

                function updateParallax() {
                    const scrolled = window.pageYOffset;
                    const rate = scrolled * -0.5;
                    
                    document.querySelectorAll('.floating-shape').forEach((shape, index) => {
                        const multiplier = (index + 1) * 0.3;
                        shape.style.transform = `translateY(${rate * multiplier}px)`;
                    });

                    ticking = false;
                }

                function requestTick() {
                    if (!ticking) {
                        requestAnimationFrame(updateParallax);
                        ticking = true;
                    }
                }

                window.addEventListener('scroll', requestTick);
            }

            initEnhancedInteractions() {
                // Enhanced button hover effects
                document.querySelectorAll('.btn-primary-custom, .btn-secondary-custom').forEach(btn => {
                    btn.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-3px) scale(1.02)';
                    });

                    btn.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) scale(1)';
                    });

                    // Enhanced click effect
                    btn.addEventListener('click', function(e) {
                        const ripple = document.createElement('span');
                        const rect = this.getBoundingClientRect();
                        const size = Math.max(rect.width, rect.height);
                        const x = e.clientX - rect.left - size / 2;
                        const y = e.clientY - rect.top - size / 2;
                        
                        ripple.style.cssText = `
                            width: ${size}px;
                            height: ${size}px;
                            left: ${x}px;
                            top: ${y}px;
                            position: absolute;
                            border-radius: 50%;
                            background: rgba(255, 255, 255, 0.6);
                            transform: scale(0);
                            animation: rippleEffect 0.6s linear;
                            pointer-events: none;
                            z-index: 1;
                        `;
                        
                        this.style.position = 'relative';
                        this.style.overflow = 'hidden';
                        this.appendChild(ripple);
                        
                        setTimeout(() => ripple.remove(), 600);
                    });
                });

                // Stat cards hover effects
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-10px) scale(1.02)';
                        this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) scale(1)';
                        this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
                    });
                });
            }

            initSmoothScrolling() {
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            const targetPosition = target.offsetTop - 100;
                            
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

            initAdvancedAnimations() {
                // Mouse trail effect
                this.initMouseTrail();
                
                // Intersection Observer for scroll animations
                this.initScrollAnimations();
                
                // Random floating shapes movement
                this.initRandomFloating();
            }

            initMouseTrail() {
                const trail = [];
                const trailLength = 10;

                function createTrailElement() {
                    const element = document.createElement('div');
                    element.style.cssText = `
                        position: fixed;
                        width: 4px;
                        height: 4px;
                        background: rgba(37, 99, 235, 0.3);
                        border-radius: 50%;
                        pointer-events: none;
                        z-index: 9998;
                        transition: all 0.3s ease;
                    `;
                    document.body.appendChild(element);
                    return element;
                }

                for (let i = 0; i < trailLength; i++) {
                    trail.push(createTrailElement());
                }

                document.addEventListener('mousemove', (e) => {
                    trail.forEach((element, index) => {
                        setTimeout(() => {
                            element.style.left = e.clientX + 'px';
                            element.style.top = e.clientY + 'px';
                            element.style.opacity = (trailLength - index) / trailLength * 0.5;
                            element.style.transform = `scale(${(trailLength - index) / trailLength})`;
                        }, index * 50);
                    });
                });
            }

            initScrollAnimations() {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0) scale(1)';
                            
                            // Add extra effects for stat cards
                            if (entry.target.classList.contains('stat-card')) {
                                setTimeout(() => {
                                    entry.target.querySelector('.stat-icon').style.transform = 'scale(1.1)';
                                    setTimeout(() => {
                                        entry.target.querySelector('.stat-icon').style.transform = 'scale(1)';
                                    }, 200);
                                }, 300);
                            }
                        }
                    });
                }, observerOptions);

                // Observe elements that aren't already animated
                document.querySelectorAll('.stat-card:not(.show)').forEach(card => {
                    observer.observe(card);
                });
            }

            initRandomFloating() {
                document.querySelectorAll('.floating-shape').forEach(shape => {
                    const randomDelay = Math.random() * 3;
                    const randomDuration = 6 + Math.random() * 4;
                    
                    shape.style.animationDelay = randomDelay + 's';
                    shape.style.animationDuration = randomDuration + 's';
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize animation controller
            new PageAnimationController();

            // Add CSS for ripple effect
            const style = document.createElement('style');
            style.textContent = `
                @keyframes rippleEffect {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible again, add subtle re-entry animation
                document.querySelectorAll('.stat-card, .ship-icon').forEach(element => {
                    element.style.animation = 'none';
                    element.offsetHeight; // Trigger reflow
                    element.style.animation = null;
                });
            }
        });

        // Performance optimization
        let rafId;
        function optimizedScroll() {
            if (rafId) return;
            rafId = requestAnimationFrame(() => {
                // Scroll-based animations here
                rafId = null;
            });
        }
        window.addEventListener('scroll', optimizedScroll, { passive: true });
    </script>
</body>
</html>