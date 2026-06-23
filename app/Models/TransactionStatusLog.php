<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'from_status', 'to_status',
        'changed_by_type', 'changed_by_id', 'notes', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
