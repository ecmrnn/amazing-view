<?php

namespace App\Livewire\App\Content;

use App\Models\Page;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DisablePage extends Component
{
    use DispatchesToast;

    #[Validate] public $password;
    public $page;

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function mount(Page $page) {
        $this->page = $page;
    }

    public function disablePage() {
        $this->validate([
            'password' => 'required'
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // disable page
            $this->page->status = Page::STATUS_DOWN;
            $this->page->save();
            
            $this->toast('Page Disabled', 'success', 'Page disabled successfully!');
            $this->dispatch('page-disabled');
            // reset
            $this->reset('password');
        } else {
            $this->toast('Hiding Page Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div x-on:page-disabled.window="show = false" class="p-3 space-y-3 sm:p-5 sm:space-y-5">
            <hgroup>
                <h3 class="font-semibold text-center">Hiding Page</h3>
                <p class="text-sm text-center">Are you sure you really want to hide this page?</p>
            </hgroup>

            <div class="space-y-3">
                <x-form.input-label for="disable-password">Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="disable-password" name="disable-password" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </div>

            <div class="flex justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click="disablePage">Yes, Hide</x-danger-button>
            </div>
        </div>
        HTML;
    }
}
