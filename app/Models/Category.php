<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_other',
        'description',
        'acronym',
        'level',
        'display_on_transfer',
        'display_on_ticket',
        'display_on_backend',
        'priority',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'display_on_transfer' => 'boolean',
        'display_on_ticket' => 'boolean',
        'display_on_backend' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_categories');
    }
}
