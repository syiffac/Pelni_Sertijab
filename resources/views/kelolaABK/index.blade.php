{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaABK\dataABK.blade.php --}}
@extends('layouts.app')

@section('title', 'Data ABK - SertijabPELNI')

@section('content')
<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">
                    <i class="bi bi-people-fill"></i>
                    Data ABK
                </h1>
                <p class="page-subtitle">Kelola data Anak Buah Kapal dan mutasi per kapal</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('abk.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i>
                    Tambah ABK
                </a>
                <a href="{{ route('mutasi.create') }}" class="btn btn-success">
                    <i class="bi bi-arrow-repeat"></i>
                    Buat Mutasi
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download"></i>
                        Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('abk.export.pdf') }}">
                            <i class="bi bi-file-pdf"></i> Export PDF
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('abk.export.excel') }}">
                            <i class="bi bi-file-excel"></i> Export Excel
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="statistics-section mb-4">
        <!-- Row 1: 2 Cards Besar - Total ABK & ABK Aktif -->
        <div class="row mb-3">
            <!-- Total ABK -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stats-number">{{ number_format($totalStatistik['total_abk']) }}</div>
                            <div class="stats-label">Total ABK</div>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ABK Aktif -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stats-number">{{ number_format($totalStatistik['abk_aktif']) }}</div>
                            <div class="stats-label">ABK Aktif</div>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Row 2: 3 Cards Kecil - ABK Organik, Non Organik, Pensiun -->
        <div class="row">
            <!-- ABK Organik -->
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="card stats-card stats-card-small">
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stats-number">{{ number_format($totalStatistik['abk_organik']) }}</div>
                            <div class="stats-label">ABK Organik</div>
                        </div>
                        <div class="stats-icon bg-info">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ABK Non Organik -->
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="card stats-card stats-card-small">
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stats-number">{{ number_format($totalStatistik['abk_non_organik']) }}</div>
                            <div class="stats-label">ABK Non Organik</div>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ABK Pensiun -->
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="card stats-card stats-card-small">
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stats-number">{{ number_format($totalStatistik['abk_pensiun']) }}</div>
                            <div class="stats-label">ABK Pensiun</div>
                        </div>
                        <div class="stats-icon bg-secondary">
                            <i class="bi bi-person-x-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- ABK per Kapal Cards - UPDATE INI -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ship"></i>
                        Data Mutasi per Kapal
                    </h5>
                </div>
                <div class="card-body">
                    @if($abkPerKapal->count() > 0)
                        <div class="row">
                            @foreach($abkPerKapal as $data)
                                <div class="col-xl-6 col-lg-12 col-md-6 mb-3">
                                    <div class="kapal-card">
                                        <div class="kapal-header">
                                            <div class="kapal-info">
                                                <h6 class="kapal-name">{{ $data['nama_kapal'] ?? 'Kapal Tidak Ditemukan' }}</h6>
                                                <small class="kapal-id text-muted">ID: {{ $data['id'] }}</small>
                                            </div>
                                            <div class="kapal-actions">
                                                <a href="{{ route('mutasi.create', ['kapal_id' => $data['id']]) }}" 
                                                   class="btn btn-sm btn-outline-success"
                                                   title="Buat Mutasi">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </a>
                                                <a href="{{ route('monitoring.index') }}?kapal={{ $data['id'] }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Monitor Mutasi">
                                                    <i class="bi bi-graph-up-arrow"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="kapal-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $data['total_abk'] ?? 0 }}</span>
                                                <span class="stat-label">Total Mutasi</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ $data['abk_aktif'] ?? 0 }}</span>
                                                <span class="stat-label">Selesai</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-warning">{{ $data['abk_tidak_aktif'] ?? 0 }}</span>
                                                <span class="stat-label">Proses</span>
                                            </div>
                                        </div>
                                        
                                        @if(($data['total_abk'] ?? 0) > 0)
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: {{ (($data['abk_aktif'] ?? 0) / ($data['total_abk'] ?? 1)) * 100 }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ number_format((($data['abk_aktif'] ?? 0) / ($data['total_abk'] ?? 1)) * 100, 1) }}% Mutasi Selesai
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <!-- Tambahan info kapal -->
                                        <div class="kapal-footer mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event"></i>
                                                    Aktivitas Terakhir
                                                </small>
                                                <a href="{{ route('mutasi.index') }}?kapal={{ $data['id'] }}" 
                                                   class="btn btn-xs btn-link text-primary">
                                                    Lihat Detail <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-ship"></i>
                            <h6>Belum Ada Data Mutasi</h6>
                            <p>Data akan muncul setelah ada mutasi ABK pada kapal</p>
                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-repeat"></i>
                                Buat Mutasi Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Mutations - UPDATE INI -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-repeat"></i>
                        Mutasi Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if($mutasiTerbaru->count() > 0)
                        <div class="mutation-list">
                            @foreach($mutasiTerbaru as $mutasi)
                                <div class="mutation-item">
                                    <div class="mutation-info">
                                        <h6 class="mutation-abk">{{ $mutasi['abkTurun']['nama_abk'] ?? 'N/A' }}</h6>
                                        <div class="mutation-route">
                                            <span class="from-kapal">
                                                <i class="bi bi-arrow-right-circle text-muted"></i>
                                                {{ $mutasi['kapalNaik']['nama_kapal'] ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="mutation-details mt-1">
                                            <small class="text-info">
                                                <i class="bi bi-calendar"></i>
                                                TMT: {{ $mutasi['periode'] }}
                                            </small>
                                            <small class="text-muted ms-2">
                                                <i class="bi bi-clock"></i>
                                                {{ $mutasi['created_at']->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="mutation-status">
                                        @php
                                            $statusClass = match($mutasi['status_mutasi']) {
                                                'Selesai' => 'bg-success',
                                                'Disetujui' => 'bg-primary',
                                                'Draft' => 'bg-secondary',
                                                'Ditolak' => 'bg-danger',
                                                default => 'bg-warning'
                                            };
                                            
                                            $jenisClass = match($mutasi['jenis_mutasi'] ?? '') {
                                                'Definitif' => 'text-success',
                                                'Sementara' => 'text-info',
                                                default => 'text-muted'
                                            };
                                        @endphp
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="badge {{ $statusClass }} mb-1">
                                                {{ $mutasi['status_mutasi'] ?? 'Pending' }}
                                            </span>
                                            @if(!empty($mutasi['jenis_mutasi']))
                                                <small class="{{ $jenisClass }}">
                                                    <i class="bi bi-tag"></i>
                                                    {{ $mutasi['jenis_mutasi'] }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('mutasi.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                                Lihat Semua Mutasi
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-arrow-repeat"></i>
                            <h6>Belum Ada Mutasi</h6>
                            <p>Mutasi akan muncul di sini setelah dibuat</p>
                            <a href="{{ route('mutasi.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i>
                                Buat Mutasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ABK Table dengan Search dan Pagination - UPDATED -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people-fill"></i>
                            Data ABK
                            <span class="badge bg-primary ms-2" id="totalCount">{{ $abkList->total() }}</span>
                        </h5>
                        <div class="d-flex gap-2 align-items-center">
                            <!-- Search Box -->
                            <div class="search-container">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                        class="form-control" 
                                        id="searchABK" 
                                        placeholder="Cari NRP, nama, jabatan, status..."
                                        maxlength="50"
                                        value="{{ $search }}">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="clearSearch"
                                            title="Hapus pencarian">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="btn-group">
                                <a href="{{ route('abk.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-person-plus"></i>
                                    Tambah ABK
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('abk.export') }}?format=excel">
                                        <i class="bi bi-file-excel"></i> Export Excel
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('abk.export') }}?format=pdf">
                                        <i class="bi bi-file-pdf"></i> Export PDF
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('abk.export') }}">
                                        <i class="bi bi-download"></i> Export & Import
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Search Loading -->
                    <div id="searchLoading" class="search-loading-overlay" style="display: none;">
                        <div class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <small class="text-muted ms-2">Mencari data...</small>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div id="tableContainer">
                        @include('kelolaABK.partials.abk-table', ['abkList' => $abkList, 'search' => $search])
                    </div>

                    <!-- Pagination Container -->
                    <div class="card-footer bg-light" id="paginationContainer">
                        @include('kelolaABK.partials.pagination', ['abkList' => $abkList, 'search' => $search])
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning"></i>
                            Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <a href="{{ route('abk.create') }}" class="quick-action-card">
                                    <div class="action-icon bg-primary">
                                        <i class="bi bi-person-plus"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>Tambah ABK Baru</h6>
                                        <p>Daftarkan ABK baru ke sistem</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 mb-3">
                                <a href="{{ route('mutasi.create') }}" class="quick-action-card">
                                    <div class="action-icon bg-success">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>Buat Mutasi</h6>
                                        <p>Mutasi ABK antar kapal</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 mb-3">
                                <a href="{{ route('monitoring.sertijab') }}" class="quick-action-card">
                                    <div class="action-icon bg-info">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>Monitoring</h6>
                                        <p>Monitor status mutasi</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 mb-3">
                                <a href="{{ route('abk.export') }}" class="quick-action-card">
                                    <div class="action-icon bg-warning">
                                        <i class="bi bi-download"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6>Export Data</h6>
                                        <p>Download laporan ABK</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus ABK
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="alert alert-warning d-inline-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span>Tindakan ini tidak dapat dibatalkan!</span>
                    </div>
                </div>
                <p class="mb-3">Apakah Anda yakin ingin menghapus ABK berikut?</p>
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-danger rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold" id="deleteName">Nama ABK</h6>
                                <small class="text-muted">ID: <span id="deleteId">-</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Ya, Hapus ABK
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Data ABK Styles */
.dashboard-container {
    padding: 24px;
    background: #f8fafc;
    min-height: calc(100vh - 70px);
}

