{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaABK\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah ABK - SertijabPELNI')

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
                            <i class="bi bi-person-plus"></i>
                            Tambah ABK
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-person-plus-fill"></i>
                    Tambah ABK Baru
                </h1>
                <p class="page-subtitle">Tambahkan data ABK baru ke dalam sistem</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="form-container">
                <!-- Form Header -->
                <div class="form-header">
                    <div class="form-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="form-title-section">
                        <h3 class="form-title">Data ABK Baru</h3>
                        <p class="form-subtitle">Lengkapi informasi ABK yang akan ditambahkan</p>
                    </div>
                </div>

                <!-- Form Content -->
                <form id="tambahABKForm" action="{{ route('abk.store') }}" method="POST" class="abk-form">
                    @csrf
                    
                    <div class="form-body">
                        <div class="row">
                            <!-- Data Pribadi ABK -->
                            <div class="col-lg-6">
                                <div class="form-section">
                                    <h5 class="section-title">
                                        <i class="bi bi-person-circle me-2"></i>
                                        Data Pribadi
                                    </h5>
                                    
                                    <!-- Field NRP/ID ABK -->
                                    <div class="form-group mb-4">
                                        <label for="id" class="form-label required">NRP/ID ABK</label>
                                        <input type="number" class="form-control" id="id" name="id" 
                                               placeholder="Masukkan NRP/ID ABK" 
                                               min="1000"
                                               max="99999999999999999999"
                                               pattern="[0-9]{4,20}"
                                               required>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            NRP yang sudah disediakan perusahaan (4-20 digit angka)
                                        </div>
                                        <div class="nrp-status" id="nrpStatus" style="display: none;">
                                            <!-- Status NRP akan muncul di sini -->
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="nama_abk" class="form-label required">Nama Lengkap ABK</label>
                                        <input type="text" class="form-control" id="nama_abk" name="nama_abk" 
                                               placeholder="Masukkan nama lengkap ABK" 
                                               maxlength="255" required>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Nama lengkap sesuai dokumen resmi
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Jabatan -->
                            <div class="col-lg-6">
                                <div class="form-section">
                                    <h5 class="section-title">
                                        <i class="bi bi-briefcase me-2"></i>
                                        Informasi Jabatan
                                    </h5>
                                    
                                    <div class="form-group mb-4">
                                        <label for="id_jabatan_tetap" class="form-label required">Jabatan Tetap</label>
                                        <select class="form-select jabatan-select" id="id_jabatan_tetap" name="id_jabatan_tetap" required>
                                            <option value="">-- Pilih Jabatan Tetap --</option>
                                            @forelse($daftarJabatan ?? [] as $jabatan)
                                                <option value="{{ $jabatan->id }}">
                                                    {{ $jabatan->nama_jabatan }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Tidak ada data jabatan</option>
                                            @endforelse
                                        </select>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Pilih jabatan tetap sesuai dengan posisi ABK
                                        </div>
                                        @if(($daftarJabatan ?? collect())->isEmpty())
                                            <small class="text-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Data jabatan kosong. Periksa koneksi database atau tabel jabatan.
                                            </small>
                                        @endif
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="status_abk" class="form-label required">Status ABK</label>
                                        <select class="form-select" id="status_abk" name="status_abk" required>
                                            <option value="">-- Pilih Status ABK --</option>
                                            <option value="Organik">Organik</option>
                                            <option value="Non Organik">Non Organik</option>
                                        </select>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Status kepegawaian ABK dalam perusahaan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Jabatan yang Dipilih -->
                        <div id="jabatanInfo" class="jabatan-info-card" style="display: none;">
                            <div class="info-header">
                                <h6 class="info-title">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    Informasi Jabatan
                                </h6>
                            </div>
                            <div class="info-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <span class="info-label">Nama Jabatan:</span>
                                            <span id="selectedJabatanNama" class="info-value">-</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <span class="info-label">Kode Jabatan:</span>
                                            <span id="selectedJabatanKode" class="info-value">-</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <span class="info-label">Level:</span>
                                            <span id="selectedJabatanLevel" class="info-value">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Data (Preview) -->
                        <div id="reviewData" class="review-section" style="display: none;">
                            <h5 class="section-title">
                                <i class="bi bi-eye me-2"></i>
                                Preview Data ABK
                            </h5>
                            <div class="review-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="review-item">
                                            <span class="review-label">NRP/ID:</span>
                                            <span id="previewNRP" class="review-value">-</span>
                                        </div>
                                        <div class="review-item">
                                            <span class="review-label">Nama ABK:</span>
                                            <span id="previewNama" class="review-value">-</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="review-item">
                                            <span class="review-label">Jabatan:</span>
                                            <span id="previewJabatan" class="review-value">-</span>
                                        </div>
                                        <div class="review-item">
                                            <span class="review-label">Status:</span>
                                            <span id="previewStatus" class="review-value">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="form-footer">
                        <div class="form-actions">
                            <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Batal
                            </a>
                            <button type="reset" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary btn-submit" disabled>
                                <i class="bi bi-check-circle me-2"></i>
                                <span class="submit-text">Simpan Data ABK</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="success-icon mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="modal-title mb-2">Berhasil!</h5>
                <p class="mb-4">Data ABK berhasil ditambahkan ke sistem</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('abk.index') }}" class="btn btn-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        Lihat Data ABK
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="bi bi-plus me-2"></i>
                        Tambah Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="error-icon mb-3">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <h5 class="modal-title mb-2">Terjadi Kesalahan!</h5>
                <p id="errorMessage" class="mb-4">-</p>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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

