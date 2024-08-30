<div>
    <x-slot name="navigation"></x-slot>
        <x-slot name="title">My Threads</x-slot>
        <body class="forum-page">
        
            @auth
                <div class="text-center show-for-small-only">
                    <a href="{{ route('forum.my.threads') }}" class="button button-blue">My Threads</a>
                    <a href="{{ route('forum.search') }}" class="button button-red">Search Forum</a>
                </div>
            @endauth
            <div class="grid-x grid-margin-x">
                <div class="cell small-9 medium-6">
                    <div class="forum-navigation">
                        <div class="forum-navigation-item">
                            <a href="{{ route('forum.index') }}">Forum</a>
                        </div>
                        <div class="forum-navigation-item">
                            <a href="{{ route('forum.search') }}">Search</a>
                        </div>
                    </div>
                </div>
                @auth
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
                @endauth
            </div>
            <div class="forum-header forum-post-header">
                <div class="grid-x grid-margin-x">
                    <div class="cell medium-8">
                        Post
                    </div>
                    <div class="text-center cell medium-1 hide-for-small-only">
                        Replies
                    </div>
                    <div class="text-center cell medium-1 hide-for-small-only">
                        Views
                    </div>
                    <div class="text-right cell medium-2 hide-for-small-only">
                        Last Post
                    </div>
                </div>
            </div>
            <div class="container">
                <input class="form-input" type="text" name="query" title="query" wire:model.defer="query" wire:keydown.enter="search" placeholder="Search Forum">
            </div>
            <div class="forum-container forum-topic-container">
                @forelse ($threads as $thread)
                    <div class="grid-x grid-margin-x align-middle forum-post-grid @if ($thread->deleted) is-deleted @endif">
                        <div class="cell medium-8">
                            <div class="forum-post-creator-avatar">
                                <img class="forum-post-creator-avatar-image" src="{{ ($thread->owner->id == 1) ? asset('img/branding/icon_text.png') : $thread->owner->headshot() }}">
                            </div>
                            <div class="forum-post-details">
                                <a href="{{ route('forum.thread', ['thread' => $thread]) }}" class="forum-post-name @if ($thread->pinned) forum-post-name-pinned @endif"> @if($thread->pinned) <i class="fa-sharp fa-solid fa-thumbtack" style="color:dodgerblue;"></i> @endif  @if ($thread->deleted) <i class="fa-sharp fa-solid fa-trash-can" style="color:red;"></i> @endif {{ $thread->title }}</a>
                                <div class="forum-post-poster">posted by <a <?php if($thread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->owner->membershipColor()."';"; } ?> href="{{ route('user.profile', $thread->owner->id) }}">{{ $thread->owner->username }}</a> {{ $thread->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="text-center cell medium-1 hide-for-small-only">
                            <div class="forum-container-stat">{{ number_format($thread->replies()->count()) }}</div>
                        </div>
                        <div class="text-center cell medium-1 hide-for-small-only">
                            <div class="forum-container-stat">{{ number_format($thread->views) }}</div>
                        </div>
                        <div class="text-right cell medium-2 hide-for-small-only">
                            
                            <a href="{{ route('forum.thread', ['thread' => $thread]) }}" class="forum-container-stat forum-container-stat-last-post">{{ $thread->title }}</a>
                                <div class="forum-container-stat forum-container-stat-last-poster">
                                    @if (!$thread->latestReply()->exists())
                                        by <a @php if($thread->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->owner->membershipColor()."';"; } @endphp href="{{ route('user.profile', $thread->owner->id) }}">{{ $thread->owner->username }}</a>, {{ $thread->created_at->diffForHumans() }}
                                    @else
                                        by <a @php if($thread->latestReply->owner->power > 0){ echo "style='font-weight:bold;color:red;'";} elseif($thread->latestReply->owner->membership > 0) { echo "style='font-weight:bold;color: ".$thread->latestReply->owner->membershipColor()."';"; } @endphp href="{{ route('user.profile', $thread->latestReply->owner->id) }}">{{ $thread->latestReply->owner->username }}</a>, {{ $thread->latestReply->created_at->diffForHumans() }}
                                    @endif
                                </div>
                        </div>
                    </div>
                @empty
                    <div class="cell">No threads found :(</div>
                @endforelse
                @if($threads->hasPages())
                    {{ $threads->onEachSide(1)->links('vendor.pagination.default') }}
                @endif
            </div>
        </body>
</div>
