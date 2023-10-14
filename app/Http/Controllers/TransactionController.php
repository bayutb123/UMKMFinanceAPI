<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\PurchaseRequest;
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
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $validated['amount'],
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
