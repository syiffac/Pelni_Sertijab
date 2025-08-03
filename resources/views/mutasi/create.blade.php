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
                                <p class="step-description">Pilih ABK yang akan naik/bertugas di kapal</p>
                            </div>
                            
                            <div class="step-body">
                                <div class="row">
                                    <!-- Personal Data ABK Naik -->
                                    <div class="col-lg-6">
                                        <h6 class="section-title">
                                            <i class="bi bi-person me-2"></i>
                                            Data Pribadi ABK
                                        </h6>
                                        
                                        <!-- Search ABK Naik -->
                                        <div class="form-group mb-3">
                                            <label for="abk_naik_search" class="form-label required">Cari ABK</label>
                                            <select class="form-select" id="abk_naik_search" name="abk_naik_search" required>
                                                <option value="">-- Cari berdasarkan NRP atau Nama --</option>
                                            </select>
                                            <div class="form-text">
                                                <i class="bi bi-info-circle"></i>
                                                Ketik NRP atau nama ABK untuk mencari
                                            </div>
                                        </div>

                                        <!-- Hidden fields for form submission -->
                                        <input type="hidden" id="nrp_naik" name="nrp_naik" required>
                                        <input type="hidden" id="nama_naik" name="nama_naik" required>
                                        <input type="hidden" id="jabatan_naik" name="jabatan_naik" required>

                                        <!-- Display selected ABK info -->
                                        <div id="selectedAbkNaikInfo" class="selected-abk-info d-none">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="card-title">
                                                        <i class="bi bi-person-check-fill"></i>
                                                        ABK Terpilih
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="abk-info-compact">
                                                        <div class="abk-info-row">
                                                            <div class="label">
                                                                <i class="bi bi-hash"></i>
                                                                NRP
                                                            </div>
                                                            <div class="value" id="displayNrpNaik">-</div>
                                                        </div>
                                                        <div class="abk-info-row">
                                                            <div class="label">
                                                                <i class="bi bi-person"></i>
                                                                Nama
                                                            </div>
                                                            <div class="value" id="displayNamaNaik">-</div>
                                                        </div>
                                                        <div class="abk-info-row">
                                                            <div class="label">
                                                                <i class="bi bi-briefcase"></i>
                                                                Jabatan
                                                            </div>
                                                            <div class="value" id="displayJabatanNaik">-</div>
                                                        </div>
                                                        <div class="abk-info-row">
                                                            <div class="label">
                                                                <i class="bi bi-shield-check"></i>
                                                                Status
                                                            </div>
                                                            <div class="value">
                                                                <span class="abk-status-compact" id="displayStatusNaik">-</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="abk-actions-compact">
                                                        <div class="abk-selected-compact">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Terpilih</span>
                                                        </div>
                                                        <button type="button" class="abk-change-compact" onclick="clearAbkSelection('naik')">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            Ganti
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Jabatan Mutasi -->
                                        <div class="form-group mb-3 mt-3">
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
                                            
                                            <!-- Search ABK Turun -->
                                            <div class="form-group mb-3">
                                                <label for="abk_turun_search" class="form-label required">Cari ABK</label>
                                                <select class="form-select" id="abk_turun_search" name="abk_turun_search">
                                                    <option value="">-- Cari berdasarkan NRP atau Nama --</option>
                                                </select>
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Ketik NRP atau nama ABK yang akan turun
                                                </div>
                                            </div>

                                            <!-- Hidden fields for ABK turun -->
                                            <input type="hidden" id="nrp_turun" name="nrp_turun">
                                            <input type="hidden" id="nama_turun" name="nama_turun">
                                            <input type="hidden" id="jabatan_turun" name="jabatan_turun">

                                            <!-- Display selected ABK turun info -->
                                            <div id="selectedAbkTurunInfo" class="selected-abk-info d-none">
                                                <div class="card">
                                                    <div class="card-header border-warning">
                                                        <h6 class="card-title">
                                                            <i class="bi bi-person-dash-fill"></i>
                                                            ABK yang Turun
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="abk-info-compact">
                                                            <div class="abk-info-row">
                                                                <div class="label">
                                                                    <i class="bi bi-hash"></i>
                                                                    NRP
                                                                </div>
                                                                <div class="value" id="displayNrpTurun">-</div>
                                                            </div>
                                                            <div class="abk-info-row">
                                                                <div class="label">
                                                                    <i class="bi bi-person"></i>
                                                                    Nama
                                                                </div>
                                                                <div class="value" id="displayNamaTurun">-</div>
                                                            </div>
                                                            <div class="abk-info-row">
                                                                <div class="label">
                                                                    <i class="bi bi-briefcase"></i>
                                                                    Jabatan
                                                                </div>
                                                                <div class="value" id="displayJabatanTurun">-</div>
                                                            </div>
                                                            <div class="abk-info-row">
                                                                <div class="label">
                                                                    <i class="bi bi-shield-check"></i>
                                                                    Status
                                                                </div>
                                                                <div class="value">
                                                                    <span class="abk-status-compact" id="displayStatusTurun">-</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="abk-actions-compact">
                                                            <div class="abk-selected-compact">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                                <span>Terpilih</span>
                                                            </div>
                                                            <button type="button" class="abk-change-compact" onclick="clearAbkSelection('turun')">
                                                                <i class="bi bi-arrow-repeat"></i>
                                                                Ganti
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Jabatan Mutasi Turun (sama seperti ABK naik) -->
                                            <div class="form-group mb-3 mt-3">
                                                <label for="id_jabatan_mutasi_turun" class="form-label required">Jabatan Mutasi Turun</label>
                                                <select class="form-select jabatan-select" id="id_jabatan_mutasi_turun" name="id_jabatan_mutasi_turun" required>
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
                                                <div class="form-text">Pilih jabatan hasil mutasi turun</div>
                                            </div>
                                        </div>

                                        <!-- Mutation Data ABK Turun (sama seperti ABK naik) -->
                                        <div class="col-lg-6">
                                            <h6 class="section-title">
                                                <i class="bi bi-arrow-repeat me-2"></i>
                                                Data Mutasi Turun
                                            </h6>

                                            <div class="form-group mb-3">
                                                <label for="nama_mutasi_turun" class="form-label required">Nama Mutasi</label>
                                                <select class="form-select" id="nama_mutasi_turun" name="nama_mutasi_turun" required>
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
                                                <div class="form-text">Pilih jenis nama mutasi sesuai dengan kondisi ABK yang turun</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="jenis_mutasi_turun" class="form-label required">Jenis Mutasi</label>
                                                <select class="form-select" id="jenis_mutasi_turun" name="jenis_mutasi_turun" required>
                                                    <option value="">-- Pilih Jenis Mutasi --</option>
                                                    <option value="Sementara">Mutasi Sementara</option>
                                                    <option value="Definitif">Mutasi Definitif</option>
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label for="TMT_turun" class="form-label required">TMT Turun</label>
                                                        <input type="date" class="form-control" id="TMT_turun" name="TMT_turun" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label for="TAT_turun" class="form-label required">TAT Turun</label>
                                                        <input type="date" class="form-control" id="TAT_turun" name="TAT_turun" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upload Dokumen Section (tetap ada) -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="section-title">
                                                <i class="bi bi-file-earmark-text me-2"></i>
                                                Dokumen Pendukung
                                            </h6>
                                            
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group mb-3">
                                                        <label for="dokumen_sertijab" class="form-label">Dokumen Sertijab</label>
                                                        <input type="file" class="form-control" id="dokumen_sertijab" name="dokumen_sertijab" 
                                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                        <div class="form-text">Upload dokumen serah terima jabatan (PDF, DOC, atau gambar)</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group mb-3">
                                                        <label for="dokumen_familisasi" class="form-label">Dokumen Familisasi</label>
                                                        <input type="file" class="form-control" id="dokumen_familisasi" name="dokumen_familisasi" 
                                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                        <div class="form-text">Upload dokumen berita acara familisasi</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
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

                                    <!-- Keterangan Tambahan -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label for="keterangan_turun" class="form-label">Keterangan Tambahan</label>
                                                <textarea class="form-control" id="keterangan_turun" name="keterangan_turun" 
                                                          rows="3" placeholder="Keterangan tambahan tentang ABK yang turun (opsional)"></textarea>
                                                <div class="form-text">Informasi tambahan terkait proses turun jabatan</div>
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

