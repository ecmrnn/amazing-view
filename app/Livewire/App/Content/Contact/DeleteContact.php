<?php

namespace App\Livewire\App\Content\Contact;

use App\Models\ContactDetails;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteContact extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $contact;

    public function mount(ContactDetails $contact_detail) {
        $this->contact = $contact_detail;
    }

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function deleteContact() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // delete contact
            $this->contact->delete();
            
            $this->toast('Contact Deleted', 'success', 'Contact deleted successfully!');
            $this->dispatch('contact-deleted');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }
    
    public function render()
    {
        return <<<'HTML'
            <section class="p-5 space-y-5 bg-white" x-on:contact-deleted.window="show = false">
                <hgroup>
                    <h2 class="font-semibold text-center text-red-500 capitalize">Delete Contact</h2>
                    <p class="max-w-sm text-sm text-center">Are you sure you really want this contact?</p>
                </hgroup>

                <div class="space-y-2">
                    <p class="text-xs">Enter your password to delete this contact.</p>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $contact->id }}-password" />
                    <x-form.input-error field="password" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                    <x-danger-button type="button" wire:click='deleteContact()'>Yes, Delete</x-danger-button>
                </div>
            </section>
        HTML;
    }
}
