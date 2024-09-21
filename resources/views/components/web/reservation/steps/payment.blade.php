<x-form.form-section>
    <x-form.form-header step="1" title="Reservation Summary" />

    <div class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <p class="text-sm">Kindly check if the information you provided is correct.</p>

                <div class="grid grid-cols-2 gap-5">
                    <div class="p-3 border rounded-lg">
                        <div>
                            {{-- Icon --}}
                        </div>
                        <div>
                            <h3 class="font-semibold">GCash Details</h3>
                            <p class="">+63 917 139 9334</p>
                            <p class="text-xs">Fabio Basbaño</p>
                        </div>
                    </div>
                </div>

                {{-- Reservation Breakdown --}}
                <div class="grid grid-cols-2 border rounded-lg">
                    <div class="px-3 py-2 space-y-2 border-r border-dashed">
                        <h4 class="font-semibold">Guest Details</h4>
                        <div class="space-y-1 text-xs">
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">ar_on_you</span><span>Juan Dela Cruz</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">cottage</span><span>410 Manila East Rd., Hulo, Pililla, Rizal</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">call</span><span>+63 958 5575 678</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">mail</span><span>delacruz.juan@gmail.com</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<div class="flex gap-3">
    <x-secondary-button>Guest Details</x-secondary-button>
    <x-primary-button>Send Reservation</x-primary-button>
</div>