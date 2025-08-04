{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\monitoring\sertijab.blade.php --}}
@extends('layouts.app')

@section('title', 'Monitoring Sertijab')

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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('monitoring.index') }}">
                                        <i class="bi bi-graph-up-arrow"></i>
                                        Monitoring
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Dokumen Sertijab</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="bi bi-file-earmark-check"></i>
                            Monitoring Dokumen Sertijab
                        </h1>
                        <p class="page-subtitle">Pantau dan verifikasi dokumen serah terima jabatan ABK</p>
                    </div>
                    <div class="header-actions">
                        <div class="action-buttons">
                            <a href="{{ route('monitoring.documents') }}" class="btn btn-primary">
                                <i class="bi bi-eye-fill me-2"></i>
                                Verifikasi Dokumen
                            </a>
                            <button class="btn btn-outline-secondary" onclick="exportData()">
                                <i class="bi bi-download me-2"></i>
                                Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section - LANGSUNG KE FILTER TANPA STATS CARDS -->
            <div class="filter-section mb-4">
                <div class="filter-card">
                    <form method="GET" action="{{ route('monitoring.sertijab') }}" class="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Cari Dokumen</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Nama ABK, Kapal, atau ID..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kapal</label>
                                <select name="kapal_id" class="form-select">
                                    <option value="">Semua Kapal</option>
                                    @foreach($kapalList as $kapal)
                                        <option value="{{ $kapal->id }}" {{ request('kapal_id') == $kapal->id ? 'selected' : '' }}>
                                            {{ $kapal->nama_kapal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian Terverifikasi</option>
                                    <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Terverifikasi</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Periode Submit</label>
                                <input type="month" name="periode" class="form-control" 
                                       value="{{ request('periode') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('monitoring.sertijab') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Section - SISANYA TETAP SAMA -->
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-table me-2"></i>
                            Data Dokumen Sertijab
                        </h5>
                        <div class="table-info">
                            Menampilkan {{ $sertijabList->firstItem() ?? 0 }}-{{ $sertijabList->lastItem() ?? 0 }} 
                            dari {{ $sertijabList->total() ?? 0 }} dokumen sertijab
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-data">
                            <thead>
                                <tr>
                                    <th width="8%">No</th>
                                    <th width="15%">Kapal</th>
                                    <th width="20%">ABK Mutasi</th>
                                    <th width="12%">Jenis Mutasi</th>
                                    <th width="12%">Dokumen</th>
                                    <th width="12%">Status</th>
                                    <th width="12%">Tanggal Submit</th>
                                    <th width="9%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sertijabList as $index => $sertijab)
                                @php
                                    $mutasi = $sertijab->mutasi;
                                @endphp
                                <tr class="table-row">
                                    <td>
                                        <span class="code-badge">
                                            {{ $sertijabList->firstItem() + $index }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="kapal-info">
                                            <strong>{{ $mutasi->kapal->nama_kapal ?? 'N/A' }}</strong>
                                            <small class="d-block text-muted">
                                                ID: {{ $mutasi->kapal->id ?? '-' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="abk-mutasi-info">
                                            <div class="abk-naik mb-1">
                                                <i class="bi bi-arrow-up-circle text-success me-1"></i>
                                                <strong>{{ $mutasi->nama_lengkap_naik ?? 'N/A' }}</strong>
                                                <small class="d-block text-muted ms-3">
                                                    NRP: {{ $mutasi->id_abk_naik ?? '-' }}
                                                </small>
                                            </div>
                                            @if($mutasi->nama_lengkap_turun)
                                            <div class="abk-turun">
                                                <i class="bi bi-arrow-down-circle text-danger me-1"></i>
                                                <span>{{ $mutasi->nama_lengkap_turun }}</span>
                                                <small class="d-block text-muted ms-3">
                                                    NRP: {{ $mutasi->id_abk_turun ?? '-' }}
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mutasi-type-info">
                                            <span class="jenis-badge jenis-{{ strtolower($mutasi->case_mutasi ?? 'mutasi') }}">
                                                {{ $mutasi->case_mutasi ?? 'Mutasi' }}
                                            </span>
                                            <div class="small text-muted mt-1">{{ $mutasi->nama_mutasi ?? '' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dokumen-info">
                                            @if($sertijab->dokumen_sertijab_path)
                                                <a href="{{ Storage::url($sertijab->dokumen_sertijab_path) }}" 
                                                   target="_blank" 
                                                   class="btn-doc btn-doc-available">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                                    Sertijab
                                                </a>
                                            @endif
                                            
                                            @if($sertijab->dokumen_familisasi_path)
                                                <a href="{{ Storage::url($sertijab->dokumen_familisasi_path) }}" 
                                                   target="_blank" 
                                                   class="btn-doc btn-doc-available">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    Familisasi
                                                </a>
                                            @endif
                                            
                                            @if($sertijab->dokumen_lampiran_path)
                                                <a href="{{ Storage::url($sertijab->dokumen_lampiran_path) }}" 
                                                   target="_blank" 
                                                   class="btn-doc btn-doc-available">
                                                    <i class="bi bi-file-earmark-plus me-1"></i>
                                                    Lampiran
                                                </a>
                                            @endif
                                            
                                            @if(!$sertijab->dokumen_sertijab_path && !$sertijab->dokumen_familisasi_path)
                                                <span class="badge bg-danger">Belum Upload</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($sertijab->status_dokumen == 'draft')
                                            <span class="status-badge status-draft">
                                                <i class="bi bi-clock me-1"></i>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($sertijab->status_dokumen == 'partial')
                                            <span class="status-badge status-partial">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Sebagian Terverifikasi
                                            </span>
                                        @elseif($sertijab->status_dokumen == 'final')
                                            <span class="status-badge status-final">
                                                <i class="bi bi-check2-all me-1"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="status-badge status-unknown">
                                                <i class="bi bi-question-circle me-1"></i>
                                                Status Tidak Diketahui
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <strong>{{ $sertijab->submitted_at ? $sertijab->submitted_at->format('d/m/Y') : '-' }}</strong>
                                            <small class="d-block text-muted">
                                                {{ $sertijab->submitted_at ? $sertijab->submitted_at->format('H:i') : '' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons-mini">
                                            <button class="btn-action btn-view" 
                                                    onclick="viewSertijab({{ $sertijab->id }})"
                                                    title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($sertijab->status_dokumen != 'final')
                                            <button class="btn-action btn-verify" 
                                                    onclick="quickVerify({{ $sertijab->id }})"
                                                    title="Quick Verify">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            @endif
                                            <button class="btn-action btn-info" 
                                                    onclick="viewMutasi({{ $mutasi->id }})"
                                                    title="Info Mutasi">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Dokumen Sertijab</h5>
                                            <p class="text-muted mb-3">
                                                @if(request('kapal_id') || request('status') || request('search'))
                                                    Tidak ada dokumen yang sesuai dengan filter. 
                                                    <a href="{{ route('monitoring.sertijab') }}">Tampilkan semua</a>
                                                @else
                                                    Data akan muncul setelah PUK submit dokumen sertijab.
                                                @endif
                                            </p>
                                            <a href="{{ route('monitoring.index') }}" class="btn btn-primary">
                                                <i class="bi bi-arrow-left me-2"></i>
                                                Kembali ke Monitoring
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($sertijabList->hasPages())
                    <div class="table-footer">
                        <div class="pagination-wrapper">
                            {{ $sertijabList->appends(request()->query())->links('custom.pagination') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Sertijab -->
<div class="modal fade" id="detailSertijabModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Dokumen Sertijab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailSertijabContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Quick Verify -->
<div class="modal fade" id="quickVerifyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-4">
                    <div class="verify-icon mx-auto mb-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <h4 class="text-success fw-bold mb-2">Quick Verify Dokumen</h4>
                    <p class="text-muted mb-0">
                        Yakin ingin memverifikasi semua dokumen sekaligus?<br>
                        <small class="text-info">
                            <i class="bi bi-info-circle me-1"></i>
                            Semua dokumen akan dimark sebagai "Final"
                        </small>
                    </p>
                </div>
                
                <div class="sertijab-info-preview mb-4 p-3 bg-light rounded">
                    <div class="row text-start">
                        <div class="col-6">
                            <small class="text-muted">ABK:</small>
                            <div class="fw-bold" id="verifyPreviewAbk">-</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Kapal:</small>
                            <div class="fw-bold" id="verifyPreviewKapal">-</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-start w-100">Catatan Verifikasi (Opsional)</label>
                    <textarea class="form-control" id="quickVerifyNote" rows="3" 
                              placeholder="Tambahkan catatan verifikasi..."></textarea>
                </div>
                
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-success px-4" id="confirmVerifyBtn">
                        <i class="bi bi-check2-circle me-2"></i>
                        <span class="btn-text">Ya, Verifikasi</span>
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
/* Import semua styling dari mutasi index */
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

/* Kapal Info */
.kapal-info strong {
    color: var(--text-dark);
    font-size: 13px;
    display: block;
}

.kapal-info small {
    font-size: 11px;
}

/* ABK Mutasi Info */
.abk-mutasi-info {
    font-size: 12px;
}

.abk-naik strong {
    color: var(--success-color);
    font-size: 13px;
}

.abk-turun span {
    color: var(--danger-color);
    font-size: 12px;
}

/* Mutasi Type Info */
.mutasi-type-info {
    font-size: 12px;
}

/* Jenis Badges */
.jenis-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    display: inline-block;
}

.jenis-promosi {
    background: #d1fae5;
    color: #065f46;
}

.jenis-demosi {
    background: #fef3c7;
    color: #92400e;
}

.jenis-rotasi {
    background: #dbeafe;
    color: #1e40af;
}

.jenis-mutasi {
    background: #f3f4f6;
    color: #374151;
}

/* Dokumen Info */
.dokumen-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.btn-doc {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: var(--transition);
    max-width: fit-content;
}

.btn-doc-available {
    background: #dbeafe;
    color: #1e40af;
}

.btn-doc-available:hover {
    background: #bfdbfe;
    color: #1e40af;
    text-decoration: none;
    transform: scale(1.05);
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-partial {
    background: #dbeafe;
    color: #1e40af;
}

.status-final {
    background: #d1fae5;
    color: #065f46;
}

.status-unknown {
    background: #f3f4f6;
    color: #374151;
}

/* Date Info */
.date-info strong {
    color: var(--text-dark);
    font-size: 13px;
}

.date-info small {
    font-size: 11px;
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

.btn-verify {
    background: #d1fae5;
    color: #065f46;
}

.btn-verify:hover {
    background: #a7f3d0;
    transform: scale(1.05);
}

.btn-info {
    background: #e0f2fe;
    color: #0891b2;
}

.btn-info:hover {
    background: #bae6fd;
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

/* Modal Styling */
.modal-xl .modal-dialog {
    max-width: 1200px;
}

.verify-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #d1fae5, #a7f3d0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    animation: pulse-success 2s infinite;
}

.verify-icon i {
    font-size: 2.5rem;
    color: #059669;
    animation: bounce 1s ease-in-out infinite alternate;
}

.sertijab-info-preview {
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

/* Animasi */
@keyframes pulse-success {
    0% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

@keyframes bounce {
    0% { transform: translateY(-2px); }
    100% { transform: translateY(2px); }
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

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .action-buttons {
        flex-direction: row;
        align-items: center;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .action-buttons-mini {
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-action {
        width: 28px;
        height: 28px;
        font-size: 10px;
    }
    
    .dokumen-info {
        gap: 2px;
    }
    
    .btn-doc {
        font-size: 9px;
        padding: 2px 6px;
    }
}

/* Form Control Enhancements */
.form-control, .form-select {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.form-control:hover, .form-select:hover {
    border-color: var(--primary-blue);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stats cards animations
    const statsCards = document.querySelectorAll('.stats-card');
    
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
        
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
            if (isNaN(target)) return;
            
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
    
    // Animate table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 50);
    });
    
    // Call animations
    setTimeout(animateNumbers, 800);
});

// Global variables
let sertijabToVerify = null;
let sertijabRowData = null;

function viewSertijab(id) {
    const modalContent = document.getElementById('detailSertijabContent');
    modalContent.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat detail sertijab...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('detailSertijabModal'));
    modal.show();
    
    fetch(`{{ route('monitoring.sertijab', '') }}/${id}`, {
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
            throw new Error(data.message || 'Gagal memuat detail sertijab');
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
        showNotification('Gagal memuat detail sertijab', 'error');
    });
}

function quickVerify(id) {
    sertijabToVerify = id;
    
    // Get row data for preview
    const row = document.querySelector(`button[onclick*="quickVerify(${id})"]`).closest('tr');
    
    let abkName = '-';
    let kapalName = '-';
    
    if (row) {
        const abkElement = row.querySelector('.abk-naik strong');
        const kapalElement = row.querySelector('.kapal-info strong');
        
        if (abkElement) abkName = abkElement.textContent.trim();
        if (kapalElement) kapalName = kapalElement.textContent.trim();
        
        sertijabRowData = { row: row };
    }
    
    // Update preview
    const verifyPreviewAbk = document.getElementById('verifyPreviewAbk');
    const verifyPreviewKapal = document.getElementById('verifyPreviewKapal');
    
    if (verifyPreviewAbk) verifyPreviewAbk.textContent = abkName;
    if (verifyPreviewKapal) verifyPreviewKapal.textContent = kapalName;
    
    // Reset form
    const noteTextarea = document.getElementById('quickVerifyNote');
    if (noteTextarea) noteTextarea.value = '';
    
    // Reset button state
    const confirmBtn = document.getElementById('confirmVerifyBtn');
    if (confirmBtn) {
        confirmBtn.classList.remove('btn-loading');
        confirmBtn.disabled = false;
        const btnText = confirmBtn.querySelector('.btn-text');
        if (btnText) btnText.textContent = 'Ya, Verifikasi';
    }
    
    // Show modal
    const modalElement = document.getElementById('quickVerifyModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

function viewMutasi(id) {
    window.open(`{{ url('mutasi') }}/${id}`, '_blank');
}

// Confirm verify button event listener
document.addEventListener('DOMContentLoaded', function() {
    const confirmVerifyBtn = document.getElementById('confirmVerifyBtn');
    if (confirmVerifyBtn) {
        confirmVerifyBtn.addEventListener('click', function() {
            if (!sertijabToVerify) return;
            
            const btn = this;
            const btnText = btn.querySelector('.btn-text');
            const noteTextarea = document.getElementById('quickVerifyNote');
            const note = noteTextarea ? noteTextarea.value.trim() : '';
            
            // Set loading state
            btn.classList.add('btn-loading');
            btn.disabled = true;
            if (btnText) btnText.textContent = 'Memverifikasi...';
            
            fetch(`{{ route('monitoring.documents') }}/${sertijabToVerify}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    catatan_admin: note || 'Quick verification dari monitoring'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Hide modal
                    const modalElement = document.getElementById('quickVerifyModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    // Show success notification
                    showNotification('Dokumen berhasil diverifikasi!', 'success');
                    
                    // Update row status
                    if (sertijabRowData && sertijabRowData.row) {
                        const statusBadge = sertijabRowData.row.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.className = 'status-badge status-final';
                            statusBadge.innerHTML = '<i class="bi bi-check2-all me-1"></i>Terverifikasi';
                        }
                        
                        // Remove verify button
                        const verifyBtn = sertijabRowData.row.querySelector('.btn-verify');
                        if (verifyBtn) {
                            verifyBtn.remove();
                        }
                        
                        // Update stats
                        updateStatsAfterVerify();
                    } else {
                        // Fallback: reload page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                } else {
                    throw new Error(data.message || 'Gagal memverifikasi dokumen');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal memverifikasi dokumen: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                if (btnText) btnText.textContent = 'Ya, Verifikasi';
                sertijabToVerify = null;
                sertijabRowData = null;
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

// Function untuk update statistik setelah verifikasi
function updateStatsAfterVerify() {
    const waitingElement = document.querySelector('.stats-card-warning .stats-number');
    const verifiedElement = document.querySelector('.stats-card-success .stats-number');
    
    if (waitingElement) {
        const currentWaiting = parseInt(waitingElement.textContent);
        waitingElement.textContent = Math.max(0, currentWaiting - 1);
        
        // Animate number change
        waitingElement.style.transform = 'scale(1.1)';
        setTimeout(() => {
            waitingElement.style.transform = 'scale(1)';
        }, 200);
    }
    
    if (verifiedElement) {
        const currentVerified = parseInt(verifiedElement.textContent);
        verifiedElement.textContent = currentVerified + 1;
        
        // Animate number change
        verifiedElement.style.transform = 'scale(1.1)';
        setTimeout(() => {
            verifiedElement.style.transform = 'scale(1)';
        }, 200);
    }
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('monitoring.sertijab.export') }}?${params.toString()}`, '_blank');
}

// Close modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    const quickVerifyModal = document.getElementById('quickVerifyModal');
    if (quickVerifyModal) {
        quickVerifyModal.addEventListener('hide.bs.modal', function() {
            sertijabToVerify = null;
            sertijabRowData = null;
            const confirmBtn = document.getElementById('confirmVerifyBtn');
            if (confirmBtn) {
                confirmBtn.classList.remove('btn-loading');
                confirmBtn.disabled = false;
                const btnText = confirmBtn.querySelector('.btn-text');
                if (btnText) btnText.textContent = 'Ya, Verifikasi';
            }
        });
    }
});
</script>
@endpush