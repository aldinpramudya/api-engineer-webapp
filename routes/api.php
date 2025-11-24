<?php

use App\Http\Controllers\CategoryCoaController;
use App\Http\Controllers\MasterCoaController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories-coa', CategoryCoaController::class);
Route::apiResource('masters-coa', MasterCoaController::class);
Route::apiResource("transactions", TransactionController::class);

// Get Monthly Data
Route::get('get-laporan-profit-loss', [TransactionController::class, 'getMonthlyTotal']);


