<x-form.form-section>
    <x-form.form-header step="2" title="Invoice Details" />

    <div x-show="rid != null" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <div class="flex flex-col gap-1 sm:flex-row">
                    <div class="w-full space-y-1 sm:w-min">
                        <x-form.input-label>Issue Date</x-form.input-label>
                        <x-form.input-date x-model="issue_date" x-bind:min="date_today" />
                    </div>
                    <div class="w-full space-y-1 sm:w-min">
                        <x-form.input-label>Due Date</x-form.input-label>
                        <x-form.input-date x-model="due_date" x-bind:min="issue_date" />
                    </div>
                </div>

                @include('components.web.reservation.add-amenity')

                <hgroup>
                    <h3 class="text-sm font-semibold">Apply Discount</h3>
                    <p class="max-w-sm text-xs">Select a discount to apply for this invoice</p>
                </hgroup>

                <div class="p-3 space-y-1 bg-white border rounded-md">
                    @if (!empty($discounts))
                        @forelse ($discounts as $discount)
                            <div key="{{ $discount->id }}" class="flex items-center gap-3">
                                <x-form.input-checkbox id="{{ $discount->id }}" label="{{ ucwords(strtolower($discount->name)) }}" wire:click='toggleDiscount({{ $discount->id }})'/>
                                {{-- @if (empty($discount->amount))
                                    <p class="text-xs font-semibold">{{ number_format($discount->percentage, 2) }}&percnt;</p>
                                @else
                                    <p class="text-xs font-semibold"><x-currency /> {{ number_format($discount->amount, 2) }}</p>
                                @endif --}}
                            </div>
                        @empty
                            <p class="text-xs font-semibold text-center">No discount available</p>
                        @endforelse
                    @endif
                </div>

                <x-primary-button type="button" x-on:click="$dispatch('open-modal', 'show-invoice-confirmation')">
                    Create Invoice
                </x-primary-button>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>