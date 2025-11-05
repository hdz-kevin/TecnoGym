<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'membership_id',
        'start_date',
        'end_date',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
    ];
}
