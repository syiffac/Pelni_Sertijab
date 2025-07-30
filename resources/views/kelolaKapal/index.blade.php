{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaKapal\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Kapal - SertijabPELNI')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-house"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-ship"></i>
                            Data Kapal
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-ship-fill"></i>
                    Data Kapal
                </h1>
                <p class="page-subtitle">Kelola data kapal PELNI</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('kapal.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Kapal
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-ship"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $statistics['total_kapal'] ?? 0 }}</div>
                    <div class="stat-label">Total Kapal</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $statistics['total_abk'] ?? 0 }}</div>
                    <div class="stat-label">Total ABK</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kapal Table -->
    <div class="data-card">
        <div class="card-header">
            <div class="header-title">
                <h5 class="card-title">
                    <i class="bi bi-list-ul me-2"></i>
                    Daftar Kapal
                </h5>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari kapal..." class="form-control">
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="kapalTable">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="45%">Nama Kapal</th>
                            <th width="30%">Jenis Kapal</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kapalList ?? [] as $index => $kapal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="kapal-info">
                                    <div class="kapal-name">{{ $kapal->nama_kapal }}</div>
                                    <small class="text-muted">ID: {{ $kapal->id }}</small>
                                </div>
                            </td>
                            <td>{{ $kapal->jenis_kapal }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editKapal({{ $kapal->id }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteKapal({{ $kapal->id }}, '{{ $kapal->nama_kapal }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-ship display-4 text-muted mb-3"></i>
                                    <h6 class="text-muted">Belum ada data kapal</h6>
                                    <p class="text-muted mb-3">Mulai dengan menambahkan kapal pertama</p>
                                    <a href="{{ route('kapal.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Tambah Kapal
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kapal <strong id="kapalName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    Data yang sudah dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash me-2"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <span id="successMessage"></span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Variables */
:root {
    --primary-blue: #2563eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
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

/* Statistics Cards */
.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-icon.bg-primary {
    background: var(--primary-blue);
}

.stat-icon.bg-success {
    background: var(--success-color);
}

.stat-icon.bg-warning {
    background: var(--warning-color);
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1;
}

.stat-label {
    color: var(--text-muted);
    font-size: 14px;
    margin-top: 4px;
}

/* Data Card */
.data-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.search-box .form-control {
    padding-left: 40px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
}

.search-box .form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Table */
.table {
    margin: 0;
}

.table th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    border: none;
    padding: 16px;
}

.table td {
    padding: 16px;
    vertical-align: middle;
    border-color: var(--border-color);
}

.kapal-info .kapal-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.keterangan-text {
    cursor: help;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

.btn {
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-outline-primary {
    background: transparent;
    color: var(--primary-blue);
    border: 1px solid var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
}

.btn-outline-danger {
    background: transparent;
    color: var(--danger-color);
    border: 1px solid var(--danger-color);
}

.btn-outline-danger:hover {
    background: var(--danger-color);
    color: white;
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
}

.modal-header {
    padding: 20px 24px 16px 24px;
}

.modal-body {
    padding: 16px 24px;
}

.modal-footer {
    padding: 16px 24px 20px 24px;
}

/* Toast */
.toast {
    border: none;
    border-radius: 8px;
    box-shadow: var(--shadow-medium);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .search-box {
        width: 100%;
    }

    .action-buttons {
        justify-content: center;
    }

    .table-responsive {
        font-size: 13px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('kapalTable');
    const tbody = table.querySelector('tbody');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// Edit kapal function
function editKapal(id) {
    window.location.href = "{{ route('kapal.edit', ':id') }}".replace(':id', id);
}

// Delete kapal function
function deleteKapal(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('kapalName').textContent = name;
    
    document.getElementById('confirmDelete').onclick = function() {
        // Ubah cara membangun URL
        const url = "/kapal/" + id;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success) {
                showToast(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            modal.hide();
            alert('Terjadi kesalahan saat menghapus data');
            console.error('Error:', error);
        });
    };
    
    modal.show();
}

// Show toast function
function showToast(message) {
    const toast = document.getElementById('successToast');
    const messageElement = document.getElementById('successMessage');
    
    messageElement.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}
</script>
@endpush