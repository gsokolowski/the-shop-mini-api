@if ($paginator->hasPages())
    <nav class="actions">
        @if ($paginator->onFirstPage())
            <span class="btn btn-secondary btn-sm" style="opacity:.5">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary btn-sm">Previous</a>
        @endif

        <span style="align-self:center;color:#6b7280">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary btn-sm">Next</a>
        @else
            <span class="btn btn-secondary btn-sm" style="opacity:.5">Next</span>
        @endif
    </nav>
@endif
