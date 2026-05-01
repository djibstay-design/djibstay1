<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const KIND_DEPOSIT = 'acompte';

    public const KIND_BALANCE = 'solde';

    protected $fillable = [
        'reservation_id',
        'payment_kind',
        'payment_method',
        'amount',
        'currency',
        'transaction_id',
        'transaction_sms_code',
        'status',
        'paid_at',
        'notes',
        'sender_name',
        'sender_phone',
        'screenshot',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
