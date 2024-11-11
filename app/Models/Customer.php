<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'id_number',
        'id_type',
        'date_of_birth',
        'address',
        'kyc_verified',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'kyc_verified' => 'boolean',
    ];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
