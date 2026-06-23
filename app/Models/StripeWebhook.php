<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeWebhook extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'stripe_event_id', 'event_type', 'payload',
        'processed', 'processed_at', 'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function markProcessed(): void
    {
        $this->update(['processed' => true, 'processed_at' => now()]);
    }

    public function markFailed(string $message): void
    {
        $this->update(['error_message' => $message]);
    }
}
