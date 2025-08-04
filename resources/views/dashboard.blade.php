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
                            <h2 class="welcome-title">Halo, {{ auth()->user()->nama_admin ?? 'Administrator' }}!</h2>
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
                                @if(isset($quickStats))
                                <span class="welcome-pending">
                                    <i class="bi bi-hourglass-split me-2"></i>
                                    {{ $quickStats['pending_approvals'] ?? 0 }} pending approval
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="welcome-actions">
                        <a href="{{ route('abk.create') }}" class="btn btn-welcome-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah ABK Baru
                        </a>
                        <a href="{{ route('mutasi.create') }}" class="btn btn-welcome-secondary">
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
                        <span>{{ $totalStatistik['mutasi_trend'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ number_format($totalStatistik['total_abk'] ?? 0) }}</h3>
                    <p class="stats-title">Total ABK</p>
                    <small class="stats-subtitle">
                        {{ $totalStatistik['abk_aktif'] ?? 0 }} aktif, 
                        {{ $totalStatistik['abk_pensiun'] ?? 0 }} pensiun
                    </small>
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
                        <span>{{ $totalStatistik['submission_progress'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ number_format($totalStatistik['sertijab_bulan_ini'] ?? 0) }}</h3>
                    <p class="stats-title">Mutasi Bulan Ini</p>
                    <small class="stats-subtitle">
                        {{ $totalStatistik['total_mutasi'] ?? 0 }} total mutasi
                    </small>
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
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>{{ $totalStatistik['verifikasi_progress'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ number_format($totalStatistik['menunggu_verifikasi'] ?? 0) }}</h3>
                    <p class="stats-title">Menunggu Proses</p>
                    <small class="stats-subtitle">
                        {{ $totalStatistik['sertijab_partial'] ?? 0 }} sebagian terverifikasi
                    </small>
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
                        <span>{{ $totalStatistik['efisiensi_proses'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="stats-body">
                    <h3 class="stats-number">{{ number_format($totalStatistik['total_arsip'] ?? 0) }}</h3>
                    <p class="stats-title">Mutasi Selesai</p>
                    <small class="stats-subtitle">
                        {{ $totalStatistik['sertijab_verified'] ?? 0 }} terverifikasi
                    </small>
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
        
        <!-- Trend Mutasi Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">Trend Mutasi Tahunan {{ date('Y') }}</h5>
                        <p class="chart-subtitle">Perbandingan data 12 bulan terakhir</p>
                    </div>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-primary" onclick="refreshChartData()">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Refresh
                        </button>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="mutasiTrendChart" width="400" height="200"></canvas>
                    </div>
                    @if(isset($chartData['monthly_mutasi']) && !empty($chartData['monthly_mutasi']))
                        <div class="chart-summary mt-3">
                            <div class="row text-center">
                                <div class="col-3">
                                    <small class="text-muted">Total Tahun Ini</small>
                                    <div class="fw-bold">{{ array_sum($chartData['monthly_mutasi']) }}</div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted">Rata-rata/Bulan</small>
                                    <div class="fw-bold">{{ round(array_sum($chartData['monthly_mutasi']) / 12, 1) }}</div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted">Bulan Tertinggi</small>
                                    <div class="fw-bold">{{ max($chartData['monthly_mutasi']) }}</div>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted">Bulan Ini</small>
                                    <div class="fw-bold">{{ $chartData['monthly_mutasi'][date('n')-1] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Status Verifikasi Chart -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">Status Verifikasi</h5>
                        <p class="chart-subtitle">Distribusi status dokumen sertijab</p>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="doughnut-chart-container">
                        <canvas id="verificationStatusChart" width="300" height="300"></canvas>
                    </div>
                    @if(isset($chartData['verification_progress']) && !empty($chartData['verification_progress']))
                        <div class="chart-legend mt-3">
                            @foreach($chartData['verification_progress'] as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="legend-color me-2" style="background-color: {{ $item['color'] }}"></div>
                                        <span class="small">{{ $item['label'] }}</span>
                                    </div>
                                    <span class="fw-bold">{{ $item['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                        <p class="activity-subtitle">Update sistem dalam 7 hari terakhir</p>
                    </div>
                    <div class="activity-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshActivities()">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Refresh
                        </button>
                    </div>
                </div>
                <div class="activity-body">
                    <div class="activity-list" id="activityList">
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
                            <div class="activity-item">
                                <div class="activity-icon bg-info">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-main">
                                        <p class="activity-text">Belum ada aktivitas terbaru</p>
                                        <span class="activity-badge badge bg-info">System</span>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <i class="bi bi-clock me-1"></i>
                                            Baru saja
                                        </span>
                                        <span class="activity-user">
                                            <i class="bi bi-robot me-1"></i>
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
                        <a href="{{ route('abk.create') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-primary">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <span class="quick-action-text">Tambah ABK</span>
                        </a>
                        
                        <a href="{{ route('mutasi.create') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-success">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <span class="quick-action-text">Buat Mutasi</span>
                        </a>
                        
                        <a href="{{ route('monitoring.sertijab') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-info">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <span class="quick-action-text">Monitoring</span>
                        </a>
                        
                        <a href="{{ route('monitoring.documents') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-warning">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <span class="quick-action-text">Verifikasi</span>
                            @if(isset($totalStatistik['menunggu_verifikasi']) && $totalStatistik['menunggu_verifikasi'] > 0)
                                <span class="quick-action-badge">{{ $totalStatistik['menunggu_verifikasi'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('kapal.index') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-secondary">
                                <i class="bi bi-ship"></i>
                            </div>
                            <span class="quick-action-text">Data Kapal</span>
                            <small class="quick-action-count">{{ $totalStatistik['total_kapal'] ?? 0 }} kapal</small>
                        </a>
                        
                        <a href="{{ route('abk.index') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-dark">
                                <i class="bi bi-people"></i>
                            </div>
                            <span class="quick-action-text">Data ABK</span>
                            <small class="quick-action-count">{{ $totalStatistik['total_abk'] ?? 0 }} ABK</small>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Summary Info Card -->
            @if(isset($summaryData))
            <div class="summary-card mt-4">
                <div class="summary-header">
                    <h6 class="summary-title">
                        <i class="bi bi-trophy me-2"></i>
                        Summary Info
                    </h6>
                </div>
                <div class="summary-body">
                    @if(isset($summaryData['top_kapal_mutasi']))
                        <div class="summary-item">
                            <strong>Kapal Terbanyak Mutasi:</strong>
                            <span>{{ $summaryData['top_kapal_mutasi']->kapal->nama_kapal ?? 'N/A' }}</span>
                            <small class="text-muted">({{ $summaryData['top_kapal_mutasi']->total ?? 0 }} mutasi)</small>
                        </div>
                    @endif
                    
                    @if(isset($summaryData['most_common_jabatan']))
                        <div class="summary-item">
                            <strong>Jabatan Terbanyak:</strong>
                            <span>{{ $summaryData['most_common_jabatan']->jabatanTetap->nama_jabatan ?? 'N/A' }}</span>
                            <small class="text-muted">({{ $summaryData['most_common_jabatan']->total ?? 0 }} ABK)</small>
                        </div>
                    @endif
                    
                    <div class="summary-item">
                        <strong>Tingkat Penyelesaian:</strong>
                        <span>{{ $summaryData['completion_rate'] ?? 0 }}%</span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ $summaryData['completion_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Real-time Update Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="realtimeToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                Data dashboard berhasil diperbarui
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

<!-- filepath: c:\laragon\www\SertijabPelni\resources\views\dashboard.blade.php -->

@push('styles')
<style>
/* ===== CSS VARIABLES ===== */
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
    --shadow-large: 0 10px 15px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== WELCOME CARD ===== */
.welcome-card {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    border-radius: var(--border-radius);
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
    color: white;
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-10px) rotate(2deg); }
    66% { transform: translateY(5px) rotate(-1deg); }
}

.welcome-content-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    position: relative;
    z-index: 2;
}

.welcome-main {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.welcome-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    backdrop-filter: blur(10px);
}

.welcome-content {
    flex: 1;
}

.welcome-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: white;
}

.welcome-sertijab {
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: rgba(255, 255, 255, 0.95);
}

.welcome-text {
    font-size: 16px;
    margin: 0 0 16px 0;
    color: rgba(255, 255, 255, 0.85);
}

.welcome-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.welcome-meta span {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.95);
    background: rgba(255, 255, 255, 0.15);
    padding: 8px 16px;
    border-radius: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.welcome-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-welcome-primary, .btn-welcome-secondary {
    padding: 14px 28px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    min-width: 180px;
}

.btn-welcome-primary {
    background: white;
    color: var(--primary-blue);
}

.btn-welcome-primary:hover {
    background: #f8fafc;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
    color: var(--primary-blue);
    text-decoration: none;
}

.btn-welcome-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.btn-welcome-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    text-decoration: none;
    transform: translateY(-3px);
}

/* ===== STATS CARDS ===== */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    transition: var(--transition);
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

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-large);
}

.stats-primary { --accent-color: var(--primary-blue); }
.stats-success { --accent-color: var(--success-color); }
.stats-warning { --accent-color: var(--warning-color); }
.stats-info { --accent-color: var(--info-color); }

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
    background: var(--accent-color);
}

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
    color: var(--danger-color);
}

.stats-body {
    text-align: left;
}

.stats-number {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    line-height: 1;
}

.stats-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.stats-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

/* ===== SECTION TITLES ===== */
.section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
}

.section-title i {
    color: var(--primary-blue);
    margin-right: 8px;
}

/* ===== CHART CARDS - FIXED HEIGHT ===== */
.chart-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
    height: 450px; /* Fixed height untuk consistency */
}

.chart-header {
    background: var(--background-light);
    padding: 16px 20px; /* Reduced padding */
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-shrink: 0; /* Prevent shrinking */
}

.chart-title-section {
    flex: 1;
}

.chart-title {
    font-size: 16px; /* Reduced font size */
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.chart-subtitle {
    font-size: 13px; /* Reduced font size */
    color: var(--text-muted);
    margin: 0;
}

.chart-body {
    padding: 16px 20px; /* Reduced padding */
    height: calc(100% - 70px); /* Calculate remaining height after header */
    display: flex;
    flex-direction: column;
}

.chart-container {
    position: relative;
    height: 250px; /* Fixed height for chart */
    width: 100%;
    flex-shrink: 0;
}

.chart-container canvas {
    max-height: 100% !important;
}

.chart-summary {
    border-top: 1px solid var(--border-color);
    padding-top: 12px; /* Reduced padding */
    margin-top: 12px; /* Reduced margin */
    flex-shrink: 0;
}

.chart-legend {
    margin-top: 12px; /* Reduced margin */
    flex-shrink: 0;
    max-height: 120px; /* Limit legend height */
    overflow-y: auto;
}

.legend-color {
    width: 10px; /* Smaller legend color */
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ===== DOUGHNUT CHART SPECIAL HANDLING ===== */
.doughnut-chart-container {
    position: relative;
    height: 200px; /* Smaller height for doughnut */
    width: 100%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doughnut-chart-container canvas {
    max-height: 100% !important;
    max-width: 100% !important;
}

/* ===== ACTIVITY CARD ===== */
.activity-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
    height: 450px; /* Fixed height matching charts */
}

.activity-header {
    background: var(--background-light);
    padding: 16px 20px; /* Reduced padding */
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-shrink: 0;
}

.activity-title-section {
    flex: 1;
}

.activity-title {
    font-size: 16px; /* Reduced font size */
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
}

.activity-subtitle {
    font-size: 13px; /* Reduced font size */
    color: var(--text-muted);
    margin: 0;
}

.activity-body {
    padding: 0;
    height: calc(100% - 70px); /* Calculate remaining height */
    overflow: hidden;
}

.activity-list {
    height: 100%;
    overflow-y: auto;
    padding: 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px; /* Reduced gap */
    padding: 12px 20px; /* Reduced padding */
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: var(--background-light);
}

.activity-icon {
    width: 36px; /* Smaller icon */
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px; /* Smaller font */
    color: white;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-main {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 6px; /* Reduced margin */
}

.activity-text {
    font-size: 13px; /* Reduced font size */
    color: var(--text-dark);
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.activity-badge {
    font-size: 10px; /* Smaller badge */
    padding: 3px 6px;
    border-radius: 10px;
    flex-shrink: 0;
}

.activity-meta {
    display: flex;
    gap: 12px;
    font-size: 11px; /* Smaller font */
    color: var(--text-muted);
}

.activity-time, .activity-user {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== QUICK ACTIONS CARD ===== */
.quick-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.quick-actions-header {
    background: var(--background-light);
    padding: 16px 20px; /* Reduced padding */
    border-bottom: 1px solid var(--border-color);
}

.quick-actions-title-section {
    text-align: center;
}

.quick-actions-title {
    font-size: 16px; /* Reduced font size */
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-actions-subtitle {
    font-size: 13px; /* Reduced font size */
    color: var(--text-muted);
    margin: 0;
}

.quick-actions-body {
    padding: 20px; /* Reduced padding */
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px; /* Reduced gap */
}

.quick-action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px; /* Reduced gap */
    padding: 16px 12px; /* Reduced padding */
    border: 2px solid var(--border-color);
    border-radius: 10px;
    text-decoration: none;
    color: var(--text-dark);
    transition: var(--transition);
    position: relative;
    background: white;
}

.quick-action-item:hover {
    border-color: var(--primary-blue);
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
    color: var(--text-dark);
    text-decoration: none;
}

.quick-action-icon {
    width: 40px; /* Smaller icon */
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.quick-action-text {
    font-size: 12px; /* Smaller text */
    font-weight: 600;
    text-align: center;
    line-height: 1.2;
}

.quick-action-count {
    font-size: 10px; /* Smaller count */
    color: var(--text-muted);
    margin-top: 2px;
    text-align: center;
}

.quick-action-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: var(--danger-color);
    color: white;
    font-size: 9px;
    font-weight: 600;
    padding: 2px 5px;
    border-radius: 8px;
    min-width: 16px;
    text-align: center;
    line-height: 1.2;
}

/* ===== SUMMARY CARD ===== */
.summary-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
    margin-top: 20px; /* Reduced margin */
}

.summary-header {
    background: var(--background-light);
    padding: 14px 18px; /* Reduced padding */
    border-bottom: 1px solid var(--border-color);
}

.summary-title {
    margin: 0;
    font-size: 15px; /* Reduced font size */
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.summary-body {
    padding: 16px; /* Reduced padding */
}

.summary-item {
    margin-bottom: 12px; /* Reduced margin */
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

.summary-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.summary-item strong {
    display: block;
    font-size: 11px; /* Smaller font */
    color: var(--text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-item span {
    font-size: 14px; /* Smaller font */
    font-weight: 600;
    color: var(--text-dark);
}

.summary-item small {
    font-size: 11px; /* Smaller font */
    color: var(--text-muted);
    margin-left: 6px;
}

/* ===== PROGRESS BAR ===== */
.progress {
    background-color: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    height: 5px; /* Smaller height */
}

.progress-bar {
    background-color: var(--primary-blue);
    transition: width 0.6s ease;
}

/* ===== BADGES ===== */
.badge {
    font-size: 10px; /* Smaller font */
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 5px;
}

.bg-primary { background-color: var(--primary-blue) !important; }
.bg-success { background-color: var(--success-color) !important; }
.bg-warning { background-color: var(--warning-color) !important; }
.bg-info { background-color: var(--info-color) !important; }
.bg-secondary { background-color: #6b7280 !important; }
.bg-dark { background-color: #374151 !important; }

/* ===== BUTTONS ===== */
.btn {
    padding: 6px 12px; /* Smaller padding */
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px; /* Smaller font */
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    text-decoration: none;
}

.btn-sm {
    padding: 4px 8px; /* Smaller padding */
    font-size: 11px;
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

.btn-outline-primary {
    background: transparent;
    color: var(--primary-blue);
    border: 1px solid var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
}

/* ===== ANIMATIONS ===== */
.stats-number.updating {
    animation: pulse 1s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.fade-in {
    animation: fadeIn 0.6s ease-out forwards;
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .chart-container {
        height: 220px; /* Smaller on medium screens */
    }
    
    .doughnut-chart-container {
        height: 180px;
    }
}

@media (max-width: 768px) {
    .welcome-content-wrapper {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .welcome-main {
        flex-direction: column;
        text-align: center;
    }
    
    .welcome-meta {
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }
    
    .welcome-actions {
        flex-direction: row;
        gap: 12px;
        justify-content: center;
    }
    
    .btn-welcome-primary,
    .btn-welcome-secondary {
        min-width: 140px;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .quick-action-item {
        flex-direction: row;
        text-align: left;
        padding: 14px;
    }
    
    .chart-card,
    .activity-card {
        height: 380px; /* Smaller height on mobile */
    }
    
    .chart-container {
        height: 200px; /* Smaller chart on mobile */
    }
    
    .doughnut-chart-container {
        height: 160px;
    }
}

@media (max-width: 576px) {
    .chart-card,
    .activity-card {
        height: 350px; /* Even smaller on very small screens */
    }
    
    .chart-container {
        height: 180px;
    }
    
    .doughnut-chart-container {
        height: 140px;
    }
}

/* ===== SCROLLBAR STYLING ===== */
.activity-list::-webkit-scrollbar {
    width: 3px;
}

.activity-list::-webkit-scrollbar-track {
    background: var(--background-light);
}

.activity-list::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
});

function updateCurrentTime() {
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        
        const timeString = now.toLocaleTimeString('id-ID', options);
        timeElement.textContent = timeString + ' WIB';
        
        timeElement.style.opacity = '0.7';
        setTimeout(() => {
            timeElement.style.opacity = '1';
        }, 100);
    }
}

function initializeDashboard() {
    const mutasiData = @json($chartData['monthly_mutasi'] ?? array_fill(0, 12, 0));
    const verificationData = @json($chartData['verification_progress'] ?? []);
    
    initMutasiTrendChart(mutasiData);
    initVerificationChart(verificationData);
    animateElements();
}

function initMutasiTrendChart(data) {
    const ctx = document.getElementById('mutasiTrendChart');
    if (!ctx) return;
    
    if (window.mutasiChart) {
        window.mutasiChart.destroy();
    }
    
    window.mutasiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Mutasi per Bulan',
                data: data,
                backgroundColor: [
                    'rgba(37, 99, 235, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)', 'rgba(6, 182, 212, 0.8)', 'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)', 'rgba(34, 197, 94, 0.8)', 'rgba(251, 146, 60, 0.8)',
                    'rgba(168, 85, 247, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'
                ],
                borderColor: [
                    '#2563eb', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6',
                    '#ec4899', '#22c55e', '#fb923c', '#a855f7', '#3b82f6', '#10b981'
                ],
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    callbacks: {
                        title: function(context) {
                            return 'Bulan ' + context[0].label + ' {{ date("Y") }}';
                        },
                        label: function(context) {
                            return 'Total Mutasi: ' + context.parsed.y + ' orang';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6b7280',
                        callback: function(value) {
                            return value + ' orang';
                        }
                    },
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)'
                    }
                },
                x: {
                    ticks: { color: '#6b7280' },
                    grid: { display: false }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
}

function initVerificationChart(data) {
    const ctx = document.getElementById('verificationStatusChart');
    if (!ctx || !data.length) return;
    
    if (window.verificationChart) {
        window.verificationChart.destroy();
    }
    
    window.verificationChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(item => item.label),
            datasets: [{
                data: data.map(item => item.value),
                backgroundColor: data.map(item => item.color),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverBorderWidth: 5,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return [
                                'Jumlah: ' + context.parsed + ' dokumen',
                                'Persentase: ' + percentage + '%'
                            ];
                        }
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
}

function refreshChartData() {
    const refreshBtn = event.target;
    const originalHTML = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1 fa-spin"></i> Loading...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        refreshBtn.innerHTML = originalHTML;
        refreshBtn.disabled = false;
        location.reload();
    }, 1500);
}

function refreshActivities() {
    location.reload();
}

function animateElements() {
    const cards = document.querySelectorAll('.stats-card, .chart-card, .activity-card, .quick-actions-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
}

window.addEventListener('resize', function() {
    clearTimeout(window.resizeTimeout);
    window.resizeTimeout = setTimeout(function() {
        if (window.mutasiChart) window.mutasiChart.resize();
        if (window.verificationChart) window.verificationChart.resize();
    }, 300);
});

console.log('Dashboard simplified loaded successfully');
</script>
@endpush