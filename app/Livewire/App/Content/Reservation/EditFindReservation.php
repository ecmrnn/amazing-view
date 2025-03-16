<?php

namespace App\Livewire\App\Content\Reservation;

use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditFindReservation extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'hero-edited' => '$refresh',
    ];

    #[Validate] public $heading;
    #[Validate] public $subheading;
    
    public $page;
    public $contents;
    public $medias;

    public function rules() {
        return [
            'heading' => 'required',
            'subheading' => 'required',
        ];
    }

    public function mount() {
        $this->page = Page::whereUrl('/search')->first();
        $this->heading = PageContent::where('key', str_replace(' ', '_', $this->page->title) . '_heading')->pluck('value')->first();
        $this->subheading = PageContent::where('key', str_replace(' ', '_', $this->page->title) . '_subheading')->pluck('value')->first();
    }

    public function submit() {
        // Validate
        $this->validate();

        // Store to database
        $heading = PageContent::where('key', str_replace(' ', '_', $this->page->title) . '_heading')->first();
        $heading->value = $this->heading;
        $heading->save();

        $subheading = PageContent::where('key', str_replace(' ', '_', $this->page->title) . '_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();

        $this->toast('Hero Edited!', 'success', 'Hero edited successfully');
        $this->dispatch('hero-edited');
        $this->dispatch('pond-reset');
    }
    
    public function render()
    {
        return <<<'HTML'
        <div>
            <form class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit='submit'>
                <hgroup>
                    <h2 class="font-semibold">Find Reservation - Edit Hero Section</h2>
                    <p class="text-xs">Update hero details here</p>
                </hgroup>
                <div class="p-5 space-y-5 border rounded-md border-slate-200">
                    <x-form.input-group>
                        <div class="mb-5">
                            <x-form.input-label for='heading'>Heading</x-form.input-label>
                            <p class="text-xs">Enter an eye catching tagline</p>
                        </div>
                        <x-form.textarea wire:model.live='heading' id="heading" name="heading" label="Heading" class="w-full" rows="2" />
                        <x-form.input-error field="heading" />
                    </x-form.input-group>
            
                    <x-form.input-group>
                        <div class="mb-5">
                            <x-form.input-label for='subheading'>Subheading</x-form.input-label>
                            <p class="text-xs">This will appear below the heading</p>
                        </div>
                        <x-form.input-text wire:model.live='subheading' id="subheading" name="subheading" label="Subheading" class="w-1/2" />
                        <x-form.input-error field="subheading" />
                    </x-form.input-group>
                </div>
            
                <div class="flex items-center justify-between">
                    <x-primary-button>Save</x-primary-button>
                    <x-loading wire:loading wire:target='submit'>Updating changes, please wait</x-loading>
                </div>
            </form>

            <x-modal.full name='show-preview-modal' maxWidth='screen-xl'>
                <section class="hidden space-y-5 overflow-y-scroll xl:block aspect-video">
                    <div class="p-5 space-y-1 min-w-[780px]">
                        <header class="flex justify-between w-3/4 p-2 mx-auto rounded-md">
                            <!-- Logo -->
                            <div class="p-3 rounded-md bg-slate-200 aspect-square"></div>
                            <!-- Links -->
                            <div class="flex gap-2">
                                <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                                <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                                <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                                <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                            </div>
                        </header>
                        
                        <div class="relative w-full overflow-hidden rounded-lg">
                            <section class="relative z-10 grid w-3/4 py-20 mx-auto rounded-md place-items-center">
                                <div class="flex justify-center w-full px-2">
                                    <div class="space-y-3 text-center">
                                        <p class="font-bold text-md">{!! nl2br(e($heading ?? '')) !!}</p>
                                        <p class="max-w-xs text-xs">{!! $subheading !!}</p>
                                        
                                        <div class="flex justify-center gap-1">
                                            <x-form.input-text id="" disabled name="" label="" />
                                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        
                        <footer class="w-full py-10 mx-auto space-y-3 text-white rounded-md bg-blue-950">
                            <div class="w-3/4 gap-10 mx-auto space-y-10 md:space-y-0 md:grid md:grid-cols-3 lg:grid-cols-4">
                                <div class="pr-5 space-y-5 border-dashed md:col-span-3 lg:col-span-1 lg:border-r border-white/50">
                                    <h2 class="text-xs font-semibold">
                                        <span>Amazing View</span><br />
                                        <span>Mountain Resort</span>
                                    </h2>
                                    <p class="text-xxs">
                                        Where every stay becomes a story,
                                        welcome to your perfect escape!
                                    </p>
                                    <x-primary-button type="button" class="text-xs">...</x-primary-button>
                                </div>
                                <div class="space-y-3">
                                    <h3 class="text-xs font-semibold">Navigate through our site</h3>
                                    <div class="space-y-3">
                                        <x-footer-link class="text-xxs" href="/">Home</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/rooms">Rooms</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/about">About</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/contact">Contact</x-footer-link>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <h3 class="text-xs font-semibold">Stay connected with us!</h3>
                
                                    <div class="space-y-3">
                                        <x-footer-link class="text-xxs" href="https://facebook.com" target="_blank">Facebook</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Instagram</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Twitter</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Youtube</x-footer-link>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <h3 class="text-xs font-semibold">Enjoy more of our content</h3>
                
                                    <div class="space-y-3">
                                        <x-footer-link class="text-xxs" href="/">Blogs</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Events</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Testimonials</x-footer-link>
                                        <x-footer-link class="text-xxs" href="/">Announcements</x-footer-link>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </section>
            </x-modal.full>
        </div>
        HTML;
    }
}
