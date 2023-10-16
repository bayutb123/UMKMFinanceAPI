<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

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
