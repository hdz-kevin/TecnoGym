<?php

namespace App\Livewire\Store;

use App\Models\Sale;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Component used to display the details of a sale in a modal.
 * Shows the sale date, time, total and a table of the products sold.
 */
class SaleDetail extends Component
{
    /**
     * Modal visibility state
     */
    public bool $showModal = false;

    /**
     * The Sale model instance currently being viewed.
     *
     * @var Sale|null
     */
    public ?Sale $sale = null;

    /**
     * Opens the detail modal for a sale.
     * Eager loads the productSales relationship to display the product lines.
     *
     * @param Sale $sale
     * @return void
     */
    #[On('open-sale-detail-modal')]
    public function openModal(Sale $sale): void
    {
        $this->sale = $sale->load('productSales');
        $this->showModal = true;
        $this->dispatch('disable-scroll');
    }

    /**
     * Closes the modal and clears the component state.
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->sale = null;
        $this->dispatch('enable-scroll');
    }

    /**
     * Renders the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.store.sale-detail');
    }
}
