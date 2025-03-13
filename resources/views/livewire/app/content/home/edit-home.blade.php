<div>
    <section class="space-y-5">
        <div class="flex items-start justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
            <x-note>Any update made on this page will be automatically applied to the website.</x-note>

            <x-status type="page" :status="$page->status" />
        </div>
        
        <!-- Hero Section -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Hero Section</h3>
                    <p class="text-xs">Update your hero section here</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'edit-hero-modal')">Edit Hero</button>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                <x-img-lg src="{{ asset('storage/' . $medias['home_hero_image']) }}" />

                <div class="grid p-5 border rounded-md border-slate-200 place-items-center">
                    <div>
                        <p class="font-semibold text-center">{!! $contents['home_heading'] !!}</p>
                        <p class="text-sm text-center">{!! $contents['home_subheading'] !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Services -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Featured Services</h3>
                    <p class="text-xs">Manage your featured services</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'create-service-modal')">Add Service</button>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($featured_services as $featured_service)
                    <div wire:key="{{ $featured_service->id }}"
                        @class(['relative p-5 border space-y-5 border-slate-200 rounded-md', 'bg-slate-50' => $featured_service->status == App\Enums\FeaturedServiceStatus::INACTIVE->value])
                        >
                        <div class="space-y-5">
                            <x-img-lg src="{{ asset('storage/' . $featured_service->image) }}" class="w-full" />

                            <div>
                                <h4 class="font-semibold">{{ $featured_service->title }}</h4>
                                <p class="text-sm text-justify line-clamp-3">{{ $featured_service->description }}</p>
                            </div>
                        </div>

                        <div class="flex justify-center gap-1 p-1 border rounded-md border-slate-200">
                            <x-tooltip text="Edit" dir="bottom">
                                <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'edit-service-modal-{{ $featured_service->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                </x-icon-button>
                            </x-tooltip>

                            @if ($featured_service->status == App\Enums\FeaturedServiceStatus::ACTIVE->value)
                                <x-tooltip text="Deactivate" dir="bottom">
                                    <x-icon-button x-bind:disabled="{{ $featured_services->count() <= 3}}" x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'deactivate-service-modal-{{ $featured_service->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            @else
                                <x-tooltip text="Activate" dir="bottom">
                                    <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'activate-service-modal-{{ $featured_service->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            @endif
                            
                            <x-tooltip text="Delete" dir="bottom">
                                <x-icon-button  x-bind:disabled="{{ $featured_services->count() <= 3}}" x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'delete-service-modal-{{ $featured_service->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </x-icon-button>
                            </x-tooltip>
                        </div>

                        <x-modal.full name="edit-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.edit-service wire:key="edit-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="deactivate-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.deactivate-service wire:key="deactivate-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="activate-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.activate-service wire:key="activate-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="delete-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.delete-service wire:key="delete-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>
                    </div>
                @endforeach
            </div>
            @if ($featured_services->count() <= 3)
                <x-note>
                    <p class="pr-2">Minimum of three (3) services must be featured</p>
                </x-note>
            @endif
        </div>
    </section>

    <!-- Modals -->
    <x-modal.full name="create-service-modal" maxWidth="sm">
        <livewire:app.content.home.create-service />
    </x-modal.full> 

    {{-- <x-modal.full name="edit-hero-modal" maxWidth="sm">
        <livewire:app.content.edit-hero page="{{ strtolower($page->title) }}" />
    </x-modal.full>  --}}
</form>