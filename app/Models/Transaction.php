<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'user_id',
        'description',
        'account_id',
        'type',
        'amount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
