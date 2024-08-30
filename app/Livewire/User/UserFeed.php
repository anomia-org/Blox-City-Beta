<?php

namespace App\Livewire\User;

use App\Models\Blurb;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserFeed extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['blurbAdded' => 'refreshUserFeed'];

    public function mount()
    {
        $this->refreshUserFeed();
    }

    public function refreshUserFeed()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Blurb::whereIn('user_id', auth()->user()->getFriends()->pluck('id')->push(auth()->user()->id))
                      ->orWhere('user_id', auth()->user()->id)
                      ->latest()
                      ->paginate(10);

        return view('livewire.user.user-feed', [
            'posts' => $posts
        ]);
    }
}
