{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Arsip Dokumen Sertijab')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('arsip.index') }}">Arsip</a></li>
                        <li class="breadcrumb-item active">Tambah Arsip</li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-plus-fill"></i>
                    Tambah Arsip Dokumen Sertijab
                </h1>
                <p class="page-subtitle">Pilih data mutasi dan upload dokumen sertijab</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('arsip.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- 3-Step Process -->
    <div class="process-container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step-item active" data-step="1">
                <div class="step-circle">
                    <i class="bi bi-ship"></i>
                </div>
                <div class="step-content">
                    <h6 class="step-title">Pilih Kapal</h6>
                    <p class="step-subtitle">Tentukan kapal</p>
                </div>
            </div>
            
            <div class="step-item" data-step="2">
                <div class="step-circle">
                    <i class="bi bi-people"></i>
                </div>
                <div class="step-content">
                    <h6 class="step-title">Pilih Mutasi ABK</h6>
                    <p class="step-subtitle">Pasangan ABK naik turun</p>
                </div>
            </div>
            
            <div class="step-item" data-step="3">
                <div class="step-circle">
                    <i class="bi bi-cloud-upload"></i>
                </div>
                <div class="step-content">
                    <h6 class="step-title">Upload Dokumen</h6>
                    <p class="step-subtitle">File sertijab & lampiran</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form id="arsipForm" action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: Pilih Kapal -->
            <div class="step-content active" data-step="1">
                <div class="step-card">
                    <div class="step-header">
                        <h4 class="step-heading">
                            <i class="bi bi-ship me-2"></i>
                            Pilih Kapal
                        </h4>
                        <p class="step-description">Pilih kapal untuk melihat daftar mutasi ABK yang tersedia</p>
                    </div>
                    
                    <div class="step-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="form-group mb-4">
                                    <label for="kapal_id" class="form-label required">Kapal</label>
                                    <select class="form-select" id="kapal_id" name="kapal_id" required>
                                        <option value="">-- Pilih Kapal --</option>
                                        @foreach($kapalList ?? [] as $kapal)
                                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Pilih kapal untuk melihat data mutasi ABK</div>
                                </div>
                                
                                <!-- Loading indicator -->
                                <div id="loadingMutasi" class="text-center d-none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Memuat data mutasi...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Memuat data mutasi...</p>
                                </div>
                                
                                <!-- No mutasi message -->
                                <div id="noMutasiMessage" class="alert alert-info d-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle me-3 fs-4"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">Belum Ada Data Mutasi</h6>
                                            <p class="mb-2">Kapal ini belum memiliki data mutasi ABK atau semua mutasi sudah memiliki dokumen final.</p>
                                            <a href="{{ route('mutasi.create') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-plus me-1"></i>Tambah Data Mutasi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-footer">
                        <button type="button" class="btn btn-primary btn-next" disabled>
                            <i class="bi bi-arrow-right me-2"></i>
                            Lanjutkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Pilih Mutasi ABK -->
            <div class="step-content" data-step="2">
                <div class="step-card">
                    <div class="step-header">
                        <h4 class="step-heading">
                            <i class="bi bi-people me-2"></i>
                            Pilih Data Mutasi ABK
                        </h4>
                        <p class="step-description">Pilih pasangan ABK naik dan turun dari daftar mutasi yang tersedia</p>
                    </div>
                    
                    <div class="step-body">
                        <div id="mutasiListContainer">
                            <!-- Mutasi list will be populated here -->
                        </div>
                        
                        <input type="hidden" id="selected_mutasi_id" name="mutasi_id" required>
                    </div>
                    
                    <div class="step-footer">
                        <button type="button" class="btn btn-outline-secondary btn-prev">
                            <i class="bi bi-arrow-left me-2"></i>
                            Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary btn-next" disabled>
                            <i class="bi bi-arrow-right me-2"></i>
                            Upload Dokumen
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Upload Dokumen -->
            <div class="step-content" data-step="3">
                <div class="step-card">
                    <div class="step-header">
                        <h4 class="step-heading">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload Dokumen
                        </h4>
                        <p class="step-description">Upload file dokumen sertijab, familisasi, dan lampiran</p>
                    </div>
                    
                    <div class="step-body">
                        <!-- Selected Mutasi Summary -->
                        <div id="selectedMutasiSummary" class="selected-mutasi-summary mb-4">
                            <!-- Summary will be populated here -->
                        </div>

                        <div class="row">
                            <!-- Document Upload Section -->
                            <div class="col-lg-8">
                                <h6 class="section-title">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>
                                    Upload Dokumen
                                </h6>

                                <!-- Sertijab Document (Required) -->
                                <div class="form-group mb-4">
                                    <label for="dokumen_sertijab" class="form-label required">Dokumen Serah Terima Jabatan</label>
                                    <input type="file" class="form-control" id="dokumen_sertijab" name="dokumen_sertijab" 
                                           accept=".pdf" required>
                                    <div class="form-text">Format: PDF, Maksimal: 10MB (Wajib)</div>
                                </div>

                                <!-- Familisasi Document (Optional) -->
                                <div class="form-group mb-4">
                                    <label for="dokumen_familisasi" class="form-label">Dokumen Familisasi</label>
                                    <input type="file" class="form-control" id="dokumen_familisasi" name="dokumen_familisasi" 
                                           accept=".pdf">
                                    <div class="form-text">Format: PDF, Maksimal: 10MB (Opsional)</div>
                                </div>

                                <!-- Lampiran Document (Optional) -->
                                <div class="form-group mb-4">
                                    <label for="dokumen_lampiran" class="form-label">Dokumen Lampiran</label>
                                    <input type="file" class="form-control" id="dokumen_lampiran" name="dokumen_lampiran" 
                                           accept=".pdf">
                                    <div class="form-text">Format: PDF, Maksimal: 10MB (Opsional)</div>
                                </div>

                                <!-- Keterangan -->
                                <div class="form-group mb-4">
                                    <label for="keterangan_dokumen" class="form-label">Keterangan Dokumen</label>
                                    <textarea class="form-control" id="keterangan_dokumen" name="keterangan_dokumen" 
                                              rows="3" placeholder="Keterangan atau catatan untuk dokumen (opsional)"></textarea>
                                </div>
                            </div>

                            <!-- Settings Section -->
                            <div class="col-lg-4">
                                <h6 class="section-title">
                                    <i class="bi bi-gear me-2"></i>
                                    Pengaturan
                                </h6>

                                <div class="form-group mb-3">
                                    <label for="status_verifikasi" class="form-label required">Status Verifikasi</label>
                                    <select class="form-select" id="status_verifikasi" name="status_verifikasi" required>
                                        <option value="draft">Draft - Menunggu Verifikasi</option>
                                        <option value="final">Final - Dokumen Terverifikasi</option>
                                    </select>
                                    <div class="form-text">Tentukan status dokumen setelah upload</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="catatan_admin" class="form-label">Catatan Admin</label>
                                    <textarea class="form-control" id="catatan_admin" name="catatan_admin" 
                                              rows="3" placeholder="Catatan internal admin (opsional)"></textarea>
                                </div>

                                <!-- Upload Requirements -->
                                <div class="upload-requirements">
                                    <h6 class="requirements-title">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Persyaratan File
                                    </h6>
                                    <ul class="requirements-list">
                                        <li><i class="bi bi-check-circle text-success me-2"></i>Format: PDF</li>
                                        <li><i class="bi bi-check-circle text-success me-2"></i>Ukuran maksimal: 10 MB</li>
                                        <li><i class="bi bi-check-circle text-success me-2"></i>Dokumen jelas dan terbaca</li>
                                        <li><i class="bi bi-check-circle text-success me-2"></i>Minimal upload Sertijab</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-footer">
                        <button type="button" class="btn btn-outline-secondary btn-prev">
                            <i class="bi bi-arrow-left me-2"></i>
                            Sebelumnya
                        </button>
                        <button type="submit" class="btn btn-success btn-submit">
                            <i class="bi bi-check-circle me-2"></i>
                            <span class="submit-text">Simpan Arsip</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="success-icon mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="modal-title mb-2">Berhasil!</h5>
                <p class="mb-4">Dokumen arsip sertijab berhasil disimpan</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('arsip.search') }}" class="btn btn-primary">
                        <i class="bi bi-archive me-2"></i>Lihat Arsip
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="bi bi-plus me-2"></i>Tambah Lagi
                    </button>
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
    --border-radius: 8px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s ease;
}

