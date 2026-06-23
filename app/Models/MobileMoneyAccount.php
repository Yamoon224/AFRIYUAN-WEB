<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileMoneyAccount extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'phone_number', 'country_id',
        'is_verified', 'is_default',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
