<?php

namespace App\Livewire\App\Content\Global;

use App\Models\Settings;
use App\Traits\DispatchesToast;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditGlobal extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'settings-updated' => '$refresh',
    ];

    public $settings;
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
            'site_phone' => 'required|digits:11|starts_with:09',
            'site_email' => 'required|email',
        ];
    }

    public function saveBranding() {
        $validated = $this->validate([
            'site_title' => $this->rules()['site_title'],
            'site_tagline' => $this->rules()['site_tagline'],
            'site_logo' => $this->rules()['site_logo'],
        ]);

        DB::transaction(function () use ($validated) {
            Settings::where('key', 'site_title')->first()->update([
                'value' => $validated['site_title']
            ]);
    
            Settings::where('key', 'site_tagline')->first()->update([
                'value' => $validated['site_tagline']
            ]);
            
            if (!empty($validated['site_logo'])) {
                $logo = Settings::where('key', 'site_logo')->first();

                // Delete the original logo first
                if (!empty($logo->value)) {
                    Storage::disk('public')->delete($logo->value);    
                }

                $logo->update([
                    'value' => $validated['site_logo']->store('global', 'public')
                ]);
            }
        });

        $this->dispatch('pond-reset');
        $this->dispatch('settings-updated');
        $this->toast('Success!', description: 'Branding and Visual Identity updated!');
    }

    public function saveContact() {
        $validated = $this->validate([
            'site_phone' => $this->rules()['site_phone'],
            'site_email' => $this->rules()['site_email'],
        ]);

        DB::transaction(function () use ($validated) {
            Settings::where('key', 'site_phone')->first()->update([
                'value' => $validated['site_phone']
            ]); 

            Settings::where('key', 'site_email')->first()->update([
                'value' => $validated['site_email']
            ]); 
        });

        $this->dispatch('settings-updated');
        $this->toast('Success!', description: 'Contact Information updated!');
    }

    public function render()
    {
        $this->settings = Settings::pluck('value', 'key');
        $this->site_title = $this->settings['site_title'];
        $this->site_tagline = $this->settings['site_tagline'];
        $this->site_phone = $this->settings['site_phone'];
        $this->site_email = $this->settings['site_email'];
        
        return view('livewire.app.content.global.edit-global');
    }
}