/* Page Layout */
.page-header {
    background: white;
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
    font-size: 14px;
}

/* Process Container */
.process-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

/* Progress Steps */
.progress-steps {
    display: flex;
    justify-content: center;
    padding: 24px;
    background: var(--background-light);
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 20%;
    right: 20%;
    height: 2px;
    background: var(--border-color);
    z-index: 1;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
    max-width: 200px;
    position: relative;
    z-index: 2;
}

.step-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: var(--text-muted);
    transition: var(--transition);
}

.step-item.active .step-circle {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
    color: white;
}

.step-item.completed .step-circle {
    background: var(--success-color);
    border-color: var(--success-color);
    color: white;
}

.step-content {
    text-align: center;
}

.step-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.step-subtitle {
    font-size: 11px;
    color: var(--text-muted);
    margin: 2px 0 0 0;
}

.step-item.active .step-title {
    color: var(--primary-blue);
}

/* Step Content */
.step-content[data-step] {
    display: none;
}

.step-content[data-step].active {
    display: block;
}

.step-card {
    padding: 32px;
}

.step-header {
    text-align: center;
    margin-bottom: 24px;
}

.step-heading {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.step-heading i {
    color: var(--primary-blue);
}

.step-description {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

.step-body {
    margin-bottom: 24px;
}

.step-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

/* Mutasi Cards */
.mutasi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.mutasi-card {
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 16px;
    cursor: pointer;
    transition: var(--transition);
    background: white;
}

.mutasi-card:hover {
    border-color: var(--primary-blue);
    box-shadow: var(--shadow-medium);
}

.mutasi-card.selected {
    border-color: var(--primary-blue);
    background: #eff6ff;
    box-shadow: var(--shadow-medium);
}

.mutasi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.mutasi-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.mutasi-meta {
    font-size: 12px;
    color: var(--text-muted);
}

.mutasi-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-sementara {
    background: #dbeafe;
    color: #1e40af;
}

.badge-definitif {
    background: #dcfce7;
    color: #166534;
}

.abk-info {
    margin-bottom: 12px;
}

.abk-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    background: var(--background-light);
    border-radius: 4px;
    margin-bottom: 4px;
    font-size: 12px;
}

.abk-label {
    font-weight: 600;
    color: var(--text-muted);
    width: 60px;
}

.abk-value {
    flex: 1;
    color: var(--text-dark);
}

.no-abk-turun {
    font-style: italic;
    color: var(--text-muted);
}

/* Selected Mutasi Summary */
.selected-mutasi-summary {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 16px;
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.summary-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.summary-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
}

.summary-label {
    font-weight: 600;
    color: var(--text-muted);
}

.summary-value {
    color: var(--text-dark);
    text-align: right;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
    font-size: 14px;
}

.form-label.required::after {
    content: " *";
    color: var(--danger-color);
}

.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    outline: none;
}

