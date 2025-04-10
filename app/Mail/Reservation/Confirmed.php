<?php

namespace App\Mail\Reservation;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Confirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $refund_date;
    
    /**
     * Create a new message instance.
     */
    public function __construct(public Reservation $reservation)
    {
        $this->refund_date = Carbon::parse($reservation->created_at)->subWeek();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Confirmed | Amazing View Mountain Resort',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation.confirmed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // $filename = $this->reservation->rid . ' - ' . strtoupper($this->reservation->user->last_name) . '_' . strtoupper($this->reservation->user->first_name) . '.pdf';
        // $path = 'storage/app/public/pdf/reservation/' . $filename;

        // return [
        //     Attachment::fromPath($path)
        // ];
    }
}
