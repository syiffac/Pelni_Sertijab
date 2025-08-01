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
                <p class="page-subtitle">Tambahkan data ABK dan proses mutasi serah terima jabatan</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('abk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Multi-Step Form -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="form-container">
                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step-item active" data-step="1">
                        <div class="step-circle">
                            <i class="bi bi-ship"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">Pilih Kapal</h6>
                            <p class="step-subtitle">Tentukan kapal tujuan</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="2">
                        <div class="step-circle">
                            <i class="bi bi-person-up"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">Data ABK Naik</h6>
                            <p class="step-subtitle">Informasi ABK yang naik</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="3">
                        <div class="step-circle">
                            <i class="bi bi-person-down"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">Data ABK Turun</h6>
                            <p class="step-subtitle">Informasi ABK yang turun (opsional)</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="4">
                        <div class="step-circle">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">Konfirmasi</h6>
                            <p class="step-subtitle">Review dan submit</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form id="tambahABKForm" action="{{ route('abk.store') }}" method="POST" class="multi-step-form">
                    @csrf
                    
                    <!-- Step 1: Pilih Kapal -->
                    <div class="step-content active" data-step="1">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-ship me-2"></i>
                                    Pilih Kapal Tujuan
                                </h4>
                                <p class="step-description">Tentukan kapal tempat ABK akan ditempatkan atau dimutasi</p>
                            </div>
                            
                            <div class="step-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group mb-4">
                                            <label for="id_kapal" class="form-label required">Kapal Tujuan</label>
                                            <select class="form-select select2" id="id_kapal" name="id_kapal" required>
                                                <option value="">-- Pilih Kapal --</option>
                                                @foreach($daftarKapal ?? [] as $kapal)
                                                    <option value="{{ $kapal->id_kapal ?? $kapal['id_kapal'] }}" 
                                                            data-code="{{ $kapal->id ?? $kapal['id'] ?? '' }}">
                                                        {{ $kapal->nama_kapal ?? $kapal['nama_kapal'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih kapal tempat ABK akan bertugas</div>
                                        </div>
                                        
                                        <!-- Note: Removed old kapal info card, now displayed inline -->
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

                    <!-- Step 2: Data ABK Naik -->
                    <div class="step-content" data-step="2">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-person-up me-2"></i>
                                    Data ABK yang Naik
                                </h4>
                                <p class="step-description">Masukkan informasi lengkap ABK yang akan naik/bertugas di kapal</p>
                            </div>
                            
                            <div class="step-body">
                                <div class="row">
                                    <!-- Personal Data -->
                                    <div class="col-lg-6">
                                        <h6 class="section-title">
                                            <i class="bi bi-person me-2"></i>
                                            Data Pribadi
                                        </h6>
                                        
                                        <div class="form-group mb-3">
                                            <label for="nrp_naik" class="form-label required">NRP</label>
                                            <input type="text" class="form-control" id="nrp_naik" name="nrp_naik" 
                                                   placeholder="Masukkan NRP ABK" required>
                                            <div class="form-text">Nomor Registrasi Pokok ABK</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="nama_naik" class="form-label required">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama_naik" name="nama_naik" 
                                                   placeholder="Masukkan nama lengkap ABK" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jabatan_naik" class="form-label required">Jabatan</label>
                                            <select class="form-select" id="jabatan_naik" name="jabatan_naik" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                <option value="1">Nakhoda</option>
                                                <option value="2">Mualim I</option>
                                                <option value="3">KKM (Kepala Kamar Mesin)</option>
                                            </select>
                                            <div class="form-text">Pilih jabatan sesuai dengan posisi ABK di kapal</div>
                                        </div>
                                    </div>

                                    <!-- Mutation Data -->
                                    <div class="col-lg-6">
                                        <h6 class="section-title">
                                            <i class="bi bi-arrow-repeat me-2"></i>
                                            Data Mutasi
                                        </h6>

                                        <div class="form-group mb-3">
                                            <label for="nama_mutasi" class="form-label required">Nama Mutasi</label>
                                            <select class="form-select" id="nama_mutasi" name="nama_mutasi" required>
                                                <option value="">-- Pilih Nama Mutasi --</option>
                                                <option value="MN" data-desc="Berlayar">MN (Berlayar)</option>
                                                <option value="TOD" data-desc="Tour of Duty">TOD (Tour of Duty)</option>
                                                <option value="CW" data-desc="Cuti Wajib">CW (Cuti Wajib)</option>
                                                <option value="MI" data-desc="Mutasi Internal">MI (Mutasi Internal)</option>
                                                <option value="MS" data-desc="Sakit">MS (Sakit)</option>
                                                <option value="PC" data-desc="Pengganti Cuti">PC (Pengganti Cuti)</option>
                                                <option value="AJS" data-desc="Antar Jabatan Sementara">AJS (Antar Jabatan Sementara)</option>
                                                <option value="AJT" data-desc="Antar Jabatan Tetap">AJT (Antar Jabatan Tetap)</option>
                                            </select>
                                            <div class="form-text">Pilih jenis nama mutasi sesuai dengan kondisi ABK</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jenis_mutasi" class="form-label required">Jenis Mutasi</label>
                                            <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required>
                                                <option value="">-- Pilih Jenis Mutasi --</option>
                                                <option value="Mutasi Sementara">Mutasi Sementara</option>
                                                <option value="Mutasi Definitif">Mutasi Definitif</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="tmt" class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                                                    <input type="date" class="form-control" id="tmt" name="tmt" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="tat" class="form-label required">TAT (Terhitung Akhir Tanggal)</label>
                                                    <input type="date" class="form-control" id="tat" name="tat" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-footer">
                                <button type="button" class="btn btn-outline-secondary btn-prev">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Sebelumnya
                                </button>
                                <button type="button" class="btn btn-primary btn-next">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Lanjutkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Data ABK Turun -->
                    <div class="step-content" data-step="3">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-person-down me-2"></i>
                                    Data ABK yang Turun (Opsional)
                                </h4>
                                <p class="step-description">Jika ada ABK yang akan digantikan, lengkapi data berikut</p>
                            </div>
                            
                            <div class="step-body">
                                <!-- Checkbox for ABK Turun -->
                                <div class="form-check-container mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="adaAbkTurun" name="ada_abk_turun">
                                        <label class="form-check-label" for="adaAbkTurun">
                                            <strong>Ada ABK yang turun/digantikan</strong>
                                            <small class="d-block text-muted">Checklist jika ada ABK yang akan turun dari jabatan ini</small>
                                        </label>
                                    </div>
                                </div>

                                <!-- Form ABK Turun (Hidden by default) -->
                                <div id="formAbkTurun" class="abk-turun-form d-none">
                                    <div class="row">
                                        <!-- Personal Data ABK Turun -->
                                        <div class="col-lg-6">
                                            <h6 class="section-title">
                                                <i class="bi bi-person me-2"></i>
                                                Data Pribadi ABK yang Turun
                                            </h6>
                                            
                                            <div class="form-group mb-3">
                                                <label for="nrp_turun" class="form-label">NRP</label>
                                                <input type="text" class="form-control" id="nrp_turun" name="nrp_turun" 
                                                       placeholder="Masukkan NRP ABK yang turun">
                                                <div class="form-text">Nomor Registrasi Pokok ABK yang turun</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="nama_turun" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="nama_turun" name="nama_turun" 
                                                       placeholder="Masukkan nama lengkap ABK yang turun">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="jabatan_turun" class="form-label">Jabatan</label>
                                                <select class="form-select" id="jabatan_turun" name="jabatan_turun">
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    <option value="1">Nakhoda</option>
                                                    <option value="2">Mualim I</option>
                                                    <option value="3">KKM (Kepala Kamar Mesin)</option>
                                                </select>
                                                <div class="form-text">Pilih jabatan ABK yang akan turun</div>
                                            </div>
                                        </div>

                                        <!-- Data Mutasi & Informasi Tambahan ABK Turun -->
                                        <div class="col-lg-6">
                                            <h6 class="section-title">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Informasi Turun & Mutasi
                                            </h6>

                                            <div class="form-group mb-3">
                                                <label for="alasan_turun" class="form-label">Alasan Turun</label>
                                                <select class="form-select" id="alasan_turun" name="alasan_turun">
                                                    <option value="">-- Pilih Alasan --</option>
                                                    <option value="Mutasi Rutin">Mutasi Rutin</option>
                                                    <option value="Promosi">Promosi</option>
                                                    <option value="Rotasi Berkala">Rotasi Berkala</option>
                                                    <option value="Pensiun">Pensiun</option>
                                                    <option value="Kesehatan">Alasan Kesehatan</option>
                                                    <option value="Disiplin">Alasan Disiplin</option>
                                                    <option value="Permintaan Sendiri">Permintaan Sendiri</option>
                                                    <option value="Kontrak Habis">Kontrak Habis</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                                <div class="form-text">Pilih alasan ABK turun dari jabatan</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="status_abk_turun" class="form-label">Status ABK Setelah Turun</label>
                                                <select class="form-select" id="status_abk_turun" name="status_abk_turun">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Aktif - Mutasi Kapal Lain">Aktif - Mutasi ke Kapal Lain</option>
                                                    <option value="Aktif - Darat">Aktif - Penugasan Darat</option>
                                                    <option value="Cuti">Cuti</option>
                                                    <option value="Sakit">Sakit</option>
                                                    <option value="Non-Aktif">Non-Aktif</option>
                                                    <option value="Pensiun">Pensiun</option>
                                                </select>
                                                <div class="form-text">Status ABK setelah turun dari jabatan</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="kapal_asal_nama" class="form-label">Kapal Asal</label>
                                                <input type="text" class="form-control" id="kapal_asal_nama" name="kapal_asal_nama" 
                                                       placeholder="Nama kapal asal ABK (jika dari kapal lain)">
                                                <div class="form-text">Kosongkan jika ABK turun dari kapal yang sama</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="keterangan_turun" class="form-label">Keterangan Tambahan</label>
                                                <textarea class="form-control" id="keterangan_turun" name="keterangan_turun" 
                                                          rows="3" placeholder="Keterangan tambahan tentang ABK yang turun (opsional)"></textarea>
                                                <div class="form-text">Informasi tambahan terkait proses turun jabatan</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-footer">
                                <button type="button" class="btn btn-outline-secondary btn-prev">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Sebelumnya
                                </button>
                                <button type="button" class="btn btn-primary btn-next">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Review Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Konfirmasi -->
                    <div class="step-content" data-step="4">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Konfirmasi Data
                                </h4>
                                <p class="step-description">Review data sebelum menyimpan ke sistem</p>
                            </div>
                            
                            <div class="step-body">
                                <!-- Review Data -->
                                <div class="review-container">
                                    <!-- Kapal Info -->
                                    <div class="review-section">
                                        <h6 class="review-title">
                                            <i class="bi bi-ship me-2"></i>
                                            Informasi Kapal & Mutasi
                                        </h6>
                                        <div class="review-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Kapal Tujuan:</span>
                                                        <span id="reviewKapal" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Nama Mutasi:</span>
                                                        <span id="reviewNamaMutasi" class="review-value">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Jenis Mutasi:</span>
                                                        <span id="reviewJenisMutasi" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Periode:</span>
                                                        <span id="reviewPeriode" class="review-value">
                                                            <span id="reviewTmt">-</span> s/d <span id="reviewTat">-</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABK Naik Info -->
                                    <div class="review-section">
                                        <h6 class="review-title">
                                            <i class="bi bi-person-up me-2"></i>
                                            ABK yang Naik
                                        </h6>
                                        <div class="review-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">NRP:</span>
                                                        <span id="reviewNrpNaik" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Nama:</span>
                                                        <span id="reviewNamaNaik" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Jabatan:</span>
                                                        <span id="reviewJabatanNaik" class="review-value">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Jenis Mutasi:</span>
                                                        <span id="reviewJenisMutasi" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">TMT:</span>
                                                        <span id="reviewTmt" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">TAT:</span>
                                                        <span id="reviewTat" class="review-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABK Turun Info (if exists) -->
                                    <div id="reviewAbkTurun" class="review-section d-none">
                                        <h6 class="review-title">
                                            <i class="bi bi-person-down me-2"></i>
                                            ABK yang Turun
                                        </h6>
                                        <div class="review-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">NRP:</span>
                                                        <span id="reviewNrpTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Nama:</span>
                                                        <span id="reviewNamaTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Jabatan:</span>
                                                        <span id="reviewJabatanTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Kapal Asal:</span>
                                                        <span id="reviewKapalAsal" class="review-value">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Alasan Turun:</span>
                                                        <span id="reviewAlasanTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Status Setelah Turun:</span>
                                                        <span id="reviewStatusTurun" class="review-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
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
                                    <span class="submit-text">Simpan Data</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
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
@endsection

@push('styles')
<!-- Tambahkan CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Existing styles... -->
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

/* Progress Steps */
.progress-steps {
    display: flex;
    justify-content: space-between;
    padding: 32px 40px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 10%;
    right: 10%;
    height: 2px;
    background: var(--border-color);
    z-index: 1;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    flex: 1;
    max-width: 200px;
    position: relative;
    z-index: 2;
}

.step-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: white;
    border: 3px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
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
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.step-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    margin: 2px 0 0 0;
}

.step-item.active .step-title {
    color: var(--primary-blue);
}

/* Step Content */
.step-content[data-step] {
    display: none;
    animation: fadeInUp 0.5s ease-out;
}

.step-content[data-step].active {
    display: block;
}

.step-card {
    padding: 40px;
}

.step-header {
    text-align: center;
    margin-bottom: 32px;
}

.step-heading {
    font-size: 24px;
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
    font-size: 16px;
    margin: 0;
}

.step-body {
    margin-bottom: 32px;
}

.step-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.form-label.required::after {
    content: " *";
    color: var(--danger-color);
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

/* Kapal Info Card */
.kapal-info-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #0ea5e9;
    border-radius: 8px;
    margin-top: 16px;
    transition: var(--transition);
}

.info-header {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(14, 165, 233, 0.2);
}

.info-header h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #0c4a6e;
    display: flex;
    align-items: center;
}

.info-body {
    padding: 16px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

.info-value {
    font-size: 13px;
    color: #0c4a6e;
    font-weight: 600;
}

/* Checkbox Container */
.form-check-container {
    background: #f8fafc;
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.form-check {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    text-align: left;
}

.form-check-input {
    width: 20px;
    height: 20px;
    margin: 0;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    transition: var(--transition);
}

.form-check-input:checked {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.form-check-label {
    flex: 1;
}

/* ABK Turun Form */
.abk-turun-form {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 24px;
    background: #f8fafc;
    transition: var(--transition);
}

.abk-turun-form.show {
    animation: slideDown 0.3s ease-out;
}

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

/* Review Container */
.review-container {
    background: #f8fafc;
    border-radius: 8px;
    padding: 24px;
}

.review-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid var(--border-color);
}

.review-section:last-child {
    margin-bottom: 0;
}

.review-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.review-title i {
    color: var(--primary-blue);
}

.review-content {
    margin: 0;
}

.review-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
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

.btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
    position: relative;
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

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #059669;
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

.btn-submit .spinner-border {
    width: 16px;
    height: 16px;
}

.btn-submit.loading .submit-text {
    display: none;
}

.btn-submit.loading .spinner-border {
    display: inline-block !important;
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

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Select2 custom placeholder */
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
    color: #6c757d;
    font-style: italic;
}

/* Select2 dropdown items */
.select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
    padding: 8px 12px;
    transition: background-color 0.2s;
}

.select2-container--bootstrap-5 .select2-dropdown .select2-results__option--highlighted {
    background-color: var(--primary-blue);
    color: white;
}

/* Perbaiki CSS untuk dropdown */
.select2-result-kapal {
    padding: 8px 0;
}

.select2-result-kapal__title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
    font-size: 14px;
}

.select2-result-kapal__meta {
    font-size: 12px;
    color: #64748b;
}

.select2-result-kapal__code {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Style untuk info kapal yang dipilih */
.kapal-info-inline {
    margin-top: 8px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.kapal-info-badge {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #93c5fd;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.kapal-info-badge i {
    font-size: 10px;
}

/* Tambahkan CSS untuk mutasi info badge */
.mutasi-info-inline {
    margin: 8px 0 4px 0;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.mutasi-info-badge {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #fbbf24;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    animation: fadeInScale 0.3s ease-out;
}

.mutasi-info-badge i {
    font-size: 10px;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
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
    let currentStep = 1;
    const totalSteps = 4;
    
    // Elements
    const stepItems = document.querySelectorAll('.step-item');
    const stepContents = document.querySelectorAll('.step-content[data-step]');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const form = document.getElementById('tambahABKForm');
    const submitButton = document.querySelector('.btn-submit');
    
    // Form elements
    const kapalSelect = document.getElementById('id_kapal');
    const nextStep1Button = document.querySelector('[data-step="1"] .btn-next');
    const adaAbkTurunCheckbox = document.getElementById('adaAbkTurun');
    const formAbkTurun = document.getElementById('formAbkTurun');
    
    // Initialize
    updateStepDisplay();
    
    // Initialize Select2 for kapal dropdown
    $(document).ready(function() {
        $('#id_kapal').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kapal --',
            allowClear: true,
            width: '100%',
            templateResult: formatKapal,
            templateSelection: formatKapalSelection
        });
        
        // Event handler untuk kapal selection
        $('#id_kapal').on('change', function() {
            const selectedValue = $(this).val();
            const selectedOption = $(this).find('option:selected');
            const kodeKapal = selectedOption.data('code') || '-';
            
            console.log('Kapal selection changed to:', selectedValue);
            
            if (selectedValue && selectedValue !== "") {
                if (nextStep1Button) {
                    nextStep1Button.disabled = false;
                    nextStep1Button.removeAttribute('disabled');
                    console.log("Button enabled successfully");
                }
                
                // Update kapal info
                updateKapalInfo(kodeKapal);
            } else {
                if (nextStep1Button) {
                    nextStep1Button.disabled = true;
                    nextStep1Button.setAttribute('disabled', 'disabled');
                    console.log("Button disabled");
                }
                
                hideKapalInfo();
            }
        });
    });
    
    // Format kapal untuk dropdown
    function formatKapal(kapal) {
        if (!kapal.id) {
            return kapal.text;
        }
        
        const kodeKapal = $(kapal.element).data('code') || '-';
        
        const $container = $(
            `<div class="select2-result-kapal">
                <div class="select2-result-kapal__title">${kapal.text}</div>
                <div class="select2-result-kapal__meta">
                    <span class="select2-result-kapal__code">
                        <i class="bi bi-tag-fill me-1"></i>Kode: ${kodeKapal}
                    </span>
                </div>
            </div>`
        );
        
        return $container;
    }
    
    function formatKapalSelection(kapal) {
        if (!kapal.id) {
            return kapal.text;
        }
        
        const kodeKapal = $(kapal.element).data('code') || '-';
        return kapal.text + ' (' + kodeKapal + ')';
    }
    
    function updateKapalInfo(kodeKapal) {
        hideKapalInfo();
        
        const infoContainer = document.createElement('div');
        infoContainer.id = 'kapalInfoInline';
        infoContainer.className = 'kapal-info-inline';
        infoContainer.innerHTML = `
            <div class="kapal-info-badge">
                <i class="bi bi-tag-fill"></i>
                <span>Kode: ${kodeKapal}</span>
            </div>
        `;
        
        const select2Container = document.querySelector('.select2-container');
        if (select2Container) {
            select2Container.parentNode.insertBefore(infoContainer, select2Container.nextSibling);
        }
    }
    
    function hideKapalInfo() {
        const existingInfo = document.getElementById('kapalInfoInline');
        if (existingInfo) {
            existingInfo.remove();
        }
    }
    
    // Nama mutasi handler
    const namaMutasiSelect = document.getElementById('nama_mutasi');
    if (namaMutasiSelect) {
        namaMutasiSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = this.value;
            const description = selectedOption.getAttribute('data-desc') || '';
            
            updateMutasiInfo(selectedValue, description);
        });
    }
    
    function updateMutasiInfo(kode, description) {
        hideMutasiInfo();
        
        if (kode && description) {
            const infoContainer = document.createElement('div');
            infoContainer.id = 'mutasiInfoInline';
            infoContainer.className = 'mutasi-info-inline';
            infoContainer.innerHTML = `
                <div class="mutasi-info-badge">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>${description}</span>
                </div>
            `;
            
            const selectContainer = namaMutasiSelect.parentNode;
            if (selectContainer) {
                const formText = selectContainer.querySelector('.form-text');
                if (formText) {
                    selectContainer.insertBefore(infoContainer, formText);
                } else {
                    selectContainer.appendChild(infoContainer);
                }
            }
        }
    }
    
    function hideMutasiInfo() {
        const existingInfo = document.getElementById('mutasiInfoInline');
        if (existingInfo) {
            existingInfo.remove();
        }
    }
    
    // ABK Turun checkbox handler - Update untuk clear semua field termasuk yang baru
    if (adaAbkTurunCheckbox) {
        adaAbkTurunCheckbox.addEventListener('change', function() {
            if (this.checked) {
                formAbkTurun.classList.remove('d-none');
                formAbkTurun.classList.add('show');
            } else {
                formAbkTurun.classList.add('d-none');
                formAbkTurun.classList.remove('show');
                
                // Clear ABK turun fields
                const abkTurunFields = formAbkTurun.querySelectorAll('input, select, textarea');
                abkTurunFields.forEach(field => {
                    field.value = '';
                });
            }
        });
    }
    
    // Next button handlers
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Next button clicked, currentStep:', currentStep);
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                    updateReviewData();
                }
            }
        });
    });
    
    // Previous button handlers
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Previous button clicked, currentStep:', currentStep);
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        });
    });
    
    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateCurrentStep()) {
                submitForm();
            }
        });
    }
    
    function updateStepDisplay() {
        console.log('Updating step display to step:', currentStep);
        
        // Update step indicators
        stepItems.forEach((item, index) => {
            const stepNumber = index + 1;
            
            item.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                item.classList.add('completed');
                const icon = item.querySelector('.step-circle i');
                if (icon) icon.className = 'bi bi-check';
            } else if (stepNumber === currentStep) {
                item.classList.add('active');
                const icons = ['bi-ship', 'bi-person-up', 'bi-person-down', 'bi-check-circle'];
                const icon = item.querySelector('.step-circle i');
                if (icon) icon.className = `bi ${icons[index]}`;
            } else {
                const icons = ['bi-ship', 'bi-person-up', 'bi-person-down', 'bi-check-circle'];
                const icon = item.querySelector('.step-circle i');
                if (icon) icon.className = `bi ${icons[index]}`;
            }
        });
        
        // Update step content
        stepContents.forEach((content, index) => {
            const stepNumber = index + 1;
            content.classList.toggle('active', stepNumber === currentStep);
        });
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function validateCurrentStep() {
        const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if (!currentStepContent) {
            console.error('Current step content not found for step:', currentStep);
            return false;
        }
        
        const requiredFields = currentStepContent.querySelectorAll('input[required], select[required]');
        
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                
                let message = 'Field ini wajib diisi';
                if (field.id === 'nama_mutasi') {
                    message = 'Silakan pilih nama mutasi';
                } else if (field.id === 'id_kapal') {
                    message = 'Silakan pilih kapal';
                }
                
                showValidationError(field, message);
            } else {
                field.classList.remove('is-invalid');
                removeValidationError(field);
            }
        });
        
        // Additional validations
        if (currentStep === 2) {
            const tmt = document.getElementById('tmt');
            const tat = document.getElementById('tat');
            
            if (tmt && tat && tmt.value && tat.value && new Date(tmt.value) >= new Date(tat.value)) {
                showValidationError(tat, 'TAT harus setelah TMT');
                isValid = false;
            }
        }
        
        console.log('Validation result for step', currentStep, ':', isValid);
        return isValid;
    }
    
    function showValidationError(field, message) {
        removeValidationError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        field.parentNode.appendChild(errorDiv);
    }
    
    function removeValidationError(field) {
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }
    
    // Update function updateReviewData untuk field baru
    function updateReviewData() {
        if (currentStep === 4) {
            // Update kapal info
            const selectedKapal = $('#id_kapal').find('option:selected');
            const reviewKapal = document.getElementById('reviewKapal');
            const kapalCode = selectedKapal.data('code') || '-';
            
            if (reviewKapal) {
                reviewKapal.innerHTML = `${selectedKapal.text()} <span class="badge bg-light text-primary">Kode: ${kapalCode}</span>`;
            }
            
            // Update ABK Naik info
            const nrpNaik = document.getElementById('nrp_naik').value;
            const namaNaik = document.getElementById('nama_naik').value;
            const jabatanNaik = document.getElementById('jabatan_naik').options[document.getElementById('jabatan_naik').selectedIndex]?.text;
            const jenisMutasi = document.getElementById('jenis_mutasi').value;
            const tmt = document.getElementById('tmt').value;
            const tat = document.getElementById('tat').value;
            
            document.getElementById('reviewNrpNaik').innerText = nrpNaik || '-';
            document.getElementById('reviewNamaNaik').innerText = namaNaik || '-';
            document.getElementById('reviewJabatanNaik').innerText = jabatanNaik || '-';
            document.getElementById('reviewJenisMutasi').innerText = jenisMutasi || '-';
            document.getElementById('reviewTmt').innerText = tmt ? new Intl.DateTimeFormat('id-ID').format(new Date(tmt)) : '-';
            document.getElementById('reviewTat').innerText = tat ? new Intl.DateTimeFormat('id-ID').format(new Date(tat)) : '-';
            
            // Update ABK Turun info if checkbox is checked
            if (adaAbkTurunCheckbox.checked) {
                const nrpTurun = document.getElementById('nrp_turun').value;
                const namaTurun = document.getElementById('nama_turun').value;
                const jabatanTurun = document.getElementById('jabatan_turun').options[document.getElementById('jabatan_turun').selectedIndex]?.text;
                const alasanTurun = document.getElementById('alasan_turun').value;
                const statusTurun = document.getElementById('status_abk_turun').value;
                const kapalAsal = document.getElementById('kapal_asal_nama').value;
                
                document.getElementById('reviewNrpTurun').innerText = nrpTurun || '-';
                document.getElementById('reviewNamaTurun').innerText = namaTurun || '-';
                document.getElementById('reviewJabatanTurun').innerText = jabatanTurun || '-';
                document.getElementById('reviewKapalAsal').innerText = kapalAsal || '-';
                document.getElementById('reviewAlasanTurun').innerText = alasanTurun || '-';
                document.getElementById('reviewStatusTurun').innerText = statusTurun || '-';
                
                // Show ABK Turun review section
                document.getElementById('reviewAbkTurun').classList.remove('d-none');
            } else {
                // Hide ABK Turun review section
                document.getElementById('reviewAbkTurun').classList.add('d-none');
            }
        }
    }
    
    // Submit form function
    function submitForm() {
        // Show loading state on submit button
        submitButton.classList.add('loading');
        
        // Simulate form submission
        setTimeout(() => {
            // Hide loading state
            submitButton.classList.remove('loading');
            
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reset form after delay
            setTimeout(() => {
                form.reset();
                currentStep = 1;
                updateStepDisplay();
                hideKapalInfo();
                hideMutasiInfo();
                document.getElementById('reviewAbkTurun').classList.add('d-none');
            }, 2000);
        }, 1500);
    }
});
</script>
@endpush