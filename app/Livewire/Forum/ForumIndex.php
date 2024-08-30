<?php

namespace App\Livewire\Forum;

use App\Models\Category;
use App\Models\Topic;
use Livewire\Component;

class ForumIndex extends Component
{
    public function render()
    {
        $categories = Category::orderBy('id')->get();
        $topics = Topic::orderBy('id')->get();

        return view('livewire.forum.forum-index', [
            'categories' => $categories,
            'topics' => $topics
        ]);
    }
}
