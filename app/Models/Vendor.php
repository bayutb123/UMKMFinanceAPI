<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'owner',
        'contact'
    ];

    public function getVendorById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function checkConflict($name, $owner_id)
    {
        return $this->where('name', $name)->where('owner_id', $owner_id)->first();
    }
}
