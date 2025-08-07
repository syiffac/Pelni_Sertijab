<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th width="15%">NRP</th>
                <th width="25%">Nama ABK</th>
                <th width="20%">Jabatan</th>
                <th width="15%">Status</th>
                <th width="15%">Kapal</th>
                <th width="10%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abkList as $abk)
                <tr class="abk-row">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <span class="badge bg-light text-dark fw-bold">{{ $abk->id }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-person-fill text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $abk->nama_abk }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-plus"></i>
                                    {{ $abk->created_at ? $abk->created_at->format('d/m/Y') : 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-medium">{{ $abk->jabatanTetap->nama_jabatan ?? 'Tidak ada jabatan' }}</span>
                    </td>
                    <td>
                        @if($abk->status_abk == 'Organik')
                            <span class="badge bg-success">Organik</span>
                        @elseif($abk->status_abk == 'Non Organik')
                            <span class="badge bg-warning">Non Organik</span>
                        @elseif($abk->status_abk == 'Pensiun')
                            <span class="badge bg-secondary">Pensiun</span>
                        @else
                            <span class="badge bg-light text-dark">{{ $abk->status_abk ?? 'N/A' }}</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($abk->kapalAktif) && $abk->kapalAktif->nama_kapal ?? false)
                            <div class="d-flex align-items-center">
                                <i class="bi bi-ship text-primary me-2"></i>
                                <span class="fw-medium">{{ $abk->kapalAktif->nama_kapal }}</span>
                            </div>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-dash-circle me-1"></i>
                                Belum Bertugas
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('abk.show', $abk->id) }}" 
                               class="btn btn-sm btn-outline-info" 
                               title="Lihat Detail ABK">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('abk.edit', $abk->id) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Edit ABK">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Hapus ABK"
                                    onclick="confirmDelete('{{ $abk->id }}', '{{ $abk->nama_abk }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="empty-state">
                            @if($search)
                                <i class="bi bi-search"></i>
                                <h6>Tidak Ada Hasil Ditemukan</h6>
                                <p>Tidak ada ABK yang sesuai dengan pencarian "<strong>{{ $search }}</strong>"</p>
                                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="clearSearch()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Tampilkan Semua
                                </button>
                            @else
                                <i class="bi bi-people"></i>
                                <h6>Belum Ada Data ABK</h6>
                                <p>Silakan tambahkan ABK baru untuk mulai mengelola data</p>
                                <a href="{{ route('abk.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-person-plus"></i>
                                    Tambah ABK Pertama
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>