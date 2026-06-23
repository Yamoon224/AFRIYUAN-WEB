<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = [
        'code', 'name', 'symbol', 'decimal_places', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'decimal_places' => 'integer',
    ];

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'preferred_currency_id');
    }

    public function transferLimits(): HasMany
    {
        return $this->hasMany(TransferLimit::class);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }
}
