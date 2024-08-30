<?php

namespace App\Livewire\Market;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class ShowItem extends Component
{
    public Item $item;
    public $likesCount;
    public $isLiked;

    public function mount(Item $item)
    {
        $this->item = $item;
        $this->likesCount = $item->likes()->count();
        $this->isLiked = $item->likes()->where('user_id', auth()->id())->exists();
    }

    public function toggleLike()
    {
        $lockKey = auth()->user()->id.':market:like';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 3);

        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are liking too fast. Please wait a few seconds before liking again.');
        }

        if ($this->isLiked) {
            $this->item->likes()->where('user_id', auth()->user()->id)->delete();
            $this->isLiked = false;
            $this->likesCount--;
            return $this->dispatch('toast:success', 'You have unliked this item.');
        } else {
            $this->item->likes()->create(['user_id' => auth()->user()->id, 'target_type' => 1]);
            $this->isLiked = true;
            $this->likesCount++;
            return $this->dispatch('toast:success', 'You have liked this item.');
        }
    }

    public function buyItem($method)
    {

        $release = Carbon::now()->addDay();

        $lockKey = auth()->user()->id.':market:buy';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are purchasing too fast. Please wait a few seconds before purchasing again.');
        }

        if ($this->item->pending > 0) {
            return $this->dispatch('toast:error', 'This item is not yet approved. Please try again later.');
        }

        if (auth()->user()->owns($this->item)) {
            return $this->dispatch('toast:error', 'You already own this item!');
        }

        if ($this->item->owner->id == auth()->user()->id) {
            return $this->dispatch('toast:error', 'You cannot purchase your own item.');
        }

        if ($this->item->stock() == 0) {
            return $this->dispatch('toast:error', 'This item is out of stock.');
        }


        DB::beginTransaction();
        try {
            switch ($method) {
                case 1: // Buy with Cash
                    if($this->item->cash == 0) {
                        return $this->dispatch('toast:error', 'This item is not for sale.');
                        break;
                    }

                    if (auth()->user()->bucks < $this->item->cash) {
                        return $this->dispatch('toast:error', 'Insufficient cash.');
                        break;
                    }
                    $cashTax = $this->item->cash * $this->item->owner->salesTax();
                    $cash = $this->item->cash - $cashTax;

                    // log purchase
                    Transaction::create([
                        'user_id' => auth()->user()->id,
                        'source_id' => $this->item->id,
                        'source_user' => $this->item->owner->id,
                        'source_type' => '1',
                        'type' => '1',
                        'cash' => $this->item->cash,
                    ]);
                    // log sale
                    Transaction::create([
                        'user_id' => $this->item->owner->id,
                        'source_id' => $this->item->id,
                        'source_user' => auth()->user()->id,
                        'source_type' => '1',
                        'type' => '2',
                        'cash' => $cash,
                        'release_at' => $release,
                    ]);
                    
                    auth()->user()->revoke_currency($this->item->cash, 1);
                    break;

                case 2: // Buy with Coins
                    if($this->item->coins == 0) {
                        return $this->dispatch('toast:error', 'This item is not for sale.');
                        break;
                    }

                    if (auth()->user()->bits < $this->item->coins) {
                        return $this->dispatch('toast:error', 'Insufficient coins.');
                        break;
                    }
                    $coinsTax = $this->item->coins * $this->item->owner->salesTax();
                    $coins = $this->item->coins - $coinsTax;

                    // log purchase
                    Transaction::create([
                        'user_id' => auth()->user()->id,
                        'source_id' => $this->item->id,
                        'source_user' => $this->item->owner->id,
                        'source_type' => '1',
                        'type' => '1',
                        'coins' => $this->item->coins,
                    ]);
                    // log sale
                    Transaction::create([
                        'user_id' => $this->item->owner->id,
                        'source_id' => $this->item->id,
                        'source_user' => auth()->user()->id,
                        'source_type' => '1',
                        'type' => '2',
                        'coins' => $coins,
                        'release_at' => $release,
                    ]);

                    auth()->user()->revoke_currency($this->item->coins, 2);
                    break;

                case 3: // Get for Free
                    if($this->item->coins == -1 && $this->item->cash == -1)
                    {
                        // log purchase
                        Transaction::create([
                            'user_id' => auth()->user()->id,
                            'source_id' => $this->item->id,
                            'source_user' => $this->item->owner->id,
                            'source_type' => '1',
                            'type' => '1',
                        ]);
                        // log sale
                        Transaction::create([
                            'user_id' => $this->item->owner->id,
                            'source_id' => $this->item->id,
                            'source_user' => auth()->user()->id,
                            'source_type' => '1',
                            'type' => '2',
                        ]);
                        break;
                    } else {
                        return $this->dispatch('toast:error', 'Invalid purchase method.');
                    }
                    break;

                default:
                    return $this->dispatch('toast:error', 'Invalid purchase method.');
                    break;
            }

            Inventory::create([
                'user_id' => auth()->user()->id,
                'item_id' => $this->item->id,
                'type' => $this->item->type,
                'special' => $this->item->special,
                'collection_number' => $this->generateSerial(),
            ]);
            
            DB::commit();
            return $this->dispatch('toast:success', 'Item purchased successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return $this->dispatch('toast:error', 'An error occurred while purchasing this item. Please try again.');
        }
    }

    private function generateSerial(): string
    {
        return bin2hex(random_bytes(5));
    }

    public function render()
    {
        return view('livewire.market.show-item');
    }
}
