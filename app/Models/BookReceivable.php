<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReceivable extends Model
{
    use HasFactory;

    protected $table = 'book_receivable';
    protected $fillable = [
        'transaction_date',
        'customer_id',
        'amount_d',
        'amount_k',
        'amount'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function getReceivableByCustomerId($customerId)
    {
        return $this->where('customer_id', $customerId)->get();
    }
}
