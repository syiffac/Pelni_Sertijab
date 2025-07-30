{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\kelolaKapal\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Kapal - SertijabPELNI')

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
                            <a href="{{ route('kapal.index') }}">
                                <i class="bi bi-ship"></i>
                                Data Kapal
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Kapal
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-plus-circle-fill"></i>
                    Tambah Kapal Baru
                </h1>
                <p class="page-subtitle">Tambahkan data kapal baru ke sistem</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('kapal.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h5 class="form-title">
                        <i class="bi bi-ship me-2"></i>
                        Informasi Kapal
                    </h5>
                    <p class="form-subtitle">Lengkapi data kapal dengan benar</p>
                </div>
                
                <form id="kapalForm" action="{{ route('kapal.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-body">
                        <div class="row">
                            <!-- Nama Kapal -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="nama_kapal" class="form-label required">Nama Kapal</label>
                                    <input type="text" class="form-control" id="nama_kapal" name="nama_kapal" 
                                           placeholder="Contoh: KM Bukit Raya" required>
                                    <div class="form-text">Masukkan nama lengkap kapal</div>
                                </div>
                            </div>

                            <!-- Kode Kapal -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="kode_kapal" class="form-label required">Kode Kapal</label>
                                    <input type="text" class="form-control" id="kode_kapal" name="kode_kapal" 
                                           placeholder="Contoh: BR001" required maxlength="50">
                                    <div class="form-text">Kode unik untuk identifikasi kapal</div>
                                </div>
                            </div>

                            <!-- Jenis Kapal -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="jenis_kapal" class="form-label required">Jenis Kapal</label>
                                    <select class="form-select" id="jenis_kapal" name="jenis_kapal" required>
                                        <option value="">-- Pilih Jenis Kapal --</option>
                                        <option value="Kapal Penumpang">Kapal Penumpang</option>
                                        <option value="Kapal Barang">Kapal Barang</option>
                                        <option value="Kapal Ro-Ro">Kapal Ro-Ro</option>
                                        <option value="Kapal Ferry">Kapal Ferry</option>
                                        <option value="Kapal Cepat">Kapal Cepat</option>
                                        <option value="Kapal Penyeberangan">Kapal Penyeberangan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tahun Pembuatan -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                                    <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" 
                                           placeholder="Contoh: 2020" min="1900" max="{{ date('Y') }}">
                                    <div class="form-text">Tahun pembuatan kapal (opsional)</div>
                                </div>
                            </div>

                            <!-- Status Kapal -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="status_kapal" class="form-label required">Status Kapal</label>
                                    <select class="form-select" id="status_kapal" name="status_kapal" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Aktif" selected>Aktif</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                        <option value="Maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" 
                                              rows="4" placeholder="Keterangan tambahan tentang kapal (opsional)" maxlength="500"></textarea>
                                    <div class="form-text">Maksimal 500 karakter</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            <i class="bi bi-x-circle me-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            <span class="submit-text">Simpan Kapal</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
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
                <p class="mb-4">Data kapal berhasil ditambahkan ke sistem</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('kapal.index') }}" class="btn btn-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        Lihat Data Kapal
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
@endsection

@push('styles')
<style>
/* Variables */
:root {
    --primary-blue: #2563eb;
    --success-color: #10b981;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f8fafc;
    --border-radius: 12px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
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

/* Form Card */
.form-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.form-header {
    padding: 24px 24px 20px 24px;
    border-bottom: 1px solid var(--border-color);
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.form-title i {
    color: var(--primary-blue);
}

.form-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 14px;
}

.form-body {
    padding: 32px 24px;
}

.form-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border-color);
    background: var(--background-light);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
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
    color: #ef4444;
}

.form-control, .form-select {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.form-text {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

/* Buttons */
.btn {
    padding: 12px 20px;
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

.btn-primary:hover:not(:disabled) {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Success Modal */
.success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--success-color), #34d399);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .form-body {
        padding: 24px 16px;
    }

    .form-footer {
        flex-direction: column;
        padding: 16px;
    }

    .form-footer .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kapalForm');
    const submitButton = form.querySelector('button[type="submit"]');
    const submitText = submitButton.querySelector('.submit-text');
    const submitSpinner = submitButton.querySelector('.spinner-border');
    
    // Auto uppercase kode kapal
    const kodeKapalInput = document.getElementById('kode_kapal');
    if (kodeKapalInput) {
        kodeKapalInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
    
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        
        requiredFields.forEach(field => {
            removeValidationError(field);
            
            if (!field.value.trim()) {
                showValidationError(field, 'Field ini wajib diisi');
                isValid = false;
            }
        });
        
        // Additional validations
        const tahunPembuatan = document.getElementById('tahun_pembuatan');
        if (tahunPembuatan.value && (tahunPembuatan.value < 1900 || tahunPembuatan.value > new Date().getFullYear())) {
            showValidationError(tahunPembuatan, 'Tahun pembuatan tidak valid');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showValidationError(field, message) {
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function removeValidationError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }
    
    function submitForm() {
        // Show loading state
        submitButton.disabled = true;
        submitText.style.display = 'none';
        submitSpinner.classList.remove('d-none');
        
        // Get form data
        const formData = new FormData(form);
        
        // Submit via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading state
            submitButton.disabled = false;
            submitText.style.display = 'inline';
            submitSpinner.classList.add('d-none');
            
            if (data.success) {
                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                
                // Reset form
                form.reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            // Remove loading state
            submitButton.disabled = false;
            submitText.style.display = 'inline';
            submitSpinner.classList.add('d-none');
            
            alert('Terjadi kesalahan saat menyimpan data');
            console.error('Error:', error);
        });
    }
});
</script>
@endpush