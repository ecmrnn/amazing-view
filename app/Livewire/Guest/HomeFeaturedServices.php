<?php

namespace App\Livewire\Guest;

use App\Enums\FeaturedServiceStatus;
use App\Models\FeaturedService;
use Livewire\Component;

class HomeFeaturedServices extends Component
{
    public $max;
    public $featured_services;

    public function render()
    {
        $this->featured_services = FeaturedService::whereStatus(FeaturedServiceStatus::ACTIVE)->get();
        $this->max = FeaturedService::whereStatus(FeaturedServiceStatus::ACTIVE)->count();
        
        return view('livewire.guest.home-featured-services');
    }
}
