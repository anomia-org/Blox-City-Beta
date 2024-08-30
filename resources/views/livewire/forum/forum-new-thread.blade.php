<div>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">New Thread</x-slot>
    <body class="forum-page">
        <div class="text-center show-for-small-only">
            <a href="{{ route('forum.my.threads') }}" class="button button-blue">My Threads</a>
            <a href="{{ route('forum.search') }}" class="button button-red">Search Forum</a>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell small-9 medium-6">
                <div class="forum-navigation">
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">Forum</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.index') }}">{{ config('app.name') }}</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="#">New Thread</a>
                    </div>
                </div>
            </div>
            <div class="text-right cell medium-6 hide-for-small-only">
                <div class="forum-auth-navigation">
                    <div class="forum-auth-navigation-item">
                        <a href="{{ route('forum.my.threads') }}">My Threads</a>
                    </div>
                    <div class="forum-auth-navigation-item">
                        <a href="{{ route('forum.search') }}">Search Forum</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="forum-header forum-thread-header">
            Create a new thread
        </div>
        <div class="container forum-container">
            <form wire:submit.prevent="submit">
                <input type="text" class="form-input" id="title" name="title" wire:model="title" placeholder="Title (max 50 characters)" value="{{ old('title') }}" required="required">
                <textarea class="form-input" name="body" id="body" wire:model="body" placeholder="Body (max 3,000 characters)" maxlength="3000" rows="6">{{ old('body') }}</textarea>
                <div class="push-15"></div>
                <button class="forum-button" type="submit">Create</button>
            </form>
        </div>
    </body>
</div>
