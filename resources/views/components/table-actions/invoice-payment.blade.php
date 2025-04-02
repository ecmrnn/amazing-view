@props([
    'width' => '16',
    'height' => '16',
])

<div class="flex justify-end gap-1">
    @if ($row->invoice->status != App\Enums\InvoiceStatus::ISSUED->value)
        @can('delete billing')
            <x-tooltip text="Delete" dir="top">
                <a x-ref="content">
                    <x-icon-button x-on:click="$dispatch('open-modal', 'delete-payment-modal-{{ $row->id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        @endcan

        <x-modal.full name='delete-payment-modal-{{ $row->id }}' maxWidth='sm'>
            <div class="p-5 space-y-5" x-on:payment-deleted.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold text-red-500">Delete Payment</h2>
                    <p class="text-xs">Are you sure you really want to delete this payment?</p>
                </hgroup>
    
                <x-form.input-group>
                    <x-form.input-label for="password-{{ $row->id }}">Enter your password to delete</x-form.input-label>
                    <x-form.input-text wire:model.live='password' type="password" id="password-{{ $row->id }}" name="password-{{ $row->id }}" label="Password" />
                    <x-form.input-error field="password" />
                </x-form.input-group>
    
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                    <x-danger-button type="submit" wire:loading.attr='disabled' x-on:click="$dispatch('delete-payment', { payment:{{ $row->id }} })">Delete</x-danger-button>
                </div>
            </div>
        </x-modal.full>
    @endif

    <x-tooltip text="View" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'view-payment-{{ $row->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" /></svg>
        </x-icon-button>
    </x-tooltip>

    <x-modal.full name='view-payment-{{ $row->id }}' maxWidth='sm'>
        <div x-data="{ 
            amount: @js((int) $row->amount), 
            payment_date: @js($row->payment_date),
            payment_method: @js($row->payment_method),
            transaction_id: @js($row->transaction_id),
            }" class="p-5 space-y-5" x-on:payment-edited.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">View Payment</h2>
                @if ($row->invoice->status != App\Enums\InvoiceStatus::ISSUED->value && $row->invoice->reservation->user->role != App\Enums\UserRole::GUEST->value)
                    <p class="text-xs">Edit payment details here</p>
                @else
                    <p class="text-xs">View payment details here</p>
                @endif
            </hgroup>
    
            @if (!empty($row->proof_image_Path))
                <div class="col-span-2 overflow-auto border rounded-md aspect-square border-slate-200">
                    <x-img src="{{ $row->proof_image_path }}" alt="payment" />
                </div>
            @endif

            <div class="grid grid-cols-2 gap-5 p-5 bg-white border rounded-md border-slate-200">
                @if (!empty($row->transaction_id))
                    <div class="col-span-2">
                        <p class="font-semibold">{{ $row->transaction_id }}</p>
                        <p class="text-xs">Reference No.</p>
                    </div>
                @endif
                <div>
                    <p class="font-semibold"><x-currency />{{ number_format($row->amount, 2) }}</p>
                    <p class="text-xs">Amount Paid</p>
                </div>

                <div>
                    <p class="font-semibold">{{ date_format(date_create($row->payment_date), 'F j, Y') }}</p>
                    <p class="text-xs">Payment Date</p>
                </div>
            </div>

            @can('update billing')
                @if ($row->invoice->status != App\Enums\InvoiceStatus::ISSUED->value)
                    <div class="grid grid-cols-2 gap-5">
                        @if ($row->payment_method != 'CASH')
                            <x-form.input-group class="col-span-2">
                                <x-form.input-label for='transaction_id-{{ $row->id }}'>Reference No.</x-form.input-label>
                                <x-form.input-text x-model="transaction_id" id="transaction_id-{{ $row->id }}" name="transaction_id-{{ $row->id }}" label="Reference No." />
                                <x-form.input-error field="transaction_id" />
                            </x-form.input-group>
                        @endif
                        <x-form.input-group>
                            <x-form.input-label for='amount-{{ $row->id }}'>Amount Paid</x-form.input-label>
                            <x-form.input-currency x-model="amount" id="amount-{{ $row->id }}" name="amount-{{ $row->id }}" label="amount" />
                            <x-form.input-error field="amount" />
                        </x-form.input-group>
                        <x-form.input-group>
                            <x-form.input-label for='payment_date-{{ $row->id }}'>Payment Date</x-form.input-label>
                            <x-form.input-date x-model="payment_date" id="payment_date-{{ $row->id }}" name="payment_date-{{ $row->id }}" label="payment_date" class="w-full" />
                            <x-form.input-error field="payment_date" />
                        </x-form.input-group>
                    </div>
            
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                        <x-primary-button 
                            type="submit"
                            wire:loading.attr='disabled'
                            x-on:click="$dispatch('edit-payment', {
                                'id': {{ $row->id }},
                                'amount': amount,
                                'payment_date': payment_date,
                                'transaction_id': transaction_id, 
                                'payment_method': payment_method 
                                })"
                            >
                            Edit
                        </x-primary-button>
                    </div>
                @endif
            @endcan
        </div>
    </x-modal.full>
</div>
