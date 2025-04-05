<div x-data="{ payment_method: @entangle('payment_method') }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
    <hgroup>
        <h2 class="font-semibold">Payment</h2>
        <p class="text-xs">Choose a payment method then enter the necessary payment details.</p>
    </hgroup>

    <x-note>
        <p>Minimum of <x-currency />500.00 must be paid for the reservation to be processed and confirmed.</p>
    </x-note>

    <div class="p-5 space-y-3 bg-white border rounded-lg">
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

        <x-form.input-error x-show="payment_method != 'cash'" field="transaction_id" />
        <x-form.input-error x-show="payment_method != 'cash'" field="proof_image_path" />
        <x-form.input-error field="downpayment" />
    </div>

    <x-form.input-group x-show="payment_method != 'cash'">
        <x-form.input-label for='transaction_id'>Enter the Payment&apos;s Reference ID</x-form.input-label>
        <x-form.input-text wire:model.live='transaction_id' label="Reference No." id="transaction_id" class="w-max" />
        <x-form.input-error field="transaction_id" />
    </x-form.input-group>

    {{-- Online Payment --}}
    <div x-show="payment_method != 'cash'">
        <x-filepond::upload
        wire:model.live="proof_image_path"
        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
        />
        <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">3MB or 3000kb</strong>&rpar;</p>
    </div>

    {{-- Cash --}}
    <div class="space-y-3">
        <x-form.input-label for="downpayment">Enter the amount paid</x-form.input-label>
        <x-form.input-currency x-model="downpayment" wire:model.live='downpayment' id="downpayment" class="w-min" />
    </div>
</div>