.page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #2A3F8E;
}

.page-subtitle {
    color: #6b7280;
    margin: 4px 0 0 0;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* STATISTICS SECTION */
.statistics-section {
    margin-bottom: 32px;
}

.statistics-section .row {
    margin-left: 0;
    margin-right: 0;
}

.statistics-section .row > [class*="col-"] {
    padding-left: 12px;
    padding-right: 12px;
}

/* STATISTICS CARDS - 2 Cards Besar di Atas */
.stats-card {
    background: white;
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    min-height: 140px;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.stats-card .card-body {
    padding: 32px 28px;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
}

.stats-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stats-number {
    font-size: 42px;
    font-weight: 900;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
}

.stats-label {
    font-size: 16px;
    color: #6b7280;
    font-weight: 600;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-icon {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* STATISTICS CARDS SMALL - 3 Cards Kecil di Bawah */
.stats-card-small {
    min-height: 110px;
}

.stats-card-small .card-body {
    padding: 24px 20px;
}

.stats-card-small .stats-number {
    font-size: 32px;
    margin-bottom: 6px;
}

.stats-card-small .stats-label {
    font-size: 13px;
}

.stats-card-small .stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Icon Colors */
.stats-icon.bg-primary {
    background: linear-gradient(135deg, #2A3F8E 0%, #4f46e5 50%, #3b82f6 100%);
}

.stats-icon.bg-success {
    background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
}

.stats-icon.bg-info {
    background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 50%, #38bdf8 100%);
}

.stats-icon.bg-warning {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%);
}

.stats-icon.bg-secondary {
    background: linear-gradient(135deg, #4b5563 0%, #6b7280 50%, #9ca3af 100%);
}

/* ABK Table Styles */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.table {
    margin-bottom: 0;
    font-size: 14px;
    background: white;
}

.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border-top: none;
}

.table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f9fafb;
    transform: translateX(2px);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border-top: none;
    background: white;
}

/* Avatar Styles */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-md {
    width: 40px;
    height: 40px;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Badge Enhancements */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-light {
    color: #6b7280 !important;
    background-color: #f3f4f6 !important;
    border: 1px solid #e5e7eb;
}

/* Button Group Enhancements */
.btn-group .btn {
    padding: 6px 10px;
    font-size: 12px;
    border-radius: 6px;
}

.btn-group .btn:first-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.btn-group .btn:last-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: none;
}

/* Enhanced Button Group untuk Edit dan Delete */
.btn-group .btn-outline-warning:hover {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.btn-group .btn-outline-danger:hover {
    background-color: #ef4444;
    border-color: #ef4444;
    color: white;
}

/* Modal Enhancements */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 24px 24px 0 24px;
}

.modal-body {
    padding: 16px 24px;
}

.modal-footer {
    padding: 0 24px 24px 24px;
}

.modal-title {
    font-weight: 600;
    color: #1f2937;
}

/* Alert dalam modal */
.modal-body .alert-warning {
    border: none;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-radius: 8px;
    font-size: 13px;
    padding: 8px 12px;
}

/* Card info ABK dalam modal */
.modal-body .card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.modal-body .card .avatar-md {
    width: 48px;
    height: 48px;
}

/* Button loading state */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Enhanced Card Header */
.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 24px;
}

.card-header .card-title {
    color: #1f2937;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header .card-title i {
    color: #2A3F8E;
    font-size: 18px;
}

/* Status Indicators */
.fw-semibold {
    font-weight: 600 !important;
}

.fw-medium {
    font-weight: 500 !important;
}

/* Kapal Cards */
.kapal-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    height: 100%;
}

.kapal-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #2A3F8E;
}

