<div>
    <div class="container" id="comments">
        @auth
            <form wire:submit.prevent="submit">
                <textarea class="form-input" wire:model="text" name="text" placeholder="Write your comment here..." rows="3"></textarea>
                <button class="button button-blue" type="submit">Post</button>
            </form>
            <hr style="border-top:1px solid #292c31;border-bottom:0.8px;"/>
        @endauth
        <div class="item-comments">
            @if($comments->count() > 0)

                @foreach($comments as $comment)
                    <div wire:key="asset-comment-{{ $comment->id }}" class="item-comment grid-x grid-margin-x">
                        <div class="text-center cell small-3 medium-2">
                            <a href="{{ route('user.profile', $comment->owner->id) }}">
                                <img src="{{ $comment->owner->render() }}">
                            </a>
                            <a href="{{ route('user.profile', $comment->owner->id) }}" class="comment-creator">{{ $comment->owner->username }}</a>
                        </div>
                        <div class="cell small-9 medium-10">
                            <div class="comment-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $comment->created_at->diffForHumans() }}</div>
                            <a href="#" class="comment-report">
                                <i class="icon icon-report"></i>
                            </a>
                            <div class="comment-body">{{ $comment->text }}</div>
                            <div class="push-10"></div>
                            <a @auth wire:click="toggleLike({{ $comment->id }})" @endauth class="forum-thread-quote">
                                <i class="icon @guest icon-favorited @endguest @auth {{ auth()->user()->likes()->where('target_type', 4)->where('target_id', $comment->id)->exists() ? 'icon-favorited' : 'icon-favorite' }} @endauth"></i>&nbsp;<span style="color:white!important;font-size:16px;">{{ number_format($comment->likes()->count()) }}</span>
                            </a>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr style="border-top:1px solid #292c31;border-bottom:0.8px;"/>
                    @endif
                @endforeach
                
                @if($comments->hasPages())
                    <div class="container" style="border:none!important;">
                        {{ $comments->onEachSide(1)->links('vendor.pagination.default') }}
                    </div>
                @endif
            @else
                <center class="mt-4">No comments :(</center>
            @endif
        </div>
    </div>
    
</div>
