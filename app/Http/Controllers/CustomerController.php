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

    public function getCustomers($userId)
    {
        $customers = Customer::where('owner_id', $userId)->get();
        return response()->json([
            'total' => count($customers),
            'customers' => $customers
        ], 200);
    }

    public function deleteCustomer($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        if ($customer != null) {
            $customer->delete();
            return response()->json([
                'message' => 'Customer deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Customer not found'
            ], 400);
        }
    }
}
