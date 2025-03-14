<?php

namespace App\Livewire\App\Content\About;

use App\Enums\MilestoneStatus;
use App\Models\MediaFile;
use App\Models\Milestone;
use App\Models\Page;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditAbout extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'milestone-added' => '$refresh',
        'milestone-edited' => '$refresh',
        'milestone-hidden' => '$refresh',
        'milestone-deactivated' => '$refresh',
        'milestone-activated' => '$refresh',
        'milestone-deleted' => '$refresh',
        'history-edited' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    public $page;
    public $contents;
    public $medias;
    public $milestones;

    public $map = array(
        'option' => [
            'center' => [
                'lat' => 14.442312,
                'lng' => 121.396931
            ],
            'zoom' => 14,
            'zoomControl' => true,
            'minZoom' => 10,
            'maxZoom' => 18,
        ],
        'marker' => [
            [
                'position' => [
                    'lat' => 14.442312,
                    'lng' => 121.396931
                ],
                'draggable' => false,
            ]
        ]
    );

    public function render()
    {
        $this->page = Page::whereUrl('/about')->first();
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
        $this->milestones = Milestone::all();
        
        return view('livewire.app.content.about.edit-about');
    }
}