/* Form Container */
.form-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

/* Form Header */
.form-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 32px 40px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 20px;
}

.form-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: var(--shadow-medium);
}

.form-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.form-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 16px;
}

/* Form Body */
.form-body {
    padding: 40px;
}

.form-section {
    margin-bottom: 32px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border-color);
}

.section-title i {
    color: var(--primary-blue);
    font-size: 20px;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-size: 14px;
}

.form-label.required::after {
    content: " *";
    color: var(--danger-color);
    font-weight: 700;
}

.form-control, .form-select {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    transition: var(--transition);
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.form-text {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Jabatan Info Card */
.jabatan-info-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
    border-radius: 12px;
    margin: 24px 0;
    transition: var(--transition);
    animation: slideDown 0.3s ease-out;
}

.info-header {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(14, 165, 233, 0.2);
    background: rgba(14, 165, 233, 0.05);
}

.info-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #0c4a6e;
    display: flex;
    align-items: center;
}

.info-body {
    padding: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

.info-value {
    font-size: 14px;
    color: #0c4a6e;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.7);
    padding: 4px 8px;
    border-radius: 6px;
}

/* Review Section */
.review-section {
    background: #f8fafc;
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 24px;
    margin: 24px 0;
    animation: slideDown 0.3s ease-out;
}

.review-content {
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid var(--border-color);
}

.review-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.review-item:last-child {
    border-bottom: none;
}

.review-label {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 500;
}

.review-value {
    font-size: 14px;
    color: var(--text-dark);
    font-weight: 600;
    background: var(--background-light);
    padding: 6px 12px;
    border-radius: 6px;
}

/* Form Footer */
.form-footer {
    background: #f8fafc;
    padding: 24px 40px;
    border-top: 1px solid var(--border-color);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
}

/* Buttons */
.btn {
    padding: 12px 24px;
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
    position: relative;
}

.btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
    pointer-events: none;
}

.btn:disabled::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
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
}

.btn-outline-warning {
    background: transparent;
    color: var(--warning-color);
    border: 2px solid var(--warning-color);
}

.btn-outline-warning:hover {
    background: var(--warning-color);
    color: white;
}

/* Form Validation */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--danger-color);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    animation: shakeError 0.5s ease-in-out;
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    border-color: var(--danger-color);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: var(--danger-color);
    font-weight: 500;
}

/* Success and Error Modals */
.success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #10b981, #34d399); /* Hijau seperti kapal */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); /* Shadow hijau */
    animation: checkmarkAnimation 0.6s ease-in-out;
}

.success-icon i {
    animation: checkmarkPop 0.3s ease-in-out 0.3s both;
}

.error-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--danger-color), #f87171);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
}

/* Animasi untuk success icon */
@keyframes checkmarkAnimation {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes checkmarkPop {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.3);
    }
    100% {
        transform: scale(1);
    }
}

/* Modal styling yang lebih menarik */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-body {
    padding: 2rem !important;
}

.modal-title {
    color: var(--text-dark);
    font-weight: 700;
}

