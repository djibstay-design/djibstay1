<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chambre extends Model
{
    protected $fillable = [
        'numero',
        'etat',
        'type_id',
    ];

    public function typeChambre(): BelongsTo
    {
        return $this->belongsTo(TypeChambre::class, 'type_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'chambre_id');
    }

    /**
     * Vérifier si la chambre est disponible pour une plage de dates
     */
    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        // La chambre doit être en état DISPONIBLE
        if ($this->etat !== 'DISPONIBLE') {
            return false;
        }

        // Vérifier qu'il n'y a pas de réservations confirmées ou en attente pour cette période
        return ! $this->reservations()
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<', $checkOut)
            ->where('date_fin', '>', $checkIn)
            ->exists();
    }

    /**
     * Obtenir les dates indisponibles pour un mois donné
     */
    public function getUnavailableDates(\DateTime $month): array
    {
        $monthStart = clone $month;
        $monthStart->modify('first day of this month');
        
        $monthEnd = clone $month;
        $monthEnd->modify('last day of this month');

        $unavailableDates = [];

        $reservations = $this->reservations()
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<=', $monthEnd->format('Y-m-d'))
            ->where('date_fin', '>', $monthStart->format('Y-m-d'))
            ->get();

        foreach ($reservations as $reservation) {
            $currentDate = clone $monthStart;
            while ($currentDate <= $monthEnd && $currentDate < $reservation->date_fin) {
                if ($currentDate >= $reservation->date_debut) {
                    $unavailableDates[] = $currentDate->format('Y-m-d');
                }
                $currentDate->modify('+1 day');
            }
        }

       return array_unique($unavailableDates);
    }
}