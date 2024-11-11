<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'show_on_display',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_display' => 'boolean',
    ];

    public function currentQueue()
    {
        return $this->belongsTo(Queue::class, 'current_queue_id');
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