.form-text {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}

.section-title i {
    color: var(--primary-blue);
}

/* Upload Requirements */
.upload-requirements {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 16px;
    margin-top: 16px;
}

.requirements-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirements-list li {
    display: flex;
    align-items: center;
    padding: 4px 0;
    font-size: 12px;
    color: var(--text-dark);
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #1d4ed8;
    color: white;
    text-decoration: none;
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #059669;
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

/* Loading State */
.btn.loading .submit-text {
    display: none;
}

.btn.loading .spinner-border {
    display: inline-block !important;
}

/* Success Modal */
.success-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    background: var(--success-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 12px;
    }
    
    .progress-steps {
        flex-direction: column;
        gap: 16px;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .step-item {
        flex-direction: row;
        max-width: none;
    }
    
    .step-card {
        padding: 20px;
    }
    
    .mutasi-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .summary-content {
        grid-template-columns: 1fr;
    }
    
    .step-footer {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    let selectedMutasiId = null;
    let selectedMutasiData = null;
    
    // Elements
    const stepItems = document.querySelectorAll('.step-item');
    const stepContents = document.querySelectorAll('.step-content[data-step]');
    const kapalSelect = document.getElementById('kapal_id');
    const form = document.getElementById('arsipForm');
    
    // Initialize
    updateStepDisplay();
    
    // Kapal selection
    kapalSelect.addEventListener('change', function() {
        const kapalId = this.value;
        const nextBtn = document.querySelector('[data-step="1"] .btn-next');
        
        if (kapalId) {
            loadMutasiByKapal(kapalId);
            nextBtn.disabled = false;
        } else {
            document.getElementById('mutasiListContainer').innerHTML = '';
            nextBtn.disabled = true;
            hideMessages();
        }
    });
    
    // Step navigation
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStepElement = this.closest('.step-content[data-step]');
            const step = parseInt(currentStepElement.getAttribute('data-step'));
            
            if (validateStep(step)) {
                if (step < 3) {
                    currentStep = step + 1;
                    updateStepDisplay();
                    
                    if (currentStep === 3) {
                        updateMutasiSummary();
                    }
                }
            }
        });
    });
    
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateStep(3)) {
            submitForm();
        }
    });
    
    function loadMutasiByKapal(kapalId) {
        const container = document.getElementById('mutasiListContainer');
        const loading = document.getElementById('loadingMutasi');
        const noMutasiMsg = document.getElementById('noMutasiMessage');
        
        // Show loading
        loading.classList.remove('d-none');
        hideMessages();
        container.innerHTML = '';
        
        fetch(`{{ route('arsip.get-mutasi-by-kapal') }}?kapal_id=${kapalId}`)
        .then(response => response.json())
        .then(data => {
            loading.classList.add('d-none');
            
            if (data.success && data.data.length > 0) {
                displayMutasiList(data.data);
            } else {
                noMutasiMsg.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loading.classList.add('d-none');
            showAlert('Terjadi kesalahan saat memuat data mutasi', 'danger');
        });
    }
    
    function displayMutasiList(mutasiList) {
        const container = document.getElementById('mutasiListContainer');
        
        let html = '<div class="mutasi-grid">';
        
        mutasiList.forEach(mutasi => {
            const badgeClass = mutasi.jenis_mutasi === 'Definitif' ? 'badge-definitif' : 'badge-sementara';
            const statusText = mutasi.has_sertijab ? 
                `Sudah ada dokumen (${mutasi.sertijab_status})` : 
                'Belum ada dokumen';
            
            html += `
                <div class="mutasi-card" data-mutasi-id="${mutasi.id}" onclick="selectMutasi(${mutasi.id})">
                    <div class="mutasi-header">
                        <div>
                            <h6 class="mutasi-title">${mutasi.nama_mutasi}</h6>
                            <div class="mutasi-meta">${mutasi.periode_mutasi}</div>
                        </div>
                        <span class="mutasi-badge ${badgeClass}">
                            ${mutasi.jenis_mutasi}
                        </span>
                    </div>
                    
                    <div class="abk-info">
                        <div class="abk-row">
                            <span class="abk-label">Naik:</span>
                            <span class="abk-value">
                                <strong>${mutasi.abk_naik.nama}</strong> (${mutasi.abk_naik.nrp})
                                <br><small>${mutasi.abk_naik.jabatan} → ${mutasi.abk_naik.jabatan_mutasi}</small>
                            </span>
                        </div>
                        <div class="abk-row">
                            <span class="abk-label">Turun:</span>
                            <span class="abk-value">
                                ${mutasi.ada_abk_turun ? 
                                    `<strong>${mutasi.abk_turun.nama}</strong> (${mutasi.abk_turun.nrp})
                                    <br><small>${mutasi.abk_turun.jabatan}</small>` :
                                    '<span class="no-abk-turun">Tidak ada ABK turun</span>'
                                }
                            </span>
                        </div>
                    </div>
                    
                    <div class="mutasi-footer">
                        <small class="text-muted">${statusText}</small>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
        
        // Store mutasi data for later use
        window.mutasiData = {};
        mutasiList.forEach(mutasi => {
            window.mutasiData[mutasi.id] = mutasi;
        });
    }
    
    function selectMutasi(mutasiId) {
        // Remove previous selection
        document.querySelectorAll('.mutasi-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked card
        document.querySelector(`[data-mutasi-id="${mutasiId}"]`).classList.add('selected');
        
        // Store selection
        selectedMutasiId = mutasiId;
        selectedMutasiData = window.mutasiData[mutasiId];
        document.getElementById('selected_mutasi_id').value = mutasiId;
        
        // Enable next button
        document.querySelector('[data-step="2"] .btn-next').disabled = false;
    }
    
    function updateMutasiSummary() {
        if (!selectedMutasiData) return;
        
        const container = document.getElementById('selectedMutasiSummary');
        const mutasi = selectedMutasiData;
        
        container.innerHTML = `
            <div class="summary-header">
                <i class="bi bi-info-circle text-primary"></i>
                <h6 class="summary-title">Data Mutasi Terpilih</h6>
            </div>
            <div class="summary-content">
                <div class="summary-item">
                    <span class="summary-label">Nama Mutasi:</span>
                    <span class="summary-value">${mutasi.nama_mutasi}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Jenis:</span>
                    <span class="summary-value">${mutasi.jenis_mutasi}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Periode:</span>
                    <span class="summary-value">${mutasi.periode_mutasi}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">ABK Naik:</span>
                    <span class="summary-value">${mutasi.abk_naik.nama} (${mutasi.abk_naik.nrp})</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">ABK Turun:</span>
                    <span class="summary-value">
                        ${mutasi.ada_abk_turun ? 
                            `${mutasi.abk_turun.nama} (${mutasi.abk_turun.nrp})` : 
                            'Tidak ada'
                        }
                    </span>
                </div>
            </div>
        `;
    }
    
    function validateStep(step) {
        switch(step) {
            case 1:
                return kapalSelect.value !== '';
            case 2:
                return selectedMutasiId !== null;
            case 3:
                const sertijabFile = document.getElementById('dokumen_sertijab').files[0];
                return sertijabFile !== undefined;
            default:
                return true;
        }
    }
    
    function updateStepDisplay() {
        // Update step indicators
        stepItems.forEach((item, index) => {
            const stepNumber = index + 1;
            item.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                item.classList.add('completed');
            } else if (stepNumber === currentStep) {
                item.classList.add('active');
            }
        });
        
        // Update step content
        stepContents.forEach((content, index) => {
            content.classList.toggle('active', index + 1 === currentStep);
        });
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function submitForm() {
    const submitBtn = document.querySelector('.btn-submit');
    const formData = new FormData(form);
    
    // Debug form data
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Validasi client-side
    if (!selectedMutasiId) {
        showAlert('Silakan pilih data mutasi terlebih dahulu', 'danger');
        return;
    }
    
    const sertijabFile = document.getElementById('dokumen_sertijab').files[0];
    if (!sertijabFile) {
        showAlert('Dokumen Sertijab wajib diupload', 'danger');
        return;
    }
    
    // Show loading
    submitBtn.disabled = true;
    submitBtn.classList.add('loading');
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showSuccessModal(data.message);
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 2000);
        } else {
            // Handle validation errors
            if (data.errors) {
                let errorMessage = 'Terjadi kesalahan validasi:\n';
                Object.keys(data.errors).forEach(field => {
                    errorMessage += `- ${data.errors[field].join(', ')}\n`;
                });
                showAlert(errorMessage, 'danger');
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'danger');
            }
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        showAlert('Terjadi kesalahan saat menyimpan data: ' + error.message, 'danger');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
    });
}
    
    function hideMessages() {
        document.getElementById('noMutasiMessage').classList.add('d-none');
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        form.insertBefore(alertDiv, form.firstChild);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    function showSuccessModal(message) {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        document.querySelector('#successModal p').textContent = message;
        modal.show();
    }
    
    // Global function for onclick
    window.selectMutasi = selectMutasi;
});
</script>
@endpush