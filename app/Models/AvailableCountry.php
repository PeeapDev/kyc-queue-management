<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableCountry extends Model
{
    protected $fillable = [
        'name',
        'code',
        'regions',
        'is_active'
    ];

    protected $casts = [
        'regions' => 'array',
        'is_active' => 'boolean'
    ];
}
