<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'client_name',
        'project_url',
        'project_type',
        'technologies_used',
        'completion_date',
        'featured_image',
        'is_featured',
        'thumbnail',
        'technologies',
        'completed_at',
    ];

    protected $casts = [
        'technologies' => 'array',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
    ];
}
