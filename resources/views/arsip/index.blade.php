{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Arsip Sertijab')

@section('content')
<div class="container-fluid px-4">
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

    <!-- Stats Cards -->
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

    <!-- Chart & Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-graph-up me-2"></i>
                        Grafik Arsip Bulanan ({{ date('Y') }})
                    </h5>
                    <a href="{{ route('arsip.laporan') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Laporan
                    </a>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <h5 class="quick-actions-title">
                        <i class="bi bi-lightning me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="quick-actions-body">
                    {{-- Action Buttons --}}
                    <div class="actions-grid">
                        <a href="{{ route('arsip.search') }}" class="action-btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                            <span>Pencarian Arsip</span>
                        </a>
                        <a href="{{ route('arsip.create') }}" class="action-btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i>
                            <span>Tambah Arsip Manual</span>
                        </a>
                        <a href="{{ route('monitoring.sertijab') }}" class="action-btn btn-outline-info">
                            <i class="bi bi-eye"></i>
                            <span>Monitoring Sertijab</span>
                        </a>
                    </div>

                    <div class="divider"></div>

                    {{-- FIXED: Status Distribution with proper progress bars --}}
                    <div class="status-distribution">
                        <div class="section-title">Status Distribution:</div>
                        
                        @php
                            $finalPercentage = $stats['completion_rate'];
                            $draftPercentage = 100 - $finalPercentage;
                        @endphp
                        
                        {{-- Final Progress --}}
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Final</span>
                                <span class="progress-value text-success">{{ $finalPercentage }}%</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ max($finalPercentage, 2) }}%"
                                     data-percentage="{{ $finalPercentage }}">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Draft Progress --}}
                        @if($draftPercentage > 0)
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Draft</span>
                                <span class="progress-value text-warning">{{ $draftPercentage }}%</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar bg-warning" 
                                     style="width: {{ max($draftPercentage, 2) }}%"
                                     data-percentage="{{ $draftPercentage }}">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="info-footer">
                        <i class="bi bi-info-circle me-2"></i>
                        Total {{ number_format($stats['total_arsip']) }} dokumen arsip tersimpan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kapal Selection dengan Search -->
    <div class="kapal-section">
        <div class="kapal-header">
            <h5 class="kapal-title">
                <i class="bi bi-ship me-2"></i>
                Pilih Kapal untuk Melihat Arsip
            </h5>
            <div class="kapal-search">
                <div class="search-wrapper">
                    <input type="text" 
                           class="search-input" 
                           id="kapalSearch" 
                           placeholder="Cari nama kapal..."
                           autocomplete="off">
                    <i class="bi bi-search search-icon"></i>
                    <button type="button" class="search-clear" id="searchClear" style="display: none;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="search-info" id="searchInfo">
                    <small>{{ count($kapalList) }} kapal tersedia</small>
                </div>
            </div>
        </div>
        
        <div class="kapal-body">
            <div class="search-status" id="searchStatus" style="display: none;">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <span id="searchStatusText">Menampilkan hasil pencarian</span>
                </div>
            </div>

            <div class="kapal-grid" id="kapalGrid">
                @forelse($kapalList as $kapal)
                <div class="kapal-item" 
                     data-name="{{ strtolower($kapal->nama_kapal) }}"
                     data-type="{{ strtolower($kapal->tipe_pax ?? 'kapal penumpang') }}">
                    <a href="{{ route('arsip.search', ['kapal_id' => $kapal->id]) }}" class="kapal-link">
                        <div class="kapal-card">
                            <div class="kapal-header">
                                <div class="kapal-icon">
                                    <i class="bi bi-ship"></i>
                                </div>
                                <div class="kapal-info">
                                    <h6 class="kapal-name">{{ $kapal->nama_kapal }}</h6>
                                    <p class="kapal-type">{{ $kapal->tipe_pax ?? 'Kapal Penumpang' }}</p>
                                </div>
                            </div>
                            
                            <div class="kapal-stats">
                                <div class="stat">
                                    {{-- FIXED: Use correct attribute names from withCount --}}
                                    <div class="stat-value text-primary">{{ $kapal->total_arsip ?? 0 }}</div>
                                    <div class="stat-label">Total</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value text-success">{{ $kapal->final_arsip ?? 0 }}</div>
                                    <div class="stat-label">Final</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value text-warning">{{ $kapal->draft_arsip ?? 0 }}</div>
                                    <div class="stat-label">Draft</div>
                                </div>
                            </div>

                            {{-- FIXED: Calculate completion percentage correctly --}}
                            @if(($kapal->total_arsip ?? 0) > 0)
                            <div class="kapal-progress">
                                @php
                                    $totalArsip = $kapal->total_arsip ?? 0;
                                    $finalArsip = $kapal->final_arsip ?? 0;
                                    $completion = $totalArsip > 0 ? round(($finalArsip / $totalArsip) * 100) : 0;
                                    $progressClass = $completion < 50 ? 'danger' : ($completion < 80 ? 'warning' : 'success');
                                @endphp
                                <div class="progress-wrapper">
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar bg-{{ $progressClass }}" 
                                             style="width: {{ max($completion, 2) }}%"
                                             data-percentage="{{ $completion }}">
                                        </div>
                                    </div>
                                    <span class="progress-text">{{ $completion }}% final</span>
                                </div>
                            </div>
                            @endif

                            <div class="kapal-footer">
                                <span>Klik untuk lihat arsip</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-ship fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Data Kapal</h5>
                    <p class="text-muted mb-3">Tambahkan data kapal terlebih dahulu</p>
                    <a href="{{ route('kapal.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kapal
                    </a>
                </div>
                @endforelse
            </div>

            <div class="empty-state" id="noResults" style="display: none;">
                <i class="bi bi-search fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Tidak Ada Kapal Ditemukan</h5>
                <p class="text-muted mb-3">Coba ubah kata kunci pencarian</p>
                <button class="btn btn-outline-primary" id="resetSearch">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Pencarian
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

/* Simplified Page Header */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
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

.action-buttons {
    display: flex;
    gap: 12px;
}

/* Simplified Stats Cards */
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

.stats-card-primary { --accent-color: var(--primary-blue); }
.stats-card-success { --accent-color: var(--success-color); }
.stats-card-warning { --accent-color: var(--warning-color); }
.stats-card-info { --accent-color: var(--info-color); }

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    background: var(--accent-color);
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
}

.progress-success { color: var(--success-color); }
.progress-warning { color: var(--warning-color); }
.progress-danger { color: var(--danger-color); }

/* Simplified Chart Card */
.chart-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.chart-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.chart-body {
    padding: 24px;
    height: 350px;
}

/* FIXED: Simplified Quick Actions */
.quick-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
    height: fit-content;
}

.quick-actions-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
}

.quick-actions-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.quick-actions-body {
    padding: 24px;
}

.actions-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border: 2px solid;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.action-btn i {
    font-size: 18px;
    width: 20px;
    text-align: center;
}

.btn-outline-primary {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-outline-success {
    color: var(--success-color);
    border-color: var(--success-color);
}

.btn-outline-success:hover {
    background: var(--success-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-outline-info {
    color: var(--info-color);
    border-color: var(--info-color);
}

.btn-outline-info:hover {
    background: var(--info-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-color), transparent);
    margin: 24px 0;
}

/* FIXED: Status Distribution with proper progress bars */
.status-distribution {
    margin-bottom: 24px;
}

.section-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-item {
    margin-bottom: 16px;
}

.progress-item:last-child {
    margin-bottom: 0;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 500;
}

.progress-value {
    font-weight: 700;
}

/* FIXED: Progress bar wrapper with minimum width */
.progress-bar-wrapper {
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease-out;
    /* FIXED: Ensure minimum width for visibility */
    min-width: 4px !important;
}

.progress-bar.bg-success {
    background: linear-gradient(90deg, var(--success-color), #34d399);
}

.progress-bar.bg-warning {
    background: linear-gradient(90deg, var(--warning-color), #fbbf24);
}

.progress-bar.bg-danger {
    background: linear-gradient(90deg, var(--danger-color), #f87171);
}

/* FIXED: Progress bar animation on load */
.progress-bar[data-percentage="100"] {
    width: 100% !important;
    min-width: unset !important;
}

.info-footer {
    text-align: center;
    padding: 16px 12px 0;
    border-top: 1px solid var(--border-color);
    font-size: 12px;
    color: var(--text-muted);
}

/* Simplified Kapal Section */
.kapal-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.kapal-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kapal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.kapal-search {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 300px;
}

.search-wrapper {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 8px 16px 8px 40px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    transition: var(--transition);
}

.search-input:focus {
    border-color: var(--primary-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.search-clear {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: var(--transition);
}

.search-clear:hover {
    color: var(--danger-color);
    background: rgba(239, 68, 68, 0.1);
}

.search-info {
    text-align: right;
    color: var(--text-muted);
    font-size: 12px;
}

.kapal-body {
    padding: 24px;
}

.kapal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.kapal-item {
    transition: var(--transition);
}

.kapal-item.hidden {
    display: none;
}

.kapal-item.highlighted .kapal-card {
    border-color: var(--primary-blue);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}

.kapal-link {
    text-decoration: none;
    color: inherit;
}

.kapal-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 20px;
    transition: var(--transition);
    cursor: pointer;
    height: 100%;
}

.kapal-card:hover {
    border-color: var(--primary-blue);
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
}

.kapal-header {
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
}

.kapal-info {
    flex: 1;
}

.kapal-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
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

.stat {
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
    padding-top: 16px;
    border-top: 1px solid var(--border-color);
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.kapal-card:hover .kapal-footer i {
    color: var(--primary-blue);
    transform: translateX(4px);
}

/* Common Styles */
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
    text-decoration: none;
}

.alert {
    border-radius: var(--border-radius);
    border: none;
    padding: 16px 20px;
    margin-bottom: 16px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
}

.alert-info {
    background: #f0f9ff;
    color: #1e40af;
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
    grid-column: 1 / -1;
}

.search-highlight {
    background: linear-gradient(120deg, #fbbf24, #f59e0b);
    color: #92400e;
    padding: 1px 2px;
    border-radius: 2px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .kapal-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .kapal-search {
        min-width: auto;
        width: 100%;
    }
    
    .kapal-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FIXED: Progress bars animation with proper 100% handling
    const progressBars = document.querySelectorAll('.progress-bar[data-percentage]');
    
    progressBars.forEach(bar => {
        const percentage = parseInt(bar.dataset.percentage);
        
        // Reset width first
        bar.style.width = '0%';
        
        // Animate to target width
        setTimeout(() => {
            if (percentage === 100) {
                bar.style.width = '100%';
                bar.style.minWidth = 'unset';
            } else {
                bar.style.width = Math.max(percentage, 2) + '%';
            }
        }, 500);
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
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
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // Simplified Search Functionality
    const searchInput = document.getElementById('kapalSearch');
    const searchClear = document.getElementById('searchClear');
    const searchInfo = document.getElementById('searchInfo');
    const searchStatus = document.getElementById('searchStatus');
    const searchStatusText = document.getElementById('searchStatusText');
    const kapalGrid = document.getElementById('kapalGrid');
    const noResults = document.getElementById('noResults');
    const resetSearch = document.getElementById('resetSearch');
    
    const kapalItems = document.querySelectorAll('.kapal-item');
    const totalKapal = kapalItems.length;

    searchInput?.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        
        if (query.length > 0) {
            searchClear.style.display = 'block';
            performSearch(query);
        } else {
            searchClear.style.display = 'none';
            resetSearchResults();
        }
    });

    searchClear?.addEventListener('click', function() {
        searchInput.value = '';
        searchClear.style.display = 'none';
        resetSearchResults();
        searchInput.focus();
    });

    resetSearch?.addEventListener('click', function() {
        searchInput.value = '';
        searchClear.style.display = 'none';
        resetSearchResults();
    });

    function performSearch(query) {
        let visibleCount = 0;

        kapalItems.forEach(item => {
            const name = item.dataset.name || '';
            const type = item.dataset.type || '';
            
            if (name.includes(query) || type.includes(query)) {
                item.classList.remove('hidden');
                item.classList.add('highlighted');
                highlightText(item, query);
                visibleCount++;
            } else {
                item.classList.add('hidden');
                item.classList.remove('highlighted');
            }
        });

        updateSearchUI(query, visibleCount);
    }

    function resetSearchResults() {
        kapalItems.forEach(item => {
            item.classList.remove('hidden', 'highlighted');
            removeHighlight(item);
        });
        
        searchStatus.style.display = 'none';
        noResults.style.display = 'none';
        kapalGrid.style.display = 'grid';
        searchInfo.innerHTML = `<small>${totalKapal} kapal tersedia</small>`;
    }

    function updateSearchUI(query, count) {
        if (count === 0) {
            kapalGrid.style.display = 'none';
            noResults.style.display = 'block';
            searchStatus.style.display = 'block';
            searchStatusText.textContent = `Tidak ditemukan kapal dengan kata kunci "${query}"`;
        } else {
            kapalGrid.style.display = 'grid';
            noResults.style.display = 'none';
            searchStatus.style.display = 'block';
            searchStatusText.textContent = `Menampilkan ${count} dari ${totalKapal} kapal`;
        }
        
        searchInfo.innerHTML = `<small>${count} kapal ditemukan</small>`;
    }

    function highlightText(item, query) {
        const nameEl = item.querySelector('.kapal-name');
        const typeEl = item.querySelector('.kapal-type');
        
        [nameEl, typeEl].forEach(el => {
            if (el && !el.dataset.original) {
                el.dataset.original = el.textContent;
                const regex = new RegExp(`(${query})`, 'gi');
                el.innerHTML = el.textContent.replace(regex, '<span class="search-highlight">$1</span>');
            }
        });
    }

    function removeHighlight(item) {
        const elements = item.querySelectorAll('[data-original]');
        elements.forEach(el => {
            el.innerHTML = el.dataset.original;
            delete el.dataset.original;
        });
    }
});
</script>
@endpush