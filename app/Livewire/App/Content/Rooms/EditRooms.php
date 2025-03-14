<?php

namespace App\Livewire\App\Content\Rooms;

use App\Models\Content;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditRooms extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'rooms-added' => '$refresh',
        'rooms-edited' => '$refresh',
        'rooms-hidden' => '$refresh',
        'rooms-deleted' => '$refresh',
        'room-type-deleted' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    public $heading;
    public $subheading;
    public $rooms_hero_image;
    public $room_types;

    public function render()
    {
        $this->rooms_hero_image = PageContent::where('key', 'rooms_hero_image')->pluck('value')->first();
        $this->heading = html_entity_decode(PageContent::where('key', 'rooms_heading')->pluck('value')->first());
        $this->subheading = html_entity_decode(PageContent::where('key', 'rooms_subheading')->pluck('value')->first());
        $this->room_types = RoomType::all();
        
        $page = Page::whereTitle('Rooms')->first();
        
        return view('livewire.app.content.rooms.edit-rooms', [
            'page' => $page,
        ]);
    }
}
