<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    protected $fillable = [
        'name',
        'address',
        'contact'
    ];

    public function getVendorById($id)
    {
        return $this->where('id', $id)->first();
    }
}
