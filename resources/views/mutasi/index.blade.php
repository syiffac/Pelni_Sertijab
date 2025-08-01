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
                            <button class="btn btn-outline-primary btn-disabled" disabled>
                                <i class="bi bi-plus-circle me-2"></i>
                                Tambah Mutasi
                            </button>
                            <button class="btn btn-outline-secondary btn-disabled" disabled>
                                <i class="bi bi-download me-2"></i>
                                Export Data
                            </button>
                        </div>
                        <span class="status-badge coming-soon">
                            <i class="bi bi-clock me-1"></i>
                            Coming Soon
                        </span>
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
                            <div class="stats-number">248</div>
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
                            <div class="stats-number">186</div>
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
                            <div class="stats-number">45</div>
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
                            <div class="stats-number">17</div>
                            <div class="stats-label">Mutasi Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Card -->
            <div class="coming-soon-section">
                <div class="coming-soon-card">
                    <div class="coming-soon-content">
                        <div class="coming-soon-icon">
                            <i class="bi bi-table"></i>
                        </div>
                        
                        <h3 class="coming-soon-title">Data Table Mutasi</h3>
                        <p class="coming-soon-subtitle">Fitur manajemen data mutasi sedang dalam pengembangan</p>
                        
                        <div class="feature-preview">
                            <h5 class="preview-title">
                                <i class="bi bi-eye me-2"></i>
                                Fitur yang Akan Tersedia
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="feature-list">
                                        <li>
                                            <i class="bi bi-table text-primary me-2"></i>
                                            Data table dengan pagination
                                        </li>
                                        <li>
                                            <i class="bi bi-search text-success me-2"></i>
                                            Pencarian dan filter mutasi
                                        </li>
                                        <li>
                                            <i class="bi bi-funnel text-info me-2"></i>
                                            Filter berdasarkan kapal & status
                                        </li>
                                        <li>
                                            <i class="bi bi-sort-alpha-down text-warning me-2"></i>
                                            Sorting kolom data
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="feature-list">
                                        <li>
                                            <i class="bi bi-pencil-square text-secondary me-2"></i>
                                            Edit & update mutasi
                                        </li>
                                        <li>
                                            <i class="bi bi-eye text-dark me-2"></i>
                                            Detail view mutasi
                                        </li>
                                        <li>
                                            <i class="bi bi-download text-primary me-2"></i>
                                            Export ke Excel/PDF
                                        </li>
                                        <li>
                                            <i class="bi bi-graph-up text-success me-2"></i>
                                            Laporan mutasi
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Table Preview -->
                        <div class="table-preview">
                            <h5 class="preview-title">
                                <i class="bi bi-table me-2"></i>
                                Preview Struktur Data Table
                            </h5>
                            
                            <div class="table-responsive">
                                <table class="table table-preview-style">
                                    <thead>
                                        <tr>
                                            <th width="10%">ID Mutasi</th>
                                            <th width="15%">ABK</th>
                                            <th width="12%">Jabatan</th>
                                            <th width="15%">Kapal Asal</th>
                                            <th width="15%">Kapal Tujuan</th>
                                            <th width="12%">Tanggal Mutasi</th>
                                            <th width="10%">Status</th>
                                            <th width="11%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="code-badge">MUT-2025-001</span></td>
                                            <td>
                                                <div class="abk-info">
                                                    <strong>Ahmad Wahyu</strong>
                                                    <small class="d-block text-muted">NRP: 24680</small>
                                                </div>
                                            </td>
                                            <td>Mualim I</td>
                                            <td>
                                                <span class="kapal-badge kapal-asal">KM BINAIYA</span>
                                            </td>
                                            <td>
                                                <span class="kapal-badge kapal-tujuan">KM BUKIT RAYA</span>
                                            </td>
                                            <td>15 Mar 2025</td>
                                            <td><span class="status-badge status-completed">Selesai</span></td>
                                            <td>
                                                <div class="action-buttons-mini">
                                                    <button class="btn-action btn-view" disabled><i class="bi bi-eye"></i></button>
                                                    <button class="btn-action btn-edit" disabled><i class="bi bi-pencil"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="code-badge">MUT-2025-002</span></td>
                                            <td>
                                                <div class="abk-info">
                                                    <strong>Siti Nurhaliza</strong>
                                                    <small class="d-block text-muted">NRP: 13579</small>
                                                </div>
                                            </td>
                                            <td>KKM</td>
                                            <td>
                                                <span class="kapal-badge kapal-asal">KM CIREMAI</span>
                                            </td>
                                            <td>
                                                <span class="kapal-badge kapal-tujuan">KM DOROLONDA</span>
                                            </td>
                                            <td>20 Mar 2025</td>
                                            <td><span class="status-badge status-process">Proses</span></td>
                                            <td>
                                                <div class="action-buttons-mini">
                                                    <button class="btn-action btn-view" disabled><i class="bi bi-eye"></i></button>
                                                    <button class="btn-action btn-edit" disabled><i class="bi bi-pencil"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="code-badge">MUT-2025-003</span></td>
                                            <td>
                                                <div class="abk-info">
                                                    <strong>Budi Santoso</strong>
                                                    <small class="d-block text-muted">NRP: 97531</small>
                                                </div>
                                            </td>
                                            <td>Nakhoda</td>
                                            <td>
                                                <span class="kapal-badge kapal-asal">KM EGON</span>
                                            </td>
                                            <td>
                                                <span class="kapal-badge kapal-tujuan">KM FLORES</span>
                                            </td>
                                            <td>25 Mar 2025</td>
                                            <td><span class="status-badge status-pending">Pending</span></td>
                                            <td>
                                                <div class="action-buttons-mini">
                                                    <button class="btn-action btn-view" disabled><i class="bi bi-eye"></i></button>
                                                    <button class="btn-action btn-edit" disabled><i class="bi bi-pencil"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="table-footer-preview">
                                <div class="pagination-preview">
                                    <span class="pagination-info">Menampilkan 1-3 dari 248 data mutasi</span>
                                    <div class="pagination-controls">
                                        <button class="btn-pagination" disabled>‹</button>
                                        <button class="btn-pagination active" disabled>1</button>
                                        <button class="btn-pagination" disabled>2</button>
                                        <button class="btn-pagination" disabled>3</button>
                                        <button class="btn-pagination" disabled>›</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="coming-soon-actions">
                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Preview Tambah Mutasi
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house-door me-2"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>
                        
                        <div class="coming-soon-meta">
                            <div class="meta-grid">
                                <div class="meta-item">
                                    <i class="bi bi-clock me-1"></i>
                                    <small>Estimasi: Q2 2025</small>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-code-slash me-1"></i>
                                    <small>Status: Under Development</small>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-database me-1"></i>
                                    <small>Database: Ready</small>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-ui-checks me-1"></i>
                                    <small>UI/UX: In Progress</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

