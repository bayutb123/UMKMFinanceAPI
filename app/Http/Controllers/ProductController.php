<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\AddProductRequest;
use App\Models\BookInventory;

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
                return response()->json([
                    'error' => 'Product already exists'
                ], 400);
            }

            $product = Product::create($validated);
            return response()->json(
                [
                    'product' => $product
                ], 200
            );
        }
        return response()->json([
            'error' => 'Validation failed'
        ], 400);
    }

    public function getProducts($userId) {
        $products = Product::where('owner_id', $userId)
        ->where('has_qty', 1)
        ->get();
        $res = [];

        foreach ($products as $product) {
            $item = BookInventory::where('product_id', $product->id)
            ->where('owner_id', $userId)
            ->first();
            // sum item qty 
            $item->quantity = BookInventory::where('product_id', $product->id)
                ->where('owner_id', $userId)
                ->sum('quantity');
            $item->purchased_in_price = BookInventory::where('product_id', $product->id)
                ->where('owner_id', $userId)
                ->average('purchased_in_price');
            if ($item->quantity > 0) {
                array_push($res, $item);
            }
        }

        return response()->json(
            [
                'total' => count($res),
                'products' => $res,
            ], 200
        );
    }

    
}
