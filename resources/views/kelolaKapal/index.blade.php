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
        <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
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
        <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $statistics['total_homebase'] ?? 0 }}</div>
                    <div class="stat-label">Jumlah Home Base</div>
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
                <div class="d-flex gap-2">
                    <button id="resetSortBtn" class="btn btn-sm btn-outline-secondary d-none">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset Urutan
                    </button>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari kapal..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="kapalTable">
                    <thead>
                        <tr>
                            <th width="5%">No. </th>
                            <th width="15%">Kode Kapal</th>
                            <th width="30%">Nama Kapal</th>
                            <th width="20%" class="sortable" data-sort="tipe_pax">
                                Tipe Pax
                                <i class="bi bi-arrow-down-up sort-icon"></i>
                            </th>
                            <th width="15%">Home Base</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kapalList ?? [] as $index => $kapal)
                        <tr data-pax="{{ $kapal->tipe_pax }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kapal->id }}</td>
                            <td>{{ $kapal->nama_kapal }}</td>
                            <td>{{ $kapal->formatted_tipe_pax }}</td>
                            <td>{{ $kapal->home_base ?: '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('kapal.edit', $kapal->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                           onclick="deleteKapal('{{ $kapal->id }}', '{{ $kapal->nama_kapal }}')" 
                                           title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
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

<!-- Toast Container -->
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
    <!-- Sort toast will be added dynamically here -->
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
    height: 100%;
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
    background: linear-gradient(135deg, #2563eb, #3b82f6);
}

.stat-icon.bg-success {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-icon.bg-info {
    background: linear-gradient(135deg, #0284c7, #0ea5e9);
}

.stat-icon.bg-warning {
    background: linear-gradient(135deg, #d97706, #f59e0b);
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

/* Table styles */
.table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    border-bottom: 2px solid var(--border-color);
    padding: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
}

.table tr:hover {
    background-color: rgba(59, 130, 246, 0.05);
}

.table tr:last-child td {
    border-bottom: none;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.action-buttons .btn {
    width: 34px;
    height: 34px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
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

/* Sortable column styling */
.sortable {
    cursor: pointer;
    position: relative;
    user-select: none;
}

.sortable:hover {
    background-color: #f1f5f9;
}

.sort-icon {
    font-size: 12px;
    margin-left: 5px;
    opacity: 0.6;
    transition: var(--transition);
}

.sortable:hover .sort-icon {
    opacity: 1;
}

.sortable.asc .sort-icon {
    opacity: 1;
    transform: rotate(180deg);
}

.sortable.desc .sort-icon {
    opacity: 1;
}

/* Active sort column */
th.sortable.active {
    background-color: #e9f0fd;
    color: var(--primary-blue);
}

th.sortable.active .sort-icon {
    color: var(--primary-blue);
}

/* Row highlighting during sort */
.row-highlight {
    animation: highlightRow 1s ease-out;
}

@keyframes highlightRow {
    0% {
        background-color: rgba(59, 130, 246, 0.1);
    }
    100% {
        background-color: transparent;
    }
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
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tbody.querySelectorAll('tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                // Skip empty state row if it exists
                if (row.querySelector('.empty-state')) return;
                
                // Get text from each cell in this row
                const id = row.cells[1].textContent.toLowerCase();
                const nama = row.cells[2].textContent.toLowerCase();
                const kapasitas = row.cells[3].textContent.toLowerCase();
                const homeBase = row.cells[4].textContent.toLowerCase();
                
                // Check if any cell contains the search term
                const isVisible = id.includes(searchTerm) || 
                                 nama.includes(searchTerm) || 
                                 kapasitas.includes(searchTerm) || 
                                 homeBase.includes(searchTerm);
                
                // Show/hide row
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            // Show empty search message if no results
            let emptySearchRow = tbody.querySelector('.empty-search-row');
            
            if (visibleCount === 0 && searchTerm !== '') {
                if (!emptySearchRow) {
                    emptySearchRow = document.createElement('tr');
                    emptySearchRow.className = 'empty-search-row';
                    emptySearchRow.innerHTML = `
                        <td colspan="6" class="text-center py-4">
                            <div class="empty-state">
                                <i class="bi bi-search text-muted display-4 mb-3"></i>
                                <h6 class="text-muted">Tidak ditemukan hasil</h6>
                                <p class="text-muted">Tidak ada kapal yang cocok dengan pencarian "${searchTerm}"</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(emptySearchRow);
                } else {
                    emptySearchRow.style.display = '';
                    emptySearchRow.querySelector('p').textContent = `Tidak ada kapal yang cocok dengan pencarian "${searchTerm}"`;
                }
            } else if (emptySearchRow) {
                emptySearchRow.style.display = 'none';
            }
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

// Sorting functionality
const sortableHeaders = document.querySelectorAll('th.sortable');
sortableHeaders.forEach(header => {
    header.addEventListener('click', function() {
        // Get the column to sort
        const sortKey = this.dataset.sort;
        const isAscending = !this.classList.contains('asc');
        
        // Reset all headers
        sortableHeaders.forEach(h => {
            h.classList.remove('asc', 'desc', 'active');
        });
        
        // Update current header
        this.classList.add(isAscending ? 'asc' : 'desc', 'active');
        
        // Sort the table
        sortTable(sortKey, isAscending);
    });
});

/**
 * Sort the table by the given column
 */
function sortTable(sortKey, isAscending) {
    const table = document.getElementById('kapalTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state):not(.empty-search-row)'));
    
    // Skip if no rows to sort
    if (rows.length === 0) return;
    
    // Check if we're sorting tipe_pax column
    if (sortKey === 'tipe_pax') {
        rows.sort((rowA, rowB) => {
            const valueA = parseInt(rowA.dataset.pax) || 0;
            const valueB = parseInt(rowB.dataset.pax) || 0;
            
            return isAscending ? valueA - valueB : valueB - valueA;
        });
    }
    
    // Apply sort
    rows.forEach(row => {
        row.classList.add('row-highlight');
        tbody.appendChild(row);
    });
    
    // Update row numbers
    updateRowNumbers();
    
    // Show sort applied toast
    showSortAppliedToast(sortKey, isAscending);
    
    // Show reset button
    toggleResetSortButton(true);
}

/**
 * Update row numbers after sorting
 */
function updateRowNumbers() {
    const rows = document.querySelectorAll('#kapalTable tbody tr:not(.empty-state):not(.empty-search-row)');
    rows.forEach((row, index) => {
        const cell = row.cells[0];
        if (cell) cell.textContent = index + 1;
    });
}

/**
 * Show a toast that sort has been applied
 */
function showSortAppliedToast(sortKey, isAscending) {
    // Create a new toast for sort notification
    const toastContainer = document.querySelector('.toast-container');
    
    // Remove any existing sort toast
    const existingSortToast = document.getElementById('sortToast');
    if (existingSortToast) {
        existingSortToast.remove();
    }
    
    // Create new sort toast
    const sortToast = document.createElement('div');
    sortToast.id = 'sortToast';
    sortToast.className = 'toast';
    sortToast.setAttribute('role', 'alert');
    sortToast.setAttribute('aria-live', 'assertive');
    sortToast.setAttribute('aria-atomic', 'true');
    
    // Format the sort column name for display
    let sortColumnName;
    switch(sortKey) {
        case 'tipe_pax':
            sortColumnName = 'Tipe PAX';
            break;
        default:
            sortColumnName = sortKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
    
    sortToast.innerHTML = `
        <div class="toast-header bg-info text-white">
            <i class="bi bi-sort-numeric-down me-2"></i>
            <strong class="me-auto">Pengurutan Diterapkan</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Data diurutkan berdasarkan <strong>${sortColumnName}</strong> secara 
            <strong>${isAscending ? 'naik ↑' : 'turun ↓'}</strong>
        </div>
    `;
    
    toastContainer.appendChild(sortToast);
    
    // Show the toast
    const bsToast = new bootstrap.Toast(sortToast, {
        delay: 3000
    });
    bsToast.show();
}

// Reset sort button
const resetSortBtn = document.getElementById('resetSortBtn');
if (resetSortBtn) {
    resetSortBtn.addEventListener('click', function() {
        // Reset all headers
        document.querySelectorAll('th.sortable').forEach(h => {
            h.classList.remove('asc', 'desc', 'active');
        });
        
        // Reload the page to reset the sort
        location.reload();
    });
}

// Show/hide reset button when sort is applied
function toggleResetSortButton(isActive) {
    const resetBtn = document.getElementById('resetSortBtn');
    if (resetBtn) {
        if (isActive) {
            resetBtn.classList.remove('d-none');
        } else {
            resetBtn.classList.add('d-none');
        }
    }
}
</script>
@endpush