.kapal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.kapal-name {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.kapal-code {
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 4px;
}

.kapal-actions {
    display: flex;
    gap: 4px;
}

.kapal-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 11px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-container {
    margin-top: 12px;
}

.progress {
    height: 6px;
    border-radius: 3px;
    background: #f3f4f6;
    margin-bottom: 4px;
}

/* Mutation List */
.mutation-list {
    max-height: 400px;
    overflow-y: auto;
}

.mutation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.mutation-item:last-child {
    border-bottom: none;
}

.mutation-abk {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.mutation-route {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 2px;
}

.mutation-route i {
    font-size: 10px;
}

.mutation-date {
    font-size: 11px;
}

/* Quick Actions */
.quick-action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.action-icon.bg-primary {
    background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%);
}

.action-icon.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.action-icon.bg-info {
    background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
}

.action-icon.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.action-content h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.action-content p {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
}

/* Enhanced Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
    margin: 20px 0;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.7;
    color: #cbd5e1;
}

.empty-state h6 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #6b7280;
}

.empty-state p {
    color: #9ca3af;
    margin-bottom: 20px;
    line-height: 1.6;
}

.empty-state .btn {
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
    transition: all 0.2s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(42, 63, 142, 0.4);
}

/* Search Info Alert */
.search-info-alert {
    margin: 16px 24px 0 24px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
}

.search-info-alert.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.search-info-alert.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Row Hover Enhancement */
.abk-row {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.abk-row:hover {
    background-color: #f8fafc;
    border-left-color: #2A3F8E;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Row Animation */
.abk-row {
    opacity: 0;
    transform: translateY(20px);
    animation: rowFadeIn 0.3s ease-out forwards;
}

.abk-row:nth-child(1) { animation-delay: 0.1s; }
.abk-row:nth-child(2) { animation-delay: 0.15s; }
.abk-row:nth-child(3) { animation-delay: 0.2s; }
.abk-row:nth-child(4) { animation-delay: 0.25s; }
.abk-row:nth-child(5) { animation-delay: 0.3s; }

@keyframes rowFadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge Enhancement for NRP */
.badge.bg-light {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151 !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Button Group Enhancement */
.btn-group .btn-sm {
    padding: 6px 10px;
    font-size: 12px;
    transition: all 0.2s ease;
}

.btn-group .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Loading Button State */
.btn.loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Search Loading Overlay - Enhanced */
.search-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    z-index: 10;
    backdrop-filter: blur(3px);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-loading-overlay .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
}

/* Enhanced Search Container */
.search-container {
    min-width: 350px;
    position: relative;
}

.search-container .input-group {
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.search-container .input-group:focus-within {
    border-color: #2A3F8E;
    box-shadow: 0 3px 15px rgba(42, 63, 142, 0.2);
}

.search-container .input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    color: #6b7280;
    font-size: 14px;
    padding: 10px 15px;
}

.search-container .form-control {
    border: none;
    padding: 10px 15px;
    font-size: 14px;
    background: white;
    transition: all 0.2s ease;
}

.search-container .form-control:focus {
    box-shadow: none;
    background: white;
    outline: none;
}

.search-container .form-control::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.search-container .btn {
    border: none;
    padding: 10px 12px;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.2s ease;
}

.search-container .btn:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.05);
}

/* Loading State untuk Search Input */
.search-container .form-control.loading {
    background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    background-size: 200% 100%;
    animation: loading-shimmer 1.5s ease-in-out infinite;
}

@keyframes loading-shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Enhanced Pagination */
.pagination-wrapper .pagination {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: white;
}

.pagination-wrapper .page-link {
    border: none;
    padding: 10px 15px;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s ease;
    background: transparent;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(42, 63, 142, 0.3);
}

.pagination-wrapper .page-link:hover:not(.active) {
    background: #f3f4f6;
    color: #2A3F8E;
    transform: translateY(-1px);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: transparent;
    color: #cbd5e1;
    cursor: not-allowed;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
    transform: none;
    background: transparent;
}

/* Per Page Selector Enhancement */
.per-page-selector .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    padding: 8px 35px 8px 12px;
    color: #6b7280;
    background-color: white;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.per-page-selector .form-select:focus {
    border-color: #2A3F8E;
    box-shadow: 0 0 0 0.2rem rgba(42, 63, 142, 0.25);
    outline: none;
}

.per-page-selector .form-select:hover {
    border-color: #9ca3af;
}

/* Pagination Info Enhancement */
.pagination-info {
    font-size: 13px;
    color: #6b7280;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

/* Enhanced Card Footer */
.card-footer {
    border-top: 1px solid #e2e8f0;
    padding: 20px 24px;
    margin: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

/* Table Container Animation */
#tableContainer {
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
}

#tableContainer.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
    margin: 20px 0;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.7;
    color: #cbd5e1;
}

.empty-state h6 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #6b7280;
}

