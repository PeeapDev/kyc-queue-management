<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'use_google_regions',
        'google_api_key',
        'is_active'
    ];

    protected $casts = [
        'use_google_regions' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
