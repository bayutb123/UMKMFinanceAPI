<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function checkCustomer($customer_id, $owner_id)
    {
        return $this->where('id', $customer_id)->where('owner_id', $owner_id)->first();
    }

    public function checkConflict($name, $owner_id)
    {
        return $this->where('name', $name)->where('owner_id', $owner_id)->first();
    }

}
