<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::resource('account', AccountController::class)->except([ 'create', 'edit' ]);
Route::resource('category', CategoryController::class)->except([ 'create', 'edit' ]);
Route::resource('transaction', TransactionController::class)->except([ 'create', 'edit' ]);
