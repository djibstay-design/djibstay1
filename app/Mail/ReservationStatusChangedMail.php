<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation,
        public string $ancienStatut
    ) {
        $this->reservation->load('chambre.typeChambre.hotel');
    }

    public function envelope(): Envelope
    {
        $statutLabel = match ($this->reservation->statut) {
            'CONFIRMEE' => 'confirmée',
            'ANNULEE' => 'annulée',
            default => 'en attente',
        };

        return new Envelope(
            subject: 'Réservation #'.$this->reservation->code_reservation.' - Statut : '.$statutLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-status-changed',
        );
    }
}
