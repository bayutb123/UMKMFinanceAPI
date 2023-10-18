<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionInventory extends Model
{
    use HasFactory;

    protected $table = 'transaction_inventory';
    protected $fillable = [
        'transaction_id',
        'inventory_id',
        'quantity',
    ];

}
