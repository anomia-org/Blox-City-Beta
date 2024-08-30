<?php

namespace App\Livewire\Forum;

use App\Models\Thread;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ForumSearch extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public $query;

    protected $rules = [
        'query' => 'required|string|min:3|max:50',
    ];

    public function mount()
    {
        $this->query = '';
    }

    public function search()
    {
        $this->validate();
    }

    public function render()
    {
        if($this->query == null || $this->query == '')
        {
            $threads = Thread::latest()->paginate(10);
        } else {
            $threads = Thread::where('title', 'like', '%'.$this->query.'%')
                ->orWhere('body', 'like', '%'.$this->query.'%')
                ->latest()
                ->paginate(10);
        }
        
        return view('livewire.forum.forum-search', [
            'threads' => $threads,
        ]);
    }
}
