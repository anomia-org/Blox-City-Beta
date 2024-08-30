<div>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">{{ $thread->title }}</x-slot>
    <body class="forum-page">
        <div id="app">
            <div class="text-center show-for-small-only">
                <a href="{{ route('forum.my.threads') }}" class="button button-blue">My Threads</a>
                <a href="{{ route('forum.search') }}" class="button button-red">Search Forum</a>
            </div>
            <div class="grid-x grid-margin-x">
                <div class="cell small-12 medium-6">
                    <div class="forum-navigation">
                        <div class="forum-navigation-item">
                            <a href="/forum">Forum</a>
                        </div>
                    <div class="forum-navigation-item">
                        <a href="/forum">{{ $category->name }}</a>
                    </div>
                    <div class="forum-navigation-item">
                        <a href="{{ route('forum.topic', $topic) }}">{{ $topic->name }}</a>
                    </div>
                </div>
            </div>
            <div class="text-right cell small-12 medium-6 hide-for-small-only">
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
            {{ $thread->title }}
            </div>

            @if($replies->currentPage() == 1)
                <div class="container">
                    <div class="forum-container forum-post-container ">
                        <div class="grid-x grid-margin-x">
                            <div class="text-center cell small-4 medium-3">
                                <div class="forum-thread-creator-username">
                                    <div class="forum-thread-status @if($thread->owner->isOnline()) status-online @else status-offline @endif" title="{{ $thread->owner->username }} is @if($thread->owner->isOnline()) online @else offline @endif" data-tooltip></div>
                                    <a href="{{ route('user.profile', $thread->owner) }}">{{ $thread->owner->username }}</a>
                                </div>
                                <a href="{{ route('user.profile', $thread->owner) }}">
                                    <img class="forum-thread-creator-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ $thread->owner->render() }}">
                                </a>
                                @if ($thread->owner->power > 0)
                                    <img src="{{ asset('/img/forum/admin.png') }}" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Administrator" alt="Administrator">
                                @endif

                                @if ($thread->owner->membership > 0)
                                    @if ($thread->owner->membership == 1)
                                        <img src="/img/forum/bronze.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Bronze" alt="BLOX City Bronze">
                                    @endif
                                    @if ($thread->owner->membership == 2)
                                        <img src="/img/forum/silver.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Silver" alt="BLOX City Silver">
                                    @endif
                                    @if ($thread->owner->membership == 3)
                                        <img src="/img/forum/gold.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Gold" alt="BLOX City Gold">
                                    @endif
                                @endif

                                @if ($thread->owner->posts() >= 500)
                                    <img src="/img/forum/pforumer.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Pro Forumer" alt="Pro Forumer">
                                @endif
                                <br>
                                <div class="forum-thread-stats">
                                    <div class="grid-x grid-margin-x">
                                        <div class="cell small-6 medium-3 medium-offset-2">
                                            <strong>Posts</strong>
                                        </div>
                                        <div class="cell small-6 medium-3">
                                            {{ $thread->owner->get_short_num($thread->owner->posts()) }}
                                        </div>
                                    </div>
                                    <div class="grid-x grid-margin-x">
                                        <div class="cell small-6 medium-3 medium-offset-2">
                                            <strong>Joined</strong>
                                        </div>
                                        <div class="cell small-6 medium-3">
                                            {{ $thread->owner->created_at->format('m/d/Y') }}
                                        </div>
                                    </div>
                                    <div class="grid-x grid-margin-x">
                                        <div class="cell small-6 medium-3 medium-offset-2">
                                            <strong>Networth</strong>
                                        </div>
                                        <div class="cell small-6 medium-3 text-cash">
                                            ${{ $thread->owner->get_short_num($thread->owner->getUserValue()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cell small-8 medium-9">
                                <div class="forum-thread-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $thread->created_at->diffForHumans() }}</div>
                                @auth
                                    <div class="forum-thread-report">
                                        <a href="#">
                                            <i class="icon icon-report"></i>
                                        </a>
                                    </div>
                                    @if(!$thread->locked) 
                                        <a href="{{ route('forum.thread.quote', ['thread' => $thread->id, 'quote_id' => $thread->id, 'quote_type' => 1]) }}" class="forum-thread-quote">
                                            <i class="icon icon-quote"></i>
                                        </a>
                                    @endif
                                @endauth
                                <div class="forum-thread-body">{{ $thread->body }}</div>
                                <div class="forum-signature">{{ nl2br($thread->owner->signature) }}</div>
                                
                                    <div class="forum-mod-tools">
                                        <div class="forum-mod-tool">
                                            <a @auth wire:click="toggleLike({{ $thread->id }}, 2)" @endauth class="forum-thread-quote">
                                                <i class="icon @guest icon-favorited @endguest @auth {{ auth()->user()->likes()->where('target_type', 2)->where('target_id', $thread->id)->exists() ? 'icon-favorited' : 'icon-favorite' }} @endauth"></i>&nbsp;<span style="color:white!important;font-size:16px;">{{ number_format($thread->likes()->count()); }}</span>
                                            </a>
                                        </div>
                                    @auth
                                        @if(auth()->user()->power > 0)   
                                            @if(!$thread->stuck)
                                                @if(!$thread->pinned)
                                                    <div class="forum-mod-tool">
                                                        <a href="#" onclick="event.preventDefault();document.getElementById('thread-pin').submit()">Pin</a>
                                                        <form method="POST" id="thread-pin" action="{{ route('forum.thread.pin', $thread->id) }}">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="forum-mod-tool">
                                                        <a href="#" onclick="event.preventDefault();document.getElementById('thread-pin').submit()">Unpin</a>
                                                        <form method="POST" id="thread-pin" action="{{ route('forum.thread.pin', $thread->id) }}">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                            @if($thread->locked)
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-unlock').submit()">Unlock</a>
                                                    <form method="POST" id="thread-unlock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @else
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-unlock').submit()">Lock</a>
                                                    <form method="POST" id="thread-unlock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @endif
                                            @if($thread->scrubbed)
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-scrub').submit()">Unscrub</a>
                                                    <form method="POST" id="thread-scrub" action="{{ route('forum.thread.scrub', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @else
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-scrub').submit()">Scrub</a>
                                                    <form method="POST" id="thread-scrub" action="{{ route('forum.thread.scrub', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @endif
                                            @if($thread->deleted)
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-delete').submit()">Undelete</a>
                                                    <form method="POST" id="thread-delete" action="{{ route('forum.thread.delete', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @else
                                                <div class="forum-mod-tool">
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('thread-delete').submit()">Delete</a>
                                                    <form method="POST" id="thread-delete" action="{{ route('forum.thread.delete', $thread->id) }}" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @endif
                                            <div class="forum-mod-tool">
                                                <a href="{{ route('forum.thread.move', $thread->id) }}">Move</a>
                                            </div>
                                        @endif
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @foreach($replies as $reply)
            <!-- Begin reply -->
            <div class="container">
                <div class="forum-container forum-post-container " id="reply_1">
                    <div class="grid-x grid-margin-x">
                        <div class="text-center cell small-4 medium-3">
                            <div class="forum-thread-creator-username">
                                <div class="forum-thread-status @if($reply->owner->isOnline()) status-online @else status-offline @endif" title="{{ $reply->owner->username }} is @if($reply->owner->isOnline()) online @else offline @endif"  data-tooltip></div>
                                <a href="{{ route('user.profile', $reply->owner->id) }}">{{ $reply->owner->username }}</a>
                            </div>
                            <a href="{{ route('user.profile', $reply->owner->id) }}">
                                <img class="forum-thread-creator-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ $reply->owner->render() }}">
                            </a>

                            @if ($reply->owner->power > 0)
                                <img src="{{ asset('/img/forum/admin.png') }}" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Administrator" alt="Administrator">
                            @endif

                            @if ($reply->owner->membership > 0)
                                @if ($reply->owner->membership == 1)
                                        <img src="/img/forum/bronze.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Bronze" alt="BLOX City Bronze">
                                @endif
                                @if ($reply->owner->membership == 2)
                                    <img src="/img/forum/silver.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Silver" alt="BLOX City Silver">
                                @endif
                                @if ($reply->owner->membership == 3)
                                    <img src="/img/forum/gold.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="BLOX City Gold" alt="BLOX City Gold">
                                @endif
                            @endif

                            @if ($reply->owner->posts() >= 500)
                                <img src="/img/forum/pforumer.png" class="img-fluid" style="margin:0 auto;padding-bottom:5px;" title="Pro Forumer" alt="Pro Forumer">
                            @endif
                            <br>

                            <div class="forum-thread-stats">
                            <div class="grid-x grid-margin-x">
                            <div class="cell small-6 medium-3 medium-offset-2">
                            <strong>Posts</strong>
                        </div>
                        <div class="cell small-6 medium-3">
                            {{ $reply->owner->get_short_num($reply->owner->posts()) }}
                        </div>
                    </div>
                    <div class="grid-x grid-margin-x">
                        <div class="cell small-6 medium-3 medium-offset-2">
                            <strong>Joined</strong>
                        </div>
                        <div class="cell small-6 medium-3">
                            {{ $reply->owner->created_at->format('m/d/Y') }}
                        </div>
                    </div>
                    <div class="grid-x grid-margin-x">
                        <div class="cell small-6 medium-3 medium-offset-2">
                            <strong>Networth</strong>
                        </div>
                        <div class="cell small-6 medium-3 text-cash">
                            ${{ $reply->owner->get_short_num($reply->owner->getUserValue()) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="cell small-8 medium-9">
                <div class="forum-thread-time-posted">
                    <i class="icon icon-time-ago"></i> Posted {{ $reply->created_at->diffForHumans() }}
                </div>
                @auth
                    <div class="forum-thread-report">
                        <a href="#">
                            <i class="icon icon-report"></i>
                        </a>
                    </div>
                    @if(!$reply->thread->locked) 
                        <a href="{{ route('forum.thread.quote', ['thread' => $reply->thread_id, 'quote_id' => $reply->id, 'quote_type' => 2]) }}" class="forum-thread-quote">
                            <i class="icon icon-quote"></i>
                        </a>
                    @endif
                    
                @endauth
                @php
                    if($reply->quote_id != NULL)
                    {
                        $quote = NULL;
                        if($reply->quote_type == 1)
                        {
                            $quote = \App\Models\Thread::where('id', $reply->quote_id)->get()->first();
                        } elseif($reply->quote_type == 2) {
                            $quote = \App\Models\Reply::where('id', $reply->quote_id)->get()->first();
                        }

                        if($quote->exists)
                        {
                @endphp

                <!-- Begin quote -->
                <div class="forum-quote">
                    <div class="forum-quote-body">{{ $quote->body }}</div>
                    <div class="forum-quote-footer"><a>{{ $quote->owner->username }}</a>, {{ $quote->created_at->diffForHumans() }}</div>
                </div>
                <!-- End quote -->
                @php
                    }
                }
                @endphp
                <div class="forum-thread-body">{{ $reply->body }}</div>
                <div class="forum-signature">{{ nl2br($reply->owner->signature) }}</div>
                
                    <div class="forum-mod-tools">
                        <div class="forum-mod-tool">
                            <a @auth wire:click="toggleLike({{ $reply->id }}, 3)" @endauth class="forum-thread-quote">
                                <i class="icon @guest icon-favorited @endguest @auth {{ auth()->user()->likes()->where('target_type', 3)->where('target_id', $reply->id)->exists() ? 'icon-favorited' : 'icon-favorite' }} @endauth"></i>&nbsp;<span style="color:white!important;font-size:16px;">{{ number_format($reply->likes()->count()); }}</span>
                            </a>
                        </div>
                @auth
                        @if(auth()->user()->power > 0)
                            @if($reply->scrubbed)
                                <div class="forum-mod-tool">
                                    <a href="#" onclick="event.preventDefault();document.getElementById('reply-scrub-{{ $reply->id }}').submit()">Unscrub</a>
                                    <form method="POST" id="reply-scrub-{{ $reply->id }}" action="{{ route('forum.reply.scrub', $reply->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @else
                                <div class="forum-mod-tool">
                                    <a href="#" onclick="event.preventDefault();document.getElementById('reply-scrub-{{ $reply->id }}').submit()">Scrub</a>
                                    <form method="POST" id="reply-scrub-{{ $reply->id }}" action="{{ route('forum.reply.scrub', $reply->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                @endauth
            </div>
            </div>
            </div>
            </div>
        @endforeach
        <div class="container" style="border:none!important;">
            {{ $replies->onEachSide(1)->links('vendor.pagination.default') }}
        </div>
        @auth
            @if (!$thread->locked || ($thread->locked && Auth::user()->power > 1))
                <div class="push-15"></div>
                <div class="text-center">
                    <a href="{{ route('forum.thread.reply', ['thread' => $thread->id]) }}" class="forum-button">Reply</a>
                </div>
            @endif
        @endauth
    </body>
</div>