.empty-state p {
    color: #9ca3af;
    margin-bottom: 20px;
    line-height: 1.6;
}

.empty-state .btn {
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
    transition: all 0.2s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(42, 63, 142, 0.4);
}

/* Search Info Alert */
.search-info-alert {
    margin: 16px 24px 0 24px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
}

.search-info-alert.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.search-info-alert.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Row Hover Enhancement */
.abk-row {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.abk-row:hover {
    background-color: #f8fafc;
    border-left-color: #2A3F8E;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Row Animation */
.abk-row {
    opacity: 0;
    transform: translateY(20px);
    animation: rowFadeIn 0.3s ease-out forwards;
}

.abk-row:nth-child(1) { animation-delay: 0.1s; }
.abk-row:nth-child(2) { animation-delay: 0.15s; }
.abk-row:nth-child(3) { animation-delay: 0.2s; }
.abk-row:nth-child(4) { animation-delay: 0.25s; }
.abk-row:nth-child(5) { animation-delay: 0.3s; }

@keyframes rowFadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge Enhancement for NRP */
.badge.bg-light {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151 !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Button Group Enhancement */
.btn-group .btn-sm {
    padding: 6px 10px;
    font-size: 12px;
    transition: all 0.2s ease;
}

.btn-group .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Loading Button State */
.btn.loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Search Loading Overlay - Enhanced */
.search-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    z-index: 10;
    backdrop-filter: blur(3px);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-loading-overlay .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
}

/* Enhanced Search Container */
.search-container {
    min-width: 350px;
    position: relative;
}

.search-container .input-group {
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.search-container .input-group:focus-within {
    border-color: #2A3F8E;
    box-shadow: 0 3px 15px rgba(42, 63, 142, 0.2);
}

.search-container .input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    color: #6b7280;
    font-size: 14px;
    padding: 10px 15px;
}

.search-container .form-control {
    border: none;
    padding: 10px 15px;
    font-size: 14px;
    background: white;
    transition: all 0.2s ease;
}

.search-container .form-control:focus {
    box-shadow: none;
    background: white;
    outline: none;
}

.search-container .form-control::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.search-container .btn {
    border: none;
    padding: 10px 12px;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.2s ease;
}

.search-container .btn:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.05);
}

/* Loading State untuk Search Input */
.search-container .form-control.loading {
    background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    background-size: 200% 100%;
    animation: loading-shimmer 1.5s ease-in-out infinite;
}

@keyframes loading-shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Enhanced Pagination */
.pagination-wrapper .pagination {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: white;
}

.pagination-wrapper .page-link {
    border: none;
    padding: 10px 15px;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s ease;
    background: transparent;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(42, 63, 142, 0.3);
}

.pagination-wrapper .page-link:hover:not(.active) {
    background: #f3f4f6;
    color: #2A3F8E;
    transform: translateY(-1px);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: transparent;
    color: #cbd5e1;
    cursor: not-allowed;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
    transform: none;
    background: transparent;
}

/* Per Page Selector Enhancement */
.per-page-selector .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    padding: 8px 35px 8px 12px;
    color: #6b7280;
    background-color: white;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.per-page-selector .form-select:focus {
    border-color: #2A3F8E;
    box-shadow: 0 0 0 0.2rem rgba(42, 63, 142, 0.25);
    outline: none;
}

.per-page-selector .form-select:hover {
    border-color: #9ca3af;
}

/* Pagination Info Enhancement */
.pagination-info {
    font-size: 13px;
    color: #6b7280;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

/* Enhanced Card Footer */
.card-footer {
    border-top: 1px solid #e2e8f0;
    padding: 20px 24px;
    margin: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

/* Table Container Animation */
#tableContainer {
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
}

#tableContainer.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
    margin: 20px 0;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.7;
    color: #cbd5e1;
}

.empty-state h6 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #6b7280;
}

.empty-state p {
    color: #9ca3af;
    margin-bottom: 20px;
    line-height: 1.6;
}

