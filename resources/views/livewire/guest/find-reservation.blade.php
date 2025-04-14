<div x-data="{ focusSearch() { document.getElementById('reservation_id').focus(); } }">
    @if ($is_authorized != 'authorized')
        <form wire:submit="submit" class="relative flex items-start justify-center max-w-sm gap-1 mx-auto mb-5">
            <div class="space-y-3">
                <x-form.input-search maxlength="12"  wire:model="reservation_id" label="Reservation ID" id="reservation_id" />
                <x-form.input-error field="reservation_id" />
            </div>

            <x-primary-button type="submit">Search</x-primary-button>
        </form>
    @endif

    {{-- Reservation Details --}}
    @if (!empty($reservation_id) && !isset($_GET['reservation_id']))
        @if (!empty($reservation))
            @if ($is_authorized == 'authorized')
                <div class="space-y-5">
                    <div class="flex gap-1">
                        <x-secondary-button wire:click='downloadPdf'>Download PDF</x-secondary-button>
                        @if ($reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT)
                            <x-primary-button x-on:click="$dispatch('open-modal', 'submit-payment-modal')">Submit Payment</x-primary-button>
                        @endif
                    </div>

                    <div class="space-y-5">
                        <div class="p-5 space-y-5 border rounded-lg border-slate-200">
                            <hgroup class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-blue-500">{{ $reservation->rid }}</h3>
                                    <p class="text-xs">Reservation ID</p>
                                </div>
                                <div class="space-x-3">
                                    <strong class="text-xs">Status: </strong>
                                    <x-status type="reservation" :status="$reservation->status" />
                                </div>
                            </hgroup>
                        </div>

                        @if ($expires_at)
                            <x-warning-message>
                                <div>
                                    <h2 class="text-lg font-semibold">This reservation is awaiting payment!</h2>
                                    <p class="text-sm">Your reservation is only valid until <strong>{{ $expires_at }}</strong>. Click the 'Submit Payment' button above to proceed.</p>
                                </div>
                            </x-warning-message>
                        @endif

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="p-5 space-y-5 border rounded-lg border-slate-200">
                                <h3 class="font-semibold">Guest Details</h3>
                                
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div>
                                        <p class="text-sm font-semibold capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</p>
                                        <p class="text-xs">Name</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold capitalize">{{ $reservation->user->phone }}</p>
                                        <p class="text-xs">Phone Number</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 space-y-5 border rounded-lg border-slate-200">
                                <h3 class="font-semibold">Reservation Details</h3>
                                
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div>
                                        <p class="text-sm font-semibold capitalize">
                                            {{ date_format(date_create($reservation->date_in), 'F j, Y') }}
                                            {{ $reservation->date_in == $reservation->date_out ? '- 8:00 AM' : ' - 2:00 PM' }}
                                        </p>
                                        <p class="text-xs">Check-in Date</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold capitalize">
                                            {{ date_format(date_create($reservation->date_out), 'F j, Y') }}
                                            {{ $reservation->date_in == $reservation->date_out ? '- 6:00 PM' : ' - 12:00 PM' }}
                                        </p>
                                        <p class="text-xs">Check-out Date</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 space-y-5 border rounded-lg border-slate-200">
                            <hgroup>
                                <h2 class="font-semibold">Reservation Breakdown</h2>
                                <p class="text-xs">View your running bill here</p>
                            </hgroup>
                            
                            <livewire:app.reservation-breakdown :reservation="$reservation">
                        </div>
                    </div>
                </div>
            @else
                @if ($remaining_otp > 0)
                    <div class="max-w-sm p-5 mx-auto text-center border rounded-lg border-slate-200">
                        <button class="text-sm font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'enter-otp')">Enter OTP</button>
                    </div>
                @endif
            @endif
        @else
            <div class="mt-5">
                <p class="mb-3 text-4xl text-center">🤷</p>
                <p class="text-2xl font-semibold text-center text-red-500">No Reservation Found!</p>
                <p class="max-w-sm mx-auto text-center">There are no reservations with that Reservation ID. If this is a mistake, kindly contact us <a href="{{ route('guest.contact') }}" class="text-blue-500 underline underline-offset-2" wire:navigate>here</a>.</p>
                <div class="mx-auto mt-3 w-max">
                    <x-secondary-button wire:click="resetSearch" x-on:click="focusSearch">Try Again</x-secondary-button>
                </div>
            </div>
        @endif
    @endif

    <x-modal.full name='submit-payment-modal' maxWidth='sm'>
        <form wire:submit='submitPayment' class="p-5 space-y-5" x-on:payment-submitted.window="show = false">
            <hgroup>
                <h3 class="text-lg font-semibold">Submit Payment</h3>
                <p class="text-sm text-justify">Upload an image of your receipt here to process your reservation, you will receive an email once your reservation is confirmed.</p>
            </hgroup>

            <x-form.input-group>
                <x-filepond::upload
                    wire:model.live="proof_image_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />
                <x-form.input-error field="proof_image_path" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='payment_date'>Payment Date</x-form.input-label>
                <x-form.input-date wire:model.live='payment_date' id="payment_date" name="payment_date" label="payment_date" class="w-full" />
                <x-form.input-error field="payment_date" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='amount'>Enter Amount Paid</x-form.input-label>
                <x-form.input-currency wire:model.live='amount' id="amount" name="amount" label="amount" />
                <x-form.input-error field="amount" />
            </x-form.input-group>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Submit</x-primary-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='enter-otp' maxWidth='sm'>
        <form x-on:otp-checked.window="show = false" wire:submit.prevent="checkOtp" autocomplete="off" class="p-5 space-y-5">
            <hgrpup>
                <h3 class="text-lg font-bold">Verify Reservation</h3>
                <p class="text-sm">A one-time-pin has been sent to your email: {{ $encrypted_email }}</p>
            </hgrpup>

            <x-note>
                <div class="space-y-3">
                    <p>You are only allowed to request an OTP five (5) times per day.</p>
                    <p wire:key='{{ $remaining_otp }}'>Remaining attempts: {{ $remaining_otp }}</p>
                </div>
            </x-note>

            @if (!$otp_expired)
                <div class="space-y-2" x-data="{
                    isNumber(input, nextInput) { 
                        const numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

                        if (numbers.includes(input)) {
                            if (nextInput == null) {
                                $wire.checkOtp();
                            } else {
                                nextInput.focus();
                            }
                        }
                    } }">
                    <label for="otp1" class="block text-sm font-semibold">Enter your OTP here</label>
                    <div class="grid grid-cols-6 gap-1">
                        <input type="text" name="otp1" id="otp1" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_1"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, $refs.otp2) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp1.focus() : '' }"
                            x-ref="otp1" />
                        <input type="text" name="otp2" id="otp2" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_2"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, $refs.otp3) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp1.focus() : '' }"
                            x-ref="otp2" />
                        <input type="text" name="otp3" id="otp3" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_3"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, $refs.otp4) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp2.focus() : '' }"
                            x-ref="otp3" />
                        <input type="text" name="otp4" id="otp4" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_4"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, $refs.otp5) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp3.focus() : '' }"
                            x-ref="otp4" />
                        <input type="text" name="otp5" id="otp5" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_5"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, $refs.otp6) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp4.focus() : '' }"
                            x-ref="otp5" />
                        <input type="text" name="otp6" id="otp6" class="text-2xl font-bold text-center text-blue-500 border rounded-md border-slate-200 aspect-square"
                            wire:model="otp_input.otp_6"
                            x-mask="9"
                            x-on:input="(e) => { isNumber(e.data, null) }"
                            x-on:keyup="(e) => { e.key === 'Backspace' ? $refs.otp5.focus() : '' }"
                            x-on:input="$wire.checkOtp();"
                            x-ref="otp6" />
                    </div>    

                    <div class="flex justify-between">
                        <p x-data="{ timer: @entangle('timer'), interval: null }"
                            x-init="interval = setInterval(() => {
                                    if(timer > 0) {
                                        timer--;
                                    } else {
                                        clearInterval(interval);
                                        $dispatch('otp-expired');
                                    }
                                }, 1000)"
                            class="text-xs">
                            OTP Expires in
                            <span x-text="`${Math.floor(timer / 60)}:${String(timer % 60).padStart(2, '0')}`"></span>
                        </p>

                        <x-form.input-error field="otp_input" />
                    </div>
                </div>

                <x-loading wire:loading wire:target='checkOtp'>Checking your OTP, please wait</x-loading>

                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button wire:click='sendOtp()' type="button">Send another OTP</x-secondary-button>
                    <x-primary-button type='button' wire:click='checkOtp()'>Check OTP</x-primary-button>
                </div>
            @else
                <p class="text-sm font-semibold text-red-500">OTP has expired. Please request for another OTP.</p>
                <x-primary-button type='button' wire:click='sendOtp()'>Send another OTP</x-primary-button>
            @endif
        </form>
    </x-modal.full>
</div>