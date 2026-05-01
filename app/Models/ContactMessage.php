<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'sujet',
        'message',
        'reponse',
        'lu',
        'archive',
        'source',
        'gmail_uid',
        'repondu_le',
        'repondu_par',
    ];

    protected $casts = [
        'lu'         => 'boolean',
        'archive'    => 'boolean',
        'repondu_le' => 'datetime',
    ];

    public function reponduPar()
    {
        return $this->belongsTo(User::class, 'repondu_par');
    }

    // ── Décodage MIME ──────────────────────────────────────────────────────
    private static function decodeMimeString(?string $str): string
    {
        if (empty($str)) return '';

        return preg_replace_callback(
            '/=\?([^?]+)\?([BbQq])\?([^?]*)\?=/',
            function ($match) {
                $charset  = strtoupper(trim($match[1]));
                $encoding = strtoupper($match[2]);
                $text     = $match[3];

                if ($encoding === 'B') {
                    $decoded = base64_decode($text);
                } else {
                    $decoded = quoted_printable_decode(
                        str_replace('_', ' ', $text)
                    );
                }

                if ($charset !== 'UTF-8') {
                    $decoded = mb_convert_encoding($decoded, 'UTF-8', $charset);
                }

                return $decoded;
            },
            $str
        ) ?? $str;
    }

    // ── Accessors — décodage automatique ──────────────────────────────────
    public function getNomAttribute($value): string
    {
        return self::decodeMimeString($value);
    }

    public function getSujetAttribute($value): string
    {
        return self::decodeMimeString($value);
    }

    public function getMessageAttribute($value): string
    {
        return self::decodeMimeString($value);
    }
}