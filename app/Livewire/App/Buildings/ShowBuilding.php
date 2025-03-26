<?php

namespace App\Livewire\App\Buildings;

use Livewire\Component;

class ShowBuilding extends Component
{
    public $building;
    
    public function render()
    {
        return view('livewire.app.buildings.show-building');
    }
}
