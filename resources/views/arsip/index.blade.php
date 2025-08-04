{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Arsip Sertijab')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header dengan styling yang sama seperti monitoring -->
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
                        <li class="breadcrumb-item active">Arsip Sertijab</li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-archive-fill"></i>
                    Data Arsip Sertijab
                </h1>
                <p class="page-subtitle">Kelola dan pantau dokumen arsip serah terima jabatan ABK PELNI</p>
            </div>
            <div class="header-actions">
                <div class="action-buttons">
                    <a href="{{ route('arsip.search') }}" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>
                        Pencarian Arsip
                    </a>
                    <a href="{{ route('arsip.create') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Arsip Manual
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards dengan styling yang sama seperti monitoring -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-icon">
                    <i class="bi bi-archive-fill"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['total_arsip']) }}</div>
                    <div class="stats-label">Total Arsip</div>
                    <div class="stats-description">Dokumen arsip tersimpan</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-success">
                <div class="stats-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['final_arsip']) }}</div>
                    <div class="stats-label">Dokumen Final</div>
                    <div class="stats-description">
                        <span class="progress-indicator progress-{{ $stats['completion_rate'] < 50 ? 'danger' : ($stats['completion_rate'] < 80 ? 'warning' : 'success') }}">
                            {{ $stats['completion_rate'] }}%
                        </span> dari total arsip
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['draft_arsip']) }}</div>
                    <div class="stats-label">Draft Arsip</div>
                    <div class="stats-description">
                        @if($stats['draft_arsip'] > 0)
                            <span class="text-warning">Perlu tindak lanjut</span>
                        @else
                            <span class="text-success">Semua sudah final</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-info">
                <div class="stats-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['pending_verification']) }}</div>
                    <div class="stats-label">Menunggu Verifikasi</div>
                    <div class="stats-description">
                        @if($stats['rejected_documents'] > 0)
                            <span class="text-danger">{{ $stats['rejected_documents'] }} ditolak</span>
                        @else
                            <span class="text-info">Tidak ada yang ditolak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart & Quick Actions dengan styling yang sama -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-graph-up me-2"></i>
                            Grafik Arsip Bulanan ({{ date('Y') }})
                        </h5>
                        <div class="table-info">
                            <a href="{{ route('arsip.laporan') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="monthlyChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-lightning me-2"></i>
                            Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('arsip.search') }}" class="btn btn-outline-primary">
                                <i class="bi bi-search me-2"></i> Pencarian Arsip
                            </a>
                            <a href="{{ route('arsip.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Arsip Manual
                            </a>
                            <a href="{{ route('monitoring.sertijab') }}" class="btn btn-outline-info">
                                <i class="bi bi-eye me-2"></i> Monitoring Sertijab
                            </a>
                            <a href="{{ route('arsip.laporan') }}" class="btn btn-outline-dark">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Generate Laporan
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="small text-muted mb-3">
                            <strong>Status Distribution:</strong>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Final</span>
                                <span class="small fw-medium">{{ $stats['completion_rate'] }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['completion_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Draft</span>
                                <span class="small fw-medium">{{ 100 - $stats['completion_rate'] }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 100 - $stats['completion_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kapal Selection Cards dengan styling yang sama -->
    <div class="row">
        <div class="col-12">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-ship me-2"></i>
                            Pilih Kapal untuk Melihat Arsip
                        </h5>
                        <div class="table-info">
                            <small class="text-muted">Klik kapal untuk melihat arsip sertijab</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($kapalList as $kapal)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="{{ route('arsip.search', ['kapal_id' => $kapal->id]) }}" class="text-decoration-none">
                                    <div class="kapal-card">
                                        <div class="kapal-card-header">
                                            <div class="kapal-icon">
                                                <i class="bi bi-ship"></i>
                                            </div>
                                            <div class="kapal-info">
                                                <h6 class="kapal-name">{{ $kapal->nama_kapal }}</h6>
                                                <p class="kapal-type">{{ $kapal->tipe_pax ?? 'Kapal Penumpang' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="kapal-stats">
                                            <div class="stat-item">
                                                <div class="stat-value text-primary">{{ $kapal->total_arsip ?? 0 }}</div>
                                                <div class="stat-label">Total</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value text-success">{{ $kapal->final_arsip ?? 0 }}</div>
                                                <div class="stat-label">Final</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value text-warning">{{ $kapal->draft_arsip ?? 0 }}</div>
                                                <div class="stat-label">Draft</div>
                                            </div>
                                        </div>

                                        @if(($kapal->total_arsip ?? 0) > 0)
                                        <div class="kapal-progress">
                                            @php
                                                $completion = round((($kapal->final_arsip ?? 0) / ($kapal->total_arsip ?? 1)) * 100);
                                                $progressClass = $completion < 50 ? 'danger' : ($completion < 80 ? 'warning' : 'success');
                                            @endphp
                                            <div class="progress-wrapper">
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $progressClass }}" style="width: {{ $completion }}%"></div>
                                                </div>
                                                <span class="progress-text">{{ $completion }}% final</span>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="kapal-footer">
                                            <span class="footer-text">Klik untuk lihat arsip</span>
                                            <i class="bi bi-arrow-right footer-icon"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-ship fs-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum Ada Data Kapal</h5>
                                    <p class="text-muted mb-3">Tambahkan data kapal terlebih dahulu atau terjadi kesalahan saat memuat data</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('kapal.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Kapal
                                        </a>
                                        <button class="btn btn-outline-secondary" onclick="location.reload()">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforelse
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
/* Import all styling variables from monitoring page */
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

/* Page Header - sama seperti monitoring */
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
    flex-direction: column;
    gap: 12px;
    align-items: flex-end;
}

.action-buttons {
    display: flex;
    gap: 12px;
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

/* Stats Cards - sama seperti monitoring */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-color);
}

.stats-card-primary {
    --accent-color: var(--primary-blue);
}

.stats-card-primary .stats-icon {
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
}

.stats-card-success {
    --accent-color: var(--success-color);
}

.stats-card-success .stats-icon {
    background: linear-gradient(135deg, var(--success-color), #34d399);
}

.stats-card-warning {
    --accent-color: var(--warning-color);
}

.stats-card-warning .stats-icon {
    background: linear-gradient(135deg, var(--warning-color), #fbbf24);
}

.stats-card-info {
    --accent-color: var(--info-color);
}

.stats-card-info .stats-icon {
    background: linear-gradient(135deg, var(--info-color), #22d3ee);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.stats-label {
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.stats-description {
    color: var(--text-muted);
    font-size: 12px;
    line-height: 1.4;
}

.progress-indicator {
    font-weight: 600;
}

.progress-success {
    color: var(--success-color);
}

.progress-warning {
    color: var(--warning-color);
}

.progress-danger {
    color: var(--danger-color);
}

/* Table Section - sama seperti monitoring */
.table-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
    margin-bottom: 24px;
}

.table-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.table-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.table-info {
    color: var(--text-muted);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Kapal Cards - styling baru yang sama dengan monitoring */
.kapal-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 20px;
    transition: var(--transition);
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.kapal-card:hover {
    border-color: var(--primary-blue);
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
}

.kapal-card-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 20px;
}

.kapal-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--info-color), #22d3ee);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.kapal-info {
    flex: 1;
    min-width: 0;
}

.kapal-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    word-wrap: break-word;
}

.kapal-type {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

.kapal-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    padding: 16px;
    background: var(--background-light);
    border-radius: 8px;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.kapal-progress {
    margin-bottom: 20px;
}

.progress-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress {
    flex: 1;
    height: 6px;
    background-color: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    white-space: nowrap;
}

.kapal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid var(--border-color);
}

.footer-text {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.footer-icon {
    color: var(--text-muted);
    font-size: 14px;
    transition: var(--transition);
}

.kapal-card:hover .footer-icon {
    color: var(--primary-blue);
    transform: translateX(4px);
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

/* Buttons - sama seperti monitoring */
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

.btn-outline-primary {
    background: transparent;
    color: var(--primary-blue);
    border: 2px solid var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
}

.btn-outline-success {
    background: transparent;
    color: var(--success-color);
    border: 2px solid var(--success-color);
}

.btn-outline-success:hover {
    background: var(--success-color);
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

.btn-outline-dark {
    background: transparent;
    color: var(--text-dark);
    border: 2px solid var(--text-dark);
}

.btn-outline-dark:hover {
    background: var(--text-dark);
    color: white;
    text-decoration: none;
}

/* Alert styling */
.alert {
    border-radius: var(--border-radius);
    border: none;
    padding: 16px 20px;
    margin-bottom: 24px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
        align-items: stretch;
    }
    
    .kapal-card-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .kapal-stats {
        gap: 8px;
    }
    
    .stat-value {
        font-size: 16px;
    }
    
    .progress-wrapper {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    
    .progress-text {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 16px;
    }
    
    .stats-card {
        padding: 16px;
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
    
    .stats-number {
        font-size: 20px;
    }
    
    .kapal-card {
        padding: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        
        if (monthlyCtx) {
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Dokumen Arsip',
                        data: @json($monthlyData),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: {
                                title: function(context) {
                                    return 'Bulan ' + context[0].label + ' {{ date("Y") }}';
                                },
                                label: function(context) {
                                    return 'Dokumen Arsip: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: '#2563eb'
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
    });
</script>
@endpush