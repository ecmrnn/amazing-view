<?php

namespace App\Mail\Reservation;

use App\Models\Reservation;
use App\Models\RoomAmenity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Updated extends Mailable
{
    use Queueable, SerializesModels;
    public $has_amenities = false;

    /**
     * Create a new message instance.
     */
    public function __construct(public Reservation $reservation)
    {
        $this->has_amenities = RoomAmenity::where('reservation_id', $reservation->id)->exists();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Updated | Amazing View Mountain Resort',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation.updated',
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
