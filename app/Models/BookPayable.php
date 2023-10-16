<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookPayable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'book_payable';
    protected $fillable = [
        'owner_id',
        'transaction_id',
        'transaction_date',
        'vendor_id',
        'amount',
        'paid'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function getPayableByVendorId($vendorId)
    {
        return $this->where('vendor_id', $vendorId)->get();
    }
}
