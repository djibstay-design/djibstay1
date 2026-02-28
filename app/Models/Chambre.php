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
}
