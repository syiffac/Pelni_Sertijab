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
                <a href="{{ route('abk.mutasi.create') }}" class="btn btn-success">
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
    <div class="row stats-cards mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-value">{{ number_format($totalStatistik['total_abk']) }}</h3>
                            <p class="card-label">Total ABK</p>
                        </div>
                        <div class="card-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-value">{{ number_format($totalStatistik['abk_aktif']) }}</h3>
                            <p class="card-label">ABK Aktif</p>
                        </div>
                        <div class="card-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-value">{{ number_format($totalStatistik['total_kapal']) }}</h3>
                            <p class="card-label">Total Kapal</p>
                        </div>
                        <div class="card-icon">
                            <i class="bi bi-ship"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-value">{{ number_format($totalStatistik['total_jabatan']) }}</h3>
                            <p class="card-label">Total Jabatan</p>
                        </div>
                        <div class="card-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- ABK per Kapal Cards -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ship"></i>
                        Data ABK per Kapal
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
                                                <h6 class="kapal-name">{{ $data['kapal']->nama_kapal }}</h6>
                                                <span class="kapal-code">{{ $data['kapal']->kode_kapal ?? 'N/A' }}</span>
                                            </div>
                                            <div class="kapal-actions">
                                                <a href="{{ route('abk.kapal', $data['kapal']->id_kapal) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('abk.mutasi.create', $data['kapal']->id_kapal) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="kapal-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $data['total_abk'] }}</span>
                                                <span class="stat-label">Total ABK</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ $data['abk_aktif'] }}</span>
                                                <span class="stat-label">Aktif</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-warning">{{ $data['abk_tidak_aktif'] }}</span>
                                                <span class="stat-label">Non-Aktif</span>
                                            </div>
                                        </div>
                                        
                                        @if($data['total_abk'] > 0)
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: {{ ($data['abk_aktif'] / $data['total_abk']) * 100 }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ number_format(($data['abk_aktif'] / $data['total_abk']) * 100, 1) }}% ABK Aktif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-ship"></i>
                            <h6>Belum Ada Data Kapal</h6>
                            <p>Silakan tambahkan data kapal terlebih dahulu</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Mutations -->
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
                                        <h6 class="mutation-abk">{{ $mutasi->abkTurun->nama_abk ?? 'N/A' }}</h6>
                                        <div class="mutation-route">
                                            <span class="from-kapal">{{ $mutasi->kapalTurun->nama_kapal ?? 'N/A' }}</span>
                                            <i class="bi bi-arrow-right"></i>
                                            <span class="to-kapal">{{ $mutasi->kapalNaik->nama_kapal ?? 'N/A' }}</span>
                                        </div>
                                        <small class="mutation-date text-muted">
                                            {{ $mutasi->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="mutation-status">
                                        <span class="badge bg-{{ $mutasi->status_mutasi == 'Selesai' ? 'success' : ($mutasi->status_mutasi == 'Proses' ? 'warning' : 'secondary') }}">
                                            {{ $mutasi->status_mutasi }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('monitoring.sertijab') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Mutasi
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-arrow-repeat"></i>
                            <h6>Belum Ada Mutasi</h6>
                            <p>Mutasi akan muncul di sini</p>
                        </div>
                    @endif
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
                            <a href="{{ route('abk.mutasi.create') }}" class="quick-action-card">
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

/* Stats Cards */
.stats-cards .stats-card {
    background: white;
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: relative;
}

.stats-card.bg-primary { background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%) !important; }
.stats-card.bg-success { background: linear-gradient(135deg, #10b981 0%, #34d399 100%) !important; }
.stats-card.bg-warning { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%) !important; }
.stats-card.bg-info { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%) !important; }

.stats-card .card-body {
    padding: 24px;
    color: white;
}

.card-value {
    font-size: 32px;
    font-weight: 800;
    margin: 0;
    line-height: 1;
}

.card-label {
    font-size: 14px;
    margin: 4px 0 0 0;
    opacity: 0.9;
}

.card-icon {
    font-size: 32px;
    opacity: 0.3;
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state h6 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #4b5563;
}

.empty-state p {
    font-size: 14px;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: stretch;
    }
    
    .header-actions .btn {
        flex: 1;
    }
    
    .kapal-stats {
        flex-direction: column;
        gap: 8px;
    }
    
    .stat-item {
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .quick-action-card {
        flex-direction: column;
        text-align: center;
        padding: 16px;
    }
}

/* Scrollbar */
.mutation-list::-webkit-scrollbar {
    width: 4px;
}

.mutation-list::-webkit-scrollbar-track {
    background: transparent;
}

.mutation-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.mutation-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animations */
.stats-card,
.kapal-card,
.quick-action-card {
    animation: fadeInUp 0.3s ease-out;
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
</style>
@endsection