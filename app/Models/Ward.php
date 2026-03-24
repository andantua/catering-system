<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ward extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact_person',
    ];

    /**
     * Relacja: oddział ma wiele tokenów
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(OrderToken::class);
    }

    /**
     * Relacja: oddział ma wiele zamówień
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relacja: oddział może mieć wiele diet (przez tabelę pośrednią)
     * Opcjonalnie – jeśli chcesz ograniczać diety dla oddziału
     */
    public function diets(): BelongsToMany
    {
        return $this->belongsToMany(Diet::class, 'ward_diet');
    }

    /**
     * Sprawdza czy oddział złożył już zamówienie na dziś
     */
    public function hasSubmittedToday(): bool
    {
        return $this->orders()
            ->whereDate('order_date', today())
            ->whereNotNull('submitted_at')
            ->exists();
    }

    /**
     * Pobiera dzisiejsze zamówienie (wersję roboczą lub zatwierdzoną)
     */
    public function getTodayOrder()
    {
        return $this->orders()
            ->whereDate('order_date', today())
            ->first();
    }
}