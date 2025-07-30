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

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" checked>
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

    <!-- Recent Import History -->
    <div class="row">
        <div class="col-12">
            <div class="history-card">
                <div class="history-header">
                    <h5 class="history-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Riwayat Import Terbaru
                    </h5>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshHistory()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Refresh
                    </button>
                </div>
                <div class="history-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>File</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Data Processed</th>
                                    <th>Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                @forelse($importHistory ?? [] as $history)
                                <tr>
                                    <td>
                                        <div class="date-info">
                                            <div class="date-text">{{ $history->created_at->format('d M Y') }}</div>
                                            <small class="time-text">{{ $history->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="file-info">
                                            <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                            <span class="file-name">{{ $history->file_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($history->import_type) }}</span>
                                    </td>
                                    <td>
                                        @if($history->status == 'success')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Berhasil
                                            </span>
                                        @elseif($history->status == 'failed')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Gagal
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                Proses
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="process-info">
                                            <div class="process-text">{{ $history->processed_records ?? 0 }} / {{ $history->total_records ?? 0 }}</div>
                                            @if($history->failed_records > 0)
                                                <small class="text-danger">{{ $history->failed_records }} gagal</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $history->admin_name ?? 'System' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($history->status == 'success')
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewImportDetail({{ $history->id }})">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @endif
                                            @if($history->error_log)
                                                <button class="btn btn-sm btn-outline-danger" onclick="viewErrorLog({{ $history->id }})">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                        Belum ada riwayat import
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelection(files[0]);
        }
    });

    uploadArea.addEventListener('click', function() {
        importFile.click();
    });

    importFile.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelection(e.target.files[0]);
        }
    });

    // Import form submission
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitImport();
    });

    function handleFileSelection(file) {
        // Validate file
        if (!validateFile(file)) {
            return;
        }

        // Show file info
        showFileInfo(file);
        
        // Show options and actions
        importOptions.classList.remove('d-none');
        importActions.classList.remove('d-none');
    }

    function validateFile(file) {
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                             'application/vnd.ms-excel', 
                             'application/pdf'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan .xlsx, .xls, atau .pdf');
            return false;
        }

        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 10MB');
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
        const formData = new FormData(importForm);

        // Submit via AJAX
        fetch('{{ route("abk.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Simulate progress
                simulateProgress(data);
            } else {
                throw new Error(data.message || 'Import gagal');
            }
        })
        .catch(error => {
            // Reset form
            submitButton.disabled = false;
            importText.textContent = 'Import Data';
            importSpinner.classList.add('d-none');
            importProgress.classList.add('d-none');

            alert('Error: ' + error.message);
            console.error('Import error:', error);
        });
    }

    function simulateProgress(data) {
        let progress = 10;
        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress >= 90) {
                progress = 90;
                clearInterval(interval);
                
                // Final check
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
        // Hide progress
        importProgress.classList.add('d-none');
        
        // Reset form
        resetImport();
        
        // Show result modal
        const resultContent = document.getElementById('importResultContent');
        resultContent.innerHTML = `
            <div class="import-result">
                <div class="result-stats">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-number text-primary">${data.total_records || 0}</div>
                                <div class="stat-label">Total Data</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-number text-success">${data.success_records || 0}</div>
                                <div class="stat-label">Berhasil</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-number text-danger">${data.failed_records || 0}</div>
                                <div class="stat-label">Gagal</div>
                            </div>
                        </div>
                    </div>
                </div>
                ${data.errors && data.errors.length > 0 ? `
                    <div class="result-errors mt-3">
                        <h6>Error Log:</h6>
                        <div class="error-list">
                            ${data.errors.map(error => `<div class="error-item">${error}</div>`).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('importResultModal'));
        modal.show();
        
        // Refresh history
        refreshHistory();
    }

    window.removeFile = function() {
        importFile.value = '';
        fileInfo.classList.add('d-none');
        importOptions.classList.add('d-none');
        importActions.classList.add('d-none');
        uploadArea.classList.remove('dragover');
    };

    window.resetImport = function() {
        removeFile();
        importProgress.classList.add('d-none');
        
        const submitButton = importForm.querySelector('button[type="submit"]');
        const importText = submitButton.querySelector('.import-text');
        const importSpinner = submitButton.querySelector('.import-spinner');
        
        submitButton.disabled = false;
        importText.textContent = 'Import Data';
        importSpinner.classList.add('d-none');
    };

    window.refreshHistory = function() {
        // Implement refresh history functionality
        location.reload();
    };

    window.viewImportDetail = function(id) {
        // Implement view detail functionality
        console.log('View detail for import ID:', id);
    };

    window.viewErrorLog = function(id) {
        // Implement view error log functionality
        console.log('View error log for import ID:', id);
    };
});

// Export functionality
function exportData(format) {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    
    const button = event.target;
    const spinner = button.querySelector('.export-spinner');
    
    // Show loading
    button.disabled = true;
    spinner.classList.remove('d-none');
    
    // Build URL with parameters
    const params = new URLSearchParams();
    for (let [key, value] of formData) {
        if (value) params.append(key, value);
    }
    
    const url = `{{ route('abk.export') }}/${format}?${params.toString()}`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = `abk_export_${format}_${new Date().toISOString().split('T')[0]}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button
    setTimeout(() => {
        button.disabled = false;
        spinner.classList.add('d-none');
    }, 2000);
}
</script>
@endpush