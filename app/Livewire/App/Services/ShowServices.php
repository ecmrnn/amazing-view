<?php

namespace App\Livewire\App\Services;

use App\Models\AdditionalServices;
use Livewire\Component;

class ShowServices extends Component
{
    protected $listeners = [
        'service-created' => '$refresh',
        'service-updated' => '$refresh',
        'service-deleted' => '$refresh',
        'service-status-changed' => '$refresh',
    ];

    public $services;

    public function render()
    {
        $this->services = AdditionalServices::count();

        return view('livewire.app.services.show-services');
    }
}
