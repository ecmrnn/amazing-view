<?php

namespace App\Livewire\App\Content\Reservation;

use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditRoomReservation extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'hero-edited' => '$refresh',
    ];

    #[On('hero-edited')]
    public function refresh() {
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
    }

    public $page;
    public $contents;
    public $medias;

    public function mount() {
        $this->page = Page::whereUrl('/reservation')->first();
        $this->contents = PageContent::where('page_id', $this->page->id)->pluck('value', 'key');
        $this->medias = MediaFile::where('page_id', $this->page->id)->pluck('path', 'key');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <section class="space-y-5">
                <livewire:app.content.edit-hero page="{{ $page->title }}" />
            </section>

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
                        
                        <div class="relative w-full rounded-lg before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                            style="background-image: url({{ asset('storage/' . $medias['room_reservation_hero_image']) }});
                            background-size: cover;
                            background-position: center;">
                            <section class="relative z-10 grid w-3/4 py-20 mx-auto text-white rounded-md place-items-center">
                                <div class="flex justify-between w-full px-2">
                                    <div class="space-y-3">
                                        <p class="font-bold text-md">{!! nl2br(e($contents['room_reservation_heading'] ?? '')) !!}</p>
                                        <p class="max-w-xs text-xs">{!! $contents['room_reservation_subheading'] !!}</p>
                                        
                                        <div class="flex gap-1">
                                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                                            <x-secondary-button type="button" class="text-xs">...</x-secondary-button>
                                        </div>
                                    </div>

                                    <div>
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
        </form>
        HTML;
    }
}
