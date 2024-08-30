<?php

namespace App\Livewire\Forum;

use App\Models\Thread;
use App\Models\View;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ForumThread extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Thread $thread;

    public function mount(Thread $thread)
    {
        $this->thread = $thread;

        if(auth()->check())
        {
            $checkView = View::where('target_type', 2)->where('target_id', $thread->id)->where('ip', $_SERVER['REMOTE_ADDR'])->where('user_id', auth()->id());
            if(!$checkView->exists()) {
                $thread->increment('views');
                View::insert(['target_type' => 2, 'target_id' => $thread->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => auth()->id()]);
            }
        }
    }

    public function toggleLike($likeableId, $likeableType)
    {
        $lockKey = auth()->user()->id.':forum:like';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 3);

        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are liking too fast. Please wait a few seconds before liking again.');
        }

        switch ($likeableType) {
            case '2':
                if(auth()->user()->likes()->where('target_type', 2)->where('target_id', $likeableId)->exists())
                {
                    auth()->user()->likes()->where('target_type', 2)->where('target_id', $likeableId)->delete();
                    return $this->dispatch('toast:success', 'You have unfavorited this thread.');
                } else {
                    auth()->user()->likes()->create(['target_type' => 2, 'target_id' => $likeableId]);
                    return $this->dispatch('toast:success', 'You have favorited this thread.');
                }
                break;
            case '3':
                if(auth()->user()->likes()->where('target_type', 3)->where('target_id', $likeableId)->exists())
                {
                    auth()->user()->likes()->where('target_type', 3)->where('target_id', $likeableId)->delete();
                    return $this->dispatch('toast:success', 'You have unfavorited this reply.');
                } else {
                    auth()->user()->likes()->create(['target_type' => 3, 'target_id' => $likeableId]);
                    return $this->dispatch('toast:success', 'You have favorited this reply.');
                }
                break;
        }

    }

    public function render()
    {
        return view('livewire.forum.forum-thread', [
            'thread' => $this->thread,
            'category' => $this->thread->topic->category,
            'topic' => $this->thread->topic,
            'replies' => $this->thread->replies()->orderBy('created_at', 'ASC')->paginate(8),
        ]);
    }
}
