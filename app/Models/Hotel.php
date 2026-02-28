<?php

namespace App\Models;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typesChambre(): HasMany
    {
        return $this->hasMany(TypeChambre::class, 'hotel_id');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class, 'hotel_id');
    }
}
