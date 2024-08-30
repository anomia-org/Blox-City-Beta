@if ($paginator->hasPages())
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li disabled><span><i class="icon icon-paginate-previous"></i></span></li>
        @else
            <li><a wire:click="previousPage" wire:loading.attr="disabled"><i class="icon icon-paginate-previous"></i></a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li disabled><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a wire:click="setPage({{ $page }})" wire:loading.attr="disabled">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if (!$paginator->hasMorePages())
            <li disabled><span><i class="icon icon-paginate-next"></i></span></li>
        @else
            <li><a wire:click="nextPage" wire:loading.attr="disabled"><i class="icon icon-paginate-next"></i></a></li>
        @endif
    </ul>
@endif