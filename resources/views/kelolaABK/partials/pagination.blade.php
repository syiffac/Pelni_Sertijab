{{-- filepath: d:\laragon\www\Pelni_Sertijab\resources\views\kelolaABK\partials\pagination.blade.php --}}

@if ($abkList->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- Pagination Info -->
        <div class="pagination-info">
            <i class="bi bi-info-circle me-1"></i>
            <small>
                Menampilkan {{ $abkList->firstItem() ?? 0 }} - {{ $abkList->lastItem() ?? 0 }} dari {{ $abkList->total() }} ABK
                @if($search)
                    <span class="text-primary">untuk pencarian "<strong>{{ $search }}</strong>"</span>
                @endif
            </small>
        </div>

        <!-- Pagination Links -->
        <div class="pagination-wrapper">
            <nav aria-label="Pagination ABK">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page Link --}}
                    @if ($abkList->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Prev</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage({{ $abkList->currentPage() - 1 }}); return false;" title="Halaman sebelumnya">
                                <i class="bi bi-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Prev</span>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $start = max(1, $abkList->currentPage() - 2);
                        $end = min($abkList->lastPage(), $abkList->currentPage() + 2);
                    @endphp

                    {{-- First Page --}}
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage(1); return false;">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    {{-- Page Numbers --}}
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $abkList->currentPage())
                            <li class="page-item active">
                                <span class="page-link">
                                    {{ $page }}
                                    <span class="visually-hidden">(current)</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="loadPage({{ $page }}); return false;" title="Halaman {{ $page }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endfor

                    {{-- Last Page --}}
                    @if($end < $abkList->lastPage())
                        @if($end < $abkList->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage({{ $abkList->lastPage() }}); return false;">{{ $abkList->lastPage() }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($abkList->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage({{ $abkList->currentPage() + 1 }}); return false;" title="Halaman selanjutnya">
                                <span class="d-none d-sm-inline me-1">Next</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <span class="d-none d-sm-inline me-1">Next</span>
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>

        <!-- Per Page Selector -->
        <div class="per-page-selector">
            <select class="form-select form-select-sm" 
                    onchange="changePerPage(this.value)" 
                    style="width: auto;"
                    title="Ubah jumlah data per halaman">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 per halaman</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per halaman</option>
                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 per halaman</option>
            </select>
        </div>
    </div>
@else
    <div class="text-center">
        <div class="pagination-info d-inline-flex align-items-center">
            <i class="bi bi-info-circle me-1 text-muted"></i>
            <small class="text-muted">
                Total: {{ $abkList->total() }} ABK
                @if($search)
                    <span class="text-primary">untuk pencarian "<strong>{{ $search }}</strong>"</span>
                @endif
            </small>
        </div>
    </div>
@endif
