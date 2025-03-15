<?php

namespace App\Livewire\App\Content\Contact;

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
    public $contact_detail;

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
            $this->contact_detail->delete();
            
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
            <form wire:submit="deleteContact" class="p-5 space-y-5" x-on:contact-deleted.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold text-red-500">Delete Contact</h2>
                    <p class="text-sm">Enter your password to delete this contact</p>
                </hgroup>

                <div class="space-y-2">
                    <x-form.input-label for="delete-{{ $contact_detail->id }}-password">Enter your password</x-form.input-label>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $contact_detail->id }}-password" />
                    <x-form.input-error field="password" />
                </div>

                <x-loading wire:loading wire:target='deleteContact'>Deleting contact, please wait</x-loading>
                
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-danger-button type="submit">Delete</x-danger-button>
                </div>
            </form>
        HTML;
    }
}
