<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomImage extends Model
{
    protected $fillable = [
        'type_chambre_id',
        'path',
        'sort_order',
    ];

    public function typeChambre(): BelongsTo
    {
        return $this->belongsTo(TypeChambre::class, 'type_chambre_id');
    }

    public function getUrlAttribute(): string
    {
        if (! $this->path) {
            return '';
        }
        if (str_starts_with($this->path, 'http') || str_starts_with($this->path, '/')) {
            return $this->path;
        }
        if (str_starts_with($this->path, 'images/')) {
            return asset($this->path);
        }
        return asset('storage/' . ltrim($this->path, '/'));
    }
}
