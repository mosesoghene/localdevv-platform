<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_type',
        'is_published',
        'external_url',
        'thumbnail',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_published' => 'boolean',
    ];
}
