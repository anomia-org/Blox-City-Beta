<div>
    <div class="container" id="suggested" style="display:none;">
        <div class="grid-x grid-margin-x">
            @forelse ($suggestions as $suggestion)
                <div class="cell small-6 medium-3">
                    <div class="suggested-item">
                        <a href="{{ route('market.item', $suggestion) }}" title="{{ $suggestion->name }}">
                            <img class="market-item-thumbnail" src="{{ $suggestion->render() }}">
                        </a>
                        <a href="{{ route('market.item', $suggestion) }}" class="market-item-name" title="{{ $suggestion->name }}">{{ $suggestion->name }}</a>
                        <div class="market-item-creator">Creator: <a href="{{ route('user.profile', $suggestion->owner) }}">{{ $suggestion->owner->username }}</a></div>
                        <div class="market-item-price">
                            @if(!$suggestion->free())
                                @if($suggestion->stock() > 0 || $suggestion->stock() == -1)
                                    @if($suggestion->cash > 0)
                                        <div class="market-item-price-cash">
                                            <i class="icon icon-cash"></i> {{ $suggestion->get_short_price($suggestion->cash) }}
                                        </div>
                                    @endif
                                    @if($suggestion->coins > 0)
                                        <div class="market-item-price-coins">
                                            <i class="icon icon-coins"></i> {{ $suggestion->get_short_price($suggestion->coins) }}
                                        </div>
                                    @endif
                                @endif
                            @else
                                Free
                            @endif
                            @if($suggestion->cash == 0 && $suggestion->coins == 0)
                            @endif
                            @if($suggestion->stock() > 0 && $suggestion->special)
                                <p style="color:#E71D36">{{ $suggestion->stock() }} Remaining</p>
                            @elseif($suggestion->stock() <= 0 && $suggestion->special)
                                <p style="color:#E71D36">Sold Out</p>
                            @endif
                            @if($suggestion->offsale_at != NULL && $suggestion->special == 0 && !$suggestion->offsale_at->isPast() && (($suggestion->cash > 0 && $suggestion->coins > 0) || ($suggestion->coins < 0 && $suggestion->cash < 0)))
                                <p style="color:#E71D36">Offsale in {{ $suggestion->offsale_at->diffForHumans(null, true, true) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="push-15 show-for-small-only"></div>
                </div>
            @empty
                <div class="cell auto">There are no suggested items.</div>
            @endforelse
        </div>
    </div>
</div>
