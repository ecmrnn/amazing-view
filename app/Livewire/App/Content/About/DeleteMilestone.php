<?php

namespace App\Livewire\App\Content\About;

use App\Models\Milestone;
use App\Services\MilestoneService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteMilestone extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $milestone;

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function deleteMilestone() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            $service = new MilestoneService;
            $service->delete($this->milestone);

            $this->toast('Milestone Deleted', 'success', 'Milestone deleted successfully!');
            $this->dispatch('milestone-deleted');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deleteMilestone" class="p-5 space-y-5 bg-white" x-on:milestone-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Milestone</h2>
                <p class="text-sm">Are you sure you really want this milestone?</p>
            </hgroup>

            <div class="space-y-2">
                <p class="text-xs">Enter your password to delete this milestone.</p>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $milestone->id }}-password" />
                <x-form.input-error field="password" />
            </div>

            <x-loading wire:loading wire:target='deleteMilestone'>Deleting milestone, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Delete</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