/* Status Badge */
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-badge.coming-soon {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.status-process {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.status-pending {
    background: #fef3c7;
    color: #92400e;
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

/* Coming Soon Section */
.coming-soon-section {
    margin-top: 20px;
}

.coming-soon-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-medium);
    border: 1px solid var(--border-color);
    padding: 40px;
    text-align: center;
}

.coming-soon-content {
    max-width: 800px;
    margin: 0 auto;
}

.coming-soon-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    animation: tableIconPulse 2s infinite;
}

@keyframes tableIconPulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
    }
}

.coming-soon-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.coming-soon-subtitle {
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 32px;
}

/* Feature Preview */
.feature-preview {
    text-align: left;
    margin: 32px 0;
    background: var(--background-light);
    border-radius: 8px;
    padding: 24px;
}

.preview-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    display: flex;
    align-items: center;
    padding: 6px 0;
    font-size: 14px;
    color: var(--text-dark);
}

/* Table Preview */
.table-preview {
    margin: 32px 0;
    text-align: left;
}

.table-preview-style {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-light);
    margin-bottom: 0;
}

.table-preview-style th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    padding: 12px 8px;
    border-bottom: 2px solid var(--border-color);
}

.table-preview-style td {
    padding: 12px 8px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
    vertical-align: middle;
}

.code-badge {
    background: #f3f4f6;
    color: var(--text-dark);
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 600;
}

.abk-info strong {
    color: var(--text-dark);
    font-size: 13px;
}

.abk-info small {
    font-size: 11px;
}

.kapal-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.kapal-asal {
    background: #fef3c7;
    color: #92400e;
}

.kapal-tujuan {
    background: #d1fae5;
    color: #065f46;
}

.action-buttons-mini {
    display: flex;
    gap: 4px;
}

.btn-action {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 4px;
    cursor: not-allowed;
    opacity: 0.5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.btn-view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-edit {
    background: #fef3c7;
    color: #92400e;
}

.table-footer-preview {
    background: var(--background-light);
    padding: 12px 16px;
    border-top: 1px solid var(--border-color);
    border-radius: 0 0 8px 8px;
}

.pagination-preview {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    font-size: 13px;
    color: var(--text-muted);
}

.pagination-controls {
    display: flex;
    gap: 4px;
}

.btn-pagination {
    width: 32px;
    height: 32px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--text-muted);
    border-radius: 4px;
    cursor: not-allowed;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-pagination.active {
    background: var(--primary-blue);
    color: white;
    border-color: var(--primary-blue);
}

.coming-soon-actions {
    margin: 32px 0 24px;
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.coming-soon-meta {
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    justify-content: center;
}

.meta-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    color: var(--text-muted);
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

.btn-outline-primary {
    background: transparent;
    color: var(--primary-blue);
    border: 2px solid var(--primary-blue);
}

.btn-outline-primary:hover:not(.btn-disabled) {
    background: var(--primary-blue);
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

.btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
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
    
    .coming-soon-card {
        padding: 24px 16px;
        margin: 0 8px;
    }
    
    .coming-soon-icon {
        width: 70px;
        height: 70px;
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    .coming-soon-title {
        font-size: 20px;
    }
    
    .feature-preview {
        padding: 16px;
    }
    
    .coming-soon-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
    }
    
    .meta-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .stats-card {
        text-align: center;
        flex-direction: column;
        gap: 12px;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .pagination-preview {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 24px;
    }
    
    .table-preview-style th,
    .table-preview-style td {
        padding: 8px 4px;
        font-size: 11px;
    }
    
    .kapal-badge,
    .code-badge {
        font-size: 10px;
        padding: 2px 6px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add interactive effects
    const statsCards = document.querySelectorAll('.stats-card');
    const comingSoonCard = document.querySelector('.coming-soon-card');
    
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
    
    // Coming soon card hover effect
    if (comingSoonCard) {
        comingSoonCard.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)';
        });
        
        comingSoonCard.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
    }
    
    // Table preview interactive effect
    const tableRows = document.querySelectorAll('.table-preview-style tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8fafc';
            this.style.cursor = 'not-allowed';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Animate stats numbers
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const target = parseInt(number.textContent);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current);
            }, 30);
        });
    }
    
    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                if (entry.target.classList.contains('stats-card')) {
                    animateNumbers();
                }
            }
        });
    });
    
    // Observe stats cards
    statsCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
    
    // Log page visit for analytics
    console.log('Mutasi Index Page - Coming Soon with Preview Data');
});
</script>
@endpush