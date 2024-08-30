<div>
    <div class="grid-x grid-margin-x mb-15">
        <div class="cell small-12 medium-2">
            <select class="form-input" wire:model="category">
                <option value="0" selected>Recent</option>
                <option value="1">Hats</option>
                <option value="2">Faces</option>
                <option value="3">Accessories</option>
                <option value="4">Shirts</option>
                <option value="5">Pants</option>
            </select>
        </div>
        <div class="cell small-12 medium-10">
            <div class="push-5 show-for-small-only"></div>
            <input wire:model="query" wire:keydown.enter="search"  class="form-input" id="search" type="text" placeholder="Search and press enter...">
        </div>
    </div>
</div>
