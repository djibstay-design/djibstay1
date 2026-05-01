<?php
namespace App\Mail;

use App\Models\DemandePartenaire;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartenaireInvitationMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public DemandePartenaire $demande,
        public string $token
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre invitation à rejoindre '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.partenaire.invitation',
            with: [
                'lien' => url('/partenaire/inscription/'.$this->token),
                'nom'  => $this->demande->nom_contact,
                'expiration' => $this->demande->token_expire_le->format('d/m/Y'),
            ]
        );
    }
}