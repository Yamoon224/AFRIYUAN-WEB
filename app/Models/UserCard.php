<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCard extends Model
{
    protected $fillable = [
        'user_id', 'stripe_payment_method_id', 'stripe_customer_id',
        'card_brand', 'last_four', 'exp_month', 'exp_year',
        'cardholder_name', 'billing_country', 'billing_zip',
        'fingerprint', 'funding', 'three_d_secure_usage',
        'is_default', 'is_active', 'last_used_at',
    ];

    protected $casts = [
        'exp_month' => 'integer',
        'exp_year' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return strtoupper($this->card_brand) . ' •••• ' . $this->last_four;
    }

    public function getExpiryAttribute(): string
    {
        return str_pad($this->exp_month, 2, '0', STR_PAD_LEFT) . '/' . $this->exp_year;
    }

    public function isExpired(): bool
    {
        return now()->year > $this->exp_year
            || (now()->year === $this->exp_year && now()->month > $this->exp_month);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
