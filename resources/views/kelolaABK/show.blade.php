{{-- filepath: d:\laragon\www\Pelni_Sertijab\resources\views\kelolaABK\show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail ABK - ' . $abk->nama_abk . ' - SertijabPELNI')

@section('content')
<!-- Update HTML structure dengan class CSS yang baru -->

<div class="abk-detail-container">
    <!-- Page Header -->
    <div class="abk-page-header">
        <div class="abk-header-content">
            <div class="header-left">
                <div class="abk-header-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="abk-breadcrumb breadcrumb">
                            <li class="abk-breadcrumb-item breadcrumb-item">
                                <a href="{{ route('abk.index') }}" class="text-decoration-none">
                                    <i class="bi bi-people-fill"></i> Data ABK
                                </a>
                            </li>
                            <li class="abk-breadcrumb-item breadcrumb-item active" aria-current="page">Detail ABK</li>
                        </ol>
                    </nav>
                </div>
                <h1 class="abk-page-title">
                    <i class="bi bi-person-circle"></i>
                    Detail ABK {{ $abk->nama_abk }}
                </h1>
                <p class="abk-page-subtitle">Informasi lengkap ABK, riwayat mutasi, dan dokumen sertijab</p>
            </div>
            <div class="abk-header-actions">
                <a href="{{ route('abk.edit', $abk->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                    Edit ABK
                </a>
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

    <div class="row">
        <!-- Left Column - ABK Info & Statistics -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <!-- ABK Profile Card -->
            <div class="card abk-profile-card mb-4">
                <div class="card-header text-center">
                    <div class="abk-profile-avatar">
                        <div class="abk-avatar-container">
                            <i class="bi bi-person-circle"></i>
                            <div class="abk-status-indicator bg-{{ $abk->status_abk == 'Organik' ? 'success' : ($abk->status_abk == 'Non Organik' ? 'warning' : 'secondary') }}"></div>
                        </div>
                    </div>
                    <h4 class="abk-profile-name">{{ $abk->nama_abk }}</h4>
                    <div class="abk-profile-badges">
                        <span class="badge abk-badge-nrp">
                            <i class="bi bi-person-badge"></i>
                            NRP: {{ $abk->id }}
                        </span>
                        <span class="badge abk-badge-status bg-{{ $abk->status_abk == 'Organik' ? 'success' : ($abk->status_abk == 'Non Organik' ? 'warning' : 'secondary') }}">
                            <i class="bi bi-shield-check"></i>
                            {{ $abk->status_abk ?? 'N/A' }}
                        </span>
                    </div>
                    @if($mutasiAktif)
                        <div class="abk-profile-current-assignment">
                            <div class="abk-assignment-badge">
                                <i class="bi bi-ship"></i>
                                <div class="abk-assignment-info">
                                    <div class="abk-assignment-kapal">{{ $mutasiAktif['kapal'] }}</div>
                                    <div class="abk-assignment-jabatan">{{ $mutasiAktif['jabatan'] }}</div>
                                </div>
                                <span class="abk-assignment-type badge bg-{{ $mutasiAktif['type'] == 'naik' ? 'success' : 'warning' }}">
                                    <i class="bi bi-arrow-{{ $mutasiAktif['type'] == 'naik' ? 'up' : 'down' }}"></i>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="abk-profile-info">
                        <!-- Jabatan Tetap -->
                        <div class="abk-info-item">
                            <div class="abk-info-icon">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div class="abk-info-content">
                                <div class="abk-info-label">Jabatan Tetap</div>
                                <div class="abk-info-value">{{ $abk->jabatanTetap->nama_jabatan ?? 'Tidak ada jabatan' }}</div>
                            </div>
                        </div>
                        
                        <!-- Status Penugasan -->
                        <div class="abk-info-item">
                            <div class="abk-info-icon">
                                <i class="bi bi-pin-map-fill"></i>
                            </div>
                            <div class="abk-info-content">
                                <div class="abk-info-label">Status Penugasan</div>
                                <div class="abk-info-value">
                                    @if($mutasiAktif)
                                        <span class="status-active">
                                            <i class="bi bi-dot text-success"></i>
                                            Aktif di {{ $mutasiAktif['kapal'] }}
                                        </span>
                                        <div class="abk-assignment-details">
                                            <small class="text-muted">
                                                {{ $mutasiAktif['tanggal'] ? $mutasiAktif['tanggal']->format('d M Y') : 'N/A' }}
                                                @if($mutasiAktif['jenis_mutasi'] == 'Definitif')
                                                    <span class="text-success">(Definitif)</span>
                                                @else
                                                    @if($mutasiAktif['tanggal_akhir'])
                                                        - {{ $mutasiAktif['tanggal_akhir']->format('d M Y') }}
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                    @else
                                        <span class="status-inactive">
                                            <i class="bi bi-dot text-secondary"></i>
                                            Belum ada penugasan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bergabung Sejak -->
                        <div class="abk-info-item">
                            <div class="abk-info-icon">
                                <i class="bi bi-calendar-plus-fill"></i>
                            </div>
                            <div class="abk-info-content">
                                <div class="abk-info-label">Bergabung Sejak</div>
                                <div class="abk-info-value">
                                    {{ $abk->created_at ? $abk->created_at->format('d M Y') : 'N/A' }}
                                    @if($abk->created_at)
                                        <div class="abk-time-badge">
                                            <small class="text-muted">{{ $abk->created_at->diffForHumans() }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Terakhir Update -->
                        <div class="abk-info-item">
                            <div class="abk-info-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div class="abk-info-content">
                                <div class="abk-info-label">Terakhir Diperbarui</div>
                                <div class="abk-info-value">
                                    {{ $abk->updated_at ? $abk->updated_at->format('d M Y') : 'N/A' }}
                                    @if($abk->updated_at)
                                        <div class="abk-time-badge">
                                            <small class="text-muted">{{ $abk->updated_at->diffForHumans() }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="abk-profile-actions mt-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('abk.edit', $abk->id) }}" class="btn btn-warning abk-btn-action">
                                <i class="bi bi-pencil-square"></i>
                                Edit Profile ABK
                            </a>
                            @if($riwayatMutasi->count() > 0)
                                <button class="btn btn-outline-primary abk-btn-action" onclick="scrollToTimeline()">
                                    <i class="bi bi-clock-history"></i>
                                    Lihat Riwayat Mutasi
                                </button>
                            @endif
                            @if($dokumenSertijab->count() > 0)
                                <button class="btn btn-outline-success abk-btn-action" onclick="scrollToDocuments()">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Lihat Dokumen
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card abk-stats-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up"></i>
                        Statistik Mutasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="abk-stats-grid">
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-primary">{{ $statistik['total_mutasi'] }}</div>
                            <div class="abk-stat-label">Total Mutasi</div>
                        </div>
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-success">{{ $statistik['mutasi_naik'] }}</div>
                            <div class="abk-stat-label">Mutasi Naik</div>
                        </div>
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-warning">{{ $statistik['mutasi_turun'] }}</div>
                            <div class="abk-stat-label">Mutasi Turun</div>
                        </div>
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-info">{{ $statistik['kapal_berbeda'] }}</div>
                            <div class="abk-stat-label">Kapal Berbeda</div>
                        </div>
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-danger">{{ $statistik['mutasi_aktif'] }}</div>
                            <div class="abk-stat-label">Mutasi Aktif</div>
                        </div>
                        <div class="abk-stat-box">
                            <div class="abk-stat-value text-secondary">{{ $statistik['sertijab_count'] }}</div>
                            <div class="abk-stat-label">Sertijab</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Assignment Card -->
            @if($mutasiAktif)
            <div class="card abk-current-assignment-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pin-map-fill"></i>
                        Penugasan Saat Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="assignment-info">
                        <div class="assignment-kapal">
                            <i class="bi bi-ship"></i>
                            <span class="fw-bold">{{ $mutasiAktif['kapal'] }}</span>
                        </div>
                        <div class="assignment-jabatan">
                            <i class="bi bi-briefcase"></i>
                            {{ $mutasiAktif['jabatan'] }}
                        </div>
                        <div class="assignment-period">
                            <i class="bi bi-calendar-range"></i>
                            {{ $mutasiAktif['tanggal'] ? $mutasiAktif['tanggal']->format('d/m/Y') : 'N/A' }}
                            @if($mutasiAktif['tanggal_akhir'])
                                - {{ $mutasiAktif['tanggal_akhir']->format('d/m/Y') }}
                            @else
                                <span class="text-success">(Definitif)</span>
                            @endif
                        </div>
                        <div class="assignment-status">
                            <span class="badge bg-{{ $mutasiAktif['status'] == 'Selesai' ? 'success' : ($mutasiAktif['status'] == 'Disetujui' ? 'primary' : 'secondary') }}">
                                {{ $mutasiAktif['status'] }}
                            </span>
                            <span class="badge bg-{{ $mutasiAktif['jenis_mutasi'] == 'Definitif' ? 'success' : 'info' }}">
                                {{ $mutasiAktif['jenis_mutasi'] }}
                            </span>
                        </div>
                        @if($mutasiAktif['type'] == 'naik')
                            <div class="assignment-type">
                                <span class="badge bg-success">
                                    <i class="bi bi-arrow-up"></i> Naik ke Kapal
                                </span>
                            </div>
                        @else
                            <div class="assignment-type">
                                <span class="badge bg-warning">
                                    <i class="bi bi-arrow-down"></i> Turun dari Kapal
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Mutation History & Documents -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs abk-nav-tabs-custom mb-4" id="mainTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mutasi-tab" data-bs-toggle="tab" data-bs-target="#mutasi" type="button" role="tab">
                        <i class="bi bi-arrow-repeat"></i>
                        Riwayat Mutasi
                        <span class="badge bg-primary ms-2">{{ $riwayatMutasi->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sertijab-tab" data-bs-toggle="tab" data-bs-target="#sertijab" type="button" role="tab">
                        <i class="bi bi-file-earmark-text"></i>
                        Dokumen Sertijab
                        <span class="badge bg-success ms-2">{{ $dokumenSertijab->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="mainTabContent">
                <!-- Mutation History Tab -->
                <div class="tab-pane fade show active" id="mutasi" role="tabpanel">
                    @if($riwayatMutasi->count() > 0)
                        <div class="abk-timeline">
                            @foreach($riwayatMutasi as $index => $mutasi)
                                <div class="abk-timeline-item {{ $index === 0 ? 'timeline-item-current' : '' }}">
                                    <div class="abk-timeline-marker">
                                        @if($mutasi['type'] == 'naik')
                                            <div class="abk-timeline-icon bg-success">
                                                <i class="bi bi-arrow-up"></i>
                                            </div>
                                        @else
                                            <div class="abk-timeline-icon bg-warning">
                                                <i class="bi bi-arrow-down"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="abk-timeline-content">
                                        <div class="abk-timeline-card">
                                            <div class="abk-timeline-header">
                                                <div class="abk-timeline-title">
                                                    @if($mutasi['type'] == 'naik')
                                                        <span class="fw-bold text-success">Naik ke {{ $mutasi['kapal'] }}</span>
                                                    @else
                                                        <span class="fw-bold text-warning">Turun dari {{ $mutasi['kapal'] }}</span>
                                                    @endif
                                                </div>
                                                <div class="abk-timeline-date">
                                                    {{ $mutasi['tanggal'] ? $mutasi['tanggal']->format('d M Y') : 'N/A' }}
                                                </div>
                                            </div>
                                            
                                            <div class="abk-timeline-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="detail-item">
                                                            <strong>Jabatan:</strong>
                                                            <span class="ms-2">{{ $mutasi['jabatan'] }}</span>
                                                        </div>
                                                        <div class="detail-item">
                                                            <strong>Jenis:</strong>
                                                            <span class="badge bg-{{ $mutasi['jenis_mutasi'] == 'Definitif' ? 'success' : 'info' }} ms-2">
                                                                {{ $mutasi['jenis_mutasi'] }}
                                                            </span>
                                                        </div>
                                                        <div class="detail-item">
                                                            <strong>Status:</strong>
                                                            <span class="badge bg-{{ $mutasi['status'] == 'Selesai' ? 'success' : ($mutasi['status'] == 'Disetujui' ? 'primary' : 'secondary') }} ms-2">
                                                                {{ $mutasi['status'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-item">
                                                            <strong>Periode:</strong>
                                                            <div class="mt-1">
                                                                <small class="text-muted">
                                                                    {{ $mutasi['tanggal'] ? $mutasi['tanggal']->format('d/m/Y') : 'N/A' }}
                                                                    @if($mutasi['tanggal_akhir'])
                                                                        - {{ $mutasi['tanggal_akhir']->format('d/m/Y') }}
                                                                    @else
                                                                        <span class="text-success">(Definitif)</span>
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                        @if($mutasi['abk_pasangan'])
                                                            <div class="detail-item">
                                                                <strong>{{ $mutasi['type'] == 'naik' ? 'Menggantikan:' : 'Digantikan:' }}</strong>
                                                                <div class="mt-1">
                                                                    <a href="{{ route('abk.show', $mutasi['abk_pasangan']['id']) }}" class="text-decoration-none">
                                                                        <small class="text-primary">
                                                                            {{ $mutasi['abk_pasangan']['nama'] }}
                                                                            ({{ $mutasi['abk_pasangan']['jabatan'] }})
                                                                        </small>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($mutasi['catatan'])
                                                    <div class="detail-item mt-2">
                                                        <strong>Catatan:</strong>
                                                        <div class="mt-1">
                                                            <small class="text-muted">{{ $mutasi['catatan'] }}</small>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Sertijab Status -->
                                                @if($mutasi['sertijab'])
                                                    <div class="sertijab-status mt-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fw-bold">
                                                                <i class="bi bi-file-earmark-check text-success"></i>
                                                                Dokumen Sertijab:
                                                            </span>
                                                            <div class="sertijab-badges">
                                                                <span class="badge text-success bg-{{ $mutasi['sertijab']->status_badge }}">
                                                                    {{ $mutasi['sertijab']->status_text }}
                                                                </span>
                                                                @if($mutasi['sertijab']->verification_progress)
                                                                    <span class="badge bg-info">
                                                                        {{ $mutasi['sertijab']->verification_progress }}% Verified
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="progress mt-2" style="height: 4px;">
                                                            <div class="progress-bar bg-success" 
                                                                 style="width: {{ $mutasi['sertijab']->verification_progress ?? 0 }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="abk-timeline-actions">
                                                <a href="{{ route('mutasi.show', $mutasi['id']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Detail Mutasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="abk-empty-state">
                            <i class="bi bi-arrow-repeat"></i>
                            <h6>Belum Ada Riwayat Mutasi</h6>
                            <p>ABK ini belum pernah mengalami mutasi atau penugasan</p>
                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i>
                                Buat Mutasi Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Sertijab Documents Tab -->
                <div class="tab-pane fade" id="sertijab" role="tabpanel">
                    @if($dokumenSertijab->count() > 0)
                        <div class="abk-documents-grid">
                            @foreach($dokumenSertijab as $dokumen)
                                <div class="abk-document-card">
                                    <div class="abk-document-header">
                                        <div class="abk-document-icon">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="abk-document-info">
                                            <div class="abk-document-title">
                                                Sertijab {{ $dokumen['kapal'] }}
                                            </div>
                                            <div class="abk-document-subtitle">
                                                {{ $dokumen['tanggal'] ? $dokumen['tanggal']->format('d M Y') : 'N/A' }} - 
                                                <span class="badge bg-{{ $dokumen['type'] == 'naik' ? 'success' : 'warning' }}">
                                                    {{ $dokumen['type'] == 'naik' ? 'Naik' : 'Turun' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="abk-document-status">
                                        <div class="status-header">
                                            <span class="badge bg-{{ $dokumen['sertijab']->status_badge }}">
                                                {{ $dokumen['sertijab']->status_text }}
                                            </span>
                                            <div class="progress-text">
                                                {{ $dokumen['sertijab']->verification_progress ?? 0 }}% Complete
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $dokumen['sertijab']->verification_progress ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="abk-document-details">
                                        @php
                                            $docCounts = $dokumen['sertijab']->document_counts;
                                        @endphp
                                        <div class="detail-row">
                                            <span>Dokumen Upload:</span>
                                            <span class="fw-bold">{{ $docCounts['uploaded'] }}/{{ $docCounts['total'] ?? 2 }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span>Terverifikasi:</span>
                                            <span class="fw-bold text-success">{{ $docCounts['verified'] }}</span>
                                        </div>
                                        @if($dokumen['sertijab']->verified_at)
                                            <div class="detail-row">
                                                <span>Verified:</span>
                                                <span class="fw-bold">{{ $dokumen['sertijab']->verified_at->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="abk-document-actions">
                                        <a href="{{ route('monitoring.show', $dokumen['sertijab']->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('mutasi.show', $dokumen['mutasi_id']) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-arrow-repeat"></i> Mutasi
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="abk-empty-state">
                            <i class="bi bi-file-earmark-text"></i>
                            <h6>Belum Ada Dokumen Sertijab</h6>
                            <p>ABK ini belum memiliki dokumen serah terima jabatan</p>
                            <a href="{{ route('mutasi.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i>
                                Buat Mutasi untuk Sertijab
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ABK Detail Container */
.abk-detail-container {
    padding: 24px;
    background: #f8fafc;
    min-height: calc(100vh - 70px);
}

/* ABK Page Header */
.abk-page-header {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.abk-header-breadcrumb {
    margin-bottom: 16px;
}

.abk-breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.abk-breadcrumb-item {
    font-size: 14px;
}

.abk-breadcrumb-item.active {
    color: #6b7280;
}

.abk-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.abk-page-title {
    font-size: 32px;
    font-weight: 800;
    color: #1f2937;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 16px;
}

.abk-page-title i {
    color: #2A3F8E;
    font-size: 36px;
}

.abk-page-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 16px;
    line-height: 1.5;
}

.abk-header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* ABK Profile Card */
.abk-profile-card {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(42, 63, 142, 0.15);
    position: relative;
    background: white;
}

.abk-profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2A3F8E 0%, #4f46e5 50%, #10b981 100%);
    z-index: 1;
}

.abk-profile-card .card-header {
    background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 50%, #4f46e5 100%);
    border: none;
    padding: 40px 24px 32px;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.abk-profile-card .card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: abkProfileShimmer 3s ease-in-out infinite;
}

@keyframes abkProfileShimmer {
    0%, 100% { transform: rotate(0deg) scale(1); opacity: 0.3; }
    50% { transform: rotate(180deg) scale(1.1); opacity: 0.1; }
}

/* ABK Avatar Container */
.abk-avatar-container {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.abk-profile-avatar i {
    font-size: 90px;
    opacity: 0.95;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
    transition: all 0.3s ease;
}

.abk-profile-card:hover .abk-profile-avatar i {
    transform: scale(1.05);
    opacity: 1;
}

.abk-status-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    animation: abkPulse 2s infinite;
}

@keyframes abkPulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* ABK Profile Name */
.abk-profile-name {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 16px;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    line-height: 1.2;
}

/* ABK Profile Badges */
.abk-profile-badges {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.abk-badge-nrp {
    background: rgba(255, 255, 255, 0.25) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.4);
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    align-self: center;
}

.abk-badge-status {
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    align-self: center;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* ABK Current Assignment in Header */
.abk-profile-current-assignment {
    margin-top: 16px;
}

.abk-assignment-badge {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.abk-assignment-badge:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.abk-assignment-badge > i {
    font-size: 24px;
    opacity: 0.9;
}

.abk-assignment-info {
    flex: 1;
    text-align: left;
}

.abk-assignment-kapal {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 2px;
    line-height: 1.2;
}

.abk-assignment-jabatan {
    font-size: 13px;
    opacity: 0.9;
    font-weight: 500;
}

.abk-assignment-type {
    padding: 6px 8px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ABK Enhanced Info Items */
.abk-profile-info {
    padding: 16px 0;
}

.abk-info-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 0;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.3s ease;
    border-radius: 8px;
    margin: 0 -8px;
    padding-left: 16px;
    padding-right: 16px;
}

.abk-info-item:last-child {
    border-bottom: none;
}

.abk-info-item:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: translateX(4px);
}

.abk-info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2A3F8E 0%, #4f46e5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(42, 63, 142, 0.3);
}

.abk-info-content {
    flex: 1;
    min-width: 0;
}

.abk-info-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.abk-info-value {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
    line-height: 1.4;
}

.abk-status-active, .abk-status-inactive {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
}

.abk-status-active .bi-dot {
    font-size: 24px;
    animation: abkBlink 1.5s ease-in-out infinite;
}

@keyframes abkBlink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.abk-assignment-details {
    margin-top: 4px;
}

.abk-time-badge {
    margin-top: 2px;
}

.abk-time-badge small {
    background: #f3f4f6;
    color: #6b7280;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

/* ABK Profile Actions */
.abk-profile-actions {
    border-top: 1px solid #f3f4f6;
    padding-top: 20px;
}

.abk-btn-action {
    font-weight: 600;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 14px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px solid transparent;
}

.abk-btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.btn-warning.abk-btn-action {
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    border-color: transparent;
    color: white;
}

.btn-warning.abk-btn-action:hover {
    background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
    color: white;
}

.btn-outline-primary.abk-btn-action {
    border-color: #2A3F8E;
    color: #2A3F8E;
    background: transparent;
}

.btn-outline-primary.abk-btn-action:hover {
    background: #2A3F8E;
    color: white;
    border-color: #2A3F8E;
}

.btn-outline-success.abk-btn-action {
    border-color: #10b981;
    color: #10b981;
    background: transparent;
}

.btn-outline-success.abk-btn-action:hover {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

/* ABK Statistics Card */
.abk-stats-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.abk-stats-card .card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    padding: 20px 24px;
}

.abk-stats-card .card-title {
    color: #1f2937;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.abk-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    padding: 8px 0;
}

.abk-stat-box {
    text-align: center;
    padding: 16px 12px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.abk-stat-value {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 4px;
    line-height: 1;
}

.abk-stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ABK Current Assignment Card */
.abk-current-assignment-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #10b981;
}

.abk-current-assignment-card .card-header {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: none;
    padding: 20px 24px;
}

.abk-current-assignment-card .card-title {
    color: #065f46;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.abk-assignment-info > div {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
}

.abk-assignment-info > div:last-child {
    justify-content: flex-start;
    gap: 8px;
    flex-wrap: wrap;
}

.abk-assignment-info i {
    color: #6b7280;
    width: 16px;
}

.abk-assignment-kapal {
    font-size: 16px !important;
}

/* ABK Custom Tabs */
.abk-nav-tabs-custom {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 24px;
}

.abk-nav-tabs-custom .nav-link {
    border: none;
    border-radius: 8px 8px 0 0;
    padding: 16px 24px;
    font-weight: 600;
    color: #6b7280;
    background: transparent;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.abk-nav-tabs-custom .nav-link:hover {
    background: #f3f4f6;
    color: #2A3F8E;
}

.abk-nav-tabs-custom .nav-link.active {
    background: white;
    color: #2A3F8E;
    border-bottom: 3px solid #2A3F8E;
    margin-bottom: -2px;
}

.abk-nav-tabs-custom .nav-link .badge {
    font-size: 11px;
}

/* ABK Timeline Styles */
.abk-timeline {
    position: relative;
    padding-left: 40px;
}

.abk-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #2A3F8E 0%, #e5e7eb 100%);
}

.abk-timeline-item {
    position: relative;
    margin-bottom: 32px;
}

.abk-timeline-item.timeline-item-current .abk-timeline-card {
    border-left: 4px solid #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.abk-timeline-marker {
    position: absolute;
    left: -32px;
    top: 8px;
    z-index: 2;
}

.abk-timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.abk-timeline-content {
    margin-left: 16px;
}

.abk-timeline-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.abk-timeline-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.abk-timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
}

.abk-timeline-title {
    font-size: 18px;
    font-weight: 600;
}

.abk-timeline-date {
    background: #f3f4f6;
    color: #6b7280;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.abk-timeline-body .abk-detail-item {
    margin-bottom: 8px;
    font-size: 14px;
}

.abk-sertijab-status {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 12px;
}

.abk-sertijab-badges {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.abk-timeline-actions {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ABK Documents Grid */
.abk-documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}

.abk-document-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.abk-document-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.abk-document-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}

.abk-document-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #2A3F8E 0%, #4f46e5 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.abk-document-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.abk-document-subtitle {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 8px;
}

.abk-document-status {
    margin-bottom: 16px;
}

.abk-status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.abk-progress-text {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
}

.abk-document-details {
    margin-bottom: 16px;
}

.abk-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 13px;
}

.abk-document-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ABK Enhanced Empty State */
.abk-empty-state {
    text-align: center;
    padding: 80px 40px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.abk-empty-state i {
    font-size: 80px;
    color: #cbd5e1;
    margin-bottom: 24px;
    opacity: 0.7;
}

.abk-empty-state h6 {
    font-size: 20px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 12px;
}

.abk-empty-state p {
    color: #9ca3af;
    margin-bottom: 24px;
    line-height: 1.6;
    font-size: 16px;
}

/* ABK Progress Bars */
.abk-progress {
    background: #f3f4f6;
    border-radius: 10px;
    overflow: hidden;
}

.abk-progress-bar {
    transition: width 0.6s ease;
}

/* ABK Badge Enhancements */
.abk-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
}

/* ABK Alert Enhancements */
.abk-alert {
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* ABK Button Enhancements */
.abk-btn {
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.abk-btn:hover {
    transform: translateY(-1px);
}

.abk-btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

/* ABK Responsive Design untuk Profile Card */
@media (max-width: 768px) {
    .abk-profile-card .card-header {
        padding: 32px 20px 24px;
    }
    
    .abk-profile-name {
        font-size: 24px;
    }
    
    .abk-profile-avatar i {
        font-size: 70px;
    }
    
    .abk-profile-badges {
        gap: 6px;
    }
    
    .abk-assignment-badge {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    
    .abk-assignment-info {
        text-align: center;
    }
    
    .abk-info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        text-align: left;
    }
    
    .abk-info-icon {
        align-self: flex-start;
    }
    
    .abk-btn-action {
        font-size: 13px;
        padding: 10px 16px;
    }
}

@media (max-width: 576px) {
    .abk-profile-card .card-header {
        padding: 24px 16px 20px;
    }
    
    .abk-profile-name {
        font-size: 20px;
    }
    
    .abk-profile-avatar i {
        font-size: 60px;
    }
    
    .abk-badge-nrp, .abk-badge-status {
        font-size: 12px;
        padding: 6px 12px;
    }
    
    .abk-assignment-kapal {
        font-size: 14px;
    }
    
    .abk-assignment-jabatan {
        font-size: 12px;
    }
    
    .abk-info-value {
        font-size: 14px;
    }
}

/* ABK Loading Animation untuk Profile Card */
.abk-profile-card.loading {
    position: relative;
}

.abk-profile-card.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
    animation: abkLoading 1.5s infinite;
}

@keyframes abkLoading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* ABK Responsive Design */
@media (max-width: 768px) {
    .abk-detail-container {
        padding: 16px;
    }
    
    .abk-page-header {
        padding: 24px;
    }
    
    .abk-header-content {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .abk-page-title {
        font-size: 24px;
    }
    
    .abk-profile-card .card-header {
        padding: 24px 20px;
    }
    
    .abk-profile-name {
        font-size: 20px;
    }
    
    .abk-stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .abk-timeline {
        padding-left: 32px;
    }
    
    .abk-timeline-marker {
        left: -24px;
    }
    
    .abk-timeline-icon {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
    
    .abk-timeline-card {
        padding: 20px;
    }
    
    .abk-documents-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .abk-document-card {
        padding: 20px;
    }
    
    .abk-nav-tabs-custom .nav-link {
        padding: 12px 16px;
        font-size: 14px;
    }
    
    .abk-timeline-actions,
    .abk-document-actions,
    .abk-header-actions {
        flex-direction: column;
    }
    
    .abk-timeline-actions .btn,
    .abk-document-actions .btn,
    .abk-header-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .abk-page-title i {
        font-size: 24px;
    }
    
    .abk-profile-avatar i {
        font-size: 60px;
    }
    
    .abk-empty-state {
        padding: 60px 20px;
    }
    
    .abk-empty-state i {
        font-size: 60px;
    }
    
    .abk-timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .abk-document-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .abk-document-icon {
        align-self: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs if not already active
    const triggerTabList = document.querySelectorAll('#mainTabs button[data-bs-toggle="tab"]');
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            tabTrigger.show();
        });
    });

    // Animate timeline items on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe timeline items
    document.querySelectorAll('.timeline-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.6s ease';
        observer.observe(item);
    });

    // Observe document cards
    document.querySelectorAll('.document-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    // Enhanced tooltips for badges
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});
function scrollToTimeline() {
    const timelineTab = document.getElementById('mutasi-tab');
    const timelineElement = document.getElementById('mutasi');
    
    // Activate timeline tab
    const tabTrigger = new bootstrap.Tab(timelineTab);
    tabTrigger.show();
    
    // Smooth scroll to timeline
    setTimeout(() => {
        timelineElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }, 300);
}

function scrollToDocuments() {
    const documentsTab = document.getElementById('sertijab-tab');
    const documentsElement = document.getElementById('sertijab');
    
    // Activate documents tab
    const tabTrigger = new bootstrap.Tab(documentsTab);
    tabTrigger.show();
    
    // Smooth scroll to documents
    setTimeout(() => {
        documentsElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }, 300);
}

// Add hover effects for profile card
document.addEventListener('DOMContentLoaded', function() {
    const profileCard = document.querySelector('.profile-card');
    
    if (profileCard) {
        profileCard.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 16px 48px rgba(42, 63, 142, 0.2)';
        });
        
        profileCard.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 12px 40px rgba(42, 63, 142, 0.15)';
        });
    }
});
</script>
@endsection