@extends('layouts.app')

@section('title', 'Tambah Mutasi - SertijabPELNI')

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
                            <a href="{{ route('mutasi.index') }}">
                                <i class="bi bi-arrow-left-right"></i>
                                Data Mutasi
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Mutasi
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-plus-circle-fill"></i>
                    Tambah Mutasi Baru
                </h1>
                <p class="page-subtitle">Tambahkan data mutasi ABK dan proses serah terima jabatan</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mutasi.index') }}" class="btn btn-outline-secondary">
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
                <form id="tambahMutasiForm" action="{{ route('mutasi.store') }}" method="POST" class="multi-step-form" enctype="multipart/form-data">
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
                                                @foreach($daftarKapal as $kapal)
                                                    <option value="{{ $kapal['id_kapal'] }}" 
                                                            data-code="{{ $kapal['id'] }}"

                                                            data-nama="{{ $kapal['nama_kapal'] }}">

                                                        {{ $kapal['nama_kapal'] }} ({{ $kapal['id'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih kapal tempat ABK akan bertugas</div>
                                            
                                            <!-- Info Kapal yang dipilih -->
                                            <div id="kapalInfo" class="kapal-info-inline" style="display: none;">
                                                <div class="kapal-info-badge">
                                                    <i class="bi bi-ship"></i>
                                                    <span id="selectedKapalName">-</span>
                                                </div>
                                                <div class="kapal-info-badge">
                                                    <i class="bi bi-hash"></i>
                                                    Kode: <span id="selectedKapalCode">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info Statistik Kapal (optional) -->
                                    <div class="col-lg-4">
                                        <div class="info-card">
                                            <div class="info-header">
                                                <i class="bi bi-info-circle"></i>
                                                <span>Informasi</span>
                                            </div>
                                            <div class="info-content">
                                                <small class="text-muted">
                                                    Total <strong>{{ $daftarKapal->count() }}</strong> kapal tersedia dalam sistem.
                                                    Pilih kapal sesuai dengan tujuan mutasi ABK.
                                                </small>
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
                                            <label for="jabatan_naik" class="form-label required">Jabatan Tetap</label>
                                            <select class="form-select jabatan-select" id="jabatan_naik" name="jabatan_naik" required>
                                                <option value="">-- Pilih Jabatan Tetap --</option>
                                                @foreach($daftarJabatan as $jabatan)
                                                    <option value="{{ $jabatan->id }}" 
                                                            data-level="{{ $jabatan->level_jabatan ?? 0 }}"


                                                            data-kode="{{ $jabatan->kode_jabatan ?? '' }}">

                                                        {{ $jabatan->nama_jabatan }}
                                                        @if($jabatan->kode_jabatan)
                                                            ({{ $jabatan->kode_jabatan }})
                                                        @endif
                                                        @if(isset($jabatan->level_jabatan))
                                                            - Level {{ $jabatan->level_jabatan }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih jabatan tetap ABK</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="id_jabatan_mutasi" class="form-label required">Jabatan Mutasi</label>
                                            <select class="form-select jabatan-select" id="id_jabatan_mutasi" name="id_jabatan_mutasi" required>
                                                <option value="">-- Pilih Jabatan Mutasi --</option>
                                                @foreach($daftarJabatan as $jabatan)
                                                    <option value="{{ $jabatan->id }}" 
                                                            data-level="{{ $jabatan->level_jabatan ?? 0 }}"


                                                            data-kode="{{ $jabatan->kode_jabatan ?? '' }}">

                                                        {{ $jabatan->nama_jabatan }}
                                                        @if($jabatan->kode_jabatan)
                                                            ({{ $jabatan->kode_jabatan }})
                                                        @endif
                                                        @if(isset($jabatan->level_jabatan))
                                                            - Level {{ $jabatan->level_jabatan }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih jabatan hasil mutasi</div>
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
                                                <option value="Sementara">Mutasi Sementara</option>
                                                <option value="Definitif">Mutasi Definitif</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="TMT" class="form-label required">TMT</label>
                                                    <input type="date" class="form-control" id="TMT" name="TMT" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="TAT" class="form-label required">TAT</label>
                                                    <input type="date" class="form-control" id="TAT" name="TAT" required>
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
                                                <select class="form-select jabatan-select" id="jabatan_turun" name="jabatan_turun">
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    @foreach($daftarJabatan as $jabatan)
                                                        <option value="{{ $jabatan->id }}" 
                                                                data-level="{{ $jabatan->level_jabatan ?? 0 }}"


                                                                data-kode="{{ $jabatan->kode_jabatan ?? '' }}">

                                                            {{ $jabatan->nama_jabatan }}
                                                            @if($jabatan->kode_jabatan)
                                                                ({{ $jabatan->kode_jabatan }})
                                                            @endif
                                                            @if(isset($jabatan->level_jabatan))
                                                                - Level {{ $jabatan->level_jabatan }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text">Pilih jabatan ABK yang akan turun</div>
                                            </div>
                                        </div>

                                        <!-- Data Mutasi & Informasi Tambahan ABK Turun -->
                                        <div class="col-lg-6">
                                            <h6 class="section-title">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Informasi Turun & Dokumen
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
                                                <label for="keterangan_turun" class="form-label">Keterangan Tambahan</label>
                                                <textarea class="form-control" id="keterangan_turun" name="keterangan_turun" 
                                                          rows="3" placeholder="Keterangan tambahan tentang ABK yang turun (opsional)"></textarea>
                                                <div class="form-text">Informasi tambahan terkait proses turun jabatan</div>
                                            </div>

                                            <!-- Upload Dokumen -->
                                            <div class="form-group mb-3">
                                                <label for="dokumen_sertijab" class="form-label">Dokumen Sertijab</label>
                                                <input type="file" class="form-control" id="dokumen_sertijab" name="dokumen_sertijab" 
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="form-text">Upload dokumen serah terima jabatan (PDF, DOC, atau gambar)</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="dokumen_familisasi" class="form-label">Dokumen Familisasi</label>
                                                <input type="file" class="form-control" id="dokumen_familisasi" name="dokumen_familisasi" 
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="form-text">Upload dokumen berita acara familisasi</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="dokumen_lampiran" class="form-label">Dokumen Lampiran</label>
                                                <input type="file" class="form-control" id="dokumen_lampiran" name="dokumen_lampiran" 
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="form-text">Upload dokumen lampiran lainnya</div>
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
                                <!-- Review Data Container -->
                                <div class="review-container">
                                    <!-- Kapal & Mutasi Info -->
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
                                                            <span id="reviewTMT">-</span> s/d <span id="reviewTAT">-</span>
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
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Jabatan Tetap:</span>
                                                        <span id="reviewJabatanNaik" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Jabatan Mutasi:</span>
                                                        <span id="reviewJabatanMutasi" class="review-value">-</span>
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
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="review-item">
                                                        <span class="review-label">Alasan Turun:</span>
                                                        <span id="reviewAlasanTurun" class="review-value">-</span>
                                                    </div>
                                                    <div class="review-item">
                                                        <span class="review-label">Dokumen:</span>
                                                        <span id="reviewDokumen" class="review-value">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Settings -->
                                    <div class="review-section">
                                        <h6 class="review-title">
                                            <i class="bi bi-gear me-2"></i>
                                            Pengaturan Tambahan
                                        </h6>
                                        <div class="review-content">
                                            <div class="form-group mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="perlu_sertijab" name="perlu_sertijab" value="1" checked>
                                                    <label class="form-check-label" for="perlu_sertijab">
                                                        <strong>Perlu Proses Serah Terima Jabatan</strong>
                                                        <small class="d-block text-muted">Checklist jika mutasi ini memerlukan proses sertijab</small>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="catatan" class="form-label">Catatan</label>
                                                <textarea class="form-control" id="catatan" name="catatan" 
                                                          rows="3" placeholder="Catatan tambahan untuk mutasi ini (opsional)"></textarea>
                                                <div class="form-text">Informasi tambahan terkait mutasi</div>
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
                                    <span class="submit-text">Simpan Mutasi</span>
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
                <p class="mb-4">Data mutasi berhasil ditambahkan ke sistem</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('mutasi.index') }}" class="btn btn-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        Lihat Data Mutasi
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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Custom CSS - Copy exact dari ABK create -->
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

/* Kapal Info Badge */
.kapal-info-inline {
    margin-top: 12px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.kapal-info-badge {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #93c5fd;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    animation: fadeInScale 0.3s ease-out;
}

.kapal-info-badge i {
    font-size: 10px;
}

/* Tambahkan ke bagian CSS existing */

/* Jabatan Option Styling */
.jabatan-option {
    padding: 8px 0;
}

.jabatan-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
    font-size: 14px;
}

.jabatan-meta {
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 12px;
}

.jabatan-level {
    background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
    color: #0277bd;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
}

.jabatan-kode {
    background: #f3f4f6;
    color: #374151;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-weight: 500;
}

/* Select2 Jabatan Customization */
.select2-container--bootstrap-5 .select2-results__option--highlighted .jabatan-level {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted .jabatan-kode {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .progress-steps {
        padding: 20px 16px;
        flex-direction: column;
        gap: 20px;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .step-item {
        flex-direction: row;
        max-width: none;
        text-align: left;
    }
    
    .step-card {
        padding: 24px 16px;
    }
    
    .step-footer {
        flex-direction: column;
        gap: 12px;
    }
    
    .step-footer .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    let validationTriggered = false; // Flag untuk kontrol kapan validasi dimulai
    
    // Elements
    const stepItems = document.querySelectorAll('.step-item');
    const stepContents = document.querySelectorAll('.step-content[data-step]');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const form = document.getElementById('tambahMutasiForm');
    const submitButton = document.querySelector('.btn-submit');
    
    // Form elements
    const kapalSelect = document.getElementById('id_kapal');
    const nextStep1Button = document.querySelector('[data-step="1"] .btn-next');
    const adaAbkTurunCheckbox = document.getElementById('adaAbkTurun');
    const formAbkTurun = document.getElementById('formAbkTurun');
    
    // Initialize
    updateStepDisplay();
    initializeSelect2();
    
    // Initialize Select2 untuk dropdown kapal dan jabatan (PERBAIKAN: Kembalikan ke format lama)
    function initializeSelect2() {
        // Kapal dropdown - Format seperti sebelumnya
        $('#id_kapal').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kapal --',
            allowClear: true,
            width: '100%'
        });
        
        // Jabatan dropdown
        $('.jabatan-select').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder') || '-- Pilih Jabatan --';
            },
            allowClear: true,
            width: '100%'
        });
        
        // Event handler untuk kapal selection - PERBAIKAN: Simplified
        $('#id_kapal').on('change', function() {
            const selectedValue = $(this).val();
            
            console.log('Kapal selection changed to:', selectedValue);
            
            if (selectedValue && selectedValue !== "") {
                enableNextButton(1);
                // Show kapal info jika diperlukan
                const selectedOption = $(this).find('option:selected');
                const kodeKapal = selectedOption.data('code') || '-';
                const namaKapal = selectedOption.data('nama') || selectedOption.text();
                updateKapalInfo(namaKapal, kodeKapal);
            } else {
                disableNextButton(1);
                hideKapalInfo();
            }
            
            // Validate only if user has tried to proceed
            if (validationTriggered) {
                validateCurrentStep();
            }
        });
        
        // Event handler untuk jabatan validation
        $('#jabatan_naik, #id_jabatan_mutasi').on('change', function() {
            if (validationTriggered) {
                validateJabatanMutasi();
                validateCurrentStep();
            }
        });
    }
    
    // Kapal info functions - PERBAIKAN: Simplified
    function updateKapalInfo(namaKapal, kodeKapal) {
        hideKapalInfo();
        
        const kapalInfoElement = document.getElementById('kapalInfo');
        if (kapalInfoElement) {
            const selectedKapalName = document.getElementById('selectedKapalName');
            const selectedKapalCode = document.getElementById('selectedKapalCode');
            
            if (selectedKapalName) selectedKapalName.textContent = namaKapal || '-';
            if (selectedKapalCode) selectedKapalCode.textContent = kodeKapal || '-';
            
            kapalInfoElement.style.display = 'flex';
        }
    }
    
    function hideKapalInfo() {
        const kapalInfoElement = document.getElementById('kapalInfo');
        if (kapalInfoElement) {
            kapalInfoElement.style.display = 'none';
        }
    }
    
    // Validasi jabatan mutasi
    function validateJabatanMutasi() {
        const jabatanTetap = document.getElementById('jabatan_naik');
        const jabatanMutasi = document.getElementById('id_jabatan_mutasi');
        
        // Clear previous validation errors for jabatan
        jabatanMutasi.classList.remove('is-invalid');
        removeValidationError(jabatanMutasi);
        
        if (jabatanTetap.value && jabatanMutasi.value) {
            // Error jika jabatan sama
            if (jabatanTetap.value === jabatanMutasi.value) {
                jabatanMutasi.classList.add('is-invalid');
                showValidationError(jabatanMutasi, 'Jabatan tetap dan jabatan mutasi tidak boleh sama');
                return false;
            }
            
            // Warning jika mutasi ke level yang lebih rendah (opsional)
            const levelTetap = parseInt(jabatanTetap.options[jabatanTetap.selectedIndex].dataset.level || 0);
            const levelMutasi = parseInt(jabatanMutasi.options[jabatanMutasi.selectedIndex].dataset.level || 0);
            
            if (levelMutasi < levelTetap) {
                showAlert('Perhatian: Mutasi ke jabatan dengan level lebih rendah', 'warning');
            }
        }
        
        return true;
    }
    
    // Nama mutasi handler
    const namaMutasiSelect = document.getElementById('nama_mutasi');
    if (namaMutasiSelect) {
        namaMutasiSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = this.value;
            const description = selectedOption.getAttribute('data-desc') || '';
            
            updateMutasiInfo(selectedValue, description);
            
            if (validationTriggered) {
                validateCurrentStep();
            }
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
    
    // ABK Turun checkbox handler
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
                    field.classList.remove('is-invalid');
                    removeValidationError(field);
                });
            }
            
            if (validationTriggered) {
                validateCurrentStep();
            }
        });
    }
    
    // PERBAIKAN: Input change handlers - hanya validasi jika validationTriggered = true
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[required], select[required], textarea[required]') && validationTriggered) {
            validateFieldOnChange(e.target);
            validateCurrentStep();
        }
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[required], select[required], textarea[required]') && validationTriggered) {
            validateFieldOnChange(e.target);
            validateCurrentStep();
        }
    });
    
    function validateFieldOnChange(field) {
        if (field.value.trim()) {
            field.classList.remove('is-invalid');
            removeValidationError(field);
        }
    }
    
    // TMT/TAT validation handler
    const tmtField = document.getElementById('TMT');
    const tatField = document.getElementById('TAT');
    
    if (tmtField && tatField) {
        [tmtField, tatField].forEach(field => {
            field.addEventListener('change', function() {
                if (validationTriggered) {
                    validateDateRange();
                    validateCurrentStep();
                }
            });
        });
    }
    
    function validateDateRange() {
        const tmt = document.getElementById('TMT');
        const tat = document.getElementById('TAT');
        
        // Clear previous validation errors
        tat.classList.remove('is-invalid');
        removeValidationError(tat);
        
        if (tmt.value && tat.value) {
            const tmtDate = new Date(tmt.value);
            const tatDate = new Date(tat.value);
            
            if (tmtDate >= tatDate) {
                tat.classList.add('is-invalid');
                showValidationError(tat, 'TAT harus setelah TMT');
                return false;
            }
        }
        
        return true;
    }
    
    // PERBAIKAN: Next button handlers - trigger validasi saat user klik Next
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Next button clicked, currentStep:', currentStep);
            
            // Set flag bahwa user sudah mencoba lanjut
            validationTriggered = true;
            
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                    if (currentStep === 4) {
                        updateReviewData();
                    }
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
            
            validationTriggered = true;
            
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
        
        // PERBAIKAN: Reset validationTriggered untuk step baru
        // validationTriggered = false; // Comment ini agar validasi tetap aktif setelah step pertama
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function validateCurrentStep() {
        const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if (!currentStepContent) {
            console.error('Current step content not found for step:', currentStep);
            return false;
        }
        
        let isValid = true;
        
        // Get required fields for current step
        const requiredFields = currentStepContent.querySelectorAll('input[required], select[required], textarea[required]');
        
        console.log(`Validating step ${currentStep}, found ${requiredFields.length} required fields`);
        
        // PERBAIKAN: Hanya validasi jika validationTriggered = true
        if (!validationTriggered) {
            // Jika belum triggered, hanya cek apakah semua field terisi untuk enable/disable button
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                }
            });
            
            // Update button state tanpa menampilkan error
            const nextBtn = document.querySelector(`[data-step="${currentStep}"] .btn-next`);
            if (nextBtn) {
                if (isValid) {
                    enableNextButton(currentStep);
                } else {
                    disableNextButton(currentStep);
                }
            }
            
            return isValid;
        }
        
        requiredFields.forEach(field => {
            const fieldValid = validateField(field);
            if (!fieldValid) {
                isValid = false;
            }
        });
        
        // Additional step-specific validations
        if (currentStep === 2) {
            // Validate TMT/TAT
            if (!validateDateRange()) {
                isValid = false;
            }
            
            // Validate jabatan mutasi
            if (!validateJabatanMutasi()) {
                isValid = false;
            }
        }
        
        // Enable/disable next button based on validation
        const nextBtn = document.querySelector(`[data-step="${currentStep}"] .btn-next`);
        if (nextBtn) {
            if (isValid) {
                enableNextButton(currentStep);
            } else {
                disableNextButton(currentStep);
            }
        }
        
        console.log('Validation result for step', currentStep, ':', isValid);
        return isValid;
    }
    
    function validateField(field) {
        let isValid = true;
        
        // Clear previous validation state
        field.classList.remove('is-invalid');
        removeValidationError(field);
        
        // Check if field is required and empty
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('is-invalid');
            
            let message = 'Field ini wajib diisi';
            if (field.id === 'id_kapal') {
                message = 'Silakan pilih kapal tujuan';
            } else if (field.id === 'jabatan_naik') {
                message = 'Silakan pilih jabatan tetap';
            } else if (field.id === 'id_jabatan_mutasi') {
                message = 'Silakan pilih jabatan mutasi';
            } else if (field.id === 'nama_mutasi') {
                message = 'Silakan pilih nama mutasi';
            } else if (field.id === 'jenis_mutasi') {
                message = 'Silakan pilih jenis mutasi';
            } else if (field.id === 'TMT') {
                message = 'Tanggal TMT wajib diisi';
            } else if (field.id === 'TAT') {
                message = 'Tanggal TAT wajib diisi';
            } else if (field.id === 'nrp_naik') {
                message = 'NRP ABK wajib diisi';
            } else if (field.id === 'nama_naik') {
                message = 'Nama ABK wajib diisi';
            }
            
            showValidationError(field, message);
            isValid = false;
        }
        
        return isValid;
    }
    
    function enableNextButton(step) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        if (nextBtn) {
            nextBtn.disabled = false;
            nextBtn.removeAttribute('disabled');
            nextBtn.classList.remove('disabled');
        }
    }
    
    function disableNextButton(step) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.setAttribute('disabled', 'disabled');
            nextBtn.classList.add('disabled');
        }
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
    
    function showAlert(message, type = 'info') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at top of form
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            formContainer.insertBefore(alertDiv, formContainer.firstChild);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    }
    
    function updateReviewData() {
        // Kapal info
        const kapalSelect = document.getElementById('id_kapal');
        const kapalText = kapalSelect.options[kapalSelect.selectedIndex]?.text || '-';
        setReviewValue('reviewKapal', kapalText);
        
        // Mutasi info
        const namaMutasi = document.getElementById('nama_mutasi');
        const namaMutasiText = namaMutasi.options[namaMutasi.selectedIndex]?.text || '-';
        setReviewValue('reviewNamaMutasi', namaMutasiText);
        
        const jenisMutasi = document.getElementById('jenis_mutasi').value || '-';
        setReviewValue('reviewJenisMutasi', jenisMutasi);
        
        const TMT = document.getElementById('TMT').value || '-';
        const TAT = document.getElementById('TAT').value || '-';
        setReviewValue('reviewTMT', TMT ? new Intl.DateTimeFormat('id-ID').format(new Date(TMT)) : '-');
        setReviewValue('reviewTAT', TAT ? new Intl.DateTimeFormat('id-ID').format(new Date(TAT)) : '-');
        
        // ABK Naik info
        setReviewValue('reviewNrpNaik', document.getElementById('nrp_naik').value || '-');
        setReviewValue('reviewNamaNaik', document.getElementById('nama_naik').value || '-');
        
        const jabatanNaik = document.getElementById('jabatan_naik');
        const jabatanNaikText = jabatanNaik.options[jabatanNaik.selectedIndex]?.text || '-';
        setReviewValue('reviewJabatanNaik', jabatanNaikText);
        
        const jabatanMutasi = document.getElementById('id_jabatan_mutasi');
        const jabatanMutasiText = jabatanMutasi.options[jabatanMutasi.selectedIndex]?.text || '-';
        setReviewValue('reviewJabatanMutasi', jabatanMutasiText);
        
        // ABK Turun info (if exists)
        const adaAbkTurun = document.getElementById('adaAbkTurun').checked;
        const reviewAbkTurun = document.getElementById('reviewAbkTurun');
        
        if (adaAbkTurun && reviewAbkTurun) {
            reviewAbkTurun.classList.remove('d-none');
            
            setReviewValue('reviewNrpTurun', document.getElementById('nrp_turun').value || '-');
            setReviewValue('reviewNamaTurun', document.getElementById('nama_turun').value || '-');
            
            const jabatanTurun = document.getElementById('jabatan_turun');
            const jabatanTurunText = jabatanTurun.options[jabatanTurun.selectedIndex]?.text || '-';
            setReviewValue('reviewJabatanTurun', jabatanTurunText);
            
            const alasanTurun = document.getElementById('alasan_turun').value || '-';
            setReviewValue('reviewAlasanTurun', alasanTurun);
            
            // Count uploaded documents
            const dokumenCount = getDokumenCount();
            setReviewValue('reviewDokumen', dokumenCount + ' file');
        } else if (reviewAbkTurun) {
            reviewAbkTurun.classList.add('d-none');
        }
    }
    
    function setReviewValue(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = value || '-';
        }
    }
    
    function getDokumenCount() {
        let count = 0;
        const dokumenInputs = ['dokumen_sertijab', 'dokumen_familisasi', 'dokumen_lampiran'];
        
        dokumenInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input && input.files && input.files.length > 0) {
                count++;
            }
        });
        
        return count;
    }
    
    function submitForm() {
        // Show loading state on submit button
        submitButton.classList.add('loading');
        submitButton.disabled = true;
        
        // Create FormData
        const formData = new FormData(form);
        
        // Submit via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'Terjadi kesalahan saat menyimpan data', 'danger');
        })
        .finally(() => {
            // Hide loading state
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        });
    }
    
    function showSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
    
    // PERBAIKAN: Initial validation - hanya cek untuk enable/disable button tanpa error
    setTimeout(() => {
        validateCurrentStep();
    }, 500);
});
</script>
@endpush
