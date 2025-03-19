<?php

namespace App\Livewire\App\Content;

use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangeStatus extends Component
{
    use DispatchesToast;

    public $page;
    #[Validate] public $status;
    #[Validate] public $password;
    public $statuses;

    public function rules() {
        return [
            'password' => 'required',
            'status' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
            'status.required' => 'Select a status',
        ];
    }

    public function mount($page) {
        $this->page = $page;
        $this->statuses = collect([
            [
                'status' => 'Active',
                'value' => 0,
            ],
            [
                'status' => 'Disable',
                'value' => 1,
            ],
            [
                'status' => 'Maintenance',
                'value' => 2,
            ],
        ]);
    }

    public function submit() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            // Change the status of the page
            $page = $this->page;
            $status = $this->status;
            
            DB::transaction(function () use ($page, $status){
                return $page->update([
                    'status' => $status,
                ]);
            });

            $this->toast('Success!', description: 'The status of this page is updated successfully!');
            $this->dispatch('page-status-updated');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again!');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <x-status type="page" :status="$page->status" />

            <x-modal.full name='change-status-modal' maxWidth='sm'>
                <form class="p-5 space-y-5" wire:submit="submit" x-on:page-status-updated.window="show = false">
                    <hgroup>
                        <h2 class="font-semibold">Change Status</h2>
                        <p class="text-xs">Choose what you want to do to this page</p>
                    </hgroup>

                    <!-- Choose what to do to this page -->
                    <x-form.input-group>
                        <x-form.input-label for="status">What do you want to do?</x-form.input-label>
                        <x-form.select id="status" wire:model.live="status">
                            <option value="">Select a Status</option>
                            @foreach ($statuses as $status)
                                @if ($status['value'] != $page->status)
                                    <option value="{{ $status['value'] }}">{{ $status['status'] }}</option>
                                @endif
                            @endforeach
                        </x-form.select>
                        <x-form.input-error field="status" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for='password'>Enter your password</x-form.input-label>
                        <x-form.input-text wire:model.live="password" type="password" id="password" name="password" label="Password" />
                        <x-form.input-error field="password" />
                    </x-form.input-group>
                    <x-loading wire:loading wire:target='submit'>Updating status, please wait</x-loading>
                
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-primary-button>Edit</x-primary-button>
                    </div>
                </form>
            </x-modal.full>
        </div>
        HTML;
    }
}
