<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Component used to create a new sale.
 * Contains all the logic for adding products to the cart, updating quantities, 
 * removing items, and saving the sale.
 */
class CreateSale extends Component
{
    /**
     * Modal visibility state
     */
    public bool $showModal = false;

    /**
     * Product search input value
     */
    public string $productSearch = '';

    /**
     * Cart items for the current sale.
     *
     * Each item is an associative array with the following keys:
     * - product_id (int)
     * - product_name (string)
     * - product_price (float)
     * - quantity (int)
     * - subtotal (float)
     * - product_stock (int|null)
     *
     * @var array<int, array<string, mixed>>
     */
    public array $cart = [];

    /**
     * Open the modal.
     */
    #[On('open-create-sale-modal')]
    public function openModal(): void
    {
        $this->showModal = true;
        $this->dispatch('disable-scroll');
    }

    /**
     * Search active products by name for the autocomplete dropdown.
     *
     * @return \Illuminate\Support\Collection<Product>
     */
    #[Computed]
    public function productResults()
    {
        if (empty($this->productSearch)) {
            return collect([]);
        }

        return Product::where('is_active', true)
                       ->where('name', 'like', '%' . $this->productSearch . '%')
                       ->orderBy('name')
                       ->limit(10)
                       ->get();
    }

    /**
     * Add a product to the cart with an initial quantity of 1.
     * If the product already exists in the cart, increment its quantity by 1.
     */
    public function selectProduct(Product $product): void
    {
        $existingIndex = collect($this->cart)->search(
            fn ($item) => $item['product_id'] === $product->id
        );

        if ($existingIndex !== false) {
            $this->cart[$existingIndex]['quantity']++;
            $this->cart[$existingIndex]['subtotal'] = ProductSale::subtotal(
                $this->cart[$existingIndex]['product_price'],
                $this->cart[$existingIndex]['quantity']
            );
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
                'product_stock' => $product->stock,
            ];
        }

        $this->productSearch = '';
    }

    /**
     * Update the quantity for a cart item and recalculate its subtotal.
     */
    public function updateQuantity(int $index, int $quantity): void
    {
        if (!isset($this->cart[$index])) {
            return;
        }

        $quantity = max(1, $quantity);

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['subtotal'] = ProductSale::subtotal(
            $this->cart[$index]['product_price'],
            $quantity
        );
    }

    /**
     * Remove an item from the cart by its index.
     */
    public function removeItem(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    /**
     * Get the total number of products in the cart
     */
    #[Computed]
    public function totalProducts(): int
    {
        return collect($this->cart)->pluck('quantity')->sum();
    }

    /**
     * Computed total for the current cart.
     */
    #[Computed]
    public function saleTotal(): float
    {
        return ProductSale::total($this->cart);
    }

    /**
     * Validate stock, persist the Sale with its ProductSale lines,
     * and decrement stock for products that track it.
     */
    public function saveSale(): void
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Agrega al menos un producto a la venta.');
            return;
        }

        // Validate stock availability for each item
        foreach ($this->cart as $index => $item) {
            if ($item['product_stock'] !== null && $item['quantity'] > $item['product_stock']) {
                $this->addError(
                    "cart.{$index}.quantity",
                    "Stock insuficiente para \"{$item['product_name']}\""
                );
                return;
            }
        }

        DB::transaction(function () {
            $sale = Sale::create([
                'total' => $this->saleTotal,
                'sold_at' => now(),
            ]);

            foreach ($this->cart as $item) {
                ProductSale::create([
                    'sale_id'       => $sale->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $item['product_name'],
                    'product_price' => $item['product_price'],
                    'quantity'      => $item['quantity'],
                    'subtotal'      => $item['subtotal'],
                ]);

                // Decrement stock if product has stock
                if ($item['product_stock'] !== null) {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }
        });

        $this->closeModal();
        $this->dispatch('sale-created', message: 'Venta registrada exitosamente');
    }

    /**
     * Close the modal and reset form state.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('enable-scroll');
    }

    /**
     * Reset form fields and validation errors.
     */
    private function resetForm(): void
    {
        $this->reset(['productSearch', 'cart']);
        $this->resetValidation();
    }

    /**
     * Render the component view.
     */
    public function render()
    {
        return view('livewire.store.create-sale');
    }
}
