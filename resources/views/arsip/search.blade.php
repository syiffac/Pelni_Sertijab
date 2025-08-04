{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\search.blade.php --}}

@extends('layouts.app')

@section('title', 'Pencarian Arsip Sertijab')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header dengan styling yang sama seperti arsip index -->
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
                    <i class="bi bi-search"></i>
                    Pencarian Arsip Sertijab
                </h1>
                <p class="page-subtitle">Cari dan filter dokumen arsip serah terima jabatan</p>
            </div>
            <div class="header-actions">
                <div class="action-buttons">
                    <a href="{{ route('arsip.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Arsip
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
    <div class="table-section">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Pencarian
                </h5>
                <div class="table-info">
                    <small class="text-muted">Gunakan filter untuk mempersempit hasil pencarian</small>
                </div>
            </div>
            <div class="card-body">
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
                            <i class="bi bi-search me-1"></i> Cari
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
    <div class="table-section">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="bi bi-list-ul me-2"></i>
                    Hasil Pencarian 
                    @if(isset($arsipList))
                        ({{ $arsipList->total() }} dokumen)
                    @endif
                </h5>
                <div class="table-info">
                    <div class="d-flex gap-2">
                        @if(isset($arsipList) && $arsipList->count() > 0)
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                                <i class="bi bi-check2-all me-1"></i> Aksi Massal
                            </button>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-info" id="select-all-btn">
                            <i class="bi bi-check-all me-1"></i> Pilih Semua
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($arsipList) && $arsipList->count() > 0)
                    <div class="row g-3">
                        @foreach($arsipList as $arsip)
                            <div class="col-lg-4 col-md-6">
                                <div class="arsip-card">
                                    <div class="arsip-card-header">
                                        <div class="form-check">
                                            <input class="form-check-input arsip-checkbox" type="checkbox" 
                                                   value="{{ $arsip->id }}" id="arsip{{ $arsip->id }}">
                                        </div>
                                        <div class="arsip-info">
                                            <h6 class="arsip-title">
                                                {{ $arsip->mutasi->nama_lengkap_turun ?? $arsip->mutasi->nama_lengkap_naik ?? 'N/A' }}
                                            </h6>
                                            <p class="arsip-subtitle">
                                                NRP: {{ $arsip->mutasi->id_abk_turun ?? $arsip->mutasi->id_abk_naik ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <span class="badge {{ $arsip->status_badge }}">
                                            {{ $arsip->status_text }}
                                        </span>
                                    </div>
                                    
                                    <div class="arsip-details">
                                        <div class="detail-item">
                                            <strong>Kapal:</strong> 
                                            {{ $arsip->mutasi->kapal->nama_kapal ?? 'N/A' }}
                                        </div>
                                        <div class="detail-item">
                                            <strong>Jabatan:</strong> 
                                            {{ $arsip->mutasi->jabatanMutasi->nama_jabatan ?? 'N/A' }}
                                        </div>
                                        <div class="detail-item">
                                            <strong>TMT:</strong> 
                                            {{ $arsip->mutasi->TMT ? $arsip->mutasi->TMT->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <div class="detail-item">
                                            <strong>Submit:</strong> 
                                            {{ $arsip->submitted_at ? $arsip->submitted_at->format('d/m/Y H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                    
                                    @if($arsip->catatan_admin)
                                        <div class="arsip-notes">
                                            <em>"{{ Str::limit($arsip->catatan_admin, 50) }}"</em>
                                        </div>
                                    @endif

                                    <div class="arsip-progress">
                                        @php
                                            $progress = $arsip->verification_progress ?? 0;
                                            $progressClass = $progress < 50 ? 'danger' : ($progress < 100 ? 'warning' : 'success');
                                        @endphp
                                        <div class="progress-wrapper">
                                            <div class="progress">
                                                <div class="progress-bar bg-{{ $progressClass }}" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="progress-text">{{ $progress }}% terverifikasi</span>
                                        </div>
                                    </div>
                                    
                                    <div class="arsip-actions">
                                        <a href="{{ route('arsip.index', $arsip->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('arsip.index', $arsip->id) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        @if($arsip->hasAnyDocuments())
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="bi bi-download"></i> File
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($arsip->dokumen_sertijab_path)
                                                        <li><a class="dropdown-item" href="{{ $arsip->sertijab_url }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Sertijab
                                                        </a></li>
                                                    @endif
                                                    @if($arsip->dokumen_familisasi_path)
                                                        <li><a class="dropdown-item" href="{{ $arsip->familisasi_url }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Familisasi
                                                        </a></li>
                                                    @endif
                                                    @if($arsip->dokumen_lampiran_path)
                                                        <li><a class="dropdown-item" href="{{ $arsip->lampiran_url }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf me-1"></i> Lampiran
                                                        </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($arsipList) && $arsipList->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $arsipList->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Arsip Ditemukan</h5>
                        <p class="text-muted mb-3">Coba ubah filter pencarian atau tambah arsip baru</p>
                        <a href="{{ route('arsip.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Arsip
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('arsip.bulk-update-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_status" class="form-label">Ubah Status</label>
                        <select class="form-select" id="bulk_status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="final">Final (Verified)</option>
                            <option value="partial">Partial (Sebagian)</option>
                            <option value="draft">Draft (Pending)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="bulk_notes" name="notes" rows="3" 
                                  placeholder="Catatan untuk perubahan status..."></textarea>
                    </div>
                    <div id="selected-count" class="text-muted small">
                        Belum ada dokumen yang dipilih
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="bulk-submit-btn" disabled>
                        <i class="bi bi-check-circle me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Import styling dari arsip index */
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

/* Table Section */
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

/* Arsip Cards */
.arsip-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 20px;
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.arsip-card:hover {
    border-color: var(--primary-blue);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
}

.arsip-card-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.arsip-info {
    flex: 1;
    min-width: 0;
}

.arsip-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    word-wrap: break-word;
}

.arsip-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

.arsip-details {
    margin-bottom: 16px;
    font-size: 13px;
}

.detail-item {
    margin-bottom: 8px;
    color: var(--text-dark);
}

.detail-item strong {
    color: var(--text-muted);
    font-weight: 600;
}

.arsip-notes {
    margin-bottom: 16px;
    padding: 12px;
    background: var(--background-light);
    border-radius: 8px;
    font-size: 12px;
    color: var(--text-muted);
    font-style: italic;
}

.arsip-progress {
    margin-bottom: 16px;
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

.arsip-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
    flex-wrap: wrap;
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

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
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

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #059669;
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

.btn-outline-warning {
    background: transparent;
    color: var(--warning-color);
    border: 2px solid var(--warning-color);
}

.btn-outline-warning:hover {
    background: var(--warning-color);
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

/* Badges */
.badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bg-success {
    background-color: var(--success-color) !important;
    color: white;
}

.bg-warning {
    background-color: var(--warning-color) !important;
    color: white;
}

.bg-secondary {
    background-color: #6b7280 !important;
    color: white;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

/* Form Controls */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.form-select,
.form-control {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    transition: var(--transition);
}

.form-select:focus,
.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
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
    
    .arsip-actions {
        flex-direction: column;
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
    
    .arsip-card {
        padding: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.arsip-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const bulkSubmitBtn = document.getElementById('bulk-submit-btn');
    const bulkForm = document.querySelector('#bulkActionModal form');
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.arsip-checkbox:checked');
        const count = selected.length;
        
        selectedCount.textContent = count > 0 ? `${count} dokumen dipilih` : 'Belum ada dokumen yang dipilih';
        bulkSubmitBtn.disabled = count === 0;
        
        // Update hidden inputs
        const existingInputs = bulkForm.querySelectorAll('input[name="sertijab_ids[]"]');
        existingInputs.forEach(input => input.remove());
        
        selected.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'sertijab_ids[]';
            hiddenInput.value = checkbox.value;
            bulkForm.appendChild(hiddenInput);
        });
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Select All functionality
    const selectAllBtn = document.getElementById('select-all-btn');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allChecked = document.querySelectorAll('.arsip-checkbox:checked').length === checkboxes.length;
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            updateSelectedCount();
        });
    }
});
</script>
@endpush