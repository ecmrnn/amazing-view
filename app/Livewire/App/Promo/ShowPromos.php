<?php

namespace App\Livewire\App\Promo;

use App\Models\Promo;
use Livewire\Component;

class ShowPromos extends Component
{
    public $promos;

    public function render()
    {
        $this->promos = Promo::all();

        return view('livewire.app.promo.show-promos');
    }
}
