<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'ward_id',
        'diet_id',
        'quantity',
        'order_date',
        'submitted_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'submitted_at' => 'datetime',
        'quantity' => 'integer',
    ];

    /**
     * Relacja: zamówienie należy do oddziału
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Relacja: zamówienie należy do diety
     */
    public function diet(): BelongsTo
    {
        return $this->belongsTo(Diet::class);
    }

    /**
     * Sprawdza czy zamówienie jest zatwierdzone
     */
    public function isSubmitted(): bool
    {
        return !is_null($this->submitted_at);
    }
}