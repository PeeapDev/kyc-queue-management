<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'customer_id',
        'counter_id',
        'service_type',
        'status',
        'wait_time',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
