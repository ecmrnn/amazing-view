<?php

namespace App\Mail\reservation;

use App\Models\Reservation;
use App\Models\RoomAmenity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class Received extends Mailable
{
    use Queueable, SerializesModels;

    public $has_amenities = false;
    public $token = null;
    
    /**
     * Create a new message instance.
     */
    public function __construct(public Reservation $reservation)
    {
        $this->has_amenities = RoomAmenity::where('reservation_id', $reservation->id)->exists();
        $this->token = Password::createToken($reservation->user);
        logger($this->token);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Received | Amazing View Mountain Resort',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation.received',
        );      
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
