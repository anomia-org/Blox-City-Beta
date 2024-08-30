<div>
    @if($posts->isEmpty())
        <div class="feed-no-notifications">You have no notifications.</div>
        <div class="feed-why-not">Why not try <a href="#" wire:navigate>searching for users</a> or <a href="#" wire:navigate>chatting with users</a> in our forum?</div>
    @else
        @foreach($posts as $blurb)
            <div class="dashboard-status">
                <div class="grid-x grid-margin-x">
                    <div class="text-center cell small-3 medium-2">
                        <div class="dashboard-status-creator-avatar">
                            <a href="#">
                                <img class="dashboard-status-creator-avatar-image" src="{{ $blurb->owner->headshot() }}">
                            </a>
                        </div>
                        <a href="#" class="dashboard-status-creator">{{ $blurb->owner->username }}</a>
                    </div>
                    <div class="cell small-9 medium-10">
                        <div class="dashboard-status-content">{{ $blurb->content }}</div>
                        <div class="dashboard-status-time"><i class="icon icon-time-ago"></i> {{ $blurb->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        @endforeach
        @if($posts->hasPages())
            <div class="container" style="border:none!important;">
                {{ $posts->onEachSide(1)->links('vendor.pagination.default') }}
            </div>
        @endif
    @endif
</div>
