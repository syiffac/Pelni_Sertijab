{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Arsip Dokumen Sertijab - SertijabPELNI')

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
                            <a href="{{ route('arsip.index') }}">
                                <i class="bi bi-archive"></i>
                                Arsip Dokumen
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-file-earmark-plus"></i>
                            Tambah Arsip
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-plus-fill"></i>
                    Tambah Arsip Dokumen Sertijab
                </h1>
                <p class="page-subtitle">Tambahkan dokumen sertijab beserta data mutasi ABK naik dan turun</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('arsip.index') }}" class="btn btn-outline-secondary">
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
                            <h6 class="step-title">ABK Naik</h6>
                            <p class="step-subtitle">Data ABK yang naik</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="3">
                        <div class="step-circle">
                            <i class="bi bi-person-down"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">ABK Turun</h6>
                            <p class="step-subtitle">Data ABK yang turun</p>
                        </div>
                    </div>

                    <div class="step-item" data-step="4">
                        <div class="step-circle">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="step-title">Upload Dokumen</h6>
                            <p class="step-subtitle">Upload file sertijab</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="5">
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
                <form id="tambahArsipForm" action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" class="multi-step-form">
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
                                                @foreach($kapalList ?? [] as $kapal)
                                                    <option value="{{ $kapal->id_kapal }}" 
                                                            data-code="{{ $kapal->id ?? '' }}"
                                                            data-type="{{ $kapal->jenis_kapal ?? '' }}">
                                                        {{ $kapal->nama_kapal }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih kapal tempat ABK akan bertugas</div>
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
                                            Data Pribadi ABK Naik
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
                                            <label for="jabatan_naik" class="form-label required">Jabatan Baru</label>
                                            <select class="form-select" id="jabatan_naik" name="jabatan_naik" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach($jabatanList ?? [] as $jabatan)
                                                    <option value="{{ $jabatan->id_jabatan }}">
                                                        {{ $jabatan->nama_jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="kapal_asal_naik" class="form-label">Kapal Asal</label>
                                            <select class="form-select" id="kapal_asal_naik" name="kapal_asal_naik">
                                                <option value="">-- Pilih Kapal Asal (Opsional) --</option>
                                                @foreach($kapalList ?? [] as $kapal)
                                                    <option value="{{ $kapal->id_kapal }}">
                                                        {{ $kapal->nama_kapal }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Kosongkan jika ABK baru atau dari darat</div>
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
                                            <input type="text" class="form-control" id="nama_mutasi" name="nama_mutasi" 
                                                   placeholder="Contoh: Mutasi Rutin Januari 2025" required>
                                            <div class="form-text">Nama atau kode mutasi ini</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jenis_mutasi" class="form-label required">Jenis Mutasi</label>
                                            <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required>
                                                <option value="">-- Pilih Jenis Mutasi --</option>
                                                <option value="Mutasi Rutin">Mutasi Rutin</option>
                                                <option value="Mutasi Darurat">Mutasi Darurat</option>
                                                <option value="Mutasi Promosi">Mutasi Promosi</option>
                                                <option value="Mutasi Demosi">Mutasi Demosi</option>
                                                <option value="Mutasi Kesehatan">Mutasi Kesehatan</option>
                                                <option value="Mutasi Disiplin">Mutasi Disiplin</option>
                                                <option value="Lainnya">Lainnya</option>
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
                                    Data ABK yang Turun
                                </h4>
                                <p class="step-description">Masukkan informasi ABK yang akan digantikan/turun dari jabatan</p>
                            </div>
                            
                            <div class="step-body">
                                <div class="row">
                                    <!-- Personal Data ABK Turun -->
                                    <div class="col-lg-6">
                                        <h6 class="section-title">
                                            <i class="bi bi-person me-2"></i>
                                            Data Pribadi ABK Turun
                                        </h6>
                                        
                                        <div class="form-group mb-3">
                                            <label for="nrp_turun" class="form-label required">NRP</label>
                                            <input type="text" class="form-control" id="nrp_turun" name="nrp_turun" 
                                                   placeholder="Masukkan NRP ABK yang turun" required>
                                            <div class="form-text">Nomor Registrasi Pokok ABK yang turun</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="nama_turun" class="form-label required">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama_turun" name="nama_turun" 
                                                   placeholder="Masukkan nama lengkap ABK yang turun" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jabatan_turun" class="form-label required">Jabatan Lama</label>
                                            <select class="form-select" id="jabatan_turun" name="jabatan_turun" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach($jabatanList ?? [] as $jabatan)
                                                    <option value="{{ $jabatan->id_jabatan }}">
                                                        {{ $jabatan->nama_jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="kapal_tujuan_turun" class="form-label">Kapal Tujuan</label>
                                            <select class="form-select" id="kapal_tujuan_turun" name="kapal_tujuan_turun">
                                                <option value="">-- Pilih Kapal Tujuan (Opsional) --</option>
                                                @foreach($kapalList ?? [] as $kapal)
                                                    <option value="{{ $kapal->id_kapal }}">
                                                        {{ $kapal->nama_kapal }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Kosongkan jika ABK turun ke darat atau pensiun</div>
                                        </div>
                                    </div>

                                    <!-- Alasan Turun -->
                                    <div class="col-lg-6">
                                        <h6 class="section-title">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Informasi Tambahan
                                        </h6>

                                        <div class="form-group mb-3">
                                            <label for="alasan_turun" class="form-label required">Alasan Turun</label>
                                            <select class="form-select" id="alasan_turun" name="alasan_turun" required>
                                                <option value="">-- Pilih Alasan --</option>
                                                <option value="Mutasi Rutin">Mutasi Rutin</option>
                                                <option value="Promosi">Promosi</option>
                                                <option value="Pensiun">Pensiun</option>
                                                <option value="Kesehatan">Alasan Kesehatan</option>
                                                <option value="Disiplin">Alasan Disiplin</option>
                                                <option value="Permintaan Sendiri">Permintaan Sendiri</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="tanggal_turun" class="form-label required">Tanggal Turun</label>
                                            <input type="date" class="form-control" id="tanggal_turun" name="tanggal_turun" required>
                                            <div class="form-text">Tanggal efektif ABK turun dari jabatan</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="keterangan_turun" class="form-label">Keterangan</label>
                                            <textarea class="form-control" id="keterangan_turun" name="keterangan_turun" 
                                                      rows="4" placeholder="Keterangan tambahan (opsional)"></textarea>
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
                                    Upload Dokumen
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Upload Dokumen -->
                    <div class="step-content" data-step="4">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-cloud-upload me-2"></i>
                                    Upload Dokumen Sertijab
                                </h4>
                                <p class="step-description">Upload file dokumen serah terima jabatan dalam format PDF</p>
                            </div>
                            
                            <div class="step-body">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <!-- Upload Area -->
                                        <div class="upload-area" id="uploadArea">
                                            <div class="upload-content">
                                                <div class="upload-icon">
                                                    <i class="bi bi-cloud-upload"></i>
                                                </div>
                                                <h5 class="upload-title">Drag & Drop file PDF disini</h5>
                                                <p class="upload-subtitle">atau klik untuk memilih file</p>
                                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('file_dokumen').click()">
                                                    <i class="bi bi-folder2-open me-2"></i>
                                                    Pilih File
                                                </button>
                                                <input type="file" class="d-none" id="file_dokumen" name="file_dokumen" accept=".pdf" required>
                                            </div>
                                        </div>

                                        <!-- File Preview -->
                                        <div id="filePreview" class="file-preview d-none">
                                            <div class="file-item">
                                                <div class="file-icon">
                                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                                </div>
                                                <div class="file-info">
                                                    <div class="file-name" id="fileName"></div>
                                                    <div class="file-size" id="fileSize"></div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Upload Requirements -->
                                        <div class="upload-requirements mt-4">
                                            <h6 class="requirements-title">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Persyaratan File
                                            </h6>
                                            <ul class="requirements-list">
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Format file: PDF</li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Ukuran maksimal: 10 MB</li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Dokumen harus jelas dan terbaca</li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Berisi tanda tangan dan cap resmi</li>
                                            </ul>
                                        </div>

                                        <!-- Additional Info -->
                                        <div class="form-group mt-4">
                                            <label for="keterangan_dokumen" class="form-label">Keterangan Dokumen</label>
                                            <textarea class="form-control" id="keterangan_dokumen" name="keterangan_dokumen" 
                                                      rows="3" placeholder="Tambahkan keterangan atau catatan khusus untuk dokumen ini (opsional)"></textarea>
                                        </div>

                                        <!-- Status Verifikasi -->
                                        <div class="form-group mt-4">
                                            <label for="status_verifikasi" class="form-label required">Status Verifikasi</label>
                                            <select class="form-select" id="status_verifikasi" name="status_verifikasi" required>
                                                <option value="pending">Pending - Menunggu Verifikasi</option>
                                                <option value="verified">Verified - Dokumen Valid</option>
                                                <option value="rejected">Rejected - Dokumen Ditolak</option>
                                            </select>
                                            <div class="form-text">Tentukan status verifikasi dokumen</div>
                                        </div>

                                        <!-- Admin Notes -->
                                        <div class="form-group mt-3">
                                            <label for="admin_notes" class="form-label">Catatan Admin</label>
                                            <textarea class="form-control" id="admin_notes" name="admin_notes" 
                                                      rows="2" placeholder="Catatan internal untuk admin (opsional)"></textarea>
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

                    <!-- Step 5: Konfirmasi -->
                    <div class="step-content" data-step="5">
                        <div class="step-card">
                            <div class="step-header">
                                <h4 class="step-heading">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Konfirmasi Data
                                </h4>
                                <p class="step-description">Review semua data sebelum menyimpan ke sistem arsip</p>
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
                                                        <span class="review-label">TMT - TAT:</span>
                                                        <span id="reviewPeriodeMutasi" class="review-value">-</span>
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
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Jabatan Baru:</span>
                                                        <span id="reviewJabatanNaik" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Kapal Asal:</span>
                                                        <span id="reviewKapalAsalNaik" class="review-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABK Turun Info -->
                                    <div class="review-section">
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
                                                        <span class="review-label">Jabatan Lama:</span>
                                                        <span id="reviewJabatanTurun" class="review-value">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Alasan Turun:</span>
                                                        <span id="reviewAlasanTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Tanggal Turun:</span>
                                                        <span id="reviewTanggalTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Kapal Tujuan:</span>
                                                        <span id="reviewKapalTujuanTurun" class="review-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dokumen Info -->
                                    <div class="review-section">
                                        <h6 class="review-title">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Informasi Dokumen
                                        </h6>
                                        <div class="review-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Nama File:</span>
                                                        <span id="reviewFileName" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Ukuran File:</span>
                                                        <span id="reviewFileSize" class="review-value">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Status Verifikasi:</span>
                                                        <span id="reviewStatusVerifikasi" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Keterangan:</span>
                                                        <span id="reviewKeteranganDokumen" class="review-value">-</span>
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
                                    <span class="submit-text">Simpan ke Arsip</span>
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
                <p class="mb-4">Dokumen arsip sertijab berhasil ditambahkan ke sistem</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('arsip.index') }}" class="btn btn-primary">
                        <i class="bi bi-archive me-2"></i>
                        Lihat Arsip
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

/* Base styles same as previous form... */
/* Copy all the base styles from the previous form */

/* Upload Area Styles */
.upload-area {
    border: 3px dashed var(--border-color);
    border-radius: var(--border-radius);
    padding: 60px 40px;
    text-align: center;
    background: var(--background-light);
    transition: var(--transition);
    cursor: pointer;
}

.upload-area:hover,
.upload-area.dragover {
    border-color: var(--primary-blue);
    background: rgba(37, 99, 235, 0.05);
}

.upload-content {
    max-width: 400px;
    margin: 0 auto;
}

.upload-icon {
    font-size: 48px;
    color: var(--primary-blue);
    margin-bottom: 16px;
}

.upload-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.upload-subtitle {
    color: var(--text-muted);
    margin-bottom: 24px;
}

/* File Preview */
.file-preview {
    margin-top: 20px;
    padding: 20px;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}

.file-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--background-light);
    border-radius: 8px;
}

.file-icon {
    font-size: 32px;
}

.file-info {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.file-size {
    font-size: 14px;
    color: var(--text-muted);
}

/* Upload Requirements */
.upload-requirements {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 20px;
}

.requirements-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirements-list li {
    display: flex;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: var(--text-dark);
}

/* Status Badge */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.verified {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .upload-area {
        padding: 40px 20px;
    }
    
    .upload-icon {
        font-size: 36px;
    }
    
    .upload-title {
        font-size: 18px;
    }
    
    .file-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    
    // Elements
    const stepItems = document.querySelectorAll('.step-item');
    const stepContents = document.querySelectorAll('.step-content[data-step]');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const form = document.getElementById('tambahArsipForm');
    const submitButton = document.querySelector('.btn-submit');
    
    // Form elements
    const kapalSelect = document.getElementById('id_kapal');
    const nextStep1Button = document.querySelector('[data-step="1"] .btn-next');
    const fileInput = document.getElementById('file_dokumen');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    
    // Initialize
    updateStepDisplay();
    
    // Initialize Select2
    $(document).ready(function() {
        $('#id_kapal, #kapal_asal_naik, #kapal_tujuan_turun').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kapal --',
            allowClear: true,
            width: '100%'
        });
        
        // Event handler for kapal selection
        $('#id_kapal').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue) {
                nextStep1Button.disabled = false;
            } else {
                nextStep1Button.disabled = true;
            }
        });
    });
    
    // File upload handling
    setupFileUpload();
    
    // Step navigation
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                    updateReviewData();
                }
            }
        });
    });
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateCurrentStep()) {
            submitForm();
        }
    });
    
    function setupFileUpload() {
        // Click to upload
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function() {
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
        
        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelection(this.files[0]);
            }
        });
    }
    
    function handleFileSelection(file) {
        // Validate file
        if (!validateFile(file)) {
            return;
        }
        
        // Update file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        // Show preview
        showFilePreview(file);
        
        // Hide upload area
        uploadArea.classList.add('d-none');
        filePreview.classList.remove('d-none');
    }
    
    function validateFile(file) {
        // Check file type
        if (file.type !== 'application/pdf') {
            alert('File harus berformat PDF');
            return false;
        }
        
        // Check file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file tidak boleh lebih dari 10MB');
            return false;
        }
        
        return true;
    }
    
    function showFilePreview(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    window.removeFile = function() {
        fileInput.value = '';
        uploadArea.classList.remove('d-none');
        filePreview.classList.add('d-none');
    };
    
    function updateStepDisplay() {
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
                // Restore original icon
                const icons = ['bi-ship', 'bi-person-up', 'bi-person-down', 'bi-cloud-upload', 'bi-check-circle'];
                const icon = item.querySelector('.step-circle i');
                if (icon) icon.className = `bi ${icons[index]}`;
            } else {
                // Restore original icon
                const icons = ['bi-ship', 'bi-person-up', 'bi-person-down', 'bi-cloud-upload', 'bi-check-circle'];
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
                showValidationError(field, 'Field ini wajib diisi');
            }
        });
        
        // Additional validations
        if (currentStep === 2) {
            // Validate dates
            const tmt = document.getElementById('tmt');
            const tat = document.getElementById('tat');
            
            if (tmt.value && tat.value && new Date(tmt.value) >= new Date(tat.value)) {
                showValidationError(tat, 'TAT harus setelah TMT');
                isValid = false;
            }
        }
        
        if (currentStep === 4) {
            // Validate file upload
            if (!fileInput.files.length) {
                alert('Silakan upload dokumen PDF terlebih dahulu');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    function showValidationError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        field.parentNode.appendChild(errorDiv);
    }
    
    function updateReviewData() {
        if (currentStep === 5) {
            // Update all review data
            updateElement('reviewKapal', $('#id_kapal option:selected').text());
            updateElement('reviewNamaMutasi', document.getElementById('nama_mutasi').value);
            updateElement('reviewJenisMutasi', document.getElementById('jenis_mutasi').value);
            
            const tmt = document.getElementById('tmt').value;
            const tat = document.getElementById('tat').value;
            updateElement('reviewPeriodeMutasi', `${formatDate(tmt)} - ${formatDate(tat)}`);
            
            // ABK Naik
            updateElement('reviewNrpNaik', document.getElementById('nrp_naik').value);
            updateElement('reviewNamaNaik', document.getElementById('nama_naik').value);
            updateElement('reviewJabatanNaik', $('#jabatan_naik option:selected').text());
            updateElement('reviewKapalAsalNaik', $('#kapal_asal_naik option:selected').text() || 'Tidak ada');
            
            // ABK Turun
            updateElement('reviewNrpTurun', document.getElementById('nrp_turun').value);
            updateElement('reviewNamaTurun', document.getElementById('nama_turun').value);
            updateElement('reviewJabatanTurun', $('#jabatan_turun option:selected').text());
            updateElement('reviewAlasanTurun', document.getElementById('alasan_turun').value);
            updateElement('reviewTanggalTurun', formatDate(document.getElementById('tanggal_turun').value));
            updateElement('reviewKapalTujuanTurun', $('#kapal_tujuan_turun option:selected').text() || 'Tidak ada');
            
            // Dokumen
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                updateElement('reviewFileName', file.name);
                updateElement('reviewFileSize', formatFileSize(file.size));
            }
            updateElement('reviewStatusVerifikasi', document.getElementById('status_verifikasi').value);
            updateElement('reviewKeteranganDokumen', document.getElementById('keterangan_dokumen').value || 'Tidak ada');
        }
    }
    
    function updateElement(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value || '-';
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }
    
    function submitForm() {
        // Add loading state
        submitButton.classList.add('loading');
        submitButton.disabled = true;
        
        // Submit via normal form submission (not AJAX due to file upload)
        form.submit();
    }
});
</script>
@endpush