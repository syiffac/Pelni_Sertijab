{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\search.blade.php --}}
@extends('layouts.app')

@section('title', 'Pencarian Arsip Sertijab')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            <i class="bi bi-search me-2"></i> Pencarian Arsip Sertijab
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('arsip.index') }}">Arsip</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
            </ol>
        </nav>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Filter Pencarian</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('arsip.search') }}">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label for="kapal_id" class="form-label">Kapal</label>
                        <select class="form-select" id="kapal_id" name="kapal_id">
                            <option value="">Semua Kapal</option>
                            @foreach($kapalList as $kapal)
                                <option value="{{ $kapal->id_kapal }}" {{ $kapalId == $kapal->id_kapal ? 'selected' : '' }}>
                                    {{ $kapal->nama_kapal }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="final" {{ $status == 'final' ? 'selected' : '' }}>Final (Verified)</option>
                            <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft (Pending/Rejected)</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select class="form-select" id="tahun" name="tahun">
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                        <label for="search" class="form-label">Cari ABK</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Nama/NRP ABK" value="{{ $searchTerm }}">
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('arsip.search') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Hasil Pencarian ({{ $arsipList->total() }} dokumen)</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('arsip.create') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Arsip
                </a>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    <i class="bi bi-check2-all me-1"></i> Aksi Massal
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($arsipList->count() > 0)
                <div class="row g-3">
                    @foreach($arsipList as $arsip)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="form-check me-3">
                                            <input class="form-check-input arsip-checkbox" type="checkbox" 
                                                   value="{{ $arsip->id_sertijab }}" id="arsip{{ $arsip->id_sertijab }}">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $arsip->mutasi->abkTurun->nama_abk ?? 'N/A' }}</h6>
                                            <p class="small text-muted mb-1">NRP: {{ $arsip->mutasi->abkTurun->NRP ?? 'N/A' }}</p>
                                        </div>
                                        <span class="badge {{ $arsip->status_verifikasi == 'verified' ? 'bg-success' : ($arsip->status_verifikasi == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($arsip->status_verifikasi) }}
                                        </span>
                                    </div>
                                    
                                    <div class="small mb-2">
                                        <div class="row">
                                            <div class="col-12 mb-1">
                                                <strong>Kapal:</strong> {{ $arsip->mutasi->kapalAsal->nama_kapal ?? 'N/A' }}
                                                @if($arsip->mutasi->kapalTujuan)
                                                    → {{ $arsip->mutasi->kapalTujuan->nama_kapal }}
                                                @endif
                                            </div>
                                            <div class="col-12 mb-1">
                                                <strong>Jabatan:</strong> {{ $arsip->mutasi->jabatanLama->nama_jabatan ?? 'N/A' }}
                                                @if($arsip->mutasi->jabatanBaru)
                                                    → {{ $arsip->mutasi->jabatanBaru->nama_jabatan }}
                                                @endif
                                            </div>
                                            <div class="col-12 mb-1">
                                                <strong>TMT:</strong> {{ $arsip->mutasi->TMT ? $arsip->mutasi->TMT->format('d/m/Y') : 'N/A' }}
                                            </div>
                                            <div class="col-12">
                                                <strong>Upload:</strong> {{ $arsip->uploaded_at ? $arsip->uploaded_at->format('d/m/Y H:i') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($arsip->keterangan_pengunggah_puk)
                                        <div class="small text-muted mb-2">
                                            <em>"{{ Str::limit($arsip->keterangan_pengunggah_puk, 50) }}"</em>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer bg-transparent border-top-0 pt-0">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('arsip.show', $arsip->id_sertijab) }}" 
                                           class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('arsip.edit', $arsip->id_sertijab) }}" 
                                           class="btn btn-sm btn-outline-warning flex-fill">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        @if($arsip->file_path)
                                            <a href="{{ Storage::url($arsip->file_path) }}" target="_blank"
                                               class="btn btn-sm btn-outline-info flex-fill">
                                                <i class="bi bi-download"></i> File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $arsipList->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Arsip Ditemukan</h5>
                    <p class="text-muted">Coba ubah filter pencarian atau tambah arsip baru</p>
                    <a href="{{ route('arsip.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Arsip
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('arsip.bulk-update-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_status" class="form-label">Ubah Status</label>
                        <select class="form-select" id="bulk_status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="bulk_notes" name="notes" rows="3" 
                                  placeholder="Catatan untuk perubahan status..."></textarea>
                    </div>
                    <div id="selected-count" class="text-muted small">
                        Belum ada dokumen yang dipilih
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="bulk-submit-btn" disabled>
                        <i class="bi bi-check-circle me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.arsip-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const bulkSubmitBtn = document.getElementById('bulk-submit-btn');
    const bulkForm = document.querySelector('#bulkActionModal form');
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.arsip-checkbox:checked');
        const count = selected.length;
        
        selectedCount.textContent = count > 0 ? `${count} dokumen dipilih` : 'Belum ada dokumen yang dipilih';
        bulkSubmitBtn.disabled = count === 0;
        
        // Update hidden inputs
        const existingInputs = bulkForm.querySelectorAll('input[name="sertijab_ids[]"]');
        existingInputs.forEach(input => input.remove());
        
        selected.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'sertijab_ids[]';
            hiddenInput.value = checkbox.value;
            bulkForm.appendChild(hiddenInput);
        });
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Select All functionality
    const selectAllBtn = document.getElementById('select-all-btn');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allChecked = document.querySelectorAll('.arsip-checkbox:checked').length === checkboxes.length;
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            updateSelectedCount();
        });
    }
});
</script>
@endpush