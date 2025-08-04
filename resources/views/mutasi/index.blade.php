{{-- Ganti konten file resources/views/mutasi/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
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
                                <li class="breadcrumb-item active">Kelola Mutasi</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="bi bi-arrow-left-right"></i>
                            Kelola Mutasi ABK
                        </h1>
                        <p class="page-subtitle">Manajemen data mutasi ABK antar kapal PELNI</p>
                    </div>
                    <div class="header-actions">
                        <div class="action-buttons">
                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tambah Mutasi
                            </a>
                            <button class="btn btn-outline-secondary" onclick="exportData()">
                                <i class="bi bi-download me-2"></i>
                                Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card stats-card-primary">
                        <div class="stats-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $statistics['total_mutasi'] ?? 0 }}</div>
                            <div class="stats-label">Total Mutasi</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card stats-card-success">
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $statistics['mutasi_selesai'] ?? 0 }}</div>
                            <div class="stats-label">Mutasi Selesai</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card stats-card-warning">
                        <div class="stats-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $statistics['mutasi_proses'] ?? 0 }}</div>
                            <div class="stats-label">Dalam Proses</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card stats-card-info">
                        <div class="stats-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $statistics['mutasi_bulan_ini'] ?? 0 }}</div>
                            <div class="stats-label">Mutasi Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="filter-section mb-4">
                <div class="filter-card">
                    <form method="GET" action="{{ route('mutasi.index') }}" class="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Cari Mutasi</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="ID, Nama ABK, atau Kapal..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="Sementara" {{ request('jenis') == 'Sementara' ? 'selected' : '' }}>Sementara</option>
                                    <option value="Definitif" {{ request('jenis') == 'Definitif' ? 'selected' : '' }}>Definitif</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Periode TMT</label>
                                <input type="month" name="periode" class="form-control" 
                                       value="{{ request('periode') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('mutasi.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-table me-2"></i>
                            Data Mutasi ABK
                        </h5>
                        <div class="table-info">
                            Menampilkan {{ $mutasiList->firstItem() ?? 0 }}-{{ $mutasiList->lastItem() ?? 0 }} 
                            dari {{ $mutasiList->total() ?? 0 }} data mutasi
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-data">
                            <thead>
                                <tr>
                                    <th width="10%">ID Mutasi</th>
                                    <th width="15%">ABK Naik</th>
                                    <th width="12%">Jabatan</th>
                                    <th width="15%">Kapal Tujuan</th>
                                    <th width="12%">TMT</th>
                                    <th width="10%">Jenis</th>
                                    <th width="10%">Status</th>
                                    <th width="11%">ABK Turun</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mutasiList as $mutasi)
                                <tr class="table-row">
                                    <td>
                                        <span class="code-badge">
                                            MUT-{{ str_pad($mutasi->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="abk-info">
                                            <strong>{{ $mutasi->nama_lengkap_naik }}</strong>
                                            <small class="d-block text-muted">
                                                NRP: {{ $mutasi->id_abk_naik }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="jabatan-info">
                                            <span class="jabatan-from">
                                                {{ $mutasi->jabatanTetapNaik->nama_jabatan ?? '-' }}
                                            </span>
                                            <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                            <span class="jabatan-to">
                                                {{ $mutasi->jabatanMutasi->nama_jabatan ?? '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="kapal-badge kapal-tujuan">
                                            {{ $mutasi->kapal->nama_kapal ?? 'Kapal tidak ditemukan' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <strong>{{ $mutasi->TMT ? $mutasi->TMT->format('d/m/Y') : '-' }}</strong>
                                            <small class="d-block text-muted">
                                                s/d {{ $mutasi->TAT ? $mutasi->TAT->format('d/m/Y') : '-' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="jenis-badge jenis-{{ strtolower($mutasi->jenis_mutasi) }}">
                                            {{ $mutasi->jenis_mutasi }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($mutasi->status_mutasi) }}">
                                            {{ $mutasi->status_mutasi }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($mutasi->ada_abk_turun && $mutasi->nama_lengkap_turun)
                                            <div class="abk-turun-info">
                                                <strong>{{ $mutasi->nama_lengkap_turun }}</strong>
                                                <small class="d-block text-muted">
                                                    {{ $mutasi->id_abk_turun }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-mini">
                                            <button class="btn-action btn-view" 
                                                    onclick="viewMutasi({{ $mutasi->id }})"
                                                    title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn-action btn-edit" 
                                                    onclick="editMutasi({{ $mutasi->id }})"
                                                    title="Edit Mutasi">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if($mutasi->status_mutasi == 'Draft')
                                            <button class="btn-action btn-delete" 
                                                    onclick="deleteMutasi({{ $mutasi->id }}, event)"
                                                    title="Hapus Mutasi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Data Mutasi</h5>
                                            <p class="text-muted mb-3">
                                                Mulai dengan menambah data mutasi ABK pertama Anda.
                                            </p>
                                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>
                                                Tambah Mutasi Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($mutasiList->hasPages())
                    <div class="table-footer">
                        <div class="pagination-wrapper">
                            {{ $mutasiList->appends(request()->query())->links('custom.pagination') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Mutasi -->
<div class="modal fade" id="detailMutasiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mutasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailMutasiContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-4">
                    <div class="warning-icon mx-auto mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h4 class="text-danger fw-bold mb-2">Konfirmasi Penghapusan</h4>
                    <p class="text-muted mb-0">
                        Apakah Anda yakin ingin menghapus mutasi ini?<br>
                        <small class="text-warning">
                            <i class="bi bi-info-circle me-1"></i>
                            Data yang sudah dihapus tidak dapat dikembalikan!
                        </small>
                    </p>
                </div>
                
                <div class="mutasi-info-preview mb-4 p-3 bg-light rounded">
                    <div class="row text-start">
                        <div class="col-6">
                            <small class="text-muted">ID Mutasi:</small>
                            <div class="fw-bold" id="deletePreviewId">-</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">ABK:</small>
                            <div class="fw-bold" id="deletePreviewAbk">-</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-2"></i>
                        <span class="btn-text">Ya, Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="notificationToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="notification-icon me-2"></i>
                <span class="notification-message"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Gunakan semua styling dari placeholder page yang sudah ada */
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

/* Stats Cards */
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
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    margin-bottom: 24px;
}

.filter-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
}

.filter-form .form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
}

/* Table Section */
.table-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
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
}

/* Table Styling */
.table-data {
    margin: 0;
    background: white;
}

.table-data th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    padding: 16px 12px;
    border-bottom: 2px solid var(--border-color);
    vertical-align: middle;
}

.table-data td {
    padding: 16px 12px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
    vertical-align: middle;
}

.table-row:hover {
    background-color: #f8fafc;
    transition: var(--transition);
}

.code-badge {
    background: #f3f4f6;
    color: var(--text-dark);
    padding: 6px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 600;
}

.abk-info strong, .abk-turun-info strong {
    color: var(--text-dark);
    font-size: 13px;
    display: block;
}

.abk-info small, .abk-turun-info small {
    font-size: 11px;
}

.jabatan-info {
    font-size: 12px;
}

.jabatan-from {
    color: var(--text-muted);
}

.jabatan-to {
    color: var(--text-dark);
    font-weight: 600;
}

.kapal-badge {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.kapal-tujuan {
    background: #d1fae5;
    color: #065f46;
}

.date-info strong {
    color: var(--text-dark);
    font-size: 13px;
}

.date-info small {
    font-size: 11px;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-draft {
    background: #f3f4f6;
    color: #374151;
}

.status-disetujui {
    background: #d1fae5;
    color: #065f46;
}

.status-ditolak {
    background: #fee2e2;
    color: #991b1b;
}

.status-selesai {
    background: #dbeafe;
    color: #1e40af;
}

/* Jenis Badges */
.jenis-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
}

.jenis-sementara {
    background: #fef3c7;
    color: #92400e;
}

.jenis-definitif {
    background: #d1fae5;
    color: #065f46;
}

/* Action Buttons */
.action-buttons-mini {
    display: flex;
    gap: 4px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: var(--transition);
}

.btn-view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-view:hover {
    background: #bfdbfe;
    transform: scale(1.05);
}

.btn-edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-edit:hover {
    background: #fde68a;
    transform: scale(1.05);
}

.btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.btn-delete:hover {
    background: #fecaca;
    transform: scale(1.05);
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

/* Table Footer */
.table-footer {
    background: var(--background-light);
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
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

/* Modal Konfirmasi Hapus */
#deleteConfirmModal .modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.warning-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #fee2e2, #fecaca);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    animation: pulse-warning 2s infinite;
}

.warning-icon i {
    font-size: 2.5rem;
    color: #dc2626;
    animation: shake 0.5s ease-in-out infinite alternate;
}

.mutasi-info-preview {
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

/* Animasi */
@keyframes pulse-warning {
    0% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

@keyframes shake {
    0% { transform: rotate(-2deg); }
    100% { transform: rotate(2deg); }
}

/* Loading state untuk tombol */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Toast Notification */
.toast {
    min-width: 300px;
    backdrop-filter: blur(10px);
    border-radius: 10px !important;
}

.toast.toast-success {
    background: linear-gradient(45deg, #10b981, #34d399);
    color: white;
}

.toast.toast-error {
    background: linear-gradient(45deg, #ef4444, #f87171);
    color: white;
}

.toast.toast-warning {
    background: linear-gradient(45deg, #f59e0b, #fbbf24);
    color: white;
}

.toast.toast-info {
    background: linear-gradient(45deg, #06b6d4, #22d3ee);
    color: white;
}

.notification-icon {
    font-size: 1.2rem;
}

/* Efek hover untuk tombol konfirmasi */
#confirmDeleteBtn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Modal backdrop blur */
.modal-backdrop {
    backdrop-filter: blur(3px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add interactive effects sama seperti placeholder
    const statsCards = document.querySelectorAll('.stats-card');
    
    // Stats cards hover effect
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // Animate stats numbers
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const target = parseInt(number.textContent);
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current);
            }, 50);
        });
    }
    
    // Call animate numbers on load
    animateNumbers();
});

// Global variable untuk menyimpan ID mutasi yang akan dihapus dan data row
let mutasiToDelete = null;
let mutasiRowData = null;

function viewMutasi(id) {
    // Show loading in modal
    const modalContent = document.getElementById('detailMutasiContent');
    modalContent.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat detail mutasi...</p>
        </div>
    `;
    
    // Show modal first
    const modal = new bootstrap.Modal(document.getElementById('detailMutasiModal'));
    modal.show();
    
    // Load detail mutasi dengan URL yang benar
    fetch(`{{ route('mutasi.index') }}/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            modalContent.innerHTML = data.html;
        } else {
            throw new Error(data.message || 'Gagal memuat detail mutasi');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalContent.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
                <h5>Gagal Memuat Detail</h5>
                <p class="mb-0">${error.message}</p>
            </div>
        `;
        showNotification('Gagal memuat detail mutasi', 'error');
    });
}

function editMutasi(id) {
    // PERBAIKAN: Gunakan route helper yang benar
    window.location.href = `{{ url('mutasi') }}/${id}/edit`;
}

function deleteMutasi(id, event) {
    // Simpan ID mutasi
    mutasiToDelete = id;
    
    // PERBAIKAN: Ambil data dari button yang diklik
    let clickedButton;
    let row;
    
    if (event && event.target) {
        clickedButton = event.target.closest('button');
        row = clickedButton.closest('tr');
    } else {
        // Fallback: cari button berdasarkan onclick attribute
        clickedButton = document.querySelector(`button[onclick*="deleteMutasi(${id})"]`);
        if (clickedButton) {
            row = clickedButton.closest('tr');
        }
    }
    
    // Ambil data mutasi dari row table untuk preview
    const mutasiId = `MUT-${String(id).padStart(4, '0')}`;
    let abkName = '-';
    
    if (row) {
        const abkElement = row.querySelector('.abk-info strong');
        if (abkElement) {
            abkName = abkElement.textContent.trim();
        }
        // Simpan referensi row untuk digunakan nanti
        mutasiRowData = { row: row, button: clickedButton };
    }
    
    // Update preview di modal
    const deletePreviewId = document.getElementById('deletePreviewId');
    const deletePreviewAbk = document.getElementById('deletePreviewAbk');
    
    if (deletePreviewId) deletePreviewId.textContent = mutasiId;
    if (deletePreviewAbk) deletePreviewAbk.textContent = abkName;
    
    // Reset button state
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.classList.remove('btn-loading');
        const btnText = confirmBtn.querySelector('.btn-text');
        if (btnText) btnText.textContent = 'Ya, Hapus';
    }
    
    // Show modal
    const modalElement = document.getElementById('deleteConfirmModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

// PERBAIKAN: Update event listener untuk tombol konfirmasi hapus
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!mutasiToDelete) return;
            
            const btn = this;
            const btnText = btn.querySelector('.btn-text');
            
            // Set loading state
            btn.classList.add('btn-loading');
            btn.disabled = true;
            if (btnText) btnText.textContent = 'Menghapus...';
            
            // PERBAIKAN: Gunakan URL yang benar
            const deleteUrl = `{{ url('mutasi') }}/${mutasiToDelete}`;
            
            // Send delete request
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    if (response.status === 405) {
                        throw new Error('Method tidak diizinkan. Route delete mungkin belum ada.');
                    } else if (response.status === 404) {
                        throw new Error('Data mutasi tidak ditemukan.');
                    } else if (response.status === 500) {
                        throw new Error('Terjadi kesalahan server.');
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Hide modal
                    const modalElement = document.getElementById('deleteConfirmModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    // Show success notification
                    showNotification('Mutasi berhasil dihapus!', 'success');
                    
                    // Remove row from table with animation
                    if (mutasiRowData && mutasiRowData.row) {
                        const row = mutasiRowData.row;
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        
                        setTimeout(() => {
                            row.remove();
                            
                            // Update stats
                            updateStatsAfterDelete();
                            
                            // Check if table is empty
                            checkEmptyTable();
                        }, 300);
                    } else {
                        // Fallback: reload page jika tidak bisa remove row
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                } else {
                    throw new Error(data.message || 'Gagal menghapus mutasi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal menghapus mutasi: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                if (btnText) btnText.textContent = 'Ya, Hapus';
                mutasiToDelete = null;
                mutasiRowData = null;
            });
        });
    }
});

// Function untuk menampilkan notifikasi toast
function showNotification(message, type = 'info') {
    const toast = document.getElementById('notificationToast');
    if (!toast) return;
    
    const toastIcon = toast.querySelector('.notification-icon');
    const toastMessage = toast.querySelector('.notification-message');
    
    // Remove existing classes
    toast.classList.remove('toast-success', 'toast-error', 'toast-warning', 'toast-info');
    
    // Set icon dan class berdasarkan type
    const config = {
        success: { icon: 'bi-check-circle-fill', class: 'toast-success' },
        error: { icon: 'bi-exclamation-triangle-fill', class: 'toast-error' },
        warning: { icon: 'bi-exclamation-triangle-fill', class: 'toast-warning' },
        info: { icon: 'bi-info-circle-fill', class: 'toast-info' }
    };
    
    const currentConfig = config[type] || config.info;
    
    // Update content
    if (toastIcon) {
        toastIcon.className = `notification-icon me-2 bi ${currentConfig.icon}`;
    }
    if (toastMessage) {
        toastMessage.textContent = message;
    }
    toast.classList.add(currentConfig.class);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
}

// Function untuk update statistik setelah hapus
function updateStatsAfterDelete() {
    const totalElement = document.querySelector('.stats-card-primary .stats-number');
    if (totalElement) {
        const currentTotal = parseInt(totalElement.textContent);
        totalElement.textContent = Math.max(0, currentTotal - 1);
        
        // Animate number change
        totalElement.style.transform = 'scale(1.1)';
        setTimeout(() => {
            totalElement.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Update proses count jika statusnya draft
    const prosesElement = document.querySelector('.stats-card-warning .stats-number');
    if (prosesElement) {
        const currentProses = parseInt(prosesElement.textContent);
        prosesElement.textContent = Math.max(0, currentProses - 1);
    }
}

// Function untuk cek tabel kosong
function checkEmptyTable() {
    const tbody = document.querySelector('.table-data tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr:not(.empty-row)');
    
    if (rows.length === 0) {
        tbody.innerHTML = `
            <tr class="empty-row">
                <td colspan="9" class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">Belum Ada Data Mutasi</h5>
                        <p class="text-muted mb-3">
                            Mulai dengan menambah data mutasi ABK pertama Anda.
                        </p>
                        <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Mutasi Pertama
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }
}

// Tambahkan event listener untuk menutup modal saat backdrop diklik
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
        deleteModal.addEventListener('hide.bs.modal', function() {
            mutasiToDelete = null;
            mutasiRowData = null;
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            if (confirmBtn) {
                confirmBtn.classList.remove('btn-loading');
                confirmBtn.disabled = false;
                const btnText = confirmBtn.querySelector('.btn-text');
                if (btnText) btnText.textContent = 'Ya, Hapus';
            }
        });
    }
});

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('mutasi.export') }}?${params.toString()}`, '_blank');
}
</script>
@endpush