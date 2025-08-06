{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaABK\export.blade.php --}}
@extends('layouts.app')

@section('title', 'Export & Import ABK - SertijabPELNI')

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
                        <li class="breadcrumb-item">
                            <a href="{{ route('abk.index') }}">
                                <i class="bi bi-people"></i>
                                Data ABK
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-arrow-down-up"></i>
                            Export & Import
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-arrow-down-up"></i>
                    Export & Import Data ABK
                </h1>
                <p class="page-subtitle">Kelola data ABK melalui export dan import file</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Export Section -->
        <div class="col-lg-6 mb-4">
            <div class="feature-card export-card">
                <div class="feature-header">
                    <div class="feature-icon bg-primary">
                        <i class="bi bi-download"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">Export Data ABK</h4>
                        <p class="feature-subtitle">Download data ABK dalam format Excel atau PDF</p>
                    </div>
                </div>

                <div class="feature-body">
                    <!-- Export Options -->
                    <div class="export-options">
                        <h6 class="options-title">
                            <i class="bi bi-gear me-2"></i>
                            Pilihan Export
                        </h6>
                        
                        <form id="exportForm" class="export-form">
                            <!-- Filter Kapal -->
                            <div class="form-group mb-3">
                                <label for="export_kapal" class="form-label">Filter Kapal</label>
                                <select class="form-select" id="export_kapal" name="export_kapal">
                                    <option value="">Semua Kapal</option>
                                    @foreach($daftarKapal ?? [] as $kapal)
                                        <option value="{{ $kapal->id_kapal }}">{{ $kapal->nama_kapal }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Status -->
                            <div class="form-group mb-3">
                                <label for="export_status" class="form-label">Filter Status</label>
                                <select class="form-select" id="export_status" name="export_status">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                    <option value="Mutasi Keluar">Mutasi Keluar</option>
                                </select>
                            </div>

                            <!-- Filter Tanggal -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="export_start_date" class="form-label">Dari Tanggal</label>
                                        <input type="date" class="form-control" id="export_start_date" name="export_start_date">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="export_end_date" class="form-label">Sampai Tanggal</label>
                                        <input type="date" class="form-control" id="export_end_date" name="export_end_date">
                                    </div>
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="export-actions">
                                <button type="button" class="btn btn-success" onclick="exportData('excel')">
                                    <i class="bi bi-file-excel me-2"></i>
                                    Export Excel
                                    <span class="export-spinner d-none">
                                        <span class="spinner-border spinner-border-sm ms-2" role="status"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="exportData('pdf')">
                                    <i class="bi bi-file-pdf me-2"></i>
                                    Export PDF
                                    <span class="export-spinner d-none">
                                        <span class="spinner-border spinner-border-sm ms-2" role="status"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Export Statistics -->
                    <div class="export-stats">
                        <h6 class="stats-title">
                            <i class="bi bi-graph-up me-2"></i>
                            Statistik Export
                        </h6>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $exportStats['total_abk'] ?? 0 }}</div>
                                <div class="stat-label">Total ABK</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $exportStats['aktif'] ?? 0 }}</div>
                                <div class="stat-label">ABK Aktif</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $exportStats['kapal'] ?? 0 }}</div>
                                <div class="stat-label">Total Kapal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Section -->
        <div class="col-lg-6 mb-4">
            <div class="feature-card import-card">
                <div class="feature-header">
                    <div class="feature-icon bg-success">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">Import Data Mutasi ABK</h4>
                        <p class="feature-subtitle">Upload file Excel atau PDF untuk menambah data mutasi</p>
                    </div>
                </div>

                <div class="feature-body">
                    <!-- Template Download -->
                    <div class="template-section mb-4">
                        <h6 class="template-title">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Download Template
                        </h6>
                        <p class="template-description">Download template terlebih dahulu untuk format yang sesuai</p>
                        <div class="template-actions">
                            <a href="{{ route('abk.template.excel') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-file-excel me-2"></i>
                                Template Excel
                            </a>
                            <a href="{{ route('abk.template.pdf') }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-file-pdf me-2"></i>
                                Template PDF
                            </a>
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="upload-section">
                        <h6 class="upload-title">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload File
                        </h6>
                        
                        <form id="importForm" enctype="multipart/form-data" class="import-form">
                            @csrf
                            
                            <!-- Upload Area -->
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <h6 class="upload-text">Drag & Drop file atau klik untuk browse</h6>
                                    <p class="upload-subtitle">Mendukung format: .xlsx, .xls, .pdf (Max: 10MB)</p>
                                    <input type="file" id="importFile" name="import_file" accept=".xlsx,.xls,.pdf" style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('importFile').click()">
                                        <i class="bi bi-folder2-open me-2"></i>
                                        Pilih File
                                    </button>
                                </div>
                            </div>

                            <!-- Selected File Info -->
                            <div id="fileInfo" class="file-info d-none">
                                <div class="file-details">
                                    <div class="file-icon">
                                        <i class="bi bi-file-earmark"></i>
                                    </div>
                                    <div class="file-content">
                                        <div class="file-name"></div>
                                        <div class="file-size"></div>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeFile()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Import Options -->
                        <div id="importOptions" class="import-options d-none">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Import</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="import_type" id="import_mutasi" value="mutasi" checked>
                                    <label class="form-check-label" for="import_mutasi">
                                        <strong>Data Mutasi ABK</strong>
                                        <small class="d-block text-muted">Import data mutasi serah terima jabatan</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="import_type" id="import_abk" value="abk">
                                    <label class="form-check-label" for="import_abk">
                                        <strong>Data ABK Baru</strong>
                                        <small class="d-block text-muted">Import data ABK baru ke sistem</small>
                                    </label>
                                </div>
                            </div>

                            <!-- PERBAIKAN: Tambahkan hidden input untuk memastikan nilai selalu dikirim -->
                            <input type="hidden" name="skip_duplicates" value="0">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" value="1" checked>
                                <label class="form-check-label" for="skipDuplicates">
                                    Skip data duplikat (berdasarkan NRP)
                                </label>
                            </div>
                        </div>

                            <!-- Import Actions -->
                            <div id="importActions" class="import-actions d-none">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-2"></i>
                                    <span class="import-text">Import Data</span>
                                    <span class="import-spinner d-none">
                                        <span class="spinner-border spinner-border-sm ms-2" role="status"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetImport()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Import Progress -->
                    <div id="importProgress" class="import-progress d-none">
                        <div class="progress-header">
                            <h6 class="progress-title">
                                <i class="bi bi-hourglass-split me-2"></i>
                                Sedang Memproses...
                            </h6>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        <div class="progress-info">
                            <span id="progressText">Mempersiapkan import...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Import Export History -->
