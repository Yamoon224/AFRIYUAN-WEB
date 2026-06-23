<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferLimit extends Model
{
    protected $fillable = [
        'country_id', 'kyc_level', 'period', 'currency_id',
        'min_amount', 'max_amount', 'is_active',
    ];

    protected $casts = [
        'kyc_level' => 'integer',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
