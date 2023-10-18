<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BookInventory;

class FinancialController extends Controller
{
    public function getTodayTransactions($userId) {
        $todayTransactions = Transaction::where('user_id', $userId)
            ->where('transaction_date', date('Y-m-d'))
            ->get();

        $totalPurchase = 0;
        $totalSale = 0;
        foreach ($todayTransactions as $transaction) {
            if ($transaction->type == 1 || $transaction->type == 3 || $transaction->type == 5) {
                $totalPurchase += $transaction->amount;
            } else if ($transaction->type == 2 || $transaction->type == 4 || $transaction->type == 6) {
                $totalSale += $transaction->amount;
            }
        }
        $summary = $totalSale - $totalPurchase;
        return response()->json([
            'message' => 'Success',
            'date' => date('Y-m-d'), 
            'transactions' => $todayTransactions,
            'total_sale' => $totalSale,
            'total_purchase' => $totalPurchase,
            'today_summary' => $summary
        ], 200);
    }

    public function getCurrentMonthTransactions($userId) {
        $last_month_date = date('Y-m-d', strtotime('-1 month'));
        $transactions = Transaction::where('user_id', $userId)
            ->where('transaction_date', '>=', $last_month_date)
            ->get();

        $totalPurchase = 0;
        $totalSale = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->type == 1 || $transaction->type == 3 || $transaction->type == 5) {
                $totalPurchase += $transaction->amount;
            } else if ($transaction->type == 2 || $transaction->type == 4 || $transaction->type == 6) {
                $totalSale += $transaction->amount;
            }
        }

        $summary = $totalSale - $totalPurchase;
        return response()->json([
            'message' => 'Success',
            'transactions' => $transactions,
            'total_sale' => $totalSale,
            'total_purchase' => $totalPurchase,
            'summary' => $summary
        ], 200);
    }

    public function getProfitLoss($userId) {
        $last_month_date = date('Y-m-d', strtotime('-1 month'));
        $transactions = Transaction::where('user_id', $userId)
            ->where('transaction_date', '>=', $last_month_date)
            ->where('type', 2)
            ->orWhere('type', 4)
            ->get();
        $book_inventory = BookInventory::where('owner_id', $userId)
            ->where('date', '>=', $last_month_date)
            ->where('out', '>', 0)
            ->get();

    
        $totalSale = 0;
        foreach ($transactions as $transaction) {
            $totalSale += $transaction->amount;
        }

        $totalCostOfGoodSold = 0;
        foreach ($book_inventory as $item) {
            $totalCostOfGoodSold += $item->purchased_in_price * $item->out;
        }
        
        
        return response()->json([

            'message' => 'Success',
            'transactions' => $transactions,
            'book_inventory' => $book_inventory,
            'total_sale' => $totalSale,
            'total_cost_of_good_sold' => $totalCostOfGoodSold,
            'profit_loss' => $totalSale - $totalCostOfGoodSold


        ], 200);
    }
}
