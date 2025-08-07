@extends('layouts.app')

@section('title', 'Detail Mutasi')

@section('content')
<div class="mutasi-detail-container">
    <!-- Page Header -->
    <div class="mutasi-page-header">
        <div class="mutasi-header-content">
            <div class="header-left">
                <nav aria-label="breadcrumb" class="mutasi-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('mutasi.index') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left-right"></i> Kelola Mutasi
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Mutasi</li>
                    </ol>
                </nav>
                <h1 class="mutasi-page-title">
                    <i class="bi bi-file-earmark-text"></i>
                    Detail Mutasi MUT-{{ str_pad($mutasi->id, 4, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="mutasi-page-subtitle">
                    Informasi lengkap mutasi ABK dan status dokumen sertijab
                </p>
            </div>
            <div class="mutasi-header-actions">
                <a href="{{ route('mutasi.edit', $mutasi->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                    Edit Mutasi
                </a>
                <a href="{{ route('mutasi.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Mutasi Info -->
        <div class="col-lg-8 mb-4">
            <!-- Mutasi Overview Card -->
            <div class="card mutasi-overview-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i>
                        Informasi Mutasi
                    </h5>
                    <div class="mutasi-status-badges">
                        <span class="status-badge status-{{ strtolower($mutasi->status_mutasi) }}">
                            {{ $mutasi->status_mutasi }}
                        </span>
                        <span class="jenis-badge jenis-{{ strtolower($mutasi->jenis_mutasi) }}">
                            {{ $mutasi->jenis_mutasi }}
                        </span>
                        @if($mutasi->perlu_sertijab)
                            <span class="sertijab-badge sertijab-required">
                                <i class="bi bi-file-earmark-check"></i>
                                Perlu Sertijab
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mutasi-info-grid">
                        <!-- Kapal Tujuan -->
                        <div class="info-section">
                            <div class="section-header">
                                <i class="bi bi-ship"></i>
                                <h6>Kapal Tujuan</h6>
                            </div>
                            <div class="section-content">
                                <div class="kapal-info">
                                    <h4>{{ $mutasi->kapal->nama_kapal ?? $mutasi->nama_kapal }}</h4>
                                    <div class="kapal-details">
                                        @if($mutasi->kapal)
                                            <span class="detail-item">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ $mutasi->kapal->home_base ?? '-' }}
                                            </span>
                                            <span class="detail-item">
                                                <i class="bi bi-people"></i>
                                                {{ $mutasi->kapal->tipe_pax ?? 0 }} PAX
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($mutasi->ada_abk_turun && $mutasi->nama_lengkap_turun)
    <!-- Jika ada ABK Turun, tampilkan dalam satu section dengan 2 kolom -->
    <div class="info-section full-width">
        <div class="section-header">
            <i class="bi bi-arrow-left-right"></i>
            <h6>Perpindahan ABK</h6>
        </div>
        <div class="section-content">
            <div class="abk-comparison-container">
                <!-- ABK Naik -->
                <div class="abk-comparison-item abk-naik-item">
                    <div class="abk-comparison-header">
                        <i class="bi bi-arrow-up-circle text-success"></i>
                        <h6>ABK Naik</h6>
                    </div>
                    <div class="abk-card abk-naik">
                        <div class="abk-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="abk-details">
                            <h5>{{ $mutasi->nama_lengkap_naik }}</h5>
                            <p class="abk-nrp">NRP: {{ $mutasi->id_abk_naik }}</p>
                            <div class="jabatan-transition">
                                <span class="jabatan-from">
                                    {{ $mutasi->jabatanTetapNaik->nama_jabatan ?? '-' }}
                                </span>
                                <i class="bi bi-arrow-right mx-2"></i>
                                <span class="jabatan-to">
                                    {{ $mutasi->jabatanMutasi->nama_jabatan ?? $mutasi->nama_mutasi }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="periode-info">
                        <div class="periode-item">
                            <label>TMT:</label>
                            <span>{{ $mutasi->TMT ? $mutasi->TMT->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="periode-item">
                            <label>TAT:</label>
                            <span>{{ $mutasi->TAT ? $mutasi->TAT->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- ABK Turun -->
                <div class="abk-comparison-item abk-turun-item">
                    <div class="abk-comparison-header">
                        <i class="bi bi-arrow-down-circle text-warning"></i>
                        <h6>ABK Turun</h6>
                    </div>
                    <div class="abk-card abk-turun">
                        <div class="abk-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="abk-details">
                            <h5>{{ $mutasi->nama_lengkap_turun }}</h5>
                            <p class="abk-nrp">NRP: {{ $mutasi->id_abk_turun }}</p>
                            <div class="jabatan-transition">
                                <span class="jabatan-from">
                                    {{ $mutasi->jabatanTetapTurun->nama_jabatan ?? '-' }}
                                </span>
                                <i class="bi bi-arrow-right mx-2"></i>
                                <span class="jabatan-to">
                                    {{ $mutasi->jabatanMutasiTurun->nama_jabatan ?? $mutasi->nama_mutasi_turun ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($mutasi->TMT_turun || $mutasi->TAT_turun)
                    <div class="periode-info">
                        <div class="periode-item">
                            <label>TMT:</label>
                            <span>{{ $mutasi->TMT_turun ? $mutasi->TMT_turun->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="periode-item">
                            <label>TAT:</label>
                            <span>{{ $mutasi->TAT_turun ? $mutasi->TAT_turun->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                    @endif
                    @if($mutasi->keterangan_turun)
                    <div class="keterangan-turun mt-3">
                        <label>Keterangan:</label>
                        <p>{{ $mutasi->keterangan_turun }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Jika tidak ada ABK Turun, tampilkan ABK Naik saja -->
    <div class="info-section">
        <div class="section-header">
            <i class="bi bi-arrow-up-circle text-success"></i>
            <h6>ABK Naik</h6>
        </div>
        <div class="section-content">
            <div class="abk-card abk-naik">
                <div class="abk-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="abk-details">
                    <h5>{{ $mutasi->nama_lengkap_naik }}</h5>
                    <p class="abk-nrp">NRP: {{ $mutasi->id_abk_naik }}</p>
                    <div class="jabatan-transition">
                        <span class="jabatan-from">
                            {{ $mutasi->jabatanTetapNaik->nama_jabatan ?? '-' }}
                        </span>
                        <i class="bi bi-arrow-right mx-2"></i>
                        <span class="jabatan-to">
                            {{ $mutasi->jabatanMutasi->nama_jabatan ?? $mutasi->nama_mutasi }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="periode-info">
                <div class="periode-item">
                    <label>TMT:</label>
                    <span>{{ $mutasi->TMT ? $mutasi->TMT->format('d M Y') : '-' }}</span>
                </div>
                <div class="periode-item">
                    <label>TAT:</label>
                    <span>{{ $mutasi->TAT ? $mutasi->TAT->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
@endif

                        <!-- Catatan Mutasi -->
                        @if($mutasi->catatan)
                        <div class="info-section full-width">
                            <div class="section-header">
                                <i class="bi bi-chat-square-text"></i>
                                <h6>Catatan Mutasi</h6>
                            </div>
                            <div class="section-content">
                                <div class="catatan-box">
                                    <p>{{ $mutasi->catatan }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sertijab Progress Card (jika diperlukan) -->
            @if($mutasi->perlu_sertijab)
            <div class="card sertijab-progress-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark-check"></i>
                        Status Dokumen Sertijab
                    </h5>
                    @if($sertijab)
                        <div class="sertijab-overall-status">
                            <span class="status-badge status-{{ $sertijab->status_dokumen }}">
                                {{ $sertijab->status_text }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($sertijab)
                        <!-- Progress Bar -->
                        <div class="progress-section mb-4">
                            <div class="progress-header">
                                <span class="progress-label">Progress Verifikasi</span>
                                <span class="progress-percentage">{{ $sertijab->verification_progress }}%</span>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar" style="width: {{ $sertijab->verification_progress }}%"></div>
                            </div>
                        </div>

                        <!-- Document List -->
                        <div class="documents-list">
                            <!-- Dokumen Sertijab -->
                            <div class="document-item {{ $sertijab->dokumen_sertijab_path ? 'has-document' : 'no-document' }}">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <div class="document-details">
                                        <h6>Dokumen Serah Terima Jabatan</h6>
                                        <p class="document-status">
                                            @if($sertijab->dokumen_sertijab_path)
                                                <i class="bi bi-check-circle text-success"></i>
                                                Dokumen telah diupload
                                            @else
                                                <i class="bi bi-x-circle text-danger"></i>
                                                Dokumen belum diupload
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if($sertijab->dokumen_sertijab_path)
                                        <a href="{{ Storage::url($sertijab->dokumen_sertijab_path) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <span class="verification-badge verification-{{ $sertijab->status_sertijab }}">
                                            @if($sertijab->status_sertijab === 'final')
                                                <i class="bi bi-check-circle"></i> Terverifikasi
                                            @else
                                                <i class="bi bi-clock"></i> Menunggu Verifikasi
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Dokumen Familisasi -->
                            <div class="document-item {{ $sertijab->dokumen_familisasi_path ? 'has-document' : 'no-document' }}">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-person"></i>
                                    </div>
                                    <div class="document-details">
                                        <h6>Dokumen Familisasi</h6>
                                        <p class="document-status">
                                            @if($sertijab->dokumen_familisasi_path)
                                                <i class="bi bi-check-circle text-success"></i>
                                                Dokumen telah diupload
                                            @else
                                                <i class="bi bi-x-circle text-warning"></i>
                                                Dokumen belum diupload (opsional)
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if($sertijab->dokumen_familisasi_path)
                                        <a href="{{ Storage::url($sertijab->dokumen_familisasi_path) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <span class="verification-badge verification-{{ $sertijab->status_familisasi }}">
                                            @if($sertijab->status_familisasi === 'final')
                                                <i class="bi bi-check-circle"></i> Terverifikasi
                                            @else
                                                <i class="bi bi-clock"></i> Menunggu Verifikasi
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Dokumen Lampiran -->
                            <div class="document-item {{ $sertijab->dokumen_lampiran_path ? 'has-document' : 'no-document' }}">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-plus"></i>
                                    </div>
                                    <div class="document-details">
                                        <h6>Dokumen Lampiran</h6>
                                        <p class="document-status">
                                            @if($sertijab->dokumen_lampiran_path)
                                                <i class="bi bi-check-circle text-success"></i>
                                                Dokumen telah diupload
                                            @else
                                                <i class="bi bi-x-circle text-warning"></i>
                                                Dokumen belum diupload (opsional)
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if($sertijab->dokumen_lampiran_path)
                                        <a href="{{ Storage::url($sertijab->dokumen_lampiran_path) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <span class="verification-badge verification-{{ $sertijab->status_lampiran }}">
                                            @if($sertijab->status_lampiran === 'final')
                                                <i class="bi bi-check-circle"></i> Terverifikasi
                                            @else
                                                <i class="bi bi-clock"></i> Menunggu Verifikasi
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        @if($sertijab->catatan_admin)
                        <div class="admin-notes mt-4">
                            <div class="notes-header">
                                <i class="bi bi-person-check"></i>
                                <h6>Catatan Admin</h6>
                            </div>
                            <div class="notes-content">
                                <p>{{ $sertijab->catatan_admin }}</p>
                                @if($sertijab->verified_at)
                                    <small class="text-muted">
                                        Diverifikasi pada {{ $sertijab->verified_at->format('d M Y H:i') }}
                                        @if($sertijab->adminVerifikator)
                                            oleh {{ $sertijab->adminVerifikator->name ?? $sertijab->verified_by_admin_nrp }}
                                        @endif
                                    </small>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Submission Info -->
                        @if($sertijab->submitted_at)
                        <div class="submission-info mt-4">
                            <div class="info-item">
                                <i class="bi bi-calendar-check text-success"></i>
                                <span>Dokumen disubmit pada {{ $sertijab->submitted_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                        @endif

                    @else
                        <!-- No Sertijab Record -->
                        <div class="no-sertijab-info">
                            <div class="empty-state">
                                <i class="bi bi-file-earmark-x display-4 text-muted"></i>
                                <h6 class="text-muted mt-3">Belum Ada Dokumen Sertijab</h6>
                                <p class="text-muted">
                                    Mutasi ini memerlukan dokumen sertijab, namun belum ada dokumen yang diupload oleh PUK.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @else
                <!-- No Sertijab Required -->
                <div class="card no-sertijab-card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-info-circle display-4 text-info mb-3"></i>
                        <h6 class="text-info">Mutasi Tidak Memerlukan Sertijab</h6>
                        <p class="text-muted mb-0">
                            Mutasi ini tidak memerlukan dokumen serah terima jabatan.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Timeline & Quick Info -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card quick-stats-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-speedometer2"></i>
                        Ringkasan Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="quick-stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Dibuat</span>
                                <span class="stat-value">{{ $mutasi->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Terakhir Update</span>
                                <span class="stat-value">{{ $mutasi->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @if($mutasi->perlu_sertijab && $sertijab)
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-file-check"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Dokumen Upload</span>
                                    <span class="stat-value">{{ $sertijab->document_counts['uploaded'] }}/3</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Terverifikasi</span>
                                    <span class="stat-value">{{ $sertijab->document_counts['verified'] }}/{{ $sertijab->document_counts['uploaded'] }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card timeline-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i>
                        Timeline Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Mutasi Created -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div class="ps-4 timeline-content">
                                <h6>Mutasi Dibuat</h6>
                                <p class="timeline-description">Mutasi {{ $mutasi->nama_lengkap_naik }} ke {{ $mutasi->kapal->nama_kapal ?? $mutasi->nama_kapal }}</p>
                                <small class="timeline-date">{{ $mutasi->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        @if($sertijab)
                            <!-- Sertijab Submitted -->
                            @if($sertijab->submitted_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info">
                                    <i class="bi bi-upload"></i>
                                </div>
                                <div class="ps-4 timeline-content">
                                    <h6>Dokumen Disubmit</h6>
                                    <p class="timeline-description">PUK mengupload dokumen sertijab</p>
                                    <small class="timeline-date">{{ $sertijab->submitted_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            @endif

                            <!-- Sertijab Verified -->
                            @if($sertijab->verified_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="ps-4 timeline-content">
                                    <h6>Dokumen Diverifikasi</h6>
                                    <p class="timeline-description">Admin memverifikasi dokumen sertijab</p>
                                    <small class="timeline-date">{{ $sertijab->verified_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            @endif
                        @endif

                        <!-- Status Changes -->
                        @if($mutasi->status_mutasi === 'Selesai')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <div class="ps-4 timeline-content">
                                <h6>Mutasi Selesai</h6>
                                <p class="timeline-description">Mutasi telah diselesaikan</p>
                                <small class="timeline-date">{{ $mutasi->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @elseif($mutasi->status_mutasi === 'Ditolak')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="ps-4 timeline-content">
                                <h6>Mutasi Ditolak</h6>
                                <p class="timeline-description">Mutasi ditolak</p>
                                <small class="timeline-date">{{ $mutasi->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
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

/* Container */
.mutasi-detail-container {
    padding: 24px;
    background: var(--background-light);
    min-height: calc(100vh - 70px);
}

/* Page Header */
.mutasi-page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: var(--shadow-light);
}

.mutasi-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 24px;
}

.mutasi-breadcrumb {
    margin-bottom: 16px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: 14px;
}

.breadcrumb-item.active {
    color: var(--text-muted);
}

.mutasi-page-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 16px;
}

.mutasi-page-title i {
    color: var(--primary-blue);
    font-size: 36px;
}

.mutasi-page-subtitle {
    color: var(--text-muted);
    margin: 0;
    font-size: 16px;
    line-height: 1.5;
}

.mutasi-header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* ABK Comparison Container */
.abk-comparison-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    position: relative;
}

.abk-comparison-container::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--success-color), var(--warning-color));
    transform: translateX(-50%);
    border-radius: 1px;
}

.abk-comparison-item {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.abk-comparison-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}

.abk-comparison-header h6 {
    margin: 0;
    font-weight: 600;
    color: var(--text-dark);
}

.abk-comparison-header i {
    font-size: 18px;
}

.abk-naik-item .abk-comparison-header i {
    color: var(--success-color);
}

.abk-turun-item .abk-comparison-header i {
    color: var(--warning-color);
}

/* ABK Card Adjustments untuk Comparison */
.abk-comparison-container .abk-card {
    margin-bottom: 0;
}

.abk-comparison-container .periode-info {
    margin-top: 0;
}

/* Hover Effects untuk Comparison */
.abk-comparison-item {
    transition: var(--transition);
    padding: 16px;
    border-radius: 12px;
    border: 2px solid transparent;
}

.abk-comparison-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.abk-naik-item:hover {
    border-color: var(--success-color);
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

.abk-turun-item:hover {
    border-color: var(--warning-color);
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
}

/* Responsive untuk ABK Comparison */
@media (max-width: 968px) {
    .abk-comparison-container {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .abk-comparison-container::before {
        display: none;
    }
    
    .abk-comparison-item {
        padding: 12px;
    }
}

@media (max-width: 768px) {
    .abk-comparison-container .abk-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .abk-comparison-container .periode-info {
        flex-direction: column;
        gap: 12px;
    }
}

/* Animation untuk Comparison Items */
.abk-comparison-item {
    animation: slideInCompare 0.6s ease-out;
}

.abk-naik-item {
    animation-delay: 0.1s;
}

.abk-turun-item {
    animation-delay: 0.2s;
}

@keyframes slideInCompare {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Visual Connection Line between ABK */
@media (min-width: 969px) {
    .abk-comparison-container {
        position: relative;
    }
    
    .abk-comparison-container::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--success-color), var(--warning-color));
        transform: translate(-50%, -50%);
        border-radius: 2px;
        z-index: 3;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .abk-comparison-container::before {
        opacity: 0.3;
    }
}

/* Badge untuk menunjukkan hubungan */
.abk-comparison-header::after {
    content: '';
    flex: 1;
}

.abk-naik-item .abk-comparison-header::before {
    content: 'MASUK';
    background: var(--success-color);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-left: auto;
}

.abk-turun-item .abk-comparison-header::before {
    content: 'KELUAR';
    background: var(--warning-color);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-left: auto;
}

/* Cards */
.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.card-header {
    background: var(--background-light);
    border-bottom: 2px solid var(--border-color);
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    color: var(--text-dark);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Mutasi Overview Card */
.mutasi-overview-card {
    border-left: 4px solid var(--primary-blue);
}

.mutasi-status-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.mutasi-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
    padding: 8px 0;
}

.info-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-section.full-width {
    grid-column: 1 / -1;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 8px;
}

.section-header h6 {
    margin: 0;
    font-weight: 600;
    color: var(--text-dark);
}

.section-header i {
    color: var(--primary-blue);
    font-size: 18px;
}

.section-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Kapal Info */
.kapal-info h4 {
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 8px;
}

.kapal-details {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
    color: var(--text-muted);
    background: var(--background-light);
    padding: 4px 8px;
    border-radius: 6px;
}

.detail-item i {
    color: var(--primary-blue);
}

/* ABK Cards */
.abk-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.abk-card.abk-naik {
    border-left: 4px solid var(--success-color);
}

.abk-card.abk-turun {
    border-left: 4px solid var(--warning-color);
}

.abk-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    flex-shrink: 0;
}

.abk-details h5 {
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 4px;
}

.abk-nrp {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 8px;
}

.jabatan-transition {
    display: flex;
    align-items: center;
    font-size: 14px;
}

.jabatan-from {
    color: var(--text-muted);
}

.jabatan-to {
    color: var(--text-dark);
    font-weight: 600;
}

/* Periode Info */
.periode-info {
    display: flex;
    gap: 24px;
    background: white;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.periode-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.periode-item label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.periode-item span {
    font-size: 14px;
    color: var(--text-dark);
    font-weight: 600;
}

/* Catatan Box */
.catatan-box {
    background: var(--background-light);
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid var(--info-color);
}

.catatan-box p {
    margin: 0;
    color: var(--text-dark);
    line-height: 1.6;
}

.keterangan-turun {
    background: #fef3c7;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid var(--warning-color);
}

.keterangan-turun label {
    font-size: 12px;
    color: var(--warning-color);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.keterangan-turun p {
    margin: 0;
    color: var(--text-dark);
    font-size: 14px;
}

/* Status Badges */
.status-badge, .jenis-badge, .sertijab-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-draft {
    background: #f3f4f6;
    color: #374151;
}

.status-disetujui {
    background: #d1fae5;
    color: #065f46;
}

.status-ditolak {
    background: #fee2e2;
    color: #991b1b;
}

.status-selesai {
    background: #dbeafe;
    color: #1e40af;
}

.jenis-sementara {
    background: #fef3c7;
    color: #92400e;
}

.jenis-definitif {
    background: #d1fae5;
    color: #065f46;
}

.sertijab-required {
    background: #e0e7ff;
    color: #3730a3;
}

/* Sertijab Progress Card */
.sertijab-progress-card {
    border-left: 4px solid var(--info-color);
}

.sertijab-overall-status .status-badge {
    font-size: 12px;
    padding: 8px 12px;
}

.status-final {
    background: #d1fae5;
    color: #065f46;
}

.status-partial {
    background: #fef3c7;
    color: #92400e;
}

/* Progress Section */
.progress-section {
    background: var(--background-light);
    padding: 20px;
    border-radius: 8px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.progress-label {
    font-weight: 600;
    color: var(--text-dark);
}

.progress-percentage {
    font-weight: 700;
    color: var(--primary-blue);
    font-size: 16px;
}

.progress-custom {
    height: 10px;
    background: #e5e7eb;
    border-radius: 5px;
    overflow: hidden;
}

.progress-custom .progress-bar {
    background: linear-gradient(90deg, var(--primary-blue), var(--success-color));
    transition: width 0.6s ease;
}

/* Documents List */
.documents-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.document-item {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}

.document-item.has-document {
    border-color: var(--success-color);
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

.document-item.no-document {
    border-color: #e5e7eb;
    background: #f9fafb;
}

.document-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.document-info {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.document-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.has-document .document-icon {
    background: linear-gradient(135deg, var(--success-color), #34d399);
    color: white;
}

.no-document .document-icon {
    background: #f3f4f6;
    color: var(--text-muted);
}

.document-details h6 {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 4px;
}

.document-status {
    margin: 0;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.document-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.verification-badge {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.verification-final {
    background: #d1fae5;
    color: #065f46;
}

.verification-draft {
    background: #fef3c7;
    color: #92400e;
}

/* Admin Notes */
.admin-notes {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #0ea5e9;
    border-radius: 8px;
    padding: 16px;
}

.notes-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.notes-header h6 {
    margin: 0;
    color: #0c4a6e;
    font-weight: 600;
}

.notes-header i {
    color: #0ea5e9;
}

.notes-content p {
    margin-bottom: 8px;
    color: var(--text-dark);
    line-height: 1.6;
}

/* Submission Info */
.submission-info {
    background: #f0fdf4;
    border: 1px solid #22c55e;
    border-radius: 8px;
    padding: 16px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-dark);
    font-weight: 500;
}

/* Empty States */
.empty-state, .no-sertijab-info {
    text-align: center;
    padding: 40px 20px;
}

.no-sertijab-card {
    border-left: 4px solid var(--info-color);
}

/* Quick Stats Card */
.quick-stats-card {
    border-left: 4px solid var(--primary-blue);
}

.quick-stats-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--background-light);
    border-radius: 8px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex: 1;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.stat-value {
    font-size: 14px;
    color: var(--text-dark);
    font-weight: 600;
}

/* Timeline */
.timeline-card {
    border-left: 4px solid var(--success-color);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary-blue) 0%, var(--border-color) 100%);
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -24px;
    top: 4px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.timeline-content h6 {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 4px;
}

.timeline-description {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 6px;
    line-height: 1.5;
}

.timeline-date {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 500;
}

/* Buttons */
.btn {
    padding: 10px 20px;
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

.btn:hover {
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    color: white;
}

.btn-warning {
    background: var(--warning-color);
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    color: white;
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

.btn-outline-primary {
    background: transparent;
    color: var(--primary-blue);
    border: 1px solid var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .mutasi-detail-container {
        padding: 16px;
    }
    
    .mutasi-page-header {
        padding: 24px;
    }
    
    .mutasi-header-content {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .mutasi-page-title {
        font-size: 24px;
    }
    
    .mutasi-page-title i {
        font-size: 28px;
    }
    
    .mutasi-info-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .abk-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .periode-info {
        flex-direction: column;
        gap: 12px;
    }
    
    .document-item {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .document-actions {
        justify-content: flex-start;
    }
    
    .quick-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .kapal-details {
        flex-direction: column;
        gap: 8px;
    }
    
    .jabatan-transition {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -16px;
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
}

@media (max-width: 576px) {
    .mutasi-page-title {
        font-size: 20px;
    }
    
    .mutasi-status-badges {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .mutasi-header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .mutasi-header-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bar on page load
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = '0%';
        
        setTimeout(() => {
            progressBar.style.width = targetWidth;
        }, 500);
    }
    
    // Add hover effects for document items
    const documentItems = document.querySelectorAll('.document-item');
    documentItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Animate timeline items on scroll
    const timelineItems = document.querySelectorAll('.timeline-item');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, observerOptions);
    
    timelineItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
    
    // Auto refresh progress if needed (polling for real-time updates)
    @if($mutasi->perlu_sertijab && $sertijab)
    function checkProgressUpdate() {
        // Only check if document is not fully verified
        @if($sertijab->verification_progress < 100)
        fetch(`{{ route('mutasi.show', $mutasi->id) }}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.progress !== undefined) {
                const currentProgress = {{ $sertijab->verification_progress }};
                if (data.progress !== currentProgress) {
                    // Reload page if progress changed
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.log('Progress check failed:', error);
        });
        @endif
    }
    
    // Check every 30 seconds
    setInterval(checkProgressUpdate, 30000);
    @endif
});

// Function to handle document preview
function previewDocument(url) {
    window.open(url, '_blank');
}

// Function to copy mutasi ID
function copyMutasiId() {
    const mutasiId = 'MUT-{{ str_pad($mutasi->id, 4, "0", STR_PAD_LEFT) }}';
    navigator.clipboard.writeText(mutasiId).then(() => {
        // Show toast notification
        showToast('ID Mutasi berhasil disalin!', 'success');
    }).catch(() => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = mutasiId;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('ID Mutasi berhasil disalin!', 'success');
    });
}

// Simple toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#2563eb'};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
</script>
@endpush