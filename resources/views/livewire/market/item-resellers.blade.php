<div>
    @auth
        <div class="modal market-modal reveal" id="buy-collectible-modal" data-reveal>
            <div class="modal-title" id="buy-collectible-modal-title"></div>
            <div class="modal-content" id="buy-collectible-modal-body"></div>
            <div class="modal-footer" id="buy-collectible-modal-footer"></div>
        </div>
        @if (auth()->user()->owns($item))
            <div class="modal market-modal reveal" id="sell-collectible-modal" data-reveal>
                <form wire:submit.prevent="resell">
                    <div class="modal-title">Sell Collectible</div>
                    <div class="modal-content">
                        <select class="form-input" wire:model="serial" name="serial" id="serial">
                            @foreach (auth()->user()->specials() as $special)
                                @if (!$special->onsale() && $special->item_id == $item->id)
                                    <option wire:key="resale-available-{{ $special->id }}" value="{{ $special->id }}">Serial #{{ $special->getSerialNumber() }}</option>
                                @endif
                            @endforeach
                        </select>
                        <input class="form-input" wire:model="price" type="number" name="price" id="price" placeholder="Price" required>
                    </div>
                    <div class="modal-footer">
                        <div class="modal-buttons">
                            <button class="button button-blue" type="submit">SELL</button>
                            <button class="button button-red" style="margin-left:10px;" data-close>CANCEL</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    @endauth
    
    <div class="item-header">Collectible Information</div>
    <div class="container mb-25">
        <div class="item-estimated-value-header">Estimated Value: <span class="text-cash">${{ number_format($item->avgResalePrice()) }} Cash</span></div>
        <canvas id="valueovertime" style="height:250px;width:100%;" class="chartjs-render-monitor"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
                    var ctx = document.getElementById('valueovertime').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($item->chartLabels()),
                            datasets: [{
                                label: "Cash Price",
                                data: @json($item->chartData()),
                                backgroundColor: [
                                    'rgba(64, 164, 80, 0.5)'
                                ],
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: 26
                                    }
                                }]
                            },
                            responsive: false,
                            maintainAspectRatio: false,
                        }
                    });
        </script>   
    </div>
    @auth
        <div class="grid-x grid-margin-x">
            <div class="auto cell">
                <div class="item-private-sellers-header">Private Sellers</div>
            </div>
            @if (auth()->user()->owns($item))
                <div class="text-right shrink cell">
                    <div class="button button-blue" style="padding:5px 25px;" data-toggle="sell-collectible-modal">Sell</div>
                    <div class="push-10"></div>
                </div>
            @endif
        </div>
        <div class="container item-private-sellers-container mb-25">
            @forelse ($markets as $reseller)
                <div wire:key="reseller-item-{{ $reseller->id }}" class="align-middle grid-x grid-margin-x reseller">
                    <div class="text-center cell small-4 medium-2">
                        <div class="item-private-seller-user-holder">
                            <a href="{{ route('user.profile', $reseller->seller) }}">
                                <div class="item-private-seller-avatar">
                                    <img class="item-private-seller-avatar-image"
                                        src="{{ $reseller->seller->headshot() }}">
                                </div>
                            </a>
                            <a href="{{ route('user.profile', $reseller->seller) }}"
                                class="item-private-seller-username">{{ $reseller->seller->username }}</a>
                        </div>
                    </div>
                    <div class="text-center cell small-4 medium-3 medium-offset-2">
                        <code class="form-input">#{{ $reseller->inventory->getSerialNumber() }} / {{ $item->sold() }}</code>
                    </div>
                    <div class="text-right cell small-4 medium-4 medium-offset-1">
                        @if (auth()->check() && $reseller->seller->id == auth()->user()->id)
                            <form >
                                <input hidden name="listing" value="{{ $reseller->id }}" />
                                <button class="button button-red item-buy-button" type="submit">Take Offsale</button>
                            </form>
                        @else
                            <button class="button button-green item-buy-button" data-price="{{ $reseller->price }}"
                                data-listing-id="{{ $reseller->id }}"
                                data-serial-id="{{ $reseller->inventory->collection_number }}"
                                data-toggle="buy-collectible-modal">Buy for {{ number_format($reseller->price) }}
                                Cash</button>
                        @endif
                    </div>
                </div>
                <hr>
            @empty
                <p>There is currently nobody selling this item.</p>
            @endforelse
        </div>
    @endauth
</div>
