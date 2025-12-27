<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_time',
        'location',
        'event_type',
        'max_attendees',
        'registration_url',
        'featured_image',
        'is_published',
        'external_url',
        'thumbnail',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /**
     * Get active ticket types
     */
    public function activeTicketTypes(): HasMany
    {
        return $this->ticketTypes()->where('is_active', true);
    }
}
