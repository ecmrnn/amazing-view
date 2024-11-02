<?php

namespace App\Livewire\App\Content\About;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Milestone;
use App\Models\Page;
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
        'milestone-deleted' => '$refresh',
        'history-edited' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    public $heading;
    public $subheading;
    public $history;
    public $milestones;
    public $history_image;
    public $about_hero_image;

    public function render()
    {
        $this->about_hero_image = Content::whereName('about_hero_image')->pluck('value')->first();
        $this->history_image = Content::whereName('about_history_image')->pluck('value')->first();
        $this->history = Content::whereName('about_history')->pluck('long_value')->first();
        $this->heading = html_entity_decode(Content::whereName('about_heading')->pluck('value')->first());
        $this->subheading = html_entity_decode(Content::whereName('about_subheading')->pluck('value')->first());
        $this->milestones = Milestone::all();
        
        $page = Page::whereTitle('About')->first();
        
        return view('livewire.app.content.about.edit-about', [
            'page' => $page,
        ]);
    }
}
