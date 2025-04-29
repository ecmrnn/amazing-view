<div class="space-y-5">
    <form class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit='saveBranding'>
        <hgroup>
            <h2 class="font-semibold">Branding and Visual Identity</h2>
            <p class="text-xs">Update how you want the internet to find your site</p>
        </hgroup>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="p-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <x-form.input-label for='site_logo'>Upload a new logo for your website</x-form.input-label>
                            <p class="text-xs">Click the button on the right to view current logo</p>
                        </div>
                        <button class="text-xs font-semibold text-blue-500 shrink-0" type="button" x-on:click="$dispatch('open-modal', 'show-current-logo')">View Logo</button>
                    </div>
                    <x-filepond::upload
                                wire:model.live="site_logo"
                                id="site_logo"
                                placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                            />
                    <x-form.input-error field="site_logo" />
                </x-form.input-group>
            </div>

            <div class="p-5 space-y-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_title'>Enter a new title for your website</x-form.input-label>
                        <p class="text-xs">This will be the text that appears on your tabs</p>
                    </div>
                    <x-form.input-text wire:model.live='site_title' id="site_title" name="site_title" label="Title" class="w-1/2" />
                    <x-form.input-error field="site_title" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_tagline'>Enter your website's tagline</x-form.input-label>
                        <p class="text-xs">This will be useful for search engines to find your website</p>
                    </div>
                    <x-form.input-text wire:model.live='site_tagline' id="site_tagline" name="site_tagline" label="Tagline" class="w-1/2" />
                    <x-form.input-error field="site_tagline" />
                </x-form.input-group>
            </div>
        </div>

        
        <div class="flex items-center justify-between">
            <x-primary-button>Save</x-primary-button>
            <x-loading wire:loading wire:target='saveBranding'>Updating changes, please wait</x-loading>
        </div>
    </form>

    <form class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit='saveContact'>
        <hgroup>
            <h2 class="font-semibold">Contact Information</h2>
            <p class="text-xs">These information will be used on generated emails and files</p>
        </hgroup>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="p-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_phone'>Enter a new phone number</x-form.input-label>
                        <p class="text-xs">This phone number will be used on generated emails and files</p>
                    </div>
                    <x-form.input-text wire:model.live='site_phone' id="site_phone" name="site_phone" label="Phone Number" class="w-1/2" />
                    <x-form.input-error field="site_phone" />
                </x-form.input-group>
            </div>
    
            <div class="p-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_email'>Enter a new email address</x-form.input-label>
                        <p class="text-xs">This email address will be used on generated emails and files</p>
                    </div>
                    <x-form.input-text wire:model.live='site_email' id="site_email" name="site_email" label="Email Address" class="w-1/2" />
                    <x-form.input-error field="site_email" />
                </x-form.input-group>
            </div>
        </div>

        
        <div class="flex items-center justify-between">
            <x-primary-button>Save</x-primary-button>
            <x-loading wire:loading wire:target='saveContact'>Updating changes, please wait</x-loading>
        </div>
    </form>

    <form class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit='savePayment'>
        <hgroup>
            <h2 class="font-semibold">GCash Payment Information</h2>
            <p class="text-xs">Update your GCash QR Code, number or name</p>
        </hgroup>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="p-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <x-form.input-label for='site_gcash_qr'>Upload a new GCash QR Code</x-form.input-label>
                            <p class="text-xs">Click the button on the right to view current QR</p>
                        </div>
                        <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'show-current-qr')">View QR</button>
                    </div>
                    <x-filepond::upload
                                wire:model.live="site_gcash_qr"
                                id="site_gcash_qr"
                                placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                            />
                    <x-form.input-error field="site_gcash_qr" />
                </x-form.input-group>
            </div>

            <div class="p-5 space-y-5 border rounded-md border-slate-200">
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_gcash_phone'>Enter a new GCash phone number</x-form.input-label>
                        <p class="text-xs">This will appear on reservation forms and email</p>
                    </div>
                    <x-form.input-text wire:model.live='site_gcash_phone' id="site_gcash_phone" name="site_gcash_phone" label="Title" class="w-1/2" />
                    <x-form.input-error field="site_gcash_phone" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <div class="mb-5">
                        <x-form.input-label for='site_gcash_name'>Enter your GCash's account name</x-form.input-label>
                        <p class="text-xs">This will appear on reservation forms and email</p>
                    </div>
                    <x-form.input-text wire:model.live='site_gcash_name' id="site_gcash_name" name="site_gcash_name" label="Tagline" class="w-1/2" />
                    <x-form.input-error field="site_gcash_name" />
                </x-form.input-group>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <x-primary-button>Save</x-primary-button>
            <x-loading wire:loading wire:target='savePayment'>Updating changes, please wait</x-loading>
        </div>
    </form>

    <form class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit='saveReservation'>
        <hgroup>
            <h2 class="font-semibold">Reservation Configuration</h2>
            <p class="text-xs">Update your reservation requirements here</p>
        </hgroup>

        <div class="w-full sm:w-1/2">
            <x-note>
                Reservation Downpayment Percentage refers to the minimum amount that guests must pay for their submitted reservations.
            </x-note>
        </div>

        <x-form.input-group>
            <x-form.input-label for='reservation_downpayment_percentage'>Reservation Downpayment Percentage</x-form.input-label>
            <x-form.input-text class="w-full sm:w-1/2" wire:model.live='site_reservation_downpayment_percentage' id="reservation_downpayment_percentage" name="reservation_downpayment_percentage" label="{{ $settings['site_reservation_downpayment_percentage'] }}" />
            <x-form.input-error field="reservation_downpayment_percentage" />
        </x-form.input-group>
        
        <div class="flex items-center justify-between">
            <x-primary-button>Save</x-primary-button>
            <x-loading wire:loading wire:target='saveReservation'>Updating changes, please wait</x-loading>
        </div>
    </form>

    <x-modal.full name='show-current-logo' maxWidth='sm'>
        <div class="p-5 space-y-5">
            <x-img src="{{ $settings['site_logo'] }}" alt="Logo of Amazing View" aspect="square" />

            <div class="flex justify-end">
                <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='show-current-qr' maxWidth='sm'>
        <div class="p-5 space-y-5">
            <x-img src="{{ $settings['site_gcash_qr'] }}" alt="QR Code" aspect="square" />

            <div class="flex justify-end">
                <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
            </div>
        </div>
    </x-modal.full>
</div>
