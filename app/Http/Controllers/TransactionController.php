<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\BookInventory;

class TransactionController extends Controller
{
    public function create(TransactionRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            $transaction = Transaction::create($validated);
            return response()->json($validated);
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }

    public function getIncoming($userId) {
        $transactions = Transaction::where('user_id', $userId)
            ->where('type', 1)
            ->get();
        return response()->json(
            [
                'transactions' => $transactions,
                'total' => $transactions->count()
            ], 200
        );
    }

    public function getOutcoming($userId) {
        $transactions = Transaction::where('user_id', $userId)
            ->where('type', 2)
            ->get();
        return response()->json(
            [
                'transactions' => $transactions,
                'total' => $transactions->count()
            ], 200
        );
    }

    public function createPurchaseTransaction(PurchaseRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            // check if product id is valid
            $checkProduct = Product::where('id', $validated['product_id'])
                ->where('owner_id', $validated['user_id'])
                ->first();

            if ($checkProduct == null) {
                return response()->json(['error' => 'Product not found'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => 1
            ]);
            $book_inventory = BookInventory::create(
                [
                    'date' => $validated['transaction_date'],
                    'owner_id' => $validated['user_id'],
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'purchased_in_price' => $validated['amount']/$validated['quantity'],
                    'transaction_id' => $transaction->id
                ]
                );
            return response()->json([
                'transaction' => $transaction,
                'book_inventory' => $book_inventory
            ]);
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }

    public function createSaleTransaction(SaleRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            // check if total of current quantity of product id is enough
            $checkProduct = BookInventory::where('product_id', $validated['product_id'])
                ->where('owner_id', $validated['user_id'])
                ->where('deleted_at', null)
                ->sum('quantity');

            if ($checkProduct < $validated['quantity']) {
                return response()->json(['error' => 'Not enough product'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => 2
            ]);
            $book_inventory = BookInventory::create(
                [
                    'date' => $validated['transaction_date'],
                    'owner_id' => $validated['user_id'],
                    'product_id' => $validated['product_id'],
                    'quantity' => -$validated['quantity'],
                    'sold_in_price' => $validated['amount']/$validated['quantity'],
                    'transaction_id' => $transaction->id
                ]
                );
            return response()->json([
                'transaction' => $transaction,
                'book_inventory' => $book_inventory
            ]);
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }
}
