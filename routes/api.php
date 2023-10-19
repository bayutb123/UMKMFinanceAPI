<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\BookAccountController;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/user/{id}', [UserController::class, 'getUser']);
Route::post('/user/edit', [UserController::class, 'edit']);

Route::post('/transaction/add', [TransactionController::class, 'addTransaction']);
Route::post('/transaction/purchase', [TransactionController::class, 'createPurchaseTransaction']);
Route::post('/transaction/sale', [TransactionController::class, 'createSaleTransaction']);
Route::post('/transaction/payment', [TransactionController::class, 'createPaymentTransaction']);
Route::post('/transaction/receipt', [TransactionController::class, 'createReceiptTransaction']);
Route::get('/transaction/all/{id}', [TransactionController::class, 'getAllTransactions']);
Route::delete('/transaction/delete/{id}', [TransactionController::class, 'deleteTransaction']);
Route::post('/vendor/add', [VendorController::class, 'addVendor']);
Route::get('/vendor/get/{id}', [VendorController::class, 'getVendors']);
Route::delete('/vendor/delete/{id}', [VendorController::class, 'deleteVendor']);
Route::post('/customer/add', [CustomerController::class, 'addCustomer']);
Route::get('/customer/get/{id}', [CustomerController::class, 'getCustomers']);
Route::delete('/customer/delete/{id}', [CustomerController::class, 'deleteCustomer']);
Route::post('/product/add', [ProductController::class, 'addProduct']);
Route::get('/product/get/{id}', [ProductController::class, 'getProducts']);
Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct']);
Route::post('/account/add', [BookAccountController::class, 'addAccountRecord']);
Route::post('/account/balance/update', [BookAccountController::class, 'updateAccountBalance']);
Route::get('/accounts/{id}', [BookAccountController::class, 'getAccounts']);

Route::get('/finance/today/{id}', [FinancialController::class, 'getTodayTransactions']);
Route::get('/finance/current-month/{id}', [FinancialController::class, 'getCurrentMonthTransactions']);
Route::get('/finance/profit-loss/{id}', [FinancialController::class, 'getProfitLoss']);