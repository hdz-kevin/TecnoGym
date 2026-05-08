<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Products extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Form properties
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $name = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('numeric', message: 'El precio debe ser un número')]
    #[Rule('min:0.01', message: 'El precio debe ser mayor a 0')]
    public $price = '';

    #[Rule('nullable')]
    #[Rule('string')]
    #[Rule('max:1024', message: 'La descripción es muy larga')]
    public $description = null;

    #[Rule('nullable')]
    #[Rule('integer', message: 'El stock debe ser un número entero')]
    #[Rule('min:0', message: 'El stock no puede ser negativo')]
    public $stock = null;

    /** Modal state */
    public $showFormModal = false;

    /**
     * Get all products
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function products()
    {
        return Product::paginate(15);
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
     * Show the create product modal.
     */
    public function create()
    {
        $this->showFormModal = true;
    }

    /**
     * Save a new product.
     */
    public function saveProduct()
    {
        $validated = $this->validate();

        Product::create($validated);

        $this->closeModal();
        session()->flash('message', 'Producto creado exitosamente');
    }

    /**
     * Close the modal and reset form state.
     */
    public function closeModal()
    {
        $this->showFormModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    /**
     * Reset the form fields to their default state.
     */
    private function resetForm()
    {
        $this->name = '';
        $this->price = '';
        $this->description = null;
        $this->stock = null;
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
