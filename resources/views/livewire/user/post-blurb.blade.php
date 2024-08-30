<div>
    <form wire:submit.prevent="submit">
        <x-text-input class="form-input" type="text" name="text" wire:model="text" placeholder="What's on your mind?" value="{{ old('text') }}" />
    </form>
</div>
