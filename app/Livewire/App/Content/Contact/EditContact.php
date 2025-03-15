<?php

namespace App\Livewire\App\Content\Contact;

use App\Models\ContactDetails;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageContent;
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

    public $page;
    public $contents;
    public $medias;
    public $contact_details;

    public function render()
    {
        $this->page = Page::whereUrl('/contact')->first();
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
        $this->contact_details = $this->page->contents->where('key', 'phone_number');
        
        return view('livewire.app.content.contact.edit-contact');
    }
}
