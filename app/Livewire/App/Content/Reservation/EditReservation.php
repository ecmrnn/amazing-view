<?php

namespace App\Livewire\App\Content\Reservation;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Page;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditReservation extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'service-added' => '$refresh',
        'service-edited' => '$refresh',
        'service-hidden' => '$refresh',
        'service-deleted' => '$refresh',
        'history-edited' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    public $heading;
    public $subheading;
    public $reservation_hero_image;


    public function render()
    {
        $this->reservation_hero_image = Content::whereName('reservation_hero_image')->pluck('value')->first();
        $this->heading = html_entity_decode(Content::whereName('reservation_heading')->pluck('value')->first());
        $this->subheading = html_entity_decode(Content::whereName('reservation_subheading')->pluck('value')->first());
        
        $page = Page::whereTitle('Reservation')->first();
        
        return view('livewire.app.content.reservation.edit-reservation', [
            'page' => $page,
        ]);
    }
}
