<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'description',
        'user_id',
        'admin_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function typesChambre(): HasMany
    {
        return $this->hasMany(TypeChambre::class, 'hotel_id');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class, 'hotel_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class)->orderBy('sort_order');
    }

    public function mainImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HotelImage::class)->where('is_main', true)->latest();
    }

    /**
     * Au moins $minRooms chambre(s) DISPONIBLE(s), type avec capacité >= $minTypeCapacite,
     * sans réservation CONFIRMEE/EN_ATTENTE qui chevauche [checkIn, checkOut).
     * (arrivée incluse, départ exclu — cohérent avec la recherche « nuits ».)
     */
    public function scopeWithRoomAvailableBetween(Builder $query, string $checkIn, string $checkOut, int $minRooms = 1, int $minTypeCapacite = 1): Builder
    {
        $minRooms = max(1, (int) $minRooms);
        $minTypeCapacite = max(1, (int) $minTypeCapacite);

        return $query->whereHas('typesChambre', function ($q) use ($checkIn, $checkOut, $minRooms, $minTypeCapacite) {
            $q->where('capacite', '>=', $minTypeCapacite)
              ->whereRaw('(
                  (SELECT COUNT(*) FROM chambres WHERE chambres.type_id = types_chambre.id AND chambres.etat = ?) - 
                  (SELECT COALESCE(SUM(quantite), 0) FROM reservations 
                   INNER JOIN chambres ON chambres.id = reservations.chambre_id
                   WHERE chambres.type_id = types_chambre.id 
                   AND reservations.statut IN (?, ?)
                   AND reservations.date_debut < ?
                   AND reservations.date_fin > ?)
              ) >= ?', ['DISPONIBLE', 'CONFIRMEE', 'EN_ATTENTE', $checkOut, $checkIn, $minRooms]);
        });
    }
}
