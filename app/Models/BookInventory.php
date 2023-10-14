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
        'product_id',
        'quantity',
        'price',
        'transaction_id'
    ];

    protected $cast = [
        'date' => 'date'
    ];
}
