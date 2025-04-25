<?php

namespace App\Livewire\App\Amenity;

use App\Models\Amenity;
use Livewire\Component;

class ShowAmenities extends Component
{
    public $amenity_count;

    public function render()
    {
        $this->amenity_count = Amenity::count();
        
        return view('livewire.app.amenity.show-amenities');
    }
}
