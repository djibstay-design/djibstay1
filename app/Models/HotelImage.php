<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelImage extends Model
{
    protected $fillable = [
        'hotel_id',
        'path',
        'is_main',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getUrlAttribute(): string
    {
        if (! $this->path) {
            return '';
        }
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        // URL fiable : asset('storage/...') utilise le domaine actuel
        if (str_starts_with($this->path, '/')) {
            return $this->path;
        }
        if (str_starts_with($this->path, 'images/')) {
            return asset($this->path);
        }
        return asset('storage/' . ltrim($this->path, '/'));
    }
}
