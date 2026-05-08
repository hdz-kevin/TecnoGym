<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Products extends Component
{
    use WithPagination, WithoutUrlPagination;

    /**
     * Get all products
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function products()
    {
        return Product::paginate(10);
    }

    /**
     * Get the total number of products
     *
     * @return int
     */
    #[Computed]
    public function totalProducts()
    {
        return Product::count();
    }

    /**
     * Get the number of active products
     *
     * @return int
     */
    #[Computed]
    public function activeProducts()
    {
        return Product::where('is_active', true)->count();
    }

    /**
     * Get the number of inactive products
     *
     * @return int
     */
    #[Computed]
    public function inactiveProducts()
    {
        return Product::where('is_active', false)->count();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.products.products');
    }
}
