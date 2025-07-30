{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\monitoring\detail.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Sertijab')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            <i class="bi bi-file-earmark-text me-2"></i> Detail Dokumen Sertijab
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('monitoring.sertijab') }}">Dokumen Sertijab</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        
        @if($mutasi->sertijab && $mutasi->sertijab->status_verifikasi == 'pending')
        <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#verifyModal">
            <i class="bi bi-check2-circle me-1"></i> Verifikasi Dokumen
        </button>
        @endif
    </div>

    <div class="row">
        <!-- Document Info -->
        <div class="col-lg-8">
            <!-- ABK & Mutasi Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informasi ABK & Mutasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted fw-semibold">Data ABK</h6>
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $mutasi->abkTurun->nama_abk }}</h6>
                                        <small class="text-muted">NRP: {{ $mutasi->nrp_turun }}</small>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Jabatan Lama</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->jabatanLama->nama_jabatan }}</div>
                                </div>
                                
                                @if($mutasi->jabatanBaru)
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Jabatan Baru</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->jabatanBaru->nama_jabatan }}</div>
                                </div>
                                @endif
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Kapal Asal</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->kapalAsal->nama_kapal }}</div>
                                </div>
                                
                                @if($mutasi->kapalTujuan)
                                <div class="row">
                                    <div class="col-5 text-muted">Kapal Tujuan</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->kapalTujuan->nama_kapal }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted fw-semibold">Data Mutasi</h6>
                            <div class="border rounded p-3">
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">ID Mutasi</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->id_mutasi }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Kasus</div>
                                    <div class="col-7">
                                        <span class="badge {{ 
                                            $mutasi->case_mutasi == 'Promosi' ? 'bg-success' : 
                                            ($mutasi->case_mutasi == 'Demosi' ? 'bg-warning' : 
                                            ($mutasi->case_mutasi == 'Rotasi' ? 'bg-info' : 'bg-secondary')) 
                                        }}">
                                            {{ $mutasi->case_mutasi }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Jenis</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->jenis_mutasi }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Nama</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->nama_mutasi }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">TMT</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->TMT->format('d M Y') }}</div>
                                </div>
                                
                                @if($mutasi->TAT)
                                <div class="row">
                                    <div class="col-5 text-muted">TAT</div>
                                    <div class="col-7 fw-medium">{{ $mutasi->TAT->format('d M Y') }}</div>
                                </div>
                                @endif
                                
                                @if($mutasi->notes_mutasi)
                                <div class="row mt-3">
                                    <div class="col-12 text-muted mb-1">Catatan Mutasi:</div>
                                    <div class="col-12">
                                        <p class="mb-0 bg-light p-2 rounded">{{ $mutasi->notes_mutasi }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Preview -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preview Dokumen</h5>
                    @if($mutasi->sertijab)
                    <a href="{{ asset('storage/' . $mutasi->sertijab->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="bi bi-file-earmark-text me-1"></i> Buka di Tab Baru
                    </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($mutasi->sertijab)
                        <div class="ratio ratio-16x9" style="min-height: 500px;">
                            <iframe src="{{ asset('storage/' . $mutasi->sertijab->file_path) }}" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x fs-1 text-muted mb-2"></i>
                            <p class="mb-3">Dokumen Sertijab belum diunggah</p>
                            <span class="badge bg-danger px-3 py-2">Menunggu Unggahan PUK</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Document Status & History -->
        <div class="col-lg-4">
            <!-- Document Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Status Dokumen</h5>
                </div>
                <div class="card-body">
                    @if(!$mutasi->sertijab)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Dokumen Sertijab belum diunggah oleh PUK
                        </div>
                    @else
                        <!-- Status -->
                        <div class="border rounded p-3 mb-4">
                            <h6 class="text-muted fw-semibold mb-3">Status</h6>
                            
                            @if($mutasi->sertijab->status_verifikasi == 'pending')
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="bi bi-hourglass-split text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Menunggu Verifikasi</h6>
                                        <small class="text-muted">Dokumen perlu diverifikasi</small>
                                    </div>
                                </div>
                            @elseif($mutasi->sertijab->status_verifikasi == 'verified')
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Terverifikasi</h6>
                                        <small class="text-muted">Dokumen sudah diverifikasi</small>
                                    </div>
                                </div>
                            @elseif($mutasi->sertijab->status_verifikasi == 'rejected')
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Ditolak</h6>
                                        <small class="text-muted">Dokumen ditolak dan perlu diunggah ulang</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Upload Info -->
                        <div class="border rounded p-3 mb-4">
                            <h6 class="text-muted fw-semibold mb-3">Informasi Unggahan</h6>
                            
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Diunggah pada</div>
                                <div class="col-7 fw-medium">{{ $mutasi->sertijab->uploaded_at->format('d M Y, H:i') }}</div>
                            </div>
                            
                            @if($mutasi->sertijab->keterangan_pengunggah_puk)
                            <div class="row mb-3">
                                <div class="col-12 text-muted mb-1">Keterangan PUK:</div>
                                <div class="col-12">
                                    <p class="mb-0 bg-light p-2 rounded small">{{ $mutasi->sertijab->keterangan_pengunggah_puk }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ asset('storage/' . $mutasi->sertijab->file_path) }}" class="btn btn-sm btn-outline-primary w-100" download>
                                        <i class="bi bi-download me-1"></i> Download Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Verification Info -->
                        @if($mutasi->sertijab->status_verifikasi != 'pending')
                        <div class="border rounded p-3 mb-4">
                            <h6 class="text-muted fw-semibold mb-3">Informasi Verifikasi</h6>
                            
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Diverifikasi pada</div>
                                <div class="col-7 fw-medium">{{ $mutasi->sertijab->verified_at ? $mutasi->sertijab->verified_at->format('d M Y, H:i') : '-' }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Verifikator</div>
                                <div class="col-7 fw-medium">{{ $mutasi->sertijab->adminVerifikator ? $mutasi->sertijab->adminVerifikator->nama_admin : '-' }}</div>
                            </div>
                            
                            @if($mutasi->sertijab->notes)
                            <div class="row mb-3">
                                <div class="col-12 text-muted mb-1">Catatan Verifikasi:</div>
                                <div class="col-12">
                                    <p class="mb-0 bg-light p-2 rounded small">{{ $mutasi->sertijab->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        @if($mutasi->sertijab->status_verifikasi == 'pending')
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                <i class="bi bi-check2-circle me-1"></i> Verifikasi Dokumen
                            </button>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0 fw-medium">Mutasi Dibuat</h6>
                                <p class="small text-muted mb-0">{{ $mutasi->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </li>
                        
                        @if($mutasi->sertijab)
                        <li class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0 fw-medium">Dokumen Diunggah</h6>
                                <p class="small text-muted mb-0">{{ $mutasi->sertijab->uploaded_at->format('d M Y, H:i') }}</p>
                            </div>
                        </li>
                            
                            @if($mutasi->sertijab->status_verifikasi != 'pending')
                            <li class="timeline-item">
                                <div class="timeline-marker {{ $mutasi->sertijab->status_verifikasi == 'verified' ? 'bg-success' : 'bg-danger' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 fw-medium">
                                        {{ $mutasi->sertijab->status_verifikasi == 'verified' ? 'Dokumen Terverifikasi' : 'Dokumen Ditolak' }}
                                    </h6>
                                    <p class="small text-muted mb-0">{{ $mutasi->sertijab->verified_at ? $mutasi->sertijab->verified_at->format('d M Y, H:i') : '-' }}</p>
                                    @if($mutasi->sertijab->notes)
                                        <div class="bg-light p-2 rounded mt-2 small">{{ $mutasi->sertijab->notes }}</div>
                                    @endif
                                </div>
                            </li>
                            @else
                            <li class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 fw-medium">Menunggu Verifikasi</h6>
                                    <p class="small text-muted mb-0">Dokumen sedang menunggu verifikasi admin</p>
                                </div>
                            </li>
                            @endif
                        @else
                        <li class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0 fw-medium">Menunggu Unggahan</h6>
                                <p class="small text-muted mb-0">Dokumen belum diunggah oleh PUK</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    @if($mutasi->sertijab && $mutasi->sertijab->status_verifikasi == 'pending')
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Dokumen Sertijab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('monitoring.verify', $mutasi->sertijab->id_sertijab) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status Verifikasi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_verifikasi" id="statusVerified" value="verified" checked>
                                    <label class="form-check-label" for="statusVerified">
                                        Terverifikasi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_verifikasi" id="statusRejected" value="rejected">
                                    <label class="form-check-label" for="statusRejected">
                                        Ditolak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan atau alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        list-style: none;
        margin-bottom: 0;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        left: -1.5rem;
        top: 0.25rem;
    }
    
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        width: 2px;
        background-color: #e9ecef;
        left: -1.15rem;
        top: 1.5rem;
        bottom: 0;
    }
</style>
@endpush