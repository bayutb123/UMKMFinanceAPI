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
