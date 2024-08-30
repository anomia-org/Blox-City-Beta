<?php

namespace App\Livewire\Market;

use Livewire\Component;

class SearchBox extends Component
{
    public $query = '';
    public $category = '';

    public function updatedQuery()
    {
        $this->dispatch('updateSearch', $this->query, $this->category);
    }

    public function updatedCategory()
    {
        $this->dispatch('updateSearch', $this->query, $this->category);
    }

    public function search()
    {
        $this->dispatch('updateSearch', $this->query, $this->category);
    }

    public function render()
    {
        return view('livewire.market.search-box');
    }
}
