<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'nom_client',
        'prenom_client',
        'email_client',
        'telephone_client',
        'code_identite',
        'chambre_id',
        'date_reservation',
        'date_debut',
        'date_fin',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'photos',
        'statut',
        'code_reservation',
    ];

    protected function casts(): array
    {
        return [
            'date_reservation' => 'date',
            'date_debut' => 'date',
            'date_fin' => 'date',
            'prix_unitaire' => 'decimal:2',
            'montant_total' => 'decimal:2',
        ];
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class);
    }

    public function getPhotosListAttribute(): array
    {
        if (empty($this->photos)) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->photos)));
    }
}
