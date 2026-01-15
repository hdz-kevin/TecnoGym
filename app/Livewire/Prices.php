<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Precios')]
class Prices extends Component
{
    public function render()
    {
        return view('livewire.prices');
    }
}
