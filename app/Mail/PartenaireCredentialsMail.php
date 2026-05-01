<?php
namespace App\Mail;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartenaireCredentialsMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $password,
        public Hotel $hotel
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos identifiants — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.partenaire.credentials',
            with: [
                'nom'      => $this->user->name,
                'email'    => $this->user->email,
                'password' => $this->password,
                'hotel'    => $this->hotel->nom,
                'lien'     => url('/login'),
            ]
        );
    }
}