<div class="row">
    <div class="col-12">
        <div class="history-card">
            <div class="history-header">
                <div class="header-left">
                    <h5 class="history-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Riwayat Import & Export Terbaru
                    </h5>
                    <p class="history-subtitle">10 aktivitas terakhir dalam sistem</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshHistory()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Refresh
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportHistory()">
                        <i class="bi bi-file-earmark-excel"></i>
                        Export
                    </button>
                </div>
            </div>
            <div class="history-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="12%">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Waktu
                                </th>
                                <th width="15%">
                                    <i class="bi bi-file-earmark me-1"></i>
                                    File
                                </th>
                                <th width="10%">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Aktivitas
                                </th>
                                <th width="10%">
                                    <i class="bi bi-tag me-1"></i>
                                    Jenis
                                </th>
                                <th width="10%">
                                    <i class="bi bi-check-square me-1"></i>
                                    Status
                                </th>
                                <th width="15%">
                                    <i class="bi bi-bar-chart me-1"></i>
                                    Progress
                                </th>
                                <th width="12%">
                                    <i class="bi bi-person me-1"></i>
                                    Admin
                                </th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            @forelse($importHistory ?? [] as $history)
                            <tr class="history-row" data-id="{{ $history->id }}">
                                <!-- Waktu -->
                                <td>
                                    <div class="date-info">
                                        <div class="date-text fw-semibold">
                                            {{ $history->created_at->format('d M Y') }}
                                        </div>
                                        <small class="time-text text-muted">
                                            {{ $history->created_at->format('H:i:s') }}
                                        </small>
                                        <div class="relative-time">
                                            <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </td>

                                <!-- File -->
                                <td>
                                    <div class="file-info">
                                        <div class="file-main d-flex align-items-center">
                                            <i class="bi {{ $history->tipe === 'import' ? 'bi-file-earmark-arrow-up text-success' : 'bi-file-earmark-arrow-down text-primary' }} me-2 fs-5"></i>
                                            <div class="file-details">
                                                <div class="file-name fw-medium text-truncate" title="{{ $history->file_name }}">
                                                    {{ Str::limit($history->file_name, 20) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Aktivitas -->
                                <td>
                                    <div class="activity-info">
                                        <span class="badge rounded-pill {{ $history->tipe === 'import' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }}">
                                            <i class="bi {{ $history->tipe_icon }} me-1"></i>
                                            {{ ucfirst($history->tipe) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Jenis -->
                                <td>
                                    <span class="badge bg-info-subtle text-info rounded-pill">
                                        {{ $history->jenis_label }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <div class="status-container">
                                        <span class="badge {{ $history->status_badge_class }} d-flex align-items-center gap-1">
                                            <i class="bi {{ $history->status_icon }}"></i>
                                            @switch($history->status)
                                                @case('success')
                                                    Berhasil
                                                    @break
                                                @case('failed') 
                                                    Gagal
                                                    @break
                                                @case('warning')
                                                    Peringatan
                                                    @break
                                                @case('processing')
                                                    Proses
                                                    @break
                                                @default
                                                    {{ ucfirst($history->status) }}
                                            @endswitch
                                        </span>
                                        
                                        @if($history->success_rate > 0)
                                        <div class="mt-1">
                                            <small class="text-muted">{{ $history->success_rate }}% berhasil</small>
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Progress -->
                                <td>
                                    <div class="progress-info">
                                        <div class="progress-stats d-flex justify-content-between mb-1">
                                            <small class="text-muted">Total: {{ number_format($history->processed_records + $history->failed_records) }}</small>
                                            @if($history->durasi_proses)
                                            <small class="text-muted">{{ $history->formatted_duration }}</small>
                                            @endif
                                        </div>
                                        
                                        @if($history->total_records > 0)
                                        <div class="progress" style="height: 6px;">
                                            @php 
                                                $successPercent = ($history->processed_records / $history->total_records) * 100;
                                                $failedPercent = ($history->failed_records / $history->total_records) * 100;
                                                $skippedPercent = (($history->jumlah_dilewati ?? 0) / $history->total_records) * 100;
                                            @endphp
                                            
                                            @if($successPercent > 0)
                                            <div class="progress-bar bg-success" style="width: {{ $successPercent }}%" 
                                                 title="Berhasil: {{ $history->processed_records }}"></div>
                                            @endif
                                            
                                            @if($skippedPercent > 0)
                                            <div class="progress-bar bg-warning" style="width: {{ $skippedPercent }}%" 
                                                 title="Dilewati: {{ $history->jumlah_dilewati ?? 0 }}"></div>
                                            @endif
                                            
                                            @if($failedPercent > 0)
                                            <div class="progress-bar bg-danger" style="width: {{ $failedPercent }}%" 
                                                 title="Gagal: {{ $history->failed_records }}"></div>
                                            @endif
                                        </div>
                                        
                                        <div class="progress-details mt-1">
                                            <div class="row text-center g-0">
                                                <div class="col">
                                                    <small class="text-success fw-semibold">{{ number_format($history->processed_records) }}</small>
                                                    <div><small class="text-muted">Berhasil</small></div>
                                                </div>
                                                @if(($history->jumlah_dilewati ?? 0) > 0)
                                                <div class="col">
                                                    <small class="text-warning fw-semibold">{{ number_format($history->jumlah_dilewati ?? 0) }}</small>
                                                    <div><small class="text-muted">Dilewati</small></div>
                                                </div>
                                                @endif
                                                @if($history->failed_records > 0)
                                                <div class="col">
                                                    <small class="text-danger fw-semibold">{{ number_format($history->failed_records) }}</small>
                                                    <div><small class="text-muted">Gagal</small></div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center py-2">
                                            <small class="text-muted">Tidak ada data</small>
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Admin -->
                                <td>
                                    <div class="admin-info">
                                        <div class="admin-name fw-medium">{{ $history->admin_name }}</div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                        <h6 class="text-muted">Belum ada riwayat aktivitas</h6>
                                        <p class="text-muted small">Aktivitas import dan export akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Stats -->
                @if($importHistory && $importHistory->count() > 0)
                <div class="history-summary mt-3 pt-3 border-top">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-value text-primary">{{ $importHistory->where('tipe', 'import')->count() }}</span>
                                <small class="summary-label d-block text-muted">Import</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-value text-success">{{ $importHistory->where('tipe', 'export')->count() }}</span>
                                <small class="summary-label d-block text-muted">Export</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-value text-success">{{ $importHistory->where('status', 'success')->count() }}</span>
                                <small class="summary-label d-block text-muted">Berhasil</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-item">
                                <span class="summary-value text-danger">{{ $importHistory->where('status', 'failed')->count() }}</span>
                                <small class="summary-label d-block text-muted">Gagal</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<!-- Import Result Modal -->
<div class="modal fade" id="importResultModal" tabindex="-1" aria-labelledby="importResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importResultModalLabel">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Hasil Import
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="importResultContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('abk.index') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>
                    Lihat Data ABK
                </a>
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
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f8fafc;
    --border-radius: 12px;
    --shadow-light: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 8px rgba(0, 0, 0, 0.15);
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

/* Feature Cards */
.feature-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    height: 100%;
    transition: var(--transition);
}

.feature-card:hover {
    box-shadow: var(--shadow-medium);
    transform: translateY(-2px);
}

.feature-header {
    padding: 24px 24px 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.feature-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.feature-icon.bg-primary {
    background: var(--primary-blue);
}

.feature-icon.bg-success {
    background: var(--success-color);
}

.feature-content {
    flex: 1;
}

.feature-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.feature-subtitle {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}

.feature-body {
    padding: 24px;
}

/* Export Options */
.options-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
}

.export-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.export-actions .btn {
    flex: 1;
    position: relative;
}

.export-spinner.d-none {
    display: none !important;
}

/* Export Statistics */
.export-stats {
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

.stats-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.stat-item {
    text-align: center;
    padding: 12px;
    background: var(--background-light);
    border-radius: 8px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    display: block;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Template Section */
.template-section {
    background: var(--background-light);
    border-radius: 8px;
    padding: 20px;
}

.template-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.template-description {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 12px;
}

.template-actions {
    display: flex;
    gap: 8px;
}

/* Upload Area */
.upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    background: var(--background-light);
}

.upload-area:hover {
    border-color: var(--primary-blue);
    background: rgba(37, 99, 235, 0.05);
}

.upload-area.dragover {
    border-color: var(--primary-blue);
    background: rgba(37, 99, 235, 0.1);
}

.upload-icon {
    font-size: 48px;
    color: var(--text-muted);
    margin-bottom: 16px;
}

.upload-text {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.upload-subtitle {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 16px;
}

/* File Info */
.file-info {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
}

.file-details {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-icon {
    width: 40px;
    height: 40px;
    background: var(--success-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.file-content {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.file-size {
    color: var(--text-muted);
    font-size: 12px;
}

.btn-remove {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: var(--transition);
}

.btn-remove:hover {
    background: var(--danger-color);
    color: white;
}

/* Import Options */
.import-options {
    margin-top: 16px;
    padding: 16px;
    background: var(--background-light);
    border-radius: 8px;
}

.form-check {
    margin-bottom: 12px;
}

.form-check-label strong {
    color: var(--text-dark);
}

/* Import Actions */
.import-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}

.import-actions .btn {
    flex: 1;
}

/* Import Progress */
.import-progress {
    margin-top: 16px;
    padding: 20px;
    background: var(--background-light);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.progress-header {
    margin-bottom: 16px;
}

.progress-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.progress-info {
    text-align: center;
    margin-top: 8px;
}

#progressText {
    color: var(--text-muted);
    font-size: 13px;
}

/* History Card */
.history-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
}

.history-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.history-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.history-body {
    padding: 0;
}

/* Table Styles */
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

.date-info .date-text {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.date-info .time-text {
    color: var(--text-muted);
    font-size: 12px;
}

.file-info {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    padding: 0;
}

.file-name {
    font-size: 13px;
    color: var(--text-dark);
}

.process-info .process-text {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

/* Form Elements */
.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
    font-size: 13px;
}

/* Buttons */
.btn {
    padding: 8px 16px;
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

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}

.btn-outline-secondary:hover {
    background: var(--text-muted);
    color: white;
    border-color: var(--text-muted);
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .export-actions {
        flex-direction: column;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .template-actions {
        flex-direction: column;
    }

    .import-actions {
        flex-direction: column;
    }

    .table-responsive {
        font-size: 13px;
    }

    /* History Table Enhancements */
.history-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
}

.history-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.header-left .history-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-left .history-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 13px;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.history-body {
    padding: 0;
}

/* Table Enhancements */
.table th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 12px;
    border: none;
    padding: 12px 8px;
    white-space: nowrap;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
    border-color: var(--border-color);
    font-size: 13px;
}

.history-row:hover {
    background-color: rgba(37, 99, 235, 0.02);
}

/* Date and Time Info */
.date-info .date-text {
    font-size: 13px;
    color: var(--text-dark);
    line-height: 1.2;
}

.date-info .time-text {
    color: var(--text-muted);
    font-size: 11px;
    line-height: 1.2;
}

.date-info .relative-time {
    margin-top: 2px;
}

/* File Info */
.file-info .file-main {
    align-items: center;
}

.file-details .file-name {
    font-size: 12px;
    color: var(--text-dark);
    line-height: 1.2;
    max-width: 120px;
}

.file-details .file-size {
    font-size: 10px;
    color: var(--text-muted);
}

/* Progress Bars */
.progress {
    background-color: #e9ecef;
    border-radius: 3px;
}

.progress-details .row .col {
    padding: 0 2px;
}

.progress-details small {
    font-size: 10px;
    line-height: 1.1;
}

/* Performance Metrics */
.performance-metric .metric-value {
    font-size: 14px;
    color: var(--text-dark);
}

.performance-metric small {
    font-size: 10px;
    line-height: 1;
}

/* Admin Info */
.admin-info .admin-name {
    font-size: 12px;
    color: var(--text-dark);
    line-height: 1.2;
}

.admin-info small {
    font-size: 10px;
    line-height: 1;
}

/* Status Badges */
.badge.rounded-pill {
    font-size: 10px;
    padding: 4px 8px;
}

.badge.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #198754 !important;
}

.badge.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
}

.badge.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
    color: #0dcaf0 !important;
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
}

.empty-state .bi {
    opacity: 0.3;
}

/* Summary Stats */
.history-summary {
    background: var(--background-light);
    padding: 16px;
    margin: 0 -24px -24px -24px;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.summary-item {
    padding: 8px;
}

.summary-value {
    font-size: 18px;
    font-weight: 700;
    display: block;
}

.summary-label {
    font-size: 11px;
    margin-top: 2px;
}

/* Dropdown */
.dropdown-menu {
    font-size: 12px;
    box-shadow: var(--shadow-medium);
    border: 1px solid var(--border-color);
}

.dropdown-item {
    padding: 6px 12px;
    font-size: 12px;
}

.dropdown-item:hover {
    background-color: var(--background-light);
}

/* Responsive */
@media (max-width: 1200px) {
    .table th,
    .table td {
        padding: 8px 4px;
        font-size: 11px;
    }
    
    .file-details .file-name {
        max-width: 80px;
    }
}

@media (max-width: 768px) {
    .history-header {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .table-responsive {
        font-size: 10px;
    }
    
    .history-summary .row .col-md-3 {
        margin-bottom: 8px;
    }
}
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const importFile = document.getElementById('importFile');
    const fileInfo = document.getElementById('fileInfo');
    const importOptions = document.getElementById('importOptions');
    const importActions = document.getElementById('importActions');
    const importForm = document.getElementById('importForm');
    const importProgress = document.getElementById('importProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    let selectedFile = null; // Store selected file
    let isProcessing = false; // Flag untuk mencegah multiple processing

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!selectedFile && !isProcessing) {
            uploadArea.classList.add('dragover');
        }
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!selectedFile && !isProcessing) {
            uploadArea.classList.remove('dragover');
        }
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragover');
        
        if (!selectedFile && !isProcessing && e.dataTransfer.files.length > 0) {
            handleFileSelection(e.dataTransfer.files[0]);
        }
    });

    // Click to select file
    uploadArea.addEventListener('click', function(e) {
        // Jangan trigger jika klik pada button yang sudah ada
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            return;
        }
        
        if (!selectedFile && !isProcessing) {
            importFile.click();
        }
    });

    // File input change
    importFile.addEventListener('change', function(e) {
        if (e.target.files.length > 0 && !selectedFile && !isProcessing) {
            handleFileSelection(e.target.files[0]);
        }
    });

    // Import form submission
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitImport();
    });

    function handleFileSelection(file) {
        // Prevent multiple selections
        if (selectedFile || isProcessing) {
            console.log('File already selected or processing, ignoring');
            return;
        }

        // Validate file
        if (!validateFile(file)) {
            return;
        }

        // Store selected file
        selectedFile = file;
        
        console.log('File selected:', file.name);

        // Show file info
        showFileInfo(file);
        
        // Show options and actions
        importOptions.classList.remove('d-none');
        importActions.classList.remove('d-none');
        
        // Update upload area appearance
        updateUploadAreaForSelectedFile();
    }

    function updateUploadAreaForSelectedFile() {
        uploadArea.classList.add('file-selected');
        const uploadContent = uploadArea.querySelector('.upload-content');
        if (uploadContent) {
            uploadContent.innerHTML = `
                <i class="bi bi-check-circle-fill text-success upload-icon"></i>
                <h6 class="upload-text text-success">File Terpilih</h6>
                <p class="upload-subtitle">File siap untuk diimport</p>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFile()">
                    <i class="bi bi-x-circle me-2"></i>
                    Hapus File
                </button>
            `;
        }
    }

    function resetUploadArea() {
        uploadArea.classList.remove('file-selected', 'dragover');
        const uploadContent = uploadArea.querySelector('.upload-content');
        if (uploadContent) {
            uploadContent.innerHTML = `
                <i class="bi bi-cloud-upload upload-icon"></i>
                <h6 class="upload-text">Drag & Drop file atau klik untuk browse</h6>
                <p class="upload-subtitle">Mendukung format: .xlsx, .xls, .pdf (Max: 10MB)</p>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('importFile').click()">
                    <i class="bi bi-folder2-open me-2"></i>
                    Pilih File
                </button>
            `;
        }
    }

    function validateFile(file) {
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                             'application/vnd.ms-excel', 
                             'application/pdf'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            showToast('Format file tidak didukung. Gunakan .xlsx, .xls, atau .pdf', 'error');
            return false;
        }

        if (file.size > maxSize) {
            showToast('Ukuran file terlalu besar. Maksimal 10MB', 'error');
            return false;
        }

        return true;
    }

    function showFileInfo(file) {
        const fileName = file.name;
        const fileSize = formatFileSize(file.size);
        const fileExtension = fileName.split('.').pop().toLowerCase();

        let iconClass = 'bi-file-earmark';
        if (fileExtension === 'xlsx' || fileExtension === 'xls') {
            iconClass = 'bi-file-earmark-excel';
        } else if (fileExtension === 'pdf') {
            iconClass = 'bi-file-earmark-pdf';
        }

        fileInfo.querySelector('.file-icon i').className = `bi ${iconClass}`;
        fileInfo.querySelector('.file-name').textContent = fileName;
        fileInfo.querySelector('.file-size').textContent = fileSize;
        
        fileInfo.classList.remove('d-none');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function submitImport() {
        if (!selectedFile) {
            showToast('Silakan pilih file terlebih dahulu', 'error');
            return;
        }

        if (isProcessing) {
            showToast('Import sedang diproses, mohon tunggu', 'warning');
            return;
        }

        isProcessing = true;

        const submitButton = importForm.querySelector('button[type="submit"]');
        const importText = submitButton.querySelector('.import-text');
        const importSpinner = submitButton.querySelector('.import-spinner');

        // Show loading state
        submitButton.disabled = true;
        importText.textContent = 'Mengupload...';
        importSpinner.classList.remove('d-none');

        // Show progress
        importProgress.classList.remove('d-none');
        updateProgress(10, 'Mengupload file...');

        // Create FormData
        const formData = new FormData();
        formData.append('import_file', selectedFile);
        
        // Append form fields
        const importType = document.querySelector('input[name="import_type"]:checked');
        if (importType) {
            formData.append('import_type', importType.value);
        }
        
        // Handle skip_duplicates checkbox
        const skipDuplicatesCheckbox = document.getElementById('skipDuplicates');
        if (skipDuplicatesCheckbox) {
            formData.append('skip_duplicates', skipDuplicatesCheckbox.checked ? '1' : '0');
        } else {
            formData.append('skip_duplicates', '1');
        }
        
        // Append CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.content);
        }

        // Debug: Log form data
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + (value instanceof File ? value.name : value));
        }

        // Submit via AJAX
        fetch('{{ route("abk.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                return response.text().then(text => {
                    let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        if (text.length > 0 && text.length < 200) {
                            errorMessage = text;
                        }
                    }
                    throw new Error(errorMessage);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                simulateProgress(data);
            } else {
                throw new Error(data.message || 'Import gagal');
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            resetFormState();
            showErrorModal(error.message, error.message);
        });
    }

    function resetFormState() {
        const submitButton = importForm.querySelector('button[type="submit"]');
        const importText = submitButton.querySelector('.import-text');
        const importSpinner = submitButton.querySelector('.import-spinner');
        
        submitButton.disabled = false;
        importText.textContent = 'Import Data';
        importSpinner.classList.add('d-none');
        importProgress.classList.add('d-none');
        
        isProcessing = false;
    }

    function showErrorModal(userMessage, technicalMessage) {
        const resultContent = document.getElementById('importResultContent');
        resultContent.innerHTML = `
            <div class="import-result">
                <div class="result-summary mb-3">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading mb-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Import Gagal
                        </h6>
                        <p class="mb-0">${userMessage}</p>
                    </div>
                </div>
                
                <div class="technical-details">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#technicalError">
                        <i class="bi bi-gear me-2"></i>
                        Detail Teknis
                    </button>
                    <div class="collapse mt-3" id="technicalError">
                        <div class="alert alert-secondary">
                            <small><strong>Technical Error:</strong><br>${technicalMessage}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('importResultModal'));
        document.getElementById('importResultModalLabel').innerHTML = `
            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
            Error Import
        `;
        modal.show();
    }

    function simulateProgress(data) {
        let progress = 10;
        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress >= 90) {
                progress = 90;
                clearInterval(interval);
                
                setTimeout(() => {
                    updateProgress(100, 'Import selesai!');
                    showImportResult(data);
                }, 1000);
            }
            
            updateProgress(progress, 'Memproses data...');
        }, 500);
    }

    function updateProgress(percent, text) {
        progressBar.style.width = percent + '%';
        progressText.textContent = text;
    }

    function showImportResult(data) {
        importProgress.classList.add('d-none');
        resetImport();
        
        const resultContent = document.getElementById('importResultContent');
        resultContent.innerHTML = `
            <div class="import-result">
                <div class="result-summary mb-3">
                    <div class="alert alert-${data.success ? 'success' : 'warning'}">
                        <h6 class="alert-heading mb-2">
                            <i class="bi bi-${data.success ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                            ${data.message}
                        </h6>
                    </div>
                </div>
                
                <div class="result-stats">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="stat-box">
                                <div class="stat-number text-primary">${data.total_records || 0}</div>
                                <div class="stat-label">Total Data</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-box">
                                <div class="stat-number text-success">${data.success_records || 0}</div>
                                <div class="stat-label">Berhasil</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-box">
                                <div class="stat-number text-info">${data.skipped_records || 0}</div>
                                <div class="stat-label">Dilewati</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-box">
                                <div class="stat-number text-danger">${data.failed_records || 0}</div>
                                <div class="stat-label">Gagal</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${data.errors && data.errors.length > 0 ? `
                    <div class="result-errors mt-4">
                        <h6>
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Detail Error (${data.errors.length}):
                        </h6>
                        <div class="error-list" style="max-height: 200px; overflow-y: auto;">
                            ${data.errors.slice(0, 10).map(error => `
                                <div class="error-item alert alert-danger py-2 px-3 mb-2">
                                    <small>${error}</small>
                                </div>
                            `).join('')}
                            ${data.errors.length > 10 ? `
                                <div class="text-muted text-center py-2">
                                    <small>... dan ${data.errors.length - 10} error lainnya</small>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('importResultModal'));
        modal.show();
        
        if (typeof refreshHistory === 'function') {
            refreshHistory();
        }
    }

    // Global functions
    window.removeFile = function() {
        console.log('Removing file...');
        
        // Reset everything
        selectedFile = null;
        isProcessing = false;
        importFile.value = '';
        
        // Hide elements
        fileInfo.classList.add('d-none');
        importOptions.classList.add('d-none');
        importActions.classList.add('d-none');
        
        // Reset upload area
        resetUploadArea();
        
        console.log('File removed successfully');
    };

    window.resetImport = function() {
        console.log('Resetting import...');
        removeFile();
        importProgress.classList.add('d-none');
        resetFormState();
    };

    window.refreshHistory = function() {
        location.reload();
    };

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease-out;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast && toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
});

// Export functionality
function exportData(format) {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    
    const button = event.target;
    const spinner = button.querySelector('.export-spinner');
    
    button.disabled = true;
    spinner.classList.remove('d-none');
    
    const params = new URLSearchParams();
    for (let [key, value] of formData) {
        if (value) params.append(key, value);
    }
    
    const url = `/abk/export/${format}?${params.toString()}`;
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `abk_export_${format}_${new Date().toISOString().split('T')[0]}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    setTimeout(() => {
        button.disabled = false;
        spinner.classList.add('d-none');
    }, 2000);
}
</script>
@endpush