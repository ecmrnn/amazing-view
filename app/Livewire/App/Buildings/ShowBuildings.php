<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
use Livewire\Component;

class ShowBuildings extends Component
{
    protected $listeners = [
        'building-created' => '$refresh',
        'building-edited' => '$refresh',
        'building-deleted' => '$refresh',
    ];

    public $buildings;

    public function render()
    {
        $this->buildings = Building::withCount('rooms')->get();

        return view('livewire.app.buildings.show-buildings');
    }
}
