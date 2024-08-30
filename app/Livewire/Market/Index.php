<?php

namespace App\Livewire\Market;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $query = '';
    public $category = '';

    protected $listeners = ['updateSearch' => 'updateSearch'];

    public function updateSearch($query, $category)
    {
        $this->query = $query;
        $this->category = $category;
    }
    public function render()
    {
        $items = Item::query()
            ->when($this->query, function ($q) {
                $q->where('name', 'like', '%' . $this->query . '%');
            })
            ->when($this->category, function ($q) {
                $q->where('type', $this->category);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.market.index', [
            'items' => $items,
        ]);
    }
}
