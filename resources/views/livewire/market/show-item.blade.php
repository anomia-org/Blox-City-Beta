<div>
    <!-- Item data -->
                <div class="container mb-25">
                    <div class="grid-x grid-margin-x">
                        <div class="cell small-12 medium-3">
                            <img style="width:250px!important;" src="{{ $item->render() }}">
                            <div class="push-15"></div>
                            <div class="text-center">
                            </div>
                            <div class="push-25 show-for-small-only"></div>
                        </div>
                        <div class="cell small-12 medium-6">
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-type">{{ $item->get_type() }}</div>
                            <div class="item-description">@if($item->desc != null)
                                {{ $item->desc }}
                                @else
                                No description set.
                                @endif</div>
                            <div class="push-25 show-for-small-only"></div>
                        </div>

                        <div class="text-center cell small-12 medium-3">
                            <div class="modal market-modal reveal" id="buy-modal" data-reveal>
                                <div class="modal-title" id="buy-modal-title"></div>
                                <div class="modal-content" id="buy-modal-body"></div>
                                <div class="modal-footer" id="buy-modal-footer"></div>
                            </div>
                            @auth
                                @if((!auth()->user()->owns($item) && $item->stock() > 0 && auth()->user()->id != $item->owner->id) || (!auth()->user()->owns($item) && $item->stock() == -1 && auth()->user()->id != $item->owner->id))
                                    @if($item->cash > 0)

                                    <!-- cash modal -->
                                    <div class="modal fade" style="padding:0!important;" id="coinModal" tabindex="-1" aria-labelledby="coinsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                @if(auth()->user()->bucks >= $item->cash)
                                                    <button wire:click="buyItem(1)" type="submit" class="button button-block button-cash item-buy-button">
                                                        Buy for {{ number_format($item->cash) }} Cash
                                                    </button>
                                                @else
                                                    <button type="button" class="button button-block button-cash item-buy-button">
                                                        Insufficient Funds
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end cash modal -->
                                    @endif

                                    @if($item->coins > 0)
                                    <!-- coins modal -->
                                    <div class="modal fade" style="padding:0!important;" id="coinModal" tabindex="-1" aria-labelledby="coinsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                @if(auth()->user()->bits >= $item->coins)
                                                    <button wire:click="buyItem(2)" type="submit" class="button button-block button-coins item-buy-button">
                                                        Buy for {{ number_format($item->coins) }} Coins
                                                    </button>
                                                @else
                                                    <button type="button" class="button button-block button-coins item-buy-button">
                                                        Insufficient Funds
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end coins modal -->
                                    @endif

                                    @if($item->free())
                                    <!-- free modal -->
                                    <div class="modal fade" id="freeModal" tabindex="-1" aria-labelledby="freeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <button wire:click="buyItem(3)" type="submit" class="button button-block button-blue">
                                                    Buy Now
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end free modal -->
                                    @endif
                                @endif
                                @if($item->owner->id == auth()->user()->id)
                                    <a href="{{ route('market.edit', $item) }}" class="button button-block button-blue item-buy-button">Edit Item</a>
                                @endif
                            @endauth
                            <div class="item-creator-title">Creator</div>
                            <a href="{{ route('user.profile', $item->owner->id) }}">
                                <div class="item-creator-avatar">
                                    <img class="item-creator-avatar-image" src="@if($item->owner->id == 1) {{ asset('img/branding/icon_text.png') }} @else {{ $item->owner->headshot() }} @endif">
                                </div>
                            </a>
                            <a href="{{ route('user.profile', $item->owner->id) }}" class="item-creator-username">{{ $item->owner->username }}</a>
                            @auth
                                <button wire:click="toggleLike"><i class="icon item-favorite {{ $isLiked ? 'icon-favorited' : 'icon-favorite' }}"></i></button>
                            @endauth
                        </div>
                    </div>
                    <div class="push-25"></div>
                    <div class="text-center grid-x grid-margin-x">
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</div>
                            <div class="item-stat-name">Time Created</div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ Carbon\Carbon::parse($item->updated_real)->diffForHumans() }}</div>
                            <div class="item-stat-name">Last Updated</div>
                            <div class="push-15 show-for-small-only"></div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ $item->get_short_price($item->sold()) }}</div>
                            <div class="item-stat-name">Owners</div>
                        </div>
                        <div class="cell small-6 medium-3">
                            <div class="item-stat-result">{{ number_format($likesCount) }}</div>
                            <div class="item-stat-name">Favorites</div>
                        </div>
                    </div>
                </div>
                <!-- Item data end -->
</div>
