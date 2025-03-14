<?php

namespace App\Livewire\App\Content\Home;

use App\Models\FeaturedService;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\Testimonial;
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
        'history-edited' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    #[Validate] public $heading;
    #[Validate] public $subheading;
    public $featured_services;
    public $testimonials;
    public $contents;
    public $medias;
    public $page;

    public function rules() {
        return [
            'heading' => 'required',
            'subheading' => 'required',
        ];
    }

    public function submit() {
        $this->validate([
            'heading' => $this->rules()['heading'],
            'subheading' => $this->rules()['subheading'],
            'history' => $this->rules()['history'],
        ]);

        $heading = PageContent::whereKey('home_heading')->first();
        $heading->value = $this->heading;
        $heading->save();
        
        $subheading = PageContent::whereKey('home_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();
        
        $this->toast('Success!', 'success', 'Changed made saved');
    }

    public function render()
    {
        $this->page = Page::whereUrl('/')->first();
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
        $this->featured_services = FeaturedService::all();
        $this->testimonials = Testimonial::all();
        
        return view('livewire.app.content.home.edit-home');
    }
}
