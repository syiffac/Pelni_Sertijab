{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Monitoring dan Verifikasi')

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
                                <i class="bi bi-house-door"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('monitoring.index') }}">Monitoring</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('monitoring.sertijab') }}">Dokumen Sertijab</a>
                        </li>
                        <li class="breadcrumb-item active">Detail Monitoring</li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Detail Monitoring Dokumen Sertijab
                </h1>
                <p class="page-subtitle">
                    Lihat detail lengkap dokumen arsip serah terima jabatan
                    @if($arsip->mutasi->kapal)
                        - {{ $arsip->mutasi->kapal->nama_kapal }}
                    @endif
                </p>
            </div>
            <div class="header-actions">
                <div class="action-buttons">
                    {{-- <a href="{{ route('arsip.edit', $arsip->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Arsip
                    </a> --}}
                    <a href="{{ route('arsip.search') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Status Overview Card -->
    <div class="status-overview-card mb-4">
        <div class="status-header">
            <div class="status-info">
                <h5 class="status-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Status Dokumen
                </h5>
                <div class="status-badges">
                    <span class="status-badge badge-{{ $arsip->status_dokumen }}">
                        {{ $arsip->status_text ?? ucfirst($arsip->status_dokumen) }}
                    </span>
                    @php
                        $progress = $arsip->verification_progress ?? 0;
                        $progressClass = $progress < 50 ? 'danger' : ($progress < 100 ? 'warning' : 'success');
                    @endphp
                    <span class="progress-badge bg-{{ $progressClass }}">
                        {{ $progress }}% selesai
                    </span>
                </div>
            </div>
            <div class="submission-info">
                <div class="info-item">
                    <i class="bi bi-calendar3 text-muted me-1"></i>
                    <span class="info-label">Disubmit:</span>
                    <span class="info-value">
                        {{ $arsip->submitted_at ? $arsip->submitted_at->format('d M Y, H:i') : 'N/A' }}
                    </span>
                </div>
                @if($arsip->verified_at)
                <div class="info-item">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    <span class="info-label">Diverifikasi:</span>
                    <span class="info-value">
                        {{ $arsip->verified_at->format('d M Y, H:i') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-section">
            <div class="progress-wrapper">
                <div class="progress-bar-wrapper">
                    <div class="progress-bar bg-{{ $progressClass }}" 
                         style="width: {{ max($progress, 2) }}%"
                         data-percentage="{{ $progress }}">
                    </div>
                </div>
                <span class="progress-text">{{ $progress }}% dari dokumen telah diverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column: Mutasi Info -->
        <div class="col-lg-8">
            <!-- Mutasi Information Card -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-ship me-2"></i>
                        Informasi Mutasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Kapal Tujuan</label>
                                <div class="info-value">
                                    <i class="bi bi-ship text-info me-2"></i>
                                    {{ $arsip->mutasi->kapal->nama_kapal ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="info-group">
                                <label class="info-label">Nama Mutasi</label>
                                <div class="info-value">{{ $arsip->mutasi->nama_mutasi ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="info-group">
                                <label class="info-label">Jenis Mutasi</label>
                                <div class="info-value">
                                    <span class="badge bg-info">{{ $arsip->mutasi->jenis_mutasi ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Periode Mutasi</label>
                                <div class="info-value">
                                    @if($arsip->mutasi->TMT && $arsip->mutasi->TAT)
                                        <i class="bi bi-calendar-range text-primary me-2"></i>
                                        {{ $arsip->mutasi->TMT->format('d M Y') }} - {{ $arsip->mutasi->TAT->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">
                                            ({{ $arsip->mutasi->TMT->diffInDays($arsip->mutasi->TAT) }} hari)
                                        </small>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-group">
                                <label class="info-label">Status Mutasi</label>
                                <div class="info-value">
                                    <span class="badge bg-{{ $arsip->mutasi->status_mutasi === 'Disetujui' ? 'success' : 'warning' }}">
                                        {{ $arsip->mutasi->status_mutasi ?? 'Proses' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABK Data Card -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-people me-2"></i>
                        Data ABK
                    </h5>
                </div>
                <div class="card-body">
                    <div class="abk-data-container">
                        <div class="abk-section">
                            <h6 class="abk-section-title text-success">
                                <i class="bi bi-arrow-up-circle me-2"></i>
                                ABK yang Naik
                            </h6>
                            <div class="abk-info-card bg-success-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="abk-detail">
                                            <label>NRP</label>
                                            <div class="abk-value">{{ $arsip->mutasi->id_abk_naik ?? 'N/A' }}</div>
                                        </div>
                                        <div class="abk-detail">
                                            <label>Nama Lengkap</label>
                                            <div class="abk-value">{{ $arsip->mutasi->nama_lengkap_naik ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="abk-detail">
                                            <label>Jabatan Tetap</label>
                                            <div class="abk-value">
                                                {{ $arsip->mutasi->jabatanTetapNaik->nama_jabatan ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="abk-detail">
                                            <label>Jabatan Mutasi</label>
                                            <div class="abk-value">
                                                {{ $arsip->mutasi->jabatanMutasi->nama_jabatan ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($arsip->mutasi->ada_abk_turun)
                        <div class="abk-section">
                            <h6 class="abk-section-title text-warning">
                                <i class="bi bi-arrow-down-circle me-2"></i>
                                ABK yang Turun
                            </h6>
                            <div class="abk-info-card bg-warning-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="abk-detail">
                                            <label>NRP</label>
                                            <div class="abk-value">{{ $arsip->mutasi->id_abk_turun ?? 'N/A' }}</div>
                                        </div>
                                        <div class="abk-detail">
                                            <label>Nama Lengkap</label>
                                            <div class="abk-value">{{ $arsip->mutasi->nama_lengkap_turun ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="abk-detail">
                                            <label>Jabatan Tetap</label>
                                            <div class="abk-value">
                                                {{ $arsip->mutasi->jabatanTetapTurun->nama_jabatan ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="abk-detail">
                                            <label>Periode Turun</label>
                                            <div class="abk-value">
                                                @if($arsip->mutasi->TMT_turun && $arsip->mutasi->TAT_turun)
                                                    {{ $arsip->mutasi->TMT_turun->format('d M Y') }} - {{ $arsip->mutasi->TAT_turun->format('d M Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="abk-section">
                            <div class="no-abk-turun">
                                <i class="bi bi-info-circle text-muted me-2"></i>
                                Tidak ada ABK yang turun untuk mutasi ini
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documents & Actions -->
        <div class="col-lg-4">
            <!-- Document Management Card -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        Dokumen Arsip
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Dokumen Sertijab -->
                    <div class="document-item">
                        <div class="document-info">
                            <div class="document-header">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <span class="document-name">Dokumen Sertijab</span>
                                <span class="document-status badge-{{ $arsip->status_sertijab === 'final' ? 'success' : 'warning' }}">
                                    {{ $arsip->status_sertijab === 'final' ? 'Final' : 'Draft' }}
                                </span>
                            </div>
                            @if($arsip->dokumen_sertijab_path)
                                <div class="document-actions">
                                    <a href="{{ asset('storage/' . $arsip->dokumen_sertijab_path) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $arsip->dokumen_sertijab_path) }}" 
                                       download class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                    @if($arsip->status_sertijab !== 'final')
                                    <button type="button" class="btn btn-sm btn-success verify-document" 
                                            data-id="{{ $arsip->id }}" data-type="sertijab">
                                        <i class="bi bi-check-circle me-1"></i> Verifikasi
                                    </button>
                                    @endif
                                </div>
                            @else
                                <div class="no-document">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    Dokumen belum diupload
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen Familisasi -->
                    <div class="document-item">
                        <div class="document-info">
                            <div class="document-header">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <span class="document-name">Dokumen Familisasi</span>
                                @if($arsip->dokumen_familisasi_path)
                                <span class="document-status badge-{{ $arsip->status_familisasi === 'final' ? 'success' : 'warning' }}">
                                    {{ $arsip->status_familisasi === 'final' ? 'Final' : 'Draft' }}
                                </span>
                                @endif
                            </div>
                            @if($arsip->dokumen_familisasi_path)
                                <div class="document-actions">
                                    <a href="{{ asset('storage/' . $arsip->dokumen_familisasi_path) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $arsip->dokumen_familisasi_path) }}" 
                                       download class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                    @if($arsip->status_familisasi !== 'final')
                                    <button type="button" class="btn btn-sm btn-success verify-document" 
                                            data-id="{{ $arsip->id }}" data-type="familisasi">
                                        <i class="bi bi-check-circle me-1"></i> Verifikasi
                                    </button>
                                    @endif
                                </div>
                            @else
                                <div class="no-document">
                                    <i class="bi bi-info-circle text-muted me-2"></i>
                                    Dokumen opsional - belum diupload
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen Lampiran -->
                    <div class="document-item">
                        <div class="document-info">
                            <div class="document-header">
                                <i class="bi bi-file-earmark-plus me-2"></i>
                                <span class="document-name">Dokumen Lampiran</span>
                                @if($arsip->dokumen_lampiran_path)
                                <span class="document-status badge-{{ $arsip->status_lampiran === 'final' ? 'success' : 'warning' }}">
                                    {{ $arsip->status_lampiran === 'final' ? 'Final' : 'Draft' }}
                                </span>
                                @endif
                            </div>
                            @if($arsip->dokumen_lampiran_path)
                                <div class="document-actions">
                                    <a href="{{ asset('storage/' . $arsip->dokumen_lampiran_path) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $arsip->dokumen_lampiran_path) }}" 
                                       download class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                    @if($arsip->status_lampiran !== 'final')
                                    <button type="button" class="btn btn-sm btn-success verify-document" 
                                            data-id="{{ $arsip->id }}" data-type="lampiran">
                                        <i class="bi bi-check-circle me-1"></i> Verifikasi
                                    </button>
                                    @endif
                                </div>
                            @else
                                <div class="no-document">
                                    <i class="bi bi-info-circle text-muted me-2"></i>
                                    Dokumen opsional - belum diupload
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    @if($arsip->status_dokumen !== 'final')
                    <div class="bulk-actions mt-3">
                        <button type="button" class="btn btn-success w-100 verify-all-documents" 
                                data-id="{{ $arsip->id }}">
                            <i class="bi bi-check-all me-2"></i>
                            Verifikasi Semua Dokumen
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes Card -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-chat-quote me-2"></i>
                        Catatan Admin
                    </h5>
                </div>
                <div class="card-body">
                    @if($arsip->catatan_admin)
                        <div class="admin-note">
                            <div class="note-content">
                                <i class="bi bi-quote"></i>
                                <p>{{ $arsip->catatan_admin }}</p>
                            </div>
                            @if($arsip->adminVerifikator)
                            <div class="note-author">
                                <small class="text-muted">
                                    - {{ $arsip->adminVerifikator->name ?? 'Admin' }}, 
                                    {{ $arsip->updated_at ? $arsip->updated_at->format('d M Y') : 'N/A' }}
                                </small>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="no-notes">
                            <i class="bi bi-chat text-muted me-2"></i>
                            <span class="text-muted">Belum ada catatan admin</span>
                        </div>
                    @endif
                    
                    <!-- Add Note Form -->
                    <div class="add-note-section mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" 
                                data-bs-toggle="collapse" data-bs-target="#addNoteForm">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah/Edit Catatan
                        </button>
                        
                        <div class="collapse mt-3" id="addNoteForm">
                            <form action="{{ route('arsip.update-status', $arsip->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="catatan_admin" rows="3" 
                                              placeholder="Masukkan catatan admin...">{{ $arsip->catatan_admin }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" name="status_dokumen">
                                        <option value="draft" {{ $arsip->status_dokumen === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="partial" {{ $arsip->status_dokumen === 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="final" {{ $arsip->status_dokumen === 'final' ? 'selected' : '' }}>Final</option>
                                    </select>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="bi bi-check me-1"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            data-bs-toggle="collapse" data-bs-target="#addNoteForm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="detail-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-lightning me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        {{-- <a href="{{ route('arsip.edit', $arsip->id) }}" class="action-btn btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                            <span>Edit Arsip</span>
                        </a> --}}
                        
                        {{-- <button type="button" class="action-btn btn-outline-info" onclick="window.print()">
                            <i class="bi bi-printer"></i>
                            <span>Cetak Detail</span>
                        </button> --}}
                        
                        <a href="{{ route('monitoring.sertijab', ['kapal_id' => $arsip->mutasi->id_kapal]) }}" 
                           class="action-btn btn-outline-primary">
                            <i class="bi bi-ship"></i>
                            <span>Monitoring Kapal Ini</span>
                        </a>
                        {{-- <a href="{{ route('mutasi.show', ['id' => $arsip->mutasi->id]) }}" 
                           class="action-btn btn-outline-primary">
                            <i class="bi bi-eye"></i>
                            <span>Detail Mutasi</span>
                        </a> --}}
                        
                        {{-- <button type="button" class="action-btn btn-outline-danger" 
                                onclick="confirmDelete({{ $arsip->id }})">
                            <i class="bi bi-trash"></i>
                            <span>Hapus Arsip</span>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-shield-check me-2"></i>
                    Verifikasi Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="verificationForm">
                    <input type="hidden" id="verifyArsipId">
                    <input type="hidden" id="verifyDocType">
                    
                    <div class="mb-3">
                        <label class="form-label">Status Verifikasi</label>
                        <select class="form-select" id="verifyStatus" required>
                            <option value="final">Final (Disetujui)</option>
                            <option value="draft">Draft (Perlu Revisi)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="verifyCatatan" rows="3" 
                                  placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="submitVerification">
                    <i class="bi bi-check-circle me-1"></i> Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus arsip dokumen ini?</p>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan. 
                    Semua file dokumen akan dihapus permanen.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Arsip
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Consistent styling with other pages */
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
    --background-card: #ffffff;
    --border-radius: 12px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 4px 12px rgba(37, 99, 235, 0.15);
    --transition: all 0.3s ease;
}

/* Page Header */
.page-header {
    background: var(--background-card);
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

.action-buttons {
    display: flex;
    gap: 12px;
}

/* Status Overview Card */
.status-overview-card {
    background: var(--background-card);
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.status-overview-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue), var(--info-color));
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.status-info h5 {
    margin: 0 0 12px 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.status-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}

.submission-info {
    text-align: right;
    font-size: 13px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 4px;
    justify-content: flex-end;
}

.info-label {
    color: var(--text-muted);
    font-weight: 500;
}

.info-value {
    color: var(--text-dark);
    font-weight: 600;
}

/* Progress Section */
.progress-section {
    margin-top: 16px;
}

.progress-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
}

.progress-bar-wrapper {
    flex: 1;
    height: 12px;
    background: #f1f5f9;
    border-radius: 6px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 6px;
    transition: width 1s ease-out;
    min-width: 4px;
}

.progress-bar.bg-success {
    background: linear-gradient(90deg, var(--success-color), #34d399);
}

.progress-bar.bg-warning {
    background: linear-gradient(90deg, var(--warning-color), #fbbf24);
}

.progress-bar.bg-danger {
    background: linear-gradient(90deg, var(--danger-color), #f87171);
}

.progress-text {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    white-space: nowrap;
}

/* Detail Cards */
.detail-card {
    background: var(--background-card);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.card-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
}

.card-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.card-body {
    padding: 24px;
}

/* Info Groups */
.info-group {
    margin-bottom: 20px;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.info-value {
    font-size: 14px;
    color: var(--text-dark);
    font-weight: 500;
    display: flex;
    align-items: center;
}

/* ABK Data Styling */
.abk-data-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.abk-section-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
}

.abk-info-card {
    padding: 20px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.bg-success-light {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.bg-warning-light {
    background: #fffbeb;
    border-color: #fed7aa;
}

.abk-detail {
    margin-bottom: 16px;
}

.abk-detail:last-child {
    margin-bottom: 0;
}

.abk-detail label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.abk-value {
    font-size: 14px;
    color: var(--text-dark);
    font-weight: 600;
}

.no-abk-turun {
    padding: 16px;
    background: var(--background-light);
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 14px;
    text-align: center;
}

/* Document Items */
.document-item {
    margin-bottom: 20px;
    padding: 16px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--background-light);
}

.document-item:last-child {
    margin-bottom: 0;
}

.document-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.document-name {
    font-weight: 600;
    color: var(--text-dark);
    flex: 1;
}

.document-status {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.document-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.no-document {
    color: var(--text-muted);
    font-size: 13px;
    font-style: italic;
}

/* Admin Notes */
.admin-note {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 16px;
}

.note-content {
    position: relative;
    padding-left: 20px;
}

.note-content i {
    position: absolute;
    left: 0;
    top: 0;
    color: var(--info-color);
    font-size: 16px;
}

.note-content p {
    margin: 0;
    font-style: italic;
    color: var(--text-dark);
    line-height: 1.5;
}

.note-author {
    margin-top: 8px;
    text-align: right;
}

.no-notes {
    color: var(--text-muted);
    font-size: 14px;
    text-align: center;
    padding: 20px;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border: 2px solid;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    background: white;
    cursor: pointer;
    width: 100%;
}

.action-btn i {
    font-size: 16px;
    width: 18px;
    text-align: center;
}

.btn-outline-warning {
    color: var(--warning-color);
    border-color: var(--warning-color);
}

.btn-outline-warning:hover {
    background: var(--warning-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-outline-info {
    color: var(--info-color);
    border-color: var(--info-color);
}

.btn-outline-info:hover {
    background: var(--info-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-outline-primary {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-outline-danger {
    color: var(--danger-color);
    border-color: var(--danger-color);
}

.btn-outline-danger:hover {
    background: var(--danger-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

/* Common Styles */
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    color: white;
    text-decoration: none;
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #059669;
    color: white;
    text-decoration: none;
}

.btn-warning {
    background: var(--warning-color);
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    color: white;
    text-decoration: none;
}

.btn-outline-secondary {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
}

.btn-outline-secondary:hover {
    background: var(--text-muted);
    color: white;
    text-decoration: none;
}

/* Badge Variants */
.badge-final {
    background: var(--success-color);
    color: white;
}

.badge-partial {
    background: var(--warning-color);
    color: white;
}

.badge-draft {
    background: #6b7280;
    color: white;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.bg-info {
    background-color: var(--info-color);
    color: white;
}

.bg-success {
    background-color: var(--success-color);
    color: white;
}

.bg-warning {
    background-color: var(--warning-color);
    color: white;
}

/* Form Controls */
.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
    font-size: 13px;
}

.form-select,
.form-control {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 13px;
    transition: var(--transition);
}

.form-select:focus,
.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    outline: none;
}

/* Alert styling */
.alert {
    border-radius: var(--border-radius);
    border: none;
    padding: 16px 20px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
}

/* Modal Styling */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--shadow-medium);
}

.modal-header {
    background: var(--background-light);
    border-bottom: 2px solid var(--border-color);
    padding: 20px 24px;
}

.modal-title {
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    background: var(--background-light);
    border-top: 1px solid var(--border-color);
    padding: 16px 24px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: stretch;
    }
    
    .status-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .submission-info {
        text-align: left;
    }
    
    .info-item {
        justify-content: flex-start;
    }
    
    .progress-wrapper {
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 768px) {
    .page-header,
    .card-body {
        padding: 16px;
    }
    
    .status-overview-card {
        padding: 16px;
    }
    
    .document-actions {
        flex-direction: column;
    }
    
    .document-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}

/* Print Styles */
@media print {
    .page-header .header-actions,
    .quick-actions,
    .document-actions,
    .add-note-section,
    .bulk-actions {
        display: none !important;
    }
    
    .detail-card {
        box-shadow: none;
        border: 1px solid #ccc;
        break-inside: avoid;
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 24px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress bar animation
    const progressBars = document.querySelectorAll('.progress-bar[data-percentage]');
    progressBars.forEach(bar => {
        const percentage = parseInt(bar.dataset.percentage);
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = Math.max(percentage, 2) + '%';
        }, 500);
    });
    
    // Verify individual document
    document.querySelectorAll('.verify-document').forEach(btn => {
        btn.addEventListener('click', function() {
            const arsipId = this.dataset.id;
            const docType = this.dataset.type;
            
            document.getElementById('verifyArsipId').value = arsipId;
            document.getElementById('verifyDocType').value = docType;
            
            const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
            modal.show();
        });
    });
    
    // Submit verification
    document.getElementById('submitVerification')?.addEventListener('click', function() {
        const arsipId = document.getElementById('verifyArsipId').value;
        const docType = document.getElementById('verifyDocType').value;
        const status = document.getElementById('verifyStatus').value;
        const catatan = document.getElementById('verifyCatatan').value;
        
        fetch(`/arsip/${arsipId}/verify-document`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                document_type: docType,
                status: status,
                catatan: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh to show updated status
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat verifikasi');
        });
    });
    
    // Verify all documents
    document.querySelector('.verify-all-documents')?.addEventListener('click', function() {
        const arsipId = this.dataset.id;
        
        if (confirm('Apakah Anda yakin ingin memverifikasi semua dokumen?')) {
            fetch(`/arsip/${arsipId}/verify-all-documents`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    catatan: 'Verifikasi semua dokumen sekaligus'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat verifikasi');
            });
        }
    });
});

// Confirm delete function
function confirmDelete(arsipId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/arsip/${arsipId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush