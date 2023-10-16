<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/user/edit', [UserController::class, 'edit']);

Route::post('/transaction/create', [TransactionController::class, 'create']);
Route::get('/transaction/incoming/{id}', [TransactionController::class, 'getIncoming']);
Route::get('/transaction/outcoming/{id}', [TransactionController::class, 'getOutcoming']);

Route::post('/transaction/purchase', [TransactionController::class, 'createPurchaseTransaction']);
Route::post('/transaction/sale', [TransactionController::class, 'createSaleTransaction']);
Route::post('/transaction/payment', [TransactionController::class, 'createPaymentTransaction']);
Route::post('/transaction/receipt', [TransactionController::class, 'createReceiptTransaction']);

Route::post('/product/add', [ProductController::class, 'addProduct']);

Route::post('/vendor/add', [VendorController::class, 'addVendor']);
Route::post('/customer/add', [CustomerController::class, 'addCustomer']);

Route::get('/product/get/{id}', [ProductController::class, 'getProducts']);