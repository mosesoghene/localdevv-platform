<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'project_url',
        'technologies',
        'completed_at',
        'is_featured',
    ];

    protected $casts = [
        'technologies' => 'array',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
    ];
}
