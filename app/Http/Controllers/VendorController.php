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
        $vendor = new Vendor();
        $checkConflict = $vendor->checkConflict($validated['name'], $validated['owner_id']);
        if ($checkConflict) {
            return response()->json(['error' => 'Vendor already exists'], 400);
        }
        $vendor = Vendor::create($validated);
        return response()->json([
            'message' => 'Vendor added successfully',
            'vendor' => $vendor
        ], 200);
    }
}
