<?php

namespace App\Livewire\App\Content\Home;

use App\Models\FeaturedService;
use Livewire\Component;

class ShowFeaturedServices extends Component
{
    protected $listeners = [
        'service-added' => '$refresh',
        'service-edited' => '$refresh',
        'service-hidden' => '$refresh',
        'service-deleted' => '$refresh',
    ];
    
    public $featured_services;

    public function render()
    {
        $this->featured_services = FeaturedService::all();
        
        return view('livewire.app.content.home.show-featured-services');
    }
}
