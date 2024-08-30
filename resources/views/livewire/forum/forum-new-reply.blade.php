<div>
    <x-slot name="title">Replying to "{{ $thread->title }}"</x-slot>
    <x-slot name="navigation"></x-slot>
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
                        <a href="{{ route('forum.topic', ['topic' => $thread->topic]) }}">{{ $thread->topic->name }}</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.thread', ['thread' => $thread]) }}">{{ $thread->title }}</a>
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
            Reply to "{{ $thread->title }}"
        </div>
        <div class="container forum-container">
            <form wire:submit.prevent="submit">
                <textarea class="form-input" name="body" id="body" wire:model="body" placeholder="Body (max 3,000 characters)" maxlength="3000" rows="6"></textarea>
                <div class="push-15"></div>
                <button class="forum-button" type="submit">Reply</button>
            </form>
        </div>
    </body>
</div>
