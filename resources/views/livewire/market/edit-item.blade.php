<div>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Editing "{{ $item->name }}"</x-slot>
    <body class="item-page">
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-6">
                <div class="container"> 
                    <h5>Edit "{{ $item->name }}"</h5>
                    <hr>
                    <form wire:submit.prevent="submit">
                        <label class="form-label">Name</label>
                        <input class="form-input" wire:model="name" type="text" name="title" placeholder="Name" value="{{ $item->name }}" required>
                        <label class="form-label">Description</label>
                        <textarea class="form-input" wire:model="desc" name="description" rows="5">{{ $item->desc }}</textarea>
                        <label class="form-label">Onsale</label>
                        <select class="form-input" wire:model="saleStatus">
                            <option value="off">No</option>
                            <option value="on">Yes</option>
                        </select>
                        @if($saleStatus == 'on')
                            <div id="sale-options">
                                <div class="grid-x grid-margin-x mb-15">
                                    <div class="cell auto">
                                        <label class="form-label">Coins</label>
                                        <input class="form-input" wire:model="bits" type="number" name="coins" placeholder="Price" value="{{ $item->coins }}" required>
                                    </div>
                                    <div class="cell auto">
                                        <label class="form-label">Cash</label>
                                        <input class="form-input" wire:model="bucks" type="number" name="cash" placeholder="Price" value="{{ $item->cash }}" required>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <input class="button button-blue" value="Update" type="submit" />
                    </form>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell small-12 medium-6">
                <div class="container">
                    <h5>Thumbnail</h5>
                    <hr>
                    <div class="text-center">
                        <img src="{{ $item->render() }}" style="max-width:100%;">
                    </div>
                </div>
            </div>
        </div>
    </body>
</div>
