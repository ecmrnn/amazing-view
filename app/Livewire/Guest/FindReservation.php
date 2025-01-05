<?php

namespace App\Livewire\Guest;

use App\Http\Controllers\OTP\MailOtp;
use App\Models\Reservation;
use App\Models\RoomReservation;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class FindReservation extends Component
{
    use DispatchesToast, WithFilePond;

    public $reservation_id;
    public $reservation;
    public $selected_rooms;
    public $selected_amenities = [];
    public $vat = 0;
    public $vatable_sales = 0;
    public $net_total = 0;
    public $sub_total = 0;
    public $night_count;
    public $discount_amount = 0;
    #[Url] public $rid;
    // Operations
    public $is_authorized = null;
    public $email;
    public $encrypted_email;
    public $expires_at;
    public $otp;
    public $otp_input = [
        'otp_1' => '',
        'otp_2' => '',
        'otp_3' => '',
        'otp_4' => '',
        'otp_5' => '',
        'otp_6' => '',
    ];
    public $otp_expired = false;
    public $timer = 300;

    #[Validate] public $proof_image_path;

    public function mount() {
        $this->reservation = new Collection;
        $this->selected_rooms = new Collection;

        if (!empty($this->rid)) {
            $this->reservation_id = $this->rid;
            $this->getReservation();
        }
    }

    public function checkOtp() {
        $otp = implode($this->otp_input);
        
        if (implode($this->otp_input) >= 100000 && implode($this->otp_input) <= 999999) {
            if (MailOtp::check($this->reservation->email, $otp)) {
                $this->is_authorized = 'authorized';
                $this->toast('Success!', 'success', 'OTP is correct.');
            } else {
                $this->is_authorized = 'unauthorized';
                $this->otp_input = [
                    'otp_1' => '',
                    'otp_2' => '',
                    'otp_3' => '',
                    'otp_4' => '',
                    'otp_5' => '',
                    'otp_6' => '',
                ];
                $this->addError('otp_input', 'Incorrect OTP.');
                $this->toast('Error!', 'warning', 'Incorrect OTP.');
            }
        } else {
            $this->toast('Error!', 'warning', 'Invalid OTP.');
        }
    }

    #[On('otp-expired')]
    public function otpExires() {
        $this->otp_expired = true;
    }

    public function resetOtp() {
        $this->reset('otp_input', 'is_authorized');
    }

    public function rules() {
        return [
            'reservation_id' => 'required',
        ];
    }

    public function getReservation() {
        $this->validate(['reservation_id' => $this->rules()['reservation_id']]);

        $this->vat = 0;
        $this->net_total = 0;
        $this->sub_total = 0;
        $this->reservation = Reservation::where('rid', $this->reservation_id)->first();
        
        if ($this->reservation != null) {
            $this->selected_rooms = Reservation::find($this->reservation['id'])->rooms;
            $this->selected_amenities = Reservation::find($this->reservation['id'])->amenities;

            // Get the number of nights between 'date_in' and 'date_out'
            $this->night_count = Carbon::parse($this->reservation['date_in'])->diffInDays(Carbon::parse($this->reservation['date_out']));
            // If 'date_in' == 'date_out', 'night_count' = 1
            $this->night_count != 0 ?: $this->night_count = 1;

            foreach ($this->selected_rooms as $room) {
                $this->sub_total += ($room->rate * $this->night_count);
            }

            foreach ($this->selected_amenities as $amenity) {
                $this->sub_total += $amenity->price;
            }

            $this->vatable_sales = $this->sub_total / 1.12;
            $this->vat = ($this->sub_total) - $this->vatable_sales;
            $this->net_total = $this->vatable_sales + $this->vat;
            $this->encrypted_email = preg_replace('/(?<=...).(?=.*@)/u', '*', $this->reservation->email);

            if (!empty($this->reservation->expires_at)) {
                $this->expires_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->reservation->expires_at)->format('F d, Y \a\t h:i A');
            }

            $this->otp = MailOtp::send($this->reservation->email);
            
            $this->reset('is_authorized', 'email');
        }
    }
    
    public function sendOtp() {
        MailOtp::send($this->reservation->email);
        $this->toast('Success!', description: 'OTP sent successfully.');
        $this->dispatch('otp-sent');
        $this->timer = 300;
        $this->otp_expired = false;
    }

    public function resetSearch() {
        $this->reset();
    }

    public function submitPayment() {
        $this->validate([
            'proof_image_path' => 'required|image|max:1024',
        ]);

        $this->reservation->update([
            'status' => Reservation::STATUS_PENDING,
            'expires_at' => null,
            'proof_image_path' => $this->proof_image_path->store('downpayments', 'public'),
        ]);

        $this->toast('Success!', 'success', 'Payment submitted successfully.');
        $this->reset('proof_image_path');
        $this->dispatch('payment-submitted');
    }

    public function submit() {
        $this->getReservation();
    }

    public function render()
    {
        return view('livewire.guest.find-reservation');
    }
}
