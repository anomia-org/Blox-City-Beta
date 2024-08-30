<?php

namespace App\Livewire\Market;

use App\Models\Item;
use Livewire\Component;

class Suggested extends Component
{
    public Item $item;

    public function mount(Item $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        $suggestions = Item::where([['id', '!=', $this->item->id], ['type', '=', $this->item->type], ['pending', '=', '0'], ['special', '=', '0']])
            ->where(function ($query) {
                $query->where('cash', '!=', 0)
                    ->orWhere('coins', '!=', 0);
            })
            ->inRandomOrder()->take(4)
            ->get();

        return view('livewire.market.suggested', [
            'suggestions' => $suggestions,
        ]);
    }
}
