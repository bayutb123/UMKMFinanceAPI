<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\AddProductRequest;

class ProductController extends Controller
{
    public function addProduct(AddProductRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            // check if product with name and user already exists
            $checkProduct = Product::where('name', $validated['name'])
                ->where('owner_id', $validated['owner_id'])
                ->first();

            if ($checkProduct != null) {
                return response()->json(['error' => 'Product already exists'], 400);
            }

            $product = Product::create($validated);
            return response()->json(
                [
                    'product' => $product
                ], 200
            );
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }
}
