<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - PELNI Sertijab System</title>
    
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
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Wave Background - Top waves */
        body::before {
            content: '';
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, rgba(140, 180, 245, 0.4) 0%, rgba(42, 63, 142, 0.3) 100%);
            clip-path: polygon(0 0, 100% 0, 100% 65%, 80% 75%, 60% 55%, 40% 85%, 20% 65%, 0 75%);
            z-index: 1;
            animation: waveFloat 2s ease-in-out infinite;
        }
        
        /* Bottom waves */
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
            animation: waveFloat 3s ease-in-out infinite reverse;
        }
        
        @keyframes waveFloat {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        
        .login-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: slideUp 0.8s ease-out;
            min-height: 500px;
            position: relative;
            z-index: 10;
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
        
        /* Dividing Line */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1px;
            height: 100%;
            background: linear-gradient(
                to bottom,
                transparent 0%,
                rgba(42, 63, 142, 0.1) 20%,
                rgba(42, 63, 142, 0.2) 50%,
                rgba(42, 63, 142, 0.1) 80%,
                transparent 100%
            );
            z-index: 5;
        }
        
        /* Left Side - Login Form */
        .login-left {
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            min-height: 500px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            position: relative;
            z-index: 15;
        }
        
        /* Right Side - Image */
        .login-right {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            min-height: 500px;
            position: relative;
            overflow: hidden;
            z-index: 15;
        }
        
        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 20%, rgba(42, 63, 142, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(59, 130, 246, 0.04) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .pelni-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
            animation: fadeInUp 0.8s ease-out 0.1s both;
            position: relative;
            z-index: 20;
        }
        
        .pelni-logo img {
            width: 150px;
            height: auto;
            object-fit: contain;
        }
        
        .welcome-title {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 12px;
            text-align: center;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease-out 0.2s both;
            position: relative;
            z-index: 20;
        }
        
        .welcome-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 30px;
            text-align: center;
            line-height: 1.5;
            animation: fadeInUp 0.8s ease-out 0.3s both;
            position: relative;
            z-index: 20;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-group {
            margin-bottom: 18px;
            animation: fadeInUp 0.8s ease-out 0.4s both;
            position: relative;
            z-index: 20;
        }
        
        .form-control {
            height: 45px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            position: relative;
            z-index: 20;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(42, 63, 142, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: var(--error-color);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
            z-index: 25;
        }
        
        .password-toggle:hover {
            color: var(--primary-blue);
            background: rgba(42, 63, 142, 0.1);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeInUp 0.8s ease-out 0.5s both;
            position: relative;
            z-index: 20;
        }
        
        .form-check {
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            width: 16px;
            height: 16px;
            border: 2px solid var(--border-color);
            border-radius: 3px;
            margin-right: 6px;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .form-check-label {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 13px;
        }
        
        .forgot-password {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        
        .forgot-password:hover {
            color: var(--secondary-blue);
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(42, 63, 142, 0.3);
            animation: fadeInUp 0.8s ease-out 0.6s both;
            z-index: 20;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(42, 63, 142, 0.4);
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--light-blue) 100%);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 63, 142, 0.3);
        }
        
        .btn-login:disabled {
            opacity: 0.8;
            transform: none;
            cursor: not-allowed;
        }
        
        .login-illustration {
            width: 100%;
            max-width: 350px;
            height: auto;
            object-fit: contain;
            animation: fadeInUp 0.8s ease-out 0.4s both;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.1));
            position: relative;
            z-index: 20;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: fadeInUp 0.8s ease-out 0.3s both;
            position: relative;
            z-index: 20;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
            animation: shake 0.5s ease-in-out;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-left: 4px solid #059669;
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border-left: 4px solid #d97706;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 280px;
            border: none;
            border-radius: 10px;
            box-shadow: var(--shadow-medium);
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast-header {
            background: var(--error-color);
            color: white;
            border-radius: 10px 10px 0 0;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
        }
        
        .toast-body {
            padding: 12px 15px;
            font-weight: 500;
            font-size: 13px;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-card::before {
                display: none;
            }
            
            .login-left {
                padding: 35px 30px;
                min-height: auto;
            }
            
            .login-right {
                padding: 30px 25px;
                min-height: 350px;
            }
            
            .welcome-title {
                font-size: 26px;
            }
            
            .welcome-subtitle {
                font-size: 13px;
            }
            
            .pelni-logo img {
                width: 130px;
            }
            
            .login-illustration {
                max-width: 280px;
            }
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
                max-width: 500px;
            }
            
            .login-left {
                padding: 30px 25px;
            }
            
            .login-right {
                padding: 25px 20px;
                min-height: 250px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
            
            .pelni-logo img {
                width: 120px;
            }
            
            .login-illustration {
                max-width: 220px;
            }
            
            .form-control {
                height: 42px;
                font-size: 13px;
            }
            
            .btn-login {
                height: 42px;
                font-size: 13px;
            }
            
            /* Hide waves on mobile for better performance */
            body::before,
            body::after {
                display: none;
            }
            
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
            }
            
            .toast {
                min-width: auto;
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
            
            .login-card {
                min-height: 450px;
            }
            
            .login-left {
                min-height: 450px;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Container for Notifications -->
    <div class="toast-container">
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="toast-header">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong class="me-auto">Login Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Username atau password tidak valid!
            </div>
        </div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="row g-0 h-100">
                <!-- Left Side - Login Form -->
                <div class="col-lg-6">
                    <div class="login-left">
                        <!-- PELNI Logo -->
                        <div class="pelni-logo">
                            <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI Logo" class="img-fluid">
                        </div>
                        
                        <!-- Welcome Text -->
                        <h1 class="welcome-title">Welcome back, Admin!</h1>
                        <p class="welcome-subtitle">Log in to manage the Sertijab Document Management System.</p>
                        
                        <!-- Success/Status Messages -->
                        @if(session('status'))
                            <div class="alert alert-success" id="statusAlert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger" id="errorAlert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <span id="errorText">{{ $errors->first() }}</span>
                            </div>
                        @endif
                        
                        <!-- Login Form - PERBAIKAN UTAMA -->
                        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                            @csrf
                            <!-- Username Field -->
                            <div class="form-group">
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Username"
                                       value="{{ old('username') }}"
                                       required>
                            </div>
                            
                            <!-- Password Field -->
                            <div class="form-group">
                                <div class="password-field">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Password"
                                           required>
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Form Options -->
                            <div class="form-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                                <a href="#" class="forgot-password">Forgot Password?</a>
                            </div>
                            
                            <!-- Login Button -->
                            <button type="submit" class="btn btn-login" id="loginBtn">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Right Side - Login Illustration -->
                <div class="col-lg-6">
                    <div class="login-right">
                        <img src="{{ asset('images/login.png') }}" alt="Login Illustration" class="login-illustration img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye';
            }
        }
        
        function showToast(message, type = 'error') {
            const toast = document.getElementById('errorToast');
            const toastMessage = document.getElementById('toastMessage');
            const toastHeader = toast.querySelector('.toast-header');
            
            // Update message
            toastMessage.textContent = message;
            
            // Update header based on type
            if (type === 'error') {
                toastHeader.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong class="me-auto">Login Error</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>';
                toastHeader.style.background = '#ef4444';
            } else if (type === 'warning') {
                toastHeader.innerHTML = '<i class="bi bi-exclamation-circle-fill me-2"></i><strong class="me-auto">Warning</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>';
                toastHeader.style.background = '#f59e0b';
            } else if (type === 'success') {
                toastHeader.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i><strong class="me-auto">Success</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>';
                toastHeader.style.background = '#10b981';
            }
            
            // Show toast with animation
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 5000
            });
            
            // Force show animation
            toast.classList.add('show');
            bsToast.show();
        }
        
        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const formInputs = document.querySelectorAll('.form-control');
            
            // Debug CSRF
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Check for error messages and show toast
            @if ($errors->any())
                const errorMessage = "{{ addslashes($errors->first()) }}";
                setTimeout(() => {
                    showToast(errorMessage, 'error');
                }, 100);
                
                // Add error shake animation to form
                setTimeout(() => {
                    document.querySelector('.login-left').style.animation = 'shake 0.5s ease-in-out';
                }, 200);
            @endif
            
            // Form submission loading state
            form.addEventListener('submit', function(e) {
                // Update CSRF token sebelum submit
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = form.querySelector('input[name="_token"]');
                if (csrfInput) {
                    csrfInput.value = csrfToken;
                }
                
                loginBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Logging in...';
                loginBtn.disabled = true;
                loginBtn.style.opacity = '0.8';
            });
            
            // Input focus effects
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-1px)';
                    this.style.boxShadow = '0 4px 12px rgba(42, 63, 142, 0.15)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.02)';
                });
            });
            
            // Remove error styling when user starts typing
            formInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
