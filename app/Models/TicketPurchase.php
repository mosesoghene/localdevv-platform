<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TicketPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_type_id',
        'order_id',
        'quantity',
        'unit_price',
        'total_amount',
        'ticket_code',
        'status',
        'attendee_name',
        'attendee_email',
        'attendee_phone',
        'checked_in_at',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate unique ticket code
     */
    public static function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(8));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }

    /**
     * Check if ticket is checked in
     */
    public function isCheckedIn(): bool
    {
        return !is_null($this->checked_in_at);
    }

    /**
     * Mark ticket as checked in
     */
    public function checkIn(): void
    {
        $this->update([
            'checked_in_at' => now(),
            'status' => 'used',
        ]);
    }
}
