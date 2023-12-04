@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        {{-- <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                <!-- Previous Page Link -->
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                <!-- Next Page Link -->
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </div> --}}
        {{-- d-none    --}}
        <div class="d-sm-flex flex-sm-fill align-items-sm-center justify-content-sm-between">
            {{-- <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div> --}}

            <div>
                <ul class="pagination pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled me-1 text-white" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span style="color:#fff" class="page-link bg-primary" aria-hidden="true">PREV</span>
                        </li>
                    @else
                        <li class="page-item me-1 text-white">
                            <a style="color:#fff" class="page-link bg-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">PREV</a>
                        </li>
                    @endif
                        
                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled pt-1" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active pt-1" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item pt-1"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item ms-1 text-white">
                            <a style="color:#fff" class="page-link bg-primary" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">NEXT</a>
                        </li>
                    @else
                        <li class="page-item disabled ms-1 text-white" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span style="color:#fff" class="page-link bg-primary" aria-hidden="true">NEXT</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
