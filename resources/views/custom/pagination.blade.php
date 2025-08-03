@if ($paginator->hasPages())
<div class="pagination-preview">
    <div class="pagination-controls">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="btn-pagination" disabled>‹</button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn-pagination">‹</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <button class="btn-pagination" disabled>{{ $element }}</button>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="btn-pagination active" disabled>{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="btn-pagination">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn-pagination">›</a>
        @else
            <button class="btn-pagination" disabled>›</button>
        @endif
    </div>
</div>
@endif