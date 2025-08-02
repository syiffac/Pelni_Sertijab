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
                                                {{-- Gunakan array syntax, bukan object --}}
                                                <h6 class="kapal-name">{{ $data['nama_kapal'] ?? 'Kapal Tidak Ditemukan' }}</h6>
                                            </div>
                                            <div class="kapal-actions">
                                                {{-- <a href="{{ route('abk.kapal', $data['id'] ?? 0) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a> --}}
                                                <a href="{{ route('abk.mutasi.create', $data['id'] ?? 0) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="kapal-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $data['total_abk'] ?? 0 }}</span>
                                                <span class="stat-label">Total ABK</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ $data['abk_aktif'] ?? 0 }}</span>
                                                <span class="stat-label">Aktif</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-warning">{{ $data['abk_tidak_aktif'] ?? 0 }}</span>
                                                <span class="stat-label">Non-Aktif</span>
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
                                                    {{ number_format((($data['abk_aktif'] ?? 0) / ($data['total_abk'] ?? 1)) * 100, 1) }}% ABK Aktif
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
                                        {{-- Gunakan array syntax --}}
                                        <h6 class="mutation-abk">{{ $mutasi['abkTurun']['nama_abk'] ?? 'N/A' }}</h6>
                                        <div class="mutation-route">
                                            <span class="from-kapal">{{ $mutasi['kapalTurun']['nama_kapal'] ?? 'N/A' }}</span>
                                            <i class="bi bi-arrow-right"></i>
                                            <span class="to-kapal">{{ $mutasi['kapalNaik']['nama_kapal'] ?? 'N/A' }}</span>
                                        </div>
                                        <small class="mutation-date text-muted">
                                            {{ $mutasi['created_at']->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="mutation-status">
                                        <span class="badge bg-{{ $mutasi['status_mutasi'] == 'Selesai' ? 'success' : ($mutasi['status_mutasi'] == 'Proses' ? 'warning' : 'secondary') }}">
                                            {{ $mutasi['status_mutasi'] ?? 'Pending' }}
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

    <!-- ABK Terbaru Table dengan Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history"></i>
                            ABK Terbaru Ditambahkan
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
                                           placeholder="Cari nama, NRP, atau jabatan"
                                           maxlength="50">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="clearSearch"
                                            title="Hapus pencarian">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <a href="{{ route('abk.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($abkList && $abkList->count() > 0)
                        <!-- Search Info -->
                        <div id="searchInfo" class="search-info mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <span id="searchResults">Menampilkan hasil pencarian</span>
                                <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="clearSearchResults()">
                                    <i class="bi bi-x"></i> Hapus Filter
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="abkTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th width="20%">Nama ABK</th>
                                        <th width="15%">NPP</th>
                                        <th width="15%">Jabatan</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Kapal</th>
                                        <th width="10%">Ditambahkan</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="abkTableBody">
                                    @foreach($abkList->take(10) as $index => $abk)
                                        <tr class="abk-row" 
                                            data-nama="{{ strtolower($abk->nama_abk ?? '') }}"

                                            data-npp="{{ strtolower($abk->id ?? '') }}"

                                            data-jabatan="{{ strtolower($abk->jabatanTetap->nama_jabatan ?? '') }}"

                                            data-status="{{ strtolower($abk->status_abk ?? '') }}"

                                            data-kapal="{{ strtolower($abk->kapalAktif->nama_kapal ?? '') }}">
                                            <td data-label="No.">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Nama ABK">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-md bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="bi bi-person-fill text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold highlight-text">{{ $abk->nama_abk ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $abk->email ?? 'Email tidak tersedia' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="NPP">
                                                <span class="badge bg-light text-dark highlight-text">{{ $abk->id ?? 'N/A' }}</span>
                                            </td>
                                            <td data-label="Jabatan">
                                                <span class="fw-medium highlight-text">{{ $abk->jabatanTetap->nama_jabatan ?? 'Tidak ada jabatan' }}</span>
                                            </td>
                                            <td data-label="Status">
                                                @if($abk->status_abk == 'Organik')
                                                    <span class="badge bg-success highlight-text">Organik</span>
                                                @elseif($abk->status_abk == 'Non Organik')
                                                    <span class="badge bg-warning highlight-text">Non Organik</span>
                                                @elseif($abk->status_abk == 'Pensiun')
                                                    <span class="badge bg-secondary highlight-text">Pensiun</span>
                                                @else
                                                    <span class="badge bg-light text-dark highlight-text">{{ $abk->status_abk ?? 'N/A' }}</span>
                                                @endif
                                            </td>
                                            <td data-label="Kapal">
                                                @if(isset($abk->kapalAktif) && $abk->kapalAktif->nama_kapal ?? false)
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-ship text-primary me-2"></i>
                                                        <span class="fw-medium highlight-text">{{ $abk->kapalAktif->nama_kapal }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="bi bi-dash-circle me-1"></i>
                                                        Belum Bertugas
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Ditambahkan">
                                                <small class="text-muted">
                                                    {{ $abk->created_at ? $abk->created_at->diffForHumans() : 'N/A' }}
                                                </small>
                                            </td>
                                            <td data-label="Aksi" class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('abk.edit', $abk->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit ABK">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Hapus ABK"
                                                            onclick="confirmDelete({{ $abk->id }}, '{{ $abk->nama_abk }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- No Results Found -->
                        <div id="noResults" class="empty-state" style="display: none;">
                            <i class="bi bi-search"></i>
                            <h6>Tidak Ada Hasil Ditemukan</h6>
                            <p id="noResultsText">Tidak ada ABK yang sesuai dengan pencarian</p>
                            <button type="button" class="btn btn-primary btn-sm mt-2" onclick="clearSearchResults()">
                                <i class="bi bi-arrow-clockwise"></i>
                                Tampilkan Semua
                            </button>
                        </div>
                        
                        <div id="tableFooter">
                            @if($abkList->count() > 10)
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Menampilkan 10 dari {{ $abkList->count() }} ABK terbaru. 
                                        <a href="{{ route('abk.index') }}" class="text-primary">Lihat semua ABK</a>
                                    </small>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h6>Belum Ada Data ABK</h6>
                            <p>Silakan tambahkan ABK baru untuk mulai mengelola data</p>
                            <a href="{{ route('abk.create') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-person-plus"></i>
                                Tambah ABK Pertama
                            </a>
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
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
    color: #9ca3af;
}

.empty-state h6 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #4b5563;
}

.empty-state p {
    font-size: 14px;
    margin: 0 0 16px 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.5;
}

/* Table Animation */
.table tbody tr {
    animation: slideInUp 0.3s ease-out;
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

/* Staggered animation untuk stats cards */
.statistics-section .row:first-child .col-lg-6:nth-child(1) .stats-card {
    animation-delay: 0.1s;
}

.statistics-section .row:first-child .col-lg-6:nth-child(2) .stats-card {
    animation-delay: 0.2s;
}

.statistics-section .row:last-child .col-lg-4:nth-child(1) .stats-card {
    animation-delay: 0.3s;
}

.statistics-section .row:last-child .col-lg-4:nth-child(2) .stats-card {
    animation-delay: 0.4s;
}

.statistics-section .row:last-child .col-lg-4:nth-child(3) .stats-card {
    animation-delay: 0.5s;
}

/* Search Container */
.search-container {
    min-width: 300px;
}

.search-container .input-group {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    overflow: hidden;
}

.search-container .input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e5e7eb;
    border-right: none;
    color: #6b7280;
}

.search-container .form-control {
    border: 1px solid #e5e7eb;
    border-left: none;
    border-right: none;
    padding: 8px 12px;
    font-size: 13px;
}

.search-container .form-control:focus {
    border-color: #2A3F8E;
    box-shadow: none;
}

.search-container .btn {
    border: 1px solid #e5e7eb;
    border-left: none;
    padding: 8px 10px;
    font-size: 12px;
}

/* Search Info */
.search-info .alert {
    margin-bottom: 0;
    padding: 12px 16px;
    font-size: 13px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

/* Highlight Search Results */
.highlight {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
    color: #92400e;
}

/* Row Animation for Search */
.abk-row {
    transition: all 0.3s ease;
}

.abk-row.searching {
    opacity: 0.3;
    transform: scale(0.98);
}

.abk-row.found {
    opacity: 1;
    transform: scale(1);
    background-color: #f0f9ff;
    border-left: 3px solid #3b82f6;
}

.abk-row.hidden {
    display: none;
}

/* Search Loading */
.search-loading {
    position: relative;
}

.search-loading::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 40px;
    width: 16px;
    height: 16px;
    margin-top: -8px;
    border: 2px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Enhanced Empty State for Search */
.empty-state i.bi-search {
    color: #3b82f6;
}

/* Search Stats */
.search-stats {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #0c4a6e;
}

.search-stats i {
    color: #0ea5e9;
    margin-right: 8px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .statistics-section .row:first-child .col-lg-6 {
        flex: 0 0 auto;
        width: 50%;
    }
    
    .statistics-section .row:last-child .col-lg-4 {
        flex: 0 0 auto;
        width: 33.333%;
    }
}

@media (max-width: 992px) {
    .statistics-section .row:first-child .col-lg-6 {
        flex: 0 0 auto;
        width: 50%;
    }
    
    .statistics-section .row:last-child .col-lg-4 {
        flex: 0 0 auto;
        width: 33.333%;
    }
}

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
    
    .statistics-section .row .col-lg-6,
    .statistics-section .row .col-lg-4 {
        flex: 0 0 auto;
        width: 100%;
    }
    
    .stats-card .card-body {
        padding: 20px;
    }
    
    .stats-card-small .card-body {
        padding: 18px;
    }
    
    .stats-number {
        font-size: 32px !important;
    }
    
    .stats-card-small .stats-number {
        font-size: 28px !important;
    }
    
    .stats-label {
        font-size: 14px !important;
    }
    
    .stats-card-small .stats-label {
        font-size: 12px !important;
    }
    
    .stats-icon {
        width: 56px !important;
        height: 56px !important;
        font-size: 24px !important;
    }
    
    .stats-card-small .stats-icon {
        width: 48px !important;
        height: 48px !important;
        font-size: 20px !important;
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
    
    .table-responsive {
        font-size: 12px;
    }
    
    .table thead th {
        padding: 12px 8px;
        font-size: 11px;
    }
    
    .table tbody td {
        padding: 12px 8px;
    }
    
    .avatar-md {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .btn-group .btn {
        padding: 4px 8px;
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .statistics-section .row > [class*="col-"] {
        padding-left: 8px;
        padding-right: 8px;
    }
    
    .stats-card .card-body {
        padding: 16px;
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .stats-card-small .card-body {
        padding: 14px;
        gap: 10px;
    }
    
    .stats-content {
        align-items: center;
    }
    
    .stats-number {
        font-size: 28px !important;
    }
    
    .stats-card-small .stats-number {
        font-size: 24px !important;
    }
    
    .stats-icon {
        width: 48px !important;
        height: 48px !important;
        font-size: 20px !important;
    }
    
    .stats-card-small .stats-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 18px !important;
    }
    
    .card-header {
        padding: 16px 20px;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        gap: 12px;
        align-items: stretch !important;
    }
    
    .empty-state {
        padding: 40px 16px;
    }
    
    .empty-state i {
        font-size: 48px;
    }
    
    /* Stack table vertically on mobile */
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
        padding: 12px;
        background: white;
        transform: none !important;
    }
    
    .table tbody td {
        display: block;
        text-align: left;
        border: none;
        padding: 6px 0;
        border-bottom: 1px solid #f3f4f6;
        background: transparent;
    }
    
    .table tbody td:last-child {
        border-bottom: none;
    }
    
    .table tbody td:before {
        content: attr(data-label) ": ";
        font-weight: 600;
        color: #6b7280;
        display: inline-block;
        width: 100px;
        font-size: 11px;
        text-transform: uppercase;
    }
    
    .modal-dialog {
        margin: 16px;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding-left: 16px;
        padding-right: 16px;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 8px;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}

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

@keyframes slideInUp {
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

<script>
// Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchABK');
    const clearBtn = document.getElementById('clearSearch');
    const searchInfo = document.getElementById('searchInfo');
    const searchResults = document.getElementById('searchResults');
    const noResults = document.getElementById('noResults');
    const noResultsText = document.getElementById('noResultsText');
    const tableFooter = document.getElementById('tableFooter');
    const abkRows = document.querySelectorAll('.abk-row');
    
    let searchTimeout;
    let originalRowNumbers = new Map();
    
    // Store original row numbers
    abkRows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child .avatar-sm');
        originalRowNumbers.set(row, numberCell.textContent);
    });

    // Search input event
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim().toLowerCase();
        
        // Add loading state
        searchInput.classList.add('search-loading');
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
            searchInput.classList.remove('search-loading');
        }, 300);
    });

    // Clear search button
    clearBtn.addEventListener('click', function() {
        clearSearchResults();
    });

    // Enter key search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            const query = this.value.trim().toLowerCase();
            performSearch(query);
            searchInput.classList.remove('search-loading');
        }
    });

    function performSearch(query) {
        if (!query) {
            clearSearchResults();
            return;
        }

        let foundCount = 0;
        let visibleRowIndex = 1;

        abkRows.forEach(row => {
            const nama = row.dataset.nama || '';
            const npp = row.dataset.npp || '';
            const jabatan = row.dataset.jabatan || '';
            const status = row.dataset.status || '';
            const kapal = row.dataset.kapal || '';

            const searchText = `${nama} ${npp} ${jabatan} ${status} ${kapal}`;
            const isMatch = searchText.includes(query);

            if (isMatch) {
                row.style.display = '';
                row.classList.remove('hidden', 'searching');
                row.classList.add('found');
                
                // Update row number for visible rows
                const numberCell = row.querySelector('td:first-child .avatar-sm');
                numberCell.textContent = visibleRowIndex;
                visibleRowIndex++;
                
                // Highlight matching text
                highlightSearchTerm(row, query);
                foundCount++;
            } else {
                row.style.display = 'none';
                row.classList.add('hidden');
                row.classList.remove('found', 'searching');
            }
        });

        // Show/hide results info
        if (foundCount > 0) {
            searchInfo.style.display = 'block';
            searchResults.innerHTML = `Ditemukan <strong>${foundCount}</strong> ABK yang sesuai dengan "<strong>${query}</strong>"`;
            noResults.style.display = 'none';
            tableFooter.style.display = 'block';
        } else {
            searchInfo.style.display = 'none';
            noResults.style.display = 'block';
            noResultsText.innerHTML = `Tidak ada ABK yang sesuai dengan pencarian "<strong>${query}</strong>"`;
            tableFooter.style.display = 'none';
        }
    }

    function highlightSearchTerm(row, query) {
        const highlightElements = row.querySelectorAll('.highlight-text');
        
        highlightElements.forEach(element => {
            const originalText = element.textContent;
            const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
            const highlightedText = originalText.replace(regex, '<span class="highlight">$1</span>');
            
            if (originalText !== highlightedText) {
                element.innerHTML = highlightedText;
            }
        });
    }

    function clearSearchResults() {
        searchInput.value = '';
        searchInfo.style.display = 'none';
        noResults.style.display = 'none';
        tableFooter.style.display = 'block';
        
        abkRows.forEach(row => {
            row.style.display = '';
            row.classList.remove('hidden', 'found', 'searching');
            
            // Restore original row numbers
            const numberCell = row.querySelector('td:first-child .avatar-sm');
            numberCell.textContent = originalRowNumbers.get(row);
            
            // Remove highlights
            const highlightElements = row.querySelectorAll('.highlight-text');
            highlightElements.forEach(element => {
                element.innerHTML = element.textContent;
            });
        });
        
        searchInput.focus();
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Global function for clear button in search info
    window.clearSearchResults = clearSearchResults;
});

// Original delete confirmation function
function confirmDelete(id, name) {
    // Set data di modal
    document.getElementById('deleteName').textContent = name || 'ABK';
    document.getElementById('deleteId').textContent = id;
    
    // Set action form
    const form = document.getElementById('deleteForm');
    form.action = `/abk/${id}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Event listener untuk menangani response setelah delete
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    // Optional: Tambahkan loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menghapus...';
    submitBtn.disabled = true;
    
    // Form akan submit normal, tidak perlu prevent default
});

// Tutup modal saat ada error/success
document.addEventListener('DOMContentLoaded', function() {
    // Auto close modal setelah 3 detik jika ada success message
    if (document.querySelector('.alert-success')) {
        setTimeout(function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) {
                modal.hide();
            }
        }, 1000);
    }
});
</script>
@endsection