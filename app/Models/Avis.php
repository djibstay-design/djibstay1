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
        'reponse_admin',
        'reponse_admin_at',
        'reponse_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_avis' => 'date',
            'reponse_admin_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function reponseAdminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reponse_admin_user_id');
    }
}
