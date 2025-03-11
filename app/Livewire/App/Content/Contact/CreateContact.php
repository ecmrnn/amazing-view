<?php

namespace App\Livewire\App\Content\Contact;

use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateContact extends Component
{
    use DispatchesToast;

    #[Validate] public $contact;

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

    public function submit() {
        $validated = $this->validate([
            'contact' => $this->rules()['contact']
        ]);

        // ContactDetails::create([
        //     'name' => 'phone_number',
        //     'type' => 'phone',
        //     'value' => $validated['contact']
        // ]);

        $this->toast('Contact Created!', 'success', 'Contact created successfully');
        $this->dispatch('contact-added');
        $this->reset('contact');
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="block p-5 space-y-5 bg-white" x-on:contact-added.window="show = false">
            <hgroup>
                <h2 class="font-semibold text-center capitalize">Add Contact</h2>
                <p class="max-w-sm text-sm text-center">Create a new contact to show here</p>
            </hgroup>

            <x-note>
                Kindly fill up the form below to add a contact. Double check the phone number you enter as this will be shown in the website
            </x-note>

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
                <x-primary-button type="button" wire:click="submit">Add Contact</x-primary-button>
            </div>
        </div>
        HTML;
    }
}
