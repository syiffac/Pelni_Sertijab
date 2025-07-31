{{-- filepath: resources\views\kelolaKapal\create.blade.php --}}
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
                            <!-- Kode Kapal (manual input) -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="id" class="form-label required">Kode Kapal</label>
                                    <input type="text" class="form-control @error('id') is-invalid @enderror" 
                                           id="id" name="id" 
                                           value="{{ old('id') }}"
                                           placeholder="Contoh: 123" required>
                                    @error('id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Masukkan kode kapal sesuai kode dari perusahaan</div>
                                </div>
                            </div>

                            <!-- Nama Kapal -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="nama_kapal" class="form-label required">Nama Kapal</label>
                                    <input type="text" class="form-control @error('nama_kapal') is-invalid @enderror" 
                                           id="nama_kapal" name="nama_kapal" 
                                           value="{{ old('nama_kapal') }}"
                                           placeholder="Contoh: KM Bukit Raya" required>
                                    @error('nama_kapal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Masukkan nama lengkap kapal</div>
                                </div>
                            </div>

                            <!-- Tipe PAX -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="tipe_pax" class="form-label">Kapasitas Penumpang (PAX)</label>
                                    <input type="number" class="form-control @error('tipe_pax') is-invalid @enderror" 
                                           id="tipe_pax" name="tipe_pax" 
                                           value="{{ old('tipe_pax') }}"
                                           placeholder="Contoh: 1500" min="0" max="10000">
                                    @error('tipe_pax')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kapasitas maksimal penumpang dalam angka</div>
                                </div>
                            </div>

                            <!-- Home Base -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="home_base" class="form-label">Home Base</label>
                                    <select class="form-select select2 @error('home_base') is-invalid @enderror" 
                                            id="home_base" name="home_base">
                                        <option value="">-- Pilih atau ketik home base --</option>
                                        @foreach($homeBaseList as $homeBase)
                                            <option value="{{ $homeBase }}" {{ old('home_base') == $homeBase ? 'selected' : '' }}>
                                                {{ $homeBase }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('home_base')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pilih atau ketik langsung home base kapal</div>
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
<!-- Tambahkan CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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

/* Number input styling */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Custom input for better number formatting */
.form-control[type="number"] {
    text-align: left;
}

/* Custom home base styling */
#custom-home-base {
    animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        margin-bottom: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
        margin-bottom: 1rem;
    }
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
}

/* Select2 custom styling */
.select2-container--bootstrap-5 .select2-selection {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 6px 8px;
    font-size: 14px;
    min-height: 46px;
    transition: var(--transition);
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.select2-container--bootstrap-5 .select2-dropdown {
    border-color: var(--primary-blue);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    line-height: 1.5;
    padding-top: 3px;
}

.select2-container .select2-search--inline .select2-search__field {
    margin-top: 5px;
    height: 30px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
    padding: 0 6px;
}

.select2-results__option--highlighted {
    background-color: var(--primary-blue) !important;
    color: white !important;
}

/* Add a loading spinner to select2 */
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    position: absolute;
    top: 50%;
    right: 3px;
    width: 20px;
    height: 20px;
    margin-top: -10px;
}

/* Select2 validation state */
.select2-container--bootstrap-5 .select2-selection--single.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid + .select2-container--bootstrap-5 .select2-selection {
    border-color: #dc3545 !important;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
}

/* Fix untuk tampilan pesan error pada Select2 */
.select2-container + .invalid-feedback {
    display: block;
    margin-top: 0.25rem;
}

/* No results styling */
.select2-results__message {
    padding: 12px;
    color: #6c757d;
    font-style: italic;
    text-align: center;
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
<!-- Tambahkan JS Select2 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kapalForm');
    const submitButton = form.querySelector('button[type="submit"]');
    const submitText = submitButton.querySelector('.submit-text');
    const submitSpinner = submitButton.querySelector('.spinner-border');
    const homeBaseSelect = document.getElementById('home_base');
    
    // Inisialisasi Select2 untuk home_base
    $(document).ready(function() {
        $('#home_base').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Pilih atau ketik home base --',
            allowClear: true,
            tags: false, // Ubah menjadi false agar tidak bisa membuat tag baru
            minimumInputLength: 1, // Minimal 1 karakter sebelum pencarian
            maximumInputLength: 20, // Batasi input maksimal
            language: {
                inputTooShort: function() {
                    return "Ketik minimal 1 karakter untuk mencari";
                },
                inputTooLong: function() {
                    return "Harap batasi pencarian hingga 20 karakter";
                },
                noResults: function() {
                    return "Tidak ditemukan home base yang sesuai";
                },
                searching: function() {
                    return "Mencari...";
                }
            },
            templateResult: function(data) {
                if (data.loading) return data.text;
                
                var $result = $('<span></span>');
                $result.text(data.text);
                return $result;
            },
            matcher: function(params, data) {
                // Jika tidak ada input pencarian, tampilkan semua opsi
                if ($.trim(params.term) === '') {
                    return data;
                }
                
                // Cari dalam daftar yang ada saja
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }
                
                // Jika tidak cocok, kembalikan null
                return null;
            }
        });
        
        // Handler ketika Select2 dibuka
        $('#home_base').on('select2:open', function() {
            // Set focus ke input search
            setTimeout(function() {
                $('.select2-search__field').focus();
            }, 100);
        });
        
        // Handler ketika nilai berubah
        $('#home_base').on('change', function() {
            const value = $(this).val();
            // Validasi: jika nilai tidak ada dalam daftar yang valid, reset
            const isValid = $('#home_base option').filter(function() {
                return $(this).val() === value;
            }).length > 0;
            
            if (!isValid && value) {
                $(this).val('').trigger('change');
                showHomeBaseError('Home base tidak valid. Silakan pilih dari daftar yang tersedia.');
            }
        });
    });
    
    // Format number input for tipe_pax
    const tipePaxInput = document.getElementById('tipe_pax');
    tipePaxInput.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to max value
        if (parseInt(this.value) > 10000) {
            this.value = '10000';
        }
    });
    
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
        
        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
        
        // Validate required fields
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showValidationError(field, 'Field ini wajib diisi');
                isValid = false;
            }
        });
        
        // Validate kode kapal
        const kodeKapalInput = document.getElementById('id');
        if (kodeKapalInput.value.trim()) {
            // Validate format - hanya menerima angka
            if (!/^\d+$/.test(kodeKapalInput.value.trim())) {
                showValidationError(kodeKapalInput, 'Kode kapal hanya boleh mengandung angka');
                isValid = false;
            }
        }
        
        // Validate tipe_pax
        const tipePaxValue = parseInt(tipePaxInput.value);
        if (tipePaxInput.value && (tipePaxValue < 0 || tipePaxValue > 10000)) {
            showValidationError(tipePaxInput, 'Kapasitas harus antara 0 - 10.000');
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
    
    function submitForm() {
        // Show loading state
        submitButton.disabled = true;
        submitText.style.display = 'none';
        submitSpinner.classList.remove('d-none');
        
        // Prepare form data
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
                $('#home_base').val('').trigger('change'); // Reset select2
            } else {
                // Show error message
                showErrorAlert('Error: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            // Remove loading state
            submitButton.disabled = false;
            submitText.style.display = 'inline';
            submitSpinner.classList.add('d-none');
            
            showErrorAlert('Terjadi kesalahan saat menyimpan data');
            console.error('Error:', error);
        });
    }
    
    function showErrorAlert(message) {
        // Create or update error alert
        let alert = document.querySelector('.alert-danger');
        if (!alert) {
            alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span class="alert-message"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.parentNode.insertBefore(alert, form);
        }
        
        alert.querySelector('.alert-message').textContent = message;
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

/**
 * Tampilkan pesan error untuk home base
 */
function showHomeBaseError(message) {
    const homeBaseField = document.getElementById('home_base');
    homeBaseField.classList.add('is-invalid');
    
    // Hapus pesan error yang mungkin sudah ada
    const existingError = homeBaseField.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
    
    // Buat pesan error baru
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    errorDiv.style.display = 'block'; // Pastikan pesan terlihat
    
    // Tambahkan error setelah container select2
    homeBaseField.parentNode.querySelector('.select2-container').after(errorDiv);
}
</script>
@endpush