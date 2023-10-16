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

    public function getVendors($userId)
    {
        $vendors = Vendor::where('owner_id', $userId)->get();
        return response()->json([
            'total' => count($vendors),
            'vendors' => $vendors
        ], 200);
    }

    public function deleteVendor($vendorId)
    {
        $vendor = Vendor::where('id', $vendorId)->first();
        if ($vendor != null) {
            $vendor->delete();
            return response()->json([
                'message' => 'Vendor deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Vendor not found'
            ], 400);
        }
    }
}
