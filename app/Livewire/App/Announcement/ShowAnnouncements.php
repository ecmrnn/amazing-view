<?php

namespace App\Livewire\App\Announcement;

use App\Models\Announcement;
use Livewire\Component;

class ShowAnnouncements extends Component
{
    protected $listeners = [
        'announcement-created' => '$refresh',
        'announcement-updated' => '$refresh',
        'announcement-disabled' => '$refresh',
        'announcement-enabled' => '$refresh',
        'announcement-deleted' => '$refresh',
    ];

    public $announcements;

    public function render()
    {
        $this->announcements = Announcement::all();

        return view('livewire.app.announcement.show-announcements');
    }
}
