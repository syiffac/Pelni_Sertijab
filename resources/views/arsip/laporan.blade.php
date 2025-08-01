{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\laporan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Arsip - SertijabPELNI')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-house"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('arsip.index') }}">
                                <i class="bi bi-archive"></i>
                                Arsip Dokumen
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-file-text"></i>
                            Laporan
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-file-text-fill"></i>
                    Laporan Arsip Dokumen
                </h1>
                <p class="page-subtitle">Generate laporan dan statistik dokumen sertijab</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('arsip.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Under Development Content -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="development-container">
                <!-- Development Card -->
                <div class="development-card">
                    <div class="development-header">
                        <div class="development-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h2 class="development-title">Sedang Dalam Pengembangan</h2>
                        <p class="development-subtitle">Fitur laporan arsip dokumen sedang dikembangkan</p>
                    </div>

                    <div class="development-body">
                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="progress-header">
                                <span class="progress-label">Progress Pengembangan</span>
                                <span class="progress-percentage">65%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: 65%"></div>
                            </div>
                        </div>

                        <!-- Coming Features -->
                        <div class="coming-features">
                            <h5 class="features-title">
                                <i class="bi bi-star me-2"></i>
                                Fitur yang Akan Tersedia
                            </h5>
                            <div class="features-grid">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Statistik Dokumen</h6>
                                        <p class="feature-desc">Ringkasan jumlah dokumen per periode, kapal, dan status</p>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-calendar-range"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Laporan Periode</h6>
                                        <p class="feature-desc">Generate laporan berdasarkan rentang tanggal tertentu</p>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-ship"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Laporan per Kapal</h6>
                                        <p class="feature-desc">Analisis mutasi ABK per kapal dan jabatan</p>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Analisis ABK</h6>
                                        <p class="feature-desc">Tracking mutasi dan riwayat karir ABK</p>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-file-pdf"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Export PDF/Excel</h6>
                                        <p class="feature-desc">Download laporan dalam format PDF atau Excel</p>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-pie-chart"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h6 class="feature-name">Dashboard Analytics</h6>
                                        <p class="feature-desc">Visualisasi data dengan chart dan grafik interaktif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="development-timeline">
                            <h5 class="timeline-title">
                                <i class="bi bi-clock-history me-2"></i>
                                Timeline Pengembangan
                            </h5>
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-marker">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Desain UI/UX</h6>
                                        <p class="timeline-desc">Perancangan interface dan user experience</p>
                                        <span class="timeline-date">Selesai</span>
                                    </div>
                                </div>

                                <div class="timeline-item completed">
                                    <div class="timeline-marker">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Database Schema</h6>
                                        <p class="timeline-desc">Struktur database untuk fitur laporan</p>
                                        <span class="timeline-date">Selesai</span>
                                    </div>
                                </div>

                                <div class="timeline-item active">
                                    <div class="timeline-marker">
                                        <i class="bi bi-gear-fill"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Backend Development</h6>
                                        <p class="timeline-desc">Pengembangan API dan logic laporan</p>
                                        <span class="timeline-date">Dalam Progress</span>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        <i class="bi bi-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Frontend Implementation</h6>
                                        <p class="timeline-desc">Implementasi interface dan integrasi</p>
                                        <span class="timeline-date">Q1 2025</span>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        <i class="bi bi-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Testing & Launch</h6>
                                        <p class="timeline-desc">Testing fitur dan peluncuran ke production</p>
                                        <span class="timeline-date">Q1 2025</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="contact-info">
                            <div class="contact-card">
                                <h6 class="contact-title">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Butuh Informasi Lebih Lanjut?
                                </h6>
                                <p class="contact-desc">
                                    Hubungi tim pengembangan untuk informasi progress atau request fitur khusus.
                                </p>
                                <div class="contact-actions">
                                    <a href="mailto:dev@pelni.co.id" class="btn btn-outline-primary">
                                        <i class="bi bi-envelope me-2"></i>
                                        Hubungi Developer
                                    </a>
                                    <a href="{{ route('arsip.index') }}" class="btn btn-primary">
                                        <i class="bi bi-archive me-2"></i>
                                        Kembali ke Arsip
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alternative Actions -->
                <div class="alternative-actions">
                    <h5 class="alternatives-title">Sementara Waktu, Anda Dapat:</h5>
                    <div class="action-cards">
                        <a href="{{ route('arsip.index') }}" class="action-card">
                            <div class="action-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h6 class="action-title">Cari & Filter Arsip</h6>
                            <p class="action-desc">Gunakan fitur pencarian dan filter di halaman arsip</p>
                        </a>

                        <a href="{{ route('arsip.create') }}" class="action-card">
                            <div class="action-icon">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <h6 class="action-title">Tambah Dokumen</h6>
                            <p class="action-desc">Tambahkan dokumen sertijab baru ke arsip</p>
                        </a>

                        <a href="{{ route('dashboard') }}" class="action-card">
                            <div class="action-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h6 class="action-title">Dashboard</h6>
                            <p class="action-desc">Lihat statistik umum di dashboard utama</p>
                        </a>
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
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f8fafc;
    --border-radius: 12px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Development Container */
.development-container {
    margin-top: 2rem;
}

.development-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.development-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #1d4ed8 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.development-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.development-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.development-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.development-body {
    padding: 2.5rem 2rem;
}

/* Progress Bar */
.progress-container {
    margin-bottom: 3rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.progress-label {
    font-weight: 600;
    color: var(--text-dark);
}

.progress-percentage {
    font-weight: 700;
    color: var(--primary-blue);
    font-size: 1.1rem;
}

.progress-bar-container {
    height: 8px;
    background: var(--background-light);
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-blue) 0%, #3b82f6 100%);
    transition: width 0.5s ease;
}

/* Features Grid */
.coming-features {
    margin-bottom: 3rem;
}

.features-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.feature-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--background-light);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.feature-item:hover {
    box-shadow: var(--shadow-medium);
    transform: translateY(-2px);
}

.feature-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-blue);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.feature-content {
    flex: 1;
}

.feature-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.feature-desc {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

/* Timeline */
.development-timeline {
    margin-bottom: 3rem;
}

.timeline-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
}

.timeline-item.completed .timeline-marker {
    color: var(--success-color);
    border-color: var(--success-color);
}

.timeline-item.active .timeline-marker {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
    animation: pulse 2s infinite;
}

.timeline-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.timeline-desc {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.timeline-date {
    font-size: 0.8rem;
    color: var(--primary-blue);
    font-weight: 500;
}

/* Contact Info */
.contact-info {
    margin-bottom: 2rem;
}

.contact-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #bae6fd;
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
}

.contact-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-desc {
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.contact-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Alternative Actions */
.alternative-actions {
    margin-top: 3rem;
}

.alternatives-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    text-align: center;
}

.action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem 1.5rem;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: var(--transition);
}

.action-card:hover {
    border-color: var(--primary-blue);
    box-shadow: var(--shadow-medium);
    transform: translateY(-4px);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--primary-blue) 0%, #3b82f6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin: 0 auto 1rem;
}

.action-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.action-desc {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

/* Animations */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .development-header {
        padding: 2rem 1.5rem;
    }
    
    .development-title {
        font-size: 1.5rem;
    }
    
    .development-body {
        padding: 2rem 1.5rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .action-cards {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush