@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Sistem Manajemen Sertijab PELNI - Overview dan Statistik')

@section('header') @endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content-wrapper">
                    <div class="welcome-main">
                        <div class="welcome-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="welcome-content">
                            <h2 class="welcome-title">Halo, {{ auth()->user()->nama_admin }}!</h2>
                            <h2 class="welcome-sertijab">Selamat Datang di Sertijab Manajemen Sistem PELNI</h2>
                            <p class="welcome-text">Kelola sistem serah terima jabatan dengan mudah dan efisien</p>
                            <div class="welcome-meta">
                                <span class="welcome-date">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    {{ date('d F Y') }}
                                </span>
                                <span class="welcome-time">
                                    <i class="bi bi-clock me-2"></i>
                                    <span id="current-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="welcome-actions">
                        <a href="{{ route('abk.create') }}" class="btn btn-welcome-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah ABK Baru
                        </a>
                        <a href="{{ route('abk.mutasi.create') }}" class="btn btn-welcome-secondary">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            Buat Mutasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="section-title">
                <i class="bi bi-graph-up me-2"></i>
                Statistik Utama
            </h4>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card stats-primary">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stats-trend positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>+12%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ isset($totalStatistik) ? number_format($totalStatistik['total_abk']) : ($mockData['total_abk'] ?? '1,245') }}</h3>
                    <p class="stats-title">Total ABK</p>
                    <small class="stats-subtitle">Dari bulan lalu</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card stats-success">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div class="stats-trend positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>+5%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ isset($totalStatistik) ? number_format($totalStatistik['sertijab_bulan_ini']) : ($mockData['sertijab_bulan_ini'] ?? '89') }}</h3>
                    <p class="stats-title">Mutasi Bulan Ini</p>
                    <small class="stats-subtitle">Dari bulan lalu</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card stats-warning">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stats-trend negative">
                        <i class="bi bi-arrow-down"></i>
                        <span>-3%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ isset($totalStatistik) ? number_format($totalStatistik['menunggu_verifikasi']) : ($mockData['menunggu_verifikasi'] ?? '23') }}</h3>
                    <p class="stats-title">Menunggu Proses</p>
                    <small class="stats-subtitle">Dari bulan lalu</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card stats-info">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                    <div class="stats-trend positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>+8%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ isset($totalStatistik) ? number_format($totalStatistik['total_arsip']) : ($mockData['total_arsip'] ?? '3,567') }}</h3>
                    <p class="stats-title">Mutasi Selesai</p>
                    <small class="stats-subtitle">Dari bulan lalu</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="section-title">
                <i class="bi bi-bar-chart-line me-2"></i>
                Analisis Data
            </h4>
        </div>
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">Trend Mutasi Tahunan</h5>
                        <p class="chart-subtitle">Perbandingan data 12 bulan terakhir</p>
                    </div>
                    <div class="chart-actions">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-secondary active">12 Bulan</button>
                            <button class="btn btn-sm btn-outline-secondary">6 Bulan</button>
                            <button class="btn btn-sm btn-outline-secondary">3 Bulan</button>
                        </div>
                        <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-primary ms-2">
                            <i class="bi bi-eye me-1"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-placeholder">
                        <div class="chart-icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <h6>Chart Trend Mutasi</h6>
                        <p>Grafik akan menampilkan trend mutasi bulanan</p>
                        <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-outline-primary">Lihat Monitoring</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">Status Mutasi</h5>
                        <p class="chart-subtitle">Distribusi status saat ini</p>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-placeholder">
                        <div class="chart-icon">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <h6>Status Distribution</h6>
                        <p>Pie chart distribusi status mutasi</p>
                        <a href="{{ route('monitoring.sertijab') }}" class="btn btn-sm btn-outline-primary">Lihat Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <!-- Recent Activities -->
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title-section">
                        <h5 class="activity-title">
                            <i class="bi bi-clock-history me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <p class="activity-subtitle">Update sistem dalam 24 jam terakhir</p>
                    </div>
                    <div class="activity-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Refresh
                        </button>
                        <a href="{{ route('abk.index') }}" class="btn btn-sm btn-link">Lihat Semua</a>
                    </div>
                </div>
                <div class="activity-body">
                    <div class="activity-list">
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            @foreach($recentActivities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon bg-{{ $activity['color'] }}">
                                        <i class="bi bi-{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-main">
                                            <p class="activity-text">{{ $activity['title'] }}</p>
                                            <span class="activity-badge badge bg-{{ $activity['color'] }}">{{ $activity['badge'] }}</span>
                                        </div>
                                        <div class="activity-meta">
                                            <span class="activity-time">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $activity['time'] }}
                                            </span>
                                            <span class="activity-user">
                                                <i class="bi bi-info-circle me-1"></i>
                                                {{ $activity['subtitle'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default activities if no data -->
                            <div class="activity-item">
                                <div class="activity-icon bg-primary">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-main">
                                        <p class="activity-text">Sistem siap digunakan untuk mengelola data ABK</p>
                                        <span class="activity-badge badge bg-primary">System</span>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <i class="bi bi-clock me-1"></i>
                                            Baru saja
                                        </span>
                                        <span class="activity-user">
                                            <i class="bi bi-person me-1"></i>
                                            Auto System
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5 mb-4">
            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <div class="quick-actions-title-section">
                        <h5 class="quick-actions-title">
                            <i class="bi bi-lightning me-2"></i>
                            Aksi Cepat
                        </h5>
                        <p class="quick-actions-subtitle">Shortcut untuk tugas umum</p>
                    </div>
                </div>
                <div class="quick-actions-body">
                    <div class="quick-actions-grid">
                        <a href="{{ route('abk.create') }}" class="quick-action-item" data-action="Tambah ABK">
                            <div class="quick-action-icon bg-primary">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <span class="quick-action-text">Tambah ABK</span>
                        </a>
                        
                        <a href="{{ route('abk.mutasi.create') }}" class="quick-action-item" data-action="Buat Mutasi">
                            <div class="quick-action-icon bg-success">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <span class="quick-action-text">Buat Mutasi</span>
                        </a>
                        
                        <a href="{{ route('arsip.search') }}" class="quick-action-item" data-action="Cari Arsip">
                            <div class="quick-action-icon bg-warning">
                                <i class="bi bi-search"></i>
                            </div>
                            <span class="quick-action-text">Cari Arsip</span>
                        </a>
                        
                        <a href="{{ route('abk.export') }}" class="quick-action-item" data-action="Export Data">
                            <div class="quick-action-icon bg-info">
                                <i class="bi bi-download"></i>
                            </div>
                            <span class="quick-action-text">Export Data</span>
                        </a>
                        
                        <a href="{{ route('settings.index') }}" class="quick-action-item" data-action="Pengaturan">
                            <div class="quick-action-icon bg-secondary">
                                <i class="bi bi-gear"></i>
                            </div>
                            <span class="quick-action-text">Pengaturan</span>
                        </a>
                        
                        <a href="{{ route('abk.index') }}" class="quick-action-item" data-action="Data ABK">
                            <div class="quick-action-icon bg-dark">
                                <i class="bi bi-people"></i>
                            </div>
                            <span class="quick-action-text">Data ABK</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Keep existing styles --}}
<style>
/* CSS Variables */
:root {
    --primary-blue: #2563eb;
    --secondary-blue: #1e40af;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #ef4444;
    --info-color: #06b6d4;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --border-radius: 16px;
    --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Global Styles */
.container-fluid {
    max-width: 1400px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
    align-items: center;
}

.section-title i {
    color: var(--primary-blue);
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-radius: var(--border-radius);
    padding: 32px;
    color: white;
    box-shadow: var(--shadow-large);
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.welcome-content-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 1;
}

.welcome-main {
    display: flex;
    align-items: center;
    flex: 1;
}

.welcome-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin-right: 24px;
    backdrop-filter: blur(10px);
}

.welcome-content {
    flex: 1;
}

.welcome-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
    line-height: 1.2;
}

.welcome-sertijab {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
    line-height: 1.2;
}

.welcome-text {
    margin: 0 0 16px 0;
    opacity: 0.9;
    font-size: 16px;
    line-height: 1.5;
}

.welcome-meta {
    display: flex;
    gap: 24px;
    font-size: 14px;
    opacity: 0.8;
}

.welcome-actions {
    display: flex;
    gap: 12px;
    flex-direction: column;
}

.btn-welcome-primary, .btn-welcome-secondary {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: var(--transition);
    white-space: nowrap;
    text-decoration: none;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-welcome-primary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.btn-welcome-secondary {
    background: transparent;
    color: white;
}

.btn-welcome-primary:hover, .btn-welcome-secondary:hover {
    background: white;
    color: var(--primary-blue);
    border-color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    height: 100%;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-blue);
    transition: var(--transition);
}

.stats-card.stats-primary::before { background: var(--primary-blue); }
.stats-card.stats-success::before { background: var(--success-color); }
.stats-card.stats-warning::before { background: var(--warning-color); }
.stats-card.stats-info::before { background: var(--info-color); }

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.stats-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stats-primary .stats-icon { background: var(--primary-blue); }
.stats-success .stats-icon { background: var(--success-color); }
.stats-warning .stats-icon { background: var(--warning-color); }
.stats-info .stats-icon { background: var(--info-color); }

