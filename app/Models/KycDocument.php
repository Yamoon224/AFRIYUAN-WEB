<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycDocument extends Model
{
    protected $fillable = [
        'user_id', 'document_type', 'file_url', 'file_hash',
        'issued_country', 'document_number', 'issued_at', 'expires_at',
        'status', 'reviewer_id', 'reviewer_notes', 'reviewed_at',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewer_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