/* Selected ABK Info Styling - Compact Version */
.selected-abk-info {
    margin-top: 16px;
    animation: slideInUp 0.3s ease-out;
}

.selected-abk-info .card {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

.selected-abk-info .card-header {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border: none;
    padding: 12px 16px;
    color: white;
}

.selected-abk-info .card-header.border-warning {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
}

.selected-abk-info .card-title {
    font-size: 13px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    color: white;
}

.selected-abk-info .card-title i {
    font-size: 14px;
}

.selected-abk-info .card-body {
    padding: 16px;
    background: white;
}

/* Compact ABK Info Layout */
.abk-info-compact {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin: 0;
}

.abk-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    border-left: 3px solid #e2e8f0;
    transition: all 0.3s ease;
}

.abk-info-row:hover {
    background: #eff6ff;
    border-left-color: #3b82f6;
}

.abk-info-row .label {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.abk-info-row .label i {
    font-size: 11px;
    color: #9ca3af;
}

.abk-info-row .value {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    text-align: right;
}

/* Compact Status Badge */
.abk-status-compact {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.abk-status-compact.status-organik {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #86efac;
}

.abk-status-compact.status-non-organik {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.abk-status-compact.status-pensiun {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.abk-status-compact i {
    font-size: 9px;
}

/* Compact Actions */
.abk-actions-compact {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.abk-selected-compact {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #059669;
    font-size: 11px;
    font-weight: 600;
}

.abk-selected-compact i {
    font-size: 12px;
}

.abk-change-compact {
    background: none;
    border: 1px solid #e2e8f0;
    color: #6b7280;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 3px;
}

.abk-change-compact:hover {
    background: #f8fafc;
    border-color: #d1d5db;
    color: #374151;
}

/* Style untuk info kapal yang dipilih */
.kapal-info-inline {
    margin-top: 8px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
    animation: fadeInScale 0.3s ease-out;
}

.kapal-info-badge {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #93c5fd;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.kapal-info-badge i {
    font-size: 12px;
    color: #3b82f6;
}

.kapal-info-badge:hover {
    background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-5px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Responsive untuk compact version */
@media (max-width: 768px) {
    .abk-info-compact {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .selected-abk-info .card-body {
        padding: 12px;
    }
    
    .abk-actions-compact {
        flex-direction: column;
        gap: 6px;
        align-items: stretch;
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
    let validationTriggered = false;
    
    // Elements
    const stepItems = document.querySelectorAll('.step-item');
    const stepContents = document.querySelectorAll('.step-content[data-step]');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const form = document.getElementById('tambahMutasiForm');
    const submitButton = document.querySelector('.btn-submit');
    
    // Form elements
    const kapalSelect = document.getElementById('id_kapal');
    const adaAbkTurunCheckbox = document.getElementById('adaAbkTurun');
    const formAbkTurun = document.getElementById('formAbkTurun');
    
    // Initialize
    updateStepDisplay();
    initializeSelect2();
    initializeAbkSelect2();
    initializeValidationListeners();
    
    // Initialize Select2 untuk dropdown kapal dan jabatan
    function initializeSelect2() {
        // Kapal dropdown
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
        
        // Event handler untuk kapal selection - PERBAIKAN
        $('#id_kapal').on('change', function() {
            const selectedValue = $(this).val();
            
            console.log('Kapal changed to:', selectedValue); // Debug log
            
            if (selectedValue && selectedValue !== "") {
                const selectedOption = $(this).find('option:selected');
                const kodeKapal = selectedOption.data('code') || '-';
                const namaKapal = selectedOption.data('nama') || selectedOption.text();
                
                // Update kapal info badge
                updateKapalInfo(namaKapal, kodeKapal);
                
                // Remove validation error if exists
                removeValidationError(this);
                
                // Enable next button if validation passes
                if (validationTriggered) {
                    setTimeout(() => validateCurrentStep(), 100);
                } else {
                    enableNextButton(1);
                }
            } else {
                hideKapalInfo();
                disableNextButton(1);
                
                if (validationTriggered) {
                    setTimeout(() => validateCurrentStep(), 100);
                }
            }
        });
    }
    
    // Initialize Select2 untuk search ABK
    function initializeAbkSelect2() {
        // ABK Naik Search
        $('#abk_naik_search').select2({
            theme: 'bootstrap-5',
            placeholder: 'Ketik NRP atau nama ABK...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("mutasi.search-abk") }}',
                dataType: 'json',
                delay: 300,
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
            templateResult: formatAbkResult,
            templateSelection: formatAbkSelection,
            escapeMarkup: function (markup) { return markup; }
        });

        // ABK Turun Search  
        $('#abk_turun_search').select2({
            theme: 'bootstrap-5',
            placeholder: 'Ketik NRP atau nama ABK...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("mutasi.search-abk") }}',
                dataType: 'json',
                delay: 300,
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
            templateResult: formatAbkResult,
            templateSelection: formatAbkSelection,
            escapeMarkup: function (markup) { return markup; }
        });

        // Event handlers untuk ABK selection
        $('#abk_naik_search').on('select2:select', function (e) {
            const data = e.params.data;
            console.log('ABK Naik selected:', data);
            
            if (data.id) {
                // Set hidden fields
                document.getElementById('nrp_naik').value = data.nrp || data.id;
                document.getElementById('nama_naik').value = data.nama_abk || '';
                document.getElementById('jabatan_naik').value = data.jabatan_id || '';
                
                // Update display
                updateSelectedAbkDisplay('naik', data);
                
                if (validationTriggered) {
                    validateCurrentStep();
                }
            }
        });

        $('#abk_naik_search').on('select2:clear', function () {
            // Clear hidden fields
            document.getElementById('nrp_naik').value = '';
            document.getElementById('nama_naik').value = '';
            document.getElementById('jabatan_naik').value = '';
            
            // Hide display
            document.getElementById('selectedAbkNaikInfo').classList.add('d-none');
            
            if (validationTriggered) {
                validateCurrentStep();
            }
        });

        // ABK Turun Search  
        $('#abk_turun_search').select2({
            theme: 'bootstrap-5',
            placeholder: 'Ketik NRP atau nama ABK...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("mutasi.search-abk") }}',
                dataType: 'json',
                delay: 300,
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
            templateResult: formatAbkResult,
            templateSelection: formatAbkSelection,
            escapeMarkup: function (markup) { return markup; }
        });

        // Event handlers untuk ABK selection
        $('#abk_turun_search').on('select2:select', function (e) {
            const data = e.params.data;
            console.log('ABK Turun selected:', data);
            
            if (data.id) {
                // Set hidden fields
                document.getElementById('nrp_turun').value = data.nrp || data.id;
                document.getElementById('nama_turun').value = data.nama_abk || '';
                document.getElementById('jabatan_turun').value = data.jabatan_id || '';
                
                // Update display
                updateSelectedAbkDisplay('turun', data);
                
                if (validationTriggered) {
                    validateCurrentStep();
                }
            }
        });

        $('#abk_turun_search').on('select2:clear', function () {
            // Clear hidden fields
            document.getElementById('nrp_turun').value = '';
            document.getElementById('nama_turun').value = '';
            document.getElementById('jabatan_turun').value = '';
            
            // Hide display
            document.getElementById('selectedAbkTurunInfo').classList.add('d-none');
            
            if (validationTriggered) {
                validateCurrentStep();
            }
        });
    }

    // Format ABK result untuk dropdown
    function formatAbkResult(abk) {
        if (abk.loading) {
            return `
                <div class="abk-search-loading">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>Mencari ABK...</span>
                    </div>
                </div>
            `;
        }

        if (!abk.nrp) {
            return abk.text;
        }

        const statusClass = (abk.status_abk || '').toLowerCase() === 'organik' ? 'success' : 
                           (abk.status_abk || '').toLowerCase() === 'non organik' ? 'info' : 'secondary';

        return `
            <div class="abk-search-result-card">
                <div class="abk-search-header">
                    <div class="abk-identity">
                        <span class="abk-nrp">${abk.nrp}</span>
                        <span class="abk-separator">•</span>
                        <span class="abk-name">${abk.nama_abk}</span>
                    </div>
                    <span class="badge bg-${statusClass} abk-status-mini">${abk.status_abk}</span>
                </div>
                <div class="abk-search-details">
                    <div class="abk-detail-item">
                        <i class="bi bi-briefcase"></i>
                        <span>${abk.jabatan_nama}</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Format ABK selection untuk display
    function formatAbkSelection(abk) {
        if (!abk.nrp) {
            return abk.text;
        }
        return `${abk.nrp} - ${abk.nama_abk}`;
    }

    // Update display selected ABK
    function updateSelectedAbkDisplay(type, data) {
        const prefix = type === 'naik' ? 'Naik' : 'Turun';
        const infoElement = document.getElementById(`selectedAbk${prefix}Info`);
        
        if (infoElement) {
            // Update display elements
            document.getElementById(`displayNrp${prefix}`).textContent = data.nrp || data.id;
            document.getElementById(`displayNama${prefix}`).textContent = data.nama_abk || '-';
            document.getElementById(`displayJabatan${prefix}`).textContent = data.jabatan_nama || '-';
            
            // Update status badge dengan styling compact
            const statusElement = document.getElementById(`displayStatus${prefix}`);
            if (statusElement) {
                statusElement.className = 'abk-status-compact';
                
                // Add appropriate status class
                const statusLower = (data.status_abk || '').toLowerCase();
                if (statusLower === 'organik') {
                    statusElement.classList.add('status-organik');
                    statusElement.innerHTML = '<i class="bi bi-check-circle"></i> Organik';
                } else if (statusLower === 'non organik') {
                    statusElement.classList.add('status-non-organik');
                    statusElement.innerHTML = '<i class="bi bi-info-circle"></i> Non Organik';
                } else if (statusLower === 'pensiun') {
                    statusElement.classList.add('status-pensiun');
                    statusElement.innerHTML = '<i class="bi bi-clock"></i> Pensiun';
                } else {
                    statusElement.classList.add('status-organik');
                    statusElement.innerHTML = '<i class="bi bi-question-circle"></i> ' + (data.status_abk || 'N/A');
                }
            }
            
            // Show the info card
            infoElement.classList.remove('d-none');
        }
    }

    // Kapal info functions - PERBAIKAN
    function updateKapalInfo(namaKapal, kodeKapal) {
        // Hide existing info first
        hideKapalInfo();
        
        const kapalInfoElement = document.getElementById('kapalInfo');
        if (kapalInfoElement) {
            const selectedKapalName = document.getElementById('selectedKapalName');
            const selectedKapalCode = document.getElementById('selectedKapalCode');
            
            // Update content
            if (selectedKapalName) selectedKapalName.textContent = namaKapal || '-';
            if (selectedKapalCode) selectedKapalCode.textContent = kodeKapal || '-';
            
            // Show the info badges
            kapalInfoElement.style.display = 'flex';
        }
    }
    
    function hideKapalInfo() {
        const kapalInfoElement = document.getElementById('kapalInfo');
        if (kapalInfoElement) {
            kapalInfoElement.style.display = 'none';
        }
    }

    // ABK Turun checkbox handler
    if (adaAbkTurunCheckbox) {
        adaAbkTurunCheckbox.addEventListener('change', function() {
            if (this.checked) {
                formAbkTurun.classList.remove('d-none');
                formAbkTurun.classList.add('show');
                
                // Set required attributes untuk fields wajib (sama seperti ABK naik)
                document.getElementById('abk_turun_search').setAttribute('required', 'required');
                document.getElementById('id_jabatan_mutasi_turun').setAttribute('required', 'required');
                document.getElementById('nama_mutasi_turun').setAttribute('required', 'required');
                document.getElementById('jenis_mutasi_turun').setAttribute('required', 'required');
                document.getElementById('TMT_turun').setAttribute('required', 'required');
                document.getElementById('TAT_turun').setAttribute('required', 'required');
            } else {
                formAbkTurun.classList.add('d-none');
                formAbkTurun.classList.remove('show');
                
                // Remove required attributes
                document.getElementById('abk_turun_search').removeAttribute('required');
                document.getElementById('id_jabatan_mutasi_turun').removeAttribute('required');
                document.getElementById('nama_mutasi_turun').removeAttribute('required');
                document.getElementById('jenis_mutasi_turun').removeAttribute('required');
                document.getElementById('TMT_turun').removeAttribute('required');
                document.getElementById('TAT_turun').removeAttribute('required');
                
                // Clear all ABK turun fields
                clearAbkSelection('turun');
                
                // Clear other turun-specific fields
                const abkTurunFields = formAbkTurun.querySelectorAll('select:not(#abk_turun_search), textarea, input[type="file"], input[type="date"]');
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

    // Next button handlers
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
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

    function validateCurrentStep() {
        const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if (!currentStepContent) {
            return false;
        }
        
        let isValid = true;
        
        // Clear previous validation errors
        clearValidationErrors(currentStepContent);
        
        // Step-specific validation
        if (currentStep === 1) {
            // Validate kapal selection
            const kapalValue = document.getElementById('id_kapal').value;
            console.log('Kapal value:', kapalValue); // Debug log
            
            if (!kapalValue || kapalValue === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('id_kapal'), 'Silakan pilih kapal tujuan');
                }
            }
        } else if (currentStep === 2) {
            // Validate ABK naik
            const nrpNaik = document.getElementById('nrp_naik').value;
            const namaNaik = document.getElementById('nama_naik').value;
            const jabatanNaik = document.getElementById('jabatan_naik').value;
            const jabatanMutasi = document.getElementById('id_jabatan_mutasi').value;
            const namaMutasi = document.getElementById('nama_mutasi').value;
            const jenisMutasi = document.getElementById('jenis_mutasi').value;
            const tmt = document.getElementById('TMT').value;
            const tat = document.getElementById('TAT').value;
            
            console.log('Step 2 validation:', { // Debug log
                nrpNaik, namaNaik, jabatanNaik, jabatanMutasi, 
                namaMutasi, jenisMutasi, tmt, tat
            });
            
            // Validate ABK selection
            if (!nrpNaik || !namaNaik || !jabatanNaik) {
                isValid = false;
                if (validationTriggered) {
                    showAlert('Silakan pilih ABK yang akan naik', 'warning');
                }
            }
            
            // Validate jabatan mutasi
            if (!jabatanMutasi || jabatanMutasi === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('id_jabatan_mutasi'), 'Silakan pilih jabatan mutasi');
                }
            }
            
            // Validate nama mutasi
            if (!namaMutasi || namaMutasi === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('nama_mutasi'), 'Silakan pilih nama mutasi');
                }
            }
            
            // Validate jenis mutasi
            if (!jenisMutasi || jenisMutasi === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('jenis_mutasi'), 'Silakan pilih jenis mutasi');
                }
            }
            
            // Validate TMT
            if (!tmt || tmt === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('TMT'), 'Silakan isi tanggal TMT');
                }
            }
            
            // Validate TAT
            if (!tat || tat === "") {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('TAT'), 'Silakan isi tanggal TAT');
                }
            }
            
            // Validate date range
            if (tmt && tat && new Date(tmt) >= new Date(tat)) {
                isValid = false;
                if (validationTriggered) {
                    showValidationError(document.getElementById('TAT'), 'TAT harus setelah TMT');
                }
            }
        } else if (currentStep === 3) {
            // Step 3 validation - enhanced untuk form yang lengkap seperti ABK naik
            const adaAbkTurun = document.getElementById('adaAbkTurun').checked;
            
            if (adaAbkTurun) {
                // Validate ABK turun selection
                const nrpTurun = document.getElementById('nrp_turun').value;
                const namaTurun = document.getElementById('nama_turun').value;
                const jabatanTurun = document.getElementById('jabatan_turun').value;
                const jabatanMutasiTurun = document.getElementById('id_jabatan_mutasi_turun').value;
                const namaMutasiTurun = document.getElementById('nama_mutasi_turun').value;
                const jenisMutasiTurun = document.getElementById('jenis_mutasi_turun').value;
                const tmtTurun = document.getElementById('TMT_turun').value;
                const tatTurun = document.getElementById('TAT_turun').value;
                
                console.log('Step 3 validation:', { // Debug log
                    nrpTurun, namaTurun, jabatanTurun, jabatanMutasiTurun, 
                    namaMutasiTurun, jenisMutasiTurun, tmtTurun, tatTurun
                });
                
                // Validate ABK selection
                if (!nrpTurun || !namaTurun || !jabatanTurun) {
                    isValid = false;
                    if (validationTriggered) {
                        showAlert('Silakan pilih ABK yang akan turun', 'warning');
                    }
                }
                
                // Validate jabatan mutasi turun
                if (!jabatanMutasiTurun || jabatanMutasiTurun === "") {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('id_jabatan_mutasi_turun'), 'Silakan pilih jabatan mutasi turun');
                    }
                }
                
                // Validate nama mutasi turun
                if (!namaMutasiTurun || namaMutasiTurun === "") {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('nama_mutasi_turun'), 'Silakan pilih nama mutasi turun');
                    }
                }
                
                // Validate jenis mutasi turun
                if (!jenisMutasiTurun || jenisMutasiTurun === "") {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('jenis_mutasi_turun'), 'Silakan pilih jenis mutasi turun');
                    }
                }
                
                // Validate TMT turun
                if (!tmtTurun || tmtTurun === "") {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('TMT_turun'), 'Silakan isi tanggal TMT turun');
                    }
                }
                
                // Validate TAT turun
                if (!tatTurun || tatTurun === "") {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('TAT_turun'), 'Silakan isi tanggal TAT turun');
                    }
                }
                
                // Validate date range turun
                if (tmtTurun && tatTurun && new Date(tmtTurun) >= new Date(tatTurun)) {
                    isValid = false;
                    if (validationTriggered) {
                        showValidationError(document.getElementById('TAT_turun'), 'TAT turun harus setelah TMT turun');
                    }
                }
            }
            // If checkbox not checked, step is valid regardless
        }
        
        // Update button state
        updateButtonState(currentStep, isValid);
        
        console.log('Step', currentStep, 'validation result:', isValid); // Debug log
        return isValid;
    }

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

    function updateReviewData() {
        // Kapal info
        const kapalSelect = document.getElementById('id_kapal');
        const kapalText = kapalSelect.options[kapalSelect.selectedIndex]?.text || '-';
        setReviewValue('reviewKapal', kapalText);
        
        // ABK Naik info
        setReviewValue('reviewNrpNaik', document.getElementById('nrp_naik').value || '-');
        setReviewValue('reviewNamaNaik', document.getElementById('nama_naik').value || '-');
        
        const jabatanNaikId = document.getElementById('jabatan_naik').value;
        const jabatanNaikSelect = document.querySelector('#id_jabatan_mutasi option[value="' + jabatanNaikId + '"]');
        setReviewValue('reviewJabatanNaik', jabatanNaikSelect ? jabatanNaikSelect.text : '-');
        
        const jabatanMutasi = document.getElementById('id_jabatan_mutasi');
        const jabatanMutasiText = jabatanMutasi.options[jabatanMutasi.selectedIndex]?.text || '-';
        setReviewValue('reviewJabatanMutasi', jabatanMutasiText);
        
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
        
        // ABK Turun info (enhanced)
        const adaAbkTurun = document.getElementById('adaAbkTurun').checked;
        const reviewAbkTurun = document.getElementById('reviewAbkTurun');

        if (adaAbkTurun && reviewAbkTurun) {
            reviewAbkTurun.classList.remove('d-none');
            
            setReviewValue('reviewNrpTurun', document.getElementById('nrp_turun').value || '-');
            setReviewValue('reviewNamaTurun', document.getElementById('nama_turun').value || '-');
            
            // Get jabatan mutasi turun name
            const jabatanMutasiTurunSelect = document.getElementById('id_jabatan_mutasi_turun');
            const jabatanMutasiTurunText = jabatanMutasiTurunSelect.options[jabatanMutasiTurunSelect.selectedIndex]?.text || '-';
            setReviewValue('reviewJabatanTurun', jabatanMutasiTurunText);
            
            // Nama mutasi turun
            const namaMutasiTurunSelect = document.getElementById('nama_mutasi_turun');
            const namaMutasiTurun = namaMutasiTurunSelect.options[namaMutasiTurunSelect.selectedIndex]?.text || '-';
            setReviewValue('reviewNamaMutasiTurun', namaMutasiTurun);
            
            // Jenis mutasi turun
            const jenisMutasiTurun = document.getElementById('jenis_mutasi_turun').value || '-';
            setReviewValue('reviewJenisMutasiTurun', jenisMutasiTurun);
            
            // TMT/TAT turun
            const tmtTurun = document.getElementById('TMT_turun').value || '-';
            const tatTurun = document.getElementById('TAT_turun').value || '-';
            setReviewValue('reviewTMTTurun', tmtTurun ? new Intl.DateTimeFormat('id-ID').format(new Date(tmtTurun)) : '-');
            setReviewValue('reviewTATTurun', tatTurun ? new Intl.DateTimeFormat('id-ID').format(new Date(tatTurun)) : '-');
            
            // Count uploaded documents
            const dokumenCount = getDokumenCount();
            setReviewValue('reviewDokumen', dokumenCount + ' file');
        } else if (reviewAbkTurun) {
            reviewAbkTurun.classList.add('d-none');
        }
    }

    // Helper functions
    function enableNextButton(step) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        if (nextBtn) {
            nextBtn.disabled = false;
            nextBtn.classList.remove('disabled');
        }
    }
    
    function disableNextButton(step) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.classList.add('disabled');
        }
    }

    function submitForm() {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
        
        const formData = new FormData(form);
        
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
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        });
    }
    
    function showSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    function showValidationError(field, message) {
        removeValidationError(field);
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        field.parentNode.appendChild(errorDiv);
    }

    function removeValidationError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            formContainer.insertBefore(alertDiv, formContainer.firstChild);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
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

    // Tambahkan event listeners untuk real-time validation
    function initializeValidationListeners() {
        // Step 1 - Kapal selection
        $('#id_kapal').on('change', function() {
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        // Step 2 - ABK dan form fields
        $('#abk_naik_search').on('select2:select select2:clear', function() {
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#id_jabatan_mutasi').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#nama_mutasi').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#jenis_mutasi').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#TMT').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#TAT').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        // Step 3 - ABK turun
        $('#adaAbkTurun').on('change', function() {
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
        
        $('#abk_turun_search').on('select2:select select2:clear', function() {
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });

        $('#id_jabatan_mutasi_turun').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });

        $('#nama_mutasi_turun').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });

        $('#jenis_mutasi_turun').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });

        $('#TMT_turun').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });

        $('#TAT_turun').on('change', function() {
            removeValidationError(this);
            if (validationTriggered) {
                setTimeout(() => validateCurrentStep(), 100);
            }
        });
    }

    // Initial validation
    setTimeout(() => {
        validateCurrentStep();
    }, 500);

    // Tambahkan helper functions ini setelah validateCurrentStep()

    function clearValidationErrors(container) {
        // Remove all validation error messages and classes
        const invalidFields = container.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        const errorMessages = container.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(error => {
            error.remove();
        });
    }

    function updateButtonState(step, isValid) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        const submitBtn = document.querySelector(`[data-step="${step}"] .btn-submit`);
        
        if (nextBtn) {
            if (isValid) {
                nextBtn.disabled = false;
                nextBtn.classList.remove('disabled');
            } else {
                nextBtn.disabled = true;
                nextBtn.classList.add('disabled');
            }
        }
        
        if (submitBtn) {
            if (isValid) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled');
            }
        }
    }

    // Update fungsi clearAbkSelection untuk handle form turun yang lengkap
    function clearAbkSelection(type) {
        const searchElement = document.getElementById(`abk_${type}_search`);
        const infoElement = document.getElementById(`selectedAbk${type === 'naik' ? 'Naik' : 'Turun'}Info`);
        
        // Clear Select2
        if (searchElement) {
            $(searchElement).val(null).trigger('change');
        }
        
        // Clear hidden fields
        document.getElementById(`nrp_${type}`).value = '';
        document.getElementById(`nama_${type}`).value = '';
        document.getElementById(`jabatan_${type}`).value = '';
        
        // Clear specific fields untuk ABK turun
        if (type === 'turun') {
            $('#id_jabatan_mutasi_turun').val('').trigger('change');
            document.getElementById('nama_mutasi_turun').value = '';
            document.getElementById('jenis_mutasi_turun').value = '';
            document.getElementById('TMT_turun').value = '';
            document.getElementById('TAT_turun').value = '';
            document.getElementById('keterangan_turun').value = '';
            
            // Clear validation errors
            removeValidationError(document.getElementById('id_jabatan_mutasi_turun'));
            removeValidationError(document.getElementById('nama_mutasi_turun'));
            removeValidationError(document.getElementById('jenis_mutasi_turun'));
            removeValidationError(document.getElementById('TMT_turun'));
            removeValidationError(document.getElementById('TAT_turun'));
        }
        
        // Hide display
        if (infoElement) {
            infoElement.classList.add('d-none');
        }
        
        // Re-validate if needed
        if (validationTriggered) {
            validateCurrentStep();
        }
    }
});
</script>
@endpush
