<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShopCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'latest';

    // Reset pagination when filter fields change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }
    public function updatingSort() { $this->resetPage(); }

    public function selectCategory($cat)
    {
        $this->category = $cat;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::active();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->category)) {
            $query->where('category', $this->category);
        }

        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        $products = $query->paginate(9);
        $categories = Product::select('category')->distinct()->pluck('category');

        $this->dispatch('filter-updated');
        return view('livewire.shop-catalog', compact('products', 'categories'));
    }
}
