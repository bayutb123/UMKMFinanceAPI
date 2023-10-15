<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Requests\AddVendorRequest;


class VendorController extends Controller
{
    public function addVendor(AddVendorRequest $request)
    {
        $validated = $request->validated();
        $vendor = Vendor::create($validated);
        return response()->json([
            'message' => 'Vendor added successfully',
            'vendor' => $vendor
        ], 201);
    }
}
