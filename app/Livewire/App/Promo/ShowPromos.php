<?php

namespace App\Livewire\App\Promo;

use App\Models\Promo;
use Livewire\Component;

class ShowPromos extends Component
{
    public $promos;

    protected $listeners = [
        'promo-created' => '$refresh',
        'promo-updated' => '$refresh',
        'promo-deleted' => '$refresh',
    ];

    public function render()
    {
        $this->promos = Promo::all();

        return view('livewire.app.promo.show-promos');
    }
}
