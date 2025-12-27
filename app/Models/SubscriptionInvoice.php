<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionInvoice extends Model
{
    protected $fillable = [
        'subscription_id',
        'amount',
        'billing_period_start',
        'billing_period_end',
        'paid_at',
        'payment_reference',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'billing_period_start' => 'datetime',
        'billing_period_end' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
