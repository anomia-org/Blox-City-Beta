<?php

namespace App\Livewire\Market;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemReseller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Pest\Plugins\Parallel\Handlers\Laravel;

class ItemResellers extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Item $item;
    public $price = '';
    public $serial = '';

    public function resell()
    {
        return $this->dispatch('toast:info', 'b4');
        $this->validate([
            'price' => 'required|numeric|min:1|max:999999999',
            'serial' => 'required|exists:inventories,id',
        ]);

        Log::info('hello');

        $lockKey = auth()->user()->id.':market:resell';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are reselling too fast. Please wait a few seconds before reselling again.');
        }

        $getItem = Inventory::where('id', '=', $this->serial)->first();

        if($getItem->item_id != $this->item->id)
        {
            return $this->dispatch('toast:error', 'Item ID mismatch! You cannot resell a collectible from a different collectible\'s page.');
        }

        // check ownership
        if(auth()->user()->owns($this->item) && $getItem != null && !$getItem->onsale())
        {
            DB::beginTransaction();
            try {

                ItemReseller::create([
                    'user_id' => auth()->user()->id,
                    'item_id' => $this->item->id,
                    'inventory_id' => $this->serial,
                    'price' => $this->price,
                ]);

                return $this->dispatch('toast:success', 'Resale offer posted successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                Redis::del($lockKey);
                return $this->dispatch('toast:error', 'An error occurred while reselling your collectible. Please try again.');
            }
        }
    }

    public function render()
    {
        $markets = $this->item->market()->paginate(5, '*', 'resellers');

        return view('livewire.market.item-resellers', [
            'markets' => $markets,
        ]);
    }
}
