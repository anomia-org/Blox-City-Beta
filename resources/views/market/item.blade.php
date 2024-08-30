<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">{{ $item->name }}</x-slot>
    <meta name="item-data" data-id="{{ $item->id }}" data-collectible="{{ $item->special }}" />
    <body class="item-page">
        <livewire:market.show-item :item="$item" />
        
        @if ($item->special && $item->stock() <= 0)
            <livewire:market.item-resellers :item="$item" />
        @endif
        
        <div class="tabs">
            <div class="tab" style="width:50%;">
                <a class="tab-link active" id="comments_tab">Comments</a>
            </div>
            <div class="tab" style="width:50%;">
                <a class="tab-link" id="suggested_tab">Suggested Items</a>
            </div>
        </div>
        <livewire:market.item-posts :item="$item" />
        <livewire:market.suggested :item="$item" />
        <x-slot name="script">
            <script>
                var currentTab = 'comments';

                $(function() {
                    $('.tab-link').click(function(tab) {
                        $(`#${currentTab}_tab`).removeClass('active');
                        $(`#${tab.target.id}`).addClass('active');

                        $(`#${currentTab}`).hide();

                        currentTab = tab.target.id.replace('_tab', '');

                        $(`#${currentTab}`).show();
                    });
                });
            </script>

            @auth
                <script>
                    var itemData;
                    var itemId;
                    var isCollectible;

                    $(function() {
                        itemData = $('meta[name="item-data"]');
                        itemId = parseInt(itemData.attr('data-id'));
                        isCollectible = parseInt(itemData.attr('data-collectible'));

                        $('[data-listing-id]').click(function() {
                            var title;
                            var body;
                            var footer = '';
                            var price = parseInt($(this).attr('data-price'));
                            var cash = {{ auth()->user()->bucks }};
                            var balanceAfter = cash - price;
                            var listingId = parseInt($(this).attr('data-listing-id'));
                            var serialId = $(this).attr('data-serial-id');

                            $('#buy-collectible-modal-title').empty();
                            $('#buy-collectible-modal-body').empty();
                            $('#buy-collectible-modal-footer').empty();

                            if (cash < price) {
                                title = 'Insufficient Cash';
                                body = `You do not have enough <div class="balance-after-cash">Cash</div> to purchase this item.`;
                            } else if (cash >= price) {
                                title = 'Purchase Serial #' + serialId;
                                body = `Are you sure you wish to purchase this item? You balance after this transaction will be <div class="balance-after-cash">${balanceAfter}</div> Cash.`;
                            } else {
                                title = 'Error';
                                body = 'An unexpected error has occurred';
                            }

                            if (cash >= price) {
                                footer = `
                                <form>
                                    <input type="hidden" name="listing" value="${listingId}">
                                    <button class="button button-green" type="submit">BUY NOW</button>
                                </form>`;
                            }

                            $('#buy-collectible-modal-title').text(title);
                            $('#buy-collectible-modal-body').html(body);
                            $('#buy-collectible-modal-footer').html('<div class="modal-buttons">' + footer + '<button class="button button-red" style="margin-left:10px;" data-close>CANCEL</button></div>');
                            $('#buy-collectible-modal').foundation('reveal', 'open');
                        });
                    });

                </script>
            @endauth
        </x-slot>
    </body>
</x-app-layout>