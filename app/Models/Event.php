<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
