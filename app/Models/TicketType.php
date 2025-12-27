<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'total_quantity',
        'sold_quantity',
        'max_per_order',
        'is_active',
        'sale_starts_at',
        'sale_ends_at',
        'additional_info',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'additional_info' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /**
     * Get available tickets
     */
    public function getAvailableQuantityAttribute(): int
    {
        return $this->total_quantity - $this->sold_quantity;
    }

    /**
     * Check if ticket is available for sale
     */
    public function isAvailableForSale(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->available_quantity <= 0) {
            return false;
        }

        $now = now();

        if ($this->sale_starts_at && $now->lt($this->sale_starts_at)) {
            return false;
        }

        if ($this->sale_ends_at && $now->gt($this->sale_ends_at)) {
            return false;
        }

        return true;
    }

    /**
     * Check if quantity is available
     */
    public function hasAvailableQuantity(int $quantity): bool
    {
        return $this->available_quantity >= $quantity;
    }
}
