{{-- resources/views/admin/analytics/partials/pagination.blade.php --}}
@if ($activities->hasPages())
    <div class="custom-pagination-wrap">
        {{-- First & Previous Page Links --}}
        @if ($activities->onFirstPage())
            <span class="page-nav-btn disabled" title="First Page"><i class="bi bi-chevron-double-left"></i></span>
            <span class="page-nav-btn disabled" title="Previous Page"><i class="bi bi-chevron-left"></i></span>
        @else
            <button type="button" onclick="fetchActivitiesPage(1)" class="page-nav-btn" title="First Page"><i class="bi bi-chevron-double-left"></i></button>
            <button type="button" onclick="fetchActivitiesPage({{ $activities->currentPage() - 1 }})" class="page-nav-btn" title="Previous Page"><i class="bi bi-chevron-left"></i></button>
        @endif

        {{-- Pagination Elements --}}
        @php
            $current = $activities->currentPage();
            $last = $activities->lastPage();
            $pages = [];
            for ($i = 1; $i <= $last; $i++) {
                if ($i === 1 || $i === $last || abs($i - $current) <= 2) {
                    $pages[] = $i;
                } elseif (end($pages) !== '...') {
                    $pages[] = '...';
                }
            }
        @endphp

        @foreach ($pages as $p)
            @if ($p === '...')
                <span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>
            @elseif ($p == $current)
                <span class="page-nav-btn active">{{ $p }}</span>
            @else
                <button type="button" onclick="fetchActivitiesPage({{ $p }})" class="page-nav-btn">{{ $p }}</button>
            @endif
        @endforeach

        {{-- Next & Last Page Links --}}
        @if ($activities->hasMorePages())
            <button type="button" onclick="fetchActivitiesPage({{ $activities->currentPage() + 1 }})" class="page-nav-btn" title="Next Page"><i class="bi bi-chevron-right"></i></button>
            <button type="button" onclick="fetchActivitiesPage({{ $last }})" class="page-nav-btn" title="Last Page"><i class="bi bi-chevron-double-right"></i></button>
        @else
            <span class="page-nav-btn disabled" title="Next Page"><i class="bi bi-chevron-right"></i></span>
            <span class="page-nav-btn disabled" title="Last Page"><i class="bi bi-chevron-double-right"></i></span>
        @endif
    </div>
@endif
