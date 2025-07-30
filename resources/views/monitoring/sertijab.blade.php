{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\monitoring\sertijab.blade.php --}}
@extends('layouts.app')

@section('title', 'Monitoring Sertijab')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            <i class="bi bi-list-check me-2"></i> Monitoring Dokumen Sertijab
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dokumen Sertijab</li>
            </ol>
        </nav>
    </div>

    <!-- Filter dan Export Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('monitoring.sertijab') }}" method="GET" class="d-flex gap-2">
                        <select name="kapal_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kapal</option>
                            @foreach($kapalList as $kapal)
                                <option value="{{ $kapal->id_kapal }}" {{ request('kapal_id') == $kapal->id_kapal ? 'selected' : '' }}>
                                    {{ $kapal->nama_kapal }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </form>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="btn-group">
                        <a href="{{ route('monitoring.sertijab.export') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Sertijab -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kapal</th>
                            <th scope="col">ABK</th>
                            <th scope="col">Kasus Mutasi</th>
                            <th scope="col">Dokumen</th>
                            <th scope="col">Status</th>
                            <th scope="col">Terakhir Update</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasiList as $index => $mutasi)
                        <tr>
                            <td>{{ $mutasiList->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $mutasi->kapalAsal->nama_kapal }}</span>
                                    @if($mutasi->kapalTujuan)
                                        <span class="text-muted small">
                                            <i class="bi bi-arrow-right"></i> {{ $mutasi->kapalTujuan->nama_kapal }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $mutasi->abkTurun->nama_abk }}</span>
                                    <span class="text-muted small">NRP: {{ $mutasi->nrp_turun }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ 
                                    $mutasi->case_mutasi == 'Promosi' ? 'bg-success' : 
                                    ($mutasi->case_mutasi == 'Demosi' ? 'bg-warning' : 
                                    ($mutasi->case_mutasi == 'Rotasi' ? 'bg-info' : 'bg-secondary')) 
                                }}">
                                    {{ $mutasi->case_mutasi }}
                                </span>
                                <div class="small text-muted mt-1">{{ $mutasi->nama_mutasi }}</div>
                            </td>
                            <td>
                                @if($mutasi->sertijab)
                                    <a href="{{ asset('storage/' . $mutasi->sertijab->file_path) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Lihat Dokumen
                                    </a>
                                @else
                                    <span class="badge bg-danger">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if(!$mutasi->sertijab)
                                    <span class="badge bg-danger">Belum Upload</span>
                                @elseif($mutasi->sertijab->status_verifikasi == 'pending')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($mutasi->sertijab->status_verifikasi == 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($mutasi->sertijab->status_verifikasi == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($mutasi->sertijab)
                                    {{ $mutasi->sertijab->updated_at->format('d M Y, H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('monitoring.sertijab.detail', $mutasi->id_mutasi) }}">
                                                <i class="bi bi-eye me-2"></i> Detail
                                            </a>
                                        </li>
                                        @if($mutasi->sertijab && $mutasi->sertijab->status_verifikasi == 'pending')
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $mutasi->id_mutasi }}">
                                                <i class="bi bi-check2-circle me-2"></i> Verifikasi
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Verification Modal -->
                                @if($mutasi->sertijab && $mutasi->sertijab->status_verifikasi == 'pending')
                                <div class="modal fade" id="verifyModal{{ $mutasi->id_mutasi }}" tabindex="-1" aria-hidden="true">
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
                                                                <input class="form-check-input" type="radio" name="status_verifikasi" id="statusVerified{{ $mutasi->id_mutasi }}" value="verified" checked>
                                                                <label class="form-check-label" for="statusVerified{{ $mutasi->id_mutasi }}">
                                                                    Terverifikasi
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="status_verifikasi" id="statusRejected{{ $mutasi->id_mutasi }}" value="rejected">
                                                                <label class="form-check-label" for="statusRejected{{ $mutasi->id_mutasi }}">
                                                                    Ditolak
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes{{ $mutasi->id_mutasi }}" class="form-label">Catatan (Opsional)</label>
                                                        <textarea class="form-control" id="notes{{ $mutasi->id_mutasi }}" name="notes" rows="3" placeholder="Tambahkan catatan atau alasan penolakan..."></textarea>
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-inbox fs-1 text-muted mb-2"></i>
                                    <p class="mb-1">Belum ada data sertijab</p>
                                    <p class="text-muted small">
                                        @if(request('kapal_id'))
                                            Coba pilih kapal lain atau tampilkan semua kapal
                                        @else
                                            Data akan muncul setelah ada mutasi ABK yang memerlukan sertijab
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $mutasiList->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection