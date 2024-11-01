<?php

namespace App\Livewire\App\Content\Home;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Page;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditHome extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'service-added' => '$refresh',
        'service-edited' => '$refresh',
        'service-hidden' => '$refresh',
        'service-deleted' => '$refresh',
    ];

    #[Validate] public $heading;
    #[Validate] public $subheading;
    #[Validate] public $history;
    #[Validate] public $history_image;
    #[Validate] public $featured_services;
    public $feature_count;

    public function rules() {
        return [
            'heading' => 'required',
            'subheading' => 'required',
            'history' => 'required',
        ];
    }

    public function mount() {
        $this->heading = html_entity_decode(Content::whereName('home_heading')->pluck('value')->first());
        $this->subheading = html_entity_decode(Content::whereName('home_subheading')->pluck('value')->first());
        $this->history_image = Content::whereName('about_history_image')->pluck('value')->first();
        $this->history = Content::whereName('about_history')->pluck('long_value')->first();
    }

    public function submit() {
        $this->validate([
            'heading' => $this->rules()['heading'],
            'subheading' => $this->rules()['subheading'],
            'history' => $this->rules()['history'],
        ]);

        $heading = Content::whereName('home_heading')->first();
        $heading->value = $this->heading;
        $heading->save();
        
        $subheading = Content::whereName('home_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();

        $history = Content::whereName('about_history')->first();
        $history->long_value = $this->history;
        $history->save();
        
        $this->toast('Success!', 'success', 'Changed made saved');
    }

    public function render()
    {
        $this->featured_services = FeaturedService::whereStatus(FeaturedService::STATUS_ACTIVE)->get();
        $this->feature_count = $this->featured_services->count();
        $page = Page::whereTitle('Home')->first();
        
        return view('livewire.app.content.home.edit-home', [
            'page' => $page,
        ]);
    }
}
