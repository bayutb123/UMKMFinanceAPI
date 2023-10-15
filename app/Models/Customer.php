<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'owner',
        'contact'
    ];

    public function getCustomerById($id)
    {
        return $this->where('id', $id)->first();
    }

}
