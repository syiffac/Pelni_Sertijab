{{-- Ganti seluruh konten edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('mutasi.index') }}">Kelola Mutasi</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Mutasi</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="bi bi-pencil-square"></i>
                            Edit Data Mutasi
                        </h1>
                        <p class="page-subtitle">Formulir untuk mengedit data mutasi ABK #{{ $mutasi->id }}</p>
                    </div>
                    <div class="header-actions">
                        <span class="status-badge {{ $mutasi->status_mutasi == 'Draft' ? 'draft' : ($mutasi->status_mutasi == 'Ditolak' ? 'rejected' : 'approved') }}">
                            <i class="bi bi-{{ $mutasi->status_mutasi == 'Draft' ? 'pencil' : ($mutasi->status_mutasi == 'Ditolak' ? 'x-circle' : 'check-circle') }} me-1"></i>
                            {{ $mutasi->status_mutasi }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Edit -->
            <form id="editMutasiForm" method="POST" action="{{ route('mutasi.update', $mutasi->id) }}">
                @csrf
                @method('PUT')
                
                <!-- Step 1: Data Kapal -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="bi bi-ship me-2"></i>Informasi Kapal Tujuan</h3>
                        <p>Pilih kapal untuk penempatan mutasi ABK</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kapal" class="form-label required">Kapal Tujuan</label>
                                <select class="form-select" id="id_kapal" name="id_kapal" required>
                                    <option value="">Pilih Kapal...</option>
                                    @foreach($daftarKapal as $kapal)
                                        <option value="{{ $kapal['id'] }}" 
                                                data-pax="{{ $kapal['tipe_pax'] }}" 
                                                data-base="{{ $kapal['home_base'] }}"
                                                {{ $mutasi->id_kapal == $kapal['id'] ? 'selected' : '' }}>
                                            {{ $kapal['nama_kapal'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kapal-info-card" id="kapalInfoCard" style="{{ $mutasi->kapal ? 'display: block;' : 'display: none;' }}">
                                <h6><i class="bi bi-info-circle me-2"></i>Info Kapal</h6>
                                <div class="info-item">
                                    <span class="label">Nama Kapal:</span>
                                    <span class="value" id="kapalNama">{{ $mutasi->kapal->nama_kapal ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Kapasitas PAX:</span>
                                    <span class="value" id="kapalPax">{{ $mutasi->kapal->tipe_pax ?? 0 }} orang</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Home Base:</span>
                                    <span class="value" id="kapalBase">{{ $mutasi->kapal->home_base ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Data ABK Naik -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="bi bi-person-plus me-2"></i>Data ABK Naik</h3>
                        <p>Informasi ABK yang akan naik ke kapal</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nrp_naik" class="form-label required">Cari ABK</label>
                                <select class="form-control" id="nrp_naik" name="nrp_naik" required>
                                    @if($mutasi->abkNaik)
                                        <option value="{{ $mutasi->abkNaik->id }}" selected>
                                            {{ $mutasi->abkNaik->id }} - {{ $mutasi->abkNaik->nama_abk }}
                                        </option>
                                    @endif
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama_naik" class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_naik" name="nama_naik" 
                                       value="{{ $mutasi->nama_lengkap_naik }}" readonly required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="jabatan_naik" class="form-label required">Jabatan Tetap</label>
                                <input type="text" class="form-control" id="jabatan_naik_display" 
                                       value="{{ $mutasi->jabatanTetapNaik->nama_jabatan ?? '-' }}" readonly>
                                <input type="hidden" id="jabatan_naik" name="jabatan_naik" 
                                       value="{{ $mutasi->jabatan_tetap_naik }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_jabatan_mutasi" class="form-label required">Jabatan Mutasi</label>
                                <select class="form-select" id="id_jabatan_mutasi" name="id_jabatan_mutasi" required>
                                    <option value="">Pilih Jabatan Mutasi...</option>
                                    @foreach($daftarJabatan as $jabatan)
                                        <option value="{{ $jabatan->id }}" 
                                                {{ $mutasi->id_jabatan_mutasi == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama_mutasi" class="form-label required">Nama Mutasi</label>
                                <input type="text" class="form-control" id="nama_mutasi" name="nama_mutasi" 
                                       value="{{ $mutasi->nama_mutasi }}" readonly required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="jenis_mutasi" class="form-label required">Jenis Mutasi</label>
                                <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required>
                                    <option value="">Pilih Jenis...</option>
                                    <option value="Sementara" {{ $mutasi->jenis_mutasi == 'Sementara' ? 'selected' : '' }}>Sementara</option>
                                    <option value="Definitif" {{ $mutasi->jenis_mutasi == 'Definitif' ? 'selected' : '' }}>Definitif</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TMT" class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                                <input type="date" class="form-control" id="TMT" name="TMT" 
                                       value="{{ $mutasi->TMT ? $mutasi->TMT->format('Y-m-d') : '' }}" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TAT" class="form-label required">TAT (Terhitung Akhir Tanggal)</label>
                                <input type="date" class="form-control" id="TAT" name="TAT" 
                                       value="{{ $mutasi->TAT ? $mutasi->TAT->format('Y-m-d') : '' }}" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Data ABK Turun (Optional) -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="bi bi-person-dash me-2"></i>Data ABK Turun (Opsional)</h3>
                        <p>Informasi ABK yang akan turun dari kapal (jika ada)</p>
                        
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ada_abk_turun" name="ada_abk_turun" 
                                   value="1" {{ $mutasi->ada_abk_turun ? 'checked' : '' }}>
                            <label class="form-check-label" for="ada_abk_turun">
                                Ada ABK yang turun dari kapal ini
                            </label>
                        </div>
                    </div>
                    
                    <div id="abkTurunSection" style="{{ $mutasi->ada_abk_turun ? 'display: block;' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nrp_turun" class="form-label">Cari ABK Turun</label>
                                    <select class="form-control" id="nrp_turun" name="nrp_turun">
                                        @if($mutasi->abkTurun)
                                            <option value="{{ $mutasi->abkTurun->id }}" selected>
                                                {{ $mutasi->abkTurun->id }} - {{ $mutasi->abkTurun->nama_abk }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nama_turun" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_turun" name="nama_turun" 
                                           value="{{ $mutasi->nama_lengkap_turun }}" readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="jabatan_turun" class="form-label">Jabatan Tetap</label>
                                    <input type="text" class="form-control" id="jabatan_turun_display" 
                                           value="{{ $mutasi->jabatanTetapTurun->nama_jabatan ?? '-' }}" readonly>
                                    <input type="hidden" id="jabatan_turun" name="jabatan_turun" 
                                           value="{{ $mutasi->jabatan_tetap_turun }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_jabatan_mutasi_turun" class="form-label">Jabatan Mutasi</label>
                                    <select class="form-select" id="id_jabatan_mutasi_turun" name="id_jabatan_mutasi_turun">
                                        <option value="">Pilih Jabatan Mutasi...</option>
                                        @foreach($daftarJabatan as $jabatan)
                                            <option value="{{ $jabatan->id }}" 
                                                    {{ $mutasi->id_jabatan_mutasi_turun == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nama_mutasi_turun" class="form-label">Nama Mutasi</label>
                                    <input type="text" class="form-control" id="nama_mutasi_turun" name="nama_mutasi_turun" 
                                           value="{{ $mutasi->nama_mutasi_turun }}" readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="jenis_mutasi_turun" class="form-label">Jenis Mutasi</label>
                                    <select class="form-select" id="jenis_mutasi_turun" name="jenis_mutasi_turun">
                                        <option value="">Pilih Jenis...</option>
                                        <option value="Sementara" {{ $mutasi->jenis_mutasi_turun == 'Sementara' ? 'selected' : '' }}>Sementara</option>
                                        <option value="Definitif" {{ $mutasi->jenis_mutasi_turun == 'Definitif' ? 'selected' : '' }}>Definitif</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TMT_turun" class="form-label">TMT Turun</label>
                                    <input type="date" class="form-control" id="TMT_turun" name="TMT_turun" 
                                           value="{{ $mutasi->TMT_turun ? $mutasi->TMT_turun->format('Y-m-d') : '' }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TAT_turun" class="form-label">TAT Turun</label>
                                    <input type="date" class="form-control" id="TAT_turun" name="TAT_turun" 
                                           value="{{ $mutasi->TAT_turun ? $mutasi->TAT_turun->format('Y-m-d') : '' }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan_turun" class="form-label">Keterangan Turun</label>
                            <textarea class="form-control" id="keterangan_turun" name="keterangan_turun" 
                                      rows="3" placeholder="Masukkan keterangan untuk ABK turun...">{{ $mutasi->keterangan_turun }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Informasi Tambahan -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="bi bi-gear me-2"></i>Pengaturan Mutasi</h3>
                        <p>Konfigurasi tambahan untuk mutasi</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_mutasi" class="form-label required">Status Mutasi</label>
                                <select class="form-select" id="status_mutasi" name="status_mutasi" required>
                                    <option value="Draft" {{ $mutasi->status_mutasi == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Disetujui" {{ $mutasi->status_mutasi == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ $mutasi->status_mutasi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="Selesai" {{ $mutasi->status_mutasi == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="perlu_sertijab" name="perlu_sertijab" 
                                       value="1" {{ $mutasi->perlu_sertijab ? 'checked' : '' }}>
                                <label class="form-check-label" for="perlu_sertijab">
                                    Memerlukan Serah Terima Jabatan
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="4" 
                                          placeholder="Masukkan catatan tambahan untuk mutasi ini...">{{ $mutasi->catatan }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-save me-2"></i>
                            <span class="btn-text">Update Mutasi</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Memproses data mutasi...</p>
    </div>
</div>

<!-- Toast Notification Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="notificationToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="notification-icon me-2"></i>
                <span class="notification-message"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
    color: var(--warning-color);
}

.page-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 14px;
}

/* Status Badge */
.status-badge {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-badge.draft {
    background: #f3f4f6;
    color: #374151;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* Form Sections */
.form-section {
    background: white;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
    margin-bottom: 24px;
    overflow: hidden;
}

.section-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
}

.section-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
}

.section-header p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}

.form-section .row {
    padding: 24px;
}

.form-section .form-check {
    margin-top: 16px;
}

/* Form Controls */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
    font-size: 14px;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger-color);
}

.form-control, 
.form-select {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 10px 12px;
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus, 
.form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-control:invalid,
.form-select:invalid {
    border-color: var(--danger-color);
}

.invalid-feedback {
    font-size: 12px;
    color: var(--danger-color);
    margin-top: 4px;
}

/* Kapal Info Card */
.kapal-info-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 16px;
    margin-top: 8px;
}

.kapal-info-card h6 {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 12px;
    font-size: 14px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 13px;
}

.info-item .label {
    color: var(--text-muted);
    font-weight: 500;
}

.info-item .value {
    color: var(--text-dark);
    font-weight: 600;
}

/* Form Check Switch */
.form-check-input:checked {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.form-check-label {
    font-weight: 500;
    color: var(--text-dark);
}

/* Form Actions */
.form-actions {
    background: white;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
    padding: 20px 24px;
    margin-bottom: 24px;
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
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: var(--shadow-medium);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    color: white;
    text-decoration: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Loading states */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9998;
    backdrop-filter: blur(4px);
}

.loading-content {
    background: white;
    padding: 32px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.loading-content p {
    color: var(--text-muted);
    margin: 0;
    font-weight: 500;
}

/* Toast Notification */
.toast {
    min-width: 300px;
    backdrop-filter: blur(10px);
    border-radius: 10px !important;
}

.toast.toast-success {
    background: linear-gradient(45deg, #10b981, #34d399);
    color: white;
}

.toast.toast-error {
    background: linear-gradient(45deg, #ef4444, #f87171);
    color: white;
}

.notification-icon {
    font-size: 1.2rem;
}

/* Animations */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .form-section .row {
        padding: 16px;
    }
    
    .section-header {
        padding: 16px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .form-actions {
        padding: 16px;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 12px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for ABK search
    initializeSelect2();
    
    // Initialize form event listeners
    initializeFormEvents();
    
    // Initialize form validation
    initializeFormValidation();
});

// Initialize Select2 for ABK search
function initializeSelect2() {
    // ABK Naik Select2
    $('#nrp_naik').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik NRP atau nama ABK...',
        allowClear: true,
        ajax: {
            url: '{{ route("mutasi.search-abk") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    type: 'naik'
                };
            },
            processResults: function (data) {
                return {
                    results: data.results || []
                };
            },
            cache: true
        },
        templateResult: function(abk) {
            if (abk.loading) return abk.text;
            
            return $(`
                <div class="abk-option">
                    <div class="abk-name">${abk.nama_abk}</div>
                    <div class="abk-details">
                        <small class="text-muted">NRP: ${abk.nrp} | Jabatan: ${abk.jabatan_nama}</small>
                    </div>
                </div>
            `);
        },
        templateSelection: function(abk) {
            return abk.nrp ? `${abk.nrp} - ${abk.nama_abk}` : abk.text;
        }
    }).on('select2:select', function(e) {
        const data = e.params.data;
        
        // Update form fields untuk ABK naik
        $('#nama_naik').val(data.nama_abk || '');
        $('#jabatan_naik_display').val(data.jabatan_nama || '');
        $('#jabatan_naik').val(data.jabatan_id || '');
    }).on('select2:clear', function() {
        // Clear form fields untuk ABK naik
        $('#nama_naik').val('');
        $('#jabatan_naik_display').val('');
        $('#jabatan_naik').val('');
    });

    // ABK Turun Select2
    $('#nrp_turun').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik NRP atau nama ABK...',
        allowClear: true,
        ajax: {
            url: '{{ route("mutasi.search-abk") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    type: 'turun'
                };
            },
            processResults: function (data) {
                return {
                    results: data.results || []
                };
            },
            cache: true
        },
        templateResult: function(abk) {
            if (abk.loading) return abk.text;
            
            return $(`
                <div class="abk-option">
                    <div class="abk-name">${abk.nama_abk}</div>
                    <div class="abk-details">
                        <small class="text-muted">NRP: ${abk.nrp} | Jabatan: ${abk.jabatan_nama}</small>
                    </div>
                </div>
            `);
        },
        templateSelection: function(abk) {
            return abk.nrp ? `${abk.nrp} - ${abk.nama_abk}` : abk.text;
        }
    }).on('select2:select', function(e) {
        const data = e.params.data;
        
        // Update form fields untuk ABK turun
        $('#nama_turun').val(data.nama_abk || '');
        $('#jabatan_turun_display').val(data.jabatan_nama || '');
        $('#jabatan_turun').val(data.jabatan_id || '');
    }).on('select2:clear', function() {
        // Clear form fields untuk ABK turun
        $('#nama_turun').val('');
        $('#jabatan_turun_display').val('');
        $('#jabatan_turun').val('');
    });
}

// Initialize form events
function initializeFormEvents() {
    // Kapal selection change
    document.getElementById('id_kapal').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kapalInfoCard = document.getElementById('kapalInfoCard');
        
        if (this.value) {
            const namaKapal = selectedOption.text;
            const pax = selectedOption.dataset.pax || '0';
            const base = selectedOption.dataset.base || '-';
            
            document.getElementById('kapalNama').textContent = namaKapal;
            document.getElementById('kapalPax').textContent = pax + ' orang';
            document.getElementById('kapalBase').textContent = base;
            
            kapalInfoCard.style.display = 'block';
        } else {
            kapalInfoCard.style.display = 'none';
        }
    });

    // Toggle ABK Turun section
    document.getElementById('ada_abk_turun').addEventListener('change', function() {
        const abkTurunSection = document.getElementById('abkTurunSection');
        const isChecked = this.checked;
        
        if (isChecked) {
            abkTurunSection.style.display = 'block';
            // Make fields required
            document.getElementById('nrp_turun').required = true;
            document.getElementById('id_jabatan_mutasi_turun').required = true;
            document.getElementById('jenis_mutasi_turun').required = true;
            document.getElementById('TMT_turun').required = true;
            document.getElementById('TAT_turun').required = true;
        } else {
            abkTurunSection.style.display = 'none';
            // Remove required
            document.getElementById('nrp_turun').required = false;
            document.getElementById('id_jabatan_mutasi_turun').required = false;
            document.getElementById('jenis_mutasi_turun').required = false;
            document.getElementById('TMT_turun').required = false;
            document.getElementById('TAT_turun').required = false;
            
            // Clear values
            $('#nrp_turun').val(null).trigger('change');
            document.getElementById('nama_turun').value = '';
            document.getElementById('jabatan_turun_display').value = '';
            document.getElementById('jabatan_turun').value = '';
            document.getElementById('id_jabatan_mutasi_turun').value = '';
            document.getElementById('nama_mutasi_turun').value = '';
            document.getElementById('jenis_mutasi_turun').value = '';
            document.getElementById('TMT_turun').value = '';
            document.getElementById('TAT_turun').value = '';
            document.getElementById('keterangan_turun').value = '';
        }
    });

    // Auto-fill nama_mutasi when jabatan_mutasi changes
    document.getElementById('id_jabatan_mutasi').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const namaMutasi = selectedOption.text;
        
        if (this.value) {
            document.getElementById('nama_mutasi').value = namaMutasi;
        } else {
            document.getElementById('nama_mutasi').value = '';
        }
    });

    // Auto-fill nama_mutasi_turun when jabatan_mutasi_turun changes
    document.getElementById('id_jabatan_mutasi_turun').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const namaMutasiTurun = selectedOption.text;
        
        if (this.value) {
            document.getElementById('nama_mutasi_turun').value = namaMutasiTurun;
        } else {
            document.getElementById('nama_mutasi_turun').value = '';
        }
    });

    // Date validation
    document.getElementById('TMT').addEventListener('change', function() {
        const tatField = document.getElementById('TAT');
        tatField.min = this.value;
        
        if (tatField.value && tatField.value <= this.value) {
            tatField.value = '';
        }
    });

    document.getElementById('TMT_turun').addEventListener('change', function() {
        const tatTurunField = document.getElementById('TAT_turun');
        tatTurunField.min = this.value;
        
        if (tatTurunField.value && tatTurunField.value <= this.value) {
            tatTurunField.value = '';
        }
    });
}

// Initialize form validation - Sederhana
function initializeFormValidation() {
    const form = document.getElementById('editMutasiForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });
}

// Submit form - Disederhanakan
function submitForm() {
    const form = document.getElementById('editMutasiForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Set loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    btnText.textContent = 'Memproses...';
    loadingOverlay.style.display = 'flex';
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Data mutasi berhasil diupdate!', 'success');
            
            // Redirect after success
            setTimeout(() => {
                window.location.href = data.redirect_url || '{{ route("mutasi.index") }}';
            }, 1500);
        } else {
            throw new Error(data.message || 'Gagal update mutasi');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal update mutasi: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-loading');
        btnText.textContent = 'Update Mutasi';
        loadingOverlay.style.display = 'none';
    });
}

// Show notification - Sederhana
function showNotification(message, type = 'info') {
    const toast = document.getElementById('notificationToast');
    if (!toast) return;
    
    const toastIcon = toast.querySelector('.notification-icon');
    const toastMessage = toast.querySelector('.notification-message');
    
    // Remove existing classes
    toast.classList.remove('toast-success', 'toast-error');
    
    // Set icon and class
    if (type === 'success') {
        toastIcon.className = 'notification-icon me-2 bi bi-check-circle-fill';
        toast.classList.add('toast-success');
    } else {
        toastIcon.className = 'notification-icon me-2 bi bi-exclamation-triangle-fill';
        toast.classList.add('toast-error');
    }
    
    toastMessage.textContent = message;
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
}
</script>
@endpush