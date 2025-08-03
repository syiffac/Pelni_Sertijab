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
                                        <input class="form-check-input" type="checkbox" id="adaAbkTurun" value="1">
                                        <input type="hidden" name="ada_abk_turun" id="ada_abk_turun_value" value="0">
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
                                                        <label for="TMT_turun" class="form-label required">TMT</label>
                                                        <input type="date" class="form-control" id="TMT_turun" name="TMT_turun" 
                                                               placeholder="Tanggal Mulai Tugas">
                                                        <div class="form-text">Terhitung Mulai Tanggal</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label for="TAT_turun" class="form-label required">TAT</label>
                                                        <input type="date" class="form-control" id="TAT_turun" name="TAT_turun" 
                                                               placeholder="Tanggal Akhir Tugas">
                                                        <div class="form-text">Terhitung Akhir Tanggal</div>
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
    // Variables
    let currentStep = 1;
    const totalSteps = 4;
    let validationTriggered = false;
    
    // Initialize components
    initializeComponents();
    initializeEventHandlers();
    updateStepDisplay();

    // Main initialization
    function initializeComponents() {
        initializeSelect2();
        initializeAbkSearch();
        initializeStepButtons();
        initializeAbkTurunToggle();
    }

    // Select2 initialization
    function initializeSelect2() {
        // Kapal dropdown
        $('#id_kapal').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kapal --',
            allowClear: true,
            width: '100%'
        }).on('change', handleKapalChange);

        // Jabatan dropdown
        $('.jabatan-select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Jabatan --',
            allowClear: true,
            width: '100%'
        });
    }

    // ABK search initialization
    function initializeAbkSearch() {
        const searchConfig = {
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("mutasi.search-abk") }}',
                dataType: 'json',
                delay: 300,
                cache: true
            },
            templateResult: formatAbkResult,
            templateSelection: formatAbkSelection,
            escapeMarkup: markup => markup
        };

        // ABK Naik
        $('#abk_naik_search').select2({
            ...searchConfig,
            placeholder: 'Ketik NRP atau nama ABK naik...',
            ajax: {
                ...searchConfig.ajax,
                data: params => ({ q: params.term, type: 'naik' })
            }
        }).on('select2:select', e => handleAbkSelection('naik', e.params.data));

        // ABK Turun  
        $('#abk_turun_search').select2({
            ...searchConfig,
            placeholder: 'Ketik NRP atau nama ABK turun...',
            ajax: {
                ...searchConfig.ajax,
                data: params => ({ q: params.term, type: 'turun' })
            }
        }).on('select2:select', e => handleAbkSelection('turun', e.params.data));
    }

    // Step buttons initialization
    function initializeStepButtons() {
        // Next buttons
        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', handleNextStep);
        });

        // Previous buttons
        document.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', handlePrevStep);
        });

        // Submit button
        document.getElementById('tambahMutasiForm').addEventListener('submit', handleFormSubmit);
    }

    // ABK Turun toggle
    function initializeAbkTurunToggle() {
        const checkbox = document.getElementById('adaAbkTurun');
        const hiddenInput = document.getElementById('ada_abk_turun_value');
        const form = document.getElementById('formAbkTurun');

        if (checkbox) {
            checkbox.addEventListener('change', function() {
                hiddenInput.value = this.checked ? "1" : "0";
                toggleAbkTurunForm(this.checked, form);
            });
        }
    }

    // Event handlers
    function initializeEventHandlers() {
        // Real-time validation
        const validationFields = [
            '#id_kapal', '#id_jabatan_mutasi', '#nama_mutasi', '#jenis_mutasi',
            '#TMT', '#TAT', '#id_jabatan_mutasi_turun', '#nama_mutasi_turun',
            '#jenis_mutasi_turun', '#TMT_turun', '#TAT_turun'
        ];

        validationFields.forEach(selector => {
            $(selector).on('change', () => {
                if (validationTriggered) validateCurrentStep();
            });
        });
    }

    // Kapal change handler
    function handleKapalChange() {
        const selectedValue = $(this).val();
        const nextBtn = document.querySelector('.step-content[data-step="1"] .btn-next');

        if (selectedValue) {
            const selectedOption = $(this).find('option:selected');
            updateKapalInfo(selectedOption.data('nama'), selectedOption.data('code'));
            enableButton(nextBtn);
        } else {
            hideKapalInfo();
            disableButton(nextBtn);
        }

        if (validationTriggered) validateCurrentStep();
    }

    // ABK selection handler
    function handleAbkSelection(type, data) {
        if (!data.id) return;

        // Set hidden fields
        document.getElementById(`nrp_${type}`).value = data.id;
        document.getElementById(`nama_${type}`).value = data.nama_abk;
        document.getElementById(`jabatan_${type}`).value = data.jabatan_id;

        // Update display
        updateAbkDisplay(type, data);

        if (validationTriggered) validateCurrentStep();
    }

    // Step navigation handlers
    function handleNextStep(e) {
        e.preventDefault();
        validationTriggered = true;

        const stepNumber = parseInt(this.closest('.step-content').getAttribute('data-step'));
        
        if (validateStep(stepNumber)) {
            if (stepNumber < totalSteps) {
                currentStep = stepNumber + 1;
                updateStepDisplay();
                if (currentStep === 4) updateReviewData();
            }
        }
    }

    function handlePrevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        validationTriggered = true;
        
        if (validateCurrentStep()) submitForm();
    }

    // Validation functions
    function validateStep(stepNumber) {
        switch(stepNumber) {
            case 1: return validateKapalStep();
            case 2: return validateAbkNaikStep();
            case 3: return validateAbkTurunStep();
            default: return true;
        }
    }

    function validateKapalStep() {
        const kapalValue = document.getElementById('id_kapal').value;
        if (!kapalValue) {
            showError('id_kapal', 'Silakan pilih kapal tujuan');
            return false;
        }
        return true;
    }

    function validateAbkNaikStep() {
        const fields = [
            { id: 'nrp_naik', message: 'Silakan pilih ABK yang akan naik' },
            { id: 'id_jabatan_mutasi', message: 'Silakan pilih jabatan mutasi' },
            { id: 'nama_mutasi', message: 'Silakan pilih nama mutasi' },
            { id: 'jenis_mutasi', message: 'Silakan pilih jenis mutasi' },
            { id: 'TMT', message: 'Silakan isi tanggal TMT' },
            { id: 'TAT', message: 'Silakan isi tanggal TAT' }
        ];

        return validateFields(fields) && validateDateRange('TMT', 'TAT');
    }

    function validateAbkTurunStep() {
        const adaAbkTurun = document.getElementById('adaAbkTurun').checked;
        if (!adaAbkTurun) return true;

        const fields = [
            { id: 'nrp_turun', message: 'Silakan pilih ABK yang akan turun' },
            { id: 'id_jabatan_mutasi_turun', message: 'Silakan pilih jabatan mutasi turun' },
            { id: 'nama_mutasi_turun', message: 'Silakan pilih nama mutasi turun' },
            { id: 'jenis_mutasi_turun', message: 'Silakan pilih jenis mutasi turun' },
            { id: 'TMT_turun', message: 'Silakan isi tanggal TMT turun' },
            { id: 'TAT_turun', message: 'Silakan isi tanggal TAT turun' }
        ];

        return validateFields(fields) && validateDateRange('TMT_turun', 'TAT_turun');
    }

    function validateCurrentStep() {
        clearValidationErrors();
        const isValid = validateStep(currentStep);
        updateButtonState(currentStep, isValid);
        return isValid;
    }

    // Helper functions
    function validateFields(fields) {
        let isValid = true;
        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (!element || !element.value) {
                showError(field.id, field.message);
                isValid = false;
            }
        });
        return isValid;
    }

    function validateDateRange(startId, endId) {
        const startDate = document.getElementById(startId).value;
        const endDate = document.getElementById(endId).value;
        
        if (startDate && endDate && new Date(startDate) >= new Date(endDate)) {
            showError(endId, 'Tanggal akhir harus setelah tanggal mulai');
            return false;
        }
        return true;
    }

    function updateKapalInfo(nama, kode) {
        const infoElement = document.getElementById('kapalInfo');
        if (infoElement) {
            document.getElementById('selectedKapalName').textContent = nama || '-';
            document.getElementById('selectedKapalCode').textContent = kode || '-';
            infoElement.style.display = 'flex';
        }
    }

    function hideKapalInfo() {
        const infoElement = document.getElementById('kapalInfo');
        if (infoElement) infoElement.style.display = 'none';
    }

    function updateAbkDisplay(type, data) {
        const prefix = type === 'naik' ? 'Naik' : 'Turun';
        const infoElement = document.getElementById(`selectedAbk${prefix}Info`);
        
        if (infoElement) {
            document.getElementById(`displayNrp${prefix}`).textContent = data.nrp || data.id;
            document.getElementById(`displayNama${prefix}`).textContent = data.nama_abk || '-';
            document.getElementById(`displayJabatan${prefix}`).textContent = data.jabatan_nama || '-';
            
            const statusElement = document.getElementById(`displayStatus${prefix}`);
            updateStatusBadge(statusElement, data.status_abk);
            
            infoElement.classList.remove('d-none');
        }
    }

    function updateStatusBadge(element, status) {
        if (!element) return;
        
        element.className = 'abk-status-compact';
        const statusLower = (status || '').toLowerCase();
        
        const statusConfig = {
            'organik': { class: 'status-organik', icon: 'check-circle', text: 'Organik' },
            'non organik': { class: 'status-non-organik', icon: 'info-circle', text: 'Non Organik' },
            'pensiun': { class: 'status-pensiun', icon: 'clock', text: 'Pensiun' }
        };
        
        const config = statusConfig[statusLower] || { class: 'status-organik', icon: 'question-circle', text: status || 'N/A' };
        element.classList.add(config.class);
        element.innerHTML = `<i class="bi bi-${config.icon}"></i> ${config.text}`;
    }

    function toggleAbkTurunForm(show, form) {
        const requiredFields = [
            'abk_turun_search', 'id_jabatan_mutasi_turun', 'nama_mutasi_turun',
            'jenis_mutasi_turun', 'TMT_turun', 'TAT_turun'
        ];

        if (show) {
            form.classList.remove('d-none');
            form.classList.add('show');
            requiredFields.forEach(id => document.getElementById(id).setAttribute('required', 'required'));
        } else {
            form.classList.add('d-none');
            form.classList.remove('show');
            requiredFields.forEach(id => document.getElementById(id).removeAttribute('required'));
            clearAbkSelection('turun');
        }
    }

    function updateStepDisplay() {
        // Update indicators
        document.querySelectorAll('.step-item').forEach((item, index) => {
            const stepNumber = index + 1;
            item.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) item.classList.add('completed');
            else if (stepNumber === currentStep) item.classList.add('active');
        });

        // Update content
        document.querySelectorAll('.step-content[data-step]').forEach((content, index) => {
            content.classList.toggle('active', index + 1 === currentStep);
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateReviewData() {
        const reviewData = {
            'reviewKapal': getSelectText('id_kapal'),
            'reviewNrpNaik': getValue('nrp_naik'),
            'reviewNamaNaik': getValue('nama_naik'),
            'reviewJabatanMutasi': getSelectText('id_jabatan_mutasi'),
            'reviewNamaMutasi': getSelectText('nama_mutasi'),
            'reviewJenisMutasi': getValue('jenis_mutasi'),
            'reviewTMT': formatDate(getValue('TMT')),
            'reviewTAT': formatDate(getValue('TAT'))
        };

        Object.entries(reviewData).forEach(([id, value]) => {
            setReviewValue(id, value);
        });

        // ABK Turun
        const adaAbkTurun = document.getElementById('adaAbkTurun').checked;
        const reviewAbkTurun = document.getElementById('reviewAbkTurun');
        
        if (adaAbkTurun && reviewAbkTurun) {
            reviewAbkTurun.classList.remove('d-none');
            setReviewValue('reviewNrpTurun', getValue('nrp_turun'));
            setReviewValue('reviewNamaTurun', getValue('nama_turun'));
        } else if (reviewAbkTurun) {
            reviewAbkTurun.classList.add('d-none');
        }
    }

    function submitForm() {
        const submitBtn = document.querySelector('.btn-submit');
        submitBtn.disabled = true;
        
        const formData = new FormData(document.getElementById('tambahMutasiForm'));
        
        fetch('{{ route("mutasi.store") }}', {
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
            showAlert(error.message || 'Terjadi kesalahan saat menyimpan data', 'danger');
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    }

    // Utility functions
    function enableButton(btn) {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('disabled');
        }
    }

    function disableButton(btn) {
        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
        }
    }

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
    }

    function updateButtonState(step, isValid) {
        const nextBtn = document.querySelector(`[data-step="${step}"] .btn-next`);
        const submitBtn = document.querySelector(`[data-step="${step}"] .btn-submit`);
        
        if (nextBtn) isValid ? enableButton(nextBtn) : disableButton(nextBtn);
        if (submitBtn) isValid ? enableButton(submitBtn) : disableButton(submitBtn);
    }

    function clearAbkSelection(type) {
        $(`#abk_${type}_search`).val(null).trigger('change');
        document.getElementById(`nrp_${type}`).value = '';
        document.getElementById(`nama_${type}`).value = '';
        document.getElementById(`jabatan_${type}`).value = '';
        
        const infoElement = document.getElementById(`selectedAbk${type === 'naik' ? 'Naik' : 'Turun'}Info`);
        if (infoElement) infoElement.classList.add('d-none');
    }

    function getSelectText(id) {
        const select = document.getElementById(id);
        return select?.options[select.selectedIndex]?.text || '-';
    }

    function getValue(id) {
        return document.getElementById(id)?.value || '-';
    }

    function formatDate(dateString) {
        return dateString && dateString !== '-' ? 
            new Intl.DateTimeFormat('id-ID').format(new Date(dateString)) : '-';
    }

    function setReviewValue(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) element.textContent = value || '-';
    }

    function showSuccessModal() {
        new bootstrap.Modal(document.getElementById('successModal')).show();
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
            setTimeout(() => alertDiv.remove(), 5000);
        }
    }

    // Format functions for Select2
    function formatAbkResult(abk) {
        if (abk.loading) {
            return '<div class="d-flex align-items-center"><div class="spinner-border spinner-border-sm me-2"></div>Mencari ABK...</div>';
        }

        if (!abk.nrp) return abk.text;

        const statusClass = (abk.status_abk || '').toLowerCase() === 'organik' ? 'success' : 'info';

        return `
            <div class="abk-search-result-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${abk.nrp} - ${abk.nama_abk}</strong>
                        <br><small class="text-muted">${abk.jabatan_nama}</small>
                    </div>
                    <span class="badge bg-${statusClass}">${abk.status_abk}</span>
                </div>
            </div>
        `;
    }

    function formatAbkSelection(abk) {
        return abk.nrp ? `${abk.nrp} - ${abk.nama_abk}` : abk.text;
    }

    // Global functions for onclick handlers
    window.clearAbkSelection = clearAbkSelection;
});
</script>
@endpush