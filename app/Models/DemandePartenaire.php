<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandePartenaire extends Model
{
    use HasFactory;

    protected $table = 'demande_partenaires';

    protected $fillable = [
        'nom_contact',
        'email_contact',
        'telephone',
        'nom_hotel',
        'ville',
        'description',
        'nombre_chambres',
        'site_web',
        'message',
        'statut',
        'lien_formulaire',
        'formulaire_rempli',
        'valide_le',
        'token_invitation',
        'invitation_envoyee_le',
        'token_expire_le',
        'traite_par',
        'notes_admin',
    ];

    protected $casts = [
        'formulaire_rempli'     => 'boolean',
        'valide_le'             => 'datetime',
        'invitation_envoyee_le' => 'datetime',
        'token_expire_le'       => 'datetime',
    ];

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function isTokenValid(): bool
    {
        return $this->token_invitation
            && $this->token_expire_le
            && $this->token_expire_le->isFuture();
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'en_attente'   => '⏳ En attente',
            'en_discussion'=> '💬 En discussion',
            'valide'       => '✅ Validé',
            'refuse'       => '❌ Refusé',
            default        => $this->statut,
        };
    }

    public function statutColor(): string
    {
        return match($this->statut) {
            'en_attente'    => '#f59e0b',
            'en_discussion' => '#0071c2',
            'valide'        => '#16a34a',
            'refuse'        => '#dc2626',
            default         => '#64748b',
        };
    }
}