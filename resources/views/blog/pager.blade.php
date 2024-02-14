@if ($paginator->hasPages())


    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <nav class="flex justify-between">
                @if ($paginator->onFirstPage())
                    <button class="cursor-auto disabled:opacity-50" disabled>
                        Previous
                    </button>
                @else
                    <a rel="prev" href="{{ $paginator->previousPageUrl() }}">
                        Previous
                    </a>
                @endif
                <span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

                @if ($paginator->onLastPage())
                    <button class="cursor-auto disabled:opacity-50" disabled>
                        Next
                    </button>
                @else
                    <a rel="next" href="{{ $paginator->nextPageUrl() }}">
                        Next
                    </a>
                @endif
            </nav>
        </div>
    </nav>

@endif
