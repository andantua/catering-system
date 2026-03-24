<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diet extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Relacja: dieta ma wiele zamówień
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relacja: dieta może być przypisana do wielu oddziałów
     */
    public function wards(): BelongsToMany
    {
        return $this->belongsToMany(Ward::class, 'ward_diet');
    }
}