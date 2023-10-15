<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";
    protected $fillable = [
        'user_id',
        'transaction_date',
        'transaction_type',
        'account_id',
        'type',
        'transaction_number',
        'description',
        'amount',
        'paid'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
