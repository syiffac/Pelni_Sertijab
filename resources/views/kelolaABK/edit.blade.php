{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaABK\edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit ABK - SertijabPELNI')

@section('content')
<div class="form-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">
                    <i class="bi bi-person-gear"></i>
                    Edit Data ABK
                </h1>
                <p class="page-subtitle">Perbarui informasi Anak Buah Kapal</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Form -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card form-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Form Edit ABK</h5>
                            <small class="text-muted">ID: {{ $abk->id }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('abk.update', $abk->id) }}" method="POST" id="editAbkForm" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- NRP/ID ABK -->
                            <div class="col-md-6 mb-4">
                                <label for="id" class="form-label required">
                                    <i class="bi bi-hash text-primary"></i>
                                    NRP/ID ABK
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('id') is-invalid @enderror" 
                                           id="id" 
                                           name="id" 
                                           value="{{ old('id', $abk->id) }}"
                                           placeholder="Masukkan NRP ABK"
                                           maxlength="20"
                                           pattern="[0-9]+"
                                           title="Hanya boleh berisi angka"
                                           required>
                                    <div class="validation-feedback" id="nrp-feedback"></div>
                                </div>
                                @error('id')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    NRP harus berupa angka, minimal 4 karakter, maksimal 20 karakter
                                </div>
                            </div>

                            <!-- Nama ABK -->
                            <div class="col-md-6 mb-4">
                                <label for="nama_abk" class="form-label required">
                                    <i class="bi bi-person text-primary"></i>
                                    Nama Lengkap ABK
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('nama_abk') is-invalid @enderror" 
                                           id="nama_abk" 
                                           name="nama_abk" 
                                           value="{{ old('nama_abk', $abk->nama_abk) }}"
                                           placeholder="Masukkan nama lengkap ABK"
                                           maxlength="255"
                                           required>
                                </div>
                                @error('nama_abk')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    Nama harus diisi minimal 2 karakter
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jabatan Tetap -->
                            <div class="col-md-6 mb-4">
                                <label for="id_jabatan_tetap" class="form-label required">
                                    <i class="bi bi-briefcase text-primary"></i>
                                    Jabatan Tetap
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </span>
                                    <select class="form-select @error('id_jabatan_tetap') is-invalid @enderror" 
                                            id="id_jabatan_tetap" 
                                            name="id_jabatan_tetap" 
                                            required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach($daftarJabatan as $jabatan)
                                            <option value="{{ $jabatan->id }}" 
                                                    {{ old('id_jabatan_tetap', $abk->id_jabatan_tetap) == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('id_jabatan_tetap')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    Pilih jabatan tetap untuk ABK ini
                                </div>
                            </div>

                            <!-- Status ABK -->
                            <div class="col-md-6 mb-4">
                                <label for="status_abk" class="form-label required">
                                    <i class="bi bi-shield-check text-primary"></i>
                                    Status ABK
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield-fill"></i>
                                    </span>
                                    <select class="form-select @error('status_abk') is-invalid @enderror" 
                                            id="status_abk" 
                                            name="status_abk" 
                                            required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Organik" 
                                                {{ old('status_abk', $abk->status_abk) == 'Organik' ? 'selected' : '' }}>
                                            Organik
                                        </option>
                                        <option value="Non Organik" 
                                                {{ old('status_abk', $abk->status_abk) == 'Non Organik' ? 'selected' : '' }}>
                                            Non Organik
                                        </option>
                                        <option value="Pensiun" 
                                                {{ old('status_abk', $abk->status_abk) == 'Pensiun' ? 'selected' : '' }}>
                                            Pensiun
                                        </option>
                                    </select>
                                </div>
                                @error('status_abk')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    Status kepegawaian ABK
                                </div>
                            </div>
                        </div>

                        <!-- Current Data Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="info-card">
                                    <div class="info-header">
                                        <i class="bi bi-info-circle"></i>
                                        <span>Informasi Data Saat Ini</span>
                                    </div>
                                    <div class="info-content">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="info-label">NRP/ID:</span>
                                                <span class="info-value">{{ $abk->id }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="info-label">Nama:</span>
                                                <span class="info-value">{{ $abk->nama_abk }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="info-label">Jabatan:</span>
                                                <span class="info-value">{{ $abk->jabatanTetap->nama_jabatan ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="info-label">Status:</span>
                                                <span class="badge bg-{{ $abk->status_abk == 'Organik' ? 'success' : ($abk->status_abk == 'Non Organik' ? 'warning' : 'secondary') }}">
                                                    {{ $abk->status_abk }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <span class="info-label">Dibuat:</span>
                                                <span class="info-value">{{ $abk->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="info-label">Terakhir Diubah:</span>
                                                <span class="info-value">{{ $abk->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex gap-3 justify-content-between">
                                <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left"></i>
                                    Batal
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="reset" class="btn btn-outline-warning btn-lg">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="bi bi-check-lg"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Form Container */
.form-container {
    padding: 24px;
    background: #f8fafc;
    min-height: calc(100vh - 70px);
}

/* Page Header */
.page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #2A3F8E;
}

.page-subtitle {
    color: #6b7280;
    margin: 4px 0 0 0;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Form Card */
.form-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.form-card .card-header {
    background: linear-gradient(135deg, #2A3F8E 0%, #4f46e5 100%);
    border: none;
    padding: 24px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    color: white;
    font-size: 20px;
}

.form-card .card-title {
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.form-card .card-body {
    padding: 32px;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    font-size: 16px;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
    font-weight: 700;
}

.input-group {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}

.input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e5e7eb;
    border-right: none;
    color: #6b7280;
    font-weight: 500;
}

.form-control,
.form-select {
    border: 1px solid #e5e7eb;
    border-left: none;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #2A3F8E;
    box-shadow: 0 0 0 3px rgba(42, 63, 142, 0.1);
}

.form-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Validation Feedback */
.validation-feedback {
    font-size: 12px;
    margin-top: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    display: none;
}

.validation-feedback.valid {
    background: #d1fae5;
    color: #065f46;
    border-left: 3px solid #10b981;
    display: block;
}

.validation-feedback.invalid {
    background: #fee2e2;
    color: #dc2626;
    border-left: 3px solid #ef4444;
    display: block;
}

.invalid-feedback {
    font-size: 12px;
    color: #dc2626;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Info Card */
.info-card {
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 20px;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #1e40af;
    font-weight: 600;
    margin-bottom: 16px;
    font-size: 14px;
}

.info-content {
    color: #374151;
    font-size: 13px;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
    display: block;
    margin-bottom: 2px;
}

.info-value {
    color: #1f2937;
    font-weight: 500;
}

/* Form Actions */
.form-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

/* Button Enhancements */
.btn {
    padding: 12px 24px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-lg {
    padding: 14px 28px;
    font-size: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, #2A3F8E 0%, #4f46e5 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
}

.btn-outline-secondary {
    border-color: #d1d5db;
    color: #6b7280;
}

.btn-outline-secondary:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.btn-outline-warning {
    border-color: #fbbf24;
    color: #f59e0b;
}

.btn-outline-warning:hover {
    background-color: #fbbf24;
    border-color: #f59e0b;
    color: white;
}

/* Loading State */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}

.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Alert Enhancements */
.alert {
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    border-left: 4px solid #ef4444;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: stretch;
    }
    
    .header-actions .btn {
        flex: 1;
    }
    
    .form-card .card-body {
        padding: 24px 20px;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
    }
    
    .form-actions .d-flex .d-flex {
        flex-direction: row;
    }
    
    .info-content .row .col-md-3,
    .info-content .row .col-md-6 {
        margin-bottom: 8px;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 24px;
    }
    
    .form-card .card-header {
        padding: 20px;
    }
    
    .form-card .card-body {
        padding: 20px 16px;
    }
    
    .header-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .btn-lg {
        padding: 12px 20px;
        font-size: 14px;
    }
}

/* Animations */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Form Card Animation */
.form-card {
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time NRP validation
    const nrpInput = document.getElementById('id');
    const nrpFeedback = document.getElementById('nrp-feedback');
    const form = document.getElementById('editAbkForm');
    const submitBtn = document.getElementById('submitBtn');
    let validateTimeout;

    nrpInput.addEventListener('input', function() {
        clearTimeout(validateTimeout);
        validateTimeout = setTimeout(() => {
            validateNRP(this.value);
        }, 500);
    });

    function validateNRP(nrp) {
        if (!nrp) {
            nrpFeedback.className = 'validation-feedback';
            nrpFeedback.textContent = '';
            return;
        }

        // Format validation
        if (!/^[0-9]+$/.test(nrp) || nrp.length < 4 || nrp.length > 20) {
            nrpFeedback.className = 'validation-feedback invalid';
            nrpFeedback.innerHTML = '<i class="bi bi-x-circle"></i> Format NRP tidak valid';
            return;
        }

        // Check availability (exclude current ABK ID)
        fetch(`/abk/check-nrp?nrp=${nrp}&exclude_id={{ $abk->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    nrpFeedback.className = 'validation-feedback valid';
                    nrpFeedback.innerHTML = '<i class="bi bi-check-circle"></i> ' + data.message;
                } else {
                    nrpFeedback.className = 'validation-feedback invalid';
                    nrpFeedback.innerHTML = '<i class="bi bi-x-circle"></i> ' + data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                nrpFeedback.className = 'validation-feedback invalid';
                nrpFeedback.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Gagal validasi NRP';
            });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        
        // Submit after short delay
        setTimeout(() => {
            this.submit();
        }, 500);
    });

    // Form reset
    const resetBtn = form.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function() {
        setTimeout(() => {
            nrpFeedback.className = 'validation-feedback';
            nrpFeedback.textContent = '';
        }, 100);
    });

    // Real-time form validation
    const inputs = form.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearValidation);
    });

    function validateField(e) {
        const input = e.target;
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    }

    function clearValidation(e) {
        const input = e.target;
        input.classList.remove('is-invalid', 'is-valid');
    }
});
</script>
@endsection