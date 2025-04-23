<?php

namespace App\Livewire\Guest;

use App\Enums\ReservationStatus;
use App\Http\Controllers\OTP\MailOtp;
use App\Models\Otp;
use App\Models\Reservation;
use App\Models\RoomReservation;
use App\Services\BillingService;
use App\Services\ReservationService;
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
    
    protected $listeners = [
        'otp-sent' => '$refresh'
    ];

    #[Url] public $reservation_id;
    public $reservation;
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
    public $otp_per_day = 4;
    public $remaining_otp;
    public $timer = 300;

    #[Validate] public $proof_image_path;
    #[Validate] public $amount;
    #[Validate] public $payment_date;

    public function rules() {
        return [
            'reservation_id' => 'required',
            'proof_image_path' => 'required|mimes:jpg,jpeg,png|file|max:1000',
            'payment_date' => 'required|date',
            'amount' => 'required|integer|gte:500',
        ];
    }

    public function messages() {
        return [
            'proof_image_path.required' => 'Upload your image here',
        ];
    }

    public function checkOtp() {
        $otp = implode($this->otp_input);
        
        if (implode($this->otp_input) >= 100000 && implode($this->otp_input) <= 999999) {
            if (MailOtp::check($this->reservation->user->email, $otp)) {
                $this->is_authorized = 'authorized';
                $this->toast('Success!', 'success', 'OTP is correct.');
                $this->dispatch('otp-checked');
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
    
    public function sendOtp() {
        $otp_record = Otp::where('email', $this->reservation->user->email)
            ->first();

        if ($otp_record && $otp_record->request_count > $this->otp_per_day) {
            $this->toast('OTP Not Sent', 'warning', 'You have reached the maximum OTP requests per day.');
            return;
        }

        $this->remaining_otp = $otp_record ? ($this->otp_per_day - $otp_record->request_count) : $this->otp_per_day;

        $this->otp = MailOtp::send($this->reservation->user->email);
        $this->toast('Success!', description: 'OTP sent successfully.');
        $this->dispatch('otp-sent');
        $this->timer = 300;
        $this->otp_expired = false;
    }

    public function resetSearch() {
        $this->reset();
    }

    public function downloadPdf() {
        $service = new ReservationService;
        $pdf = $service->downloadPdf($this->reservation);

        if (!$pdf) {
            $this->toast('Generating PDF', 'info', 'Please wait for a few seconds and then try again.');
        } else {
            $this->toast('Downloading PDF', description: 'Stay online while we download your file!');
            return $pdf;
        }
    }

    public function submitPayment() {
        $payment = $this->validate([
            'proof_image_path' => $this->rules()['proof_image_path'],
            'amount' => $this->rules()['amount'],
            'payment_date' => $this->rules()['payment_date'],
        ]);

        $service = new BillingService;
        $service->addPayment($this->reservation->invoice, $payment);

        $this->toast('Success!', 'success', 'Payment submitted successfully.');
        $this->reset('proof_image_path', 'expires_at');
        $this->dispatch('payment-submitted');
    }

    public function submit() {
        $this->validate(['reservation_id' => $this->rules()['reservation_id']]);

        $this->reservation = Reservation::where('rid', $this->reservation_id)->first();
        
        if ($this->reservation != null) {
            $otp_record = Otp::where('email', $this->reservation->user->email)
                ->first();

            if ($otp_record && $otp_record->request_count > $this->otp_per_day) {
                $this->toast('OTP Not Sent', 'warning', 'You have reached the maximum OTP requests per day.');
                return;
            }

            $this->encrypted_email = preg_replace('/(?<=...).(?=.*@)/u', '*', $this->reservation->user->email);

            if (!empty($this->reservation->expires_at)) {
                $this->expires_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->reservation->expires_at)->format('F d, Y \a\t h:i A');
            }

            if (empty($this->otp)) {
                $this->sendOtp();
            }

            $this->reset('is_authorized', 'email');
            $this->dispatch('open-modal', 'enter-otp');
        }
    }

    public function render()
    {
        return view('livewire.guest.find-reservation');
    }
}
