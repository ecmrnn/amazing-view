<form x-data="{ type: @entangle('type'), start_date: @entangle('start_date'), format: @entangle('format') }" class="grid gap-5" wire:submit='store()'>
    <hgroup x-show="! type">
        <h3 class="font-semibold">Generate Report</h3>
        <p class="text-xs">Choose a report type you want to generate.</p>
    </hgroup>

    <div x-show='type' class="flex items-center justify-between">
        <h1 class="text-xl font-semibold capitalize" x-text='type'></h1>
        <button type='button' class="text-xs font-semibold text-blue-500" x-on:click="$wire.set('type', ''); $wire.resetReportType()">Change Report</button>
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
                <option value="csv" x-show="type != 'occupancy report'">CSV</option>
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
        <template x-if='type === "{{ \App\Enums\ReportType::RESERVATION_SUMMARY->value }}"'>
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

        <template x-if='type === "{{ \App\Enums\ReportType::INCOMING_RESERVATIONS->value }}"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Incoming Reservation</h2>
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

        <template x-if='type === "{{ \App\Enums\ReportType::OCCUPANCY_REPORT->value }}"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Occupancy Report</h2>
                    <p class="text-sm">Select a room type and the date range for the occupancy report.</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.select id="room_type_id" name="room_type_id" wire:model.live='room_type_id' class="w-full">
                        <option value="">Select a Room Type</option>
                        @foreach ($room_types as $room)
                            <option key="{{ $room->id }}" value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input-error field="room_type_id" />
                </x-form.input-group>
    
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

        <template x-if='type === "{{ \App\Enums\ReportType::REVENUE_PERFORMANCE->value }}"'>
            <div class="p-5 space-y-3 border rounded-md border-slate-200">
                <hgroup>
                    <h2 class="text-sm font-semibold">Revenue Performance</h2>
                    <p class="text-sm">Select the date range for the revenue performance report.</p>
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

        <div class="flex justify-end gap-1">
            <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
            <x-primary-button wire:click="store()">Generate Report</x-primary-button>
        </div>
    </div>

    <div x-show='! type' class="space-y-5">
        {{-- <p class="text-sm">Please select a type of report to generate before you continue</p> --}}

        <button type="button" class="flex items-center justify-between w-full gap-5 p-5 text-left border rounded-md border-slate-200" x-on:click="$wire.set('type', '{{ \App\Enums\ReportType::RESERVATION_SUMMARY->value }}')">
            <div>
                <p class="font-semibold text-md">Reservation Summary</p>
                <p class="text-xs">Overview of total reservations over a period.</p>
            </div>
            <div class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>
            </div>
        </button>

        <button type="button" class="flex items-center justify-between w-full p-5 text-left border rounded-md border-slate-200" x-on:click="$wire.set('type', '{{ \App\Enums\ReportType::INCOMING_RESERVATIONS->value }}')">
            <div>
                <p class="font-semibold text-md">Incoming Reservation</p>
                <p class="text-xs">Daily check-ins, check-outs, and cancellations.</p>
            </div>
            <div class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-1"><path d="M11 14h1v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M8 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
            </div>
        </button>

        <button type="button" class="flex items-center justify-between w-full p-5 text-left border rounded-md border-slate-200" x-on:click="$wire.set('type', '{{ \App\Enums\ReportType::OCCUPANCY_REPORT->value }}')">
            <div>
                <p class="font-semibold text-md">Occupancy Report</p>
                <p class="text-xs">Room occupancy rates over a period.</p>
            </div>
            <div class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-key"><circle cx="16" cy="20" r="2"/><path d="M10 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v2"/><path d="m22 14-4.5 4.5"/><path d="m21 15 1 1"/></svg>
            </div>
        </button>

        <button type="button" class="flex items-center justify-between w-full p-5 text-left border rounded-md border-slate-200" x-on:click="$wire.set('type', '{{ \App\Enums\ReportType::REVENUE_PERFORMANCE->value }}')">
            <div>
                <p class="font-semibold text-md">Revenue Performance</p>
                <p class="text-xs">Total revenue generated by room type.</p>
            </div>
            <div class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-piggy-bank"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><path d="M16 11h.01"/></svg>
            </div>
        </button>
        
        <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
    </div>
</form>