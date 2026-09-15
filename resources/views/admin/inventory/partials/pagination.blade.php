{{-- resources/views/admin/inventory/partials/pagination.blade.php --}}
@if ($products->hasPages())
    <div class="custom-pagination-wrap">
        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <span class="page-nav-btn disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <button type="button" onclick="loadInventoryPage('{{ $products->previousPageUrl() }}')" class="page-nav-btn"><i class="bi bi-chevron-left"></i></button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if ($page == $products->currentPage())
                <span class="page-nav-btn active">{{ $page }}</span>
            @elseif ($page <= 3 || $page >= $products->lastPage() - 1 || abs($page - $products->currentPage()) <= 1)
                <button type="button" onclick="loadInventoryPage('{{ $url }}')" class="page-nav-btn">{{ $page }}</button>
            @elseif ($page == 4 && $products->currentPage() > 5)
                <span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <button type="button" onclick="loadInventoryPage('{{ $products->nextPageUrl() }}')" class="page-nav-btn"><i class="bi bi-chevron-right"></i></button>
        @else
            <span class="page-nav-btn disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
@endif
