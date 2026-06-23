<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name', 'iso_code', 'phone_prefix', 'currency_id',
        'is_source', 'is_destination', 'is_active', 'flag_url',
    ];

    protected $casts = [
        'is_source' => 'boolean',
        'is_destination' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function transferFees(): HasMany
    {
        return $this->hasMany(TransferFee::class, 'from_country_id');
    }

    public function transferLimits(): HasMany
    {
        return $this->hasMany(TransferLimit::class);
    }

    public function mobileMoneyAccounts(): HasMany
    {
        return $this->hasMany(MobileMoneyAccount::class);
    }
}
