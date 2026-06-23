<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferFee extends Model
{
    protected $fillable = [
        'from_country_id', 'from_currency', 'to_currency',
        'min_amount', 'max_amount', 'fee_type',
        'fixed_fee', 'percentage_fee', 'min_fee', 'max_fee', 'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fixed_fee' => 'decimal:2',
        'percentage_fee' => 'decimal:2',
        'min_fee' => 'decimal:2',
        'max_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function fromCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'from_country_id');
    }

    public function calculate(float $amount): float
    {
        return match ($this->fee_type) {
            'fixed' => (float) $this->fixed_fee,
            'percentage' => max(
                (float) $this->min_fee,
                min($amount * ($this->percentage_fee / 100), (float) ($this->max_fee ?? PHP_FLOAT_MAX))
            ),
            'mixed' => (float) $this->fixed_fee + max(
                (float) $this->min_fee,
                min($amount * ($this->percentage_fee / 100), (float) ($this->max_fee ?? PHP_FLOAT_MAX))
            ),
        };
    }
}
