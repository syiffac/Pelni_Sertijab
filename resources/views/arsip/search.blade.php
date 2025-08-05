{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\search.blade.php --}}

@extends('layouts.app')

@section('title', 'Pencarian Arsip Sertijab')

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
                        <li class="breadcrumb-item">
                            <a href="{{ route('arsip.index') }}">Arsip</a>
                        </li>
                        <li class="breadcrumb-item active">Pencarian</li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-archive-fill"></i>
                    Arsip Sertijab
                </h1>
                <p class="page-subtitle">Lihat dan cari dokumen arsip serah terima jabatan ABK PELNI</p>
            </div>
            <div class="header-actions">
                <div class="action-buttons">
                    <a href="{{ route('monitoring.sertijab') }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>
                        Monitoring
                    </a>
                    <a href="{{ route('arsip.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
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

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-card">
            <div class="filter-header">
                <h5 class="filter-title">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Pencarian
                </h5>
                <div class="filter-info">
                    <small class="text-muted">Gunakan filter untuk mempersempit hasil pencarian arsip</small>
                </div>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('arsip.search') }}">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="kapal_id" class="form-label">Kapal</label>
                            <select class="form-select" id="kapal_id" name="kapal_id">
                                <option value="">Semua Kapal</option>
                                @if(isset($kapalList) && $kapalList->count() > 0)
                                    @foreach($kapalList as $kapal)
                                        <option value="{{ $kapal->id }}" {{ ($kapalId ?? '') == $kapal->id ? 'selected' : '' }}>
                                            {{ $kapal->nama_kapal }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="final" {{ ($status ?? '') == 'final' ? 'selected' : '' }}>Final (Verified)</option>
                                <option value="partial" {{ ($status ?? '') == 'partial' ? 'selected' : '' }}>Partial (Sebagian)</option>
                                <option value="draft" {{ ($status ?? '') == 'draft' ? 'selected' : '' }}>Draft (Pending)</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ ($bulan ?? '') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ ($tahun ?? date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-4">
                            <label for="search" class="form-label">Cari ABK</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Nama/NRP ABK" value="{{ $searchTerm ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Cari Arsip
                        </button>
                        <a href="{{ route('arsip.search') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="results-section">
        <div class="results-card">
            <div class="results-header">
                <h5 class="results-title">
                    <i class="bi bi-archive me-2"></i>
                    Arsip Ditemukan
                    @if(isset($arsipList))
                        <span class="badge bg-primary ms-2">{{ $arsipList->total() }}</span>
                    @endif
                </h5>
                <div class="results-info">
                    @if(isset($arsipList) && $arsipList->count() > 0)
                        <span class="text-muted">
                            Menampilkan {{ $arsipList->firstItem() }}-{{ $arsipList->lastItem() }} 
                            dari {{ $arsipList->total() }} dokumen
                        </span>
                    @endif
                </div>
            </div>
            <div class="results-body">
                @if(isset($arsipList) && $arsipList->count() > 0)
                    {{-- UPDATED: More compact card layout --}}
                    <div class="arsip-grid">
                        @foreach($arsipList as $arsip)
                            <div class="arsip-card">
                                {{-- Compact Card Header --}}
                                <div class="arsip-header">
                                    <div class="arsip-meta">
                                        <h6 class="arsip-title">
                                            {{ $arsip->mutasi->kapal->nama_kapal ?? 'N/A' }}
                                        </h6>
                                        <p class="arsip-date">
                                            TMT: {{ $arsip->mutasi->TMT ? $arsip->mutasi->TMT->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="header-badges">
                                        <span class="status-badge badge-{{ $arsip->status_dokumen }}">
                                            {{ $arsip->status_text }}
                                        </span>
                                        @php
                                            $progress = $arsip->verification_progress ?? 0;
                                            $progressClass = $progress < 50 ? 'danger' : ($progress < 100 ? 'warning' : 'success');
                                        @endphp
                                        <span class="progress-badge bg-{{ $progressClass }}">
                                            {{ $progress }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- UPDATED: Simplified ABK Data Table --}}
                                <div class="abk-data-section">
                                    <div class="abk-table-container">
                                        <table class="abk-table-compact">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>ABK Naik</th>
                                                    <th>ABK Turun</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="field-label">Nama</td>
                                                    <td class="field-value">
                                                        <div class="abk-name">{{ $arsip->mutasi->nama_lengkap_naik ?? '-' }}</div>
                                                        <div class="abk-nrp">{{ $arsip->mutasi->id_abk_naik ?? '-' }}</div>
                                                    </td>
                                                    <td class="field-value">
                                                        <div class="abk-name">{{ $arsip->mutasi->nama_lengkap_turun ?? '-' }}</div>
                                                        <div class="abk-nrp">{{ $arsip->mutasi->id_abk_turun ?? '-' }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="field-label">Jabatan Tetap</td>
                                                    <td class="field-value">
                                                        {{ $arsip->mutasi->jabatanTetapNaik->nama_jabatan ?? '-' }}
                                                    </td>
                                                    <td class="field-value">
                                                        {{ $arsip->mutasi->jabatanTetapTurun->nama_jabatan ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="field-label">Jabatan Mutasi</td>
                                                    <td class="field-value" colspan="2">
                                                        {{ $arsip->mutasi->jabatanMutasi->nama_jabatan ?? '-' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Compact Document Status --}}
                                <div class="document-status-compact">
                                    <div class="document-row">
                                        <span class="document-label">
                                            <i class="bi bi-file-earmark me-1"></i>
                                            Dokumen:
                                        </span>
                                        <div class="document-badges">
                                            @if($arsip->dokumen_sertijab_path)
                                                <span class="doc-badge badge-{{ $arsip->status_sertijab === 'final' ? 'success' : 'warning' }}">
                                                    Sertijab
                                                </span>
                                            @endif
                                            @if($arsip->dokumen_familisasi_path)
                                                <span class="doc-badge badge-{{ $arsip->status_familisasi === 'final' ? 'success' : 'warning' }}">
                                                    Familisasi
                                                </span>
                                            @endif
                                            @if($arsip->dokumen_lampiran_path)
                                                <span class="doc-badge badge-{{ $arsip->status_lampiran === 'final' ? 'success' : 'warning' }}">
                                                    Lampiran
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Compact Admin Notes --}}
                                @if($arsip->catatan_admin)
                                    <div class="admin-notes-compact">
                                        <i class="bi bi-chat-quote text-info me-1"></i>
                                        <span class="notes-text">"{{ Str::limit($arsip->catatan_admin, 60) }}"</span>
                                    </div>
                                @endif

                                {{-- Compact Card Actions --}}
                                <div class="arsip-actions-compact">
                                    <div class="action-buttons-left">
                                        <a href="{{ route('arsip.show', $arsip->id) }}" 
                                           class="btn btn-outline-primary btn-xs">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                        
                                        @if(method_exists($arsip, 'hasAnyDocuments') && $arsip->hasAnyDocuments())
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-info btn-xs dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if($arsip->dokumen_sertijab_path)
                                                        <li><a class="dropdown-item" href="{{ asset('storage/' . $arsip->dokumen_sertijab_path) }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Sertijab
                                                        </a></li>
                                                    @endif
                                                    @if($arsip->dokumen_familisasi_path)
                                                        <li><a class="dropdown-item" href="{{ asset('storage/' . $arsip->dokumen_familisasi_path) }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Familisasi
                                                        </a></li>
                                                    @endif
                                                    @if($arsip->dokumen_lampiran_path)
                                                        <li><a class="dropdown-item" href="{{ asset('storage/' . $arsip->dokumen_lampiran_path) }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Lampiran
                                                        </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="submission-info-compact">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $arsip->submitted_at ? $arsip->submitted_at->format('d/m/y') : 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    @if(isset($arsipList) && $arsipList->hasPages())
                        <div class="pagination-wrapper">
                            {{ $arsipList->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-archive"></i>
                        </div>
                        <h5 class="empty-title">Tidak Ada Arsip Ditemukan</h5>
                        <p class="empty-description">
                            Tidak ada dokumen arsip yang sesuai dengan filter pencarian. 
                            Coba ubah filter atau kata kunci pencarian.
                        </p>
                        <div class="empty-actions">
                            <a href="{{ route('arsip.search') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                            </a>
                            <a href="{{ route('monitoring.sertijab') }}" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i> Lihat Monitoring
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Compact Styling for Arsip Search */
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
    --background-card: #ffffff;
    --border-radius: 8px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 4px 12px rgba(37, 99, 235, 0.15);
    --transition: all 0.2s ease;
}

/* Page Layout */
.page-header {
    background: var(--background-card);
    border-radius: var(--border-radius);
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-light);
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
    margin-bottom: 8px;
    font-size: 13px;
}

.breadcrumb-item a {
    color: var(--text-muted);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.page-title {
    font-size: 24px;
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
    font-size: 13px;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

/* Filter Section */
.filter-section {
    margin-bottom: 20px;
}

.filter-card {
    background: var(--background-card);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.filter-header {
    background: var(--background-light);
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.filter-body {
    padding: 20px;
}

/* Results Section */
.results-section {
    margin-bottom: 20px;
}

.results-card {
    background: var(--background-card);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.results-header {
    background: var(--background-light);
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.results-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.results-body {
    padding: 20px;
}

/* UPDATED: More Compact Arsip Grid */
.arsip-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

/* UPDATED: More Compact Arsip Cards */
.arsip-card {
    background: var(--background-card);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 14px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    font-size: 13px;
}

.arsip-card:hover {
    border-color: var(--primary-blue);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.arsip-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-blue), var(--info-color));
}

/* UPDATED: Compact Card Header */
.arsip-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-top: 4px;
}

.arsip-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 2px 0;
    line-height: 1.2;
}

.arsip-date {
    font-size: 11px;
    color: var(--text-muted);
    margin: 0;
}

.header-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: flex-end;
}

/* UPDATED: Compact Status Badges */
.status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

.progress-badge {
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 9px;
    font-weight: 600;
    color: white;
    white-space: nowrap;
}

.badge-final {
    background: var(--success-color);
    color: white;
}

.badge-partial {
    background: var(--warning-color);
    color: white;
}

.badge-draft {
    background: #6b7280;
    color: white;
}

/* UPDATED: Simplified ABK Data Table */
.abk-data-section {
    margin-bottom: 12px;
}

.abk-table-container {
    overflow-x: auto;
    border: 1px solid var(--border-color);
    border-radius: 6px;
}

.abk-table-compact {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    background: white;
}

.abk-table-compact th {
    background: var(--background-light);
    padding: 8px 10px;
    font-weight: 600;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-color);
    text-align: left;
    white-space: nowrap;
}

.abk-table-compact td {
    padding: 8px 10px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: top;
}

.abk-table-compact tbody tr:last-child td {
    border-bottom: none;
}

.abk-table-compact tbody tr:hover {
    background: var(--background-light);
}

.field-label {
    font-weight: 600;
    color: var(--text-muted);
    background: #f9fafb;
    white-space: nowrap;
    min-width: 70px;
    font-size: 10px;
}

.field-value {
    color: var(--text-dark);
    word-wrap: break-word;
}

/* UPDATED: ABK Name styling */
.abk-name {
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.2;
    margin-bottom: 2px;
}

.abk-nrp {
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 500;
}

/* UPDATED: Compact Document Status */
.document-status-compact {
    margin-bottom: 12px;
    padding: 8px;
    background: var(--background-light);
    border-radius: 6px;
    border: 1px solid var(--border-color);
}

.document-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.document-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    white-space: nowrap;
}

.document-badges {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.doc-badge {
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* UPDATED: Compact Admin Notes */
.admin-notes-compact {
    margin-bottom: 12px;
    padding: 6px 8px;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    font-size: 11px;
    display: flex;
    align-items: flex-start;
    gap: 4px;
}

.notes-text {
    color: var(--text-dark);
    font-style: italic;
    line-height: 1.3;
    flex: 1;
}

/* UPDATED: Compact Card Actions */
.arsip-actions-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
    margin-top: auto;
}

.action-buttons-left {
    display: flex;
    gap: 6px;
}

.submission-info-compact {
    font-size: 10px;
    color: var(--text-muted);
}

/* UPDATED: Compact Badges */
.badge {
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge-success {
    background: var(--success-color);
    color: white;
}

.badge-warning {
    background: var(--warning-color);
    color: white;
}

.badge-secondary {
    background: #6b7280;
    color: white;
}

/* UPDATED: Extra Small Buttons */
.btn-xs {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 4px;
    font-weight: 600;
}

/* UPDATED: Compact Buttons */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
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

.btn-outline-info {
    background: transparent;
    color: var(--info-color);
    border: 1px solid var(--info-color);
}

.btn-outline-info:hover {
    background: var(--info-color);
    color: white;
    text-decoration: none;
}

.btn-outline-secondary {
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}

.btn-outline-secondary:hover {
    background: var(--text-muted);
    color: white;
    text-decoration: none;
}

/* Form Controls */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 13px;
}

.form-select,
.form-control {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 13px;
    transition: var(--transition);
}

.form-select:focus,
.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    outline: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-description {
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.empty-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

/* Alert styling */
.alert {
    border-radius: var(--border-radius);
    border: none;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 13px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
}

/* Dropdown Menus */
.dropdown-menu {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    box-shadow: var(--shadow-medium);
    font-size: 11px;
    min-width: 120px;
}

.dropdown-item {
    padding: 6px 12px;
    font-size: 11px;
}

.dropdown-item:hover {
    background: var(--background-light);
    color: var(--text-dark);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .arsip-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }
}

@media (max-width: 992px) {
    .arsip-grid {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 12px;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: stretch;
    }
}

@media (max-width: 768px) {
    .arsip-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 10px;
    }
    
    .arsip-actions-compact {
        flex-direction: column;
        gap: 6px;
        align-items: stretch;
    }
    
    .action-buttons-left {
        justify-content: space-between;
    }
    
    .abk-table-compact {
        font-size: 10px;
    }
    
    .abk-table-compact th,
    .abk-table-compact td {
        padding: 6px 8px;
    }
}

@media (max-width: 576px) {
    .page-header,
    .filter-body,
    .results-body {
        padding: 16px;
    }
    
    .arsip-card {
        padding: 12px;
    }
    
    .arsip-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced search functionality
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mencari...';
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Subtle card hover effects
    const arsipCards = document.querySelectorAll('.arsip-card');
    arsipCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Lazy loading for large datasets
    const observerOptions = {
        root: null,
        rootMargin: '50px',
        threshold: 0.1
    };

    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    arsipCards.forEach(card => {
        cardObserver.observe(card);
    });
});
</script>
@endpush