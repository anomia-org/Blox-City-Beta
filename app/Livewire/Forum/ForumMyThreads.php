<?php

namespace App\Livewire\Forum;

use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ForumMyThreads extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $threads = auth()->user()->threads()->latest();

        return view('livewire.forum.forum-my-threads', [
            'threads' => $threads->paginate(10),
        ]);
    }
}
