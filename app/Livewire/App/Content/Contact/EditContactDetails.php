<?php

namespace App\Livewire\App\Content\Contact;

use App\Models\ContactDetails;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditContactDetails extends Component
{
    use DispatchesToast;

    #[Validate] public $contact;
    public $contact_detail;

    public function rules() {
        return [
            'contact' => 'required|size:11|starts_with:09'
        ];
    }

    public function messages() {
        return [
            'contact.required' => 'Enter a contact number',
            'contact.size' => 'Number must be 11 digits',
            'contact.starts_with' => 'Number must start with "09"',
        ];
    }

    public function mount(ContactDetails $contact_detail) {
        $this->contact_detail = $contact_detail;
        $this->contact = $contact_detail->value;
    }

    public function submit() {
        $validated = $this->validate([
            'contact' => $this->rules()['contact']
        ]);

        $this->contact_detail->value = $validated['contact'];
        $this->contact_detail->save();

        $this->toast('Contact Edited!', 'success', 'Contact edited successfully');
        $this->dispatch('contact-edited');
        $this->reset('contact');
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="block p-5 space-y-5 bg-white" x-on:contact-edited.window="show = false">
            <hgroup>
                <h2 class="font-semibold text-center capitalize">Edit Contact</h2>
                <p class="max-w-sm text-sm text-center">Edit contact details here</p>
            </hgroup>

            <div class="space-y-2">
                <div>
                    <x-form.input-label for="contact">Contact Details</x-form.input-label>
                    <p class="text-xs">Enter the phone number of your new contact</p>
                </div>
                
                <x-form.input-text id="contact" maxlength="11" name="contact" label="Phone Number" wire:model.live="contact" />
                <x-form.input-error field="contact" />
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="submit">Edit Contact</x-primary-button>
            </div>
        </div>
        HTML;
    }
}
