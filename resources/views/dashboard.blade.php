<x-app-layout>
    <x-slot name="navigation"></x-slot>
    <x-slot name="title">Dashboard</x-slot>
    <body class="dashboard-page">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-3">
                <div class="container dashboard-avatar-container mb-25">
                    <img class="dashboard-avatar" src="{{ auth()->user()->render() }}">
                        </div>
                        <div class="dashboard-header">Updates</div>
                        <div class="container dashboard-blog-container">
                            <div class="dashboard-blog-post">
                                <a href="#" class="blog-post-title" target="_blank">##########</a>
                                <div class="blog-post-body">################## ### ####### ### ##### ##### ## ## ####</div>
                            </div>
                        </div>
                        <div class="push-25 show-for-small-only"></div>
                    </div>
                    <div class="cell medium-9">
                        <div class="container mb-25">
                            <livewire:user.post-blurb />
                        </div>
                        <div class="dashboard-header">Feed</div>
                        <div class="container dashboard-feed-container">
                            <livewire:user.user-feed />
                        </div>
                        <div class="push-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>
