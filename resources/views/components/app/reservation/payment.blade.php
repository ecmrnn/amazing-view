<x-form.form-section>
    <x-form.form-header step="5" title="Payment" />

    <div x-show="can_submit_payment" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div x-data="{ 
                    payment_method: $wire.entangle('payment_method'),
                }" class="p-5 space-y-3">
                <x-note>
                    <p class="max-w-sm">Minimum of <x-currency />500.00 must be paid for the reservation to be processed and confirmed.</p>
                </x-note>
                
                <div class="p-3 space-y-3 bg-white border rounded-lg">
                    <hgroup>
                        <h3 class="text-sm font-semibold">Payment Methods</h3>
                        <p class="text-xs text-zinc-800">Select how the customer wants to pay</p>
                    </hgroup>

                    {{-- Payment methods --}}
                    <div class="grid space-y-2">
                        <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="cash" id="cash" label="Cash" />
                        <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="gcash" id="gcash" label="GCash" />
                        <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="bank" id="bank" label="Bank Transfer" />
                    </div>

                    <div x-show="payment_method != 'cash'">
                        <x-form.input-text wire:model='transaction_id' label="Transaction ID" id="transaction_id" />
                    </div>

                    <x-form.input-error x-show="payment_method != 'cash'" field="transaction_id" />
                    <x-form.input-error x-show="payment_method != 'cash'" field="proof_image_path" />
                    <x-form.input-error field="downpayment" />
                </div>

                {{-- Online Payment --}}
                <div x-show="payment_method != 'cash'">
                    <x-filepond::upload
                    wire:model.live="proof_image_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
                </div>

                {{-- Cash --}}
                <div class="space-y-3">
                    <x-form.input-label for="downpayment">Enter the amount paid</x-form.input-label>
                    <x-form.input-currency x-model="downpayment" wire:model.live='downpayment' min="500" id="downpayment" class="w-min" />
                </div>

                <div class="flex items-center gap-1">
                    <x-secondary-button type="button" x-on:click="can_submit_payment = false; can_add_amenity = true">Edit Addons &amp; Amenities</x-secondary-button>
                    <x-primary-button>Submit Reservation</x-primary-button>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>