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
        $customer = Customer::create($validated);
        return response()->json([
            'message' => 'Customer added successfully',
            'customer' => $customer
        ], 201);
    }
}
