<?php

namespace App\Livewire\App\Content\Contact;

use App\Models\ContactDetails;
use App\Models\Content;
use App\Models\Milestone;
use App\Models\Page;
use App\Traits\DispatchesToast;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditContact extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'contact-added' => '$refresh',
        'contact-edited' => '$refresh',
        'contact-hidden' => '$refresh',
        'contact-deleted' => '$refresh',
        'history-edited' => '$refresh',
        'hero-edited' => '$refresh',
    ];

    public $heading;
    public $subheading;
    public $contact_hero_image;
    public $contact_details;

    public function render()
    {
        $this->contact_hero_image = Content::whereName('contact_hero_image')->pluck('value')->first();
        $this->heading = html_entity_decode(Content::whereName('contact_heading')->pluck('value')->first());
        $this->subheading = html_entity_decode(Content::whereName('contact_subheading')->pluck('value')->first());
        $this->contact_details = ContactDetails::whereName('phone_number')->get();
        
        $page = Page::whereTitle('Contact')->first();
        
        return view('livewire.app.content.contact.edit-contact', [
            'page' => $page,
        ]);
    }
}
