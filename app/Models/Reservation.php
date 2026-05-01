<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Reservation extends Model
{
    /** Acompte exigé à la réservation (% du montant total). */
    public const DEPOSIT_PERCENT = 30;

    protected $fillable = [
        'user_id',
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
        'photo_carte',
        'photo_visage',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function depositDueAmount(): float
    {
        $percent = (float) \App\Models\SiteSetting::get('resa_acompte_percent', self::DEPOSIT_PERCENT);
        return round((float) $this->montant_total * $percent / 100, 2);
    }

    public function hasPaidDeposit(): bool
    {
        $required = $this->depositDueAmount();
        if ($required <= 0) {
            return true;
        }

        $paid = (float) $this->payments()
            ->where('status', 'accepted')
            ->where('payment_kind', Payment::KIND_DEPOSIT)
            ->sum('amount');

        return $paid + 0.005 >= $required;
    }

    public function getPhotosListAttribute(): array
    {
        if (empty($this->photos)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->photos)));
    }

    protected static function booted(): void
    {
        static::deleting(function (Reservation $reservation): void {
            if ($reservation->photo_carte) {
                Storage::disk('public')->delete($reservation->photo_carte);
            }
            if ($reservation->photo_visage) {
                Storage::disk('public')->delete($reservation->photo_visage);
            }
        });
    }
}
