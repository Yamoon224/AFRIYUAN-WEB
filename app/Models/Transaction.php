<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'uuid', 'reference_number', 'user_id', 'beneficiary_id',
        'send_amount', 'send_currency', 'send_currency_symbol',
        'exchange_rate_id', 'applied_rate', 'fee_amount', 'fee_currency',
        'total_debit_amount', 'receive_amount', 'receive_currency',
        'payment_method', 'stripe_payment_intent_id', 'stripe_charge_id',
        'receive_method', 'payout_reference',
        'status', 'failure_reason', 'cancelled_by', 'cancelled_at',
        'compliance_status', 'compliance_notes',
        'initiated_at', 'payment_confirmed_at', 'processing_started_at', 'completed_at',
        'source_ip', 'user_agent', 'device_fingerprint',
    ];

    protected $casts = [
        'send_amount' => 'decimal:2',
        'applied_rate' => 'decimal:8',
        'fee_amount' => 'decimal:2',
        'total_debit_amount' => 'decimal:2',
        'receive_amount' => 'decimal:2',
        'initiated_at' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'processing_started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Transaction $transaction) {
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) Str::uuid();
            }
            if (empty($transaction->reference_number)) {
                $transaction->reference_number = self::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        return 'AY-' . now()->format('Ymd') . '-' . str_pad(
            (self::whereDate('created_at', today())->count() + 1),
            6, '0', STR_PAD_LEFT
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function exchangeRate(): BelongsTo
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(TransactionStatusLog::class);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFlagged($query)
    {
        return $query->where('compliance_status', 'flagged');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['initiated', 'payment_pending']);
    }
}
