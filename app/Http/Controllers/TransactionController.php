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
use App\Http\Requests\ReciptRequest;
use App\Http\Requests\PaymentRequest;


class TransactionController extends Controller
{
    public function addTransaction(TransactionRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            $transaction = Transaction::create($validated);
            return response()->json($validated);
        }
        return response()->json(['error' => 'Validation failed'], 400);
    }

    public function deleteTransaction($transactionId) {
        $transaction = Transaction::where('id', $transactionId)->first();
        if ($transaction != null) {
            if ($transaction->type == 3) {
                $book_payable = BookPayable::where('transaction_id', $transactionId)->first();
                $book_inventory = BookInventory::where('transaction_id', $transactionId)->first();
                $book_inventory->delete();
                $book_payable->delete();
            } else if ($transaction->type == 4) {
                $book_receivable = BookReceivable::where('transaction_id', $transactionId)->first();
                $book_inventory = BookInventory::where('transaction_id', $transactionId)->first();
                $book_inventory->delete();
                $book_receivable->delete();
            }

            
            $transaction->delete();
            return response()->json([
                'message' => 'Transaction deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Transaction not found'
            ], 400);
        }
    }

    public function getAllTransactions($userId) {
        $transactions = Transaction::where('user_id', $userId)->get();
        return response()->json([
            'status' => '200',
            'total' => count($transactions),
            'transactions' => $transactions
        ]);
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
                    'in' => $validated['quantity'],
                    'purchased_in_price' => $validated['amount']/$validated['quantity'],
                    'transaction_id' => $transaction->id,
                    'qty' => $validated['quantity'],
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
                ->sum('in');
            
            $soldProduct = BookInventory::where('product_id', $validated['product_id'])
                ->where('owner_id', $validated['user_id'])
                ->where('deleted_at', null)
                ->sum('out');

            $checkProduct = $checkProduct - $soldProduct;

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
            for ($i = 0; $i < $validated['quantity']; $i++) {
                $book_inventory = BookInventory::where('product_id', $validated['product_id'])
                    ->where('owner_id', $validated['user_id'])
                    ->where('qty', '>', 0)
                    ->where('deleted_at', null)
                    ->first();
                $book_inventory->out += 1;
                $book_inventory->qty -= 1;
                $book_inventory->save();
            }
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

    public function createPaymentTransaction(PaymentRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            $book_payable = BookPayable::where('transaction_id', $validated['transaction_id'])
                ->where('owner_id', $validated['user_id'])
                ->first();

            if ($book_payable == null) {
                return response()->json(['error' => 'Book payable not found'], 400);
            }

            $book_payable->paid_amount += $validated['amount'];
            $book_payable->paid = $book_payable->paid_amount == $book_payable->amount ? 1 : 0;

            if ($book_payable->paid == 1) {
                $book_payable->paid_date = $validated['transaction_date'];
            }

            if ($book_payable->paid_amount > $book_payable->amount) {
                return response()->json(['error' => 'Paid amount is more than payable amount'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => $validated['type']
            ]);

            $book_payable->save();

            return response()->json([
                'type' => 'Pembayaran Hutang',
                'transaction' => $transaction,
                'book_payable' => $book_payable
            ]);
        }

        return response()->json(['error' => 'Validation failed'], 400);
    }

    public function createReceiptTransaction(ReciptRequest $request) {
        $validated = $request->validated();
        if ($validated) {
            $book_receivable = BookReceivable::where('transaction_id', $validated['transaction_id'])
                ->where('owner_id', $validated['user_id'])
                ->first();

            if ($book_receivable == null) {
                return response()->json(['error' => 'Book receivable not found'], 400);
            }

            $book_receivable->paid_amount += $validated['amount'];
            $book_receivable->paid = $book_receivable->paid_amount == $book_receivable->amount ? 1 : 0;

            if ($book_receivable->paid == 1) {
                $book_receivable->paid_date = $validated['transaction_date'];
            }

            if ($book_receivable->paid_amount > $book_receivable->amount) {
                return response()->json(['error' => 'Paid amount is more than receivable amount'], 400);
            }

            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'user_id' => $validated['user_id'],
                'description' => $validated['description'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'type' => $validated['type']
            ]);

            $book_receivable->save();

            return response()->json([
                'type' => 'Pembayaran Piutang',
                'transaction' => $transaction,
                'book_receivable' => $book_receivable
            ]);
        }

        return response()->json(['error' => 'Validation failed'], 400);
    }
}
