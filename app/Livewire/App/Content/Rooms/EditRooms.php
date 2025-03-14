<?php

namespace App\Livewire\App\Content\Rooms;

use App\Models\Content;
use App\Models\MediaFile;
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

    public $page;
    public $contents;
    public $medias;
    public $room_types;

    public function render()
    {
        $this->page = Page::whereUrl('/rooms')->first();
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
        $this->room_types = RoomType::all();
        
        return view('livewire.app.content.rooms.edit-rooms');
    }
}
