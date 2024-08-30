<?php

namespace App\Livewire\Market;

use App\Models\Item;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ItemPosts extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public Item $item;
    public $text;

    protected $rules = [
        'text' => 'required|string|min:6|max:255',
    ];

    public function toggleLike($commentId)
    {
        $lockKey = auth()->user()->id.':post:like';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 3);

        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are liking too fast. Please wait a few seconds before liking again.');
        }

        $comment = Post::find($commentId);

        if(!$comment) {
            return $this->dispatch('toast:error', 'Comment not found.');
        }

        if ($comment->likes()->where('user_id', auth()->user()->id)->exists()) {
            $comment->likes()->where('user_id', auth()->user()->id)->delete();
            return $this->dispatch('toast:success', 'You have unliked this comment.');
        } else {
            $comment->likes()->create(['user_id' => auth()->user()->id, 'target_type' => 4]);
            return $this->dispatch('toast:success', 'You have liked this comment.');
        }
    }

    public function submit()
    {
        $this->validate();

        $lockKey = auth()->user()->id.':post:comment';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are posting too fast. Please wait a few seconds before posting again.');
        }

        DB::beginTransaction();
        try {

            $this->item->comments()->create([
                'user_id' => auth()->user()->id,
                'text' => $this->text,
                'target_type' => 1,
            ]);

            DB::commit();
            $this->text = '';
            $this->dispatch('commentAdded');
            return $this->dispatch('toast:success', 'Comment posted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return $this->dispatch('toast:error', 'An error occurred while posting your comment. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.market.item-posts', [
            'comments' => $this->item->comments()->latest()->paginate(10),
        ]);
    }
}
