<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\BookInventory;
use App\Models\BookReceivable;
use App\Models\BookPayable;
use App\Models\Vendor;
use App\Models\Customer;


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

            $checkVendor = new Vendor;
            $checkVendorImpl = $checkVendor->checkVendor($validated['vendor_id'], $validated['user_id']);

            if ($checkProduct == null) {
                return response()->json(['error' => 'Product not found'], 400);
            } else if($checkVendorImpl == null) {
                return response()->json(['error' => 'Vendor not found'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => $validated['type']
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
            
            $invenBook = new BookInventory;
            $validate = $invenBook->checkIfHasQty($validated['user_id'], $validated['product_id']);
            
            if ($validate) {
                Product::where('id', $validated['product_id'])
                    ->where('owner_id', $validated['user_id'])
                    ->update(['has_qty' => 1]);
            }


            if ($validated['type'] == 3) {
                $book_payable = BookPayable::create(
                    [
                        'owner_id' => $validated['user_id'],
                        'transaction_id' => $transaction->id,
                        'transaction_date' => $validated['transaction_date'],
                        'vendor_id' => $validated['vendor_id'],
                        'amount' => $validated['amount'],
                        'paid' => 0
                    ]
                );
                return response()->json([
                    'type' => 'Pembelian Kredit',
                    'transaction' => $transaction,
                    'book_inventory' => $book_inventory,
                    'book_payable' => $book_payable,
                ]);
            }
            return response()->json(
                [
                    'type' => 'Pembelian Tunai',    
                    'transaction' => $transaction,
                    'book_inventory' => $book_inventory,
                ]
            );
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

            $checkCustomer = new Customer;
            $checkCustomerImpl = $checkCustomer->checkCustomer($validated['customer_id'], $validated['user_id']);

            if ($checkProduct < $validated['quantity']) {
                return response()->json(['error' => 'Not enough product'], 400);
            } else if($checkCustomerImpl == null) {
                return response()->json(['error' => 'Customer not found'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => $validated['type']
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
            if ($validated['type'] == 4) {
                $book_receivable = BookReceivable::create(
                    [
                        'owner_id' => $validated['user_id'],
                        'transaction_id' => $transaction->id,
                        'transaction_date' => $transaction->transaction_date,
                        'customer_id' => $validated['customer_id'],
                        'amount' => $validated['amount'],
                        'paid' => 0
                    ]
                );
                return response()->json([
                    'type' => 'Penjualan Kredit',
                    'transaction' => $transaction,
                    'book_inventory' => $book_inventory,
                    'book_receivable' => $book_receivable
                ]);
            }
            return response()->json([
                'type' => 'Penjualan Tunai',
                'transaction' => $transaction,
                'book_inventory' => $book_inventory
            ]);
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }
}
