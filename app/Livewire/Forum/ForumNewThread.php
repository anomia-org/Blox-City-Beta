<?php

namespace App\Livewire\Forum;

use App\Models\Thread;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class ForumNewThread extends Component
{
    public Topic $topic;
    public $title;
    public $body;

    protected $rules = [
        'title' => 'required|string|min:3|max:50',
        'body' => 'required|string|min:3|max:3000',
    ];

    public function submit()
    {
        $this->validate();
        $lockKey = auth()->user()->id.':forum:post';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are posting too fast. Please wait a few seconds before posting again.');
        }

        DB::beginTransaction();
        try {
            $thread = new Thread();
            $thread->user_id = auth()->user()->id;
            $thread->topic_id = $this->topic->id;
            $thread->title = $this->title;
            $thread->body = $this->body;
            $thread->last_reply = Carbon::now();
            $thread->save();
            DB::commit();
            $this->title = '';
            $this->body = '';
            // Optionally, you can add a success message or any other response
            return redirect()->route('forum.thread', $thread)->with('success', 'Thread posted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return $this->dispatch('toast:error', 'An error occurred while posting your thread. Please try again.');
        }
    }

    public function mount(Topic $topic)
    {
        $this->topic = $topic;

        if($this->topic->admin > 0 && auth()->user()->power <= 0)
        {
            return redirect()->route('forum.topic', $this->topic)->with('error', 'You do not have permission to create a thread in this topic.');
        }
    }
    public function render()
    {
        return view('livewire.forum.forum-new-thread', [
            'topic' => $this->topic,
        ]);
    }
}
