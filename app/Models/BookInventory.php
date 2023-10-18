<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookInventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'book_inventory';
    protected $fillable = [
        'date',
        'owner_id',
        'product_id',
        'in',
        'purchased_in_price',
        'out',
        'sold_in_price',
        'transaction_id',
        'qty',
    ];

    protected $cast = [
        'date' => 'date'
    ];

    public function getItemByOwnerId($ownerId)
    {
        return $this->where('owner_id', $ownerId)->get();
    }

    public function getItemByProductId($ownerId, $productId)
    {
        return $this->where('product_id', $productId)
            ->where('owner_id', $ownerId)
            ->get();
    }

    public function checkIfHasQty($ownerId, $productId)
    {
        $item = $this->where('product_id', $productId)
            ->where('owner_id', $ownerId)
            ->first();
        if ($item) {
            return true;
        } else {
            return false;
        }
    }
}
