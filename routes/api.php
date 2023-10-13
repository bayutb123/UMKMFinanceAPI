<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/user/edit', [UserController::class, 'edit']);

Route::post('/transaction/create', [TransactionController::class, 'create']);
Route::get('/transaction/incoming/{id}', [TransactionController::class, 'getIncoming']);
Route::get('/transaction/outcoming/{id}', [TransactionController::class, 'getOutcoming']);