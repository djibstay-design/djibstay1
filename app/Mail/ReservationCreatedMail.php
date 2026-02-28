<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation,
        public bool $forAdmin = false
    ) {
        $this->reservation->load('chambre.typeChambre.hotel');
    }

    public function envelope(): Envelope
    {
        $sujet = $this->forAdmin
            ? 'Nouvelle réservation #'.$this->reservation->code_reservation
            : 'Confirmation de votre réservation #'.$this->reservation->code_reservation;

        return new Envelope(
            subject: $sujet,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: $this->forAdmin ? 'emails.reservation-created-admin' : 'emails.reservation-created-client',
        );
    }
}
