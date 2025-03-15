<?php

namespace App\Livewire\App\Content\Global;

use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditGlobal extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $site_title;
    #[Validate] public $site_tagline;
    #[Validate] public $site_logo;
    #[Validate] public $site_phone;
    #[Validate] public $site_email;

    public function rules() {
        return [
            'site_title' => 'required',
            'site_tagline' => 'required',
            'site_logo' => 'nullable|image',
            'site_phone' => 'required|digits:11',
            'site_email' => 'required|email',
        ];
    }

    public function saveBranding() {
        $this->validate([
            'site_title' => $this->rules()['site_title'],
            'site_tagline' => $this->rules()['site_tagline'],
            'site_logo' => $this->rules()['site_logo'],
        ]);

        $this->toast('Success!', description: 'Branding and Visual Identity updated!');
    }

    public function saveContact() {
        $this->validate([
            'site_phone' => $this->rules()['site_email'],
            'site_email' => $this->rules()['site_email'],
        ]);

        $this->toast('Success!', description: 'Contact Information updated!');
    }

    public function render()
    {
        return view('livewire.app.content.global.edit-global');
    }
}