.stats-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
}

.stats-trend.positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}

.stats-trend.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error-color);
}

.stats-body {
    text-align: left;
}

.stats-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-family: 'Poppins', sans-serif;
    line-height: 1;
}

.stats-title {
    font-size: 16px;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-weight: 600;
}

.stats-subtitle {
    font-size: 12px;
    color: var(--text-muted);
}

/* Chart Cards */
.chart-card, .activity-card, .quick-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    height: 100%;
    transition: var(--transition);
}

.chart-card:hover, .activity-card:hover, .quick-actions-card:hover {
    box-shadow: var(--shadow-medium);
}

.chart-header, .activity-header, .quick-actions-header {
    padding: 24px 24px 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.chart-title-section, .activity-title-section, .quick-actions-title-section {
    flex: 1;
}

.chart-title, .activity-title, .quick-actions-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
    align-items: center;
}

.chart-subtitle, .activity-subtitle, .quick-actions-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.chart-actions, .activity-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.chart-body, .activity-body, .quick-actions-body {
    padding: 24px;
}

.chart-placeholder {
    height: 320px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 2px dashed var(--border-color);
    text-align: center;
}

.chart-icon {
    width: 80px;
    height: 80px;
    background: rgba(37, 99, 235, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--primary-blue);
    margin-bottom: 16px;
}

.chart-placeholder h6 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.chart-placeholder p {
    margin: 0 0 16px 0;
    font-size: 14px;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    transition: var(--transition);
    border: 1px solid transparent;
}

.activity-item:hover {
    background: white;
    border-color: var(--border-color);
    transform: translateX(4px);
    box-shadow: var(--shadow-light);
}

.activity-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-main {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.activity-text {
    margin: 0;
    font-size: 14px;
    color: var(--text-dark);
    line-height: 1.5;
    flex: 1;
    margin-right: 12px;
}

.activity-badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}

.activity-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: var(--text-muted);
}

.activity-time, .activity-user {
    display: flex;
    align-items: center;
}

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.quick-action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 24px 16px;
    background: #f8fafc;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-dark);
    transition: var(--transition);
    border: 2px solid transparent;
    text-align: center;
}

.quick-action-item:hover {
    color: var(--text-dark);
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
    border-color: var(--border-color);
    background: white;
    text-decoration: none;
}

.quick-action-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
}

.quick-action-text {
    font-size: 13px;
    font-weight: 600;
    line-height: 1.3;
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

.fade-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .welcome-content-wrapper {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .welcome-actions {
        flex-direction: row;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 16px;
        padding-right: 16px;
    }
    
    .welcome-card {
        padding: 24px;
    }
    
    .welcome-main {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .welcome-title {
        font-size: 24px;
    }
    
    .welcome-meta {
        justify-content: center;
    }
    
    .welcome-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .stats-number {
        font-size: 28px;
    }
    
    .chart-placeholder {
        height: 240px;
    }
    
    .chart-header, .activity-header, .quick-actions-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .chart-actions, .activity-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .quick-actions-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .quick-action-item {
        padding: 20px 12px;
    }
    
    .quick-action-icon {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }
    
    .quick-action-text {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .activity-main {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .activity-meta {
        flex-direction: column;
        gap: 4px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Add entrance animations
    const cards = document.querySelectorAll('.stats-card, .chart-card, .activity-card, .quick-actions-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
    
    // Add click handlers for quick actions that don't have routes
    const quickActions = document.querySelectorAll('.quick-action-item[data-action]');
    quickActions.forEach(action => {
        // Skip if it's a real link
        if (action.getAttribute('href') && action.getAttribute('href') !== '#') {
            return;
        }
        
        action.addEventListener('click', function(e) {
            e.preventDefault();
            const actionText = this.getAttribute('data-action');
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Show notification (you can replace this with your toast function)
            console.log(`Fitur "${actionText}" akan segera tersedia`);
        });
    });
    
    // Add hover effects for stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Simulate loading success message
    setTimeout(() => {
        console.log('Dashboard berhasil dimuat dengan data real-time');
    }, 1000);
});
</script>
@endpush