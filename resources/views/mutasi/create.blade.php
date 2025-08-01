@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="bi bi-house-door"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('mutasi.index') }}">Kelola Mutasi</a>
                                </li>
                                <li class="breadcrumb-item active">Tambah Mutasi</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="bi bi-arrow-left-right"></i>
                            Tambah Mutasi Baru
                        </h1>
                        <p class="page-subtitle">Formulir untuk menambahkan data mutasi ABK antar kapal</p>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Card -->
            <div class="coming-soon-container">
                <div class="coming-soon-card">
                    <div class="coming-soon-content">
                        <div class="coming-soon-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        
                        <h2 class="coming-soon-title">Halaman Tambah Mutasi</h2>
                        <p class="coming-soon-subtitle">Sedang dalam tahap pengembangan</p>
                        
                        <div class="coming-soon-description">
                            <p>Fitur tambah mutasi akan segera hadir dengan kemampuan:</p>
                            <ul class="feature-list">
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Form input data mutasi ABK
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Pemilihan kapal asal dan tujuan
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Validasi data mutasi
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Upload dokumen pendukung
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Approval workflow
                                </li>
                            </ul>
                        </div>
                        
                        <div class="coming-soon-actions">
                            <a href="{{ route('mutasi.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali ke Daftar Mutasi
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </div>
                        
                        <div class="coming-soon-meta">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Estimasi selesai: Q2 2025
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Variables */
:root {
    --primary-blue: #2563eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f8fafc;
    --border-radius: 12px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Header */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 12px;
    font-size: 14px;
}

.breadcrumb-item a {
    color: var(--text-muted);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.page-title i {
    color: var(--primary-blue);
}

.page-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 14px;
}

/* Coming Soon Container */
.coming-soon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 500px;
}

.coming-soon-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-medium);
    border: 1px solid var(--border-color);
    padding: 60px 40px;
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.coming-soon-content {
    max-width: 500px;
    margin: 0 auto;
}

.coming-soon-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 32px;
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
    }
}

.coming-soon-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.coming-soon-subtitle {
    font-size: 18px;
    color: var(--text-muted);
    margin-bottom: 32px;
}

.coming-soon-description {
    text-align: left;
    margin-bottom: 40px;
}

.coming-soon-description p {
    font-size: 16px;
    color: var(--text-dark);
    margin-bottom: 16px;
    font-weight: 600;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    display: flex;
    align-items: center;
    padding: 8px 0;
    font-size: 15px;
    color: var(--text-dark);
}

.coming-soon-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.coming-soon-meta {
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

/* Buttons */
.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
    color: white;
    text-decoration: none;
}

.btn-outline-secondary {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
}

.btn-outline-secondary:hover {
    background: var(--text-muted);
    color: white;
    border-color: var(--text-muted);
    text-decoration: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .coming-soon-card {
        padding: 40px 24px;
        margin: 0 16px;
    }
    
    .coming-soon-icon {
        width: 100px;
        height: 100px;
        font-size: 40px;
        margin-bottom: 24px;
    }
    
    .coming-soon-title {
        font-size: 28px;
    }
    
    .coming-soon-subtitle {
        font-size: 16px;
    }
    
    .coming-soon-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 24px;
    }
    
    .coming-soon-icon {
        width: 80px;
        height: 80px;
        font-size: 32px;
    }
    
    .coming-soon-title {
        font-size: 24px;
    }
    
    .feature-list li {
        font-size: 14px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interactive effects
    const comingSoonCard = document.querySelector('.coming-soon-card');
    
    // Add hover effect
    comingSoonCard.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
    });
    
    comingSoonCard.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    });
    
    // Log page visit for analytics
    console.log('Mutasi Create Page - Coming Soon');
});
</script>
@endpush