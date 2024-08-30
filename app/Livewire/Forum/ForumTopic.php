<?php

namespace App\Livewire\Forum;

use App\Models\Topic;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ForumTopic extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Topic $topic;
    //public $threads;

    public function mount(Topic $topic)
    {
        $this->topic = $topic;
        //$this->threads = $topic->threads()->orderBy('stuck', 'DESC')->orderBy('pinned', 'DESC')->orderBy('last_reply', 'DESC');
    }

    public function render()
    {
        //return dd($this->topic);
        return view('livewire.forum.forum-topic', [
            'topic' => $this->topic,
            'threads' => $this->topic->threads()->orderBy('stuck', 'DESC')->orderBy('pinned', 'DESC')->orderBy('last_reply', 'DESC')->paginate(10),
        ]);
    }
}
