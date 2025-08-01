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
                                <li class="breadcrumb-item active">Edit Mutasi</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="bi bi-pencil-square"></i>
                            Edit Data Mutasi
                        </h1>
                        <p class="page-subtitle">Formulir untuk mengedit data mutasi ABK yang sudah ada</p>
                    </div>
                    <div class="header-actions">
                        <span class="status-badge pending">
                            <i class="bi bi-clock me-1"></i>
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Card -->
            <div class="coming-soon-container">
                <div class="coming-soon-card">
                    <div class="coming-soon-content">
                        <div class="coming-soon-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        
                        <h2 class="coming-soon-title">Halaman Edit Mutasi</h2>
                        <p class="coming-soon-subtitle">Fitur sedang dalam tahap pengembangan</p>
                        
                        <div class="coming-soon-description">
                            <p>Fitur edit mutasi akan memungkinkan Anda untuk:</p>
                            <ul class="feature-list">
                                <li>
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                    Mengedit data mutasi yang sudah ada
                                </li>
                                <li>
                                    <i class="bi bi-person-check text-info me-2"></i>
                                    Mengubah data ABK yang terlibat
                                </li>
                                <li>
                                    <i class="bi bi-ship text-primary me-2"></i>
                                    Memperbarui kapal asal dan tujuan
                                </li>
                                <li>
                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                    Mengatur ulang tanggal mutasi
                                </li>
                                <li>
                                    <i class="bi bi-file-earmark-text text-secondary me-2"></i>
                                    Memperbarui dokumen pendukung
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Validasi data yang diubah
                                </li>
                            </ul>
                        </div>
                        
                        <div class="current-data-preview">
                            <h5 class="preview-title">
                                <i class="bi bi-eye me-2"></i>
                                Preview Data Mutasi
                            </h5>
                            <div class="preview-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="preview-item">
                                            <span class="preview-label">ID Mutasi:</span>
                                            <span class="preview-value">MUT-2025-001</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">ABK:</span>
                                            <span class="preview-value">John Doe (NRP: 12345)</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Jabatan:</span>
                                            <span class="preview-value">Mualim I</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview-item">
                                            <span class="preview-label">Kapal Asal:</span>
                                            <span class="preview-value">KM BINAIYA</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Kapal Tujuan:</span>
                                            <span class="preview-value">KM BUKIT RAYA</span>
                                        </div>
                                        <div class="preview-item">
                                            <span class="preview-label">Tanggal Mutasi:</span>
                                            <span class="preview-value">15 Maret 2025</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="coming-soon-actions">
                            <a href="{{ route('mutasi.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali ke Daftar Mutasi
                            </a>
                            <a href="{{ route('mutasi.show', 1) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye me-2"></i>
                                Lihat Detail
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </div>
                        
                        <div class="coming-soon-meta">
                            <div class="meta-item">
                                <i class="bi bi-clock me-1"></i>
                                <small class="text-muted">Estimasi selesai: Q2 2025</small>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-code-slash me-1"></i>
                                <small class="text-muted">Status: Under Development</small>
                            </div>
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
    --info-color: #06b6d4;
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

.header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
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
    color: var(--warning-color);
}

.page-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 14px;
}

/* Status Badge */
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

/* Coming Soon Container */
.coming-soon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 600px;
}

.coming-soon-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-medium);
    border: 1px solid var(--border-color);
    padding: 50px 40px;
    text-align: center;
    max-width: 700px;
    width: 100%;
}

.coming-soon-content {
    max-width: 600px;
    margin: 0 auto;
}

.coming-soon-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 28px;
    background: linear-gradient(135deg, var(--warning-color), #fbbf24);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    animation: editPulse 2s infinite;
}

@keyframes editPulse {
    0% {
        transform: scale(1) rotate(0deg);
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
    }
    50% {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
    }
    100% {
        transform: scale(1) rotate(0deg);
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

.coming-soon-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.coming-soon-subtitle {
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 28px;
}

.coming-soon-description {
    text-align: left;
    margin-bottom: 32px;
}

.coming-soon-description p {
    font-size: 15px;
    color: var(--text-dark);
    margin-bottom: 16px;
    font-weight: 600;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 8px;
}

.feature-list li {
    display: flex;
    align-items: center;
    padding: 6px 0;
    font-size: 14px;
    color: var(--text-dark);
}

/* Current Data Preview */
.current-data-preview {
    margin: 32px 0;
    text-align: left;
}

.preview-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.preview-item:last-child {
    border-bottom: none;
}

.preview-label {
    font-weight: 600;
    color: var(--text-muted);
    font-size: 14px;
}

.preview-value {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 14px;
}

.coming-soon-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.coming-soon-meta {
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Buttons */
.btn {
    padding: 10px 20px;
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

.btn-outline-info {
    background: transparent;
    color: var(--info-color);
    border: 2px solid var(--info-color);
}

.btn-outline-info:hover {
    background: var(--info-color);
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
        padding: 32px 20px;
        margin: 0 16px;
    }
    
    .coming-soon-icon {
        width: 80px;
        height: 80px;
        font-size: 32px;
        margin-bottom: 20px;
    }
    
    .coming-soon-title {
        font-size: 24px;
    }
    
    .coming-soon-subtitle {
        font-size: 14px;
    }
    
    .feature-list {
        grid-template-columns: 1fr;
    }
    
    .coming-soon-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 280px;
    }
    
    .preview-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .coming-soon-meta {
        flex-direction: column;
        gap: 12px;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 24px;
    }
    
    .coming-soon-icon {
        width: 70px;
        height: 70px;
        font-size: 28px;
    }
    
    .coming-soon-title {
        font-size: 22px;
    }
    
    .feature-list li {
        font-size: 13px;
    }
    
    .preview-card {
        padding: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add interactive effects
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
    
    // Preview card hover effect
    const previewCard = document.querySelector('.preview-card');
    if (previewCard) {
        previewCard.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.1)';
        });
        
        previewCard.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    }
    
    // Log page visit for analytics
    console.log('Mutasi Edit Page - Coming Soon');
});
</script>
@endpush