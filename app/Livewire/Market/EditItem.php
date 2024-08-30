<?php

namespace App\Livewire\Market;

use App\Models\Item;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class EditItem extends Component
{
    public Item $item;
    public $name;
    public $desc;
    public $bucks;
    public $bits;
    public $saleStatus;

    protected $rules = [
        'name' => 'required|string|min:3|max:64',
        'desc' => 'nullable|string|min:3|max:2048',
        'bucks' => 'required|integer|min:0|max:999999999',
        'bits' => 'required|integer|min:0|max:999999999',
        'saleStatus' => 'required|string|in:on,off',
    ];

    public function mount(Item $item)
    {
        $this->item = $item;
        $this->name = $item->name;
        $this->desc = $item->desc;
        $this->bits = $item->coins;
        $this->bucks = $item->cash;
        $this->saleStatus = ($item->cash > 0 || $item->coins > 0) ? 'on' : 'off';
    }

    public function updatedSaleStatus($value)
    {
        if ($value === 'off') {
            $this->bucks = 0;
            $this->bits = 0;
        }
    }

    public function submit()
    {
        $this->validate();

        if ($this->saleStatus === 'off') {
            $this->bucks = 0;
            $this->bits = 0;
        }

        $lockKey = auth()->user()->id.':market:edit';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are editing too fast. Please wait a few seconds before editing again.');
        }

        $this->item->update([
            'name' => $this->name,
            'desc' => $this->desc,
            'cash' => $this->bucks,
            'coins' => $this->bits,
        ]);

        $this->dispatch('toast:success', 'Item updated successfully.');
    }

    public function render()
    {
        return view('livewire.market.edit-item');
    }
}
