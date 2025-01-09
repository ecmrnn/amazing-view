<form x-data="{ type: @entangle('type'), start_date: @entangle('start_date'), format: @entangle('format') }" class="space-y-5" wire:submit='store()'>
    <x-form.select x-show='! type' id="report_type" name="report_type" wire:model.live='type' class="w-full">
        <option value="">Select a Report</option>
        <option value="reservation summary">Reservation Summary</option>
        <option value="daily reservations">Daily Reservations</option>
        <option value="occupancy report">Occupancy Report</option>
        <option value="financial report">Financial Report</option>
    </x-form.select>

    <div x-show='type' class="flex items-center justify-between pt-5 border-t border-dashed border-slate-200">
        <h1 class="text-xl font-semibold capitalize" x-text='type'></h1>
        <button type='button' class="text-xs font-semibold text-blue-500" wire:click='resetReportType()'>Change Report</button>
    </div>

    <div x-show='type' class="space-y-5" x-cloak>
        {{-- Report Details --}}
        <div class="p-5 space-y-3 border rounded-md border-slate-200">
            <hgroup>
                <h2 class="text-sm font-semibold">Report Details</h2>
                <p class="text-sm">Describe your report by giving it a name and a short description, then choose a format for your report.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-text id="name" name="name" label="File Name" wire:model.live='name' />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-text id="description" name="description" label="Description (Optional)" wire:model.live='description' />
                <x-form.input-error field="description" />
            </x-form.input-group>

            <x-form.select id="format" name="format" wire:model.live='format' class="w-full">
                <option value="">Select a Format</option>
                <option value="pdf">PDF</option>
                <option value="csv">CSV</option>
            </x-form.select>

            <x-form.input-group x-show="format === 'pdf'">
                <x-form.select id="size" name="size" wire:model.live='size' class="w-full">
                    <option value="">Select a Paper Size</option>
                    <option value="letter">Letter &lpar;8.5in x 11in&rpar;</option>
                    <option value="legal">Legal &lpar;8.5in x 14in&rpar;</option>
                    <option value="a4">A4 &lpar;8.27in x 11.7in&rpar;</option>
                </x-form.select>
                <x-form.input-error field="size" />
            </x-form.input-group>
        </div>

        {{-- Reservation Summary --}}
        <template x-if='type === "reservation summary"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Reservation Summary</h2>
                    <p class="text-sm">Select the date range for the reservation summary report.</p>
                </hgroup>
    
                <div class="grid grid-cols-2 gap-3">
                    <di class="space-y-3">
                        <label for="start_date" class="text-sm font-semibold">Start Date</label>
                        <x-form.input-group>
                            <x-form.input-date max="{{ $max_date }}" x-on:input="$wire.setMinDate($el.value)" id="start_date" name="start_date" label="Start Date" wire:model.live='start_date' class="w-full" />
                            <x-form.input-error field="start_date" />
                        </x-form.input-group>
                    </di>
                    
                    <di class="space-y-3">
                        <label for="end_date" class="text-sm font-semibold">End Date</label>
                        <x-form.input-group>
                            <x-form.input-date x-bind:disabled="start_date == '' || start_date == null" min="{{ !empty($min_date) ? $min_date : '' }}" id="end_date" name="end_date" label="End Date" wire:model.live='end_date' class="w-full" />
                            <x-form.input-error field="end_date" />
                        </x-form.input-group>
                    </di>
                </div>
            </div>
        </template>

        <template x-if='type === "daily reservations"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Daily Reservations</h2>
                    <p class="text-sm">Select the date of the reservations you want to get.</p>
                </hgroup>
    
                <div class="space-y-3">
                    <label for="start_date" class="text-sm font-semibold">Reservation Date</label>
                    <x-form.input-group>
                        <x-form.input-date id="start_date" name="start_date" label="Start Date" wire:model.live='start_date' class="w-full" />
                        <x-form.input-error field="start_date" />
                    </x-form.input-group>
                </div>
            </div>
        </template>

        <template x-if='type === "occupancy report"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Occupancy Report</h2>
                    <p class="text-sm">Select a room type and the date range for the occupancy report.</p>
                </hgroup>

                <x-form.select id="room_id" name="room_id" wire:model.live='room_id' class="w-full">
                    <option value="">Select a Room Type</option>
                    @foreach ($room_types as $room)
                        <option key="{{ $room->id }}" value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </x-form.select>
    
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-3">
                        <label for="start_date" class="text-sm font-semibold">Start Date</label>
                        <x-form.input-group>
                            <x-form.input-date x-on:input="$wire.setMinDate($el.value)" id="start_date" name="start_date" label="Start Date" wire:model.live='start_date' class="w-full" />
                            <x-form.input-error field="start_date" />
                        </x-form.input-group>
                    </div>
                    
                    <div class="space-y-3">
                        <label for="end_date" class="text-sm font-semibold">End Date</label>
                        <x-form.input-group>
                            <x-form.input-date x-bind:disabled="start_date == '' || start_date == null" min="{{ !empty($min_date) ? $min_date : '' }}" id="end_date" name="end_date" label="End Date" wire:model.live='end_date' class="w-full" />
                            <x-form.input-error field="end_date" />
                        </x-form.input-group>
                    </div>
                </div>
            </div>
        </template>

        <template x-if='type === "financial report"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Financial Report</h2>
                    <p class="text-sm">Select the date range for the financial report.</p>
                </hgroup>
    
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-3">
                        <label for="start_date" class="text-sm font-semibold">Start Date</label>
                        <x-form.input-group>
                            <x-form.input-date x-on:input="$wire.setMinDate($el.value)" id="start_date" name="start_date" label="Start Date" wire:model.live='start_date' class="w-full" />
                            <x-form.input-error field="start_date" />
                        </x-form.input-group>
                    </div>
                    
                    <div class="space-y-3">
                        <label for="end_date" class="text-sm font-semibold">End Date</label>
                        <x-form.input-group>
                            <x-form.input-date x-bind:disabled="start_date == '' || start_date == null" min="{{ !empty($min_date) ? $min_date : '' }}" id="end_date" name="end_date" label="End Date" wire:model.live='end_date' class="w-full" />
                            <x-form.input-error field="end_date" />
                        </x-form.input-group>
                    </div>
                </div>
            </div>
        </template>

        {{-- Additional Note --}}
        <label for="note" class="block text-sm font-semibold">Additional Note (optional)</label>
        <x-form.textarea id="note" name="note" label="Additional Note (Optional)" rows='4' wire:model.live='note' class="w-full" />
        <x-form.input-error field="note" />

        <div class="flex gap-3">
            <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
            <x-primary-button>Generate Report</x-primary-button>
        </div>
    </div>

    <div x-show='! type' class="space-y-5">
        <div class="p-5 text-center border rounded-md border-slate-200">
            <p class="text-sm text-slate-500">Please select a report type to continue</p>
        </div>
        
        <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
    </div>
</form>