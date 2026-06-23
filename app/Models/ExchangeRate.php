<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExchangeRate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'from_currency', 'to_currency', 'rate', 'margin_rate',
        'margin_percent', 'source', 'fetched_at', 'expires_at',
    ];

    protected $casts = [
        'rate' => 'decimal:8',
        'margin_rate' => 'decimal:8',
        'margin_percent' => 'decimal:2',
        'fetched_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }

    public function scopeCurrent($query, string $from, string $to)
    {
        return $query->where('from_currency', $from)
                     ->where('to_currency', $to)
                     ->where('expires_at', '>', now())
                     ->latest('fetched_at');
    }
}
