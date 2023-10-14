<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookInventory extends Model
{
    use HasFactory;

    protected $table = 'book_inventory';
    protected $fillable = [
        'date',
        'owner_id',
        'product_id',
        'quantity',
        'purchased_in_price',
        'sold_in_price',
        'transaction_id'
    ];

    protected $cast = [
        'date' => 'date'
    ];
}