.empty-state .btn {
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
    transition: all 0.2s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(42, 63, 142, 0.4);
}

/* Search Info Alert */
.search-info-alert {
    margin: 16px 24px 0 24px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
}

.search-info-alert.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.search-info-alert.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Row Hover Enhancement */
.abk-row {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.abk-row:hover {
    background-color: #f8fafc;
    border-left-color: #2A3F8E;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Row Animation */
.abk-row {
    opacity: 0;
    transform: translateY(20px);
    animation: rowFadeIn 0.3s ease-out forwards;
}

.abk-row:nth-child(1) { animation-delay: 0.1s; }
.abk-row:nth-child(2) { animation-delay: 0.15s; }
.abk-row:nth-child(3) { animation-delay: 0.2s; }
.abk-row:nth-child(4) { animation-delay: 0.25s; }
.abk-row:nth-child(5) { animation-delay: 0.3s; }

@keyframes rowFadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge Enhancement for NRP */
.badge.bg-light {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151 !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Button Group Enhancement */
.btn-group .btn-sm {
    padding: 6px 10px;
    font-size: 12px;
    transition: all 0.2s ease;
}

.btn-group .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Loading Button State */
.btn.loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Search Loading Overlay - Enhanced */
.search-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    z-index: 10;
    backdrop-filter: blur(3px);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-loading-overlay .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
}

/* Enhanced Search Container */
.search-container {
    min-width: 350px;
    position: relative;
}

.search-container .input-group {
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.search-container .input-group:focus-within {
    border-color: #2A3F8E;
    box-shadow: 0 3px 15px rgba(42, 63, 142, 0.2);
}

.search-container .input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    color: #6b7280;
    font-size: 14px;
    padding: 10px 15px;
}

.search-container .form-control {
    border: none;
    padding: 10px 15px;
    font-size: 14px;
    background: white;
    transition: all 0.2s ease;
}

.search-container .form-control:focus {
    box-shadow: none;
    background: white;
    outline: none;
}

.search-container .form-control::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.search-container .btn {
    border: none;
    padding: 10px 12px;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.2s ease;
}

.search-container .btn:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.05);
}

/* Loading State untuk Search Input */
.search-container .form-control.loading {
    background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    background-size: 200% 100%;
    animation: loading-shimmer 1.5s ease-in-out infinite;
}

@keyframes loading-shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Enhanced Pagination */
.pagination-wrapper .pagination {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: white;
}

.pagination-wrapper .page-link {
    border: none;
    padding: 10px 15px;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s ease;
    background: transparent;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(42, 63, 142, 0.3);
}

.pagination-wrapper .page-link:hover:not(.active) {
    background: #f3f4f6;
    color: #2A3F8E;
    transform: translateY(-1px);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: transparent;
    color: #cbd5e1;
    cursor: not-allowed;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
    transform: none;
    background: transparent;
}

/* Per Page Selector Enhancement */
.per-page-selector .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    padding: 8px 35px 8px 12px;
    color: #6b7280;
    background-color: white;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.per-page-selector .form-select:focus {
    border-color: #2A3F8E;
    box-shadow: 0 0 0 0.2rem rgba(42, 63, 142, 0.25);
    outline: none;
}

.per-page-selector .form-select:hover {
    border-color: #9ca3af;
}

/* Pagination Info Enhancement */
.pagination-info {
    font-size: 13px;
    color: #6b7280;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

/* Enhanced Card Footer */
.card-footer {
    border-top: 1px solid #e2e8f0;
    padding: 20px 24px;
    margin: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

/* Table Container Animation */
#tableContainer {
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
}

#tableContainer.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
    margin: 20px 0;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.7;
    color: #cbd5e1;
}

.empty-state h6 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #6b7280;
}

.empty-state p {
    color: #9ca3af;
    margin-bottom: 20px;
    line-height: 1.6;
}

.empty-state .btn {
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
    transition: all 0.2s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(42, 63, 142, 0.4);
}

/* Search Info Alert */
.search-info-alert {
    margin: 16px 24px 0 24px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
}

