<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderToken extends Model
{
    protected $fillable = [
        'ward_id',
        'token',
        'code',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Relacja: token należy do oddziału
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Generuje nowy token dla oddziału
     */
    public static function generateForWard(Ward $ward): self
    {
        return self::create([
            'ward_id' => $ward->id,
            'token' => Str::random(32),
            'code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addHours(5),
        ]);
    }

    /**
     * Sprawdza czy token jest ważny (nieużyty i nie wygasł)
     */
    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    /**
     * Oznacza token jako użyty
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}