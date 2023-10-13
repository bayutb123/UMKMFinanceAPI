<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;

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
}
