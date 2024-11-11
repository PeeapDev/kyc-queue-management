<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'username',
        'password',
        'address',
        'unique_id',
        'role',
        'counter_id',
        'show_next_button',
        'desktop_notifications',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'show_next_button' => 'boolean',
        'desktop_notifications' => 'boolean',
    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'staff_categories');
    }
}
