<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'queue_number',
        'name',
        'phone_number',
        'status',
        'counter_id'
    ];

    public static function generateQueueNumber()
    {
        $lastQueue = self::whereDate('created_at', today())->latest()->first();
        $lastNumber = $lastQueue ? intval(substr($lastQueue->queue_number, 1)) : 0;
        return 'Q' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
