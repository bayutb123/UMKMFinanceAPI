<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\AddCustomerRequest;

class CustomerController extends Controller
{
    public function addCustomer(AddCustomerRequest $request)
    {
        $validated = $request->validated();
        $customer = new Customer();
        $checkConflict = $customer->checkConflict($validated['name'], $validated['owner_id']);
        if ($checkConflict) {
            return response()->json(['error' => 'Customer already exists'], 400);
        }
        $customer = Customer::create($validated);
        return response()->json([
            'message' => 'Customer added successfully',
            'customer' => $customer
        ], 200);
    }
}
