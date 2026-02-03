<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Precios')]
class Prices extends Component
{
    /**
     * Listen for notifications from child components
     *
     * @param string $message - Message to display
     * @param string $type - Type of flash message (default: message, error)
     * @return void
     */
    #[On('notify-changes')]
    public function notifyChanges(string $message, string $type = 'message')
    {
        session()->flash($type, $message);
    }

    /**
     * Render the component
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.prices');
    }
}
