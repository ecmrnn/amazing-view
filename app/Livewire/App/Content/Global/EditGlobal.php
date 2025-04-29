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
    /** 
     * Branding and Visual Identity
     */
    #[Validate] public $site_title;
    #[Validate] public $site_tagline;
    #[Validate] public $site_logo;

    /** 
     * Contact Information
     */
    #[Validate] public $site_phone;
    #[Validate] public $site_email;

    /** 
     * GCash Payment Information
     */
    #[Validate] public $site_gcash_phone;
    #[Validate] public $site_gcash_name;
    #[Validate] public $site_gcash_qr;

    /** 
     * Reservation Configuration
     */

    #[Validate] public $site_reservation_downpayment_percentage;
    
    public function rules() {
        return [
            'site_title' => 'required',
            'site_tagline' => 'required',
            'site_logo' => 'nullable|image|max:1024',

            'site_phone' => 'required|digits:11|starts_with:09',
            'site_email' => 'required|email',
            
            'site_gcash_phone' => 'required|digits:11|starts_with:09',
            'site_gcash_name' => 'required',
            'site_gcash_qr' => 'nullable|image|max:1024',

            'site_reservation_downpayment_percentage' => 'required|lte:.99|gte:.01',
        ];
    }

    public function mount() {
        $this->settings = Settings::pluck('value', 'key');

        $this->site_title = $this->settings['site_title'];
        $this->site_tagline = $this->settings['site_tagline'];

        $this->site_phone = $this->settings['site_phone'];
        $this->site_email = $this->settings['site_email'];
        
        $this->site_gcash_name = $this->settings['site_gcash_name'];
        $this->site_gcash_phone = $this->settings['site_gcash_phone'];
        $this->site_reservation_downpayment_percentage = $this->settings['site_reservation_downpayment_percentage'];
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

        $this->settings = Settings::pluck('value', 'key');
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

    public function savePayment() {
        $validated = $this->validate([
            'site_gcash_phone' => $this->rules()['site_gcash_phone'],
            'site_gcash_name' => $this->rules()['site_gcash_name'],
            'site_gcash_qr' => $this->rules()['site_gcash_qr'],
        ]);

        DB::transaction(function () use ($validated) {
            Settings::where('key', 'site_gcash_phone')->first()->update([
                'value' => $validated['site_gcash_phone']
            ]);
    
            Settings::where('key', 'site_gcash_name')->first()->update([
                'value' => $validated['site_gcash_name']
            ]);
            
            if (!empty($validated['site_gcash_qr'])) {
                $logo = Settings::where('key', 'site_gcash_qr')->first();

                // Delete the original logo first
                if (!empty($logo->value)) {
                    Storage::disk('public')->delete($logo->value);    
                }

                $logo->update([
                    'value' => $validated['site_gcash_qr']->store('global', 'public')
                ]);
            }
        });

        $this->settings = Settings::pluck('value', 'key');
        $this->dispatch('pond-reset');
        $this->dispatch('settings-updated');
        $this->toast('Success!', description: 'GCash Payment Information updated!');
    }

    public function saveReservation() {
        $validated = $this->validate([
            'site_reservation_downpayment_percentage' => $this->rules()['site_reservation_downpayment_percentage'],
        ]);
        
        DB::transaction(function () use ($validated) {
            Settings::where('key', 'site_reservation_downpayment_percentage')->first()->update([
                'value' => $validated['site_reservation_downpayment_percentage']
            ]);
        });

        $this->settings = Settings::pluck('value', 'key');
        $this->dispatch('settings-updated');
        $this->toast('Success!', description: 'Reservation Configuration updated!!');
    }

    public function render()
    {
        return view('livewire.app.content.global.edit-global');
    }
}
