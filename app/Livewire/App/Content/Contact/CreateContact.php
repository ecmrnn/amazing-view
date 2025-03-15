<?php

namespace App\Livewire\App\Content\Contact;

use App\Models\Page;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateContact extends Component
{
    use DispatchesToast;

    #[Validate] public $contact;

    public function rules() {
        return [
            'contact' => 'required|size:11|starts_with:09|unique:page_contents,value'
        ];
    }

    public function messages() {
        return [
            'contact.required' => 'Enter a contact number',
            'contact.size' => 'Number must be 11 digits',
            'contact.starts_with' => 'Number must start with "09"',
            'contact.unique' => 'Number exists already',
        ];
    }

    public function submit() {
        $validated = $this->validate([
            'contact' => $this->rules()['contact']
        ]);

        $page = Page::whereUrl('/contact')->first();

        PageContent::create([
            'page_id' => $page->id,
            'key' => 'phone_number',
            'type' => 'text',
            'value' => $validated['contact']
        ]);

        $this->toast('Contact Created!', 'success', 'Contact created successfully');
        $this->dispatch('contact-added');
        $this->reset('contact');
    }
    
    public function render()
    {
        return <<<'HTML'
        <form wire:submit="submit" class="p-5 space-y-5" x-on:contact-added.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Add Contact</h2>
                <p class="text-sm">Create a new contact to show here</p>
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

            <x-loading wire:loading wire:target='submit'>Creating contact, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Add Contact</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
