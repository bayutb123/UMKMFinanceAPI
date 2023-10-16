<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;   

class BookReceivable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'book_receivable';
    protected $fillable = [
        'owner_id',
        'transaction_id',
        'transaction_date',
        'customer_id',
        'amount',
        'paid'
    ];

    
    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function getReceivableByCustomerId($customerId)
    {
        return $this->where('customer_id', $customerId)->get();
    }
}
