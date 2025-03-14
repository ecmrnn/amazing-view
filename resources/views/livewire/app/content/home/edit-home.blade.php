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

        {{-- Featured Services --}}
        <livewire:app.content.home.show-featured-services />

        {{-- Testimonials --}}
        <livewire:app.content.home.show-testimonials />
    </section>

    <!-- Modals -->
    <x-modal.full name="create-service-modal" maxWidth="sm">
        <livewire:app.content.home.create-service />
    </x-modal.full> 

    <x-modal.full name="edit-hero-modal" maxWidth="sm">
        <livewire:app.content.edit-hero page="{{ strtolower($page->title) }}" />
    </x-modal.full> 
</form>