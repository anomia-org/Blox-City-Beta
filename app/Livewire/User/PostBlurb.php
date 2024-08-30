<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Blurb;
use Illuminate\Support\Facades\Redis;

class PostBlurb extends Component
{
    public $text;

    protected $rules = [
        'text' => 'required|string|min:6|max:255',
    ];

    public function submit()
    {
        $this->validate();
        $lockKey = auth()->user()->id.':post:blurb';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are posting too fast. Please wait a few seconds before posting again.');
        }
        DB::beginTransaction();
        try {
            $blurb = new Blurb();
            $blurb->user_id = auth()->user()->id;
            $blurb->content = $this->text;
            $blurb->save();
            DB::commit();
            $this->text = '';
            $this->dispatch('blurbAdded');
            return $this->dispatch('toast:success', 'Blurb posted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return $this->dispatch('toast:error', 'An error occurred while posting your blurb. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.post-blurb');
    }
}