.search-info-alert.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.search-info-alert.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Row Hover Enhancement */
.abk-row {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.abk-row:hover {
    background-color: #f8fafc;
    border-left-color: #2A3F8E;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Row Animation */
.abk-row {
    opacity: 0;
    transform: translateY(20px);
    animation: rowFadeIn 0.3s ease-out forwards;
}

.abk-row:nth-child(1) { animation-delay: 0.1s; }
.abk-row:nth-child(2) { animation-delay: 0.15s; }
.abk-row:nth-child(3) { animation-delay: 0.2s; }
.abk-row:nth-child(4) { animation-delay: 0.25s; }
.abk-row:nth-child(5) { animation-delay: 0.3s; }

@keyframes rowFadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge Enhancement for NRP */
.badge.bg-light {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151 !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Button Group Enhancement */
.btn-group .btn-sm {
    padding: 6px 10px;
    font-size: 12px;
    transition: all 0.2s ease;
}

.btn-group .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Loading Button State */
.btn.loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// Live Search dan Pagination - PERBAIKAN
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchABK');
    const clearBtn = document.getElementById('clearSearch');
    const searchLoading = document.getElementById('searchLoading');
    const tableContainer = document.getElementById('tableContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const totalCount = document.getElementById('totalCount');
    
    let searchTimeout;
    let currentPage = {{ request('page', 1) }};
    let currentPerPage = {{ request('per_page', 10) }};
    let isSearching = false;
    let currentSearch = '{{ $search }}';

    // Debug logging
    console.log('Search initialized:', {
        currentPage: currentPage,
        currentPerPage: currentPerPage,
        currentSearch: currentSearch
    });

    // Live Search
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            console.log('Search input changed:', query);
            
            searchTimeout = setTimeout(() => {
                if (query !== currentSearch) {
                    performSearch(query, 1); // Reset ke halaman 1 saat search berubah
                }
            }, 500);
        });

        // Enter key search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                console.log('Enter pressed, searching:', query);
                performSearch(query, currentPage);
            }
        });
    }

    // Clear Search
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            console.log('Clear search clicked');
            searchInput.value = '';
            if (currentSearch !== '') {
                performSearch('', 1);
            }
            searchInput.focus();
        });
    }

    // Perform Search Function - PERBAIKAN
    function performSearch(query, page = 1) {
        if (isSearching) {
            console.log('Already searching, skipping...');
            return;
        }
        
        console.log('Starting search:', { query, page, currentPerPage });
        
        isSearching = true;
        showLoading();
        
        // Update current values
        currentSearch = query;
        currentPage = page;
        
        const params = new URLSearchParams();
        
        // Add parameters
        if (query && query.trim() !== '') {
            params.set('search', query.trim());
        }
        params.set('page', page.toString());
        params.set('per_page', currentPerPage.toString());

        const url = `{{ route('abk.index') }}?${params.toString()}`;
        console.log('Fetching URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response received:', response.status, response.headers.get('content-type'));
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON. Content-Type: ' + contentType);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            if (data.success) {
                // Update content
                if (tableContainer && data.html) {
                    tableContainer.innerHTML = data.html;
                }
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                if (totalCount && data.total !== undefined) {
                    totalCount.textContent = data.total;
                }
                
                // Update current values
                currentPage = data.current_page || page;
                
                // Update URL without reload
                updateURL(query, page);
                
                // Animate table rows
                animateTableRows();
                
                // Show search feedback
                showSearchFeedback(query, data.total);
                
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat mencari data');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showError('Terjadi kesalahan saat mencari data: ' + error.message);
        })
        .finally(() => {
            hideLoading();
            isSearching = false;
            console.log('Search completed');
        });
    }

    // Update URL function
    function updateURL(query, page) {
        try {
            const newUrl = new URL(window.location.href);
            
            if (query && query.trim() !== '') {
                newUrl.searchParams.set('search', query.trim());
            } else {
                newUrl.searchParams.delete('search');
            }
            
            newUrl.searchParams.set('page', page.toString());
            newUrl.searchParams.set('per_page', currentPerPage.toString());
            
            // Push state hanya jika URL berubah
            if (newUrl.toString() !== window.location.toString()) {
                window.history.pushState({}, '', newUrl.toString());
            }
        } catch (error) {
            console.error('Error updating URL:', error);
        }
    }

    // Show search feedback
    function showSearchFeedback(query, total) {
        hideSearchInfo(); // Clear existing feedback
        
        if (query && query.trim() !== '') {
            if (total > 0) {
                showSearchInfo(`Ditemukan ${total} ABK untuk pencarian "${query}"`);
            } else {
                showSearchInfo(`Tidak ada hasil untuk pencarian "${query}"`, 'warning');
            }
        }
    }

    // Load Page Function (untuk pagination)
    window.loadPage = function(page) {
        console.log('Loading page:', page);
        if (page !== currentPage && !isSearching) {
            performSearch(currentSearch, page);
        }
    };

    // Change Per Page Function
    window.changePerPage = function(perPage) {
        console.log('Changing per page to:', perPage);
        const newPerPage = parseInt(perPage);
        if (newPerPage !== currentPerPage && !isSearching) {
            currentPerPage = newPerPage;
            performSearch(currentSearch, 1); // Reset ke halaman 1
        }
    };

    // Clear Search Function (global)
    window.clearSearch = function() {
        console.log('Clearing search globally');
        if (searchInput) {
            searchInput.value = '';
        }
        if (currentSearch !== '') {
            performSearch('', 1);
        }
        if (searchInput) {
            searchInput.focus();
        }
    };

    // Show/Hide Loading
    function showLoading() {
        if (searchLoading) {
            searchLoading.style.display = 'block';
        }
        if (tableContainer) {
            tableContainer.style.opacity = '0.6';
            tableContainer.style.pointerEvents = 'none';
        }
        if (paginationContainer) {
            paginationContainer.style.opacity = '0.6';
        }
    }

    function hideLoading() {
        if (searchLoading) {
            searchLoading.style.display = 'none';
        }
        if (tableContainer) {
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
        }
        if (paginationContainer) {
            paginationContainer.style.opacity = '1';
        }
    }

    // Show Search Info
    function showSearchInfo(message, type = 'info') {
        // Remove existing search info
        hideSearchInfo();

        const alertClass = type === 'warning' ? 'alert-warning' : 'alert-info';
        const iconClass = type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show search-info-alert`;
        alertDiv.innerHTML = `
            <i class="${iconClass}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const cardBody = tableContainer?.parentElement;
        if (cardBody) {
            cardBody.insertBefore(alertDiv, tableContainer);
        }
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            if (alertDiv && alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Hide Search Info
    function hideSearchInfo() {
        const searchInfo = document.querySelector('.search-info-alert');
        if (searchInfo) {
            searchInfo.remove();
        }
    }

    // Show Error
    function showError(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const cardHeader = document.querySelector('.card-header');
        if (cardHeader && cardHeader.nextElementSibling) {
            cardHeader.parentElement.insertBefore(alertDiv, cardHeader.nextElementSibling);
        }
        
        setTimeout(() => {
            if (alertDiv && alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Animate Table Rows
    function animateTableRows() {
        const rows = document.querySelectorAll('.abk-row');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function(event) {
        console.log('Popstate event triggered');
        const urlParams = new URLSearchParams(window.location.search);
        const search = urlParams.get('search') || '';
        const page = parseInt(urlParams.get('page')) || 1;
        const perPage = parseInt(urlParams.get('per_page')) || 10;
        
        // Update form values
        if (searchInput) {
            searchInput.value = search;
        }
        currentPerPage = perPage;
        
        // Update per page selector if exists
        const perPageSelect = document.querySelector('.per-page-selector select');
        if (perPageSelect) {
            perPageSelect.value = perPage;
        }
        
        // Perform search without updating URL
        performSearchWithoutURL(search, page);
    });

    // Perform search without updating URL (for popstate)
    function performSearchWithoutURL(query, page = 1) {
        if (isSearching) return;
        
        isSearching = true;
        showLoading();
        
        currentSearch = query;
        currentPage = page;
        
        const params = new URLSearchParams();
        if (query && query.trim() !== '') {
            params.set('search', query.trim());
        }
        params.set('page', page.toString());
        params.set('per_page', currentPerPage.toString());

        fetch(`{{ route('abk.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (tableContainer) tableContainer.innerHTML = data.html;
                if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                if (totalCount) totalCount.textContent = data.total;
                currentPage = data.current_page;
                animateTableRows();
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            hideLoading();
            isSearching = false;
        });
    }

    // Initial animation and setup
    animateTableRows();
    
    // Focus search input on load if there's a search query
    if (currentSearch && searchInput) {
        searchInput.focus();
        showSearchInfo(`Menampilkan hasil pencarian "${currentSearch}"`);
    }
    
    console.log('Search initialization completed');
});

// Delete Confirmation Function
function confirmDelete(id, name) {
    const deleteNameEl = document.getElementById('deleteName');
    const deleteIdEl = document.getElementById('deleteId');
    const deleteForm = document.getElementById('deleteForm');
    
    if (deleteNameEl) deleteNameEl.textContent = name || 'ABK';
    if (deleteIdEl) deleteIdEl.textContent = id;
    if (deleteForm) deleteForm.action = `/abk/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle form submission loading state
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menghapus...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endsection