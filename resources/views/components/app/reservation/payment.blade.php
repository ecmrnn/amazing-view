<x-form.form-section>
    <x-form.form-header step="4" title="Payment" />

    <div x-show="can_submit_payment" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div x-data="{ 
                    payment_method: $wire.entangle('payment_method'),
                    cash_payment: $wire.entangle('cash_payment'),
                }" class="p-5 space-y-3">
                <div class="p-3 space-y-3 bg-white border rounded-lg">
                    <hgroup>
                        <h3 class="text-sm font-semibold">Payment Methods</h3>
                        <p class="text-xs text-zinc-800">Select how the customer wants to pay</p>
                    </hgroup>

                    {{-- Payment methods --}}
                    <div class="grid gap-1">
                        <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" x-bind:checked="payment_method == online" value="online" name="payment_methods" id="online" label="Online  (GCash, Online Banking)" />
                        <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" value="cash" name="payment_methods" id="cash" label="Cash" />
                    </div>

                    <x-form.input-error x-show="payment_method == 'online'" field="proof_image_path" />
                    <x-form.input-error x-show="payment_method == 'cash'" field="cash_payment" />
                </div>

                {{-- Online Payment --}}
                <div x-show="payment_method == 'online'">
                    <x-filepond::upload
                    wire:model.live="proof_image_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
                </div>

                {{-- Cash --}}
                <div x-show="payment_method == 'cash'" class="space-y-3">
                    <x-form.input-label for="cash_payment">Enter the amount paid</x-form.input-label>
                    <x-form.input-currency x-model="cash_payment" wire:model.live='cash_payment' min="500" id="cash_payment" />
                </div>
                
                {{-- Submit --}}
                <x-primary-button>
                    Submit Reservation
                </x-primary-button>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>