/* Button styling dalam modal */
.modal .btn-primary {
    background: linear-gradient(135deg, #10b981, #34d399); /* Hijau untuk consistency */
    border: none;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal .btn-primary:hover {
    background: linear-gradient(135deg, #059669, #10b981);
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(16, 185, 129, 0.4);
}

.modal .btn-outline-secondary {
    border: 2px solid #e5e7eb;
    color: #6b7280;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal .btn-outline-secondary:hover {
    background: #6b7280;
    border-color: #6b7280;
    color: white;
    transform: translateY(-2px);
}

/* Animations */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shakeError {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes fadeInError {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInSuccess {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Select2 Customization */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 46px;
    padding: 8px 12px;
    border: 2px solid var(--border-color);
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}

.select2-container--bootstrap-5 .select2-selection--single:focus {
    border-color: var(--primary-blue);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .form-header {
        flex-direction: column;
        text-align: center;
        padding: 24px 20px;
    }
    
    .form-body {
        padding: 24px 20px;
    }
    
    .form-footer {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

/* Tambahkan di bagian CSS */
.nrp-checking {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.nrp-status-success {
    padding: 8px 12px;
    background: #d1edff;
    border: 1px solid #0ea5e9;
    border-radius: 6px;
    animation: fadeInSuccess 0.3s ease-out;
}

.nrp-status-danger {
    padding: 8px 12px;
    background: #fee2e2;
    border: 1px solid #ef4444;
    border-radius: 6px;
    animation: fadeInError 0.3s ease-out;
}

.form-control.is-valid {
    border-color: #10b981;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%2310b981' viewBox='0 0 12 12'%3e%3cpath d='m3 5 2 2 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-valid:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Tambahkan di bagian CSS untuk loading state */
.btn.loading {
    position: relative;
    pointer-events: none;
}

.btn.loading .submit-text {
    opacity: 0.7;
}

.btn.loading .spinner-border {
    width: 1rem;
    height: 1rem;
    margin-left: 0.5rem;
}
</style>
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('tambahABKForm');
    const submitButton = document.querySelector('.btn-submit');
    const jabatanSelect = document.getElementById('id_jabatan_tetap');
    const jabatanInfo = document.getElementById('jabatanInfo');
    const reviewData = document.getElementById('reviewData');
    
    // Update variabel form fields
    const nrpField = document.getElementById('id');
    const namaField = document.getElementById('nama_abk');
    const statusField = document.getElementById('status_abk');
    const nrpStatus = document.getElementById('nrpStatus');

    let nrpCheckTimeout;
    let validationTriggered = false;

    // NRP validation and availability check
    if (nrpField) {
        nrpField.addEventListener('input', function() {
            // Hapus karakter non-angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Clear previous timeout
            clearTimeout(nrpCheckTimeout);
            
            // Show loading jika panjang >= 4
            if (this.value.length >= 4) {
                showNRPLoading();
                
                // Debounce NRP check
                nrpCheckTimeout = setTimeout(() => {
                    checkNRPAvailability(this.value);
                }, 500);
            } else {
                hideNRPStatus();
            }
            
            if (validationTriggered) {
                validateField(this);
                validateForm();
            } else {
                checkFormCompletion();
            }
            updatePreview();
        });

        // Prevent non-numeric input
        nrpField.addEventListener('keypress', function(e) {
            // Hanya izinkan angka
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter'].includes(e.key)) {
                e.preventDefault();
            }
        });
    }

    // Function to check NRP availability
    function checkNRPAvailability(nrp) {
        if (!nrp || nrp.length < 4) {
            hideNRPStatus();
            return;
        }
        
        fetch('/abk/check-nrp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ nrp: nrp })
        })
        .then(response => response.json())
        .then(data => {
            showNRPStatus(data.available, data.message);
        })
        .catch(error => {
            console.error('Error checking NRP:', error);
            hideNRPStatus();
            showNRPStatus(false, 'Terjadi kesalahan saat memeriksa NRP');
        });
    }

    // Show NRP loading status
    function showNRPLoading() {
        nrpStatus.innerHTML = `
            <div class="nrp-checking">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <small class="text-muted">Memeriksa ketersediaan NRP...</small>
            </div>
        `;
        nrpStatus.style.display = 'block';
    }

    // Show NRP status
    function showNRPStatus(available, message) {
        const statusClass = available ? 'success' : 'danger';
        const iconClass = available ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        
        nrpStatus.innerHTML = `
            <div class="nrp-status-${statusClass} mt-2">
                <small class="text-${statusClass}">
                    <i class="bi ${iconClass} me-1"></i>
                    ${message}
                </small>
            </div>
        `;
        nrpStatus.style.display = 'block';
        
        // Update field validation state
        if (available) {
            nrpField.classList.remove('is-invalid');
            nrpField.classList.add('is-valid');
        } else {
            nrpField.classList.remove('is-valid');
            nrpField.classList.add('is-invalid');
        }
    }

    // Hide NRP status
    function hideNRPStatus() {
        nrpStatus.style.display = 'none';
        nrpField.classList.remove('is-valid', 'is-invalid');
    }

    // Initialize Select2 for jabatan
    $(document).ready(function() {
        $('#id_jabatan_tetap').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Jabatan Tetap --',
            allowClear: true,
            width: '100%'
        });

        $('#id_jabatan_tetap').on('change', function() {
            const selectedValue = $(this).val();
            const selectedOption = $(this).find('option:selected');
            
            if (selectedValue && selectedValue !== "") {
                const jabatanNama = selectedOption.text();
                const jabatanKode = '-';
                const jabatanLevel = '1';
                
                showJabatanInfo(jabatanNama, jabatanKode, jabatanLevel);
            } else {
                hideJabatanInfo();
            }
            
            if (validationTriggered) {
                validateForm();
            } else {
                checkFormCompletion();
            }
            updatePreview();
        });
    });

    // Show jabatan info
    function showJabatanInfo(nama, kode, level) {
        document.getElementById('selectedJabatanNama').textContent = nama;
        document.getElementById('selectedJabatanKode').textContent = kode;
        document.getElementById('selectedJabatanLevel').textContent = level;
        jabatanInfo.style.display = 'block';
    }

    // Hide jabatan info
    function hideJabatanInfo() {
        jabatanInfo.style.display = 'none';
    }

    // Real-time validation on input
    [nrpField, namaField, statusField].forEach(field => {
        if (field) {
            field.addEventListener('input', function() {
                if (validationTriggered) {
                    validateField(field);
                    validateForm();
                } else {
                    checkFormCompletion();
                }
                updatePreview();
            });
            
            field.addEventListener('change', function() {
                if (validationTriggered) {
                    validateField(field);
                    validateForm();
                } else {
                    checkFormCompletion();
                }
                updatePreview();
            });
        }
    });

    // Check form completion
    function checkFormCompletion() {
        const allFields = [nrpField, namaField, jabatanSelect, statusField];
        const isComplete = allFields.every(field => field && field.value.trim() !== '');
        
        if (submitButton) {
            submitButton.disabled = !isComplete;
        }
        
        if (isComplete) {
            showPreview();
        } else {
            hidePreview();
        }
    }

    // Validate individual field
    function validateField(field) {
        const fieldValue = field.value.trim();
        
        // Clear previous validation
        field.classList.remove('is-invalid');
        removeValidationError(field);
        
        if (!fieldValue) {
            field.classList.add('is-invalid');
            let message = 'Field ini wajib diisi';
            
            if (field.id === 'id') {
                message = 'NRP/ID ABK wajib diisi';
            } else if (field.id === 'nama_abk') {
                message = 'Nama ABK wajib diisi';
            } else if (field.id === 'status_abk') {
                message = 'Status ABK wajib dipilih';
            }
            
            showValidationError(field, message);
            return false;
        }
        
        // Additional validation for NRP
        if (field.id === 'id') {
            if (fieldValue.length < 4) {
                field.classList.add('is-invalid');
                showValidationError(field, 'NRP minimal 4 digit');
                return false;
            }
            
            if (fieldValue.length > 20) {
                field.classList.add('is-invalid');
                showValidationError(field, 'NRP maksimal 20 digit');
                return false;
            }
            
            if (!/^[0-9]+$/.test(fieldValue)) {
                field.classList.add('is-invalid');
                showValidationError(field, 'NRP hanya boleh berisi angka');
                return false;
            }
        }
        
        // Additional validation for nama
        if (field.id === 'nama_abk') {
            if (fieldValue.length < 2) {
                field.classList.add('is-invalid');
                showValidationError(field, 'Nama minimal 2 karakter');
                return false;
            }
        }
        
        return true;
    }

    // Validate entire form
    function validateForm() {
        const requiredFields = [nrpField, namaField, jabatanSelect, statusField];
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (field) {
                if (!validateField(field)) {
                    isValid = false;
                }
            }
        });
        
        // Validate jabatan selection
        if (jabatanSelect && !jabatanSelect.value) {
            jabatanSelect.classList.add('is-invalid');
            showValidationError(jabatanSelect.parentNode, 'Jabatan tetap wajib dipilih');
            isValid = false;
        }
        
        // Check NRP availability
        const nrpStatusElement = document.querySelector('.nrp-status-danger');
        if (nrpStatusElement) {
            isValid = false;
        }
        
        if (submitButton) {
            submitButton.disabled = !isValid;
        }
        
        return isValid;
    }

    // Show validation error
    function showValidationError(field, message) {
        removeValidationError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        if (field.parentNode) {
            field.parentNode.appendChild(errorDiv);
        }
    }

    // Remove validation error
    function removeValidationError(field) {
        const container = field.parentNode || field;
        const existingError = container.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    // Update preview data
    function updatePreview() {
        const allFieldsFilled = nrpField.value.trim() && 
                               namaField.value.trim() && 
                               jabatanSelect.value && 
                               statusField.value;
        
        if (allFieldsFilled) {
            const selectedJabatan = jabatanSelect.options[jabatanSelect.selectedIndex];
            
            document.getElementById('previewNRP').textContent = nrpField.value;
            document.getElementById('previewNama').textContent = namaField.value;
            document.getElementById('previewJabatan').textContent = selectedJabatan ? selectedJabatan.text : '-';
            document.getElementById('previewStatus').textContent = statusField.value;
            
            showPreview();
        } else {
            hidePreview();
        }
    }

    // Show preview section
    function showPreview() {
        reviewData.style.display = 'block';
    }

    // Hide preview section
    function hidePreview() {
        reviewData.style.display = 'none';
    }

    // AKTIFKAN KEMBALI AJAX SUBMISSION
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // AKTIFKAN KEMBALI preventDefault
            validationTriggered = true;
            
            if (validateForm()) {
                submitForm(); // Gunakan AJAX submission
            }
        });
    }

    // Reset form handler
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Reset form
            form.reset();
            
            // Reset Select2
            $('#id_jabatan_tetap').val(null).trigger('change');
            
            // Clear validation states
            document.querySelectorAll('.is-invalid, .is-valid').forEach(field => {
                field.classList.remove('is-invalid', 'is-valid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(error => {
                error.remove();
            });
            
            // Hide info sections
            hideJabatanInfo();
            hidePreview();
            hideNRPStatus();
            
            // Reset validation flag
            validationTriggered = false;
            
            // Disable submit button
            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    }

    // SUBMIT FORM FUNCTION DENGAN AJAX
    function submitForm() {
        console.log('submitForm called'); // Debug log
        
        // Show loading state
        submitButton.classList.add('loading');
        submitButton.disabled = true;
        
        // Update button text
        const submitText = submitButton.querySelector('.submit-text');
        const spinner = submitButton.querySelector('.spinner-border');
        
        const originalText = submitText.textContent;
        if (submitText) submitText.textContent = 'Menyimpan...';
        if (spinner) spinner.classList.remove('d-none');
        
        const formData = new FormData(form);
        
        // AJAX submission dengan header yang benar
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text); // Debug log
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data); // Debug log
            
            if (data.success) {
                showSuccessModal();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
            }
        })
        .catch(error => {
            console.error('Error details:', error); // Debug log
            showErrorModal(error.message);
        })
        .finally(() => {
            // Hide loading state
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
            
            // Reset button text
            if (submitText) submitText.textContent = originalText;
            if (spinner) spinner.classList.add('d-none');
        });
    }

    // Show success modal
    function showSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
        
        // Reset form setelah berhasil
        setTimeout(() => {
            form.reset();
            $('#id_jabatan_tetap').val(null).trigger('change');
            
            // Clear validation states
            document.querySelectorAll('.is-invalid, .is-valid').forEach(field => {
                field.classList.remove('is-invalid', 'is-valid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(error => {
                error.remove();
            });
            
            // Hide info sections
            hideJabatanInfo();
            hidePreview();
            hideNRPStatus();
            
            // Reset validation flag
            validationTriggered = false;
            
            // Disable submit button
            if (submitButton) {
                submitButton.disabled = true;
            }
        }, 1000);
    }

    // Show error modal
    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('errorModal'));
        modal.show();
    }

    // Initialize form state
    checkFormCompletion();
});
</script>
@endpush