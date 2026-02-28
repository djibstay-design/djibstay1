<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'nom_client',
        'email_client',
        'note',
        'commentaire',
        'date_avis',
        'hotel_id',
    ];

    protected function casts(): array
    {
        return [
            'date_avis' => 'date',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
