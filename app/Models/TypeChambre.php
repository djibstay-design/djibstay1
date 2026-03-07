<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeChambre extends Model
{
    protected $table = 'types_chambre';

    protected $fillable = [
        'nom_type',
        'capacite',
        'description',
        'prix_par_nuit',
        'hotel_id',
    ];

    protected function casts(): array
    {
        return [
            'prix_par_nuit' => 'decimal:2',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function chambres(): HasMany
    {
        return $this->hasMany(Chambre::class, 'type_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'type_chambre_id')->orderBy('sort_order');
    }
}
