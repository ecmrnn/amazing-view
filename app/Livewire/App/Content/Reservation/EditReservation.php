<?php

namespace App\Livewire\App\Content\Reservation;

use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditReservation extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'hero-edited' => '$refresh',
    ];

    public $pages;
    public $contents;
    public $medias;

    public function render()
    {
        $this->pages = Page::whereUrl('/reservation')
            ->orWhere('url', '/function-hall')
            ->get();
            
        $this->contents = PageContent::whereIn('page_id', $this->pages->pluck('id'))->pluck('value', 'key');
        $this->medias = MediaFile::whereIn('page_id', $this->pages->pluck('id'))->pluck('path', 'key');

        return view('livewire.app.content.reservation.edit-reservation');
    }
}
