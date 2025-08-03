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
                                                    onclick="deleteMutasi({{ $mutasi->id }})"
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .header-actions {
        align-items: stretch;
        width: 100%;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .stats-card {
        text-align: center;
        flex-direction: column;
        gap: 12px;
    }
    
    .filter-form .row {
        gap: 12px;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 24px;
    }
    
    .table-data th,
    .table-data td {
        padding: 12px 8px;
        font-size: 11px;
    }
    
    .kapal-badge,
    .code-badge,
    .status-badge,
    .jenis-badge {
        font-size: 10px;
        padding: 4px 6px;
    }
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

// Action functions
function viewMutasi(id) {
    // Load detail mutasi in modal
    fetch(`/mutasi/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailMutasiContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('detailMutasiModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail mutasi');
        });
}

function editMutasi(id) {
    window.location.href = `/mutasi/${id}/edit`;
}

function deleteMutasi(id) {
    if (confirm('Apakah Anda yakin ingin menghapus mutasi ini?')) {
        fetch(`/mutasi/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mutasi berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus mutasi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus mutasi');
        });
    }
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/mutasi/export?${params.toString()}`, '_blank');
}
</script>
@endpush