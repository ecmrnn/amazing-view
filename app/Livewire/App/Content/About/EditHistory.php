<?php

namespace App\Livewire\App\Content\About;

use App\Models\MediaFile;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditHistory extends Component
{
    use DispatchesToast, WithFilePond;
    
    #[Validate] public $history;
    #[Validate] public $history_image;
    #[Validate] public $history_length;
    public $current_image;

    public function mount() {
        $this->history = PageContent::where('key', 'about_history')->pluck('value')->first();
        $this->history_length = strlen($this->history);
        $this->current_image = MediaFile::where('key', 'about_history_image')->pluck('path')->first();
    }

    public function rules() {
        return [
            'history' => 'required|max:1000',
            'history_image' => 'nullable|mimes:jpg,jpeg,png|image',
        ];
    }

    public function messages() {
        return [
            'history.required' => 'History is required',
            'history.max' => 'Maximum characters is 1000',

        ];
    }

    public function submit() {
        $this->validate([
            'history' => $this->rules()['history'],
            'history_image' => $this->rules()['history_image'],
        ]);

        if (!empty($this->history_image)) {
            // delete saved image para di magdoble doble
            $history_image = MediaFile::where('key', 'about_history_image')->first();
            
            if (!empty($history_image->path)) {
                Storage::disk('public')->delete($history_image->path);
            }
            
            $history_image->path = $this->history_image->store('about', 'public');
            $history_image->save();
            $this->current_image = $history_image->fresh()->path;
        }

        $history = PageContent::where('key', 'about_history')->first();
        $history->value = $this->history;
        $history->save();

        $this->toast('History Updated', 'success', 'History updated successfully!');
        $this->dispatch('history-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <form x-on:history-edited.window="show = false; count = 0;" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit="submit">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Edit History</h2>
                    <p class="max-w-sm text-sm">Update history details here</p>
                </hgroup>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="p-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <div class="flex items-start justify-between mb-5">
                                <div>
                                    <x-form.input-label for='history_image'>Upload a new image for your history</x-form.input-label>
                                    <p class="text-xs">Click the button on the right to view current image</p>
                                </div>

                                @if (!empty($current_image))
                                    <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'show-current-image')">View Image</button>
                                @endif
                            </div>
                            <x-filepond::upload
                                wire:model.live="history_image"
                                id="history_image"
                                placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                            />
                            <x-form.input-error field="history_image" />
                        </x-form.input-group>
                    </div>

                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <div class="mb-5">
                                <x-form.input-label for="edit-history">History</x-form.input-label>
                                <p class="text-xs">Write an amazing story here</p>
                            </div>

                            <x-form.textarea id="edit-history" name="history" wire:model.live="history" class="w-full" max="1000" />
                            <x-form.input-error field="history" />
                        </x-form.input-group>
                    </div>
                </div>

                
                <div class="flex items-center justify-between gap-1">
                    <x-primary-button type="submit">Save</x-primary-button>
                    <x-loading wire:loading wire:target='submit'>Editing history, please wait</x-loading>
                </div>

                <x-modal.full name='show-current-image' maxWidth='sm'>
                    <div class="p-5 space-y-5">
                        <img src="{{ asset('storage/' . $current_image) }}" alt="History" class="bg-white border rounded-md border-slate-200">

                        <div class="flex justify-end">
                            <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                        </div>
                    </div>
                </x-modal.full>
            </form>
        HTML;
    }
}
