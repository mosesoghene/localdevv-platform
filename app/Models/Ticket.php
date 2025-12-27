<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'subscription_id',
        'subject',
        'ticket_type',
        'status',
        'priority',
        'is_priority_support',
    ];

    protected $casts = [
        'is_priority_support' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
}
